<?php
/**
 * Handles XMLHttpRequest messages (AJAX) from SearchSelectGrids
 *
 * Returns a list of items (usu. people) matching the submotted search criteria
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
require_once '../../config.php';
set_include_path(PEAR_PATH . PATH_SEPARATOR . get_include_path());

//necessary for unserializing
include_once CLASSES_PATH . '/search.class.php';
include_once CLASSES_PATH . '/components.php';

//causes session timeouts to return a catchable response
define('IS_RPC', true);

//main include file - performs all general application setup
require_once INCLUDE_PATH . '/page_startup.php';

//search fields of the module, serialized in this file
$filename = GENERATED_PATH . '/'.$ModuleID.'/'.$ModuleID.'_SearchFields.gen';

//check for cached page for this module
if (!file_exists($filename)){
    die("ERROR MESSAGE (ssgGetAvailable.php): Could not find file '$filename'.");
}

//the included file extracts $searchFields
include $filename;

// create a new instance of JSON
require_once THIRD_PARTY_PATH.'/JSON.php';
$json = new JSON();

$search = $_SESSION['Search_ssg_'.$ModuleID];
if(empty($search)){
    $search = new Search($ModuleID);
}

$search->prepareFroms(&$searchFields, &$_POST);

$SQL = $search->getListSQL('Name');

$available = $dbh->getAssoc($SQL);

print $json->encode($available);
?>


