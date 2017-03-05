<?php
/**
 *  Data handling class (saves data)
 *
 *  This file contains the DataHandler class definition. The purpose of this
 *  class is to handle all forms of data saving to the database.
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


class DataHandler
{
    var $moduleID;
    var $PKFields                = array(); //Primary Key fields. (WAS $recordIDFields)
    var $tableFields             = array(); //all the table fields in module
    var $remoteFields            = array(); //table fields used by this DataHandler
    var $remoteFieldAliasKeys    = array(); //the remote fields, grouped by their join key.
    var $autoIncrement           = true;    //the PK field is an auto-increment field (can only have single PK field when true)
    var $charPK                  = false;   //PK field is a character data type (only the Module module uses this)
    var $isImport                = false;   //the DataHandler object is instantiated for XML import
    var $updateImports           = false;   //updating imports are enabled (requires _GlobalID field), otherwise imports can only insert data
    var $relatedRecordFields     = array();
    var $useRemoteIDCheck        = false; //set this to true if saving remote fields, otherwise checks existence by record ID values
    var $ownerOrgField;
    
    var $dbValues                 = array();
    var $PKFieldValues            = array();
    var $relatedRecordFieldValues = array();
    

    /**private**/
    var $_selects        = array();
    var $_joins          = array();


/**
 *  constructor is called at parse-time only. To get a DataHandler object at run-time, use the GetDataHandler() global function
 */
function DataHandler($moduleID, $fields = array(), $isImport = false){
    if(!defined('EXEC_STATE') || EXEC_STATE != 4){
        trigger_error("Cannot instantiate a DataHandler object at run-time.", E_USER_ERROR);
    }

    $this->moduleID = $moduleID;
    $this->isImport = $isImport;

    $moduleFields =& GetModuleFields($moduleID);
    $moduleInfo = GetModuleInfo($moduleID);

    $this->PKFields = $moduleInfo->getProperty('primaryKeys');
    $this->autoIncrement  = $moduleInfo->getProperty('autoIncrement');
    $this->updateImports = $moduleInfo->getProperty('updateImports');
    $this->ownerOrgField = $moduleInfo->getProperty('ownerField');

    foreach($this->PKFields as $PKFieldName){
        $PKModuleField = $moduleFields[$PKFieldName];
        if(false !== strpos($PKModuleField->dataType, 'char')){
            $this->charPK = true;
        }
    }

    if(count($fields) == 0){
        $fields = array_keys($moduleFields);
    }

    global $SQLBaseModuleID;
    $SQLBaseModuleID = $this->moduleID;
    $this->tableFields = array();
    foreach($fields as $fieldName){
        $moduleField = $moduleFields[$fieldName];
        switch(get_class($moduleField)){
        case 'tablefield':
            $this->tableFields[$fieldName] = $moduleField->dataType;
            $this->_selects[$fieldName] = $moduleField->makeSelectDef($this->moduleID);
            break;
        case 'remotefield':
            $this->remoteFields[$fieldName] = $moduleField;
            $aliasKey = $moduleField->getTableAliasKey($this->moduleID);
            $this->remoteFieldAliasKeys[$aliasKey][] = $fieldName;
//            $this->_selects[$fieldName] = $moduleField->makeSelectDef($this->moduleID);
//            $this->_joins[$fieldName] = $moduleField->makeJoinDef($this->moduleID);
            $this->_selects[$fieldName] = GetSelectDef($fieldName);
            $this->_joins[$fieldName] = GetJoinDef($fieldName);

            break;
        default:
            break;
        }
    }
}



/*******************
 Private functions
*******************/

function _buildLogSQL(){
    $fieldNames = implode(",", array_keys($this->tableFields));

    $logSQL = "INSERT INTO {$this->moduleID}_l ($fieldNames) SELECT $fieldNames FROM `{$this->moduleID}` WHERE ";

    $atFirst = true;
    foreach($this->PKFields as $PKField){
        if($atFirst){
            $atFirst = false;
        } else {
            $logSQL .= ' AND ';
        }
        $logSQL .= $PKField . " = '[*pk*$PKField*]'"; //get value from the $this->PKFieldValues property
    }
    return $logSQL;
}


