<?php
/**
 * Example configurations for S2A/Active Agenda
 *
 * Copy and modify this file to fit your specific server/site settings. Save it under
 * the name 'config.php'.
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
 *  The title of this Active Agenda site.
 *
 *  This is what's displayed on the login/logout screens, and in the title bar of the browser.
 */
DEFINE('SITE_NAME', 'ACME Active Agenda');


/**
 *  A short acronym or title for the site.
 *
 *  This will be used to prepend subject lines in email messages. Alpha-numeric 
 *  characters only. No spaces, commas, periods, etc.
 */
DEFINE('SITE_SHORTNAME', 'AcmeAA');


/**
 *  Name of the session ID cookie
 *
 *  This will be used for the session cookie name. Alpha-numeric
 *  characters only. No spaces, commas, periods, etc.
 */
DEFINE('SITE_SESSIONNAME', SITE_SHORTNAME . 'SESSID');


/**
 *  The root installation folder.
 *
 *  This should be located *outside* the web root. It should not be accessible from the
 *  web.  It should (theoretically) be possible for mutiple sites to share the s2a, lib and 
 *  pear files, since the more site-specific files and templates are reserved for the
 *  APP_FOLDER location.  On single-site Linux servers, we've used /var/www/s2a.
 */
DEFINE('S2A_FOLDER', 'c:/s2a');


/**
 *  Classes used when generating and running Active Agenda
 */
DEFINE('CLASSES_PATH', S2A_FOLDER . '/lib/classes');


/**
 *  Utility functions used when generating and running Active Agenda
 */
DEFINE('INCLUDE_PATH', S2A_FOLDER . '/lib/includes');


/**
 *  Third-party utility classes from the PHP PEAR project
 */
DEFINE('PEAR_PATH', S2A_FOLDER . '/pear');


/**
 *  Templates for files generated from the XML definitions.
 */
DEFINE('MODELS_PATH', S2A_FOLDER . "/lib/templates");


/**
 *  Site-specific files
 *
 *  This folder contains folders that contain more site specific data, such as the themes, the
 *  web folder, the xml defintions folder, the generated fiels and the upload folder. This
 *  folder should also be outside the site's web root. This configuration file should be
 *  located here.
 */
DEFINE('APP_FOLDER', S2A_FOLDER . '/active_agenda');  //on Linux/Unix, maybe /var/www/s2a/active_agenda



/**
 *  Web root folder
 *
 *  Configured your web server settings so that HTTP requests to the the site are directed to
 *  files in this folder. This could be done easily with a "soft link" to this folder from the
 *  server's pre-configured web root, or you can configure a Directory and Alias in Apache's
 *  configuration file to point here, or you can set up the site as a virtual host...
 */
DEFINE('WEB_PATH', APP_FOLDER . '/web');


/**
 *  Third party javascript libraries: Menu, calendar, and more.
 */
DEFINE('THIRD_PARTY_PATH', APP_FOLDER . '/web/3rdparty');


/**
 *  Module include files for specific modules.
 *
 *  These includes are in this folder rather than in INCLUDE_PATH because they were thought
 *  to be more likely to be customized by end-users.
 */
DEFINE('APP_INCLUDE_PATH', APP_FOLDER . '/includes');


/**
 *  Root folder for themes
 *
 *  (Changed in 0.8.1)
 */
DEFINE('THEME_PATH', WEB_PATH . '/themes');


/**
 *  Web folder for themes
 *
 *  (New in 0.8.1)
 */
DEFINE('THEME_WEB_PATH', 'themes'); //note: no preceding "/"!


/**
 *  Point this path to the preferred default theme.
 */
DEFINE('DEFAULT_THEME', 'aa_theme');


/**
 *  Home of thousands of generated files.
 *
 *  When "generating" a module, the resulting files are saved here automatically.
 */
DEFINE('GENERATED_PATH', APP_FOLDER . '/.generated');


/**
 *  Root folder for file uploads.
 *
 *  This is one of only two locations where the web server needs write permissions. Configure
 *  file permissions so that other users on the system can't read or write here.
 */
DEFINE('UPLOAD_PATH', APP_FOLDER . '/uploads');


/**
 *  Root folder for gettext library .po files.
 *
 *  This should contain all translation files, and be accessible by the gettext utility
 */
DEFINE('LOCALE_PATH', APP_FOLDER . '/lang');


/**
 *  Location of XML module definitions.
 *
 *  Web server does not need access to this folder. These files are used only when 
 *  (re)generating a module.
 */
DEFINE('XML_PATH', APP_FOLDER . '/xml');


/**
 *  Location for PDF report forms
 */
DEFINE('PDF_PATH', APP_FOLDER . '/pdf');


/**
 *  Location for error and debug log files
 *
 *  This is the second location where the web server will need write access. Run-time
 *  error messages are appended to the errors.log file by the web server.  If the
 *  TRACE_RUNTIME option (not for production, only debugging) is enabled, the trace
 *  files will be saved here. The module generator (s2a.php and s2a-generate-module.php)
 *  also save log files here.
 */
DEFINE('GEN_LOG_PATH', APP_FOLDER . '/s2alog');


/**
 *  Locale string, used by gettext.
 *
 *  Make sure this is a valid locale string on your system: Windows may use 'english-usa'
 */
