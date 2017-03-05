<?php

/**
 *  Module Fields class definitions
 *
 *  This file contains the class definitions for the module fields. They
 *  correspond with the elements in the ModuleFields section of the module
 *  definition XML documents.  Each class represent a type of field, either
 *  "real" (TableField) or "virtual" (all others). For data retrieval, the 
 *  "virtual" fields can be referenced in screen and grid definitions much
 *  like the "real" ones. The individual implementations of the makeJoinDef
 *  and makeSelectDef methods in each class provide the necessary SQL snippets
 *  to build the correct SQL SELECT statement. For saving, the RemoteField
 *  supports saving data into tables other than the one that belongs to the
 *  module that is defined in a particular XML definition.
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
 * @version        SVN: $Revision: 499 $
 * @last-modified  SVN: $Date: 2007-02-16 13:43:40 -0800 (Fri, 16 Feb 2007) $
 */


/**
 * abstract parent class for fields that appear in ModuleFields section of Module Definition File
 */
class ModuleField
{
    var $name;
    var $phrase;
    var $moduleID;
    var $dataType;
    var $defaultValue; //default form value
    var $displayFormat; //sptintf-type formatting string

function Factory($element, $moduleID)
{
    return false;
}


function getQualifiedName($pModuleID)
{
    //override this
    return $this->makeSelectDef($pModuleID, false);
}


function makeSelectDef($pModuleID, $pIncludeFieldAlias = true)
{
    //override this
    return "--missing field {$this->name} in statement (unhandled field type)";
}


function makeJoinDef($pModuleID)
{
    //override this
    return "--missing field {$this->name} in statement (unhandled field type)";
}


function assignParentJoinAlias($dependentAlias, $parentAlias)
{
    global $SQLBaseModuleID;
    global $gTableAliasParents;

    print "{$this->moduleID}.{$this->name}: setting gTableAliasParents['$SQLBaseModuleID']['$dependentAlias'] = $parentAlias\n";
    $gTableAliasParents[$SQLBaseModuleID][$dependentAlias] = $parentAlias;

}


/**
 * returns a string which is unique for each join, but the same for different fields that share the same join.
 */
function getTableAliasKey()
{
    return '';
}

/**
 * whether or not this field needs to be retrieved after the record has been saved
 *
 * true for calculated fields (always), and viewfields (if using RPC updates)
 */
function needsReGet()
{
    return false;
}


//obsolete but interesting
function getFieldDependencies($pID)
{
    //override this: supposed to return a nested array of all fields that a field depends on
    return array();
}


/**
 * simply returns a list of directly dependent fields, not recursive
 */
function getDependentFields()
{
    return array();
}


/**
 * creates a trigger def to update the RDC for fields that depend on this field
 *
 */
function makeRDCTrigger($callerModuleID, $callerDefs= array())
{
    $debug_prefix = debug_indent("ModuleField-makeRDCTrigger() {$this->moduleID}.{$this->name}:");
    print "$debug_prefix callerModuleID = $callerModuleID\n";

/*
    //bailout 1: no trigger for local tablefield
    if(('tablefield' == get_class($this)) && ($callerModuleID == $this->moduleID)){
        print "$debug_prefix skipping triggermaking - this is a local tableField\n";
        debug_unindent();
        return true;
    }
*/

    //bailout 2: PK field
    $trigger_moduleInfo = GetModuleInfo($this->moduleID);
    $trigger_pkField = $trigger_moduleInfo->getPKField();
    /*$t_module = GetModule($this->moduleID);
    $pkField = end($t_module->PKFields); //picks "most local" PK field*/
    
    if(($trigger_pkField == $this->name)){
        print "$debug_prefix skipping triggermaking - this is a PK field\n";
        debug_unindent();
        return true;
    }


    if('tablefield' == get_class($this)){ //create the trigger

        $joins = array();
        $newModuleFields = array();
        foreach($callerDefs as $callerDef){
            print "$debug_prefix the callerDef element:\n";
            indent_print_r($callerDef);
            $newModuleFields[] = $callerDef;

        }

        //make the SQL statement (finds the records that need to ne updated)
        global $SQLBaseModuleID;
        $SQLBaseModuleID = $callerModuleID;

        $callerModule = GetModule($callerModuleID);
        $callerPKFieldName = end($callerModule->PKFields);

        $joins = array();
print "$debug_prefix newModuleFields\n";
indent_print_r($newModuleFields);

        $alias = $callerModuleID;
        foreach($newModuleFields as $name => $mf){
print "$debug_prefix calling makeJoinDef for new modulefield. alias is $alias\n";
            $joins = array_merge($mf->makeJoinDef($alias), $joins);
            $alias = GetTableAlias($mf, $alias);
        }
        $triggerAlias = $alias; //last alias is expected to be the alias of the triggering module
    //global $gTableAliasCached;
    //indent_print_r($gTableAliasCached);
        $joins = SortJoins($joins);

        //$SQL = "SELECT $callerAlias.$callerPKFieldName\n";
        $SQL = "SELECT `$callerModuleID`.$callerPKFieldName\n";
        //$SQL .= "FROM `{$this->moduleID}` -- $callerModuleID\n";
        $SQL .= "FROM `$callerModuleID`\n";

        print "$debug_prefix made these joins for {$this->moduleID}.{$this->name} (trigger alias: $triggerAlias):\n";
        indent_print_r($joins);

        foreach($joins as $join){
            $SQL .= $join."\n";
        }
        $SQL .= "WHERE `$callerModuleID`._Deleted = 0\n";
        $SQL .= "AND `$triggerAlias`._Deleted = 0\n";
        $SQL .= "AND `$triggerAlias`.$trigger_pkField = '/**RecordID**/'\n";
        print "$debug_prefix triggerSQL\n$SQL\n";
        
        if(CheckSQL($SQL)){
            print "$debug_prefix verified SQL statement\n";

            //open RDCTriggersModel file
            $modelFileName = "RDCTriggersModel.php";
            $CreateFileName = "{$this->moduleID}_RDCTriggers.gen";
            $CreateFilePath = GENERATED_PATH. "/{$this->moduleID}/$CreateFileName";
            //open (or create) a RDCTriggers file for the callerModuleID and save the trigger SQL

            if(file_exists($CreateFilePath)){
                include($CreateFilePath); //sets $RDCtriggers
            } else {
                $RDCtriggers = array();
            }
            $RDCtriggers[$callerModuleID] = $SQL;

            $codeArray = array('/**RDCtriggers**/' => escapeSerialize($RDCtriggers));
    print "$debug_prefix Saving trigger\n";

            //file creation code...
            SaveGeneratedFile($modelFileName, $CreateFileName, $codeArray, $this->moduleID);
        }
print "\n";
        //restore normal module
        // $foreignModules[$this->moduleID] = $bakModule;

    } else { //continue recursion:
    
        //append this field's caller def
//        $def = $this->makeRDCCallerDef();
print "$debug_prefix def\n";
indent_print_r($def);

        $defs = $callerDefs;
        $defs[] = $this;
        /*if(!empty($def)){
            $defs[] = $def;
        }*/
        
        //3. recursively calls dependent fields
        $deps = $this->getDependentFields();
        print "$debug_prefix deps\n";
        indent_print_r($deps);
        if(count($deps) > 0){
            //foreach dependent field (ignore local tablefields), call $moduleField->makeRDCTrigger()
            foreach($deps as $dep){
                $moduleField = GetModuleField($dep['moduleID'], $dep['name']);
                if($moduleField->moduleID == $this->moduleID){
                    $moduleField->makeRDCTrigger($callerModuleID, $callerDefs);
                } else {
                    $moduleField->makeRDCTrigger($callerModuleID, $defs);
                }
            }
        }

    }
    debug_unindent();
}


/**
 * should return a definition how the calling field connects to the dependent field (which is to contain the trigger)
 */
function makeRDCCallerDef()
{
    return null; //override
}


function getGridAlign()
{
    switch ($this->getDataType()){
    case 'bool':
    case 'date':
    case 'datetime':
        $gridAlign = 'center';
        break;
    case 'float':
    case 'int':
    case 'money':
        $gridAlign = 'right';
        break;
    case 'text':
        $gridAlign = 'justify';
        break;
    default:
        $gridAlign = 'left';
        break;
    }

    return $gridAlign;
}

function getDataType()
{
    return $this->dataType;
}

}





class TableField extends ModuleField
{
    var $dbFlags;
    var $validate;
    var $defaultValue;   //to be passed on to ScreenFields that need them
    var $listType;       //to be passed on to ScreenFields that need them
    var $listConditions = array(); //to be passed on to ScreenFields that need them

    function Factory($element, $moduleID){
        //check for a dbFlags attribute - this avoids a "Warning"
        if (isset($element->attributes['dbFlags'])){
            $dbFlags = $element->attributes['dbFlags'];
        } else {
            $dbFlags = '';
        }
        
        if(array_key_exists('validate', $element->attributes)){
            $validate = $element->attributes['validate'];
        } else {
            $validate = '';
        }
        //don't validate if it contains the string 'noValidate'
        if(false !== strpos($validate, 'noValidate')){
            $validate = '';
        }
        
        if(isset($element->attributes['defaultValue'])){
            $defaultValue = $element->attributes['defaultValue'];
        } else {
            $defaultValue = '';
        }
        if(isset($element->attributes['listType'])){
            $listType = $element->attributes['listType'];
        } else {
            $listType = '';
        }
        
        $field =& new TableField (
            $element->attributes['name'],
            $element->attributes['type'],
            $element->attributes['displayFormat'],
            $dbFlags,
            $element->attributes['phrase'],
            $validate,
            $defaultValue,
            $moduleID,
            $listType
            );

        if(count($element->c) > 0){
            foreach($element->c as $sub_element){
                if('ListCondition' == $sub_element->type){
                    $conditionObj = $sub_element->createObject($moduleID);
                    $field->listConditions[$conditionObj->name] = $conditionObj;
                }
            }
        }

        return $field;
    }
    
    function TableField($pName, $pDataType, $pDisplayFormat, $pDBFlags, $pPhrase, $pValidate, $pDefaultValue, $pModuleID, $pListType = null)
    {
        $this->name = $pName;
        $this->dataType = $pDataType;
        $this->displayFormat = $pDisplayFormat;
        $this->dbFlags = $pDBFlags;
        $this->phrase = $pPhrase;
        $this->validate = $pValidate;
        $this->defaultValue = $pDefaultValue;
        $this->moduleID = $pModuleID;
        $this->listType = $pListType;
    }

    function getQualifiedName($pModuleID){
        $debug_prefix = debug_indent("TableField-getQualifiedName() {$this->moduleID}.{$this->name}:");

        $name = "`$pModuleID`.{$this->name}";

        print "$debug_prefix returned $name\n";
        debug_unindent();
        return $name;
    }

    function makeSelectDef($pModuleID, $pIncludeFieldAlias = true){

        if($pIncludeFieldAlias){
            return "`{$pModuleID}`.{$this->name}";

        } else {
            switch($this->dataType){
            case 'money':
                return "ROUND(`{$pModuleID}`.{$this->name}, 2)";
                break;
            default:
                return "`{$pModuleID}`.{$this->name}";
            }
        }
    }

    function makeJoinDef($pModuleID){
        $debug_prefix = debug_indent("TableField-makeJoinDef() {$this->moduleID}.{$this->name}:");

        $joins = array();
        //no need for a join
        printd("$debug_prefix no need for a join\n", 2);

        debug_unindent();
        return $joins;
    }
    
    function getFieldDependencies($pID){
        //a TableField has no dependencies
        return array();
    }
    
}







class ForeignField extends ModuleField
{
    var $localTable;
    var $localKey;
    var $foreignTable;
    var $foreignTableAlias;
    var $foreignKey;
    var $foreignField;
    var $listCondition;  //deprecated - to be removed
    var $listConditions = array();
    var $joinType;

    function Factory($element, $moduleID){

        if(array_key_exists('localTable', $element->attributes)){
            $localTable = $element->attributes['localTable'];
        } else {
            //$localTable = '';
            $localTable = $moduleID;
        }

        $foreignTableAlias = ''; //this seems obsolete


        $field =& new ForeignField (
            $element->attributes['name'],
            $element->attributes['type'],
            $element->attributes['displayFormat'],
            $localTable,
            $element->attributes['key'], //localKey
            $element->attributes['foreignTable'],
            $foreignTableAlias,
            $element->attributes['foreignKey'],
            $element->attributes['foreignField'],
            $element->attributes['listCondition'],
            $element->attributes['triggerCondition'],
            $element->attributes['joinType'],
            $element->attributes['phrase'],
            $element->attributes['defaultValue'],
            //$moduleID
            $localTable
            );

        if(count($element->c) > 0){
            foreach($element->c as $sub_element){
                if('ListCondition' == $sub_element->type){
                    $conditionObj = $sub_element->createObject($moduleID);
                    $field->listConditions[$conditionObj->name] = $conditionObj;
                }
            }
        }

        return $field;
    }

