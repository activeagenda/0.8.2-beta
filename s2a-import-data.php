#!/usr/bin/php
<?php
/**
 *  Utility to import data from XML files
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
$InFile = $_SERVER[argv][2];

print "s2a-import-data: project = $Project\n";


/**
 * Defines execution state as 'non-generating command line'.  Several classes and
 * functions behave differently because of this flag.
 */
DEFINE('EXEC_STATE', 2);

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

include_once CLASSES_PATH . '/modulefields.php';
include_once CLASSES_PATH . '/data_handler.class.php';
include_once CLASSES_PATH . '/data_map.class.php';
include_once INCLUDE_PATH . '/parse_util.php';
include_once INCLUDE_PATH . '/general_util.php';
include_once INCLUDE_PATH . '/web_util.php';


/**
 * Sets custom error handler
 */
set_error_handler('handleError');

//connect with superuser privileges - regular user has no permission to
//change table structure
global $dbh;
$dbh = DB::connect(GEN_DB_DSN);
dbErrorCheck($dbh);


$dataMap =& new DataMap($InFile);

if(count($dataMap->c > 0)){
    foreach($dataMap->c as $element){
        if('Records' == $element->type){
            $dataImporter = new DataImporter($element);
            $dataImporter->import();
        }
    }
}

?>