<?php
/**
 *  Generally available functions to be used at run-time as well as parse-time.
 *
 *  This file contains functions that are used by any other file in the
 *  Active Agenda/s2a project. It is included by most if not all running files.
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
 *  Returns the portion of a phrase string before the "pipe" caracter, or the entire string if none.
 */
function ShortPhrase($phrase)
{
    $pos = strpos($phrase, '|');
    if ($pos > 0){
        $phrase = substr($phrase, 0, $pos);
    }

    return $phrase;
}



/**
 *  Returns the portion of a phrase string after the "pipe" caracter, or the entire string if none.
 */
function LongPhrase($phrase)
{
    $pos = strpos($phrase, '|');
    if ($pos > 0){
        $phrase = substr($phrase, $pos+1);
    }

    return $phrase;
}



/**
 *  Returns an array of modulefields of the specified module.
 */
function &GetModuleFields($pModuleID)
{
    if(defined('EXEC_STATE') && EXEC_STATE == 4){
        $debug_prefix = debug_indent("GetModuleFields() {$pModuleID}:");

        $t_module =& GetModule($pModuleID);
        if(is_object($t_module)){

            if(empty($t_module->ModuleFields)){
                indent_print_r($t_module);
                trigger_error("$debug_prefix modulefields are empty", E_USER_ERROR);
            }
            debug_unindent();
            return $t_module->ModuleFields;

        } else {

            print "$debug_prefix could not find $pModuleID (end)\n";
            debug_unindent();
            return false;
        }

    } else {

        $genFileName = GENERATED_PATH . "/{$pModuleID}/{$pModuleID}_ModuleFields.gen";

        if(file_exists($genFileName)){
            //imports $modulefields
            include($genFileName);

            $modulefields = unserialize($modulefields);

            if(!empty($modulefields)){
                return $modulefields;
            } else {
                trigger_error("GetModuleFields() {$pModuleID}: moduleFields are empty", E_USER_ERROR);
            }
        } else {
            trigger_error("GetModuleFields() {$pModuleID}: error loading file $genFileName", E_USER_ERROR);
        }

        return false;
    }
}



/**
 *  Returns a modulefield object
 */
function GetModuleField($pModuleID, $fieldname)
{
    $moduleFields = GetModuleFields($pModuleID);
    if(!isset($moduleFields[$fieldname])){
        trigger_error("GetModuleField: cannot find ModuleField '$pModuleID'.'$fieldname'", E_USER_ERROR);
    }
    return $moduleFields[$fieldname];
}



/**
 *  Returns a ModuleInfo object
 */
function &GetModuleInfo($moduleID)
{
//print "GetModuleInfo $moduleID\n";
    if(empty($moduleID)){
        trigger_error("moduleID cannot be empty", E_USER_ERROR);
    }
    require_once CLASSES_PATH . '/moduleinfo.class.php';
    static $moduleInfos = array();

    if(empty($moduleInfos[$moduleID])){
        $moduleInfo = new ModuleInfo($moduleID);;
//print_r($moduleInfo);
        $moduleInfos[$moduleID] =& $moduleInfo;
//print_r($moduleInfos);
        return $moduleInfo;
    } else {
        return $moduleInfos[$moduleID];
    }
}

/**
 *  Returns a DataHandler object
 */
function &GetDataHandler($moduleID, $isImport = false, $useRemoteIDCheck = false)
{
    require_once CLASSES_PATH . '/data_handler.class.php';
    $includeFileName = GENERATED_PATH . "/{$moduleID}/{$moduleID}_DataHandler.gen";

    if(file_exists($includeFileName)){
        include($includeFileName); //supplies $dataHandler
    }
    $dataHandler->isImport = $isImport;
    $dataHandler->useRemoteIDCheck = $useRemoteIDCheck;
//print_r($dataHandler);
    return $dataHandler;
}


/**
 *  Returns the proper table alias for a ModuleField, given the $SQLBaseModuleID
 */
