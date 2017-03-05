<?php
/**
 * Handles content for the Charts Screen
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

//this include contains the search class
include_once CLASSES_PATH . '/search.class.php';

include_once CLASSES_PATH . '/components.php';

//main include file - performs all general application setup
require_once INCLUDE_PATH . '/page_startup.php';

$tabsQSargs = $qsArgs;
unset($tabsQSargs['scr']);
unset($tabsQSargs['gid']);
unset($tabsQSargs['grw']);
$tabsQS = MakeQS($tabsQSargs);

$tabs = array(); 
$tabs['List'] = array("list.php?$tabsQS", gettext("List|View the list"));
$tabs['Search'] = array("search.php?$tabsQS", gettext("Search|Go to the search screen"));
$tabs['Charts'] = array('', gettext("Charts|View charts"));

if(!empty($_GET['dsbc'])){


    $dashboardChartID = intval($_GET['dsbc']);
    $SQL = "SELECT ChartName FROM dsbc WHERE DashboardChartID = $dashboardChartID AND ModuleID = '$ModuleID' AND UserID = {$User->PersonID}";

    $selectedChartName = $dbh->getOne($SQL);
    dbErrorCheck($selectedChartName);
    if(empty($selectedChartName)){
        trigger_error(gettext("Invalid Dashboard Chart ID"), E_USER_WARNING);
        $use_dsbc = false;
    } else {
        $dashChartName = $selectedChartName;
        $use_dsbc = true;
    }
} else {
    $dashChartName = '';
}

if($use_dsbc){

    $search = GetNewSearch($ModuleID);
    $search->loadChartConditions($User->PersonID, $dashboardChartID);

    $_SESSION['Search_'.$ModuleID] = $search; 

} else {

    if(!empty($_GET['chn'])) {
        $selectedChartName = addslashes($_GET['chn']);
    } else {
        $selectedChartName = '';
    }

    $search = $_SESSION['Search_'.$ModuleID];
}


if(!is_object($search)){
    $search = GetNewSearch($ModuleID);
    $_SESSION['Search_'.$ModuleID] = $search;
}

$content = '';
$chartImgTag = '';

$SQL = "SELECT Name, Title, Type FROM modch WHERE ModuleID = '$ModuleID' ORDER BY Title";
$r = $dbh->getAll($SQL, DB_FETCHMODE_ASSOC);
dbErrorCheck($r);

$content .= '<script language="JavaScript">
    var moduleID = \''.$ModuleID.'\';
    var dashChartName = \''.$dashChartName.'\';
    function replaceChart(val){
        d = new Date();  //appending the date integer ensures that the browser cache isn\'t used
        img_chart = document.getElementById("chartimg");
        img_chart.src="chartViewer.php?mdl="+moduleID+"&chn="+val+"&t="+d.getTime();

        if(dashChartName){
            oDashChartExists = document.getElementById(\'is_dsbc\');
            oAddDashChart = document.getElementById(\'not_dsbc\');
            if(dashChartName == val){
                oDashChartExists.style.display = \'block\';
                oAddDashChart.style.display = \'none\';
            } else {
                oDashChartExists.style.display = \'none\';
                oAddDashChart.style.display = \'block\';
            }
        }
    }
</script>';

$content .= "<div class=\"sz2tabs\">\n";
$content .= "<div style=\"display:table\">\n";
$content .= "<form method=\"POST\" action=\"popChart.php?$qs\">\n";
$content .= "<select class=\"edt\" id=\"ChartName\" name=\"ChartName\" onchange=\"replaceChart(this.value);\" onkeyup=\"replaceChart(this.value);\">";
foreach($r as $rowID=>$row){ //$chartName => $chartTitle
    if(0 == $rowID){
        if(empty($selectedChartName)){
            $selectedChartName = $row['Name']; //show first chart of none specified
        }
    }
    if($selectedChartName == $row['Name']){
        $content .= "<option selected=\"true\" value=\"{$row['Name']}\">{$row['Title']} ({$row['Type']})</option>\n";
        $chartImgTag = "<img id=\"chartimg\" src=\"chartViewer.php?mdl=$ModuleID&chn={$row['Name']}\"/>\n";
    } else {
        $content .= "<option value=\"{$row['Name']}\">{$row['Title']} ({$row['Type']})</option>\n";
    }
}
$content .= "</select>";
$content .= "</form>";

$content .= $chartImgTag;
$content .= "</div>\n";
$content .= "</div>\n";

//save 
if('set' == $_GET['dashchart']){
    $r = $dbh->query('BEGIN');
    dbErrorCheck($r);

    //check if chart exists in table for this user (whether to insert or update)
    //select DashboardChartID from dsbcc where UserID, ChartName, conditions, and neither debcc and conditions are deleted
    $ChartName = dbQuote($_GET['chn']);

    $ConditionSQL = '';
    $ConditionCount = count($search->postData);
    if(0 < $ConditionCount){

        $Conditions = array();

        foreach($search->postData as $postKey => $postValue){
            if(is_array($postValue)){
                $postValue = join(',',$postValue);
            }
            $Conditions[] = "(dsbcc.ConditionField = '$postKey' AND dsbcc.ConditionValue = '$postValue')";
        }
        $ConditionSQL = " AND (\n";
        $ConditionSQL .= join("\n OR ", $Conditions);
        $ConditionSQL .= ') ';
    }

    $SQL = 
"SELECT
    dsbc.DashboardChartID
FROM
    dsbc
WHERE
    dsbc.UserID = {$User->PersonID}
    AND dsbc.ChartName = $ChartName
    AND dsbc.ModuleID = '$ModuleID'
    AND $ConditionCount = (
    SELECT Count(*)
    FROM dsbcc
    WHERE
        dsbc.DashboardChartID = dsbcc.DashboardChartID 
        AND dsbcc._Deleted = 0
        $ConditionSQL
    )";

    $existingChartID = $dbh->getOne($SQL);
    dbErrorCheck($existingChartID);

    //determine SortOrder: 
    switch($_GET['place']){
    case 'top':
        //   make it 1, and increment SortOrder for all others
        $SortOrder = 1;
        $SQL = "UPDATE dsbc SET SortOrder = SortOrder + 1 WHERE UserID = {$User->PersonID}";
        $r = $dbh->query($SQL);

        break;
    case 'bottom':
    default:
        // get the max current SortOrder and increment by one
        $SQL = "SELECT MAX(SortOrder) FROM dsbc WHERE UserID = {$User->PersonID}";
        $SortOrder = $dbh->getOne($SQL);
        dbErrorCheck($SortOrder);
        $SortOrder = intval($SortOrder) + 1;
        break;
    }

    //make search phrases
    if(count($search->phrases) > 0){
        $phrases = join('<br />', $search->phrases);
    } else {
        $phrases = gettext("All records");
    }

    //insert/update
    if(empty($existingChartID)){
        $SQL = "INSERT INTO dsbc (UserID, ChartName, ModuleID, SortOrder, ConditionPhrases, _ModBy, _ModDate) VALUES ({$User->PersonID}, $ChartName, '$ModuleID', $SortOrder, '$phrases', {$User->PersonID}, NOW())";

        $r = $dbh->query($SQL);
        dbErrorCheck($r);

        $ChartID = $dbh->getOne('SELECT LAST_INSERT_ID()');
        dbErrorCheck($ChartID);
    } else {
        $SQL = "UPDATE dsbc SET ConditionPhrases = '$phrases', _ModBy = {$User->PersonID}, _ModDate = NOW(), _Deleted = 0 WHERE DashboardChartID = $existingChartID";

        $r = $dbh->query($SQL);
        dbErrorCheck($r);

        $ChartID = $existingChartID;
    }

    //call search->saveChartConditions
    $search->saveChartConditions($User->PersonID, $ChartID);
    $r = $dbh->query('COMMIT');
    dbErrorCheck($r);

    //how to provide feedback that chart has been saved??

}

$content .= "<div class=\"searchFilter\"><b>Filter Conditions:</b><br />\n";
$content .= $search->getPhrases();
$content .= "<br />\n"; 
$content .= "</div><br />\n";

$moduleInfo = GetModuleInfo($ModuleID);

$title = $moduleInfo->getProperty('moduleName');

if($search->hasConditions()){
    if($search->isUserDefault){
        $title .= ' ['.gettext("default filter").'] ';
    } else {
        $title .= ' ['.gettext("custom filter").'] ';
    }
} else {
    $title .= ' ['.gettext("all").'] ';
}
$linkHere = "charts.php?mdl=$ModuleID";
$subtitle = gettext("Select one");
$screenPhrase = ShortPhrase($screenPhrase);
$moduleID = $ModuleID;
$chartToDashHTML = 
'<div class="ds">
<script lang="JavaScript">
    function showDashChartForm(){
        dc = document.getElementById(\'dashChartForm\');
        dcPlace = document.getElementById(\'dashChartPlace\');
        dc.style.display = \'block\';
        dcPlace.focus();
    }
    function hideDashChartForm(){
        dc = document.getElementById(\'dashChartForm\');
        dc.style.display = \'none\';
    }
    function saveDashChart(){

        dcSelect = document.getElementById(\'ChartName\');
        dcPlace = document.getElementById(\'dashChartPlace\');
        theLink = "'.$linkHere.'"+\'&dashchart=set&place=\'+dcPlace.value+\'&chn=\'+dcSelect.value;
        document.location = theLink;
    }
</script>';
if($use_dsbc){
    $chartToDashHTML .= '<div id="is_dsbc">'.gettext("This chart is on your dashboard").'</div>';
    $hide_add_dashchart = ' style="display:none"';
} else {
    $hide_add_dashchart = '';
}
$chartToDashHTML .= '<div id="not_dsbc"'.$hide_add_dashchart.'><a href="javascript:showDashChartForm()" title="'.gettext("Click here to add this chart to your dashboard.").'">'.gettext("Add this chart to your dashboard").'</a></div>
<div id="dashChartForm" style="display:none">
    Place: <select id="dashChartPlace" name="dashChartPlace" class="edt">
    <option value="top">Top</option>
    <option value="bottom">Bottom</option>
    </select>
    <input type="button" onclick="saveDashChart()" value="Save" class="btn" />
    <input type="button" onclick="hideDashChartForm()" value="Cancel" class="btn" />
</div>
</div>';

$moduleInfo = GetModuleInfo($ModuleID);

$globalDiscussions = DISCUSSION_LINK_GLOBAL . $moduleInfo->getProperty('globalDiscussionAddress');;
$localDiscussions = DISCUSSION_LINK_LOCAL . $moduleInfo->getProperty('localDiscussionAddress');;
//$recordID;
//$messages; //any error messages, acknowledgements etc.
//$content;

include_once $theme . '/search.template.php';
?>