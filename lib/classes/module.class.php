<?php
/**
 *  Module Class definition and related classes
 *
 *  This file contains the Module class and related classes used
 *  by the Module class.
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
 * @version        SVN: $Revision: 519 $
 * @last-modified  SVN: $Date: 2007-02-20 14:34:37 -0800 (Tue, 20 Feb 2007) $
 */

//this include contains the render-able component classes
include_once CLASSES_PATH . '/grids.php'; //includes field classes

//this include contains the module field classes
include_once CLASSES_PATH . '/modulefields.php';

//this include contains utility functions
include_once INCLUDE_PATH . '/general_util.php';

//module map class
include_once CLASSES_PATH . '/module_map.class.php';


//debug support: change this to get more debug messages
define('DEBUG_LEVEL', 0); //0 = don't print nearly anything








/** Module class
 *
 * This class does the following:
 * - Parses an XML Module Definition file upon instantiation,
 *   including module definitions of related modules and submodules, 
 *   and stores this information in its structure.
 * - Provides methods for creating and updating database tables, building
 *   SQL statements, and generating PHP cached scripts.
 */

class Module
{

    //data-specific properties
    var $ModuleID;                          //the Module ID as well as table name for the module
    var $Name;                              //the Module's Name
    var $SingularRecordName;                //phrase that describes one record
    var $PluralRecordName;                  //phrase that describes multiple records
    var $addNewName;                        //if present, overrides SingularRecordName for the "new" tab
    var $OwnerField;                        //field name that specifies the owner organization of a record
    var $recordDescriptionField;
    var $recordLabelField;
    var $isGlobal = false;
    var $includeGlobalModules = true;
    var $ModuleFields = array();            //contains descendants of ModuleField
    var $SubModules = array();              //list of sub-modules
    var $_screens = array();                 //list of screen objects (private)
    var $viewScreen = null;
    var $searchScreen = null;
    var $listScreen = null;                 //reference to the list screen in the $_screens array
    
    var $_listFields = array();              //fields for the list grid
    var $permissionParentModuleID;
    
    var $PKFields = array();                //primary key fields
    var $Indexes = array();                 //indexes
    var $AllowAddRecord = true;             //whether there should be a link to add a record in this module (otherwise, records can only be added via an EditGrid in a different module, or some other automatic way)
    var $extendsModuleID = '';              //if set, indicates that this module should extend another
    var $extendsModuleKey = '';
    var $isExtendedByModuleID = '';
    var $extendsModuleFilterField = '';
    var $extendsModuleFilterValue = '';
    var $updateImports = false;
    
    var $_documentation = array();
    
