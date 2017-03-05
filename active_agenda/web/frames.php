<?php
/**
 * Handles redirection into two frames
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

//general settings
require_once '../config.php';
set_include_path(PEAR_PATH . PATH_SEPARATOR . get_include_path());

//main include file - performs all general application setup
require_once(INCLUDE_PATH . '/page_startup.php');

$dest = $_GET['dest'];
if(0 < strlen($dest)){
    $dest = (base64_decode($dest));
    if(false !== strpos($dest, 'frames.php')){
        $dest = 'home.php';
    } else {
        //block some XSS (possible?) possibilities
        $dest = urldecode($dest);
        $dest = strtr($dest, "\r\n<>();", '       ');
    }
} else {
    $dest = 'home.php';
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Active Agenda | <?php echo SITE_NAME; ?></title>
    <script language="javascript">
        var scriptPath="3rdparty/menuG5/";
        var menuTimer=300;
        var showMessage=1;
        var inheritStyle=1;
    </script>
    <script language="javascript" src="3rdparty/menuG5/menuG5LoaderFSX.js"></script>
</head>
<frameset rows="50, *" bordercolor="#2461aa" framespacing="0" frameborder="0" border="0">
    <frame name="nav" src="navigation.php" scrolling="no">
    <frame name="main" src="<?php echo $dest; ?>">
</frameset>
</html>