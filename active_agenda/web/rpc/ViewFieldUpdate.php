<?php
/**
 * Handles XMLHttpRequest messages (AJAX) from ViewFields to update their diplayed content
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

//causes session timeouts to return a catchable response
define('IS_RPC', true);

//main include file - performs all general application setup
require_once(INCLUDE_PATH . '/page_startup.php');

if(!empty($ModuleID)){
    $CachedFileName = GENERATED_PATH . '/'.$ModuleID.'/'.$ModuleID.'_ViewFieldSQL.gen';
    
    if(file_exists($CachedFileName)){
        include_once($CachedFileName);

        if($User->PermissionToView($ModuleID) > 0){ //should get ownerOrgID here
            $SQL = $viewFieldSQLs[$_POST['recipient']];
            $SQL = str_replace('/*recordID*/', intval($_POST['value']), $SQL);
            $value = $dbh->getOne($SQL);
            if(dbErrorCheck($value, false, false)){
                $content = $value;
            } else {
                $content = $value->userinfo;
            }
        } else {
            $content = gettext("PERMISSION DENIED: You appear not to have permission to this data.");
        }
    } else {
        $content = gettext("ERROR: The following file could not be found:").$CachedFileName;
    }
} else {

    $content = gettext("ERROR: No module ID.");
}

// create a new instance of JSON
require_once(THIRD_PARTY_PATH .'/JSON.php');
$json = new JSON();

$response = array();
$response['formname'] = $_POST['formname'];
$response['recipient'] = $_POST['recipient'];
$response['content'] = $content;
print $json->encode($response);
?>