    //function-specific properties
    var $_map;                      //module map
    var $Parsed = false;            //is set to true once the XML file has been read
    var $ParseContext = array();    //provides info on parent node while parsing
    var $LastIndex;                 //latest index parsed
    var $LastScreen;                //latest screen parsed
    var $ForeignTables = array();   //collection of tables joined to. NOT UNIQUE
    var $RemoteModules = array();           //collection of modules pointed to with a remotefield
    var $ExportGrids = array();
    var $ListScreenCustomCodes = array(); //necessary b/c List screens aren't a separate class
    var $revisionInfo = array();    //array of revision properties
    var $useBestPractices = false;
    var $dataCollectionForm = false;
    var $isMasterData = false;


function Factory($element, $moduleID)
{
    return false;
}



//constructor
function Module($moduleID)
{
    $debug_prefix = debug_indent("Module constructor $moduleID:");
    print "$debug_prefix current class is ".get_class($this)."\n";

    global $moduleParseList;

    $this->ModuleID = $moduleID;

    $moduleDefFile = XML_PATH . "/{$moduleID}ModuleDef.xml";


    if(file_exists($moduleDefFile)){
        //$this->Debug[] = "Found file '$moduleDefFile'. Parsing...";
//print "status $moduleID OK\n";            
        //this will eventually replace the Module object's own SAX parsing functions...
        $moduleMap = new ModuleMap($moduleID);
        $moduleMap->parseMe();
        $this->_map = $moduleMap;
        //print_r ($this->_map);

        $module_attributes = $this->_map->attributes;

        //set the module name:
        $this->Name = $module_attributes['name'];
        $this->SingularRecordName = $module_attributes['singularRecordName'];
        $this->PluralRecordName = $module_attributes['pluralRecordName'];
        if(isset($module_attributes['addNewName'])){
            $this->addNewName = $module_attributes['addNewName'];
        }
        $this->permissionParentModuleID = $module_attributes['parentModule'];

        if(isset($module_attributes['allowAddRecord']) && 
            'no' == strtolower($module_attributes['allowAddRecord'])){
            $this->AllowAddRecord = false;  //defaults to TRUE
        }
        if(isset($module_attributes['isGlobal']) && 
            'yes' == strtolower($module_attributes['isGlobal'])){
            $this->isGlobal = true; //defaults to FALSE
        }
        if(isset($module_attributes['includeGlobalModules']) && 
            'no' == strtolower($module_attributes['includeGlobalModules'])){
            $this->includeGlobalModules = false;  //defaults to TRUE
        }
        if(isset($module_attributes['updateImports']) && 
            'yes' == strtolower($module_attributes['updateImports'])){
            $this->updateImports = true;  //defaults to FALSE
        }
        
        if(isset($module_attributes['dataCollectionForm']) && 
            'yes' == strtolower($module_attributes['dataCollectionForm'])){
            $this->dataCollectionForm = true;  //defaults to FALSE
        }
        if(isset($module_attributes['isMasterData']) && 
            'yes' == strtolower($module_attributes['isMasterData'])){
            $this->isMasterData = true;  //defaults to FALSE
        }
        


        //----------------------
        //set up module info
        //----------------------
        $module_info_element = $this->_map->selectFirstElement('ModuleInfo', null, null);
//print_r ($module_info_element);

        //primary key field(s)
        $pk_element = $module_info_element->selectFirstElement('PrimaryKey');
        foreach($pk_element->c as $pkfieldref_element){
            $this->PKFields[] = $pkfieldref_element->name;
        }

        //indexes
        $index_elements = $module_info_element->selectElements('Index');
        foreach($index_elements as $index_element){
            $t_ixFields = array();
            foreach($index_element->c as $index_field_element){
                $t_ixFields[] = $index_field_element->name;
            }
            $this->Indexes[$index_element->name] = $t_ixFields;
        }
        if($this->updateImports){
            $this->Indexes['GlobalID'] = array('_GlobalID');
        }

        //owner field
        $owner_field_element = $module_info_element->selectFirstElement('OwnerField');
        if(!empty($owner_field_element)){
            $this->OwnerField = $owner_field_element->name;
        }


        //record Description Field
        $rd_field_element = $module_info_element->selectFirstElement('RecordDescriptionField');
        if(!empty($rd_field_element)){
            $this->recordDescriptionField = $rd_field_element->name;
        }


        //record Label Field
        $rl_field_element = $module_info_element->selectFirstElement('RecordLabelField');
        if(!empty($rl_field_element)){
            $this->recordLabelField = $rl_field_element->name;
        }

        //extending functionality: 
        $ext_element = $module_info_element->selectFirstElement('ExtendModule');
        if($ext_element){ //is false if not successful
            $this->extendsModuleID = $ext_element->attributes['moduleID'];
            $this->extendsModuleKey = $ext_element->attributes['localKey'];
            $this->extendsModuleFilterField = $ext_element->attributes['filterField'];
            $this->extendsModuleFilterValue = $ext_element->attributes['filterValue'];
        }

        $revision_info_element = $this->_map->selectFirstElement('Revision', null, null);
        if(!empty($revision_info_element)){
            foreach($revision_info_element->attributes as $attribute_name => $attribute){
                $pattern = '/: (.+) \$/';
                $matches = array();
                preg_match ( $pattern, $attribute, $matches);
                $this->revisionInfo[$attribute_name] = $matches[1];
            }
            //indent_print_r($this->revisionInfo, 'revisionInfo');
        }


        global $foreignModules;
        $foreignModules[$moduleID] = &$this;
        //$foreignModules[$moduleID] = $this;

        //----------------------
        //set up module fields
        //----------------------
        $modulefields_element = $this->_map->selectFirstElement('ModuleFields', NULL, NULL);
//print_r ($modulefields_element->c);
        //make sure to add TableFields FIRST
        foreach($modulefields_element->c as $modulefield_element){
            if('TableField' == $modulefield_element->type){
                $this->ModuleFields[$modulefield_element->name] = $modulefield_element->createObject($moduleID);
            }
        }

        //then the rest of them
        foreach($modulefields_element->c as $modulefield_element){
            switch($modulefield_element->type){
            case 'ForeignFields':
            case 'RemoteFields':
                $attrs = $modulefield_element->attributes;
                $cont = $modulefield_element->c;
                foreach($cont as $grouped_modulefield_element){
                    $grouped_modulefield_element->c = array_merge(
                        $grouped_modulefield_element->c,
                        $cont
                        );
                    $grouped_modulefield_element->attributes = array_merge(
                        $grouped_modulefield_element->attributes,
                        $attrs
                        );
                    $this->ModuleFields[$grouped_modulefield_element->name] = $grouped_modulefield_element->createObject($moduleID);
                }
                break;
            case 'TableField':
                break;
            default:
                $this->ModuleFields[$modulefield_element->name] = $modulefield_element->createObject($moduleID);
                break;
            }
        }

        //append audit trail fields to table def
        $this->ModuleFields['_ModDate'] = new TableField (
            '_ModDate',
            'datetime',
            '',
            'not null',
            'Modified On',
            '',
            '',
            $this->ModuleID
            );
        $this->ModuleFields['_ModBy'] = new TableField (
            '_ModBy',
            'int',
            '',
            'unsigned not null default 0',
            'Modified By',
            '',
            '',
            $this->ModuleID
            );
        $this->ModuleFields['_Deleted'] = new TableField (
            '_Deleted',
            'bool',
            '',
            'not null default 0',
            'Deleted',
            '',
            '',
            $this->ModuleID
            );
        //make sure ppl module is included
        GetModule('ppl');
        $this->ModuleFields['_ModByName'] = new ForeignField (
            '_ModByName',
            'text',
            '',
            $this->ModuleID,
            '_ModBy',
            'ppl',
            '_ppl',
            'PersonID',
            'DisplayName',
            '',
            '',
            'left',
            'Modified By',
            '',
            $this->ModuleID
            );

        if($this->updateImports){
            $this->ModuleFields['_GlobalID'] = new TableField (
                '_GlobalID',
                'varchar(20)',
                '',
                'default NULL',
                'Global ID',
                '',
                '',
                $this->ModuleID
            );
        }

        //record label field
        $record_label_element = $module_info_element->selectFirstElement('RecordLabelField');
        if(!empty($record_label_element)){
            $this->recordLabelField = $record_label_element->name;
        } else {
            //look for a RecordDescription field
            if(array_key_exists('RecordDescription', $this->ModuleFields)){
                $this->recordLabelField = 'RecordDescription';
            }
        }

        //$foreignModules[$moduleID] = $this;
        $this->Parsed = true;

        //do the following only if current class name is "Module"
        if('module' == get_class($this)){
            $this->loadSubModules();
        }

        $moduleParseList[$this->ModuleID] = 'parsed';

    } else {

        $moduleParseList[$this->ModuleID] = 'no file';

        trigger_error("$debug_prefix Could not find XML file '$moduleDefFile'.", E_USER_ERROR);
    }

    debug_unindent();
}

/**
 * returns TRUE if the module uses an auto_increment field
 */
function usesAutoIncrement()
{
    $found = false;
    foreach($this->PKFields as $PKField){
        $moduleField = $this->ModuleFields[$PKField];
        if(false !== strpos($moduleField->dbFlags, "auto_increment")){
            $found = true;
        }
    }
    return $found;
}


/**
 *  Returns the specified submodule without loading all submodules
 */
function getSubModule($moduleID)
{
    if(isset($this->SubModules[$moduleID])){
        return $this->SubModules[$moduleID];
    } else {
        $submodule_elements = $this->_map->selectChildrenOfFirst('SubModules', null, null);
        if(count($submodule_elements) > 0){
            foreach($submodule_elements as $submodule_element){
                if($submodule_element->attributes['moduleID']){
                    $submodule =& $submodule_element->createObject($this->ModuleID);
                    break;
                }
            }
        }
        if(empty($submodule)){
            return false;
        } else {
            return $submodule;
        }
    }
}



/**
 *  Returns the all the submodules, and loads them if not already loaded
 */
function getSubModules()
{
    if(count($this->SubModules) == 0){
        $this->loadSubModules();
    }
    return $this->SubModules;
}



/**
 *  Creates and loads all the submodules of the module
 */
function loadSubModules()
{
    $submodule_elements = $this->_map->selectChildrenOfFirst('SubModules', null, null);
    if(count($submodule_elements) > 0){
        foreach($submodule_elements as $submodule_element){
            $submodule = $submodule_element->createObject($this->ModuleID);
            $this->SubModules[$submodule_element->attributes['moduleID']] = $submodule;
            print "$debug_prefix Submodule {$submodule_element->attributes['moduleID']} parsed.\n";
        }

        //special for best practices: add the IsBestPractice SummaryField
        if(isset($this->SubModules['bpc'])){
            $this->useBestPractices = true;
            $recordIDField = end($this->PKFields);

            $field_object = MakeObject(
                $this->ModuleID,
                'IsBestPractice',
                'SummaryField',
                array(
                    'name' => 'IsBestPractice',
                    'type' => 'tinyint',
                    'summaryFunction' => 'count',
                    'summaryField' => 'BestPracticeID',
                    'summaryKey' => 'RelatedRecordID',
                    'summaryModuleID' => 'bpc',
                    'localKey' => $recordIDField,
                    'phrase' => 'Is Best Practice|Whether the associated record is a best practice'
                )
            );
print "best practice auto field";
print_r($field_object);
//die();
            $this->ModuleFields['IsBestPractice'] = $field_object;
        }

        //copies submodule conditions to summary fields
        foreach($this->ModuleFields as $fieldName => $field){
            if('summaryfield' == get_class($field)){
                if(!$field->isGlobal){

                    if(isset($this->SubModules[$field->summaryModuleID])){
                        $subModule =& $this->SubModules[$field->summaryModuleID];
                        if(count($subModule->conditions) > 0){
                            $field->conditions = $subModule->conditions;
                            $this->ModuleFields[$fieldName] = $field;
                        }
                    } else {
                        trigger_error("The summaryfield '{$field->name}' requires a '{$field->summaryModuleID}' submodule.", E_USER_ERROR);
                    }
                }
            }
        }
    }
}



/**
 *  Returns the screens of a module (and creates them if they're not yet created)
 */
function &getScreens()
{

    if(count($this->_screens) > 0){
        return $this->_screens;
    } else {
        $debug_prefix = debug_indent("Module-getScreens() {$this->ModuleID}:");

        $screens_element = $this->_map->selectFirstElement('Screens', NULL, NULL);

        if(!empty($screens_element) && count($screens_element->c) > 0){
            foreach($screens_element->c as $screen_element){
                $screen =& $screen_element->createObject($this->ModuleID);
                $this->_screens[$screen_element->name] =& $screen;

                switch(get_class($screen)){
                case 'viewscreen':
                    $this->viewScreen =& $screen;
                    break;
                case 'searchscreen':
                    $this->searchScreen =& $screen;
                    break;
                case 'listscreen':
                    $this->listScreen =& $screen;
                    break;
                default:
                    //do nothing
                }
                unset($screen);
            }
        }

        //temporary workaround until all modules have migrated from a ListFields section to a ListScreen
        $listfields_element = $this->_map->selectFirstElement('ListFields', null, null);
        if(!empty($listfields_element) && count($listfields_element->c) > 0){
            $listscreen_element = new Element('List', 'ListScreen', array('name' => 'List', 'phrase' => 'List'), $listfields_element->c);

            $listScreen =& $listscreen_element->createObject($this->ModuleID);

            $this->_screens[$listscreen_element->name] =& $listScreen;
            $this->listScreen =& $listScreen;

        }

        debug_unindent();
        return $this->_screens;
    }
//print_r($this->_screens);
}



/**
 *  Returns the a screen by name
 */
function &getScreen($name)
{
    print "getScreen: $name\n";
    if(isset($this->_screens[$name]) && count($this->_screens) > 0){
        return $this->_screens[$name];
    } else {
        if($screens = $this->getScreens()){
            return $screens[$name];
        } else {
            return false;
        }
    }
}



function getScreenOfType($type)
{
    $screens = $this->getScreens();

    if(count($this->_screens) > 0){
        foreach($this->_screens as $screen_name => $screen_object){
            if(get_class($screen_object) == $type){
                return $screen_object;
                break;
            }
        }
        print_r(array_keys($this->_screens));
        die("could not find a screen of type $type");
    } else {
        return null;
    }
}



function &getListFields()
{
    if(!empty($this->listScreen)){
        return $this->listScreen->Fields;
    } else {
        $listScreen =& $this->getScreen('List');
        return $listScreen->Fields;
    }
}



/**
 *  Returns the documentation of the module as an array (creates it if required)
 */
function getDocumentation()
{
    if(count($this->_documentation) > 0){
        return $this->_documentation;
    } else {
        $debug_prefix = debug_indent("Module-getDocumentation() {$this->ModuleID}:");

        $this->_documentation = array(
            'Introduction' => '',
            'Implementation' => '',
            'PeopleElements' => '',
            'DataCollection' => '',
            'DataInput' => '',
            'OrganizationalInfo' => '',
            'OrganizationalLearning' => '',
            'Training' => '',
            'ApplyKnowledge' => ''
            //'FAQ' = null
        );

        $documentation_element = $this->_map->selectFirstElement('Documentation', NULL, NULL);
        if(count($documentation_element->c) > 0){
            foreach($documentation_element->c as $docsection_element){
                $title = $docsection_element->attributes['title'];
                print "$debug_prefix generating section: $title\n";

                $content = '';

                //loop through contents
                foreach($docsection_element->c as $contentItem){

                    switch(get_class($contentItem)){
                    case 'element':
                        $content .= $contentItem->getContent();
                        break;
                    case 'characterdata':
                        $content .= $contentItem->content;
                        break;
                    default:
                        die("$debug_prefix unknown content type in documentation section");
                    }
                }

                $this->_documentation[$docsection_element->attributes['sectionID']] = array($docsection_element->attributes['title'], $content);
            }
        }

        debug_unindent();
        return $this->_documentation;
    }
}



/**
 *  Returns a list of moduleIDs that belong to remote fields of the module
 *
 *  (Note slight misnomer: returns module IDs, not module objects)
 */
function getRemoteModules()
{
    $remoteModules = array();
    foreach($this->ModuleFields as $mfName => $mf){
        if('remotefield' == get_class($mf)){
            $remoteModules[$mfName] = $mf->remoteModuleID;
        }
    }
    return $remoteModules;
}




//**************************************//
//        SQL-related functions         //
//**************************************//

/**
 *  Saves suggested but potentially destructive SQL changes in a text file 
 *
 *  Rather than directly applying DROP COLUMN and data type changes directly, 
 *  this function simply logs those changes to the dbChanges.sql file. 
 */
function appendSQLChangeFile($alterSQL)
{

    static $overwrite = true;
    if($overwrite){
        $writemode = 'w';
        $overwrite = false;
    } else {
        $writemode = 'a';
    }

    $outFile = GEN_LOG_PATH . '/'.$this->ModuleID.'_dbChanges.sql';
    $alterSQL .= "-- statement separator --\n";
    $alterSQL = str_replace("\n", "\r\n", $alterSQL);
    $fh = fopen($outFile, $writemode);
    fwrite($fh, $alterSQL);
    fclose($fh);

    return true;
}



/**
 *  Returns whether the database contains the module table already
 */
function checkTableExists($tableName)
{
    $SQL = "SHOW TABLES LIKE '$tableName';";
    //$Result = $dbh->query($SQL);
    global $dbh;
    $r = $dbh->getCol($SQL);
    dbErrorCheck($r);

    if (in_array($tableName, $r)){
        return true;
    } else {
        return false;
    }
}



/**
 *  see whether existing table needs to be updated
 */
function checkTableStructure($tableName)
{
    $debug_prefix = debug_indent("Module-checkTableStructure() {$this->ModuleID}:");
    
    $FieldsToUpdate = array(); //key = field name, value = instruction
    $NewFields = array(); //key = field name, value = create stmt
    $RemovedFields = array(); //value = field name (no automatic column drop)
    $UpdatePK = false; //if set to true, drops and recreates the Primary Key
    $UpdateIndexes = false; //if set to true, causes to check the index structure,
    //                          and add or rebuild indexes as needed

    $dbFormat = new DBFormat($targetDB);  //object that provides db-specific data type changes
    $dataTypes = $dbFormat->dataTypes;
/*
    //remove obsolete SQL Change file
    $sql_change_file = GEN_LOG_PATH . '/'.$this->ModuleID.'_dbChanges.sql';
    if(file_exists($sql_change_file)){
        unlink($sql_change_file);
    }
*/

    //get column info
    $SQL = "SHOW COLUMNS FROM `$tableName`;";
    global $dbh;
    $Columns = $dbh->getAll($SQL);
    dbErrorCheck($Columns);


    //print_r($Indexes);

    //the columns of the result set are:
    //1. Field Name
    //2. Data Type (w/ length)
    //3. Allow Null? (MySQL 4.1: "YES" or ""; MySQL 5 "YES" or "NO")
    //4. Key ("PRI" for Primary Key, "MUL" for index
    //5. Default
    //6. Extra ("auto_increment")

    //check that module table fields are in the existing table

    foreach($this->ModuleFields as $FieldName =>  $ModuleField){
        if (!is_object($ModuleField)){
            print $debug_prefix .' '. get_class($this) .' '. $this->ModuleID . "\n";
            print ("$debug_prefix ModuleField $FieldName is empty. where did it come from??\n");
        } else {
            if (get_class($ModuleField) == 'tablefield'){
                printd("$debug_prefix Defined Field: " . $ModuleField->name . "\n", 2);
                printd( "$debug_prefix    Data Type:" . $ModuleField->dataType . "\n", 2);
                printd( "$debug_prefix    DB Flags:" . $ModuleField->dbFlags . "\n", 2);

                //check if the field is part of the primary key
                if (in_array($ModuleField->name, $this->PKFields)){
                    printd( "$debug_prefix    PRIMARY KEY FIELD\n", 2);
                }

                //check if the field is part of an index
                foreach($this->Indexes as $IxName => $Index){
                    if (in_array($ModuleField->name, $Index)){
                        printd( "$debug_prefix    INDEXED in $IxName\n", 2);
                    }
                }


                //find a matching column in existing table
                $match = false;
                foreach($Columns as $Col){
                    if($Col[0] == $ModuleField->name){
                        $ExistingField = $Col;
                        $match = true;
                    }
                }
                if ($match){
                    printd( "$debug_prefix Table Field: $ExistingField[0]\n", 2);
                    printd( "$debug_prefix    Data Type :$ExistingField[1]\n", 2);
                    printd( "$debug_prefix    Allow Null:$ExistingField[2]\n", 2);
                    printd( "$debug_prefix    Key       :$ExistingField[3]\n", 2);
                    printd( "$debug_prefix    Default   :$ExistingField[4]\n", 2);
                    printd( "$debug_prefix    Extra     :$ExistingField[5]\n", 2);


                    //check if update is needed:
                    $NeedUpdate = false;
                    //generic field def - takes care of changing: data type, allow null, default, auto_increment
                    $FieldDef = "MODIFY COLUMN {$ModuleField->name} {$dataTypes[$ModuleField->dataType]} {$ModuleField->dbFlags}";
                    //1. check data type
                    switch ($ExistingField[1]) {
                    case 'int(10) unsigned':
                        if($ModuleField->dataType == 'int'){
                            if(false !== strpos($ModuleField->dbFlags, 'unsigned')){
                                printd( "$debug_prefix OK: verified data type\n", 2);
                            } else {
                                $NeedUpdate = true;
                                $FieldsToUpdate[$ExistingField[0]]['dataType'] = "--change data type to signed int\n$FieldDef";
                                printd( "$debug_prefix UP: data type need change to signed int\n", 2);
                            }
                        } else {
                            $NeedUpdate = true;
                            printd( "$debug_prefix UP: data type need change to {$ModuleField->dataType}\n", 2);
                            $FieldsToUpdate[$ExistingField[0]]['dataType'] = "--change data type to {$ModuleField->dataType}\n$FieldDef";
                        }
                        break;
                    case 'int(11)':
                        if($ModuleField->dataType == 'int'){
                            if(false === strpos($ModuleField->dbFlags, 'unsigned')){
                                printd( "$debug_prefix OK: verified data type\n", 2);
                            } else {
                                $NeedUpdate = true;
                                printd( "$debug_prefix UP: data type need change to unsigned int\n", 2);
                                $FieldsToUpdate[$ExistingField[0]]['dataType'] = "--change data type to unsigned int\n$FieldDef";

                            }
                        } else {
                            $NeedUpdate = true;
                            printd( "$debug_prefix UP: data type need change to {$ModuleField->dataType}\n", 2);
                            $FieldsToUpdate[$ExistingField[0]]['dataType'] = "--change data type to {$ModuleField->dataType}\n$FieldDef";
                        }
                        break;
                    case 'tinyint(3) unsigned':
                        if($ModuleField->dataType == 'tinyint'){
                            if(false !== strpos($ModuleField->dbFlags, 'unsigned')){
                                printd( "$debug_prefix OK: verified data type\n", 2);
                            } else {
                                $NeedUpdate = true;
                                $FieldsToUpdate[$ExistingField[0]]['dataType'] = "--change data type to signed tinyint\n$FieldDef";
                                printd( "$debug_prefix UP: data type need change to signed tinyint\n", 2);
                            }
                        } else {
                            $NeedUpdate = true;
                            printd( "$debug_prefix UP: data type need change to {$ModuleField->dataType}\n", 2);
                            $FieldsToUpdate[$ExistingField[0]]['dataType'] = "--change data type to {$ModuleField->dataType}\n$FieldDef";
                        }
                        break;
                    case 'tinyint(1)';
                        if(in_array($ModuleField->dataType, array('tinyint', 'bool'))){
                            if(false === strpos($ModuleField->dbFlags, 'unsigned')){
                                printd( "$debug_prefix OK: verified data type\n", 2);
                            } else {
                                $NeedUpdate = true;
                                printd( "$debug_prefix UP: data type need change to unsigned tinyint\n", 2);
                                $FieldsToUpdate[$ExistingField[0]]['dataType'] = "--change data type to unsigned tinyint\n$FieldDef";

                            }
                            printd( "$debug_prefix OK: verified data type bool = tinyint(1)\n", 2);
                        } else {
                            $NeedUpdate = true;
                            printd( "$debug_prefix UP: data type need change to {$ModuleField->dataType}\n", 2);
                            $FieldsToUpdate[$ExistingField[0]]['dataType'] = "--change data type to {$ModuleField->dataType}\n$FieldDef";
                        }
                        break;
                    case 'decimal(12,4)';
                        if($ModuleField->dataType == 'money'){
                                printd( "$debug_prefix OK: verified data type money = decimal(12,4)\n", 2);
                        } else {
                            $NeedUpdate = true;
                            printd( "$debug_prefix UP: data type need change to {$ModuleField->dataType}\n", 2);
                            $FieldsToUpdate[$ExistingField[0]]['dataType'] = "--change data type to {$ModuleField->dataType}\n$FieldDef";
                        }
                        break;
                    case 'decimal(12,2)';
                        if($ModuleField->dataType == 'decimal(2)'){
                                printd( "$debug_prefix OK: verified data type decimal(2) = decimal(12,2)\n", 2);
                        } else {
                            $NeedUpdate = true;
                            printd( "$debug_prefix UP: data type need change to {$ModuleField->dataType}\n", 2);
                            $FieldsToUpdate[$ExistingField[0]]['dataType'] = "--change data type to {$ModuleField->dataType}\n$FieldDef";
                        }
                        break;
                    case 'float unsigned';
                        if($ModuleField->dataType == 'float'){
                            if(false !== strpos($ModuleField->dbFlags, 'unsigned')){
                                printd( "$debug_prefix OK: verified data type float = float unsigned\n", 2);
                            } else {
                                $NeedUpdate = true;
                                $FieldsToUpdate[$ExistingField[0]]['dataType'] = "--change data type to signed float\n$FieldDef";
                                printd( "$debug_prefix UP: data type need change to signed float\n", 2);
                            }
                        } else {
                            $NeedUpdate = true;
                            printd( "$debug_prefix UP: data type need change to {$ModuleField->dataType}\n", 2);
                            $FieldsToUpdate[$ExistingField[0]]['dataType'] = "--change data type to {$ModuleField->dataType}\n$FieldDef";
                        }
                        break;
                    //add more types as needed
                        
                    default:
                        if ($ModuleField->dataType == $ExistingField[1]){
                            printd( "$debug_prefix OK: data type {$ModuleField->dataType} matches {$ExistingField[1]}\n", 2);
                        } else {
                            printd( "$debug_prefix XX: data type {$ModuleField->dataType} seems not to match {$ExistingField[1]}\n", 2);
                            $FieldsToUpdate[$ExistingField[0]]['dataType'] = "--change data type to {$ModuleField->dataType}\n$FieldDef";
                            $NeedUpdate = true;
                        }

                        break;
                    }

                    //2. check allow null
                    switch ($ExistingField[2]) {
                    case '': //does not allow NULL
                    case 'NO':
                        if (false !== strpos($ModuleField->dbFlags, 'not null')){
                            printd( "$debug_prefix OK: verified disallow NULL\n", 2);
                        } else {
                            $NeedUpdate = true;
                            printd( "$debug_prefix UP: need to allow NULL\n", 2);
                            $FieldsToUpdate[$ExistingField[0]]['allownull'] = "--allow NULL\n$FieldDef";
                        }
                        break;
                    case 'YES': //allows NULL
                        if (false === strpos($ModuleField->dbFlags, 'not null')){
                            printd( "$debug_prefix OK: verified ALLOW NULL\n", 2);
                        } else {
                            $NeedUpdate = true;
                            printd( "$debug_prefix UP: need to disallow NULL\n", 2);
                            $FieldsToUpdate[$ExistingField[0]]['allownull'] = "--disallow NULL\n$FieldDef";
                        }

                        break;
                    default:
                        printd( "$debug_prefix XX: ALLOW NULL had an unexpected value '$ExistingField[2]'\n", 2);
                        $NeedUpdate = true;
                        $FieldsToUpdate[$ExistingField[0]]['allownull'] = "--ERROR: allow null had an unexpected value\n$FieldDef";
                        break;
                    }


                    //3. check key membership (fuzzy)

                    //preparation: gather some index data
                    $indexed = false;
                    $fieldInIndexes = array();
                    foreach($this->Indexes as $IxName => $Index){
                        if (in_array($ModuleField->name, $Index)){
                            $indexed = true;
                            $fieldInIndexes[] = $IxName;
                        }
                    }


                    switch ($ExistingField[3]) {
                    case '':    //neither primary key nor any index
                        //verify not added to PK
                        if (!in_array($ModuleField->name, $this->PKFields)){
                            printd( "$debug_prefix OK: verified not part of PK\n", 2);
                        } else {
                            printd( "$debug_prefix UP: need to add to PK\n", 2);
                            $UpdatePK = true;
                            $NeedUpdate = true;
                        }
                        //verify not added to any index
                        if (!$indexed){
                            printd( "$debug_prefix OK: verified not part of any index\n", 2);
                        } else {
                            foreach($fieldInIndexes as $IxName){
                                printd( "$debug_prefix UP: need to add to index $IxName\n", 2);
                            }
                            $UpdateIndexes = true;
                            $NeedUpdate = true;
                        }
                        break;
                    case 'PRI': //primary key
                        //verify part of PK
                        if (in_array($ModuleField->name, $this->PKFields)){
                            printd( "$debug_prefix OK: verified is part of PK\n", 2);
                        } else {
                            printd( "$debug_prefix UP: need to remove from PK\n", 2);
                            $UpdatePK = true;
                            $NeedUpdate = true;
                        }
                        //(cannot determine if should also be part of index)
                        $UpdateIndexes = true; //this will verify indexes and update if necessary
                        printd( "$debug_prefix NOTE: will verify indexes and update if necessary\n", 2);
                        break;
                    case 'MUL': //indexes
                    default:
                        //verify not added to PK
                        if (!in_array($ModuleField->name, $this->PKFields)){
                            printd( "$debug_prefix OK: verified not part of PK\n", 2);
                        } else {
                            printd( "$debug_prefix UP: need to add to PK\n", 2);
                            $UpdatePK = true;
                            $NeedUpdate = true;
                        }
                        //verify is part of an index
                        if ($indexed){
                            printd( "$debug_prefix OK: verified is part of an index\n", 2);
                            $UpdateIndexes = true;
                            printd( "$debug_prefix NOTE: will verify indexes and update if necessary\n", 2);
                        } else {
                            $UpdateIndexes = true;
                            printd( "$debug_prefix UP: need to remove from all indexes\n", 2);
                        }
                        break;
                    }



                    //4. check default
                    switch (trim($ExistingField[4])) {
                    /*case '':*/
                    case 'NULL':
                    case NULL:
                    case '0':
                    case '0000-00-00 00:00:00':
                        if (false === strpos($ModuleField->dbFlags, 'default')){
                            printd( "$debug_prefix OK: verified no default\n", 2);
                        } elseif( false !== strpos( strtolower($ModuleField->dbFlags), 'default '.trim($ExistingField[4]))) {
                            printd( "$debug_prefix OK: verified default match with explicit default\n", 2);
                        } else {
                            $default = strstr($ModuleField->dbFlags, 'default');
                            //remove 'default '
                            $default = str_replace('default ', '', $default);
                            if ($default != $ExistingField[4]) {
                                $NeedUpdate = true;
                                printd( "$debug_prefix UP: need to add default $default\n", 2);
                                $FieldsToUpdate[$ExistingField[0]]['default'] = "--add default $default\n$FieldDef";
                            } else {
                                printd( "$debug_prefix OK: verified default match\n", 2);
                            }
                        }
                        break;
                    default:
                        if (false !== strpos($ModuleField->dbFlags, 'default')){
                            //get the new default value
                            $default = strstr($ModuleField->dbFlags, 'default');
                            $default = str_replace('default ', '', $default);//remove 'default='
                            $default = str_replace('\'', '', $default);//remove single quotes
                            //compare the two default values
                            if ($default == $ExistingField[4]){
                                printd( "$debug_prefix OK: verified default $default\n", 2);
                            } elseif( false !== strpos( strtolower($ModuleField->dbFlags), 'default '.trim($ExistingField[4]))) {
                                printd( "$debug_prefix OK: verified default match with explicit default\n", 2);
                            } else {
                                $NeedUpdate = true;
                                printd( "$debug_prefix UP: need to change default from $ExistingField[4] to $default\n", 2);
                                $FieldsToUpdate[$ExistingField[0]]['default'] = "--change default from $ExistingField[4] to $default\n$FieldDef";
                            }
                        } else {
                            $NeedUpdate = true;
                            printd( "$debug_prefix UP: need to remove default\n", 2);
                            $FieldsToUpdate[$ExistingField[0]]['default'] = "--remove default '$ExistingField[4]'\n$FieldDef";
                        }
                        break;
                    }


                    //5. check 'extra'
                    switch ($ExistingField[5]) {
                    case '':
                        if (false === strpos($ModuleField->dbFlags, 'auto_increment')){
                            printd( "$debug_prefix OK: verified no auto_increment\n", 2);
                        } else {
                            //special case for log file: no field but _RecordID should have auto_increment
                            //trouble is, $ModuleField->dbFlags might still have it b/c it's the main table def
                            if (strpos($tableName, "_") > 0 && $ExistingField[0] != "_RecordID"){
                                printd( "$debug_prefix OK: log table should only have auto_increment on _RecordID\n", 2);
                            } else {
                                $NeedUpdate = true;
                                printd( "$debug_prefix UP: need to add auto_increment\n", 2);
                                $FieldsToUpdate[$ExistingField[0]]['extra'] = "--add auto_increment\n$FieldDef";
                            }
                        }
                        break;
                    case 'auto_increment':
                        if (false !== strpos($ModuleField->dbFlags, 'auto_increment')){
                            printd( "$debug_prefix OK: verified auto_increment\n", 2);
                        } else {
                            $NeedUpdate = true;
                            printd( "$debug_prefix UP: need to remove auto_increment\n", 2);
                            $FieldsToUpdate[$ExistingField[0]]['extra'] = "--remove auto_increment\n$FieldDef";
                        }
                        break;
                    default:
                        printd( "$debug_prefix XX: 'Extra' had an unexpected value '$ExistingField[5]'\n", 2);
                        $NeedUpdate = true;
                        $FieldsToUpdate[$ExistingField[0]]['extra'] = "--ERROR: 'Extra' had an unexpected value '$ExistingField[5]'\n$FieldDef";
                        break;
                    }

                    /*
                    if ($NeedUpdate){
                        $FieldsToUpdate[] = $ModuleField->name;
                    }
                    */

                } else {
                    $dbFormat = new DBFormat($targetDB); //object that contains DB-specific translations
                    printd( "$debug_prefix THIS IS A NEW FIELD\n", 2);
                    $UpdateIndexes = true;

                    $FieldDef = "ADD COLUMN {$ModuleField->name} ";
                    $FieldDef .= $dbFormat->dataTypes[$ModuleField->dataType];
                    if ($ModuleField->dbFlags) {
                        $FieldDef .= $dbFormat->dbFlags[$ModuleField->dbFlags];
                    }
                    printd( "$debug_prefix $FieldDef\n", 2);
                    $NewFields[$ModuleField->name] = $FieldDef;
                    unset($dbFormat);
                }
                printd( "\n\n", 2);

            }
        }
    }

    //check for removed fields (are in existing table but not in module def)
    foreach($Columns as $Col){
        $match = false;
        foreach($this->ModuleFields as $ModuleField){
            if (get_class($ModuleField) == 'tablefield'){
                if($Col[0] == $ModuleField->name){
                    $match = true;
                }
            }
        }
        if (!$match){
            //easy log-field checking: log field names begins w/ underscore
            if ($Col[0][0] != '_'){
                $RemovedFields[] = $Col[0];
                $UpdateIndexes = true;
                printd("$debug_prefix Field should be removed: $Col[0]\n", 2);
            }
        }
    }


    printd( "*******************************************\n", 1);
    printd( "    TABLE CHANGES SUMMARY FOR '$tableName':    \n", 1);
    printd( "*******************************************\n\n", 1);

    printd( "New columns:\n", 1);
    printd( "------------------------------------------\n", 1);
    if (count($NewFields)){
        foreach($NewFields as $field => $sql){
            printd( "$field: $sql\n", 1);
        }
    } else {
        printd( "(None)\n", 1);
    }

    printd( "\nColumns that need to be updated:\n", 1);
    printd( "------------------------------------------\n", 1);
    if (count($FieldsToUpdate)){
        //print_r($FieldsToUpdate);
        foreach($FieldsToUpdate as $field => $updates){
            printd( "$field:\n", 1);
            foreach($updates as $type => $sql){
                printd( "   $type:  $sql\n", 1);

                if (!isset($FieldUpdates)){
                    $FieldUpdates = array();
                }
                $FieldUpdates[$field] = $sql; //multiple updates do the same thing, actually (TODO: fix up update creation)
            }
        }
        $FieldUpdates = array_unique($FieldUpdates);//ensures updates aren't duplicated

        printd( "*** NOTE THAT COLUMNS MUST BE MANUALLY UPDATED!!! ***\n", 1);
        printd( "Verify that there will be no loss of important data\n", 1);
        printd( "and then execute the following SQL statement:\n", 1);

        $alterSQL  = "ALTER TABLE `$tableName`\n";
        $alterSQL .= implode(",\n", $FieldUpdates);
        $alterSQL .= ";\n\n";
        $this->appendSQLChangeFile($alterSQL);
        printd( $alterSQL, 1);

        printd( "*** END OF NOTE ***\n\n", 1);
    } else {
        printd( "(None)\n", 1);
    }
    printd( "\nColumns that should be removed:\n", 1);
    printd( "------------------------------------------\n", 1);
    if (count($RemovedFields)){
        foreach($RemovedFields as $field){
            printd( "   $field\n", 1);
        }
        printd( "*** NOTE THAT COLUMNS MUST BE MANUALLY REMOVED!!! ***\n", 1);
        printd( "Verify that there will be no loss of important data\n", 1);
        printd( "and then execute the following SQL statement:\n", 1);
        
        $alterSQL  = "ALTER TABLE `$tableName`\n   DROP COLUMN ";
        $alterSQL .= implode(",\n   DROP COLUMN ",$RemovedFields);
        $alterSQL .= ";\n\n";
        $this->appendSQLChangeFile($alterSQL);
        printd( $alterSQL, 1);
        
        printd( "*** END OF NOTE ***\n\n", 1);

    } else {
        printd( "(None)\n", 1);
    }

    //execute field changes: add fields
    //(modify and drop fields after updating indexes)
    if (count($NewFields)){
        $nLastNewField = count($NewFields) -1;
        $SQL  = "ALTER TABLE `$tableName`\n   ";
        $SQL .= implode(",\n   ", $NewFields);
        $SQL .= ";";

        printd("$SQL\n", 1);
        printd("Adding fields (executing):\n", 0);

        $result = $dbh->query($SQL);
        dbErrorCheck($result);
        printd( "Successfully added fields.\n", 0);
    }

    $UpdateIndexes = true;//debug: always check indexes (maybe leave it this way):
    if ($UpdatePK || $UpdateIndexes){
        //get current index & PK info from DB
        $SQL = "SHOW INDEX FROM `$tableName`;";
        $Indexes = $dbh->getAll($SQL);
        dbErrorCheck($Indexes);

        //print_r($Indexes);

        if ($UpdatePK){
            printd( "Will update primary key with the following statement:\n", 0);

            //drop existing primary key
            $SQL = "ALTER TABLE `$tableName`\n";
            $SQL .= "DROP PRIMARY KEY,\n";
            //create it again with the new definition:
            $SQL .= "ADD PRIMARY KEY ";
            $SQL .= "(" . implode(", ",  $this->PKFields) . ");";
            printd( $SQL . "\n", 0);


            printd( "Executing...\n", 0);
            $result = $dbh->query($SQL);
            dbErrorCheck($result);
            printd( "Successfully updated primary key.\n", 0);
        }
        if ($UpdateIndexes){
            printd( "Checking Indexes:\n", 1);

            //transform structure of $indexes array of existing indexes
            foreach($Indexes as $Index){

                if ($Index[2] != 'PRIMARY'){ //ignore PK fields
                    //add index name - clean off uniqifying table prefix from name
                    $ExistingIndexes[str_replace($tableName . '_', '', $Index[2])][] = $Index[4];
                    //print "Table Index name: $Index[2] Field name: $Index[4]\n";
                }
            }

            //loop through existing indexes and compare
            if (count($ExistingIndexes) > 0){
                foreach($ExistingIndexes as $ExistingIndexName => $ExistingIndex){
                    if (isset($this->Indexes[$ExistingIndexName])){
                        //matching index name - check if fields are the same, in order
                        $DefinedIndex = $this->Indexes[$ExistingIndexName];

                        //see if a field has been removed
                        foreach($ExistingIndex as $i => $IndexField){
                            if ($DefinedIndex[$i] == $ExistingIndex[$i]){
                                printd( "$i. ($ExistingIndexName) index field {$DefinedIndex[$i]} matches {$ExistingIndex[$i]}\n", 2);
                            } else {
                                printd( "$i. ($ExistingIndexName) index has changed: the field $ExistingIndex[$i] should be removed\n", 2);
                                $IndexesToDrop[] = $tableName . '_' . $ExistingIndexName;
                                $IndexesToAdd[] = $ExistingIndexName;
                            }
                        }

                        //see if a field has been added

                        foreach($DefinedIndex as $i => $IndexField){
                            if ($DefinedIndex[$i] == $ExistingIndex[$i]){
                                //matching index field, all is cool
                                printd( "$i. [$ExistingIndexName] index field {$DefinedIndex[$i]} matches {$ExistingIndex[$i]}\n", 2);
                            } else {
                                printd( "$i. [$ExistingIndexName] index has changed: the field $DefinedIndex[$i] should be added\n", 2);
                                $IndexesToDrop[] = $tableName . '_' . $ExistingIndexName;
                                $IndexesToAdd[] = $ExistingIndexName;
                            }
                        }
                    } else {
                        //no matching index name
                        printd( "index $ExistingIndexName should be dropped\n", 2);
                        $IndexesToDrop[] = $tableName . '_' . $ExistingIndexName;
                    }
                }
            }

            //loop through defined indexes and see if there are new ones
            printd( "checking for new indexes:\n", 1);
            foreach($this->Indexes as $DefinedIndexName => $DefinedIndex){
                if (isset($ExistingIndexes[$DefinedIndexName])){
                    printd( "checked: index $DefinedIndexName exists\n", 2);
                } else {
                    printd( "index $DefinedIndexName must be created\n", 1);
                    $IndexesToAdd[] = $DefinedIndexName;
                }
            }

            if (count($IndexesToDrop) > 0){
                printd( "Dropping indexes:\n", 1);

                $SQL = "ALTER TABLE `$tableName`\nDROP INDEX ";
                $SQL .= "" . implode(",\nDROP INDEX ",  $IndexesToDrop) . ";";
                printd( $SQL . "\n", 1);


                printd( "Executing...\n", 1);
                $result = $dbh->query($SQL);
                dbErrorCheck($result);
                printd( "Successfully dropped existing indexes.\n", 1);

            }
            if (count($IndexesToAdd) > 0){
                printd( "Adding indexes:\n", 1);
                $nLastIndex = count($IndexesToAdd) -1;
                $SQL = "ALTER TABLE `$tableName`\n";
                foreach($IndexesToAdd as $i => $IndexToAdd){
                    $SQL .= "ADD INDEX {$tableName}_{$IndexToAdd} (";
                    $SQL .= implode(', ', $this->Indexes[$IndexToAdd]);
                    if ($i < $nLastIndex){
                        $SQL .= "),\n"; //add comma except for the last index
                    }
                }
                $SQL .= ");";
                printd( $SQL . "\n", 1);


                printd( "Executing...\n", 1);
                $result = $dbh->query($SQL);
                dbErrorCheck($result);
                printd( "Successfully created new indexes.\n", 1);

            }
        }
    }


    debug_unindent();
    return $Columns;
}



/**
 *  actually create table:
 */
function createTable($createLogTable)
{
    $tableName = $this->ModuleID;
    if($createLogTable){
        $tableName .= '_l';
    }
    printd( "Generating CREATE TABLE statement for $tableName table:\n", 1);
    $SQL = $this->generateCreateTableSQL(DB_TYPE, $createLogTable);
    $SQL = trim($SQL); //trailing white space may create errors
    printd( $SQL, 1);

    printd( "Executing...\n", 1);
    global $dbh;
    $result = $dbh->query($SQL);
    dbErrorCheck($result);
    printd( "Successfully created $tableName table\n", 1);

    if ($this->checkTableExists($tableName)){
        return true;
    } else {
        return false;
    }
}



/**
 *  generate the create table statement(s)
 */
function generateCreateTableSQL($targetDB = 'MySQL', $makeLogTable = true)
{
    if (!$makeLogTable){
        //create the main table statement
        $SQL = $this->_generateBasicCreateTable(
            $targetDB,
            $this->ModuleID,
            $this->ModuleFields,
            $this->PKFields,
            $this->Indexes
        );

    } else { //make changes from basic structure for audit trail table
        //table name for the log table is Module ID + "_l"
        $logTableName = $this->ModuleID . '_l';

        //copy module table fields to a local array
        $logTableFields = $this->ModuleFields;

        //copy primary key fields to a local array
        $logPKfields = $this->PKFields;

        //copy indexes to a local array
        $logIndexes = $this->Indexes;

        //remove 'auto_increment' from any primary key field
        foreach($logPKfields as $value){
            $logTableFields[$value]->dbFlags =
                str_replace('auto_increment', '',
                    $logTableFields[$value]->dbFlags
                );
        }

        //prepend a special recordID to the beginning of the array
        $logPKfield = new TableField (
            '_RecordID',
            'int',
            '',
            'unsigned not null auto_increment',
            'LogID',
            '',
            '',
            $this->ModuleID
            );
        array_unshift($logTableFields, $logPKfield);

        //also prepend it to the primary key list
        array_unshift($logPKfields, '_RecordID');

        //just some white space padding
        $SQL .= "\n\n";

        //build the CREATE TABLE statement
        $SQL .= $this->_generateBasicCreateTable(
            $targetDB,
            $logTableName,
            $logTableFields,
            $logPKfields,
            $logIndexes
        );

        //dispose of the log table array
        unset($logTableFields);
    }
    return $SQL;
}



/**
 *  "private" function to generate a CREATE TABLE statement
 */
function _generateBasicCreateTable($targetDB, $tableName, &$tableFields, $PKFields, $Indexes)
{
    $dbFormat = new DBFormat($targetDB); //object that contains DB-specific translations
    //print_r ($dbFormat);

    //fix up index names (need to be unique in at least MS SQL)
    //not so good coding practice for performance but items are few in these arrays
    foreach($Indexes as $key => $value){
        $IndexNames["{$tableName}_{$key}"] = $value;
    }

    //start building the statement
    $SQL = "CREATE TABLE `$tableName` (\n";
    //  print($SQL);
    //  print_r($tableFields);
    //add the fields
    foreach($tableFields as $vName => $value){
        if(!is_object($value)){
            print_r($tableFields);
            die("m. _generateBasicCreateTable: Field $vName is not a valid ModuleField.");
        } else {
            if (get_class($value) == 'tablefield'){
                $SQL .= "   {$value->name} {$dbFormat->dataTypes[$value->dataType]}";
                if (!empty($value->dbFlags)){
                    $flag = $value->dbFlags;
                    foreach($dbFormat->flags as $k=>$v){
                        $flag = str_replace($k, $v, $flag);
                    }
                    $SQL .= " $flag";
                }
                $SQL .= ",\n";

            }
        }

    }

    //add primary key definiton
    $SQL .= "   {$dbFormat->PKDeclaration}(\n      ";
    $SQL .= implode(",\n      ",  $PKFields);
    $SQL .= "\n   )";

    //add MySQL indexes:
    //within the CREATE TABLE statement
    if ($targetDB == 'MySQL'){
        //$SQL .= ",\n";
        if (count($IndexNames) > 0){
            foreach($IndexNames as $key => $value){
                $SQL .= ",\n   INDEX $key (\n      ";
                $SQL .= implode(",\n      ",  $value);
                $SQL .= "\n   )";
            }
        }
    }

    //close the statement
    if ($targetDB == 'MySQL'){
        $SQL .= "\n) TYPE=InnoDB;\n"; //using transacion-capable tables
    } else {
        $SQL .= "\n);\n";
    }

    //add MS SQL Server indexes:
    //with separate CREATE INDEX statements
    if ($targetDB == 'MSSQL'){
        if (count($IndexNames) > 0){
            foreach($IndexNames as $key => $value){
                $SQL .= "CREATE INDEX $key ON `$tableName` (\n   ";
                $SQL .= implode(",\n   ",  $value);
                $SQL .= "\n);\n";
            }
        }
    }

    unset($dbFormat);

    return $SQL;
}



/**
 *  Returns the SQL filter for permissions
 */
function getOwnerFieldFilter()
{
    if(empty($this->OwnerField)){
        return '';
    }
    $ownerMF = $this->ModuleFields[$this->OwnerField];

    if(empty($ownerMF)){
        print "ModuleField names: ({$this->ModuleID})\n";
        print_r(array_keys($this->ModuleFields));
        trigger_error("Module->getOwnerFieldFilter found an empty/invalid Owner Field named {$this->OwnerField}.", E_USER_ERROR);
    }

    global $SQLBaseModuleID;
    $SQLBaseModuleID = $this->ModuleID;
    $ownerFieldFilter = $ownerMF->getQualifiedName($this->ModuleID) . ' IN (%s)';
    return $ownerFieldFilter;
}













function _prepareConditions($conditions, $pExtendingModuleID = null)
{
    $debug_prefix = debug_indent("Module-_prepareConditions() {$this->ModuleID}:");
    print "$debug_prefix\n";
    $parentRecordConditions = array();
    $whereConditions = array();
    $protectJoinAliases = array();

    $use_parent = true;
    if(empty($parentModuleID)){
        if(empty($this->parentModuleID)){
            $use_parent = false;
        } else {
            $parentModuleID = $this->parentModuleID;
        }
    }

    if($use_parent){
        $parentModule =& GetModule($parentModuleID);
        $parentPK = end($parentModule->PKFields);
    }

    if(! empty($pExtendingModuleID )){
        print "extending module conditions: $pExtendingModuleID, {$this->ModuleID}\n";
        $extendingModule = GetModule($pExtendingModuleID);
        if(!empty( $extendingModule->extendsModuleFilterField )){
            $conditions[$extendingModule->extendsModuleFilterField] = $extendingModule->extendsModuleFilterValue;
            print "added extended condition:\n";
            print_r($conditions);
        }
    }

    if(count($conditions) > 0){
        foreach($conditions as $conditionField => $conditionValue){
            print "$debug_prefix Condition $conditionField => $conditionValue\n";

            $conditionModuleField = $this->ModuleFields[$conditionField];
            if(empty($conditionModuleField)){
                die("field {$this->ModuleID}.$conditionModuleField is empty\n");
            }
            $qualConditionField = $conditionModuleField->getQualifiedName($this->ModuleID);
            $conditionJoins = $conditionModuleField->makeJoinDef($this->ModuleID);
            if(count($conditionJoins) > 0){
                foreach(array_keys($conditionJoins) as $conditionJoinAlias){
                    $protectJoinAliases[] = $conditionJoinAlias;
                }
            }

            if($use_parent){
                if(preg_match('/\[\*([\w]+)\*\]/', $conditionValue, $match[1])){
                    if($match[1][1] == $parentPK){
                        $whereConditions[$qualConditionField] = '/**RecordID**/';
                    } else {
                        print "SM found match $match[1][0]\n";
                        $parentRecordConditions[$qualConditionField] = $match[1][1];
                    }
                } else {
                    $whereConditions[$qualConditionField] = $conditionValue;
                }
            } else {
                $whereConditions[$qualConditionField] = $conditionValue;
            }
        }
    }

    if($use_parent){
        $needParentSubselect = false;
        if(count($parentRecordConditions) > 0){
            foreach($parentRecordConditions as $localConditionField => $parentConditionField){
                $parentModuleField = $parentModule->ModuleFields[$parentConditionField];
                if(empty($parentModuleField)){
                    die("field {$this->parentModuleID}.$parentConditionField is empty\n");
                }
                if(get_class($parentModuleField) != 'tablefield'){
                    $needParentSubselect = true;
                }
            }
            if($needParentSubselect){
                $parentJoins = array();
                $parentSelects = array();
                foreach($parentRecordConditions as $localConditionField => $parentConditionField){
                    $parentModuleField = $parentModule->ModuleFields[$parentConditionField];
                    $parentSelects[] = $parentModuleField->makeSelectDef('iparent');
                    $parentJoins = array_merge($parentJoins, $parentModuleField->makeJoinDef('iparent'));
                }

                $subSQL = 'SELECT ';
                $subSQL .= implode(",\n", $parentSelects);
                $subSQL .= "\nFROM `{$this->parentModuleID}` AS `iparent`\n";
                $parentJoins = SortJoins($parentJoins);
                $subSQL .= implode("\n   ", $parentJoins);
                $subSQL .= "\nWHERE `iparent`.$parentPK = '/**RecordID**/' \n";

                $SQL = "\n INNER JOIN ($subSQL) AS `parent` ON ( ";
                $parentConditionStrings = array();
                foreach($parentRecordConditions as $localConditionField => $parentConditionField){
                    $parentConditionStrings[] = "\n `parent`.$parentConditionField = $localConditionField";
                }
                $SQL .= implode("\n AND ", $parentConditionStrings);
                $SQL .= ")\n";
            } else {
                foreach($parentRecordConditions as $localConditionField => $parentConditionField){
                    $parentModuleField = $parentModule->ModuleFields[$parentConditionField];
                    $qualParentConditionField = $parentModuleField->getQualifiedName('parent');
                    $SQL = "\n INNER JOIN `{$this->parentModuleID}` AS `parent` ON $localConditionField = $qualParentConditionField";
                }
                $whereConditions["`parent`.$parentPK"] = '/**RecordID**/';
            }

        }
    }

    debug_unindent();
    return array(
        'parentJoinSQL' => $SQL,
        'whereConditions' => $whereConditions,
        'protectJoinAliases' => $protectJoinAliases
    );
}


/**
 *  generates the Count SQL statement
 */
function generateListCountSQL($Grid, $pExtendingModuleID = null)
{
    $debug_prefix = debug_indent("Module-generateListCountSQL() {$this->ModuleID}:");
    $extended = false;

    $Fields =& $Grid->Fields;
    if(! empty($pExtendingModuleID )){
        $extended = true;
    }

    if(!empty($this->extendsModuleID)){
        if($Grid->listExtended && $this->ModuleID == $Grid->moduleID){

            print "$debug_prefix extending {$this->extendsModuleID} with {$this->ModuleID} ({$Grid->moduleID})\n";
            $extendedModule = GetExtendedModule($this->extendsModuleID, $this->ModuleID);

            debug_unindent();
            //call same function for the extended module
            return $extendedModule->generateListCountSQL($Grid, $this->ModuleID);
        }
    }

    global $SQLBaseModuleID;
    $SQLBaseModuleID = $this->ModuleID;

    //array of fields in the SELECT statement (just to be able to use 'implode')
    $Joins = array();

    //ensuring that OwnerField is included
    if(!empty($this->OwnerField)){
        if(! array_key_exists($this->OwnerField, $Fields)){
            $ownerMF = $this->ModuleFields[$this->OwnerField];
            $Joins = array_merge($Joins, GetJoinDef($ownerMF->name));
        }
    }

    $conditions = array_merge($this->conditions, $Grid->conditions);

    if(!empty($this->localKey)){
        if(!empty($this->parentKey)){
            $conditions[$this->localKey] = '[*'.$this->parentKey.'*]';
        } else {
            die("Found non-empty submodule local key but no corresponding parent key.");
        }
    }

    if(count($conditions) > 0){
        foreach($conditions as $conditionField => $conditionValue){
            if(!array_key_exists($conditionField, $Fields)){
                $conditionMF = $this->ModuleFields[$conditionField];
                if('tablefield' != get_class($conditionMF)){
                    //$Joins = array_merge($Joins, $conditionMF->makeJoinDef($this->ModuleID));
                    $Joins = array_merge($Joins, GetJoinDef($conditionMF->name));
                }
            }
        }
    }

    $SQL = "SELECT \n   ";
    foreach($Fields as $fName => $field){

        print "$debug_prefix field: $fName\n";

        //look up corresponding ModuleField based on list field name
        $mf = $this->ModuleFields[$fName];
        if(!is_object($mf)){
            die("$debug_prefix Field $fName is not a valid ModuleField.");
        }
        //$Joins = array_merge($Joins, $mf->makeJoinDef($this->ModuleID));
        $Joins = array_merge($Joins, GetJoinDef($mf->name));

        print "$debug_prefix Adding JoinDef of ".get_class($mf)." {$field->name}\n";
    }
    $SQL .= " count(*) ";
    $SQL .= "\nFROM `{$this->ModuleID}`\n   ";

    $Joins = SortJoins($Joins);

    $prepared_conditions = $this->_prepareConditions($conditions, $pExtendingModuleID);
    $parentJoinSQL = $prepared_conditions['parentJoinSQL'];
    $whereConditions = $prepared_conditions['whereConditions'];
    $protectJoinAliases = $prepared_conditions['protectJoinAliases'];

    foreach($Joins as $key => $join){
        if(!in_array($key, $protectJoinAliases)){
            $joinStr = substr($join, 0, 20);
            if(false !== strpos($joinStr, 'OUTER JOIN')){
                print "$debug_prefix unsetting uneeded join '$key'\n";
                unset($Joins[$key]); //a COUNT(*) statement isn't usually affected by outer joins
            }
        }
    }

    $SQL .= implode("\n   ", $Joins);

    if(!empty($parentJoinSQL)){
        $SQL .= $parentJoinSQL;
    }

    $SQL .= "\nWHERE {$this->ModuleID}._Deleted = 0\n   ";
    foreach($whereConditions as $whereCondition => $whereConditionValue){
        if(false === strpos($whereConditionValue, '|')){
            $SQL .= "AND $whereCondition = '$whereConditionValue'\n";
        } else {
            $whereConditionValues = explode('|', $whereConditionValue);
            $whereConditionString = join('\',\'', $whereConditionValues);
            $SQL .= "AND $whereCondition IN ('$whereConditionString')\n";
        }
    }

    //close the statement
    $SQL .= ""; //NO ENDING NEWLINE - that will cause error messages...

    print "$debug_prefix list count SQL:\n";
    print $SQL."\n";

    //verify that the SQL statement will execute without error
    CheckSQL($SQL);

    debug_unindent();
    return $SQL;
}


function generateListSQL($Grid, $pExtendingModuleID = null)
{
    $debug_prefix = debug_indent("Module-generateListSQL() {$this->ModuleID}:");
    print "$debug_prefix pExtendingModuleID = '$pExtendingModuleID'\n";

    $extended = false;
    $listModuleID = $this->ModuleID;

    if(! empty($pExtendingModuleID )){
        $extended = true;
    }

    switch(get_class($Grid)){
    case 'uploadgrid':
        if($Grid->hasGridForm){
            //make sure the form fields are used over view list fields (duplicate field names in the latter array overwrites fields from the former)
            $Fields = array_merge($Grid->Fields, $Grid->FormFields);

        } else {
            $Fields = $Grid->Fields;
        }
        break;
    case 'selectgrid':
    case 'searchselectgrid':
    case 'codeselectgrid':
        return '';
        break;
    case 'permissiongrid';
        return $Grid->listSQL;
        break;
    default:
        $Fields = $Grid->Fields;
    }

    print "$debug_prefix grid Fields\n";
    indent_print_r(array_keys($Fields));

    if(!empty($this->extendsModuleID)){
        if($Grid->listExtended && $this->ModuleID == $Grid->moduleID){

            print "$debug_prefix extending the module {$this->extendsModuleID} with {$this->ModuleID} ({$Grid->moduleID})\n";
            $extendedModule = GetExtendedModule($this->extendsModuleID, $this->ModuleID);
            print "extended module is a ".get_class($extendedModule)."\n";
            //call same function for the extended module
            debug_unindent();
            return $extendedModule->generateListSQL($Grid, $this->ModuleID);

        }
    }

    global $SQLBaseModuleID;
    $SQLBaseModuleID = $listModuleID;

    print "$debug_prefix listModuleID = $listModuleID\n";

    //array of fields in the SELECT statement
    $SelectFieldNames = array();

    //ensure that OwnerField is included
    if(!empty($this->OwnerField)){
        if(! array_key_exists($this->OwnerField, $Fields)){
            $SelectFieldNames[$this->OwnerField] = true;
        }
    }

    //ensure that local key is in the conditions
    $conditions = array_merge($this->conditions, $Grid->conditions);

    if(!empty($this->localKey)){
        if(!empty($this->parentKey)){
            $conditions[$this->localKey] = '[*'.$this->parentKey.'*]';
        } else {
            die("Found non-empty submodule local key but no corresponding parent key.");
        }
    }

    //ensures that local condition field is in the SELECT fields
    if(count($conditions) > 0){
        foreach($conditions as $conditionField => $conditionValue){
            if(!array_key_exists($conditionField, $Fields)){
                $conditionMF = $this->ModuleFields[$conditionField];
                if('tablefield' != get_class($conditionMF)){
                    if(!array_key_exists($conditionField, $Fields)){
                        $SelectFieldNames[$conditionField] = false;
                    }
                }
            }
        }
    }
    foreach($Fields as $fieldName => $field){

        //combo grid fields etc have several ModuleFields
        if($ListScreen){
            $SelectFieldNames[$fieldName] = true;
        } else {
            $SelectFieldNames = array_merge($SelectFieldNames, $field->getSelectFields());
        }


        if(!empty($field->linkField)){
            $SelectFieldNames[$field->linkField] = true;
        }
        if(!empty($field->formatField)){
            $SelectFieldNames[$field->formatField] = true;
        }

    }

    //make sure module rowID is the first column
    $rowIDFieldName = end($this->PKFields); //if there are more than one PK field, use the LAST
    if(reset(array_keys($SelectFieldNames)) != $rowIDFieldName){
        unset($SelectFieldNames[$rowIDFieldName]);
        $SelectFieldNames=array_merge(array($rowIDFieldName=>true),$SelectFieldNames);
    }

    //ensure that OwnerField is included
    if(!empty($this->OwnerField)){
        if(! in_array($this->OwnerField, $SelectFieldNames)){
            $SelectFieldNames[$this->OwnerField] = true;
        }
    }
    $Joins = array();
    foreach($SelectFieldNames as $SelectFieldName => $makeSelect){

        //look up corresponding ModuleField based on list field name
        $mf = $this->ModuleFields[$SelectFieldName];
        if(!is_object($mf)){
            indent_print_r($this->ModuleFields, true, 'module fields');
            print "$debug_prefix SelectFieldNames:\n";
            indent_print_r($SelectFieldNames);
            die("$debug_prefix Field $SelectFieldName is not a valid ModuleField.");
        }

        //delegates the creation of select and from clauses to the module fields
        if($makeSelect){
            //$SelectFields[] = $mf->makeSelectDef($listModuleID, true);
            $SelectFields[] = GetSelectDef($mf->name);
        }
        //$Joins = array_merge($Joins, $mf->makeJoinDef($listModuleID));
        $Joins = array_merge($Joins, GetJoinDef($mf->name));

    }

    $prepared_conditions = $this->_prepareConditions($conditions, $pExtendingModuleID);
    $parentJoinSQL = $prepared_conditions['parentJoinSQL'];
    $whereConditions = $prepared_conditions['whereConditions'];

    $SQL = "SELECT \n   ";
    $SQL .= implode(",\n", $SelectFields);
    $SQL .= "\nFROM `{$this->ModuleID}`\n";

    //ensure that joins are sorted well
    $Joins = SortJoins($Joins);

    //adds joins
    $SQL .= implode("\n   ", $Joins);

    if(!empty($parentJoinSQL)){
        $SQL .= $parentJoinSQL;
    }
    $SQL .= "\nWHERE {$listModuleID}._Deleted = 0\n";

    foreach($whereConditions as $whereCondition => $whereConditionValue){
        if(false === strpos($whereConditionValue, '|')){
            $SQL .= "AND $whereCondition = '$whereConditionValue'\n";
        } else {
            $whereConditionValues = explode('|', $whereConditionValue);
            $whereConditionString = join('\',\'', $whereConditionValues);
            $SQL .= "AND $whereCondition IN ('$whereConditionString')\n";
        }
    }

    //verifies that the SQL statement will execute without error
    CheckSQL($SQL);

    $SQL .= "\n";

    print "$debug_prefix listSQL:\n";
    indent_print_r($SQL . "\n");

    debug_unindent();
    return $SQL;
}


