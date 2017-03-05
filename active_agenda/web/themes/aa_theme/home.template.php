<?php
/**
 * HTML/PHP layout template for the Home (Dashboard) screen
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

    <script language="JavaScript" src="js/lib.js"></script>
    <script language="JavaScript" src="3rdparty/DataRequestor.js"></script>
    <script language="JavaScript" src="js/home.js"></script>
    <script language="JavaScript">
        var openedDashMenu;
    </script>
</head>
<body >
    <div id="content_notitle">
        <div id="dashMenuSection">
            <h1 id="dashboard_title"><?php echo $title;?></h1>
            <div class="dashMenu">
                <a id="act" href="#" onclick="togglePopOver('act')">Your Actions (<span id="act_count"><?php echo $dashGridInfo['act'];?></span>)&nbsp;&nbsp;<img src="<?php echo $theme_web; ?>/img/arrowdown.gif" alt="(Open the List)" /></a>
            </div>
            <div class="dashMenu">
                <a id="acc" href="#" onclick="togglePopOver('acc')">Your New Accountabilities (<span id="acc_count"><?php echo $dashGridInfo['acc'];?></span>)&nbsp;&nbsp;<img src="<?php echo $theme_web; ?>/img/arrowdown.gif" alt="(Open the List)" /></a>
            </div>
            <div class="dashMenu">
                <a id="usrds" href="#" onclick="togglePopOver('usrds')">Your Shortcuts (<span id="usrds_count"><?php echo $dashGridInfo['usrds'];?></span>)&nbsp;&nbsp;<img src="<?php echo $theme_web; ?>/img/arrowdown.gif" alt="(Open the List)" /></a>
            </div>
            <div id="dashStretch">&nbsp;</div>
        </div>
        <?php echo $content;?>
    <?php
        if($User->IsAdmin){
            echo '<div style="clear:both;"><a href="#" onclick="open(\'checkServer.php\', \'checkServer\', \'toolbar=0,width=600,height=600\');">'.gettext("Check server configuration").'</a></div>';
        }
    ?>
    </div>
    <div id="popover" style="position:absolute; display:none; z-index:2;">
        <div id="popover_close">
            <a id="close_link" href="#" onclick="closePopOver()" title="Close">
            <img src="<?php echo $theme_web; ?>/img/chart_remove.png" alt="Close"/>
            </a>
        </div>
        <div id="popover_bar">
            &nbsp;
        </div>
        <div id="popover_content">

        </div>
    </div>
<?
    include 'footer.snip.php';
?>
</body>
</html>