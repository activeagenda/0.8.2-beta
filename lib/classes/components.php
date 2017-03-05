<?php
/**
 *  Renderable screen component classes
 *
 *  This file contains abstract class definitions for screen components,
 *  as well as concrete implementations of screen field classes.  For
 *  grids, see the grids.php file.
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
 * @version        SVN: $Revision: 513 $
 * @last-modified  SVN: $Date: 2007-02-19 16:17:44 -0800 (Mon, 19 Feb 2007) $
 */




/**
 *  Root class for all renderable controls, i.e. screen fields and grids
 */
class ScreenControl
{
var $name;

/**
 * Abstract factory class
 */
function Factory($element, $moduleID)
{
    return false;
}


/**
 * Whether the control is editable.
 *
 * This is implemented as a method in order simulate a class property.
 */
function isEditable()
{
    return false;
}


/**
 *  Checks whether there is a (display) condition assigned to the control, and evaluates it.
 *
 * Caution: This method is not much used, if at all. May need some overhaul if conditional
 * displays are needed.
 */
function checkCondition(&$values)
{
    return true; //phasing this method out | MJT
}


/**
 * Abstract render function. This would generate the HTML code that displays the control.
 */
function render()
{
    return "//undefined\n";
}


/**
 * Determines whether the control should be displayed or not.
 */
function isVisible()
{
    return true;
}
} //end class ScreenControl





/**
 *  Root class for all renderable fields
 */
class ScreenField extends ScreenControl {
var $parentName; //name of containing parent field: blank if not a sub-field
var $Fields = array(); //a collection of subfields (objects) to be displayed together with the current field
var $phrase; //only used in grids, really
var $dataType;
var $validate;
var $invalid;
var $formName; //'mainForm' if not in a grid
var $gridAlign; //left, right or center: the alignment of the data in a grid cell
var $displayFormat;  //sprintf-style formatting string.
var $isDefault;

/**
 * Creates a documentation object
 */
function DocFactory($element, $moduleID){
    return new ScreenFieldDoc($element, $moduleID);
}


function isVisible()
{
    //function to determine whether a control should be displayed
    return true;
}


function needsReGet()
{
    //determines whether an EditScreen needs to re-load data after saving
    return false;
}


//generates the output to be displayed in a form
function render(&$values, &$phrases)
{
    //check render condition
    if($this->checkCondition(&$values)){

        //get content from subfields
        switch (count($this->Fields)){
        case 0:
            //just use the data for this field
            $data = $this->simpleRender(&$values);
            break;
        case 1:
            $data = $this->simpleRender(&$values);
            $subField = end($this->Fields);
            $data .= $subField->simpleRender(&$values);
            break;
        default:
            //just use the data from the subfields - this field is repeated here
            foreach($this->Fields as $key => $subField){
                if ('Self' == $key) {
                    $data = $this->simpleRender(&$values);
                } else {
                    $data .= $subField->simpleRender(&$values);
                }
            }
            break;
        }

        if($this->isSubField()){
            //simply return the content padded w/ spaces
            $content = ' '.$data.' ';
        } else {
            if(strlen($this->validate)){
                if($this->invalid){
                    $required = 'flblh flbl';
                } else {
                    if(FALSE !== strpos($this->validate, 'notEmpty')){
                        $required = 'flblr flbl';
                    } elseif(FALSE !== strpos($this->validate, 'RequireSelection')) {
                        $required = 'flblr flbl';
                    } else {
                        $required = 'flbl';
                    }
                }

            } else {
                //print $this->name . ": nonreq<br>";
                $required = 'flbl';
            }
            $phrase = $phrases[$this->name];
            if(empty($phrase)){
                $phrase = $this->phrase;
            }
            $formName = $this->formName;
            if(empty($formName)){
                $formName = 'mainForm';
            }
            if($this->isDefault){
                $unsavedClass = 'unsaved';
            } else {
                $unsavedClass = '';
            }
            $content = sprintf(
                FIELD_HTML,
                LongPhrase($phrase),
                ShortPhrase($phrase),
                $data,
                $required,
                $formName.'.'.$this->name,
                $unsavedClass
                );
        }
    } else {
        //$content = "field {$this->name} blocked by condition<br>";
        $content = '';
    }
    return $content;
}


function simpleRender(&$values)
{
    return '';//override this
}


function gridHeaderPhrase()
{
    if(empty($this->phrase)){
        return $this->name;
    } else {
        return ShortPhrase(gettext($this->phrase));
    }
}


function gridViewRender(&$pRow)
{
    return $this->viewRender(&$pRow);
}


function gridEditRender(&$pRow)
{

    if(!is_array($pRow)){
        $pRow = array($this->name => '');
    }
    return $this->simpleRender(&$pRow);
}


function checkGridRender(&$pRow)
{
    return $this->viewRender(&$pRow);
}


function gridFormRender(&$pRow)
{

    if(!is_array($pRow)){
        $pRow = array($this->name => '');
    }
    //we create a temporary array just to pass it on to the render function.
    //this is somewhat ineffective but allows code reuse and not having to change the render() function.
    $phrases = array();
    $phrases[$this->name] = $this->phrase;

    return $this->render(&$pRow, $phrases);
}


//used in grids and by ViewField
function viewRender(&$values)
{
    global $User;

    $rawValue = $values[$this->name];

    switch ($this->dataType){
    case 'bool':

        //now uses joins to code type 1
        switch(strval($rawValue)) {
        case '1':
            $content = gettext("Yes");
            break;
        case '0':
        case '-1': //ought to be saved as zero
            $content = gettext("No");
            break;
        default:
            $content = gettext("No data") . $rawValue;
        }
        break;
    case 'date':

        //check for nulls and empty values
        if ((null == $values[$this->name]) || ('0000-00-00' == $rawValue)) {
            $content = gettext("(no date set)");
        } else {
            //if the date is < year 2038, format it
            if (intval(substr($rawValue, 0,4)) < 2038){
                $content = strftime(
                    $User->getDateFormatPHP(),
                    strtotime($rawValue)
                );
            } else {
                //formatting function can't format it, just print it
                $content = $rawValue;
            }
        }
        break;
    case 'datetime':

        //check for nulls and empty values
        if ((null == $rawValue) || ('0000-00-00' == $rawValue)) {
            $content = gettext("(no date/time set)");
        } else {
            //if the date is < year 2038, format it
            if (intval(substr($rawValue, 0,4)) < 2038){
                $content = strftime(
                    $User->getDateTimeFormatPHP(),
                    strtotime($rawValue)
                );
            } else {
                //formatting function can't format it, just print it
                $content = $rawValue;
            }
        }
        break;
    case 'money':
        //we want to display only two decimals unless last two aren't zero.
        $nVal = 100 * $rawValue;

        if(floor($nVal) == $nVal){
            $content = MASTER_CURRENCY.' '.number_format($rawValue, 2);
        } else { //there are more than 2 decimals so display the whole thing
            $content = MASTER_CURRENCY.' '.number_format($rawValue, 4);
        }
        break;
    case 'varchar(128)':
    case 'varchar(255)':
    case 'text':
        //longer character fields
        $content = nl2br($rawValue);
        break;
    default:
        if(is_numeric($rawValue) && ('' != $this->displayDecimals)){
            if(isset($this->roundingMethod) && 'round' != $this->roundingMethod){
                $tempMultiplier = pow(10, $this->displayDecimals);
                $tempValue = $rawValue * $tempMultiplier;
                switch($this->roundingMethod){
                case 'ceil':
                    $tempValue = ceil($tempValue);
                    break;
                case 'floor':
                default:
                    $tempValue = floor($tempValue);
                    break;
                }
                $rawValue = $tempValue / $tempMultiplier;
            }
            $content = number_format($rawValue, $this->displayDecimals);
        } else {
            $content = $rawValue;
        }
    }

    if(!empty($this->displayFormat)){
        //$content = $content . $this->displayFormat;
        $content = sprintf('%'.$this->displayFormat, $content);
    }

    if('viewfield' != get_class($this)){ //avoid duplicate rendering of subfields
        if(count($this->Fields) > 0){
            foreach($this->Fields as $key => $subField){
                if('Self' != $key){
                    $content .= ' ' .$subField->viewRender(&$values);
                }
            }
        }
    }

    return $content;
}


function searchRender(&$values, &$phrases)
{
    //to be overriden by field types that render differently on a search screen than an Edit screen
    $this->defaultValue = '';
    return $this->render(&$values, &$phrases);
}


/**
 * Used when rendering paper forms for data collection purposes
 */
function dataCollectionRender(&$values, $format = 'html')
{
    return '';//override this
}


function checkSearch(&$data)
{
    //determines whether the user entered a search expression for this field.
    //this function can be overriden for field types where this is determined in a different way.
    if ('' != trim($data[$this->name]) ){
        return true;
    } else {
        return false;
    }
}


function quoteValue($value)
{
    //overridable function for proper quoting of POSTed values
    return dbQuote($value);
}


function getSearchPhrase(&$data, &$moduleFields)
{
    $content = '';
    //global $phrases;
    if(empty($this->phrase)){
        $phrase = $moduleFields[$this->name]->phrase;
    } else {
        $phrase = $this->phrase;
    }
    $content .= ShortPhrase($phrase) . ': ' . $data[$this->name];
    return $content;
}


function handleSearch(&$data, &$moduleFields, $overrideModuleID = '')
{
    //check whether the user entered an expression for this field
    if ($this->checkSearch(&$data)){
        $moduleField =& $moduleFields[$this->name];

        if(empty($overrideModuleID)){
            global $ModuleID;
        } else {
            $ModuleID = $overrideModuleID;
        }

        $from = array();
        $where = array();
        $phrase = array();
//        $alias = GetTableAlias($moduleField, $ModuleID);
/*        $name = $moduleField->getQualifiedName($ModuleID);
        $from = $moduleField->makeJoinDef($ModuleID);
*/
        $name = GetQualifiedName($this->name);
        $from = GetJoinDef($this->name);

        $value = $this->quoteValue($data[$this->name]);
        $where[$this->name] = "$name = $value";
        $phrase[$this->name] = $this->getSearchPhrase(&$data, &$moduleFields);

        $searchDef = array(
            'f' => $from,
            'w' => $where,
            'p' => $phrase,
            'v' => array($this->name => $data[$this->name])
        );
    } else {
        $searchDef = NULL;
    }
    return $searchDef;
}


function isSubField()
{
    if (!empty($this->parentName)){
        return true;
    } else {
        return false;
    }
}


//this function returns the field names to be added to a SELECT
//statement on account of this field. defaults to own name only.
function getSelectFields()
{
    $fields = array();
    if(count($this->Fields) > 0){
        foreach($this->Fields as $field_name => $field){
            if('Self' != $field_name){
                $fields = $field->getSelectFields();
            }
        }
    }
    if(isset($this->linkField) && !empty($this->linkField)){
        if(!array_key_exists($this->linkField, $fields)){
            $fields[$this->linkField] = true;
        }
    }
    $fields[$this->name] = true;
    return $fields;
}


//returns an array of names of subfields (recursively), including this field
function getRecursiveFields()
{
    $fields = array();
    if(count($this->Fields) > 0){
//print_r($this->Fields);
        foreach($this->Fields as $field_name=>$subField){
            if('Self' != $field_name){
                $fields = array_merge($fields, $subField->getRecursiveFields());
            }
        }
    }
    $fields[$this->name] = $this;
    return $fields;
}


//replaces [*fieldname*] tags with the posted value, recursive
function replacePostedValues($SQL)
{
    if(count($this->Fields) > 0){
        foreach($this->Fields as $field_name => $subField){
            if('Self' != $field_name){
                $SQL = $subField->replacePostedValues($SQL);
            }
        }
    }

    $SQL = str_replace("'[*{$this->name}*]'", dbQuote($_POST[$this->name], $this->dataType), $SQL);

    return $SQL;
}

} //end class ScreenField




class EditableField extends ScreenField 
{

function isEditable()
{
    return true;
}
} //end class EditableField




//field class for data that should not be displayed on a screen but needs to be retrieved
class InvisibleField extends ScreenField 
{

function &Factory($element, $moduleID)
{
    return new InvisibleField($element, $moduleID);
}


function InvisibleField($element, $moduleID)
{
    $this->name = $element->attributes['name'];
    if(!isset($element->attributes['formName'])){
        $this->formName = 'mainForm';
    } else {
        $this->formName = $element->attributes['formName'];
    }
}


function isVisible()
{
    //function to determine whether a control should be displayed
    return false;
}


function simpleRender(&$values)
{
    return '';
}


function viewRender(&$values)
{
    return '';
}


function render(&$values, &$phrases)
{
    return '';
}
} //end class InvisibleField



//field class for data that should not be visibly displayed on a screen but needs to be posted
class HiddenField extends InvisibleField {

function &Factory($element, $moduleID)
{
    return new HiddenField($element, $moduleID);
}


function HiddenField($element, $moduleID)
{
    $this->name = $element->attributes['name'];
    if(!isset($element->attributes['formName'])){
        $this->formName = 'mainForm';
    } else {
        $this->formName = $element->attributes['formName'];
    }
}


function isEditable()
{
    return true;
}


function simpleRender(&$values)
{
    return "<input type=\"hidden\" name=\"{$this->name}\" value=\"{$values[$this->name]}\"/>";
}


function viewRender(&$values)
{
    return '';
}
} //end class HiddenField




