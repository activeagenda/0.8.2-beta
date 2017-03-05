<?php
/**
 *  Generally available functions to be used at parse-time.
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
 * @version        SVN: $Revision: 539 $
 * @last-modified  SVN: $Date: 2007-02-27 20:06:04 -0800 (Tue, 27 Feb 2007) $
 */

include_once INCLUDE_PATH . '/general_util.php';

/**
 *  returns a (reference to a) module object of the specified module.
 */
function &GetModule($pModuleID) {
    $debug_prefix = debug_indent("GetModule() {$pModuleID}:");
    if(empty($pModuleID)){
        die("$debug_prefix No Module ID passed.");
    }
    include_once CLASSES_PATH . '/module.class.php';

    if(defined('EXEC_STATE') && EXEC_STATE == 4){

        //access the global foreignModules array
        global $foreignModules;

        //add structure of foreign module, unless it is either:
        //1. unavailable, 2. already done, 3. already in $foreignModules
        if(empty($foreignModules[$pModuleID])){

            $foreignModules[$pModuleID] = 'preparing';
            $t_ForeignModule =& new ForeignModule($pModuleID);

            //check whether it worked
            if ($t_ForeignModule->Parsed){

                $foreignModules[$pModuleID] =& $t_ForeignModule;

                print "$debug_prefix newly parsed $pModuleID\n";
                debug_unindent();
                return $t_ForeignModule;
            } else {

                $foreignModules[$pModuleID] = 'empty';

                print "$debug_prefix Could not add foreign module $pModuleID. Parse of its XML file was not successful.\n";

                debug_unindent();
                return false;
            }
        } else {

            $t_ForeignModule =& $foreignModules[$pModuleID];

            if (is_object($t_ForeignModule)){
                //print "$debug_prefix returning $pModuleID from global list\n";
                debug_unindent();
                return $t_ForeignModule;
            } else {
                indent_print_r($t_ForeignModule);
                print "$debug_prefix $pModuleID not found\n";
                debug_unindent();
                return false;
            }
        }

    } else {

        //we don't parse modules at run time
        debug_unindent();
        return false;
    }
}

/**
 *  Serializes and escapes a string/array/object so it can be easily saved to a generated file
 */
function escapeSerialize($param){
    return str_replace("'", "\\'",
                str_replace("\\", "\\\\",
                    serialize($param)
                )
            );
}



/**
 *  Indents a string with a specified number of tab characters
 */
function TabPad($string, $num){
    if(empty($num)){
        $num = 1;
    }

    if (FALSE === strpos($string, "\n")){
        //one line
        return str_repeat("\t", $num) . $string;
    } else {
        //several lines
        $lines = explode("\n", $string);
        $string = '';
        foreach($lines as $line){
            $string .= str_repeat("\t", $num) . $line . "\n";
        }
        return $string;
    }
}