function _getPKSQLSnip()
{
    $SQL = '';
    if(!empty($this->_PKSQLSnip)){
        return $this->_PKSQLSnip;
    }

    $atFirst = true;

    foreach($this->PKFields as $PKField){
        if($atFirst){
            $atFirst = false;
        } else {
            $SQL .= ' AND ';
        }
        $SQL .=  "`{$this->moduleID}`.$PKField = '[*pk*$PKField*]'";
    }

    $this->_PKSQLSnip = $SQL;
//print debug_r($SQL);
    return $SQL;
}


function _buildRelatedSQLSnip()
{
    $SQL = '';
//print "_buildRelatedSQLSnip\n";
//print debug_r($this->relatedRecordFields);

trace($this->relatedRecordFieldValues, "DataHandler->_buildRelatedSQLSnip: relatedRecordFieldValues");

    $atFirst = true;
    foreach($this->relatedRecordFieldValues as $relatedRecordField => $relatedRecordFieldValue){
        if($atFirst){
            $atFirst = false;
        } else {
            $SQL .= ' AND ';
        }
        //$SQL .= $relatedRecordField . " = '[*rf*$relatedRecordField*]'";
        $SQL .= "`{$this->moduleID}`.{$relatedRecordField} = '$relatedRecordFieldValue'";
    }

trace($SQL, "DataHandler->_buildRelatedSQLSnip: SQL");
//print debug_r($SQL);
    return $SQL;

}


function _buildGetSQL($fields)
{
//print_r($fields);
    global $SQLBaseModuleID;
    $SQLBaseModuleID = $this->moduleID;
    $SQL = 'SELECT ';
    foreach($fields as $field){
        if(isset($this->_selects[$field])){
            $SQL .= $this->_selects[$field] . ',';
        } else {
            //trigger_error("The field $field is not defined.", E_USER_ERROR);
        }

    }
    $SQL = substr($SQL, 0, -1);

    $SQL .= ' FROM `' . $this->moduleID . '` ';
    $joins = array();
    if(count($this->remoteFields) > 0){
        $affectedRemoteFields = array_intersect(array_keys($this->remoteFields), $fields);
        if(count($affectedRemoteFields) > 0){
            foreach($affectedRemoteFields as $affectedRemoteField){
                $joins = array_merge($joins, $this->_joins[$affectedRemoteField]);
            }
            $joins = SortJoins($joins);
            $SQL .= implode("\n   ", $joins);
        }
    }



    if($this->isImport){
        if($this->updateImports){
            $SQL .= " WHERE `{$this->moduleID}`._GlobalID = '[*GlobalID*]'";
        } else {
            //without updateImports, the import will only INSERT data
        }
    } else {
        //use remote field checking when the PK field values haven't been looked up yet.
        if($this->useRemoteIDCheck && 0 == count($this->PKFieldValues)){
            $SQL .= ' WHERE ';
            $SQL .= $this->_buildRelatedSQLSnip();
        } else {
            $SQL .= ' WHERE ';
            $SQL .= $this->_getPKSQLSnip();
        }
    }

    return $SQL;
} //end function DataHandler->buildGetSQL()



/**
 * retrieves the record from the database, and populates the internal dbData and PKFieldValues arrays. 
 * Returns true if the record exists.
 **/
function _populate($fields)
{
    if($this->isPopulated){
        return $this->exists;
    }
    $fields = array_merge($this->PKFields, $fields);
    $SQL = $this->_buildGetSQL($fields);
$debug_prefix = "DataHandler({$this->moduleID})->_populate";
trace("$debug_prefix useRemoteIDCheck = '{$this->useRemoteIDCheck}'");

trace($this->PKFieldValues, $debug_prefix . ' PKFieldValues');
    if($this->useRemoteIDCheck && 0 == count($this->PKFieldValues)){
        //make sure related fields are populated, if using remote ID check.
        if(count($this->relatedRecordFieldValues) == 0){
            trigger_error("Must populate related record values before getting data for {$this->moduleID} fields", E_USER_ERROR);
        }

        //populate related field values
        foreach($this->relatedRecordFieldValues as $relatedRecordField => $relatedRecordFieldValue){
            $SQL = str_replace("[*rf*$relatedRecordField*]", $relatedRecordFieldValue, $SQL);
        }
    } else {
        //populate PK field values
        foreach($this->PKFieldValues as $PKField => $PKFieldValue){
            $SQL = str_replace("[*pk*$PKField*]", $PKFieldValue, $SQL);
        }
    }
trace($SQL, $debug_prefix.' getSQL');
    global $dbh;

    $data = $dbh->getRow($SQL, DB_FETCHMODE_ASSOC);
    dbErrorCheck($data);


    if(!empty($data)){
        $exists = true;
trace($data, $debug_prefix.' data');
        $this->dbValues = $data;

        if($this->useRemoteIDCheck){
            foreach($this->PKFields as $pkField){
                $this->PKFieldValues[$pkField] = $data[$pkField];
            }
        }
    } else {
        $exists = false;
trace($debug_prefix.' no matching record found');
    }

    $this->isPopulated = true;
    $this->exists = $exists;

    return $exists;
} //end function DataHandler->_populate()