class ViewField extends ScreenField
{
var $linkField; //name of (optional) module field that holds a URL
var $parentField; //updating parent
var $displayDecimals;
var $roundingMethod; //only if using displayDecimals: 'floor', 'ceil', 'round' (default)

function &Factory($element, $moduleID)
{
    return new ViewField($element, $moduleID);
}


function ViewField(&$element, $moduleID)
{

    $moduleField = GetModuleField($moduleID, $element->name);
    $debug_prefix = debug_indent("ViewField-constructor() {$moduleID}.{$element->name}:");

    $this->name = $element->attributes['name'];
    if(!empty($element->attributes['dataType'])){
        $this->dataType = $element->attributes['dataType'];
    } else {
        $this->dataType = $moduleField->dataType;
    }
    $this->displayDecimals = $element->attributes['displayDecimals'];
    $this->roundingMethod  = $element->attributes['roundingMethod'];

    $this->linkField = $element->attributes['link'];
    $this->parentField = $element->attributes['parentField'];

    if(!empty($element->attributes['formName'])){
        $this->formName = $element->attributes['formName'];
    } else {
        $this->formName = 'mainForm';
    }

    if(empty($element->attributes['gridAlign'])){
        $this->gridAlign = $moduleField->getGridAlign();
    } else {
        $this->gridAlign = $element->attributes['gridAlign'];
    }
    if(empty($element->attributes['displayFormat'])){
        $this->displayFormat = $moduleField->displayFormat;
    } else {
        $this->displayFormat = $element->attributes['displayFormat'];
    }

    $this->needsReGet = $moduleField->needsReGet();

    //allows phrase attribute to override module field phrase
    if(!empty($element->attributes['phrase'])){
        $this->phrase = $element->attributes['phrase'];
    } else {
        $this->phrase = $moduleField->phrase;
    }

    if(count($element->c) > 0){
        foreach($element->c as $sub_element){
            if('Self' == $sub_element->type){
                $this->Fields['Self'] = $field;
            } else {
                if(!empty($element->attributes['formName'])){
                    $sub_element->attributes['formName'] = $element->attributes['formName'];
                }

                $this->Fields[$sub_element->name] = $sub_element->createObject($moduleID);
            }
        }
    }

    if(!empty($element->attributes['formatField'])){
        $this->formatField = $element->attributes['formatField'];
    }

    //generate cached SQL statement that retrieves data update...
    if(!empty($element->attributes['parentField'])){
        $this->needsReGet = true;
        $SQL = $this->makeUpdateSQL($moduleID, $element->attributes['parentField']);

        $createFileName = $moduleID.'_ViewFieldSQL.gen';
        $modelFileName = 'ViewFieldSQLModel.php';
        $createFilePath = GENERATED_PATH ."/$moduleID/$createFileName";
        if (file_exists($createFilePath)){
            include($createFilePath); //not include_once...
        } else {
            $viewFieldSQLs = array();
        }
        $viewFieldSQLs[$this->name] = $SQL;
        $replaceValues = array('/**viewFieldSQLs**/' => escapeSerialize($viewFieldSQLs));

        SaveGeneratedFile($modelFileName, $createFileName, $replaceValues, $moduleID);

    }
    debug_unindent();
}


function needsReGet()
{
    if(isset($this->needsReGet)){
        return $this->needsReGet;
    }
}


function _simpleRender(&$values)
{

    if (!empty($this->linkField)){
        $link = $values[$this->linkField];
        $newWin = '';
        $internal = false;
        if(!empty($link)){
            list($link, $internal, $newWin) = linkFormat($link);

            if($internal){
                $poplink = base64_encode($link);
                global $theme_web;
                
                $content = '';
                
                $content .= '<div style="float:right">';
                $content .= '<a href="#" onclick="window.open(\'frames_popup.php?dest=\\\''.$poplink.'\\\'\', \''.$this->moduleID.'RelRec\', \'toolbar=0,resizable=1\')">';
                $content .= '<img src="'.$theme_web.'/img/open_new_window.gif" title="'.gettext("View in new window").'"/>';
                $content .= '</a> ';
                $content .= '</div>';

                $content .= '<div style="margin-right:12px">';
                $content .= '<a href="'.$link.'">';
                $content .= $this->viewRender(&$values);
                $content .= '</a>';
                $content .= '</div>';
                
            } else {
                if($newWin){
                    $str_target = ' target="_blank"';
                } else {
                    $str_target = '';
                }
                $content = '<a href="'.$link.'"'.$str_target.'>';
                $content .= $this->viewRender(&$values);
                $content .= '</a>';
            }
        } else {
            $content = $this->viewRender(&$values);
        }
    } else {
        $content = $this->viewRender(&$values);
    }
    return $content;
}


function simpleRender(&$values)
{
    if (!empty($this->formatField)){
        $className = ' class="'.$values[$this->formatField].'" ';
    } else {
        $className = '';
    }
    $formName = $this->formName;
    if(empty($formName)){
        $formName = 'mainForm';
    }
    
    $idDiv = '<div id="'.$formName.'_'.$this->name.'"'.$className.'>/*content*/</div>';
    
    return str_replace('/*content*/', $this->_simpleRender($values), $idDiv);
}


function gridViewRender(&$values)
{
    return $this->_simpleRender(&$values);
}


function makeUpdateSQL($moduleID, $parentFieldName)
{
    $debug_prefix = debug_indent("ViewField-makeUpdateSQL() {$moduleID}.{$this->name}:");

    $parentForeignFieldName = substr($parentFieldName, 0, -2);
    $parentForeignField = GetModuleField($moduleID, $parentForeignFieldName);
    if(empty($parentForeignField)){
        die("$debug_prefix The field $moduleID.$parentForeignFieldName is required for generating the viewfield update sql");
    }

    global $SQLBaseModuleID;
    $SQLBaseModuleID = $parentForeignField->getForeignModuleID();
    $foreignModuleFields = GetModuleFields($SQLBaseModuleID);

    $localModuleField = GetModuleField($moduleID, $this->name);
    if(!is_a($localModuleField, 'foreignfield')){
        indent_print_r($localModuleField, true, 'localModuleField');
        die("$debug_prefix must be a ForeignField in order to use ViewField Updates");
    }

    $foreignModuleFieldName = $localModuleField->foreignField;
    if($localModuleField->foreignTable == $SQLBaseModuleID){
        $foreignModuleField = $foreignModuleFields[$foreignModuleFieldName];
    } else {
        //print "$debug_prefix will match foreignfields in $SQLBaseModuleID with:\n";
        //indent_print_r($localModuleField, true, 'localModuleField');

        //the localModuleField must be a foreignfield in the $foreignModuleFields
        foreach($foreignModuleFields as $lookupField){
            if(is_a($lookupField, 'foreignfield')){
                //indent_print_r($lookupField, true, 'lookupField');

                if($localModuleField->foreignTable == $lookupField->foreignTable
                    && $localModuleField->foreignField == $lookupField->foreignField
                ){
                    $foreignModuleField = $lookupField;
                    //print "$debug_prefix found a matching lookupfield\n";
                    break;
                }
            }
        }
    }
    if(empty($foreignModuleField)){
        die("$debug_prefix cannot find a matching field in $SQLBaseModuleID");
    }

    $moduleInfo = GetModuleInfo($SQLBaseModuleID);
    $foreignPK = $moduleInfo->getPKField();
/*
    $select = $foreignModuleField->makeSelectDef($SQLBaseModuleID, false);
    $joins = $foreignModuleField->makeJoinDef($SQLBaseModuleID);
*/
    $select = GetQualifiedName($foreignModuleField->name);
    $joins = GetJoinDef($foreignModuleField->name);

    $joins = SortJoins($joins);

    $SQL = "SELECT\n$select AS Value\n";
    $SQL .= "FROM `$SQLBaseModuleID`\n";
    foreach($joins as $alias => $def){
        $SQL .= "$def\n";
    }
    $SQL .= "WHERE\n";
    $SQL .= "`{$SQLBaseModuleID}`._Deleted = 0";
    $SQL .= "\nAND `{$SQLBaseModuleID}`.{$foreignPK} = '/*recordID*/'";

    CheckSQL($SQL);

    //indent_print_r($SQL, true, 'UpdateSQL');

    debug_unindent();
    return $SQL;
}
}//end class ViewField



class EditField extends EditableField
{
var $size;
var $maxLength;
var $align = 'left';
var $validate;

function &Factory($element, $moduleID)
{
    return new EditField($element, $moduleID);
}


function EditField(&$element, $moduleID)
{
    $moduleField = GetModuleField($moduleID, $element->name);

    $this->name      = $element->attributes['name'];
    $this->size      = $element->attributes['size'];
    $this->maxLength = $element->attributes['maxLength'];
    $this->validate  = $moduleField->validate;

    if(empty($element->attributes['dataType'])){
        $this->dataType = $moduleField->dataType;
    } else {
        $this->dataType  = $element->attributes['dataType'];
    }
    if(empty($element->attributes['formName'])){
        $this->formName  = 'mainForm';
    } else {
        $this->formName  = $element->attributes['formName'];
    }

    if(empty($element->attributes['gridAlign'])){
        $this->gridAlign = $moduleField->getGridAlign();
    } else {
        $this->gridAlign = $element->attributes['gridAlign'];
    }
    if(empty($element->attributes['displayFormat'])){
        $this->displayFormat = $moduleField->displayFormat;
    } else {
        $this->displayFormat = $element->attributes['displayFormat'];
    }

    if(count($element->c) > 0){
        foreach($element->c as $sub_element){
            if('Self' == $sub_element->type){
                $this->Fields['Self'] = $field;
            } else {
                if(!empty($element->attributes['formName'])){
                    $sub_element->attributes['formName'] = $element->attributes['formName'];
                }
                $this->Fields[$sub_element->name] = $sub_element->createObject($moduleID);
            }
        }
    }

}


function simpleRender(&$values, $overrideName = '')
{

    if(!empty($overrideName)){
        $name = $overrideName;
    } else {
        $name = $this->name;
    }

    return ' '.
        sprintf(
            FORM_EDIT_HTML,
            $name,
            html_entity_decode(stripslashes($values[$name]), ENT_QUOTES),
            $this->size,
            $this->maxLength,
            $this->align,
            ''
        )
        .' ';
}

function dataCollectionRender(&$values, $format = 'html')
{
    return '';
}


function useFromToFields()
{
    //this is a helper function used for determining how the field is
    //rendered on a search screen
    switch ($this->dataType){
    case 'int':
    case 'float':
    case 'money':
        return true;
        break;
    default:
        return false;
    }
}


function searchRender(&$values, &$phrases)
{
    $this->defaultValue = '';

    //check render condition
    if($this->checkCondition(&$values)){

    //print "data type: {$this->dataType}<br>\n";
        //check the parent data type - if it is numeric, provide two entry fields
        if($this->useFromToFields()) {
            $data = $this->simpleRender(&$values, $this->name.'_f');
            $data .= ' - ';
            $data .= $this->simpleRender(&$values, $this->name.'_t');

            $content = sprintf(
                FIELD_HTML,
                addslashes(LongPhrase($phrases[$this->name])),
                ShortPhrase($phrases[$this->name]),
                $data,
                'flbl',
                $this->formName.'.'.$this->name,
                ''
            );

        } else {
            $content = $this->render(&$values, &$phrases);
        }

    } else {
        //$content = "field {$this->name} blocked by condition<br>";
        $content = '';
    }
    return $content;
}


function checkSearch(&$data)
{
    //determines whether the user entered a search expression for this field.
    //overrides the generic checkSearch function
    if ('' != trim($data[$this->name]) || '' != trim($data[$this->name.'_f']) || '' != trim($data[$this->name.'_t'])){
        return true;
    } else {
        return false;
    }
}


function getSearchPhrase(&$data, &$moduleFields)
{
    $content = '';
    //global $phrases;
    if(empty($this->phrase)){
        $phrase = $moduleFields[$this->name]->phrase;
    } else {
        $phrase = $this->phrase;
    }
    if($this->useFromToFields()) {

        if (!empty($data[$this->name.'_f'])){
            $content .= ShortPhrase($phrase) . ' >= ' . $data[$this->name.'_f'];
        }
        if (!empty($data[$this->name.'_t'])){
            if(strlen($content) > 0){
                $content .= "<br />\n";
            }
            $content .= ShortPhrase($phrase) . ' <= ' . $data[$this->name.'_t'];
        }
    } else {
        $content .= ShortPhrase($phrase) . ': ' . $data[$this->name];
    }
    return $content;
}


function handleSearch(&$data, &$moduleFields)
{
    //check whether the user entered an expression for this field
    if ($this->checkSearch(&$data)){
        $moduleField =& $moduleFields[$this->name];

        global $ModuleID;

        $from = array();
        $where = array();
        $values = array();

        //$alias = GetTableAlias($moduleField, $ModuleID);
        //$name = $moduleField->getQualifiedName($ModuleID);
        //$from = $moduleField->makeJoinDef($ModuleID);
        $name = GetQualifiedName($this->name);
        $from = GetJoinDef($this->name);

        //check whether uses from-to values
        if($this->useFromToFields()) {

            if (!empty($data[$this->name.'_f'])){
                $value = $this->quoteValue($data[$this->name.'_f']);
                $where[$this->name.'_f'] = "$name >= $value";
                $values[$this->name.'_f'] = $data[$this->name.'_f'];
            }
            if (!empty($data[$this->name.'_t'])){
                $value = $this->quoteValue($data[$this->name.'_t']);
                $where[$this->name.'_t'] = "$name <= $value";
                $values[$this->name.'_t'] = $data[$this->name.'_t'];
            }

        } else {
            $value = str_replace('*', '%', $this->quoteValue($data[$this->name]));
            $where[$this->name] = "$name LIKE $value";
        }
        
        $phrase[$this->name] = $this->getSearchPhrase(&$data, &$moduleFields);

        $searchDef = array(
            'f' => $from,
            'w' => $where,
            'p' => $phrase,
            'v' => $values
        );

    } else {
        $searchDef = null;
    }
    return $searchDef;
}
}//end class EditField



class PasswordField extends EditField {
    var $confirm;
    
