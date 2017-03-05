<?php
/**
 *  Classes for preparing data for report output
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
 * @version        SVN: $Revision: 498 $
 * @last-modified  SVN: $Date: 2007-02-16 13:29:54 -0800 (Fri, 16 Feb 2007) $
 */



include_once CLASSES_PATH . '/components.php';


/**
 * methods and properties shared by the Report and SubReport classes
 */
class AbstractReport
{

var $moduleID;
var $name;  //internal name (for file names etc) - no spaces in name
var $title; //user-displayed title
var $hierarchyID; //ID in the hierarchy of reports and subreports
var $localKey;
var $fields = array();
var $orderByFields = array();
var $subReports = array();
var $rootReport;
var $singularRecordName;
var $distinct = false; //whether the SELECT statemenr includes DISTINCT


/**
 *  Removes some properties that aren't necessary after serializing
 */
function __sleep()
{
    //the properties to be saved when serializing
    $properties = get_object_vars($this);
    unset($properties['rootReport']);
    unset($properties['parentReport']);
    return array_keys($properties);
}


/**
 * returns the SQL statement for the report or subreport
 */
function buildSQL()
{
    $debug_prefix = debug_indent("AbstractReport->buildSQL() {$this->moduleID}:");

    global $SQLBaseModuleID;
    $SQLBaseModuleID = $this->moduleID;

    $selects = array();
    $joins = array();

    foreach($this->fields as $fieldName => $field){
        $selects[] = $field->makeSelectDef($this->moduleID);
        $joins = array_merge($joins, $field->makeJoinDef($this->moduleID));
    }
    $joins = SortJoins($joins);

    if($this->distinct){
        $SQL = "SELECT DISTINCT\n";
    } else {
        $SQL = "SELECT \n";
    }
    $SQL .= join(",\n", $selects);
    $SQL .= "\n";
    $SQL .= "FROM \n `{$this->moduleID}`\n";
    foreach($joins as $alias => $def){
        $SQL .= "$def\n";
    }

    $SQL .= $this->buildParentSQL($this->moduleID);

    if(count($this->groupByFields) > 0){
        $SQL .= " GROUP BY " . join(', ', $this->groupByFields);
    }

    if(count($this->orderByFields) > 0){
        $SQL .= " ORDER BY ";
        $onFirst = true;
        foreach($this->orderByFields as $fieldName => $direction){
            if($onFirst){
                $SQL .= " $fieldName";
                $onFirst = false;
            } else {
                $SQL .= ",\n $fieldName";
            }
            if(!empty($direction)){
                $SQL .= ' '.$direction;
            }
        }
    }

    $this->rootReport->SQLs[$this->hierarchyID] = $SQL;

    debug_unindent();
}


function buildParentSQL($localAlias)
{
    $SQL = "WHERE $localAlias._Deleted = 0 AND $localAlias.{$this->localKey} = '/**ReportRecordID**/'";
    /*if('summarize' != $this->mode){
        $SQL .= " ORDER BY $localAlias.{$this->localKey}";
    }*/
    return $SQL;
}

} //end class AbstractReport



/**
 * Represents a report document
 */
