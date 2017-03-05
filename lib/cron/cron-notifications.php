<?php 
/**
 *  Sends notifications via email, on a scheduled basis
 *
 *  This script should be executed regularly by a cron job (or similar), in order for 
 *  email notifications to be sent.
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

//get project name from parameter
$Project = $_SERVER[argv][1];
$suppress = $_SERVER[argv][2];
if(!empty($suppress)){
    $suppress = true;
}

//get s2a folder: assumes we're in the 's2a/cron' folder 
$folder = realpath(dirname($_SERVER['SCRIPT_FILENAME']).'');
$folder = str_replace('/lib/cron', '', $folder);
$folder .= '/'.$Project;

//settings
include_once $folder . '/config.php';
set_include_path(PEAR_PATH . PATH_SEPARATOR . get_include_path());

//PEAR libraries
require_once PEAR_PATH . '/DB.php';        //PEAR DB class
require_once PEAR_PATH . '/Validate.php';  //PEAR Validation class, used for email validation
require_once PEAR_PATH . '/Mail.php';
require_once PEAR_PATH . '/Mail/mime.php';

//handy functions
include_once INCLUDE_PATH . '/general_util.php';


//connect to database (using normal permissions)
$dbh = DB::connect(DB_DSN, array('persistent' => true));
if (DB::isError($dbh)) {
    die ($dbh->getMessage());
}


//get unsent notifications
$SQL = "SELECT ntf.NotificationID, 
    ntf.Subject,
    ntf.TextContent,
    ntf.HTMLContent,
    ppl.DisplayName as SenderName,
    ppl.WorkEmail as SenderEmail
FROM ntf 
    LEFT OUTER JOIN ppl 
    ON ntf._ModBy = ppl.PersonID
WHERE 
    ntf._Deleted = 0 
    AND ntf.StatusID = 2";
$notifications = $dbh->getAssoc($SQL);
dbErrorCheck($notifications);

//get the distinct email addresses, for a up-to-date validation check
$SQL = "SELECT DISTINCT ppl.WorkEmail, '' as Status 
    FROM ntfr
        LEFT OUTER JOIN ppl
        ON (ntfr.RecipientID = ppl.PersonID
            AND ppl._Deleted = 0
        )
    WHERE ntfr.StatusID = 2 
    AND ntfr._Deleted = 0";
$addresses = $dbh->getAssoc($SQL);
dbErrorCheck($addresses);

//validates email addresses with the PEAR Validate class
printm("validating email addresses:\n", $suppress);
foreach($addresses as $address => $status){
    //validate email address syntax
    if(Validate::email($address, VALIDATE_EMAIL_DOMAINS)){
        $addresses[$address] = 'valid'; //"seems valid", that is
    } else {
        $addresses[$address] = 'invalid';
    }
    printm("'$address' is {$addresses[$address]}\n", $suppress);
}
printm("finished validating\n", $suppress);

//get notification messages
$SQL = "SELECT 
    ntfr.NotificationRecipientID,
    ntfr.NotificationID, 
    ntfr.RecipientID,
    ppl.DisplayName,
    ppl.WorkEmail
FROM ntfr
    LEFT OUTER JOIN ppl
    ON (ntfr.RecipientID = ppl.PersonID
        AND ppl._Deleted = 0
    )
WHERE ntfr.StatusID = 2 
AND ntfr._Deleted = 0
ORDER BY ntfr.NotificationID, ntfr._ModDate";

$messages = $dbh->getAll($SQL, DB_FETCHMODE_ASSOC);
dbErrorCheck($messages);


printm("Sending notifications:\n", $suppress);
$sentNotifications = array();
$failedRecipients = array();

//walk through messages (ntfr)
if(count($messages) > 0){
    $message = reset($messages); //first message
    $currentNotificationID = $message['NotificationID'];
    $subjectPrefix = '['.SITE_SHORTNAME.'] ';

    $sender = '"'.$notifications[$message['NotificationID']][3].'" <'. $notifications[$message['NotificationID']][4].'>';
    $subject = $subjectPrefix. $notifications[$message['NotificationID']][0];

    foreach($messages as $message){

        //check if we've moved on to a new notification
        if($currentNotificationID != $message['NotificationID']){

            handleFailedRecipients($failedRecipients[$currentNotificationID], $subject);
            $currentNotificationID = $message['NotificationID'];

            $sender = '"'.$notifications[$message['NotificationID']][3].'" <'. $notifications[$message['NotificationID']][4].'>';
            $subject = $subjectPrefix. $notifications[$message['NotificationID']][0];
        }

        $address = trim($message['WorkEmail']);

        //check if address is empty
        if(empty($address)){
            $result = 'empty address';
        } else {
            $result = $addresses[$address];
        }

        if('valid' == $result){
            printm("Sending notification {$message['NotificationID']} to {$message['DisplayName']} <$address>\n", $suppress);
            
            //send message
            $statusID = sendEmail(
                $sender,  //from
                '"'.$message['DisplayName'].'" <'.$address.'>',  //to
                $subject, //subject
                $notifications[$message['NotificationID']][1], //text message
                $notifications[$message['NotificationID']][2]  //HTML message
            );
        } else {
            $statusID = 22;
            $failedRecipients[$currentNotificationID][] = "$result: {$message['DisplayName']} <$address>\n";
        }

        //update ntfr with status
        $SQL = "UPDATE ntfr SET StatusID = $statusID, _ModDate = NOW() WHERE NotificationRecipientID = {$message['NotificationRecipientID']}";
        $r = $dbh->query($SQL);
        dbErrorCheck($r);

        if(empty($sentNotifications[$message['NotificationID']])){
            $sentNotifications[$message['NotificationID']] = 1;
        } else {
            $sentNotifications[$message['NotificationID']]++;
        }
    }

    handleFailedRecipients($failedRecipients[$currentNotificationID], $subject);

    if(count($sentNotifications) > 0){
        $str_sentNotifications = join(',',array_keys($sentNotifications));
        $SQL = "UPDATE ntf SET StatusID = 3 WHERE NotificationID IN ($str_sentNotifications)";
        $r = $dbh->query($SQL);
        dbErrorCheck($r);
    }
}
printm("all done.\n", $suppress);

function printm($msg, $suppress = false){
    if(!$suppress){
        print $msg;
    }
}



function handleFailedRecipients($failedRecipients, $origSubject){
    global $notifications;
    global $message;
    global $subjectPrefix;

    if(count($failedRecipients) > 0){
        $to = '"'.$notifications[$message['NotificationID']][3].'" <'. $notifications[$message['NotificationID']][4].'>';
        $failureSubject = "re: $origSubject (delivery problems)";
        $failureMessage = "The following recipients could not be reached:\n";
        $failureMessage .= join("\n", $failedRecipients);
        sendEmail('errors@activeagenda.net', $sender, $failureSubject, $failureMessage);
    }
}
?>