    function Factory($element, $moduleID){
        $moduleField = GetModuleField($moduleID, $element->name);
    
        $field = new PasswordField(
            $element->attributes['name'],
            $element->attributes['size'],
            $element->attributes['maxLength'],
            $moduleField->dataType,
            '',
            $element->attributes['conditionField'],
            $element->attributes['conditionValue'],
            $moduleField->validate,
            $element->attributes['confirm']
        );

        if(empty($element->attributes['gridAlign'])){
            $field->gridAlign = $moduleField->getGridAlign();
        } else {
            $field->gridAlign = $element->attributes['gridAlign'];
        }
        if(empty($element->attributes['displayFormat'])){
            $field->displayFormat = $moduleField->displayFormat;
        } else {
            $field->displayFormat = $element->attributes['displayFormat'];
        }
        
        if(count($element->c) > 0){
            foreach($element->c as $sub_element){
                if('Self' == $sub_element->type){
                    $field->Fields['Self'] = $field;
                } else {
                    if(!empty($element->attributes['formName'])){
                        $sub_element->attributes['formName'] = $element->attributes['formName'];
                    }
                
                    $field->Fields[$sub_element->name] = $sub_element->createObject($moduleID);
                }
            }
        }
        
        return $field;
    }
    
    function PasswordField($pName, $pSize, $pMaxLength, $pDataType, $pParentName = '', $pConditionField, $pConditionValue, $pValidate, $confirm, $formName = 'mainForm'){
        $this->conditionField = $pConditionField;
        $this->conditionValue = $pConditionValue;
        $this->name = $pName;
        //$this->editable = TRUE;
        if(isset($pParentName)){
            $this->parentName = $pParentName;
        }
        $this->size = $pSize;
        $this->maxLength = $pMaxLength;
        $this->dataType = $pDataType;
        $this->validate = $pValidate;
        $this->formName = $formName;
        $this->confirm = $confirm;
    }
    
    function simpleRender(&$values, $overrideName = ''){
        $content = ' '.
            sprintf(
                FORM_PWD_HTML,
                $this->name,
                '',
                $this->size,
                $this->maxLength,
                $this->align
            )
            .' ';

        if($this->confirm){
            $name = $this->name . '_confirm';
            
            $content .= "<br />\n";
            $content .= ' '.
            sprintf(
                FORM_PWD_HTML,
                $name,
                '',
                $this->size,
                $this->maxLength,
                $this->align
            )
            .' ';
            $content .= gettext("(confirm)") . "\n";
        }

        return $content;
    }
}



class UploadField extends EditField {

function Factory($element, $moduleID){
    $moduleField = GetModuleField($moduleID, $element->name);

    $field = new UploadField(
        $element->attributes['name'],
        $element->attributes['size'],
        $element->attributes['maxLength'],
        $moduleField->dataType,
        '',
        $element->attributes['conditionField'],
        $element->attributes['conditionValue'],
        $moduleField->validate
    );

    if(empty($element->attributes['gridAlign'])){
        $field->gridAlign = $moduleField->getGridAlign();
    } else {
        $field->gridAlign = $element->attributes['gridAlign'];
    }
    if(empty($element->attributes['displayFormat'])){
        $field->displayFormat = $moduleField->displayFormat;
    } else {
        $field->displayFormat = $element->attributes['displayFormat'];
    }
    
    if(count($element->c) > 0){
        foreach($element->c as $sub_element){
            if('Self' == $sub_element->type){
                $field->Fields['Self'] = $field;
            } else {
                if(!empty($element->attributes['formName'])){
                    $sub_element->attributes['formName'] = $element->attributes['formName'];
                }
            
                $field->Fields[$sub_element->name] = $sub_element->createObject($moduleID);
            }
        }
    }
    
    return $field;
}

    function UploadField($pName, $pSize, $pMaxLength, $pDataType, $pParentName = '', $pConditionField, $pConditionValue, $pValidate, $formName = 'mainForm'){
        $this->conditionField = $pConditionField;
        $this->conditionValue = $pConditionValue;
        $this->name = $pName;
        //$this->editable = TRUE;
        if(isset($pParentName)){
            $this->parentName = $pParentName;
        }
        $this->size = $pSize;
        $this->maxLength = $pMaxLength;
        $this->dataType = $pDataType;
        $this->validate = $pValidate;
        $this->formName = $formName;
    }
    
    function simpleRender(&$values, $overrideName = ''){


        return ' '.
            sprintf(
                FORM_FILE_HTML,
                UPLOAD_MAX_FILE_SIZE,
                $this->name,
                htmlspecialchars(stripslashes($values[$this->name]), ENT_QUOTES),
                $this->size,
                $this->maxLength
            )
            .' ';
    }
    
    //viewRender function should render the download link
    function gridViewRender(&$values){
    
        global $ModuleID;
        global $recordID;   
    
        $link = "download.php?mdl=$ModuleID&rid=$recordID&fid={$values['AttachmentID']}";
        $content = '<a href="'.$link.'" target="_blank">';
        $content .= $values[$this->name];
        $content .= '</a>';
        return $content;
    }
}


class DateField extends EditableField {
var $align = 'right';
var $validate;
var $dataType;
var $defaultValue;

function Factory($element, $moduleID){
    $moduleField = GetModuleField($moduleID, $element->name);

    $field = new DateField(
        $element->attributes['name'],
        $moduleField->dataType,
        '',
        $element->attributes['conditionField'],
        $element->attributes['conditionValue'],
        $moduleField->defaultValue,
        $moduleField->validate
    );
    
    if(empty($element->attributes['gridAlign'])){
        $field->gridAlign = $moduleField->getGridAlign();
    } else {
        $field->gridAlign = $element->attributes['gridAlign'];
    }
    if(empty($element->attributes['displayFormat'])){
        $field->displayFormat = $moduleField->displayFormat;
    } else {
        $field->displayFormat = $element->attributes['displayFormat'];
    }

    if(count($element->c) > 0){
        foreach($element->c as $sub_element){
            if('Self' == $sub_element->type){
                $field->Fields['Self'] = $field;
            } else {
                if(!empty($element->attributes['formName'])){
                    $sub_element->attributes['formName'] = $element->attributes['formName'];
                }
            
                $field->Fields[$sub_element->name] = $sub_element->createObject($moduleID);
            }
        }
    }
    
    return $field;
}


function DateField($pName, $pDataType = 'date', $pParentName = '', $pConditionField, $pConditionValue, $pDefaultValue, $pValidate, $formName = 'mainForm'){
    $this->conditionField = $pConditionField;
    $this->conditionValue = $pConditionValue;
    $this->name = $pName;
    $this->dataType = $pDataType;
    //$this->editable = TRUE;
    $this->defaultValue = $pDefaultValue;
    $this->validate = $pValidate;
    if(isset($pParentName)){
        $this->parentName = $pParentName;
    }
    $this->formName = $formName;
}


function simpleRender(&$values, $overrideName = ''){
    global $User;

    if(!empty($overrideName)){
        $name = $overrideName;
    } else {
        $name = $this->name;
    }

    if('datetime' == $this->dataType){
        $size = '22';
        $maxlength = '19';
        $formatString = $User->getDateTimeFormatPHP(); //'%x %X'
    } else {

        $size = '12';
        $maxlength = '10';
        $formatString = $User->getDateFormatPHP(); //'%x';
    }
    
    //check for nulls and empty values
    if ((null == $values[$name]) || ('0000-00-00' == $values[$name])) {
        switch($this->defaultValue){
        case 'today':
            $value = strftime($formatString); //defaults to current date/time
            break;
        default:
            $value = '';
        }
    } else {
        //if the date is < year 2038, format it
        if (intval(substr($values[$name], 0,4)) < 2038){
            $value = strftime($formatString, strtotime($values[$name]));
        } else {
            //formatting function can't format it, just print it
            $value = $values[$name];
        }

    }
    
    
    return ' '.
        sprintf(
            FORM_DATE_HTML,
            $name,
            $value,
            $size,
            $maxlength,
            $this->align,
            $User->getDateFormat()
        )
        .' ';
}


function searchRender(&$values, &$phrases){
    $this->defaultValue = '';
    
    //format to display two date fields - "from" and "to"
    //check render condition
    if($this->checkCondition(&$values)){


        $data = $this->simpleRender(&$values, $this->name.'_f');
        $data .= ' - ';
        $data .= $this->simpleRender(&$values, $this->name.'_t');

        $content = sprintf(
            FIELD_HTML,
            addslashes(LongPhrase($phrases[$this->name])),
            ShortPhrase($phrases[$this->name]),
            $data,
            'flbl',
            $this->formName.'.'.$this->name,
            ''
            );
    } else {
        //$content = "field {$this->name} blocked by condition<br>";
        $content = '';
    }
    return $content;
}


function checkSearch(&$data){
    //determines whether the user entered a search expression for this field.
    //overrides the generic checkSearch function
    if ('' != trim($data[$this->name.'_f']) || '' != trim($data[$this->name.'_t'])){
        return true;
    } else {
        return false;
    }
}


function quoteValue($value){
    //overridable function for proper quoting of POSTed values
    return DateToISO($value);
}


function getSearchPhrase(&$data, &$moduleFields){
    $content = '';
    //global $phrases;
    if(empty($this->phrase)){
        $phrase = $moduleFields[$this->name]->phrase;
    } else {
        $phrase = $this->phrase;
    }
        
    if (!empty($data[$this->name.'_f'])){
        $content .= ShortPhrase($phrase) . ' '.gettext("on or after").' ' . $data[$this->name.'_f'];
    }
    if (!empty($data[$this->name.'_t'])){
        if(strlen($content) > 0){
            $content .= "<br />\n";
        }
        $content .= ShortPhrase($phrase) . ' '.gettext("on or before").' ' . $data[$this->name.'_t'];
        }

    return $content;
}


function handleSearch(&$data, &$moduleFields){

    if ($this->checkSearch(&$data)){
        $moduleField =& $moduleFields[$this->name];
        global $ModuleID;

        $from = array();
        $where = array();
        $values = array();

        //$alias = GetTableAlias($moduleField, $ModuleID);
        //$name = $moduleField->getQualifiedName($ModuleID);
        //$from = $moduleField->makeJoinDef($ModuleID);
        $name = GetQualifiedName($this->name);
        $from = GetJoinDef($this->name);

        //add from - to values
        if (!empty($data[$this->name.'_f'])){
            $value = $this->quoteValue($data[$this->name.'_f']);
            $where[$this->name.'_f'] = "$name >= $value";
            $values[$this->name.'_f'] = $data[$this->name.'_f'];
        }
        if (!empty($data[$this->name.'_t'])){
            $value = $this->quoteValue($data[$this->name.'_t']);
            $where[$this->name.'_t'] = "$name <= $value";
            $values[$this->name.'_t'] = $data[$this->name.'_t'];
        }
        
        $phrase[$this->name] = $this->getSearchPhrase(&$data, &$moduleFields);

        $searchDef = array(
            'f' => $from,
            'w' => $where,
            'p' => $phrase,
            'v' => $values
        );

    } else {
        $searchDef = null;
    }
    return $searchDef;

}
}//end class DateField

class TimeField extends DateField {
    var $align = 'right';
    var $validate;
    var $dataType = 'time';

    function Factory($element, $moduleID){
        $moduleField = GetModuleField($moduleID, $element->name);
    
        $field = new TimeField(
            $element->attributes['name'],
            '',
            $element->attributes['conditionField'],
            $element->attributes['conditionValue'],
            $moduleField->validate
        );
        
        if(empty($element->attributes['gridAlign'])){
            $field->gridAlign = $moduleField->getGridAlign();
        } else {
            $field->gridAlign = $element->attributes['gridAlign'];
        }
        if(empty($element->attributes['displayFormat'])){
            $field->displayFormat = $moduleField->displayFormat;
        } else {
            $field->displayFormat = $element->attributes['displayFormat'];
        }

        if(count($element->c) > 0){
            foreach($element->c as $sub_element){
                if('Self' == $sub_element->type){
                    $field->Fields['Self'] = $field;
                } else {
                    if(!empty($element->attributes['formName'])){
                        $sub_element->attributes['formName'] = $element->attributes['formName'];
                    }
                
                    $field->Fields[$sub_element->name] = $sub_element->createObject($moduleID);
                }
            }
        }
        
        return $field;
    }
    
    function TimeField($pName, $pParentName = '', $pConditionField, $pConditionValue, $pValidate, $formName = 'mainForm'){
        $this->conditionField = $pConditionField;
        $this->conditionValue = $pConditionValue;
        $this->name = $pName;
        //$this->editable = TRUE;
        $this->validate = $pValidate;
        if(isset($pParentName)){
            $this->parentName = $pParentName;
        }
        $this->formName = $formName;
    }
    function simpleRender(&$values){
        return ' '.
            sprintf(
                FORM_TIME_HTML,
                $this->name,
                $values[$this->name],
                $this->align
            )
            .' ';
    }
    function quoteValue($value){
        //overridable function for proper quoting of POSTed values
        return dbQuote($value);
    }
}//end class TimeField



class MoneyField extends EditField {
    var $localAmountField;
    var $localCurrencyIDField;
    var $dataType = 'money';

    function Factory($element, $moduleID){
        $moduleField = GetModuleField($moduleID, $element->name);
    
        $field = new MoneyField(
            $element->attributes['name'],
            $element->attributes['size'],
            $element->attributes['maxLength'],
            $element->attributes['localAmountField'], 
            $element->attributes['localCurrencyIDField'], 
            '', 
            $element->attributes['conditionField'],
            $element->attributes['conditionValue'],
            $moduleField->validate
        );
        
        if(empty($element->attributes['gridAlign'])){
            $field->gridAlign = $moduleField->getGridAlign();
        } else {
            $field->gridAlign = $element->attributes['gridAlign'];
        }
        if(empty($element->attributes['displayFormat'])){
            $field->displayFormat = $moduleField->displayFormat;
        } else {
            $field->displayFormat = $element->attributes['displayFormat'];
        }

        if(count($element->c) > 0){
            foreach($element->c as $sub_element){
                if('Self' == $sub_element->type){
                    $field->Fields['Self'] = $field;
                } else {
                    if(!empty($element->attributes['formName'])){
                        $sub_element->attributes['formName'] = $element->attributes['formName'];
                    }
                
                    $field->Fields[$sub_element->name] = $sub_element->createObject($moduleID);
                }
            }
        }
        
        return $field;
    }
    
