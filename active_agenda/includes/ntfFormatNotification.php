<?php
/**
 * Custom function to format a notification into text and HTML
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

function ntf_formatNotification($relModuleID, $relRecordID){
    global $User;
    global $dbh;
    global $recordID;

    $textContent = '';
    $HTMLContent = '';

    //these could become template snips or something
    $HTMLTemplate = '<html>
<head><title>%s</title></head>
<style>%s</style>
<body><div id="aa_body"><div id="aa_content"><br />%s</div></div></body>
</html>';
    $HTMLTableTemplate = '   <table class="aa_fields">
        %s
    </table>'; //for section fields
    $HTMLGridTemplate = '<div class="aa_grid">
    <div class="aa_gridtitle">%1$s</div>
    <table class="aa_grid" cellpadding="2" cellspacing="1">%2$s</table>
</div>';

    $filename = GENERATED_PATH . "/{$relModuleID}/{$relModuleID}_ViewSer.gen";

    //check for cached page for this module
    if (!file_exists($filename)){
        die(gettext("Could not find file:") . "'$filename'.");
    }

    //the included file sets $phrases and $sections
    include $filename;

    /*adding global modules as a separate section*/
    $globalModules = array('act','att','cos','lnk','nts');
    $globalSection = array();
    $globalSection['phrase'] = gettext("Global");
    $globalGrids = array();

    foreach($globalModules as $gmID){
        include_once GENERATED_PATH . "/{$gmID}/{$gmID}_GlobalViewGrid.gen";

        if(isset($grid)){
            $globalGrids[] =& $grid;
            unset($grid);
        }
    }
    $globalSection['grids'] =& $globalGrids;
    $sections[] =& $globalSection; 

    /*retrive data and format Text and HTML*/
    $data = array();

    foreach($sections as $sectionID => $section){
        if(!empty($section['phrase'])){
            $phrase = $section['phrase'].':';
            $textContent .= $phrase."\n";
            $textContent .= str_pad('', strlen($phrase), '=')."\n";

            $HTMLContent .= '<h1 class="aa">'.$phrase.'</h1>';
        }
        //$textContent .= "\n";
        if(count($section['fields']) > 0){
            $textArray = array();
            $HTMLFieldContent = '';

            $SQL = $section['sql'];
            $SQL = str_replace('$recordID', $relRecordID, $SQL);

            $row = $dbh->getRow($SQL, DB_FETCHMODE_ASSOC);
            dbErrorCheck($row);
            $data = array_merge($data, $row);
//print debug_r($data);
            foreach($section['fields'] as $screenFieldName => $screenField){
                $textArray[] = array(
                    shortPhrase($phrases[$sectionID][$screenFieldName]),
                    wordwrap(strip_tags($screenField->viewRender($data)), 60)
                );

                $HTMLFieldContent .= $screenField->render($data, $phrases[$sectionID]);
            }

            $textTable =& new TextTable($textArray);
            $textContent .= $textTable->render() . "\n";

            $HTMLContent .= sprintf($HTMLTableTemplate, $HTMLFieldContent);
        }
        if(count($section['grids']) > 0){
            foreach($section['grids'] as $grid){
                $textContent .= $grid->renderText() . "\n";
              //  $HTMLContent .= sprintf($HTMLGridTemplate, $grid->render('#', array()));
                $HTMLContent .= $grid->renderEmail();
            }
        }
    }


    //retrieve Message, etc from Notification
    $SQL = "SELECT Message FROM ntf WHERE NotificationID = '$recordID'";
    $row = $dbh->getRow($SQL, DB_FETCHMODE_ASSOC);
    dbErrorCheck($row);

    $message = $row['Message'];


    //put together HTML and Text content
    $HTMLMessage = nl2br($message);
    $theme = GetThemeLocation();
    $styles = file_get_contents ($theme . '/email.css');
    $HTMLContent = $HTMLMessage . $HTMLContent; //prepend message
    $HTMLContent =  sprintf($HTMLTemplate, 'title', $styles, $HTMLContent);
    $textContent =  $message . "\n\n" . $textContent;

    return array($textContent, $HTMLContent);
}
?>