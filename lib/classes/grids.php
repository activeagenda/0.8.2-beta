<?php
/**
 *  Renderable grid classes
 *
 *  This file contains class definitions for grids, i.e. components that 
 *  provide a multi-record user interface to data in a (sub)module.
 *
 *  PHP version 4
 *
 *
 *  LICENSE NOTE:
 *
 *  Copyright  2003-2007 Active Agenda Inc., All Rights Reserved.
 *
 *  Unless explicitly acquired and licensed from Licensor under a "commercial license",
 *  the contents of this file are subject to the Reciprocal Public License ("RPL")
 *  Version 1.4, or subsequent versions as allowed by the RPL,and You may not copy
 *  or use this file in either source code or executable form, except in compliance
 *  with the terms and conditions of the RPL. You may obtain a copy of the RPL from
 *  Active Agenda Inc. at http://www.activeagenda.net/license.
 *
 *  All software distributed under the Licenses is provided strictly on an "AS IS"
 *  basis, WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESS OR IMPLIED, AND ACTIVE AGENDA
 *  INC. HEREBY DISCLAIMS ALL SUCH WARRANTIES, INCLUDING WITHOUT LIMITATION, ANY 
 *  WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, QUIET ENJOYMENT,
 *  OR NON-INFRINGEMENT. See the Licenses for specific language governing rights and
 *  limitations under the Licenses.
 *
 *
 * @author         Mattias Thorslund <mthorslund@activeagenda.net>
 * @copyright      2003-2007 Active Agenda Inc.
 * @license        http://www.activeagenda.net/license  RPL 1.4
 * @version        SVN: $Revision: 502 $
 * @last-modified  SVN: $Date: 2007-02-17 01:03:38 -0800 (Sat, 17 Feb 2007) $
 */



/**
 * Include the renderable field classes
 */
include_once CLASSES_PATH . '/components.php';



/**
 * abstract class, for properties and methods common to all grids
 */
class Grid extends ScreenControl
{
var $moduleID; //ModuleID of related sub-module
var $phrase;
var $number; //sequence number on the parent screen
var $Fields = array(); //list of fields
var $countSQL; //SELECT COUNT (*)
var $listSQL;
var $listExtended = false;
var $ParentRowSQL; //compiled SQL snip identifying the parent record
var $localKey; //name of field in the grid that corresponds with the parent record
var $parentKey; //name of key field in parent record
var $PKFields;
var $formatOptions = array(); //catch-all array for formatting modifications
var $fieldTypes = array(); //data types of select fields

/**
 * Abstract factory method
 *
 * usage: parse-time
 */
function Factory($element, $moduleID)
{
    return false;
}


/**
 * Returns a documentation object that has the right data for a grid
 *
 * usage: parse-time
 */
function DocFactory($element, $moduleID)
{
    return new GridDoc($element, $moduleID);
}


/**
 * overridden for editable grids
 *
 * usage: parse-time
 */
function isEditable()
{
    return false;
}


/**
 * adds a field (descendant of ScreenField) to the grid
 *
 * usage: parse-time
 */
function AddField($field)
{
    $this->Fields[$field->name] = $field;
}


/**
 * display the grid (abstract)
 *
 * usage: run-time
 */
function render()
{
    return $this->phrase . " grid";
}

function prepareCountSQL()
{
   // global $User;
    global $data;
    global $recordID;

    $countSQL = $this->countSQL;

    //filter out records where user has no permission 
    //NEW: ListData object does this...
    //$countSQL .= $User->getListFilterSQL($this->moduleID, false);

    //replace field value placeholders
    $countSQL = PopulateValues($countSQL, &$data);
    $countSQL = str_replace('/**RecordID**/', $recordID, $countSQL);
//print debug_r($countSQL, $this->moduleID. ' countSQL');
    return $countSQL;
}


/**
 * populates the listSQL statement with values from the parent record
 *
 * usage: run-time
 */
function prepareListSQL()
{
    global $data;
    //global $User;
    global $ModuleID;
    //global $orderBy;
    //global $prevOrderBy;
    global $recordID;

    $listSQL = $this->listSQL;

    //filter out records where user has no permission
    //NEW: ListData object does this...
    //$listSQL .= $User->getListFilterSQL($this->moduleID, false);
    /*if(empty($orderBy)){
        $orderBy = $this->orderBy;
    }
    if(!empty($orderBy)){
        $listSQL .= "\n ORDER BY $orderBy ";
        if($orderBy == $prevOrderBy){
            $listSQL .= " DESC\n";
        } else {
            $listSQL .= " ASC\n";
        }
    }*/
    
    //replace field value placeholders
    $listSQL = PopulateValues($listSQL, &$data);
    $listSQL = str_replace('/**RecordID**/', $recordID, $listSQL);
//print debug_r($listSQL, $this->moduleID. ' listSQL');
    return $listSQL;
}

function getRecordCount()
{
    global $dbh;
    $countSQL = $this->prepareCountSQL();
    $r = $dbh->getOne($countSQL);
    if (DB::isError($r)) {
        return 'error';
    }
    return intval($r);
}

function setUpFieldTypes(&$moduleFields){
    foreach(array_keys($this->Fields) as $fieldName){
        $mf = $moduleFields[$fieldName];
        $this->fieldTypes[$fieldName] = $mf->dataType;
        $this->fieldAlign[$fieldName] = $mf->getGridAlign();
    }
}


} //end class Grid



/**
 *  A class that provides a view-only HTML table of submodule data
 */