function GetTableAlias(&$pModuleField, $parentAlias = null)
{
    $debug_prefix = debug_indent("GetTableAlias() {$pModuleField->moduleID}.{$pModuleField->name}:");
    print "$debug_prefix parentAlias = $parentAlias\n";

    global $SQLBaseModuleID;
    print "$debug_prefix SQLBaseModuleID = $SQLBaseModuleID\n";



    switch(get_class($pModuleField)){
    case 'calculatedfield':
        return "{$parentAlias}_cf";
        break;
    case 'recordmetafield':
    case 'rangefield':
    case 'linkfield':
    case 'tablefield':
        return $parentAlias;
        break;
    default:
        $arrayKey = $pModuleField->getTableAliasKey($parentAlias);
        $foreignModuleID = $pModuleField->getForeignModuleID();
    }

    global $gTableAliasCached;
    if(!defined('EXEC_STATE') || EXEC_STATE != 4){
        $tableAliases = array();
        include_once(GENERATED_PATH . "/{$SQLBaseModuleID}/{$SQLBaseModuleID}_TableAliases.gen");
        if(count($tableAliases) > 0){ //detects whether the include happened (note "include_once" anpve)
            $gTableAliasCached[$SQLBaseModuleID] = $tableAliases;
        }
    }

    global $gForeignModules;

    /*$condition = trim($condition); //makes sure it doens't create different matches b/c of whitespace differences*/

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

    if(empty($gTableAliasCached[$SQLBaseModuleID][$arrayKey])){
        if(empty($gForeignModules[$SQLBaseModuleID][$foreignModuleID])){
            $gForeignModules[$SQLBaseModuleID][$foreignModuleID] = 1;
        } else {
            $gForeignModules[$SQLBaseModuleID][$foreignModuleID] += 1;
        }

        $alias = $foreignModuleID . $gForeignModules[$SQLBaseModuleID][$foreignModuleID];
        $gTableAliasCached[$SQLBaseModuleID][$arrayKey] = $alias;
        $inserting = true;
    } else {
        $alias = $gTableAliasCached[$SQLBaseModuleID][$arrayKey];
    }

    print "$debug_prefix field type " .get_class($pModuleField). "\n";
    print "$debug_prefix parent alias $parentAlias\n";
    if($inserting){
        print "$debug_prefix Adding an alias: $alias\n";
        print "$debug_prefix gTableAliasCached for $SQLBaseModuleID\n";
        indent_print_r($gTableAliasCached[$SQLBaseModuleID]);
        print "$debug_prefix gForeignModules for $SQLBaseModuleID\n";
        indent_print_r($gForeignModules[$SQLBaseModuleID]);
    }

    print "$debug_prefix alias = $alias (end)\n";
    debug_unindent();
    return $alias;
}


/**
 *  Returns a modulfield's qualified name from a cached definition
 */
function GetQualifiedName($fieldName, $moduleID = null)
{
    $def = _getCachedFieldDef($fieldName, $moduleID);
    return $def[0];
}


/**
 *  Returns a Select Def
 *
 *  A Select Def is an expression of a modulefield for the SELECT part of a SQL
 *  query.  Looks for a cached def, otherwise generates the def from the module
 *  field.
 */
function GetSelectDef($fieldName, $moduleID = null)
{
    $def = _getCachedFieldDef($fieldName, $moduleID);
    return $def[1];
}


/**
 *  Returns a Join Def
 *
 *  A Join Def is an expression of a modulefield for the FROM part of a SQL
 *  query (defines the joins needed to get the information from the field).
 *  Looks for a cached def, otherwise generates the def from the module
 *  field.
 */
function GetJoinDef($fieldName, $moduleID = null)
{
    $def = _getCachedFieldDef($fieldName, $moduleID);
    return $def[2];
}


/**
 *  Private function for use by GetSelectDef() and GetJoinDef()
 */
