#!/usr/bin/php
<?php

/**
 *  Generates a module from an XML spec file.
 *
 *  This file creates all the generated files necessary for a module, and uses
 *  the Module class to create or modify database tables that are needed.
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


$Project = $_SERVER[argv][1];
$Argument = $_SERVER[argv][2];
$SaveNavigationPhrases = $_SERVER[argv][3];


if(empty($Argument)){
    print '
    s2a-generate-module: Generates a spec2app module from an XML definition.

    BASIC USAGE:
    ./s2a-generate-module.php <project_name> <moduleID>

    SUGGESTED USAGE: Because of the amount of output, use the "less" command to
    handle it.
    ./s2a-generate-module.php <project_name> <moduleID> | less

    Requires 2 parameters: The name of the project, and a valid moduleID.

';
    die('Not enough parameters.');
}

print "s2a-generate-module: project = $Project\n";

//set_error_handler('error_flush');


//assumes we're in the 's2a' folder 
$site_folder = realpath(dirname($_SERVER['SCRIPT_FILENAME']).'');
$site_folder .= '/'.$Project;

//includes
$config_file = $site_folder . '/config.php';
if(!file_exists($config_file)){
    print "Config file not found at $config_file\n";
    exit;
}
$gen_config_file = $site_folder . '/gen-config.php';
if(!file_exists($gen_config_file)){
    print "Config file not found at $gen_config_file\n";
    exit;
}

//get settings
include_once $config_file;
include_once $gen_config_file;

set_include_path(PEAR_PATH . PATH_SEPARATOR . get_include_path());

require_once PEAR_PATH . '/DB.php' ;  //PEAR DB class

//this include contains utility functions
include_once INCLUDE_PATH . '/parse_util.php';

/**
 * Sets custom error handler
 */
set_error_handler('handleError');

//connect with superuser privileges - regular user has no permission to
//change table structure
global $dbh;
$dbh = DB::connect(GEN_DB_DSN);

dbErrorCheck($dbh);



/**
 * Defines execution state as 'generating'.  Several classes and
 * functions behave differently because of this flag.
 */
DEFINE('EXEC_STATE', 4);

//module builder class
include_once CLASSES_PATH . '/module.class.php';

global $moduleParseList;
unset($moduleParseList);
$moduleParseList = array();