    //generates the SQL statement used for the list in the CheckGrids and CodeCheckGrids
    function generateCheckListSQL($Grid)
    {
        //(for grids, this function is called on the associated SubModule)

        print "\n";
        print "primaryListField: {$Grid->primaryListField}\n";
        
        $primaryListField = $Grid->Fields[$Grid->primaryListField];
        //print_r($primaryListField);
        
        $t_ModuleFields = GetModuleFields($Grid->moduleID);
        $values_PKField = reset($t_ModuleFields);
        
        global $SQLBaseModuleID;
        $SQLBaseModuleID = $Grid->moduleID;
        
        $listAlias = GetTableAlias($t_ModuleFields[$Grid->primaryListField], $Grid->moduleID);
        print "listAlias: $listAlias\n";
        
        $list_ModuleField = $t_ModuleFields[$Grid->primaryListField];
        $list_PKField = $list_ModuleField->foreignKey;
        print "list_PKField: $list_PKField\n";
        //die();
        
        $Fields = $Grid->Fields;

        

        //array of fields in the SELECT statement (just to be able to use 'implode')
        $SelectFields = array();
        $ValueConditions = array();

        $ValueConditions[] = $Grid->moduleID . '._Deleted = 0';

        $SQL = "SELECT \n   ";
        //add PK of list module, to be used as insert keys
        $SelectFields[] = "$listAlias.$list_PKField AS RowID";

        foreach($Fields as $value){
            if('Checked' != $value->name){ //allow special field Checked to pass
                //combo grid fields etc have several ModuleFields
                $SelectFieldNames = $value->getSelectFields();
    
                if(!empty($value->linkField)){
                    $SelectFieldNames[] = $value->linkField;
                }
                
                foreach($SelectFieldNames as $SelectFieldName){
                    //look up corresponding ModuleField based on list field name
                    $mf = $this->ModuleFields[$SelectFieldName];
                    if(!is_object($mf)){
                        print_r($this->ModuleFields);
                        die("m. generateCheckListSQL: Field $SelectFieldName is not a valid ModuleField.");
                    }
                    
                    //delegate the creation of select and from clauses to the module fields
                    //$SelectFields[] = $mf->makeSelectDef($this->ModuleID);
                    $SelectFields[] = GetSelectDef($mf->name);
                    //$Joins = array_merge($Joins, $mf->makeJoinDef($listModuleID));
                    $Joins = array_merge($Joins, GetJoinDef($mf->name));
                    
                } //foreach
                
                $mf = $this->ModuleFields[$value->name];
                print "m. Adding ".get_class($mf)." {$value->name}\n";
            
            } else {
                $SelectFields[] = "IF({$Grid->moduleID}.{$values_PKField->name} IS NOT NULL, $listAlias.$list_PKField, NULL) AS Checked";
            }
        }

        $SQL .= implode(",\n   ", $SelectFields);

        
            
        $listJoin = $Joins[$listAlias];
        $copy_start = strpos($listJoin, ' JOIN ') + 6;
        $copy_end = strpos($listJoin, 'ON ');
        $copy_length = $copy_end - $copy_start-1;
        $listTableAndAlias = trim(substr($listJoin, $copy_start, $copy_length));
        print "copy_start: $copy_start\n";
        print "copy_end: $copy_end\n";
        print "copy_length: $copy_length\n";
        print "listTableAndAlias: $listTableAndAlias\n";
        
        //swap list table and alias with module ID 
        $listJoin = str_replace($listTableAndAlias, $this->ModuleID.' ', $listJoin);
        //append value conditions
        if(count($ValueConditions) > 0){
            $listJoin = str_replace(')', ' AND '.join(' AND ', $ValueConditions).' /*ValueConditions*/)', $listJoin);
        } else {
            $listJoin = str_replace(')', ' /*ValueConditions*/)', $listJoin);
        }
        $Joins[$listAlias] = $listJoin;
        
        //print_r($Joins);
        //die();

        //$SQL .= "\nFROM {$this->ModuleID}\n   ";
        $SQL .= "\nFROM $listTableAndAlias\n  ";
        
        $Joins = SortJoins($Joins);
        
        //add joins here
        $SQL .= implode("\n   ", $Joins);


        $SQL .= "\nWHERE {$listAlias}._Deleted = 0\n   ";
        if(!empty($list_ModuleField->listCondition)){
            $SQL .= " AND {$listAlias}.{$list_ModuleField->listCondition}\n";
        }
        
        //close the statement
        $SQL .= ""; //NO ENDING NEWLINE - that will cause error messages...



        print $SQL."\n";
        
        //verify that the SQL statement will execute without error
        CheckSQL($SQL);     


//print "checkListSQL:\n";
//print $SQL . "\n";
        //die();
        return $SQL;
    }
    