function _getCachedFieldDef($fieldName, $moduleID = null)
{
//print "_getCachedFieldDef($fieldName, $moduleID)\n";
    global $gFieldDefs;
    if(empty($moduleID)){
        global $SQLBaseModuleID;
        $moduleID = $SQLBaseModuleID;
    }
    if(empty($moduleID)){
        global $ModuleID;
        $moduleID = $ModuleID;
    }

    if(!defined('EXEC_STATE') || EXEC_STATE != 4){
        if(empty($gFieldDefs[$moduleID])){
            //look up file
            $genfile_name = GENERATED_PATH . "/{$moduleID}/{$moduleID}_FieldDefCache.gen";
            if(file_exists($genfile_name)){
                include_once($genfile_name);
            }
        }
    }

    if(empty($gFieldDefs[$moduleID][$fieldName])){
//print "generating FieldDef here\n";
        //generate the def here
        $modulefield = GetModuleField($moduleID, $fieldName);
        $field_def = array(
            $modulefield->getQualifiedName($moduleID),
            $modulefield->makeSelectDef($moduleID),
            $modulefield->makeJoinDef($moduleID)
        );
        $gFieldDefs[$moduleID][$fieldName] = $field_def;
    }
//print "FieldDef:\n";
//print_r($gFieldDefs);

    return $gFieldDefs[$moduleID][$fieldName];
}


/**
 *  returns a new search object with no conditions
 */
function &GetNewSearch($moduleID)
{
    $listFieldsFileName = GENERATED_PATH . "/{$moduleID}/{$moduleID}_ListFields.gen";

    if (!file_exists($listFieldsFileName)){
        trigger_error("Could not find file '$listFieldsFileName'.", E_USER_ERROR);
    }

    //the included file sets $listFields variable
    include $listFieldsFileName;

    $ModuleInfo = GetModuleInfo($moduleID);
    $listPK = $ModuleInfo->getPKField();
    $search =& new Search(
        $moduleID,
        $listFields
    );

    return $search;
}


/**
 *  Handy "object factory" function.
 */
function &MakeObject($moduleID, $name, $type, $attributes = array())
{
    require_once(CLASSES_PATH.'/module_map.class.php');
    $attributes['name'] = $name;
    $element =& new Element($name, $type, $attributes);
    return $element->createObject($moduleID);
}


/**
 *  Checks a PEAR DB query result for error messages
 */
function dbErrorCheck($dbResult, $die = true, $print = false)
{
    global $dbh;

    if (DB::isError($dbResult)) {
        if($dbResult != $dbh){
            $dbh->query("ROLLBACK");
        }
        //print $result->toString() . "\n";

        //$message = $dbResult->userinfo ."\n". $dbResult->getMessage();

        
        $reg = '/\[nativecode=(.*)\]/';
        $matches = array();
        preg_match($reg, $dbResult->userinfo, $matches);
        //print_r($matches);
        $server_code = $matches[1];

        $message = $dbResult->getMessage();
        $message .= "\nMySQL error ".$server_code;

        if($print){
            print debug_r($message). "<br />\n";
        }

        if($die || (defined('IS_RPC') && IS_RPC)){
            trigger_error($message, E_USER_ERROR);
            die();
        } else {
            trigger_error($message, E_USER_WARNING);
        }

        return false;
    }
    return true;
}



/**
 *  Checks that a SQL statement is (syntactically) valid 
 */
function CheckSQL($SQL, $die = true)
{
    global $dbh;
    global $SQLBaseModuleID;

    $debug_prefix = debug_indent("CheckSQL() $SQLBaseModuleID:");

    //start transaction
    $result = $dbh->query('BEGIN');
    dbErrorCheck($result);

    //execute statement
    $result = $dbh->query($SQL);
    if (DB::isError($result)) {
        $dbh->query("ROLLBACK");
        
        $message = "$debug_prefix SQL error: \n";
        $message .= $result->getMessage() . "\n";
        $message .= $SQL . "\n";
        $message .= $result->getMessage();
        
        $reg = '/\[nativecode=(.*)\]/';
        $matches = array();
        preg_match($reg, $result->userinfo, $matches);
        //print_r($matches);
        $server_code = $matches[1];
        $message .= "\nMySQL error ".$server_code;
        
        if(defined('EXEC_STATE') && EXEC_STATE == 4){
            die($message);
        }
        
        if($die){
            trigger_error($message, E_USER_ERROR);
            die();
        } else {
            trigger_error($message, E_USER_WARNING);
            
            debug_unindent();
            return false;
        }
    }

    //roll back transaction
    $result = $dbh->query('ROLLBACK');

    debug_unindent();
    return true;
}