function CreateModule($pModuleID){
    $start_time = getMicroTime();

    global $dbh;
    global $ModuleID;
    $ModuleID = $pModuleID;

    print "generate-module: starting to build module $ModuleID...\n";

    //global foreign modules array - metastructure info
    global $foreignModules;
    unset($foreignModules);
    $foreignModules = array();

    //create the Module class
    global $module;
    $module = new Module($ModuleID);

    //remove obsolete SQL Change file
    $sql_change_file = GEN_LOG_PATH . '/'.$this->ModuleID.'_dbChanges.sql';
    if(file_exists($sql_change_file)){
        unlink($sql_change_file);
    }

    //checks if the module table exists
    if ($module->checkTableExists($ModuleID)){
        print "generate-module: table exists: {$ModuleID}\n";
        $tableExists = true;
    } else {
        print "generate-module: table missing: {$ModuleID}\n";
        $tableExists = false;
    };

    //checks if the module log table exists
    if ($module->checkTableExists($ModuleID. "_l")){
        print "generate-module: table exists: {$ModuleID}_l\n";
        $logTableExists = true;
    } else {
        print "generate-module: table missing: {$ModuleID}_l\n";
        $logTableExists = false;
    };


    if ($tableExists) { //table exists
        $module->checkTableStructure($ModuleID);
    } else { //table does not exist
        //create the table:
        $module->createTable(false);
    }

    if ($logTableExists) { //table exists
        $module->checkTableStructure($ModuleID . '_l');
    } else { //table does not exist
        //create the audit trail table:
        $module->createTable(true);
    }

    require_once CLASSES_PATH . '/moduleinfo.class.php';
    $moduleInfo =& new ModuleInfo($ModuleID);
    $moduleInfo->makeGeneratedFile();

    //make and save sql table create statements
    $CreateTableSQL = $module->generateCreateTableSQL('MySQL', false);
    $CreateLogTableSQL = $module->generateCreateTableSQL('MySQL', true);
    $SQL = $CreateTableSQL . "\n-- statement separator --\n". $CreateLogTableSQL;

    $CreateTableFile = GENERATED_PATH . "/{$ModuleID}/{$ModuleID}_CreateTables.sql";
    if($fp = fopen($CreateTableFile, 'w')) {
        $SQL = str_replace("\n", "\r\n", $SQL); //convert unix line-endings to windows line-endings
        if(fwrite($fp, $SQL)){
            //print no output about saving to log
        } else {
            die( "s2a: could not save to file $CreateTableFile. Please check file/folder permissions.\n" );
        }
        fclose($fp);
    } else {
        die( "s2a: could not open file $CreateTableFile. Please check file/folder permissions.\n" );
    }

    //important to do this also when it's empty (to reflect when ParentModuleID is removed)...
    SaveParentModuleID($ModuleID, $module->permissionParentModuleID);

    if($SaveNavigationPhrases){
        SaveNavigationPhrases();
    }

    MakeGlobalRollupDef();


    RebuildScreen('FieldDefCache');

    MakeDashBoardGrids();

    if('pple' == $ModuleID){
        custom_ppl_GenerateSQL();
    }

    if($module->dataCollectionForm){
        RebuildScreen('DataCollectionForm');
    }
    RebuildScreen('Tabs');

    //build the List Screen:
    if(count($module->getListFields()) > 0){
        RebuildScreen('List');
    }


    RebuildScreen('DataHandler');

    //serialized grids and fields for view screen and notification output...
    RebuildScreen('ViewScreenSer');

    RebuildScreen('Export');

    $screens = $module->getScreens();

    if (count($screens) > 0){

        //rebuild other screens
        foreach($screens as $ScreenName => $Screen){
            if(!empty($Screen->linkToModuleID)){
                print "generate-module: {$Screen->name} is a link only.\n";
            } else {
                RebuildScreen($ScreenName);
            }
        }

        //rebuild any other screens: Stats, Delete confirmation
    }

    RebuildScreen('ScreenList');

    //cache the search fields
    RebuildScreen('SearchFields');

    //cache the Module Fields
    RebuildScreen('ModuleFields');

    //cache the loss cost calculation, if needed
    RebuildScreen('CostSeveritySQL');

    //cache info needed by the Audit screen
    RebuildScreen('Audit');

    //cache info to be used on global screens
    if(count($module->getListFields()) > 0){
        RebuildScreen('LabelSection');
        RebuildScreen('ListFields');
    }
    
    if($module->isGlobal){
        RebuildScreen('GlobalViewGrid');
        RebuildScreen('GlobalEditGrid');
    }
    RebuildScreen('ScreenInfo');
    RebuildScreen('RDCUpdate');

    //check if module exists in mod table
    $SQL = "SELECT COUNT(*) FROM `mod` WHERE ModuleID = '$ModuleID'";
    $exists = $dbh->getOne($SQL);
    dbErrorCheck($exists);

    //if the module isn't in the mod table, add it
    if(empty($exists)){
        $SQL = "INSERT INTO `mod` (ModuleID, Name, _ModDate, _ModBy)
        VALUES ('$ModuleID', '{$module->Name}', NOW(), 0)";
        $r = $dbh->query($SQL);
        dbErrorCheck($r);

        print "Module->createTable: Inserted record for {$ModuleID} in mod table.\n";
    }
    
    $SQL = "INSERT INTO `mod_l` (ModuleID, Name, _ModDate, _ModBy)
    VALUES ('{$ModuleID}', '{$module->Name}', NOW(), 0)";
    $r = $dbh->query($SQL);
    dbErrorCheck($r);

    //update mod table:
    $SQL = "UPDATE
        `mod`
    SET
        Name='{$module->Name}',
        Global = '". intval($module->isGlobal). "',
        ParentModuleID = '{$module->permissionParentModuleID}',
        OwnerField = '{$module->OwnerField}',
        RecordDescriptionField = '{$module->recordDescriptionField}',
        RecordLabelField = '{$module->recordLabelField}',
        RevisionAuthor = '{$module->revisionInfo['author']}',
        RevisionNumber = '{$module->revisionInfo['id']}',
        RevisionDate = '{$module->revisionInfo['date']}',
        LastParsed = NOW(),
        _ModDate=NOW()
    WHERE ModuleID = '$ModuleID';";
    $result = $dbh->query($SQL);
    dbErrorCheck($result);
    printd( "Successfully updated mod table with LastParsed date.\n", 0);
    
    //check module status
    $SQL = "SELECT cod.Value FROM `mod` INNER JOIN cod ON (mod.ModuleStatusID = cod.CodeID AND cod.CodeTypeID = 272) WHERE mod.ModuleID = '{$ModuleID}'";
    $modStatusVal = $dbh->getOne($SQL);
    print $SQL . "\n";
    print ("modStatusVal = $modStatusVal");
    dbErrorCheck($exists);
    
    //set module status to "tables and files", if module isn't 
    if(intval($modStatusVal) < 3){
        $SQL = "UPDATE `mod` SET ModuleStatusID = 3 WHERE ModuleID = '$ModuleID';";
        $result = $dbh->query($SQL);
        dbErrorCheck($result);
        printd( "Successfully updated mod table with ModuleStatusID.\n",0);
    }

    //make sure SubModule tables exist
    foreach($module->SubModules as $SubModuleID => $SubModule){
        if(count($SubModule->ModuleFields) > 0){
            if ($SubModule->checkTableExists($SubModuleID)){
                print "generate-module: table exists: {$SubModuleID}\n";
                $tableExists = true;
            } else {
                print "generate-module: table missing: {$SubModuleID}\n";
                $tableExists = false;
            };
    
            //check if the module log table exists
            if ($SubModule->checkTableExists($SubModuleID. "_l")){
                print "generate-module: table exists: {$SubModuleID}_l\n";
                $logTableExists = true;
            } else {
                print "generate-module: table missing: {$SubModuleID}_l\n";
                $logTableExists = false;
            };
        
        
            if ($tableExists) { //table exists
                $SubModule->checkTableStructure($SubModuleID);
            } else { //table does not exist
                //create the table:
                $SubModule->createTable($SubModuleID, false);
            }
    
    
            if ($logTableExists) { //table exists
                $SubModule->checkTableStructure($SubModuleID . "_l");
            } else { //table does not exist
                //create the audit trail table:
                $SubModule->createTable($SubModuleID . "_l", true);
            }
        }
    }

    //generate chart definitions
    GenerateCharts();

    //moved down
    RebuildScreen('TableAliases');

    //generate report definitions
    GenerateReports();

    //not necessary except for data that was entered before SMC triggers
    UpdateSMCTriggers();

    //sample code types;
    print "checking if there are any code types that need codes...\n";

    global $dbh;
    //check if code type exists in cod table
    $SQL = "SELECT DISTINCT CodeTypeID FROM cod";

    $existingCodes = $dbh->getCol($SQL);
    dbErrorCheck($existingCodes);


    //print "existing code types with codes\n";
    //print_r($existingCodes);

    global $g_CodeTypes;

    print "codes from sample items\n";
    print_r($g_CodeTypes);
    if(count($g_CodeTypes) > 0){
        foreach($g_CodeTypes as $codeTypeID => $codeType){
            if(!in_array($codeTypeID, $existingCodes)){
                print "code type $codeTypeID:\n";
                //print_r($codeType);

                print "importing codes\n";

                foreach($codeType as $codeID => $codeName){
                    $SQL = "INSERT INTO cod (CodeTypeID, CodeID, Description) VALUES ($codeTypeID, $codeID, '". mysql_escape_string($codeName) ."')";
                    print "$SQL\n\n";

                    $r = $dbh->query($SQL);
                    dbErrorCheck($r);

                }

                print "done.\n\n";

            }
        }
    } else {
        print "no codes from sample items\n";
    }
    
    //document what code types are used by this module
    $codeTypesUsed = array();
    foreach($module->ModuleFields as $mf){
        if('codefield' == get_class($mf)){
            $codeTypesUsed[$mf->codeTypeID] = $mf->codeTypeID;
        }
    }
    
    global $dbh;
    
    if(count($codeTypesUsed)){
        //print "code types used";
        //print_r($codeTypesUsed);
        
        $inserts = array();
    
        $SQL = "DELETE FROM codtd WHERE DependencyID = '$ModuleID'";
        $r = $dbh->query($SQL);
        dbErrorCheck($r);
        
        $SQL = "INSERT INTO codtd (CodeTypeID, DependencyID) VALUES ";
        foreach($codeTypesUsed AS $codeTypeID){
            $inserts[] = "($codeTypeID, '$ModuleID')";
        }
        $SQL .= join(',', $inserts);
        
        $r = $dbh->query($SQL);
        dbErrorCheck($r);
    }
    
    
    

    //print "ModuleFields:\n";
    //print_r($module->ModuleFields);

    //print "Primary Key Fields:\n";
    //($module->PKFields);

    global $foreignModules;
    $foreignModuleIDs = array_keys($foreignModules);
    
    print "generate-module: foreignModules:\n";
    //print_r($foreignModuleIDs);
    
    //  print "form screen:\n";
    //  print_r($module->Screens['View']);

    //first remove all existing records
    print "generate-module: removing all dependencies with $ModuleID from modd table\n";
    $SQL = "DELETE FROM modd WHERE DependencyID = '$ModuleID';\n";
    $r = $dbh->query($SQL);
    dbErrorCheck($r);

    print "generate-module: Foreign Modules:\n";
    foreach($foreignModuleIDs as $k => $key){

        //check if this foreignModule is listed in the modd table
        if(false === strpos($key, '_') && $ModuleID != $key){
            print "generate-module: inserting $key foreignModule dependency\n";
            $SQL = "INSERT INTO modd (ModuleID, DependencyID, ForeignDependency, _ModDate) 
            VALUES ('{$key}', '$ModuleID', 1, NOW())";
    
            $r = $dbh->query($SQL);
            dbErrorCheck($r);
        } else {
            //handle DataViews here if needed
        }
    }
    
    print "generate-module: getting listed foreignModules from modd table (again)\n";
    $SQL = "SELECT ModuleID FROM modd WHERE DependencyID = '$ModuleID';\n";
    //get data
    $deps = $dbh->getCol($SQL);
    dbErrorCheck($deps);
    
    $fTables = array_unique($module->getRemoteModules());
    print_r($fTables);

    print "generate-module: Remote Modules:\n";
    foreach($fTables as $k => $key){
    
        //check if this foreignModule is listed in the modd table
        if(!in_array($key, $deps)){
            print "generate-module: inserting $key Remote Module dependency\n";
            $SQL = "INSERT INTO modd (ModuleID, DependencyID, RemoteDependency, _ModDate)
            VALUES ('{$key}', '$ModuleID', 1, NOW())";
            
        } else {
            print "generate-module: we update $key Remote Module dependency\n";
            $SQL = "UPDATE modd SET 
                RemoteDependency = 1, 
                _ModDate = NOW()
            WHERE ModuleID = '{$key}' AND DependencyID = '$ModuleID';";
            
        }

        $r = $dbh->query($SQL);
        dbErrorCheck($r);
        
        //also update the remote module entry itself
        $SQL = "UPDATE `mod` SET Remote = 1, _ModDate = NOW() WHERE ModuleID = '$key';";
        $result = $dbh->query($SQL);
        dbErrorCheck($result);
    }
    
    print "generate-module: getting listed dependencies from modd table (again)\n";
    $SQL = "SELECT ModuleID FROM modd WHERE DependencyID = '$ModuleID';\n";
    //get data
    $deps = $dbh->getCol($SQL);
    dbErrorCheck($deps);
    
    foreach($module->SubModules as $key => $SubModule){
        //check if this foreignModule is listed in the modd table
        if(!in_array($key, $deps)){
            if(false === strpos($key, '_')){
                print "generate-module: inserting $key subModule dependency\n";
                $SQL = "INSERT INTO modd (ModuleID, DependencyID, SubModDependency, _ModDate)
                VALUES ('{$key}', '$ModuleID', 1, NOW())";
            }
        } else {
            print "generate-module: we update $key subModule dependency\n";
            $SQL = "UPDATE modd SET 
                SubModDependency = 1, 
                _ModDate = NOW()
            WHERE ModuleID = '{$key}' AND DependencyID = '$ModuleID';";
            
        }
        $r = $dbh->query($SQL);
        dbErrorCheck($r);
    }

    //global $gMissingSubModules;
    if(count($module->MissingSubModules) > 0){
        
        global $dbh;
        
        print "Missing Submodule Files:\n";
        foreach ($module->MissingSubModules as $sm){
            print "   $sm\n";
            
            //check if record exists in mod table
            $SQL = "SELECT Count(*) as Exists FROM `mod` WHERE ModuleID = '$sm';\n";
            //get data
            $exists = $dbh->getOne($SQL);
            dbErrorCheck($exists);

            
            //if not, add it and mark it as 'missing'
            if(empty($exists)){
                $SQL = "INSERT INTO mod (ModuleID, Name, ModuleStatusID, _ModDate)
                VALUES ('{$sm}', 'Missing: {$sm}. Needed by {$module->ModuleID}.', '1', NOW())";
                $r = $dbh->query($SQL);
                dbErrorCheck($r);
                print "generate-module: Inserted record for missing module {$sm} in mod table.\n";
            }
            
        }
        
        //insert missing module IDs in mod table....
    }
    


    MakeRDCTriggers();
        
    
    //print "documentation:\n";
    //print_r($module->Documentation);
    $sortOrder = 10;
    
    //get support document ID
    $SQL = "SELECT SupportDocumentID FROM spt WHERE ModuleID = '{$module->ModuleID}' ORDER BY SupportDocumentID LIMIT 1;";
    
    $recordID = $dbh->getOne($SQL);
    dbErrorCheck($recordID);
    
    if(empty($recordID)){
        $SQL = "INSERT INTO spt (ModuleID, _ModDate) VALUES
('{$module->ModuleID}', NOW())";
        $r = $dbh->query($SQL);
        dbErrorCheck($r);
        
        $SQL = "SELECT LAST_INSERT_ID();";
        $recordID = $dbh->getOne($SQL);
        dbErrorCheck($recordID);
    }
    
    //handle support documentation
    $documentationSections = $module->getDocumentation();
    foreach($documentationSections as $key => $docArray){
        //if(!empty($docArray[1])){
        if(TRUE){
            $title = $dbh->quote($docArray[0]);
            $text = $dbh->quote(trim($docArray[1]));
            
            //check if the section exists
            $SQL = "SELECT SupportDocumentSectionID, Protected FROM spts WHERE
SupportDocumentID = $recordID AND SectionID = '$key' ORDER BY
SupportDocumentSectionID LIMIT 1;";
    
            $r = $dbh->getRow($SQL, DB_FETCHMODE_ASSOC);
            dbErrorCheck($r);
//print "Existing section:\n";
//print_r($r);

            if(!empty($r['SupportDocumentSectionID'])){
//print "Section $title exists\n";
                if(in_array(intval($r['Protected']), array(-1, 0) )){
//print "Section $title is not protected\n";
                    $SQL = "UPDATE spts SET Title = {$title}, SectionText =
{$text}, SortOrder = $sortOrder, SectionID = '$key', _Deleted = 0 WHERE
SupportDocumentSectionID = {$r['SupportDocumentSectionID']}";
                    $r = $dbh->query($SQL);
                    dbErrorCheck($r);
                }
            } else {
//print "Section $title does not exist\n";

                //insert if it dowsn't exist
                $SQL = "INSERT INTO spts (SupportDocumentID, Title, SectionText,
SortOrder, SectionID) VALUES ($recordID, {$title}, {$text}, $sortOrder, '$key')";
                $r = $dbh->query($SQL);
                dbErrorCheck($r);
            }
        }
        $sortOrder = $sortOrder + 10;
    }
    
    
    
    $end_time = getMicroTime();
    
    $duration = round($end_time - $start_time, 2);
    $SQL = "INSERT INTO modgt (ModuleID, Duration, _ModDate, _ModBy, _Deleted) VALUES ('$pModuleID', $duration, NOW(), 0, 0);";
    $r = $dbh->query($SQL);
    dbErrorCheck($r, false);
    
    //ob_clean();
    print "generate-module: Created module $pModuleID in $duration s.\n";
    
} //end function CreateModule