class ViewGrid extends Grid
{
var $orderByFields = array();
var $isInfo = false;
var $isGuidance = false;
var $isVertical = false;
var $verticalFormats = array();

function &Factory($element, $moduleID)
{
    return new ViewGrid($element, $moduleID);
}


function ViewGrid($element, $moduleID)
{
    $subModuleID = $element->attributes['moduleID'];
    if('yes' == strtolower($element->attributes['isDataView'])){
        $this->isDataView = true;
    }
    print "moduleID = $moduleID\n";

    $debug_prefix = debug_indent("ViewGrid [constructor] $subModuleID:");

    //when building Global Grids, there's no parent module 
    if(1 == $element->attributes['isGlobalGrid']){
        $subModule = GetModule($subModuleID);
        if(1 == $element->attributes['isGlobalGridWithConditions']){
            $localKey = '';
        } elseif(1 == $element->attributes['hasNoParentRecordID']){
            $localKey = '';
            $conditions = array(
                'RelatedModuleID' => '/**DynamicModuleID**/'
            );
        } else {
            $localKey = 'RelatedRecordID';
            $conditions = array(
                'RelatedModuleID' => '/**DynamicModuleID**/',
                'RelatedRecordID' => '/**RecordID**/'
            );
        }
    } else {
        $module = GetModule($moduleID);
        $subModule = $module->SubModules[$subModuleID];
        if(empty($subModule)){
            die("$debug_prefix could not find a submodule that matches moduleID '$subModuleID'");
        }
        $localKey = $subModule->localKey;
        $conditions = null; //$subModule->conditions;
    }

    if(!is_object($subModule)){
        die("$debug_prefix Could not retrieve submodule '$subModuleID'");
    }

    //check for fields in the element: if there are none, we will import from the Exports section of the sub-module
    if((count($element->c) == 0) || 'yes' == $element->attributes['import']){

        $exports_element = $subModule->_map->selectFirstElement('Exports');
        if(empty($exports_element)){
            die("$debug_prefix Can't find an Exports section in the $subModuleID module.");
        }

        $grid_element = $exports_element->selectFirstElement('ViewGrid');
        if(empty($grid_element)){
            die("$debug_prefix Can't find a matching view grid in the $subModuleID module.");
        }

        //copy all the fields of the imported grid to the current element
        $element->c = array_merge($element->c, $grid_element->c);

        //copy attributes but allow existing attributes to override
        foreach($grid_element->attributes as $attrName => $attrValue){
            if(empty($element->attributes[$attrName])){
                $element->attributes[$attrName] = $attrValue;
            }
        }
    }

    $this->moduleID = $subModuleID;
    $this->phrase = $element->attributes['phrase'];

    if(strlen($element->attributes['listExtended']) > 0){
        $this->listExtended = true;
    }
    if(!empty($conditions)){
        $this->conditions = $conditions;
    }
    $this->localKey = $localKey;

    if('yes' == strtolower($element->attributes['isGuidance'])){
        $this->isGuidance = true;
        $this->formatOptions['suppressTitle'] = true;
        $this->formatOptions['suppressRecordIcons'] = true;
    }
    if('yes' == strtolower($element->attributes['isInfo'])){
        $this->isInfo = true;
        $this->formatOptions['infoTitle'] = true;
        $this->formatOptions['suppressRecordIcons'] = true;
    }
    if('yes' == strtolower($element->attributes['verticalDisplay'])){
        $this->isVertical = true;
        $this->formatOptions['suppressTitle'] = true;
    }
    if($this->listExtended){
        $this->formatOptions['suppressRecordIcons'] = true;
    }

    //append fields
    if(count($element->c) > 0){
        foreach($element->c as $sub_element){
            $type = str_replace('Grid', '', $sub_element->type);

            if('OrderByField' == $sub_element->type){
                //add invisible field if not in the selects already
                if(!isset($this->Fields[$sub_element->name])){
                    //make invisiblefield element
                    $invisibleField_element = new Element($sub_element->name, 'InvisibleField', array('name' => $sub_element->name));
                    $field_object = $invisibleField_element->createObject($subModuleID, null, $grid);
                    $this->AddField($field_object);
                    unset($field_object);
                }
                //add to $this->orderByFields
                $this->orderByFields[$sub_element->name] = $sub_element->attributes['direction'];
            } elseif('VerticalFormat' == $sub_element->type) {
                if(count($sub_element->c) > 0){
                    foreach($sub_element->c as $vformat_element){
                        $this->verticalFormats[$vformat_element->name] = $vformat_element->type;
                    }
                }
            } elseif('Conditions' == $sub_element->type) {
                if(count($sub_element->c) > 0){
                    foreach($sub_element->c as $condition_element){
                        if('Condition' == $condition_element->type){
                            $this->conditions[$condition_element->attributes['field']] = $condition_element->attributes['value'];
                        }
                    }
                }
            } else {
                $sub_element->attributes['formName'] = $subModuleID;
    
                $field_object = $sub_element->createObject($subModuleID, $type, $grid);
                if(empty($sub_element->attributes['phrase'])){
                    $field_object->phrase = $subModule->ModuleFields[$field_object->name]->phrase;
                }
    
                $this->AddField($field_object);
                unset($field_object);
            }
        }
    }

    $this->listSQL  = $subModule->generateListSQL(&$this);
    $this->countSQL = $subModule->generateListCountSQL(&$this);
    $this->setUpFieldTypes($subModule->ModuleFields);

    $createFileName = $moduleID.'_'.$subModuleID.'ViewGrid.gen';
    $modelFileName = 'CustomModel.php';
    $createFilePath = GENERATED_PATH ."/$moduleID/$createFileName";
    $replaceValues = array('/**custom**/' => '$grid = unserialize(\''.escapeSerialize($this).'\');');
    SaveGeneratedFile($modelFileName, $createFileName, $replaceValues, $moduleID);
    debug_unindent();
}


function AddField($field)
{
    if(!$field->isEditable()){
        $this->Fields[$field->name] = $field;
        //print_r($field);
    } else {
        die("cannot add an editable field to a ViewGrid\n");
    }
}


function render($page, $qsArgs)
{
    require_once CLASSES_PATH . '/lists.php';
    global $dbh;
    global $User;
    global $theme_web;
    global $recordID;

    //print debug_r($qsArgs);

    //check whether user has permission at all
    if(! $User->PermissionToView($this->moduleID) ){
        $moduleInfo = GetModuleInfo($this->moduleID);
        $moduleName = $moduleInfo->getProperty('moduleName');

        if($this->formatOptions['suppressTitle']){
            $gridTitle = '';
        } else {
            $gridTitle = sprintf(
                GRID_TAB,
                $this->phrase,
                '#',
                '#',
                '' //count
            );
        }

        //format grid table
        $content = sprintf(
            VIEWGRID_MAIN,
            $gridTitle,
            sprintf(gettext("You have no permissions to view records in the %s module."), $moduleName) . "<br />\n"
            );

        return $content;
    }

    if($this->formatOptions['suppressPaging']){
        $perPage = -1;
    } else {
        if(!defined('IS_RPC') || !IS_RPC){
            $perPage = 10;
        } else {
            $perPage = intval($_GET['pp']);
        }
    }

    $listSQL = $this->prepareListSQL();
    $listData =& new ListData(
        $this->moduleID,
        $listSQL,
        $perPage,
        $this->fieldTypes,
        false,
        $this->prepareCountSQL()
        );
    $nRows = $listData->getCount();


    if(!$this->formatOptions['suppressTitle'] && (!defined('IS_RPC') || !IS_RPC)){
        if(empty($nRows)){
            $count = '';
        } else {
            $count = '('.$nRows.')';
        }
        if(!$this->formatOptions['infoTitle']){
            $gridTitle = sprintf(
                GRID_TAB,
                $this->phrase,
                'list.php?mdl='.$this->moduleID,
                'frames_popup.php?dest=list&mdl='.$this->moduleID,
                $count
                );
        } else {
            $gridTitle = sprintf(
                GRID_TAB_NOLINKS,
                $this->phrase,
                $count
                );
        }
    } else {
        $gridTitle = '';
    }


    if($this->isVertical){
        $areaContent = '';
        $firstRecord = true;
        foreach($listData->getData() as $rowNum => $row){
            if($firstRecord){
                $firstRecord = false;
            } else {
                $areaContent .= '<hr class="vgrid_record_separator" />';
            }
            $rowContent = '<div class="vgrid_record">';
            foreach($this->Fields as $field){
                if($field->isVisible() && empty($field->parentName)){
                    if(array_key_exists($field->name, $this->verticalFormats)){
                        switch ($this->verticalFormats[$field->name]){
                        case 'LogoField':
                            $formatStr = '<div class="vgrid_logo">%s</div>';
                            break;
                        case 'TitleField':
                            $formatStr = '<h1 class="vgrid_title">%s</h1>';
                            break;
                        case 'FeatureField':
                            $formatStr = '<span class="vgrid_feature">%s</span><br />';
                            break;
                        default:
                            break;
                        }
                        $rowContent .= sprintf($formatStr, $field->simpleRender(&$row));


                    } else {
                        //normal fields
                        $formatStr = "<i>%s</i>: %s<br />";
                        $rowContent .= sprintf(
                            $formatStr,
                            ShortPhrase($field->phrase),
                            $row[$field->name]
                            );
                    }
                }
            }
            $rowContent .= '</div>';
            $areaContent .= $rowContent;

        }
        $content = sprintf('<div class="vgrid_main">%s</div>', $areaContent);
    } else {

        if(0 == $nRows){
            return sprintf(VIEWGRID_NONE, $gridTitle);
        }
        $headerPhrases = array();
        $fieldAlign = array();
        $linkFields = array();
        foreach($this->Fields as $fieldName => $field){
            if($field->isVisible() && empty($field->parentName)){
                $headerPhrases[$fieldName] = $field->gridHeaderPhrase();
                $fieldAlign[$fieldName] = $field->gridAlign;
                if(!empty($field->linkField)){
                    $linkFields[$fieldName] = $field->linkField;
                }
            }
        }

        if(!defined('IS_RPC') || !IS_RPC) {
            $startRow = 0;
        } else {
            $startRow = intval($qsArgs['sr']);
        }
        $renderer =& new ListRenderer(
            $this->moduleID,
            $listData,
            $headerPhrases,
            'frames_popup.php?dest=view&',
            'rpc/gridList.php?grt=view&smd='.$this->moduleID.'&',
            $fieldAlign,
            'view',
            $linkFields,
            $this->formatOptions
        );
        $renderer->useBestPractices = false;
        $content = $renderer->render($startRow, $defaultOrderBys);
//$content .= debug_r($this);
        if(!defined('IS_RPC') || !IS_RPC) {
            $content = '<div id="list_'.$this->moduleID.'">'.$content.'</div>';
            $content = sprintf(
                VIEWGRID_MAIN,
                $gridTitle,
                $content
            );
        }
    }
//print $content . "pre<br/>";
    return $content;
}

function old_render($page, $qsArgs)
{
//global $recordID;
    //$start_time = getMicroTime();
    global $dbh;
    global $User;
    global $theme_web;

    //check whether user has permission at all
    if(! $User->PermissionToView($this->moduleID) ){
        $moduleInfo = GetModuleInfo($this->moduleID);
        $moduleName = $moduleInfo->getProperty('moduleName');

        if(!$this->formatOptions['suppressTitle']){
            $gridTitle = sprintf(
                GRID_TAB,
                $this->phrase,
                '#',
                '#',
                '' //count
            );
        } else {
            $gridTitle = '';
        }

        //format grid table
        $content = sprintf(
            VIEWGRID_MAIN,
            $gridTitle,
            sprintf(gettext("You have no permissions to view records in the %s module."), $moduleName) . "<br />\n"
            );

        return $content;
    }

    //capture order by parameter
    $orderBy = $qsArgs['ob'.$this->number];
    $prevOrderBy = $qsArgs['pob'.$this->number];

    //making sure ob and pob fields exist in grid:
    if(!in_array($orderBy, array_keys($this->Fields))){
        $orderBy = '';
    }
    if(!in_array($prevOrderBy, array_keys($this->Fields))){
        $prevOrderBy = '';
    }

    unset($qsArgs['ob'.$this->number]);
    unset($qsArgs['pob'.$this->number]);
    $qs = MakeQS($qsArgs);

    $listSQL = $this->prepareListSQL();
//echo debug_r($listSQL);

    //setting up link for the next pob
    if(!empty($orderBy)){ 
        $prevOBString = '&pob'.$this->number.'='.$orderBy;
    } else {
        $prevOBString = '';
        if(count($this->orderByFields) > 0){
            $listSQL .= "\nORDER BY ";
            $onFirst = true;
            //print debug_r($this->orderByFields);
            foreach($this->orderByFields AS $fieldname => $direction){
                if($onFirst){
                    $listSQL .= "\n $fieldname";
                    $onfirst = false;
                } else {
                    $listSQL .= ",\n $fieldname";
                }
                if(!empty($direction)){
                    $listSQL .= ' '.$direction;
                }
            }
        }
    }

    if(!$this->isVertical){
        //grid headers
        $content = '';
        foreach($this->Fields as $FieldName => $Field){
            //if('InvisibleField' != $Field->getType() && empty($Field->parentName)){
            if($Field->isVisible() && empty($Field->parentName)){
                if($prevOrderBy != $FieldName){
                    $fPrevOBString = $prevOBString;
                } else {
                    $fPrevOBString = '';
                }
                $content .= sprintf(
                    GRID_HEADER_CELL,
                    //'view.php?'.$qs.'&ob'.$this->number.'='.$FieldName.$fPrevOBString,
                    $page .'?'.$qs.'&ob'.$this->number.'='.$FieldName.$fPrevOBString,
                    $Field->gridHeaderPhrase()
                );
            }
        }

        //format header row
        $content = sprintf(
            GRID_HEADER_ROW,
            $content
        );
    }

//print debug_r($listSQL);
    $start_sql = getMicroTime();
    //get data
    $r = $dbh->getAll($listSQL, DB_FETCHMODE_ASSOC);
    if(!dbErrorCheck($r, false)){
        trigger_error("SQL of previous error: \n".$listSQL, E_USER_WARNING);
        return "Could not display {$this->phrase}. <br />";
    }

    $end_sql = getMicroTime();
    $duration = round($end_sql - $start_sql, 2);
    //print "ViewGrid sql ({$this->moduleID}) took $duration seconds<br />\n";

    if(count($r) > 0){
        if($this->isVertical){
            $areaContent = '';
            $firstRecord = true;
            foreach($r as $rowNum => $row){
                if($firstRecord){
                    $firstRecord = false;
                } else {
                    $areaContent .= '<hr class="vgrid_record_separator" />';
                }
                $rowContent = '<div class="vgrid_record">';
                foreach($this->Fields as $field){
                    if($field->isVisible() && empty($field->parentName)){
                        if(array_key_exists($field->name, $this->verticalFormats)){
                            switch ($this->verticalFormats[$field->name]){
                            case 'LogoField':
                                $formatStr = '<div class="vgrid_logo">%s</div>';
                                break;
                            case 'TitleField':
                                $formatStr = '<h1 class="vgrid_title">%s</h1>';
                                break;
                            case 'FeatureField':
                                $formatStr = '<span class="vgrid_feature">%s</span><br />';
                                break;
                            default:
                                break;
                            }
                            $rowContent .= sprintf($formatStr, $field->simpleRender(&$row));


                        } else {
                            //normal fields
                            $formatStr = "<i>%s</i>: %s<br />";
                            $rowContent .= sprintf(
                                $formatStr,
                                ShortPhrase($field->phrase),
                                $row[$field->name]
                                );
                        }
                    }
                }
                $rowContent .= '</div>';
                $areaContent .= $rowContent;

            }
            $content = sprintf('<div class="vgrid_main">%s</div>', $areaContent);
        } else {
            //alternating row background colors
            $tdFormatting = array("l", "l2");

            //display rows
            foreach($r as $rowNum => $row){
                $tdClass = $tdFormatting[$rowNum % 2];
                $rowContent = '';
                $fldpos = 0;

                foreach($this->Fields as $field){
                    //if('InvisibleField' != $field->getType() && empty($field->parentName)){
                    if($field->isVisible() && empty($field->parentName)){
                        //$rowContent .= sprintf(GRID_VIEW_CELL, "left",  $tdClass, $row[$fldpos]);
                        $rowContent .= sprintf(
                            GRID_VIEW_CELL,
                            $field->gridAlign, //'left',
                            $tdClass,
                            $field->gridViewRender(&$row)
                        );
                    }
                    $fldpos++;
                }
                $rowID = reset($row);
                if(!$this->formatOptions['suppressRecordIcons']){
                    $links = '<a href="#" title="'.gettext("Open Record in New Window").'" onclick="window.open(\'frames_popup.php?dest=view&mdl='.$this->moduleID.'&rid='.$rowID.'\', \'\', \'toolbar=0\')"><img src="'.$theme_web.'/img/record_new_window.gif"/></a>';
                } else {
                    $links = '';
                }
                $content .= sprintf(VIEWGRID_ROW, $tdClass, $links, $rowContent);
            }
            $content = sprintf(VIEWGRID_TABLE, $content);
            $content = '<div id="subgrid_'.$this->moduleID.'">'.$content.'</div>';
        }
    } else {
        $content = ''; //gettext("(no data)");
        //$content = '<br />'.gettext("(none)");
    }

    $count = $this->getRecordCount();
    if('error' == $count){
        $count = '';
    } else {
        $count = '('.$count.')';
    }

    if(!$this->formatOptions['suppressTitle']){
        $gridTitle = sprintf(
            GRID_TAB,
            $this->phrase,
            'list.php?mdl='.$this->moduleID,
            'frames_popup.php?dest=list&mdl='.$this->moduleID,
            $count
            );
    } else {
        $gridTitle = '';
    }

    if($this->isVertical){
        $content = sprintf(
            VIEWGRID_MAIN_VERTICAL,
            $content
            );
    } else {
        //format grid table
        if(!empty($content)){
            $content = sprintf(
                VIEWGRID_MAIN,
                $gridTitle,
                $content
                );
        } else {
            $content =  sprintf(
                VIEWGRID_NONE,
                $gridTitle
                );
        }
    }
    //$content .= debug_r($this);

    //$end_time = getMicroTime();
    //$duration = round($end_time - $start_time, 2);
    //print "ViewGrid render ({$this->moduleID}) took $duration seconds<br />\n";
    return  $content;
}


/**
 *  Renders the ViewGrid in text-only mode, suitable for text emails
 */
function renderText()
{
    global $dbh;
    global $User;

    if(! $User->PermissionToView($this->moduleID) ){
        return $this->phrase . ": no permission\n";
    }
    $content = $this->phrase . "\n";
    $headers = array();

    foreach($this->Fields as $fieldName => $field){
        if($field->isVisible() && empty($field->parentName)){
            $headers[] = $field->gridHeaderPhrase();
        }
    }

    $listSQL = $this->prepareListSQL();

//print debug_r($listSQL, "grid {$this->moduleID} SQL");

    $r = $dbh->getAll($listSQL, DB_FETCHMODE_ASSOC);
    dbErrorCheck($r);
    $data = array();

    if(count($r) > 0){
        foreach($r as $rowNum => $row){

            foreach($this->Fields as $field){
                if($field->isVisible() && empty($field->parentName)){
                    $data[$rowNum][] = $row[$field->name];
                }
            }
        }
        $textTable =& new TextTable($data, $headers);

        $content .= $textTable->render();

    } else {
        return $this->phrase . "\n (no data)\n";
    }

    return $content;
}


/**
 *  Renders the ViewGrid in a format suitable for HTML emails
 */
function renderEmail()
{
// global $recordID;
    global $dbh;
    global $User;

    //check whether user has permission at all
    if(! $User->PermissionToView($this->moduleID) ){
        $moduleInfo = GetModuleInfo($this->moduleID);
        $moduleName = $moduleInfo->getProperty('moduleName');
        return $this->phrase . ": no permission<br />\n";
    }

    //headers without links
    $content = '';
    foreach($this->Fields as $FieldName => $Field){
        if($Field->isVisible() && empty($Field->parentName)){
            $content .= sprintf(
                GRID_HEADER_CELL_EMAIL,
                $Field->gridHeaderPhrase()
            );
        }
    }

    //format header row
    $content = sprintf(
        GRID_HEADER_ROW_EMAIL,
        $content
    );

    $listSQL = $this->prepareListSQL();
    $r = $dbh->getAll($listSQL, DB_FETCHMODE_ASSOC);
    dbErrorCheck($r);
    if(count($r) > 0){

        //CSS classes alternating row background colors
        $tdFormatting = array("aa_l", "aa_l2");

        //display rows
        foreach($r as $rowNum => $row){
            $tdClass = $tdFormatting[$rowNum % 2];
            $rowContent = "";
            $fldpos = 0;

            foreach($this->Fields as $field){
                if($field->isVisible() && empty($field->parentName)){
                    $rowContent .= sprintf(
                        GRID_VIEW_CELL,
                        $field->gridAlign, 'left',
                        $tdClass,
                        $field->gridViewRender(&$row)
                    );
                }
                $fldpos++;
            }
            $content .= sprintf(VIEWGRID_ROW_EMAIL, $rowContent);

        }
        $content = sprintf(VIEWGRID_TABLE, $content);
    } else {
        $content = gettext("(No data)");
    }

    //format grid table
    $content = sprintf(
        VIEWGRID_MAIN_EMAIL,
        $this->phrase,
        $content
        );

    return  $content;
}


/**
 *  Renders the ViewGrid in XML
 */
function renderXML()
{
//global $recordID;
    global $dbh;
    global $User;
    
    $XMLTemplate = '<document moduleID="%s">
<record id="%s">
    %s
</record>
</document>';
    $XMLRecordTemplate = '<record id="%s">
    %s
</record>';
    $XMLGridTemplate = '<subdocument moduleID="%s">
</subdocument>';


    //check whether user has permission at all
    if(! $User->PermissionToView($this->moduleID) ){
        $moduleInfo = GetModuleInfo($this->moduleID);
        $moduleName = $moduleInfo->getProperty('moduleName');
        return $this->phrase . ": no permission<br />\n";
    }

    $recordIDField = $moduleInfo->getPKField();

    $listSQL = $this->prepareListSQL();
    $r = $dbh->getAll($listSQL, DB_FETCHMODE_ASSOC);
    dbErrorCheck($r);
    if(count($r) > 0){

        //display rows
        foreach($r as $rowNum => $row){
            $rowXML = '';
            foreach($this->Fields as $fieldName => $field){
                if($field->isVisible() && empty($field->parentName)){
                    $rowXML .= "<$fieldName>{$row[$fieldName]}</$fieldName>\n";
                }
            }
            $content .= sprintf($XMLRecordTemplate, $row[$recordIDField], $rowXML);

        }
    } else {
        $content = '';
    }

    //format grid XML
    $content = sprintf(
        $XMLGridTemplate,
        $this->moduleID,
        $content
        );

    return  $content;
}
} //end ViewGrid class



