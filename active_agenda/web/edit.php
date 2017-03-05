<?php
/**
 * Handles content for the Edit Screen
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
//    include_once CLASSES_PATH . '/components.php';  //grids include this
include_once CLASSES_PATH . '/modulefields.php';

include_once $theme .'/component_html.php';

//get the record ID
$recordID = intval($_GET['rid']);
if($recordID == 0){
    if(strlen($_GET['rid']) >= 3){
        $recordID = substr($_GET['rid'], 0, 5);
    }
}

$ScreenName = addslashes($_GET['scr']);
$jsIncludes = '';

$moduleInfo = GetModuleInfo($ModuleID);

//if no screen name was supplied, go to the first screen
if(empty($ScreenName)){
    $ScreenName = $moduleInfo->getProperty('firstEditScreen');
} else {
    //validate the supplied ScreenName
    $includeFile = GENERATED_PATH . "/{$ModuleID}/{$ModuleID}_ScreenList.gen";
    if(file_exists($includeFile)){
        include $includeFile; //provides $screenList
        if(!isset($screenList[$ScreenName])){
            trigger_error("This module has no screen by the name '$ScreenName'.", E_USER_ERROR);
        }
        if('editscreen' != $screenList[$ScreenName]){
            trigger_error("The screen '$ScreenName' is not an EditScreen (it's a '{$screenList[$ScreenName]}').", E_USER_ERROR);
        }
    } else {
        trigger_error("Could not find a Screen List to verify the requested screen.", E_USER_ERROR);
    }
}

$filename = GENERATED_PATH . "/{$ModuleID}/{$ModuleID}_Edit{$ScreenName}.gen";

//check for cached page for this module
if (!file_exists($filename)){
    trigger_error("Could not find file '$filename'. ", E_USER_ERROR);
}

$messages = array(); //init

//the included file sets $content variable used by template below
include($filename);

include_once(GENERATED_PATH . '/moddr/moddr_GlobalViewGrid.gen');
if(!empty($ownerField) && isset($grid)){ //unfortunate name returned by include above
    $directionCount = $grid->getRecordCount();
    if(intval($directionCount) > 0){
        $content .= sprintf(
            POPOVER_DIRECTIONS,
            ShortPhrase($grid->phrase),
            $grid->render('edit.php', $qsArgs)
            );
    }
} else {
    $directionCount = 0;
}
//print "Owner org: {$data[$ownerField]}\n";

if(isset($guidanceGrid)){
    $content .= sprintf(
        POPOVER_GUIDANCE,
        ShortPhrase($guidanceGrid->phrase),
        $guidanceGrid->render('edit.php', $qsArgs)
    );
}

unset($grid);
include_once(GENERATED_PATH . '/res/res_GlobalViewGrid.gen');
if(!empty($ownerField) && isset($grid)){ //unfortunate name returned by include above
    $resourceCount = $grid->getRecordCount();
    if(intval($resourceCount) > 0){
        $content .= sprintf(
            POPOVER_RESOURCES,
            ShortPhrase($grid->phrase),
            $grid->render('edit.php', $qsArgs)
            );
    }
} else {
    $resourceCount = 0;
}

$jsIncludes .= '<script type="text/javascript" src="3rdparty/jscalendar/calendar_stripped.js"></script>'."\n";
$LangPrefix = substr($User->Lang, 0, 2);
$jsIncludes .= '<script type="text/javascript" src="3rdparty/jscalendar/lang/calendar-'.$LangPrefix.'.js"></script>'."\n";
$jsIncludes .= '<script type="text/javascript" src="3rdparty/jscalendar/calendar-setup_stripped.js"></script>'."\n";
$jsIncludes .= '<script type="text/javascript">
function confirmDelete(sender){
    if(confirm(\'Delete this record?\')){
        sender.form[\'Delete\'].value = "Delete";
        sender.form.submit();
    }
}
</script>'."\n";

$globalDiscussions = DISCUSSION_LINK_GLOBAL . $moduleInfo->getProperty('globalDiscussionAddress');
$localDiscussions = DISCUSSION_LINK_LOCAL . $moduleInfo->getProperty('localDiscussionAddress');

$screenPhrase = ShortPhrase($screenPhrase);

if(0 < strlen($_GET['sr'])){
    list($prevLink,$nextLink) = GetSeqLinks($ModuleID, $_GET['sr'], 'edit.php');
}

$moduleID = $ModuleID;
if($existing){
    $recordLabel = $recordLabelField;
    $title = $pageTitle.' - '.$screenPhrase;
} else {
    $recordLabel = sprintf(gettext("Entering a new %s record"), $singularRecordName);
    $title = $pageTitle.' - '.gettext("New Record");
}
//$recordID;
//$content;
//$globalDiscussions;
//$localDiscussions;

include_once $theme . '/edit.template.php';
?>

