<?php
/**
 *  Utility functions needed by the usr module.
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
 * @version        SVN: $Revision: 499 $
 * @last-modified  SVN: $Date: 2007-02-16 13:43:40 -0800 (Fri, 16 Feb 2007) $
 */

function verifyPassword($fieldName){
    global $messages;

    $pass = $_POST[$fieldName];
    if(empty($pass)){
        //no password: allowed for updates, not for inserts
        global $recordID;
        if(empty($recordID)){
            $messages[] = array('e', 'You must supply a password');
            return FALSE;
        } else {
            $messages[] = array('m', 'The old password will not be changed.');
            global $data;
            unset($data[$fieldName]);
            unset($_POST[$fieldName]);
            return TRUE;
        }
    } else {
        if($_POST[$fieldName] == $_POST[$fieldName.'_confirm']){
            $messages[] = array('m', 'The password will be updated.');

            global $data;
            $data[$fieldName] = encryptPassword($_POST[$fieldName]);

            return TRUE;
        } else {
            $messages[] = array('e', 'The passwords you supplied did not match');
            return FALSE;
        }
    }

}

function encryptPassword($password){
    return crypt($password, CRYPT_SEED);
}


function checkForDeletedRow(){
    //changes $exisiting to true if a deleted row exists
    //this is useful because the PK field (PersonID) does not auto_increment

    global $dbh;
    global $data;

    $qPersonID = dbQuote($data['PersonID'], 'int');

    $SQL = "SELECT PersonID FROM usr WHERE PersonID = $qPersonID";
    $r = $dbh->getOne($SQL);
    dbErrorCheck($r);

    global $existing;

    if(empty($r)){
        $existing = FALSE;
    } else {
        //"undeleete" the row
        $SQL = "UPDATE usr SET _Deleted = 0 WHERE PersonID = $qPersonID";
        $r = $dbh->query($SQL);
        dbErrorCheck($r);
        $existing = TRUE;
    }
}
?>