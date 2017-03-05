<?php
/**
 *  Utility functions to be used at run-time only.
 *
 *  This file contains functions that are mostly web disply/formatting-centric
 *  in nature. It is included by most files that are executed at run-time.
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
 * @version        SVN: $Revision: 528 $
 * @last-modified  SVN: $Date: 2007-02-23 19:34:49 -0800 (Fri, 23 Feb 2007) $
 */


/**
 * handle 'double PHPSESSID cookie problem'
 */
function CleanSessionCookie()
{
    if(strpos($_COOKIE['PHPSESSID'], ', PHPSESSID=') > 0){
        $cookieparts = explode(', PHPSESSID=', $_COOKIE['PHPSESSID']);
        $_COOKIE['PHPSESSID'] = $cookieparts[0];
    }
}


/**
 *  prepares query strings from a QS array, to be used in links
 */
function MakeQS($items)
{

    if(count($items)){
        foreach($items as $key => $value){
            $valuePairs[] = "$key=$value";
        }
        $qString = implode('&', $valuePairs);
    } else {
        $qString = '';
    }
    return $qString;
}


/**
 *  formats a value according to the supplied data type
 */
function fldFormat($dt, $val)
{
    global $User;

    switch($dt){
    case 'bool':
        if(1 == $val){
            $content = gettext("Yes");
        } elseif (NULL == $val) {
            $content = '-';
        } else {
            $content = gettext("No");
        }
        break;
    case 'date':
        if(empty($val)){
            $content = gettext("(no date set)");
        } else {
                //if the date is < year 2038, format it
            if (intval(substr($val, 0,4)) < 2038){
                $content = " ".strftime($User->getDateFormatPHP(), strtotime($val))." ";
            } else {
                //formatting function can't format it, just print it
                $content = " ".$val." ";
            }
        }
        break;
    case 'datetime':
        if(empty($val)){
            $content = gettext("(no date/time set)");
        } else {
            //if the date is < year 2038, format it
            if (intval(substr($val, 0,4)) < 2038){
                $content = " ".strftime($User->getDateTimeFormatPHP(), strtotime($val))." ";
            } else {
                //formatting function can't format it, just print it
                $content = " ".$val." ";
            }
        }
        break;
    case 'money':
        //we want to display only two decimals unless last two aren't zero.
        $nVal = 100 * $val;

        if(floor($nVal) == $nVal){
            $content = MASTER_CURRENCY.' '.number_format($val, 2).' ';
        } else { //there are more than 2 decimals so display the whole thing
            $content = MASTER_CURRENCY.' '.number_format($val, 4).' ';
        }
        break;
    default:
        $content = $val;
    }
    return $content;
}


/**
 * formats a link value into an array with proper URL or mailto address, and whether or not the link is internal
 */
function linkFormat($link)
{
    $internal = false;
    $newWin = false;
    if(false !== strpos($link, '@')){
        $link = 'mailto:'.$link;    //email address
    } elseif(false === strpos(strtolower($link), 'http://')) {
        if(false === strpos($link, 'internal:')){
            if(false !== strpos($link, 'special:')){
                $link = str_replace('special:', '', $link);
            } else {
                $link = 'http://'.$link;    //external address
                $newWin = true;
            }
        } else {
            $link = str_replace('internal:', '', $link);
            $internal = true;
        }
    }
    return array($link, $internal, $newWin);
}



/**
 *  Makes a print_r debug of an object (or whatever) and formats it to be legible in a browser.
 */
function debug_r($object, $title = '')
{
    ob_start();
        $content = '';
        if(!empty($title)){
            $content .= "<b>$title</b><br />\n";
        }
        print_r($object);
        $content .= strtr(ob_get_contents(), array("\n"=>"<br />\n", ' '=>'&nbsp;'));
        $content .= "<br />\n";
    ob_end_clean();
    return $content;
}



/**
 *  Quotes and escapes posted values, to make them safe for inclusion in SQL statements
 */
