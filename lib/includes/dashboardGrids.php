<?php
/**
 *  Supports displaying the dashboard grids in the dashboard screen.
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
 * @version        SVN: $Revision: 501 $
 * @last-modified  SVN: $Date: 2007-02-17 01:00:27 -0800 (Sat, 17 Feb 2007) $
 */


include_once(CLASSES_PATH . '/grids.php');


class DashboardGrid extends ViewGrid {

function prepareCountSQL()
{
    global $User;
    $countSQL = parent::prepareCountSQL();
    $countSQL = str_replace('/**UserID**/', $User->PersonID, $countSQL);

    return $countSQL;
}

function prepareListSQL()
{
    global $User;
    $listSQL = parent::prepareListSQL();
    $listSQL = str_replace('/**UserID**/', $User->PersonID, $listSQL);
    return $listSQL;
}

} //end class DashboadGrid



class ActionsDashboardGrid extends DashboardGrid {
function ActionsDashboardGrid (){
    //all properties are pre-set here, so no XML needed
    $this->moduleID = 'act';
    $this->phrase = 'Action List';
    $this->formatOptions['suppressTitle'] = true;
    $this->formatOptions['suppressPaging'] = true;
    $this->orderBy = 'DueDate';

    $module = GetModule($this->moduleID);

    //easier to define the fields in "element" format
    $field_elements = array(
        0 => new Element(
            'ActionID', 
            'InvisibleGridField',
            array(
                'name' => 'ActionID'
            )
        ),
        1 => new Element(
            'Title', 
            'ViewGridField', 
            array(
                'name' => 'Title'/*,
                'link' => 'RelatedRecordLink'*/
            )
        ),
        2 => new Element(
            'DueDate',
            'ViewGridField',
            array(
                'name' => 'DueDate',
                'formatField' => 'DueDateFormat'
            )
        ),
        3 => new Element(
            'ActionStatus', 
            'ViewGridField', 
            array(
                'name' => 'ActionStatus'
            )
        )

    );

    foreach($field_elements as $field_element){
        $type = str_replace('Grid', '', $field_element->type);

        $field_object = $field_element->createObject($this->moduleID, $field_element->type);

        if(empty($sub_element->attributes['phrase'])){
            $field_object->phrase = $module->ModuleFields[$field_object->name]->phrase;
        }
        $this->AddField($field_object);
    }

    $this->setUpFieldTypes($module->ModuleFields);

    $this->conditions['PersonAccountableID'] = '/**UserID**/';
    $this->conditions['ActionStatusID'] = '1|2';

    $this->listSQL = $module->generateListSQL($this);
    $this->countSQL = $module->generateListCountSQL($this);

} //end constructor ActionsDashboardGrid

/*function appendSQLConditions($conditions){
    parent::appendSQLConditions($conditions);
    $this->ParentRowSQL .= "\n AND act.ActionStatusID IN (1,2)";
}*/

function render($page, $qsArgs)
{
    $content =& parent::render($page, $qsArgs);
    $content .= gettext("\"Complete\" or \"Deferred\" actions are not shown.");

    return $content;
}

} //end class ActionsDashboardGrid


