<?php
/**
 *  Class Definitions for data importing purposes
 *
 *  PHP version 4
 *
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
 *
 * @author         Mattias Thorslund <mthorslund@activeagenda.net>
 * @copyright      2003-2007 Active Agenda Inc.
 * @license        http://www.activeagenda.net/license  RPL 1.4
 * @version        SVN: $Revision: 499 $
 * @last-modified  SVN: $Date: 2007-02-16 13:43:40 -0800 (Fri, 16 Feb 2007) $
 */


//data_map.class.php

include_once CLASSES_PATH . '/module_map.class.php';


/**
 * XML Map class specific to parsing XML data files
 */
class DataMap extends XMLMap {

var $rootElement = 'S2aData';


/**
 * Constructor
 */
function DataMap($fileName)
{
    $this->XMLFileName = $fileName;
    $this->parseXMLFile();
}

} //end class DataMap



class DataImporter
{
var $moduleID;
var $defaultAction;
var $records = array(); //will add DataG|Handler objects here

function DataImporter($element)
{
    $this->moduleID = $element->attributes['moduleID'];
    $this->defaultAction = $element->attributes['defaultAction'];
    if(count($element->c) > 0){
        foreach($element->c as $record_element){
            if('Record' == $record_element->type){
                $this->records[] = new DataRecord($record_element);
            }
        }
    } else {
        trigger_error("No records to import into {$this->moduleID}.", E_USER_ERROR);
    }
}

function import($overrideRecordIDs = array())
{
    $dataHandler =& GetDataHandler($this->moduleID, true);
    foreach($this->records as $record){

        $sub_overrideRecordIDs = $dataHandler->saveRow($record->fieldValues, $overrideRecordIDs, $record->globalID);
        $record->import($sub_overrideRecordIDs);
    }
    return true;
}

} //end class DataImporter



class DataRecord
{
var $fieldValues = array();
var $globalID;
var $subImporters = array();

function DataRecord($element)
{
    $this->globalID = $element->attributes['globalID'];
    if(count($element->c) > 0){
        foreach($element->c as $value_element){
            if('RecordValue' == $value_element->type){
                $this->fieldValues[$value_element->attributes['fieldName']] = $value_element->attributes['value'];
            } elseif('Records' == $value_element->type){
                $this->subImporters[] =& new DataImporter($value_element);
            } else {
                //unknown element type
            }
        }
    }
}

function import($overrideRecordIDs = array())
{
    if(count($this->subImporters) > 0){
        foreach($this->subImporters as $importer){
            $importer->import($overrideRecordIDs);
        }
    }
}
} //end class DataRecord
?>