<?php
ini_set('session.cookie_httponly',true);
ini_set('session.cookie_secure',true);
ini_set('session.use_only_cookies',1);

date_default_timezone_set('Asia/kolkata'); //Set timezone on 12 May, 2014 06:42 PM

ini_set('session.gc_maxlifetime', 43200);
session_set_cookie_params(time()+43200,'/','indane.co.in',true,true);
//session_set_cookie_params(time()+43200,'/','indane.co.in',true,true);
//session_set_cookie_params(0,'/','indane.co.in',true,true);
@session_start();

$currentCookieParams = session_get_cookie_params();  
$sidvalue = session_id();  
setcookie(  
    'PHPSESSID',//name  
    $sidvalue,//value  
    time()+43200,//expires at end of session  
    $currentCookieParams['path'],//path  
    $currentCookieParams['domain'],//domain  
    true, //secure  
	true
);

//define('DB_HOST','111.118.188.223');
define('DB_HOST','192.168.50.90');
//define('DB_HOST_SLAVE','111.118.188.188');
define('DB_HOST_SLAVE','192.168.50.188');

define('TP_SERVER_HOST','203.101.101.110');
define('TP_SERVER_USERNAME','root');
define('TP_SERVER_PASSWORD','indaneprod');

header("strict-transport-security: max-age=43200");
error_reporting(0);
ini_set("display_errors", 0);

function validatePostMethod($serverReferrer) {
    //echo $serverReferrer;
    $domain = strstr($serverReferrer, "indane.co.in");
    if ($domain == false)
        exit();
}

global $link;
$url = $_SERVER['REQUEST_URI'];
$findchar = strstr($url, '>');

$liteLogin = (date('H') >= 8 && date('H') < 20);
if ($findchar != false)
    exit();

$pageOpen = $_SERVER['HTTP_HOST'] . strtolower($_SERVER['PHP_SELF']);

$staticPages = array('indane.co.in/index.php', 'indane.co.in/brief.php', 'indane.co.in/vision.php', 'indane.co.in/achievements.php', 'indane.co.in/market.php', 'indane.co.in/inter-company-portability.php', 'indane.co.in/online-services.php', 'indane.co.in/5kg-ftl.php', 'indane.co.in/faq.php', 'indane.co.in/pressrelease.php', 'indane.co.in/bottling.php', 'indane.co.in/feedback.php', 'indane.co.in/our_network.php', 'indane.co.in/contact.php', 'indane.co.in/customer-care.php', 'indane.co.in/how-i-do.php', 'indane.co.in/registration_process.php', 'indane.co.in/reactivate.php', 'indane.co.in/transferconnection.php', 'indane.co.in/onlinegasbooking.php', 'indane.co.in/nonfuelproducts.php', 'indane.co.in/transfer.php', 'indane.co.in/tarrifs_main.php', 'indane.co.in/auto_gas.php', 'indane.co.in/applications.php', 'indane.co.in/consumer_education.php', 'indane.co.in/games.php', 'indane.co.in/jokes.php', 'indane.co.in/sms-world.php', 'indane.co.in/wallpapers.php', 'indane.co.in/recipe.php', 'indane.co.in/safety-tips.php', 'indane.co.in/domestic_lpg.php', 'indane.co.in/non_domestic_lpg.php', 'indane.co.in/bulk_lpg.php', 'indane.co.in/autogas.php', 'indane.co.in/oisd.php', 'indane.co.in/peso.php', 'indane.co.in/gascylrule.php', 'indane.co.in/smpvrule.php', 'indane.co.in/aadhaar-seeding.php', 'indane.co.in/brands.php', 'indane.co.in/changelink.php', 'indane.co.in/changelink.php', 'indane.co.in/about.php', 'indane.co.in/transparency/about-portal.php', 'indane.co.in/book_cylinder.php', 'indane.co.in/do-i-need-kyc.php','indane.co.in/rate_distributor_info.php', 'indane.co.in/optout_inf.php', 'indane.co.in/surrender_info.php', 'indane.co.in/png_info.php', 'indane.co.in/join-dbtl.php', 'indane.co.in/joindbtlopt1.php', 'indane.co.in/joindbtlopt2.php', 'indane.co.in/dbtl-forms.php', 'indane.co.in/aadhaarcheck.php', 'indane.co.in/findlpgid.php');