    function MoneyField($pName, $pSize, $pMaxLength, $pLocalAmountField, $pLocalCurrencyIDField, $pParentName = '', $pConditionField, $pConditionValue, $pValidate, $formName = 'mainForm'){
        $this->conditionField = $pConditionField;
        $this->conditionValue = $pConditionValue;
        $this->name = $pName;
        //$this->editable = TRUE;
        if(isset($pParentName)){
            $this->parentName = $pParentName;
        }
        $this->size = $pSize;
        $this->maxLength = $pMaxLength;
        $this->localAmountField = $pLocalAmountField;
        $this->localCurrencyIDField = $pLocalCurrencyIDField;
        $this->validate = $pValidate;
        $this->formName = $formName;
    }
    
    function simpleRender(&$values, $overrideName = ''){

        if(!empty($overrideName)){
            $name = $overrideName;
        } else {
            $name = $this->name;
        }
        
        if($values[$this->name] != ''){
            //we want to display only two decimals unless last two aren't zero. 
            $val = floatval(str_replace(',','',$values[$this->name]));
            //print "string value: {$values[$this->name]}<br>";
            //print "floated value: $val<br>";
            $nVal = 10000 * $val;
            $fudge = 1; //fudge factor to account for some floating-point fuzzy math
            $mod = fmod(($nVal), 100);
            
            if($mod < $fudge){ //there are max 2 (non-zero) decimals
            //print "2 decimals<br>";
                //$content = " ".number_format($values[$name], 2)." ";
                $content = number_format($val, 2);
            } else { //there are more than 2 decimals so display the whole thing
            //print "4 decimals<br>";
                $content = $values[$name];
            }
        } else {
            $val = '';
        }

        return ' '.MASTER_CURRENCY.' '.
            sprintf(
                FORM_EDIT_HTML,
                $name,
                htmlspecialchars(stripslashes($content), ENT_QUOTES),
                $this->size,
                $this->maxLength,
                $this->align,
                ''
            )
            .' ';
    }
}





class CheckBoxField extends EditableField {
    var $ShortPhrase;
    var $validate;
    var $dataType = 'bool';

    function Factory($element, $moduleID){
print "calling GetModuleField from CheckBoxField Factory for {$element->name}\n";
        if('Checked' != $element->name){
            $moduleField = GetModuleField($moduleID, $element->name);
        }
        $field = new CheckBoxField(
            $element->attributes['name'],
            '',
            $element->attributes['conditionField'],
            $element->attributes['conditionValue'],
            $moduleField->validate
        );

        if(empty($element->attributes['gridAlign'])){
            $field->gridAlign = $moduleField->getGridAlign();
        } else {
            $field->gridAlign = $element->attributes['gridAlign'];
        }
        if(empty($element->attributes['displayFormat'])){
            $field->displayFormat = $moduleField->displayFormat;
        } else {
            $field->displayFormat = $element->attributes['displayFormat'];
        }

        if(count($element->c) > 0){
            foreach($element->c as $sub_element){
                if('Self' == $sub_element->type){
                    $field->Fields['Self'] = $field;
                } else {
                    if(!empty($element->attributes['formName'])){
                        $sub_element->attributes['formName'] = $element->attributes['formName'];
                    }
                
                    $field->Fields[$sub_element->name] = $sub_element->createObject($moduleID);
                }
            }
        }
        
        return $field;
    }
    
    function CheckBoxField($pName, $pParentName = '', $pConditionField, $pConditionValue, $pValidate, $formName = 'mainForm'){
        $this->conditionField = $pConditionField;
        $this->conditionValue = $pConditionValue;
        $this->name = $pName;
        //$this->editable = TRUE;
        $this->validate = $pValidate;
        if(isset($pParentName)){
            $this->parentName = $pParentName;
        }
        $this->formName = $formName;
    }
    
    //replaces [*fieldname*] tags with the posted value, recursive
    function replacePostedValues($SQL){
    
        foreach($this->Fields as $key => $subField){
            if('Self' != $key){
                $SQL = $subField->replacePostedValues($SQL);
            }
        }
        
        if('1' == $_POST[$this->name] ){
            $value = '1';
        } elseif('-1' == $_POST[$this->name]) {
            $value = '0';
        } else {
            $value = 'NULL';
        }
        $SQL = str_replace("'[*{$this->name}*]'", dbQuote($value, $this->dataType), $SQL);
        
        return $SQL;
    }
    
    function getSearchPhrase(&$data, &$moduleFields){
        $content = '';
        //global $phrases;
        if(empty($this->phrase)){
            $phrase = $moduleFields[$this->name]->phrase;
        } else {
            $phrase = $this->phrase;
        }
        switch($data[$this->name]){
        case '1':
            $valuePhrase = gettext("Yes");
            break;
        case '-1':
            $valuePhrase = gettext("No");
            break;
        default:
            $valuePhrase = gettext("Unknown");
        }
        $content .= ShortPhrase($phrase) . ': ' . $valuePhrase;
        return $content;
    }

    function simpleRender(&$values){
    

        //if data comes from the DB, massage it into "form" mode
        //(uses -1 for no)        
//comment 2005/12/20: in-module checkbox fields work when this is present, gridform ones work when this is absent???
        if(empty($_POST['Save']) && empty($_POST['Continue'])){
            $values[$this->name] = ChkUnFormat($values[$this->name]);
        }

        //create a code radio field to generate the SELECT list
        $crf = new CodeRadioField(
            $this->name,
            'cod.CodeTypeID = 10',
            '',
            'horizontal',
            '',
            '',
            $this->validate,
            '',
            TRUE,
            $this->formName);


        $content = $crf->simpleRender(&$values);
//$content.= debug_r($values);
        unset($crf);
        return $content;

    }

    function dataCollectionRender(&$values, $format = 'html')
    {
        return '[Yes] [No]';
    }

    function checkSearch(&$data){
        //determines whether the user entered a search expression for this field.
        //this overrides the generic checkSearch function
        $value = trim( $data[$this->name]);
        if ( !empty($value) ){
            return true;
        } else {
            return false;
        }
    }
    function quoteValue($value){
        //overridable function for proper quoting of POSTed values
        return ChkFormat($value);
    }
    function searchRender(&$values, &$phrases){
        $this->defaultValue = '';

        //check render condition
        if($this->checkCondition(&$values)){

            //create a code radio field to generate the SELECT list
            $crf = new CodeRadioField(
                $this->name,
                'cod.CodeTypeID = 10',
                '',
                'horizontal',
                '',
                '',
                $this->validate,
                '',
                TRUE,
                $this->formName);

            $data = $crf->simpleRender(&$values);
            unset($crf);

            $content = sprintf(
                FIELD_HTML,
                addslashes(LongPhrase($phrases[$this->name])),
                ShortPhrase($phrases[$this->name]),
                $data,
                'flbl',
                $this->formName.'.'.$this->name,
                ''
                );
        } else {
            //$content = "field {$this->name} blocked by condition<br>";
            $content = '';
        }
        return $content;
    }
    
    
    
    function checkGridRender(&$pRow){
    
        if(!empty($pRow[$this->name])){
            $checked = 'checked';
            $origChecked = ''; //'(in DB)';
        } else {
            $checked = '';
            $origChecked = '';
        }
    
        //parameters: field name, field ID, short phrase, checked ("checked" or ''), value
        $data = sprintf(
            FORM_CHECKBOX,
            $this->name.'[]',
            $this->name.'_'.$pRow['RowID'],
            $origChecked,
            $checked,
            $pRow['RowID']
        );
        

        return $data;
    }
}//end class CheckBoxField



class MemoField extends EditableField {
    var $rows;
    var $cols;
    var $validate;

    function Factory($element, $moduleID){
        $moduleField = GetModuleField($moduleID, $element->name);

        $field = new MemoField(
            $element->attributes['name'],
            $element->attributes['rows'],
            $element->attributes['cols'],
            '',
            $element->attributes['conditionField'],
            $element->attributes['conditionValue'],
            $moduleField->validate
        );

        if(empty($element->attributes['gridAlign'])){
            $field->gridAlign = $moduleField->getGridAlign();
        } else {
            $field->gridAlign = $element->attributes['gridAlign'];
        }
        if(empty($element->attributes['displayFormat'])){
            $field->displayFormat = $moduleField->displayFormat;
        } else {
            $field->displayFormat = $element->attributes['displayFormat'];
        }

        if(count($element->c) > 0){
            foreach($element->c as $sub_element){
                if('Self' == $sub_element->type){
                    $field->Fields['Self'] = $field;
                } else {
                    if(!empty($element->attributes['formName'])){
                        $sub_element->attributes['formName'] = $element->attributes['formName'];
                    }

                    $field->Fields[$sub_element->name] = $sub_element->createObject($moduleID);
                }
            }
        }

        return $field;
    }

    function MemoField($pName, $pRows, $pCols, $pParentName = '', $pConditionField, $pConditionValue, $pValidate, $formName = 'mainForm'){
        $this->conditionField = $pConditionField;
        $this->conditionValue = $pConditionValue;
        $this->name = $pName;
        //$this->editable = TRUE;
        if(isset($pParentName)){
            $this->parentName = $pParentName;
        }
        $this->rows = $pRows;
        $this->cols = $pCols;
        $this->validate = $pValidate;
        $this->formName = $formName;
    }
    function simpleRender(&$values){
        return ' '.
            sprintf(
                FORM_MEMO_HTML,
                $this->name,
                $this->rows,
                $this->cols,
                stripslashes($values[$this->name])
            )
            .' ';
    }

    function dataCollectionRender(&$values, $format = 'html')
    {
        return str_repeat("\r\n",$this->rows);
    }

}//end class MemoField



