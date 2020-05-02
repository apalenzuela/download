<?php

//--------------------------------------------------------------------
// App Namespace
//--------------------------------------------------------------------
// This defines the default Namespace that is used throughout
// CodeIgniter to refer to the Application directory. Change
// this constant to change the namespace that all application
// classes should use.
//
// NOTE: changing this will require manually modifying the
// existing namespaces of App\* namespaced-classes.
//
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
|--------------------------------------------------------------------------
| Composer Path
|--------------------------------------------------------------------------
|
| The path that Composer's autoload file is expected to live. By default,
| the vendor folder is in the Root directory, but you can customize that here.
*/
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
|---------------------------------------------------------------------------
| Define URL to Micro-Services
|---------------------------------------------------------------------------
|
| defining the list of URLs pointing to the Micro-Services on the farm
|
*/
defined('URL_TOKEN')      || define('URL_TOKEN',      'http://users2.alainpalenzuela.com/token');
defined('URL_MEMBERS')    || define('URL_MEMBERS',    'http://users2.alainpalenzuela.com/members');
defined('URL_BOOKS')      || define('URL_BOOKS',      'http://search2.alainpalenzuela.com/books');
defined('URL_DOWNLOADS')  || define('URL_DOWNLOADS',  'http://download2.alainpalenzuela.com/books');
defined('URL_DOWN_STATS') || define('URL_DOWN_STATS', 'http://download2.alainpalenzuela.com/stats');
defined('URL_IMAGES')     || define('URL_IMAGES',     'http://images2.alainpalenzuela.com/book');
defined('URL_STATS')      || define('URL_STATS',      'http://stats.alainpalenzuela.com/download/');

defined('BOOKS_PATH')     || define('BOOKS_PATH',     '/var/www/html/download/html/all_books/');
defined('IMAGES_PATH')    || define('IMAGES_PATH',    '/var/www/html/all_images/');
defined('GOOGLE_API')     || define('GOOGLE_API',     'https://www.googleapis.com/books/v1/volumes?q=isbn:');


defined('SRCH_USER')      || define('SRCH_USER',  'udownload@alainpalenzuela.com');
defined('SRCH_PSWD')      || define('SRCH_PSWD',  'ThaureauxDown');

/*
|---------------------------------------------------------------------------
| Define HTTP Methods
|---------------------------------------------------------------------------
|
| defining all HTTP methods for the lack of definition on PHP
|
*/
defined('HTTP_GET')         || define('HTTP_GET',           'GET');
defined('HTTP_POST')        || define('HTTP_POST',          'POST');
defined('HTTP_PUT')         || define('HTTP_PUT',           'PUT');
defined('HTTP_PATCH')       || define('HTTP_PATCH',         'PATCH');
defined('HTTP_DELETE')      || define('HTTP_DELETE',        'DELETE');
defined('HTTP_HEAD')        || define('HTTP_HEAD',          'HEAD');
defined('HTTP_OPTIONS')     || define('HTTP_OPTIONS',       'OPTIONS');

/*
|--------------------------------------------------------------------------
| Constants for USER status
|--------------------------------------------------------------------------
|
| This constants represent the status for a user
|
*/
defined('ST_ACTIVE')        || define('ST_ACTIVE',	1);
defined('ST_INACTIVE')      || define('ST_INACTIVE',	0);
defined('ST_CLOSE')         || define('ST_CLOSE',	2);
defined('ST_DELETE')        || define('ST_DELETE',	3);

/*
|--------------------------------------------------------------------------
| Definition of the Type of Items
|--------------------------------------------------------------------------
|
| Type of items we are going to handle on the site
|
*/
defined('TYPE_BOOK')	OR define('TYPE_BOOK',	'book');
defined('TYPE_SONG')	OR define('TYPE_SONG',	'song');
defined('TYPE_PLUG')	OR define('TYPE_PLUG',	'plug');

/*
|--------------------------------------------------------------------------
| Constants for PASSWORD encryption
|--------------------------------------------------------------------------
|
| will define the ways that the password could be encrypted
*/
defined('PSW_MD5')          || define('PSW_MD5',        'md5');
defined('PSW_SHA1')         || define('PSW_SHA1',       'sha1');
defined('PSW_SHA256')       || define('PSW_SHA256',     'sha256');
defined('PSW_SHA512')       || define('PSW_SHA512',     'sha512');