    //generates the SQL statement used to get a record
    function generateGetSQL($ScreenName, $section = null, $isGrid = false)
    {
        $debug_prefix = debug_indent("Module-generateGetSQL() {$this->ModuleID} $ScreenName $section:");

        $fields = array();
        $isScreen = false;

        if(empty($section)){
            if($ScreenName == 'ListFields'){ //yes, we use ListFields to make Labels
                print "$debug_prefix getting ListFields\n";
                $fields = $this->getListFields();
            } else {

                if(is_array($ScreenName)){
                    $fields = $ScreenName;
                } else {

                    if($ScreenName == 'View'){
                        print "$debug_prefix getting View Screen Fields\n";
                        $Screen =& $this->viewScreen;
                    } else {
                        print "$debug_prefix getting screen fields\n";
                        $Screen = $this->getScreen($ScreenName); //&
                    }
                    $fields = $Screen->Fields;
                    $isScreen = true;
                }
            }
        } else {

            //$Screen = $section;
            print "$debug_prefix getting View Screen Section Fields\n";
            $fields = $section->Fields;

        }

        global $SQLBaseModuleID;
        $SQLBaseModuleID = $this->ModuleID;

        //handle sub-fields - we "flatten" the subfield hierarchy
        $allFields = array();
        foreach($fields as $sfName => $sf){
            $allFields = array_merge($allFields, $sf->getRecursiveFields());
        }

        $selectFieldNames = array();
        foreach($allFields as $fieldName => $field){
            $selectFieldNames = array_merge($selectFieldNames, $field->getSelectFields());
        }
        if($isScreen){
            if(!empty($this->recordLabelField)){
                if(!array_key_exists($this->recordLabelField, $selectFieldNames)){
                    $selectFieldNames[$this->recordLabelField] = true;
                }
            }
        }
        if(!empty($this->OwnerField)){
            if(!array_key_exists($this->OwnerField, $selectFieldNames)){
                $selectFieldNames[$this->OwnerField] = true;
            }
        }

        //array of fields in the SELECT statement (just to be able to use 'implode')
        $SelectDefs = array();
        $Conditions = array();

        $SQL = "SELECT \n   ";
        foreach($selectFieldNames as $fieldName => $displayed){

            //look up corresponding ModuleField based on screen field name
            $mf = $this->ModuleFields[$fieldName];

            print "$debug_prefix mfname: " . $mf->name . "\n";
            if(empty($mf)){

                indent_print_r($fields, true, 'fields');
                indent_print_r($selectFieldNames, true, 'selectFieldNames');
                die("$debug_prefix The screen {$Screenname} contains a field {$fieldName} that is not in the ModuleFields of the {$this->ModuleID} module.\n");
            } else {
                //delegate the creation of select and from clauses to the module fields
                //$SelectDefs[] = $mf->makeSelectDef($this->ModuleID);
                $SelectDefs[] = GetSelectDef($mf->name);
                //$Joins = array_merge($mf->makeJoinDef($this->ModuleID), $Joins);
                $Joins = array_merge($Joins, GetJoinDef($mf->name));
            }
        }

//print_r($Joins);
        $SQL .= implode(",\n   ", $SelectDefs);
        $SQL .= "\nFROM `{$this->ModuleID}`\n   ";

        $Joins = SortJoins($Joins);

        //add joins here
        $SQL .= implode("\n     ", $Joins);

        //special for GET: record ID condition
        $pkField = end($this->PKFields);
        if($isGrid){
            $Conditions[] = "{$this->ModuleID}.{$pkField}='/**RowID**/'";
        } else {
            $Conditions[] = "{$this->ModuleID}.{$pkField}='/**RecordID**/'";
        }


        //if there are any conditions
        if (count($Conditions) > 0){
            $Conditions = array_unique($Conditions);
            $SQL .= "\nWHERE\n   ";
            $SQL .= implode("\n   AND ", $Conditions);
        }
        //close the statement
        $SQL .= ''; //NO ENDING NEWLINE - that will cause error messages...
print "$debug_prefix $SQL\n";
        
        //verify that the SQL statement will execute without error
        CheckSQL(str_replace(array('/**RecordID**/', '/**RowID**/'), array('1', '1'), $SQL));
        
        debug_unindent();
        return $SQL;
    }

    //helper function to determine whether to make SQL statements to insert or update fields
    //i.e. if an EditScreen only has View fields (and one or more EditGrids),
    //don't bother adding SQL statements that will never be used
    function CheckForEditableFields($ScreenName){
        $Screen = &$this->getScreen($ScreenName);
        //$Screen = &$this->Screens[$ScreenName];
        $nEditableFields = 0;
        foreach($Screen->Fields as $sf){
            if ($sf->isEditable()){
                $nEditableFields += 1;
            }
        }
        if ($nEditableFields > 0){
            return true;
        } else {
            return false;
        }
    }



    /**
     * generates the SQL statement used to insert a record
     *
     * expects an EditScreen or EditGrid object
     */
    function generateInsertSQL(&$Screen, $logTable, $format = 'var')
    {
        //some sanity checking for the format parameter
        switch($format){
        case 'var':
        case 'replace':
            break;
        default:
            die("ERROR: Parameter \$format in function generateInsertSQL expects either 'var' or 'replace'.\n");
        }

        //array of fields in the SELECT statement (just to be able to use 'implode')
        $InsertFields = array();

        if ($logTable){
            $tableName = $this->ModuleID."_l";

            $PKFieldName = end($this->PKFields);
            if('var' == $format){
                $InsertFields[$PKFieldName] = "\"'\".\$recordID.\"'\"";
            } else {
                $InsertFields[$PKFieldName] = '/**RecordID**/';
            }

        } else {
            $tableName = $this->ModuleID;
            //inserts should include the PK field if it's not auto_increment
            foreach($this->PKFields as $PKFieldName){
                if(false === strpos($this->ModuleFields[$PKFieldName]->dbFlags, 'auto_increment')){
                    if('var' == $format){
                        $InsertFields[$PKFieldName] = "\"'\".\$recordID.\"'\"";
                    } else {
                        $InsertFields[$PKFieldName] = '/**RecordID**/';
                    }
                }
            }
        }

        
        if (in_array(get_class($Screen), array('editgrid', 'uploadgrid'))){
            //add grid specific fields 
            if(!empty($Screen->localKey)){
                $InsertFields[$Screen->localKey] = '/**PR-ID**/';
            }
            
            if($Screen->listExtended){
                $extendingModule = GetModule($Screen->moduleID);
                $InsertFields[$extendingModule->extendsModuleKey] = '/**extendID**/';
            }

            if(count($Screen->conditions) > 0){
                $conditions = $Screen->conditions;
            } else {
                $conditions = $this->conditions;
            }

            if(count($conditions) > 0){
                foreach($conditions as $condField => $condValue){
                    if(!in_array($condField, $InsertFields)){
                        $InsertFields[$condField] = "'{$condValue}'";
                    }
                }
            }
            
            
            //pick the right set of editable fields
            if($Screen->hasGridForm){
                $t_fields = $Screen->FormFields;
            } else {
                $t_fields = $Screen->Fields;
            }
        } else {
            if(is_array($Screen)){
                $t_fields = $Screen;
            } else {
                $t_fields = $Screen->Fields;
            }
        }
//print_r($t_fields);
        //handle sub-fields - we "flatten" the subfield hierarchy
        $saveFields = array();
        foreach($t_fields as $sfName => $sf){
            //print "screen field name $sfName\n";
            $saveFields = array_merge($saveFields, $sf->getRecursiveFields());
        }

        foreach($saveFields as $sfName => $sf){

            //only insert editable fields (no ViewFields etc)
            if ($sf->isEditable()){

                //look up corresponding ModuleField based on screen field name
                $mf = $this->ModuleFields[$sfName];

                switch (get_class($mf)){
                case 'tablefield':
                    $insert = false;
                    //exclude any auto_increment field
                    if ($logTable){
                        if ("_RecordID" != $sfName){
                            $insert = true;
                        }
                    } else {
                        if (false === strpos($mf->dbFlags, "auto_increment")){
                            $insert = true;
                        }
                    }
                    if ($insert){
                        if('var' == $format){
                            $InsertFields[$sfName] = "dbQuote(\$data['{$sfName}'], '{$mf->dataType}')";
                            
                        } elseif('replace' == $format){
                            //using replace-style formatting, the dbQuote,
                            //ChkFormat and and DateToISO must be called
                            //outside this function.
                            $InsertFields[$sfName] = "'[*$sfName*]'"; //this is a tag to be replaced before the prepared SQL is executed
                        }
                    }
                    break;
                case 'remotefield';
                    //this should save to the remote table - must generate separate SQL statement
                    //(ignore RemoteFields here)

                    break;
                case 'foreignfield':
                case 'codefield':
                case 'dynamicforeignfield':
                case 'calculatedfield':
                    //die("Cannot use a foreign field in INSERT statement ($sfName)\n");
                    print "AA warning: (Screen {$Screen->name}, Field $sfName, Table $tableName)\n";
                    print "         Non-saving field type used in form - INSERT statement will not save data  for this field. \n";
                    print "         This may be OK if this field is used for combo box filtering only.\n";
                    break;

                default:
                    print "m. field type for INSERT statement: '".get_class($mf)."'\n";
                    die("m. Unknown field type in INSERT statement (Screen {$Screen->name}, Field $sfName, Table $tableName)\n");
                    break;
                }
            }
        }

        //add log data:
        if('var' == $format){
            $InsertFields['_ModDate'] = '"NOW()"';
            $InsertFields['_ModBy'] = '$User->PersonID';
        } elseif('replace' == $format){
            $InsertFields['_ModDate'] = 'NOW()';
            $InsertFields['_ModBy'] = '[**UserID**]';
        }
//print "generateInsertSQL {$this->ModuleID} InsertFields: \n";
//print_r($InsertFields);

        if (count($InsertFields) > 0){
            //format SQL Statement
            if('var' == $format){
                $SQL = "INSERT INTO `$tableName` (\n   ";
                $SQL .= implode(",\n   ", array_keys($InsertFields));
                $SQL .= "\n) VALUES (\n   \".";
                $SQL .= implode(".\",\n   \".", $InsertFields);
                $SQL .= ".\")"; //NO ENDING NEWLINE - that will cause error messages...
            } elseif('replace' == $format){
                $SQL = "INSERT INTO `$tableName` (\n   ";
                $SQL .= implode(",\n   ", array_keys($InsertFields));
                $SQL .= "\n) VALUES (\n   ";
                $SQL .= implode(",\n   ", $InsertFields);
                $SQL .= ")"; //NO ENDING NEWLINE - that will cause error messages...
            }
        } else {
            $SQL = "";
        }
        return $SQL;
    }