if (!in_array($pageOpen, $staticPages) || (date('H') >= 00 && date('H') <= 05)) {
    connection();
}

$website_title = "Indane Online : Consumer Login";
if($pageOpen=='indane.co.in/optout_inf.php'){
	$website_title = "Indane - Give Up LPG Subsidy";	
}

putenv("TZ=Asia/Calcutta");

# For 5 KG FTL MySQLi
define('DB_SERVER_5KGFTL', '192.168.50.90');
define('DB_USERNAME_5KGFTL', 'admin_indane');
define('DB_PASSWORD_5KGFTL', 'Sandman{49}XS');
define('DB_DATABASE_5KGFTL', 'admin_indane');

function connection() {
	//validatePostMethod($_SERVER['HTTP_REFERER']);
    $arr_user[1] = 'admin_indane';
    $arr_user[2] = 'admin_indane1';
    $arr_user[3] = 'admin_indane2';
    $arr_user[4] = 'admin_indane3';

    $arr_pass[1] = 'Sandman{49}XS';
    $arr_pass[2] = 'Blps470$';
    $arr_pass[3] = 'Blps470$';
    $arr_pass[4] = 'Blps470$';

    $rand = mt_rand(1, 4);

    global $link;

    //$hostname = "192.168.50.108";
	//$hostname = "111.118.186.215";

    $user = $arr_user[$rand];

    $pass = $arr_pass[$rand];

    $dbname = "admin_indane";

    $link = mysql_connect(DB_HOST, $user, $pass);

    if (!$link) {
        $rand = mt_rand(1, 3);
        $user = $arr_user[$rand];
        $pass = $arr_pass[$rand];
        $link = mysql_connect(DB_HOST, $user, $pass);
        //die('Could not connect: ' . mysql_error());
    }

    /* if connection not available redirect to maintenance page */
    if (!$link) {
        if (date('H') >= 00 && date('H') <= 05) {
            echo "<script>window.location='http://indane.co.in/undermaintenance.html'</script>";
            exit();
        }
    }

    $linkdb1 = mysql_select_db($dbname, $link);
	// update connection id of cronfile to monitor the execution
	if(php_sapi_name()=='cli')
	{
		if($_SERVER['PHP_SELF'] != 'master_cron_1.php')
		{
			$query = "update gb_cron_status set connection_id=connection_id() where cron_file='".$_SERVER['PHP_SELF']."'";
			if(mysql_query($query))
			{
				echo 'connected to 190 | ';
			}
		}
	}
}



function connection_slave() {
    $arr_user[1] = 'admin_indane';
    $arr_pass[1] = 'Sandman{49}XS';

    $rand = mt_rand(1, 1);

    global $conn_slave;

    $user = $arr_user[$rand];
    $pass = $arr_pass[$rand];
	
	
	//////// This define Conntant variable "DB_HOST_SLAVE" for slave  /////
	///////  Chnage in 27'feb-2019 DB_HOST_SLAVE to DB_HOST           /////

    $conn_slave = mysql_connect(DB_HOST, $user, $pass);
    if (!$conn_slave) {
        $user = $arr_user[$rand];
        $pass = $arr_pass[$rand];
        $conn_slave = mysql_connect(DB_HOST, $user, $pass);
    }

    mysql_select_db("admin_indane", $conn_slave);
}


function filterRequestValue($val) {
	$val = mysql_real_escape_string($val);
	$val = strip_tags($val);
	$val = filterMysqlQueries($val);
	return $val;
}

