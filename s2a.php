#!/usr/bin/php
<?php
/**
 *  Utility to (re-)generate all modules, or thise that match the supplied criteria
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

if(false === strpos(PHP_OS, "WIN")){
    define('PATH_SEPARATOR', ':');
} else {
    define('PATH_SEPARATOR', ';');
}

//get PEAR class that handles command line arguments
//since this is needed before we include the config.php file, this is sort-of hard coded.
set_include_path('./pear' . PATH_SEPARATOR . get_include_path());
require_once 'pear/Console/Getargs.php';

$config = array();

$config['project'] =
    array('short' => 'p',
        'min'   => 0,
        'max'   => 1,
        'desc'  => 'The s2a project name. Must be a folder under the s2a folder.',
        'default' => 'active_agenda'
    );
$config['match'] =
    array('short'   => 'm',
        'min'     => 0,
        'max'     => 1,
        'desc'    => 'A wildcard expression that matches the IDs of the modules to generate. Use % as wildcard character. Examples: %, ac%, act',
        'default' => '%'
    );
$config['startat'] =
    array('short'   => 's',
        'min'     => 0,
        'max'     => 1,
        'desc'    => 'Within the matched module IDs, skip all modules prior to the module ID specified here.',
        'default' => 'optional'
    );
$config['listsource'] =
    array('short'   => 'l',
        'min'     => 0,
        'max'     => 1,
        'desc'    => 'Use \'db\' or \'files\'. The source for the list of modules to match. \'files\' matches all ModuleDef files in the XML folder. \'db\' matches currently installed modules but won\'t pick up newly added ModuleDefs.',
        'default' => 'db'
    );
$config['debug'] =
    array('short'   => 'd',
        'min'     => 0,
        'max'     => 1,
        'desc'    => 'When to generate separate debug logs for modules. Options: \'always\', \'onfail\', \'never\'',
        'default' => 'onfail'
    );
$config['cleartriggers'] =
    array('short'   => 't',
        'min'     => 0,
        'max'     => 1,
        'desc'    => 'Removes all triggers before starting the generate procedure. Options: \'yes\', \'no\'',
        'default' => 'no'
    );
$config['help'] =
    array('short' => 'h',
        'max'   => 0,
        'desc'  => 'Show this help.'
    );

$args =& Console_Getargs::factory($config);
if (PEAR::isError($args)) {
    if ($args->getCode() === CONSOLE_GETARGS_ERROR_USER) {
        // User put illegal values on the command line.
        echo Console_Getargs::getHelp($config, NULL, $args->getMessage())."\n";
    } else if ($args->getCode() === CONSOLE_GETARGS_HELP) {
        // User needs help.
        echo Console_Getargs::getHelp($config)."\n";
    }
    exit;
}

//getting the passed parameters
$Project       = $args->getValue('project');
$Match         = $args->getValue('match');
$StartAt       = $args->getValue('startat');
$ListSource    = $args->getValue('listsource');
$Debug         = $args->getValue('debug');
$ClearTriggers = $args->getValue('cleartriggers');



//clean them up
if(empty($Match)){
    $Match = '%';
}

$Match = str_replace(array('*','-'), array('%',''), $Match);
switch($ListSource){
    case 'files':
        break;
    case 'db':
        break;
    default:
        $ListSource = 'db';
        break;
}

if(empty($Project)){
    $Project = 'active_agenda';
}

if(empty($Debug)){
    $Debug = 'onfail';
} else {
    $Debug = trim(strtolower($Debug));
    $opts = array('onfail', 'always', 'never');
    if(! in_array($Debug, $opts) ){
        die("'debug' option accepts only one of the following: ".join(', ', $opts)."\n");
    }
}


if(!empty($ClearTriggers)){
    if(trim(strtolower($ClearTriggers)) == 'yes'){
        $bClearTriggers = true;
    } else {
        $bClearTriggers = false;
    }
}



print "\n";
print "\n";
print "****************************************\n" ;
print "* s2a: Batch module generating utility *\n" ;
print "****************************************\n" ;
print "s2a: project is $Project\n";
print "s2a: generating modules matching '$Match'";
if(!empty($StartAt)){
    print " from $StartAt and forward";
}
print "\n";
print "s2a: using listsource '$ListSource'";

print "\n\n";




/*********************************    
 *   INITIALIZATION & SETUP      *
 *********************************/