    //generates the SQL statement used to update a record
    function generateUpdateSQL(&$Screen, $format = 'var')
    //now expects an EditScreen or EditGrid object
    //function generateUpdateSQL($ScreenName)
    {
        global $SQLBaseModuleID;
        $SQLBaseModuleID = $this->ModuleID;

        //some sanity checking for the format parameter
        if ($format != 'var' && $format != 'replace'){
            if (empty($format)) {
                $format = 'var';
            } else {
                die("ERROR: Parameter \$format in function generateUpdateSQL expects either 'var' or 'replace'.\n");
            }
        }

        //array of fields in the SQL statement (just to be able to use 'implode')
        $UpdateFields = array();

        $tableName = $this->ModuleID;
        
        if (in_array(get_class($Screen), array('editgrid', 'uploadgrid'))){
            //pick the right set of editable fields
            if($Screen->hasGridForm){
                $t_fields = $Screen->FormFields;
            } else {
                $t_fields = $Screen->Fields;
            }
        } else {
            if(is_array($Screen)){
                $t_fields = $Screen;
            } else {
                $t_fields = $Screen->Fields;
            }
        }
        
        //handle sub-fields - we "flatten" the subfield hierarchy
        $saveFields = array();
        foreach($t_fields as $sfName => $sf){
            $saveFields = array_merge($saveFields, $sf->getRecursiveFields());
        }

        foreach($saveFields as $sfName => $sf){
//      foreach($Screen->Fields as $sfName => $sf){

            //only insert editable screen fields (no ViewFields etc)
            if ($sf->isEditable()){
                //look up corresponding ModuleField based on screen field name
                $mf = $this->ModuleFields[$sfName];

                switch (get_class($mf)){
                case 'tablefield':
                    $insert = false;
                    //exclude any auto_increment field
                    if ($logTable){
                        if ("_RecordID" != $sfName){
                            $insert = true;
                        }
                    } else {
                        if (false === strpos($mf->dbFlags, "auto_increment")){
                            $insert = true;
                        }
                    }
                    if ($insert){
                        if('var' == $format){

                            //add the field/value pair
                            $UpdateFields[$sfName] = "dbQuote(\$data['{$sfName}'], '{$mf->dataType}')";
                            /*
                            if ("date" == $mf->dataType){
                                $UpdateFields[$sfName] = "DateToISO(\$data['".$sfName."'])";
                            } elseif ("bool" == $mf->dataType) {
                                $UpdateFields[$sfName] = "ChkFormat(\$data['".$sfName."'])";
                            } else {
                                $UpdateFields[$sfName] = "dbQuote(\$data['".$sfName."'])";
                            }*/
                        } elseif('replace' == $format){
                            //using replace-style formatting, the dbQuote,
                            //ChkFormat and and DateToISO must be called
                            //outside this function.
                            $UpdateFields[$sfName] = "'[*$sfName*]'"; //this is a tag to be replaced before the prepared SQL is executed
                        }
                    }
                    break;
                case 'remotefield';
                    //this should save to the remote table - must generate separate SQL statement
                    //(ignore RemoteFields here)
                    print "Note: (Screen {$Screen->name}, Field $sfName, Table $tableName)\n";
                    print "         RemoteField used. Will need a separate SQL statement to save data for this field. \n";
                    break;
                case 'foreignfield':
                case 'codefield':
                case 'dynamicforeignfield':
                case 'calculatedfield':
                    //die("Cannot use a foreign field in INSERT statement ($sfName)\n");
                    print "AA warning: (Screen {$Screen->name}, Field $sfName, Table $tableName)\n";
                    print "         Non-saving field type used in form - UPDATE statement will not save data for this field. \n";
                    print "         This may be OK if this field is used for combo box filtering only.\n";
                    break;

                default:
                    die("Unknown field type in UPDATE statement (Screen {$Screen->name}, Field $sfName, Table $tableName)\n");
                    break;
                }
            }
        }

        //add log data:
        if('var' == $format){
            $UpdateFields["_ModDate"] = "\"NOW()\"";
            $UpdateFields["_ModBy"] = "\$User->PersonID";
        } elseif('replace' == $format){
            $UpdateFields["_ModDate"] = "NOW()";
            $UpdateFields["_ModBy"] = "[**UserID**]";
        }


        //check for one or more PK's
        if (count($this->PKFields) > 1){

            if('var' == $format){
                //note: I'm not sure if this is useful - will
                //probably redo this case

                //if more than one PK field, append # to $recordID
                foreach($this->PKFields as $key=>$PKField){
                    $Conditions[] = $PKField."=\$recordID$key";
                }
            } else {
                //note: it is assumed that the first PK field
                //is the same as the parent row ID
                //and the second is the current module rowID
                $Conditions[] = $this->PKFields[0]."=/**PR-ID**/";
                $Conditions[] = $this->PKFields[1]."=/**RecordID**/";
            }
        } else {
            if('var' == $format){
                $Conditions[] = $this->PKFields[0]."='\$recordID'";
            } else {
                $Conditions[] = $this->PKFields[0]."='/**RecordID**/'";
            }
        }
        if(count($this->conditions) > 0){
            foreach($this->conditions as $condField => $condValue){
                $Conditions[] = "`{$this->ModuleID}`.$condField = '{$condValue}'";
            }
        }


        if (count($UpdateFields) > 0){
            $FormatFields = array();

            //format SQL Statement
            $SQL = "UPDATE `$tableName`\nSET\n   ";
            if('var' == $format){
                foreach($UpdateFields as $field => $value){
                    $FormatFields[] = "$field = \".$value.\"";
                }
            } else {
                foreach($UpdateFields as $field => $value){
                    $FormatFields[] = "$field = $value";
                }

            }
            $SQL .= implode(",\n    ", $FormatFields);

            $SQL .= "\nWHERE\n   ";

            //add row identifying conditions
            $SQL .= implode("\n   AND ", $Conditions);

            $SQL .= ""; //NO ENDING NEWLINE - that will cause error messages...
        } else {
            $SQL = "";
        }
        return $SQL;
    }

    //generates the SQL statement used to delete a record
    function generateDeleteSQL(&$Screen, $format = 'var')
    {
        global $SQLBaseModuleID;
        $SQLBaseModuleID = $this->ModuleID;
    
        //some sanity checking for the format parameter
        if ($format != 'var' && $format != 'replace'){
            if (empty($format)) {
                $format = 'var';
            } else {
                die("ERROR: Parameter \$format in function generateDeleteSQL expects either 'var' or 'replace'.\n");
            }
        }

        //check for one or more PK's
        if (count($this->PKFields) > 1){
            if('var' == $format){
                //note: I'm not sure if this is useful - will
                //probably redo this case

                //if more than one PK field, append # to $recordID
                foreach($this->PKFields as $key=>$PKField){
                    $Conditions[] = $PKField."=\$recordID$key";
                }
            } else {
                //note: it is assumed that the first PK field
                //is the same as the parent row ID
                //and the second is the current module rowID
                $Conditions[] = $this->PKFields[0]."=/**PR-ID**/";
                $Conditions[] = $this->PKFields[1]."=/**RecordID**/";
            }
        } else {
            if('var' == $format){
                $Conditions[] = $this->PKFields[0]."=\$recordID";
            } else {
                $Conditions[] = $this->PKFields[0]."=/**RecordID**/";
            }
        }

        $tableName = $this->ModuleID;

        //format SQL Statement (we use Update because we simply set _Deleted to true)
        $SQL = "UPDATE `$tableName`\n   SET\n      ";
        $SQL .= "_Deleted = 1,\n";
        if('var' == $format){
            $SQL .= "_ModBy = \".\$User->PersonID.\",\n";
            $SQL .= "_ModDate = NOW()\n";
        } else {
            $SQL .= "_ModBy = [**UserID**],\n";
            $SQL .= "_ModDate = NOW()\n";
        }

        $SQL .= "\nWHERE\n   ";

        //add row identifying conditions
        $SQL .= implode("\n   AND ", $Conditions);

        $SQL .= ""; //NO ENDING NEWLINE - that will cause error messages...

        return $SQL;
    }

    
//generates the SQL statement used to delete a record
    function generateDeleteLogSQL(&$Screen, $format = 'var')
    {

        $tableName = $this->ModuleID."_l";

        //look up the regular auto_increment field and include it
        //$FirstField = &reset($this->ModuleFields);
        $FirstField = reset($this->ModuleFields);

        $InsertFields[] = '_Deleted';
        $InsertValues[] = '"1"';
        
        $InsertFields[] = $FirstField->name;
        if('var' == $format){
            $InsertValues[] = "\$recordID";
        } else {
            $InsertValues[] = "/**RecordID**/";
        }
    
        
        //add log data:
        $InsertFields[] = "_ModDate";

        $InsertFields[] = "_ModBy";
        if('var' == $format){
            $InsertValues[] = "\"NOW()\"";
            $InsertValues[] = "\$User->PersonID";
        } elseif('replace' == $format){
            $InsertValues[] = "NOW()";
            $InsertValues[] = "[**UserID**]";
        }


        if (count($InsertFields) > 0){
            //format SQL Statement
            if('var' == $format){
                $SQL = "INSERT INTO `$tableName` (\n   ";
                $SQL .= implode(",\n   ", $InsertFields);
                $SQL .= "\n) VALUES (\n   \".";
                $SQL .= implode(".\",\n   \".", $InsertValues);
                $SQL .= ".\")"; //NO ENDING NEWLINE - that will cause error messages...
            } elseif('replace' == $format){
                $SQL = "INSERT INTO $tableName (\n   ";
                $SQL .= implode(",\n   ", $InsertFields);
                $SQL .= "\n) VALUES (\n   ";
                $SQL .= implode(",\n   ", $InsertValues);
                $SQL .= ")"; //NO ENDING NEWLINE - that will cause error messages...
            }
        } else {
            $SQL = "";
        }
        
        return $SQL;
    }

    
    //**************************************//
    //       create screen functions        //
    //**************************************//
    function generateTabs($pScreenName, $pRestriction = ''){
        if(0 == count($this->getScreens())){
            return '';
        }

        $tabs = array();
        $Screens =& $this->getScreens();

        if ("List" == $pScreenName){
            if(empty($this->addNewName)){
                $addNewName = $this->SingularRecordName;
            } else {
                $addNewName = $this->addNewName;
            }

            if($this->AllowAddRecord){
                if('view' == $pRestriction){
                    $content  = "\$tabs['New'] = array(\"\", gettext(\"No Add New|You cannot add a new {$addNewName} because you don't have permission\"), 'disabled');";
                } else {
                    //get first edit screen and insert a tab link to it as "new"
                    foreach ($Screens as $Screen){
                        if ("editscreen" == get_class($Screen)){
                            $content  = "\$tabs['New'] = array(\"edit.php?mdl={$this->ModuleID}&scr={$Screen->name}\", gettext(\"Add New|Add a new {$addNewName}\"));";

                            break; //exits loop
                        }
                    }
                }
            } else {
                $content  = "\$tabs['New'] = array(\"\", gettext(\"No Add New|To add a new {$addNewName} you must go to a parent module\"), 'disabled');";
            }

            //get search screen and insert a tab link to it
            if(count($Screens)){
                foreach ($Screens as $Screen){
                //foreach ($this->Screens as $Screen){

                    if ('searchscreen' == get_class($Screen)){

                        $content  .= "\$tabs['Search'] = array(\"search.php?mdl={$this->ModuleID}\", gettext(\"{$Screen->phrase}\"));";
                        $content  .= "\$tabs['Charts'] = array(\"charts.php?mdl={$this->ModuleID}\", gettext(\"Charts\"));";
                    } else if('listreportscreen'  == get_class($Screen)){
                        $content  .= "\$tabs['Reports'] = array(\"reports.php?mdl={$this->ModuleID}\", gettext(\"Reports\"));";
                    }
                }
            }
            if($this->dataCollectionForm){
                $content  .= "\$tabs['DataCollection'] = array(\"dataCollectionForm.php?mdl={$this->ModuleID}\", gettext(\"Blank Form\"), 'download');";
            }

        } else {
            print "m. GenerateTabs: current screen $pScreenName\n";
            
            
            //$currentScreen = $this->Screens[$pScreenName];
            $currentScreen = $this->getScreen($pScreenName);

            foreach ($Screens as $Screen){
                $linkTo = '';
                switch(get_class($Screen)){
                case "viewscreen":
                    $handler = "view.php";
                    if(in_array($this->SingularRecordName[0], array('a','e','i','o','h','y','A','E','I','O','H','Y'))){
                        $a = 'an';
                    } else {
                        $a = 'a';
                    }
                    $phrase = "View|View summary information about\").' '.gettext(\"$a ". $this->SingularRecordName;
                    break;
                case "editscreen":
                    if(!empty($Screen->linkToModuleID)){
                        $linkTo = $Screen->linkToModuleID;
                    }
                    $handler = "edit.php";
                    
                    if (empty($Screen->phrase)){
                        $phrase = $Screen->name;
                    } else {
                        $phrase = $Screen->phrase;
                    }
                    break;
                case "searchscreen":
                    $handler = "search.php";
                    $phrase = "Search|Search \").' '.gettext(\"". $this->PluralRecordName;
                    break;
                case "listscreen":
                    $handler = "list.php";
                    $phrase = "List|View the list of \").' '.gettext(\"". $this->PluralRecordName;
                    break;
                case "recordreportscreen":
                    $handler = "reports.php";
                    $phrase = $Screen->phrase;
                    break;
                case "listreportscreen":
                    $handler = "reports.php";
                    $phrase = $Screen->phrase;
                    break;
                default:
                    print_r($Screens);
                    die("unknown screen type: '".get_class($Screen)."'\n");
                }
                
                if(!empty($Screen->tabConditionModuleID)){
                    $tabConditionModuleID = ", '{$Screen->tabConditionModuleID}'";
                }

                if ($pScreenName != $Screen->name){
                    if (("view" == $pRestriction && "viewscreen" == get_class($Screen))
                        || ("view" != $pRestriction)){

                        //insert link
                        if($linkTo == ''){
                            $tab = "      \$tempTabs['{$Screen->name}'] = array( \"$handler?scr={$Screen->name}&\$tabsQS\", gettext(\"{$phrase}\") $tabConditionModuleID);\n";
                        } else {
                            $tab = "      \$tempTabs['{$Screen->name}'] = array( \"$handler?mdl=$linkTo&rid=\$recordID\", gettext(\"{$phrase}\") $tabConditionModuleID);\n";
                        }
                    }
                } else {
                    //Current screen: insert name only
                    //$tab = "      \$tempTabs[gettext(\"{$phrase}\")] = \"\";\n";
                    $tab = "      \$tempTabs['{$Screen->name}'] = array( \"\", gettext(\"{$phrase}\") $tabConditionModuleID);\n";
                }

                if(in_array(get_class($Screen), array('viewscreen', 'editscreen', 'recordreportscreen'))){
                    $content .= $tab;
                }
            }
        }


        //format into a string
        $content .= "\$tabs = array_merge(\$tabs, \$tempTabs);";

        return $content;
    }


function getDataCollectionForms()
{

    //get all edit screens
    $dataForms = array();
    foreach($this->getScreens() as $screenName => $screen){
        if('editscreen' == get_class($screen)){
            //remove unnecessary properties?

            //filter out screens without editable fields (we might support screens with grids later)
            $fields = $screen->Fields;
            $dataFields = array();

            foreach($fields as $fieldName => $field){
                //determine which screen fields to show
                if($field->isEditable()){
                    $moduleField = $this->ModuleFields[$fieldName];
                    switch(get_class($moduleField)){
                    case 'tablefield':
                    case 'remotefield':

                        break;
                    default:
                        //non-saving field
                        $field->nonSaving = true;
                        break;
                    }
                    $field->phrase = $moduleField->phrase;
                    $dataFields[$fieldName] = $field;
                }
            }
            if(count($dataFields) > 0){
                $dataForms[$screenName]['phrase'] = $screen->phrase;
                $dataForms[$screenName]['fields'] = $dataFields;
            }

            if(count($screen->Grids) > 0){
                $grids = $screen->Grids;
                foreach($grids as $gridName => $grid){
                    if('editgrid' == get_class($grid) && $grid->dataCollectionForm){
                        $subModule =& $this->SubModules[$grid->moduleID];
                        $gridFields = array();

                        foreach($grid->FormFields as $fieldName => $field){
                            //determine which screen fields to show
                            if($field->isEditable()){
                                $moduleField = $subModule->ModuleFields[$fieldName];
                                switch(get_class($moduleField)){
                                case 'tablefield':
                                case 'remotefield':

                                    break;
                                default:
                                    //non-saving field
                                    $field->nonSaving = true;
                                    break;
                                }
                                $field->phrase = $moduleField->phrase;
                                $gridFields[$fieldName] = $field;
                            }
                        }

                        $dataForms[$screenName]['moduleName'] = $subModule->Name;
                        $dataForms[$screenName]['phrase'] = $screen->phrase;
                        $dataForms[$screenName]['sub'][$grid->moduleID] = array(
                            $grid->phrase,
                            $gridFields
                            );

                    }
                }
            }
        }
    }
    return $dataForms;
} //end getDataCollectionForms()


    function generateExport(){
        require_once CLASSES_PATH . '/data_handler.class.php';
        require_once CLASSES_PATH . '/report.class.php';
        require_once CLASSES_PATH . '/module_map.class.php';
        $exportFields = array();

        foreach($this->ModuleFields as $fieldName => $moduleField){
            switch(get_class($moduleField)){
            case 'tablefield':
            case 'remotefield':
                $exportFields[$fieldName] = $fieldName;
                break;
            default:
                break;
            }
        }

        unset($exportFields['_ModDate']);
        unset($exportFields['_ModBy']);
        unset($exportFields['_Deleted']);

        $subElements = array();
        foreach($exportFields as $exportField){
            $subElements[] = & new Element($exportField, 'ReportField', array('name' => $exportField));
        }

        if(count($this->SubModules) > 0){
            foreach($this->SubModules as $subModuleID => $subModule){
                if('submodule' == get_class($subModule)){
                    if(!empty($subModule->parentKey)){
                        //creates submodule report element
                        $submodule_element = $subModule->makeExportSubReportElement();
                        if(!$submodule_element){
                            print "Skipped XML export for submodule $subModuleID\n";
                        } else {
                            $subElements[] = $submodule_element;
                        }
                    }
                }
            }
        }

        $report_element =& new Element(
            'XmlExport',
            'Report',
            array(
                'moduleID' => $this->ModuleID,
                'title' => 'XML Export'
            ),
            $subElements);

        $report =& $report_element->createObject($this->ModuleID);

        print "\n\ngenerateExport:\n $exportSQL\n\n";
        return array('/**exportReport**/' => escapeSerialize($report));
    }


    function getNextScreen($pScreenName){
        //look up the current screen
        $ScreenNames = array_keys($this->getScreens());
        $current = array_search($pScreenName, $ScreenNames);
        $nextScreen = $ScreenNames[$current+1];
        if (empty($nextScreen)){
            $nextScreen = "";
        }
        return $nextScreen;
    }