class EditGrid extends Grid
{
    var $insertSQL;
    var $updateSQL;
    var $deleteSQL;
    var $logSQL;
    var $getFormSQL = '';
    var $getRowSQL = '';
    var $remoteFields; //a simple list of fields that are connected to a RemoteField
    var $hasGridForm = false;
    var $FormFields = array(); //Controls the style of the form.  If fields are present, the form is rendered vertically with the fields referenced here.  Otherwise, all fields will be used for the form, and rendered horizontally.  Only populated if the XML def has a GridForm tag.
    var $selectedID;
    var $encType = '';
    var $IDTranslationSQL = '';  //for listExtended
    var $listExtendedConditon = '';
    var $PKField;
    var $showGlobalSMRecords = false; //whether a global grid should show records of submodule records
    var $orderByFields = array();
    var $dataCollectionForm = true;

function &Factory($element, $moduleID)
{
    $debug_prefix = debug_indent("EditGrid::Factory:");

    $module = GetModule($moduleID); //local module
    $subModuleID = $element->attributes['moduleID'];

    //when building GlobalEditGrids, there's no SubModule 
    $isGlobalEditGrid = false;
    $hasNoParentRecordID = false;
    if(1 == $element->attributes['isGlobalEditGrid']){
        $isGlobalEditGrid = true;
        $subModule = GetModule($subModuleID);

        if(1 == $element->attributes['hasNoParentRecordID']){
            $hasNoParentRecordID = true;
            $localKey = '';
            $conditions = array(
                'RelatedModuleID' => '/**DynamicModuleID**/'
            );
        } else {
            $localKey = 'RelatedRecordID';
            $conditions = array(
                'RelatedModuleID' => '/**DynamicModuleID**/',
                'RelatedRecordID' => '/**RecordID**/'
            );
        }
    } else {
        $subModule = $module->SubModules[$subModuleID];
        if(empty($subModule)){
            die("$debug_prefix could not find a submodule that matches moduleID  '$subModuleID'");
        }
        $localKey = $subModule->localKey;
        $conditions = $subModule->conditions;
    }

    //check for fields in the element: if there are none, we will import from the Exports section of the sub-module
    if((count($element->c) == 0) || 'yes' == $element->attributes['import']){
        $exports_element = $subModule->_map->selectFirstElement('Exports');
        if(empty($exports_element)){
            die("$debug_prefix Can't find an Exports section in the $subModuleID module.");
        }

        $grid_element = $exports_element->selectFirstElement('EditGrid');
        if(empty($grid_element)){
            die("$debug_prefix Can't find a matching edit grid in the $subModuleID module.");
        }

        //copy all the fields of the imported grid to the current element
        $element->c = array_merge($element->c, $grid_element->c);

        //copy attributes but allow existing attributes to override
        foreach($grid_element->attributes as $attrName => $attrValue){
            if(empty($element->attributes[$attrName])){
                $element->attributes[$attrName] = $attrValue;
            }
        }
    }

    $grid =& new EditGrid(
        $subModuleID,
        $element->attributes['phrase'],
        $localKey,
        $subModule->parentKey,
        $conditions,
        $element->attributes['listExtended'],
        $element->attributes['dataCollectionForm']
    );

    //append fields and GridForm
    if(count($element->c) > 0){
        foreach($element->c as $sub_element){
            if('GridForm' == $sub_element->type){
                foreach($sub_element->c as $form_element){
                    $type = str_replace('Grid', '', $form_element->type);

                    $form_element->attributes['formName'] = $subModuleID;

                    $field_object = $form_element->createObject($subModuleID, $type, $grid);
                    $field_object->phrase = $subModule->ModuleFields[$field_object->name]->phrase;
                    $field_object->dataType = $subModule->ModuleFields[$field_object->name]->dataType;

                    $grid->AddFormField($field_object);
                }
            } else {
                $type = str_replace('Grid', '', $sub_element->type);

                if('OrderByField' == $sub_element->type){
                    //add invisible field if not in the selects already
                    if(!isset($grid->Fields[$sub_element->name])){
                        //make invisiblefield element
                        $invisibleField_element = new Element($sub_element->name, 'InvisibleField', array('name' => $sub_element->name));
                        $field_object = $invisibleField_element->createObject($subModuleID, null, $grid);
                        $grid->AddField($field_object);
                        unset($field_object);
                    }
                    //add to $this->orderByFields
                    $grid->orderByFields[$sub_element->name] = $sub_element->attributes['direction'];
                } elseif('Conditions' == $sub_element->type){
                
                    if(count($sub_element->c) > 0){
                        foreach($sub_element->c as $condition_element){
                            if('Condition' == $condition_element->type){
                                $grid->conditions[$condition_element->attributes['field']] = $condition_element->attributes['value'];
                            }
                        }
                    }
                
                } else {
                    $sub_element->attributes['formName'] = $subModuleID;

                    $field_object = $sub_element->createObject($subModuleID, $type, $grid);
                    $field_object->phrase = $subModule->ModuleFields[$field_object->name]->phrase;
                    $field_object->dataType = $subModule->ModuleFields[$field_object->name]->dataType;
                    $grid->AddField($field_object);
                }
            }
        }
    }


    if($isGlobalEditGrid){
        $grid->isGlobalEditGrid = true;
        $grid->hasNoParentRecordID = $hasNoParentRecordID;
    }

    //PHASE OUT
    $moduleInfo = GetModuleInfo($grid->moduleID);
    $grid->PKField = $moduleInfo->getPKField();

    //PHASE IN:
    $grid->PKFields = $subModule->PKFields;

    if($grid->listExtended){

        $extendingModule = GetModule($grid->moduleID);
        $grid->IDTranslationSQL = "SELECT {$grid->PKField} AS Value FROM {$grid->moduleID} WHERE {$extendingModule->extendsModuleKey} = /*value*/";

        $conditions = array();
        $localKey = "`{$grid->moduleID}`.{$subModule->localKey}";
        $conditions[] = "{$localKey} = '/**RecordID**/'";

        foreach($subModule->conditions as $conditionField => $conditionValue){
            $conditions[] = "{$grid->moduleID}.$conditionField = '$conditionValue'\n";
        }

        $grid->IDTranslationSQL .= ' AND '.join("\n AND ", $conditions);
        $grid->extendedModulePK = $extendingModule->extendsModuleKey;
    }

    //sql stuff
    $grid->listSQL  = $subModule->generateListSQL(&$grid);
    $grid->countSQL = $subModule->generateListCountSQL(&$grid);

    //serializing a copy of the grid
    if($grid->hasGridForm){
        $fieldList = $grid->FormFields;
    } else {
        $fieldList = $grid->Fields;
    }
    print "$debug_prefix generating Grid-getFormSQL:\n";
    $grid->getFormSQL = $subModule->generateGetSQL($fieldList, null, true);
    print "$debug_prefix generating Grid-getRowSQL:\n";
    $grid->getRowSQL = $subModule->generateGetSQL($grid->Fields, null, true);

    if(count($grid->PKFields) == 2){
        $grid->getFormSQL .= "\nAND {$grid->moduleID}.{$grid->PKFields[0]} = '/**RecordID**/'";
        $grid->getRowSQL .= "\nAND {$grid->moduleID}.{$grid->PKFields[0]} = '/**RecordID**/'";
    }

    //RPC cached file section
    $createFileName = "{$moduleID}_{$grid->moduleID}EditGridRPC.gen";
    $modelFileName = 'EditGridRPCModel.php';


    $codeArray = array(
        '/**grid**/' => escapeSerialize($grid)
    );

    SaveGeneratedFile($modelFileName, $createFileName, $codeArray, $moduleID);

    debug_unindent();
    return $grid;
}


function EditGrid(
    $pModuleID,
    $pPhrase,
    $pLocalKey,
    $pParentKey,
    $conditions,
    $listExtended,
    $dataCollectionForm
    )
{
    $this->moduleID = $pModuleID;
    $this->phrase = $pPhrase;
    if(strlen($listExtended) > 0){
        $this->listExtended = true;
    }
    if(strlen($dataCollectionForm) > 0){
        if('yes' != strtolower($dataCollectionForm)){
            $this->dataCollectionForm = false;
        }
    }

    if(!empty($conditions)){
        $parentConditionFields = array();
        foreach($conditions as $conditionField => $conditionValue){
            //looks for [*fieldName*] references to parent record fields
            if(false !== strpos($conditionValue, '[*')){
                $pattern = '/\[\*(\w*)\*\]/';
                $matches = array();
                if(preg_match( $pattern, $conditionValue, $matches)){
                    $parentConditionFields[$conditionField] = $matches[1];
                }
            }
        }
        if(count($parentConditionFields) > 0){
            $this->needsParentFieldValues = true;
            $this->parentConditionFields = $this->makeParentFieldConditions($parentConditionFields);
        }


        $this->conditions = $conditions;
    }

    $this->localKey = $pLocalKey;
    $this->parentKey = $pParentKey;
}


/**
 *  converts conditions that include [*fieldName*] parent field references to a sub-query
 */
function makeParentFieldConditions($parentConditionFields)
{
    global $ModuleID;
    global $SQLBaseModuleID;
    $SQLBaseModuleID = $ModuleID;
    $parentModuleFields = GetModuleFields($ModuleID);
    $parentModuleInfo = GetModuleInfo($ModuleID);
    $parentRecordIDField = $parentModuleInfo->getPKField();

    $selects = array();
    $joins = array();

    $converted = array();
    foreach($parentConditionFields as $conditionField => $parentField){
        $parentModuleField = $parentModuleFields[$parentField];
        //$selects[] = $parentModuleField->makeSelectDef($ModuleID, false);
        //$joins = array_merge($joins, $parentModuleField->makeJoinDef($ModuleID));

        $selects[] = GetSelectDef($parentField);
        $joins = array_merge($joins, GetJoinDef($parentField));

        $joins = SortJoins($joins);
        $SQL = ' SELECT ';
        $SQL .= implode(', ', $selects);
        $SQL .= " FROM `$ModuleID` ";
        $SQL .= implode("\n   ", $joins);
        $SQL .= ' WHERE ';
        $SQL .= "`$ModuleID`.$parentRecordIDField = '/**RecordID**/'";
        $SQL .= ' ';
        $converted[$conditionField] = $SQL;
    }


    return $converted;
}


function isEditable()
{
    return true;
}

function AddFormField($field)
{
    $this->hasGridForm = TRUE;
    $this->FormFields[$field->name] = $field;
}


function render($page, $qsArgs)
{
    require_once CLASSES_PATH . '/lists.php';
    global $dbh;
    global $User;
    global $theme_web;
    global $recordID;
    global $ModuleID;

    //check whether user has permission at all
    if(! $User->PermissionToView($this->moduleID) ){
        $moduleInfo = GetModuleInfo($this->moduleID);
        $moduleName = $moduleInfo->getProperty('moduleName');

        $gridTitle = sprintf(
            GRID_TAB,
            $this->phrase,
            '#',
            '#',
            '' //count
            );

        //format grid table
        $content = sprintf(
            VIEWGRID_MAIN,
            $gridTitle,
            sprintf(gettext("You have no permissions to view or edit records in the %s module."), $moduleName) . "<br />\n"
            );
        return $content;
    }

    if(!defined('IS_RPC') || !IS_RPC) {
        $perPage = 10;
    } else {
        $perPage = intval($_GET['pp']);
    }
    $fieldTypes = array(); //need to get list of file types

    $listSQL = $this->prepareListSQL();

    $listData =& new ListData(
        $this->moduleID,
        $listSQL,
        $perPage,
        $fieldTypes,
        false,
        $this->prepareCountSQL()
        );
    $nRows = $listData->getCount();

    $headerPhrases = array();
    $fieldAlign = array();
    $linkFields = array();
    foreach($this->Fields as $fieldName => $field){
        if($field->isVisible() && empty($field->parentName)){
            $headerPhrases[$fieldName] = $field->gridHeaderPhrase();
            $fieldAlign[] = $field->gridAlign;
            if(!empty($field->linkField)){
                $linkFields[$fieldName] = $field->linkField;
            }
        }
    }

    if(!defined('IS_RPC') || !IS_RPC) {
        $startRow = 0;
    } else {
        $startRow = intval($qsArgs['sr']);
    }

    if($this->listExtended){
        $gridType = 'edit_nfe';
    } else {
        $gridType = 'edit';
    }
    $renderer =& new ListRenderer(
        $this->moduleID,
        $listData,
        $headerPhrases,
        'frames_popup.php?dest=edit&',
        'rpc/gridList.php?grt=edit&smd='.$this->moduleID.'&',
        $fieldAlign,
        $gridType,
        $linkFields
    );
    $renderer->useBestPractices = false;
    $content = $renderer->render($startRow, $defaultOrderBys);

    $gridform_template = 
    '<div id="gridFormDiv" style="position:absolute;display:none; z-index:900;" class="l3"><table class="frm" border="0" cellspacing="2" cellpadding="2">%s</table></div>';

    $formDiv = sprintf($gridform_template, '');
    $content = sprintf(VIEWGRID_TABLE, $content);

    $gridTitle = sprintf(
        GRID_TAB,
        $this->phrase,
        'list.php?mdl='.$this->moduleID,
        'frames_popup.php?dest=list&mdl='.$this->moduleID,
        ''
    );

    //format grid table
    if(!defined('IS_RPC') || !IS_RPC) {
        $content = '<div id="list_'.$this->moduleID.'">'.$content.'</div>';
        $content = sprintf(
            VIEWGRID_MAIN,
            $gridTitle,
            $content
        );
    }

    $content .= $formDiv;
    $content .= "<script type=\"text/javascript\">\n";
    $content .= "\tvar recordID = '$recordID';\n";
    $content .= "\tvar moduleID = '$ModuleID';\n";
    $content .= "\tvar submoduleID = '{$this->moduleID}';\n";
    $content .= "\tvar useRowClassNum = 1;\n";
    $content .= "\tvar rowClasses = Array('l', 'l2');\n";
    $content .= "</script>\n";
    $content .= "<script language=\"JavaScript1.2\" src=\"js/CBs.js\"></script>\n";
    $content .= "<script language=\"JavaScript1.2\" src=\"3rdparty/filtery.js\"></script>\n";
    $content .= "<script language=\"JavaScript1.2\" src=\"3rdparty/DataRequestor.js\"></script>\n";
    return  $content;
}


function old_render($page, $qsArgs)
{
    global $recordID;
    global $dbh;
    global $User;
    global $data;
    global $ModuleID;
    global $theme_web;

    //check whether user has permission at all
    if(! $User->PermissionToView($this->moduleID) ){
        $moduleInfo = GetModuleInfo($this->moduleID);
        $moduleName = $moduleInfo->getProperty('moduleName');

        $gridTitle = sprintf(
            GRID_TAB,
            $this->phrase,
            '#',
            '#',
            '' //count
            );

        //format grid table
        $content = sprintf(
            VIEWGRID_MAIN,
            $gridTitle,
            sprintf(gettext("You have no permissions to view or edit records in the %s module."), $moduleName) . "<br />\n"
            );
        return $content;
    }

    //TODO: Don't display an Edit link for records where user has View but not Edit permissions

    //capture order by parameter
    $orderBy = $qsArgs['ob'.$this->number];

    //making sure ob and pob fields exist in grid:
    if(!in_array($orderBy, array_keys($this->Fields))){
        $orderBy = '';
    }
    if(!in_array($prevOrderBy, array_keys($this->Fields))){
        $prevOrderBy = '';
    }

    //add grid ID to all links
    $qsArgs['gid'] = $this->number;

    unset($qsArgs['ob'.$this->number]);
    unset($qsArgs['pob'.$this->number]);
    $headerQS = MakeQS($qsArgs);

    //make form query string
    $formQS = MakeQS($qsArgs);

    $listSQL = $this->prepareListSQL();

//print debug_r($listSQL);
//print debug_r($this);




    //setting up link for the next pob
    if(!empty($orderBy)){ 
        $prevOBString = '&pob'.$this->number.'='.$orderBy;
    } else {
        $prevOBString = '';
        if(count($this->orderByFields) > 0){
            $listSQL .= "\nORDER BY ";
            $onFirst = true;
            //print debug_r($this->orderByFields);
            foreach($this->orderByFields AS $fieldname => $direction){
                if($onFirst){
                    $listSQL .= "\n $fieldname";
                    $onfirst = false;
                } else {
                    $listSQL .= ",\n $fieldname";
                }
                if(!empty($direction)){
                    $listSQL .= ' '.$direction;
                }
            }
        }
    }

    //grid headers
   // $content = '';
    $nCols = 0;
    foreach($this->Fields as $FieldName => $Field)
    {
        //if('InvisibleField' != $Field->getType() && empty($Field->parentName)){
        if($Field->isVisible() && empty($Field->parentName)){
            if($prevOrderBy != $FieldName){
                $fPrevOBString = $prevOBString;
            } else {
                $fPrevOBString = '';
            }
            $content .= sprintf(
                GRID_HEADER_CELL,
                $page.'?'.$headerQS.'&ob'.$this->number.'='.$FieldName.$fPrevOBString,
                $Field->gridHeaderPhrase()
            );
            $nCols++;
        }
    }

    //using first table header as "add new" location
    if($this->listExtended){
        $addNew = '<th class="l" id="loc0">&nbsp;</th>';
    } else {
        $addNew = '<th class="l" id="loc0"><a class="lh" href="javascript:editRow(\'0\');" title="'.gettext("Add New").'"><img src="'.$theme_web.'/img/grid_quickadd.gif"/></a></th>';
    }
    //format header row
    $content = '<tr>'.$addNew.$content.'</tr>';

//    $content .= debug_r($this->insertSQL);
//    $content .= debug_r($this->updateSQL);
//print debug_r($listSQL);
    //get data
    $r = $dbh->getAll($listSQL, DB_FETCHMODE_ASSOC);
    dbErrorCheck($r, true);


//print debug_r($r);
//print_r($r);
    //alternating row background colors
    $tdFormatting = array("l", "l2");

    //get selected row id
    $selRowID = $this->selectedID; //this allows Cancel to reset //$qsArgs['grw'];

    unset($qsArgs['grw']); //remove from QS
    $rowQS = MakeQS($qsArgs); //QS for row "Edit" links
//print debug_r($this->Fields);

    //display rows
    foreach($r as $rowNum => $row){

        $curRowID = reset($row); //assumes first column is row ID
        $tdClass = $tdFormatting[($rowNum) % 2];
        $rowContent = '';

        //add view cells
        foreach($this->Fields as $key => $field){
            //if('InvisibleField' != $field->getType() && empty($field->parentName)){
            if($field->isVisible() && empty($field->parentName)){
                $rowContent .= sprintf(
                    GRID_VIEW_CELL,
                    $field->gridAlign, //'left',
                    $tdClass,
                    $field->gridViewRender(&$row)
                );
            } else {
                if($field->isEditable()){
                    $rowContent .= $field->gridViewRender(&$row);
                }
            }
        }

        if($this->listExtended){
            $content .= sprintf(
                EDITGRID_VIEWROW_NOFULLEDIT,
                $tdClass,
                $curRowID,
                'javascript:editRow(\''.$curRowID.'\');',
                '<img src="'.$theme_web.'/img/grid_quickedit.gif" title="'.gettext("Quick Edit").'"/>',
                $rowContent);
        } else {
            $content .= sprintf(
                EDITGRID_VIEWROW,
                $tdClass,
                $curRowID,
                '#" onclick="window.open(\'frames_popup.php?dest=edit&mdl='.$this->moduleID.'&rid='.$curRowID.'\', \'\', \'toolbar=0\')',
                '<img src="'.$theme_web.'/img/grid_fulledit.gif" title="'.gettext("Full Edit (in new window)").'"/>',
                'javascript:editRow(\''.$curRowID.'\');',
                '<img src="'.$theme_web.'/img/grid_quickedit.gif" title="'.gettext("Quick Edit").'"/>',
                $rowContent);
        }
    }

    $gridform_template = 
    '<div id="gridFormDiv" style="position:absolute;display:none; z-index:900;" class="l3"><table class="frm" border="0" cellspacing="2" cellpadding="2">%s</table></div>';

    $formDiv = sprintf($gridform_template, '');
    $content = sprintf(VIEWGRID_TABLE, $content);

    $gridTitle = sprintf(
        GRID_TAB,
        $this->phrase,
        'list.php?mdl='.$this->moduleID,
        'frames_popup.php?dest=list&mdl='.$this->moduleID,
        ''
    );

    //format grid table
    $content = sprintf(
        VIEWGRID_MAIN,
        $gridTitle,
        $content
    );

    $content .= $formDiv;
    $content .= "<script type=\"text/javascript\">\n";
    $content .= "\tvar recordID = '$recordID';\n";
    $content .= "\tvar moduleID = '$ModuleID';\n";
    $content .= "\tvar submoduleID = '{$this->moduleID}';\n";
    $content .= "\tvar useRowClassNum = 1;\n";
    $content .= "\tvar rowClasses = Array('l', 'l2');\n";
    $content .= "</script>\n";
    $content .= "<script language=\"JavaScript1.2\" src=\"js/CBs.js\"></script>\n";
    $content .= "<script language=\"JavaScript1.2\" src=\"3rdparty/filtery.js\"></script>\n";
    $content .= "<script language=\"JavaScript1.2\" src=\"3rdparty/DataRequestor.js\"></script>\n";
    return  $content;
} //function render


function renderForm($rowID){
    global $dbh;
    global $User;
    global $phrases;
    global $recordID;

    $content = '';
    $precontent = "<script type=\"text/javascript\">\n<!--\n//[js:]\n";
    $precontent .= "    form_moduleID = \"{$this->moduleID}\";\n";
    $precontent .= "//[:js]\n-->\n</script>\n";
    
    //make sure we have correct rowID:
    if($this->listExtended){
        $SQL = str_replace(array('/*value*/', '/**RecordID**/'), array($rowID, $recordID), $this->IDTranslationSQL);
        $rowID = $dbh->getOne($SQL);
        dbErrorCheck($rowID);
//return debug_r($SQL);
    }
    if(empty($rowID)){
        $rowID = 0;
    }
    
    //check permission
    if($User->PermissionToEdit($this->moduleID) > 0){ //should get ownerOrgID here
        $perm = 'permitted';
        
        $SQL = str_replace(array('/**RowID**/', '/**RecordID**/'), array($rowID, $recordID), $this->getFormSQL);
        $values = $dbh->getAll($SQL, DB_FETCHMODE_ASSOC);
        dbErrorCheck($values);

//return debug_r($SQL);
//return debug_r($values);
        //get first (only) row from dataset
        if(count($values) > 0){
            $data = $values[0];
        } else {
            $data = array();
        }

        if($this->hasGridForm){
            $fieldList = $this->FormFields;
        } else {
            $fieldList = $this->Fields;
        }

        foreach($fieldList as $fName => $field){
            $content .= $field->render($data, $phrases);
            if('datefield' == get_class($field)){
                $datecontent .= "Calendar.setup({\n";
                $datecontent .= "\tinputField : \"$fName\",\n";
                if('datetime' == $field->dataType){
                    $datecontent .= "\t" . $User->getCalFormat(true) ."\n";
                    $datecontent .= "\tshowsTime   : true,\n";
                } else {
                    $datecontent .= "\t" . $User->getCalFormat(false) ."\n";
                }
                $datecontent .= "\tbutton     : \"cal_$fName\"\n";
                $datecontent .= "});\n";
            }
        }
    }

    if($rowID > 0){
        $deleteButton = sprintf(FORM_BUTTON_HTML, 'Delete', gettext("Delete"), 'editGridDeleteRow(moduleID, submoduleID, editRowID)').' ';
    } else {
        $deleteButton = '';
    }
    
    //wrap into form & table
    $content .= sprintf(FORM_BUTTONROW_HTML, 
        sprintf(FORM_BUTTON_HTML, 'Save', gettext("Save"), 'editGridSaveForm(moduleID, submoduleID, editRowID)').' '.
        $deleteButton.
        sprintf(FORM_BUTTON_HTML, 'Cancel', gettext("Cancel"), 'cancelEditRow(editRowID);')
    );
    $content = sprintf(EDITGRID_FORM, $this->moduleID, $content);
    if(!empty($datecontent)){
        $content .= "<script type=\"text/javascript\">\n<!--\n//[js:]\n";
        $content .= $datecontent . "\n";
        $content .= "//[:js]\n-->\n</script>\n";
    }
    
    $content = $precontent.$content;
    return $content;
}


function validateForm(){
    if($this->hasGridForm){
        $fields =& $this->FormFields;
    } else {
        $fields =& $this->Fields;
    }
    $messages = '';
    foreach($fields as $fieldname => $field){
        $message = Validate($_POST[$fieldname], ShortPhrase($field->phrase), $field->validate);
        $field->invalid = true;
        $messages .= $message;
    }
    return $messages;
}


/**
 * handles the form in AJAX-style (override for UploadGrid)
 */
function handleForm(){ //EditGrid
    global $recordID; //parent record id

    $rowID = intval($_GET['grw']); //capture any selected row (record ID of grid's module)

    global $dbh;
    global $User;

    $error = ''; //the rpc relies on capturing and returning errors in a JSON-encoded format.

    switch($_GET['action']){
    case 'save':
    case 'add':
        $action = 'save';
        break;
    case 'delete':
        $action = 'delete';
        break;
    default:
        return array('error'=>gettext("Unknown action requested."));
        break;
    }

trace($this->conditions, "EditGrid pre populated conditions");

    if($this->listExtended){
        //check for existing record that matches the posted ID (happens to be the Extended module's PK...)
        $extendedRowID = $rowID;
        $SQL = str_replace(array('/*value*/', '/**RecordID**/'), array($rowID, $recordID), $this->IDTranslationSQL);
    
        $rowID = $dbh->getOne($SQL);
        dbErrorCheck($rowID);
        //$origRowID = $rowID;
        trace("EditGrid translated ID from $extendedRowID to $rowID");
    }

    if($this->needsParentFieldValues){
        foreach($this->parentConditionFields as $conditionField => $parentSQL){
            $parentSQL = str_replace('/**RecordID**/', $recordID, $parentSQL);
            $parentValue = $dbh->getOne($parentSQL);
            dbErrorCheck($parentValue);
            $this->conditions[$conditionField] = $parentValue;
        }
trace($parentSQL, "EditGrid parent SQL");
trace($this->conditions, "EditGrid populated conditions");
    }

    global $ModuleID;
    if(count($this->conditions) > 0){
        foreach($this->conditions as $conditionField => $conditionValue){
            if('/**RecordID**/' == $conditionValue){
                $this->conditions[$conditionField] = $recordID;
            } elseif('/**DynamicModuleID**/' == $conditionValue) {
                $this->conditions[$conditionField] = $ModuleID;
            }
        }
    }

    $dh = GetDataHandler($this->moduleID, false);
    if(count($this->conditions) > 0){
        foreach($this->conditions as $conditionField => $conditionValue){
            $dh->relatedRecordFieldValues[$conditionField] = $conditionValue;
        }
    }
    if(!empty($this->localKey)){
trace($this->conditions, "EditGrid localkey {$this->localKey} = $recordID");
        $dh->relatedRecordFieldValues[$this->localKey] = $recordID;
    }
    if($this->listExtended){
        $dh->relatedRecordFieldValues[$this->extendedModulePK] = $extendedRowID;
    }

trace($dh->relatedRecordFieldValues, "EditGrid-dh relatedRecordFieldValues");

    if('save' == $action){

        $validateMsg = $this->validateForm();
        if(!empty($validateMsg)){
            $error .= gettext("The data was not saved, because:")."\n".$validateMsg;

            return array('rowID'=>$rowID, 'error'=>$error);
        }


        $resultID = $dh->saveRow($_POST, $rowID);
        if($this->listExtended){
            if(empty($rowID)){
                $rowID = $resultID;
            } else {
                //ignores $resultID
            }
        } else {
            $rowID = $resultID;
        }
trace($SQL, "EditGrid: dataHandler returned rowID $resultID.");

        $displayRow = true;
    } elseif('delete' == $action){

        //use dataHandler delete function
        $success = $dh->deleteRow($rowID);
        if($success){
            //whatever
        }

        if($this->listExtended){
            $displayRow = true;
        } else {
            $displayRow = false;
        }

    }

    if($displayRow){
        $SQL = str_replace(array('/**RowID**/', '/**RecordID**/'), array($rowID, $recordID), $this->getRowSQL);
trace($SQL, "EditGrid row retrieval ");
        $values = $dbh->getAll($SQL, DB_FETCHMODE_ASSOC);
        if(!dbErrorCheck($values, false, false)){
            $error = gettext("Database error") . ':' . $SQL;
            return array('rowID'=>$rowID, 'error'=>$error);
        }

        //get first (only) row from dataset
        if(count($values) > 0){
            $data = $values[0];
        } else {
            $data = array();
        }

        foreach($this->Fields as $field){

            if($field->isVisible() && empty($field->parentName)){
                $cells[] = array(
                    'align' => 'left',
                    'className' => 'l',
                    'innerHTML' => $field->gridViewRender(&$data)
                );
            }
            $fldpos++;
        }
    }

    return array('rowID' => $rowID, 'cells' => $cells, 'error' => $error);
}


function prepRemoteFields(){
    $debug_prefix = debug_indent("EditGrid-prepRemoteFields() {$this->moduleID}:");

//identify a list of remote fields
    if ($this->hasGridForm){
        $fieldList = &$this->FormFields;
    } else {
        $fieldList = &$this->Fields;
    }
//die('looking for RemoteFields');

    $moduleFields = GetModuleFields($this->moduleID); //renmoved &
    
    foreach($fieldList as $fieldName => $field){
        if($field->isEditable()){
            $moduleField = $moduleFields[$fieldName]; //removed &
            if(get_class($moduleField) == 'remotefield'){
                print "$debug_prefix Found Remote Field $fieldName\n\n";
                $this->remoteFields[$fieldName] = $moduleField;
            } else {
                //print "EditGrid->PrepRemoteFields: Field $fieldName is not a RemoteField\n\n";
            }
        }
    }
    debug_unindent();
}

} //end EditGrid class