/**
 * Defines execution state as 'non-generating command line'.  Several classes and
 * functions behave differently because of this flag.
 */
DEFINE('EXEC_STATE', 2);


global $moduleList; //list of modules that will be parsed
$moduleList = array();


//get folder for settings file: assumes we're in the s2a folder (sucks)
$site_folder = realpath(dirname($_SERVER['SCRIPT_FILENAME']).'');
$site_folder .= '/'.$Project;

if(!file_exists($site_folder)){
    print "The project folder '$Project' does not exist.\n";
    exit;
}

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
include_once INCLUDE_PATH . '/general_util.php';
include_once INCLUDE_PATH . '/parse_util.php';

$start_time = getMicroTime();

//start log file
saveLog('s2a starting at '.date('r')."\n", true);
saveLog('arguments: ' . join($_SERVER[argv], ' ')."\n");

global $moduleList;

if('files' == $ListSource) {
    $file_ParseList = array();
    $str_fileMatch = str_replace('%', '*', $Match);
    $fileMatches = explode(',', $str_fileMatch);
    foreach($fileMatches as $fileMatch){
        $fileMatch = trim($fileMatch);
        $arr = glob(XML_PATH.'/'.$fileMatch.'ModuleDef.xml');
        foreach($arr as $filepath){
            $file_ParseList[basename($filepath, 'ModuleDef.xml')] = 'unknown';
        }
    }
    /*print_r($moduleList);
    print "File list source not implemented yet.\n";
    exit;*/
    
}

//database connect:
//connect with super-user privileges - regular user has no permission to
//change table structure
require_once PEAR_PATH . '/DB.php' ;  //PEAR DB class
global $dbh;
$dbh = DB::connect(GEN_DB_DSN);
dbErrorCheck($dbh);

if('%' == $Match){
    $limiter = '';
} else {
    $limiters = array();
    $sqlMatches = explode(',', $Match);
    foreach($sqlMatches as $sqlMatch){
        $sqlMatch = trim($sqlMatch);
        if(FALSE !== strpos($sqlMatch, '%')){
            $expr = 'LIKE';
        } else {
            $expr = '=';
        }
        $limiters[] = "`mod`.ModuleID $expr '$sqlMatch' ";
    }
    $limiter = ' AND ('.join(' OR ', $limiters) . ')';;
}


$SQL = "SELECT `mod`.ModuleID, Avg(Duration) AS AvgDuration FROM `mod` LEFT OUTER JOIN modgt ON `mod`.ModuleID = modgt.ModuleID WHERE 1=1 $limiter GROUP BY `mod`.ModuleID ORDER BY ModuleID";


$moduleList = $dbh->getAssoc($SQL);
dbErrorCheck($moduleList, false);

if(!empty($file_ParseList)){
    print "Matched module defs not in folder\n";
    print join(
        array_diff(
            array_keys($moduleList),
            array_keys($file_ParseList)
        ),
        ', '
    );
    print "\n";
    print "Matched module defs not in database\n";
    print join(
        array_diff(
            array_keys($file_ParseList),
            array_keys($moduleList)
        ),
        ','
    );
    print "\n";

    if('%' == $Match){ //disallow wholesale file parsing from files for now...
        print "Can't generate all modules using '-l files'.\n";
        exit;
    }
    $moduleList = array_merge($file_ParseList, $moduleList);
}


if(0 == count($moduleList)){
    print "No modules matched the expression '$Match'.\n";
    exit;
}

if($bClearTriggers){
    print "Clearing all trigger files. Make sure to run a full generation job in order to re-create them.\n";
    $triggerPattern = '*Triggers.gen';
    $arr = glob(GENERATED_PATH.'/*/'.$triggerPattern);

    $errors = '';
    foreach($arr as $filepath){
        print "Removing trigger file $filepath\n";
        if(!unlink($filepath)){
            $errors .= "Can't remove file $filepath\n";
        }
    }

    $triggerPattern = '*RDCUpdate.gen';
    $arr = glob(GENERATED_PATH.'/*/'.$triggerPattern);
    foreach($arr as $filepath){
        print "Removing trigger update file $filepath\n";
        if(!unlink($filepath)){
            $errors .= "Can't remove file $filepath\n";
        }
    }

    if(!empty($errors)){
        print $errors;
    }

}


$failedModules = GenerateModules($Project, $moduleList, $StartAt);


