<?php
/**
 *  Template file for generated files (alt. a generated file)
 *
 *  PHP version 4
 *
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
 *
 * @author         Mattias Thorslund <mthorslund@activeagenda.net>
 * @copyright      2003-2007 Active Agenda Inc.
 * @license        http://www.activeagenda.net/license  RPL 1.4
 * @version        SVN: $Revision: 499 $
 * @last-modified  SVN: $Date: 2007-02-16 13:43:40 -0800 (Fri, 16 Feb 2007) $
 */

    /**CUSTOM_CODE|init**/

    //list of objects containing the field information
    /**fields**/

    //list of grids
    /**grids**/

    //phrases for field names, in field order
    /**phrases**/

    /**ownerField**/
    
    /**disbleGlobalModules**/
    /**useBestPractices**/

    //retrieve record
    $data = array();
    
    /**SQL|GET_BEGIN**/
    $SQL = "/**SQL|GET**/";

    /**CUSTOM_CODE|before_get**/

    //get data
    $r = $dbh->getAll(str_replace('/**RecordID**/', $recordID, $SQL), DB_FETCHMODE_ASSOC);
    dbErrorCheck($r);

    
    /**SQL|GET_END**/
    //need to repeat for each getSQL........
    $data = array_merge($data, $r[0]); //assign first (only) row
    
    /**CUSTOM_CODE|get**/
    
    //check if user has permission to view or edit record - will redirect if no permission at all
    $allowEdit = $User->CheckViewScreenPermission();
    //$allowEdit = true;

    $tabsQSargs = $qsArgs;
    unset($tabsQSargs['scr']);
    $tabsQS = MakeQS($tabsQSargs);

    //List tab
    $tabs['List'] = Array("list.php?$qs", gettext("List|View the list of /**plural_record_name**/"));

    if ($allowEdit){
        /**tabs|EDIT**/
    } else {
        /**tabs|VIEW**/
    }
    
    /**RecordLabelField**/

    $content = '';
    foreach($fields as $key => $field){
        if (!$field->isSubField()){
            $content .= $field->render($data, $phrases);
        }
    }

    $pageTitle = gettext("/**singular_record_name**/");
    $screenPhrase = gettext("/**screen_phrase**/");

    $backlink = "list.php?$qs";

    $nextScreen = "/**nextScreen**/";
    $editlink = "edit.php?$tabsQS&scr=$nextScreen";
    $content = renderViewTable($content, $allowEdit, $backlink, $editlink);

    //add byline info on record modification info
    $content .= "<div class=\"recinfo\">{$phrases['_ModDate']} {$data['_ModDate']}";
    if ($User->IsAdmin) {
        $content .= '&nbsp; &nbsp; <a href="audit.php?mdl='.$ModuleID.'&rid='.$recordID.'">'.gettext("Audit Trail").'</a> ';
    }
    $content .= "</div>";

    //display view grids here
/**VIEWGRIDS**/

//View Screen Sections here
/**VIEWSCREENSECTIONS**/
?>