    function getFirstEditScreen(){
        $FirstEditScreenName = '';

        $screens_element = $this->_map->selectFirstElement('Screens', NULL, NULL);
        if(!empty($screens_element) && count($screens_element->c) > 0){
            foreach($screens_element->c as $screen_element){
                if('EditScreen' == $screen_element->type){
                    $FirstEditScreenName = $screen_element->name;
                    break;
                }
            }
        }
        return $FirstEditScreenName;
    }




    function _generateSerializedFieldPhrases($fields){
        $phrases = array();
        $content = "array(\n";
        foreach($fields as $FieldName=>$Field){

            $t_mf = $this->ModuleFields[$FieldName];
            
            if (!empty($t_mf->phrase)){
                $phrases[] = "   '$FieldName' => gettext(\"{$t_mf->phrase}\")";
            } else {
                $phrases[] = "   '$FieldName' => gettext(\"$FieldName\")";
            }
        }
        $content .= join(",\n", $phrases);
        $content .= "\n   );\n";
        return $content;
    }

    function generateViewScreenSections(){
        printd( "m.generateViewScreenSections: begin\n", 1);
        
        //$screen = $this->getScreenOfType('viewscreen');
        $screen =& $this->viewScreen;
        if(empty($screen)){
            return null;
        }

        $sections = array();
        $sections[0]['phrase'] = $this->SingularRecordName;

        
        if(count($screen->Fields) > 0){
            $serPhrases = "\$phrases[0] = ";
            $serPhrases .= $this->_generateSerializedFieldPhrases($screen->Fields);
            $sections[0]['sql'] = $this->generateGetSQL('View');
            $sections[0]['fields'] = $screen->Fields;
        }
        if(count($screen->Grids) > 0){
            foreach($screen->Grids as $gridID => $grid){
//                $subModule = GetModule($grid->moduleID);
//                $grid->listSQL = $subModule->generateListSQL($grid);
                $sections[0]['grids'][$gridID] = $grid;
            }
        }
        $sectionID = 0;
        if(count($screen->sections) > 0){
            foreach($screen->sections as $section){
                $sectionID++;
                $sections[$sectionID]['phrase'] = $section->phrase;
                if(count($section->Fields) > 0){
                    $serPhrases .= "\$phrases[$sectionID] = ";
                    $serPhrases .= $this->_generateSerializedFieldPhrases($section->Fields);
                    $sections[$sectionID]['sql'] = $this->generateGetSQL('View', $section);
                    $sections[$sectionID]['fields'] = $section->Fields;
                }
                if(count($section->Grids) > 0){
                    foreach($section->Grids as $gridID => $grid){
                        $subModule = GetModule($grid->moduleID);
                        $grid->listSQL = $subModule->generateListSQL($grid);
                        $sections[$sectionID]['grids'][$gridID] = $grid;
                    }
                    
                }
            }
        }
        
        $output['/**PHRASES**/'] = $serPhrases;
        $output['/**SECTIONS**/'] = "\$sections = unserialize('" . escapeSerialize($sections) . "');";
        return $output;
    }

    function BuildViewScreen(){
        printd( "m.BuildViewScreen: begin ({$this->ModuleID})\n", 1);

        global $SQLBaseModuleID;
        $SQLBaseModuleID = $this->ModuleID;
        
        $output['/**module_name**/'] = $this->Name;
        $output['/**singular_record_name**/'] = $this->SingularRecordName;
        $output['/**plural_record_name**/'] = $this->PluralRecordName;

        $Screen =& $this->viewScreen;

        if($this->useBestPractices){
            $output['/**useBestPractices**/'] = '$useBestPractices = true;';

            $field_object = MakeObject(
                $this->moduleID,
                'IsBestPractice',
                'InvisibleField',
                array(
                    'name' => 'IsBestPractice'
                )
            );
            $Screen->Fields['IsBestPractice'] = $field_object;
        }

        //fields
        printd( "Generating fields:\n", 1);
        $content = "\$fields = unserialize('";
        $content .=
            str_replace("'", "\\'",
                str_replace("\\", "\\\\",
                    serialize($Screen->Fields)
                )
            );
        $content .= "');\n";
        $output['/**fields**/'] = $content;

        $output['/**screen_phrase**/'] = $Screen->phrase;

        if(!$this->includeGlobalModules){
            $output['/**disbleGlobalModules**/'] = '$disableGlobalModules = true;';
        }

        //phrases array
        printd( "Building phrases:\n", 1);
        $phrases = array();
        $content = "\$phrases = array(\n";
        foreach($Screen->Fields as $FieldName=>$Field){

            $t_mf = $this->ModuleFields[$FieldName];
            
            if (!empty($t_mf->phrase)){
                $phrases[] = "   '$FieldName' => gettext(\"{$t_mf->phrase}\")";
            } else {
                $phrases[] = "   '$FieldName' => gettext(\"$FieldName\")";
            }
        }
        $content .= join(",\n", $phrases);
        $content .= "\n   );\n";

//print $content."\n\n";
        $output['/**phrases**/'] = $content;

        //ownerField filter
        printd("m. getting owner field\n", 1);
        if($this->OwnerField) {
            $content = $this->OwnerField;
        } else {
            $content = '';
        }
        $output['/**ownerField**/'] = "\$ownerField = '". $content . "';\n";

        printd("m. generating get statement\n", 1);
        $output['/**SQL|GET**/'] = $this->generateGetSQL('View');
        

        $output['/**tabs|EDIT**/'] = $this->generateTabs('View');
        $output['/**tabs|VIEW**/'] = $this->generateTabs('View', "view");

        $output['/**nextScreen**/'] = $this->getNextScreen('View');

        if(empty($this->recordLabelField)){
            $output['/**RecordLabelField**/'] = "\$recordLabelField = 'Record ' . \$recordID;";
        } else {
            $output['/**RecordLabelField**/'] = "\$recordLabelField = \$data['{$this->recordLabelField}'];";
        }

        //grids
        $i = 1;
        $grids = "";
        foreach($Screen->Grids as $Grid){
            if (is_object($Grid)){
                $t_subModule =& $this->SubModules[$Grid->moduleID];

                $Grid->number = $i;
//                $Grid->listSQL = $t_subModule->generateListSQL($Grid);

                $grids .= "   \$Grid$i = unserialize('";
                $grids .=
                    str_replace("'", "\\'",
                        str_replace("\\", "\\\\",
                            serialize($Grid)
                        )
                    );
                $grids .= "');\n";
                $grids .= "   \$content .= \$Grid{$i}->render('view.php', \$qsArgs);\n";

                unset($t_subModule);
            }
            $i++;
        }

        $output['/**VIEWGRIDS**/'] = $grids;
        
        if(count($Screen->sections) > 0){
            //for each ViewScreenSection, display it...
            //$content = "include(INCLUDE_PATH.'/viewscreensection.php');\n";
            $content = ''; //line above has been transferred to view.php - needed for global grids...
            foreach($Screen->sections as $section){
                $content .= $this->BuildViewScreenSection($section);
            }
            $output['/**VIEWSCREENSECTIONS**/'] = $content;
        }
        //see if there are any CustomCode elements
        if(count($Screen->customCodes) > 0){
            foreach($Screen->customCodes as $location => $customCode){
                $output[$location] = $customCode->getContent();
            }
        }
        
        printd( "m.BuildViewScreen: end ({$this->ModuleID})\n", 1);
        return $output;
    }


    function BuildViewScreenSection($section){
    
        $content = "\$content .= '<h1>'. gettext(\"{$section->phrase}\") .'</h1>';\n";
        
        if(count($section->Fields) > 0){
            $content .= "\$fields = unserialize('";
            $content .=
                str_replace("'", "\\'",
                    str_replace("\\", "\\\\",
                        serialize($section->Fields)
                    )
                );
            $content .= "');\n";
            
            
            $content .= "\$phrases = array(\n";
            $phrases = array();
            foreach($section->Fields as $FieldName=>$Field){
    
                $t_mf = $this->ModuleFields[$FieldName];
    
                if (!empty($t_mf->phrase)){
                    $phrases[] = "   '$FieldName' => gettext(\"{$t_mf->phrase}\")";
                } else {
                    $phrases[] = "   '$FieldName' => gettext(\"$FieldName\")";
                }
            }
            $content .= join(",\n", $phrases);
            $content .= "\n   );\n";
        
            print "ViewScreenSection:\n";
            //print_r($section);
            
            
            //get data
            $SQL = $this->generateGetSQL('View', $section);
            $content .= "\$SQL = \"$SQL\";\n";
        } else {
            $content .= "\$fields = '';\n";
            $content .= "\$phrases = array();\n";
            $content .= "\$SQL = '';\n";
        }
        
        //define grids (TODO)
                //grids
        $i = 1;
        //$grids = "";
        $grids = "   \$grids = array();\n";

print "m.before submodule loop\n";
print "m. module id: {$this->ModuleID}\n";
//print_r(array_keys($this->SubModules));
        foreach($section->Grids as $Grid){
            if (is_object($Grid)){
print "m.grid module id: {$Grid->moduleID}\n";

                //$t_subModule =& $this->SubModules[$Grid->moduleID];
                if(!in_array($Grid->moduleID, array_keys($this->SubModules))){
                    die("Grid {$Grid->moduleID} has no corresponding SubModule.");
                }
                $t_subModule = $this->SubModules[$Grid->moduleID];

                $Grid->number = $i;
//                $Grid->listSQL = $t_subModule->generateListSQL($Grid);

                $grids .= "   \$grids[$i] = unserialize('";
                $grids .=
                    str_replace("'", "\\'",
                        str_replace("\\", "\\\\",
                            serialize($Grid)
                        )
                    );
                $grids .= "');\n";

                unset($t_subModule);
            }
            $i++;
        }

        //print "testing grids for VSS\n";
        //print_r($section);
        
        //$output['/**VIEWGRIDS**/'] = $grids;
        
        
        $content .= $grids;
        
        
        $content .= "\$content .= renderViewScreenSection(\$fields, \$phrases, \$SQL, \$grids);\n";
        
        return $content;
    
    }
    


    function BuildEditScreen($ScreenName){

        printd( "m.BuildEditScreen: begin ({$this->ModuleID}:{$ScreenName})\n", 1);
    
        global $SQLBaseModuleID;
        $SQLBaseModuleID = $this->ModuleID;
        
        $output['/**module_name**/'] = $this->Name;
        $output['/**singular_record_name**/'] = $this->SingularRecordName;
        $output['/**plural_record_name**/'] = $this->PluralRecordName;
        
        //fields
        printd( "Generating fields:\n", 1);
        $Screen = $this->getScreen($ScreenName);
//print_r($Screen);
        $content = "\$fields = unserialize('";
        $content .=
            str_replace("'", "\\'",
                str_replace("\\", "\\\\",
                    serialize($Screen->Fields)
                )
            );
        $content .= "');\n";
        $output['/**fields**/'] = $content;


        $output['/**screen_phrase**/'] = $Screen->phrase;

        if(!$this->includeGlobalModules){
            $output['/**disbleGlobalModules**/'] = '$disableGlobalModules = true;';
        }

        $PKModuleFieldDataType = $this->ModuleFields[end($this->PKFields)]->dataType;
        switch($PKModuleFieldDataType){
        case 'int':
            $output['/**check_empty_recordID**/'] = 'if ($recordID > 0) {';
            break;
        case 'varchar(5)':
            $output['/**check_empty_recordID**/'] = 'if (strlen($recordID) > 1) {';
            break;
        default:
            print "pkfield: {$this->PKFields[0]}\n";
            die('Problem getting data type for primary key field...');
        }
        
        $output['/**PKField**/'] = "\$PKField = '".end($this->PKFields)."';\n";
        
        //list of date fields (needed to insert calendar code)
        $dateFields = array();

//      print "\n-*-*-*-*-*-\n";
//      print_r($Screen->Fields);
//      print "\n-*-*-*-*-*-\n";
        $hasEditableFields = 'false';

        //build list of DateFields
        foreach($Screen->Fields as $fieldKey => $field){
            print "Module->BuildEditScreen: screen field $fieldKey\n";
            if(0 == strlen(trim($fieldKey))){
                print "a problem occurred in module {$this->ModuleID}:\n";
                print "The offending field:\n";
                print_r ($field);
                print "HINT: Is there a non-GridField inside a Grid?\n";
                die("Module->BuildEditScreen: Screen field '$fieldKey' is not valid.\n");
            }
            
            if ("datefield" == get_class($field)){
                $dateFields[] = $field->name;
            }
            if($field->isEditable()){
                $hasEditableFields = 'true';
            }
        }
        $output['/**hasEditableFields**/'] = "\$hasEditableFields = {$hasEditableFields};\n";
        

        //generate content for each date field
        if (count($dateFields) > 0){
            $content = "\$content .= \"\n<script type=\\\"text/javascript\\\">\n";
            foreach($dateFields as $fieldName){
                $content .= "Calendar.setup({\n";
                $content .= "   inputField : \\\"$fieldName\\\",\n";
                if('datetime' == $this->ModuleFields[$fieldName]->getDataType()){
                    $content .= "\".\$User->getCalFormat(true).\"\n";
                    $content .= "   showsTime   : true,\n";
                } else {
                    $content .= "\".\$User->getCalFormat(false).\"\n";
                }
                $content .= "   onUpdate    : indicateUnsavedDateChanges,\n";
                $content .= "   button      : \\\"cal_$fieldName\\\"\n";
                $content .= "});\n";
            }
            $content .= "</script>\\n\";";

            $output['/**dateFields**/'] = $content;
        }

        //list of remote fields:
        $remoteFields = array();

        //build list of RemoteFields
        //only add RemoteFields that are on THIS SCREEN
        foreach($Screen->Fields as $FieldName=>$Field){            
        
            if(!in_array($FieldName, array_keys($this->ModuleFields))){
                //print_r($this->ModuleFields);
                print_r($Screen->Fields);
                die("Field {$FieldName} in edit screen {$ScreenName} has no corresponding ModuleField");
            }
        
            $t_mf = $this->ModuleFields[$FieldName];
            //print_r ($t_mf);
            if ('remotefield' == get_class($t_mf)){
                $remoteFields[$FieldName] = $t_mf;
            } 
        }

        //generate content for each RemoteField
        if (count($remoteFields) > 0){

            $content = "\$remoteFields = unserialize('";
            $content .=
                str_replace("'", "\\'",
                    str_replace("\\", "\\\\",
                        serialize($remoteFields)
                    )
                );
            $content .= "');\n";

            $output['/**REMOTEFIELDS_ARRAY**/'] = $content;
        } else {
            $output['/**REMOTEFIELDS_BEGIN**/'] = '/**-remove_begin-**/'; //"\nif(0){\n   //this section is commented out because\n   //there are no remote fields\n";
            $output['/**REMOTEFIELDS_END**/'] = '/**-remove_end-**/'; //"}\n";
        }
        
        
        //see if there are any CustomCode elements
        if(count($Screen->customCodes) > 0){
            foreach($Screen->customCodes as $location => $customCode){
                $output[$location] = $customCode->getContent();
                indent_print_r($customCode, $location);
            }
        }
        
        //THIS SHOULD BE REMOVED WHEN XML CHANGES ARE DONE
        //checks for resource grids - and hides them
        foreach($Screen->Grids as $id => $Grid){
            if('res' == $Grid->moduleID){
                unset($Screen->Grids[$id]);
            }
        }
        
        //define grids
        $i = 1;
        $grids = "   \$grids = array();\n";
        foreach($Screen->Grids as $Grid){
            if (is_object($Grid)) { // && get_class($Grid) == 'editgrid'){ let's allow ViewGrids
                $t_subModule =& $this->SubModules[$Grid->moduleID];
                if(!$Grid->isGuidance){
                    $Grid->number = $i;
                    
                    $grids .= "   \$grids[$i] = unserialize('";
                    $grids .=
                        str_replace("'", "\\'",
                            str_replace("\\", "\\\\",
                                serialize($Grid)
                            )
                        );
                    $grids .= "');\n";
                } else {
                    $output['/**guidanceGrid**/'] = '$guidanceGrid = unserialize(\''. escapeSerialize($Grid) . "');\n";
                }

                unset($t_subModule);
            }
            $i++;
        }
        $output['/**GRIDS|DEFINE**/'] = $grids;


        //saving posted grid forms
        $i = 1;
        $grids = "";
        foreach($Screen->Grids as $Grid){
            if (is_object($Grid) && 'viewgrid' != get_class($Grid)){

                $grids .= "   \$content .= \$grids[{$i}]->handleForm();\n";
            }
            $i++;
        }

        $output['/**GRIDS|SAVE**/'] = $grids;


        //display grids
        $i = 1;
        $grids = "";
        if(count($Screen->Grids > 0)){
            $grids .= "foreach(\$grids as \$gridID => \$grid){\n";
            $grids .= "   \$content .= \$grid->render('edit.php', \$qsArgs);\n";
            $grids .= "}\n";
        }

        $output['/**GRIDS|DISPLAY**/'] = $grids;


        //phrases array
        printd( "Building phrases:\n", 1);
        $phrases = array();
        $content = "\$phrases = array(\n";
        foreach($Screen->Fields as $FieldName=>$Field){
            switch(get_class($Field)){
                case 'combofield':
                case 'personcombofield':
                case 'orgcombofield':
                case 'codecombofield':
                case 'radiofield':
                case 'coderadiofield':
                    //get the list source field instead of the ID field
                    $listSourceFieldName = substr($FieldName, 0, -2);
                    $t_mf = $this->ModuleFields[$listSourceFieldName];
                    break;
                default:
                    $t_mf = $this->ModuleFields[$FieldName];
            }

            if (!empty($t_mf->phrase)){
    //print "   FIELD {$t_mf->name} is missing a phrase!!!\n\n";
                $phrases[] = "   '$FieldName' => gettext(\"{$t_mf->phrase}\")";
            } else {
                $phrases[] = "   '$FieldName' => gettext(\"$FieldName\")";
            }
        }

        $content .= join(",\n", $phrases);
        $content .= "\n   );\n";
        $output['/**phrases**/'] = $content;


        //ownerField
        printd("m. getting owner field\n", 1);
        if($this->OwnerField) {
            $content = $this->OwnerField;
        } else {
            $content = '';
        }
        $output['/**ownerField**/'] = "\$ownerField = '". $content . "';\n";

        //validation
        $content = '';
        foreach($Screen->Fields as $field){
            if($field->isEditable()){
                $vString = $this->ModuleFields[$field->name]->validate;
                if (0 < strlen($vString)){
                    //$content .= "\$vMsgs .= Validate(\$data['{$field->name}'], ShortPhrase(\$phrases['{$field->name}']), '{$vString}');\n";
    
                    $content .= "\$vMsg = Validate(\$data['{$field->name}'], ShortPhrase(\$phrases['{$field->name}']), '{$vString}');
            if(\$vMsg != ''){
                \$vMsgs .= \$vMsg;
                \$fields['{$field->name}']->invalid = TRUE;
            }\n";
                }
            }
        }
        $output['/**VALIDATE_FORM**/'] = $content;


        //SQL statements:
        $output['/**SQL|GET**/'] = $this->generateGetSQL($ScreenName);
        
        $needsReGet = false;
        foreach($Screen->Fields as $field){
            if($field->needsReGet()){
                $needsReGet = true;
            }
        }
        if(!$needsReGet){
            
            $output['/**RE-GET_BEGIN**/'] = '/**-remove_begin-**/'; //"\nif(0){\n";
            $output['/**RE-GET_END**/'] = '/**-remove_end-**/'; //"\n}\n";
        }

        if ($this->CheckForEditableFields($ScreenName)){

            //inserts
            $output['**SQL|INSERT**'] = $this->generateInsertSQL(&$Screen, false, 'var');

            //updates
            $output['**SQL|UPDATE**'] = $this->generateUpdateSQL(&$Screen, 'var');

            //deletes
            $output['**SQL|DELETE**'] = $this->generateDeleteSQL(&$Screen, 'var');
            $output['**SQL|INSERT_LOG_DELETE**'] = $this->generateDeleteLogSQL(&$Screen, 'var');

            $output['**SQL|INSERT_LOG**'] = $this->generateInsertSQL(&$Screen, true, 'var');

        } else {
            $output['/**DB_SAVE_BEGIN**/'] = '/**-remove_begin-**/'; //"\nif(0){\n//the following has been commented out\n//because this screen has no editable fields";
            $output['/**DB_SAVE_END**/'] = '/**-remove_end-**/'; //"}";
        }

        $output['/**tabs|EDIT**/'] = $this->generateTabs($ScreenName);
        if(empty($this->addNewName)){
            $addNewName = $this->SingularRecordName;
        } else {
            $addNewName = $this->addNewName;
        }
        $output['/**tabs|ADD**/'] = "      \$tabs['{$ScreenName}'] = array( \"\", gettext(\"New {$addNewName}\") );\n";

        //handle sub-fields - we "flatten" the subfield hierarchy
        $selectFields = array();
        foreach($Screen->Fields as $sfName => $sf){
            $selectFields = array_merge($selectFields, $sf->getRecursiveFields());
        }
        
        $output['/**data**/'] = "'".implode("' => '',\n         '", array_keys($selectFields))."' => ''";

        $output['/**nextScreen**/'] = $this->getNextScreen($ScreenName);

        //delete button goes on the first edit screen
        if ($this->getFirstEditScreen() == $ScreenName){
            $output['/**deletelink**/'] = "view.php?\$tabsQS&delete=1";
        } else {
            $output['/**deletelink**/'] = "";
        }
        
        if(empty($this->recordLabelField)){
            $output['/**RecordLabelField**/'] = "\$recordLabelField = 'Record ' . \$recordID;";
        } else {
            $output['/**RecordLabelField**/'] = "\$recordLabelField = \$data['{$this->recordLabelField}'];";
        }
        
        printd( "m.BuildEditScreen: end ({$this->ModuleID}:{$ScreenName})\n", 1);
        return $output;
    } //BuildEditScreen



