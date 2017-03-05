#!/usr/bin/php
<?php
/**
 *  Utility to export selected codes as SQL data
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
$CodeTypeIDs = $_SERVER[argv][2];
if(empty($CodeTypeIDs)){
    print '
    s2a-export-codes: Generates a SQL data file from the Codes module from the specified Code Types.
    
    BASIC USAGE:
    ./s2a-export-codes.php <project_name> <CodeTypeIDs>
    
    Requires 2 parameters: The name of the project, and a comma-separated list of Code Type IDs.
    
    ';
    die('Not enough parameters.');
}


//assumes we're in the 's2a' folder 
$site_folder = realpath(dirname($_SERVER['SCRIPT_FILENAME']).'');
$site_folder .= '/'.$Project;

//includes
$config_file = $site_folder . '/config.php';
if(!file_exists($config_file)){
    print "Config file not found at $config_file\n";
    exit;
}


//get settings
include_once $config_file;
set_include_path(PEAR_PATH . PATH_SEPARATOR . get_include_path());
require_once PEAR_PATH . '/DB.php' ;  //PEAR DB class

//utility functions
include_once INCLUDE_PATH . '/parse_util.php';

global $dbh;
$dbh = DB::connect(DB_DSN);
dbErrorCheck($dbh);

/**
 * Defines execution state as 'non-generating command line'.  Several classes and
 * functions behave differently because of this flag.
 */
DEFINE('EXEC_STATE', 2);

//cleaning user input
$arCodeTypeIDs = explode(',', $CodeTypeIDs);
$arCleanedCodeTypeIDs = array();
foreach($arCodeTypeIDs as $CodeTypeID){
    $arCleanedCodeTypeIDs[] = intval($CodeTypeID);
}
$strCleanedCodeTypeIDs = join(',',$arCleanedCodeTypeIDs);


$SQL = "SELECT 
    CodeTypeID,
    Name,
    Description,
    UseValue,
    _ModBy,
    _ModDate,
    _Deleted
FROM codt
WHERE CodeTypeID IN ($strCleanedCodeTypeIDs)";

$r = $dbh->getAll($SQL, DB_FETCHMODE_ASSOC);
dbErrorCheck($r);

$genSQL = "DELETE FROM codt WHERE CodeTypeID IN ($strCleanedCodeTypeIDs);\r\n";

if(count($r) > 0){
    foreach($r as $row){
        $genSQL .= 'INSERT INTO codt (CodeTypeID,Name,Description,UseValue,_ModBy,_ModDate,_Deleted)
        VALUES ('.quoteValue($row['CodeTypeID']).','.quoteValue($row['Name']).','.quoteValue($row['Description']).','.quoteValue($row['UseValue']).','.quoteValue($row['_ModBy']).','.quoteValue($row['_ModDate']).','.quoteValue($row['_Deleted']).');'."\r\n";
    }
} else {
    die("No data in code types '$strCleanedCodeTypeIDs'\n");
}



$SQL = "SELECT 
    CodeTypeID,
    CodeID,
    SortOrder,
    Value,
    Description,
    _ModBy,
    _ModDate,
    _Deleted
FROM cod
WHERE CodeTypeID IN ($strCleanedCodeTypeIDs)";

$r = $dbh->getAll($SQL, DB_FETCHMODE_ASSOC);
dbErrorCheck($r);

//print_r($r);
$genSQL .= "DELETE FROM cod WHERE CodeTypeID IN ($strCleanedCodeTypeIDs);\r\n";

if(count($r) > 0){
    foreach($r as $row){
        $genSQL .= 'INSERT INTO cod (CodeTypeID,CodeID,SortOrder,Value,Description,_ModBy,_ModDate,_Deleted)
        VALUES ('.quoteValue($row['CodeTypeID']).','.quoteValue($row['CodeID']).','.quoteValue($row['SortOrder']).','.quoteValue($row['Value']).','.quoteValue($row['Description']).','.quoteValue($row['_ModBy']).','.quoteValue($row['_ModDate']).','.quoteValue($row['_Deleted']).');'."\r\n";
    }
} else {
    die("No codes in code types '$strCleanedCodeTypeIDs'\n");
}

//print "The following SQL statements were generated:\n$genSQL\n";
print "\n";

$datestamp = date('Y-m-d_H-i-s');
$strFileCodeTypeIDs = str_replace(',','_ct', $strCleanedCodeTypeIDs);
$createFileName = GEN_LOG_PATH.'/codeExport_ct'.$strFileCodeTypeIDs.'_'.$datestamp.'.sql';

if($fp = fopen($createFileName, 'w')) {
    if(fwrite($fp, $genSQL)){
        print "File $createFileName saved.\n";
    } else {
        die( "Could not create file $createFileName.\n" );
    }
    fclose($fp);
}

function quoteValue($value)
{
    $quoted = '';
    if('' == trim($value)){
        $quoted = 'NULL';
    } else {
        $quoted = '\''.mysql_escape_string(trim($value)).'\'';
    }

    return $quoted;
}
?>