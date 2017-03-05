<?php
/**
 * Handles the Search screen
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

//classes
include_once(CLASSES_PATH . '/search.class.php');
include_once(CLASSES_PATH . '/components.php');
include_once(CLASSES_PATH . '/modulefields.php');

//main include file - performs all general application setup
require_once(INCLUDE_PATH . '/page_startup.php');

include_once $theme .'/component_html.php';

$user_info = "<b>" . $User->DisplayName . "</b>";
if ($User->IsAdmin) {
            $user_info .= ", Site Administrator";
}

//main business here
$filename = GENERATED_PATH . '/'.$ModuleID.'/'.$ModuleID.'_Search.gen';

//check for cached page for this module
if (!file_exists($filename)){
    trigger_error("Could not find file '$filename'.", E_USER_ERROR);
}

//the included file sets $content variable used by template below
include($filename);

$jsIncludes .= '<script type="text/javascript" src="3rdparty/jscalendar/calendar_stripped.js"></script>'."\n";
$LangPrefix = substr($User->Lang, 0, 2);
$jsIncludes .= '<script type="text/javascript" src="3rdparty/jscalendar/lang/calendar-'.$LangPrefix.'.js"></script>'."\n";
$jsIncludes .= '<script type="text/javascript" src="3rdparty/jscalendar/calendar-setup_stripped.js"></script>'."\n";

$moduleInfo = GetModuleInfo($ModuleID);
$globalDiscussions = DISCUSSION_LINK_GLOBAL . $moduleInfo->getProperty('globalDiscussionAddress');
$localDiscussions = DISCUSSION_LINK_LOCAL . $moduleInfo->getProperty('localDiscussionAddress');

//have $jsIncludes
$title = $pageTitle;
//have $user_info
//have $tabs
$chartToDashHTML = '';

//have $screenPhrase
//have $content
//have $globalDiscussions
//have $localDiscussions
//have $ModuleID
include_once $theme . '/search.template.php';
?>