    function BuildSearchScreen($ScreenName){

        printd( "m.BuildSearchScreen: begin ({$this->ModuleID}:{$ScreenName})\n", 1);
        
        $output['/**module_name**/'] = $this->Name;
        $output['/**singular_record_name**/'] = $this->SingularRecordName;
        $output['/**plural_record_name**/'] = $this->PluralRecordName;

        //fields
        printd( "Generating screen fields:\n", 1);
        $Screen = $this->getScreen($ScreenName);
        $content = "\$fields = unserialize('";
        $content .=
            str_replace("'", "\\'",
                str_replace("\\", "\\\\",
                    serialize($Screen->Fields)
                )
            );
        $content .= "');\n";
        $output['/**fields**/'] = $content;

        //module fields
        printd( "Generating module fields:\n", 1);


        $moduleFields = array();
        foreach ($this->ModuleFields as $fieldname => $moduleField){
            switch(get_class($moduleField)){
                case 'tablefield':
                    
                    break;
                case 'foreignfield':
                case 'codefield':
                    $moduleField->foreignTableAlias = GetTableAlias($moduleField, $this->ModuleID);
                    break;
                case 'remotefield':
                    $moduleField->remoteTableAlias = GetTableAlias($moduleField, $this->ModuleID);
                    //die($moduleField->remoteTableAlias);
                    break;
                case 'dynamicforeignfield':
                case 'combinedfield':
                case 'calculatedfield':
                case 'summaryfield':
                case 'recordmetafield':
                case 'rangefield':
                case 'linkfield':
                    break;
                default:
                    print_r($moduleField);
                    die('m. BuildSearchScreen: unknown modulefield type?');
            }
            
            //reduce the size by removing the phrase
            unset($moduleField->phrase);
            $moduleFields[$fieldname] = $moduleField;
        }


        $content = "\$moduleFields = unserialize('";
        $content .=
            str_replace("'", "\\'",
                str_replace("\\", "\\\\",
                    serialize($moduleFields)
                )
            );
        $content .= "');\n";
        $output['/**module_fields**/'] = $content;



        //list screen fields
        printd( "Generating list fields:\n", 1);
        $content = "\$listFields = unserialize('";
        $content .=
            str_replace("'", "\\'",
                str_replace("\\", "\\\\",
                    serialize(array_keys($this->getListFields()))
                )
            );
        $content .= "');\n";
        $output['/**list_fields**/'] = $content;



        //loop through list fields to find some with link fields
        foreach ($this->getListFields() as $listField){
            if(!empty($listField->linkField)){
                $linkFields[] = "'{$listField->name}' => '{$listField->linkField}'";
            }
        }

        $content = "\$linkFields = array(\n";
        if(count($linkFields) > 0){
            $content .= join(",\n", $linkFields);
        } else {
            print "no link fields\n";
        }
        $content .= "\n      );\n";
        $output['/**linkFields**/'] = $content;



        //list of date fields (needed to insert calendar code)
        $dateFields = array();
        foreach($Screen->Fields as $field){

            if ("datefield" == get_class($field)){
                $dateFields[] = $field->name;
            }
        }

        if (count($dateFields) > 0){
            $content = "\$content .= \"\n<script type=\\\"text/javascript\\\">\n";
            foreach($dateFields as $fieldName){
                $content .= "Calendar.setup({\n";
                $content .= "   inputField : \\\"{$fieldName}_f\\\",\n";
                //$content .= "   ifFormat   : \\\"\".\$User->getDateFormatCal().\"\\\",\n";
                $content .= "\".\$User->getCalFormat().\"\n";
                $content .= "   button     : \\\"cal_{$fieldName}_f\\\"\n";
                $content .= "});\n";

                $content .= "Calendar.setup({\n";
                $content .= "   inputField : \\\"{$fieldName}_t\\\",\n";
                //$content .= "   ifFormat   : \\\"\".\$User->getDateFormatCal().\"\\\",\n";
                $content .= "\".\$User->getCalFormat().\"\n";
                $content .= "   button     : \\\"cal_{$fieldName}_t\\\"\n";
                $content .= "});\n";

            }
            $content .= "</script>\\n\";";

            $output['/**dateFields**/'] = $content;
        }



        //phrases array
        printd( "Building phrases:\n", 1);

        $content = "\$phrases = array(\n";
        foreach($Screen->Fields as $FieldName=>$Field){
        
            if(empty($Field->phrase)){ //most fields have no phrase, so use the ModuleField phrase
            
                switch(get_class($Field)){
                    case 'combofield':
                    case 'personcombofield':
                    case 'orgcombofield':
                    case 'codecombofield':
                    case 'radiofield':
                    case 'coderadiofield':
                        //get the list source field instead of the ID field
                        $listSourceFieldName = substr($FieldName, 0, -2);
                        $t_mf = $this->ModuleFields[$listSourceFieldName];
                        break;
                    
                    default:
                        $t_mf = $this->ModuleFields[$FieldName];
                }
            
                if (!empty($t_mf->phrase)){
                    $phrases[] = "   '$FieldName' => gettext(\"{$t_mf->phrase}\")";
                } else {
                    $phrases[] = "   '$FieldName' => gettext(\"$FieldName\")";
                }
            } else {
                $phrases[] = "   '$FieldName' => gettext(\"{$Field->phrase}\")";
            }
        }

        $content .= join(",\n", $phrases);
        $content .= "\n   );\n";
        $output['/**phrases**/'] = $content;


        //ownerField filter
        printd("m. getting owner field filter\n", 1);
        if($this->OwnerField) {
            $ownerFieldFilter = $this->getOwnerFieldFilter();
            $content = "\$ownerFieldFilter = \"". $ownerFieldFilter . "\";\n";
            
            $output['/**ownerFieldFilter**/'] = $content;
        } else {
            $output['/**ownerFieldFilter**/'] = "//This module has no owner field\n";
        }

        $output['/**screen_phrase**/'] = ShortPhrase($Screen->phrase);

        printd( "m.BuildSearchScreen: end ({$this->ModuleID}:{$ScreenName})\n", 1);
        return $output;
    } //BuildSearchScreen

} //end class Module



//SubModule class
class SubModule extends Module {
    var $parentModuleID;
    var $parentKey; //key field of parent module
    var $localKey;  //local key field
    var $conditions = array();

    //the following are deprecated... to be retired
    var $Join;  //the sub-module's join expression
    var $Condition; //the row selection condition (generic)
    var $ConditionField; //specific field: will also be used for INSERTs
    var $ConditionValue; //value

function &Factory($element, $moduleID)
{

//print "submodule factory\n";
//print_r($element);
    $subModule =& new SubModule(
        $element->attributes['moduleID'],
        $moduleID,
        $element->attributes['parentKey'],
        $element->attributes['localKey'],
        $element->attributes['join'],
        $element->attributes['condition'],
        $element->attributes['conditionField'],
        $element->attributes['conditionValue']
    );

    //look for conditions
    if(count($element->c) > 0){
        foreach($element->c as $sub_element){
            if('SubModuleCondition' == $sub_element->type){
                $subModule->AddCondition(
                    $sub_element->attributes['field'],
                    $sub_element->attributes['value']
                );
            }
        }
    }
/*
print "conditions for {$element->attributes['moduleID']}\n";
print_r($subModule->conditions);
print "join parameters for {$element->attributes['moduleID']}\n";
print "parentKey: {$subModule->parentKey}\n";
print "localKey:  {$subModule->localKey}\n";
*/

    //maintains some metadata in the `mod` table:

    //determines whether this is a Central Sub-Module:
    $isCentralSub = 0;
    if(!empty($subModule->parentKey)){
        foreach($subModule->conditions as $fieldName => $fieldValue){
            if($fieldValue == $moduleID){
                $isCentralSub = 1;
                $CentralSubSnip = ', Association = 1';
            }
        }
    }

    global $dbh;
    $SQL = "UPDATE `mod` SET SubModule = 1 {$CentralSubSnip}, _ModDate = NOW() WHERE ModuleID = '{$subModule->ModuleID}';";
    $result = $dbh->query($SQL);
    dbErrorCheck($result);


    return $subModule;
}

function SubModule (
    $moduleID,
    $parentModuleID,
    $parentKey,
    $localKey,
    $join,
    $condition,
    $conditionField,
    $conditionValue
    )
{
    global $foreignModules;
    $foreignModules[$moduleID] = $this; //&$this;

    $this->ModuleID = $moduleID;
    $this->parentModuleID = $parentModuleID;
    $this->parentKey = $parentKey;
    $this->localKey = $localKey;

    Parent::Module($moduleID);

    //support for legacy format
    $this->Join = $join;
    $this->Condition = $condition;
    $this->ConditionField = $conditionField;
    $this->ConditionValue = $conditionValue;

    //converting legacy format to new
    if(!empty($conditionField)){
        $this->AddCondition($conditionField, $conditionValue);
    }
    if(!empty($condition)){
        $t_conditions = split(' AND ', $condition);
        foreach($t_conditions as $t_condition){
            $t_parts = explode('=', $t_condition);
            $t_fieldName = trim($t_parts[0]);
            $t_pos = strpos($t_fieldName, '.');
            if(FALSE !== $t_pos){
                $t_fieldName = substr($t_fieldName, $t_pos+1);
            }
            $this->AddCondition($t_fieldName, trim($t_parts[1]));
        }
    }
    if(empty($this->parentKey) && !empty($join)){
        $t_parts = array();
        $t_parts = explode('=', $join);
        foreach($t_parts as $t_part){
            print "t_part: $t_part\n";
            $t_subparts = explode(".", $t_part);
            print_r($t_subparts);
            switch(trim($t_subparts[0])){
            case $parentModuleID:
                $this->parentKey = trim($t_subparts[1]);
                break;
            case $moduleID:
                $this->localKey = trim($t_subparts[1]);
                break;
            default:
                print "t_subpart: {$t_subparts[0]}\n";
                die("unknown syntax in $join\n");
            }
        }
    }

    printd( "SubModule constructor: $moduleID (end)\n", 1);
}


function AddCondition($fieldName, $fieldValue)
{
    $this->conditions[$fieldName] = $fieldValue;
}




function makeExportSubReportElement()
{
    $exportFields = array();
    $tableFields = array();
    foreach($this->ModuleFields as $fieldName => $moduleField){
        switch(get_class($moduleField)){
        case 'tablefield':
            $tableFields[$fieldName] = $fieldName;
            //no break!
        case 'remotefield':
            $exportFields[$fieldName] = $fieldName;
            break;
        default:
            break;
        }
    }

    unset($exportFields['_ModDate']);
    unset($exportFields['_ModBy']);
    unset($exportFields['_Deleted']);

    $subElements = array();
    if(count($this->conditions) > 0){
        foreach($this->conditions as $conditionField => $conditionValue){
            if(!array_key_exists($conditionField, $tableFields)){
                return false; //bailing out
            }
            $subElements[] = & new Element($conditionField, 'SubReportCondition', array('field' => $conditionField, 'value' => $conditionValue));
        }
    }

    foreach($exportFields as $exportField){
        $subElements[] = & new Element($exportField, 'ReportField', array('name' => $exportField));
    }


                            //$name, $type, $attributes
    $element =& new Element(
        'Sub_'.$this->ModuleID,
        'SubReport',
        array(
            'moduleID' => $this->ModuleID,
            'title' => 'Sub_'.$this->ModuleID
        ),
        $subElements
        );

/*print "ExportSubReportElement ";
print_r($element);*/
    return $element;
}

} //end SubModule class



require_once CLASSES_PATH . '/data_view.class.php';

//SubDataView class
class SubDataView {
    var $dataViewID;
    var $parentModuleID;
    var $parentKey; //key field of parent module
    var $localKey;  //local key field
    var $conditions = array();
    var $_dataView;
    var $ModuleFields; //slightly faked. This is a "shim" to make the ViewField class work with the DataView

function &Factory($element, $moduleID)
{
    return new SubDataView($element, $moduleID);
}

function SubDataView($element, $moduleID)
{
print "SubDataView (begin)\n";
print_r($element);

    $this->dataViewID = $element->attributes['dataViewID'];
    $this->parentModuleID = $moduleID;
    $this->parentKey = $element->attributes['parentKey'];
    $this->localKey = $element->attributes['localKey'];

    $fileName = XML_PATH ."/{$this->dataViewID}_DataView.xml";
     if(file_exists($fileName)){

        include_once CLASSES_PATH . '/data_view_map.class.php';
        include_once CLASSES_PATH . '/data_view.class.php';

        $dataViewMap = new DataViewMap($fileName);
        //indent_print_r($dataViewMap, 'dataViewFile: '.$dataViewFile);
        $dataView = $dataViewMap->generateDataView();

    } else {
        trigger_error("Could not find file $fileName.", E_USER_ERROR);
    } 

    $this->_dataView =& $dataView;

    //look for conditions
    if(count($element->c) > 0){
        foreach($element->c as $sub_element){
            if('SubDataViewCondition' == $sub_element->type){
                $this->AddCondition(
                    $sub_element->attributes['field'],
                    $sub_element->attributes['value']
                );
            }
        }
    }

    $this->ModuleFields = $dataView->getModuleFields();

    global $foreignModules;
    $foreignModules[$this->dataViewID] =& $this;
print "SubDataView (end)\n";
}

function AddCondition($fieldName, $fieldValue)
{
    $this->conditions[$fieldName] = $fieldValue;
}

function generateListSQL($grid){
    return $this->_dataView->generateListSQL($grid);
}

function generateListCountSQL($grid){
    return $this->_dataView->generateListCountSQL($grid);
}

function checkTableExists($tableName)
{
    return true; //fake the table
}

function checkTableStructure($tableName)
{
    return null; //fake the check
}

}












//ForeignModule class
class ForeignModule extends Module {
    //var $Parent; //reference to module that created this object

    function Factory($element, $moduleID){
        //only parse the submodule file of the main module being parsed
        //check for a submodule definition file
        $foreignModule = new ForeignModule(
            $element->attributes['moduleID']
        );
    }
    
    function ForeignModule($moduleID){
        
        
        global $foreignModules;
        $foreignModules[$moduleID] = &$this;
        
        Parent::Module($moduleID);
    }
} //end ForeignModule class







//Screen classes:
class Screen { //abstract
var $moduleID;
var $name;
var $phrase;
var $allowEdit;
var $customCodes = array();
var $Fields = array();
var $templateFieldName;
var $genFileName;
var $tabConditionModuleID = '';

function Factory($element, $moduleID)
{
    return false; //override this method
}

function DocFactory($element, $moduleID)
{
    return new ScreenDoc($element, $moduleID);
}

function addField(&$field)
{
    $this->Fields[$field->name] = &$field;
}

function addCustomCode($customCode)
{
    $this->customCodes[$customCode->getTag()] = $customCode;
}

function build()
{
    return false; //override this method
}

/**
 *  returns true for screens that handle one record at the time
 */
function isRecordScreen()
{
    return true;
}
} //end class Screen







class ViewScreen extends Screen {
var $Grids = array();


function &Factory($element, $moduleID){
    return new ViewScreen(&$element, $moduleID);
}


function ViewScreen($element, $moduleID)
{
    $this->name = $element->attributes['name'];
    $this->phrase = $element->attributes['phrase'];
    $this->allowEdit = false;
    $this->moduleID = $moduleID;
    $this->templateFieldName = "ViewModel.php";
    $this->genFileName = "{$moduleID}_View.gen";
    
    foreach($element->c as $field_element){
        if('Field' == substr($field_element->type, -5)){
            $this->addField($field_element->createObject($moduleID));
        } elseif('Grid' == substr($field_element->type, -4)){
            $this->addGrid($field_element->createObject($moduleID));
        } elseif('ViewScreenSection' == $field_element->type){
            $this->sections[$field_element->name] = $field_element->createObject($moduleID);
        } elseif('CustomCode' == $field_element->type){
            $this->addCustomCode($field_element->createObject($moduleID));
        } else {
            die("ViewScreen {$this->name}: element type {$field_element->type} not handled");
        }
    }
    
}


function addField(&$field)
{
    if ($field->isEditable()){
        print "ERROR: can't add a ";
        print get_class($field);
        print " field to a View screen.\n";
    } else {
        $this->Fields[$field->name] = &$field;
    }
}


function addGrid(&$grid)
{
    if ($grid->isEditable()){
        print "ERROR: can't add a ";
        print get_class($grid);
        print " grid to a View screen.\n";
    } else {
        $gridID = get_class($grid) . '_' . $grid->moduleID;
        $this->Grids[$gridID] = &$grid;
    }
}


function build()
{
    $module =& GetModule($this->moduleID);
    return $module->buildViewScreen();
}
} //end class ViewScreen








class EditScreen extends Screen {
var $Grids = array();
var $linkToModuleID;
var $sections = array(); //ViewScreenSections

function &Factory($element, $moduleID){
    return new EditScreen($element, $moduleID);
}


function EditScreen(&$element, $moduleID)
{
    $this->name = $element->attributes['name'];
    $this->phrase = $element->attributes['phrase'];
    $this->allowEdit = true;
    $this->moduleID = $moduleID;
    $this->linkToModuleID = $element->attributes['linkToModuleID'];
    $this->tabConditionModuleID = $element->attributes['tabConditionModuleID'];

    $this->templateFieldName = "EditModel.php";
    $this->genFileName = "{$moduleID}_Edit{$this->name}.gen";

    foreach($element->c as $field_element){
        if('GridField' == substr($field_element->type, -9)){
            die("Screen {$this->name} has a GridField {$field_element->name} which is not part of a grid.");
        }
        if('Field' == substr($field_element->type, -5)){
            $this->addField($field_element->createObject($moduleID));
        } elseif('Grid' == substr($field_element->type, -4)){
            $this->addGrid($field_element->createObject($moduleID));
        } elseif('CustomCode' == $field_element->type){
            $this->addCustomCode($field_element->createObject($moduleID));
        } elseif('RecordSummaryFieldsRef' == $field_element->type){
            print "RecordSummaryFieldsRef in $moduleID {$element->name}\n";

            //look up SummaryFields section
            $module = GetModule($moduleID);

            $summaryfields_element = $module->_map->selectFirstElement('RecordSummaryFields');
            if(!empty($summaryfields_element) && count($summaryfields_element->c) > 0){
                foreach($summaryfields_element->c as $summaryfield_element){
                    $this->addField($summaryfield_element->createObject($moduleID));
                }
            }
        } else {
            die("EditScreen {$this->name}: element type {$field_element->type} not handled");
        }
    }
}


function addGrid(&$grid)
{
    //$this->Grids[$grid->moduleID] = $grid;
    $gridID = get_class($grid) . '_' . $grid->moduleID;
    $this->Grids[$gridID] = &$grid;
}


function build()
{
    $module =& GetModule($this->moduleID);
    return $module->buildEditScreen($this->name);
}

} //end class EditScreen


//fake wrapper class to EditScreen
class EditScreenLink extends EditScreen
{

function &Factory(&$element, $moduleID)
{
    return EditScreen::Factory($element, $moduleID);
}
}






