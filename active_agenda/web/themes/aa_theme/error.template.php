<?php
/**
 * HTML/PHP layout template for the Error message screen
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
//error might happen before $theme_web is set
if(empty($theme_web)){
    $theme_web = 'themes/aa_theme';
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

        <script language="JavaScript">
        var defaultMessage = "<?php echo  $title . " - " . $screenPhrase ?>";
        window.defaultStatus = defaultMessage;
        </script>

    </head>
    <body>

        <div id="content" style="float:left;padding-left:25px">

            <div id="logo">
                <a href="http://www.activeagenda.net" target="_blank"><img src="<?php echo $theme_web; ?>/img/logo_thumb.png"/></a>
            </div>
            <div class="tabclear">&nbsp;</div>
            <h1 class="pageTitle"><?php echo $title;?></h1>

                <?php echo $content;?>
                <p><b><a href="#" onclick="history.go(-1)">Back</a></b></p>
        </div>
        <?php 
              include 'footer.snip.php';
              ?>
        <script language="JavaScript">
            pageLoaded = true;
        </script>
    </body>
</html>