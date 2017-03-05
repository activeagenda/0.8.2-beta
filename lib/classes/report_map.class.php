<?php
/**
 *  Class for parsing report definition XML
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

include_once CLASSES_PATH . '/module_map.class.php';


/**
* XML Map class specific to parsing ReportDef files
*/
class ReportMap extends XMLMap
{
var $moduleID;
var $name;
var $rootElement = 'ReportGroup';

/**
* Constructor
*/
function ReportMap($fileName)
{
    $this->XMLFileName = $fileName;
    $this->parseXMLFile();
    list($moduleID,,$name)  = explode('_', basename($fileName));

    $this->name = substr($name, 0, strpos($name, '.'));
    $this->moduleID = $moduleID;
    
    foreach($this->c as $id => $content){
        if('Report' == $content->type){
            $content->attributes['moduleID'] = $moduleID;
            $this->c[$id] = $content;
        }
    }
}

function &generateReports()
{
    $reports = array();
    foreach($this->c as $id => $content){
        if('Report' == $content->type){
            $reports[] = $content->createObject($this->moduleID);
        }
    }
    return $reports;
}

} //end class ReportMap
?>