function MakeRDCTriggers(){
    global $module;
    $rdFieldName = $module->recordDescriptionField;
    $debug_prefix = debug_indent("generate-module-MakeRDCTriggers() $rdFieldName:");
    
    if(!empty($rdFieldName)){
        if(array_key_exists($rdFieldName, $module->ModuleFields)){
            
            $rdField = $module->ModuleFields[$rdFieldName];
            
            //$deps = $rdField->getFieldDependencies('');
            $deps = $rdField->getDependentFields();
            indent_print_r($deps);
            
            if(count($deps) > 0){
                //foreach dependent field (ignore local tablefields), call $moduleField->makeRDCTrigger()
                foreach($deps as $dep){
                    if($dep['moduleID'] == $module->ModuleID){
                        $moduleField = $module->ModuleFields[$dep['name']];
                    } else {
                        $moduleField = GetModuleField($dep['moduleID'], $dep['name']);
                    }
                   // $moduleField->makeRDCTrigger($module->ModuleID, $rdField->makeRDCCallerDef());
                     $moduleField->makeRDCTrigger($module->ModuleID);
                }
            } else {
                print "$debug_prefix Warning: no dependent fields for RecordDescription\n";
            }
            
        } else {
            die("$debug_prefix no RecordDescription field called $rdFieldName");
        }
    }
    debug_unindent();
}
    