function _update($values)
{
$debug_prefix = "DataHandler({$this->moduleID})->_update";

    $tableFieldNames = array_keys($this->tableFields);

    $SQL = "UPDATE `{$this->moduleID}` SET ";
    foreach($values as $field => $value){
        if(in_array($field, $tableFieldNames) && !in_array($field, $this->PKFields)){
            $qValue = dbQuote($value, $this->tableFields[$field]);
            $SQL .= "$field = $qValue,";
        }
    }

    $SQL .= '_ModDate=NOW(),_Deleted=0';
    if($this->isImport){
        if($this->updateImports){
            $SQL .= " WHERE `{$this->moduleID}`._GlobalID = '{$this->globalID}'";
        }
    } else {
        $SQL .= ' WHERE ';
        $SQL .= $this->_getPKSQLSnip();
    }

    global $dbh;

    //populate PK field values
    foreach($this->PKFieldValues as $PKField => $PKFieldValue){
        $SQL = str_replace("[*pk*$PKField*]", $PKFieldValue, $SQL);
    }

trace($SQL, $debug_prefix.' SQL');

    $r = $dbh->query($SQL);
    dbErrorCheck($r);

    return true;

} //end function DataHandler->_update()


function _insert($values)
{
$debug_prefix = "DataHandler({$this->moduleID})->_insert";
    $tableFieldNames = array_keys($this->tableFields);

    if(!$this->autoIncrement){
        foreach($this->PKFields as $PKField){
            $values[$PKField] = $this->PKFieldValues[$PKField];
        }
    }

    if(count($this->relatedRecordFieldValues) > 0){
        foreach($this->relatedRecordFieldValues as $relatedRecordField => $relatedRecordFieldValue){
            $values[$relatedRecordField] = $relatedRecordFieldValue;
        }
    }
//$fields: combine $values, $pk fields (if not autoIncrement), $related fields

    $SQL = "INSERT INTO `{$this->moduleID}` (\n";
    $valueSQL = ''; //second part of sql statement
    foreach($values as $field => $value){
        if(in_array($field, $tableFieldNames)){
            $qValue = dbQuote($value, $this->tableFields[$field]);
            $SQL .= "$field,";
            $valueSQL .= "$qValue,";
        }
    }

    $SQL .= "_ModDate,_Deleted) VALUES ($valueSQL NOW(),0)";

//execute query
    global $dbh;
    $r = $dbh->query($SQL);
    dbErrorCheck($r);
//print debug_r($values, 'insert values');
//print debug_r($SQL, 'insertSQL');
trace($SQL, $debug_prefix.' SQL');

    if($this->autoIncrement){
        $SQL = "SELECT LAST_INSERT_ID();";
        $recordID = $dbh->getOne($SQL);
        dbErrorCheck($recordID);

        $PKField = end($this->PKFields);
        $this->PKFieldValues[$PKField] = $recordID;
    }

    return true;

} //end function DataHandler->_insert()