    function ForeignField(
        $pName,
        $pDataType,
        $pDisplayFormat,
        $pLocalTable,
        $pLocalKey,
        $pForeignTable,
        $pForeignTableAlias,
        $pForeignKey,
        $pForeignField,
        $pListCondition,
        $pTriggerCondition,
        $pJoinType,
        $pPhrase,
        $pDefaultValue,
        $pModuleID)
    {
        $localKeys = explode(' ', $pLocalKey);
        $foreignKeys = explode(' ', $pForeignKey);

        if(count($localKeys) != count($foreignKeys)){
            die("ForeignField $pModuleID.$pName: Inconsistent number of local keys vs. foreign keys.");
        }

        $this->name = $pName;
        $this->displayFormat = $pDisplayFormat;
        $this->localTable = $pLocalTable;
        $this->localKey = $localKeys[0];
        if(count($localKeys) == 2){ //only supports a second join, not a third
            $this->localKey2 = $localKeys[1];
        }
        $this->foreignTable = $pForeignTable;
        $this->foreignTableAlias = $pForeignTableAlias;
        $this->foreignKey = $foreignKeys[0];
        if(count($foreignKeys) == 2){
            $this->foreignKey2 = $foreignKeys[1];
        }
        $this->foreignField = $pForeignField;
        $this->listCondition = $pListCondition;
        $this->triggerCondition = $pTriggerCondition;
        $this->joinType = $pJoinType;
        $this->phrase = $pPhrase;
        $this->defaultValue = $pDefaultValue;
        $this->moduleID = $pModuleID;
        $this->dataType = $pDataType;

        //simple sanity check to avoid infinite loops
        if($pName == $pLocalKey){
            print "The foreignField:\n";
            print_r($this);
            die("ForeignField '{$pName}': Name and (local) Key can't be the same (error)");
        }
    }


    function getQualifiedName($pModuleID){
        $debug_prefix = debug_indent("ForeignField-getQualifiedName() {$this->moduleID}.{$this->name}:");
        print "$debug_prefix pModuleID = $pModuleID\n";

        $foreignAlias = GetTableAlias($this, $pModuleID);

        //let's check to make sure the foreignField is a TableField
        //$ffType = $this->getForeignFieldType();

        $foreignField = GetModuleField($this->foreignTable, $this->foreignField);
        $type = get_class($foreignField);

        print "$debug_prefix foreignField type: $ffType\n";

        if('tablefield' == $type){
            $name = "`{$foreignAlias}`.{$this->foreignField}";
        } else {

            $name = $foreignField->getQualifiedName($foreignAlias);
        }

        print "$debug_prefix returned $name\n";

        debug_unindent();
        return $name;
    }

    //return the ModuleField type of the field that the foreignField property points to
    function getForeignFieldType(){
        $debug_prefix = debug_indent("ForeignField-getForeignFieldType() {$this->moduleID}.{$this->name}:");

        $t_mf = GetModuleField($this->foreignTable, $this->foreignField);
        $type = get_class($t_mf);

        print "$debug_prefix returns $type (end)\n";
        debug_unindent();
        return $type;
    }


    function makeSelectDef($pModuleID, $pIncludeFieldAlias = true){
        $debug_prefix = debug_indent("ForeignField-makeSelectDef() {$this->moduleID}.{$this->name}:");
        print "$debug_prefix pModuleID = {$pModuleID}\n";
        print "$debug_prefix pIncludeFieldAlias = {$pIncludeFieldAlias}\n";

        print "$debug_prefix Making select def for: {$this->foreignTable}\n";
        print "$debug_prefix   foreign Module: {$this->foreignTable} \n";
        print "$debug_prefix   foreign field name: {$this->foreignField}\n";


        $foreignAlias = GetTableAlias($this, $pModuleID);
        $t_mf = GetModulefield($this->foreignTable, $this->foreignField);
        
        if(!is_object($t_mf)){
            die("$debug_prefix ModuleField '{$this->foreignField}' appears not to exist in module '{$this->foreignTable}' (error)");
        }
        
        //if the foreignField field is not a TableField, create its selectDef
        if(get_class($t_mf) != 'tablefield'){
            print "$debug_prefix {$t_mf->name} is a ".get_class($t_mf)."\n";
            if($pIncludeFieldAlias){
                $def = $t_mf->makeSelectDef($foreignAlias, false) . ' AS ' . $this->name;
            } else {
                $def = $t_mf->makeSelectDef($foreignAlias, false);
            }
            //die("can only join foreign fields to a table field");
        } else {

            if($pIncludeFieldAlias){
                $def = "`{$foreignAlias}`.{$this->foreignField} AS {$this->name}";
            } else {
                switch($this->dataType){
                case 'money':
                    $def = "ROUND(`{$foreignAlias}`.{$this->foreignField}, 2)";
                    break;
                default:
                    $def = "`{$foreignAlias}`.{$this->foreignField}";
                }
            }
        }
        print "$debug_prefix returning $def\n";
        debug_unindent();
        return $def;
    }


    function makeJoinDef($pModuleID)
    {
        $debug_prefix = debug_indent("ForeignField-makeJoinDef() {$this->moduleID}.{$this->name}:");
        print "$debug_prefix \$pModuleID = '$pModuleID'\n";
        indent_print_r($this);
        
        $joins = array();
        
        //aliases
        $foreignAlias = GetTableAlias($this, $pModuleID);
        if(empty($pModuleID)){
            global $SQLBaseModuleID;
            $localAlias = $SQLBaseModuleID; //suppose we're at the base module
        } else {
            $localAlias = $pModuleID;  //uses the local alias passed from the calling field
        }
        
        //import dependent fields:
        $localKeyField = GetModuleField($this->moduleID, $this->localKey);
        $foreignKeyField = GetModuleField($this->foreignTable, $this->foreignKey);
        $foreignField = GetModuleField($this->foreignTable, $this->foreignField);
        
        //make sure they are real moduleFields
        if(empty($localKeyField)){
            die("$debug_prefix local key {$this->moduleID}.{$this->localKey} not found in ModuleFields\n");
        }
        if(empty($foreignKeyField)){
            die("$debug_prefix foreign key {$this->foreignTable}.{$this->foreignKey} not found in ModuleFields\n");
        }
        if(empty($foreignField)){
            die("$debug_prefix foreign field {$this->foreignTable}.{$this->foreignField} not found in ModuleFields\n");
        }
        
        //determine their classes
        $dt_localKeyField = get_class($localKeyField);
        $dt_foreignKeyField = get_class($foreignKeyField);
        $dt_foreignField = get_class($foreignField);
        
        if('tablefield' != get_class($localKeyField)){
            $parentAlias = GetTableAlias($localKeyField, $pModuleID);
        } else {
            $parentAlias = $pModuleID;
        }
        $this->assignParentJoinAlias($foreignAlias, $parentAlias);


        //those that aren't tablefields need another joinDef
        
        if('tablefield' != $dt_foreignField){
            //get joindef for foreignField
            $foreignFieldJoin = $foreignField->makeJoinDef($foreignAlias);
            $joins = array_merge($foreignFieldJoin, $joins);
            
            $dt_foreignField = get_class($foreignField);
            print "$debug_prefix dt_foreignField = $dt_foreignField\n";
            
            indent_print_r($foreignFieldJoin);
        }
        if('tablefield' != $dt_foreignKeyField){
            //get joindef for foreignKeyField
            $foreignKeyJoin = $foreignKeyField->makeJoinDef($foreignAlias);
            $joins = array_merge($foreignKeyJoin, $joins);

            $dt_foreignKey = get_class($dt_foreignKeyField);
            print "$debug_prefix dt_foreignKey = $dt_foreignKey\n";
        } 
        if('tablefield' != $dt_localKeyField){
            //get joindef for localKeyField
            $localJoin = $localKeyField->makeJoinDef($localAlias);
            $joins = array_merge($localJoin, $joins);
            indent_print_r($localJoin);
        }
        if($dt_localKeyField == 'foreignfield' && $localKeyField->getForeignFieldType() != 'tablefield'){

            //check whether the localKeyField's foreign field, in turn, is a tablefield or not
            $dt_localKey_ForeignField = $localKeyField->getForeignFieldType();
            print "$debug_prefix dt_localKey_ForeignField = $dt_localKey_ForeignField\n";

            print "$debug_prefix skipping ordinary join because local key isn't a TableField\n";

            if('tablefield' == $dt_localKey_ForeignField){
            } else {

                switch($dt_localKey_ForeignField){
                case 'foreignfield';
                    die("$debug_prefix Can't handle ForeignField");
                    break;
                case 'remotefield':
                    print "$debug_prefix adding a special bridge join\n";

                    $localKeyAlias = GetTableAlias($localKeyField, $localAlias);

                    //get the bridge field
                    $bridgeField = GetModuleField($localKeyField->foreignTable, $localKeyField->foreignField);

                    print "$debug_prefix localAlias = $localAlias: localKeyAlias = $localKeyAlias, pModuleID $pModuleID\n";
                    print "$debug_prefix the bridge field:\n";
                    //$bridgeField->moduleID = $this->moduleID;
                    indent_print_r($bridgeField);
                    //$bridgeAlias = GetTableAlias($bridgeField, $localAlias);
                    $bridgeAlias = GetTableAlias($bridgeField, $localKeyAlias);

                    //add the bridge join
                    $bridgejoin = "LEFT OUTER JOIN `{$this->foreignTable}` AS {$foreignAlias} \n";
                    $bridgejoin .= "ON (`{$bridgeAlias}`.{$bridgeField->remoteField} = `{$foreignAlias}`.{$this->foreignKey} )";

                    print "$debug_prefix made bridge join\n";
                    indent_print_r($bridgejoin);

                    //$joins = array_merge($bridgeField->makeJoinDef($localAlias), $joins);
                    $joins = array_merge($bridgeField->makeJoinDef($localKeyAlias), $joins);
                    $joins[$foreignAlias] = $bridgejoin;


                    break;
                case 'dynamicforeignfield':


                    $localKey_ForeignField = GetModuleField($localKeyField->getForeignModuleID(), $localKeyField->getTarget());

                    $localKeyAlias = GetTableAlias($localKeyField, $localAlias);
                    $joins = array_merge($joins, $localKey_ForeignField->makeJoinDef($localKeyAlias));

                    break;
                default:
                    die("$debug_prefix Can't handle $dt_localKey_ForeignField");
                    break;
                }

            }

        } else {

            //(create local joindef)

            if ($this->joinType == "left"){
                //list condition need to be attached in join statement for MySQL
                $join = "LEFT OUTER JOIN `{$this->foreignTable}` AS {$foreignAlias} ";
            } else {
                $join = "INNER JOIN `{$this->foreignTable}` AS {$foreignAlias} ";
            }

            $localKeyName = $localKeyField->getQualifiedName($localAlias);
            $foreignKeyName = $foreignKeyField->getQualifiedName($foreignAlias);
            print "$debug_prefix local key name $localKeyName\n";
            print "$debug_prefix foreign key name $foreignKeyName\n";

            $join .= "\n   ON ($localKeyName = $foreignKeyName ";

            if(!empty($this->localKey2)){
                $localKeyField2 = GetModuleField($this->moduleID, $this->localKey);
                $localKeyName2 = $localKeyField2->getQualifiedName($localAlias);
                $foreignKeyName2 = $foreignAlias.'.'.$this->foreignKey2; //assumes tablefield
                $join .= "\n     AND $localKeyName2 = $foreignKeyName2";
            }


            if (!empty($this->triggerCondition)){
                //look for local module id, replace with localAlias
                $condition = str_replace("={$this->foreignTable}.", "=$foreignAlias.", $this->triggerCondition);
                $join .= "\n     AND `{$localAlias}`.$condition";
            } elseif (!empty($this->listCondition)){
                $condition = $this->listCondition;
                $join .= "\n     AND `{$foreignAlias}`.$condition";
            }

            $join .= ")";

            $joins[$foreignAlias] = $join;
        }

        indent_print_r($joins);
        debug_unindent();
        return $joins;
    }


