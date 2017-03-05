<?php
/**
 *  Class definition for the PermissionGrid
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
 * @version        SVN: $Revision: 518 $
 * @last-modified  SVN: $Date: 2007-02-20 14:32:27 -0800 (Tue, 20 Feb 2007) $
 */


class PermissionGrid extends Grid
{
var $primaryListField;
var $insertSQL;
var $updateSQL;
var $deleteSQL;
var $valueListSQL; //simplified list statement to get just the checked values

function &Factory($element, $moduleID)
{
    $module = GetModule($moduleID); //local module
    $subModuleID = $element->attributes['moduleID'];

    //when building GlobalEditGrids, there's no SubModule 
    if(1 == $element->attributes['isGlobalEditGrid']){
        $subModule = GetModule($subModuleID);
    } else {
        $subModule = $module->SubModules[$subModuleID];

        if(empty($subModule)){
            die("CodeCheckGrid-Factory: could not find a submodule that matches moduleID  '$subModuleID'");
        }
    }

    //check for fields in the element: if there are none, we will import from the Exports section of the sub-module
    if(count($element->c) == 0){
        $exports_element = $subModule->_map->selectFirstElement('Exports');
        if(empty($exports_element)){
            die("Can't find an Exports section in the $subModuleID module.");
        }

        $grid_element = $exports_element->selectFirstElement('PermissionGrid');
        if(empty($grid_element)){
            die("Can't find a matching edit grid in the $subModuleID module.");
        }

        //copy all the fields of the imported grid to the current element
        $element->c = $grid_element->c;

        //copy attributes but allow existing attributes to override
        foreach($grid_element->attributes as $attrName => $attrValue){
            if(empty($element->attributes[$attrName])){
                $element->attributes[$attrName] = $attrValue;
            }
        }
    }

    $grid =& new PermissionGrid(
        $subModuleID,
        $element->attributes['phrase']
    );

    return $grid;
}// end Factory()


function PermissionGrid($pModuleID, $pPhrase)
{
    $this->moduleID = $pModuleID;
    $this->phrase = $pPhrase;
    $this->editable = TRUE;

    $field1 = MakeObject($pModuleID, 'ModuleID', 'InvisibleField', array());
    $this->AddField($field1);
    $field2 = MakeObject($pModuleID, 'Module', 'ViewField', array());
    $this->AddField($field2);
    $field3 = new PermissionField(
            'EditPermission',
            'tinyint',
            "Edit Permission"
            );
    $this->AddField($field3);
    $field4 = new PermissionField(
            'ViewPermission',
            'tinyint',
            "View Permission"
            );
    $this->AddField($field4);

    $this->listSQL = "SELECT `mod`.ModuleID, `mod`.Name AS Module, `mod`.OwnerField, usrp.EditPermission, usrp.ViewPermission FROM `mod` LEFT OUTER JOIN usrp ON `mod`.ModuleID = usrp.ModuleID AND (ISNULL(usrp.PersonID) OR usrp.PersonID = /*recordID*/) WHERE `mod`.ParentModuleID = '' ";
    $this->valueListSQL = "";
    $this->ParentRowSQL = "";
} // end PermissionGrid constructor


function init()
{
    //overrides inherited init function
} //end init()


function render($page, $qsArgs)
{
    global $recordID;
    global $dbh;

    //capture order by parameter
    $orderBy = $qsArgs['ob'.$this->number];
    $prevOrderBy = $qsArgs['pob'.$this->number];

    //making sure ob and pob fields exist in grid:
    if(!in_array($orderBy, array_keys($this->Fields))){
        $orderBy = '';
    }
    if(!in_array($prevOrderBy, array_keys($this->Fields))){
        $prevOrderBy = '';
    }

    //add grid ID to all links
    $qsArgs['gid'] = $this->number;

    unset($qsArgs['ob'.$this->number]);
    unset($qsArgs['pob'.$this->number]);
    $headerQS = MakeQS($qsArgs);

    //make form query string
    $formQS = MakeQS($qsArgs);

    $listSQL = $this->listSQL;
    $listSQL = str_replace('/*recordID*/', $recordID, $listSQL);

    if(!empty($orderBy)){
        $listSQL .= "\n ORDER BY $orderBy ";
        if($orderBy == $prevOrderBy){
            $listSQL .= " DESC\n";
        } else {
            $listSQL .= " ASC\n";
        }
    } else {
        $listSQL .= "\n ORDER BY mod.Name ";
    }

    //setting up link for the next pob
    if(!empty($orderBy)){ 
        $prevOBString = '&pob'.$this->number.'='.$orderBy;
    } else {
        $prevOBString = '';
    }

    $content = '';
    foreach($this->Fields as $FieldName => $Field)
    {
        if('invisiblefield' != get_class($Field) && empty($Field->parentName)){
            if($prevOrderBy != $FieldName){
                $fPrevOBString = $prevOBString;
            } else {
                $fPrevOBString = '';
            }
            $content .= sprintf(
                GRID_HEADER_CELL,
                'edit.php?'.$headerQS.'&ob'.$this->number.'='.$FieldName.$fPrevOBString,
                $Field->gridHeaderPhrase()
            );
        }
    }

    //format header row
    $content = sprintf(
        GRID_HEADER_ROW,
        $content
    );

    $r = $dbh->getAll($listSQL, DB_FETCHMODE_ASSOC);
    dbErrorCheck($r);

    //alternating row background colors
    $tdFormatting = array('l', 'l2');

    //get selected row id
    $selRowID = $this->selectedID; //this allows Cancel to reset //$qsArgs['grw'];

    unset($qsArgs['grw']); //remove from QS
    $rowQS = MakeQS($qsArgs); //QS for row "Edit" links

    //display rows
    foreach($r as $rowNum => $row){

        $curRowID = reset($row); //assumes first column is row ID
        $trClass = $tdFormatting[($rowNum) % 2];
        $rowContent = "";

        //add editable cells
        foreach($this->Fields as $key => $field){
            if('permissionfield' == get_class($field)){
                $tdClass = 'n';
                $modID = '';
            } else {
                $tdClass = $trClass;
                $modID = ' ('.$row['ModuleID'] . ')';
            }
            if('invisiblefield' != get_class($field) && empty($field->parentName)){
                $rowContent .= sprintf(
                    GRID_EDIT_CELL,
                    "left",
                    $tdClass,
                    $field->checkGridRender(&$row) . $modID
                );
            }
        }

        //parameters: class, rowID, firstcellcontent, content
        $content .= sprintf(
            VIEWGRID_ROW,
            $trClass,
            '0',
            '',
            $rowContent
        );
    }

    //append collection fields
    $content .= '</form><form action="edit.php?'.$formQS.'" name="'.$this->moduleID.'" method="post">';
    $content .= '<input type="hidden" name="collEditAll" id="collEditAll" value=""/>';
    $content .= '<input type="hidden" name="collEditOrgs" id="collEditOrgs" value=""/>';
    $content .= '<input type="hidden" name="collEditNone" id="collEditNone" value=""/>';
    $content .= '<input type="hidden" name="collViewAll" id="collViewAll" value=""/>';
    $content .= '<input type="hidden" name="collViewOrgs" id="collViewOrgs" value=""/>';
    $content .= '<input type="hidden" name="collViewNone" id="collViewNone" value=""/>';

    //append row with save button
    $content .= sprintf(
        //parameters: class, columns, savePhrase
        CHECKGRID_SAVEROW,
        'flbb',
        4,
        'Save'
    );

    //format grid table: phrase (grid title), form target, form name, enctype, grid number, table content
    $content = sprintf(
        EDITGRID_MAIN,
        $this->phrase . ' (' . gettext("Note: this page will take some time to load.").')',
        'edit.php?'.$formQS,
        'bogus',
        '',
        $this->number,
        $content);

    global $data;
    if($data['IsAdmin']){
        $content = gettext("NOTE: This user has Site Admin privileges, which gives <strong>full access</strong> to all modules in the application. The permissions selected below are effective only if the Site Admin privilege is revoked.<br />") . $content;
    }

    global $jsIncludes;
    $jsIncludes .= "\n".'<style>
.prm {
    white-space:nowrap;
    text-align:center;
    border:3px solid
}
.pri_none {
    background-color: #ffbbbb
}
.pri_org {
    background-color: #ffff99
}
.pri_all {
    background-color: #bbffbb
}
.pro_none {
    border-color: #ffbbbb
}
.pro_org {
    border-color: #ffff99
}
.pro_all {
    border-color: #bbffbb
}
</style>'."\n";

    return  $content;
} //end render()


function handleForm()
{
    if(!empty($_POST['Save'])){
        global $recordID;
        global $dbh;
        global $User;

        $posted = array();
        if(!empty($_POST['collEditAll'])){
            $posted['edit'][2]  = explode(' ', trim($_POST['collEditAll']));
        }
        if(!empty($_POST['collEditOrgs'])){
            $posted['edit'][1] = explode(' ', trim($_POST['collEditOrgs']));
        }
        if(!empty($_POST['collEditNone'])){
            $posted['edit'][0] = explode(' ', trim($_POST['collEditNone']));
        }
        if(!empty($_POST['collViewAll'])){
            $posted['view'][2]  = explode(' ', trim($_POST['collViewAll']));
        }
        if(!empty($_POST['collViewOrgs'])){
            $posted['view'][1] = explode(' ', trim($_POST['collViewOrgs']));
        }
        if(!empty($_POST['collViewNone'])){
            $posted['view'][0] = explode(' ', trim($_POST['collViewNone']));
        }
        //data validation
        foreach($posted as $type => $values){
            foreach($values as $value => $modules){
                if(count($modules) > 0){
                    foreach($modules as $modID){
                        //there's probably a regular expression that could be used here?
                        if (strlen($modID) < 3){
                            print "'$modID'<br/>";
                            die('error in posted data');
                        }
                        if (strlen($modID) > 5){
                            print "'$modID'<br/>";
                            die('error in posted data');
                        }
                        //should also check that all characters are all letters and numbers
                    }
                }
            }
        }

        //start transaction

        //get current values from DB - to see what should be inserted, and what should be updated
        global $dbh;
        global $recordID;
        $SQL = str_replace('/*recordID*/', $recordID, $this->listSQL);

        $r = $dbh->getAll($SQL, DB_FETCHMODE_ASSOC);
        dbErrorCheck($r);

        //see what modules there is a permission record for, and not
        $inserts = array();
        $updates = array();
        foreach($r as $row){
            if('' == $row['EditPermission'] && '' == $row['ViewPermission']){
                $inserts[] = $row['ModuleID'];
            } else {
                $updates[] = $row['ModuleID'];
            }
        }

        $insertValues = array();
        $updateValues = array();

        //rearrange the data so that we can put it in the SQL statements easily
        foreach($posted as $type => $values){
            foreach($values as $value => $modules){
                foreach($modules as $modID){
                    if(in_array($modID, $updates)){
                        $updateValues[$type][$value][] = $modID;
                        //$updateValues[$type][$modID] = $value;
                    } elseif(in_array($modID, $inserts)) {
                        $insertValues[$modID][$type] = $value;
                        //$insertValues[$type][$modID] = $value;
                    }
                }
            }
        }
//print debug_r($updateValues, 'updates');
//print debug_r($insertValues, 'inserts');

        //make and execute the upste statements
        foreach($updateValues as $type => $values){
            foreach($values as $value => $modIDs){
                $quotedModuleIDs = array();
                foreach($modIDs as $modID){
                    $quotedModuleIDs[] = "'$modID'";
                }
                $updateModuleIDs = join(', ', $quotedModuleIDs);
                $updateSQL = "UPDATE usrp SET {$type}Permission = {$value}, _ModDate = NOW(), _ModBy = {$User->PersonID} WHERE PersonID = $recordID AND ModuleID IN ($updateModuleIDs)";
//print debug_r($updateSQL)."<br />\n";
                $r = $dbh->query($updateSQL);
                dbErrorCheck($r);
            }
        }

        //make the insert value strings
        $insertSQLs = array();
        if(count($insertValues) > 0){
            $insertSQL = "INSERT INTO usrp (PersonID, ModuleID, EditPermission, ViewPermission, _ModDate, _ModBy) VALUES ";
            foreach($insertValues as $modID => $values){
                $editVal = intval($values['edit']);
                $viewVal = intval($values['view']);
                $insertSQLs[] = "($recordID, '$modID', {$editVal}, {$viewVal}, NOW(), {$User->PersonID})";
            }

            //execute the insert statement
            if(count($insertSQLs) > 0){
                $insertSQL = $insertSQL . join(', ', $insertSQLs);
//print $insertSQL."<br />\n";
                $r = $dbh->query($insertSQL);
                dbErrorCheck($r);
            }
        }
    }
    return true;
} //end handleForm()

} //end class PermissionGrid