//temporary class for adding AJAX/JSON edit functionality in EditGrid
class AEditGrid extends EditGrid{

    function &Factory($element, $moduleID){
        return Parent::Factory($element, $moduleID);
    }   

    function AEditGrid(
        $pModuleID, 
        $pPhrase, 
        $pLocalKey,
        $conditions,
        $listExtended
        )
    {
        Parent::EditGrid(
            $pModuleID, 
            $pPhrase, 
            $pLocalKey,
            $conditions,
            $listExtended
        );
    }
    
} //class AEditGrid




class UploadGrid extends EditGrid
{
var $uploadFields = array();
var $encType = 'enctype="multipart/form-data"';


function Factory($element, $moduleID)
{
    $module = GetModule($moduleID);
    $subModuleID = $element->attributes['moduleID'];

    //when building GlobalEditGrids, there's no SubModule 
    if(1 == $element->attributes['isGlobalEditGrid']){
        $subModule = GetModule($subModuleID);
        $localKey = 'RelatedRecordID';
        $conditions = array(
            'RelatedModuleID' => '/**DynamicModuleID**/',
            'RelatedRecordID' => '/**RecordID**/'
        );
    } else {
        $subModule = $module->SubModules[$subModuleID];
        if(empty($subModule)){
            die("UploadGrid-Factory: could not find a submodule that matches moduleID  '$subModuleID'");
        }
        $localKey = $subModule->localKey;
        $conditions = null;
    }

    //check for fields in the element: if there are none, we will import from the Exports section of the sub-module
    if(count($element->c) == 0){
        $exports_element = $subModule->_map->selectFirstElement('Exports');
        if(empty($exports_element)){
            die("Can't find an Exports section in the $subModuleID module.");
        }

        $grid_element = $exports_element->selectFirstElement('UploadGrid');
        if(empty($grid_element)){
            die("Can't find a matching upload grid in the $subModuleID module.");
        }

        //copy all the fields of the imported grid to the current element
        $element->c = $grid_element->c;

        //copy attributes but allow existing attributes to override
        foreach($grid_element->attributes as $attrName => $attrValue){
            if(empty($element->attributes[$attrName])){
                $element->attributes[$attrName] = $attrValue;
            }
        }
    }

    $grid = new UploadGrid(
        $subModuleID,
        $element->attributes['phrase'],
        $conditions,
        $subModule->localKey
    );

    //append fields and GridForm
    if(count($element->c) > 0){
        foreach($element->c as $sub_element){
            if('GridForm' == $sub_element->type){
                foreach($sub_element->c as $form_element){
                    $type = str_replace('Grid', '', $form_element->type);

                    $field_object = $form_element->createObject($subModuleID, $type, $grid);
                    $form_element->attributes['formName'] = $subModuleID;

                    $field_object->phrase = $subModule->ModuleFields[$field_object->name]->phrase;

                    $grid->AddFormField($field_object);
                }
            } else {
                $type = str_replace('Grid', '', $sub_element->type);

                $field_object = $sub_element->createObject($subModuleID, $type, $grid);
                $sub_element->attributes['formName'] = $subModuleID;
                $field_object->phrase = $subModule->ModuleFields[$field_object->name]->phrase;

                $grid->AddField($field_object);
            }
        }
    }
    $moduleInfo = GetModuleInfo($grid->moduleID);
    $grid->PKField = $moduleInfo->getPKField();

    $grid->listSQL  = $subModule->generateListSQL(&$grid);

    return $grid;
}


function UploadGrid(
    $pModuleID, 
    $pPhrase,
    $conditions,
    $pLocalKey
    )
{
    $this->moduleID = $pModuleID;
    $this->phrase = $pPhrase;
    if(!empty($conditions)){
        $this->conditions = $conditions;
    }
    $this->localKey = $pLocalKey;
}


function render($page, $qsArgs)
{
    global $recordID;
    global $dbh;
    global $User;
    global $data;

    //check whether user has permission at all
    if(! $User->PermissionToView($this->moduleID) ){
        $moduleInfo = GetModuleInfo($this->moduleID);
        $moduleName = $moduleInfo->getProperty('moduleName');

        $gridTitle = sprintf(
        GRID_TAB,
            $this->phrase,
            '#',
            '#',
            '' //count
        );

        //format grid table
        $content = sprintf(
            VIEWGRID_MAIN,
            $gridTitle,
            sprintf(gettext("You have no permissions to view records in the %s module."), $moduleName) . "<br />\n"
            );
        return $content;
    }


    //check that the uploads folder is writeable
    global $messages;
    if(!is_writeable(UPLOAD_PATH)){
        $err_msg = gettext("The server's upload directory is not writeable. You will not be able to upload files.");
        $messages[] = array('e', $err_msg);
        trigger_error($err_msg, E_USER_WARNING);
    } else {
        if(!is_writeable(UPLOAD_PATH.'/'.$ModuleID)){
            $err_msg = gettext("The server's upload directory for this module is not writeable. You will not be able to upload files.");
            $messages[] = array('e', $err_msg);
            trigger_error($err_msg, E_USER_WARNING);
        }
    }

    //TODO: Don't display an Edit link for records where user has View but not Edit permissions


    //capture order by parameter
    $orderBy = $qsArgs['ob'.$this->number];
    $prevOrderBy = $qsArgs['pob'.$this->number];

    //making sure ob and pob fields exist in grid:
    if(!in_array($orderBy, array_keys($this->Fields))){
        $orderBy = '';
    }
    if(!in_array($prevOrderBy, array_keys($this->Fields))){
        $prevOrderBy = '';
    }

    //add grid ID to all links
    $qsArgs['gid'] = $this->number;

    unset($qsArgs['ob'.$this->number]);
    unset($qsArgs['pob'.$this->number]);
    $headerQS = MakeQS($qsArgs);

    //make form query string
    $formQS = MakeQS($qsArgs);

    $listSQL = $this->prepareListSQL();


    //setting up link for the next pob
    if(!empty($orderBy)){ 
        $prevOBString = '&pob'.$this->number.'='.$orderBy;
    } else {
        $prevOBString = '';
    }

    //grid headers
    $content = '';
    foreach($this->Fields as $FieldName => $Field)
    {
        //if('InvisibleField' != $Field->getType() && empty($Field->parentName)){
        if($Field->isVisible() && empty($Field->parentName)){
            if($prevOrderBy != $FieldName){
                $fPrevOBString = $prevOBString;
            } else {
                $fPrevOBString = '';
            }
            $content .= sprintf(
                GRID_HEADER_CELL,
                $page.'?'.$headerQS.'&ob'.$this->number.'='.$FieldName.$fPrevOBString,
                $Field->gridHeaderPhrase()
            );
        }
    }

    //format header row
    $content = sprintf(
        GRID_HEADER_ROW,
        $content
    );

    //print nl2br($listSQL);
    //get data
    $r = $dbh->getAll($listSQL, DB_FETCHMODE_ASSOC);
    dbErrorCheck($r, true);

    //alternating row background colors
    $tdFormatting = array("l", "l2");

    //get selected row id
    $selRowID = $this->selectedID; //this allows Cancel to reset //$qsArgs['grw'];

    unset($qsArgs['grw']); //remove from QS
    $rowQS = MakeQS($qsArgs); //QS for row "Edit" links
//print debug_r($this->Fields);

    //display rows
    foreach($r as $rowNum => $row){

        $curRowID = reset($row); //assumes first column is row ID
        $tdClass = $tdFormatting[($rowNum) % 2];
        $rowContent = "";

        if ($selRowID != $curRowID){

            //add view cells
            foreach($this->Fields as $key => $field){
                //if('InvisibleField' != $field->getType() && empty($field->parentName)){
                if($field->isVisible() && empty($field->parentName)){
                    $rowContent .= sprintf(
                        GRID_VIEW_CELL,
                        $field->gridAlign, //'left',
                        'l2',
                        $field->gridViewRender(&$row)
                    );
                } else {
                    if($field->isEditable()){
                        $rowContent .= $field->gridViewRender(&$row);
                    }
                }
            }

            $content .= sprintf(
                UPLOADGRID_VIEWROW,
                $tdClass,
                $curRowID,
                //'edit.php?'.$formQS.'&grw='.$curRowID, //adds row ID
                $page.'?'.$rowQS.'&grw='.$curRowID, //adds row ID
                gettext("Edit"),
                $rowContent
            );
        } else {
            if($this->hasGridForm){

                //display editable fields like a regular form (not a row)
                $numFields = count($this->Fields);
                //render each gridForm field
                foreach($this->FormFields as $formField){
                    if(empty($formField->parentName)){
                        $rowContent .= $formField->gridFormRender(&$row);

                        if ("DateField" == get_class($formField)){
                            $dateFields[] = $formField->name;
                        }
                    }
                }
                //wrap into form
                $rowContent = sprintf(GRIDFORM_HTML, $numFields, $rowContent);

            } else {
                //add editable cells
                foreach($this->Fields as $key => $field){
                    //if('InvisibleField' != $field->getType() && empty($field->parentName)){
                    if($field->isVisible() && empty($field->parentName)){
                        $rowContent .= sprintf(
                            GRID_EDIT_CELL,
                            "left",
                            "l3",
                            $field->gridEditRender(&$row)
                        );
                        if ("DateField" == get_class($field)){
                            $dateFields[] = $field->name;
                        }
                    } else {
                        //if it's still editable:
                        if($field->isEditable()){
                            $rowContent .= $field->gridEditRender(&$row);
                        }
                    }
                }
            }
            $content .= sprintf(
                EDITGRID_EDITROW,
                "l3",
                gettext("Save"),
                gettext("Delete"),
                gettext("Cancel"),
                $rowContent);
        }
    }

    //if inserting, add insert row
    if(!$this->listExtended && empty($selRowID)){
        $rowContent = "";
        global $data;

        //add insert cells
        if($this->hasGridForm){
            //display editable fields like a regular form (not a row)
            $numFields = count($this->Fields);
            //render each gridForm field
            foreach($this->FormFields as $formField){
                if(empty($formField->parentName)){
                    $rowContent .= $formField->gridFormRender($selRowID);

                    if ("DateField" == get_class($formField)){
                        $dateFields[] = $formField->name;
                    }
                }
            }
            //wrap into form
            $rowContent = sprintf(GRIDFORM_HTML, $numFields, $rowContent);

        } else {
            foreach($this->Fields as $key => $field){
                //if('InvisibleField' != $field->getType()){
                if($field->isVisible() && empty($field->parentName)){
                    $rowContent .= sprintf(
                        GRID_EDIT_CELL,
                        "left",
                        "l3",
                        $field->gridEditRender($selRowID) //passing an empty variable in order to avoid warnings - omitting a parameter or passing an empty string causes warning
                    );
                    if ("DateField" == get_class($field)){
                        $dateFields[] = $field->name;
                    }
                } else {
                    //if it's still editable:
                    if($field->isEditable()){
                        $rowContent .= $field->gridEditRender($selRowID);
                    }
                }
            }
        }
        $content .= sprintf(
            EDITGRID_INSERTROW,
            "l3",
            gettext("Add"),
            $rowContent);
    }

    //format grid table
    $content = sprintf(
        EDITGRID_MAIN,
        $this->phrase,
        $page.'?'.$formQS,
        $this->moduleID,
        $this->encType,
        $this->number,
        $content);

    global $User;

    //generate content for each date field
    if (count($dateFields) > 0){
        $content .= "<script type=\"text/javascript\">\n";
        foreach($dateFields as $fieldName){
            $content .= "Calendar.setup({\n";
            $content .= "\tinputField : \"$fieldName\",\n";
            //$content .= "\tifFormat   : \\\"\".\$User->getDateFormatCal().\"\\\",\n";
            $content .= "\t" . $User->getCalFormat() ."\n";
            $content .= "\tbutton     : \"cal_$fieldName\"\n";
            $content .= "});\n";
        }
        $content .= "</script>\n";
    }

    //$content .= debug_r($listSQL);
    //$content .= debug_r($this->insertSQL);
    //$content .= debug_r($this->updateSQL);

    return  $content;
} //render


function handleForm(){
    global $recordID; 
    global $ModuleID; 
    global $messages;
    global $dbh;

    if (empty($_POST['cancel'])){

        //capture any selected row
        if (intval($_GET['grw']) > 0){
            $rowID = intval($_GET['grw']);
            $this->selectedID = $rowID;
        }

        if (intval($_POST['gridnum']) == $this->number){

            global $User;

            //check whether user has permission to edit at all
            if(! $User->PermissionToEdit($this->moduleID) ){
                $moduleInfo = GetModuleInfo($this->moduleID);
                $moduleName = $moduleInfo->getProperty('moduleName');
                trigger_error(sprintf(gettext("You have no permissions to edit records in the %s module."), $moduleName) . E_USER_ERROR);
            }

            //set up data handler
            $dh = GetDataHandler($this->moduleID);
            if(!empty($this->localKey)){
                $dh->relatedRecordFieldValues[$this->localKey] = $recordID;
            }
            if(count($this->conditions) > 0){
                foreach($this->conditions as $conditionField => $conditionValue){
                    if('/**RecordID**/' == $conditionValue){
                        $dh->relatedRecordFieldValues[$conditionField] = $recordID;
                    } elseif('/**DynamicModuleID**/' == $conditionValue) {
                        $dh->relatedRecordFieldValues[$conditionField] = $ModuleID;
                    } else {
                        $dh->relatedRecordFieldValues[$conditionField] = $conditionValue;
                    }
                }
            }

            //check whether to save or delete
            //add
            //save
            //delete
//trace($_POST, 'POST');
//trace($_GET, 'GET');
            if(!empty($_POST['delete'])){

                $result = $dh->deleteRow($rowID);
                $this->selectedID = 0;

            } elseif(!empty($_POST['add']) || !empty($_POST['save'])){

                $validateMsg = $this->validateForm();
                if(!empty($validateMsg)){
                    //use the EditScreen error
                    $validateMsg = gettext("The record has not been saved, because:")."\n".$validateMsg;
                    $validateMsg = nl2br($validateMsg);

                    //return error messages
                    global $messages;
                    $messages[] = array('e', $validateMsg);
                }
print debug_r($_POST);
//die('test');


                switch($_FILES['FileName']['error']){
                case UPLOAD_ERR_OK:
                    if(is_uploaded_file($_FILES['FileName']['tmp_name'])){

print "file upload went well<br>\n";

                        //adding the file name to the POST variable
                        $_POST['FileName'] = $_FILES['FileName']['name']; 

                        if(empty($_POST['Description'])){
                            $_POST['Description'] = $_FILES['FileName']['name'];
                        }

                        $rowID = $dh->saveRow($_POST, $rowID);

                        //build the file name
                        $destination = UPLOAD_PATH . "/{$ModuleID}/att_{$ModuleID}_{$recordID}_{$rowID}.dat";

                        //create the folder if needed
                        if(!file_exists(dirname($destination))){
                            mkdir(dirname($destination));
                        }

print "saving $destination<br />\n";
                        if(move_uploaded_file($_FILES['FileName']['tmp_name'], $destination)){
                            //print "upload successful<br />\n";
                            $messages[] = array('m', gettext("The file was uploaded successfully."));

                            if(!empty($_FILES['FileName']['size'])){
                                $size = $_FILES['FileName']['size'];
                                $SQL = "UPDATE att SET FileSize = '$size' WHERE AttachmentID = $rowID";
                                //print $SQL;
                                $r = $dbh->query($SQL);
                                dbErrorCheck($r);
                            }

                            $r = $dbh->query('COMMIT');
                            dbErrorCheck($r);
                        } else {
                            //print "nope. didn't work<br />\n";
                            $messages[] = array('e', gettext("There was a problem uploading the file ") . "{$_FILES['FileName']['name']}.");

                            $r = $dbh->query('ROLLBACK');
                            dbErrorCheck($r);
                        }

                    } else {
                        //print "what's the matter?<br>\n";
                        $messages[] = array('e', gettext("There was a problem uploading the file. "));
                    }

                    break;
                case UPLOAD_ERR_INI_SIZE:
                    //print "file upload: file is larger than allowed for this server<br>\n";
                    $err_msg = gettext("The file is larger than allowed for this server.");
                    $messages[] = array('e', $err_msg);
                    trigger_error($err_msg, E_USER_WARNING);
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    //print "file upload: file is larger than allowed for this form<br>\n";
                    $err_msg = gettext("The file is larger than allowed.");
                    $messages[] = array('e', $err_msg);
                    trigger_error($err_msg, E_USER_WARNING);
                    break;
                case UPLOAD_ERR_PARTIAL:
                    //print "file upload: file was not completely uploaded<br>\n";
                    $err_msg = gettext("The file was not completely uploaded.");
                    $messages[] = array('e', $err_msg);
                    trigger_error($err_msg, E_USER_WARNING);
                    break;
                case UPLOAD_ERR_NO_FILE:
                    if (!empty($_POST['add'])){
                        //don't save record w/o file
                        $err_msg = gettext("No file was uploaded.");
                        $messages[] = array('e', $err_msg);
                        trigger_error($err_msg, E_USER_WARNING);
                        $this->selectedID = 0; //show the add form
                    } else {
                        //As long as $_POST does not contain FileName, the original file name won't be overwritten!!
                        $rowID = $dh->saveRow($_POST, $rowID);
                    }
                    break;
                default:
                    $err_msg = gettext("There was a problem uploading the file. Unknown problem.");
                    $messages[] = array('e', $err_msg);
                    trigger_error($err_msg, E_USER_ERROR);
                    $messages[] = array('e', $err_msg);
                }
            }
        }
    } else {
        $this->selectedID = 0;
    }
}

} //end class UploadGrid