function dbQuote($value, $type = '')
{
    if (!empty($type)) {
        switch($type) {
        case 'date':
            return DateToISO($value);
            break;
        case 'datetime':
            return DateToISO($value, TRUE);
            break;
        case 'bool':
            return ChkFormat($value);
            break;
        case 'int':
            //preserve blank values
            if('' == trim($value)) {
                return 'NULL';
            } else {
                return intval($value);
            }
            break;
        case 'money':
            //preserve blank values
            if('' == trim($value)) {
                return 'NULL';
            } else {
                //remove thousands-separating commas (some locale-specificity here wouldn't hurt at all :-)
                $value = str_replace(',', '', $value);

                return floatval($value);
            }
            break;
        case 'text':
            //allows some HTML but filters out (hopefully all) XSS possibilities
            require_once PEAR_PATH . '/HTML/Safe.php';
            $safe_html_parser =& new HTML_Safe();
            $value = $safe_html_parser->parse($value);
            $value = '\''.$value.'\'';
            trace($value, "HTML_Safe parsed");
            return $value;
            break;
        default:
            //trap XSS problems
            $value = htmlspecialchars($value);
            return dbQuote($value);
        }

    } else {
        if('' === trim($value)){
            return 'NULL';
        }

        //escape quotes and special chars only if magic_quotes_gpc is off
        if (1 == get_magic_quotes_gpc()) {
            return "\"".trim($value)."\"";
        } else {
            return "\"".mysql_escape_string(trim($value))."\"";
        }
    }
}


/**
 *  Inserts or updates a desktop shortcut
 */
function SaveDesktopShortCut($PersonID, $Title, $Link, $Type, $ModuleID = null)
{
    global $dbh;
    $qTitle = dbQuote($Title);
    $qLink = dbQuote($Link);

    //check whether link exists
    $SQL = "SELECT COUNT(*) FROM usrds WHERE PersonID = $PersonID AND Link = $qLink";
    $numExisting = $dbh->getOne($SQL);
    dbErrorCheck($numExisting);
    if(0 < $numExisting){
        //update
        $SQL = "UPDATE usrds
        SET
            Title = $qTitle,
            Type = '$Type',
            ModuleID = '$ModuleID',
            _ModDate = NOW(),
            _Deleted = 0
        WHERE
            PersonID = $PersonID AND Link = $qLink";
    } else {
        //insert
        $SQL = "INSERT INTO usrds (PersonID, Title, Link, Type, ModuleID, _ModBy, _ModDate) VALUES ($PersonID, $qTitle, $qLink, '$Type', '$ModuleID', $PersonID, NOW())";

    }
    $r = $dbh->query($SQL);
    dbErrorCheck($r);

    $_SESSION['desktopShortcuts'][$Link] = $Title;

}


/**
 *  Removes a desktop shortcut
 */
function RemoveDesktopShortcut($PersonID, $Link)
{
    global $dbh;
    $qLink = dbQuote($Link);

    $SQL = "UPDATE usrds 
    SET 
        _ModDate = NOW(),
        _Deleted = 1
    WHERE
        PersonID = $PersonID AND Link = $qLink";
    $r = $dbh->query($SQL);
    dbErrorCheck($r);

    unset($_SESSION['desktopShortcuts'][$Link]);
}




/**
 *  Formats a non-ISO-formatted date to ISO format (YYYY-MM-DD)
 */
function DateToISO($pDate, $hasTime = false)
{
    $pDate = trim($pDate);
    if (strpos($pDate, '/') > 0) {
        //US format
        list($month, $day, $year) = explode('/', $pDate);
    } elseif (strpos($pDate, '.') > 0) {
        //German format
        list($day, $month, $year) = explode('.', $pDate);
    } elseif (strpos($pDate, '-') > 0) {
        //already ISO fomat
        return "\"$pDate\"";
    } else {
        if (strlen($pDate) > 0) {
            print("Unknown date format $pDate\n");
            $pDate = "NULL";
        }
    }
    if($hasTime) {
        list($year, $time, $ampm) = explode(' ', $year);
        list($hour, $minute) = explode(':', $time);
        $hour = intval($hour);
        $minute = intval($minute);
        if(!empty($ampm)) {
            $hour = $hour % 12; // a way to convert 12 into 0
            if(strtoupper($ampm) == 'PM') {
                $hour += 12;
            }
        }
    }
    $year = intval($year);
    $month = intval($month);
    $day = intval($day);

    if (checkdate($month, $day, $year)){
        if($hasTime){
            $ISODate = "\"$year-$month-$day $hour:$minute:00\"";
        } else {
            $ISODate = "\"$year-$month-$day\"";
        }
    } else {
        $ISODate = "NULL";
    }
    return $ISODate;
}



/**
 *  Translates submitted Checkbox values to database values
 */