    //for making "extended" Get and List statements
    function makeExtendedJoinDef($pModuleID){
        print "\n\n";
        print ">>> ===-===-===-===-===-===-===-===-===-===-===-===-===-===-=== >>>\n";
        print "          f o r e i g n f i e l d         b e g i n\n";
        print "                {$this->moduleID} . {$this->name} \n";
        print ">>> ===-===-===-===-===-===-===-===-===-===-===-===-===-===-=== >>>\n";
        print "ForeignField-makeExtendedJoinDef(): {$this->name} (begin)\n\n";
        $joins = array();
        
        //register this field on the joinField stack
        //if($this->useJoinFieldStack) PushJoinFieldStack(&$this);
        
        print "ForeignField: foreign Module:      {$this->foreignTable} \n";
        print "ForeignField: foreign field name:  {$this->foreignField}\n";
        print "ForeignField: foreign table alias: {$this->foreignTableAlias}\n";
        print "ForeignField: local key:           {$this->localKey}\n";
        print "ForeignField: foreign key:         {$this->foreignKey}\n";
        print "ForeignField: local module:        {$this->moduleID}\n";
        print "ForeignField: local table:         {$this->localTable}\n";

        $plainFTAlias = GetTableAlias($this, $pModuleID);
        //$plainFTAlias = $pModuleID;
        $foreignTableAlias = $plainFTAlias;
        
        print "ForeignField: foreignTableAlias = '$foreignTableAlias', plainFTAlias = '$plainFTAlias'\n";

        //foreign field ('&' removed)
        $t_ModuleFields = GetModulefields($this->foreignTable);
        $t_mf = $t_ModuleFields[$this->foreignField];

        //local key field
        $local_ModuleFields = GetModulefields($this->moduleID);
        if(isset($local_ModuleFields[$this->localKey])){
            $t_key = $local_ModuleFields[$this->localKey];
        } else {
            print "looked for localKey {$this->moduleID}.{$this->localKey}\n";
            
            global $foreignModules;
            print_r($foreignModules);
            
            die("localKey {$this->moduleID}.{$this->localKey} not found in local moduleFields");
        }
        //if there's a problem finding the key field
        if (!is_object($t_key)){
            print "ForeignField: this:\n";
            print_r ($this);
            die( "ForeignField: .....localKey = ". $this->localKey."\nlocalKey is empty\n");
        }
        
        
        if(empty($pModuleID)){
            global $SQLBaseModuleID;
            $localAlias = $SQLBaseModuleID; //suppose we're at the base module
        } else {
            $localAlias = $pModuleID;
        }
            
        print "ForeignField {$this->name}: localAlias = $localAlias\n";
        
        
        
        if(get_class($t_key) == 'ForeignField' && $t_key->getForeignFieldType() == 'remotefield'){
            print "iii. we avoid making a local join since the key does not point to a TableField.\n";
            print "iii. localAlias: $localAlias\n";
        
            //print "ForeignField: the key field is a RemoteField\n";
            //$joins = $t_key->makeJoinDef($pModuleID, $pDFF, $this, 'key');
            //print_r($joins);
            
            
            
            //get the RemoteField
            $t_rmfs = GetModuleFields($t_key->foreignTable);
            $t_rmf = $t_rmfs[$t_key->foreignField];
            $remoteTblAlias = GetTableAlias($t_rmf, $localAlias);

            
            //add the join towards the RemoteField...
            $t_join = "LEFT OUTER JOIN `{$this->foreignTable}` AS {$foreignTableAlias} \n";
            $t_join .= "ON ({$remoteTblAlias}.{$t_rmf->remoteField} = {$foreignTableAlias}.{$this->foreignKey} )";
                        
            $joins = array_merge($joins, $t_rmf->makeJoinDef($localAlias));
            $joins[$foreignTableAlias] = $t_join;
            
        } else { 
            print "iv. key type: ".get_class($t_key)."\n";
        
            //build join statement:
            if ($this->joinType == "left"){
                //list condition need to be attached in join statement for MySQL
                $t_join = "LEFT OUTER JOIN `{$this->foreignTable}` AS {$foreignTableAlias} ";
            } else {
                $t_join = "INNER JOIN `{$this->foreignTable}` AS {$foreignTableAlias} ";
            }
                    
            $qKeyName = $t_key->getQualifiedName($localAlias);
            print "ForeignField-makeJoinDef(): key qualified name = $qKeyName\n";
            
            $t_join .= "\n   ON (".$qKeyName." = {$foreignTableAlias}.{$this->foreignKey} ";
            
            
            if ($this->listCondition != ''){
                $t_join .= "\n     AND {$foreignTableAlias}.{$this->listCondition})";
            } else {
                $t_join .= ")";
            }
            
            print "ForeignField-makeExtendedJoinDef(): join is:\n $t_join\n";
            $joins[$foreignTableAlias] = $t_join;
        

            //if the localKey is not a TableField, create its joinDef
            switch(get_class($t_key)){
            case 'tablefield':
                
                break;
            case 'remotefield':
    print "ForeignField-makeExtendedJoinDef(): Key is RemoteField: DFF=$pDFF";
                //$joins = array_merge($t_key->makeJoinDef($t_alias, $pDFF, $this, 'key'), $joins);
                $joins = array_merge($t_key->makeJoinDef($localAlias), $joins);
        
            
                break;
            case 'foreignfield':
    print "ForeignField-makeExtendedJoinDef(): Key is ForeignField: \n";
   
                $joins = array_merge($t_key->makeJoinDef($localAlias), $joins);
                
                break;
            default:
                print "ForeignField: Auto-join not implemented: ". get_class($t_key) ." as key of a ForeignField\n";
                print "ForeignField: This is OK if the referenced field is also part of the selected fields\n\n";
                die("end");
            }
        }


        
        if(!is_object($t_mf)){
            print_r($t_ModuleFields);
        //  print_r($this);
            print "\n$t_mf\n";
            die("mf is empty");
        }
        
        //if the foreign (pointed to) field is not a TableField, create its joinDef
        if(get_class($t_mf) != 'tablefield'){
            //$t_alias = $t_mf->getTableAlias($pModuleID);
            $t_alias = $plainFTAlias; //$this->foreignTableAlias;
            
            
            print("***{$this->name}*********** alias = $t_alias \n\n");
            //$joins = $t_mf->makeJoinDef($pModuleID, &$t_ModuleFields);
            
            print "ForeignField-makeExtendedJoinDef({$this->name}): calling makeJoinDef for foreignField ".get_class($t_mf)." {$t_mf->name}\n";
            
            //the foreign field's joinDef should generally be preceded by the previous joins
            if($pRole == 'field'){
                $t_Role = 'field';
            } else {
                $t_Role = 'key';
            }
            $joins = array_merge($joins, $t_mf->makeJoinDef($t_alias));
            
        } else {
            print "ForeignField-makeExtendedJoinDef(): {$this->name}->foreignField is a TableField\n";
        }

        print "ForeignField-makeExtendedJoinDef(): {$this->name} <begin joins> created the following joins: \n";
        print_r( $joins );
        print "ForeignField-makeExtendedJoinDef(): {$this->name} <end joins>\n";
        
       // if($this->useJoinFieldStack) PopJoinFieldStack(&$this);
        
        print "<<< ===-===-===-===-===-===-===-===-===-===-===-===-===-===-=== <<<\n";
        print "\nForeignField-makeExtendedJoinDef(): {$this->name} (end)\n";
        print "<<< ===-===-===-===-===-===-===-===-===-===-===-===-===-===-=== <<<\n";
        return $joins;
    }
    
    
    function getFieldDependencies($pID){
        //return a nested array of all fields that a field depends on - a ForeignField depends on 3 other fields
        $localModuleFields   = GetModuleFields($this->moduleID);
        $foreignModuleFields = GetModuleFields($this->foreignTable);
        
        $localKey     = $localModuleFields[$this->localKey];
        $foreignKey   = $foreignModuleFields[$this->foreignKey];
        
        
        $foreignField = $foreignModuleFields[$this->foreignField];
        
        $deps = array(
            array(
                'ID' => $pID . '_01',
                'parentID' => $pID,
                'moduleID' => $this->moduleID, 
                'name' => $this->localKey,
                'type' => get_class($localKey),
                'deps' => $localKey->getFieldDependencies($pID . '_01'),
                ), 
            array(
                'ID' => $pID . '_02',
                'parentID' => $pID,
                'moduleID' => $this->foreignTable, 
                'name' => $this->foreignKey,
                'type' => get_class($foreignKey),
                'deps' => $foreignKey->getFieldDependencies($pID . '_02')
                ),
            array(
                'ID' => $pID . '_03',
                'parentID' => $pID,
                'moduleID' => $this->foreignTable, 
                'name' => $this->foreignField,
                'type' => get_class($foreignField),
                'deps' => $foreignField->getFieldDependencies($pID . '_03')
                )
        );
        
        return $deps;
    }
    
    function getDependentFields(){
        $deps = array(
            array(
                'moduleID' => $this->moduleID,
                'name' => $this->localKey
                ), 
            array(
                'moduleID' => $this->foreignTable,
                'name' => $this->foreignKey
                ),
            array(
                'moduleID' => $this->foreignTable,
                'name' => $this->foreignField
                )
        );
        
        return $deps;
    }
    
    function makeRDCCallerDef(){
        print "ForeignField-makeRDCCallerDef() {$this->moduleID}.{$this->name}: \n";
        $t_module = GetModule($this->moduleID);
        $pkField = end($t_module->PKFields); //picks "most local" PK field
        
        //creates an element object of the "reverse" foreignfield of this one.
        /*$attributes = array(
            'name' => $this->foreignField, //$pkField, 
            'localTable' => $this->foreignTable,
            'key' => $this->foreignKey,
            'foreignTable' => $this->moduleID,
            'foreignField' => $pkField,
            'foreignKey' => $this->localKey, //make sure this does not point to a FF????????? Or, support it?
            'joinType' => 'inner',
            'triggerCondition' => $this->listCondition,
            'madeby' => "{$this->moduleID}.{$this->name} (foreignfield)"
        );*/
        
        //"un-flipped" version
        $attributes = array(
            'name' => $pkField, 
            'localTable' => $this->moduleID,
            'key' => $this->localKey,
            'foreignTable' => $this->foreignTable,
            'foreignField' => $this->foreignField,
            'foreignKey' => $this->foreignKey,
            'joinType' => 'inner',
            'triggerCondition' => $this->listCondition,
            'madeby' => "{$this->moduleID}.{$this->name} (foreignfield)"
        );
        return new Element($this->foreignField, 'ForeignField', $attributes);
    }

    function getListSQL(){
        $SQL = "SELECT {$this->foreignKey} AS ID, {$this->foreignField} AS Name \n";
        $SQL .= "FROM {$this->foreignTable} \n";
        if (!empty($this->listCondition)){
            $SQL .= "WHERE {$this->listCondition} \n";
        }
        $SQL .= "ORDER BY {$this->foreignField}; \n";


        return $SQL;
    }
    
    function getLocalModuleID(){
        return $this->localTable;
    }
    function getLocalKey(){
        return $this->localKey;
    }
    function getForeignModuleID(){
        return $this->foreignTable;
    }
    function getForeignKey(){
        return $this->foreignKey;
    }
    function getTarget(){
        return $this->foreignField;
    }
    function getCondition($foreignAlias){
        if(!empty($foreignAlias)){
            return "`$foreignAlias`.{$this->listCondition}";
        } else {
            return $this->listCondition;
        }
    }

    function getTableAliasKey($parentAlias)
    {
        global $SQLBaseModuleID;
        $localModuleID = $this->moduleID;

        if(!empty($parentAlias)){
            //sanity check: makes sure $localModuleAlias.$localKey isn't actually the same field as $foreignModuleID.$foreignKey
            if(preg_replace('/[\d]*/', '', $parentAlias) == $foreignModuleID){
                $localModuleAlias = $localModuleID;
            } else {
                $localModuleAlias = $parentAlias;
            }
        } else {
            $localModuleAlias = $localModuleID;
        }


        //$condition is used by CodeField
        $condition = $this->listCondition;

        return
            $localModuleAlias.'.'.$this->localKey.'|'.
            $this->foreignTable.'.'.$this->foreignKey.'|'.
            $condition;
    }

/**
 * returns the data type
 *
 * Preferrably not called at run-time.
 */
function getDataType()
{
    if(empty($this->dataType)){
        $foreignField = GetModuleField($this->foreignTable, $this->foreignField);
        return $foreignField->getDataType();
    } else {
        return $this->dataType;
    }
}
}







class CodeField extends ForeignField
{
    var $codeTypeID;
    var $sampleItems = array();
    
    function Factory($element, $moduleID){

        if(array_key_exists('localTable', $element->attributes)){
            $localTable = $element->attributes['localTable'];
        } else {
            $localTable = '';
        }
    
        $foreignTableAlias = ''; //this seems obsolete

        
        $field =& new CodeField (
            $element->attributes['name'],
            $element->attributes['type'],
            $element->attributes['displayFormat'],
            $localTable,
            $element->attributes['key'], //localKey
            $foreignTableAlias,
            $element->attributes['codeTypeID'],
            $element->attributes['phrase'],
            $element->attributes['defaultValue'],
            $moduleID
            );
            
        if(count($element->c) > 0){
            foreach($element->c as $sub_element){
                if('ListCondition' == $sub_element->type){
                    $conditionObj = $sub_element->createObject($moduleID);
                    $field->listConditions[$conditionObj->name] = $conditionObj;
                }
            }
        }

        return $field;
    }