class SelectGrid extends EditGrid {
    var $availableIDField;
    var $availableNameField;
    
    var $listAvailableSQL;
    var $listConditions = array();
    var $listSelectedSQL;
    var $listExistingSelectedSQL;
    var $insertSQL;
    var $insertRemoteSQL;
    var $removeSQL;
    var $removeRemoteSQL;
    var $restoreSQL;
    var $restoreRemoteSQL;
    var $getRemoteIDSQL;
    var $getRemoteRowIDSQL;
    var $logSQL;
    var $logRemoteSQL;

    var $useRemoteField = false;
    var $listKeyType = '';
    
    function &Factory($element, $moduleID)
    {
        return new SelectGrid($element, $moduleID);
    }

    function SelectGrid (&$element, $moduleID)
    {
        $module = GetModule($moduleID);
        $subModuleID = $element->attributes['moduleID'];
        $subModule = $module->SubModules[$subModuleID];

        //copies attributes from submoule's Exports section if grid is not defined locally
        if('SelectGrid' == $element->type && count($element->c) == 0){

            $exports_element = $subModule->_map->selectFirstElement('Exports');
            if(empty($exports_element)){
                die("Can't find an Exports section in the $subModuleID module.");
            }

            $grid_element = $exports_element->selectFirstElement('SelectGrid');
            //$grid_element = $exports_element->selectFirstElement($element->type);
            if(empty($grid_element)){
                die("Can't find a matching check grid in the $subModuleID module.");
            }

            //copies all the fields of the imported grid to the current element
            $element->c = $grid_element->c;

            //copies attributes but allows existing attributes to override
            foreach($grid_element->attributes as $attrName => $attrValue){
                if(empty($element->attributes[$attrName])){
                    $element->attributes[$attrName] = $attrValue;
                }
            }
        }
    
        $this->moduleID = $subModuleID;
        $this->phrase = $element->attributes['phrase'];
        $this->primaryListField = $element->attributes['primaryListField'];
        $this->localKey = $subModule->localKey;
        $this->conditions = $subModule->conditions;
        
        $listModuleField = GetModuleField($this->moduleID, $this->primaryListField);
        $this->listModuleID = $listModuleField->foreignTable;
        
        $this->availableIDField = $listModuleField->foreignKey;
        $this->availableNameField = $listModuleField->foreignField;

        if(count($element->c) > 0){
            foreach($element->c as $sub_element){
                if('AvailbleListConditions' == $sub_element->type){
                    foreach($sub_element->c as $condition_element){
                        $conditionObj = $condition_element->createObject($this->listModuleID);
                        $this->listConditions[$conditionObj->name] = $conditionObj;
                    }
                }
            }
        }

        $conditionStrings = array();
        if(!empty($subModule->localKey)){
            if(!$this->listExtended){
                $localKey = $subModule->localKey;
                $this->conditions[$localKey] = "/*recordID*/";
            }
        }
//print "submodule localKey = {$subModule->localKey}\n";
        $this->init($this->conditions);

        //SQL for the "available items" list
        $listModuleFields = GetModuleFields($this->listModuleID);
        $listIDField = $listModuleFields[$this->availableIDField];
        $listNameField = $listModuleFields[$this->availableNameField];
        

        $SQL = "SELECT ";
//        $SQL .= $listIDField->makeSelectDef($this->listModuleID, false) . " AS ID, ";
//        $SQL .= $listNameField->makeSelectDef($this->listModuleID, false) . " AS Name ";
        $SQL .= GetQualifiedName($this->availableIDField, $this->listModuleID) . ' AS ID, ';
        $SQL .= GetQualifiedName($this->availableNameField, $this->listModuleID) . ' AS Name ';

        $SQL .= "FROM `{$this->listModuleID}` ";

        $listFroms = array();
        $listWheres = array();
        if(count($this->listConditions) > 0){
            foreach($this->listConditions as $listCondition){
                $exprArray = $listCondition->getExpression($this->listModuleID);
                $listFroms = array_merge($exprArray['joins'], $listFroms);
                $listWheres[] = $exprArray['expression'];
            }
        }

//        $listFroms = array_merge($listFroms, $listIDField->makeJoinDef($this->listModuleID));
//        $listFroms = array_merge($listFroms, $listNameField->makeJoinDef($this->listModuleID));

        $listFroms = array_merge($listFroms, GetJoinDef($this->availableIDField, $this->listModuleID));
        $listFroms = array_merge($listFroms, GetJoinDef($this->availableNameField, $this->listModuleID));

        foreach($listFroms as $alias => $def){
            $SQL .= "$def\n";
        }
//print "listFroms:\n";  
//print_r($listFroms);

        $SQL .= "WHERE\n";
        $SQL .= "{$this->listModuleID}._Deleted = 0";
        if(!empty($listModuleField->listCondition)){
            $SQL .= " AND {$this->listModuleID}.{$listModuleField->listCondition}\n";
        }

        if(count($listWheres > 0)){
            foreach($listWheres as $listWhere){
                $SQL .= " AND {$listWhere}\n";
            }
        }
        $this->listAvailableSQL = $SQL;

    }
    