class Report extends AbstractReport
{

var $datasets = array();
var $SQLs = array();
var $reportLocations = array();
var $reportPage;
var $displayFormat;
var $isLoaded = false;
var $mode = 'list';
var $groupByFields = array();


/**
 *  Factory method
 */
function &Factory($element, $moduleID)
{
    $object =& new Report();

    $object->moduleID = $element->attributes['moduleID'];

    $object->name = $element->name;

    if(!isset($element->attributes['displayFormat'])){
        $object->displayFormat = 'html-linear';
    } else {
        $object->displayFormat = $element->attributes['displayFormat'];
    }

    if(!empty($element->attributes['title'])){
        $object->title = $element->attributes['title'];
    } else {
        $object->title = $element->name;
    }

    if(!empty($element->attributes['mode'])){
        $object->mode = $element->attributes['mode'];
    }

    if(!empty($element->attributes['distinct']) && 'yes' == strtolower($element->attributes['distinct'])){
        $object->distinct = true;
    }

    $object->hierarchyID = $object->moduleID;
    $object->rootReport =& $object;

    $module = GetModule($object->moduleID);
    $rowIDField = end($module->PKFields);
    $object->localKey = $rowIDField;

    if(!empty($element->attributes['singularRecordName'])){
        $object->singularRecordName = $element->attributes['singularRecordName'];
    } else {
        $object->singularRecordName = $module->singularRecordName;
    }

    //header (title) field
    if(!empty($element->attributes['headerField'])){
        $object->headerField = $element->attributes['headerField'];
        $field_element = new Element($object->headerField,'ReportField',array('invisible' => true));
        $object->fields[$object->headerField] = $field_element->createObject($object->moduleID);
    }

    //sets up SubReports so they have a reference BACK to the report
    foreach($element->c as $content){
        switch($content->type){
        case 'ReportLocation':
            switch($content->attributes['level']){
            case 'Record':
                $object->reportLocations['Record'] = $content->attributes['group'];
                break;
            case 'List':
                $object->reportLocations['List'] = $content->attributes['group'];
                break;
            default:
                //ignores any other levels for now
                break;
            }
            break;
        case 'ReportField':
            $field = $content->createObject($object->moduleID);
            $object->fields[$content->name] = $field; 

            if('summarize' == $object->mode && 'groupby' == $field->summarize){
                $object->groupByFields[] = $field->name;
            }
            break;
        case 'OrderByField':
            $object->orderByFields[$content->name] = $content->attributes['direction'];
            break;
        case 'SubReport':
            $subReport =& $content->createObject($object->moduleID, null, &$object);
            $object->subReports[$content->attributes['moduleID']] = $subReport;

            if(!isset($object->fields[$subReport->parentKey])){
                $field_element = new Element($subReport->parentKey, 'ReportField',array('invisible' => true));
                $object->fields[$subReport->parentKey] = $field_element->createObject($object->moduleID);
            }
            if(count($subReport->conditions) > 0){
                foreach($subReport->conditions as $conditionField => $conditionValue){
                    if(false !== strpos($conditionValue, '*')){
                        $conditionValueField = str_replace('*', '', $conditionValue);
                        $field_element = new Element($conditionValueField, 'ReportField',array('invisible' => true));
                        $object->fields[$conditionValueField] = $field_element->createObject($object->moduleID);
                    }
                }
            }

            unset($subReport);
            break;
        case 'ReportPage':
            $object->reportPage =& $content->createObject($object->moduleID, null, &$object);
            break;
        default:
            die("Unexpected {$content->type} content in Report element {$report->moduleID}-{$element->name}");
        }
    }

    $object->buildSQL();

    return $object;
}


/**
 *  Populates the report's datasets property with data.
 */
function loadData($recordID)
{
    global $dbh;

    if(empty($recordID)){
        if(in_array('List', array_keys($this->reportLocations))){
            global $ModuleID;
            $ModuleInfo = GetModuleInfo($ModuleID);
            $listPK = $ModuleInfo->getPKField();

            if(isset($_SESSION['Search_'.$ModuleID])){
                $search = $_SESSION['Search_'.$ModuleID];
            } else {

                $search = new Search(
                    $this->moduleID,
                    array($listPK)
                );
            }
            $searchSQL = $search->getCustomListSQL(array($listPK));
            $searchSQL = 'IN ('.$searchSQL.')';
        } else {
            trigger_error(gettext("This report is not designed to be displayed as a List report"), E_USER_ERROR);
        }

        foreach($this->SQLs as $hierarchyID => $SQL){
            $SQL = str_replace('= \'/**ReportRecordID**/\'', $searchSQL, $SQL);
            $result = $dbh->getAll($SQL, DB_FETCHMODE_ASSOC);
            dbErrorCheck($result);
            $this->dataSets[$hierarchyID] = $result;
        }
    } else {

        foreach($this->SQLs as $hierarchyID => $SQL){
            $SQL = str_replace('/**ReportRecordID**/', $recordID, $SQL);
            $result = $dbh->getAll($SQL, DB_FETCHMODE_ASSOC);
            dbErrorCheck($result);
            $this->dataSets[$hierarchyID] = $result;
        }
    }
    $this->isLoaded = true;
} //end loadData()


/**
 *  Fills in the FDF form of a PDF file and sends the output to browser.
 *
 *  This requires the 'pdftk' command-line utility from accesspdf.com.
 */
function renderPDF($recordID)
{
    global $report_render_mode;
    $report_render_mode = 'pdf';

    $this->loadData($recordID);
    $dataPages = $this->reportPage->generateData($this->dataSets);

    //uncomment these lines to test that the data is populated correctly into the data pages 
    //print debug_r($this->dataSets);
    //print debug_r($dataPages);
    //die('test');

    $pdfDoc = PDF_PATH .'/'. $this->reportPage->filename;

    if(!file_exists($pdfDoc)){
        trigger_error("Cannot find source PDF file $pdfDoc.", E_USER_ERROR);
    }

    /**
     * adapted from an example from www.pdfhacks.com (begin)
     **/
    require_once( INCLUDE_PATH . '/forge_fdf.php' );

    $fdf_basefilename= tempnam( '/tmp', 'fdf' );
    unlink( $fdf_basefilename ); // delete temp file

    foreach($dataPages as $dataPageID => $dataPage){

        $fdf_data_names = array();
        $fields_hidden = array();
        $fields_readonly = array();
        $fdf_filename = $fdf_basefilename . '-'.str_pad($dataPageID, 3, '0', STR_PAD_LEFT);

        $fdf= forge_fdf(
            '',
            $dataPage,
            $fdf_data_names,
            $fields_hidden,
            $fields_readonly
            );

        $fp = fopen( $fdf_filename, 'w' );
        if( $fp ) {
            fwrite( $fp, $fdf );
            fclose( $fp );

            $command = "pdftk $pdfDoc fill_form $fdf_filename output $fdf_filename.pdf flatten"; 
            exec($command, $err_output, $return_status);
            if(0 != $return_status){ 
                trigger_error( 'Error: trouble with creating PDF report page. pdftk returned '. $err_output . ' (status '. $return_status.')', E_USER_ERROR );
            }
        }
        else { // error
            trigger_error( 'Error: unable to open temp file for writing fdf data: '. $fdf_filename, E_USER_ERROR );
        }
    }

    global $User;

//    header( 'Cache-Control: private, no-cache' );
//    header( 'Pragma: no-cache' );

    if($User->browserInfo['is_IE']){
        //display inline (we expect that this is in a new window)
        header( 'content-disposition: inline; filename="'.$this->reportPage->filename.'"' ); //filename might still not be used by browser
    } else {
        // prompt to save to disk or open directly in PDF viewer
        header( 'content-disposition: attachment; filename="'.$this->reportPage->filename.'"' );
    }
    header( 'Content-type: application/pdf' );

    $command = "pdftk $fdf_basefilename*.pdf cat output - ";
    passthru($command, $return_status);
    if(0 != $return_status){
        trigger_error( 'Error: trouble with concatenating PDF report pages. pdftk returned '. $return_status, E_USER_ERROR );
    }

    $files = glob($fdf_basefilename.'*');
    foreach($files as $filename){
        unlink( $filename ); // delete temp file
    }

    /**
     * adapted from an example from www.pdfhacks.com (end)
     */

} //end renderPDF()


/**
 * returns the report's data (including subreports) as HTML
 */
function renderHTML($recordID)
{
    $this->loadData($recordID);

    $content = '<h1>'.$this->title.'</h1>';
    foreach($this->dataSets[$this->hierarchyID] as $row){
        if(!empty($this->headerField)){
            $content .= '<h2>'. $row[$this->headerField].'</h2>';
        } else {
            $content .= '<h2>'.$this->singularRecordName.'</h2>';
        }
        $content .= '<table>';
        foreach($this->fields as $fieldName => $field){
            if($field->isVisible()){
                $content .= '<tr>';
                $content .= '<td>'.$field->phrase.':</td><td>'.$field->simpleRender($row).'</td>';
                $content .= '</tr>';
            }
        }
        $content .= '</table>';
        if(count($this->subReports)>0){
            foreach($this->subReports as $subReport){
                $subParentID = $row[$subReport->parentKey];
                $content .= $subReport->renderHTML($subParentID, $this);
            }
        }
    }
    $content = '<div class="report">'.$content.'</div>';
    return $content;
}


/**
 * returns the report's data (including subreports) as XML
 */
function renderXML($recordID)
{
    $this->loadData($recordID);

    $content = '<S2aData>
    <Records moduleID="'.$this->moduleID.'">';

    foreach($this->dataSets[$this->hierarchyID] as $row){
        $globalIDInsert = '';
        if(!empty($row['_GlobalID'])){
            $globalIDInsert = "globalID=\"{$row['_GlobalID']}\"";
        }
        $content .= "<Record $globalIDInsert>";

        foreach($this->fields as $fieldName => $field){
            $content .= "<RecordValue fieldName=\"$fieldName\" value=\"".htmlspecialchars($field->viewRender($row))."\" />";
        }

        if(count($this->subReports)>0){
            foreach($this->subReports as $subReport){
                $subParentID = $row[$subReport->parentKey];
                $content .= $subReport->renderXML($subParentID, $this);
            }
        }
        $content .= '</Record>';
    }
    $content .= '</Records> 
</S2aData>';
    return $content;
}

}  //end class Report



