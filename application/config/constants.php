<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESCTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

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
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code 
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code 


define('SITE_TITLE', 'Follup');


define('ADMIN_LIMIT', 10);



define('CATEGORY_PATH', 'resources/images/category/');
define('INFO_EMAIL', 'info@pview.com');

define('SMTP_HOST','smtp.gmail.com');
define('SMTP_USER', 'parmar.jay350@gmail.com');
define('SMTP_PASS', 'parmarjssss'); 
define('SMTP_PORT', '25'); 
define('PROTOCOL', 'smtp');
define('MAILPATH', '/usr/sbin/sendmail');
define('MAILTYPE', 'html');
define('CHARSET', 'utf-8');
define('WORD_WRAP', TRUE);
define('SMTP_TIMEOUT',300);
define('enc_value','1345');
define('FROM_NAME','Pview');
define('WEBSITE_NAME','Pview');

//define('GOOGLE_PLACE_KEY', '');
//define('GOOGLE_PLACE_KEY', 'AIzaSyCfIENl_GkKAeSI715vOLYnMwhPXs88td0');
define('GOOGLE_PLACE_KEY', 'AIzaSyB_jfNvLIu7C6yw_LdYzWRKOf4lvDiMWXg');  //client API KE

define('DEV_MODE',TRUE );



//OLD

define('WASHCARE_PATH', 'resources/images/wash_care/');
define('BUTTON_PATH', 'resources/images/button/');
define('YARN_PATH', 'resources/images/yarn/');
define('WEAVE_PATH', 'resources/images/weave/');

define('BANNER_PATH', 'resources/images/banner/');
define('CLIENT_LOGO_PATH', 'resources/images/client/');
define('TESTIMONIAL_PATH', 'resources/images/testimonial/');

//keys

define('USER_PATH', 'resources/images/user_profile/');

define('APP_KEY','OvfK6C4nX2BG4EGFcpdEdgbbFq7uOIct');
// define('GOOGLE_API_KEY','AIzaSyAO2J4teAnyP8v6gPXlS4nsHF0bQTUvwSo');
define('GOOGLE_API_KEY','AIzaSyCRUl1GmjWWWG5BKl9nY3hi6-jH98TSxcI');

define('INVALID_STRIPE_PARAMS', 'Invalid stripe parameters received. Please try again.');
define('AUTHENTICATION_STRIPE_FAILED', 'Authentication with stripe failed. Please try again.');
define('NETWORK_STRIPE_FAILED', 'Network communication with Stripe failed.');
define('STRIPE_FAILED', 'Stripe payment failed. Please try again.');


// define('SK_KEY', 'sk_test_7HCatjECmmcCaPL10wPWCY6t'); // rkhan
//define('SK_KEY', 'sk_test_hUsur9xGi1zRPBvpx4H4fvYn');






// define('FROM_NUMBER', '+12029328815');
// define('SK_KEY', 'AC409286e52a76834716461926d801d45b');
// define('TOKEN', '218719ac50bc02a5207fa5c6e16d511b');


define('CALL_KEY', '60714e8e12b34e28ade78e824ac7302c');
define('FROM_NUMBER', '+18042232541');

define('SK_KEY', 'ACa40dd4a24594803a7bc189a97a9beb40');
define('TOKEN', 'ebce7440bc7eed010bac75758286e920');

