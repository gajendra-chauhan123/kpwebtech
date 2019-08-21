<?php
define('SITE_URL', "https://indwsm.indane.co.in/");
#define('API_FOLDER', "mobile_api_coded/");
define('API_FOLDER', "mobile_api_coded_123A/");
define('IP_SITE_URL', "http://111.118.188.152/");




/////define('ACCESS_TOKEN', 'IND=2908WEBSECURE^0210');

function get_request_headers() 
{
	$device_id = mysql_real_escape_string($_SERVER['HTTP_DEVICEID']);
	$token = mysql_real_escape_string($_SERVER['HTTP_TOKEN']);
	return array('token'=>$token, "deviceid"=>$device_id);
}
/**
 * simple method to encrypt or decrypt a plain text string
 * initialization vector(IV) has to be the same when encrypting and decrypting
 * PHP 5.4.9 ( check your PHP version for function definition changes )
 *
 * this is a beginners template for simple encryption decryption
 * before using this in production environments, please read about encryption
 * use at your own risk
 *
 * @param string $action: can be 'encrypt' or 'decrypt'
 * @param string $string: string to encrypt or decrypt
 *
 * @return string
 */
function encrypt_decrypt($action, $stringPlaneText) {
    $output = false;

    $encrypt_method = "AES-256-CBC";
    $secret_key = '*/Qm%Y8*?>7;X!lxlsc@=ukQ#!}<xO';
    $secret_iv = '!P<!habnc-sD|l1&OX]@';
	$string=strtotime(date('Y-m-d H:i:s')).$stringPlaneText.strtotime(date('Y-m-d H:i:s'));
    // hash
    $key = hash('sha256', $secret_key);
    
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    
	else if( $action == 'decrypt' ){
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
	
    return $output;
}
/////////////////////////////////////

///////////// Check User Session Time Out ////
/*
@check token from sql
@return false if over than 2 hour
*/

function CheckUserTimeOut(){
		$token = get_request_headers();
		$token = array_change_key_case($token, CASE_LOWER);
		$userToken = mysql_real_escape_string($token['token']);
		$deviceID = mysql_real_escape_string($token['deviceid']);
		header("Content-type: text/xml");
		
		if($userToken=='' || $deviceID=='')
		{
			header("HTTP/1.1 401 Unauthorized Access");
			echo '<?xml version="1.0" encoding="iso-8859-1"?>';
			echo '<UserSurrenderInfo><Status>Invalid Credentials</Status></UserSurrenderInfo>';  
			exit();
		}
		else {
			try{
				$sqlCheck = mysql_query("select user_id,device_id,expiry_date from `gb_user_token` where md5(user_token)='".md5($userToken)."' and md5(device_id)='".md5($deviceID)."'") or die(mysql_error());

				if(mysql_num_rows($sqlCheck)>0){
					$UserData = mysql_fetch_array($sqlCheck);
					$start_date = new DateTime(date('Y-m-d H:i:s'));
					$since_start = $start_date->diff(new DateTime($UserData['expiry_time']));
					$value = $since_start->i;
					if($value < 120){
						return array($UserData['user_id'], $UserData['device_id']);
					}
					else{
						header("HTTP/1.1 401 Unauthorized Access");
						echo '<?xml version="1.0" encoding="iso-8859-1"?>';
						echo '<UserSurrenderInfo><Status>Invalid Credentials</Status></UserSurrenderInfo>';  
						exit();
					}	
				}
				else{
					header("HTTP/1.1 401 Unauthorized Access");
					echo '<?xml version="1.0" encoding="iso-8859-1"?>';
					echo '<UserSurrenderInfo><Status>Invalid Credentials</Status></UserSurrenderInfo>';  
					exit();
				}	
			}
			catch(Exception $e){
				header("HTTP/1.1 502 Bad Data");
				$error_message = $e->getMessage();
				echo '<?xml version="1.0" encoding="iso-8859-1"?>';
				echo '<UserSurrenderInfo><Status>'. $error_message .'</Status></UserSurrenderInfo>';
				exit();
			}
		}
		
}


/*$accessToken = base64_decode(trim($_REQUEST['access_token']));

if($accessToken!=ACCESS_TOKEN){
	header("HTTP/1.1 401 Unauthorized Access");
	header("Content-type: text/xml");
	echo '<?xml version="1.0" encoding="iso-8859-1"?>';
	echo '<Info><Status>false</Status><Message>Invalid Access Token</Message></Info>';  
	exit();
}  */

if ($no_token_use != true){
	list($userID, $deviceID) = CheckUserTimeOut();
}

?>