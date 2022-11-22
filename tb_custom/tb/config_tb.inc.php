<?php 

/**
 * Configuraciones particulares de un cliente
 */

// more than 8MB memory needed for graphics
// memory limit default value = 64M
ini_set('memory_limit','512M');

// show or hide calendar, world clock, calculator, chat and CKEditor 
// Do NOT remove the quotes if you set these to false! 
$CALENDAR_DISPLAY = 'true';
$USE_RTE = 'true';

// helpdesk support email id and support name (Example: 'support@vtiger.com' and 'vtiger support')
$HELPDESK_SUPPORT_EMAIL_ID = 'tomeu.95@gmail.com';
$HELPDESK_SUPPORT_NAME = 'your-support name';
$HELPDESK_SUPPORT_EMAIL_REPLY_ID = $HELPDESK_SUPPORT_EMAIL_ID;

/* database configuration
      db_server
      db_port
      db_hostname
      db_username
      db_password
      db_name
*/

$dbconfig['db_server'] = 'localhost';
$dbconfig['db_port'] = ':3306';
$dbconfig['db_username'] = 'root';
$dbconfig['db_password'] = '';
$dbconfig['db_name'] = 'vtigercrm';
$dbconfig['db_type'] = 'mysqli';
$dbconfig['db_status'] = 'true';

// TODO: test if port is empty
// TODO: set db_hostname dependending on db_type
$dbconfig['db_hostname'] = $dbconfig['db_server'].$dbconfig['db_port'];

// log_sql default value = false
$dbconfig['log_sql'] = false;

// persistent default value = true
$dbconfigoption['persistent'] = true;

// autofree default value = false
$dbconfigoption['autofree'] = false;

// debug default value = 0
$dbconfigoption['debug'] = 0;

// seqname_format default value = '%s_seq'
$dbconfigoption['seqname_format'] = '%s_seq';

// portability default value = 0
$dbconfigoption['portability'] = 0;

// ssl default value = false
$dbconfigoption['ssl'] = false;

$host_name = $dbconfig['db_hostname'];

$site_URL = 'http://localhost/vtigercrm/';

// url for customer portal (Example: http://vtiger.com/portal)
$PORTAL_URL = $site_URL.'/customerportal';
// root directory path
$root_directory = 'C:\xampp\htdocs\vtigercrm/';

// cache direcory path
$cache_dir = 'cache/';

// tmp_dir default value prepended by cache_dir = images/
$tmp_dir = 'cache/images/';

// import_dir default value prepended by cache_dir = import/
$import_dir = 'cache/import/';

// upload_dir default value prepended by cache_dir = upload/
$upload_dir = 'cache/upload/';

// maximum file size for uploaded files in bytes also used when uploading import files
// upload_maxsize default value = 3000000
$upload_maxsize = 3145728;//3MB

// flag to allow export functionality
// 'all' to allow anyone to use exports 
// 'admin' to only allow admins to export 
// 'none' to block exports completely 
// allow_exports default value = all
$allow_exports = 'all';

// files with one of these extensions will have '.txt' appended to their filename on upload
// upload_badext default value = php, php3, php4, php5, pl, cgi, py, asp, cfm, js, vbs, html, htm
$upload_badext = array('php', 'php3', 'php4', 'php5', 'pl', 'cgi', 'py', 'asp', 'cfm', 'js', 'vbs', 'html', 'htm', 'exe', 'bin', 'bat', 'sh', 'dll', 'phps', 'phtml', 'xhtml', 'rb', 'msi', 'jsp', 'shtml', 'sth', 'shtm');

// list_max_entries_per_page default value = 20
$list_max_entries_per_page = '20';

// history_max_viewed default value = 5
$history_max_viewed = '5';

// default_action default value = index
$default_action = 'index';

// set default theme
// default_theme default value = blue
$default_theme = 'softed';

// default text that is placed initially in the login form for user name
// no default_user_name default value
$default_user_name = '';

// default text that is placed initially in the login form for password
// no default_password default value
$default_password = '';