function ChkFormat($value)
{
    if($value == '1') {
        return 1;
    } elseif($value == '-1') {
        return 0;
    } else {
        return 'NULL'; //"unselected"
    }

}



/**
 *  Translates database 'bool' values to Checkbox values
 */
function ChkUnFormat($value)
{
    if($value == '0') {
        return -1;
    } elseif ($value == '') {
        return ''; //UNselected!!
    } else {
        return 1;
    }
}





/**
 * handles server-side validation
 */
function Validate($value, $shortPhrase, $validationString) 
{

    $retval = '';  //empty string means valid
    $validationString = trim($validationString);

    if(strlen($validationString) == 0) {

        return '';

    } else {

        $validationStrings = split(' ', $validationString);

        foreach($validationStrings as $validationString) {

            switch($validationString) {
            case "noValidation":
                return '';
                break;
            case "notZero":
                if('' != trim($value)) {
                    if(!is_numeric($value)) {
                        $retval .= sprintf(gettext("The field '%s' can only contain numeric values."), $shortPhrase)."\n";
                    }
                    if((0 == intval($value)) && ('' != trim($value))) {
                        $retval .= sprintf(gettext("The field '%s' may not be zero."), $shortPhrase)."\n";
                    }
                }
            case "notEmpty":
                if('' == $value) {
                    $retval .= sprintf(gettext("The field '%s' may not be empty."), $shortPhrase)."\n";
                }
                break;
            case "notNegative":
                if('' != trim($value)) {
                    if(!is_numeric($value)) {
                        $retval .= sprintf(gettext("The field '%s' can only contain numeric values."), $shortPhrase)."\n";
                    }
                    if($value < 0) {
                        $retval .= sprintf(gettext("The field '%s' may not be negative."), $shortPhrase)."\n";
                    }
                }
                break;
            case "Email":
                if('' != trim($value)) {
                    require_once PEAR_PATH . '/Validate.php';  //PEAR Validation class, used for email validation
                    if(!Validate::email(trim($value), false)){
                        $retval .= sprintf(gettext("The field '%s' must be a valid email address. The format is incorrect for an email address.") . "\n", $shortPhrase);
                    } else {
                        if(VALIDATE_EMAIL_DOMAINS){
                            if(!Validate::email(trim($value), true)){
                                $retval .= sprintf(gettext("The field '%s' must be a valid email address. The domain name (the value after \"@\") is invalid for email addresses.") . "\n", $shortPhrase);
                            }
                        }
                    }
                    /*
                    //substituted the below regex with PEAR class above, which supports DNS domain validation
                    if(!preg_match('/^[^@\s]+@([-a-z0-9]+\.)+[a-z]{2,}$/i', $value)){
                        $retval .= sprintf(gettext("The field '%s' must be a valid email address."), $shortPhrase)."\n";
                    }*/
                }
                break;
            case "Integer":
                if(((!is_numeric($value)) && !is_integer(intval($value))) &&  ('' != trim($value))) {
                    $retval .= sprintf(gettext("The field '%s' must be a valid whole number."), $shortPhrase)."\n";
                }
                break;
            case "Money":
                $value = str_replace('$', '', $value);
                $value = str_replace(',', '', $value);
                if((!is_numeric($value)) &&  ('' != trim($value))) {
                    $retval .= sprintf(gettext("The field '%s' must be a valid number."), $shortPhrase)."\n";
                }
                break;
            case "Number":
                if((!is_numeric($value)) &&  ('' != trim($value))) {
                    $retval .= sprintf(gettext("The field '%s' must be a valid number."), $shortPhrase)."\n";
                }
                break;
            case "RequireSelection":
                if((0 == intval($value))) {
                    if(strlen($value) > 2 && strlen($value) < 6) {
                        //possibly a moduleID field....
                    } else {
                        $retval .= sprintf(gettext("The field '%s' must have a selected value."), $shortPhrase)."\n";
                    }
                }
                break;
            default:
                $retval .= sprintf(gettext("The field '%s' has an unknown validation type '%s'. There is a problem in the XML file that defines this module."), $shortPhrase, $validationString);
                break;
            }
        }
    }

    return $retval;
}



/**
 *  replaces placeholders with data values
 */