function _saveRemoteFields($values)
{
    if(count($this->remoteFields) > 0){
        //save remote fields
        //SaveRemoteFields($this->remoteFields, $this->moduleID, $recordID, $values);
        if(!defined('EXEC_STATE') || EXEC_STATE == 1){
            global $User;
            $userID = $User->PersonID;
            //check save permissions
        } else {
            $userID = 0;
        }

        global $dbh;
        $localPKField = end($this->PKFields);

        foreach($this->remoteFieldAliasKeys as $aliasKey =>$remoteFields){
            $remoteValues = array();  //values to be saved to the remote module
            $relatedValues = array(); //"related" fields are those required to look up the record

            foreach($remoteFields as $remoteFieldName){
                $remoteField = $this->remoteFields[$remoteFieldName];
                if(empty($values[$remoteFieldName])){
                    if(isset($this->relatedRecordFieldValues[$remoteFieldName])){
                        $remoteValues[$remoteField->remoteField] = $this->relatedRecordFieldValues[$remoteFieldName];
                    } else {
                        //skips empty values
                    }
                } else {
                    $remoteValues[$remoteField->remoteField] = $values[$remoteFieldName];
                }
            }
    
            if(count($remoteValues) > 0){
                $relatedValues[$remoteField->remoteModuleIDField] = $this->moduleID;
                
                $relatedValues[$remoteField->remoteRecordIDField] = $this->PKFieldValues[$localPKField];
                if(!empty($remoteField->remoteDescriptor)){
                    $relatedValues[$remoteField->remoteDescriptorField] = $remoteField->remoteDescriptor;
                }
                
                $remoteDataHandler = GetDataHandler($remoteField->remoteModuleID, false, true);
                $remoteDataHandler->saveRowWithRelatedValues($remoteValues, $relatedValues);
            }
        }
    }
    return true;
} //end function DataHandler->_saveRemoteFields()


function _deleteRemoteFields()
{
    if(count($this->remoteFields) > 0){

        if(!defined('EXEC_STATE') || EXEC_STATE == 1){
            global $User;
            $userID = $User->PersonID;
            //check save permissions
        } else {
            $userID = 0;
        }

        global $dbh;
        $localPKField = end($this->PKFields);

        foreach($this->remoteFieldAliasKeys as $aliasKey =>$remoteFields){

            $relatedValues = array();
            foreach($remoteFields as $remoteFieldName){
                $remoteField = $this->remoteFields[$remoteFieldName];
            }
            $relatedValues[$remoteField->remoteModuleIDField] = $this->moduleID;

            $relatedValues[$remoteField->remoteRecordIDField] = $this->PKFieldValues[$localPKField];
            if(!empty($remoteField->remoteDescriptor)){
                $relatedValues[$remoteField->remoteDescriptorField] = $remoteField->remoteDescriptor;
            }

            $remoteDataHandler = GetDataHandler($remoteField->remoteModuleID, false, true);
            $remoteDataHandler->deleteRowWithRelatedValues($relatedValues);
        }
    }
    return true;
} //end function DataHandler->_deleteRemoteFields()



function _saveCaches($values, $delete = false)
{
trace($values, '_saveCaches ' . $this->moduleID);
    $recordID = end($this->PKFieldValues);
    $PKField = end($this->PKFields);

    UpdateRDCache($this->moduleID, $recordID, $PKField);
    //UpdateSMCache($this->moduleID, $recordID, $PKField);
    
    $this->_saveSMC($recordID, $PKField);
    
    $this->_saveCSC($recordID, $PKField);
    
} //end function DataHandler->_saveCaches()



function _saveSMC($recordID, $PKField)
{
    global $dbh;

    //get triggers file
    $triggerFile = GENERATED_PATH . "/{$this->moduleID}/{$this->moduleID}_SMCTriggers.gen";

    if(file_exists($triggerFile)){
        include($triggerFile); //sets $SMCtriggers

        foreach($SMCtriggers as $triggerModuleID => $triggerSQL){

            //possible to consolidate these 3 SQL statements?

            //get parent data
            $triggerSQL = str_replace(array('/*SubModuleID*/', '/*SubRecordID*/'), array($this->moduleID, $recordID), $triggerSQL);
            $data = $dbh->getRow($triggerSQL, DB_FETCHMODE_ASSOC);
            dbErrorCheck($data);

            if(!empty($data)){

                trace($data, "updating SMC trigger");

                $lookupSQL = "SELECT COUNT(*) FROM `smc` WHERE ModuleID = '{$data['ModuleID']}' AND RecordID = '{$data['RecordID']}' AND SubModuleID = '{$data['SubModuleID']}' AND SubRecordID = '{$data['SubRecordID']}'";

                $exists = $dbh->getOne($lookupSQL);
                dbErrorCheck($exists);

                if(empty($exists)){
                    if(!defined('EXEC_STATE') || EXEC_STATE == 1){
                        global $User;
                        $user_id = $User->PersonID;
                    } else {
                        $user_id = 0;
                    }
                    $insertSQL = "INSERT INTO `smc` (ModuleID, RecordID, SubModuleID, SubRecordID, _ModBy, _ModDate, _Deleted)
                    VALUES ('{$data['ModuleID']}', '{$data['RecordID']}', '{$data['SubModuleID']}', '{$data['SubRecordID']}', '$user_id', NOW(), 0)";

                    $r = $dbh->query($insertSQL);
                    dbErrorCheck($r);
                }
            }
        }
    }


} //end function DataHandler->_saveSMC()