    function CodeField(
        $pName,
        $pDataType,
        $pDisplayFormat,
        $pLocalTable,
        $pLocalKey,
        $pForeignTableAlias,
        $pCodeTypeID,
        $pPhrase,
        $pDefaultValue,
        $pModuleID)
    {
        $this->name = $pName;
        $this->dataType = $pDataType;
        $this->displayFormat = $pDisplayFormat;
        $this->localTable = $pLocalTable;
        $this->localKey = $pLocalKey;
        $this->foreignTable = 'cod';
        $this->foreignTableAlias = $pForeignTableAlias;
        $this->foreignKey = 'CodeID';
        $this->foreignField = 'Description'; //this should be based on current language
        $this->listCondition = "CodeTypeID = '$pCodeTypeID'";
        $this->joinType = 'left';
        $this->codeTypeID = $pCodeTypeID;
        $this->phrase = $pPhrase;
        $this->defaultValue = $pDefaultValue;
        $this->moduleID = $pModuleID;
    }

}






class CombinedField extends ModuleField
{
    var $content = array();
    var $dataType = 'text';
    var $separator = '';

    function Factory($element, $moduleID){

        $fieldObject = new CombinedField (
            $element->attributes['name'],
            $element->attributes['phrase'],
            $element->attributes['defaultValue'],
            $moduleID,
            $element->attributes['separator']
            );

        $mfs = GetModuleFields($moduleID);

        //look through children of element object and append field refs and text data
        foreach($element->c as $contentItem){
            if(is_object($contentItem)){
                switch(get_class($contentItem)){
                case 'element':
                    if('CombinedFieldRef' == $contentItem->type){
                        $fieldRef = $mfs[$contentItem->name];
                        $fieldObject->AddFieldRef($fieldRef, $contentItem->attributes['prepend'], $contentItem->attributes['append']);
                    } else {
                        //pass on HTML elements, and whatever
                        $fieldObject->AddTextData($contentItem->getContent());
                    }
                    break;
                case 'characterdata':
                    $fieldObject->AddTextData($contentItem->content);
                    break;
                default:
                    print_r($contentItem);
                    die('CombinedField::Factory: class type not handled');
                }
            } else {

                $fieldObject->AddTextData($contentItem);
            }
        }

        //print_r($fieldObject);

        return $fieldObject;

    }

    function CombinedField($pName, $pPhrase, $pDefaultValue, $pModuleID, $pSeparator)
    {
        $this->name = $pName;
        $this->phrase = $pPhrase;
        $this->defaultValue = $pDefaultValue;
        $this->moduleID = $pModuleID;
        $this->separator = $pSeparator;

    }

    function needsReGet(){
        $needsReGet = false;
        foreach($this->content as $content){
            if (is_array($content)){
                $contentField = $content['field'];
                if($contentField->needsReGet()){
                    $needsReGet = true;
                }
            }
        }
        return $needsReGet;
    }

    function AddFieldRef($pField, $pPrepend, $pAppend)
    {

        $this->content[] = array(
            'field'   => $pField,
            'prepend' => $pPrepend,
            'append'  => $pAppend
        );

    }

    function AddTextData($pData)
    {

        $this->content[] = $pData;

    }

    function makeSelectDef($pModuleID, $pIncludeFieldAlias = true)
    {
        $debug_prefix = debug_indent("CombinedField-makeSelectDef() {$this->moduleID}.{$this->name}:");

        $translations = array('_' => ' ', ';' => ' |', '"' => '\"');
        
        if(strlen($this->separator) > 0){
            $use_nullif = false;
            $separator = strtr($this->separator, $translations);
            $concatFunction = "CONCAT_WS('$separator',";
        } else {
            $use_nullif = true;
            $concatFunction = 'CONCAT(';
        }
        
        $ar_selects = array();
        $staticContent = '';
        foreach($this->content as $content){
            if (is_array($content)){
                $contentField = $content['field'];
                if(!empty($content['prepend']) || !empty($content['append'])){
                    $preContent = strtr("'{$content['prepend']}'", $translations).',';
                    $appContent = ','.strtr("'{$content['append']}'", $translations);
                    $def = 'CONCAT('.
                        $preContent.
                        $contentField->makeSelectDef($pModuleID, false).
                        $appContent.
                        ')';
                } else {
                    $def = $contentField->makeSelectDef($pModuleID, false);
                }
                if($use_nullif){
                    $ar_selects[] = 'IFNULL('.$def.',\'\')';
                } else {
                    $ar_selects[] = $def;
                }
            } else {
                if(0 < strlen(trim($content))){
                    //translate underscore into space, semicolon to space + pipe, quote to escaped quote
                    $translatedContent = strtr("'$content'", $translations);
                    $ar_selects[] = $translatedContent;
                    $staticContent .= addslashes(str_replace('\'', '', $translatedContent));
                }
            }
        }
        
        print "$debug_prefix staticContent: '$staticContent'\n";

        $select = "NULLIF($concatFunction";
        $select .= join($ar_selects , ',');
        $select .= "),'$staticContent')";

        if($pIncludeFieldAlias){
            $select .= " AS {$this->name}";
        }
        
        print "$debug_prefix returns: '$select'\n";
        debug_unindent();
        return $select;
    }

    function makeJoinDef($pModuleID)
    {
        $debug_prefix = debug_indent("CombinedField-makeJoinDef() {$this->moduleID}.{$this->name}:");

print "$debug_prefix pModuleID = $pModuleID\n";

        $joins = array();

        $modFields = GetModuleFields($this->moduleID);
        foreach($this->content as $content){
            if (is_array($content)){
                $contentField = $content['field'];

print "$debug_prefix calls " . get_class($contentField) . ' ' .$contentField->name." (pModuleID = $pModuleID)\n\n";
                
                $joins = array_merge($contentField->makeJoinDef($pModuleID), $joins);

            }
        }
        
print "$debug_prefix Joins in CombinedField:\n";
indent_print_r($joins);

        //this is to make sure the chain of joins isn't broken      
        if(empty($joins[$pModuleID])){
print "$debug_prefix need to create a join def for $pModuleID\n";
            
            $moduleFields = GetModuleFields($this->moduleID);
            
            //this would not work if there's absolutely no tablefield in the module 
            foreach($moduleFields as $mf){
                if(get_class($mf) == 'tablefield'){
                    break; //exit the foreach loop
                }
            }
print "$debug_prefix found a table field to create a connecting join: {$mf->name}\n";
            $addJoin = $mf->makeJoinDef($pModuleID);
            
print "$debug_prefix connecting join: {$mf->name}\n";
indent_print_r($addJoin);

            $joins = array_merge($addJoin, $joins);
            indent_print_r($joins);
        }

        debug_unindent();
        return $joins;
    }
    
    
    
    function getFieldDependencies($pID)
    {
        //returns a nested array of all fields that a field depends on
        
        
        $localModuleFields   = GetModuleFields($this->moduleID);
        $deps = array();
        
        $counter =  0;
        
        foreach($this->content as $content){
            if (is_array($content)){
                $contentField = $content['field'];
                
                $counter++;
                
                if($counter < 10){
                    $counterPad = '0'.$counter;
                } else {
                    $counterPad = $counter;
                }
                
                $deps[] = array(
                    'ID' => $pID . '_'.$counterPad,
                    'parentID' => $pID,
                    'moduleID' => $contentField->moduleID, 
                    'name' => $contentField->name, 
                    'type' => get_class($contentField),
                    'deps' => $contentField->getFieldDependencies($pID . '_'.$counterPad)
                );
            }
        }
        
        return $deps;
    }
    
    function getDependentFields()
    {
        $deps = array();
        
        foreach($this->content as $content){
            if (is_array($content)){
                $contentField = $content['field'];
                
                $deps[] = array(
                    'moduleID' => $contentField->moduleID, 
                    'name' => $contentField->name
                );
            }
        }
        
        return $deps;
    }
}







class DynamicForeignField extends ModuleField
{
    var $key;
    var $moduleIDField;
    var $foreignField;
    var $condition;
    var $joinType;
    var $RelatedModuleRefs = array();
    var $cacheTable;
    
    function Factory($element, $moduleID)
    {
        $fieldObject = new DynamicForeignField (
            $element->attributes['name'],
            $element->attributes['type'],
            $element->attributes['displayFormat'],
            $element->attributes['key'],
            $element->attributes['moduleIDField'],
            $element->attributes['foreignField'],
            $element->attributes['condition'],
            $element->attributes['joinType'],
            $element->attributes['phrase'],
            $element->attributes['defaultValue'],
            $moduleID,
            $element->attributes['cacheTable']
            );

        foreach($element->c as $contentItem){
            if(is_object($contentItem)){
                switch(get_class($contentItem)){
                case 'element':
                    //add ref'd module ID to the DynamicForeignField
                    $fieldObject->addRelatedModuleRef(
                        $element->attributes['moduleID']
                    );
                    break;

                default:
                    print_r($contentItem);
                    die('DynamicForeignField::Factory: class type not handled');
                }
            } else {
                //ignore anything else
            }
        }

        return $fieldObject;
    }


    function DynamicForeignField(
        $pName,
        $pDataType,
        $pDisplayFormat,
        $pKey,
        $pModuleIDField,
        $pForeignField,
        $pCondition,
        $pJoinType,
        $pPhrase,
        $pDefaultValue,
        $pModuleID,
        $pCacheTable)
    {
        $this->name = $pName;
        $this->dataType = $pDataType;
        $this->displayFormat = $pDisplayFormat;
        $this->key = $pKey;
        $this->moduleIDField = $pModuleIDField;
        $this->foreignField = $pForeignField;
        $this->condition = $pCondition;
        $this->joinType = $pJoinType;
        $this->phrase = $pPhrase;
        $this->defaultValue = $pDefaultValue;
        $this->moduleID = $pModuleID;
        if(empty($pCacheTable)){
            $this->cacheTable = 'rdc';
        } else {
            $this->cacheTable = $pCacheTable;
        }
    }




    //adds module refs (to be used at compile time [lookup from $foreignModules], not run time)
    function addRelatedModuleRef($pModuleID)
    {

        $this->RelatedModuleRefs[$pModuleID] = $pModuleID;
    }

    
    function getQualifiedName($pModuleID)
    {
        $debug_prefix = debug_indent("DFF-getQualifiedName() {$this->moduleID}.{$this->name}:");
        print "$debug_prefix pModuleID = $pModuleID\n";
        
        $name = $this->makeSelectDef($pModuleID, false);
        
        print "$debug_prefix returned $name\n";
        debug_unindent();
        return $name;
    }

    //makeSelectDef
    function makeSelectDef($pModuleID, $pIncludeFieldAlias = true)
    {
        $tableAlias = GetTableAlias($this, $pModuleID);
        
        switch($this->foreignField){
        case 'RecordDescription':
            $fieldName = 'Value';
            break;
        case 'OwnerOrganizationID':
            $fieldName = 'OrganizationID';
            break;
        default:
            $fieldName = $this->foreignField;
        }
        if($pIncludeFieldAlias){
            return "`$tableAlias`.$fieldName AS {$this->name}";
        } else {
            return "`$tableAlias`.$fieldName";
        }
    }


    //makeJoinDef
    function makeJoinDef($pModuleID){
        $debug_prefix = debug_indent("DFF-makeJoinDef() {$this->moduleID}.{$this->name}:");
        print "$debug_prefix pModuleID = $pModuleID\n";
        
        $joins = array();
        $tableAlias = GetTableAlias($this, $pModuleID);
        
        //check that key and moduleID field are TableFields:
        $keyField = GetModuleField($this->moduleID, $this->key);
        print "$debug_prefix key {$this->key} is ".get_class($keyField)."\n";
        $moduleIDField = GetModuleField($this->moduleID, $this->moduleIDField);
        print "$debug_prefix moduleIDField {$this->key} is ".get_class($moduleIDField)."\n";

        //create joins to either field if not a TableField
        if('tablefield' != get_class($keyField)){
            print "$debug_prefix getting join for keyField {$this->key}\n";
            $joins = array_merge($keyField->makeJoinDef($pModuleID), $joins);
        }
        if('tablefield' != get_class($moduleIDField)){
            print "$debug_prefix getting join for moduleIDField {$this->moduleIDField}\n";
            $joins = array_merge($moduleIDField->makeJoinDef($pModuleID), $joins);

        }
        $keyQName = $keyField->getQualifiedName($pModuleID);
        $moduleIDFieldQName = $moduleIDField->getQualifiedName($pModuleID);


if('tablefield' != get_class($keyField)){
    $parentAlias = GetTableAlias($keyField, $pModuleID);
} else {
    $parentAlias = $pModuleID;
}
$this->assignParentJoinAlias($tableAlias, $parentAlias);

        $joins[$tableAlias] = "LEFT OUTER JOIN `{$this->cacheTable}` AS $tableAlias ON ({$keyQName} = $tableAlias.RecordID AND {$moduleIDFieldQName} = $tableAlias.ModuleID)";


        print "$debug_prefix Joins:\n";
        indent_print_r($joins);

        debug_unindent();
        return $joins;

    }
    