DEFINE('DEFAULT_LOCALE', 'en_US');


/**
 *  Master currency
 *
 *  This is the currency symbol that will be displayed in money fields. It will also be used
 *  when we implement multi-currency functionality.
 */
DEFINE('MASTER_CURRENCY', 'USD');


/**
 *  Seed value for enrypting user passwords in the database
 *
 *  Definitely change this when setting up the server.  Changing it when there already are 
 *  some users in the database would invalidate their passwords, which would need to be reset.
 */
DEFINE('CRYPT_SEED', 'default');


/**
 *  Database server type identifier.
 *
 *  Currently, only MySQL (v. 4.1, 5) is supported. This constant is meant to control the
 *  behavior of the SQL functionality, should the need arise to support other database
 *  servers.
 */
DEFINE('DB_TYPE', 'MySQL');


/**
 *  Database connection string
 *
 *  Change this to reflect your settings. Refer to the PEAR DB documentation here:
 *  http://pear.php.net/manual/en/package.database.db.intro-dsn.php
 *  The database installation script creates this MySQL user - change the name
 *  and password.
 *
 *  Choose the database name well. Underscores can be a source of trouble.
 */
DEFINE('DB_NAME', 'activeagenda');
DEFINE('DB_USER', 'theuser');
DEFINE('DB_PASS', 'thepassword');
DEFINE('DB_HOST', 'localhost');
DEFINE('DB_DSN',  'mysql://'.DB_USER.':'.DB_PASS.'@'.DB_HOST.'/'.DB_NAME);




/**
 *  URL to Active Agenda's global discussion forum.
 *
 *  This contains the URL to access a specific topic (there's one per forum), when combined
 *  with the unique value for the topic's ID, stored in the Module module (`mod`).
 */
DEFINE('DISCUSSION_LINK_GLOBAL', 'http://www.activeagenda.net/discussions/viewforum.php?f=');


/**
 *  URL to an organization-internal discussion forum.
 *
 *  To implement the link from the Active Agenda interface, some tweaking of the
 *  theme template files would be necessary (adding an extra icon).
 */
DEFINE('DISCUSSION_LINK_LOCAL', 'http://www.example.com/discussions/viewforum.php?f=');


/**
 *  An identifier for the type of navigation menu to use.
 *
 *  Currently, the only supported value is 'G5'
 */
DEFINE('MENU_TYPE', 'G5');


/**
 *  Whether the application should do a DNS lookup when validating email addresses
 *
 *  When saving forms in the application that include fields validated as "Email",
 *  the default behavior is to only verify the format. When this setting is true,
 *  the application will also do a DNS lookup to verify that domain name part
 *  of the address is a valid, active domain.
 */
DEFINE('VALIDATE_EMAIL_DOMAINS', false);


/**
 *  Email address used for the Return-Path header in emails.
 *
 *  Specify an email address that should receive notification in case an email
 *  sent from the application could not be delivered because the recipient's
 *  address is no longer valid, or their inbox is full, or otherwise unreachable.
 */
DEFINE('BOUNCE_EMAIL_ADDRESS', 'bounces@example.com');


/**
 *  Maximum allowed file size, in bytes.
 *
 *  Note that there is a php.ini directive as well: upload_max_filesize.
 *  The ini-file directive is the limit that is enforced. This setting
 *  is sent to the browser as an advisory only.
 */
DEFINE('UPLOAD_MAX_FILE_SIZE', 2097152); //default 2.0 MiB


/**
 *  Whether to log EVERY page access in the usrl table
 *
 *  Login/logout and permission errors are logged to the table in either case.
 */
DEFINE('USER_LOG_PAGE_ACCESS', false);


/**
 *  Whether to send error messages by email.
 *
 *  When set to true, an email notification will be sent to the address specified
 * in the EMAIL_ERROR_ADDRESS setting.
 */
DEFINE('EMAIL_ERRORS', false);


/**
 *  Email address of an administrator to be contacted in case of errors.
 */
DEFINE('EMAIL_ERROR_ADDRESS', 'errors@example.com');


/**
 *  Whether to log errors to a log file.
 *
 *  The error log file is called errors.log, located in the GEN_LOG_PATH folder.
 */
DEFINE('LOG_ERRORS', true);


/**
 *  Whether to save a debug "trace".
 *
 *  Not for production use. When set to true, this setting will save a debug
 *  output file for each request to the server where the code contains any
 *  calls to the trace() function. This is for debugging purposes only, since
 *  it causes slower performance.
 */
DEFINE('TRACE_RUNTIME', false);


/**
 *  Big brothers can choose to be emailed any time someone logs in :-)
 */
DEFINE('NOTIFY_LOGINS_EMAIL', false);


/**
 *  Email address where login noitifications should be sent if NOTIFY_LOGINS_EMAIL is enabled.
 */
DEFINE('EMAIL_LOGIN_NOTIFICATION_ADDRESS', 'logins@example.com');


/**
 *  Defines PATH_SEPARATOR depending on operating system.
 */
if(!defined(PATH_SEPARATOR)){
        //You may simplify this according to your operating system
        if(false === strpos(PHP_OS, "WIN")){
                define('PATH_SEPARATOR', ':');  //non-Windows
        } else {
                define('PATH_SEPARATOR', ';');  //Windows
        }
}

?>