if(count($failedModules) > 0){
    print "\n";
    print "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx\n" ;
    print "x s2a: Some modules were not generated correctly: x\n" ;
    print "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx\n" ;
    print "\n";
    print join("\n\n", $failedModules);
    print "\n";
    print "Just the moduleIDs: ". join(',', array_keys($failedModules));
    print "\n";
    print "To analyze why a module did not generate correctly, look into the log files at ".GEN_LOG_PATH.".\n";

    print "\n";
    print "When generating many modules, errors might occur because of dependencies\n";
    print "with changes to modules that haven't been generated at the time of the error.\n";
    print "Therefore, it can be useful to try re-generating the failed modules again.\n";
    if(prompt("Would you like to retry the failed modules once?")){
        $retryList = array();
        foreach($failedModules as $failedModuleID => $errorMessage){
            $retryList[$failedModuleID] = $moduleList[$failedModuleID];
        }
        $failedModules = GenerateModules($Project, $retryList);
        if(count($failedModules) > 0){
            print "\n";
            print "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx\n" ;
            print "x s2a: Some modules were STILL not generated correctly: x\n" ;
            print "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx\n" ;
            print "\n";
            print join("\n\n", $failedModules);
        }
    }


} else {
    print "\n";
    print "+-------------------------------------------------------+\n" ;
    print "| s2a: All selected modules were successfully generated |\n" ;
    print "+-------------------------------------------------------+\n" ;
}

$end_time = getMicroTime();
$duration = round($end_time - $start_time, 2);
$minutes = floor($duration/60);
$seconds = $duration % 60;
print "Total generating time: $minutes minutes $seconds seconds ($duration s)\n" ;
print "\n";
print "\n";

//end batch process


$sql_change_files = glob(GEN_LOG_PATH.'/*_dbChanges.sql');
if(count($sql_change_files)){

    print " xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx\n" ;
    print "x                                                       x\n";
    print "x  s2a: Potentially destructive table changes skipped.  x\n" ;
    print "x                                                       x\n";
    print " xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx\n" ;
    print "\n";
    print "Changes to table structure that would DROP table fields, or\n";
    print "change data types so that loss of data could occur, were\n";
    print "NOT automatically applied but saved to the following files:\n";
    print "\n";
    foreach($sql_change_files as $sql_change_file){
        print "$sql_change_file\n";
    }
    print "\n";
    print "You can review and apply these changes right now, by\n";
    print "answering 'y', below.\n";
    print "\n";
    print "You may also do this later, by running the command-line \n";
    print "utility s2a-apply-table-changes.php, like this:\n";
    print "\n";
    print "cd [your s2a folder location]\n";
    print "php s2a-apply-table-changes.php\n";
    print "\n";
    print "If the PHP executable cannot be found, you must specify\n";
    print "its location as you type the command:\n";
    print "\n";
    print "(Example)\n";
    print "cd [your s2a folder location]\n";
    print "c:\php4\php s2a-apply-table-changes.php\n";
    print "(Substitute c:\php4\php with the absolute path to the\n";
    print "PHP executable on your system.)\n";
    print "\n";
    print "\n";

    if(prompt("Would you like to review and apply the table changes now?")){
        define(APPLY_TABLE_CHANGES_INCLUDED, true);

        include_once(S2A_FOLDER . '/s2a-apply-table-changes.php');
    }
}





/****************
*   FUNCTIONS   *
****************/

