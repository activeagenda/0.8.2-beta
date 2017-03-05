<?php
/**
 * Creates downloadable data on the fly: XML, CSV or Excel
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
 * author         Mattias Thorslund <mthorslund@activeagenda.net>
 * copyright      2003-2007 Active Agenda Inc.
 * license        http://www.activeagenda.net/license
 **/

//general settings
require_once '../config.php';
set_include_path(PEAR_PATH . PATH_SEPARATOR . get_include_path());

//this include contains the search class
include_once CLASSES_PATH . '/search.class.php';
include_once CLASSES_PATH . '/components.php';

//this causes session timeouts to display a message instead of redirecting to the login screen 
DEFINE('IS_POPUP', true);

//main include file - performs all general application setup
require_once INCLUDE_PATH . '/page_startup.php';

$search = $_SESSION["Search_$ModuleID"];

if(!is_object($search)){
    trigger_error("An active search is required.", E_USER_ERROR);
}

if(!isset($_GET['type'])){
    trigger_error("Invalid request URL. A type is required.", E_USER_ERROR);
}

//check that there's a person selected
$fileTypeSelected = intval($_GET['type']);

$SQL = $search->getListSQL();
$SQL .= $User->getListFilterSQL($ModuleID, true);  //also checks permission

//execute SQL statement
$r = $dbh->getAll($SQL, DB_FETCHMODE_ASSOC);
dbErrorCheck($r);

switch($fileTypeSelected){
case 1:
    //csv
    $saveAsName = 'file.csv';

    header("Content-Type: text/plain");
    header("Content-Disposition: attachment; filename=$saveAsName");
    $names = array_keys($r[0]);
    print join(', ', $names) . "\n";

    foreach($r as $row){
        foreach($row as $name => $valule){
            $row[$name] = addslashes($valule);
        }
        print '"';
        print join('","', $row);
        print "\"\n";
    }

    break;
case 2:
    //xml
    $saveAsName = 'file.xml';

    header("Content-Type: text/xml");
    header("Content-Disposition: attachment; filename=$saveAsName");
    print "<document>\n";
    foreach($r as $row){
        print "<record>\n";
        foreach($row as $name => $valule){
            print "<$name>$valule</$name>\n";
        }
        print "</record>\n";
    }
    print "</document>\n";

    break;
case 3:
    //excel
    $saveAsName = 'file.xls';

    //using PEAR class Spreadsheet_Excel_Writer
    require_once(PEAR_PATH . '/Spreadsheet/Excel/Writer.php');
    $workbook = new Spreadsheet_Excel_Writer();

    //send headers
    $workbook->send($saveAsName);

    //format for field names
    $titleFormat =& $workbook->addFormat();
    $titleFormat->setBold();

    //add a worksheet
    $worksheet =& $workbook->addWorksheet('data');

    $colwidths = array();

    //titles
    foreach(array_keys($r[0]) as $fieldindex=>$fieldname){
        $worksheet->write(0, $fieldindex, $fieldname, $titleFormat);
        $colwidths[$fieldindex] = strlen($fieldname);
    }

    //data
    foreach($r as $rowindex => $row){
        foreach(array_values($row) as $fieldindex => $fieldvalue){
            $worksheet->write($rowindex+1, $fieldindex, $fieldvalue);

            if(strlen($fieldvalue) > $colwidths[$fieldindex]){
                $colwidths[$fieldindex] = strlen($fieldvalue);
            }
        }
    }

    //column widths
    foreach($colwidths as $colindex => $colwidth){
        $worksheet->setColumn($colindex, $colindex, $colwidth);
    }

    $worksheet->freezePanes(array(1)); //top row visible
    $workbook->close();

    break;
default:
    trigger_error("Invalid request URL. Unknown file type requested.", E_USER_ERROR);
    break;
}
?>