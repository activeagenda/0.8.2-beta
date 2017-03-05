<?php
/**
 * Handles display of global view grids on View screens
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

if(!$disableGlobalModules){
    //file: globalViewGrids.php
    //renders global ViewGrids in a View screen
    $globalModules = array('act','att','cos','lnk','nts');
    if(!in_array($ModuleID, $globalModules)){
        $content .= '<h1>'.gettext("Global").'</h1>';
        $grids = array();

        foreach($globalModules as $gmID){
            include_once(GENERATED_PATH."/{$gmID}/{$gmID}_GlobalViewGrid.gen");

            if(isset($grid)){
                //insert dynamic data
                $replFields = array('/**DynamicModuleID**/', '/**PR-ID**/');
                //$replValues = array($ModuleID, "'".$recordID."'");
                $replValues = array($ModuleID, $recordID);
                //$replValues = array($ModuleID, $recordID);
                $grid->ParentRowSQL = str_replace($replFields, $replValues, $grid->ParentRowSQL);
                $grids[] =& $grid;
                unset($grid);
            }
        }
        $fields = null;
        $phrases = null;
        $SQL = '';
        $content .= renderViewScreenSection($fields, $phrases, $SQL, $grids);
    }
}
?>