//rationalizing the screen rebuilding business into a function:
function RebuildScreen($ScreenName){

    print "\n\n\n";
    print ">>> ################################################################### >>>\n";
    print "   generate-module->RebuildScreen: Rebuilding the $ScreenName screen...\n";
    print ">>> ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| >>>\n";
    print "\n\n";

    global $module;
    global $ModuleID;
    global $parsingModuleID;
    $parsingModuleID = $ModuleID;

    $cancel = false;

    //set up some parameters depending on $ScreenName
    switch($ScreenName){
    case 'Audit':
        $modelFileName = "AuditModel.php";
        $pkField = $module->PKFields[0];
        $codeArray = Array('/**recordIDField**/' => $pkField);
        $createFileName = "{$ModuleID}_Audit.gen";
        break;
    case 'CostSeveritySQL':
        if(in_array('rskxa', $module->getRemoteModules())){
            if(isset($module->ModuleFields['CostSeverityValue'])){
                $csvField = $module->ModuleFields['CostSeverityValue'];
                if(isset($module->ModuleFields['TotalCost'])){
                    $tcField = $module->ModuleFields['TotalCost'];
                } else {
                    trigger_error("The module '$ModuleID' must have a field named TotalCosts.", E_USER_WARNING);
                }

                global $SQLBaseModuleID;
                $SQLBaseModuleID = $ModuleID;
                $selectDefs = array();
                $selectDefs[] = $csvField->makeSelectDef($ModuleID);
                $joinDefs = $csvField->makeJoinDef($ModuleID);
                if(isset($tcField)){
                    $selectDefs[] = $tcField->makeSelectDef($ModuleID);
                    $joinDefs = array_merge($joinDefs, $csvField->makeJoinDef($ModuleID));
                } else {
                    $selectDefs[] = '0 AS TotalCost';
                }

                $joinDefs = SortJoins($joinDefs);
                $PKField = end($module->PKFields);

                $SQL = "SELECT ";
                $SQL .= join(", ", $selectDefs);
                $SQL .= " FROM `$ModuleID`\n";
                $SQL .= join("\n", $joinDefs);
                $SQL .= "WHERE `$ModuleID`.$PKField = '/**RecordID**/'";
                print $SQL;

            } else {
                //make a dummy trigger
                $SQL = 'SELECT 5';

                trigger_error("The module $ModuleID uses a RemoteField to rskxa, and should have a field named CostSeverityValue.", E_USER_WARNING);
            }

            $modelFileName = 'CustomModel.php';
            $createFileName = $ModuleID.'_CostSeveritySQL.gen';

            $codeArray['/**custom**/'] = '$csSQL = \''.addslashes($SQL).'\'';
        } else {
            return true;
        }
        break;
    case 'DataCollectionForm':
        $dataForms = $module->getDataCollectionForms();
        $modelFileName = 'CustomModel.php';
        $createFileName = $ModuleID.'_DataCollection.gen';
        $codeArray['/**custom**/'] = '$dataCollection = unserialize(\''.escapeSerialize($dataForms) .'\')';
        break;
    case 'DataHandler':
        $modelFileName = 'CustomModel.php';
        $createFileName = $ModuleID.'_DataHandler.gen';
        require_once CLASSES_PATH . '/data_handler.class.php';
        $dataHandler = new DataHandler($ModuleID);
        $codeArray['/**custom**/'] = '$dataHandler = unserialize(\''.escapeSerialize($dataHandler) .'\')';
        break;
    case 'Export':
        $modelFileName = 'ExportModel.php';
        $codeArray = $module->generateExport();
        $createFileName = "{$ModuleID}_Export.gen";

        break;
    case 'FieldDefCache':

        $modelFileName = 'CustomModel.php';
        $createFileName = $ModuleID.'_FieldDefCache.gen';

        global $SQLBaseModuleID;
        $SQLBaseModuleID = $ModuleID;

        global $gFieldDefs;
        $gFieldDefs = array();

        foreach($module->ModuleFields as $modulefield_name => $modulefield){
            $field_def = array(
                $modulefield->getQualifiedName($ModuleID),
                $modulefield->makeSelectDef($ModuleID, true),
                $modulefield->makeJoinDef($ModuleID)
            );
            $gFieldDefs[$ModuleID][$modulefield_name] = $field_def;
        }

//print_r($gFieldDefs);

        $codeArray['/**custom**/'] = '$gFieldDefs[\''.$ModuleID.'\'] = unserialize(\''.escapeSerialize($gFieldDefs) .'\')';


        break;
    case 'GlobalEditGrid':
        $moduleMap = $module->_map;

        $export_matches = $moduleMap->selectElements('Exports');

        if(!empty($export_matches[0])){
            $matches = $export_matches[0]->selectElements('EditGrid');

            if(count($matches[0]) == 0){
                $matches = $export_matches[0]->selectElements('UploadGrid');
            }

            if(!empty($matches)){
                //build and serialize the EditGrid
                $editGrid_element = $matches[0];
                $editGrid_element->attributes['isGlobalEditGrid'] = 1; //add a special attribute
                switch($module->ModuleID){
                case 'modnr':
                case 'moddr':
                    //grids that apply to module but not a record in the module
                    $editGrid_element->attributes['hasNoParentRecordID'] = 1; //add another special attribute
                    break;
                default:
                    break;
                }

                $editGrid = &$editGrid_element->createObject($ModuleID);
                $editGrid->number = 1;
                $editGrid->listSQL = $module->generateListSQL(&$editGrid);
                $editGrid->insertSQL = $module->generateInsertSQL(&$editGrid, false, 'replace');
                $editGrid->updateSQL = $module->generateUpdateSQL(&$editGrid, 'replace');
                $editGrid->deleteSQL = $module->generateDeleteSQL(&$editGrid, 'replace');
                $editGrid->logSQL = $module->generateInsertSQL(&$editGrid, true, 'replace');
                $editGrid->prepRemoteFields();

                $codeArray = array(
                    '/**EditGrid**/' => escapeSerialize($editGrid),
                    '/**plural_record_name**/' => $module->PluralRecordName
                    );
                $modelFileName = "GlobalEditGridModel.php";
                $createFileName = "{$ModuleID}_GlobalEditGrid.gen";
            } else {
                $cancel = true;
            }
        }
        break;
    case 'GlobalViewGrid':
        $moduleMap = $module->_map;
        $export_matches = $moduleMap->selectElements('Exports');

        if(!empty($export_matches[0])){
            $matches = $export_matches[0]->selectElements('ViewGrid');

            if(!empty($matches)){
                //build and serialize the grid
                $grid_element = $matches[0];
                $grid_element->attributes['isGlobalGrid'] = 1; //add a special attribute
                switch($module->ModuleID){
                case 'modnr':
                case 'moddr':
                    //grids that apply to module but not a record in the module
                    $grid_element->attributes['hasNoParentRecordID'] = 1; //add another special attribute
                    break;
                case 'res':
                    $grid_element->attributes['isGlobalGridWithConditions'] = 1; //add another special attribute
                    break;
                default:
                    break;
                }
                $grid = &$grid_element->createObject($ModuleID);
                $grid->number = 1;
                $grid->listSQL = $module->generateListSQL(&$grid);

                $codeArray = array(
                    '/**Grid**/' => escapeSerialize($grid)
                    );

                $modelFileName = "GlobalViewGridModel.php";
                $createFileName = "{$ModuleID}_GlobalViewGrid.gen";
            }
        }
        break;
    case 'LabelSection':
        $modelFileName = "LabelModel.php";

        $phrases = array();
        $moduleFields = $module->ModuleFields;
        $labelFields = array();

        $lf = $module->getListFields();
        foreach($lf as $name => $field){
            $tempModField = $moduleFields[$name]; //&$module->ModuleFields[$name];
            $labelFields[$name] = MakeObject($ModuleID, $name, 'ViewField', array());

            $phrases[$name] = $tempModField->phrase;
            unset($tempModField);
        }

        if(!empty($module->recordLabelField)){
            if(!isset($labelFields[$module->recordLabelField])){
                $labelFields[$module->recordLabelField] = MakeObject($ModuleID, $module->recordLabelField, 'InvisibleField');
            }
        }

        $labelSQL = $module->generateGetSQL($labelFields);

        //generate $fields, $phrases, screenTitle, $labelSQL
        $codeArray = array(
            '/**LabelFields**/' => escapeSerialize($labelFields),
            '/**LabelPhrases**/' => escapeSerialize($phrases),
            '/**singular_record_name**/' => $module->SingularRecordName,
            '/**GetSQL**/' => $labelSQL,
            '/**recordLabelField**/' => $module->recordLabelField
        );
        $createFileName = "{$ModuleID}_LabelSection.gen";
        break;
    case 'ListFields';
        $modelFileName = "ListFieldsModel.php";
        $listFields = $module->getListFields();
        $linkFields = array();
        foreach($listFields as $fieldname => $field){
            if(!empty($field->linkField)){
                $linkFields[$fieldname] = $field->linkField;
            }
        }
        $codeArray = array(
            '/**ListFields**/' => escapeSerialize(array_keys($listFields)),
            '/**LinkFields**/' => escapeSerialize($linkFields)
        );
        $createFileName = "{$ModuleID}_ListFields.gen";
        break;
    case 'ModuleFields':
        $modelFileName = "ModuleFieldsModel.php";
        $t_mfs = array();

        global $SQLBaseModuleID;
        $SQLBaseModuleID = $ModuleID;

        foreach($module->ModuleFields as $t_mf){
            switch(get_class($t_mf)){
                case 'tablefield':
                    break;
                case 'foreignfield':
                case 'codefield':
                    $t_mf->foreignTableAlias = GetTableAlias($t_mf);
                    print "RebuildScreen:ModuleFields... foreignTableAlias {$t_mf->foreignTableAlias}.{$t_mf->name}\n";
                    break;
                case 'remotefield':
                    $t_mf->remoteTableAlias = GetTableAlias($t_mf);
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
                    print_r($t_mf);
                    die('createModule: unknown module field type');
            }
            $t_mfs[$t_mf->name] = $t_mf;
        }

        $ser_mfs = "";
        $ser_mfs .= str_replace("'", "\\'",
            str_replace("\\", "\\\\",
                serialize($t_mfs)
            )
        );
        $ser_mfs .= "";
        $codeArray = Array('/**ModuleFields**/' => $ser_mfs);
        $createFileName = "{$ModuleID}_ModuleFields.gen";
        break;
    case 'RDCUpdate':

        $pkFieldName = end($module->PKFields);
        $rdFieldName = $module->recordDescriptionField;
        $ooFieldName = $module->OwnerField;
        $mfs = GetModuleFields($ModuleID);
        $joinDefs = array();

        global $SQLBaseModuleID;
        $SQLBaseModuleID = $ModuleID;  //needed by [ModuleField]->makeJoinDef()

        if(empty($rdFieldName)){
            $rdFieldName = 'RecordDescription';
            if(!array_key_exists('RecordDescription', $mfs)){
                print "RDCUpdate: No RecordDescription field";
                return true;
            }
        } else {
            if(!array_key_exists($rdFieldName, $mfs)){
                die( "RDCUpdate: RecordDescription field '$rdField' does not exist in ModuleFields." );
                return false;
            }
        }

        if(!empty($ooFieldName)){
            if(!array_key_exists($ooFieldName, $mfs)){
                die( "RDCUpdate: Owner Organization field '$ooFieldName' does not exist in ModuleFields." );
                return false;
            }
            $ooField = $mfs[$ooFieldName];
            $ooFieldSelect = $ooField->makeSelectDef($ModuleID, false);
            $joinDefs = array_merge($joinDefs, $ooField->makeJoinDef($ModuleID));

            $ooInsertFieldSQL = ", OrganizationID";
            $ooInsertValueSQL = ",\n$ooFieldSelect";
            $ooUpdateSQL = ",\nrdc.OrganizationID = $ooFieldSelect";
        }

        $rdField = $mfs[$rdFieldName];

        print "$moduleID RDField:\n";
        print_r($rdField);

        ob_start(); //suppress the printout from these
        $select_sql = $rdField->makeSelectDef($ModuleID, false);
        $joinDefs = array_merge($joinDefs, $rdField->makeJoinDef($ModuleID));
        $joinDefs = SortJoins($joinDefs);
        ob_end_clean();

        print "RDCUpdate: created the following SELECT definition:\n";
        print "   $select_sql\n";

        print "RDCUpdate: created the following join definition:\n";
        print_r ($joinDefs);

        $RDCinsertSQL = "INSERT INTO rdc (ModuleID, RecordID, Value$ooInsertFieldSQL, _ModDate)\n";
        $RDCinsertSQL .= "SELECT \n";
        $RDCinsertSQL .= "   '$ModuleID' AS ModuleID,\n";
        $RDCinsertSQL .= "   $ModuleID.{$pkFieldName} AS RecordID,\n";
        $RDCinsertSQL .= "   $select_sql AS Value$ooInsertValueSQL,\n";
        $RDCinsertSQL .= "    NOW() AS _ModDate\n";
        $RDCinsertSQL .= "FROM \n   `$ModuleID`\n";
        $RDCinsertSQL .= "LEFT OUTER JOIN `rdc` ON ($ModuleID.{$pkFieldName} = rdc.RecordID AND rdc.ModuleID = '$ModuleID')\n";
        foreach($joinDefs as $joinDef){
            $RDCinsertSQL .= "   $joinDef\n";
        }

        $RDCinsertSQL .= "WHERE $ModuleID.{$pkFieldName} IN ([*insertIDs*])";

        print "RDCUpdate: created the following INSERT SQL statement:\n";
        print "$RDCinsertSQL\n";

        $RDCupdateSQL = "UPDATE rdc\n";
        $RDCupdateSQL .= "INNER JOIN $ModuleID ON (rdc.RecordID = $ModuleID.{$pkFieldName} AND rdc.ModuleID = '$ModuleID')\n";
        foreach($joinDefs as $joinDef){
            $RDCupdateSQL .= "   $joinDef\n";
        }
        $RDCupdateSQL .= "SET\n";
        $RDCupdateSQL .= "   rdc.Value = {$select_sql}{$ooUpdateSQL},\n";
        $RDCupdateSQL .= "   rdc._ModDate = NOW()\n";
        $RDCupdateSQL .= "WHERE\n";
        $RDCupdateSQL .= "   rdc.ModuleID = '$ModuleID'\n";
        $RDCupdateSQL .= "   AND rdc.RecordID IN ([*updateIDs*]);\n";

        $codeArray = array(
            '/**RDCinsert**/' => escapeSerialize($RDCinsertSQL),
            '/**RDCupdate**/' => escapeSerialize($RDCupdateSQL)
        );
        $modelFileName = "RDCUpdateModel.php";
        $createFileName = "{$ModuleID}_RDCUpdate.gen";
        break;
    case 'ScreenInfo':  //used by the Documentation viewer
        $content = '';

        print "ScreenInfo: $ModuleID\n";

        $moduleMap = $module->_map;

        //look for the Screens section
        $screens_element = $moduleMap->selectFirstElement('Screens');
        $n_modulefields = $moduleMap->selectChildrenOfFirst('ModuleFields');

        //make an array of modulefield elements by name rather than numerically indexed
        $modulefields = array();
        foreach($n_modulefields as $modulefield_element){
            $modulefields[$modulefield_element->name] = $modulefield_element;
        }

        if(!empty($screens_element)){
            include_once CLASSES_PATH . '/metadoc.class.php';
            $screen_elements = $screens_element->selectElements('EditScreen');
            foreach($screen_elements as $screen_element){
                $screen_doc = $screen_element->createDoc($ModuleID);
                //print_r($screen_doc);
                $content .=  $screen_doc->getContent();
            }
        }

        $codeArray = array(
            '/**editScreens**/' => escapeSerialize($content)
        );

        $modelFileName = "ScreenInfoModel.php";
        $createFileName = "{$ModuleID}_ScreenInfo.gen";
        break;
    case 'ScreenList':
        $screenList = array();
        $screens = $module->getScreens();
        if (count($screens) > 0){
            //detect screens, save a list of them and their types
            foreach($screens as $screenName => $screen){
                if(!empty($Screen->linkToModuleID)){
                    print "generate-module: {$Screen->name} is a link only.\n";
                } else {
                    $screenList[$screenName] = get_class($screen);
                }
            }
        }
        $modelFileName = 'CustomModel.php';
        $createFileName = $ModuleID.'_ScreenList.gen';
        $codeArray['/**custom**/'] = '$screenList = unserialize(\''.escapeSerialize($screenList) .'\')';
        break;
    case 'SearchFields':  //to be used by the Dashboard item setup
        $moduleMap = $module->_map;
        $screen_element = $moduleMap->selectFirstElement('SearchScreen');
        if(empty($screen_element)){
            $cancel = true;
        } else {
            $searchFields = array();

            //create all search fields
            if(count($screen_element->c) == 0){
                $cancel = true;
            } else {
                foreach($screen_element->c as $field_element){
                    $field_name = $field_element->name;
                    $searchFields[$field_name] = $field_element->createObject($ModuleID);
                }
                $codeArray = array(
                    '/**searchFields**/' => escapeSerialize($searchFields)
                );

                $modelFileName = "SearchFieldsModel.php";
                $createFileName = "{$ModuleID}_SearchFields.gen";
            }
        }
        break;
    case 'TableAliases':
        global $gTableAliasCached;
        global $gTableAliasParents;
        $modelFileName = "TableAliasesModel.php";
        $codeArray = Array(
            '/**TableAliases**/' => escapeSerialize($gTableAliasCached[$ModuleID]),
            '/**TableAliasParents**/' => escapeSerialize($gTableAliasParents[$ModuleID])
        );
        $createFileName = "{$ModuleID}_TableAliases.gen";
        break;
    case 'Tabs':
        $modelFileName = "TabsModel.php";
        $codeArray = array(
            '/**Tabs**/' => $module->generateTabs('')
        );
        $createFileName = "{$ModuleID}_Tabs.gen";
        break;
    case 'ViewScreenSer':
        $modelFileName = "ViewSerModel.php";
        $codeArray = $module->generateViewScreenSections();
        if(empty($codeArray)){
            $codeArray = array();
        }
        $createFileName = "{$ModuleID}_ViewSer.gen";
        break;
    default:
        print "generate-module: Building Screen $ScreenName\n";
        $Screen = $module->getScreen($ScreenName); //rm &
        $makeCacheFile = true;
        if (!empty($Screen)){
            if($codeArray = $Screen->build()){
                $modelFileName = $Screen->templateFieldName;
                $createFileName = $Screen->genFileName;
            } else {
                return true;
            }
        }
    }

    if(!$cancel){
        SaveGeneratedFile($modelFileName, $createFileName, $codeArray, $ModuleID);
    }

    print "\n";
    print "<<< ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| <<<\n";
    print "   generate-module->RebuildScreen: Finished the $ScreenName screen...\n";
    print "<<< ################################################################### <<<\n";
    print "\n";
}


