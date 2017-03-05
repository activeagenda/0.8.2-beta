<?php
/**
 * The code that closes the popup frame
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

require_once '../config.php';
set_include_path(PEAR_PATH . PATH_SEPARATOR . get_include_path());

//this causes session timeouts to display a message instead of redirecting to the login screen
DEFINE('IS_POPUP', true);

//main include file - performs all general application setup
require_once INCLUDE_PATH . '/page_startup.php';
?>
<html>
<head><title>Popup navigation frame</title>
<link rel="stylesheet" type="text/css" href="getCSS.php?sheet=style">
<link rel="stylesheet" type="text/css" href="<?php echo $theme_web; ?>/style.css" />
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_web; ?>/style-ie.css" />
<![endif]-->
</head>
<body id="navframe">
    
    <script type="text/javascript">
        function reloadAndClose(){
            closePopup();
            if(self.parent.opener.document.forms.length > 0){
                self.parent.opener.document.forms[0].submit();
            } else {
                self.parent.opener.location.reload();
            }
        }
        function closePopup(){
            self.parent.close();
            self.parent.opener.focus(); 
        }
    </script>
    <div id="popupTitle">Active Agenda | <?php echo SITE_NAME; ?></div>
    <div id="popupClose">
        <a href="#" onclick="closePopup()"><?php echo gettext("Close");?></a>
        &nbsp;&nbsp;&nbsp;
        <a href="#" onclick="reloadAndClose()"><?php echo gettext("Close and Reload");?></a>
    </div>
</body>
</html>