class ComboField extends EditableField
{
var $foreignTable;
var $foreignKey;
var $foreignField;
var $listCondition; //only used by CodeComboField -- could be retired after migrating to listConditions
var $listConditions = array();
var $SQL;
var $getSQL; //sql statement to retrieve one single item
var $validate;
var $parentField;
var $parentListModuleField; //table field in the module that provides the list. Must be used when parser can't figure this from $parentField
var $childFields = array();
var $moduleID;
var $findMode = '';
var $ownerFieldFilter;
var $defaultValue;
var $suppressItemAdd = false; //whether the "plus" add item should be hidden or not

function &Factory($element, $moduleID)
{
    return new ComboField($element, $moduleID);
}

function ComboField(&$element, $moduleID, $grid = null)
{
    $debug_prefix = debug_indent("ComboField [constructor]:");

    $moduleFields = GetModuleFields($moduleID);
    $moduleField = $moduleFields[$element->name];

    $listfield_name = substr($element->attributes['name'], 0, -2);
    $list_moduleField = $moduleFields[$listfield_name];

    //assign list condition/filter
    /** this could be retired? **/
    $list_filter = $element->attributes['listFilter'];
    if (empty($list_filter)){
        $list_filter = $list_moduleField->listCondition;
    }


    if(!empty($element->attributes['formName'])){
        $formName = $element->attributes['formName'];
    } else {
        $formName = 'mainForm';
    }


    if(empty($element->attributes['gridAlign'])){
        $this->gridAlign = ''; //there isn't always a moduleField anyway.
    } else {
        $this->gridAlign = $element->attributes['gridAlign'];
    }
    if(empty($element->attributes['displayFormat'])){
        $this->displayFormat = '';
    } else {
        $this->displayFormat = $element->attributes['displayFormat'];
    }

    $this->name = $element->attributes['name'];
    if(isset($element->attributes['foreignTable'])){
        $this->foreignTable = $element->attributes['foreignTable'];
        $this->foreignKey   = $element->attributes['foreignKey'];
        $this->foreignField = $element->attributes['foreignField'];
    } else {
        $this->foreignTable = $list_moduleField->foreignTable;
        $this->foreignKey   = $list_moduleField->foreignKey;
        $this->foreignField = $list_moduleField->foreignField;
    }
    $this->listCondition = $list_filter;
    $this->listConditions = $list_moduleField->listConditions;
    $this->parentField = $element->attributes['parentField'];
    $this->parentListModuleField = $element->attributes['parentListModuleField'];
    $this->validate = $moduleField->validate;
    $this->moduleID = $moduleID;
    $this->formName = $formName;
    $this->suppressItemAdd = strtolower($element->attributes['suppressItemAdd']) == 'yes';

    switch(strtolower($element->attributes['findMode'])) {
    case 'text':
    case 'alpha':
        $this->findMode = 'text'; //was alpha, but 'text' works well for 'alpha' purposes, too
        break;
    default:
        $this->findMode = '';
    }

    $this->defaultValue = $moduleField->defaultValue;

    $this->_handleSubElements($element->c);

    $this->SQL = $this->buildSQL($grid);

    print "$debug_prefix Created. {$this->name}\n";
    debug_unindent();
}


function _handleSubElements($sub_elements = array())
{
    if(count($sub_elements) > 0){
        foreach($sub_elements as $sub_element){
            if('Field' == substr($sub_element->type, -5)){
                $sub_element->attributes['formName'] = $this->formName;
                $this->Fields[$sub_element->name] = $sub_element->createObject($moduleID);
            } elseif('Self' == $sub_element->type){
                $this->Fields['Self'] = $this;
            } elseif('UpdateFieldRef' == $sub_element->type){
                $this->childFields[$sub_element->name] = $sub_element->attributes;
            } elseif('SampleItem' == $sub_element->type){
                //ignore sample items
            } elseif('ListConditions' == $sub_element->type){

                foreach($sub_element->c as $condition_element){
                    $conditionObj = $condition_element->createObject($moduleID);
                    $this->listConditions[$conditionObj->name] = $conditionObj;
                }

            } else {
                trigger_error("Unknown sub-element type {$sub_element->type}.", E_USER_WARNING);
            }
        }
    }

}


/**
 * This handles the special case with cascading filtering CBs that depend on the child for the items
 *
 * ...and although this works, it's sort of a kludge.
 */
function _buildSQLwChildDep(){
    $debug_prefix = debug_indent("ComboField-_buildSQLwChildDep: {$this->moduleID}.{$this->name}");
    print "$debug_prefix foreignTable {$this->foreignTable}\n";
    print "$debug_prefix foreignKey {$this->foreignKey}\n";
    print "$debug_prefix foreignField {$this->foreignField}\n";

    //this include contains utility functions
    require_once(INCLUDE_PATH . '/general_util.php');

    $localMFs = GetModuleFields($this->moduleID);  //module fields of the local module (i.e. same as the form this field is part of)

    foreach($this->childFields as $childFieldName => $childFieldAttrs){
        if(isset($childFieldAttrs['listParentField'])){

            //getting the local foreign/remote field that corresponds with the child field
            $childListFieldName = substr($childFieldName, 0, -2);
            $childListField = $localMFs[$childListFieldName];

            //determines the base moduleID for the SQL statement
            $childListForeignModuleID = $childListField->getForeignModuleID();
            global $SQLBaseModuleID;
            $SQLBaseModuleID = $childListForeignModuleID;



            //this will provide the ID column in the SQL statement, and required joins
            $IDModuleField = GetModuleField($childListForeignModuleID, $childFieldAttrs['listParentField']);

            //$selects['ID'] = $IDModuleField->makeSelectDef($childListForeignModuleID, false);
            //$joins = $IDModuleField->makeJoinDef($childListForeignModuleID);
            $selects['ID'] = GetQualifiedName($IDModuleField->name, $childListForeignModuleID);
            $joins = GetJoinDef($IDModuleField->name, $childListForeignModuleID);



            //this will provide the "Name" column in the SQL statement, and required joins
            $NameModuleField = GetModuleField($childListForeignModuleID, substr($childFieldAttrs['listParentField'], 0, -2));

            //$selects['Name'] = $NameModuleField->makeSelectDef($childListForeignModuleID, false);
            //$joins = array_merge($joins, $NameModuleField->makeJoinDef($childListForeignModuleID));
            $selects['Name'] = GetQualifiedName($NameModuleField->name, $childListForeignModuleID);
            $joins = array_merge($joins, GetJoinDef($NameModuleField->name, $childListForeignModuleID));


            //this will provide the "ParentID" column in the SQL statement, and required joins
            if(!empty($this->parentListModuleField)){
                $parentFieldName = $this->parentListModuleField;
            } else {
                $parentFieldName = $this->parentField;
            }
            print "parentField: `$childListForeignModuleID`.{$parentFieldName}\n";
            $ParentModuleField = GetModuleField($childListForeignModuleID, $parentFieldName);

            //$selects['ParentID'] = $ParentModuleField->makeSelectDef($childListForeignModuleID, false);
            //$joins = array_merge($joins, $ParentModuleField->makeJoinDef($childListForeignModuleID));
            $selects['ParentID'] = GetQualifiedName($ParentModuleField->name, $childListForeignModuleID);
            $joins = array_merge($joins, GetJoinDef($ParentModuleField->name, $childListForeignModuleID));


//            print_r($selects);
//            print_r($joins);

            //appends field aliases
            foreach($selects as $selectName => $select){
                $selects[$selectName] = $select . ' AS ' . $selectName;
            }

            $SQL = "SELECT DISTINCT\n";
            $SQL .= join(', ', $selects);
            $SQL .= "\nFROM `{$childListForeignModuleID}`\n";

            if(count($joins) > 0){
                $joins = SortJoins($joins);
                $SQL .= join("\n", $joins);
            }
            
            
            //branch off getSQL here
            $idField = $IDModuleField->getQualifiedName($childListForeignModuleID);
            $this->getSQL = $SQL . "\nWHERE $idField = '/*recordID*/'";
            CheckSQL($this->getSQL);
        
        
            $SQL .= "\nWHERE `{$childListForeignModuleID}`._Deleted = 0\n";
            $SQL .= "AND $idField IS NOT NULL\n";
            /*if (!empty($localField->listCondition)){
                $SQL .= " AND {$localField->listCondition}\n";
            }
            if(count($wheres) > 0){
                foreach($wheres as $where){
                    $SQL .= " AND {$where}\n";
                }
            }*/
            
            $SQL .= " ORDER BY Name, ID, ParentID;";
            
            CheckSQL($SQL);
        
            print "$debug_prefix SQL = \n";
            indent_print_r($SQL);
        
            debug_unindent();
            return $SQL;
        }
    }
    debug_unindent();
}

function buildSQL($pGrid = NULL){
        $debug_prefix = debug_indent("ComboField-buildSQL: {$this->moduleID}.{$this->name}");
        print "$debug_prefix foreignTable {$this->foreignTable}\n";
        print "$debug_prefix foreignKey {$this->foreignKey}\n";
        print "$debug_prefix foreignField {$this->foreignField}\n";

        //this include contains utility functions
        require_once(INCLUDE_PATH . '/general_util.php');

        $localMFs = GetModuleFields($this->moduleID);  //module fields of the local module (i.e. same as the form this field is part of)

        if('ID' == substr($this->name, -2)){
            $localValueFieldName = substr($this->name, 0, -2);
            $localField = $localMFs[$localValueFieldName];

            //list module is the one that provides the list
            $listModuleID = $localField->foreignTable;
            $keyName = $localField->foreignKey;
            $fieldName = $localField->foreignField;
        } else {
            //the org list in a personComboField would use this (ends with '_org')
            $localValueFieldName = $this->foreignField;

            $listModuleID = $this->foreignTable;
            $keyName = $this->foreignKey;
            $fieldName = $this->foreignField;
        }

        if(!empty($this->parentField)){
            $hasParent = true;
        } else {
            $hasParent = false;
        }


        print "$debug_prefix local value field: $localValueFieldName.\n";
        print "$debug_prefix list module id: $listModuleID\n";

        $listModuleFields = GetModuleFields($listModuleID);
        print "$debug_prefix got list module fields.\n";

        $listFields = array();
        $listFields['ID']   = $listModuleFields[$keyName];
        if(!is_object($listFields['ID'])){
            die("$debug_prefix List field '$listModuleID.$keyName' is invalid.");
        }

        $listFields['Name'] = $listModuleFields[$fieldName];
        if(!is_object($listFields['Name'])){
            die("$debug_prefix List field '$listModuleID.$fieldName' is invalid.");
        }

        if($hasParent){
            //this is required for Dan's "three-level filtering" (a misnomer)
            //the purpose is to add a temporary ForeignField to the listModuleFields that emulates the parent ID
            if(count($this->childFields) == 1){
            
                $SQL = $this->_buildSQLwChildDep();
                if(!empty($SQL)){
                    debug_unindent();
                    return $SQL;
                }
            }

            
            //if there is a parentListModuleField, use it
            if(!empty($this->parentListModuleField)){
            
                $listFields['ParentID'] = $listModuleFields[$this->parentListModuleField];
                
                if(empty($listFields['ParentID'])){
                    
                    die("$debug_prefix parentListModuleField {$this->parentListModuleField} does not match a module field in module '{$localFields['Name']->moduleID}'");
                    
                }
                
            } else {  
                //otherwise, guess the name, with some assumptions
                
                //see if the name is the same in local and list modules
                $listFields['ParentID'] = $listModuleFields[$this->parentField];
                
                if(empty($listFields['ParentID'])){
                    
                    //get the parent field in the local module
                    $parentField = $localMFs[$this->parentField];
                    $localParentField = $localMFs[$parentField->name];
                    
print "$debug_prefix localParentField = {$localParentField->name}\n";
//print_r($localParentField);
                    
                    //get the foreignField of that field in the list module
                    $localParentFieldName = $localParentField->foreignField;
                    $listFields['ParentID'] = $listModuleFields[$localParentFieldName];
                
                }
                
                
                //a different try:
                if(empty($listFields['ParentID'])){
                    //parent field name w/o ID 
                    $localParentValueFieldName = substr($localParentField->name, 0, -2);
                    $localParentValueField = $localMFs[$localParentValueFieldName];
                
                    //get the foreignKey of that field in the list module
                    $localParentFieldName = $localParentValueField->foreignKey;
                    $listFields['ParentID'] = $listModuleFields[$localParentFieldName];
                }
                
                
                if(empty($listFields['ParentID'])){
                
                
                    die("$debug_prefix parent field {$this->parentField} does not match a module field in module '{$listModuleID}'. Try adding a parentListModuleField to {$this->name}");
                }
            }
        }
        
        
        global $SQLBaseModuleID;
        $SQLBaseModuleID = $listModuleID;
        print "$debug_prefix Base ModuleID used when generating SQL: {$SQLBaseModuleID}\n";
        
        $selects = array();
        $joins = array();

print "$debug_prefix listModuleID: {$listModuleID}\n";
//print_r($localFields['Name']);
//print "$debug_prefix fieldName = $fieldName\n";
//print_r($listModuleFields);
//print_r($listFields);

        foreach($listFields as $name => $field){
            //$selects[] = $field->makeSelectDef($listModuleID, false)." AS $name";
            //$joins = array_merge($field->makeJoinDef($listModuleID), $joins);
            $selects[] = GetQualifiedName($field->name, $listModuleID) . " AS $name";
            $joins = array_merge($joins, GetJoinDef($field->name, $listModuleID));
        }

        //check for a OwnerOrganization field
        $moduleInfo = GetModuleInfo($listModuleID);
        $ownerField = $moduleInfo->getProperty('ownerField');

        if(!empty($ownerField)){
            $ownerMF = $listModuleFields[$ownerField];
            
            if(empty($ownerMF)){
                print_r($listModuleFields);
                die("$debug_prefix OwnerField $ownerField does not match a field in $listModuleID ModuleFields");
            }

            //$this->ownerFieldFilter = $ownerMF->getQualifiedName($listModuleID) . ' IN (%s)';
            //$joins = array_merge($ownerMF->makeJoinDef($listModuleID), $joins);
            $this->ownerFieldFilter = GetQualifiedName($ownerMF->name, $listModuleID) . ' IN (%s)';
            $joins = array_merge($joins, GetJoinDef($ownerMF->name, $listModuleID));
        }
        
        $wheres = array();
        if(count($this->listConditions) > 0){
            foreach($this->listConditions as $listCondition){
                $exprArray = $listCondition->getExpression($listModuleID);
                $joins = array_merge($exprArray['joins'], $joins);
                $wheres[] = $exprArray['expression'];
            }
        }

        $SQL = "SELECT \n";// {$keySelect} AS ID, $fieldSelect AS NAME\n";
        $SQL .= join(', ', $selects);
        $SQL .= " FROM `{$listModuleID}`\n";

        if(count($joins) > 0){
            $joins = SortJoins($joins);
            indent_print_r($joins);
            foreach($joins as $j){
                $SQL .= " $j\n";
            }
        }

        //branch off getSQL here
        $idField = $listFields['ID']->getQualifiedName($listModuleID);
        $this->getSQL = $SQL . "\nWHERE $idField = '/*recordID*/'";
        CheckSQL($this->getSQL);


        $SQL .= "WHERE {$listModuleID}._Deleted = 0\n";
        if (!empty($localField->listCondition)){
            $SQL .= " AND {$localField->listCondition}\n";
        }
        if(count($wheres) > 0){
            foreach($wheres as $where){
                $SQL .= " AND {$where}\n";
            }
        }
        
        $SQL .= " ORDER BY Name, ID;";
        
        CheckSQL($SQL);

        print "$debug_prefix SQL = \n";
        indent_print_r($SQL);

        debug_unindent();
        return $SQL;
    }

    
    //adds filters based on permissions and list conditions
    function getFilteredSQL(){
        global $User;
        
        $SQL = $this->SQL;
        
        $filterSQL = sprintf($this->ownerFieldFilter, join(',', $User->getPermittedOrgs($this->foreignTable)));
        $SQL = str_replace('ORDER', ' AND '.$filterSQL . ' ORDER', $SQL);
        return $SQL;
        
    }
    
    function getFindHTML(){
        if('text' == $this->findMode){
            return sprintf(FORM_FINDMODE_TEXT_HTML, $this->name); 
        } else {
            return '';
        }
    }
    
    function getAddNewLink()
    {
        $addNewLink = '';
        if(!$this->suppressItemAdd){
            $addNewLink = "<a href=\"#\" onclick=\"window.open('frames_popup.php?dest=edit&mdl={$this->foreignTable}', '{$this->name}AddItem', 'toolbar=0,resizable=1')\">+</a>";
        }

        return $addNewLink;
    }
    