    function getForeignModuleID(){
        return $this->cacheTable;
    }
    
    function getTableAliasKey($parentAlias)
    {
        global $SQLBaseModuleID;
        $localModuleID = $this->moduleID;

        if(!empty($parentAlias)){
            //sanity check: makes sure $localModuleAlias.$localKey isn't actually the same field as $foreignModuleID.$foreignKey
            if(preg_replace('/[\d]*/', '', $parentAlias) == $foreignModuleID){
                $localModuleAlias = $localModuleID;
            } else {
                $localModuleAlias = $parentAlias;
            }
        } else {
            $localModuleAlias = $localModuleID;
        }


        $condition = "`{$this->cacheTable}`.ModuleID='{$this->moduleID}'";

        return
            $localModuleAlias.'.'.$this->key.'|'.
            '`'.$this->cacheTable.'`.RecordID|'.
            $condition;
    }
}






class RemoteField extends ModuleField
{
    var $localTable;
    var $localRecordIDField;
    var $remoteModuleID;
    var $remoteTableAlias;      //used by Search at run-time, set by parser
    var $remoteModuleIDField;
    var $remoteRecordIDField;   //field in remote that matches local record ID
    var $remoteField;
    var $remoteDescriptorField;
    var $remoteDescriptor;
    var $remoteFieldType;       //data type of the remote field
    var $remotePKField;         //actual recordID field of remote module
    var $validate;
    var $defaultValue;          //to be passed on to ScreenFields that need them
    var $listTypes;             //to be passed on to ScreenFields that need them
    var $listConditions = array(); //to be passed on to ScreenFields that need them
    var $conditionModuleID;     //overriding module id for the module id to be passed to the remote module
    var $reversed;              //special "hack" for makeJoinDef

    function Factory($element, $moduleID){
        $debug_prefix = debug_indent("Remotefield::Factory() $moduleID:");
    
        $localTable = $moduleID;
        if (!is_null($element->attributes['localTable'])){
            $localTable = $element->attributes['localTable'];
        }
        
        $validate = $element->attributes['validate'];
        //don't validate if it contains the string 'noValidate'
        if(empty($validate) || FALSE !== strpos('noValidate', $validate)){
            $validate = '';
        }

        //when extending a module, it's important to get the correct field name...
        if(!empty($element->attributes['conditionModuleID'])){
            $moduleInfo = GetModuleInfo($element->attributes['conditionModuleID']);
        } else {
            $moduleInfo = GetModuleInfo($moduleID);
        }
        
        $local_pkField = $moduleInfo->getPKField();
        
        $t_remoteModuleFields = GetModuleFields($element->attributes['remoteModuleID']);
        $t_remoteModuleField = $t_remoteModuleFields[$element->attributes['remoteField']];
        $remote_pkField = reset($t_remoteModuleFields);
        
        $fieldObject = new RemoteField(
            $element->attributes['name'],
            $element->attributes['type'],
            $element->attributes['displayFormat'],
            $localTable,
            $local_pkField,
                //$t_mfKeys[0],       //localRecordIDField is supposed to be first PK field; however, that may not have been parsed at the stage when this is executed ... room for a fix
            $element->attributes['remoteModuleID'],
            $element->attributes['remoteModuleIDField'],
            $element->attributes['remoteRecordIDField'],
            $element->attributes['remoteField'],
            $element->attributes['remoteDescriptorField'],
            $element->attributes['remoteDescriptor'],
                //$foreignModules[$attributes['remoteModuleID']]->ModuleFields[$attributes['remoteField']]->dataType,
            $t_remoteModuleField->dataType,
            $remote_pkField->name,
            $element->attributes['phrase'],
            $validate,
            $element->attributes['defaultValue'],
            $moduleID,
            $element->attributes['listType'],
            $element->attributes['conditionModuleID']
            );
        
        if(count($element->c) > 0){
            foreach($element->c as $sub_element){
                if('ListCondition' == $sub_element->type){
                    $conditionObj = $sub_element->createObject($moduleID);
                    $fieldObject->listConditions[$conditionObj->name] = $conditionObj;
                }
            }
        }

        /*print "$debug_prefix created field object\n";
        print_r($fieldObject);*/
        debug_unindent();
        return $fieldObject;
    }
    
    
    function RemoteField (
        $pName,
        $pDataType,
        $pDisplayFormat,
        $pLocalTable,
        $pLocalRecordIDField,
        $pRemoteModuleID,
        $pRemoteModuleIDField,
        $pRemoteRecordIDField,
        $pRemoteField,
        $pRemoteDescriptorField,
        $pRemoteDescriptor,
        $pRemoteFieldType,
        $pRemotePKField,
        $pPhrase,
        $pValidate,
        $pDefaultValue,
        $pModuleID,
        $pListType = null,
        $pConditionModuleID = null
        )
    {
        $this->name = $pName;
        $this->displayFormat = $pDisplayFormat;
        $this->localTable = $pLocalTable;
        $this->localRecordIDField = $pLocalRecordIDField;
        $this->remoteModuleID = $pRemoteModuleID;
        $this->remoteModuleIDField = $pRemoteModuleIDField;
        $this->remoteRecordIDField = $pRemoteRecordIDField;
        $this->remoteField = $pRemoteField;
        $this->remoteDescriptorField = $pRemoteDescriptorField;
        $this->remoteDescriptor = $pRemoteDescriptor;
        $this->remoteFieldType = $pRemoteFieldType;
        $this->remotePKField = $pRemotePKField;
        $this->phrase = $pPhrase;
        $this->validate = $pValidate;
        $this->defaultValue = $pDefaultValue;
        $this->moduleID = $pModuleID;
        $this->listType = $pListType;
        if(!empty($pConditionModuleID)){
            $this->conditionModuleID = $pConditionModuleID;
        } else {
            $this->conditionModuleID = $pLocalTable;
        }
        $this->dataType = $pDataType;
    }


    function getQualifiedName($pModuleID){
        $debug_prefix = debug_indent("RemoteField-getQualifiedName() {$this->moduleID}.{$this->name}:");
        print "$debug_prefix pModuleID = $pModuleID\n";
/*
        $foreignAlias = GetTableAlias($this, $pModuleID);
        print "$debug_prefix foreignAlias = $foreignAlias\n";
        
        //qualified name of the field in the remote table
        $name =  "`{$foreignAlias}`.{$this->remoteField}";
        
        print "$debug_prefix returned $name\n";
        debug_unindent();
        return $name;
*/
        debug_unindent();
        return $this->makeSelectDef($pModuleID, false);
    }



    function makeSelectDef($pModuleID, $pIncludeFieldAlias = true){
        $foreignAlias = GetTableAlias($this, $pModuleID);

        $remoteModuleField = GetModuleField($this->remoteModuleID, $this->remoteField);
        if('tablefield' != get_class($remoteModuleField)){
            $def = $remoteModuleField->makeSelectDef($foreignAlias, false);
        } else {
            switch($this->dataType){
            case 'money':
                $def = "ROUND(`{$foreignAlias}`.{$this->remoteField}, 2)";
                break;
            default:
                $def = "`{$foreignAlias}`.{$this->remoteField}";
            }
        }
        
        if ($pIncludeFieldAlias){
            return "$def AS {$this->name}";
        } else {
            return $def;
        }
    }



function makeJoinDef($pModuleID)
{
    $debug_prefix = debug_indent("RemoteField-makeJoinDef() {$this->moduleID}.{$this->name}:");
    print "$debug_prefix pModuleID = $pModuleID\n";
    indent_print_r($this);

    $foreignAlias = GetTableAlias($this, $pModuleID);
    global $SQLBaseModuleID;
    print "$debug_prefix SQLBaseModuleID is $SQLBaseModuleID\n";
    print "$debug_prefix this->moduleID is {$this->moduleID}\n";

    //when to use the passed $pModuleID
    if($this->localTable == $pModuleID || preg_match("/{$this->localTable}[\d]/", $pModuleID)){
        $localTable = $pModuleID;
        print "$debug_prefix localTable - using pModuleID since {$this->localTable} matches $pModuleID\n";
    } else {
        $localTable = $this->localTable . '1'; //if needed, figure out how to determine this properly
        print "$debug_prefix localTable - using guessed value $localTable since '{$this->localTable}' does not match '$pModuleID'\n";
    }

    $joins = array();

    $remoteModuleField = GetModuleField($this->remoteModuleID, $this->remoteField);
    $joins = array_merge($joins, $remoteModuleField->makeJoinDef($foreignAlias));

    $localRecordIDField = GetModuleField($this->moduleID, $this->localRecordIDField);
    $qualifiedLocalRecordIDField = $localRecordIDField->getQualifiedName($pModuleID);
    $joins = array_merge($joins, $localRecordIDField->makeJoinDef($pModuleID));

if($this->reversed){
    $t_join = "INNER JOIN `{$pModuleID}` AS {$localTable} ";
    $t_join .= "\n    ON (`{$foreignAlias}`.{$this->remoteRecordIDField} = $qualifiedLocalRecordIDField";
} else {
    $t_join = "LEFT OUTER JOIN `{$this->remoteModuleID}` AS {$foreignAlias} ";
    $t_join .= "\n    ON ($qualifiedLocalRecordIDField = `{$foreignAlias}`.{$this->remoteRecordIDField} ";
}
    $t_join .= "\n     AND `{$foreignAlias}`.{$this->remoteModuleIDField} = '{$this->conditionModuleID}'";
    $t_join .= "\n     AND `{$foreignAlias}`._Deleted = 0";

    if (!empty($this->remoteDescriptor)){
        $t_join .= "\n     AND `{$foreignAlias}`.{$this->remoteDescriptorField} = '{$this->remoteDescriptor}')";
    } else {
        $t_join .= ")";
    }

    $joins = array_merge(array($foreignAlias => $t_join), $joins);

    print "$debug_prefix {$this->name} <begin joins> created the following joins: \n";
    indent_print_r( $joins );
    print "$debug_prefix {$this->name} <end joins>\n";



if('tablefield' != get_class($localRecordIDField)){
    $parentAlias = GetTableAlias($localRecordIDField, $pModuleID);
} else {
    $parentAlias = $pModuleID;
}

    //$this->assignParentJoinAlias($foreignAlias, $localTable);
    $this->assignParentJoinAlias($foreignAlias, $pModuleID);

    debug_unindent();
    return $joins;
}



    function getFieldDependencies($pID){
        //return a nested array of all fields that a field depends on
        
        $localModuleFields  = GetModuleFields($this->moduleID);
        $remoteModuleFields = GetModuleFields($this->remoteModuleID);
        
        //$localRecordIDField  = $localModuleFields[$this->localRecordIDField]; - is never used??
        $remoteModuleIDField = $remoteModuleFields[$this->remoteModuleIDField];
        $remoteRecordIDField = $remoteModuleFields[$this->remoteRecordIDField];
        $remoteField         = $remoteModuleFields[$this->remoteField];
        
        $deps = array(
            /*array(
                $this->moduleID, 
                $this->localRecordIDField, 
                $localRecordIDField->getFieldDependencies()
            ), */
            array(  
                'ID' => $pID . '_01',
                'parentID' => $pID,
                'moduleID' => $this->remoteModuleID, 
                'name' => $this->remoteModuleIDField,
                'type' => get_class($remoteModuleIDField),
                'deps' => $remoteModuleIDField->getFieldDependencies($pID . '_01')
            ),
            array(
                'ID' => $pID . '_02',
                'parentID' => $pID,
                'moduleID' => $this->remoteModuleID, 
                'name' => $this->remoteRecordIDField,
                'type' => get_class($remoteRecordIDField),
                'deps' => $remoteRecordIDField->getFieldDependencies($pID . '_02')
            ),
            array(
                'ID' => $pID . '_03',
                'parentID' => $pID,
                'moduleID' => $this->remoteModuleID, 
                'name' => $this->remoteField,
                'type' => get_class($remoteField),
                'deps' => $remoteField->getFieldDependencies($pID . '_03')
            )
        );
        
        return $deps;
    }
        