/**
 * debug print function
 *
 * This function is a (really quick) attempt to control debug
 * messages.
 *
 * really, something better is dearly needed..........
 */
function printd($message, $level)
{

    if ($level <= DEBUG_LEVEL){ //1 = status messages, 2 = debug output (verbose)
        print $message;
    }
}



/**
 * pretty-indents debug messages from function calls: to be called at top of function declaration
 */
function debug_indent($prefix)
{
    global $debug_indent_level;
    global $debug_array;
    if(empty($debug_indent_level)){
        $debug_indent_level = 1;
        $debug_array = array($prefix);
    } else {
        $debug_indent_level++;
        $debug_array[] = $prefix;
    }
    if(defined('DEBUG_STYLE_PRINT_LEVEL') && DEBUG_STYLE_PRINT_LEVEL){
        print "d+ $debug_indent_level\n";
    }
    if(defined('DEBUG_STYLE_BACKTRACE') && DEBUG_STYLE_BACKTRACE){
        print join(' | ', $debug_array);
        print " (begin)\n";
    }
    if(defined('DEBUG_STYLE_INDENT') && DEBUG_STYLE_INDENT){
        return str_pad('', $debug_indent_level*3, ' ', STR_PAD_LEFT) . $prefix;
    } else {
        return $prefix;
    }
}



/**
 * companion to debug_indent: to be called immediately before any return statement
 */
function debug_unindent()
{
    global $debug_indent_level;
    global $debug_array;

    if(defined('DEBUG_STYLE_PRINT_LEVEL') && DEBUG_STYLE_PRINT_LEVEL){
        print "d- $debug_indent_level\n";
    }
    if(defined('DEBUG_STYLE_BACKTRACE') && DEBUG_STYLE_BACKTRACE){
        print join(' | ', $debug_array);
        print " (end)\n";
    }
    array_pop($debug_array);
    $debug_indent_level--;
    if(0 > $debug_indent_level){
        trigger_error("Debug indent level is zero: check code for mismatch between debug_indent() and debug_unindent()", E_USER_WARNING);
    }
}



/**
 * prints the output of print_r(), indented
 */
function indent_print_r($object, $print = true, $title = null)
{
    global $debug_indent_level;
    $indents = $debug_indent_level + 1;
    $pad = str_pad('', $indents*3, ' ', STR_PAD_LEFT);

    ob_start();
        if(!empty($title)){
            print $title.":\n";
        }
        print_r($object);
        $lines = explode("\n", ob_get_contents());
    ob_end_clean();

    $content = '';
    foreach($lines as $line){
        $content .= $pad . $line . "\n";
    }

    if($print){
        print $content;
    }
    return $content;
}



/**
 * orders join definitions so that dependents are always after parents
 */