function _saveCSC($recordID, $PKField)
{
    switch($this->moduleID){
    case 'cos':
    case 'lcod':
        //continue below
        break;
    default:
        return true;
        break;
    }

    $rskxaFeederModules = array(
        'hza',
        'ire',
        'len',
        'lin',
        'lit',
        'lpa',
        'lpd',
        'lppb',
        'lppe',
        'lppo',
        'lppv'
    );
    $str_rskxaFeederModules = "'hza','ire','len','lin','lit','lpa','lpd','lppb','lppe','lppo','lppv'";

    global $dbh;

//trace($this, 'datahandler properties');

    //identify relvant parent records, ordered by moduleID
    if('cos' == $this->moduleID){
        //possible to get cos.RelatedRecordID and cos.RelatedModuleID from DataHandler
        $SQL = "SELECT ModuleID, RecordID FROM `smc` WHERE SubModuleID = '{$this->relatedRecordFieldValues['RelatedModuleID']}' AND SubRecordID = '{$this->relatedRecordFieldValues['RelatedRecordID']}' AND ModuleID IN ($str_rskxaFeederModules)";
        
        $parents = $dbh->getAll($SQL, DB_FETCHMODE_ASSOC);
        dbErrorCheck($parents);

        
        if(in_array($this->relatedRecordFieldValues['RelatedModuleID'], $rskxaFeederModules)){
            $directParent = array(
                'ModuleID' => $this->relatedRecordFieldValues['RelatedModuleID'],
                'RecordID' => $this->relatedRecordFieldValues['RelatedRecordID']
            );
            $parents[] = $directParent;
        }

    } elseif('lcod' == $this->moduleID) {
        
        //select 
        $SQL = "SELECT clm.RelatedModuleID AS ModuleID, clm.RelatedRecordID AS RecordID, clm.IncidentReportID
        FROM lcod
        INNER JOIN lco ON lcod.LossCostID = lco.LossCostID
        INNER JOIN clm ON lco.ClaimID = clm.ClaimID
        WHERE lcod.LossCostDetailID = '$recordID'";
        
        $r = $dbh->getRow($SQL, DB_FETCHMODE_ASSOC);
        dbErrorCheck($r);
        
        $parents = array(
            array('ModuleID' => 'ire', 'RecordID' => $r['IncidentReportID']),
            array('ModuleID' => $r['ModuleID'], 'RecordID' => $r['RecordID'])
        );
        
    } else {
        return true;
    }
    
    trace($parents, 'cos relevant parents');

    if(count($parents) > 0){
        foreach($parents as $parent){
            //look up generated *_CostSeveritySQL.gen file
            $fileName = GENERATED_PATH . "/{$parent['ModuleID']}/{$parent['ModuleID']}_CostSeveritySQL.gen";
            if(file_exists($fileName)) {
                include($fileName); //sets $csSQL

                $csSQL = str_replace('/**RecordID**/', $parent['RecordID'], $csSQL);
                trace($csSQL, 'csSQL');

                //for each parent record, calculate the CostSeverity and update the csc table
                //calculate
                //$severity = $dbh->getOne($csSQL);
                //dbErrorCheck($severity);
                
                $cachedData = $dbh->getRow($csSQL, DB_FETCHMODE_ASSOC);
                dbErrorCheck($cachedData);

                //check presence in csc
                $SQL = "SELECT COUNT(*) > 0 FROM `csc` WHERE ModuleID='{$parent['ModuleID']}' AND RecordID='{$parent['RecordID']}'";
                $exists =$dbh->getOne($SQL);
                dbErrorCheck($exists);

                if($exists){
                    //update
                    $SQL = "UPDATE `csc` SET 
                        SeverityValue = '{$cachedData['CostSeverityValue']}',
                        TotalCost = '{$cachedData['TotalCost']}'
                        WHERE ModuleID='{$parent['ModuleID']}' AND RecordID='{$parent['RecordID']}'";
                    
                } else {
                    //insert
                    if(!defined('EXEC_STATE') || EXEC_STATE == 1){
                        global $User;
                        $user_id = $User->PersonID;
                    } else {
                        $user_id = 0;
                    }

                    $SQL = "INSERT INTO `csc` (ModuleID, RecordID, SeverityValue, TotalCost, _ModBy, _ModDate, _Deleted) VALUES ('{$parent['ModuleID']}', '{$parent['RecordID']}', '{$cachedData['CostSeverityValue']}', '{$cachedData['TotalCost']}', $user_id, NOW(), 0)";
                }
                
                $r = $dbh->query($SQL);
                dbErrorCheck($r);
            }
        
        }
    } else {
        return true;
    }
    return true;

}