/*
|--------------------------------------------------------------------------
| Definition of the Internal Messaging Structure
|--------------------------------------------------------------------------
|
| Internal messaging structure for handle results
|
*/
defined('EX_CODE')          || define('EX_CODE', 	'code'); 
defined('EX_MSG')           || define('EX_MSG', 	'message');

defined('MYSQL_DATE_FORMAT')|| define('MYSQL_DATE_FORMAT',  'Y-m-d H:i:s');
defined('DATE_PRETTY')      || define('DATE_PRETTY',        'M d');

/*                              
|--------------------------------------------------------------------------
| Constants for PARAMETERS     
|--------------------------------------------------------------------------
|                               
|   This is the definition all parameters 
|                               
*/
defined('PRM_TOKEN')            || define('PRM_TOKEN',      'token');
defined('PRM_OFFSET')           || define('PRM_OFFSET',     'offset');
defined('PRM_PAGESIZE')         || define('PRM_PAGESIZE',   'pagesize');
defined('PRM_STATUS')           || define('PRM_STATUS',     'status');
defined('PRM_TERMS')            || define('PRM_TERMS',      'terms');
defined('PRM_ROWS')             || define('PRM_ROWS',       'rows');
defined('PRM_TOTAL')            || define('PRM_TOTAL',      'total');
defined('PRM_PUBLISHER')        || define('PRM_PUBLISHER',  'publisher');
defined('PRM_AUTHORS')          || define('PRM_AUTHORS',    'authors');
defined('PRM_TITLE')            || define('PRM_TITLE',      'title');
defined('PRM_CATEGORY')         || define('PRM_CATEGORY',   'category');
defined('PRM_LANGUAGE')         || define('PRM_LANGUAGE',   'language');
defined('PRM_THUMBNAIL')        || define('PRM_THUMBNAIL',  'thumbnail');

/*                              
|--------------------------------------------------------------------------
| Definitions of FIELDS for ITEMS table     
|--------------------------------------------------------------------------
|                               
|   Field's definition
|                               
*/
defined('FLD_ID')               || define('FLD_ID',             'id');
defined('FLD_USER_ID')          || define('FLD_USER_ID',        'user_id');
defined('FLD_ITEM_ID')          || define('FLD_ITEM_ID',        'item_id');
defined('FLD_FILENAME')         || define('FLD_FILENAME',       'filename');
defined('FLD_TYPE')             || define('FLD_TYPE',           'type');
defined('FLD_STATUS')           || define('FLD_STATUS',         'status');
defined('FLD_CREATED_AT')       || define('FLD_CREATED_AT',     'created_at');
defined('FLD_CREATED_BY')       || define('FLD_CREATED_BY',     'created_by');

defined('FLD_EVENT')            || define('FLD_EVENT',          'event');
defined('FLD_ITEMS')            || define('FLD_ITEMS',          'items');

defined('FLD_DELAY')            || define('FLD_DELAY',          'delay');
defined('FLD_START_BYTE')       || define('FLD_START_BYTE',     'start_byte');
defined('FLD_BYTE_COUNT')       || define('FLD_BYTE_COUNT',     'byte_count');
defined('FLD_FILE_SIZE')        || define('FLD_FILE_SIZE',      'file_size');

