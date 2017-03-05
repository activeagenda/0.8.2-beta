#!/usr/bin/php
<?php
/**
 *  Utility to refresh cached data in the Record Description Cache
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
$StartAt = $_SERVER[argv][3];
$moduleID = $_SERVER[argv][2];

/**
 * Defines execution state as 'non-generating command line'.  Several classes and
 * functions behave differently because of this flag.
 */
DEFINE('EXEC_STATE', 2);

if(empty($Argument)){
    print '
    s2a-rdc-refresh: refreshes the Record Description Cache for one or more modules.

    USAGE:
    ./s2a-rdc-refresh.php <project_name> <moduleID>|-all [<StartModuleID>]

    Requires minimum 2 parameters: The name of the project, and either 
    "-all" (refreshes all modules) or a valid moduleID.

';
    die('Not enough parameters.');
}

print "s2a-rdc-refresh: project = $Project\n";

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

//classes
include_once CLASSES_PATH . '/modulefields.php';
include_once CLASSES_PATH . '/module.class.php';
include_once INCLUDE_PATH . '/general_util.php';
include_once INCLUDE_PATH . '/parse_util.php';

//connect to database
require_once PEAR_PATH . '/DB.php' ;  //PEAR DB class

//connect with superuser privileges - regular user has no permission to
//change table structure
global $dbh;
$dbh = DB::connect(GEN_DB_DSN);
dbErrorCheck($dbh);


$moduleParseList = array();
global $moduleParseList;

/*print_r($moduleInfo);
die();*/

if(FALSE === strpos($Argument, '-')){
    //Insert/update caches for one module only
    UpdateRDCaches($Argument);
} else {
    print "s2a-rdc-refresh: we'll update caches for all modules\n";
    global $foreignModules;

    if('-menu' == $Argument) {
        //get module list from Navigation.xml
        $navigationFile = XML_PATH . "/Navigation.xml";
        if (file_exists($navigationFile)){
                //assign SAX parser objects
                $parser = xml_parser_create();
                //xml_set_object($parser, $this);
                xml_set_element_handler($parser, 'parseStartElement', 'parseEndElement');
                //xml_set_character_data_handler($parser, 'parseCharacterData');

                xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, false);

                $fp = fopen($navigationFile, 'r') or die("Can't read XML data.");
                while ($data = fread($fp, 4096)) {
                    xml_parse($parser, $data, feof($fp)) or die("Can't parse XML data.");
                }
                fclose($fp);

                xml_parser_free($parser);

        } else {
            die("Could not open navigation menu file<br>\n");
        }
    } else  {
        //get module list from mod table
        if('-remote' == $Argument) {
            $limiter = ' AND mod.Remote = 1 ';
        } else {
            $limiter = '';
        }


        $SQL = "SELECT ModuleID FROM `mod` WHERE 1=1 $limiter ORDER BY ModuleID;\n";
        //get data
        $r = $dbh->getCol($SQL);
        dbErrorCheck($r);

        $moduleParseList = array_flip($r);
    }
    if(empty($StartAt)){
        $StartAt = reset(array_keys($moduleParseList));
    }
    $started = FALSE;

    foreach($moduleParseList as $parseModule => $dummy){
        if($parseModule == $StartAt){
            $started = TRUE;
        }
        if($started){
            print "...updating $parseModule\n\n";
            $result = UpdateRDCaches($parseModule);
            $moduleParseList[$parseModule] = $result;
        } else {
            print "...skipping $parseModule\n\n";
            $moduleParseList[$parseModule] = 'skipped';
        }
        //print_r($moduleParseList);
//      print_r(array_keys(get_defined_vars()));
    }
    
    print_r ($moduleParseList);
    
}