function PopulateValues($SQL, &$data)
{
    $pattern = '/\'\[\*(\w*)\*\]\'/';
    $matches = array();
    if(preg_match_all ( $pattern, $SQL, $matches)){
        //print debug_r($matches[1], 'PopulateValues');
        foreach($matches[1] as $fieldName){
            $SQL = str_replace(
            '\'[*'.$fieldName.'*]\'',
            dbQuote($data[$fieldName]),
            $SQL
        );
        }
    }
    if(false !== strpos($SQL, '[**UserID**]')){
        global $User;
        $SQL = str_replace('[**UserID**]', $User->PersonID, $SQL);
    }
    if(false !== strpos($SQL, '/**DynamicModuleID**/')){
        global $ModuleID;
        $SQL = str_replace('/**DynamicModuleID**/', $ModuleID, $SQL);
    }
    if(false !== strpos($SQL, '[**OwnerOrganizationID**]')){
        global $ModuleID;
        global $ownerField;
        if(!empty($ownerField)){
            $SQL = str_replace('[**OwnerOrganizationID**]', $data[$ownerField], $SQL);
        }
    }
    return $SQL;
}



/**
 *  formats a ViewTable
 */
function renderViewTable($content, $edit=NULL, $backlink=NULL, $editlink=NULL)
{
    $content = sprintf(VIEWTABLE_HTML, $content);
    return $content;
}



/**
 *  formats a popupViewTable
 */
function renderPopupViewTable($content)
{
    $content .= sprintf(VIEWTABLE_POPUPNAV_HTML, gettext("Close"));
    $content = sprintf(VIEWTABLE_HTML, $content);
    return $content;
}



/**
 *  formats an edit screen foem
 */
function renderForm($content, $targetlink, $deletelink, $cancellink, $nextScreen, $enctype, $moduleID, $addButtons = TRUE)
{
    if($addButtons){
        //make buttons:
        //first insert Save button
        $buttons = sprintf(FORM_SUBMIT_HTML, "Save", gettext("Save"), '');
        if(0 < strlen($deletelink)) {
            $buttons .= '<input type="hidden" name="Delete" id="Delete" value=""/>';
            $buttons .= "&nbsp;".sprintf(FORM_BUTTON_HTML, "Delete_btn", gettext("Delete"), 'confirmDelete(this);');
        }
        $buttons .= "&nbsp;".sprintf(FORM_BUTTON_HTML, "Cancel", gettext("Cancel"), "location='$cancellink'");

        //add button row
        $content .= sprintf(FORM_BUTTONROW_HTML, $buttons);
    }
    //insert all the content in the form
    $content = sprintf(FORM_HTML, $targetlink, $enctype, $content, $moduleID);

    return $content;
}



/**
 *  formats a search screen form
 */
function renderSearchForm($content, $targetlink, $chartLink, $moduleID)
{
    //make buttons:
    //first insert Save button
    $buttons = sprintf(FORM_SUBMIT_HTML, "Search", gettext("Search"), '');
    $buttons .= "&nbsp;".sprintf(FORM_SUBMIT_HTML, "Chart", gettext("Chart"), '');

    //add button row
    $content .= sprintf(FORM_BUTTONROW_HTML, $buttons);

    //insert all the content in the form
    $content = sprintf(FORM_HTML, $targetlink, '', $content, $moduleID);

    return $content;
}



/**
 * Generates the HTML for a LabelFields section
 */
function renderLabelFields($moduleID, $recordID)
{
    $content = '';
    require_once CLASSES_PATH . '/components.php';

    $label_filename = GENERATED_PATH . '/'.$moduleID.'/'.$moduleID.'_LabelSection.gen';
    if (!file_exists($label_filename)){
        return gettext("ERROR: file not found: ").$label_filename;
    } else {
        global $singularRecordName; //some screens use this for title
        include $label_filename;
    }

    //get label data
    global $dbh;
    $r = $dbh->getRow(str_replace('/**RecordID**/', $recordID, $labelSQL), DB_FETCHMODE_ASSOC);
    dbErrorCheck($r);

    if(count($r) > 0) {

        //display label
        foreach($fields as $key => $field){
            if (!$field->isSubField()){
                $content .= $field->render($r, $phrases);
            }
        }

        $content = renderViewTable($content);

        if(!empty($recordLabelField)){
            global $recordLabel;
            $recordLabel = $r[$recordLabelField];
        }

    } else {
        $content = gettext("ERROR: Empty query result.");
    }

    return $content;
}



/**
 * retrieves RDC triggers for module, and updates RDC for affected records
 */