defined('FLD_ISBN')             || define('FLD_ISBN',           'isbn');
defined('FLD_TITLE')            || define('FLD_TITLE',          'title');
defined('FLD_AUTHORS')          || define('FLD_AUTHORS',        'authors');
defined('FLD_DESCRIPTION')      || define('FLD_DESCRIPTION',    'description');
defined('FLD_PUBLISHER')        || define('FLD_PUBLISHER',      'publisher');
defined('FLD_PUBLISHED_DATE')   || define('FLD_PUBLISHED_DATE', 'published_date');
defined('FLD_IDENTIFIERS')      || define('FLD_IDENTIFIERS',    'identifiers');
defined('FLD_PAGE_COUNT')       || define('FLD_PAGE_COUNT',     'page_count');
defined('FLD_CATEGORIES')       || define('FLD_CATEGORIES',     'categories');
defined('FLD_LANGUAGE')         || define('FLD_LANGUAGE',       'language');
defined('FLD_THUMBNAIL')        || define('FLD_THUMBNAIL',      'thumbnail');
defined('FLD_THUMBNAIL_SMALL')  || define('FLD_THUMBNAIL_SMALL','thumbnail_small');
defined('FLD_TEXT_SNIPPED')     || define('FLD_TEXT_SNIPPED',   'text_snippet');
defined('FLD_MODIFIED_AT')      || define('FLD_MODIFIED_AT',    'modified_at');
defined('FLD_MODIFIED_BY')      || define('FLD_MODIFIED_BY',    'modified_by');


/*                              
|--------------------------------------------------------------------------
| Definitions of Events 
|--------------------------------------------------------------------------
|                               
|   Definition of events for transactions table
|                               
*/
defined('EV_DOWN_VALID')        || define('EV_DOWN_VALID',  'event_valid');
defined('EV_DOWN_ERROR')        || define('EV_DOWN_ERROR',  'event_error');
defined('EV_DOWN_DELETE')       || define('EV_DOWN_DELETE', 'event_delete');
defined('EV_DOWN_EXIST')        || define('EV_DOWN_EXIST',  'event_exist');
defined('EV_DOWN_DOWN')         || define('EV_DOWN_DOWN',   'event_download');
defined('EV_DOWN_STATS')        || define('EV_DOWN_STATS',  'event_stats');
defined('EV_STATS_ERROR')       || define('EV_STATS_ERROR', 'event_error');


/*                              
|--------------------------------------------------------------------------
| Definitions of FORMATs 
|--------------------------------------------------------------------------
|                               
|   Definition of all formats for output
|                               
*/
defined('FRMT_JSON')            || define('FRMT_JSON',      'json');
defined('FRMT_XML')             || define('FRMT_XML',       'xml');
defined('FRMT_PHP')             || define('FRMT_PHP',       'php');
defined('FRMT_HTML')            || define('FRMT_HTML',      'html');
defined('FRMT_CSV')             || define('FRMT_CSV',       'csv');

defined('FRMT_DATE')            || define('FRMT_DATE',      'Y-m-d H:i:s');

/*                              
|--------------------------------------------------------------------------
| Definitions for Thumbnails 
|--------------------------------------------------------------------------
|                               
|   Definition of thumbnails constants
|                               
*/
defined('IMG_REG_X')            || define('IMG_REG_X',      230);
defined('IMG_REG_Y')            || define('IMG_REG_Y',      320);
defined('IMG_SML_X')            || define('IMG_SML_X',      100);
defined('IMG_SML_Y')            || define('IMG_SML_Y',      139);
defined('IMG_FRMT')             || define('IMG_FRMT',       'png');

/*
|---------------------------------------------------------------------------
| Definitions of Privileges levels
|---------------------------------------------------------------------------
|
| All definition for Privilege level
|
*/
defined('USR_SUPER')        || define('USR_SUPER',      100);

defined('USR_ADMIN')        || define('USR_ADMIN',      10);
defined('USR_LIST')         || define('USR_LIST',       11);
defined('USR_CREATE')       || define('USR_CREATE',     12);
defined('USR_EDIT')         || define('USR_EDIT',       13);
defined('USR_DELETE')       || define('USR_DELETE',     14);
defined('USR_SELF')         || define('USR_SELF',       15);

defined('BK_ADMIN')         || define('BK_ADMIN',       20);
defined('BK_LIST')          || define('BK_LIST',        21);
defined('BK_CREATE')        || define('BK_CREATE',      22);
defined('BK_EDIT')          || define('BK_EDIT',        23);
defined('BK_DELETE')        || define('BK_DELETE',      24);

defined('MM_ADMIN')         || define('MM_ADMIN',       30);
defined('MM_LIST')          || define('MM_LIST',        31);
defined('MM_CREATE')        || define('MM_CREATE',      32);
defined('MM_EDIT')          || define('MM_EDIT',        33);
defined('MM_DELETE')        || define('MM_DELETE',      34);