function SaveGeneratedFile($modelFileName, $createFileName, $replaceValues, $moduleID = null){
    $debug_prefix = debug_indent("SaveGeneratedFile():");

    $modelFileName = MODELS_PATH . '/'.$modelFileName;
    if (file_exists($modelFileName)){
        //get the Model file
        $fp = fopen($modelFileName, 'r');
        $contents = fread($fp, filesize($modelFileName));
        fclose($fp);
        print "$debug_prefix Reading $modelFileName...\n";

        //replace placeholders in model with data from the code array
        foreach($replaceValues as $placeholder=>$code){
            $contents = str_replace($placeholder, $code, $contents);
        }

        //eliminate code between /**-remove_begin-**/ and /**-remove_end-**/ tags (can be nested)
        $offset = 0;
        $remove_starts = array();
        $remove_ends = array();

        $begin_tag = '/**-remove_begin-**/';
        $end_tag = '/**-remove_end-**/';

        if(false  !== strpos($contents, $begin_tag)){
            print "$debug_prefix begin removing\n";
            //indent_print_r($contents, true, 'BEFORE');
            while(false  !== strpos($contents, $begin_tag, $offset)){
                $start_pos = strpos($contents, $begin_tag, $offset);
                $end_pos = strpos($contents, $end_tag, $offset);
                $offset = strpos($contents, '/**-remove_', $offset);
                //$offset = 0;
                print "$debug_prefix looping... $start_pos, end: $end_pos, offset: $offset\n";

                if($start_pos == $offset){
                    $remove_starts[] = $start_pos;
                    $offset = $offset + strlen($begin_tag);
                } elseif($end_pos == $offset){
                    $remove_ends[] = $end_pos;
                    $offset = $offset + strlen($end_tag);
                } else {
                    indent_print_r($contents, true, 'what is wrong?');
                    die('something wrong with the remove-tags (nested wrong?)');
                }

                if(0 == count($remove_starts) && 0 == count($remove_ends)){
                    break;
                }
                if(count($remove_starts) == count($remove_ends)){
                    indent_print_r($remove_starts);
                    indent_print_r($remove_ends);
                    $start = reset($remove_starts);
                    $end   = end($remove_ends) + strlen($end_tag);

                    if($start > $end){
                        print "$debug_prefix remove start: $start, end: $end, offset: $offset\n";
                        indent_print_r($remove_starts);
                        indent_print_r($remove_ends);
                        indent_print_r($contents, true, 'Err');
                        die("how does this happen??");
                    }

                    //removes the code between $start and $end:
                    print "$debug_prefix remove start: $start, end: $end, offset: $offset\n";

                    $contents = substr($contents, 0, $start) . substr($contents, $end);

                    $remove_starts = array();
                    $remove_ends = array();
                }
                if($offset > strlen($contents)){
                    $offset = strlen($contents); //avoids a warning in "while" above
                }

            }
        }

        if(!empty($moduleID)){
            $generationLocation = GENERATED_PATH . '/'.$moduleID;
            if(!file_exists($generationLocation)){
                if(!mkdir($generationLocation)){
                    die( "$debug_prefix could not create folder '$moduleID'.\n" );
                }
            }
         } else {
            $generationLocation = GENERATED_PATH;
         }

        //write the new file to the disk
        if($fp = fopen($generationLocation . "/{$createFileName}", 'w')) {
            if(fwrite($fp, $contents)){
                print "$debug_prefix file $createFileName saved.\n";
            } else {
                die( "$debug_prefix could not create file $createFileName.\n" );
            }
            fclose($fp);

            //make sure cached files can be executed
            $perm = 0775;
            chmod($generationLocation . "/{$createFileName}", $perm);
            chgrp($generationLocation . "/{$createFileName}", 'adm');
            print "$debug_prefix set file permission to ".decoct($perm)."\n";
        }
        else {
            die("$debug_prefix Unable to write file $createFileName.\n");
        }
    } else {
        print "$debug_prefix Could not find $modelFileName\n";
    }

    debug_unindent();
}


function prompt($q)
{
   print "$q\n";
   print "(Type 'y' or 'n' and hit ENTER.)\n";
   $response = fread(STDIN, 2);
   $response = strtolower(trim($response));
   $response = $response[0];

   return 'y' == $response;
}

function textPrompt($q)
{
   print "$q\n";
   print "Press ENTER when finished: ";
   $response = fread(STDIN, 30);

   return trim($response);
}

function shellQuery($sql, $db_name = ''){
   global $mysql_command;
   global $root_pwd;

   print "shellQuery '$sql'\n";
   $temp_file_path = tempnam('/tmp', 'aatmp');
   $temp_h = fopen($temp_file_path, 'w');
   fwrite($temp_h, $sql);
   fclose($temp_h);

   //for some reason mysql --execute='$sql'" wouldn't work at all, so we use the temp file
   if(empty($db_name)){
      $command = "$mysql_command -u root -p{$root_pwd} < $temp_file_path";
   } else {
      $command = "$mysql_command -u root -p{$root_pwd} $db_name < $temp_file_path";
   }

   $result = shellCommand($command, true);

   unlink($temp_file_path);
   return $result;
}


/**
 * Executes a shell command
 */
function shellCommand($command, $print = true, $die = true)
{
   if($print){
      print "$debug_prefix Executing command: $command\n";
   }
   ob_start();

   system($command, $errcode);
   $result = ob_get_contents();
   ob_end_clean();

   if($errcode > 0){
      print "shell output:\n";
      print $result;
    if($die){
      die("$debug_prefix Error $errcode. Could not perform the following command:\n$command\n");
    }
   } elseif($print){
      print $result;
   }
   return $result;
}


/**
 *  Recursive function to find correct MySQL folder (windows only)
 */
function findMySQLexe($folder)
{
   $file_list = glob($folder . '\\*');
   foreach($file_list as $file_path){
      if(false !== strpos(strtolower($file_path), 'mysql.exe')){
         return $file_path;
         break;
      } elseif($found_path = findMySQLexe($file_path)){
         break;
      }
   }
   return $found_path;
}
?>