/**
 * Represents submodule data in a report document
 */
class SubReport extends AbstractReport
{

var $parentKey;
var $conditions = array();
var $parentReport;
var $headerField;


/**
 *  Factory method
 */
function &Factory($element, $moduleID, &$callerRef){
    $debug_prefix = debug_indent("SubReport::Factory() {$moduleID}_{$element->attributes['moduleID']}:");

    $object =& new SubReport();

    $object->moduleID = $element->attributes['moduleID'];

    $object->name = $element->name;
    if(!empty($element->attributes['title'])){
        $object->title = $element->attributes['title'];
    } else {
        $object->title = $element->name;
    }
    if(!empty($element->attributes['distinct']) && 'yes' == strtolower($element->attributes['distinct'])){
        $object->distinct = true;
    }

    $object->parentReport =& $callerRef;
    if(get_class($callerRef) == 'report'){
        $object->rootReport =& $callerRef;
    } else {
        $object->rootReport =& $callerRef->rootReport;
    }

    $object->hierarchyID = $callerRef->hierarchyID . '_' . $object->moduleID;

    $parentModule = GetModule($moduleID);
    $currentModule =& $parentModule->getSubModule($object->moduleID);
    if(empty($currentModule)){
        print "$debug_prefix Submodule {$object->moduleID} not found in $moduleID ".get_class($parentModule)." object\n";
    }

    //loads localKey, parentKey and conditions
    if(empty($element->attributes['parentKey'])){
        $object->localKey = $currentModule->localKey;
        $object->parentKey = $currentModule->parentKey;
        $object->conditions = $currentModule->conditions;
    } else {
        $object->localKey = $element->attributes['localKey'];
        $object->parentKey = $element->attributes['parentKey'];
        $condition_elements = $element->selectElements('SubReportCondition');
        if(count($condition_elements)>0){
            foreach($condition_elements as $condition_element){
                $object->conditions[$condition_element->attributes['field']] = $condition_element->attributes['value'];
            }
        }
    }

    if(!empty($element->attributes['singularRecordName'])){
        $object->singularRecordName = $element->attributes['singularRecordName'];
    } else {
        $object->singularRecordName = $currentModule->singularRecordName;
    }

    //ensure the localKey is included
    if(!isset($object->fields[$object->localKey])){
        $field_element = new Element($object->localKey,'ReportField',array('invisible' => true));
        $object->fields[$object->localKey] = $field_element->createObject($object->moduleID);
    }

    //header (title) field
    if(!empty($element->attributes['headerField'])){
        $object->headerField = $element->attributes['headerField'];
        $field_element = new Element($object->headerField,'ReportField',array('invisible' => true));
        $object->fields[$object->headerField] = $field_element->createObject($object->moduleID);
    }

    //loads report fields and subReports
    foreach($element->c as $content){
        switch($content->type){
        case 'ReportField':
            //$object->fields[$content->name] = 'ReportField';
            $object->fields[$content->name] = $content->createObject($object->moduleID);
            break;
        case 'OrderByField':
            $object->orderByFields[$content->name] = $content->attributes['direction'];
            break;
        case 'SubReport':
            $subReport =& $content->createObject($object->moduleID, null, &$object);
            $subModuleID = $content->attributes['moduleID'];
            $object->subReports[$subModuleID] = $subReport;

            if(!isset($object->fields[$subReport->parentKey])){
                $field_element = new Element($subReport->parentKey,'ReportField',array('invisible' => true));
                $object->fields[$subReport->parentKey] = $field_element->createObject($object->moduleID);
            }

            if(count($subReport->conditions) > 0){
                foreach($subReport->conditions as $conditionField => $conditionValue){
                    if(false !== strpos($conditionValue, '*')){
                        $conditionValueField = str_replace('*', '', $conditionValue);
                        $field_element = new Element($conditionValueField, 'ReportField',array('invisible' => true));
                        $object->fields[$conditionValueField] = $field_element->createObject($object->moduleID);
                    }
                }
            }

            unset($subReport);
            break;
        case 'SubReportCondition':
            //skip -- already handled
            break;
        default:
            die("Unexpected {$content->type} content in Report element {$object->moduleID}-{$element->name}");
        }
    }

    $object->buildSQL();

    debug_unindent();
    return $object;
}


/**
 *  Builds the SQL snip that contains the joins with the parent report(s)
 */
function buildParentSQL($localAlias){

    $parentModuleID = $this->parentReport->moduleID;

    $SQL = "INNER JOIN `{$parentModuleID}` AS {$parentModuleID}_p
    ON (`$localAlias`._Deleted = 0 AND `{$parentModuleID}_p`.{$this->parentKey} = `$localAlias`.{$this->localKey}";

    if(count($this->conditions) > 0){
        foreach($this->conditions as $conditionField => $conditionValue){
            if(FALSE === strpos('*', $conditionValue)){
                $conditionValue = "'$conditionValue'";
            } else {
                die('hold on - we need to define the value replacement for '.$conditionValue);
            }
            $SQL .= "\nAND $conditionValue = `$localAlias`.$conditionField";
        }
    }
    $SQL .= ")\n";

    //ask parent for "grandParentSQL"
    $SQL .= $this->parentReport->buildParentSQL($parentModuleID.'_p');

    return $SQL;
}


/**
 * returns the subreport's data (including subreports) as HTML
 */
function renderHTML($recordID, &$rootReport)
{
    $content = '';

    foreach($rootReport->dataSets[$this->hierarchyID] as $row){
        if($row[$this->localKey] == $recordID){
            //$object->headerField
            $content .= '<div class="subreport">';
            if(!empty($this->headerField)){
                $content .= '<div class="reporttitle">['.$this->singularRecordName.']</div>';
                //$content .= '<h2>'.$this->singularRecordName .': '. $row[$this->headerField].'</h2>';
                $content .= '<h2>'.$row[$this->headerField].'</h2>';
            } else {
                $content .= '<h2>'.$this->singularRecordName.'</h2>';
            }

            $content .= '<table>';
            foreach($this->fields as $fieldName => $field){
                if($field->isVisible()){
                    $content .= '<tr>';
                    $content .= '<td>'.$field->phrase.':</td><td>'.$field->simpleRender($row).'</td>';
                    $content .= '</tr>';
                }
            }
            $content .= '</table>';
            if(count($this->subReports)>0){
                foreach($this->subReports as $subReport){
                    $subParentID = $row[$subReport->parentKey];
                    $content .= $subReport->renderHTML($subParentID, $rootReport);
                }
            }
            $content .= '</div>';
        }
    }

    return $content;
}


/**
 *  Returns the subreport's data as XML
 */
function renderXML($recordID, &$rootReport)
{
    $content = '';

    foreach($rootReport->dataSets[$this->hierarchyID] as $row){
        $content .= '<Records moduleID="'.$this->moduleID.'">';
        if($row[$this->localKey] == $recordID){
            $globalIDInsert = '';
            if(!empty($row['_GlobalID'])){
                $globalIDInsert = "globalID=\"{$row['_GlobalID']}\"";
            }
            $content .= "<Record $globalIDInsert>";
            foreach($this->fields as $fieldName => $field){
                $content .= "<RecordValue fieldName=\"$fieldName\" value=\"".htmlspecialchars($field->viewRender($row))."\" />";
            }

            if(count($this->subReports)>0){
                foreach($this->subReports as $subReport){
                    $subParentID = $row[$subReport->parentKey];
                    $content .= $subReport->renderXML($subParentID, $rootReport);
                }
            }
            $content .= '</Record>';
        }
        $content .= '</Records>';
    }

    return $content;
}

} //end class SubReport



/**
 * Custom field class for report-viewable fields
 */
class ReportField extends ViewField
{

var $moduleFieldName;
var $summarize; //groupby, sum, etc...
var $invisible = false; //whether to display it in HTML reports
var $transformation;

/**
 *  Factory method
 */
function Factory(&$element, $moduleID)
{
    return new ReportField($element, $moduleID);
}


/**
 *  Constructor
 */
function ReportField(&$element, $moduleID)
{
    $this->name = $element->name;
    $this->moduleID = $moduleID;


    if(count($element->c) > 0){
        foreach($element->c as $sub_element){
            if('Transformation' == $sub_element->type){
                $this->transformation = $sub_element->createObject($this->moduleID);
            }
        }

        $this->phrase = $element->attributes['phrase'];
    } else {

        if(!empty($element->attributes['moduleFieldName'])){
            $this->moduleFieldName = $element->attributes['moduleFieldName'];
        } else {
            $this->moduleFieldName = $this->name;
        }

        if(!empty($element->attributes['phrase'])){
            $this->phrase = $element->attributes['phrase'];
        } else {
            $mf = GetModuleField($moduleID, $this->moduleFieldName);
            $this->phrase = ShortPhrase($mf->phrase);
        }
    }

    if(!empty($element->attributes['summarize'])){
        $this->summarize = $element->attributes['summarize'];
    }
    if(!empty($element->attributes['invisible'])){
        if(
            true === $element->attributes['invisible']
            || 'yes' == strtolower($element->attributes['invisible'])
        ){
            $this->invisible = $element->attributes['invisible'];
        }
    }

} //end ReportField() - constructor


/**
 *  Returns the field's SQL SELECT expression, using the corresponding ModuleField
 */
function makeSelectDef($moduleID)
{
    if(empty($this->transformation)){
        $mf = GetModuleField($this->moduleID, $this->moduleFieldName);
        $select = $mf->makeSelectDef($moduleID, false);
    } else {
        $select = $this->transformation->makeSelectDef($moduleID);
    }

    if(!empty($this->summarize)){
        switch($this->summarize){
        case 'groupby':
            //handled separately
            break;
        case 'sum':
            $select = "SUM($select)";
            break;
        default:
            trigger_error("Unknown summarization function '{$this->summarize}'.");
            break;
        }
    }

    $select = $select . ' AS ' . $this->name;
    return $select;
}


/**
 *  Returns the field's SQL join expression, using the corresponding ModuleField
 */
function makeJoinDef($moduleID)
{
    if(empty($this->transformation)){
        $mf = GetModuleField($this->moduleID, $this->moduleFieldName);
        return $mf->makeJoinDef($moduleID);
    } else {
        return $this->transformation->makeJoinDef($moduleID);
    }
}

/**
 *  Returns whether the field should be displayed (in HTML) or not
 */
function isVisible()
{
    return !$this->invisible;
}

} //end ReportField



/**
 * Class for formatting report pages (a way of mapping DB fields to PDF page fields)
 */
class ReportPage
{
var $rootReport;
var $pageFields = array(); //page fields not attached to a line
var $pageLines = array();      //lines contain page fields
var $pageFieldGroups = array();
var $pageSummaryFields = array();
var $pageBreakFields = array();
var $filename;
var $subReportID; //to be retired
var $subReportRefs = array();
var $offsets = array();
var $subReportLocalKey;//to be retired
var $subReportParentKey;//to be retired


/**
 *  Factory method
 */
function &Factory(&$element, $moduleID, &$callerRef)
{
    $page =& new ReportPage($element, $moduleID, &$callerRef);
    return $page;
}


/**
 *  Constructor
 */
function ReportPage(&$element, $moduleID, &$callerRef)
{
    $this->rootReport =& $callerRef;
    $this->filename = $element->attributes['filename'];

    foreach($element->c as $sub_element){
        switch($sub_element->type){
        case 'PageField':
            $object = $sub_element->createObject($moduleID, null);
            $this->pageFields[$sub_element->attributes['name']] = $object;
            if($object->pageBreak){
                $this->pageBreakFields[] = $object;
            }
            break;
        case 'PageSummaryField':
            $this->pageSummaryFields[$sub_element->attributes['name']] = $sub_element->createObject($moduleID, null);
            break;
        case 'PageMetaField':
            $this->pageMetaFields[$sub_element->attributes['name']] = $sub_element->attributes['type'];
            break;
        case 'PageLines':

            if(count($this->rootReport->subReports) > 0){
                $subReportID = $sub_element->attributes['subReportID'];
                $subReport = $this->rootReport->subReports[$subReportID];
                $hierKey = $moduleID .'_'. $subReportID;
                $this->subReportRefs[$hierKey] = array(
                    'parentKey' => $subReport->parentKey, 
                    'localKey' => $subReport->localKey
                );
            } else {
                $hierKey = $moduleID;
            }

            foreach($sub_element->c as $line_element){
                foreach($line_element->c as $field_element){
                    $this->pageLines[$hierKey][$line_element->attributes['id']][] = $field_element->createObject($moduleID, null);
                }
            }
            break;
        case 'PageFieldGroup':
            $subReport = $this->rootReport->subReports[$sub_element->attributes['subReportID']];
            $sub_element->attributes['localKey'] = $subReport->localKey;
            $sub_element->attributes['parentKey'] = $subReport->parentKey;
            $fieldGroup = $sub_element->createObject($moduleID, null);
            $this->pageFieldGroups[] = $fieldGroup;
            break;
        default:
            break;
        }
    }
}


/**
 *  Returns the supplied data sets transformed into paged results useful for the PDF forms
 */
function generateData(&$datasets)
{

    $dataPages = array();
    $continue = true;
    $moduleID = $this->rootReport->moduleID;

    $this->offsets['_main_'] = 0;

    if(count($this->subReportRefs) > 0){
        foreach($this->subReportRefs as $subReportID => $subReportRef){
            $this->offsets[$subReportID] = 0;
        }
    }

    while($continue) {
        $result = $this->generateDataPage(&$datasets);

        list($continue, $dataPage) = $result;

        if($continue){
            $dataPages[] = $dataPage;
        } else {
            break;
        }
    }

    if(count($this->pageMetaFields) > 0){
        foreach($this->pageMetaFields as $pageMetaField => $type){
            switch($type){
            case 'current_page_nbr':
                foreach($dataPages as $dataPageID => $dataPage){
                    $dataPage[$pageMetaField] = $dataPageID+1; //start page number at 1
                    $dataPages[$dataPageID] = $dataPage;
                }
                break;
            case 'total_nbr_pages':
                $pageCount = count($dataPages);
                foreach($dataPages as $dataPageID => $dataPage){
                    $dataPage[$pageMetaField] = $pageCount;
                    $dataPages[$dataPageID] = $dataPage;
                }
                break;
            default:

                break;
            }
        }
    }

    return $dataPages;

} //end generateData()



/**
 *  returns one page of data mapped to the page fields (fields in the PDF document)
 */
function generateDataPage(&$datasets)
{

    $pageData = array();
    $moduleID = $this->rootReport->moduleID;
    $rootOffset =& $this->offsets['_main_'];

    if(count($datasets[$moduleID]) <= $rootOffset){
        return array(false, array());
    }

    $breakPage = false;
    $incrementRootOffset = true;

    //populates the direct page fields
    foreach($this->pageFields as $pageField){
        $pageData[$pageField->name] = $pageField->getValue($datasets[$moduleID][$rootOffset]);
    }

    if(count($this->subReportRefs) > 0){
        $hasSubReport = true; //so pageLines are for the subreport data
    } else {
        $hasSubReport = false; //so pageLines are for the main data
    }

    //PageLines is a way to map resultset rows against multiple fields in a page
    if(count($this->pageLines) > 0){
        if($hasSubReport){
            $parentDataRow = $datasets[$moduleID][$rootOffset];

            foreach($this->pageLines as $hierKey => $pageLines){
                $subDataset = $datasets[$hierKey];
                $parentKeyName = $this->subReportRefs[$hierKey]['parentKey'];
                $parentKeyValue = $parentDataRow[$parentKeyName];
                $subOffset =& $this->offsets[$hierKey];

                $pageLineIDs = array_keys($pageLines);
                $lastPageLineIx = end($pageLineIDs);
                $pageLineIx = reset($pageLineIDs);
                $onFirstRow = true;

                //get lines in the $subDataset that match the $parentKeyValue, starting with the correct offset
                while(
                    isset($subDataset[$subOffset]) &&
                    ($subDataset[$subOffset][$this->subReportRefs[$hierKey]['localKey']] == $parentKeyValue)
                ){
                    if($onFirstRow){
                        $onFirstRow = false;
                        $this->setPageBreakFields($subDataset[$subOffset]);
                    } else {
                        if($this->checkPageBreak($subDataset[$subOffset])){
                            break;
                        }
                }

                    foreach($pageLines[$pageLineIx] as $pageFieldIx => $pageField){
                        //pageField returns an array if the dataSet value is longer than allowed
                        $fieldValue = $pageField->getValue($subDataset[$subOffset]);
                        if(is_array($fieldValue)){

                            //populate each subsequent field in same column with a line of $fieldValue:
                            foreach($fieldValue as $fieldValueLine){
                                $pageField = $pageLines[$pageLineIx][$pageFieldIx];
                                $pageFieldName = $pageField->name;
                                $pageData[$pageFieldName] = $fieldValueLine;
                                $pageLineIx = next($pageLineIDs);

                                if(!$pageLineIx){
                                    //mark that this subreport needs to trigger a page break
                                    $breakPage = true;
                                    $incrementRootOffset = false;
                                }
                            }
                        } else {
                            $pageData[$pageField->name] = $fieldValue;
                        } 
                    }

                    $pageLineIx = next($pageLineIDs);
                    $subOffset++;
                    if(false === $pageLineIx){
                        //mark that this subreport needs to trigger a page break
                        $breakPage = true;
                        $incrementRootOffset = false;
                        break; //no need to keep looping, next page should continue from here
                    }
                }
                unset($subOffset); //unlinks reference to $this->offsets[$hierKey]
            }
        } else {
            //same thing for the case where pageLines belong to the main module (or rewrite to combine with above?)
            $pageLines =& $this->pageLines[$moduleID];
            $dataset = $datasets[$moduleID];
            $pageLineIDs = array_keys($pageLines);
            $lastPageLineIx = end($pageLineIDs);
            $pageLineIx = reset($pageLineIDs);
            $onFirstRow = true;

            //get lines in the $subDataset that match the $parentKeyValue, starting with the correct offset
            while(isset($dataset[$rootOffset])){
                if($onFirstRow){
                    $onFirstRow = false;
                    $this->setPageBreakFields($dataset[$rootOffset]);
                } else {
                    if($this->checkPageBreak($dataset[$rootOffset])){
                        break;
                    }
                }
                foreach($pageLines[$pageLineIx] as $pageFieldIx => $pageField){
                    //pageField returns an array if the dataSet value is longer than allowed
                    $fieldValue = $pageField->getValue($dataset[$rootOffset]);
                    if(is_array($fieldValue)){

                        //populate each subsequent field in same column with a line of $fieldValue:
                        foreach($fieldValue as $fieldValueLine){
                            $pageField = $pageLines[$pageLineIx][$pageFieldIx];
                            $pageFieldName = $pageField->name;
                            $pageData[$pageFieldName] = $fieldValueLine;
                            $pageLineIx = next($pageLineIDs);

                            if(!$pageLineIx){
                                //mark that this needs to trigger a page break

                            }
                        }
                    } else {
                        $pageData[$pageField->name] = $fieldValue;
                    }
                }

                $pageLineIx = next($pageLineIDs);
                $rootOffset++;
                if(false === $pageLineIx){
                    break; //no need to keep looping, next page should continue from here
                }

            }
            $incrementRootOffset = false;
        }
    } else {

        //PageFieldGroups only supported when there are no PageLines
        if(count($this->pageFieldGroups) > 0){
            foreach($this->pageFieldGroups as $pageFieldGroup){
                $pageData = array_merge($pageData, $pageFieldGroup->populatePage($datasets, $rootOffset));
            }
        }
    }

    if(count($this->pageSummaryFields) > 0){
        foreach($this->pageSummaryFields as $pageSummaryField){
            $pageData[$pageSummaryField->name] = $pageSummaryField->getValue($pageData);
        }
    }

    if($incrementRootOffset){
        $rootOffset++;
    }

    return array(true, $pageData);
} //end generateDataPage


/**
 *  Initializes the pageBreakFields
 */
function setPageBreakFields(&$dataRow)
{
    if(count($this->pageBreakFields) > 0){
        foreach($this->pageBreakFields as $pageBreakFieldID => $pageBreakField){
            $pageBreakField->startValue = $pageBreakField->getValue($dataRow);
            $this->pageBreakFields[$pageBreakFieldID] = $pageBreakField;
        }
    }
} //end setPageBreakFields


/**
 *  Returns true if any set pageBreakFields were changed
 */
function checkPageBreak(&$dataRow)
{
    if(count($this->pageBreakFields) > 0){
        foreach($this->pageBreakFields as $pageBreakField){
            if($pageBreakField->checkPageBreak($dataRow)){
                return true;
            }
        }
        return false;
    } else {
        return false;
    }
} //end checkPageBreak

} //end class ReportPage



class PageFieldGroup
{
var $pageFields = array();
var $subReportID;
var $parentModuleID;
var $localKey;
var $parentKey;

function &Factory(&$element, $moduleID)
{
    $object =& new PageFieldGroup();
    $object->subReportID = $element->attributes['subReportID'];
    $object->parentModuleID = $moduleID;
    $object->localKey = $element->attributes['localKey'];
    $object->parentKey = $element->attributes['parentKey'];

    if(count($element->c) > 0){
    foreach($element->c as $sub_element){
        $object->pageFields[$sub_element->attributes['name']] = $sub_element->createObject($moduleID, null);
    }
    } else {
        trigger_error("PageFieldGroup must have some fields", E_USER_ERROR);
    }

    return $object;
}

function populatePage(&$datasets, $offset)
{
    $groupData = array();

    $hierKey = $this->parentModuleID .'_'. $this->subReportID;
    $parentReportData = $datasets[$this->parentModuleID];
    $subReportData = $datasets[$hierKey];

    $parentKeyValue = $parentReportData[$offset][$this->parentKey];

    //find the correct subReportData row:
    $found = false;
    foreach($subReportData as $subReportDataRow){
        if($subReportDataRow[$this->localKey] == $parentKeyValue){
            $found = true;
            break;
        }
    }

    if($found){
        //print debug_r($subReportDataRow, "subReportDataRow");

        //populate each field in group
        foreach($this->pageFields as $pageField){
            $groupData[$pageField->name] = $pageField->getValue($subReportDataRow);
        }
    }

    //print debug_r($groupData, "groupData");

    return $groupData;
}
}




class PageField
{
var $name;
var $reportField;
var $maxLength;
var $overflowAction;
var $conditionValue;
var $trueResult;
var $format;
var $pageBreak;
var $replaceEmptyValue;

function &Factory(&$element, $moduleID)
{
    $object =& new PageField();
    $object->name = $element->attributes['name'];
    $object->reportField = $element->attributes['reportField'];
    $object->maxLength = $element->attributes['maxLength'];
    $object->overflowAction = $element->attributes['overflowAction'];
    $object->format = $element->attributes['format'];
    $object->replaceEmptyValue = $element->attributes['replaceEmptyValue'];

    if(isset($element->attributes['pageBreak']) && 'yes' == strtolower($element->attributes['pageBreak'])){
        $object->pageBreak = true;
    } else {
        $object->pageBreak = false;
    }

    if(isset($element->attributes['conditionValue'])){
        $object->conditionValue = $element->attributes['conditionValue'];
        $object->trueResult = $element->attributes['trueResult'];
    }
    return $object;
}

function getValue(&$data)
{
    $rawValue = $data[$this->reportField];

    global $report_render_mode;
    if('pdf' == $report_render_mode){
        $rawValue = strip_tags($rawValue);
    }

    if(isset($this->replaceEmptyValue) && ('' == $rawValue)){
        $rawValue = $this->replaceEmptyValue;
    }

    if(!empty($this->format)){
        switch($this->format){
        case 'monthday':
            if(!empty($rawValue)){
                $rawValue = date('m/d', strtotime($rawValue));
            }
            break;
        case 'year_2':
            if(!empty($rawValue)){
                $rawValue = date('y', strtotime($rawValue));
            }
            break;
        default:
            //otherwise, support all PHP date formats
            if(!empty($rawValue)){
                $rawValue = date($this->format, strtotime($rawValue));
            }
            break;
        }
    }

    if('' == $this->conditionValue){
        if(empty($this->maxLength)){
            return $rawValue;
        } else {
            if(strlen($rawValue) > $this->maxLength){
                if('nextline' == $this->overflowAction){
                    $wrapped = wordwrap($rawValue, $this->maxLength, '-|-');
                    return explode('-|-', $wrapped);
                } else {
                    return substr($rawValue, 0, $this->maxLength);
                }
            } else {
                return $rawValue;
            }
        }
    } else {
        if($rawValue == $this->conditionValue){
            //return iconv('UTF-8', 'ISO-8859-1', 'Yes'); //'YES';//$this->trueResult;
            return $this->trueResult;
        } else {
            return 'Off';
        }
    }
    return '';
}

function checkPageBreak(&$data)
{
    $value = $this->getValue(&$data);
    if($this->startValue == $value){
        return false;
    } else {
        return true;
    }
}

}


class PageSummaryField
{
var $name;
var $mode;
var $refs = array();
var $matchValue;

function Factory(&$element, $moduleID)
{
    $object =& new PageSummaryField();
    $object->name = $element->attributes['name'];
    $object->mode = $element->attributes['mode'];
    $object->matchValue = $element->attributes['matchValue'];

    if(count($element->c) > 0){
        foreach($element->c as $sub_element){
            $object->refs[] = $sub_element->name;
        }
    }

    return $object;
}

function getValue($pageData)
{
    //print debug_r($data);
    if('countValue' == $this->mode){
        $matches = 0;
        foreach($this->refs as $ref){
            if($this->matchValue == $pageData[$ref]){
                $matches++;
            }
        }
        return $matches;
    } elseif('numeric' == $this->mode) {
        $value = 0;
        foreach($this->refs as $ref){
            if(is_numeric($pageData[$ref])){
                $value += $pageData[$ref];
            }
        }
        return $value;
    }

    return 0;
}
}



class Transformation
{
var $parameters = array();
var $functionName;

function Factory(&$element, $moduleID)
{
    $object =& new Transformation();
    $object->functionName = $element->attributes['function'];

    if(count($element->c) > 0){
        foreach($element->c as $sub_element){
            $object->parameters[] = $sub_element->createObject($moduleID);
        }
    }

    return $object;
}

function makeSelectDef($moduleID)
{
    switch($this->functionName){
    case 'year':
        $expression = 'YEAR(%1$s)';
        break;
    case 'year_firstday':
        $expression = 'DATE_FORMAT(%1$s,\'%Y-01-01\')';
        break;
    case 'equals':
        $expression = 'IF(%1$s = %2$s, 1, 0)';
        break;
    default:
        trigger_error("Unknown transformation function named '{$this->functionName}'", E_USER_ERROR);
        break;
    }

    if(count($this->parameters) == $this->_numberExpectedParameters()){
        $param_count = 1;
        foreach($this->parameters as $parameter){
            $expression = str_replace("%$param_count\$s", $parameter->makeSelectDef($moduleID), $expression);
            $param_count++;
        }
        return $expression;

    } else {
        trigger_error("Wrong number of parameters supplied for transformation function '{$this->functionName}'", E_USER_ERROR);
    }
}

function makeJoinDef($moduleID)
{
    if(count($this->parameters) == $this->_numberExpectedParameters()){
        $paramJoins = array();
        foreach($this->parameters as $parameter){
            $paramJoins = array_merge($paramJoins, $parameter->makeJoinDef($moduleID));
        }
        return $paramJoins;

    } else {
        trigger_error("Wrong number of parameters supplied for transformation function '{$this->functionName}'", E_USER_ERROR);
    }
}

function _numberExpectedParameters()
{
    switch($this->functionName){
    case 'year':
    case 'year_firstday':
        $nExpected = 1;
        break;
    case 'equals':
        $nExpected = 2;
        break;
    default:
        trigger_error("Unknown transformation function named '{$this->functionName}'", E_USER_ERROR);
        break;
    }
    return $nExpected;
}

}



class ModuleFieldRef
{
var $moduleID;
var $name;

function Factory(&$element, $moduleID)
{
    $object =& new ModuleFieldRef();
    $object->moduleID = $moduleID;
    $object->name = $element->attributes['name'];

    return $object;
}

function makeSelectDef($moduleID)
{
    $mf = GetModuleField($this->moduleID, $this->name);
    return $mf->makeSelectDef($moduleID, false);
}

function makeJoinDef($moduleID)
{
    $mf = GetModuleField($this->moduleID, $this->name);
    return $mf->makeJoinDef($moduleID);
}

}

class StaticValue
{
var $value;

function Factory(&$element, $moduleID)
{
    $object =& new StaticValue();
    $object->value = $element->attributes['value'];

    return $object;
}

function makeSelectDef($moduleID)
{
    return '\''.$this->value.'\'';
}

function makeJoinDef($moduleID)
{
    return array();
}
}



?>