defined('PG_ADMIN')         || define('PG_ADMIN',       40);
defined('PG_LIST')          || define('PG_LIST',        41);
defined('PG_CREATE')        || define('PG_CREATE',      42);
defined('PG_EDIT')          || define('PG_EDIT',        43);
defined('PG_DELETE')        || define('PG_DELETE',      44);

/*
|--------------------------------------------------------------------------
| Constants definition for DEFAULT values
|--------------------------------------------------------------------------
|
| definitions of the defauls values passed by the client of the API
|
*/
defined('DFLT_OFFSET')   || define('DFLT_OFFSET',	0);
defined('DFLT_PAGESIZE') || define('DFLT_PAGESIZE',	15);
defined('DFLT_FORMAT')   || define('DFLT_FORMAT',	FRMT_JSON);
defined('DFLT_STATUS')   || define('DFLT_STATUS',	ST_ACTIVE);


/*
|--------------------------------------------------------------------------
| Definition of Error Codes
|--------------------------------------------------------------------------
|
|
|
*/
defined('CODE_0')  || define('CODE_0',	0); 
defined('CODE_1')  || define('CODE_1',	1); 
defined('CODE_2')  || define('CODE_2',	2); 
defined('CODE_3')  || define('CODE_3',	3); 
defined('CODE_4')  || define('CODE_4',	4); 
defined('CODE_5')  || define('CODE_5',	5); 
defined('CODE_6')  || define('CODE_6',	6); 
defined('CODE_7')  || define('CODE_7',	7); 
defined('CODE_8')  || define('CODE_8',	8); 
defined('CODE_9')  || define('CODE_9',	9); 
defined('CODE_10') || define('CODE_10',	10); 

defined('CODE_10') || define('CODE_10', 10);
defined('CODE_11') || define('CODE_11', 11);
defined('CODE_12') || define('CODE_12', 12);
defined('CODE_13') || define('CODE_13', 13);
defined('CODE_14') || define('CODE_14', 14);
defined('CODE_15') || define('CODE_15', 15);
defined('CODE_16') || define('CODE_16', 16);
defined('CODE_17') || define('CODE_17', 17);
defined('CODE_18') || define('CODE_18', 18);
defined('CODE_19') || define('CODE_19', 19);
defined('CODE_20') || define('CODE_20', 20);

defined('CODE_21') || define('CODE_21', 21);
defined('CODE_22') || define('CODE_22', 22);
defined('CODE_23') || define('CODE_23', 23);
defined('CODE_24') || define('CODE_24', 24);
defined('CODE_25') || define('CODE_25', 25);
defined('CODE_26') || define('CODE_26', 26);
defined('CODE_27') || define('CODE_27', 27);
defined('CODE_28') || define('CODE_28', 28);
defined('CODE_29') || define('CODE_29', 29);
defined('CODE_30') || define('CODE_30', 30);

defined('CODE_31') || define('CODE_31', 31);
defined('CODE_32') || define('CODE_32', 32);
defined('CODE_33') || define('CODE_33', 33);
defined('CODE_34') || define('CODE_34', 34);
defined('CODE_35') || define('CODE_35', 35);
defined('CODE_36') || define('CODE_36', 36);
defined('CODE_37') || define('CODE_37', 37);
defined('CODE_38') || define('CODE_38', 38);
defined('CODE_39') || define('CODE_39', 39);
defined('CODE_40') || define('CODE_40', 40);

defined('CODE_41') || define('CODE_41', 41);
defined('CODE_42') || define('CODE_42', 42);
defined('CODE_43') || define('CODE_43', 43);
defined('CODE_44') || define('CODE_44', 44);
defined('CODE_45') || define('CODE_45', 45);
defined('CODE_46') || define('CODE_46', 46);
defined('CODE_47') || define('CODE_47', 47);
defined('CODE_48') || define('CODE_48', 48);
defined('CODE_49') || define('CODE_49', 49);
defined('CODE_50') || define('CODE_50', 50);

/*
|--------------------------------------------------------------------------
| Definition of Messages
|--------------------------------------------------------------------------
|
| Definition of messages by range 
| - [ 0 -  0] Success
| - [ 1 - 10] Command execution problems
| - [11 - 30] Password and account
| - [31 - 40] Parameters
| - [41 - 50] Items 
*/
defined('MSG_0')  || define('MSG_0', "Successful execution of API command");