function SortJoins($joins)
{

    global $SQLBaseModuleID;
    if(empty($SQLBaseModuleID)){
        global $ModuleID;
        $SQLBaseModuleID = $ModuleID;
    }

    global $gTableAliasParents;
    if(!defined('EXEC_STATE') || EXEC_STATE != 4){
        $tableAliasParents = array();
        include(GENERATED_PATH . "/{$SQLBaseModuleID}/{$SQLBaseModuleID}_TableAliases.gen");

        $gTableAliasParents[$SQLBaseModuleID] = $tableAliasParents;
    }

    $debug_prefix = debug_indent("SortJoins():");
    trace("$debug_prefix (begin)");


    if(count($joins) == 0){
        print "$debug_prefix no joins\n";
        //debug_unindent();
        return array();
    }

//    trace($joins, "$debug_prefix unsorted joins");
//    trace($gTableAliasParents[$SQLBaseModuleID], "$debug_prefix join parents");
//print "SQLBaseModuleID $SQLBaseModuleID<br />";
//print_r($gTableAliasParents);
//print_r($tableAliases);
//print_r($joins);

    $dependences = array();
    foreach($joins as $dependent => $join){
        if(!empty($join)){

            if(!empty($gTableAliasParents[$SQLBaseModuleID][$dependent])){
                $parent = $gTableAliasParents[$SQLBaseModuleID][$dependent];
                $dependences[$parent][] = $dependent;
            } else {
                if($SQLBaseModuleID == $dependent){
                    //not a problem
                } else {
                    trigger_error( "there should be a cached parent alias for base='$SQLBaseModuleID' dep='$dependent'\n", E_USER_WARNING);
                }
            }
        }

    }

//    print "$debug_prefix dependences:\n";
    //indent_print_r($dependences);
//    print debug_r($dependences);

    $newJoins = array();
    $ordered = array();
    $flat_dependences = array();
    $parents = array();

    foreach($dependences as $parent => $items){
        $parents[] = $parent;
        foreach($items as $item){
            $flat_dependences[$item] = $parent;
        }
    }

//    print "$debug_prefix flat dependents\n";
    //indent_print_r($flat_dependences);
//    print debug_r($flat_dependences);

//    trace($parents, "$debug_prefix parents");

/*
    //find root
    $root = '';
    foreach($parents as $parent){
        //if(0 === $parent){
        //    $root = $parent;
        //}
        if('*' != $parent){
            if(!in_array($parent, array_keys($flat_dependences))){
                $root = $parent;
            }
        } else {
            if(count($parents) == 1){
                $root = '*';
            }
        }
    }
*/
$root = $SQLBaseModuleID;
//    trace("$debug_prefix root: $root");
//print "$debug_prefix root: $root";
    

    //panic if there's no root
    if('' === $root){
        //die("$debug_prefix Can't find a root table in joins.");
        trigger_error("Can't find a root table in joins.", E_USER_ERROR);
    }

    //start by inserting root:
    $ordered[] = $root;
    $subs = array();
    $running = true;
    $current_parent = $root;
    $current_children = array();
    $bail_counter = 50;
    while($running){
        foreach($flat_dependences as $item => $parent){
            if($parent == $current_parent){
                $ordered[] = $item;
                unset($flat_dependences[$item]);
                $current_children[] = $item;
            }
        }

        //pick a new parent
        if(! ($current_parent = array_shift($current_children))){
            $running = false;

            //inset any remaining joins
            foreach($flat_dependences as $item => $parent){
                $ordered[] = $item;
            }
            break;
        }
//        trace("$debug_prefix new parent: $current_parent");
        if(count($flat_dependences) == 0){
            $running = false;
        } 
        $bail_counter--;
        if($bail_counter < 1){
            $running = false;
        }
    }

    //print "$debug_prefix ordered\n";
    //indent_print_r($ordered);
    foreach($ordered as $item){
        $newJoins[$item] = $joins[$item]; //append the joins in the new order
    }

//    trace($newJoins, "$debug_prefix sorted joins");
//    print debug_r($newJoins, "$debug_prefix sorted joins");
    trace("$debug_prefix (end)");
    debug_unindent();
    return $newJoins;
}



/**
 * returns a timestamp
 */
function getMicroTime()
{
    list($usec, $sec) = explode(' ', microtime());
    return (float)$sec + (float)$usec;
}



/**
 * sets a time stamp in the global $timestamps array
 */
function setTimeStamp($name)
{
    global $timestamps;
    $timestamps[$name] = getMicroTime();
}



/**
 * returns a performance report
 */