function filterMysqlQueries($value) {
	$val = strtolower($value);
	$injection_found = false;
	
	if (strpos($val, 'select') !== false && strpos($val, 'from') !== false) {
		$injection_found = true;
	}
	if (strpos($val, 'update') !== false && strpos($val, 'set') !== false) {
		$injection_found = true;
	}
	if (strpos($val, 'delete') !== false && strpos($val, 'from') !== false) {
		$injection_found = true;
	}
	if (strpos($val, 'drop') !== false && strpos($val, 'table') !== false) {
		$injection_found = true;
	}
	if (strpos($val, 'truncate') !== false && strpos($val, 'table') !== false) {
		$injection_found = true;
	}
	if (strpos($val, 'union') !== false && strpos($val, 'select') !== false) {
		$injection_found = true;
	}
	if (strpos($val, 'insert') !== false && strpos($val, 'into') !== false) {
		$injection_found = true;
	}
	if (strpos($val, 'alter') !== false && strpos($val, 'table') !== false) {
		$injection_found = true;
	}
	if (strpos($val, 'explain') !== false && strpos($val, 'select') !== false) {
		$injection_found = true;
	}
	if (strpos($val, 'explain') !== false && strpos($val, 'delete') !== false) {
		$injection_found = true;
	}
	if (strpos($val, 'explain') !== false && strpos($val, 'update') !== false) {
		$injection_found = true;
	}

	if ($injection_found == true ) {
		header("HTTP/1.1 400 Bad Request");
		echo '<?xml version="1.0" encoding="iso-8859-1"?>';
		echo '<UserSurrenderInfo><Status>Invalid Request Found</Status></UserSurrenderInfo>';  
		die();
	}
	else {
		return $value;
	}
}

foreach($_REQUEST as $key=>$val){
   $_REQUEST[$key]   = filterRequestValue($val);	     
}
foreach($_POST as $key=>$val){
   $_POST[$key]   = filterRequestValue($val);	     
}
foreach($_GET as $key=>$val){
   $_GET[$key]   = filterRequestValue($val);	     
}




function connection_indane_152() {
	//$host = '111.118.188.152';
	$host = '192.168.50.152';
    $arr_user[1] = 'admin_indane';
    $arr_user[2] = 'admin_indane1';
    $arr_user[3] = 'admin_indane2';
	
    $arr_pass[1] = 'Sandman{49}XS';
    $arr_pass[2] = 'Blps470$';
    $arr_pass[3] = 'Blps470$';


    $rand = mt_rand(1, 3);

    global $link_2;

    $user = $arr_user[$rand];
    $pass = $arr_pass[$rand];

    $link_2 = mysql_connect($host, $user, $pass);

    mysql_select_db("admin_indane", $link_2);
}

//mysql_query("SET GLOBAL interactive_timeout=300");
//mysql_query("SET GLOBAL wait_timeout=300");

//define("SEND_MAIL","info@indane.co.in");
define("SEND_MAIL", "no-reply@indane.co.in");
define("INFO_MAIL", "info@indane.co.in"); 
define("ADMIN_MAIL", "admin@indane.co.in");
define("FEEDBACK_MAIL", "feedback@indane.co.in");
define("LOGS_CYFT_MAIL", "Indane-Crone@cyfuture.com");
define("LOGS_CYFT_MAIL_NOTIFICATION", "shashi.bhushan@cyfuture.com,nitin.sukhija@cyfuture.com,dhiraj.kumar@cyfuture.com,gajendra.singh@cyfuture.com");

///define("LOGS_CYFT_MAIL", "prem.vaishnav@cyfuture.com"); // changed due to issue in Indane-Crone@cyfuture.com email group

define("FEEDBACK_PAGE_URL", "https://cx.indianoil.in/EPICIOCL/faces/GrievanceMainPage.jspx"); // Feedback page url for all mails of indane.co.in

define("SERVER_URL", "https://indane.co.in/"); // http://indane.co.in/  changed on feb 9 2016 suggest by Manish Sarthak Sir.
define("SERVER_URL_SSL", "https://indane.co.in/");

