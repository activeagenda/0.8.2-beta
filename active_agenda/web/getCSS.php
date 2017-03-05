<?php
/**
 * returns CSS styles for the user's theme
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

//general settings
require_once '../config.php';
set_include_path(PEAR_PATH . PATH_SEPARATOR . get_include_path());

require_once(CLASSES_PATH . '/user.class.php');

/**
 * Utility functions includes.
 */
require_once INCLUDE_PATH . '/general_util.php';
require_once INCLUDE_PATH . '/web_util.php';


/**
 * Sets custom error handler
 */
set_error_handler('handleError');


/**
 * handle 'double PHPSESSID cookie problem'
 */
CleanSessionCookie();

$User = $_SESSION['User'];

$theme = GetThemeLocation();

$sheet = $_GET['sheet'];
$sheet = str_replace(array('.', '/', '\''), '', $sheet);
$sheet = addslashes($sheet);


$filename = $theme . '/' . $sheet . '.css';
if(file_exists($filename)){
    header('Content-type: text/css');
    include($filename);
}
?>