//parses a module with a seperate process
function CreateModule($Project, $moduleID){
    global $Debug;
    static $SaveNavigationPhrases = true;

    if(FALSE === strpos(PHP_OS, "WIN")){
        $phpcmd = "php";         //linux/unix system
    } else {
        $phpcmd = "c:\php4\php"; //windows system
    }

    $command = "$phpcmd s2a-generate-module.php $Project $moduleID $SaveNavigationPhrases";
    $cmdHandle = popen($command, 'r');
    $datestamp = date('Y-m-d_H-i-s');
    $dumpFile = GEN_LOG_PATH . '/debug_'.$moduleID.'_'.$datestamp.'.log';
    $dumpHandle = fopen($dumpFile, 'w');

    if(!$cmdHandle){
        print " ERROR:\nCould not run command $command\n";
    }
    if(!$dumpHandle){
        print " ERROR:\nCould not open file $datestamp for writing\n";
    }
    $line = '';
    while(!feof($cmdHandle)){
        $prevLine = $line;
        $line = fgets($cmdHandle, 4096); //reads line by line
        fwrite($dumpHandle, $line);
    }

    pclose($cmdHandle);
    fclose($dumpHandle);

    if(empty($line)){
        $lastLine = trim($prevLine);
    } else {
        $lastLine = trim($line);
    }
    saveLog(str_pad($moduleID, 6, ' ').': '.$lastLine . "\n");

    if(false === strpos($lastLine, 'generate-module: Created module')){
        print " ERROR:\n $lastLine\n";
        $error_msg = "s2a: module '$moduleID' failed with the error message: \n $lastLine";

        switch($Debug){
        case 'onfail':
        case 'always':
            if(!defined('DEBUG_LOG_FORMAT') || DEBUG_LOG_FORMAT == 'win'){ //defaults to 'win'
                $convFHr = fopen($dumpFile, 'r');
                $convFHw = fopen($dumpFile.'.txt', 'w');
                $line = '';
                while(!feof($convFHr)){
                    $line = fgets($convFHr, 4096); //reads line by line
                    $line = str_replace("\n", "\r\n", $line); //convert unix line-endings to windows line-endings
                    fwrite($convFHw, $line);
                }
                fclose($convFHr);
                fclose($convFHw);
                unlink($dumpFile);
            }
            break;
        default:
            //don't keep the log
            clearLog($moduleID);
        }

        return $error_msg;
    } else {
        $parseTime = substr($lastLine, strpos($lastLine, ' in ') + 4);
        $parseTime = str_replace("\r\n", '', $parseTime);

        print " SUCCESSFUL: $parseTime";


        if('always' != $Debug){
            clearLog($moduleID);
        }

        unset($debug);
        return '';
    }
    if($SaveNavigationPhrases){
        $SaveNavigationPhrases = false;
    }
}



function saveLog($msg, $new = false){
    global $logFile;
    if($new){
        $datestamp = date('Y-m-d_H-i-s');
        $logFile = GEN_LOG_PATH . '/s2a'.$datestamp.'.log';
        $write_mode = 'w';
    } else {
        $write_mode = 'a';
    }
    
    if($fp = fopen($logFile, $write_mode)) {
        $msg = str_replace("\n", "\r\n", $msg); //convert unix line-endings to windows line-endings
        if(fwrite($fp, $msg)){
            //print no output about saving to log
        } else {
            die( "s2a: could not save to file $logFile. Please check file/folder permissions.\n" );
        }
        fclose($fp);
    } else {
        die( "s2a: could not open file $logFile. Please check file/folder permissions.\n" );
    }
}



function clearLog($moduleID)
{
    $pattern = 'debug_'.$moduleID.'_*.log*';
    $arr = glob(GEN_LOG_PATH.'/'.$pattern);

    $errors = '';
    foreach($arr as $filepath){
        if(!unlink($filepath)){
            $errors .= "Can't remove file $filepath\n";
        }
    }
    if(!empty($errors)){
        print $errors;
    }
}


function GenerateModules($project, $moduleList, $startAt = null)
{
    $failedModules = array();

    //start with the first module in the list, unless specified
    if(empty($startAt)){
        $startAt = reset(array_keys($moduleList));
    }
    $started = false;
    $nTotal  = count($moduleList);
    $nCurrent = 0;

    foreach($moduleList as $module_id => $avg_time){
        if($module_id == $startAt){
            $started = true;
        }
        if($started){
            $nCurrent++;
            if(empty($avg_time)){
                print "s2a: generating '$module_id' ($nCurrent/$nTotal); no time estimate ";
            } else {
                
                str_pad($input, 10, "-=", STR_PAD_LEFT);
                print "s2a: generating ";
                print str_pad('\''.$module_id.'\'',7,' ',STR_PAD_RIGHT);
                print " ";
                print str_pad("($nCurrent/$nTotal)",9,' ',STR_PAD_LEFT);
                print "; est: ";
                print str_pad(number_format($avg_time, 2),8,' ',STR_PAD_LEFT);
                print " s";
            }
            $error_msg = CreateModule($project, $module_id);
            if(0 == strlen($error_msg)){
                //success
            } else {
                $failedModules[$module_id] = $error_msg;
            }
            print "\n";
        } else {
            $moduleList[$module_id] = 'skipped';
            $nTotal = $nTotal -1;
        }
    }
    
    if(!$started){
        print "All matched modules were skipped (no module matched '$startAt')\n";
        exit;
    }
    return $failedModules;

}
?>