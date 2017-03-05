<?php
/**
 *  Displays the image link to the live dashboard charts
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

//chart ID, title, condition phrases, chart
define(CHART_TRIM, 
'<div class="charttrim" id="chart%1$s">
<div class="chartarea">
<div class="chartbar">
<div class="charticons">
    <a onclick="moveChartUp(%1$s)"><img src="'.$theme_web.'/img/chart_left.png" title="move chart left/up" alt="move chart left/up" onmouseover="imgOver(this, \''.$theme_web.'/img/chart_left_o.png\')" onmouseout="imgOut(this)"/></a><a onclick="moveChartDown(%1$s)"><img src="'.$theme_web.'/img/chart_right.png" title="move chart right/down" alt="move chart right/down" onmouseover="imgOver(this, \''.$theme_web.'/img/chart_right_o.png\')" onmouseout="imgOut(this)"/></a><a onclick="removeChart(%1$s)"><img src="'.$theme_web.'/img/chart_remove.png" title="remove this chart from the desktop" alt="remove this chart from the desktop"/></a>
</div>
<h3>%2$s</h3>
</div>

<p class="chartphrases">%3$s</p>
%4$s
</div>
</div>');



$SQL = "SELECT 
    dsbc.DashboardChartID, 
    modch.Title,
    dsbc.ModuleID,
    dsbc.ConditionPhrases
FROM dsbc 
    INNER JOIN modch 
    ON dsbc.ChartName = modch.Name AND dsbc.ModuleID = modch.ModuleID
WHERE dsbc.UserID = {$User->PersonID} 
AND dsbc._Deleted = 0 
ORDER BY dsbc.SortOrder";


$dashCharts = $dbh->getAll($SQL, DB_FETCHMODE_ASSOC);
$loadChartJS = '';
$load_url = $theme_web . '/img/chart_loading.png';

if(count($dashCharts) > 0){
    foreach($dashCharts as $dashChart){
        $url = 'chartViewer.php?dsbc='.$dashChart['DashboardChartID'].'&mini=1&t='.time();
        
        $chartID = 'dc'.$dashChart['DashboardChartID'];
        $content .= sprintf(
            CHART_TRIM,
            $dashChart['DashboardChartID'],
            $dashChart['Title'],
            $dashChart['ConditionPhrases'],
            '<a href="charts.php?mdl='.$dashChart['ModuleID'].'&dsbc='.$dashChart['DashboardChartID'].'&t='.time().'"><img id="'.$chartID.'" width="300" height="200" src="'.$load_url.'"></a>'
        ); //appending the date integer ensures that the browser cache isn't used

        //$loadChartJS .= "loadChart('$chartID', '$url');\n";
        $loadChartJS .= "var chartloader_$chartID = function(e){loadChart('$chartID', '$url')}\n";
        $loadChartJS .= " YAHOO.util.Event.addListener(window, 'load', chartloader_$chartID)\n";
    }

    $content .= '<script language="JavaScript">'.$loadChartJS.'</script>';
} else {
    $content .= '<p>';
    $content .= gettext("You have no charts yet. You can add charts to this dashboard from the Charts screen in any module.");
    $content .= '</p>';
}


?>