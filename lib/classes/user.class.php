<?php
/**
 *  Defines the User class
 *
 *  This file contains the definition of the User class, which not only tracks
 *  common user data such as person ID and name, but also handles the permission
 *  checking, i.e. verifies that the user has permissions to view or edit.
 *  Run-time only.
 *
 *  PHP version 4
 *
 *
 *  LICENSE NOTE:
 *
 *  Copyright  2003-2007 Active Agenda Inc., All Rights Reserved.
 *
 *  Unless explicitly acquired and licensed from Licensor under a "commercial license",
 *  the contents of this file are subject to the Reciprocal Public License ("RPL")
 *  Version 1.4, or subsequent versions as allowed by the RPL,and You may not copy
 *  or use this file in either source code or executable form, except in compliance
 *  with the terms and conditions of the RPL. You may obtain a copy of the RPL from
 *  Active Agenda Inc. at http://www.activeagenda.net/license.
 *
 *  All software distributed under the Licenses is provided strictly on an "AS IS"
 *  basis, WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESS OR IMPLIED, AND ACTIVE AGENDA
 *  INC. HEREBY DISCLAIMS ALL SUCH WARRANTIES, INCLUDING WITHOUT LIMITATION, ANY 
 *  WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, QUIET ENJOYMENT,
 *  OR NON-INFRINGEMENT. See the Licenses for specific language governing rights and
 *  limitations under the Licenses.
 *
 *
 * @author         Mattias Thorslund <mthorslund@activeagenda.net>
 * @copyright      2003-2007 Active Agenda Inc.
 * @license        http://www.activeagenda.net/license  RPL 1.4
 * @version        SVN: $Revision: 500 $
 * @last-modified  SVN: $Date: 2007-02-16 20:08:34 -0800 (Fri, 16 Feb 2007) $
 */

include_once INCLUDE_PATH . '/general_util.php';


