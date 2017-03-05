/**
 * User Interface functions for the PermissionGrid in s2a/Active Agenda
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

function checkPermissionRadioButton(sender){
    if(sender.id.indexOf('Edit') == -1){
        isEdit = false;

        //get module ID
        moduleID = sender.name.substring(0, sender.name.indexOf('View'));
        oppositeName = sender.name.replace('View', 'Edit');

    } else {
        isEdit = true;

        //get module ID
        moduleID = sender.name.substring(0, sender.name.indexOf('Edit'));
        oppositeName = sender.name.replace('Edit', 'View');
    }

    //find out opposite selected value
    oppositeElementValue = 0;
    for(i = 0; i <= 2; i++){
        if(oOppositeElement = document.getElementById(oppositeName + '_' + i)){
            if (oOppositeElement.checked){
                oppositeElementValue = i;
                break;
            }
        }
    }
//alert(sender.checked);
    //decide whether to change the opposite selection
    if(isEdit){
        //Edit permissions can be equal or less but not greater than the View permission
        if(sender.value > oppositeElementValue){
            oOppositeField = document.getElementById(oppositeName + '_' + sender.value);
            oOppositeField.checked = true
            checkPermissionRadioButton(oOppositeField);
        }

        if(sender.value == 2){
            oAddTo = document.getElementById('collEditAll');
            oRemoveFrom1 = document.getElementById('collEditOrgs');
            oRemoveFrom2 = document.getElementById('collEditNone');
        } else if(sender.value == 1){
            oAddTo = document.getElementById('collEditOrgs');
            oRemoveFrom1 = document.getElementById('collEditAll');
            oRemoveFrom2 = document.getElementById('collEditNone');
        } else {
            oAddTo = document.getElementById('collEditNone');
            oRemoveFrom1 = document.getElementById('collEditAll');
            oRemoveFrom2 = document.getElementById('collEditOrgs');
        }

    } else {
        //View permissions can be greater or equal but not less than the Edit permission
        if(sender.value < oppositeElementValue){
            oOppositeField = document.getElementById(oppositeName + '_' + sender.value);
            oOppositeField.checked = true
            checkPermissionRadioButton(oOppositeField);
        }

        if(sender.value == 2){
            oAddTo = document.getElementById('collViewAll');
            oRemoveFrom1 = document.getElementById('collViewOrgs');
            oRemoveFrom2 = document.getElementById('collViewNone');
        } else if(sender.value == 1){
            oAddTo = document.getElementById('collViewOrgs');
            oRemoveFrom1 = document.getElementById('collViewAll');
            oRemoveFrom2 = document.getElementById('collViewNone');
        } else {
            oAddTo = document.getElementById('collViewNone');
            oRemoveFrom1 = document.getElementById('collViewAll');
            oRemoveFrom2 = document.getElementById('collViewOrgs');
        }
    }

    oAddTo.value += moduleID + ' ';
    oRemoveFrom1.value = oRemoveFrom1.value.replace(moduleID + ' ', '');
    oRemoveFrom2.value = oRemoveFrom2.value.replace(moduleID + ' ', '');

    colors = ['none','org','all'];
    senderdiv = document.getElementById('bg_'+sender.name);

    currClass = 'pro_' + colors[sender.value];

    YAHOO.util.Dom.removeClass(senderdiv, 'pro_none');
    YAHOO.util.Dom.removeClass(senderdiv, 'pro_org');
    YAHOO.util.Dom.removeClass(senderdiv, 'pro_all');
    YAHOO.util.Dom.addClass(senderdiv, currClass);
}