function UpdateRDCache($moduleID, $recordID, $PKFieldName, $delete = false)
{
    global $dbh;

    //get triggers file
    $triggerFile = GENERATED_PATH . "/{$moduleID}/{$moduleID}_RDCTriggers.gen";

    if(file_exists($triggerFile)){
        include_once($triggerFile); //sets $RDCtriggers

        if(count($RDCtriggers) > 0){
            foreach($RDCtriggers as $triggerModuleID => $triggerSQL){

                if(false !== strpos($triggerSQL, '/**RecordID**/')){
                    $triggerSQL = str_replace('/**RecordID**/', $recordID, $triggerSQL);
                } else {
                    //this can be removed once all installations have been fully parsed
                    $triggerSQL .= " AND {$moduleID}.$PKFieldName = '$recordID'";
                }

                $triggerRecordIDs = $dbh->getCol($triggerSQL);

                if(dbErrorCheck($triggerRecordIDs, false, false)){
                    if(count($triggerRecordIDs)>0){
                        $strTriggerRecordIDs = join(',', $triggerRecordIDs);

                        if($delete){
                            $SQL = "UPDATE `rdc` SET _Deleted = 1 WHERE ModuleID = '$triggerModuleID' AND RecordID IN ($strTriggerRecordIDs)";

                            trace($SQL, "RDC delete");
                        } else {
                            //get existing cached records       
                            $SQL = "SELECT RecordID FROM `rdc` WHERE ModuleID = '$triggerModuleID' AND RecordID IN ($strTriggerRecordIDs)";

                            $cachedRecordIDs = $dbh->getCol($SQL);
                            $strCachedRecordIDs = join(',',$cachedRecordIDs);

                            $insertIDs = array_diff($triggerRecordIDs, $cachedRecordIDs);

                            //get cached SQL file:
                            $RDCUpdateFile = GENERATED_PATH . "/{$triggerModuleID}/{$triggerModuleID}_RDCUpdate.gen";
                            if(file_exists($RDCUpdateFile)){
                                include($RDCUpdateFile); //imports $RDCinsert and $RDCupdate

                                if(!empty($strCachedRecordIDs)){ //should always be something?
                                    //update existing
                                    $RDCupdate = str_replace('[*updateIDs*]', $strCachedRecordIDs, $RDCupdate);

                                    $r = $dbh->query($RDCupdate);
                                    dbErrorCheck($r);
                                }
                                //insert new, if any
                                if(count($insertIDs)>0){
                                    $RDCinsert = str_replace('[*insertIDs*]', join(',', $insertIDs), $RDCinsert);
                                    $r = $dbh->query($RDCinsert);
                                    dbErrorCheck($r);

                                }
                            }
                        }
                    }
                } else {
                    trigger_error("Warning: RDC update for module ($triggerModuleID) failed in $triggerFile.", E_USER_NOTICE);
                }
            }
        } 
    } else {
       // print "DEBUG: No triggers for $moduleID<br>\n";
    }

}

/**
 * retrieves SMC triggers for module, and updates SMC for affected records
 */
function UpdateSMCache($moduleID, $recordID, $PKFieldName)
{
    global $dbh;

    //get triggers file
    $triggerFile = GENERATED_PATH . "/{$moduleID}/{$moduleID}_SMCTriggers.gen";

    $insertSQL = "INSERT INTO `smc` (ModuleID, RecordID, SubModuleID, SubRecordID)\n";

    if(file_exists($triggerFile)){
        include_once($triggerFile); //sets $SMCtriggers

        foreach($SMCtriggers as $triggerModuleID => $triggerSQL){
            $triggerSQL .= " LEFT OUTER JOIN smc ON 
                `$moduleID`.$PKFieldName = smc.SubRecordID
                AND smc.ModuleID = '$triggerModuleID'
                AND smc.SubModuleID = '$moduleID' ";

            $triggerSQL = str_replace(array('/*SubModuleID*/', '/*SubRecordID*/'), array($moduleID, $recordID), $triggerSQL);

            $SQL = $insertSQL . $triggerSQL;
            $SQL .= "\nWHERE smc.ModuleID IS NULL\n";
            $SQL .= " AND `{$moduleID}`.$PKFieldName = '$recordID'";

             //   print debug_r($SQL, "SQL for $triggerModuleID");
             trace($SQL, "SMC SQL for $triggerModuleID");

            $r = $dbh->query($SQL);
            dbErrorCheck($r);
        }
    }
}