    function init($conditions) {
        /*
        SQL value placeholders:
        -------------------------
        *recordID*  - (parent) recordID
        *userID*    - PersonID of user who updates
        *value*     - ID of an "available" item
        *rowID*     - record id of record from $this->moduleID
        *deleted*   - 
        
        */
    
        global $SQLBaseModuleID;
        $SQLBaseModuleID = $this->moduleID;
    
        $ModuleFields = GetModuleFields($this->moduleID);
        $listModuleField = $ModuleFields[$this->primaryListField];
        $listKeyModuleField = $ModuleFields[$listModuleField->localKey];
        
        print "selectgrid key data type {$listKeyModuleField->dataType}\n";
        if('varchar(5)' == $listKeyModuleField->dataType){
            $this->listKeyType = 'modID';
        }
        
        $moduleInfo = GetModuleInfo($this->moduleID);
        $module_PK = $moduleInfo->getPKField();
        
        //if the field we're saving to is a RemoteField, then we need to save both a local record and a record in the remote module.
        if('remotefield' == get_class($listKeyModuleField)){
            $this->useRemoteField = true;
            $remoteModuleInfo = GetModuleInfo($listKeyModuleField->remoteModuleID);
            $remoteModule_PK = $remoteModuleInfo->getPKField();
            
        }
        
        $aConditionStrings = array();
        $strInsertConditionFields = '';
        $strInsertConditionValues = '';
        $aInsertConditionFields = array();
        $aInsertConditionValues = array();
        /*
print_r($conditions);
die('Seectgrid conditions');
        */
        if(count($conditions) > 0){
            foreach($conditions as $conditionField => $conditionValue){
                $aConditionStrings[] = "{$this->moduleID}.{$conditionField} = '$conditionValue'";
                
                //if(FALSE !== strpos($conditionField,$this->moduleID.'.')){
                    $aInsertConditionFields[] = str_replace($this->moduleID.'.', '', $conditionField);
                    $aInsertConditionValues[] = $conditionValue;
                //}
            }
            $strConditions =  join(' AND ', $aConditionStrings);

            $strInsertConditionFields = join(',', $aInsertConditionFields).',';
            $strInsertConditionValues = '\''.join('\',\'', $aInsertConditionValues).'\',';
        }
        
        
        //gets existing, non-deleted selected items
        $SQL = 'SELECT '; 
//        $SQL .= $listKeyModuleField->makeSelectDef($this->moduleID, false) . ' AS ID, ';
//        $SQL .= $listModuleField->makeSelectDef($this->moduleID, false) . ' AS Name ';
        $SQL .= GetQualifiedName($listKeyModuleField->name) . ' AS ID, ';
        $SQL .= GetQualifiedName($listModuleField->name) . ' AS Name ';

        $SQL .= " FROM {$this->moduleID} "; 

        //$joins = $listKeyModuleField->makeJoinDef($this->moduleID);
        //$joins = array_merge($listModuleField->makeJoinDef($this->moduleID), $joins);

        $joins = GetJoinDef($listKeyModuleField->name);
        $joins = array_merge($joins, GetJoinDef($listModuleField->name));

        $joins = SortJoins($joins);
        
        if(count($joins) > 0){
            foreach($joins as $j){
                $SQL .= " $j\n";
            }
        }
        $SQL .= ' WHERE '; 
        //$SQL .= $this->ParentRowSQL;
        /* included in ParentRowSQL...
        */
        if(!empty($strConditions)){
            //$SQL .= ' AND '.$strConditions; //join(' AND ', $aConditionStrings);
            $SQL .= ' '.$strConditions;
        }
        $this->listSelectedSQL = $SQL . " AND {$this->moduleID}._Deleted = 0 ORDER BY Name";
        
        
        //gets existing selected items, _Deleted as a column
        $this->listExistingSelectedSQL = str_replace(' FROM', ", {$this->moduleID}._Deleted FROM", $SQL);
        
        
        
        if(!$this->useRemoteField){
            $this->insertSQL = 
                "INSERT INTO {$this->moduleID} ({$listKeyModuleField->name}, $strInsertConditionFields _ModBy, _ModDate) VALUES ('/*value*/', $strInsertConditionValues /*userID*/, NOW());";
                
            //stmt to translate actual record id to row ids 
            $this->getRemoteIDSQL = "SELECT {$module_PK} AS RowID FROM {$listKeyModuleField->moduleID} WHERE {$listKeyModuleField->name} = '/*value*/'";
            if(!empty($strConditions)){
                $this->getRemoteIDSQL .= ' AND '.$strConditions;
            }
            
            $this->logSQL = 
                "INSERT INTO {$this->moduleID}_l ({$listKeyModuleField->name}, $strInsertConditionFields _ModBy, _ModDate, _Deleted) VALUES ('/*value*/', $strInsertConditionValues /*userID*/, NOW(), /*deleted*/);";
            
        } else {
            $this->insertSQL = 
                "INSERT INTO {$this->moduleID} ($strInsertConditionFields _ModBy, _ModDate) VALUES ($strInsertConditionValues /*userID*/, NOW());";

//print $this->insertSQL;            
            $remoteDescriptorField = '';
            $remoteDescriptor = '';
            $remoteDescriptorCondition = '';            
            if(!empty($listKeyModuleField->remoteDescriptorField)){
                $remoteDescriptorField = $listKeyModuleField->remoteDescriptorField.',';
                $remoteDescriptor = $listKeyModuleField->remoteDescriptor.',';
                $remoteDescriptorCondition = " AND {$listKeyModuleField->remoteDescriptorField} = '{$listKeyModuleField->remoteDescriptor}'";
//print "found remoteDescriptorField\n";
            }

            $this->insertRemoteSQL = 
                "INSERT INTO {$listKeyModuleField->remoteModuleID}
                ({$listKeyModuleField->remoteModuleIDField},
                {$listKeyModuleField->remoteRecordIDField}, 
                {$listKeyModuleField->remoteField}, 
                {$remoteDescriptorField}
                _ModBy, _ModDate) VALUES ('{$this->moduleID}', 
                /*recordID*/, 
                /*value*/,
                {$remoteDescriptor}
                /*userID*/, NOW()
                );";
//print $this->insertRemoteSQL;
                
            $this->removeRemoteSQL = "UPDATE {$listKeyModuleField->remoteModuleID} SET 
                _Deleted = 1,
                _ModBy = /*userID*/, 
                _ModDate = NOW()
            WHERE 
                {$listKeyModuleField->remoteModuleIDField} = '{$this->moduleID}' AND
                {$listKeyModuleField->remoteRecordIDField} = /*rowID*/ $remoteDescriptorCondition";
                
            //this is simply the reverse
            $this->restoreRemoteSQL = str_replace('_Deleted = 1', '_Deleted = 0', $this->removeRemoteSQL);
            
            //stmt to translate actual record (person) id to row ids 
            $this->getRemoteIDSQL = "SELECT {$listKeyModuleField->remoteRecordIDField} AS RowID FROM {$listKeyModuleField->remoteModuleID} WHERE {$listKeyModuleField->remoteField} = /*value*/
            AND {$listKeyModuleField->remoteModuleIDField} = '{$this->moduleID}'
            $remoteDescriptorCondition ORDER BY RowID DESC LIMIT 1";


            //stmt to tranlate local rowIDs to remote rowIDs
            $this->getRemoteRowIDSQL = "SELECT $remoteModule_PK AS RowID FROM
            {$listKeyModuleField->remoteModuleID} WHERE
            {$listKeyModuleField->remoteModuleIDField} = '{$this->moduleID}' AND
            {$listKeyModuleField->remoteRecordIDField} = /*recordID*/ $remoteDescriptorCondition  ORDER BY RowID DESC LIMIT 1";
            
            //log SQL statements
            $this->logRemoteSQL = 
                "INSERT INTO {$listKeyModuleField->remoteModuleID}_l
                ($remoteModule_PK,
                {$listKeyModuleField->remoteModuleIDField},
                {$listKeyModuleField->remoteRecordIDField}, 
                {$listKeyModuleField->remoteField}, 
                {$remoteDescriptorField}
                _ModBy, _ModDate, _Deleted) VALUES (
                /*rowID*/, 
                '{$this->moduleID}', 
                /*recordID*/, 
                /*value*/,
                {$remoteDescriptor}
                /*userID*/, NOW(), /*deleted*/
                );";
                
            //log statement
            $this->logSQL = 
                "INSERT INTO {$this->moduleID}_l ($module_PK, $strInsertConditionFields _ModBy, _ModDate, _Deleted) VALUES (/*rowID*/,  $strInsertConditionValues /*userID*/, NOW(), /*deleted*/);";

        }
        
        //Remove SQL
        $SQL = "UPDATE {$this->moduleID} SET 
                _Deleted = 1,
                _ModBy = /*userID*/, 
                _ModDate = NOW()
            WHERE ";
//$SQL .= $this->ParentRowSQL;
        $SQL .= "{$module_PK} = /*rowID*/";
        if(!empty($strConditions)){
            $SQL .= ' AND '.$strConditions;
        }
        $this->removeSQL = $SQL;
                
        //Restore SQL: this is simply the reverse
        $this->restoreSQL = str_replace('_Deleted = 1', '_Deleted = 0', $this->removeSQL);

    }
    