function generateCharts(){
    global $module;
    global $dbh;
    $modelFileName = "ChartModel.php";
    $chart_elements = $module->_map->selectChildrenOfFirst('Charts');

    $SQL = "DELETE FROM modch WHERE ModuleID = '{$module->ModuleID}'";
    $r = $dbh->query($SQL);
    dbErrorCheck($r);

    if(count($chart_elements)>0){
        require_once CLASSES_PATH . '/chart.class.php';
        $added_charts = array();
        foreach($chart_elements as $chart_element){
            if(in_array($chart_element->name, $added_charts)){
                die("Module {$module->ModuleID} cannot have more than one chart named {$chart_element->name}.");
            }

            $chart_obj =& $chart_element->createObject($module->ModuleID);

            //serialize each chart to a separate file, by moduleID and name
            $createFileName = "{$module->ModuleID}_{$chart_element->name}_Chart.gen";

            SaveGeneratedFile($modelFileName, $createFileName, array('/**Chart**/' => escapeSerialize($chart_obj)), $module->ModuleID);

            //save to database, modch table
            $SQL = "INSERT INTO modch (ModuleID, Name, Title, Type, _ModDate, _ModBy, _Deleted) VALUES ('{$module->ModuleID}', '{$chart_element->name}', '{$chart_element->attributes['title']}', '".$chart_obj->getDisplayType()."', NOW(), 0, 0)";

            $r = $dbh->query($SQL);
            dbErrorCheck($r);

            unset($chart_obj);
            $added_charts[] = $chart_element->name;
        }
    }
}