function getDuration()
{
    global $timestamps;
    $end_time = getMicroTime();
    $first_timestamp = reset($timestamps);
    $prev_timestamp = $first_timestamp;
    $durations = array();
    foreach($timestamps as $name => $timestamp){
        $durations['since_begin'][$name] = round($timestamp - $first_timestamp, 2);
        $durations['since_prev'][$name] = round($timestamp - $prev_timestamp, 2);
        $prev_timestamp = $timestamp;
    }
    $durations['since_begin']['end'] = round($end_time - $first_timestamp, 2);
    $durations['since_prev']['end'] = round($end_time - $prev_timestamp, 2);

    $wrapper = "<!-- %s -->";
    return sprintf($wrapper, indent_print_r($durations, false));
}


/**
 *  generic emailing function
 */
function sendEmail($from, $to, $subject, $textMessage, $HTMLMessage = null){
    include_once PEAR_PATH . '/Mail.php';
    include_once PEAR_PATH . '/Mail/mime.php';

    $mail_headers = array();
    //send message
    $mail_headers['Subject'] = $subject;
    $mail_headers['To']      = $to;
    $mail_headers['Reply-To'] = $from;
    $mail_headers['From']     = $from;
    $mail_headers['Return-Path'] = BOUNCE_EMAIL_ADDRESS;

    $mime =& new Mail_mime();
    $mime->setTXTBody($textMessage);
    if(!empty($HTMLMessage)){
        $mime->setHTMLBody($HTMLMessage);
    }
    $body = $mime->get();
    $headers = $mime->headers($mail_headers);

    $mail =& Mail::factory('mail');
    $result = $mail->send('', $headers, $body);
    if(PEAR::IsError($result)){
        trigger_error("Sending email failed.\n".$result->toString(), E_USER_WARNING);
        $statusID = 23;
    } else {
        $statusID = 3;
    }

    unset($mime);
    unset($mail);
    return $statusID;
}


/**
 * Custom error handler
 */