// create user with default username and password
// create_default_user default value = false
$create_default_user = false;

//Master currency name
$currency_name = 'Euro';

// default charset
// default charset default value = 'UTF-8' or 'ISO-8859-1'
$default_charset = 'UTF-8';

// default language
// default_language default value = en_us
$default_language = 'en_us';

//Option to hide empty home blocks if no entries.
$display_empty_home_blocks = false;

//Disable Stat Tracking of vtiger CRM instance
$disable_stats_tracking = false;

// Generating Unique Application Key
$application_unique_key = '2c25c98bf6080ed5eb063f5d31140e85';

// trim descriptions, titles in listviews to this value
$listview_max_textlength = 40;

// Maximum time limit for PHP script execution (in seconds)
$php_max_execution_time = 0;

// Set the default timezone as per your preference
$default_timezone = 'UTC';

/** If timezone is configured, try to set it */
if(isset($default_timezone) && function_exists('date_default_timezone_set')) {
	@date_default_timezone_set($default_timezone);
}


$g_prefixes_mobiles = array(
    // España
    '+34' => array('6', '7'),
    // Francia
    '+33' => array('6', '7'),
    // Italia
    '+39' => array('3'),
    // Inglaterra
    '+44' => array('7'),
    // Alemania
    '+49' => array('15', '16', '17', '70'),
    // Polonia
    '+48' => array('5', '6', '7', '8'),
    // Rumania
    '+40' => array('7'),
    // Países Bajos
    '+31' => array('6', '9'),   
    // Grecia
    '+30' => array('6'),
    // Bélgica
    '+32' => array('4'),
    // República Checa
    '+420' => array('6', '7', '9'),
    // Portugal
    '+351' => array('6', '9'),
    // Suecia
    '+46' => array('2', '3', '5', '6', '7'),
    // Hungría
    '+36' => array('2', '3', '7'),
    // Suiza
    '+41' => array('7', '8'),
    // Bulgaria
    '+359' => array('8', '9'),
    // Serbia
    '+381' => array('3', '6'),
    // Dinamarca
    '+45' => array('2', '3', '4', '5', '6', '71', '81', '9'),
    // Finlandia
    '+358' => array('4', '50'),
    // Eslovaquia
    '+421' => array('9'),
    // Noruega
    '+47' => array('4', '5', '9'),
    // Irlanda
    '+353' => array('8'),
    // Croacia
    '+385' => array('9'),
    // Bosnia y Herzegovina
    '+387' => array('6'),
    // Moldavia
    '+373' => array('6', '7'),
    // Albania
    '+355' => array('6'),
    // Lituania
    '+370' => array('6'),
    // Macedonia
    '+389' => array('7'),
    // Eslovenia
    '+386' => array('3', '4', '51', '64', '68', '70', '83', '9'),
    // Letonia
    '+371' => array('2', '65', '68', '78', '80', '90' ),
    // Estonia
    '+372' => array('5', '8'),
    // Montenegro
    '+382' => array('6'),
    // Luxemburgo
    '+352' => array('60', '62', '66', '67', '69'),
    // Malta
    '+356' => array('77', '79', '96', '98', '99'),
    // Islandia
    '+354' => array('38', '6', '77', '78', '8'),
    // Andorra
    '+376' => array('3', '4', '6'),
    // Liechenstein
    '+423' => array('6', '7'),
    // Mónaco
    '+377' => array('4', '6'),
    // San Marino
    '+378' => array('66'),
    
    
    
    
    
    
    // USA
    '+1' => array('1', '2', '3', '4', '5', '6', '7', '8', '9'),
    // South Africa
    '+27' => array('6', '7', '8'),
    // United Arab Emirates
    '+971' => array('5'),
    // Méjico
    '+52' => array('2', '3', '4', '5', '6', '7', '8', '9'),
    // Colombia
    '+57' => array('3'),
    // Argentina
    '+54' => array('9'),
    // Bolivia
    '+591' => array('6'),
    // Chile
    '+56' => array('9'),
    // Canadá
    '+1' => array('1', '2', '3', '4', '5', '6', '7', '8', '9'),
    // Costa Rica
    '+506' => array('5', '6', '7', '8'),
    // India
    '+91' => array('7', '8', '9'),
    // Ecuador
    '+593' => array('9'),
    // El salvador
    '+503' => array('60', '61', '62', '63', '7'),
    // Guatemala
    '+502' => array('22', '23', '24', '30', '31', '32', '33', '34', '4', '5', '66', '77', '78', '79'),
    // honduras
    '+504' => array('31', '32', '72', '74', '8', '9'),
    // Jamaica
    '+1' => array('2', '3', '4', '5', '6', '7', '8', '9'),
    // Nicaragua
    '+505' => array('5', '7', '8'),
    // Panama
    '+507' => array('6'),
    // Paraguay
    '+595' => array('9'),
    // Perú
    '+51' => array('9'),
    // República Dominicana
    '+1' => array('2', '3', '4', '5', '6', '7', '8', '9'),
    // Uruguay
    '+598' => array('9'),
    // Venezuela
    '+58' => array('41', '42'),
    
);