define("CDN_SERVER_URL", "https://indane.co.in/"); // http://indane.co.in/  changed on feb 9 2016 suggest by Manish Sarthak Sir.

define("BASE_PATHM", "C:/Inetpub/vhosts/indane.co.in/httpdocs/");
define("TRANSPARENCY_PORTAL_URL","https://spandan.indianoil.co.in/transparency/");
define("SPANDAN_PORTAL_URL","https://spandan.indianoil.co.in/");

define("TP_INDANE_WS","https://spandan.indianoil.co.in/tpservices/");

define("SMS_WSDL_PATH" , "http://sandesh.indianoil.co.in/IOCSMSGateway/SMSGateway?wsdl"); //SMS sending path for wsdl file
define("SMS_USER_ID" , "LPGV");              // LPGI                 // LPGV
define("SMS_PASSWORD", "LPG@VITARAK");        // LPG@INDSOFT@INDANE   // LPG@VITARAK

### Response of XID ##
define("XID_RSP_WS_URL" , "https://spandan.indianoil.co.in/ConsumerInfoIndane/PrtlInt/getresponse.jsp"); 
define("XID_RSP_USER_ID" , "INDANEUSER");             
define("XID_RSP_PASSWORD", "E*tEnvV3nd0r");
### Response of XID ##

### XML Request Post To IOCL ###
define("XML_REQ_WS_URL" , "https://spandan.indianoil.co.in/ConsumerInfoIndane/PrtlInt/IndaneValidation.jsp"); 
define("XML_REQ_USER_ID" , "INDANEUSER");             
define("XML_REQ_PASSWORD", "E*tEnvV3nd0r");
### XML Request Post To IOCL ##

$title = "Indane Online : Online Gas Booking and Services";
define("MAIL_TITLE", "Indane");
define("INDENT_MAIL_TITLE", "Indane Indent");
define("INDENT_SEND_MAIL", "productindent@indane.co.in");

define("SBIEPAY_ENCRYPTION_KEY", "I6iQgMzvrgAeI7F45HZEeQ==");
define("SBIEPAY_MERCHANT_ID", 1000090);

//define("ICICI_TPSL_IV_KEY", "1592230072VSNUOF");
//define("ICICI_TPSL_SECRET_KEY", "7814259398FROSBU");
//define("ICICI_TPSL_MERCHANT_ID", "T3541");

define("ICICI_TPSL_MERCHANT_ID", "L4922");
define("ICICI_TPSL_SECRET_KEY", "3791427274HCGMMD");
define("ICICI_TPSL_IV_KEY", "7710172565AQHGCI");

define("ICICI_TPSL_MERCHANT_ID_CC", "L5164");
define("ICICI_TPSL_SECRET_KEY_CC", "6154694479PLBSBU");
define("ICICI_TPSL_IV_KEY_CC", "5967473283MFYVTD");

define("ICICI_TPSL_MERCHANT_ID_DC", "L5165");
define("ICICI_TPSL_SECRET_KEY_DC", "5789224647GSQKPR");
define("ICICI_TPSL_IV_KEY_DC", "9634799239YIDUTO");

define("PASSWORD_ENCRYPTION_KEY", "OTvHiKShvDjdfyqfkvd6w==");
define("SHA_SALT", "OTvHiJLhvGHdfyqfkvd6w==");
define("CAY_REF_ID", "VGOFpME09Jcm55b01");
define("CAY_REF_ID_IV", "!IV@_$2");





function v_connection() {
    //credential for verification database
    $v_hostname = "localhost";
    $v_username = "technocrat";
    $v_password = "dsgtechno";
    $v_database = "admin_consumerdata";
    //global $link;
    $link = mysql_connect($v_hostname, $v_username, $v_password);
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }
    $link = mysql_select_db($v_database, $link);
    if (!$link) {
        die('Could not connect from Database: ' . mysql_error());
        exit();
    }
}

