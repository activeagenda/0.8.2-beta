<?php
/**
 * Handles the View Screen
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

//main include file - performs all general application setup
require_once INCLUDE_PATH . '/page_startup.php';

include_once CLASSES_PATH . '/grids.php';
include_once INCLUDE_PATH.'/viewscreensection.php';
include_once $theme .'/component_html.php';

setTimeStamp('view_after_classdefs');

//get the record ID
$recordID = intval($_GET['rid']);
if($recordID == 0){
    if(strlen($_GET['rid']) >= 3){
        $recordID = substr($_GET['rid'], 0, 5);
    }
}

//verify that a record id was passed
if (empty($recordID)){
    trigger_error("No record was selected.", E_USER_ERROR);
}

$filename = GENERATED_PATH . '/'.$ModuleID.'/'.$ModuleID.'_View.gen';

//check for cached page for this module
if (!file_exists($filename)){
    trigger_error("Could not find file '$filename'.", E_USER_ERROR);
}

//the included file sets $content variable used by template below
include($filename);


setTimeStamp('view_after_gen_screen');

include_once('globalViewGrids.php');

setTimeStamp('view_after_global_viewgrids');

$moduleInfo = GetModuleInfo($ModuleID);
$globalDiscussions = DISCUSSION_LINK_GLOBAL . $moduleInfo->getProperty('globalDiscussionAddress');
$localDiscussions = DISCUSSION_LINK_LOCAL . $moduleInfo->getProperty('localDiscussionAddress');

include_once(GENERATED_PATH . '/moddr/moddr_GlobalViewGrid.gen');
if(!empty($ownerField) && isset($grid)){ //unfortunate name returned by include above
    $directionCount = $grid->getRecordCount();
    if(intval($directionCount) > 0){
        $content .= sprintf(
            POPOVER_DIRECTIONS,
            ShortPhrase($grid->phrase),
            $grid->render('view.php', $qsArgs)
        );
    }
} else {
    $directionCount = 0;
}

//shortcuts functionality
$linkHere = "view.php?mdl=$ModuleID&rid=$recordID";
if(empty($_GET['sctitle'])){
    $scTitle = $pageTitle;
} else {
    $scTitle = $_GET['sctitle'];
}
if(!empty($_GET['shortcut'])){
    switch($_GET['shortcut']){
    case 'set':
        SaveDesktopShortCut($User->PersonID, $scTitle, $linkHere, 'View', $ModuleID);
        break;
    case 'remove':
        RemoveDesktopShortcut($User->PersonID, $linkHere);
        break;
    default:
        //nada
    }
}
$dash_shortcutTitle = $_SESSION['desktopShortcuts'][$linkHere];
if(empty($dash_shortcutTitle)){
    $dash_shortcutTitle = $recordLabelField;
    $hasShortcut = false;
} else {
    $hasShortcut = true;
}


if(0 < strlen($_GET['sr'])){
    list($prevLink,$nextLink) = GetSeqLinks($ModuleID, $_GET['sr'], 'view.php');
}


$title = $pageTitle;
$recordLabel = $recordLabelField;

//$tabs;
$screenPhrase = ShortPhrase($screenPhrase);
$moduleID = $ModuleID;
//$recordID;
//$content;
//$globalDiscussions;
//$localDiscussions;
$xmlExportLink = "xmlExport.php?mdl=$ModuleID&rid=$recordID";

include_once $theme . '/view.template.php';
?>