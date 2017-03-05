<?php
/**
 * HTML/PHP layout template for the Edit screens
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

if(!defined('EXEC_STATE') || EXEC_STATE != 1){
    print gettext("This file should not be accessed directly.");
    trigger_error("This file should not be accessed directly.", E_USER_ERROR);
    exit;
}
?>
<!DOCTYPE public "-//w3c//dtd html 4.01 transitional//en">
<html>
<head>
    <title><?php echo $title;?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="<?php echo $theme_web; ?>/style.css" />
    <!--[if IE]>
    <link rel="stylesheet" type="text/css" href="<?php echo $theme_web; ?>/style-ie.css" />
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="<?php echo $theme_web; ?>/menuG5.css">

    <!-- yahoo user interface library -->
    <script type="text/javascript" src="3rdparty/yui/yahoo/yahoo.js"></script>
    <script type="text/javascript" src="3rdparty/yui/event/event.js" ></script>
    <script type="text/javascript" src="3rdparty/yui/dom/dom.js" ></script>
    <script type="text/javascript" src="3rdparty/yui/container/container.js" ></script>

    <script language="JavaScript" src="js/lib.js"></script>
    <script language="JavaScript" src="js/tabs.js"></script>
    <?php echo $jsIncludes; ?>
    <script language="JavaScript">
        var pageLoaded = false;
        YAHOO.namespace("activeagenda");
<?php if($User->browserInfo['is_IE']){ ?>
        YAHOO.util.Event.addListener(window, "load", layoutFixIE);
        YAHOO.util.Event.addListener(window, "resize", layoutFixIE);
<?php } else { ?>
        YAHOO.util.Event.addListener(window, "load", layoutFix);
        YAHOO.util.Event.addListener(window, "resize", layoutFix);
<?php } //end if ?>
        YAHOO.util.Event.addListener(window, "load", attachTabEffects);
        YAHOO.util.Event.addListener(window, "load", setupTabTooltips);
        YAHOO.util.Event.addListener(window, "load", setupFormTooltips);
        YAHOO.util.Event.addListener(window, "load", setupFormEffects);
    </script>
</head>
<body onload="pageLoaded = true;">
    <script language="javascript" type="text/javascript" src="3rdparty/tiny_mce/tiny_mce.js"></script>
    <script language="javascript" type="text/javascript">
    <!--
        tinyMCE.init({
        theme : "simple",
        mode : "textareas",
        onchange_callback : "indicateUnsavedTMCEChanges"
    });
    -->
    </script>
    <div id="content">
        <div id="pagetitle_left">
            <div id="pagetitle_right">
                <div id="pagetitle">
                    <div id="pagetitle_label">
                        <?php if(!empty($prevLink)){
                            print '<a href="'.$prevLink.'"><img src="'.$theme_web.'/img/seq_prev.png" alt="previous"/></a>';
                        } ?>
                        <?php echo $title;?>
                        <?php echo GetPageTitleIcons();?>
                        <?php if(!empty($nextLink)){
                            print '<a href="'.$nextLink.'"><img src="'.$theme_web.'/img/seq_next.png" alt="next"/></a>';
                        } ?>
                    </div>
                </div>
            </div>
        </div>
        <div id="sideshim">
        <div id="sidearea">
            <div id="logo">
                <a href="http://www.activeagenda.net" target="_blank"><img src="<?php echo $theme_web; ?>/img/logo_thumb.png" /></a>
            </div>
            <?php
                include_once($theme . '/icons.snip.php');
            ?>
            <div id="tabwrapper">
                <div id="tabcontainer">
                    <div id="tabcontainer_inner">
<?php
                    foreach ($tabs as $tab_key=>$tab_value) {
                        if(empty($tab_value[0])){
?>
                        <div class="tabsel">
                            <div class="tabb">
                                <?php echo ShortPhrase($tab_value[1]); ?>
                            </div>
                        </div>
<?php
                        } else {
                            if(empty($tab_value[2]) || $User->PermissionToEdit($tab_value[2])){
?>
                        <div class="tabunsel">
                            <a class="tabb" id="<?php echo 'tab'.$tab_key; ?>"  href="<?php echo $tab_value[0]; ?>" title="<?php echo addslashes(LongPhrase($tab_value[1])); ?>"><?php echo ShortPhrase($tab_value[1]); ?></a>
                        </div>
<?php
                            }
                        }
                    }
                    print GetGlobalTabs($ModuleID, $recordID, $GlobalModuleID);
?>
                    </div>
                </div>
            </div>
        </div><!-- end sidearea -->
        </div><!-- end sideshim -->
        <div id="recordLabel_wrapper">
            <span id="recordLabel"><?php echo $recordLabel;?></span>
        </div>
<?php
            if (count($messages) > 0){
                foreach($messages as $message){
                    if ($message[0] == 'e'){
                        print "<div class=\"errmsg\">$message[1]</div><br />";
                    } else {
                        print "<div class=\"msg\">$message[1]</div><br />";
                    }
                }
            }
?>
        <?php echo $content; ?>
    </div>
<?php 
    include 'footer.snip.php';
?>
    <script language="JavaScript">
        var pageLoaded = true;
    </script>
</body>
</html>