function get_preferred_time_slot($day, $time = '') {
    switch ($day) {
        case "Monday":return 1;
        case "Tuesday":return 1;
        case "Wednesday":return 1;
        case "Thursday":return 1;
        case "Thrusday":return 1;
        case "Friday":return 1;
        case "Saturday":return 2;
        case "Sunday":return 2;
        case "Any day":return 3;
        default:return 0;
    }
}

function get_preferred_cat_title($cat_id = 0) {
    switch ($cat_id) {
        case 1:return "Preferred Day and Time Customer";
        case 2:return "Saturday/Sunday Preferred Customer";
        case 3:return "Preferred Time Customer";
        default:return "";
    }
}

function lot_multipleConnection() {
//    $select = '';
//    if($val != ''){
//        $select = 'selected="selected"';
//    }
//
    $str = '<option value="">ALL</option>
              <option value="20D">P1</option>
              <option value="34D">P2</option>
              <option value="P3">P3</option>
              <option value="P4">P4</option>
              <option value="P5">P5</option>
              <option value="P6">P6</option>
              <option value="P7">P7</option>
			  <option value="P8">P8</option>
              <option value="P10">P10</option>
              <option value="IADU">IADU</option>
              <option value="IADU05">IADU05</option>
              <option value="06_A">06A</option>
              <option value="06B">06B</option>';

    return $str;
}

function suspectFlag_multipleConnection() {
    $str = '<option value="" selected="selected">ALL</option>
          <option value="NEAR_SNSA">NEAR SNSA</option>
          <option value="FAR_SNSA">FAR SNSA</option>
          <option value="NEAR_DNSA">NEAR DNSA</option>
          <option value="FAR_DNSA">FAR DNSA</option>
          <option value="NAME_INSUFF">NAME INSUFFICIENT</option>
          <option value="ADDRESS_INSUFF">ADDRESS INSUFFICIENT</option>
          <option value="IADU">Aadhaar Duplicate</option>
          <option value="IADU05">IADU05</option>
          <option value="IADU6A">IADU6A</option>
          <option value="IADU6B">IADU6B</option>';

    return $str;
}

function checkCDNServer() {
    $l = strlen(@file_get_contents("https://www.indane.co.in/images/1menubg.gif")); // http://www.indane.co.in/  changed on feb 9 2016 suggest by Manish Sarthak Sir.
    if ($l == 208)
        return true;
    else
        return false;
}

function callshutdownmysql() {
	global $link;
    // update connection id of cronfile to monitor the execution
	if(php_sapi_name()=='cli')
	{
		if($_SERVER['PHP_SELF'] != 'master_cron_1.php')
		{
			$query = "update gb_cron_status set end_time=now() where connection_id=connection_id() ";
			if(mysql_query($query))
			{
				echo ' | completed | ';
			}
		}
	}
	try {
        mysql_close($link);
    }
    catch (Exception $e) {

    }
}

//function getClientIP(){
//	return $_SERVER['HTTP_X_CLIENTIP'];	
//}

function getClientIP(){
	$ipAddr = isset($_SERVER['HTTP_X_CLIENTIP']) ? $_SERVER['HTTP_X_CLIENTIP'] : $_COOKIE['clientip'];	        
        // start here. 
        // added by Manish Arora, Suggest By: Manish Sarthak, Add Date: 10-Mar-2016, Purpose: Client IP not available in https layer
        return $ipAddr;
}

function convertEmail_toPlain($string)
{
	$string = str_replace('@','[at]',$string);
	$string = str_replace('.','[dot]',$string);
	return $string;
}

foreach($_COOKIE as $k=>$v)
{
	setcookie(  
		$k,//name  
		$v,//value  
		time()+43200,//expires at end of session  
		$currentCookieParams['path'],//path  
		$currentCookieParams['domain'],//domain  
		true, //secure  
		true
	);
}

register_shutdown_function('callshutdownmysql');
?>