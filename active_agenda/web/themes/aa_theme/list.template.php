<?php
/**
 * HTML/PHP layout template for the List screens
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
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <link rel="stylesheet" type="text/css" href="<?php echo $theme_web; ?>/style.css" />
    <!--[if IE]>
    <link rel="stylesheet" type="text/css" href="<?php echo $theme_web; ?>/style-ie.css" />
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="<?php echo $theme_web; ?>/menuG5.css">

    <!-- yahoo user interface library -->
    <script type="text/javascript" src="3rdparty/yui/yahoo/yahoo.js"></script>
    <script type="text/javascript" src="3rdparty/yui/event/event.js" ></script>
    <script type="text/javascript" src="3rdparty/yui/dom/dom.js" ></script>
    <script type="text/javascript" src="3rdparty/yui/dragdrop/dragdrop.js" ></script>
    <script type="text/javascript" src="3rdparty/yui/container/container.js"></script>
    <!--<link type="text/css" rel="stylesheet" href="3rdparty/yui/container/assets/container.css">-->

    <!-- DataRequestor (could be replaced with yahoo's connection class) -->
    <script type="text/javascript" src="3rdparty/DataRequestor.js"></script>

    <!-- Active Agenda functions -->
    <script language="JavaScript" src="js/lib.js"></script>
    <script language="JavaScript" src="js/tabs.js"></script>

    <script language="JavaScript">
        var moduleID = '<?php echo $moduleID;?>';

        YAHOO.namespace("activeagenda");
        function yInit() {
<?php if(!empty($parentInfo)){ ?>
            // Instantiate a Panel from markup
            YAHOO.activeagenda.poprel = new YAHOO.widget.Panel("pop_relations", { width:"300px", visible:false, constraintoviewport:false, context:['relations','tr','br']} );
            YAHOO.activeagenda.poprel.render();
<?php } //end if ?>
            return true;
        }

<?php if($User->browserInfo['is_IE']){ ?>
        YAHOO.util.Event.addListener(window, "load", layoutFixIE);
        YAHOO.util.Event.addListener(window, "resize", layoutFixIE);
<?php } else { ?>
        YAHOO.util.Event.addListener(window, "load", layoutFix);
        YAHOO.util.Event.addListener(window, "resize", layoutFix);
<?php } //end if ?>

        YAHOO.util.Event.addListener(window, "load", attachTabEffects);
        YAHOO.util.Event.addListener(window, "load", setupTabTooltips);
        YAHOO.util.Event.addListener(window, "load", yInit);
    </script>
</head>
<body>
    <div id="content">
        <div id="pagetitle_left">
            <div id="pagetitle_right">
                <div id="pagetitle">
                    <div id="pagetitle_label">
                        <?php echo $title;?>

                    </div>
                </div>
            </div>
        </div><!-- end pagetitle_left -->
<?php if(!empty($parentInfo)){ ?>
        <div id="relations_left">
            <div id="relations_right">
                <div id="relations">
                    <div id="relations_label">
                        <?php echo $parentInfo;?>

                    </div>
                </div>
            </div>
        </div>

        <div id="pop_relations">
            <div class="hd">
                Relations
            </div>
            <div class="bd" id="pop_relations_content">No data.</div>
            <div class="ft"></div>
        </div>
<?php } //end if ?>
        <div id="sideshim">
            <div id="sidearea">
                <div id="logo">
                    <img src="<?php echo $theme_web; ?>/img/logo_thumb.png"/>
                </div>
<?php 
                include_once($theme . '/icons.snip.php');
?>
                <div id="tabwrapper">
                    <div id="tabcontainer">
                        <div id="tabcontainer_inner">
<?php
                foreach ($tabs as $tab_key=>$tab_value) {
                    switch($tab_value[2]){
                    case 'self':
?>
                            <div class="tabsel">
                                <div class="tabb">
                                    <?php echo ShortPhrase($tab_value[1]); ?>
                                </div>
                            </div>
<?php
                        break;
                    case 'disabled':
?>
                            <div class="tabdisabled">
                                <div class="tabb" id="<?php echo 'tab'.$tab_key; ?>" title="<?php echo addslashes(LongPhrase($tab_value[1])); ?>">
                                    <?php echo ShortPhrase($tab_value[1]); ?>
                                </div>
                            </div>
<?php
                        break;
                    case 'download':
                        $target = '';
                        if($User->browserInfo['is_IE']){
                            $target = ' target="'.$tab_value[2].'"';
                        }
?>
                            <div class="tabunsel">
                                <a class="tabb" id="<?php echo 'tab'.$tab_key; ?>" href="<?php echo $tab_value[0]; ?>"<?php echo $target; ?> title="<?php echo addslashes(LongPhrase($tab_value[1])); ?>"><?php echo ShortPhrase($tab_value[1]);?></a>
                            </div>
<?php
                        break;
                    default:
                        if(empty($tab_value[3])){
                            $tab_class = 'tabunsel';
                        } else {
                            $tab_class = $tab_value[3];
                        }
?>
                            <div class="<?php echo $tab_class; ?>">
                                <a class="tabb" id="<?php echo 'tab'.$tab_key; ?>" href="<?php echo $tab_value[0]; ?>" title="<?php echo addslashes(LongPhrase($tab_value[1])); ?>"><?php echo ShortPhrase($tab_value[1]);?></a>
                            </div>
<?php

                    }
                }
?>
                            <div class="tabg">
                                <a class="tabb" id="tabNotificationSetup" href="notificationSetup.php?mdl=<?php echo $moduleID;?>" title="Manage the list of people that should be notified." >Notification List</a>
                            </div>
                        </div>
                    </div>
                </div>

<?php 
        //include for dashboard shortcut
        include_once $theme . '/dashcut.snip.php';
?>
            </div><!-- end #sidearea -->
            </div><!-- end #sideshim -->
            <?php echo $introText;?>
            <?php echo $content;?>
        <!-- </div> --> <!-- end #content2 -->
    </div><!-- end #content -->
<?php 
    include 'footer.snip.php';
?>
</body>
</html>