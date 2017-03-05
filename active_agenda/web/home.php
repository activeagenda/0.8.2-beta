<?php
/**
 * Handles content for the Dashboard ('Home') Screen
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

//main include file - performs all general application setup
require_once INCLUDE_PATH . '/page_startup.php';

include_once $theme .'/component_html.php';

//class defs for dashboad grids
include_once INCLUDE_PATH.'/dashboardGrids.php';

$dashGridInfo = array('acc'=>0, 'act'=>0, 'usrds'=>0);

foreach($dashGridInfo as $dashGridID => $count){
    include_once GENERATED_PATH.'/dsb/dsb_'.$dashGridID.'DashboardGrid.gen'; //returns $dashgrid
    $countSQL = $dashgrid->prepareCountSQL();
    //echo debug_r($countSQL);
    $count = $dbh->getOne($countSQL);
    $dashGridInfo[$dashGridID] = $count;
}

include_once(INCLUDE_PATH . '/dashboardCharts.php');

$title = sprintf(gettext("%s's Dashboard"), $User->DisplayName);
//$content;
include_once($theme . '/home.template.php');

?>