class AccountabilityDashboardGrid extends DashboardGrid {
function AccountabilityDashboardGrid (){
    //all properties are pre-set here, so no XML needed
    $this->moduleID = 'acc';
    $this->phrase = 'Accountabilities';
    $this->formatOptions['suppressTitle'] = true;
    $this->formatOptions['suppressRecordIcons'] = true;
    $this->formatOptions['suppressPaging'] = true;

    $module = GetModule($this->moduleID);

    //easier to define the fields in "element" format
    $field_elements = array(
        0 => new Element(
            'AccountabilityDescriptorID',
            'InvisibleGridField',
            array(
                'name' => 'AccountabilityDescriptorID'
            )
        ),
        1 => new Element(
            'AccountabilityDescriptor',
            'ViewGridField',
            array(
                'name' => 'AccountabilityDescriptor'/*,
                'link' => 'RelatedRecordLink'*/
            )
        ),
        2 => new Element(
            'Total', 
            'ViewGridField',
            array(
                'name' => 'Total'
            )
        ),
        3 => new Element(
            'New',
            'ViewGridField',
            array(
                'name' => 'New',
                'phrase' => 'New'
            )
        )

    );

    foreach($field_elements as $field_element){
        $type = str_replace('Grid', '', $field_element->type);

        $field_object = $field_element->createObject($this->moduleID, $field_element->type);
        if(empty($sub_element->attributes['phrase'])){
            $field_object->phrase = $module->ModuleFields[$field_object->name]->phrase;
        }
        $this->AddField($field_object);
    }


    //$this->listSQL = $module->generateListSQL($this);
    $this->listSQL = "SELECT
    acc.AccountabilityDescriptorID,
    cod1.Description AS AccountabilityDescriptor,
    COUNT(AccountabilityID) AS Total,
    SUM(CASE WHEN acc._ModDate > '/**UserPreviousVisit**/' THEN 1 ELSE 0 END) AS `New`
FROM acc
    LEFT OUTER JOIN cod AS cod1 ON acc.AccountabilityDescriptorID = cod1.CodeID AND cod1.CodeTypeID = 260
WHERE
    acc._Deleted = 0
    AND acc.PersonAccountableID = '/**UserID**/'
GROUP BY AccountabilityDescriptor
HAVING `New` > 0
ORDER BY `New` DESC, AccountabilityDescriptor";

    $this->countSQL = "SELECT
    count(*)
FROM acc
WHERE
    acc._Deleted = 0
    AND acc._ModDate > '/**UserPreviousVisit**/'
    AND acc.PersonAccountableID = '/**UserID**/'";

    //$this->appendSQLConditions();

} //end constructor AccountabilityDashboardGrid 



function prepareListSQL(){
    global $User;
    $previousVisit = $User->previousVisit;
    if(empty($previousVisit)){
        $previousVisit = '1/1/2000';
    }
    //$listSQL = parent::prepareListSQL();
    $listSQL = $this->listSQL;
    $listSQL = str_replace('/**UserPreviousVisit**/', $previousVisit, $listSQL);
    $listSQL = str_replace('/**UserID**/', $User->PersonID, $listSQL);
//print debug_r($listSQL);
    return $listSQL;
}

function prepareCountSQL()
{
    global $User;
    $previousVisit = $User->previousVisit;
    if(empty($previousVisit)){
        $previousVisit = '1/1/2000';
    }
    $countSQL = $this->countSQL;

    if(!empty($this->ParentRowSQL)){
        if(FALSE === strpos($countSQL, 'WHERE')){
            $countSQL .= ' WHERE ';
        } else {
            $countSQL .= ' AND ';
        }
        $countSQL .= $this->ParentRowSQL;
    }

    //no need to filter out records where user has no permission (users must be able to see their own)
    //$countSQL .= $User->getListFilterSQL($this->moduleID, true);
    $countSQL = str_replace('/**UserID**/', $User->PersonID, $countSQL);
    $countSQL = str_replace('/**UserPreviousVisit**/', $previousVisit, $countSQL);
    return $countSQL;
}

function render($page, $qsArgs)
{
    $count = $this->getRecordCount();
    if(intval($count) == 0){
        $content = gettext("No new accountabilities have been assigned to you since your last login.");
    } else {
        $content =& parent::render($page, $qsArgs);
        $content .= gettext("\"New\" accountabilities were assigned today,<br /> or since your last login a previous day.");
    }
    return $content;
}

} //end class AccountabilityDashboardGrid 


class ShortcutDashboardGrid extends DashboardGrid {

function ShortcutDashboardGrid()
{
//all properties are pre-set here, so no XML needed
    $this->moduleID = 'usrds';
    $this->phrase = 'Shortcuts';
    $this->orderBy = 'Type, Title';
    $this->formatOptions['suppressTitle'] = true;
    $this->formatOptions['suppressRecordIcons'] = true;
    $this->formatOptions['suppressPaging'] = true;

    $module = GetModule($this->moduleID);

    //easier to define the fields in "element" format
    $field_elements = array(
        0 => new Element(
            'RecordID',
            'InvisibleGridField',
            array(
                'name' => 'RecordID'
            )
        ),
        1 => new Element(
            'Type',
            'ViewGridField',
            array(
                'name' => 'Type'
            )
        ),
        2 => new Element(
            'Title',
            'ViewGridField',
            array(
                'name' => 'Title',
                'link' => 'InternalLink'
            )
        )
    );

    foreach($field_elements as $field_element){
        $type = str_replace('Grid', '', $field_element->type);

        $field_object = $field_element->createObject($this->moduleID, $field_element->type);

        if(empty($sub_element->attributes['phrase'])){
            $field_object->phrase = $module->ModuleFields[$field_object->name]->phrase;
        }
        $this->AddField($field_object);
    }

    $this->conditions['PersonID'] = '/**UserID**/';
    $this->listSQL = $module->generateListSQL($this);
    $this->countSQL = $module->generateListCountSQL($this);
}




} //end class ShortcutDashboardGrid

?>