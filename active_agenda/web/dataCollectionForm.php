<?php
/**
 * Displays an HTML data collection form
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


//main business here
$filename = GENERATED_PATH . "/{$ModuleID}/{$ModuleID}_DataCollection.gen";

//check for cached page for this module
if (!file_exists($filename)){
    trigger_error("Could not find file '$filename'.", E_USER_ERROR);
}

$moduleInfo = GetModuleInfo($ModuleID);
$moduleName = $moduleInfo->getProperty('moduleName');

include_once CLASSES_PATH . '/components.php';

include_once $filename; //provides $dataCollection

//$title = sprintf(gettext("Data Collection form for the %s module."), $moduleName);
$title = $moduleName . ' Data Collection Form';

require_once PEAR_PATH . '/File/PDF.php';

//this is how to get the headers and footers in...
class DataFormPDF extends File_PDF
{
function header()
{
    global $title;
    $this->setFont('Arial', '', 24);
    $this->cell(0, 36, $title);
    $this->newLine();
}


function footer()
{
    global $theme;
    $this->image($theme .'/img/logo-pdfinclude-noalpha.png', 24,-60,126);

    // Go to 36 pt from bottom
    $this->setY(-48);
    // Select Arial italic 8
    $this->setFont('Arial', 'I', 8);
    // Print centered
    $this->cell(0, 10, 'www.ActiveAgenda.net', 0, 0, 'C', 0, 'http://www.activeagenda.net');
    $this->newLine();
    $this->cell(0, 10, '"Controlling Loss, Not Minds, Methods, or Markets"', 0, 0, 'C');
    $this->newLine();
    //page number
    $this->cell(0, 10, 'Page ' . $this->getPageNo() . ' of {nb}', 0, 0, 'R');
}

function checkPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->getY()+$h>$this->_page_break_trigger){
        $this->addPage();
    }
}

//estimates how many lines a multiCell field would generate (not exact but hopefully good enough)
function getNumberOfLines($width, $text)
{
    if(strlen($text) > 0){
        $textWidth = $this->getStringWidth($text);
        $wrapLines = ceil($textWidth / $width); //number of lines because of line wrapping (approx)
        $hardLines = substr_count($text, "\n");
        $nLines = $wrapLines + $hardLines;
    } else {
        $nLines = 1; //even if the text is empty, there will be a line (ceil function above would return 0)
    }

    return $nLines;
}

function checkFieldHeight($field, $labelWidth, $minLabelWidth, $descLineHeight, $fillLineHeight, $description, $fill)
{
    $height = 0;

    $descriptionWidth = $this->fw - $labelWidth - $this->_left_margin - $this->_right_margin;
    $fillWidth = $this->fw - $minLabelWidth - $this->_left_margin - $this->_right_margin;

    //number of lines for description
    $this->setFont('Arial', 'I', 10);
    $nLines = $this->getNumberOfLines($descriptionWidth, $description);
    $height = $height + $nLines * $descLineHeight;

    //number of lines for fill-in area
    $this->setFont('Arial', '', 10);
    $nLines = $this->getNumberOfLines($fillWidth, $fill);
    $height = $height + $nLines * $fillLineHeight;

    return $height;
}

function printFields($fields)
{
    foreach($fields as $field){
        $label = ShortPhrase(gettext($field->phrase));
        $this->setFont('Arial', 'B', 10);
        $labelWidth = $this->getStringWidth($label);
        $minLabelWidth = 108;
        if($labelWidth < $minLabelWidth){
            $labelWidth = $minLabelWidth;
        }

        $descLineHeight = 12;
        $fillinLineHeight = 24;
        $description = LongPhrase(gettext($field->phrase));
        $fill = $field->dataCollectionRender($values);

        //get height of next field
        $fieldHeight = $this->checkFieldHeight($field, $labelWidth, $minLabelWidth, $descLineHeight, $fillinLineHeight, $description, $fill);

        //adds a page break before the field if it would reach out of bounds
        $this->checkPageBreak($fieldHeight);

        $this->setFont('Arial', 'B', 10);
        $this->cell($labelWidth, 12, $label);
        $this->cell(12, 12, ''); //padding cell

        $this->setFont('Arial', 'I', 10);
        $this->multiCell(0, $descLineHeight, $description);

        $this->setFont('Arial', '', 10);
        $outline = strlen(trim($fill)) == 0; //outline if no choices provided
        if(strlen($fill) == 0){
            $outline = 'B';
        }
//$fill = $fieldHeight . ' ' . $descLineHeight . ' ' . $fillinLineHeight . ' ' .  $fill.
        $this->cell($minLabelWidth + 12, 12, ''); //padding cell
        $this->multiCell(0, $fillinLineHeight, $fill, $outline);
        $this->newLine(12);
    }
}

}

$pdf = &DataFormPDF::factory(array('orientation' => 'P','unit' => 'pt','format' => 'Letter'), 'DataFormPDF');
$pdf->aliasNbPages();

$pdf->open();
$pdf->setMargins(24, 24);

$pdf->addPage('P');

$onFirstPage = true;

foreach($dataCollection as $screenName => $screen){
    if(!$onFirstPage){
        $pdf->addPage('P');
    }
    $pdf->setFont('Arial', 'B', 16);
    $pdf->cell(0, 24, ShortPhrase(gettext($screen['phrase'])) . ' Screen', 'B');
    $pdf->newLine(36);

    foreach($screen as $type => $content){
        if('fields' == $type){
            $pdf->printFields($content);
        } elseif('sub' == $type){
            foreach($content as $subModuleID => $sub){
                $pdf->setFont('Arial', '', 9);
                $msg = gettext("These are the fields of the '%1\$s' grid. Please print as many sheets of this page as necessary, or see the %2\$s module for a complete data entry form.");

                $pdf->multiCell(0, 9, sprintf($msg, $sub[0], $screen['moduleName']));
                $pdf->newLine(18);
                $pdf->printFields($sub[1]);
            }
        }
    }
    $onFirstPage = false;
}

$pdf->close();

if($User->browserInfo['is_IE']){
    $inline = true;
} else {
    $inline = false;
}
$pdf->output($ModuleID.'_dataCollection.pdf', $inline);

?>