class User
{

var $PersonID;
var $UserName;
var $DisplayName;
var $Lang; //preferred user language
var $IsAdmin = false; //SiteAdmin
var $isAuthenticated = false;
var $Permissions = array(); //array of arrays indexed by ModuleIDs. sub-array lists allowed organizationIDs and Edit (E) ot View (V)
var $DefaultAllowedOrgs = array();
var $previousVisit; //last previous login, excluding same day
var $defaultOrgID; //a user-selected organization ID to pre-populate selected OrgCombo fields
var $browserInfo = array();


//constructor
function User()
{
    require PEAR_PATH . '/Net/UserAgent/Detect.php';
    $this->browserInfo['is_IE'] = Net_UserAgent_Detect::isIE();
}


function LoadPermissions()
{
    global $dbh;

    $SQL = "SELECT ModuleID, EditPermission, ViewPermission FROM usrp WHERE PersonID = '{$this->PersonID}' ORDER BY ModuleID";
    $modPermissions = $dbh->getAll($SQL, DB_FETCHMODE_ASSOC);
    dbErrorCheck($modPermissions);


    $SQL = "SELECT OrganizationID FROM usrpo WHERE PersonID = '{$this->PersonID}'";
    $orgPermissions = $dbh->getAll($SQL, DB_FETCHMODE_ASSOC);
    dbErrorCheck($orgPermissions);

    foreach($modPermissions as $row){
        switch ($row['EditPermission'] . $row['ViewPermission']) {
        case '22':
            $this->Permissions[$row['ModuleID']]['e'] = 2;
            $this->Permissions[$row['ModuleID']]['v'] = 2;
            break;
        case '12':
            $this->Permissions[$row['ModuleID']]['e'] = 1;
            $this->Permissions[$row['ModuleID']]['v'] = 2;
            $this->Permissions[$row['ModuleID']]['o'] = 'default';
            break;
        case '11':
            $this->Permissions[$row['ModuleID']]['e'] = 1;
            $this->Permissions[$row['ModuleID']]['v'] = 1;
            $this->Permissions[$row['ModuleID']]['o'] = 'default';
        break;
        case '02':
            $this->Permissions[$row['ModuleID']]['e'] = 0;
            $this->Permissions[$row['ModuleID']]['v'] = 2;
            break;
        case '01':
            $this->Permissions[$row['ModuleID']]['e'] = 0;
            $this->Permissions[$row['ModuleID']]['v'] = 1;
            $this->Permissions[$row['ModuleID']]['o'] = 'default';
            break;
        default:
        //  don't even do this (we want to use the keys for a list of modules with any permssion but "none"):
        //    $this->Permissions[$row['ModuleID']]['e'] = 0;
        //    $this->Permissions[$row['ModuleID']]['v'] = 0;
            break;
        }
    }

    foreach($orgPermissions as $row){
        $this->DefaultAllowedOrgs[] = $row['OrganizationID'];
    }

} //end LoadPermissions



/**
 *  Determines the type of permission a user has to a record or module
 *
 *   return values:
 *       0 = no access to view, 
 *       1 = access to view records belonging to specified organizations,
 *       2 = access to view all records in module
 *   if $OrgID was not passed, the possible results are 0 and 1 only
 **/
function checkPermission($moduleID, $permissionType, $orgID = 0, $handleAccessDenied = true)
{
    if ($this->IsAdmin) {
        return 2;
    } else {
        $checkModuleID = $this->getPermissionModuleID($moduleID);

        if(0 == $orgID){
            if(array_key_exists($checkModuleID, $this->Permissions)){
                return $this->Permissions[$checkModuleID][$permissionType];
            } else {
                return 0;
            }
        } else {
            if(array_key_exists($checkModuleID, $this->Permissions)){  
                $permission = $this->Permissions[$checkModuleID];
                switch($permission[$permissionType]){
                case 2:
                    return 2;
                case 1:
                    $orgs = $this->getPermittedOrgs($moduleID);
                    return in_array($orgID, $orgs);
                default:
                    return 0;
                }
            } else {
                return 0;
            }
        }
    }
} //end checkPermission


/*  function PermissionToView
    return values: 
        0 = no access to view,
        1 = access to view records belonging to specified organizations,
        2 = access to view all records in module
    if $OrgID was not passed, the possible results are 0 and 1 only
*/
function PermissionToView($moduleID, $orgID = 0)
{
    return $this->checkPermission($moduleID, 'v', $orgID);
}


function PermissionToViewRecord($moduleID, $recordID)
{
    if($this->IsAdmin){
        return 2;
    }

    $moduleInfo = GetModuleInfo($moduleID);
    $ownerField = $moduleInfo->getProperty('ownerField');
    $recordIDField = $moduleInfo->getProperty('recordIDField');
    if(!empty($ownerField)){
        global $dbh;
        $SQL = "SELECT `$ownerField` FROM `$moduleID` WHERE `$recordIDField` = '$recordID'";
        $orgID = $dbh->getOne($SQL);
        dbErrorCheck($orgID);
    } else {
        $orgID = null;
    }
    return $this->checkPermission($moduleID, 'v', $orgID);
}


/*  function PermissionToEdit
    to be called by pages to see if the user has permission to edit a
    particular module...
    return values: 
        0 = no access to edit, 
        1 = access to edit records belonging to specified organizations,
        2 = access to edit all records in module
    if $OrgID was passed, $PermittedOrgs is set to return an array of 
    permitted OrganizationIDs.
*/
function PermissionToEdit($moduleID, $orgID = 0)
{
    return $this->checkPermission($moduleID, 'e', $orgID);
}


function getPermissionModuleID($moduleID)
{

    if(defined('LOADING_NAVIGATION') && LOADING_NAVIGATION){
        static $permissionModuleIDs = array();

        if(count($permissionModuleIDs) == 0){
            //load parentModuleIDs file only once
            if(file_exists(GENERATED_PATH . '/ParentModuleIDs.gen')){
                include(GENERATED_PATH . '/ParentModuleIDs.gen');
            }
        }

        if(!isset($permissionModuleIDs[$moduleID])){
            return $moduleID;
        } else {
            return $permissionModuleIDs[$moduleID];
        }
    }

    $moduleInfo = GetModuleInfo($moduleID);
    return $moduleInfo->getPermissionModuleID();
}


function getPermittedOrgs($moduleID)
{
    $checkModuleID = $this->getPermissionModuleID($moduleID);
    $permission = $this->Permissions[$checkModuleID];

    if(array_key_exists('o', $permission)){
        if('default' == $permission['o']){
            return $this->DefaultAllowedOrgs;
        } else {
            return $permission['o']; //specific orgs for this module
        }
    } else {
        return array();
    }
}


function getListFilterSQL($moduleID, $die = false)
{
    $moduleInfo = GetModuleInfo($moduleID);

    $ownerField = $moduleInfo->getProperty('ownerField');

    if(!empty($ownerField)){
        //see what type of view permission the user has
        $permissionLevel = $this->checkPermission($moduleID, 'v');
        switch(intval($permissionLevel)){
        case 2:
            return '';
            break;
        case 1:
            $ownerFieldFilter = $moduleInfo->getProperty('ownerFieldFilter');

            $orgArray = $this->getPermittedOrgs($moduleID);
            $orgString = join(',', $orgArray);
            return sprintf(' AND ' . $ownerFieldFilter, $orgString);
            break;
        default:
            return ' AND 0 = 1 ';
        }
    } else {
        //we don't check access permission to the module here - do this at the top of the List screen
        return '';
    }
} //end getListFilterSQL


//checks view permission; redirects if no permission
//returns EDIT permission (!)
function CheckViewScreenPermission()
{
    global $ModuleID;
    global $recordID;
    global $data;

    $moduleInfo = GetModuleInfo($ModuleID);
    $ownerField = $moduleInfo->getProperty('ownerField');

    if(!empty($ownerField)){
        $permission = $this->PermissionToView($ModuleID, $data[$ownerField]);
    } else {
        $permission = $this->PermissionToView($ModuleID);
    }

    switch($permission){
    case 2:
    case 1:
        if(!empty($ownerField)){
            return $this->PermissionToEdit($ModuleID, $data[$ownerField]);
        } else {
            return $this->PermissionToEdit($ModuleID);
        }
        break;
    default:
        $this->_handleAccessDenied($ModuleID, $recordID);
    }
}


//checks view permission; redirects if no permission
//returns EDIT permission (!)
function CheckListScreenPermission()
{
    global $ModuleID;
    $permission = $this->PermissionToView($ModuleID);

    switch($permission){
    case 2:
    case 1:
            return $this->PermissionToEdit($ModuleID);
        break;
    default:
        $this->_handleAccessDenied($ModuleID, null);
        global $qs;
        header('Location:nopermission.php?'.$qs);
        exit();
        //die("You don't have permission to view this record");
    }
}


//checks edit permission; redirects if no permission
function CheckEditScreenPermission()
{
    global $ModuleID;
    global $data;
    global $recordID;

    $moduleInfo = GetModuleInfo($ModuleID);
    $ownerField = $moduleInfo->getProperty('ownerField');

    if(!empty($ownerField)){
        $permission = $this->PermissionToEdit($ModuleID, $data[$ownerField]);
    } else {
        $permission = $this->PermissionToEdit($ModuleID);
    }

    switch($permission){
    case 2:
    case 1:
        return true;
        break;
    default:
        $this->_handleAccessDenied($ModuleID, $recordID, 'edit');
    }
}


function _handleAccessDenied($moduleID, $recordID, $accessType = 'view', $die = false)
{

    global $dbh;
    $escaped_moduleID = $dbh->escapeSimple($moduleID);
    $escaped_recordID = $dbh->quoteSmart($recordID);

    //handle logging
    $recordID = intval($_GET['rid']);

    $this->saveLogEntry(10, "Access Denied to $accessType $escaped_moduleID, record $escaped_recordID.", true);

    //die or redirect
    if($die){
        die("Access denied");
    } else {
        global $qs;
        header('Location:nopermission.php?'.$qs);
        exit();
    }
}


//login function
function Login($pUserName, $pPassword)
{
    //returns true or false

    //connect to DB
    require_once PEAR_PATH . '/DB.php' ;  //PEAR DB class
    $dbh = DB::connect(DB_DSN);
    dbErrorCheck($dbh);

    $username = $dbh->quote($pUserName);
    $password = $pPassword;

    $SQL = "SELECT 
        P.PersonID,
        P.DisplayName,
        U.IsAdmin,
        C.Value AS Lang,
        U.DefaultOrganizationID,
        P.OrganizationID
    FROM
        usr AS U
        INNER JOIN ppl AS P
            ON P.PersonID = U.PersonID
        LEFT OUTER JOIN cod as C
            ON U.LangID = C.CodeID
            AND C.CodeTypeID = 138
    WHERE
        U._Deleted = 0
        AND U.UserName = $username
        AND U.Password = '".crypt($password, CRYPT_SEED)."'";

    //execute query - get just one row
    $Row = $dbh->getRow($SQL, null, DB_FETCHMODE_ASSOC);
    if (isset($Row['PersonID'])) {
        $this->isAuthenticated = true;
        $this->UserName = $username;
        $this->DisplayName = $Row['DisplayName'];
        $this->PersonID = $Row['PersonID'];
        $this->IsAdmin = $Row['IsAdmin'];
        $this->Lang = $Row['Lang'];
        if(empty($Row['DefaultOrganizationID'])){
            $this->defaultOrgID = $Row['OrganizationID'];
        } else {
            $this->defaultOrgID = $Row['DefaultOrganizationID'];
        }

        if (! $this->IsAdmin) {
            $this->LoadPermissions();
        }


        $SQL = "SELECT 
            MAX(_ModDate) AS LastLogin
        FROM usrl 
        WHERE 
            PersonID = '{$this->PersonID}' 
            AND _ModDate < CURDATE()
            AND EventTypeID = 1";
        $this->previousVisit = $dbh->getOne($SQL);

        $this->saveLogEntry(1, 'login', true);

        //prevents session fixation
        session_regenerate_id();

        if(defined('NOTIFY_LOGINS_EMAIL') && NOTIFY_LOGINS_EMAIL){
            $IP = $_SERVER['REMOTE_ADDR'];
            $addr = gethostbyaddr($IP);
            $email_msg = "User {$this->DisplayName} (ID {$this->PersonID}) logged in from IP $IP  ($addr).";
/*
//gather info in order to determine cause of failed "successful" logins:
$email_msg .="\n";
$email_msg .="Debug Info:\n";
$email_msg .="===========\n";
$email_msg .="\n";
$email_msg .="_POST:\n";
$email_msg .=str_replace(array('&nbsp;', '<br />'), array(' ', "\n"), debug_r($_POST));
$email_msg .="\n";
$email_msg .="_GET:\n";
$email_msg .=str_replace(array('&nbsp;', '<br />'), array(' ', "\n"), debug_r($_GET));
$email_msg .="\n";
$email_msg .="_COOKIE:\n";
$email_msg .=str_replace(array('&nbsp;', '<br />'), array(' ', "\n"), debug_r($_COOKIE));
$email_msg .="\n";
$email_msg .="_SERVER:\n";
$email_msg .=str_replace(array('&nbsp;', '<br />'), array(' ', "\n"), debug_r($_SERVER));
$email_msg .="\n";
$email_msg .="_ENV:\n";
$email_msg .=str_replace(array('&nbsp;', '<br />'), array(' ', "\n"), debug_r($_ENV));
//die($email_msg);
*/
            //send login notice to administrator
            sendEmail(EMAIL_LOGIN_NOTIFICATION_ADDRESS, EMAIL_LOGIN_NOTIFICATION_ADDRESS, SITE_SHORTNAME . ' (successful login)', $email_msg);
        }

        return true;
    } else {
        if(defined('NOTIFY_LOGINS_EMAIL') && NOTIFY_LOGINS_EMAIL){
            $IP = $_SERVER['REMOTE_ADDR'];
            $addr = gethostbyaddr($IP);
            $email_msg = "Failed login from IP $IP ($addr). Username $username.";
            //send login notice to administrator
            $from = 'no-reply@'.$_SERVER["HOSTNAME"];
            sendEmail(EMAIL_LOGIN_NOTIFICATION_ADDRESS, EMAIL_LOGIN_NOTIFICATION_ADDRESS, SITE_SHORTNAME . ' (failed login)', $email_msg);
        }

        $this->saveLogEntry(9, 'failed login', true);
        return false;
    }

}


function Logout()
{

    $this->saveLogEntry(2, 'logout', false);

}


function Block()
{
    //TODO: lock the user out from the application (prevents from logging in)

}


function getDateFormat()
{
    switch($this->Lang){
    case "sv_SE":
        return 'YYYY-MM-DD';
    default:
        return 'MM/DD/YYYY';
    }
}


function getDateFormatPHP()
{
    switch($this->Lang){
    case "sv_SE":
        return '%Y-%m-%d';
    default:
        return '%m/%d/%Y';
    }
}


function getDateTimeFormatPHP()
{
    switch($this->Lang){
    case "sv_SE":
        return '%Y-%m-%d %H:%M';
    default:
        return '%m/%d/%Y %I:%M %p';
    }
}


//same as getDateFormat but returns a string usable by the calendar control
function getDateFormatCal()
{
    switch($this->Lang){
    case "sv_SE":
        return '%Y-%m-%d';
    default:
        return '%m/%d/%Y';
    }
}


//returns calendar init settings based on user's language/locale
function getCalFormat($hasTime = false)
{
    switch($this->Lang){
    case "sv_SE":
        if($hasTime){
            $settings  = "\tifFormat    : \"%Y-%m-%d %H:%M\",\n";
            $settings .= "\ttimeFormat  : 24,";
        } else {
            $settings = "\tifFormat    : \"%Y-%m-%d\",\n";
        }
        $settings .= "\tmondayFirst : true,\n";
        $settings .= "\tweekNumbers : true,";
        break;
    default:
        if($hasTime){
            $settings = "\tifFormat    : \"%m/%d/%Y %I:%M %p\",\n";
            $settings .= "\ttimeFormat  : 12,";
        } else {
            $settings = "\tifFormat    : \"%m/%d/%Y\",\n";
        }
        $settings .= "\tmondayFirst : false,\n";
        $settings .= "\tweekNumbers : false,";
        break;
    }
    return $settings;
}


function saveLogEntry($pEventTypeID, $pEventDescription, $saveURL = true)
{
    global $dbh;

    if(empty($dbh)){
        //connect to DB
        require_once PEAR_PATH . '/DB.php' ;  //PEAR DB class
        $dbh = DB::connect(DB_DSN);
        dbErrorCheck($dbh);
    }

    if($saveURL){
        if(get_magic_quotes_gpc()){
            $escaped_requestURL = $_SERVER['REQUEST_URI']; //addslashes($_SERVER['REQUEST_URI']);
        } else {
            $escaped_requestURL = $dbh->escapeSimple($_SERVER['REQUEST_URI']);
        }
    }

    $personID = intval($this->PersonID); //makes it 0 if empty

    $IP = $_SERVER['REMOTE_ADDR'];

    //log user event
    $SQL = "INSERT INTO usrl (
        PersonID,
        EventTypeID,
        EventDescription,
        EventURL,
        RemoteIP,
        _ModDate
    ) VALUES (
        '{$personID}',
        $pEventTypeID,
        '$pEventDescription',
        '$escaped_requestURL',
        '$IP',
        NOW())";

    $r = $dbh->query($SQL);

    dbErrorCheck($r);
}

} //end class User

?>