    function getDefaultValue()
    {
        if(empty($this->defaultValue)){
            return null;
        }
        switch($this->defaultValue){
        case 'userID':
            global $User;
            return $User->PersonID;
            break;
        case 'defaultorgID':
            global $User;
            return $User->defaultOrgID;
            break;
        default:
            if(false !== strpos($this->defaultValue, '#')){
                return str_replace('#', '', $this->defaultValue);
            }
            if(false !== strpos($this->defaultValue, '[*')){
                global $data;
                return PopulateValues($this->defaultValue, $data);
            } else {
                return null;
            }
            break;
        }
    }

function getListData($selected, &$values, $format = null, $isDefault = false){
    //connects to database and retrieves the data
    //think of some form of caching of list items in the browser
    global $dbh;
    global $User;
    global $recordID;

    $SQL = $this->SQL;

    //check the user's permission to the list module
    switch(intval($User->PermissionToView($this->foreignTable))){
    case 2:
        break;
    case 1:
        $SQL = $this->getFilteredSQL();
        break;
    default:
        $moduleInfo = GetModuleInfo($this->foreignTable);
        $foreignModuleName = $moduleInfo->getProperty('moduleName');
        $msg = sprintf(gettext("Permission Error:  You have no permission to view records in the %s module"), $foreignModuleName);
        if(empty($this->parentField)){
            $content = "<option value=\"0\">$msg</option>\n";
        } else {
            $content = "ar{$this->name}[0] =  new Array(0, \"$msg\", 0);\n";
        }
        return $content;
    }


    //populate any dynamic field conditions with the proper values
    $SQL = PopulateValues($SQL, &$values);

    $listItems = $dbh->getAssoc($SQL);
    dbErrorCheck($listItems, false);
    if(!$isDefault && !empty($selected) && !array_key_exists($selected, $listItems)){
        $missingItem = $dbh->getAssoc(str_replace('/*recordID*/', $selected, $this->getSQL));
        dbErrorCheck($missingItem);
        $listItems = $listItems + $missingItem; //array_merge would re-sequence the index IDs
    }
    if('php' == $format){
        return $listItems;
    } else {
        if(empty($this->parentField)){
            $content .= '<option value="0">'.gettext("(unselected)")."</option>\n";
            if($selected === null){
                $noselect = true;
            } else {
                $noselect = false;
            }
            foreach($listItems as $id=>$name){
                if (!$noselect && $id == $selected){
                    $content .= "<option selected value=\"$id\">$name</option>\n";
                } else {
                    $content .= "<option value=\"$id\">$name</option>\n";
                }
            }
        } else {
            //we're building a javaScript array
            //put together JavaScript array definiton for the items
            $content = "ar{$this->name}[0] =  new Array(0, \"".gettext("(unselected)")."\", 0);\n";
            $i = 1;
            foreach($listItems as $id=>$name){
                $content .= "\tar{$this->name}[{$i}] =  new Array({$id}, \"". str_replace(array("\n", "\r"), '', $name[0]) ."\", \"{$name[1]}\");\n";
                $i++;
            }

        }
    }
    return $content;
}

//override: Combo box type grid fields must display their related foreignField when viewed.
function viewRender(&$values){

    $relatedField = substr($this->name, 0, -2);
    return ' '.htmlspecialchars(stripslashes($values[$relatedField]), ENT_QUOTES).' ';
}

function simpleRender(&$values){
    if(0 == count($this->childFields)){
        $refreshJS = '';
    } else {
        $refreshJS = "UpdateFields('{$this->formName}', this, new Array('" .join(array_keys($this->childFields), "','"). "')); return true;";
    }

    $this->isDefault = false;
    $selected = $values[$this->name];
    if(empty($selected)){
        $selected = $this->getDefaultValue();
        if(!empty($selected)){
            $this->isDefault = true;
        }
    }

    $addNewLink = $this->getAddNewLink();

    if(empty($this->parentField)){
        //this is a regular combo field, without child fields to update
        return ' '.
            sprintf(
                FORM_DROPLIST_HTML,
                $this->name,
                $this->getListData($selected, &$values, null, $isDefault),
                $refreshJS,
                $this->getFindHTML(),
                $addNewLink,
                ''
            )
            .' ';
    } else {

        //FormName, FieldName, CurrentIDValue (at page load), ChildNames, JavaScript Array definition
        return ' '.
            sprintf(
                FORM_FILTER_CB_HTML,
                $this->formName,
                $this->name,
                $values[$this->name],
                '',
                $this->getListData($values[$this->name], &$values),
                $this->parentField,
                $refreshJS,
                $this->getFindHTML(),
                $addNewLink,
                ''
            )
            .' ';
    }
}

function dataCollectionRender(&$values, $format = 'html')
{
    $items = $this->getListData(0, $values, 'php');
    if(count($items) > 10){
        return '';
    } else {
        $content = '';
        if(count($items) > 0){
            foreach($items as $item){
                if(is_array($item)){
                    $content .= ' ['.$item[0].'] ';
                } else {
                    $content .= ' ['.$item.'] ';
                }
            }
        }
        return trim($content);
    }
}



//this function returns the field names to be added to a SELECT
//statement on account of this field. defaults to own name only.
function getSelectFields()
{
    $fields = array();
    if(count($this->Fields) > 0){
        foreach($this->Fields as $field_name => $field){
            if('Self' != $field_name){
                $fields = $field->getSelectFields();
            }
        }
    }
    $listFieldName = substr($this->name, 0, -2);
    if(!empty($listFieldName)){
        if(!array_key_exists($listFieldName, $fields)){
            $fields[$listFieldName] = true;
        }
    }
    $fields[$this->name] = true;
    return $fields;
}

function getSearchPhrase(&$data, &$moduleFields){
    $content = '';

    //global $phrases;
    if(empty($this->phrase)){
        $phrase = $moduleFields[$this->name]->phrase;
    } else {
        $phrase = $this->phrase;
    }

    global $dbh;
    $SQL = $this->SQL;

    //TODO: we should add a WHERE clause to only grab the item we're looking for

    //print '<br /><br />list sql<br />'.nl2br($SQL)."\n";

    $r = $dbh->getAssoc($SQL);
    dbErrorCheck($r);

    $content .= ShortPhrase($phrase) . ': ' . $r[$data[$this->name]];

    return $content;
}


function checkSearch(&$data){
    //determines whether the user entered a search expression for this field.
    //this overrides the generic checkSearch function
    $value = trim($data[$this->name]);
    if ( !empty($value) ){ //&& intval($value) != 0){
        return true;
    } else {
        return false;
    }
}

}//end class ComboField



class CodeComboField extends ComboField {
var $codeTypeID;

function &Factory($element, $moduleID){
    return new CodeComboField($element, $moduleID);
}


function CodeComboField(&$element, $moduleID, $grid = null)
{
    $moduleFields = GetModuleFields($moduleID);
    $moduleField = $moduleFields[$element->name];

    $listfield_name = substr($element->attributes['name'], 0, -2);
    $list_moduleField = $moduleFields[$listfield_name];

    //assign list condition/filter
    $list_filter = $element->attributes['listFilter'];
    if(empty($list_filter)){
        $list_filter = $list_moduleField->listCondition;
    }

    if(!empty($element->attributes['formName'])){
        $formName = $element->attributes['formName'];
    } else {
        $formName = 'mainForm';
    }

    $this->name =  $element->attributes['name'];
    $this->foreignTable = 'cod';
    $this->foreignKey = 'CodeID';
    $this->foreignField = 'Description';
    $this->listCondition = $list_filter;
    $this->listConditions = $list_moduleField->listConditions;
    $this->parentField = $element->attributes['parentField'];
    $this->parentListModuleField = $element->attributes['parentListModuleField'];
    $this->codeTypeID = $list_moduleField->codeTypeID;
    $this->validate = $moduleField->validate;
    $this->moduleID = $moduleID;
    $this->formName = $formName;
    $this->suppressItemAdd = strtolower($element->attributes['suppressItemAdd']) == 'yes';
    $this->defaultValue = $moduleField->defaultValue;
    
    switch(strtolower($element->attributes['findMode'])) {
    case 'text':
    case 'alpha':
        $this->findMode = 'text';
        break;
    default:
    }

    if(empty($element->attributes['gridAlign'])){
        $this->gridAlign = $moduleField->getGridAlign();
    } else {
        $this->gridAlign = $element->attributes['gridAlign'];
    }
    if(empty($element->attributes['displayFormat'])){
        $this->displayFormat = $moduleField->displayFormat;
    } else {
        $this->displayFormat = $element->attributes['displayFormat'];
    }

    $this->_handleSubElements($element->c);
    $this->SQL = $this->buildSQL($pGrid);

    //add sort order to sort conditions
    $this->SQL = str_replace(' ORDER BY Name, ID;', ' ORDER BY SortOrder, Name, ID;', $this->SQL);
}

    function getAddNewLink()
    {
        $addNewLink = '';
        if(!$this->suppressItemAdd){
            $addNewLink = "<a href=\"#\" onclick=\"window.open('frames_popup.php?dest=edit&mdl={$this->foreignTable}&cti={$this->codeTypeID}', '{$this->name}AddItem', 'toolbar=0,resizable=1')\">+</a>";
        }

        return $addNewLink;
    }
}//end class CodeComboField



class OrgComboField extends ComboField {

function &Factory($element, $moduleID){
    return new OrgComboField($element, $moduleID);
}

function OrgComboField(&$element, $moduleID, $grid = null)
{
    //($pName, $pListCondition, $pParentField, $pSQL = '', $pConditionField, $pConditionValue, $pValidate, $pModuleID, $pGrid = NULL, $formName = 'mainForm', $findMode = 'text', $pSuppressItemAdd = false){

    $moduleFields = GetModuleFields($moduleID);
    $moduleField = $moduleFields[$element->name];

    $listfield_name = substr($element->attributes['name'], 0, -2);
    $list_moduleField = $moduleFields[$listfield_name];

    //assign list condition/filter
    $list_filter = $element->attributes['listFilter'];
    if (empty($list_filter)){
        $list_filter = $list_moduleField->listCondition;
    }

    if(!empty($element->attributes['formName'])){
        $formName = $element->attributes['formName'];
    } else {
        $formName = 'mainForm';
    }

    if(empty($element->attributes['gridAlign'])){
        $this->gridAlign = $moduleField->getGridAlign();
    } else {
        $this->gridAlign = $element->attributes['gridAlign'];
    }
    if(empty($element->attributes['displayFormat'])){
        $this->displayFormat = $moduleField->displayFormat;
    } else {
        $this->displayFormat = $element->attributes['displayFormat'];
    }

    $this->name = $element->attributes['name'];
    $this->foreignTable = 'org';
    $this->foreignKey = 'OrganizationID';
    $this->foreignField = 'Name';
    $this->listCondition = $list_filter;
    $this->parentField = $element->attributes['parentField'];
    $this->validate = $moduleField->validate;
    $this->moduleID = $moduleID;
    $this->formName = $formName;
    $this->suppressItemAdd = strtolower($element->attributes['suppressItemAdd']) == 'yes';
    $this->findMode = 'text';

    $this->defaultValue = $moduleField->defaultValue;
    $this->listConditions = $list_moduleField->listConditions;

    $this->_handleSubElements($element->c);
    $this->SQL = $this->buildSQL(&$grid);
}
}



class PersonComboField extends ComboField {
    var $orgField; //limiting org field
    
    function Factory($element, $moduleID){
        $moduleFields = GetModuleFields($moduleID);
        $moduleField = $moduleFields[$element->name];

        $listfield_name = substr($element->attributes['name'], 0, -2);
        $list_moduleField = $moduleFields[$listfield_name];
        
        //assign list condition/filter
        $list_filter = $element->attributes['listFilter'];
        if (empty($list_filter)){
            $list_filter = $list_moduleField->listCondition;
        }
        
        if(!empty($element->attributes['formName'])){
            $formName = $element->attributes['formName'];
        } else {
            $formName = 'mainForm';
        }
        

        $field = new PersonComboField(
            $element->attributes['name'],
            $list_filter,
            $element->attributes['orgListCondition'],
            $list_moduleField->sql,
            $element->attributes['conditionField'],
            $element->attributes['conditionValue'],
            $moduleField->validate,
            $moduleField->defaultValue,
            $moduleID,
            NULL, 
            $formName, 
            'text',
            $element->attributes['suppressItemAdd']
        );
        
        if(empty($element->attributes['gridAlign'])){
            $field->gridAlign = $moduleField->getGridAlign();
        } else {
            $field->gridAlign = $element->attributes['gridAlign'];
        }
        if(empty($element->attributes['displayFormat'])){
            $field->displayFormat = $moduleField->displayFormat;
        } else {
            $field->displayFormat = $element->attributes['displayFormat'];
        }

        if(count($element->c) > 0){
            foreach($element->c as $sub_element){
                if('Field' == substr($sub_element->type, -5)){
                    if(!empty($element->attributes['formName'])){
                        $sub_element->attributes['formName'] = $element->attributes['formName'];
                    }
                
                    $field->Fields[$sub_element->name] = $sub_element->createObject($moduleID);
                }
                if('UpdateFieldRef' == $sub_element->type){
                    $field->childFields[$sub_element->name] = $sub_element->attributes;
                }
                if('Self' == $sub_element->type){
                    $field->Fields['Self'] = $field;
                }
            }
        }
        
        return $field;
    }

    function PersonComboField($pName, $pListCondition, $pOrgListCondition, $pSQL = '', $pConditionField, $pConditionValue, $pValidate, $pDefaultValue, $pModuleID, $pGrid = NULL, $formName = 'mainForm', $findMode = 'text', $pSuppressItemAdd = false){
        $this->conditionField = $pConditionField;
        $this->conditionValue = $pConditionValue;
        $this->name = $pName;
        //$this->editable = TRUE;
        $this->foreignTable = 'ppl';
        $this->foreignKey = 'PersonID';
        $this->foreignField = 'DisplayName';
        $this->listCondition = $pListCondition;
        $this->formName = $formName;
        $this->validate = $pValidate;
        $this->defaultValue = $pDefaultValue;
        $this->moduleID = $pModuleID;
        $this->suppressItemAdd = strtolower($pSuppressItemAdd) == 'yes';

        switch(strtolower($findMode)) {
        case 'text':
        case 'alpha':
            $this->findMode = 'text';
            break;
        default:
        }

        if (!empty($pSQL)){
            $this->SQL = $pSQL;
        } else {
            $this->SQL = $this->buildSQL(&$pGrid);
        }

        $orgField =& MakeObject(
            'ppl',
            $pName.'_org',
            'ComboField',
            array(
                'name' => $pName.'_org',
                'foreignTable' => 'org',
                'foreignKey' => 'OrganizationID',
                'foreignField' => 'Name',
                'listCondition' => $pOrgListCondition,
                'formName' => $this->formName,
                'findMode' => $findMode
            )
        );

        if('defaultorgID' == $pDefaultValue){
            $orgField->defaultValue = $pDefaultValue; //pass on defaultOrgID to org combo
            $this->defaultValue = null;
        }
        $this->orgField =& $orgField;
    }