defined('MSG_1')  || define('MSG_1', "Command execution failed");
defined('MSG_2')  || define('MSG_2', "Command cannot be executed - error on submitted method");
defined('MSG_3')  || define('MSG_3', "Command cannot be executed - error in the submitted URL");
defined('MSG_4')  || define('MSG_4', "Query execution completed successfully, no result data return");
defined('MSG_5')  || define('MSG_5', "Query execution completed successfully, resuld data follows");
defined('MSG_6')  || define('MSG_6', "Login successfully completed, result data follows");
defined('MSG_7')  || define('MSG_7', "");
defined('MSG_8')  || define('MSG_8', "Unknown or internal error");
defined('MSG_9')  || define('MSG_9', "System unavailable");
defined('MSG_10') || define('MSG_10', "Reserved");

defined('MSG_11') || define('MSG_11', "Password expired"); 
defined('MSG_12') || define('MSG_12', "Required login credential not provided");
defined('MSG_13') || define('MSG_13', "Login credential has expired");
defined('MSG_14') || define('MSG_14', "Login credential garbled (cannot be decoded)");
defined('MSG_15') || define('MSG_15', "Login credential invalid");
defined('MSG_16') || define('MSG_16', "Login ID or password Invalid");
defined('MSG_17') || define('MSG_17', "Account has been suspended");
defined('MSG_18') || define('MSG_18', "Account has been closed");                                  
defined('MSG_19') || define('MSG_19', "Account does not have necessary privilege to execute request");  
defined('MSG_20') || define('MSG_20', "Account does not have adequate privilege to download requested item");

defined('MSG_21') || define('MSG_21', "Offset and size invalid, cannot complete requested download");
defined('MSG_22') || define('MSG_22', "The user has used the maximum of donwload allowable");
defined('MSG_23') || define('MSG_23', "Download can not be completed now because of heavy demand");
defined('MSG_24') || define('MSG_24', "Account exist already");
defined('MSG_25') || define('MSG_25', "");
defined('MSG_26') || define('MSG_26', "");
defined('MSG_27') || define('MSG_27', "");
defined('MSG_28') || define('MSG_28', "");
defined('MSG_29') || define('MSG_29', "");
defined('MSG_30') || define('MSG_30', "");   

defined('MSG_31') || define('MSG_31', "Required parameters missing");                                  
defined('MSG_32') || define('MSG_32', "Optional parameters not supported in this command");                           
defined('MSG_33') || define('MSG_33', "Parameters values outside of valid range");          
defined('MSG_34') || define('MSG_34', "API version incompatibility");                     
defined('MSG_35') || define('MSG_35', "Request language not supported");                      
defined('MSG_36') || define('MSG_36', "Unknown command");                    
defined('MSG_37') || define('MSG_37', "Block cannot be parsed");                    
defined('MSG_38') || define('MSG_38', "Missing required element");                              
defined('MSG_39') || define('MSG_39', "Requested method invalid");                          
defined('MSG_40') || define('MSG_40', "");   

defined('MSG_41') || define('MSG_41', "Requested item does not exist");
defined('MSG_42') || define('MSG_42', "Invalid item ID format");
defined('MSG_43') || define('MSG_43', "Item cannot be created");
defined('MSG_44') || define('MSG_44', "Item exist already");
defined('MSG_45') || define('MSG_45', "Item cannot be updated");
defined('MSG_46') || define('MSG_46', "Item cannot be deleted");
defined('MSG_47') || define('MSG_47', "");
defined('MSG_48') || define('MSG_48', "");
defined('MSG_49') || define('MSG_49', "");
defined('MSG_50') || define('MSG_50', "");

/*
|--------------------------------------------------------------------------
| Timing Constants
|--------------------------------------------------------------------------
|
| Provide simple ways to work with the myriad of PHP functions that
| require information to be in seconds.
*/
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2592000);
defined('YEAR')   || define('YEAR', 31536000);
defined('DECADE') || define('DECADE', 315360000);

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code