//campos en users
$v_senderName = "";
$v_newsletterSenderEmail = "";


$a_caracteres_especiales_de = array (
    'ü'=>'ue',
    'Ü'=>'UE',
    'ö'=>'oe',
    'Ö'=>'OE',
    'ï'=>'ie',
    'Ï'=>'IE',
    'ë'=>'ee',
    'Ë'=>'EE',
    'ä'=>'ae',
    'Ä'=>'AA',
);

$a_caracteres_especiales = array(
    'á'=>'a',
    'à'=>'a',
    'â'=>'a',
    'ã'=>'a',
    'ª'=>'a',
    'ä'=>'a',
    'Á'=>'A',
    'À'=>'A',
    'Â'=>'A',
    'Ã'=>'A',
    'Ä'=>'A',
    'é'=>'e',
    'è'=>'e',
    'ê'=>'e',
    'ë'=>'e',
    'É'=>'E',
    'È'=>'E',
    'Ê'=>'E',
    'Ë'=>'E',
    'í'=>'i',
    'ì'=>'i',
    'î'=>'i',
    'ï'=>'i',
    'Í'=>'I',
    'Ì'=>'I',
    'Î'=>'I',
    'Ï'=>'I',
    'ó'=>'o',
    'ò'=>'o',
    'ô'=>'o',
    'õ'=>'o',
    'º'=>'o',
    'ö'=>'o',
    'Ó'=>'O',
    'Ò'=>'O',
    'Ô'=>'O',
    'Õ'=>'O',
    'Ö'=>'O',
    'ú'=>'u',
    'ù'=>'u',
    'û'=>'u',
    'ü'=>'u',
    'Ú'=>'U',
    'Ù'=>'U',
    'Û'=>'U',
    'Ü'=>'U',
    'ß'=>'ss',
    'ç'=>'c',
    'Ç'=>'C',
    'ñ'=>'n',
    'Ñ'=>'n',
    'Ÿ'=>'y',
    '|'=>'',
    ';'=>'',
    ':'=>'',
    '.'=>'',
    ','=>'',
    '('=>'',
    ')'=>'',
    '¨'=>'',
    '+'=>'',
    '¡'=>'',
    '¿'=>'',
    '?'=>'',
    '!'=>'',
    '%'=>'',
    '"'=>'',
    '&ndash;'=>'-',
    '&ldquo;'=>'-',
    '&rdquo;'=>'-',
    '&lsquo;'=>'-',
    '&rsquo;'=>'-',
    '&#039;'=>'-',
    ' '=>'-',
    "'"=>'-',
    '´'=>'-',
    '`'=>'-',
    '’'=>'-',
    '\\' =>'',
    '&amp;'=>'',
    '&'=>'',
    '/'=>'-',
    '--'=>'-',
);