class PermissionField extends CodeRadioField
{

function PermissionField($pName, $pDataType, $pPhrase)
{
    $this->name = $pName;
    $this->dataType = $pDataType;
    $this->phrase = $pPhrase;
} //end PermissionField constructor


function checkGridRender(&$pRow)
{
    $data = '';

    if(!empty($pRow['OwnerField'])){
        $values = array(
            2 => 'All',
            1 => 'Orgs',
            0 => 'None'
        );
    } else {
        $values = array(
            2 => 'All',
            0 => 'None'
        );
    }
    $colors = array(
        2 => 'all',
        1 => 'org',
        0 => 'none'
    );
    $data .= '<div id="bg_'.$pRow['ModuleID'].$this->name.'" class="prm pro_'.$colors[intval($pRow[$this->name])].' pri_'.$colors[intval($pRow[$this->name])].'">';
    foreach($values as $valueID => $valueLabel){
        if($valueID == $pRow[$this->name]){
            $checked = 'checked';
        } else {
            $checked = '';
        }

        //parameters: 1. field name, 2. value name, 3. checked, 4. value id
        $data .= sprintf(
            FORM_RADIOBUTTON,
            $pRow['ModuleID'].$this->name,
            $valueLabel,
            $checked . ' onchange="checkPermissionRadioButton(this);" ',
            $valueID
        );
        $data .= '&nbsp;&nbsp;';
    }
    $data .= '</div>';

    return $data;
}//end checkGridRender()


function gridHeaderPhrase()
{
    return $this->phrase;
}//end gridHeaderPhrase()

}//end class PermissionField
?>