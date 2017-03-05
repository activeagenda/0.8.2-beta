/**
 * SelectGrid JS functions for s2a/Active Agenda
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

function loadData(moduleID){
    var req = new DataRequestor();
    req.addArg(_GET, "mdl", moduleID);

    for (n=0;n<searchFieldNames.length;n++){
        req.addArg(_POST, searchFieldNames[n], document.forms['searchForm'].elements[searchFieldNames[n]].value);
    }

    req.getURL('rpc/ssgGetAvailable.php');
    req.onload = function (data, obj) {

        if('session timeout' == data){
            alert("Data cannot be retrieved from the server because your session has expired. Click OK to re-login.");
            location.reload();
            return false;
        }
        if('ERROR' == data.substring(0,5)){
            alert("An error was encountered:\n" + data);
            return false;
        }
        if('<BR />' == data.substring(0,6)){
            alert("An error was encountered:\n" + data);
            return false;
        }

        data = 'available = ' + data;
        eval(data); //returns object (i.e. assoc array "available")

        listAvailable(available);
    }

}
function listAvailable(available){
    var htmlCode = '';
    for(itemID in available) {
        if(selected.hasOwnProperty(itemID)){
            itemClass = "selected";
        } else {
            itemClass = "unselected";
        }
        htmlCode = htmlCode + '<li class="'+itemClass+'" onclick="clickAvailable(this);" id="' + itemID + '">' + available[itemID]+'</li>\n';
    }
    document.getElementById('availableList').innerHTML = htmlCode;
}

//populates selected list
function initSelected(selected){
    for(itemID in selected) {
        document.getElementById('selectedList').innerHTML += '<li id="s' + itemID + '" onclick="clickSelected(this);">' + selected[itemID] +'</li>\n';
    }
}

function clickAvailable(item){
    selectItem(item.id, item);
}

//removes an item from the selected list
function clickSelected(item){
    itemID = item.id.substring(1);
    unselectItem(itemID);
}

//second parameter optional
function selectItem(itemID, availItem){
    if(!selected.hasOwnProperty(itemID)){
        if(!availItem){
            availItem = document.getElementById(itemID);
        }
        selected[itemID] = availItem.innerText; //add to assoc array
        document.getElementById('selectedList').innerHTML = '<li id="s' + itemID + '" onclick="clickSelected(this);">' + availItem.innerHTML+'</li>\n' + document.getElementById('selectedList').innerHTML;
    }

    //remove from available ul
    toRemove = document.getElementById(itemID);
    document.getElementById('availableList').removeChild(toRemove);
}

function unselectItem(itemID, availItem){
    if(selected.hasOwnProperty(itemID)){
        if(!availItem){ //check if passed
            availItem = document.getElementById(itemID);
        }
        selItem = document.getElementById('s'+itemID);
        if(availItem){ //check if visible
            availItem.className = "unselected";
        } else {

            document.getElementById('availableList').innerHTML = '<li class="unselected" id="' + itemID + '" onclick="clickAvailable(this);">'+selItem.innerHTML+'</li>\n' + document.getElementById('availableList').innerHTML;

        }
        delete selected[itemID];
        available[itemID] = selItem.innerHTML;

        //remove from selected ul
        toRemove = document.getElementById('s' + itemID);
        document.getElementById('selectedList').removeChild(toRemove);
    }
}

function selectAll(){
    for(itemID in available){
        selectItem(itemID);
    }
}

function unselectAll(){
    for(itemID in selected){
        unselectItem(itemID);
    }
}

//populates the hidden field and submits the form...
function saveSelected(){
    var selectedIDs = '';
    for(itemID in selected){
        selectedIDs = selectedIDs + ' ' + itemID;
    }
    document.forms['searchForm'].elements['SaveIDs'].value = selectedIDs;
    document.forms['searchForm'].submit();
}