function GetGlobalTabs($ModuleID, $recordID, $selModuleID = null)
{
    if(empty($recordID)){ //if there's no record ID ($recordID is 0 or ''), we're in "new record" mode, so don't show global tabs
        return '';
    }

    $labels = array(
        'act' => gettext("Actions"),
        'att' => gettext("Attachments"),
        'cos' => gettext("Costs"),
        'lnk' => gettext("Links"),
        'nts' => gettext("Notes")
    );

    //don't show global module tabs in global modules
    if(in_array($ModuleID, array_keys($labels))){
        return '';
    }

    global $disableGlobalModules;
    if($disableGlobalModules){
        return '';
    }

    global $qsArgs;
    $gQsArgs = $qsArgs;
    unset($gQsArgs['gmd']);
    $globalQS = MakeQS($gQsArgs);

    $globalSummary = GetGlobalSummary($ModuleID, $recordID);

    //class, globalModuleID, link, mouseovermsg, label
    $normalTab = "<div class=\"%1\$s\">
    <a class=\"tabb\" id=\"tab_%2\$s\" href=\"%3\$s\" title=\"%4\$s\">%5\$s</a>
</div>\n";

    //class, label
    $selectedTab = "<div class=\"%s\">
    <div class=\"tabb\">
        %s
    </div>
</div>\n";

    $html = '';
    foreach($labels as $globalModuleID => $label){
        if($selModuleID == $globalModuleID){
            if(!empty($globalSummary[$globalModuleID])){
                $class = 'tabseld';
            } else {
                $class = 'tabsel';
            }
            $html .= sprintf(
                $selectedTab,
                $class,
                $label
                );
        } else {
            if(!empty($globalSummary[$globalModuleID])){
                $class = 'tabgd';
            } else {
                $class = 'tabg';
            }

            //class, globalModuleID, link, mouseovermsg, label
            $html .= sprintf(
                $normalTab,
                $class,
                $globalModuleID,
                'global.php?'.$globalQS.'&gmd='.$globalModuleID,
                sprintf(gettext("Edit %s for this record"), $label),
                $label
                );
        }
    }

    return $html;
}


function GetGlobalSummary($moduleID, $recordID){
    global $dbh;

    $SQL = "SELECT 'act' AS ModuleID, COUNT(*) AS records 
FROM `act` 
WHERE RelatedModuleID = '$moduleID' AND RelatedRecordID = '$recordID' AND _Deleted = 0
UNION
SELECT 'att' AS ModuleID, COUNT(*) AS records 
FROM `att` 
WHERE RelatedModuleID = '$moduleID' AND RelatedRecordID = '$recordID' AND _Deleted = 0
UNION
SELECT 'cos' AS ModuleID, COUNT(*) AS records
FROM `cos` 
WHERE RelatedModuleID = '$moduleID' AND RelatedRecordID = '$recordID' AND _Deleted = 0
UNION
SELECT 'lnk' AS ModuleID, COUNT(*) AS records 
FROM `lnk` 
WHERE RelatedModuleID = '$moduleID' AND RelatedRecordID = '$recordID' AND _Deleted = 0
UNION
SELECT 'nts' AS ModuleID, COUNT(*) AS records 
FROM `nts` 
WHERE RelatedModuleID = '$moduleID' AND RelatedRecordID = '$recordID' AND _Deleted = 0
";

    $res = $dbh->getAssoc($SQL);
    dbErrorCheck($res);
    return $res;
}


/**
 *  returns file system path to the current theme folder
 */
function GetThemeLocation()
{
    global $User;
    if(!empty($User->theme)){
        $theme = $User->theme;
    } else {
        $theme = DEFAULT_THEME;
    }
    return THEME_PATH . '/' . $theme;
}


/**
 *  returns web path to the current theme folder
 */
function GetThemeWebLocation()
{
    global $User;
    if(!empty($User->theme)){
        $theme = $User->theme;
    } else {
        $theme = DEFAULT_THEME;
    }
    return THEME_WEB_PATH . '/' . $theme;
}


/**
 * returns the HTML for page title icons, as needed
 */