function GenerateReports(){
    $debug_prefix = debug_indent("GenerateReports(): ");
    global $module;
    global $dbh;

    $SQL = "DELETE FROM modrp WHERE ModuleID = '{$module->ModuleID}'";
    $r = $dbh->query($SQL);
    dbErrorCheck($r);

    //look for report data structure defs
    $filePattern = XML_PATH ."/{$module->ModuleID}_ReportDef_*.xml";
    $reportDefFiles = glob($filePattern);
    if(count($reportDefFiles) > 0){

        include_once CLASSES_PATH . '/report_map.class.php';
        include_once CLASSES_PATH . '/report.class.php';

        foreach($reportDefFiles as $reportDefFile){
            //  map the file
            $reportMap = new ReportMap($reportDefFile);

            //create one or more ReportDef objects of the file
            $reports = $reportMap->generateReports();

            foreach($reports as $report){
                //  serialize the ReportDef object to a generated file
                SaveGeneratedFile('CustomModel.php', "{$module->ModuleID}_Report_{$report->name}.gen", array('/**custom**/' => '$report = unserialize(\''.escapeSerialize($report).'\')'), $module->ModuleID);

                foreach($report->reportLocations as $level => $group){
                    //save a reference to the database 
                    $SQL = "INSERT INTO modrp (ModuleID, Name, Title, LocationLevel, LocationGroup, Format) VALUES ('{$module->ModuleID}', '{$report->name}', '{$report->title}', '$level', '$group', '{$report->displayFormat}')";

                    $r = $dbh->query($SQL);
                    dbErrorCheck($r);
                }
            }
        }
    } else {
        print "$debug_prefix No reports to be generated.";
    }
    debug_unindent();
}


/**
 *  Creates a generated file for each of the module's submodules
 */
function GenerateSubModuleInfo()
{
    global $module;
    global $ModuleID;
    if(count($module->SubModules) > 0){
        foreach($module->SubModules as $subModuleID => $subModule){

            $props = array();
            $props['parentKey'] = $subModule->parentKey;
            $props['localKey'] = $subModule->localKey;
            $props['conditions'] = $subModule->conditions;

            $codeArray['/**custom**/'] = '$sub_props = unserialize(\''.escapeSerialize($props) .'\')';

            $modelFileName = 'CustomModel.php';
            $createFileName = $ModuleID.'_'.$subModuleID.'_SubModuleInfo.gen';
            SaveGeneratedFile($modelFileName, $createFileName, $codeArray, $ModuleID);
        }
    }
}