function _saveLog()
{
    $SQL = $this->_buildLogSQL();

    foreach($this->PKFieldValues as $PKField => $PKFieldValue){
        $SQL = str_replace("[*pk*$PKField*]", $PKFieldValue, $SQL);
    }

//print debug_r($SQL, "{$this->moduleID} _saveLogSQL");

    global $dbh;
    $r = $dbh->query($SQL);

    dbErrorCheck($r);

    return true;
} //end function DataHandler->_saveLog()


function _startTransaction()
{
    global $transaction_started;
    global $dbh;

    if(!$transaction_started){
        $dbh->query('BEGIN');
        $transaction_started = true;
    }

    dbErrorCheck($r);

    return true;

} //end function DataHandler->_startTransaction()


function _endTransaction()
{
    global $transaction_started;
    global $dbh;

    if($transaction_started){
        $dbh->query('COMMIT');
        $transaction_started = false;
    }

    dbErrorCheck($r);

    return true;

} //end function DataHandler->_endTransaction()


function _checkPermission()
{
    if(!defined('EXEC_STATE') || EXEC_STATE == 1){
        global $User;
        $userID = $User->PersonID;

        //checks save permissions
        if(!empty($this->ownerOrgField)){
            if($exists){
                $orgID = $this->dbValues[$this->ownerOrgField];
            } else {
                $orgID = $values[$this->ownerOrgField]; //should probably also include this in the "exists" case, since otherwise, a user can change a record belonging to a peritted org to belong to a non-permitted org. The code would then need to deal with two orgIDs...
            }

        } else {
            $orgID = null;
        }
        $permission = $User->PermissionToEdit($this->moduleID, $orgID);
        if(!$permission){
            trigger_error("User {$User->UserName} has no permission to edit records in the '{$this->moduleID}' module.", E_USER_ERROR);
        }
    }
    return true;
}




/**
 *  Private, consolidated row saving function
 */
function _save($values)
{
    $this->_startTransaction();

    $exists = $this->_populate(array_keys($values));

    //permission checking (does not apply to command-prompt import)
    if(!defined('EXEC_STATE') || EXEC_STATE == 1){
        global $User;
        $userID = $User->PersonID;

        $this->_checkPermission();

        $values['_ModBy'] = $userID;
    } else {
        $values['_ModBy'] = 0;
    }


    if($exists){
        //update record
        $this->_update($values);
    } else {
        //insert record
        $this->_insert($values);
    }

    //save remote fields
    $this->_saveRemoteFields($values);

    //handle RDC and SMC caches
    $this->_saveCaches($values);
    

    //save log
    $this->_saveLog();

    $this->_endTransaction();

    $PKField = end($this->PKFields);
    $recordID = $this->PKFieldValues[$PKField];

    return $recordID;

} //end function DataHandler->_save

/**
 *  Private, consolidated row deletion function
 */
function _delete()
{

    //start transaction
    $this->_startTransaction();

    $exists = $this->_populate(array());

    if($exists){
        //check permission
        if(!defined('EXEC_STATE') || EXEC_STATE == 1){
            global $User;
            $userID = $User->PersonID;

            $this->_checkPermission();
        } else {
            $userID = 0;
        }

        //mark selected row as deleted
        $SQL = "UPDATE `{$this->moduleID}` SET _ModBy = $userID, _ModDate = NOW(), _Deleted = 1 WHERE ";
        $SQL .= $this->_getPKSQLSnip();
        foreach($this->PKFieldValues as $PKField => $PKFieldValue){
            $SQL = str_replace("[*pk*$PKField*]", $PKFieldValue, $SQL);
        }

        global $dbh;
        $r = $dbh->query($SQL);
        dbErrorCheck($r);
        trace($SQL, $this->moduleID.' deleteSQL');

        //mark all remote records as deleted
        $this->_deleteRemoteFields();

        //undo RDC, SMC
        $this->_saveCaches($values, true);

        //log
        $this->_saveLog();
    }

    //end transaction
    $this->_endTransaction();
}