    function render($page, $qsArgs){
        
        //add grid ID to all links
        $qsArgs['gid'] = $this->number;

        //make form query string
        $formQS = MakeQS($qsArgs);
        
            
        global $dbh;
        global $data;
        global $User;
        global $recordID;
    
        // create a new instance of JSON
        require_once(THIRD_PARTY_PATH . '/JSON.php');
        $json = new JSON();

        //use a generated List SQL statement...
//print debug_r($this->listAvailableSQL);
        $SQL = $this->listAvailableSQL . ' ORDER BY Name';
                
//print 'listAvailableSQL:'.debug_r($SQL);
        $available = $dbh->getAssoc($SQL);  
        $js_available =  $json->encode($available);
        
        
        //get already selected
        
        //$SQL = sprintf($this->listSelectedSQL, $recordID);
        $SQL = str_replace('/*recordID*/', $recordID, $this->listSelectedSQL);
//print debug_r($SQL);
        $selected = $dbh->getAssoc($SQL);  
        $js_selected =  $json->encode($selected);
        
        $phrase = $this->phrase;
        if(empty($phrase)){
            $phrase = 'Select';
        }

        $gridTitle = sprintf(
            GRID_TAB,
            $this->phrase,
            'list.php?mdl='.$this->moduleID,
            'frames_popup.php?dest=list&mdl='.$this->moduleID,
            ''
            );

        

        $content = "
        <script language=\"JavaScript1.2\">
            var available = $js_available;
            var selected = $js_selected;
        </script>
        <script language=\"JavaScript1.2\" src=\"js/selectGrid.js\"></script>";
        $content .= '<div id="sg" class="sz2tabs">';
        $content .= "$gridTitle
        <form name=\"searchForm\" method=\"post\" action=\"edit.php?$formQS\">
        <table class=\"frm\">
        <input type=\"hidden\" name=\"SaveIDs\" value=\"\"/>
        <input type=\"hidden\" name=\"gridnum\" value=\"{$this->number}\"/>
        ";
        
        $phrases = array('OrganizationID' => 'Organization', 'DepartmentID' => 'Department');
        
        $content .= sprintf(FORM_BUTTONROW_HTML, 
            sprintf(
                FORM_BUTTON_HTML,
                'SelectAll_btn', 
                gettext("Add All"), 
                "selectAll()"
                ).' '.
            sprintf(
                FORM_BUTTON_HTML,
                'UnelectAll_btn', 
                gettext("Remove All"), 
                "unselectAll()"
                ).' &nbsp; '.
            sprintf(
                FORM_BUTTON_HTML,
                'Save_btn', 
                gettext("Save"), 
                "saveSelected()"
                )
            );
        //$content .= "</form></table>";
        $content .= '<tr><td valign="top">';
        
        $content .= 
        '<div class="sgList">
            <div class="sgTitle">Available:</div>
            <div id="sgAvailable">
            <ul id="availableList">
            </ul>
            </div>
        </div>';
        $content .= '</td><td valign="top">';
        $content .=
        '<div class="sgList">
            <div class="sgTitle">Selected:</div>
            <div id="sgSelected">
            <ul id="selectedList">
            </ul>
            </div>
        </div>';
        $content .= "</td></tr>";
        $content .= "</table>";
        $content .= "</form>";
        $content .= '</div>';
        //initially display "available" and "selected" list
        $content .= "<script language=\"JavaScript1.2\">
            listAvailable(available);
            initSelected(selected);
        </script>";
        
        
       // $content .= debug_r($search->getListSQL());
       // $content .= debug_r($this);
        
        
        return $content;
    }
    
