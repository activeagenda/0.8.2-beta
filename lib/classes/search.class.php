<?php
/**
 *  Defines the Search class
 *
 *  This file contains the definition of the Search class, which generates the
 *  necessary SQL statements on-the-fly, in response to searches made by the
 *  user. It also supports charts and saved Default searches, which means that 
 *  it loads the search conditions from the database. Run-time only.
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
 * @version        SVN: $Revision: 498 $
 * @last-modified  SVN: $Date: 2007-02-16 13:29:54 -0800 (Fri, 16 Feb 2007) $
 */

class Search
{
var $froms = array();  //from clauses resulting from search criteria
var $wheres = array(); //where clauses resulting from search criteria
var $moduleID;
var $phrases = array(); //verbal search expressions to be displayed to user
var $listFields = array();
var $listSelects = array();
var $listFroms = array();
var $postData = array();
var $isUserDefault = false;

function Search(
    $moduleID,
    $listFields = array(),
    $formFields = null,
    $postdata = null
)
{
    $this->moduleID = $moduleID;
    $this->listFields = $listFields;
    $this->prepareFroms($formFields, $postdata);
}



function prepareFroms(&$formFields, &$postdata)
{
    $moduleFields =& GetModuleFields($this->moduleID);
    $this->postData = array();
    if(count($postdata) > 0){
        foreach($postdata as $postKey => $postValue){
            if(!empty($postValue)){
                switch($postKey){
                case 'Search':
                case 'Chart':
                    break;
                default:
                    $this->postData[$postKey] = $postValue;
                    break;
                }
            }
        }
    }

    ob_start();

    //clear the SELECTs, FROMs and WHEREs
    $this->listSelects = array();
    $this->listFroms = array();
    $this->froms = array();
    $this->wheres = array();

    global $SQLBaseModuleID;
    $SQLBaseModuleID = $this->moduleID;

    global $ModuleID;
    $origModuleID = $ModuleID;
    $ModuleID = $this->moduleID; //yes, an ugly hack...

    if(count($formFields)>0){
        foreach($formFields as $fieldname => $field){

            //allow field objects to determine how to handle whether andf
            //how a field is being searched on
            $searchDef = &$field->handleSearch(&$postdata, &$moduleFields);

            if(!empty($searchDef)){

                //$fromLines is an array with either 0 or 1 values
                //added to $froms as needed
                $fromLines = $searchDef['f'];
                foreach($fromLines as $alias => $fromLine){
                    $this->froms[$alias] = $fromLine;
                }

                $this->wheres[] = $searchDef['w'];

                //adds human-readable search expressions to be displayed to user...
                foreach($searchDef['p'] as $searchPhrase){
                    $this->phrases[] = $searchPhrase; 
                }

//removable?    $this->searchValues = array_merge($searchValues, $searchDef['v']); //?
            }
        }
        $this->froms = SortJoins($this->froms);
    }
//print_r($this->listFields);
        //list field data
    foreach($this->listFields as $listFieldAlias => $listField){ //$listFieldName key is numbered
        if(is_int($listFieldAlias)){
            $listFieldAlias = $listField;
        }
        $moduleField =& $moduleFields[$listField];
        $this->listSelects[] = $moduleField->makeSelectDef($this->moduleID, false) . " AS $listFieldAlias";

        $this->listFroms = array_merge($this->listFroms, $moduleField->makeJoinDef($this->moduleID));

    }
    $this->listFroms = SortJoins($this->listFroms);

    $ModuleID = $origModuleID;

    $output = ob_get_contents();
    ob_end_clean();
//print debug_r($output);
//print debug_r($this);
       //die();

}



function getListSQL($orderByField = null, $split_statement = false)
{

    $selectSQL = "SELECT \n";
    $selectSQL .= join(",\n", $this->listSelects);
    $selectSQL .= "\n";

    $froms = array_merge($this->froms, $this->listFroms);
    ob_start();
    $froms = SortJoins($froms);
    ob_end_clean();
    $fromSQL .= "FROM \n `{$this->moduleID}`\n";
    foreach($froms as $alias => $def){
        $fromSQL .= "$def\n";
    }

    $fromSQL .= "WHERE\n";
    $fromSQL .= "{$this->moduleID}._Deleted = 0";

    foreach($this->wheres as $fields){
        foreach($fields as $fieldname => $def){
            $fromSQL .= "\nAND $def";
        }
    }
    $fromSQL .= "\n";


    if(!empty($orderByField)){
        $fromSQL .= "ORDER BY $orderByField\n"; //need to qualify name?
    }
    if($split_statement){
        $recordIDSelect = $this->listSelects[0];
        return array($selectSQL, $fromSQL, $recordIDSelect);
    } else {
        return $selectSQL . $fromSQL;
    }
} //end getListSQL



function getCustomListSQL($listFields)
{
//print "getCustomListSQL";
    $moduleFields = GetModuleFields($this->moduleID);
    global $SQLBaseModuleID;
    $SQLBaseModuleID = $this->moduleID;
    $listSelects = array();
    $listFroms = array();

    $boolFieldTranslation = 'CASE %s WHEN 1 THEN \'Yes\' WHEN 0 THEN \'No\' ELSE NULL END AS %s';

    ob_start();
    foreach($listFields as $listField){ //key is numbered
        $moduleField =& $moduleFields[$listField];
        if('bool' == $moduleField->dataType){
            $listSelects[] = sprintf(
                $boolFieldTranslation, 
                $moduleField->makeSelectDef($this->moduleID, false),
                $listField
                ) . " AS $listField";
        } else {
            $listSelects[] = $moduleField->makeSelectDef($this->moduleID, false) . " AS $listField";
        }
        $listFroms = array_merge($listFroms, $moduleField->makeJoinDef($this->moduleID));
    }
//        $output = ob_get_contents();
    ob_end_clean();
//print debug_r($output);

    $SQL = "SELECT \n";
    $SQL .= join(",\n", $listSelects);
    $SQL .= "\n";

    $froms = array_merge($this->froms, $listFroms);
    ob_start();
    $froms = SortJoins($froms);
    ob_end_clean();
    $SQL .= "FROM \n `{$this->moduleID}`\n";
    foreach($froms as $alias => $def){
        $SQL .= "$def\n";
    }

    $SQL .= "WHERE\n";
    $SQL .= "{$this->moduleID}._Deleted = 0";

    foreach($this->wheres as $fields){
        foreach($fields as $fieldname => $def){
            $SQL .= "\nAND $def";
        }
    }

    $SQL .= "\n";

    return $SQL;
} //end getCustomListSQL



function getCountSQL()
{
    $SQL = "SELECT COUNT(*) \n";

    $froms = array_merge($this->froms, $this->listFroms);
    $SQL .= "FROM \n `{$this->moduleID}`\n";
    ob_start();
    $froms = SortJoins($froms);
    ob_end_clean();

    foreach($froms as $alias => $def){
        $SQL .= "$def\n";
    }

    $SQL .= "WHERE {$this->moduleID}._Deleted = 0";

    foreach($this->wheres as $fields){
        foreach($fields as $fieldname => $def){
            $SQL .= "\nAND $def";
        }
    }
    $SQL .= "\n";
//$this->isUserDefault = false;
//print debug_r($this);
//print debug_r($SQL);

    return $SQL;
} //end getCountSQL



function getSummarySQL($summaryFields, $groupByFields, $orderBy = 'value')
{
//print "getSummarySQL<br />";

    $froms = array();
    $selectStrings = array();
    $moduleFields = GetModuleFields($this->moduleID); //this could be optimized if select defs etc were cached
//print debug_r($moduleFields);
    global $SQLBaseModuleID;
    $SQLBaseModuleID = $this->moduleID;

    $boolFieldTranslation = 'CASE %s WHEN 1 THEN \'Yes\' WHEN 0 THEN \'No\' ELSE NULL END AS %s';

    $dateFieldTranslation['year'] = 'DATE_FORMAT([*field*], \'%Y\')';
    $dateFieldTranslation['monthnum'] = 'DATE_FORMAT([*field*], \'%m\')';
    $dateFieldTranslation['week'] = 'DATE_FORMAT([*field*], \'%u\')'; //week starts Monday
    $dateFieldTranslation['yearweek'] = 'DATE_FORMAT([*field*], \'%x-W%u\')'; //week starts Monday
    $dateFieldTranslation['yearmonth'] = 'DATE_FORMAT([*field*], \'%Y-%m\')';
    $dateFieldTranslation['yearmonthday'] = 'DATE_FORMAT([*field*], \'%Y-%m-%d\')';
    $dateFieldTranslation['yearquarter'] = 'CONCAT(YEAR([*field*]), \' q\', QUARTER([*field*]))';

    foreach($groupByFields as $fieldName => $date_interval){
        ob_start();
            $groupByField = $moduleFields[$fieldName];

            switch($groupByField->dataType){
            case 'bool':
                $selectStrings[] = sprintf(
                    $boolFieldTranslation, 
                    $groupByField->makeSelectDef($this->moduleID, false),
                    $fieldName
                    );
                break;
            case 'date':
            case 'datetime':
                if(empty($date_interval)){
                    $date_interval = 'yearmonthday';
                }
                $selectStrings[] = str_replace(
                    '[*field*]',
                    $groupByField->makeSelectDef($this->moduleID, false),
                    $dateFieldTranslation[$date_interval]
                ). ' AS ' . $fieldName;
                break;
            default:
                $selectStrings[] = $groupByField->makeSelectDef($this->moduleID);
                break;
            }
            $froms = array_merge($froms, $groupByField->makeJoinDef($this->moduleID));

//$output = ob_get_contents();
        ob_end_clean();
//print debug_r($output);
    }
//print debug_r($froms, 'resulting froms');

    foreach($summaryFields as $fieldName => $summaryType){
        ob_start();
            $summarizeField = $moduleFields[$fieldName];
            $selectStrings[] = $summaryType.'('.$summarizeField->makeSelectDef($this->moduleID, false).') AS '.$fieldName;
            $froms = array_merge($froms, $summarizeField->makeJoinDef($this->moduleID));
        ob_end_clean();
    }
//print debug_r($froms);        
//print debug_r($this->froms);
    $SQL = "SELECT \n";
    $SQL .= implode(', ', $selectStrings) . "\n";

    $SQL .= "FROM \n `{$this->moduleID}`\n";
    $froms = array_merge($froms, $this->froms);

    ob_start();
    $froms = SortJoins($froms);
//$output = ob_get_contents();
    ob_end_clean();
//print debug_r($output, 'SortJoins');
//print debug_r($froms, 'sorted froms');
    foreach($froms as $alias => $def){
        $SQL .= "$def\n";
    }

    $SQL .= "WHERE {$this->moduleID}._Deleted = 0";

    foreach($this->wheres as $fields){
        foreach($fields as $fieldname => $def){
            $SQL .= "\nAND $def";
        }
    }
    $SQL .= "\n";
    $SQL .= "GROUP BY ";
    $SQL .= implode(', ', array_keys($groupByFields));
    switch($orderBy){
    case 'value':
        $SQL .= "\nORDER BY ";
        reset($summaryFields);
        $SQL .= key($summaryFields).' DESC ';
        break;
    case 'label':
        $SQL .= "\nORDER BY ";
        reset($groupByFields);
        $SQL .= key($groupByFields);
        break;
    default:
        //original order
    }
//print debug_r($SQL);
//die();
    return $SQL;
} //end getSummarySQL



function getPhrases()
{
    if(count($this->phrases) > 0){
        $content = join($this->phrases, "<br />\n");
    } else {
        $content = gettext("None");
    }

    return $content;
}



function hasConditions()
{
    return count($this->phrases) > 0;
}



/**
 * Saves the search conditions to a table.
 *
 * Conditions can be saved to one of the following tables:
 * usrsd - User Search Defaults
 * dsbcc - Dashboard Chart Conditions
 *
 * @param int       $userID       The UserID of the user for whom to save conditions
 * @param string    $table        Name of the table (module) where to save the conditions. 
 *                                 Currently supported values are 'usrsd' and 'dsbcc'
 * @param string    $chartID      ID of chart to save conditions for (if $table == 'dsbcc')
 */
function _saveConditions($userID, $table, $chartID = null)
{
    global $dbh;
    //$moduleID from $this->moduleID

    $userID = intval($userID);

    switch($table){
    case 'usrsd':
        $chartWhereSnip = '';
        $chartInsertSnip = '';
        $chartInsertValSnip = '';
        break;
    case 'dsbcc':
        if(empty($userID)){
            $dbh->query('ROLLBACK');
            die("Search->_saveConditions: No userID specified.");
        }
        if(empty($chartID)){
            $dbh->query('ROLLBACK');
            die("Search->_saveConditions: No chartID specified.");
        }
        $chartWhereSnip = " AND DashboardChartID = '$chartID'";
        $chartInsertSnip = ",\n DashboardChartID";
        $chartInsertValSnip = ",\n '$chartID'";
        break;
    default:
        die("Search->_saveConditions: Table $table not supported.");
        break;
    }


    //start transaction

    //SQL to check for existing conditions:
    $SQL = "SELECT ConditionID, ConditionField, ConditionValue FROM `$table` WHERE UserID = $userID AND ModuleID = '{$this->moduleID}'$chartWhereSnip";

    $existingSet = $dbh->getAll($SQL, DB_FETCHMODE_ASSOC);
    $removes = array();
    $updates = array();

    if(count($existingSet) > 0){
        //figure out what conditions are currently saved, and decide which to update or remove
        foreach($existingSet as $existingRow){
            if(array_key_exists($existingRow['ConditionField'], $this->postData)){
                $updates[$existingRow['ConditionField']] = $existingRow['ConditionID'];
            } else {
                $removes[] = $existingRow['ConditionID'];
            }
        }
    }

    foreach($this->postData as $postField => $postValue){
        if(is_array($postValue)){
            $postValue = join(',',$postValue);
        }
        if(array_key_exists($postField, $updates)){
            $SQL = "UPDATE `$table`\n";
            $SQL .= "SET\n";
            $SQL .= "    ConditionField = '$postField',\n";
            $SQL .= "    ConditionValue = '$postValue',\n";
            $SQL .= "    _Deleted = 0\n";
            $SQL .= "WHERE\n";
            $SQL .= "    ConditionID = {$updates[$postField]}\n";
        } else {
            $SQL = "INSERT INTO `$table` (\n";
            $SQL .= "    ModuleID,\n";
            $SQL .= "    UserID,\n";
            $SQL .= "    ConditionField,\n";
            $SQL .= "    ConditionValue{$chartInsertSnip}\n";
            $SQL .= ") VALUES (\n";
            $SQL .= "    '{$this->moduleID}',\n";
            $SQL .= "    $userID,\n";
            $SQL .= "    '$postField',\n";
            $SQL .= "    '$postValue'{$chartInsertValSnip}\n";
            $SQL .= ")";
        }
//print debug_r($SQL);
        $r = $dbh->query($SQL);
        dbErrorCheck($r);
    }

    if(count($removes) > 0){
        $strRemoves = join(',', $removes);
        $SQL = "UPDATE `$table`\n";
        $SQL .= "SET\n";
        $SQL .= "    _Deleted = 1\n";
        $SQL .= "WHERE\n";
        $SQL .= "    ConditionID IN ({$strRemoves})\n";
        $r = $dbh->query($SQL);
        dbErrorCheck($r);
    }

    //end transaction
} //end _saveConditions



/**
 * Saves the search conditions to the User Search Defaults table.
 *
 * @param int       $userID       The UserID of the user for whom to save conditions
 */
function saveUserDefault($userID)
{
    $this->_saveConditions($userID, 'usrsd');
    $this->isUserDefault = true;
    $_SESSION['Search_'.$this->moduleID] = $this;
}



/**
 * Saves the chart conditions to the Dashboard Chart Conditions table.
 *
 * @param int       $userID       The UserID of the user for whom to save conditions
 * @param string    $chartID      ID of chart to save conditions for
 */
function saveChartConditions($userID, $chartID)
{
    $this->_saveConditions($userID, 'dsbcc', $chartID);
}



/**
 * Loads saved conditions from a table
 *
 * Conditions can be loaded from one of the following tables:
 * usrsd - User Search Defaults
 * dsbcc - Dashboard Chart Conditions
 *
 * @param int       $userID       The UserID of the user for whom to load conditions
 * @param string    $table        Name of the table (module) from where to load the conditions. 
 *                                 Currently supported values are 'usrsd' and 'dsbcc'
 * @param string    $chartID      ID of chart to load conditions for (if $table == 'dsbcc')
 */
function _loadConditions($userID, $table, $chartID = null){

    global $dbh;

    if(!empty($chartID)){
        $chartWhereSnip = " AND DashboardChartID = $chartID";
    }



    $SQL = "SELECT ConditionID, ConditionField, ConditionValue FROM `$table` WHERE _Deleted = 0 AND UserID = $userID AND ModuleID = '{$this->moduleID}'$chartWhereSnip";

    $conditionsSet = $dbh->getAll($SQL, DB_FETCHMODE_ASSOC);
    $conditions = array();

    if(count($conditionsSet) > 0){
        //figure out what conditions are currently saved, and decide which to update or remove
        foreach($conditionsSet as $conditionsRow){
            $conditions[$conditionsRow['ConditionField']] = $conditionsRow['ConditionValue'];
        }
    }

    //get searchFields:
    include_once(CLASSES_PATH . '/components.php');
    include_once(GENERATED_PATH . "/{$this->moduleID}/{$this->moduleID}_SearchFields.gen"); //returns $searchFields

    $this->prepareFroms($searchFields, $conditions);


} //end _loadConditions


/**
 * Loads the search conditions from the User Search Defaults table.
 *
 * @param int       $userID       The UserID of the user for whom to load conditions
 */
function loadUserDefault($userID)
{
    $this->_loadConditions($userID, 'usrsd');
    $this->isUserDefault = true;
    $_SESSION['Search_'.$this->moduleID] = $this;
}


/**
 * Loads the search conditions from the Dashboard Chart Conditions table.
 *
 * @param int       $userID       The UserID of the user for whom to load conditions
 * @param string    $chartID      ID of chart to load conditions for
 */
function loadChartConditions($userID, $chartID)
{
    $this->_loadConditions($userID, 'dsbcc', $chartID);
}

} //end class Search



?>