    function getDependentFields(){
        $deps = array(
            array(  
                'moduleID' => $this->remoteModuleID, 
                'name' => $this->remoteModuleIDField
            ),
            array(
                'moduleID' => $this->remoteModuleID, 
                'name' => $this->remoteRecordIDField
            ),
            array(
                'moduleID' => $this->remoteModuleID, 
                'name' => $this->remoteField
            )
        );
        
        return $deps;
    }
    
    function makeRDCCallerDef(){
        print "RemoteField-makeRDCCallerDef() {$this->moduleID}.{$this->name}: \n";
        
        $t_module = GetModule($this->moduleID);
        $pkField = end($t_module->PKFields); //picks "most local" PK field
        
        if($pkField == $this->localRecordIDField){
            return null;
        }
        
        //creates an element object of the "reverse" foreignfield of this one.
        /*$attributes = array(
            'name' => $this->remoteField, //$pkField,
            'localTable' => $this->remoteModuleID,
            'key' => $this->remoteRecordIDField,
            'foreignTable' => $this->moduleID,
            'foreignField' => $pkField,
            'foreignKey' => $this->localRecordIDField,
            'joinType' => 'inner',
            //'listCondition' => "{$this->remoteModuleIDField} = '{$this->moduleID}'",
            'triggerCondition' => "{$this->remoteModuleIDField} = '{$this->moduleID}'",
            'madeby' => "{$this->moduleID}.{$this->name} (remotefield)"
        );
        */

        //"un-flipped" version
        $attributes = array(
            'name' => $this->name,//$pkField,
            'localTable' => $this->moduleID,
            'key' => $this->localRecordIDField,
            'foreignTable' => $this->remoteModuleID,
            'foreignField' => $this->remoteField,
            'foreignKey' => $this->remoteRecordIDField,
            'joinType' => 'inner',
            'triggerCondition' => "{$this->remoteModuleIDField} = '{$this->moduleID}'", //?
            'madeby' => "{$this->moduleID}.{$this->name} (remotefield)"
        );
        
        return new Element($this->remoteField, 'ForeignField', $attributes);
    }

        
    function getLocalModuleID(){
        return $this->localTable;
    }
    function getLocalKey(){
        return $this->localRecordIDField;
    }
    function getForeignModuleID(){
        return $this->remoteModuleID;
    }
    function getForeignKey(){
        return $this->remoteRecordIDField;
    }
    function getTarget(){
        return $this->remoteField;
    }
    function getCondition($foreignAlias){
        $condition = "`{$foreignAlias}`.{$this->remoteModuleIDField} = '{$this->localTable}'";
        if (!empty($this->remoteDescriptor)){
            $condition .= "\n     AND `{$foreignAlias}`.{$this->remoteDescriptorField} = '{$this->remoteDescriptor}'";
        }
        return $condition;
    }
    
    
    function getTableAliasKey($parentAlias)
    {
        global $SQLBaseModuleID;
        $localModuleID = $this->moduleID;

        if(!empty($parentAlias)){
            //sanity check: makes sure $localModuleAlias.$localKey isn't actually the same field as $foreignModuleID.$foreignKey
            if(preg_replace('/[\d]*/', '', $parentAlias) == $foreignModuleID){
                $localModuleAlias = $localModuleID;
            } else {
                $localModuleAlias = $parentAlias;
            }
        } else {
            $localModuleAlias = $localModuleID;
        }


        $condition = $this->getCondition($this->remoteModuleID);

        //minor beautfication: avoiding newlines
        $condition = str_replace("\n", '', $condition);

        return
            $localModuleAlias.'.'.$this->localRecordIDField.'|'.
            $this->remoteModuleID.'.'.$this->remoteRecordIDField.'|'.
            $condition;
    }

/**
 * returns the data type
 *
 * Preferrably not called at run-time.
 */
function getDataType()
{
    if(empty($this->dataType)){
        $remoteField = GetModuleField($this->remoteModuleID, $this->remoteField);
        return $remoteField->getDataType();
    } else {
        return $this->dataType;
    }
}
}




class LinkField extends ModuleField
{
var $moduleIDField;
var $recordIDField;
var $foreignModuleID;


function &Factory($element, $moduleID)
{
    return new LinkField($element, $moduleID);
}


function LinkField(&$element, $moduleID)
{
    $this->name = $element->attributes['name'];
    if(!isset($element->attributes['type'])){
        $this->dataType = 'varchar(128)';
    } else {
        $this->dataType = $element->attributes['type'];
    }
    $this->phrase = $element->attributes['phrase'];
    $this->moduleID = $moduleID;

    $this->moduleIDField = $element->attributes['moduleIDField'];
    $this->recordIDField = $element->attributes['recordIDField'];
    $this->foreignModuleID = $element->attributes['foreignModuleID'];
    
    if(empty($this->moduleIDField) && empty($this->foreignModuleID)){
        die("LinkField {$this->name} requires either a moduleIDField or a foreignModuleID");
    }
} //end constructor


function needsReGet()
{
    return true;
}

//this is probably wrong
function getQualifiedName($pModuleID)
{
    $debug_prefix = debug_indent("LinkField-getQualifiedName() {$this->moduleID}.{$this->name}:");
    print "$debug_prefix pModuleID = $pModuleID\n";

    $name =  "`$pModuleID`.{$this->name}";
    
    print "$debug_prefix returned $name\n";
    debug_unindent();
    return $name;
}


function makeSelectDef($pModuleID, $pIncludeFieldAlias = true)
{
    echo "LinkField-makeSelectDef {$this->name}\n";

    if(empty($this->moduleIDField)){
        $hasModuleIDField = false;
    } else {
        $hasModuleIDField = true;
    }
    if(empty($this->recordIDField)){
        $hasRecordIDField = false;
    } else {
        $hasRecordIDField = true;
    }

    if($hasModuleIDField){
        $moduleIDField = GetModuleField($this->moduleID, $this->moduleIDField);
    }
    if($hasRecordIDField){
        $recordIDField = GetModuleField($this->moduleID, $this->recordIDField);
    }

    $def = 'CONCAT(\'internal:';
    if($hasRecordIDField){
        $def .= 'view.php?mdl=';
    } else {
        $def .= 'list.php?mdl=';
    }
    $def .= '\',';

    if($hasModuleIDField){
        $def .= $moduleIDField->makeSelectDef($pModuleID, false);
    } else {
        $def .= "'{$this->foreignModuleID}'";
    }

    if($hasRecordIDField){
        $def .= ',\'&rid=\','.$recordIDField->makeSelectDef($pModuleID, false);
    }
    $def .= ')';

    if($pIncludeFieldAlias){
        $def .= ' AS '.$this->name;
    }

    return $def;
}


function makeJoinDef($pModuleID)
{
    print "LinkField-makeJoinDef {$this->name}\n";
    $joins = array();

    if(!empty($this->moduleIDField)){
        $moduleIDField = GetModuleField($this->moduleID, $this->moduleIDField);
        $joins = array_merge($moduleIDField->makeJoinDef($pModuleID), $joins);
    }
    if(!empty($this->recordIDField)){
        $recordIDField = GetModuleField($this->moduleID, $this->recordIDField);
        $joins = array_merge($recordIDField->makeJoinDef($pModuleID), $joins);
    }

    return $joins;
}


function getDependentFields(){
    $deps = array();
    $deps[] = array(
        'moduleID' => $this->moduleID,
        'name' => $this->moduleIDField
    );

    if(!empty($this->recordIDField)){
        $deps[] = array(
            'moduleID' => $this->moduleID,
            'name' => $this->recordIDField
        );
    }

    return $deps;
}

} //end class LinkField




class CalculatedField extends ModuleField
{
    var $calcFunction;
    var $params = array();
    var $paramTypes = array();

    function Factory($element, $moduleID){

        return new CalculatedField(
            $element->attributes['name'],
            $element->attributes['type'],
            $element->attributes['displayFormat'],
            $element->attributes['calcFunction'],
            $element->attributes['params'],
            $element->attributes['phrase'],
            $moduleID
            );

    }
    
    
    function CalculatedField(
        $pName,
        $pDataType,
        $pDisplayFormat,
        $pCalcFunction,
        $pParams,
        $pPhrase,
        $pModuleID)
    {
        $this->name = $pName;
        $this->dataType = $pDataType;
        $pDisplayFormat;
        $this->calcFunction = $pCalcFunction;
        
        $this->phrase = $pPhrase;
        $this->moduleID = $pModuleID;
        
        $params = explode(' ', $pParams);
        
        foreach($params as $param){
            switch(substr($param, 0, 1)){
            case '#': //literal number
                $this->params[] = str_replace('#', '', $param); //exclude the #
                $this->paramTypes[] = 'l'; //literal
                break;
            case '+': //literal string
                $this->params[] = str_replace(array('+', '_'), array('', ' '), $param); //exclude the +
                $this->paramTypes[] = 's'; //literal string
                break;
            case '*': //variable
                $this->params[] = str_replace('*', '', $param); //exclude the *
                $this->paramTypes[] = 'v'; //variable
                break;
            default:
                $this->params[] = $param;
                $this->paramTypes[] = 'f'; //field
            }
        }
    }
    
    function needsReGet(){
        return true;
    }

    function getQualifiedName($pModuleID){
        /*$debug_prefix = debug_indent("CalculatedField-getQualifiedName() {$this->moduleID}.{$this->name}:");
        print "$debug_prefix pModuleID = $pModuleID\n";

        $name =  "`$pModuleID`.{$this->name}";
        
        print "$debug_prefix returned $name\n";
        debug_unindent();*/
        return $this->makeSelectDef($pModuleID, false);
    }
    
    function checkParameters($paramsExpected){
        //rudimentary parameter check
        if( count($this->params) != $paramsExpected ){
            die("CalculatedField {$this->name}: expects $paramsExpected parameters, but found ".count($this->params) .".\n");
        }
    }