function _getNextRecordID($conditionTags, $conditions)
{
    global $dbh;
    $recordIDField = end($this->PKFields);

    //we need the next available record ID
    $SQL = "SELECT MAX($recordIDField) +1 FROM `{$this->moduleID}`";
    if(count($this->PKFields) > 1){
        $SQL .= " WHERE ";
        $SQL .= $this->_buildRelatedSQLSnip();
//        $SQL = str_replace($conditionTags, $conditions, $SQL);
//print "record ID generating SQL: $SQL\n";
    }
trace($SQL, "DataHandler->_getNextRecordID() SQL");
    $newRecordID = $dbh->getOne($SQL);
    dbErrorCheck($newRecordID);
    return $newRecordID;

}


/******************
 Public Functions
******************/


/**
 *   The normal row saving function
 *
 *   @param $values            : Associative array of field names and values to be saved
 *   @param $recordIDs         : Simple array of supplied record IDs (typically one entry)
 */
function saveRow($values, $recordID)
{
trace($values, "values passed to saveRow");
    //applies to cod and maybe others
    if(empty($recordID) && !$this->autoIncrement){
        $recordIDField = end($this->PKFields);
        if((!isset($values[$recordIDField])) || empty($values[$recordIDField])){
            $recordID = $this->_getNextRecordID(array_keys($this->relatedRecordFieldValues), $this->relatedRecordFieldValues);
        } else {
            $recordID = intval($values[$recordIDField]);
        }
    }

    foreach($this->PKFields as $PKField){
        $this->PKFieldValues[$PKField] = $recordID;
    }

    if(count($this->PKFields) > 1){
        foreach($this->relatedRecordFieldValues as $fieldName => $fieldValue){
            $this->PKFieldValues[$fieldName] = $fieldValue;
        }
    }

    return $this->_save($values);

} //end function DataHandler->saveRow()

/**
 *  Saves the row based on "related" values.
 *
 *  This is used when saving remote fields. Note that if all the remote fields are
 *  empty, this function will implicitly DELETE the record instead!
 */
function saveRowWithRelatedValues($values, $relatedValues)
{
    $this->useRemoteIDCheck = true;
    $this->relatedRecordFieldValues = $relatedValues;

$debug_prefix = "DataHandler({$this->moduleID})->saveRowWithRelatedValues";
trace($this->relatedRecordFieldValues, "$debug_prefix saveRowWithRelatedValues");
trace($values, "$debug_prefix passed values");

    $this->_startTransaction();

    //check existence
    $exists = $this->_populate(array_keys($values));

    //check if any values were actually passed for saving
    if(count($values) > 0){
        $found_value = false;
        foreach($values as $value){
            if(!empty($value)){
                $found_value = true;
            }
        }
    }

    //don't implicitly delete the record if it also has remotefields
    //this could be expanded to count submodule records too (i.e. don't implicitly delete records that have submodules)
    $has_dependents = (count($this->remoteFields) > 0);

    if($found_value){
        return $this->_save($values);
    } else {
        if($exists && !$has_dependents){
            return $this->_delete();
        } else {
            return true; //do nothing
        }
    }
} //end function DataHandler->saveRowWithRelatedValues()


function importRow($values)
{

} //end function DataHandler->importRow()


function deleteRow($recordID)
{

    foreach($this->PKFields as $PKid => $PKField){
        $this->PKFieldValues[$PKField] = $recordID;
    }

    if(count($this->PKFields) > 1){
        foreach($this->relatedRecordFieldValues as $fieldName => $fieldValue){
            $this->PKFieldValues[$fieldName] = $fieldValue;
        }
    }
trace($this->PKFieldValues, "PKFieldValues");
    return $this->_delete();
}

function deleteRowWithRelatedValues($relatedValues)
{
    $this->useRemoteIDCheck = true;
    $this->relatedRecordFieldValues = $relatedValues;

$debug_prefix = "DataHandler({$this->moduleID})->deleteRowWithRelatedValues";
trace($this->relatedRecordFieldValues, "$debug_prefix relatedRecordFieldValues");

    return $this->_delete();
}


} //end class DataHandler

?>