    function simpleRender(&$values){
//      print "PersonComboField {$this->name} -> simpleRender()<br />\n";
//      print_r($values);
//      print "<br />\n";
        
        global $dbh;
        
        if(empty($values[$this->name])){
            $value = $this->getDefaultValue();
        } else {
            $value = $values[$this->name];
        }

        //get all orgs (but filter by orgListCondition)
        $SQL = 'SELECT OrganizationID, Name FROM org WHERE _Deleted = 0 ORDER BY Name';
//print "org list SQL: ".nl2br($SQL);
        //get data
        $r = $dbh->getAll($SQL);
        dbErrorCheck($r);

        //put together JavaScript array definiton for Orgs
        $jsCode = "ar{$this->name}Orgs[0] = new Array(0, \"".gettext("(unselected)")."\");\n";
        $i = 1;
        foreach($r as $row){
            $jsCode .= "\tar{$this->name}Orgs[{$i}] =  new Array({$row[0]}, \"{$row[1]}\");\n";
            $i++;
        }

        //get all people (but filter by orgListCondition)
        $SQL = 'SELECT PersonID, DisplayName, OrganizationID FROM ppl WHERE _Deleted = 0 ORDER BY DisplayName';
//print "ppl list SQL: " . nl2br($SQL);
        //put together JavaScript array definiton for People
        //get data
        $r = $dbh->getAll($SQL);
        dbErrorCheck($r);

        //put together JavaScript array definiton for Orgs
        $jsCode .= "\tar{$this->name}People[0] = new Array(0, \"".gettext("(unselected)")."\", 0);\n";
        $i = 1;
        foreach($r as $row){
            $jsCode .= "\tar{$this->name}People[{$i}] = new Array({$row[0]}, \"".addslashes($row[1])."\", {$row[2]});\n";
            $i++;
        }
//print_r($values);
        $addNewLink = $this->getAddNewLink();

        //format the whole thing
        //FormName, PersonFieldName, PersonIDValue, JavaScript Array definition
        $content = sprintf(
                FORM_PERSON_CB_HTML,
                $this->formName,
                $this->name,
                $value,
                $jsCode,
                $this->getFindHTML(),
                $this->orgField->getFindHTML(),
                $addNewLink
            );

        return $content;
    }
}



class RadioField extends ComboField
{
var $orientation;

function Factory($element, $moduleID)
{
    $moduleFields = GetModuleFields($moduleID);
    $moduleField = $moduleFields[$element->name];

    $listfield_name = substr($element->attributes['name'], 0, -2);
    $list_moduleField = $moduleFields[$listfield_name];
    
    //assign list condition/filter
    $list_filter = $element->attributes['listFilter'];
    if (empty($list_filter)){
        $list_filter = $list_moduleField->listCondition;
    }

    $field = new RadioField(
        $element->attributes['name'],
        $list_moduleField->foreignTable,
        $list_moduleField->foreignKey,
        $list_moduleField->foreignField,
        $list_filter,
        $list_moduleField->sql,
        $element->attributes['orientation'],
        $element->attributes['conditionField'],
        $element->attributes['conditionValue'],
        $moduleField->validate,
        $moduleID
    );
    $field->defaultValue = $moduleField->defaultValue;

    if(empty($element->attributes['gridAlign'])){
        $field->gridAlign = $moduleField->getGridAlign();
    } else {
        $field->gridAlign = $element->attributes['gridAlign'];
    }
    if(empty($element->attributes['displayFormat'])){
        $field->displayFormat = $moduleField->displayFormat;
    } else {
        $field->displayFormat = $element->attributes['displayFormat'];
    }

    if(count($element->c) > 0){
        foreach($element->c as $sub_element){
            if('Field' == substr($sub_element->type, -5)){
                if(!empty($element->attributes['formName'])){
                    $sub_element->attributes['formName'] = $element->attributes['formName'];
                }
            
                $field->Fields[$sub_element->name] = $sub_element->createObject($moduleID);
            }
            if('Self' == $sub_element->type){
                $field->Fields['Self'] = $field;
            }
        }
    }
    
    return $field;
}

function RadioField($pName, $pFTable, $pFKey, $pFField, $pListCondition, $pOrientation = 'horizontal', $pSQL = '', $pConditionField, $pConditionValue, $pValidate, $pModuleID, $pGrid = NULL, $formName = 'mainForm')
{
    $this->conditionField = $pConditionField;
    $this->conditionValue = $pConditionValue;
    $this->name = $pName;
    $this->foreignTable = $pFTable;
    $this->foreignKey = $pFKey;
    $this->foreignField = $pFField;
    $this->listCondition = $pListCondition;
    $this->orientation = $pOrientation;
    $this->validate = $pValidate;
    $this->moduleID = $pModuleID;
    $this->formName = $formName;
    if (!empty($pSQL)){
        $this->SQL = $pSQL;
    } else {
        $this->SQL = $this->buildSQL($pSQL);
    }
}


function getListData($selected, &$values, $format = null)
{

    //connects to database and retrieves the data
    global $dbh;
    $SQL = $this->SQL;

    if(empty($selected)){
        $selected = $this->getDefaultValue();
    }

    //populate any dynamic field conditions with the proper values
    $SQL = PopulateValues($SQL, &$values);

    $r = $dbh->getAssoc($SQL);
    dbErrorCheck($r);

    if('php' == $format){
        return $r;
    }

    $content = '<input type="hidden" name="'.$this->name.'" id="'.$this->name.'" value="'.$selected.'" class="edt">';


    $checked = '';
    foreach($r as $id=>$name){
//print "{$this->name} $id = $selected<br>";
        if ($id == $selected){
            $checked = 'checked';
        } else {
            $checked = '';
        }
//print "$checked<br><br>\n";
        $content .= sprintf(
            FORM_RADIOBUTTON,
            'r_'.$this->name,
            $name,
            $checked .  ' onclick="setRadioValue(this, \''.$this->name.'\')"',
            $id
        );
        if('vertical' == $this->orientation){
            $content .= "<br \>\n";
        } else {
            $content .= "&nbsp;&nbsp;\n";
        }
    }
    //format the output as <option> values
    return $content;
}


function simpleRender(&$values)
{

    return ' '.$this->getListData($values[$this->name], &$values).' ';

}

}//end class RadioField



class CodeRadioField extends RadioField {

    function Factory($element, $moduleID){
        $moduleFields = GetModuleFields($moduleID);
        $moduleField = $moduleFields[$element->name];

        $listfield_name = substr($element->attributes['name'], 0, -2);
        $list_moduleField = $moduleFields[$listfield_name];
        
        //assign list condition/filter
        $list_filter = $element->attributes['listFilter'];
        if (empty($list_filter)){
            $list_filter = $list_moduleField->listCondition;
        }
        
        $field = new CodeRadioField(
            $element->attributes['name'],
            $list_filter,
            $list_moduleField->sql,
            $element->attributes['orientation'],
            $element->attributes['conditionField'],
            $element->attributes['conditionValue'],
            $moduleField->validate,
            $moduleID
        );
        $field->defaultValue = $moduleField->defaultValue;

        if(empty($element->attributes['gridAlign'])){
            $field->gridAlign = $moduleField->getGridAlign();
        } else {
            $field->gridAlign = $element->attributes['gridAlign'];
        }
        if(empty($element->attributes['displayFormat'])){
            $field->displayFormat = $moduleField->displayFormat;
        } else {
            $field->displayFormat = $element->attributes['displayFormat'];
        }
        
        if(count($element->c) > 0){
            foreach($element->c as $sub_element){
                if('Field' == substr($sub_element->type, -5)){
                    if(!empty($element->attributes['formName'])){
                        $sub_element->attributes['formName'] = $element->attributes['formName'];
                    }
                
                    $field->Fields[$sub_element->name] = $sub_element->createObject($moduleID);
                }
                if('Self' == $sub_element->type){
                    $field->Fields['Self'] = $field;
                }
            }
        }
        
        return $field;
    }

    function CodeRadioField($pName, $pListCondition, $pSQL = '', $pOrientation = 'horizontal', $pConditionField, $pConditionValue, $pValidate, $pModuleID, $pRuntime = FALSE, $pGrid = NULL, $formName = 'mainForm'){

        $this->conditionField = $pConditionField;
        $this->conditionValue = $pConditionValue;
        $this->name = $pName;
        //$this->editable = TRUE;
        $this->foreignTable = 'cod';
        $this->foreignKey = 'CodeID';
        $this->foreignField = 'Description';
        $this->listCondition = $pListCondition;
        $this->orientation = $pOrientation;
        $this->validate = $pValidate;
        $this->moduleID = $pModuleID;
        $this->formName = $formName;

        if (!empty($pSQL)){
            $this->SQL = $pSQL;
        } else {
            if($pRuntime) {
                $this->SQL = "SELECT CodeID as ID, Description as NAME FROM cod\n";
                $this->SQL .= " WHERE _Deleted = 0 AND " . $this->listCondition . "\n";
                $this->SQL .= " ORDER BY Name, ID;";

            } else {
                $this->SQL = $this->buildSQL($pSQL);
            }
        }

        //add sort order to sort conditions
        $this->SQL = str_replace(' ORDER BY Name, ID;', ' ORDER BY SortOrder, Name, ID;', $this->SQL);

    }
}//end class CodeRadioField






////////////////////////////////////
//
// Search-Only classes
//
////////////////////////////////////

//abstract base class
class SearchField extends ScreenControl
{
    var $conditionField;
    var $conditionValue;
    //var $editable = true;
    var $name;
    
    function isEditable()
    {
        return true;
    }
    
    function searchRender(&$values, &$phrases){

        return false; //override this

    }
    
    
    function handleSearch(&$data, &$moduleFields){

        return null; //override this
    
    }
    
    
    function checkSearch(&$data){
        //determines whether the user checked any value
        //note: this could be improved to ignore both if NONE or ALL options are selected.
        $value = trim( $data[$this->name]);
        $result = !empty($value);
        
        die( "checkSearch result for {$this->name}: $result<br><br>" );
        
        return $result;
        
    }
    
    
    //returns a human-readable expression of the search criteria.  (won't use $moduleFields)
    function getSearchPhrase($selectedValues){

        return false; //override this

    }

        
    function isSubField(){

        return FALSE; //this field has no subfields

    }
} //SearchField





class ComboSearchField extends SearchField 
{
    var $subModuleID;
    var $listModuleID;
    var $subModuleKey;
    var $listKey;
    var $listField;
    var $listSQL;
    var $isSearchOnly = true;
    var $phrase;
    var $moduleID;
        
    function Factory($element, $moduleID)
    {
        $field =& new ComboSearchField (
            $element->attributes['name'],
            $element->attributes['subModuleID'],
            $element->attributes['listModuleID'],
            $element->attributes['subModuleKey'],
            $element->attributes['listKey'],
            $element->attributes['listField'],
            $element->attributes['phrase'],
            $element->attributes['conditionField'],
            $element->attributes['conditionValue'],
            $moduleID
            );
            
        return $field;
    }
    
    function ComboSearchField (
        $pName,
        $pSubModuleID,
        $pListModuleID,
        $pSubModuleKey,
        $pListKey,
        $pListField,
        $pPhrase,
        $pConditionField, 
        $pConditionValue, 
        $pModuleID
        )
    {
        $this->conditionField = $pConditionField;
        $this->conditionValue = $pConditionValue;
        $this->name = $pName;
        //$this->editable = TRUE;
        
        $this->subModuleID = $pSubModuleID;
        $this->listModuleID = $pListModuleID;
        $this->subModuleKey = $pSubModuleKey;
        $this->listKey = $pListKey;
        $this->listField = $pListField;
        $this->phrase = $pPhrase;
        $this->moduleID = $pModuleID;
        
        $this->listSQL = $this->_buildSQL();
    
    }
    
    function _buildSQL()
    {
        $SQL = '';
        
        $listFields = array();
        $listFields['ID']   = GetModuleField($this->listModuleID, $this->listKey);
        if(!is_object($listFields['ID'])){
            die("ComboSearchField {$this->name}: List field '$listModuleID.$keyName' is invalid.");
        }
        
        $listFields['Name'] = GetModuleField($this->listModuleID, $this->listField);
        if(!is_object($listFields['Name'])){
            die("ComboSearchField {$this->name}: List field '$listModuleID.$fieldName' is invalid.");
        }
        
        global $SQLBaseModuleID;
        $SQLBaseModuleID = $this->listModuleID;
        print "ComboSearchField->_buildSQL: Base ModuleID used when generating SQL: {$SQLBaseModuleID}\n";
        
        $selects = array();
        $joins = array();



        foreach($listFields as $name => $field){
        
            if($name == 'ID'){
                $role = 'key'; 
            } else {
                $role = 'field';
            }

            //$selects[] = $field->makeSelectDef($SQLBaseModuleID, false)." AS $name";
            //$joins = array_merge($field->makeJoinDef($SQLBaseModuleID), $joins);
            $selects[] = GetQualifiedName($field->name, $SQLBaseModuleID) . " AS $name";
            $joins = array_merge($joins, GetJoinDef($field->name, $SQLBaseModuleID));
        }
        
        //check for a OwnerOrganization field
        $moduleInfo = GetModuleInfo($this->listModuleID);
        $ownerField = $moduleInfo->getProperty('ownerField');
        if(!empty($ownerField)){
            $ownerMF = GetModuleField($this->listModuleID, $ownerField);
            
            if(empty($ownerMF)){
                die("OwnerField $ownerField does not match a field in $this->listModuleID ModuleFields");
            }
            
            //$this->ownerFieldFilter = $ownerMF->getQualifiedName($SQLBaseModuleID) . ' IN (%s)';
            //$joins = array_merge($ownerMF->makeJoinDef($SQLBaseModuleID), $joins);
            $this->ownerFieldFilter = GetQualifiedName($ownerMF->name, $SQLBaseModuleID) . ' IN (%s)';
            $joins = array_merge($joins, GetJoinDef($ownerMF->name, $SQLBaseModuleID));
        }

        $SQL = "SELECT \n";// {$keySelect} AS ID, $fieldSelect AS NAME\n";
        $SQL .= join(', ', $selects);
        $SQL .= " FROM `{$this->listModuleID}`\n";
            
        if(count($joins) > 0){
            foreach($joins as $j){
                $SQL .= " $j\n";
            }
        }
        $SQL .= "WHERE {$this->listModuleID}._Deleted = 0\n";
        if (!empty($localField->listCondition)){
            $SQL .= " AND {$localField->listCondition}\n";
        }
        
        $SQL .= " ORDER BY Name, ID;";
        
        CheckSQL($SQL);

        print "ComboSearchField->_buildSQL: \n\t\tSQL = \n" . $SQL."\n";
        print "ComboSearchField->_buildSQL: {$this->name} (end)\n";

    
        return $SQL;
    }


