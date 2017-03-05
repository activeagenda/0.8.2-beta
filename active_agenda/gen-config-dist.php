<?php
/**
 * Example configurations for generating modules with s2a. 
 *
 * Copy and modify this file to fit your specific server/site settings. Save it under
 * the name 'gen-config.php'.
 *
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
 * @author         Mattias Thorslund <mthorslund@activeagenda.net>
 * @copyright      2003-2007 Active Agenda Inc.
 * @license        http://www.activeagenda.net/license
 **/


/**
 *  Requires the main configurations
 */
require_once 'config.php';


/**
 * MySQL credentials for a more privileged MySQL user than the normal one specified in config.php
 *
 * Generating modules requires more MySQL privileges than the running application needs.
 * Specify a MySQL user that has "all" privileges to the database here. When installing,
 * this user can be created by the database install script.
 */
DEFINE('GEN_DB_USER', 's2aroot');
DEFINE('GEN_DB_PASS', 's2apassword');
DEFINE('GEN_DB_DSN', 'mysql://'.GEN_DB_USER.':'.GEN_DB_PASS.'@'. DB_HOST.'/'.DB_NAME);

/**
 *  These settings control the output of the module generating process
 */
DEFINE('DEBUG_LOG_FORMAT', 'win'); //either 'win' (default) or 'unix'
DEFINE('DEBUG_STYLE_BACKTRACE', false);
DEFINE('DEBUG_STYLE_INDENT', true);
DEFINE('DEBUG_STYLE_PRINT_LEVEL', false);
?>