    function makeSelectDef($pModuleID, $pIncludeFieldAlias = true){
    
        $params_translated = array();
        
        $localModuleFields   = GetModuleFields($this->moduleID);
        
        foreach($this->params as $ix => $param){
            print "makeSelectDef: processing parameter $param\n";
            switch($this->paramTypes[$ix]){
            case 'f':
                //insert select defs of fields referenced
                $params_translated[] = $localModuleFields[$param]->makeSelectDef($pModuleID, false);
                break;
            case 'v':
                $params_translated[] = "*{$param}*";
                break;
            case 'l':
                $params_translated[] = $param;
                break;
            case 's':
                $params_translated[] = '\''.addslashes($param) . '\'';
                break;
            default:
                //
            }
        }
        
        
        switch($this->calcFunction){
        case 'if':
            $this->checkParameters(3);
            $content = "IF({$params_translated[0]}, {$params_translated[1]}, {$params_translated[2]})";
            break;
        case 'ifnull':
            $this->checkParameters(2);
            $content = "IFNULL({$params_translated[0]}, {$params_translated[1]})";
            break;
        case 'datediff':
            $this->checkParameters(2);
            $content = "DATEDIFF({$params_translated[0]}, {$params_translated[1]})";
            break;
        case 'datediff_inclusive':  //used for date intervals where the last day should be counted whole
            $this->checkParameters(2);
            $content = "DATEDIFF({$params_translated[0]}, {$params_translated[1]}) + 1";
            break;
        case 'datediff_year_month':  //returns number of years and months
            $str_years = gettext("years");
            $str_months = gettext("months");
            $this->checkParameters(2);
            $content = "CONCAT(FLOOR(DATEDIFF({$params_translated[0]}, {$params_translated[1]})/365.24), ' $str_years, ', ROUND(MOD(DATEDIFF({$params_translated[0]}, {$params_translated[1]}), 365.24)/30.44), ' $str_months')";
            break;
        case 'datediff_day_hour':  //returns number of years and months
            $str_days = gettext("days");
            $str_hours = gettext("hours");
            $this->checkParameters(2);
            //days: floor((unix_timestamp(now())-unix_timestamp('2006-03-31'))/(86400))
            //hours: round(mod((unix_timestamp(now())-unix_timestamp('2006-03-31')),86400)/3600)
            
            $content = "CONCAT(FLOOR((UNIX_TIMESTAMP({$params_translated[0]})-UNIX_TIMESTAMP({$params_translated[1]}))/86400), ' $str_days, ', ROUND(MOD((UNIX_TIMESTAMP({$params_translated[0]})-UNIX_TIMESTAMP({$params_translated[1]})),86400)/3600), ' $str_hours')";
            break;
        case 'daysremaining':
            $this->checkParameters(1);
            $content = "DATEDIFF({$params_translated[0]}, NOW())";
            break;
        case 'daysremaining_not_negative':
            $this->checkParameters(1);
            $content = "CASE WHEN DATEDIFF({$params_translated[0]}, NOW()) > 0 THEN DATEDIFF({$params_translated[0]}, NOW()) ELSE 0 END";
            break;
        case 'dateadd':
            $this->checkParameters(3);

            //hard coded IDs to uts - YUCK!
            $content = "CASE {$params_translated[1]} 
                WHEN 48 THEN
                    DATE_ADD({$params_translated[0]}, INTERVAL {$params_translated[2]} SECOND)
                WHEN 5 THEN
                    DATE_ADD({$params_translated[0]}, INTERVAL {$params_translated[2]} MINUTE)
                WHEN 19 THEN
                    DATE_ADD({$params_translated[0]}, INTERVAL {$params_translated[2]} HOUR)
                WHEN 53 THEN
                    DATE_ADD({$params_translated[0]}, INTERVAL {$params_translated[2]} DAY)
                WHEN 54 THEN 
                    DATE_ADD({$params_translated[0]}, INTERVAL (7 * {$params_translated[2]}) DAY)
                WHEN 55 THEN
                    DATE_ADD({$params_translated[0]}, INTERVAL {$params_translated[2]} MONTH)
                WHEN 56 THEN
                    DATE_ADD({$params_translated[0]}, INTERVAL (3 * {$params_translated[2]}) MONTH)
                WHEN 57 THEN
                    DATE_ADD({$params_translated[0]}, INTERVAL {$params_translated[2]} YEAR)
                ELSE
                    NULL
                END";
            /*$content = "CASE {$params_translated[1]} 
                WHEN 1 THEN
                    DATE_ADD({$params_translated[0]}, INTERVAL {$params_translated[2]} SECOND)
                WHEN 2 THEN
                    DATE_ADD({$params_translated[0]}, INTERVAL {$params_translated[2]} MINUTE)
                WHEN 3 THEN
                    DATE_ADD({$params_translated[0]}, INTERVAL {$params_translated[2]} HOUR)
                WHEN 4 THEN
                    DATE_ADD({$params_translated[0]}, INTERVAL {$params_translated[2]} DAY)
                WHEN 5 THEN 
                    DATE_ADD({$params_translated[0]}, INTERVAL (7 * {$params_translated[2]}) DAY)
                WHEN 6 THEN
                    DATE_ADD({$params_translated[0]}, INTERVAL {$params_translated[2]} MONTH)
                WHEN 7 THEN
                    DATE_ADD({$params_translated[0]}, INTERVAL (3 * {$params_translated[2]}) MONTH)
                WHEN 8 THEN
                    DATE_ADD({$params_translated[0]}, INTERVAL {$params_translated[2]} YEAR)
                ELSE
                    NULL
                END";*/
            break;
        case 'timediff':
            $this->checkParameters(2);
            $content = "TIMEDIFF({$params_translated[0]}, {$params_translated[1]})";
            break;
        case 'timeremaining':
            $this->checkParameters(1);
            //$content = "CASE WHEN DATEDIFF({$params_translated[0]}, NOW()) > 0 THEN DATEDIFF({$params_translated[0]}, NOW()) ELSE 0 END";
            $content = "TIMEDIFF({$params_translated[0]}, NOW())";
            break;
        case 'filesize':
            $this->checkParameters(1);
            $str_bytes = gettext("bytes");
            $str_kilobytes = gettext("kilobytes");
            $str_megabytes = gettext("megabytes");
            $content = "CASE 
            WHEN {$params_translated[0]} >= 1048576 THEN CONCAT(ROUND(({$params_translated[0]}/1048576), 1), ' $str_megabytes')
            WHEN {$params_translated[0]} >= 1024 THEN CONCAT(ROUND(({$params_translated[0]}/1024), 1), ' $str_kilobytes')
            ELSE CONCAT({$params_translated[0]}, ' $str_bytes') END";
            break;
        case 'add':
            $this->checkParameters(2);
            $content = "IFNULL({$params_translated[0]}, 0) + IFNULL({$params_translated[1]}, 0)";
            break;
        case 'sum':
            $params_treated = array();
            foreach($params_translated as $paramID => $param){
                $params_treated[$paramID] = "IFNULL({$param}, 0)";
            }
            $content = join(' + ', $params_treated);
            break;
        case 'subtract':
            $this->checkParameters(2);
            $content = "IFNULL({$params_translated[0]},0) - IFNULL({$params_translated[1]},0)";
            break;
        case 'multiply':
            $this->checkParameters(2);
            $content = "IFNULL({$params_translated[0]},0) * IFNULL({$params_translated[1]},0)";
            break;
        case 'divide':
            $this->checkParameters(2);
            $content = "IFNULL({$params_translated[0]},0) / {$params_translated[1]}"; //no need to add IFNULL on divisor: division by null *should* result in null
            break;
        case 'duedateformat':
            $this->checkParameters(1);
            $content = "CASE WHEN (IFNULL({$params_translated[0]},0) < 0) THEN 'od' ELSE '' END";
            break;
        case 'is_recent':
            $this->checkParameters(2);
            $content = "CASE WHEN {$params_translated[0]} > '{$params_translated[1]}' THEN 1 ELSE 0 END";
            break;
        case 'max':
            $this->checkParameters(2);
            $content = "IF({$params_translated[0]} > {$params_translated[1]}, {$params_translated[0]}, {$params_translated[1]})";
            break;
        default:
            die("CalculatedField {$this->name}: calcFunction '{$this->calcFunction}' not supported\n");
        }
        
        if($pIncludeFieldAlias){
            return $content . ' AS ' .$this->name; 
        } else {
            switch($this->dataType){
                case 'money':
                    return "ROUND($content, 2)";
                    break;
                default:
                    return $content;
            }   
        }
    }

    function makeJoinDef($pModuleID){
        print "CalculatedField-makeJoinDef {$this->name}\n";
        $joins = array();

        $localModuleFields   = GetModuleFields($this->moduleID);

        foreach($this->params as $ix => $param){
            switch($this->paramTypes[$ix]){
            case 'f':
                //insert select defs of fields referenced
                $joins = array_merge($localModuleFields[$param]->makeJoinDef($pModuleID), $joins); //pass on calling field status
                break;
            case 'l':
                //do nothing
                break;
            default:
                //do nothing
            }
        }
        
        return $joins;
    }
    
    function getFieldDependencies($pID){
        //return a nested array of all fields that a field depends on - a TableField has no dependencies
        return array();
    }
    
    function getDependentFields(){
        $deps = array();
        
        foreach($this->params as $ix => $param){
            if('f' == $this->paramTypes[$ix]){
                $deps[] = array(  
                    'moduleID' => $this->moduleID, 
                    'name' => $param
                );
            }
        }
        
        return $deps;
    }
}


class SummaryField extends ModuleField
{
    var $summaryFunction;
    var $summaryField;
    var $summaryKey;
    var $summaryModuleID;
    var $summaryCondition;
    var $localKey;
    var $conditions; //copied from submodule
    var $isGlobal = false;

    function Factory($element, $moduleID){

        return new SummaryField(
            $element->attributes['name'],
            $element->attributes['type'],
            $element->attributes['displayFormat'],
            $element->attributes['summaryFunction'],
            $element->attributes['summaryField'],
            $element->attributes['summaryKey'],
            $element->attributes['summaryModuleID'],
            $element->attributes['summaryCondition'],
            $element->attributes['localKey'],
            $element->attributes['phrase'],
            $moduleID,
            $element->attributes['isGlobal']
            );

    }

    function SummaryField(
        $pName,
        $pDataType,
        $pDisplayFormat,
        $pSummaryFunction,
        $pSummaryField,
        $pSummaryKey,
        $pSummaryModuleID,
        $pSummaryCondition,
        $pLocalKey,
        $pPhrase,
        $pModuleID,
        $pIsGlobal)
    {
        $this->name             = $pName;
        $this->dataType         = $pDataType;
        $this->displayFormat    = $pDisplayFormat;
        $this->phrase           = $pPhrase;
        $this->moduleID         = $pModuleID;

        $this->summaryFunction  = $pSummaryFunction;
        $this->summaryField     = $pSummaryField;
        $this->summaryKey       = $pSummaryKey;
        $this->summaryModuleID  = $pSummaryModuleID;
        $this->summaryCondition = $pSummaryCondition;

        $this->localKey         = $pLocalKey;
        if('yes' == strtolower($pIsGlobal)){
            $this->isGlobal         = true;
        }

        //$this->conditions are loaded AFTER submodule loading, because of infinite loop issue
    }

    function needsReGet(){
        return true;
    }

    function getQualifiedName($pModuleID){
        $debug_prefix = debug_indent("SummaryField-getQualifiedName() {$this->moduleID}.{$this->name}:");
        print "$debug_prefix pModuleID = $pModuleID\n";
        $tableAlias = GetTableAlias($this, $pModuleID);
        
        $name =  "`$tableAlias`.{$this->name}";

        print "$debug_prefix returned $name\n";
        debug_unindent();
        return $name;
    }
    

    function makeSelectDef($pModuleID, $pIncludeFieldAlias = true){
        
        
        if($pIncludeFieldAlias){
            return $this->getQualifiedName($pModuleID) . ' AS ' .$this->name; 
        } else {
            switch($this->dataType){
                case 'money':
                    return "ROUND(".$this->getQualifiedName($pModuleID).", 2)";
                    break;
                default:
                    return $this->getQualifiedName($pModuleID);
            }
        }
    }

    function makeJoinDef($pModuleID){
        $debug_prefix = debug_indent("SummaryField-makeJoinDef {$this->moduleID}.{$this->name}:");
        print "$debug_prefix localKey = {$this->localKey}\n";

        global $SQLBaseModuleID;
        $joins = array();

        $tableAlias = GetTableAlias($this, $pModuleID);

        $localKeyMF = GetModuleField($this->moduleID, $this->localKey);
        $localKeyQualified = $localKeyMF->getQualifiedName($pModuleID);
        if('tablefield' != get_class($localKeyMF)){
            $parentAlias = GetTableAlias($localKeyMF, $pModuleID);
            
            $joins = array_merge($joins, $localKeyMF->makeJoinDef($pModuleID));
        } else {
            $parentAlias = $pModuleID;
        }
        $this->assignParentJoinAlias($tableAlias, $parentAlias);
        
        print "$debug_prefix localKeyQualified = {$localKeyQualified}\n";
        print "$debug_prefix parentAlias = {$parentAlias}\n";
        
        
        $backup_SQLBaseModuleID = $SQLBaseModuleID;

        
        $standardSummary = true;
        
        //list of fields to be combined
        static $selectFields = array();
        $selectFields[$tableAlias][$this->name] = $this->summaryField;
        
        print "$debug_prefix selectFields array:\n";
        print_r($selectFields);
        
        switch($this->summaryFunction){
        case 'average':
            $function = "AVG(%s)";
            break;
        case 'count':
            $function = "COUNT(%s)";
            break;
        case 'sum':
            $function = "SUM(%s)";
            break;
        case 'list':
            $function = 'GROUP_CONCAT(%1$s ORDER BY %1$s SEPARATOR \', \')';
            break;
        case 'lco_latest_id':
            $standardSummary = false;

            $join = "LEFT OUTER JOIN (SELECT
   `lco_sub1`.LossCostID AS {$this->name},
   `lco_sub1`.ClaimID
FROM 
   `lco` AS `lco_sub1`
WHERE
    `lco_sub1`._Deleted = 0
   AND `lco_sub1`.LossCostID = (
      select `lco_sub2`.LossCostID
      from `lco` AS `lco_sub2`
      where `lco_sub2`._Deleted = 0
        and `lco_sub2`.ClaimID = `lco_sub1`.ClaimID
      order by `lco_sub2`.ValuationDate DESC
      limit 1
   )) AS `$tableAlias` ON 
            $localKeyQualified = $tableAlias.{$this->summaryKey}\n";
            break;
        case 'cos_rollup_sum': //summarize cos
            $standardSummary = false;

 $join = "LEFT OUTER JOIN (SELECT
   SUM(`cos_r`.{$this->summaryField}) AS {$this->name},
   `smc`.ModuleID,
   `smc`.RecordID
FROM 
   `smc`
    INNER JOIN `cos` AS cos_r
    ON `smc`.SubModuleID = `cos_r`.RelatedModuleID
    AND `smc`.SubRecordID = `cos_r`.RelatedRecordID
WHERE
    `cos_r`._Deleted = 0
GROUP BY `smc`.ModuleID, `smc`.RecordID
   ) AS `$tableAlias` ON 
    $tableAlias.{$this->summaryKey} = $localKeyQualified
    AND $tableAlias.{$this->summaryCondition}
\n";