function GetPageTitleIcons()
{
    $content = '';
    global $theme_web;

    //see if this is a best practice record
    global $useBestPractices;
    if($useBestPractices){
        global $data;
        if($data['IsBestPractice']){
            $content .= '<img src="'.$theme_web.'/img/best_practice.png" alt="(This record is a Best Practice)"/>';
        }
    }

    //determine whether to display Directions
    global $directionCount; //must be generated before calling this function
    if(intval($directionCount) > 0){
        if(!empty($content)){
            //adds some spacing between the icons
            $content .= '&nbsp;';
        }
        $content .= '<a href="#" onmouseover="showTitlePopover(\'directions_popover\', this)" onmouseout="hideTitlePopover(\'directions_popover\')">';
        $content .= '<img src="'.$theme_web.'/img/directions.gif" alt="Directions"/>';
        $content .= '</a>';
    }

    //determine whether to display Guidance
    global $guidanceGrid;
    if(!empty($guidanceGrid)){
        $guidanceCount = $guidanceGrid->getRecordCount();
        if(intval($guidanceCount) > 0){
            if(!empty($content)){
                //adds some spacing between the icons
                $content .= '&nbsp;';
            }
            $content .= '<a href="#" onmouseover="showTitlePopover(\'guidance_popover\', this)" onmouseout="hideTitlePopover(\'guidance_popover\')">';
            $content .= '<img src="'.$theme_web.'/img/guidance.png" alt="Guidance"/>';
            $content .= '</a>';
        }
    }

    //determine whether to display Guidance
    global $resourceCount; //must be generated before calling this function
    if(intval($resourceCount) > 0){
        if(!empty($content)){
            //adds some spacing between the icons
            $content .= '&nbsp;';
        }
        $content .= '<a href="#" onmouseover="showTitlePopover(\'resources_popover\', this)" onmouseout="hideTitlePopover(\'resources_popover\')">';
        $content .= '<img src="'.$theme_web.'/img/resources.png" alt="Resources"/>';
        $content .= '</a>';
    }

    if(!empty($content)){
        $content = '&nbsp;&nbsp;' . $content;
    }
    return $content;
}



function GetParentInfo($moduleID)
{
    global $dbh;
    global $theme_web;

    $SQL = "SELECT 
    `modd`.DependencyID AS ParentModuleID,
    `mod`.Name AS ParentModule
FROM
    `modd`
    INNER JOIN `mod`
    ON (`modd`.DependencyID = `mod`.ModuleID
    AND `modd`.SubModDependency = 1
    )
WHERE `modd`.ModuleID = '$moduleID'
ORDER BY ParentModule";
    $r = $dbh->getAll($SQL, DB_FETCHMODE_ASSOC);
    dbErrorCheck($r);

    $relations_icon = '<a href="#" onclick="showRelationsPopover(\''.$moduleID.'\', this)" title="Relations"><img src="'.$theme_web.'/img/module_relations.gif" alt="Relations" /></a>&nbsp;';

    switch(count($r)){
    case 0:
        return $relations_icon;
    case 1:
        $link =  '<a href="list.php?mdl='.$r[0]['ParentModuleID'].'">';
        $link .= sprintf(gettext("Parent Module: %s"), $r[0]['ParentModule']);
        $link .= '</a>';
        return $relations_icon . $link;
    default:
        return $relations_icon . sprintf(gettext("Parent Modules: %s"), count($r));
    }
}



/**
 *  Returns the URLs for the "next" and "previous" records, used by View and Edit screens.
 */