    function _getListData($selected, &$values){
        //connects to database and retrieves the data
        //think of some form of caching of list items
        global $dbh;
        global $User;
        $SQL = $this->listSQL;

        //check the user's permission to the list module
        switch(intval($User->PermissionToView($this->listModuleID))){
        case 2:
            break;
        case 1:
            $SQL = $this->getFilteredSQL();
            break;
        default:
            //die('no permission');
            $moduleInfo = GetModuleInfo($this->listModuleID);
            $foreignModuleName = $moduleInfo->getProperty('moduleName');
            $msg = sprintf(gettext("Permission Error:  You have no permission to view records in the %s module"), $foreignModuleName);
            if(empty($this->parentField)){
                $content = "<option value=\"0\">$msg</option>\n";
            } else {
                $content = "ar{$this->name}[0] =  new Array(0, \"$msg\", 0);\n";
            }
            return $content;
        }

        global $recordID;

        //populate any dynamic field conditions with the proper values
        $SQL = PopulateValues($SQL, &$values);
        $r = $dbh->getAssoc($SQL);
        dbErrorCheck($r, false);
        if(empty($this->parentField)){
            $content .= "<option value=\"0\">".gettext("(unselected)")."</option>\n";
            foreach($r as $id=>$name){
                if ($id == $selected){
                    $content .= "<option selected value=\"$id\">$name</option>\n";
                } else {
                    $content .= "<option value=\"$id\">$name</option>\n";
                }
            }
        } else {
            $content = "ar{$this->name}[0] =  new Array(0, \"".gettext("(unselected)")."\", 0);\n";
            $i = 1;
            foreach($r as $id=>$name){
                $content .= "\tar{$this->name}[{$i}] =  new Array({$id}, \"". str_replace(array("\n", "\r"), '', $name[0]) ."\", \"{$name[1]}\");\n";
                $i++;
            }

        }
        //format the output as <option> values
        return $content;
    
    }
    
    
    function searchRender(&$values, &$phrases)
    {
        $this->defaultValue = '';
    
        if(0 == count($this->childFields)){
            $refreshJS = '';
        } else {
            $refreshJS = "UpdateChildCBs('{$this->formName}', this, new Array('" .join(array_keys($this->childFields), "','"). "')); return true;";
        }

        if(empty($this->parentField)){
            //this is a regular combo field, without child fields to update
            $content =  ' '.
                sprintf(
                    FORM_DROPLIST_HTML,
                    $this->name,
                    $this->_getListData($values[$this->name], &$values),
                    $refreshJS,
                    $this->getFindHTML(),
                    '',
                    '' //additional class
                )
                .' ';
        } else {

            //FormName, FieldName, CurrentIDValue (at page load), ChildNames, JavaScript Array definition
            $content = ' '.
                sprintf(
                    FORM_FILTER_CB_HTML,
                    $this->formName,
                    $this->name,
                    $values[$this->name],
                    '',
                    $this->getListData($values[$this->name], &$values),
                    $this->parentField,
                    $refreshJS,
                    $this->getFindHTML(),
                    ''
                )
                .' ';
        }
    
        //format as field row
        $content = sprintf(
            FIELD_HTML,
            addslashes(LongPhrase($phrases[$this->name])),
            ShortPhrase($phrases[$this->name]),
            $content,
            'flbl',
            $this->formName.'.'.$this->name,
            ''
        );
        return $content;
    }
    
    function handleSearch(&$data, &$moduleFields)
    {
        return null;
    }

    function getFindHTML(){
        if('text' == $this->findMode){
            return sprintf(FORM_FINDMODE_TEXT_HTML, $this->name); 
        } else {
            return '';
        }
    }
    
    
} //ComboSearchField

class CodeCheckSearchField extends SearchField {
    var $conditionField;
    var $conditionValue;
    //var $editable = true;
    
    var $subModuleID;
    var $subModuleModuleIDField;
    var $subModuleRecordIDField;
    var $codeIDField;
    var $codeTypeID;
    var $recordIDField;
    var $listCondition;
    var $listSQL;
    var $isSearchOnly = true;
    var $phrase;
    var $tableAlias;

    function Factory($element, $moduleID){
        
        //this assumes the record ID field is the first of the module fields...
        //$recordIDField = reset(GetModuleFields($moduleID));
        $moduleInfo = GetModuleInfo($moduleID);
        $recordIDField = $moduleInfo->getPKField();

        return new CodeCheckSearchField(
            $element->attributes['name'],
            $element->attributes['listCondition'],
            $element->attributes['listSQL'],
            $element->attributes['subModuleID'],
            $element->attributes['subModuleModuleIDField'],
            $element->attributes['subModuleRecordIDField'],
            $element->attributes['codeIDField'],
            $element->attributes['codeTypeID'],
            $recordIDField,
            $element->attributes['phrase'],
            $element->attributes['conditionField'],
            $element->attributes['conditionValue'],
            $moduleID
        );
    
    }

    function CodeCheckSearchField($pName, $pListCondition, $pSQL = '', $pSubModuleID, $pSubModuleModuleIDField, $pSubModuleRecordIDField, $pCodeIDField, $pCodeTypeID, $pRecordIDField, $pPhrase, $pConditionField, $pConditionValue, $pModuleID){

        $this->conditionField = $pConditionField;
        $this->conditionValue = $pConditionValue;
        $this->name = $pName;
        //$this->editable = true;
        
        $this->subModuleID = $pSubModuleID;
        $this->subModuleModuleIDField = $pSubModuleModuleIDField;
        $this->subModuleRecordIDField = $pSubModuleRecordIDField;
        $this->codeIDField = $pCodeIDField;
        $this->codeTypeID = $pCodeTypeID;
        $this->recordIDField = $pRecordIDField;
        $this->phrase = $pPhrase;
        
        $this->listCondition = $pListCondition;
        $this->moduleID = $pModuleID;

        if (!empty($pSQL)){
            $this->listSQL = $pSQL;
        } else {
            //$this->listSQL = $this->buildSQL($pSQL);
            $this->listSQL = "SELECT CodeID as ID, Description as NAME FROM cod\n";
            $this->listSQL .= " WHERE _Deleted = 0 AND CodeTypeID = {$this->codeTypeID} \n";
            if(!empty($this->listCondition)){
                $this->listSQL .= " AND {$this->listCondition}\n";
            }
            $this->listSQL .= " ORDER BY SortOrder, Name, ID;";
        }

        
        global $SQLBaseModuleID;
        $SQLBaseModuleID = $this->moduleID;
        $this->assignParentJoinAlias($this->subModuleID.'_sub', $this->moduleID);

    }

    function searchRender(&$values, &$phrases){
        $this->defaultValue = '';
        
        //render as a list of checkboxes
        
        global $dbh;
        
        $content = '';
        
        $r = $dbh->getAssoc($this->listSQL);

        dbErrorCheck($r);
        foreach($r as $id=>$name){
            $content .= sprintf(
                FORM_CHECKBOX,
                $this->name.'[]', //field name, 
                $this->name.'_'.$id,   //field ID, 
                $name, //short phrase, 
                '', //checked ("checked" or ''), 
                $id //value
            );
            $content .= "<br />\n";
        }
        
        //format as field row
        $content = sprintf(
            FIELD_HTML,
            addslashes(LongPhrase($phrases[$this->name])),
            ShortPhrase($phrases[$this->name]),
            $content,
            'flbl',
            $this->formName.'.'.$this->name,
            ''
        );
        
        
        return $content;
        
        
    }
    
    function handleSearch(&$data, &$moduleFields){
        //check whether the user entered an expression for this field
        if ($this->checkSearch(&$data)){
        
            $from = array();
            //$where = array();
            $phrase = array();
            
            $values = array();  //list of checked values
            
            if(is_array($data[$this->name])){
                foreach($data[$this->name] as $value){
                    $values[] = intval($value);
                }
            } else {
                $values[] = addslashes($data[$this->name]);
            }
/*print "<br>handleSearch {$this->name}<br>";
print debug_r($data);
print debug_r($values);
print debug_r($this);
die();*/
            if(empty($this->subModuleModuleIDField)){
                $moduleIDCondition = '';
            } else {
                $moduleIDCondition = "AND {$this->subModuleModuleIDField} = '{$this->moduleID}' ";
            }

            $selectFields = array();
            $selectFields[] = $this->subModuleRecordIDField;
            $selectFields[] = $this->codeIDField;
            $subJoins = array();
            $qualNames = array();
            foreach($selectFields as $selectField){
//                $subField = GetModuleField($this->subModuleID, $selectField);
//                $subJoins = array_merge($subJoins, $subField->makeJoinDef($this->subModuleID));
//                $qualNames[$selectField] = $subField->getQualifiedName($this->subModuleID);

                $qualNames[$selectField] = GetQualifiedName($selectField, ($this->subModuleID));
                $subJoins = array_merge($subJoins, GetJoinDef($selectField, ($this->subModuleID)));
            }
            

            $joinSQL = "INNER JOIN (SELECT DISTINCT {$qualNames[$this->subModuleRecordIDField]} FROM `{$this->subModuleID}` ";
            foreach($subJoins as $alias => $def){
                $joinSQL .= "$def\n";
            }
            $joinSQL .= "WHERE `{$this->subModuleID}`._Deleted = 0 {$moduleIDCondition}AND {$qualNames[$this->codeIDField]} IN (".join(',', $values).")) 
            AS {$this->subModuleID}_sub 
            ON (`{$this->moduleID}`.{$this->recordIDField} = {$this->subModuleID}_sub.{$this->subModuleRecordIDField})";

            //$from[$this->subModuleID] = $joinSQL;
            //$from[$this->tableAlias] = $joinSQL;
            $from[$this->subModuleID.'_sub'] = $joinSQL;

            $phrase[$this->name] = $this->getSearchPhrase($values);

            $searchDef = array(
                'f' => $from,
                'w' => array('1=1'),
                'p' => $phrase,
                'v' => array($this->name => $data[$this->name])
            );
            
        } else {
            $searchDef = NULL;
        }
        
        return $searchDef;
    
    }
    
function assignParentJoinAlias($dependentAlias, $parentAlias)
{
    global $SQLBaseModuleID;
    global $gTableAliasParents;

    print "{$this->moduleID}.{$this->name}: setting gTableAliasParents['$SQLBaseModuleID']['$dependentAlias'] = $parentAlias\n";
    $gTableAliasParents[$SQLBaseModuleID][$dependentAlias] = $parentAlias;
}


    function checkSearch(&$data){
        //determines whether the user checked any value
        //note: this could be improved to ignore both if NONE or ALL options are selected.
        $value = trim($data[$this->name]);
        return !empty($value);
        
    }
    
    //returns a human-readable expression of the search criteria.  (won't use $moduleFields)
    function getSearchPhrase($selectedValues){
        
        //get list of values, select the checked ones...
        //global $phrases;
        if(empty($this->phrase)){
            $phrase = $moduleFields[$this->name]->phrase;
        } else {
            $phrase = $this->phrase;
        }
        global $dbh;
        $labels = array();
        
        $r = $dbh->getAssoc($this->listSQL);
        dbErrorCheck($r);

        foreach($selectedValues as $selectedID){
            $labels[] = $r[$selectedID];
        }
        
        return ShortPhrase($phrase) . ': '.join(', ', $labels);
        
    }
    
    function isSubField(){
        return FALSE; //this field has no subfields
    }
    
}//end class CodeCheckSearchField



///
/// ListCondition classes
///

class ListCondition
{
var $name;
var $mode;
var $values = array();

function &Factory($element, $moduleID)
{
    return new ListCondition($element, $moduleID);
}
function ListCondition(&$element, $moduleID)
{
    $this->name = $element->attributes['fieldName'];
    $this->mode = $element->attributes['mode'];
    foreach($element->c as $sub_element){
        if('StaticValue' == $sub_element->type){
            $value = $sub_element->attributes['value'];
            switch(strtolower($sub_element->attributes['value'])){
            case 'true':
                $value = 'true';
                break;
            case 'false':
                $value = 'false';
                break;
            case 'null':
                $value = 'null';
                break;
            default:
                $value = '\''.$sub_element->attributes['value'].'\'';
                break;
            }
            $this->values[] = $value;
        }
        if('FieldValue' == $sub_element->type){
            $this->values[] = '\'[*'.$sub_element->attributes['value'].'*]\'';
        }
    }
}

function getExpression($listModuleID)
{
    $expression = '';
    global $SQLBasModuleID;

//    $moduleField = GetModuleField($listModuleID, $this->name);
//    $fieldExpression = $moduleField->getQualifiedName($listModuleID);
//    $joins = $moduleField->makeJoinDef($listModuleID);

    $fieldExpression = GetQualifiedName($this->name, $listModuleID);
    $joins = GetJoinDef($this->name, $listModuleID);


    switch($this->mode){
    case 'in':
        $strValues = join(',',$this->values);
        $expression = "$fieldExpression IN ($strValues)";
        break;
    case 'equals':
        $strValue = reset($this->values);
        $expression = "$fieldExpression = $strValue";
        break;
    default:
        trigger_error("Don't know how to handle FieldCondition mode '{$this->mode}'.", E_USER_WARNING);
        break;
    }
    return array('expression' => $expression, 'joins' => $joins);
}
} //end class FieldCondition


?>