    function handleForm(){
        if($_POST['gridnum'] == $this->number){
        //  print debug_r($_POST);
        //  print debug_r($this);

            global $recordID;
            global $dbh;
            global $User;
                
            
            
            //check whether user has permission to edit at all
            if(! $User->PermissionToEdit($this->moduleID) ){
                $moduleInfo = GetModuleInfo($this->moduleID);
                $moduleName = $moduleInfo->getProperty('moduleName');
                die(sprintf(gettext("You have no permissions to edit records in the %s module."), $moduleName) . "<br />\n");
            }
            
            $postedIDs = array();
            
            if(!empty($_POST['SaveIDs'])){
                //sanitize posted data:
                $uncleanDataArray = split(' ', trim($_POST['SaveIDs']));
//print "SaveIDs: ".debug_r($_POST['SaveIDs']);
    
                //different cleanup if we're dealing with ModuleIDs...
                if('modID' == $this->listKeyType){
                    foreach($uncleanDataArray as $unclean){
                        $postedIDs[] = substr($unclean,0,5);
                    }
                } else {
                    foreach($uncleanDataArray as $unclean){
                        $postedIDs[] = intval($unclean);
                    }
                }
            } else {
                $uncleanDataArray = array();
            }
            
            //start transaction
            $SQL = "BEGIN;";
            $r = $dbh->query($SQL);
            dbErrorCheck($r);
                    
            //get info on selected items (including existing but _Deleted records)
            //$existingDataSet = $dbh->getAll(sprintf($this->listExistingSelectedSQL, $recordID), DB_FETCHMODE_ASSOC);
            $SQL = str_replace('/*recordID*/', $recordID, $this->listExistingSelectedSQL);
            $existingDataSet = $dbh->getAll($SQL, DB_FETCHMODE_ASSOC);
//print "listExistingSelectedSQL {$SQL} <br/>\n";
//print debug_r($existingDataSet);

            
            dbErrorCheck($existingDataSet);
            $existing = array();
            $active = array();
            $inactive = array();
            foreach($existingDataSet as $existingRow){
                if(0 != $existingRow['_Deleted']){
                    $inactive[] = $existingRow['ID'];
                } else {
                    $active[] = $existingRow['ID'];
                }
                $existing[] = $existingRow['ID'];
            }
            
//print "existing: ".debug_r($existing);
//print "inactive: ".debug_r($inactive);
//print "active: ".debug_r($active);
            

            //insert any never before selected IDs
            if(count($postedIDs) > 0){
                foreach($postedIDs as $postedID){
                    if(!in_array($postedID, $existing)){
                        //main record
                        $iSQL = str_replace(array('/*value*/','/*recordID*/', '/*rowID*/', '/*userID*/'), array($postedID, $recordID, $rowID, $User->PersonID), $this->insertSQL); 
//print "insertSQL: $iSQL <br/>\n";
                        $r = $dbh->query($iSQL);
                        dbErrorCheck($r);
                        
                        $SQL = "SELECT LAST_INSERT_ID();";
                        $subrecordID = $dbh->getOne($SQL);
                        dbErrorCheck($subrecordID);
                            
                        if($this->useRemoteField) {
                            //remote record
                            $iSQL = str_replace(array('/*recordID*/', '/*value*/', '/*userID*/'), array($subrecordID, $postedID, $User->PersonID), $this->insertRemoteSQL); 
//print "insert remote: $iSQL <br/>\n";
                            $r = $dbh->query($iSQL);
                            dbErrorCheck($r);
                        
                            $SQL = "SELECT LAST_INSERT_ID();";
                            $remoteRecordID = $dbh->getOne($SQL);
                            dbErrorCheck($remoteRecordID);
                        }
                        
                        //main log record
                        $lSQL = str_replace(array('/*rowID*/', '/*value*/', '/*recordID*/', '/*userID*/', '/*deleted*/'), array($subrecordID, $postedID, $postedID, $User->PersonID, '0'), $this->logSQL);
//print "log main: $lSQL <br/>\n";    
                        $r = $dbh->query($lSQL);
                        dbErrorCheck($r);
                        
                        if($this->useRemoteField) {
                            //remote log record
                            $lSQL = str_replace(array('/*rowID*/', '/*recordID*/', '/*value*/', '/*userID*/', '/*deleted*/'), array($remoteRecordID, $subrecordID, $postedID, $User->PersonID, '0'), $this->logRemoteSQL);
//print "log remote : $lSQL <br/>\n";    
                            $r = $dbh->query($lSQL);
                            dbErrorCheck($r);
                        }
                    }
                }
            }
            
//print debug_r($this->getRemoteIDSQL);
            //update existing but _Deleted IDs, reverting _Deleted to FALSE
            if(count($postedIDs) > 0){
                foreach($postedIDs as $postedID){
                
                    if(in_array($postedID, $inactive)){
//print "inactive: $postedID<br>\n";
                        //get recordID from local table
                        $getRemoteIDSQL = str_replace(
                            array('/*value*/', '/*recordID*/'), 
                            array($postedID, $recordID), 
                            $this->getRemoteIDSQL
                        );
                        $rowID = $dbh->getOne($getRemoteIDSQL);
                        dbErrorCheck($rowID);
//print "getRemoteIDSQL: $getRemoteIDSQL returned \$rowID = $rowID<br/>\n";
//print "<br/>\n";
                        if($this->useRemoteField) {
//    print "going to restore remote row $rowID, person $postedID<br>\n";
                            //remote record
                            $rSQL = str_replace(array('/*rowID*/', '/*recordID*/', '/*userID*/'), array($rowID, $recordID, $User->PersonID), $this->restoreRemoteSQL); 
//print "remote restore: $rSQL <br/>\n";
                            $r = $dbh->query($rSQL);
                            dbErrorCheck($r);
                        }

//print "local restoreSQL: {$this->restoreSQL} <br/>\n";                        
                        //main record
                        $rSQL = str_replace(
                            array('/*rowID*/', '/*value*/', '/*recordID*/', '/*userID*/'), 
                            array($rowID, $recordID, $recordID, $User->PersonID), 
                            $this->restoreSQL
                        );
//print "local restoreSQL: $rSQL <br/>\n";
                        $r = $dbh->query($rSQL);
                        dbErrorCheck($r);
                        
                        //main log record
                        $lSQL = str_replace(array('/*rowID*/', '/*value*/', '/*recordID*/', '/*userID*/', '/*deleted*/'), array($rowID, $postedID, $recordID, $User->PersonID, '0'), $this->logSQL);
//print "main restore logSQL $lSQL <br/>\n";    
                        $r = $dbh->query($lSQL);
                        dbErrorCheck($r);
                        
                        if($this->useRemoteField) {
                            //get recordID of remote table (so we can log it)
                            $SQL = str_replace(array('/*recordID*/'), array($rowID), $this->getRemoteRowIDSQL);
//print "(restore) remote rowID: $SQL <br/>\n";
                            $remoteRowID = $dbh->getOne($SQL);
                            dbErrorCheck($remoteRowID);
                            
                            //remote log record
                            $lSQL = str_replace(array('/*rowID*/', '/*recordID*/', '/*value*/', '/*userID*/', '/*deleted*/'), array($remoteRowID, $rowID, $postedID, $User->PersonID, '0'), $this->logRemoteSQL);
//print "(restore) remote log: $lSQL <br/>\n";    
                            $r = $dbh->query($lSQL);
                            dbErrorCheck($r);
                        }
                    }
                    
                }
            }
            
            //update removed IDs by setting _Deleted to TRUE
            if(count($active) > 0){
                foreach($active as $activeID){
                    if(!in_array($activeID, $postedIDs)){
//print "removing $activeID<br>\n";
                        $getRemoteIDSQL = str_replace(array('/*value*/','/*recordID*/'), array($activeID, $recordID), $this->getRemoteIDSQL);
                        $rowID = $dbh->getOne($getRemoteIDSQL);
//print debug_r($this->getRemoteIDSQL, 'getRemoteIDSQL');
//print "getRemoteIDSQL: $getRemoteIDSQL <br/>\n";
//print "<br/>\n";

                        dbErrorCheck($rowID);
//print "getRemoteIDSQL: $getRemoteIDSQL <br/>\n";
                
                        if($this->useRemoteField) {
                            //remote record
                            $rSQL = str_replace(array('/*rowID*/', '/*userID*/'), array($rowID, $User->PersonID), $this->removeRemoteSQL); 
//print "removeRemoteSQL: $rSQL <br/>\n";
                            $r = $dbh->query($rSQL);
                            dbErrorCheck($r);
                    } 
                        
                        //main record
                        $rSQL = str_replace(array('/*recordID*/', '/*rowID*/', '/*userID*/'), array($recordID, $rowID, $User->PersonID), $this->removeSQL); 
//print "removeSQL: $rSQL <br/>\n";
                        $r = $dbh->query($rSQL);
                        dbErrorCheck($r);
                        
                        
                        //main log record
                        $lSQL = str_replace(array('/*rowID*/', '/*value*/', '/*recordID*/', '/*userID*/', '/*deleted*/'), array($rowID, $activeID, $activeID, $User->PersonID, '1'), $this->logSQL);
//print "main log: $lSQL <br/>\n";    
                        $r = $dbh->query($lSQL);
                        dbErrorCheck($r);
                        
                        if($this->useRemoteField) {
                            //get recordID of remote table (so we can log it)
                            $SQL = str_replace(array('/*recordID*/'), array($rowID), $this->getRemoteRowIDSQL);
//print "(remove) remote rowID: $SQL <br/>\n";
                            $remoteRowID = $dbh->getOne($SQL);
                            dbErrorCheck($remoteRowID);
                            
                            //remote log record
                            $lSQL = str_replace(array('/*rowID*/', '/*recordID*/', '/*value*/', '/*userID*/', '/*deleted*/'), array($remoteRowID, $rowID, $activeID, $User->PersonID, '1'), $this->logRemoteSQL);
//print "(remove) remote log: $lSQL <br/>\n";    
                            $r = $dbh->query($lSQL);
                            dbErrorCheck($r);
                        }
                    }
                }
            }
            
           
            
            
            
            $SQL = "COMMIT;";
            $r = $dbh->query($SQL);
            dbErrorCheck($r);
        }
    }
    
} //end SelectGrid class

class CodeSelectGrid extends SelectGrid
{
}


class SearchSelectGrid extends SelectGrid
{
var $searchFields = array(); 

function Factory($element, $moduleID)
{
    $debug_prefix = debug_indent("SearchSelectGrid::Factory():");

    $module = GetModule($moduleID);
    $subModuleID = $element->attributes['moduleID'];
    $subModule = $module->SubModules[$subModuleID];

    //check for fields in the element: if there are none, we will import from the Exports section of the sub-module
    if('SearchSelectGrid' == $element->type && count($element->c) == 0){
        //print_r($element);
    
        $exports_element = $subModule->_map->selectFirstElement('Exports');
        if(empty($exports_element)){
            die("$debug_prefix Can't find an Exports section in the $subModuleID module.");
        }
        
        $grid_element = $exports_element->selectFirstElement('SearchSelectGrid');
        if(empty($grid_element)){
            die("$debug_prefix Can't find a matching check grid in the $subModuleID module.");
        }
        
        //copy all the fields of the imported grid to the current element
        $element->c = $grid_element->c;
        
        //copy attributes but allow existing attributes to override
        foreach($grid_element->attributes as $attrName => $attrValue){
            if(empty($element->attributes[$attrName])){
                $element->attributes[$attrName] = $attrValue;
            }
        }
    }

    print "$debug_prefix $subModuleID\n";  
  
    $grid = new SearchSelectGrid(
        $subModuleID,
        $element->attributes['phrase'],
        $subModule->localKey,
        $subModule->conditions,
        $element->attributes['primaryListField']
    );
    
    $listModuleField = GetModuleField($grid->moduleID, $grid->primaryListField);
    $grid->listModuleID = $listModuleField->foreignTable;
    
    $grid->availableIDField = $listModuleField->foreignKey;
    $grid->availableNameField = $listModuleField->foreignField;
        
    //append fields
    if(count($element->c) > 0){
        foreach($element->c as $sub_element){
            if('SearchForm' == $sub_element->type){
                foreach($sub_element->c as $field_element){

                    $field_element->attributes['formName'] = 'searchForm';
                    print "$debug_prefix SearchForm: adding field:\n";
                    indent_print_r($field_element);
                    //$field_object = $field_element->createObject($grid->listModuleID, $field_element->type);
                    $field_object = $field_element->createObject($grid->moduleID, $field_element->type, $grid);
                    $grid->AddSearchField($field_object);
                    
                }
            } else {
                $type = str_replace('Grid', '', $sub_element->type);
                
                $field_object = $sub_element->createObject($subModuleID, $type, $grid);
                $sub_element->attributes['formName'] = $subModuleID;
                
                $field_object->phrase = $subModule->ModuleFields[$field_object->name]->phrase;
                
                $grid->AddField($field_object);
            }
        }
    }
    
    
    
    
    //add RemoteFields
    $grid->prepRemoteFields();
    
    debug_unindent();
    return $grid;
}


function SearchSelectGrid (
    $pModuleID, 
    $pPhrase, 
    $pLocalKey, 
    $conditions, 
    $primaryListField
    )
{
    $debug_prefix = debug_indent("SearchSelectGrid [constructor] {$pModuleID}:");

    print "$debug_prefix ($pModuleID - $primaryListField)...\n";  
    $this->moduleID = $pModuleID;
    $this->phrase = $pPhrase;

    $this->primaryListField = $primaryListField;
    $listModuleField = GetModuleField($this->moduleID, $this->primaryListField);
    $this->listModuleID = $listModuleField->foreignTable;
    
    $this->availableIDField = $listModuleField->foreignKey;
    $this->availableNameField = $listModuleField->foreignField;
    $this->localKey = $pLocalKey;
        
    $subModule = GetModule($this->moduleID);
    $conditions = $subModule->conditions;
    $conditionStrings = array();
    
    if(!empty($subModule->localKey)){
        if(!$this->listExtended){
            $localKey = $subModule->localKey;
            $conditions[$localKey] = "/*recordID*/";
        }
    }
    foreach($conditions as $conditionField => $conditionValue){
        $conditionStrings[] = "{$this->moduleID}.$conditionField = '$conditionValue'";
    }

    $this->init($conditions);
    debug_unindent();
}



function AddSearchField($field){
    $this->searchFields[$field->name] = $field;
}

function render($page, $qsArgs){
    
    //add grid ID to all links
    $qsArgs['gid'] = $this->number;

    //make form query string
    $formQS = MakeQS($qsArgs);
    
    //look for search object
        //how to include the Search class w/o error msgs??? (maybe not needed anyway...)
    //$search = $_SESSION['Search_'.$this->listModuleID];
    
    //generate a default search if needed (fields in SearchForm must be available on current screen)
    //if(empty($search)){
        
    global $dbh;
    global $data;
    global $User;
    global $recordID;

    include_once(CLASSES_PATH . '/search.class.php');

    $search = new Search(
        $this->listModuleID,
        array('ID' => $this->availableIDField, 'Name' => $this->availableNameField), // ID, Name
        $this->searchFields,
        $data //values from $data
        );
    $_SESSION['Search_ssg_'.$this->listModuleID] = $search;

    //}
    
    // create a new instance of JSON
    require_once(THIRD_PARTY_PATH. '/JSON.php');
    $json = new JSON();


    
    $search->prepareFroms(&$this->searchFields, &$data);
    //$SQL = $search->getListSQL($this->availableNameField);
    $SQL = $search->getListSQL('Name');
//print debug_r($SQL);
    $available = $dbh->getAssoc($SQL);  
    $js_available =  $json->encode($available);
    
    
    //get already selected
    
    //$SQL = sprintf($this->listSelectedSQL, $recordID);
    $SQL = str_replace('/*recordID*/', $recordID, $this->listSelectedSQL);
//print debug_r($SQL);
    $selected = $dbh->getAssoc($SQL);  
    $js_selected =  $json->encode($selected);
    

    $content = '<div id="sg" class="sz2tabs">';
    
    $searchFieldNames = array_keys($this->searchFields);
    $jsSearchFieldNames = '"' . join('","', $searchFieldNames) . '"';

    //$filterPhrase = gettext("Choose people to add by first searching by Organization and Department. Click on available names to select.");
    
    $content .= "        
    <script language=\"JavaScript1.2\">
        var searchFieldNames = Array($jsSearchFieldNames);
        var available = $js_available;
        var selected = $js_selected;
    </script>
    <script language=\"JavaScript1.2\" src=\"js/CBs.js\"></script>
    <script language=\"JavaScript1.2\" src=\"3rdparty/filtery.js\"></script>
    <script language=\"JavaScript1.2\" src=\"3rdparty/DataRequestor.js\"></script>
    <script language=\"JavaScript1.2\" src=\"js/selectGrid.js\"></script>
    
    <div class=\"gridtitle\">
        {$this->phrase}
    </div>
    <form name=\"searchForm\" method=\"post\" action=\"edit.php?$formQS\">
    <table class=\"frm\">
    
    <input type=\"hidden\" name=\"SaveIDs\" value=\"\"/>
    <input type=\"hidden\" name=\"gridnum\" value=\"{$this->number}\"/>
    ";
    
    $phrases = array('OrganizationID' => 'Organization', 'DepartmentID' => 'Department');
    
    //display search form
    foreach($this->searchFields as $searchField){
        $content .= $searchField->render($data, $phrases);
    }
    
    //how to pass values in form to fsgGetAvailable???
    //how to reference them at all???
    $content .= sprintf(FORM_BUTTONROW_HTML, 
        sprintf(
            FORM_BUTTON_HTML,
            'Search_btn', 
            gettext("Search"), 
            "loadData('{$this->listModuleID}')"
            ).' '.
        sprintf(
            FORM_BUTTON_HTML,
            'SelectAll_btn', 
            gettext("Add All"), 
            "selectAll()"
            ).' '.
        sprintf(
            FORM_BUTTON_HTML,
            'UnelectAll_btn', 
            gettext("Remove All"), 
            "unselectAll()"
            ).' &nbsp; '.
        sprintf(
            FORM_BUTTON_HTML,
            'Save_btn', 
            gettext("Save"), 
            "saveSelected()"
            )
        );
    $content .= '<tr><td valign="top">';
    //$content .= "</form></table>";
            
    $content .= 
    '<div class="sgList">
        <div class="sgTitle">Available:</div>
        <div id="sgAvailable">
        <ul id="availableList">
        
        </ul>   
        </div>
    </div>';
    $content .= '</td><td valign="top">';
    $content .= '<div class="sgList">
        <div class="sgTitle">Selected:</div>
        <div id="sgSelected">
        <ul id="selectedList">
        
        </ul>
        </div>
    </div>';
    $content .= "</td></tr>";
    $content .= "</table>";
    $content .= "</form>";
    //initially display "available" list
    $content .= "<script language=\"JavaScript1.2\">
        listAvailable(available);
    </script>";
    //initially display "selected" list
    $content .= "<script language=\"JavaScript1.2\">
        initSelected(selected);
    </script>";
    // $content .= debug_r($search->getListSQL());
    
    
    
    return $content;
}
} //end SearchSelectGrid class



    
?>