function GetSeqLinks($moduleID, $currentSeqID, $pageName){
    $currentSeqID = intval($currentSeqID);

    if(empty($_SESSION[$moduleID . '_ListSeq'])){
        return array(null, null);
    } else {
        $listSequence =& $_SESSION[$moduleID . '_ListSeq'];
    }

    global $qsArgs;
    $sqQSArgs = $qsArgs;
    unset($sqQSArgs['rid']);
    unset($sqQSArgs['sr']);
    $sqQS = MakeQS($sqQSArgs);

    $nPageRows = $listSequence['rpp'];

    $prevSeq = $currentSeqID-1;
    $pageStartRow = floor($prevSeq / $nPageRows) * $nPageRows;
    if(isset($listSequence['rows'][$prevSeq])){
        $pageStartRow = floor($prevSeq / $nPageRows) * $nPageRows;
        $prevRecordID = $listSequence['rows'][$prevSeq];
        $prevLink = $pageName.'?'.$sqQS.'&rid='.$prevRecordID.'&sr='.$prevSeq;
    } else {
        if($prevSeq < 0){
            $prevLink = null;
        } else {
            $SQL = $listSequence['sql'];
            $SQL .= " LIMIT $pageStartRow, $nPageRows";
            global $dbh;
            $result = $dbh->getCol($SQL);
            dbErrorCheck($result);
            foreach($result as $rowIX => $recordID){
                $listSequence['rows'][$pageStartRow + $rowIX] = $recordID;
            }
            $prevRecordID = $listSequence['rows'][$prevSeq];
            $prevLink = $pageName.'?'.$sqQS.'&rid='.$prevRecordID.'&sr='.$prevSeq;
        }
    }

    $nextSeq = $currentSeqID+1;
    $pageStartRow = floor($nextSeq / $nPageRows) * $nPageRows;
    if(isset($listSequence['rows'][$nextSeq])){
        $nextRecordID = $listSequence['rows'][$nextSeq];
        $nextLink = $pageName.'?'.$sqQS.'&rid='.$nextRecordID.'&sr='.$nextSeq;
    } else {

        if($nextSeq >= $listSequence['count']){
            $nextLink = null;
        } else {

            $SQL = $listSequence['sql'];
            $SQL .= " LIMIT $nextSeq, $nPageRows";
            global $dbh;
            $result = $dbh->getCol($SQL);
            dbErrorCheck($result);
            foreach($result as $rowIX => $recordID){
                $listSequence['rows'][$nextSeq + $rowIX] = $recordID;
            }

            $nextRecordID = $listSequence['rows'][$nextSeq];
            $nextLink = $pageName.'?'.$sqQS.'&rid='.$nextRecordID.'&sr='.$nextSeq;
        }
    }

    return(array($prevLink, $nextLink));
}



/**
 *  Helper class to render text tables
 */
class TextTable {
    var $colWidths = array();
    var $rowHeights = array();
    var $data = array();
    var $headers = array();
    var $hasHeaders = false;

    function TextTable($data, $headers = null){
        $this->data = $data;
        if(!empty($headers)){
            $this->hasHeaders = true;
            $this->headers = $headers;
        }
    }

    function render(){
        $content = '';

        if($this->hasHeaders){
            $data = array_merge(array($this->headers), $this->data);
        } else {
            $data = $this->data;
        }

        //calculate dimensions for all rows and columns
        foreach($data as $row_ix => $row){
            foreach($row as $col_ix => $value){
                list($w, $h) = $this->getCellDimensions($value);
                if($this->colWidths[$col_ix] < $w){
                    $this->colWidths[$col_ix] = $w;
                }
                if($rowHeights[$row_ix] < $h){
                    $this->rowHeights[$row_ix] = $h; 
                }
            }
        }

        $separator = $this->getSeparator($this->colWidths);

        $content = $separator;

        foreach($data as $row_ix => $row){
            $content .= $this->formatRow($row, $this->rowHeights[$row_ix], $this->colWidths) . "\n";
            if($this->hasHeaders && 0 == $row_ix){
                $content .= $separator;
            }
        }
        $content .= $separator;

        return $content;
    }

    function getSeparator($colWidths){
        $content = '+';
        foreach($colWidths as $colWidth){
            $content .= '-' . str_pad('', $colWidth, '-') . '-+';
        }
        $content .= "\n";
        return $content;
    }

    function formatRow($row, $height, $colWidths){
        $rowLines = array();

        //walk through cells of row
        foreach($row as $col_ix => $cellValue){

            $cellLines = explode("\n", $cellValue);
            $cellHeight = count($cellLines);
            $align = STR_PAD_RIGHT;

            //prepend pipe on each row
            if(0 == $col_ix){
                $align = STR_PAD_LEFT;
                for($line_ix = 0; $line_ix < $height; $line_ix++){
                    $rowLines[$line_ix] = '|';
                }
            }

            //walk through lines of cell
            for($line_ix = 0; $line_ix < $height; $line_ix++){
                if($line_ix < $cellHeight){
                    $rowLines[$line_ix] .= ' '. str_pad(trim($cellLines[$line_ix]), $colWidths[$col_ix], ' ', $align) . ' |';
                } else {
                    $rowLines[$line_ix] .= ' '. str_pad('', $colWidths[$col_ix]) . ' |';
                }
            } 
        }

        return join("\n", $rowLines);
    }

    function getCellDimensions($value){
        $lines = explode("\n", $value);
        $maxLineLength = 0;
        foreach($lines as $line){
            if(strlen($line) > $maxLineLength){
                $maxLineLength = strlen($line);
            }
        }
        return array($maxLineLength, count($lines));
    }
}



?>