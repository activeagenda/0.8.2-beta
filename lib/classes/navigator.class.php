<?php
/**
 *  Class that generates the JavaScript output definition for the Menu
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

class Navigator
{
var $Items = array();
var $ParseContext = array();
var $counter = 0;

function Navigator($UserID)
{
    define('LOADING_NAVIGATION', true);

    $navigationFile = APP_FOLDER . '/Navigation.xml';

    if (file_exists($navigationFile)){
            //assign SAX parser objects
            $parser = xml_parser_create();
            xml_set_object($parser, $this);
            xml_set_element_handler($parser, 'parseStartElement', 'parseEndElement');
            xml_set_character_data_handler($parser, 'parseCharacterData');

            //load the file ans parse its contents
            xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, false);

            $fp = fopen($navigationFile, 'r') or die("Can't read XML data.");
            while ($data = fread($fp, 4096)) {
                xml_parse($parser, $data, feof($fp)) or die("Can't parse XML data.");
            }
            fclose($fp);

            xml_parser_free($parser);

    } else {
        die("Could not open navigation settings file<br>\n");
    }

}

function parseStartElement($parser, $tag, $attr)
{
    switch($tag){
    case "Category":
        $t_item = &new NavCategory(
            $attr['phrase']
        );
        break;
    case "InternalLink":
        $t_item = &new NavInternalLink(
            $attr['primary'],
            $attr['secondary'],
            $attr['frame'],
            $attr['expand'],
            $attr['newbrowser'],
            $attr['phrase']
        );
        break;
    case "ModuleLink":
        $t_item = &new NavModuleLink(
            $attr['moduleID'],
            $attr['phrase']
        );
        break;
    case "ExternalLink":
        $t_item = &new NavExternalLink(
            $attr['target'],
            $attr['phrase']
        );
        break;
    }

    switch($tag){
    case "Category":
    case "InternalLink":
    case "ModuleLink":
    case "ExternalLink":

        //find the parent by iterating down the context
        $t_parent = &$this;
        foreach($this->ParseContext as $id){
            $t_parent =& $t_parent->Items[$id];
        }


        $this->ParseContext[] = $this->counter; //&$t_item;
        $t_parent->Items[$this->counter] = &$t_item;

        unset($t_parent);
        unset($t_item);

        //increment counter
        $this->counter++;
    }
}

function parseEndElement($parser, $tag)
{
    switch($tag){
        case "Category":
        case "InternalLink":
        case "ModuleLink":
        case "ExternalLink":
            $item = &array_pop($this->ParseContext);
            //print "items = ".count($this->Items)."<br>\n";
            //print "<br>Now leaving " . $item->phrase . "<br><br>\n";
            break;
    }
}

function parseCharacterData($parser, $data)
{
    //not many elements in or module definition take character data...
}

function render($menuType = '')
{
    switch($menuType){
    case 'G5':
        return $this->renderG5Menu();
        break;
    }

    $menuCode = '';
    $counter = 1;
    foreach($this->Items as $item){
        $menuCode .= $item->render("$counter");
        $menuCode .= "\n";
        $counter++;
    }

    $content = '<script type="text/javascript">' . "\n" . $menuCode . '</script>';

    return $content;
}

function renderG5Menu()
{
    $topname = 'nav-top';
    $menuCode = "addMenu('Nav', '{$topname}');\n";

    $counter = 1;
    foreach($this->Items as $item){
        $menuCode .= $item->render("{$topname}_{$counter}", 'G5');
        $menuCode .= "\n";
        $counter++;
    }

    $menuCode .= "endMenu();\n";
    $content = $menuCode;

    return $content;
}


function renderHTML($selModuleID = '')
{

    $content = '';
    $counter = 1;
    foreach($this->Items as $item){
        $content .= $item->renderHTML();
        $content .= "\n";
        $counter++;
    }


    return $content;
}


function getPhrases()
{
    $phrases = array();
    foreach($this->Items as $item){
        $phrases = array_merge($item->getPhrases(), $phrases);
    }
    return $phrases;
}
}  //end class Navigator


//navigation item classes

//abstract class
class NavItem 
{
var $Items = array();
var $phrase;
var $longPhrase;
var $itemWidth = 180;
var $itemHeight = 20;

function getNumVisibleChildren()
{
    $num = 0;
    foreach($this->Items as $item){
        if($item->isVisible()){
            $num++;
        }
    }

    return $num;
}

function isVisible(){
    return TRUE; //override for Categories and Modules
}

function render($myID, $menuType = '')
{
    return gettext($this->phrase); //override this
}

function renderChildren($myID, $menuType = '')
{
    $content = '';

    if(count($this->Items) > 0){
        $counter = 1;
        foreach($this->Items as $item){

            $itemContent = $item->render("{$myID}_{$counter}", $menuType);
            if(!empty($itemContent)){
                $content .= "\n";
                $content .= $itemContent;

                $counter++;
            }
        }
    }

    return $content;
}


function renderHTML($showClassName = FALSE)
{
    if($this->phrase == 'Navigate'){
        $content .= $this->renderChildrenHTML();
    } else {
        if($showClassName){
            $content = "<li>".get_class($this) .' '. gettext($this->phrase); 
        } else {
            $content = '<li><a href="#">&nbsp;</a><b>'.gettext($this->phrase).'</b>'; 
        }
        $content .= $this->renderChildrenHTML();
        
        $content .= "</li>";
    }
    return $content;
}

function renderChildrenHTML()
{
    if($this->phrase == 'Navigate'){
        $id = 'id="nav"';
    } else {
        $id = '';
    }

    if(count($this->Items) > 0){
        $content = "<ul $id>\n";
        $counter = 1;
        foreach($this->Items as $item){

            $content .= "\n";
            $content .= $item->renderHTML();

            $counter++;
        }
        $content .= "\n</ul>\n";
    }

    return $content;
}

function getPhrases()
{
    $phrases = array($this->phrase); //no gettext call here
    if(count($this->Items) > 0){
        foreach($this->Items as $item){
            $phrases = array_merge($item->getPhrases(), $phrases);
        }
    }
    return $phrases;
}

function checkPhrase($phrase)
{
    if(empty($phrase)){
        return ' ';
    } else {
        return $phrase;
    }
}

} //end class NavItem



class NavCategory extends NavItem 
{
function NavCategory($pPhrase)
{
    $this->phrase = NavItem::checkPhrase(ShortPhrase($pPhrase));
    $this->longPhrase = NavItem::checkPhrase(LongPhrase($pPhrase));
}

function isVisible()
{
    //only visible if at least one child is visible
    $visible = false;
    foreach($this->Items as $item){
        if($item->isVisible()){
            $visible = true;
        }
    }
    return $visible;
}

function render($myID, $menuType = '')
{
    $childrenContent = $this->renderChildren($myID, $menuType);

    if(!empty($childrenContent)){
        $numItems = $this->getNumVisibleChildren();
        $phrase = gettext($this->phrase);

        switch($menuType){
        case 'G5':
        default:
            $parentID = substr($myID, 0, strrpos($myID, '_')); 
            if('nav-top' == $parentID){
                $group = '';
            } else {
                $group = 'cat';
            }

            if(0 === $numItems){
                $content = "addLink(\"$parentID\", \"$phrase\", \"\", \"\", \"$group\");";
            } else {
                $content = "addSubMenu(\"$parentID\", \"$phrase\", \"\", \"\", \"$myID\", \"$group\");";
            }
            break;
        }
        //include children:
        $content .= $childrenContent;

        return $content;

    } else {
        return '';
    }
}

function renderHTML($showClassName = FALSE)
{
    $content = '';
    if($this->isVisible()){
        if($this->phrase == 'Navigate'){
            $content .= $this->renderChildrenHTML();
        } else {
            if($showClassName){
                $content = "<li>".get_class($this) .' '. gettext($this->phrase); 
            } else {
                $content = '<li><a href="#">&nbsp;</a><b>'.gettext($this->phrase).'</b>'; 
            }
            $content .= $this->renderChildrenHTML();

            $content .= "</li>";
        }
        return $content;
    } else {
        return '';
    }
}
}

class NavInternalLink extends NavItem 
{
var $primary;   //primary target
var $secondary; //secondary target (is loaded in opposite frame when MultiFrame is used)
var $frame;     //the frame to load $primary into. valid values are 'upper' and 'lower'
var $expand = 'both';   //what frame to expand. the other valid values are 'upper' and 'lower'
var $newbrowser; //whether to open a new browser

function NavInternalLink($pPrimary, $pSecondary, $pFrame, $pExpand, $pNewBrowser, $pPhrase){

    $this->primary = $this->addPageExtenstion($pPrimary);
    $this->secondary = $this->addPageExtenstion($pSecondary);

    $this->frame = $pFrame;
    $this->expand = $pExpand;
    $this->newbrowser = $pNewBrowser;
    $this->phrase = NavItem::checkPhrase(ShortPhrase($pPhrase));
    $this->longPhrase = NavItem::checkPhrase(LongPhrase($pPhrase));
}


function render($myID, $menuType = '')
{

    $numItems = $this->getNumVisibleChildren();
    $phrase = gettext($this->phrase);
    $linkto = $this->primary;

    switch($menuType){
    case 'G5':
    default:
        $parentID = substr($myID, 0, strrpos($myID, '_')); 
        if(0 === $numItems){
            $content = "addLink(\"$parentID\", \"$phrase\", \"{$this->longPhrase}\", \"$linkto\", \"\");";
        } else {
            $content = "addSubMenu(\"$parentID\", \"$phrase\", \"{$this->longPhrase}\", \"$linkto\", \"$myID\", \"\");";
        }
        break;
    }
    //include children:
    $content .= $this->renderChildren($myID, $menuType);

    return $content;
}


function renderHTML($showClassName = FALSE)
{
    $linkto = $this->primary;
    $content = "<li>";
    if($showClassName){
        $content .= get_class($this);
    } 
    $content .= '<a href="'.$linkto.'"><b>'.gettext($this->phrase).'</b></a>'; 
    $content .= $this->renderChildrenHTML();

    return $content;
}

function addPageExtenstion($link)
{
    $ext = 'php';
    if(false === strpos(strtolower($link), '.'.$ext)){
        if(false === strpos(strtolower($link), '?')){
            $link .= '.'.$ext;
        } else {
            $parts = split('?', $link, 1);
            $link = $parts[0] . '.' . $ext . '?' . $parts[1];
        }
    } 
    return $link;
}
}

class NavExternalLink extends NavItem
{
var $target;
function NavExternalLink($pTarget, $pPhrase)
{
    $this->target = $pTarget;
    $this->phrase = NavItem::checkPhrase(ShortPhrase($pPhrase));
    $this->longPhrase = NavItem::checkPhrase(LongPhrase($pPhrase));
}

function render($myID, $menuType = '')
{
    $numItems = $this->getNumVisibleChildren();
    $phrase = gettext($this->phrase);
    $linkto = 'http://'.$this->target;

    switch($menuType){
    case 'G5':
    default:
        $parentID = substr($myID, 0, strrpos($myID, '_')); 

        if(0 == $numItems){
            $content = 
            "addLink(\"$parentID\", \"$phrase\", \"{$this->longPhrase}\", \"$linkto\", \"\");";
        } else {
            "addSubMenu(\"$parentID\", \"$phrase\", \"{$this->longPhrase}\", \"$linkto\", \"$myID\", \"\");";
        }
        break;
    }
    //include children:
    $content .= $this->renderChildren($myID, $menuType);

    return $content;

}

function renderHTML($showClassName = FALSE)
{
    $linkto = 'http://'.$this->target;
    $content = "<li>";
    if($showClassName){
        $content .= get_class($this);
    } 
    $content .= '<a href="'.$linkto.'"><b>'.gettext($this->phrase).'</b></a>'; 
    $content .= $this->renderChildrenHTML();

    return $content;
}
}


class NavModuleLink extends NavInternalLink
{
var $moduleID;
function NavModuleLink($pModuleID, $pPhrase)
{
    $this->moduleID = $pModuleID;
    $this->primary = "list.php?mdl=$pModuleID";
    $this->secondary = "search.php?mdl=$pModuleID";
    $this->frame = 'lower';
    $this->expand = 'both';
    $this->newbrowser = false;
    $this->phrase = NavItem::checkPhrase(ShortPhrase($pPhrase));
    $this->longPhrase = NavItem::checkPhrase(LongPhrase($pPhrase));
}

function isVisible()
{
    global $User;
    //if the user has no permission to this item but to a child
    if(! $User->PermissionToView($this->moduleID)){
        $visible = FALSE;
        foreach($this->Items as $item){
            if($item->isVisible()){
                $visible = TRUE;
            }
        }
        return $visible;
    } else {
        return TRUE;
    }
}

function render($myID, $menuType = '')
{
    global $User;

    if($User->PermissionToView($this->moduleID)){
        return parent::render($myID, $menuType);
    } else {
        if($this->isVisible()){
            //forced view render w/o link...
            $numItems = $this->getNumVisibleChildren();
            $phrase = gettext($this->phrase);
            switch($menuType){
            case 'G5':
            default:
                $parentID = substr($myID, 0, strrpos($myID, '_')); 

                if(0 == $numItems){
                    $content = 
                    "addLink(\"$parentID\", \"$phrase\", \"{$this->longPhrase}\", \"\", \"\");";
                } else {
                    "addSubMenu(\"$parentID\", \"$phrase\", \"{$this->longPhrase}\", \"\", \"$myID\", \"\");";
                }
                break;
            }
            //include children:
            $content .=  $this->renderChildren($myID, $menuType);

            return $content;
        } else {
            return '';
        }
    }
}

function renderHTML($showClassName = FALSE)
{
    global $User;

    if($User->PermissionToView($this->moduleID)){
        if($showClassName){
            $content = "<li>".get_class($this) .' '. gettext($this->phrase); 
        } else {
            if(count($this->Items) == 0){
                $class = ' class="pad"';
                $expandlink = '&nbsp;';
            } else {
                $class = '';
                $expandlink = '<a href="#">&nbsp;</a>';
            }
            $content = '<li>'.$expandlink.'<b><a'.$class.' href="'.$this->primary.'">'.gettext($this->phrase).'</a></b>'; 
        }
        $content .= $this->renderChildrenHTML();
        $content .= "</li>";
        return $content;
    } else {
        if($this->isVisible()){
            //same as render a category
            $content = NavItem::renderHTML($showClassName);

            return $content;
        } else {
            return '';
        }
    }
}
}

?>
