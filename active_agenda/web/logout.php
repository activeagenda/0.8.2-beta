<?php
/**
 * Handles logout and removes session
 *
 * LICENSE NOTE:
 *
 * Copyright  2003-2007 Active Agenda Inc., All Rights Reserved.
 *
 * Unless explicitly acquired and licensed from Licensor under a "commercial license",
 * the contents of this file are subject to the Reciprocal Public License ("RPL")
 * Version 1.4, or subsequent versions as allowed by the RPL,and You may not copy
 * or use this file in either source code or executable form, except in compliance
 * with the terms and conditions of the RPL. You may obtain a copy of the RPL from
 * Active Agenda Inc. at http://www.activeagenda.net/license.
 *
 * All software distributed under the Licenses is provided strictly on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESS OR IMPLIED, AND ACTIVE AGENDA
 * INC. HEREBY DISCLAIMS ALL SUCH WARRANTIES, INCLUDING WITHOUT LIMITATION, ANY 
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, QUIET ENJOYMENT,
 * OR NON-INFRINGEMENT. See the Licenses for specific language governing rights and
 * limitations under the Licenses.
 *
 * author         Mattias Thorslund <mthorslund@activeagenda.net>
 * copyright      2003-2007 Active Agenda Inc.
 * license        http://www.activeagenda.net/license
 **/

define('USER_LOGOUT', true);

//general settings
require_once '../config.php';
set_include_path(PEAR_PATH . PATH_SEPARATOR . get_include_path());

//utility functions
require_once INCLUDE_PATH . '/general_util.php';
require_once INCLUDE_PATH . '/web_util.php';

/**
 * Sets custom error handler
 */
set_error_handler('handleError');


/**
 * Use a unique session name instead of PHPSESSID
 */
session_name(SITE_SESSIONNAME);



/**
 * handle 'double PHPSESSID cookie problem'
 */
CleanSessionCookie();

//user object class
require_once(CLASSES_PATH . '/user.class.php');


//start session handling
if (! session_start()) {
    //log error "session could not be created"
    trigger_error("Session could not be created.", E_USER_WARNING);
    include_once('login.php');
    exit;
}

//log user logout to DB via User obj
if (isset($_SESSION['User'])) {
    //get our user object from session:
    //$User = unserialize($_SESSION['User']);
    //no need to unserialize
    $User = $_SESSION['User'];
    $User->Logout();
    $logout_message = "This ends the session for {$User->DisplayName} ({$User->UserName})...<br /><br />";

}

//empty the session object - this helps allow destroying it.
$_SESSION = array();

//sniff the session domain
list(,,$cookie_domain,) = session_get_cookie_params();
$session_name = session_name();

//destroy the session
if (session_destroy()) {
    $logout_message .= "You have successfully logged out.";
} else {
    $logout_message .= "There was a problem logging out.";
    trigger_error("Logout problem.", E_USER_WARNING);
}

include_once('login.php');

?>