class SubModuleMap extends ModuleMap
{
var $subRels = array();

function makeGlobalRollupDef($callerModuleID, $callerPK, $callerDefs)
{
    print "SubModuleMap->makeGlobalRollupDef(): {$this->moduleID}\n";

    if(count($callerDefs) > 0){

        //generates trigger SQL for this submodule
        $SQL = "SELECT 
            '$callerModuleID' as ModuleID, 
            `$callerModuleID`.$callerPK AS RecordID,
            '/*SubModuleID*/' AS SubModuleID,
            '/*SubRecordID*/' AS SubRecordID
        FROM `$callerModuleID`\n";
        foreach($callerDefs as $cdModuleID => $callerDef){
            if(isset($callerDef['remoteJoins'])){

                //do the following INNER JOIN also, but slightly differently...
                $SQL .= "INNER JOIN `{$callerDef['RemoteModID']}` AS `{$callerDef['RemoteModAlias']}`ON ({$callerDef['join']} ";
                if(count($callerDef['conditions']) > 0){
                    foreach($callerDef['conditions'] as $condition){
                        //print_r($condition);
                        $SQL .= " AND `$cdModuleID`.{$condition['field']} = '{$condition['value']}' ";
                    }
                }
                $SQL .= ")\n";

                //change the remotefield too
                $SQL .= join($callerDef['remoteJoins']);
                $SQL .= "\n";

            } else {
                $SQL .= "INNER JOIN `$cdModuleID` ON ({$callerDef['join']} ";
                if(count($callerDef['conditions']) > 0){
                    foreach($callerDef['conditions'] as $condition){
                        $SQL .= " AND `$cdModuleID`.{$condition['field']} = '{$condition['value']}' ";
                    }
                }
                $SQL .= ")\n";
            }
        }

        $cdModuleInfo = GetModuleInfo($cdModuleID);
        $cdModulePK = $cdModuleInfo->getPKField();
        $SQL .= " WHERE `{$this->moduleID}`.$cdModulePK = '/*SubRecordID*/'";


        CheckSQL($SQL);
        print "Submodule trigger SQL $cdModuleID: $SQL\n";

        //saves trigger to $cdModuleID
        $modelFileName = "SMCTriggersModel.php";
        $createFileName = "{$this->moduleID}_SMCTriggers.gen";
        $createFilePath = GENERATED_PATH. "/{$this->moduleID}/$createFileName";
        if(file_exists($createFilePath)){
            include $createFilePath; //sets $SMCtriggers
        } else {
            $SMCtriggers = array();
        }
        $SMCtriggers[$callerModuleID] = $SQL;

        $codeArray = array('/**SMCtriggers**/' => escapeSerialize($SMCtriggers));
print "Creating trigger for {$this->moduleID}.{$this->name}:\n";

        //file creation code...
        SaveGeneratedFile($modelFileName, $createFileName, $codeArray, $this->moduleID);
    }

    $rels = array(); //rels used for stats

    $subs = $this->selectChildrenOfFirst('SubModules', NULL, NULL);

    if(count($subs) > 0){

        $rels['_totSubs'] = count($subs);

        foreach($subs as $sub_elem){
            if('SubModule' == $sub_elem->type){
                $subModuleID = $sub_elem->attributes['moduleID'];

                $subMap = new SubModuleMap($subModuleID);
                $subMap->parseMe();
                $subMap->parentModuleID = $this->moduleID;

                //propagates attributes from the submodule element to the submodule map
                foreach($sub_elem->attributes as  $attrKey => $attrVal){
                    $subMap->attributes[$attrKey] = $attrVal;
                }

                if(!empty($sub_elem->attributes['parentKey'])) {
                    $def = array();
                    $lkIsRemote = false;

                    $lkModuleField = GetModuleField($subModuleID, $sub_elem->attributes['localKey']);
                    switch(get_class($lkModuleField)){
                    case 'tablefield':
                        //nothing to do
                        break;
                    case 'remotefield':
                        $lkIsRemote = true;
                        global $SQLBaseModuleID;
                        $SQLBaseModuleID = $subModuleID;

                        $lkModuleField->reversed = true;
                        $remoteJoins = $lkModuleField->makeJoinDef($subModuleID);
                        //$remoteJoins = $lkModuleField->makeJoinDef($lkModuleField->moduleID);

                        $def['remotejoins'] = $remoteJoins;

                        break;
                    default:
print "Submodule trigger local key field {$lkModuleField->name} is a ". get_class($lkModuleField). "\n";
                        unset($lkModuleField);
                        print "Skipping submodule trigger for `$subModuleID`.{$sub_elem->attributes['localKey']}: not a table field";
                        break 2;
                    }

                    $parentKeyModuleField = GetModuleField($this->moduleID, $sub_elem->attributes['parentKey']);
                    if('tablefield' != get_class($parentKeyModuleField)){
                        unset($parentKeyModuleField);
                        print "Skipping submodule trigger for {$this->moduleID}.{$sub_elem->attributes['parentKey']}: not a table field";
                        break;
                    }

                    if($lkIsRemote){
                        $def['remoteJoins'] = $remoteJoins;
                        $lkQualified = $lkModuleField->makeSelectDef($subModuleID, false);
                        $def['RemoteModID'] = $lkModuleField->remoteModuleID;
                        $def['RemoteModAlias'] = GetTableAlias($lkModuleField, $subModuleID);
                        $def['join'] .= "$lkQualified = `{$this->moduleID}`.{$sub_elem->attributes['parentKey']}";
                    } else {
                        $def['join'] = "`$subModuleID`.{$sub_elem->attributes['localKey']} = `{$this->moduleID}`.{$sub_elem->attributes['parentKey']}";
                    }

                    if(count($sub_elem->c)> 0){
                        foreach($sub_elem->c as $cond_elem){
                            if($cond_elem->type == 'SubModuleCondition') {
                                $def['conditions'][] = $cond_elem->attributes;
                            }
                        }
                    }
                    $defs = $callerDefs;
                    $defs[$subModuleID] = $def;

                    $subRel = $subMap->makeGlobalRollupDef($callerModuleID, $callerPK, $defs);
                }
            }

            //some stats
            if(isset($subRel[$subModuleID]['_totSubs'])){
                $rels['_totSubs'] = $rels['_totSubs'] + $subRel[$subModuleID]['_totSubs'];
            }

            $rels = array_merge($rels, $subRel);
        }
    }
    return array($this->moduleID => $rels);
}
} //class SubModuleMap

function MakeGlobalRollupDef(){
    global $ModuleID;
    //using SubModuleMap to recurse through submodule definitions
    $smm = new SubModuleMap($ModuleID);
    $smm->parseMe();
    
    global $module; 
    $PKField = end($module->PKFields);
    $rels = $smm->makeGlobalRollupDef($ModuleID, $PKField, array());
    
    //print_r($rels);
    
    $msg =  "Number of Submodules: {$rels[$ModuleID]['_totSubs']}\n";
//die($msg);
}