            break;
        default:
            die("SummaryField {$this->name}: summaryFunction '{$this->summaryFunction}' not supported\n");
        }

        if($standardSummary){
            //temporary change this to make the subquery
            $SQLBaseModuleID = $this->summaryModuleID;
            
            $summaryModuleFields = GetModuleFields($this->summaryModuleID);
            $summaryKeyModuleField = $summaryModuleFields[$this->summaryKey];
            
            $summarySelectDefs = array();
            $summaryJoinDefs = array();
            foreach($selectFields[$tableAlias] as $selectKey=>$selectField){
                $summaryModuleField = $summaryModuleFields[$selectField];
                if(empty($summaryModuleField)){
                    die("SummaryField-makeJoinDef {$this->name}: Could not find a module field that matches the summaryField attribute ({$selectField})");
                }
                $summarySelectDefs[$selectKey] = $summaryModuleField->makeSelectDef($this->summaryModuleID, false);
                
                $summaryJoinDefs = array_merge(
                    $summaryJoinDefs,
                    $summaryModuleField->makeJoinDef($this->summaryModuleID)
                );
            }
            
            $summaryJoinDefs = array_merge(
                $summaryJoinDefs,
                $summaryKeyModuleField->makeJoinDef($this->summaryModuleID)
            );
            $summaryJoinDefs = SortJoins($summaryJoinDefs);


            $summaryKeyQualified = $summaryKeyModuleField->getQualifiedName($this->summaryModuleID);
            
            $subSQL = "SELECT \n";
            foreach($summarySelectDefs as $selectField => $summarySelectDef){
                $subSQL .= sprintf($function, $summarySelectDef) . " AS {$selectField},\n";
            }
            $subSQL .= "$summaryKeyQualified FROM `{$this->summaryModuleID}` \n";
            foreach($summaryJoinDefs as $def){
                $subSQL .= $def."\n";
            }
            $subSQL .= " WHERE `{$this->summaryModuleID}`._Deleted = 0 \n";
            if(strlen($this->summaryCondition) > 0){
                $subSQL .= " AND {$this->summaryCondition} \n";
            }
            if(count($this->conditions) > 0){
                foreach($this->conditions as $conditionField => $conditionValue){
                    $conditionModuleField = $summaryModuleFields[$conditionField];

                    if('tablefield' == get_class($conditionModuleField)){
                        $conditionFieldQualified = $conditionModuleField->getQualifiedName($this->summaryModuleID);
                        $subSQL .= " AND '$conditionValue' = $conditionFieldQualified\n";
                    }
                }
            }
            $subSQL .= "GROUP BY $summaryKeyQualified \n";
            
            //verify the statement is OK so far:
            if(defined('EXEC_STATE') && EXEC_STATE == 4){
                CheckSQL($subSQL);
            }
            
            $join = "LEFT OUTER JOIN ($subSQL) AS $tableAlias \n";
            $join .= "ON ($localKeyQualified = $tableAlias.{$this->summaryKey}) ";
            
        }
        
        $joins[$tableAlias] = $join;
        
        //restore base moduleID
        $SQLBaseModuleID = $backup_SQLBaseModuleID;

        debug_unindent();
        return $joins;
    }
    
    function getFieldDependencies($pID){
        return array();
    }
    
    //ought to return fields of submodule?
    function getDependentFields(){
        return array();
    }
    
    function getForeignModuleID(){
        return $this->summaryModuleID;
    }

    function getTableAliasKey($parentAlias = null)
    {
        global $SQLBaseModuleID;
        $localModuleID = $this->moduleID;

        if(!empty($parentAlias)){
            //sanity check: makes sure $localModuleAlias.$localKey isn't actually the same field as $foreignModuleID.$foreignKey
            if(preg_replace('/[\d]*/', '', $parentAlias) == $foreignModuleID){
                $localModuleAlias = $localModuleID;
            } else {
                $localModuleAlias = $parentAlias;
            }
        } else {
            $localModuleAlias = $localModuleID;
        }

        $condition = $this->summaryFunction.'+'.$this->summaryCondition;

        return
            $localModuleAlias.'.'.$this->localKey.'|'.
            $this->summaryModuleID.'.'.$this->summaryKey.'|'.
            $condition;
    }
}



class RecordMetaField extends ModuleField
{
    var $lookupType; //either 'created' or 'modified'
    var $returnType; //either 'date' or 'userID'
    
    function Factory($element, $moduleID){

        return new RecordMetaField(
            $element->attributes['name'],
            $element->attributes['lookupType'],
            $element->attributes['returnType'],
            $element->attributes['phrase'],
            $moduleID
            );
    }
    
    
    function RecordMetaField($pName, $pLookupType, $pReturnType, $pPhrase, $pModuleID)
    {
        $this->name = $pName;
        
        if(empty($pLookupType)){
            $pLookupType = 'created';
        }
        if(in_array($pLookupType, array('created', 'modified'))){
            $this->lookupType = $pLookupType;
        } else {
            die("RecodMetaField lookupType unknown: $pLookupType");
        }
        
        if(empty($pReturnType)){
            $pReturnType = 'date';
        }
        if(in_array($pReturnType, array('date', 'userID'))){
            $this->returnType = $pReturnType;
        } else {
            die("RecodMetaField returnType unknown: $pReturnType");
        }
        
        $this->phrase = $pPhrase;
        $this->moduleID = $pModuleID;
        
        if('date' == $pReturnType){
            $this->dataType = 'datetime';
        } else {
            $this->dataType = 'int';
        }
    }
    
    function needsReGet()
    {
        return true;
    }
    
    function getQualifiedName($pModuleID){
        $debug_prefix = debug_indent("RecordMetaField-getQualifiedName() {$this->moduleID}.{$this->name}:");
        print "$debug_prefix pModuleID = $pModuleID\n";

        $name =  $this->makeSelectDef($pModuleID, false);
        
        print "$debug_prefix returned $name\n";
        debug_unindent();
        return $name;
    }

    function makeSelectDef($pModuleID, $pIncludeFieldAlias = true, $pDFF = false){

        if($pIncludeFieldAlias){
            $fieldAlias = ' AS '. $this->name;
        } else {
            $fieldAlias = '';
        }
        switch($this->returnType){
        case 'userID':
            $type = 'By';
            break;
        case 'date':
            $type = 'Date';
            break;
        default:
            $type = 'By';
            break;
        }
        
        switch($this->lookupType){
        case 'created':
            $tableAlias = $this->moduleID . '_l';  //GetTableAlias($this, $pModuleID);  --- if we need it...
            return "`$tableAlias`.create{$type}{$fieldAlias}";
            break;
        case 'modified':
            return "`$pModuleID`._Mod{$type}{$fieldAlias}";
            break;
        default:
            die('RecordMetaField lookupType is not recognized.');
        }

    }

    function makeJoinDef($pModuleID){
        print "RecordMetaField-makeJoinDef {$this->name}";
        $joins = array();
        
        switch($this->lookupType){
        case 'created':
            $tableAlias = $this->moduleID . '_l'; //GetTableAlias($this, $pModuleID);  --- if we need it...
            
            $moduleFields = GetModuleFields($this->moduleID);
            $recordIDField = reset(array_keys($moduleFields));
            
            $this->assignParentJoinAlias($tableAlias, $pModuleID);
            //get record ID field for module.........
            /*$SQL = "LEFT OUTER JOIN (SELECT 
                    `{$pModuleID}_l`.{$recordIDField}, 
                    MIN(`{$pModuleID}_l`._ModDate) AS {$this->lookupType}Date
                 FROM `{$pModuleID}_l` 
                 GROUP BY `{$pModuleID}_l`.{$recordIDField}
                 ) AS $tableAlias 
                 ON `{$pModuleID}`.{$recordIDField} = `{$pModuleID}_l`.{$recordIDField}";*/
            $logTable = "`{$this->moduleID}_l`";
            $minTable = "`{$this->moduleID}_min`";
            $SQL = "LEFT OUTER JOIN (
                SELECT
                    $logTable.{$recordIDField},
                    $logTable._ModDate AS createDate,
                    $logTable._ModBy AS createBy
                FROM $logTable
                INNER JOIN (
                    SELECT
                        {$recordIDField},
                        MIN(_RecordID) AS MinRecordID
                    FROM $logTable
                    GROUP BY {$recordIDField}) as $minTable
                ON $logTable._RecordID = $minTable.MinRecordID
                ) AS $tableAlias
                ON (`{$pModuleID}`.{$recordIDField} = $tableAlias.{$recordIDField})";

            return array($tableAlias => $SQL);
            break;
        case 'modified':
            return array();
            break;
        default:
            die('RecordMetaField lookupType is not recognized.');
        }
    }
    
    function getFieldDependencies($pID){
        //return a nested array of all fields that a field depends on - a RecordMetaField has no dependencies
        return array();
    }
    
    function getDependentFields(){
        return array();
    }
}



//like a ForeignField but matches several values to one.
class RangeField extends ForeignField
{
    var $localTable;
    var $localKey;
    var $foreignTable;
    var $foreignTableAlias;
    var $foreignKey;
    var $foreignField;
    var $listCondition;
    var $joinType;

    
    function Factory($element, $moduleID){

        return new RangeField(
            $element->attributes['name'],
            $element->attributes['type'],
            $element->attributes['displayFormat'],
            $localTable, //?
            $element->attributes['key'], //localKey
            $element->attributes['foreignTable'],
            $element->attributes['foreignKey'],
            $element->attributes['foreignField'],
            $element->attributes['phrase'],
            $moduleID
        );
    }
    
    
    function RangeField(
        $pName,
        $pDataType,
        $pDisplayFormat,
        $pLocalTable,
        $pLocalKey,
        $pForeignTable,
        $pForeignKey,
        $pForeignField,
        $pPhrase,
        $pModuleID)
    {
        $this->name = $pName;
        $this->dataType = $pDataType;
        $this->displayFormat = $pDisplayFormat;
        $this->localTable = $pLocalTable;
        $this->localKey = $pLocalKey;
        $this->foreignTable = $pForeignTable;
        $this->foreignKey = $pForeignKey;
        $this->foreignField = $pForeignField;
        $this->phrase = $pPhrase;
        $this->moduleID = $pModuleID;
        
        //sanity check to avoid infinite loops
        if($pName == $pLocalKey){
            die("RangeField '{$pName}': Name and (local) Key can't be the same (error)");
        }
    }

    function getQualifiedName($pModuleID){
        $debug_prefix = debug_indent("RangeField-getQualifiedName() {$this->moduleID}.{$this->name}:");
        print "$debug_prefix pModuleID = $pModuleID\n";

        $name =  $this->makeSelectDef($pModuleID, false);
        
        print "$debug_prefix returned $name\n";
        debug_unindent();
        return $name;
    }

    function makeSelectDef($pModuleID, $pIncludeFieldAlias = true){

        if($pIncludeFieldAlias){
            $fieldAlias = ' AS '. $this->name;
        } else {
            $fieldAlias = '';
        }
        
        /*global $SQLBaseModuleID;
        $bak_SQLBaseModuleID = $SQLBaseModuleID;
        $SQLBaseModuleID = $this->foreignTable;*/
        
        $foreignKey        = GetModuleField($this->foreignTable, $this->foreignKey);
        $foreignKeyName    = $foreignKey->getQualifiedName($this->foreignTable);
        $localModuleFields = GetModuleFields($this->moduleID);
        $localKeyExpr      = $localModuleFields[$this->localKey]->makeSelectDef($pModuleID, false);
        
        
        $subSQL  = "SELECT {$this->foreignField}\n";
        $subSQL .= "FROM {$this->foreignTable}\n";
        /*if(count($localKeyJoins) > 0){
            $localKeyJoins = SortJoins($localKeyJoins);
            $subSQL .= implode("\n   ", $localKeyJoins);
            $subSQL .= "\n";
        }*/
        $subSQL .= "WHERE {$foreignKeyName} <= $localKeyExpr\n";
        $subSQL .= "ORDER BY {$foreignKeyName} DESC\n";
        $subSQL .= "LIMIT 1";
        
        $content = "($subSQL)$fieldAlias";
        
        //$SQLBaseModuleID = $bak_SQLBaseModuleID;
        return $content;

    }

    function makeJoinDef($pModuleID){
        print "RangeField-makeJoinDef {$this->name}";
        $localKeyJoins = array();
        
        $localKey = GetModuleField($this->moduleID, $this->localKey);
        $localKeyJoins = $localKey->makeJoinDef($pModuleID);
        
        return $localKeyJoins;
    }
    
    
    function getDependentFields(){
        $deps = array(
            array(
                'moduleID' => $this->moduleID, 
                'name' => $this->localKey
                ), 
            array(
                'moduleID' => $this->foreignTable, 
                'name' => $this->foreignKey
                ),
            array(
                'moduleID' => $this->foreignTable, 
                'name' => $this->foreignField
                )
        );
        
        return $deps;
    }
}
?>