function UpdateRDCaches($moduleID){
    $mfs = GetModuleFields($moduleID);
    $moduleInfo = GetModuleInfo($moduleID);

    $RDFieldName = $moduleInfo->getProperty('recordDescriptionField');

    if(empty($RDFieldName)){
        $RDFieldName = 'RecordDescription';
    }

    if($RDFieldName){
        if(!array_key_exists($RDFieldName, $mfs)){
            print "s2a-rdc-refresh: Module $moduleID has no RecordDescription field\n";
            return 'error - RD field not in ModuleFields';
        }

        /************************ 
            INITIALIZE
        ************************/

        $pkField = reset($mfs);
        $rdField = $mfs[$RDFieldName];

        global $SQLBaseModuleID;
        $SQLBaseModuleID = $moduleID;  //needed by [ModuleField]->makeJoinDef()

        include GENERATED_PATH . "/{$moduleID}/{$moduleID}_RDCUpdate.gen"; // returns $RDCinsert, $RDCupdate

        //find the last where (strrpos only works on single-character patterns in PHP4)
        $revRDCinsert = strrev($RDCinsert);
        $rPos = strlen($RDCinsert) - strlen('WHERE') - strpos($revRDCinsert, strrev('WHERE'));

        //use the statament until the last WHERE
        //$RDCinsert = substr($RDCinsert, 0, strrpos($RDCinsert, 'WHERE '));
        $RDCinsert = substr($RDCinsert, 0, $rPos);

        //$RDCupdate = str_replace('AND rdc.RecordID IN ([*updateIDs*])', '',  $RDCupdate);

        /***************************************** 
            LOOK FOR EXISTING CACHE DEFS
        *****************************************/

        //check if records exist already
        $SQL = "SELECT RecordID FROM rdc WHERE ModuleID = '$moduleID'";
        global $dbh;
        $result = $dbh->getCol($SQL);
        dbErrorCheck($result);

        print "Successfully looked for RecordDescription caches for $moduleID records.\n";

        //print_r($result);
        //die('stop here now');
        if(count($result) > 0){
            $existingIDs = join(',', $result);
        } else {
            $existingIDs = '';
        }

        /************************** 
            INSERT CACHES
        **************************/

        //get the 'live' insert statement from here -- it needs a different WHERE clause
        $SQL = $insertSQL;*/
        $SQL = $RDCinsert;
        if(!empty($existingIDs)){
            $SQL .= "WHERE $moduleID.{$pkField->name} NOT IN ($existingIDs)";
        }
        $SQL .= ";";

        print "s2a-rdc-refresh: created the following INSERT SQL statement:\n";
        print "$SQL\n";

        print "s2a-rdc-refresh: executing SQL statement:\n";

        $result = $dbh->query($SQL);
        dbErrorCheck($result);

        print "Successfully inserted RecordDescription cache with $moduleID records.\n";

        $nInserted = $dbh->affectedRows();


        /***************************** 
            UPDATE CACHE TABLE
        *****************************/

        if(!empty($existingIDs)){

            $SQL = str_replace('[*updateIDs*]', $existingIDs, $RDCupdate);

            print "s2a-rdc-refresh: created the following UPDATE SQL statement:\n";
            print "$SQL\n"; 

            $result = $dbh->query($SQL);
            dbErrorCheck($result);

            print "Successfully Updated RecordDescription cache for $moduleID records.\n";

            $nUpdated = $dbh->affectedRows();
        }

        /************************** 
            CLEAN UP
        **************************/

        //trying to reset alias cache
        $gTableAliasCached = array();
        unset($gTableAliasCached);
        unset($gForeignModules);
        unset($SQLBaseModuleID);
        if(strlen($nUpdated) == 0){
            $updateMsg = 'no rows in source module';
        } else {
            $updateMsg = "updated $nUpdated rows";
        }
        return "successful:    inserted $nInserted rows, $updateMsg";
    } else {

        print "s2a-rdc-refresh: Module $moduleID has no RecordDescription field\n";
        return 'skipped - no RD field';
    }
}

function parseStartElement($parser, $tag, $attr)
{
    global $moduleParseList;
    if($tag == "ModuleLink"){
        $moduleParseList[$attr['moduleID']] = 'pending';
    }

}

function parseEndElement($parser, $tag)
{
    //do nothing really
}
?>