function handleError($error_number, $error_string, $error_file, $error_line)
{
    $errortype = array (
        E_ERROR           => "Error",
        E_WARNING         => "Warning",
        E_PARSE           => "Parsing Error",
        E_NOTICE          => "Notice",
        E_CORE_ERROR      => "Core Error",
        E_CORE_WARNING    => "Core Warning",
        E_COMPILE_ERROR   => "Compile Error",
        E_COMPILE_WARNING => "Compile Warning",
        E_USER_ERROR      => "AA Error",
        E_USER_WARNING    => "AA Warning",
        E_USER_NOTICE     => "AA Notice"
        );

    if(defined('EXEC_STATE') && EXEC_STATE == 1){
        $parsing = false;
        global $User;
        $happenedTo = "{$User->DisplayName} (ID {$User->PersonID}, IP {$_SERVER['REMOTE_ADDR']})";
    } else {
        $parsing = true;
        $happenedTo = 's2a generator';
    }

    //skip notices
    if(E_NOTICE == $error_number){
        return true;
    }

    //don't die on warnings (but do notify)
    if(E_WARNING == $error_number || E_USER_WARNING == $error_number){
        $die = false;
    } else {
        $die = true;
    }

    global $ModuleID;
    global $recordID;
    global $ScreenName;
    $appInfo = '';
    if(!empty($ModuleID)){
        $appInfo = "Module '$ModuleID',";
    }
    if(!empty($recordID)){
        $appInfo .= "Record '$recordID',";
    }
    if(!empty($ScreenName)){
        $appInfo .= "ScreenName '$ScreenName',";
    }
    $moreInfo = '';
    if(!empty($_GET)){
        $moreInfo = "\nGET: ".print_r($_GET, true);
    }
    if(!empty($_POST)){
        $moreInfo = "\nPOST: ".print_r($_POST, true);
    }

    $date = date('r');
    $error_msg = "An error of type {$errortype[$error_number]} happened to $happenedTo at $date.\n";
    $error_msg .= "$appInfo File $error_file, line $error_line:\n$error_string\n";
    $error_msg .= $moreInfo."\n";
    $backtrace = debug_backtrace();

    foreach($backtrace as $key => $trace_items){
        if($key == 0){
            $error_msg .= "backtrace:\n";
        } else {
            $error_msg .= "($key) function {$trace_items['function']}, file {$trace_items['file']},  line {$trace_items['line']}\n";
        }
    }

    if(defined('EMAIL_ERRORS') && EMAIL_ERRORS){
        //send error report to administrator
        sendEmail('errors@activeagenda.net', EMAIL_ERROR_ADDRESS, SITE_SHORTNAME . ' (error notice)', $error_msg);
    }
    if(defined('LOG_ERRORS') && LOG_ERRORS){
        logError($error_msg);
    }

    //if we're at parse time, print and die.
    if($parsing){
        echo $error_msg . "\n";
        if($die){
            die($error_string);
        }
    }

    $generic_message = '<b>'.gettext("An error occurrred while processing your request.")."</b>\n";
    $generic_message .= gettext("Since all errors are logged and administrators are automatically notified, it is likely that we are already working on it.

    You can help us by %sfiling an error report%s, explaining what you were trying to do, and what you would have expected to see. Please be as specific as you can.");
    $bugtracker_link = '<b><a href="http://bugs.activeagenda.net/" target="_blank">';

    $generic_message = nl2br(sprintf($generic_message, $bugtracker_link, '</a></b>'));

    if(defined('IS_RPC') && IS_RPC){
        if($die){
            //if we're in RPC, return a JSON object with an "error" property, die
            // create a new instance of JSON
            require_once(THIRD_PARTY_PATH .'/JSON.php');
            $json = new JSON();

            print $json->encode(array('error' => $generic_message));
            die();
        }
    } else {
        //if we're "in the browser", clear the buffer and display an error page, die

        if($die){
            ob_clean();
            global $theme;
            $title = gettext("Ooops! Something went wrong!");
            $content = $generic_message;
            if(empty($theme)){
                $theme = THEME_PATH . '/' . DEFAULT_THEME;
            }
            include($theme . '/error.template.php');
            die();
        } else {
            //print($generic_message);
        }
    }
    //echo $error_msg." (default error notice)\n";
}


/**
 *  Makes a print_r debug of an object (or whatever) and formats it.
 */
function text_debug_r($object, $title = '')
{
    ob_start();
        $content = '';
        if(!empty($title)){
            $content .= "$title\n";
        }
        print_r($object);
        $content .= ob_get_contents();
    ob_end_clean();
    return $content;
}


/**
 * Logs an error message
 */
function logError($message)
{
    $errorFile = GEN_LOG_PATH . '/errors.log';
    if(file_exists($errorFile)){
        $write_mode = 'a';
    } else {
        $write_mode = 'w';
    }
    
    if($fp = fopen($errorFile, $write_mode)) {
        $msg = str_replace("\n", "\r\n", $message); //convert unix line-endings to windows line-endings
        $msg .= "\n- - - - -\n\n";
        if(fwrite($fp, $msg)){
            //print no output about saving to log
        } else {
            die( "s2a: could not save to file $errorFile. Please check file/folder permissions.\n" );
        }
        fclose($fp);
    } else {
        die( "s2a: could not open file $errorFile. Please check file/folder permissions.\n" );
    }
}


function trace($message, $title = '')
{
    if(defined('EXEC_STATE') && EXEC_STATE == 1){
        if(defined('TRACE_RUNTIME') && TRACE_RUNTIME){
            static $traceFileName = 'init';

            if('init' == $traceFileName){
                $traceFileName = GEN_LOG_PATH . '/debug_'.date('Y-m-d_H-i-s').'.txt';
            }

            if($fp = fopen($traceFileName, 'a')) {
                $message = indent_print_r($message, false, $title);
                $message = "\n" . $message;
                $message = str_replace("\n", "\r\n", $message); //convert unix line-endings to windows line-endings
                if(fwrite($fp, $message)){
                    //print no output about saving to log
                } else {
                    die( "trace: could not save to file $traceFileName. Please check file/folder permissions.\n" );
                }
                fclose($fp);
            } else {
                die( "trace: could not open file $traceFileName. Please check file/folder permissions.\n" );
            }
        }
    } else {
        echo indent_print_r($message, false, $title) . "\n";
    }
}
?>