class SearchScreen extends Screen {
function &Factory($element, $moduleID){
    return new SearchScreen($element, $moduleID);
}

function SearchScreen(&$element, $moduleID)
{
    $this->name = $element->attributes['name'];
    $this->phrase = $element->attributes['phrase'];
    $this->allowEdit = false;
    $this->moduleID = $moduleID;

    $this->templateFieldName = "SearchModel.php";
    $this->genFileName = "{$moduleID}_Search.gen";

    foreach($element->c as $field_element){
        if('GridField' == substr($field_element->type, -9)){
            die("Screen {$this->name} has a GridField {$field_element->name} which is not part of a grid.");
        }
        if('Field' == substr($field_element->type, -5)){
            $this->addField($field_element->createObject($moduleID));
        } else {
            die("The search screen '{$this->name}' does not support {$field_element->type}");
        }
    }

}

function build()
{
    $module =& GetModule($this->moduleID);
    return $module->buildSearchScreen($this->name);
}

function isRecordScreen()
{
    return false;
}
} //end class SearchScreen


class ListScreen extends Screen {
var $orderByFields = array();

function &Factory($element, $moduleID){
    return new ListScreen($element, $moduleID);
}


function ListScreen(&$element, $moduleID)
{
    if(empty($element->attributes['name'])){
        $this->name = 'List';
    } else {
        $this->name = $element->attributes['name'];
    }
    $this->phrase = $element->attributes['phrase'];
    $this->allowEdit = false;
    $this->moduleID = $moduleID;

    $this->templateFieldName = "ListModel.php";
    $this->genFileName = "{$this->moduleID}_List.gen";



    foreach($element->c as $field_element){
        if('GridField' == substr($field_element->type, -9)){
            die("Screen {$this->name} has a GridField {$field_element->name} which is not part of a grid.");
        }
        if('OrderByField' == $field_element->type){
            //add invisible field if not in the selects already
            if(!isset($this->Fields[$field_element->name])){
                //make invisiblefield element
                /*$invisibleField_element = new Element($field_element->name, 'InvisibleField', array('name' => $field_element->name));
                $field_object = $invisibleField_element->createObject($this->moduleID);
                */
                $field_object = MakeObject($this->moduleID, $field_element->name, 'InvisibleField', array('name' => $field_element->name));
                $this->AddField($field_object);
                //unset($invisibleField_element);
                unset($field_object);
            }

            //add to $this->orderByFields
            $this->orderByFields[$field_element->name] = $field_element->attributes['direction'];

        } elseif('Field' == substr($field_element->type, -5)){
            $this->addField($field_element->createObject($moduleID));
        } elseif('CustomCode' == $field_element->type) {
            $customCode = $field_element->createObject($moduleID);
            $this->addCustomCode($customCode);
        } else {
            die("The search screen '{$this->name}' does not support {$field_element->type}");
        }
    }
    
    $module =& GetModule($moduleID);
    if($module->useBestPractices){
print "found best practices\n";
        $field_object = MakeObject(
            $this->moduleID,
            'IsBestPractice',
            'ListField', //InvisibleField
            array(
                'name' => 'IsBestPractice'
            )
        );
        $this->AddField($field_object);
    } else {
print "no best practices submodule\n";
    }

}


function build()
{
    $debug_prefix = debug_indent("ListScreen->build() {$this->moduleID}:");
    echo "$debug_prefix (begin)\n";

    $module =& GetModule($this->moduleID);

    /*module properties*/
    $output['/**module_name**/'] = $module->Name;
    $output['/**singular_record_name**/'] = $module->SingularRecordName;
    $output['/**plural_record_name**/'] = $module->PluralRecordName;

    $headers = array();
    $linkFields = array();

    /*list column headers and link fields*/
    foreach ($this->Fields as $listField){
        if(!empty($listField->phrase)){
            $headers[] = "   '{$listField->name}' => gettext(\"{$listField->phrase}\")";
        } else {
            $headers[] = "   '{$listField->name}' => ''";
        }
        if(!empty($listField->linkField)){
            $linkFields[] = "   '{$listField->name}' => \"{$listField->linkField}\"";
        }
    }

    $content = "\$headers = array(\n";
    $content .= join(",\n", $headers);
    $content .= "\n   );\n";
    $output['/**headers**/'] = $content;

    $content = "\$linkFields = array(\n";
    $content .= join(",\n", $linkFields);
    $content .= "\n   );\n";
    $output['/**linkFields**/'] = $content;

    if(true === $module->useBestPractices){
        $content = "\$useBestPractices = true;\n";
        $output['/**useBestPractices**/'] = $content;
    }

    //ownerField filter
    echo "$debug_prefix getting owner field filter\n";
    if($module->OwnerField) {
        $ownerFieldFilter = $module->getOwnerFieldFilter();
        $content = "\$ownerFieldFilter = \"". $ownerFieldFilter . "\";\n";

        $output['/**ownerFieldFilter**/'] = $content;
    } else {
        $output['/**ownerFieldFilter**/'] = "//This module has no owner field\n";
    }

    //list screen fields
    $content = "\$listFields = unserialize('";
    $content .= escapeSerialize(array_keys($this->Fields));
    $content .= "');\n";
    $output['/**list_fields**/'] = $content;

    $pkField = end($module->PKFields);

    //field alignment
    $content = "\$fieldAlign = array(\n";
    foreach ($this->Fields as $listField){
        if($listField->isVisible()){
            $dType = $module->ModuleFields[$listField->name]->getDataType();
            $fieldTypes[] = "'{$listField->name}' => '$dType'";
    
            if($pkField == $listField->name){
                $fieldAlign[$listField->name] = "'{$listField->name}' => 'center'";
            } else {
                if(empty($listField->listColAlign)){
                    switch ($dType){
                        case 'int':
                        case 'tinyint':
                        case 'float':
                        case 'money':
                            $fieldAlign[$listField->name] = "'{$listField->name}' => 'right'";
                            break;
                        case 'date':
                        case 'time':
                        case 'datetime':
                        case 'bool';
                            $fieldAlign[$listField->name] = "'{$listField->name}' =>  'center'";
                            break;
                        default:
                            $fieldAlign[$listField->name] = "'{$listField->name}' =>  'left'";
                            break;
                    }
        
                } else {
                    $fieldAlign[] = "'{$listField->name}' => '{$listField->listColAlign}'";
                }
            }
        } else {
            $fieldAlign[] = "'{$listField->name}' => 'hide'"; //checked by ListRenderer
        }
    }
    //change ID column (always first) to align center
    //$fieldAlign[0] = "   'center'";
    /*$content .= join(",\n", $fieldAlign);
    $content .= "\n   );\n";*/
    $content = "\$fieldAlign = array(\n";
    $content .= join(",\n", $fieldAlign);
    $content .= "\n);\n";
    $output['/**fieldAlign**/'] = $content;
    
    $content = "\$fieldTypes = array(\n";
    $content .= join(",\n", $fieldTypes);
    $content .= "\n);\n";
    $output['/**fieldTypes**/'] = $content;

    //set the default ORDER BY columns
    if(count($this->orderByFields) > 0){
        $orderBys = $this->orderByFields;
    } else {
        $orderByField = reset($this->Fields);
        $orderBys = array($orderByField->name => false);
    }

    $output['/**defaultOrderBys**/'] = '$defaultOrderBys = unserialize(\''.escapeSerialize($orderBys).'\');';

    $output['/**tabs|EDIT**/'] = $module->generateTabs('List');
    $output['/**tabs|VIEW**/'] = $module->generateTabs('List', "view");


    //custom code
    if(count($this->customCodes) > 0){
        foreach($this->customCodes as $location => $codeObject){
            $output[$location] = $codeObject->getContent();
        }
    }

    echo "$debug_prefix (end)\n";
    debug_unindent();
    return $output;
}

function isRecordScreen()
{
    return false;
}
} //end class ListScreen




class RecordReportScreen extends Screen {
    function &Factory($element, $moduleID){
        return new RecordReportScreen($element, $moduleID);
    }
    function RecordReportScreen(&$element, $moduleID)
    {
        $this->name = $element->attributes['name'];
        $this->phrase = $element->attributes['phrase'];
        $this->allowEdit = false;
        $this->moduleID = $moduleID;
    }
}


class ListReportScreen extends Screen {

    function &Factory($element, $moduleID){
        return new ListReportScreen($element, $moduleID);
    }
    function ListReportScreen(&$element, $moduleID)
    {
        $this->name = $element->attributes['name'];
        $this->phrase = $element->attributes['phrase'];
        $this->allowEdit = false;
        $this->moduleID = $moduleID;
    }

    function isRecordScreen()
    {
        return false;
    }
}


class ViewScreenSection {
    var $Fields = array();
    var $Grids = array();
    var $allowEdit;
    var $moduleID;

    function &Factory($element, $moduleID){
        return new ViewScreenSection($element, $moduleID);
    }
    
    function ViewScreenSection(&$element, $moduleID)
    {
        $this->name = $element->attributes['name'];
        $this->phrase = $element->attributes['phrase'];
        $this->allowEdit = false;
        $this->moduleID = $moduleID;
        
        foreach($element->c as $field_element){
            if('Field' == substr($field_element->type, -5)){
                $this->addField($field_element->createObject($moduleID));
            } elseif('Grid' == substr($field_element->type, -4)){
                $this->addGrid($field_element->createObject($moduleID));
            } else {
                die("ViewScreenSection ($this->name): element type {$field_element->type} not handled");
            }
        }
    }

    function addField(&$field){
        if ($field->isEditable()){
            print "ERROR: can't add a ";
            print get_class($field);
            print " field to a View screen section.\n";
        } else {
            $this->Fields[$field->name] = &$field;
        }
    }

    function addGrid(&$grid){
        if ($grid->isEditable()){
            print "ERROR: can't add a ";
            print get_class($grid);
            print " grid to a View screen section.\n";
        } else {
            //$this->Grids[$grid->moduleID] = $grid;
            $gridID = get_class($grid) . '_' . $grid->moduleID;
            $this->Grids[$gridID] = &$grid;
        }
    }
}





class ListField
{
var $name;
var $phrase;
var $linkField; //name of module field that holds a URL
var $listColAlign;


function &Factory($element, $moduleID)
{
    return new ListField ($element, $moduleID);
}


function ListField(&$element, $moduleID)
{
    $this->name = $element->attributes['name'];
    $this->phrase = $element->attributes['phrase'];
    $this->linkField = $element->attributes['link'];
    $this->listColAlign = $element->attributes['align'];
}


//returns an array of names of subfields (recursively), including this field
function getRecursiveFields()
{
    $subFields = array();
    $subFields[$this->name] = $this;
    return $subFields;
}


function isEditable()
{
    return false;
}

function isVisible()
{
    return true;
}
} //end class ListField




class CustomCode {
    var $location;
    var $tagPrefix;
    var $content;
    function Factory($element, $moduleID){
        return new CustomCode (
            $element->attributes['location'],
            $element->attributes['tagPrefix'],
            $element->c
        );
    }
    
    function DocFactory($element, $moduleID){
        return new CustomCodeDoc($element, $moduleID);
    }
    
    function CustomCode($pLocation, $pTagPrefix, $contentLines){
        $this->location = $pLocation;
        if(empty($pTagPrefix)){
            $this->tagPrefix = 'CUSTOM_CODE|';
        }
        if('none' == $pTagPrefix){
            $this->tagPrefix = '';
        }
        $this->content = '';
        foreach($contentLines as $contentLine){
            $this->content .= trim($contentLine->content)."\n";
        }
    }
    function getTag(){
        return '/**'.$this->tagPrefix . $this->location.'**/';
    }
    function getContent(){
        return $this->content;
    }
}









//this class defines the database-specific types and styles of each RDBMS
//supports MySQL and MS SQL Server ('MSSQL')
class DBFormat {
    var $dataTypes = array();   //list of translated data type names
    var $PKDeclaration;         //string for the Primary Key drclaration
    var $flags = array();       //list of translated column options

    function DBFormat($targetDB){
        //translates to
        switch ($targetDB){
        case 'MSSQL':
            $this->dataTypes = array(
                'bool' => 'bit',
                'tinyint' => 'int',
                'int' => 'int',
                'varchar(5)' => 'nvarchar(5)',
                'varchar(10)' => 'nvarchar(10)',
                'varchar(15)' => 'nvarchar(15)',
                'varchar(20)' => 'nvarchar(20)',
                'varchar(25)' => 'nvarchar(25)',
                'varchar(30)' => 'nvarchar(30)',
                'varchar(50)' => 'nvarchar(50)',
                'varchar(128)' => 'nvarchar(128)',
                'varchar(255)' => 'nvarchar(255)',
                'date' => 'shortdatetime',
                'time' => 'shortdatetime',
                'datetime' => 'datetime',
                'text' => 'ntext',
                'money' => 'money',
                'decimal(2)' => 'decimal(12,2)',
                'float' => 'float'
            );
            $this->flags = array(
                'not null' => 'NOT NULL',
                'unsigned' => '', //nothing corresponding
                'auto_increment' => 'IDENTITY (1,1)'
            );
            $this->PKDeclaration = "CONSTRAINT PK_$tableName PRIMARY KEY";
            //$ixStyle = "separate";
            break;
        default:    //such as MySQL
            $this->dataTypes = array(
                'bool' => 'bool',
                'int' => 'int',
                'tinyint' => 'tinyint',
                'varchar(5)' => 'varchar(5)',
                'varchar(10)' => 'varchar(10)',
                'varchar(15)' => 'varchar(15)',
                'varchar(20)' => 'varchar(20)',
                'varchar(25)' => 'varchar(25)',
                'varchar(30)' => 'varchar(30)',
                'varchar(50)' => 'varchar(50)',
                'varchar(128)' => 'varchar(128)',
                'varchar(255)' => 'varchar(255)',
                'date' => 'date',
                'time' => 'time',
                'datetime' => 'datetime',
                'text' => 'text',
                'money' => 'decimal(12,4)',
                'decimal(2)' => 'decimal(12,2)',
                'float' => 'float'
            );
            $this->flags = array(
                'not null' => 'not null',
                'unsigned' => 'unsigned',
                'auto_increment' => 'auto_increment'
            );
            $this->PKDeclaration = "PRIMARY KEY";
            break;
        }
    }
}


//a dbquote function for preg_replace_callback
function aQuote($matches){
//  print "matches:\n";
//  print_r($matches);
//  print "\n";

    $matches[1] = str_replace('**' , '', $matches[1]);
    list($type, $val) = split(':', $matches[1]);

    switch($type){
    case 'localVar':
        return "\". \${$val} .\"";
        break;
    case 'fieldVal':
        return "\". dbQuote(\$data['{$val}']) .\"";
        break;
    case 'fieldChkVal':
        return "\". dbQuote(\$data['{$val}'], 'bool') .\"";
        break;
    case 'fieldDateVal':
        return "\". dbQuote(\$data['{$val}'], 'date') .\"";
        break;
    case 'fieldDateTimeVal':
        return "\". dbQuote(\$data['{$val}'], 'datetime') .\"";
        break;
    case 'fieldIntVal':
        return "\". dbQuote(\$data['{$val}'], 'int') .\"";
        break;
    case 'fieldMoneyVal':
        return "\". dbQuote(\$data['{$val}'], 'money') .\"";
        break;
    case 'UserID':
        return "\".\$User->PersonID.\"";
        break;
    case 'PR-ID':
        return "\".\$recordID.\"";
        break;
    case 'RecordID':
        return "\".\$recordID.\"";
        break;
    default:
        return $val;
    }

    //return "replaced";
}

//"extends" a module with reversed ForeignFields from an "extending" (dependent) module
function GetExtendedModule($ModuleID, $extendingModuleID){
    $debug_prefix = debug_indent("GetExtendedModule() $ModuleID, $extendingModuleID:");

    global $foreignModules;
    
    //parpe
    $extendedModule = GetModule($ModuleID);
    if($extendedModule->isExtendedByModuleID == $extendingModuleID){
        return $extendedModule;
    }
    
    //parse
    $extendingModule = GetModule($extendingModuleID);
    
    $extendedModule->parentModuleID = $extendingModule->parentModuleID;
/*
if(!empty($extendingModule->isExtendedByModuleID)){
    return $extendingModule;
    die("Attempts to extend an extending module");
}
*/
    $moduleInfo = GetModuleInfo($ModuleID);
    $extendedPK = $moduleInfo->getPKField();
    $extModuleInfo = GetModuleInfo($extendingModuleID);
    $extendingPK = $extModuleInfo->getPKField();
    
    $extendedMFNames = array_keys($extendedModule->ModuleFields);
    $new_elements = array();
    $extendedModuleMap = &$extendedModule->_map;
    foreach($extendedModuleMap->c as $map_element){
        if('ModuleFields' == $map_element->name){
            $extendedModuleFields_element = &$map_element;
            break;
        } 
    }
    foreach($extendingModule->ModuleFields as $name => $dummy){

        
        if(!in_array($name, $extendedMFNames)){
            print "$debug_prefix   adding field $name to $ModuleID\n";
            $extendingMF = $extendingModule->ModuleFields[$name];
            //translate the extending modulefield into a valid foreign field in the extended module
            switch(get_class($extendingMF)){
            case 'tablefield':
                $newFieldType = 'ForeignField';
                $listCondition = '';
                if($extendingModule->parentKey){
                    //$listCondition = $extendingModule->localKey." = '[*{$extendingModule->parentKey}*]'";
                    $listCondition = $extendingModule->localKey." = '/**RecordID**/'";
                }
                
                /*$conditions = array();
                
                if(count($extendingModule->conditions) > 0){
                    
                    foreach($extendingModule->conditions as $conditionField => $conditionValue){
                        $conditions[] = $conditionField .'='. $conditionValue;
                    }
                    $listCondition = join(' AND ', $conditions);
                }*/
                $attributes = array(
                    'name' => $name,
                    'type' => $extendingMF->dataType,
                    'localTable' => $ModuleID,
                    'key' => $extendedPK,
                    'foreignTable' => $extendingModuleID,
                    'foreignField' => $name,
                    'foreignKey' => $extendingModule->extendsModuleKey, //was $extendingPK (wrong)
                    'joinType' => 'left',
                    'phrase' => $extendingMF->phrase,
                    'defaultValue' => $extendingMF->defaultValue,
                    'listCondition' => $listCondition
                );

                break;
            case 'foreignfield':
                $newFieldType = 'ForeignField';
                $attributes = array(
                    'name' => $name,
                    'type' => $extendingMF->dataType,
                    'localTable' => $ModuleID,
                    'key' => $extendingMF->localKey,
                    'foreignTable' => $extendingMF->foreignTable,
                    'foreignField' => $extendingMF->foreignField,
                    'foreignKey' => $extendingMF->foreignKey,
                    'joinType' => 'left',
                    'phrase' => $extendingMF->phrase,
                    'defaultValue' => $extendingMF->defaultValue
                );
                break;
            case 'remotefield':
                $newFieldType = 'RemoteField';
                $attributes = array(
                    'name' => $name,
                    'type' => $extendingMF->dataType,
                    'localTable' => $extendingMF->ModuleID, 
                    'remoteModuleID' => $extendingMF->remoteModuleID,
                    'remoteModuleIDField' => $extendingMF->remoteModuleIDField,
                    'remoteRecordIDField' => $extendingMF->remoteRecordIDField,
                    'remoteField' => $extendingMF->remoteField,
                    'remoteDescriptorField' => $extendingMF->remoteDescriptorField,
                    'remoteDescriptor' => $extendingMF->remoteDescriptor,
                    'phrase' => $extendingMF->phrase,
                    'defaultValue' => $extendingMF->defaultValue,
                    'conditionModuleID' => $extendingModuleID //parse
                );
                break;
            default:
                //die('class '.get_class($extendingMF).' not handled in function GetExtendedModule');
                print "$debug_prefix class ".get_class($extendingMF)." not handled in function GetExtendedModule\n";
                break 2; //
            }
            $new_element = new Element($name, $newFieldType, $attributes);
            $extendedModuleFields_element->c[] = $new_element;
            
            //$newModuleField = $new_element->createObject($extendingModuleID);
            $newModuleField = $new_element->createObject($ModuleID); //this parses
            
            indent_print_r($newModuleField, 'new modulefield');
            
            $extendedModule->ModuleFields[$name] = $newModuleField;

//            print_r($extendedModule->ModuleFields[$name]);

        }
    }

    $extendedModule->isExtendedByModuleID = $extendingModuleID;
    $foreignModules[$ModuleID] = $extendedModule;

    debug_unindent();
    return $extendedModule;
}


?>