function UpdateSMCTriggers(){

return true;

//skipped

    global $ModuleID;
    global $module;
    global $dbh;
    $PKField = end($module->PKFields);
    
    //get triggers for module
    $TriggerFileName = GENERATED_PATH. "/{$ModuleID}/{$ModuleID}_SMCTriggers.gen";
    $insertSQL = "INSERT INTO `smc` (ModuleID, RecordID, SubModuleID, SubRecordID)\n";
            
    if(file_exists($TriggerFileName)){
        include $TriggerFileName;
        
        foreach($SMCtriggers as $triggerModuleID => $triggerSQL){
            $triggerSQL .= " LEFT OUTER JOIN smc ON 
                `$ModuleID`.$PKField = smc.SubRecordID
                AND smc.ModuleID = '$triggerModuleID'
                AND smc.SubModuleID = '$ModuleID' ";
            $triggerSQL = str_replace(array('/*SubModuleID*/', '\'/*SubRecordID*/\''), array($ModuleID, "`$ModuleID`.$PKField"), $triggerSQL);
            
           // $triggerSQL .= " AND {$ModuleID}.$PKFieldName = '$recordID'";
            
           // print debug_r($triggerSQL, "SMCTriggerSQL for $triggerModuleID");
           
            
            
            $SQL = $insertSQL . $triggerSQL;
            $SQL .= "\nWHERE smc.ModuleID IS NULL\n";
            
            print "\ntriggerSQL for $triggerModuleID\n";
            print_r($SQL);
            
            $r = $dbh->query($SQL);
            dbErrorCheck($r);
        }
    }
}

function MakeDashBoardGrids(){
    global $ModuleID;

    switch($ModuleID){
    case 'act':
    case 'acc':
    case 'usrds':

        global $module;
        global $dbh;

        include_once INCLUDE_PATH . '/dashboardGrids.php'; //class definitions

        switch($ModuleID){
        case 'act':
            $dashgrid = new ActionsDashboardGrid();
            break;
        case 'acc':
            $dashgrid = new AccountabilityDashboardGrid();
            break;
        case 'usrds':
            $dashgrid = new ShortcutDashboardGrid();
            break;
        default:
            die("DashboardGrid for module '$ModuleID' aren't implemented.");
            break;
        }
        $replaceValues = array('/**dashgrid**/' => escapeSerialize($dashgrid));

        SaveGeneratedFile('DashboardGridModel.php', '/dsb_'.$ModuleID.'DashboardGrid.gen', $replaceValues, 'dsb');
        break;
    default:
        break;
    }
}


/**
 *  A custom function for pple only: it needs SQL statements from ppl
 */
function custom_ppl_GenerateSQL(){
    $debug_prefix = debug_indent("custom_ppl_GenerateSQL()");

    global $module; //pple module
    $pplModule = GetModule('ppl');
    $ppleScreens =& $module->getScreens();
    $ppleModuleFields =& GetModuleFields('pple');
    $pplSQLs = array();

    foreach($ppleScreens as $screenName => $screen){
        if($screen->isRecordScreen()){
            $screenFields = array();
            $selectFields = array();
            $editableFields = array();

            foreach($screen->Fields as $fieldName => $field){
                $screenFields = array_merge($screenFields, $field->getRecursiveFields());
            }
            if(count($screen->sections) > 0){
                foreach($screen->sections as $section){
                    foreach($section->Fields as $fieldName => $field){
                        $screenFields = array_merge($screenFields, $field->getRecursiveFields());
                    }
                }
            }
            foreach($screenFields as $fieldName => $field){
                if(get_class($ppleModuleFields[$fieldName]) == 'foreignfield'){
                    if('ppl' == $ppleModuleFields[$fieldName]->foreignTable){
                        if('PersonID' == $ppleModuleFields[$fieldName]->localKey){
                            $selectFields[$ppleModuleFields[$fieldName]->foreignField] = $screenFields[$fieldName];
                            echo "theField\n";
                            print_r($field);
                            if($field->isEditable()){
                                $editableFields[$ppleModuleFields[$fieldName]->foreignField] = $screenFields[$fieldName];
                            }
                        }
                    }
                }
            }

            switch(get_class($screen)){
            case 'viewscreen':
                $pplSQLs['View']['get'] = $pplModule->generateGetSQL($selectFields);
                break;
            case 'editscreen':
                if(count($selectFields) > 0){
                    $pplSQLs[$screenName]['get'] = $pplModule->generateGetSQL($selectFields);
                    //if($module->CheckForEditableFields($screenName)){
                    if(count($editableFields) > 0){
                        $remotefields = array();
                        foreach(array_keys($selectFields) as $fieldname){
                            $field = $ppleModuleFields[$fieldname];
                            if('remotefield' == get_class($field)){
                                $remotefields[$fieldname] = $field;
                                unset($editableFields[$fieldname]);
                            }
                        }
                        if(count($remotefields) > 0){
                            $pplSQLs[$screenName]['remotefields'] = $remotefields;
                        }

                        if(count($editableFields) > 0){
                            $pplSQLs[$screenName]['update'] = $pplModule->generateUpdateSQL($editableFields, 'replace');
                            $pplSQLs[$screenName]['log'] = $pplModule->generateInsertSQL($editableFields, true, 'replace');
                        }

                    }
                }
                break;
            default:
                break;
            }
        }
    }

    //indent_print_r($pplSQLs);
    //die('just testing');

    $modelFileName = 'CustomModel.php';
    $createFileName = 'pple_CustomSQLs.gen';
    $codeArray['/**custom**/'] = '$custom_pplSQLs = unserialize(\''.escapeserialize($pplSQLs).'\')';

    SaveGeneratedFile($modelFileName, $createFileName, $codeArray, 'pple');

    debug_unindent();
}


function SaveParentModuleID($moduleID, $parentModuleID)
{
    $createFileName = 'ParentModuleIDs.gen';
    $permissionModuleIDs = array();
    $needSave = false;

    if(file_exists(GENERATED_PATH . "/{$createFileName}")){
        include(GENERATED_PATH . "/{$createFileName}");
    }

    if(empty($parentModuleID) && isset($permissionModuleIDs[$moduleID])){
        unset($permissionModuleIDs[$moduleID]);
        $needSave = true;
    } elseif(!empty($parentModuleID)){
        $permissionModuleIDs[$moduleID] = $parentModuleID;
        $needSave = true;
    }

    if($needSave){
        $modelFileName = 'CustomModel.php';
        //$codeArray['/**custom**/'] = '$permissionModuleIDs = unserialize(\''.escapeSerialize($permissionModuleIDs).'\')';
        $codeArray['/**custom**/'] = var_export($permissionModuleIDs, true);

        SaveGeneratedFile($modelFileName, $createFileName, $codeArray);
    }
}


/**
 *  Save a file with gettext calls for the navigation menu
 *
 *  This is pretty much a dummy file; it's needed only so that
 *  the xgettext command will find the phrases.
 */
function SaveNavigationPhrases()
{
    require_once CLASSES_PATH . '/navigator.class.php';
    $nav =& new Navigator(0);
    $phrases = $nav->getPhrases();
    $gettext_phrases = array();
    foreach($phrases as $phrase){
        if(!empty($phrase)){
            $gettext_phrases[] = 'gettext("'.addslashes($phrase).'")';
        }
    }
    $content = 'array('."\n";
    $content .= join(",\n     ", $gettext_phrases);
    $content .= "\n".')';
    $modelFileName = 'CustomModel.php';
    $codeArray['/**custom**/'] = '$phrases = ' . $content;
    $createFileName = 'NavigationPhrases.gen';
    SaveGeneratedFile($modelFileName, $createFileName, $codeArray);

    //die('test');
}




//"MAIN"

//Create one module
CreateModule($Argument);

?>