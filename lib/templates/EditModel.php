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

/**CUSTOM_CODE|classdef**/

//list of objects containing the field information
/**fields**/
/**hasEditableFields**/

$singularRecordName = gettext("/**singular_record_name**/");

//field value array
$data = array(
        /**data**/
    );
foreach($data as $fieldName=>$value){
    $data[$fieldName] = $_POST[$fieldName];
}



//list of grids
/**GRIDS|DEFINE**/

    /**guidanceGrid**/

    /**PKField**/
    /**ownerField**/

    /**disbleGlobalModules**/

    //handle any posted grid form
/**GRIDS|SAVE**/

    $tabsQSargs = $qsArgs;
    unset($tabsQSargs['scr']);
    unset($tabsQSargs['gid']);
    unset($tabsQSargs['grw']);
    $tabsQS = MakeQS($tabsQSargs);
    $nextScreen = "/**nextScreen**/";
    $nextlink = "edit.php?$tabsQS&scr=$nextScreen";
    $form_enctype = '';

    /**CUSTOM_CODE|init**/

    $getSQL = "/**SQL|GET**/";


    /*populates screen messages differently depending on whether the record exists in db or not*/
    /**check_empty_recordID**/
        $existing = true;

        $pageTitle = gettext("/**singular_record_name**/");
        $screenPhrase = gettext("/**screen_phrase**/");

        /**CUSTOM_CODE|before_get**/

        //retrieve record
        /**SQL|GET_BEGIN**/
        $r = $dbh->getAll(str_replace('/**RecordID**/', $recordID, $getSQL), DB_FETCHMODE_ASSOC);
        dbErrorCheck($r);
        /**SQL|GET_END**/
        
        if(count($r) > 0) {

            //populate data array, combining POSTed data with DB record:
            //POST data takes precedence
            foreach($r[0] as $fieldName=>$dbValue){
                //(checking for gridnum avoids interference with any posted edit grid)
                if (empty($_POST['gridnum']) && isset($_POST[$fieldName])){
                    $data[$fieldName] = $_POST[$fieldName];
                } else {
                    $data[$fieldName] = $dbValue;
                }
            }

        } else {

            $messages[] = array('e', gettext("ERROR: Empty query result."));

        }

        /**CUSTOM_CODE|get**/
    } else {
        //inserting a record
        $existing = false;
        $pageTitle = gettext("/**module_name**/");
        /**CUSTOM_CODE|new**/
    }

    //check if user has permission to edit record
    $allowEdit = $User->CheckEditScreenPermission();
    //if not, it terminates and display error msg.


    //phrases for field names, in field order
    /**phrases**/

    //if the form was posted by clicking the Save button
    if(!empty($_POST['Save'])){
        /**DB_SAVE_BEGIN**/

        /**CUSTOM_CODE|save**/

        //validate submitted data:
        $vMsgs = "";
        /**VALIDATE_FORM**/


        if(0 != strlen($vMsgs)){
            //prepend a general error message
            $vMsgs = gettext("The record has not been saved, because:")."\n".$vMsgs;
            $vMsgs = nl2br($vMsgs);

            //return error messages
            $messages[] = array('e', $vMsgs);

        } else {
        
            /**CUSTOM_CODE|check_deleted_row_exists**/

            $dh = GetDataHandler($ModuleID);
            $recordID = $dh->saveRow($data, $recordID);
            
            //recreate $nextlink b/c of new record ID when inserting
            if(!$existing){
                $qsArgs['rid'] = $recordID; //pass both to tabs and other links
                $tabsQSargs = $qsArgs;
                unset($tabsQSargs['scr']);
                //$tabsQSargs['rid'] = $recordID;
                $tabsQS = MakeQS($tabsQSargs);
                $nextlink = "edit.php?$tabsQS&scr=$nextScreen";

                $existing = true;
            }
        }

        /**CUSTOM_CODE|save_end**/
        /**DB_SAVE_END**/

        /**RE-GET_BEGIN**/
        //only executed on screens that need it: have ViewField with Update, or Calculated/Summary fields
        $r = $dbh->getAll(str_replace('/**RecordID**/', $recordID, $getSQL), DB_FETCHMODE_ASSOC);
        dbErrorCheck($r);
        if(count($r) > 0) {
            foreach($r[0] as $fieldName=>$dbValue){
                //(checking for gridnum avoids interference with any posted edit grid)
                if (empty($_POST['gridnum']) && isset($_POST[$fieldName])){
                    $data[$fieldName] = $_POST[$fieldName];
                } else {
                    $data[$fieldName] = $dbValue;
                }
            }
        } else {
            $messages[] = array('e', gettext("Error: Empty query result."));
        }
        /**RE-GET_END**/
       
        //note: assumes all messages up til this point were errors
        if (count($messages) == 0){
            //add success message
            if ($existing){
                $messages[] = array('m', gettext("The record was updated successfully."));
            } else {
                $messages[] = array('m', gettext("The record was added successfully."));
            }
        }
    }
    /**SQL|DELETE_BEGIN**/
    if(!empty($_POST['Delete'])){

        $dh = GetDataHandler($ModuleID);
        $result = $dh->deleteRow($recordID);

        $deletelink = "list.php?$tabsQS";
        
        //redirect to list screen
        header("Location:" . $deletelink);
        exit;
        
    }
    /**SQL|DELETE_END**/
    
    /**CUSTOM_CODE|after_save**/

    //moved down from above
    $qs = MakeQS($qsArgs);

    //List tab
    $tabs['List'] = Array("list.php?$tabsQS", gettext("List|View the list of /**plural_record_name**/"));

    //setting up tabs:

    //target for FORMs
    $targetlink = "edit.php?$qs";

    //formatting that depends on whether the record exists or not
    if($existing){
        //delete button only appears on the first EditScreen.
        $deletelink = "/**deletelink**/";
        //link for Cancel button
        $cancellink = "view.php?$tabsQS";

        /**tabs|EDIT**/

    } else {
        $deletelink = "";
        //link for Cancel button
        $cancellink = "list.php?$tabsQS";

        /**tabs|ADD**/

    }

    /**CUSTOM_CODE|form**/

    $content = '';
    foreach($fields as $key => $field){
        if (!$field->isSubField()){
            $content .= $field->render($data, $phrases);
        }
    }

    $content = renderForm($content, $targetlink, $deletelink, $cancellink, $nextScreen, $form_enctype, $ModuleID, $hasEditableFields);

    //insert code to enable calendar controls
    /**dateFields**/
    
    /**CUSTOM_CODE|after_form**/

    //display edit grids here
/**GRIDS|DISPLAY**/

    /**CUSTOM_CODE|after_grids**/
    
    /**RecordLabelField**/
?>