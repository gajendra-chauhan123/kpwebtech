<?php

function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   $string = preg_replace('/[^A-Za-z0-9\-.]/', '', $string); // Removes special chars.

   return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}
ob_start();

include_once("includes/header.php");
$status_db = '';

if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
	$id = base64_decode($_REQUEST['id']);
	$acc_details_qry = mysql_query("select * from gb_accident_insurance where id = ".$id);
	$acc_details = mysql_fetch_array($acc_details_qry);
	$status_db = $acc_details['status'];
	
	if($acc_details['last_refill_supply_date']!='')
	$acc_details['last_refill_supply_date'] = date('d/m/Y',strtotime(str_replace('/','-',$acc_details['last_refill_supply_date'])));
	if($acc_details['last_change_of_lpg_hose_date']!='')
	$acc_details['last_change_of_lpg_hose_date'] = date('d/m/Y',strtotime(str_replace('/','-',$acc_details['last_change_of_lpg_hose_date'])));
}

?>
<link rel="stylesheet" href="<?=SERVER_URL?>js/dhtmlgoodies_calendar.css?random=20051112" media="screen"/>
<script type="text/javascript" src="<?=SERVER_URL?>js/dhtmlgoodies_calendar.js?random=20060118"></script>

<?php
$area_id = $user['area_id'];
if($user['isp_type'] == 'SZ'){
	$ofcnameqry = mysql_query("select id,office_name from gb_offices where id in (select id1 from gb_office_relation where id2 = '".$area_id."')");
}
if($user['isp_type'] == 'AO'){
	$ofcnameqry = mysql_query("select id,office_name from gb_offices where id = '".$area_id."'");
}
$self_report = "accident_insurance_report.php";
if(isset($_REQUEST['id'])){
	$self = "accident_insurance.php?id=".$_REQUEST['id']; //File Name
}else{
	$self = "accident_insurance.php"; //File Name
}
if ($user['isp_type'] == 'AO') {
    $str = " and gcd.ao_id = '" . $area_id . "'";
}
if ($user['isp_type'] == 'SZ') {
    $str = " and gcd.sz_id = '" . $area_id . "'";
}
if ($user['isp_type'] == 'SO') {
    $str = " and gcd.so_id = '" . $area_id . "'";
}
include_once("../includes/comman_functions.php");

$errors = array();
$maxpdfsize = 2097152; // 2 mb
$maxuploadfilesize = 2097152; // 2 mb
function get_extension($image = '') {
        /*if ($image != '') {
            $ext = strtolower(substr($image, strpos($image, '.') + 1));
            return $ext;
        }*/
		return end(explode('.',$image));
    }
$extension = array('pdf','PDF');
$only_image_extension = array('pdf','PDF');
if (isset($_POST['modesubmit']) && $_POST['modesubmit'] != '') {
	
	if($_POST['modesubmit'] == 'SAVE' || $_POST['modesubmit'] == 'UPDATE'){
		if($status_db != '1' && $status_db != 2){
			$status = '0';
		}
		
	}
	else if($_POST['modesubmit'] == 'SUBMIT'){
		if((isset($acc_details) && $status_db != '2')){
			$status = '1';
		}
		
	}
	else if($_POST['modesubmit'] == 'CLOSE'){
		$status = '2';
	}
	
	
	
    $extension = array('pdf','PDF');
    $only_image_extension = array('pdf','PDF');

	if(!isset($acc_details)){
		if (IsNullOrEmptyString($_POST['state_id'])) {
			$errors[] = 'State required.';
		}
		if (IsNullOrEmptyString($_POST['district'])) {
			$errors[] = 'District required.';
		}
		if (IsNullOrEmptyString($_POST['location'])) {
			$errors[] = 'Location required.';
		}
		if (IsNullOrEmptyString($_POST['consumer_name'])) {
			$errors[] = 'Consumer name required.';
		}
		if(preg_match('/[^a-zA-Z ,.\'-]+/i', $_POST['consumer_name'])) {
			$errors[] = 'Invalid characters in consumer name.';
		}
		if(strlen($_POST['consumer_name']) > 50){
			$errors[] = 'Consumer name should be less than 50 characters.';
		}
		if (IsNullOrEmptyString($_POST['consumer_number'])) {
			$errors[] = 'Consumer number required.';
		}
		if(preg_match('/[^0-9]+/i', $_POST['consumer_number']) || strlen($_POST['consumer_number'])!=16) {
			$errors[] = 'Consumer ID should be 16 digits numeric number.';
		}
		if (IsNullOrEmptyString($_POST['customer_category'])) {
			$errors[] = 'Customer Category required.';
		}
		if (IsNullOrEmptyString($_POST['sv_number'])) {
			$errors[] = 'SV Number required.';
		}
		if (IsNullOrEmptyString($_POST['sv_date'])) {
			$errors[] = 'SV Date required.';
		}
		if (IsNullOrEmptyString($_POST['distributor'])) {
			$errors[] = 'Distributor required.';
		}

	   
		if (IsNullOrEmptyString($_POST['date_accident'])) {
			$errors[] = 'Date of accident required.';
		} 
		if (preg_match('\d{1,2}/\d{1,2}/\d{4}', $_POST['date_accident'])){
			$errors[] = 'Invalid Date of accident.';
		}
		
		$php_date_of_accident = strtotime(str_replace('/', '-', $_POST['date_accident']));
		$date_of_accident = date('Y-m-d',$php_date_of_accident); 
		$end = strtotime('01-04-2013'); 
		$enddate = date('Y-m-d', $end); 

		if ($date_of_accident < $enddate) 
		{
			$errors[] = 'Date of accident cannot be less than 01/04/2013 ';
		}
		//echo "%%%%%%%%%%".$date_of_accident."**************<br>";
		//if ((isset($acc_details)) || (date('Y-m',$php_date_of_accident) == date('Y-m') || ((int)date('d')<=10 && date('Y-m',strtotime($date_of_accident . " +1 months")) == date('Y-m'))))
		if($date_of_accident >='2018-04-01' &&  $date_of_accident <='2019-03-31')
		{
			//echo date('Y-m',strtotime($date_of_accident . " +1 months"));
			//allowed
			//$errors[] = 'Date of accident is invalid. It cannot be previous month.';
		}
		else{
			//echo date('Y-m',strtotime($date_of_accident . " +1 months"));
			$errors[] = 'Date of accident is invalid. It cannot be previous month.';
		}
		/*if ((!isset($acc_details)) && date('Y-m',$php_date_of_accident) != date('Y-m') && (int)date('d')>10) 
		{
			$errors[] = 'Date of accident is invalid. It cannot be previous month.';
		}*/
		
		
		
		$todays = date('Y-m-d');
		if ($date_of_accident > $todays) {
			$errors[] = 'Date of accident cannot be greater than todays date';
		}
		
		if (IsNullOrEmptyString($_POST['hour_accident'])) {
			$errors[] = 'Hour of accident required.';
		}
		if (IsNullOrEmptyString($_POST['time_accident'])) {
			$errors[] = 'Time of accident required.';
		}
		
	   
		if (IsNullOrEmptyString($_POST['cylinder_burst'])) {
			$errors[] = 'Cylinder burst required.';
		}
		
		if (IsNullOrEmptyString($_POST['brief_accident'])) {
			$errors[] = 'Brief of accident required.';
		}
		// if(preg_match('/[^a-zA-Z ,._/\'-]+/i', $_POST['brief_accident'])) {
			// $errors[] = 'Invalid characters in brief of accident.';
		// }
		if (strlen($_POST['brief_accident']) < 50) {
			$errors[] = 'Brief of accident can have min 50 characters';
		}
		if(strlen($_POST['brief_accident']) > 500){
			$errors[] = 'Brief of accident can have max 500 characters';
		}
		if (IsNullOrEmptyString($_POST['accident_category'])) {
				$errors[] = 'Cause of accident required.';
		}
		if (IsNullOrEmptyString($_POST['cause_accident'])) {
			$errors[] = 'Cause of accident required.';
		}
		if (IsNullOrEmptyString($_POST['installation_done'])) {
			$errors[] = 'Installation done as per procedure required.';
		}
		if (IsNullOrEmptyString($_POST['last_refill_supply_date'])) {
			$errors[] = 'Date of last refill supply  required.';
		}
		if (preg_match('\d{1,2}/\d{1,2}/\d{4}', $_POST['last_refill_supply_date'])){
			$errors[] = 'Invalid Date of last refill supply.';
		}
		if (IsNullOrEmptyString($_POST['last_change_of_lpg_hose_date'])) {
			$errors[] = 'Date of last change of LPG Hose  required.';
		}
		if (preg_match('\d{1,2}/\d{1,2}/\d{4}', $_POST['last_change_of_lpg_hose_date'])){
			$errors[] = 'Invalid Date of last change of LPG hose supply.';
		}
		if (IsNullOrEmptyString($_POST['person_using_lpg'])) {
			$errors[] = 'Person using LPG at the time of accident  required.';
		}
		if (IsNullOrEmptyString($_POST['any_leakage_complaint_lodged'])) {
			$errors[] = 'Was there any leakage complaint lodged immediately prior to accident  required.';
		}
		else
		{
			if ($_POST['any_leakage_complaint_lodged'] == '1' && IsNullOrEmptyString($_POST['any_leakage_complaint_attended'])) {
				$errors[] = 'Was it attended within prescribed time  required.';
			}
		}
		if (IsNullOrEmptyString($_POST['major_supply_point_state_id'])) {
			$errors[] = 'State of Major Supply Point  required.';
		}
		if (IsNullOrEmptyString($_POST['major_supply_point'])) {
			$errors[] = 'Major Supply Point  required.';
		}
		
		if (IsNullOrEmptyString($_POST['cause_attr'])) {
			$errors[] = 'Cause of accident required.';
		}
		
		if (IsNullOrEmptyString($_POST['no_deaths'])) {
			$errors[] = 'Number of deaths required.';
		}
		else if (!ctype_digit(trim($_POST['no_deaths']))) {
			 $errors[] = 'Number of deaths should be digits only.';
		}
		
		if (IsNullOrEmptyString($_POST['no_injuries'])) {
			$errors[] = 'Number of injuries required.';
		}
		else if (!ctype_digit(trim($_POST['no_injuries']))) {
			 $errors[] = 'Number of injuries should be digits only.';
		}
		if (IsNullOrEmptyString($_POST['property_damage'])) {
			$errors[] = 'Property damage required.';
		}
		
	}
    
    
	if($_POST['modesubmit'] == 'SUBMIT'){
		if($_POST['formata_doc'] == ''){
				if ($_FILES["formata"]["name"] == '') {
				$errors[] = 'Format A required.';
			}
		}
		if($_POST['formatb_doc'] == ''){
			if ($_FILES["formatb"]["name"] == '') {
				$errors[] = 'Format B required.';
			}
		}
	}
	if($status_db != '2'){
		if (IsNullOrEmptyString($_POST['intim_date_dist'])) {
			$errors[] = 'Date of intimation to Insurance Company by distributor under Policy taken by them is required.';
		}
		if (IsNullOrEmptyString($_POST['intimation_date'])) {
			$errors[] = 'Intimation date required.';
		}
		
		if (preg_match('\d{1,2}/\d{1,2}/\d{4}', $_POST['intimation_date'])){
			$errors[] = 'Invalid intimation date.';
		}
		$intimation_date = date('Y-m-d',strtotime(str_replace('/','-',$_POST['intimation_date'])));
		
		if($intimation_date < $date_of_accident){
			$errors[] = 'Intimation date cannot be less than date of accident.';
		}
		if($intimation_date > date('Y-m-d')){
			$errors[] = 'Intimation date cannot be greater than todays date.';
		}
		
		
		if (IsNullOrEmptyString($_POST['insurance_company'])) {
			$errors[] = 'Insurance Company required.';
		}
		if (IsNullOrEmptyString($_POST['claim_settled'])) {
			$errors[] = 'Claim settled required.';
		}
		if($_POST['claim_settled'] == '0'){
			if (IsNullOrEmptyString($_POST['reason_pending'])) {
				$errors[] = 'Reason for pending required.';
			}
		}
		if($_POST['claim_settled'] == '1'){
			
			
			if (IsNullOrEmptyString($_POST['death_amount'])) {
				$errors[] = 'Amount for death required.';
			}
			else if (!ctype_digit(trim($_POST['death_amount']))) {
				 $errors[] = 'Amount for death should be digits only.';
			}
			if (IsNullOrEmptyString($_POST['injury_amount'])) {
				$errors[] = 'Amount for injury required.';
			}
			else if (!ctype_digit(trim($_POST['injury_amount']))) {
				 $errors[] = 'Amount for injury should be digits only.';
			}
			if (IsNullOrEmptyString($_POST['property_damage_amount'])) {
				$errors[] = 'Amount for property damage required.';
			}
			else if (!ctype_digit(trim($_POST['property_damage_amount']))) {
				 $errors[] = 'Amount for property damage should be digits only.';
			}
			
		}
	}
	if($_POST['modesubmit'] == 'CLOSE'){
		if($status_db != '2'){
			if($_POST['formata_doc'] == ''){
				if ($_FILES["formata"]["name"] == '') {
					$errors[] = 'Format A required.';
				}
			}
			if($_POST['formatb_doc'] == ''){
				if ($_FILES["formatb"]["name"] == '') {
					$errors[] = 'Format B required.';
				}
			}
			
			if (IsNullOrEmptyString($_POST['claim_number'])) {
				$errors[] = 'Claim number required.';
			}
			if($_POST['claim_settled'] == '0'){
				$errors[] = 'Required to select Paid or No claim while closing.';
			}
			if($_POST['claim_settled'] == '1'){
				if (IsNullOrEmptyString($_POST['amount_paid'])) {
					$errors[] = 'Amount paid required.';
				}
				else if (!ctype_digit(trim($_POST['amount_paid']))) {
					 $errors[] = 'Amount paid should be digits only.';
				}
				if (IsNullOrEmptyString($_POST['death_amount'])) {
					$errors[] = 'Amount for death required.';
				}
				else if (!ctype_digit(trim($_POST['death_amount']))) {
					 $errors[] = 'Amount for death should be digits only.';
				}
				if (IsNullOrEmptyString($_POST['injury_amount'])) {
					$errors[] = 'Amount for injury required.';
				}
				else if (!ctype_digit(trim($_POST['injury_amount']))) {
					 $errors[] = 'Amount for injury should be digits only.';
				}
				if (IsNullOrEmptyString($_POST['property_damage_amount'])) {
					$errors[] = 'Amount for property damage required.';
				}
				else if (!ctype_digit(trim($_POST['property_damage_amount']))) {
					 $errors[] = 'Amount for property damage should be digits only.';
				}
				if($_POST['acknowledgement_doc'] == ''){
					if ($_FILES["acknowledgement"]["name"] == '') {
						$errors[] = 'Acknowledgement required.';
					}
				}
			}
		}
		
		
	}
	if($status_db != '2' || !isset($acc_details)){
		if (count($errors) == 0) {
		$base_directory = dirname(basename(__FILE__));
        $formata_img = $formatb_img = $acknowledgement ='';
        $upload_dir = '/profileuploads/insurance/';
        $new_dir = $upload_dir;
        $upload = false;
        $invalid = false;
        $server_array = array(
            "111.118.188.215" => array
                (
                "username" => 'Indane',
                "password" => 'wYh2@t13',
                "basepath" => '/'
				)
        );
        include('../includes/ftp.class.php');
        $ftpObj = new ftpClass();
		if($_POST['formata_doc'] == ''){
			if ($_FILES['formata']['name'] != '' && is_uploaded_file($_FILES['formata']['tmp_name'])) {
				if (in_array(get_extension($_FILES['formata']['name']), $only_image_extension)) {
					if ($_FILES['formata']['size'] <= $maxuploadfilesize) {
						if ($_FILES['formata']['size'] <= 0) {
							$errors[] = 'Format A document file could not be uploaded.';
						}
						
						$formata_img = $new_dir . '/formata_' . date('Ymdhis') . '_' . clean($_FILES['formata']['name']);
						$formata_file_name = 'formata_' . date('Ymdhis') . '_' . clean($_FILES['formata']['name']);
						foreach ($server_array as $host => $server) {
							$ftpObj->ftp($_FILES['formata']['tmp_name'], $formata_img, $host, $server['username'], $server['password'], $new_dir, false);

							if ($ftpObj->GetMessages()) {
								$upload = true;
							} else {
								$errors[] = 'Format A not uploaded. Please try again later.';
							}
						}
					} else {
						$errors[] = 'Format A file too large. File must be less than 2 mb.';
					}
				} else {
					$errors[] = 'Please upload a valid Format A Document (pdf).';
				}
			}
		}
		if($_POST['formatb_doc'] == ''){
			if ($_FILES['formatb']['name'] != '' && is_uploaded_file($_FILES['formatb']['tmp_name'])) {
				if (in_array(get_extension($_FILES['formatb']['name']), $only_image_extension)) {
					if ($_FILES['formatb']['size'] <= $maxuploadfilesize) {
						if ($_FILES['formatb']['size'] <= 0) {
							$errors[] = 'Format B document file could not be uploaded.';
						}
						$formatb_img = $new_dir . '/formatb_' . date('Ymdhis') . '_' . clean($_FILES['formatb']['name']);
						$formatb_file_name = 'formatb_' . date('Ymdhis') . '_' . clean($_FILES['formatb']['name']);
						foreach ($server_array as $host => $server) {
							$ftpObj->ftp($_FILES['formatb']['tmp_name'], $formatb_img, $host, $server['username'], $server['password'], $new_dir, false);

							if ($ftpObj->GetMessages()) {
								$upload = true;
							} else {
								$errors[] = 'Format B not uploaded. Please try again later.';
							}
						}
					} else {
						$errors[] = 'Format B file too large. File must be less than 2 mb.';
					}
				} else {
					$errors[] = 'Please upload a valid Format B Document (pdf).';
				}
			}
		}
		if($_POST['acknowledgement_doc'] == ''){
			if ($_FILES['acknowledgement']['name'] != '' && is_uploaded_file($_FILES['acknowledgement']['tmp_name'])) {
				if (in_array(get_extension($_FILES['acknowledgement']['name']), $only_image_extension)) {
					if ($_FILES['acknowledgement']['size'] <= $maxuploadfilesize) {
						if ($_FILES['acknowledgement']['size'] <= 0) {
							$errors[] = 'Acknowledgement document file could not be uploaded.';
						}
						$acknowledgement_img = $new_dir . '/acknowledgement_' . date('Ymdhis') . '_' . $_FILES['acknowledgement']['name'];
						$acknowledgement_file_name = 'acknowledgement_' . date('Ymdhis') . '_' . $_FILES['acknowledgement']['name'];
						foreach ($server_array as $host => $server) {
							$ftpObj->ftp($_FILES['acknowledgement']['tmp_name'], $acknowledgement_img, $host, $server['username'], $server['password'], $new_dir, false);

							if ($ftpObj->GetMessages()) {
								$upload = true;
							} else {
								$errors[] = 'Acknowledgement not uploaded. Please try again later.';
							}
						}
					} else {
						$errors[] = 'Acknowledgement file too large. File must be less than 2 mb.';
					}
				} else {
					$errors[] = 'Please upload a valid Acknowledgement Document (pdf).';
				}
			}
		}
		
        if (count($errors) == '0') {
               
			  	$state_id = sanitizeInputs($_POST['state_id']);
				$district = sanitizeInputs($_POST['district']);
                $location = sanitizeInputs($_POST['location']);
                $consumer_name = sanitizeInputs($_POST['consumer_name']);
				$consumer_number = sanitizeInputs($_POST['consumer_number']);
                $distributor = sanitizeInputs($_POST['distributor']);
				$date_accident = sanitizeInputs($_POST['date_accident']);
				$time_accident = sanitizeInputs($_POST['hour_accident']).' : '.sanitizeInputs($_POST['time_accident']);
				$cylinder_burst = sanitizeInputs($_POST['cylinder_burst']);
				$brief_accident = sanitizeInputs($_POST['brief_accident']);
                $accident_category = sanitizeInputs($_POST['accident_category']);
				
				$installation_done = sanitizeInputs($_POST['installation_done']);
				$last_refill_supply_date = sanitizeInputs($_POST['last_refill_supply_date']);
				$last_refill_supply_date = date('Y-m-d',strtotime(str_replace('/','-',$last_refill_supply_date)));
				$last_change_of_lpg_hose_date = sanitizeInputs($_POST['last_change_of_lpg_hose_date']);
				$last_change_of_lpg_hose_date = date('Y-m-d',strtotime(str_replace('/','-',$last_change_of_lpg_hose_date)));
				$person_using_lpg = sanitizeInputs($_POST['person_using_lpg']);
				$any_leakage_complaint_lodged = sanitizeInputs($_POST['any_leakage_complaint_lodged']);
				$any_leakage_complaint_attended = sanitizeInputs($_POST['any_leakage_complaint_attended']);
				$major_supply_point_state_id = sanitizeInputs($_POST['major_supply_point_state_id']);
				$major_supply_point = sanitizeInputs($_POST['major_supply_point']);
				
				$cause_accident = sanitizeInputs($_POST['cause_accident']);
				$cause_attr = sanitizeInputs($_POST['cause_attr']);
                $no_deaths = sanitizeInputs($_POST['no_deaths']);
				$no_injuries = sanitizeInputs($_POST['no_injuries']);
                $property_damage = sanitizeInputs($_POST['property_damage']);
                $formata = $formata_file_name;
				$formatb = $formatb_file_name;
				$intim_date_dist = sanitizeInputs($_POST['intim_date_dist']);
				$intim_date_dist_status = sanitizeInputs($_POST['intim_date_dist_status']);
                $amount_paid = sanitizeInputs($_POST['amount_paid']);
                $insurance_company = sanitizeInputs($_POST['insurance_company']);
				$claim_number = sanitizeInputs($_POST['claim_number']);
				$acknowledgement = $acknowledgement_file_name;
				$intimation_date = sanitizeInputs($_POST['intimation_date']);
				$claim_settled = sanitizeInputs($_POST['claim_settled']);
				$reason_pending = sanitizeInputs($_POST['reason_pending']);
				$death_amount = sanitizeInputs($_POST['death_amount']);
				$injury_amount = sanitizeInputs($_POST['injury_amount']);
				$property_damage_amount = sanitizeInputs($_POST['property_damage_amount']);
				$customer_category = sanitizeInputs($_POST['customer_category']);
				$sv_number = sanitizeInputs($_POST['sv_number']);
				$sv_date = sanitizeInputs($_POST['sv_date']);
				$totalamount = $death_amount+$injury_amount+$property_damage_amount;
				
				$dealerqry = mysql_query("select sz_id,so_id,ao_id from gb_company_dealer where id = '".$distributor."' and company_id = '1' and dealer_status = '1'");
				$rowdealer = mysql_fetch_array($dealerqry);
				
				$so_id = $rowdealer['so_id'];
				$ao_id = $rowdealer['ao_id'];
				$sz_id = $rowdealer['sz_id'];
				$created_by = $user['id'];
				
				
				
				if($_POST['mode'] == 'Save'){
					$insertIntoDB = "insert into gb_accident_insurance set 
					state_id = '".$state_id."',
					district = '".$district."',
					location = '".$location."',
					consumer_name = '".$consumer_name."',
					consumer_number = '".$consumer_number."',
					distributor = '".$distributor."',
					so_id = '".$so_id."',
					ao_id = '".$ao_id."',
					sz_id = '".$sz_id."',
					area_office = '".$area_office."', 
					date_accident = '".$date_accident."',
					time_accident = '".$time_accident."',
					cylinder_burst = '".$cylinder_burst."',
					brief_accident = '".$brief_accident."',
					accident_category = '".$accident_category."',
					installation_done = '".$installation_done."',
					last_refill_supply_date = '".$last_refill_supply_date."',
					last_change_of_lpg_hose_date = '".$last_change_of_lpg_hose_date."',
					person_using_lpg = '".$person_using_lpg."',
					any_leakage_complaint_lodged = '".$any_leakage_complaint_lodged."',
					any_leakage_complaint_attended = '".$any_leakage_complaint_attended."',
					major_supply_point_state_id = '".$major_supply_point_state_id."',
					major_supply_point = '".$major_supply_point."',
					cause_accident = '".$cause_accident."',
					cause_attr = '".$cause_attr."',
					no_deaths = '".$no_deaths."',
					no_injuries = '".$no_injuries."',
					property_damage = '".$property_damage."',
					formata = '".$formata_file_name."',
					formatb = '".$formatb_file_name."',
					intim_date_dist = '".$intim_date_dist."',
					intim_date_dist_status = '".$intim_date_dist_status."',
					amount_paid = '".$amount_paid."',
					insurance_company = '".$insurance_company."',
					claim_number = '".$claim_number."',
					acknowledgement = '".$acknowledgement."',
					intimation_date = '".$intimation_date."',
					claim_settled = '".$claim_settled."',
					reason_pending = '".$reason_pending."',
					death_amount = '".$death_amount."',
					injury_amount = '".$injury_amount."', 
					property_damage_amount = '".$property_damage_amount."',
					totalamount = '".$totalamount."',
					created_by = '".$user['id']."',
					status = '".$status."',
					created_date = '".date('Y-m-d H:i:s')."',
					customer_category = '".$customer_category."',
					sv_number = '".$sv_number."',
					sv_date = '".$sv_date."'";
					
				}
				else if($_POST['mode'] == 'Update'){
					
					$insertIntoDB = "update gb_accident_insurance set ";
					if($status_db == '0'){
						$insertIntoDB .= "state_id = '".$state_id."',
						district = '".$district."',
						location = '".$location."',
						consumer_name = '".$consumer_name."',
						consumer_number = '".$consumer_number."',
						distributor = '".$distributor."',
						so_id = '".$so_id."',
						ao_id = '".$ao_id."',
						sz_id = '".$sz_id."',
						area_office = '".$area_office."', 
						date_accident = '".$date_accident."',
						time_accident = '".$time_accident."',
						cylinder_burst = '".$cylinder_burst."',
						brief_accident = '".$brief_accident."',
						accident_category = '".$accident_category."',
						installation_done = '".$installation_done."',
						last_refill_supply_date = '".$last_refill_supply_date."',
						last_change_of_lpg_hose_date = '".$last_change_of_lpg_hose_date."',
						person_using_lpg = '".$person_using_lpg."',
						any_leakage_complaint_lodged = '".$any_leakage_complaint_lodged."',
						any_leakage_complaint_attended = '".$any_leakage_complaint_attended."',
						major_supply_point_state_id = '".$major_supply_point_state_id."',
						major_supply_point = '".$major_supply_point."',
						cause_accident = '".$cause_accident."',
						cause_attr = '".$cause_attr."',
						no_deaths = '".$no_deaths."',
						no_injuries = '".$no_injuries."',
						property_damage = '".$property_damage."',";
						if($_POST['formata_doc'] == ''){
							$insertIntoDB .= "formata = '".$formata_file_name."',";
						}
						if($_POST['formatb_doc'] == ''){
							$insertIntoDB .= "formatb = '".$formatb_file_name."',";
						}
					}
					
					
					if($_POST['acknowledgement_doc'] == ''){
						$insertIntoDB .= "acknowledgement = '".$acknowledgement_file_name."',";
					}
					
					$insertIntoDB .= "intim_date_dist = '".$intim_date_dist."',
					intim_date_dist_status = '".$intim_date_dist_status."',
					amount_paid = '".$amount_paid."',
					insurance_company = '".$insurance_company."',
					claim_number = '".$claim_number."',
					intimation_date = '".$intimation_date."',
					claim_settled = '".$claim_settled."',
					reason_pending = '".$reason_pending."',
					death_amount = '".$death_amount."',
					injury_amount = '".$injury_amount."', 
					property_damage_amount = '".$property_damage_amount."',
					totalamount = '".$totalamount."',";
					if($status != ''){
						$insertIntoDB .= "status = '".$status."',";
					}
					
					$insertIntoDB .= "updated_by = '".$user['id']."',
					updated_date = '".date('Y-m-d H:i:s')."',
					customer_category = '".$customer_category."',
					sv_number = '".$sv_number."',
					sv_date = '".$sv_date."' where id = ".$_POST['acc_id'];
				}
			
				//echo $insertIntoDB;
                if (mysql_query($insertIntoDB)) {
                    $_SESSION['successMsg'] = 'Submitted Successfully.';
                    
                    header("location:$self");
                    die;
                }
                else {
                    $error[] = "Request not submitted.";
                }
        }
    }
		}
	}


function IsNullOrEmptyString($string) {
    return (!isset($string) || trim($string) === '');
}

function replace_specialChar($content = '') {
    if ($content != '') {
        return trim(str_replace("'", "", str_replace('&', 'and', $content)));
    }
}

function sanitizeInputs($string) {
    if (!IsNullOrEmptyString($string)) {
        return mysql_real_escape_string(trim(filter_var($string, FILTER_SANITIZE_STRING)));
    }
}
?>
<script type="text/javascript">
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57) && (charCode < 96 || charCode > 105)) {
        return false;
    }
    return true;
}


function updatetotal(){
	var totlnmbr = 0;
	if($('#death_amount').val() != ''){
		totlnmbr += parseInt($('#death_amount').val());
	}
	if($('#injury_amount').val() != ''){
		totlnmbr += parseInt($('#injury_amount').val());
	}
	if($('#property_damage_amount').val() != ''){
		totlnmbr += parseInt($('#property_damage_amount').val());
	}
	document.getElementById('totalamount').innerHTML = totlnmbr;
	document.getElementById('amount_paid').value = totlnmbr;
}
   
function showDiv(val){
	if(val == '1'){
		document.getElementById('ackdiv').style.display = 'block';
	}
	else{
		document.getElementById('ackdiv').style.display = 'none';
	}
}


function validate() {
	
	if (!$('#state_id').val() || $('#state_id').val().replace(/\s+$/, '') == '') {
		alert("Please select state");
		$('#state_id').focus();
		return false;
	}
	
	if (!$('#district').val() || $('#district').val().replace(/\s+$/, '') == '') {
		alert("Please select district");
		$('#district').focus();
		return false;
	}
	
    if (!$('#location').val() || $('#location').val().replace(/\s+$/, '') == '') {
		alert("Please enter location");
		$('#location').focus();
		return false;
	}
	if (!$('#consumer_name').val() || $('#consumer_name').val().replace(/\s+$/, '') == '') {
		alert("Please enter consumer name");
		$('#consumer_name').focus();
		return false;
	}
	
	
	var letters = /^[A-Za-z0-9 ,_\/.'-]+$/;  
	if(!$('#consumer_name').val().match(letters))  
	{  
		alert("Invalid characters entered in consumer name");
		$('#consumer_name').focus();
		return false;
	}  
	if($('#consumer_name').val().length > 50){
		alert("Consumer name should be less than 50 characters");
		$('#consumer_name').focus();
		return false;
	}
	if (!$('#consumer_number').val() || $('#consumer_number').val().replace(/\s+$/, '') == '') {
		alert("Please enter consumer number");
		$('#consumer_number').focus();
		return false;
	}
	var alpha = /^[A-Za-z0-9]+$/;  
	if(!$('#consumer_number').val().match(alpha))  
	{  
		alert("Invalid characters entered in consumer number");
		$('#consumer_number').focus();
		return false;
	}  
	if($('#customer_category').val() == '')
	{
		alert("Please select customer category");
		$('#customer_category').focus();
		return false;
	}
	if($('#sv_number').val() == '')
	{
		alert("Please enter SV Number");
		$('#sv_number').focus();
		return false;
	}
    if (!$('#sv_date').val() || $('#sv_date').val().replace(/\s+$/, '') == '') 
	{
		alert("Please select date of SV");
		$('#sv_date').focus();
		return false;
	}
    if (!$('#distributor').val() || $('#distributor').val().replace(/\s+$/, '') == '') {
		alert("Please select distributor");
		$('#distributor').focus();
		return false;
	}
	if (!$('#date_accident').val() || $('#date_accident').val().replace(/\s+$/, '') == '') {
		alert("Please select date of accident");
		$('#date_accident').focus();
		return false;
	}
	var pattern = /^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/;
	if(!pattern.test($('#date_accident').val())){
		alert("Please enter valid date of accident");
		$('#date_accident').focus();
		return false;
	}
	
	
	var date_incident_arr = $('#date_accident').val().split("/");
	var d = date_incident_arr[0];
	var m = date_incident_arr[1];
	var y = date_incident_arr[2];
	var chkdate = new Date(2013,4,1);
	var date_to_check = y+'-'+m+'-'+d;
	
	
	//var today = new Date().toISOString().slice(0,10);
	//var today_date_arr = today.split("-");
	//var y = today_date_arr[0];
	//var m = today_date_arr[1];
	//var d = today_date_arr[2];
	//var todaydate = new Date(y,m,d);
	
	
	// GET CURRENT DATE
	var date = new Date();
	 
	// GET YYYY, MM AND DD FROM THE DATE OBJECT
	var yyyy = date.getFullYear().toString();
	var mm = (date.getMonth()+1).toString();
	var dd  = date.getDate().toString();
	// CONVERT mm AND dd INTO chars
	var mmChars = mm.split('');
	var ddChars = dd.split('');
	 
	// CONCAT THE STRINGS IN YYYY-MM-DD FORMAT
	var todaydate = yyyy + '-' + (mmChars[1]?mm:"0"+mmChars[0]) + '-' + (ddChars[1]?dd:"0"+ddChars[0]);
	
	
	if (date_to_check < chkdate){
		alert("Date of accident cannot be less than 01/04/2013");
		$('#date_accident').focus();
		return false;
	}
	// alert(date_to_check);
	// alert(todaydate);
	
	if(date_to_check > todaydate){
		alert("Date of accident cannot be greater than todays date");
		$('#date_accident').focus();
		return false;
	}
	<?php
	if(!isset($acc_details))
	{
		?>
	if(y+'-'+m != yyyy + '-' + (mmChars[1]?mm:"0"+mmChars[0]) && dd>10)
	{
		alert("Date of accident is invalid. It cannot be previous month.");
		$('#date_accident').focus();
		return false;
	}
	<?php
	}
	?>
	
	if (!$('#hour_accident').val() || $('#hour_accident').val().replace(/\s+$/, '') == '') {
		alert("Please select hour of accident");
		$('#hour_accident').focus();
		return false;
	}
	
	
	if (!$('#time_accident').val() || $('#time_accident').val().replace(/\s+$/, '') == '') {
		alert("Please select time of accident");
		$('#time_accident').focus();
		return false;
	}
	
    if (!$('#cylinder_burst').val() || $('#cylinder_burst').val().replace(/\s+$/, '') == '') {
		alert("Please select cylinder burst");
		$('#cylinder_burst').focus();
		return false;
	}
	if (!$('#brief_accident').val() || $('#brief_accident').val().replace(/\s+$/, '') == '') {
		alert("Please enter brief of accident");
		$('#brief_accident').focus();
		return false;
	}
	
	// if(!$('#brief_accident').val().match(letters))  
	// {  
		// alert("Invalid characters entered in brief of accident");
		// $('#brief_accident').focus();
		// return false;
	// }  
	
	
	if($('#brief_accident').val().length < 50){
		alert("Please enter min 50 characters");
		$('#brief_accident').focus();
		return false;
	}
	if($('#brief_accident').val().length > 500){
		alert("Please enter max 500 characters");
		$('#brief_accident').focus();
		return false;
	}
	if (!$('#accident_category').val() || $('#accident_category').val().replace(/\s+$/, '') == '') {
		alert("Please select cause of accident");
		$('#accident_category').focus();
		return false;
	}
	if (!$('#cause_accident').val() || $('#cause_accident').val().replace(/\s+$/, '') == '') {
		alert("Please select cause of accident");
		$('#cause_accident').focus();
		return false;
	}
	if (!$('#cause_attr').val() || $('#cause_attr').val().replace(/\s+$/, '') == '') {
		alert("Please select cause of accident");
		$('#cause_attr').focus();
		return false;
	}
	if (!$('#no_deaths').val() || $('#no_deaths').val().replace(/\s+$/, '') == '') {
		alert("Please enter number of fatalities");
		$('#no_deaths').focus();
		return false;
	}
	else if (numOnly('no_deaths')) {
		alert('Invalid characters used in number of fatalities');
		$('#no_deaths').focus();
		return false;
	}
	if (!$('#no_injuries').val() || $('#no_injuries').val().replace(/\s+$/, '') == '') {
		alert("Please enter number of injuries");
		$('#no_injuries').focus();
		return false;
	}
	else if (numOnly('no_injuries')) {
		alert('Invalid characters used in number of injuries');
		$('#no_injuries').focus();
		return false;
	}
	if (!$('#property_damage').val() || $('#property_damage').val().replace(/\s+$/, '') == '') {
		alert("Please select property damage");
		$('#property_damage').focus();
		return false;
	}
	
	
	if (!$('#intim_date_dist').val() || $('#intim_date_dist').val().replace(/\s+$/, '') == '') {
		alert("Please select Intimation to Insurance Company by distributor under Policy taken by them");
		$('#intim_date_dist').focus();
		return false;
	}
	
	if (!$('#intimation_date').val() || $('#intimation_date').val().replace(/\s+$/, '') == '') {
		alert("Please select Intimation date");
		$('#intimation_date').focus();
		return false;
	}
	var intimation_date_arr = $('#intimation_date').val().split("/");
	var d = intimation_date_arr[0];
	var m = intimation_date_arr[1];
	var y = intimation_date_arr[2];
	
	var chkdate = y+'-'+m+'-'+d;
	
	if (chkdate < date_to_check){
		alert("Intimation date cannot be less than Date of accident.");
		$('#intimation_date').focus();
		return false;
	}
	
	if(chkdate > todaydate){
		alert("Intimation date cannot be greater than todays date");
		$('#intimation_date').focus();
		return false;
	}
	
	if (!$('#insurance_company').val() || $('#insurance_company').val().replace(/\s+$/, '') == '') {
		alert("Please select insurance company");
		$('#insurance_company').focus();
		return false;
	}
	if (!$('#claim_settled').val() || $('#claim_settled').val().replace(/\s+$/, '') == '') {
		alert("Please select claim settled");
		$('#claim_settled').focus();
		return false;
	}
	if($('#claim_settled').val() == '0'){
		if (!$('#reason_pending').val() || $('#reason_pending').val().replace(/\s+$/, '') == '') {
			alert("Please select Reason for pendency");
			$('#reason_pending').focus();
			return false;
		}
	}
	if($('#claim_settled').val() == '1'){
		if (!$('#death_amount').val() || $('#death_amount').val().replace(/\s+$/, '') == '') {
			alert("Please enter death amount");
			$('#death_amount').focus();
			return false;
		}
		else if (numOnly('death_amount')) {
			alert('Invalid characters used in amount for death');
			$('#death_amount').focus();
			return false;
		}
		if (!$('#injury_amount').val() || $('#injury_amount').val().replace(/\s+$/, '') == '') {
			alert("Please enter injury amount");
			$('#injury_amount').focus();
			return false;
		}
		else if (numOnly('injury_amount')) {
			alert('Invalid characters used in amount for injury');
			$('#injury_amount').focus();
			return false;
		}
		if (!$('#property_damage_amount').val() || $('#property_damage_amount').val().replace(/\s+$/, '') == '') {
			alert("Please enter property damage amount");
			$('#property_damage_amount').focus();
			return false;
		}
		else if (numOnly('property_damage_amount')) {
			alert('Invalid characters used in amount for property damage');
			$('#property_damage_amount').focus();
			return false;
		}
	}
	return true;
}
function validateform(){
	if(validate()){
		document.getElementById('modesubmit').value = "SAVE";
		document.getElementById('form_acc').submit();
	}
	
}
function check_documents(){
	if(validate()){
		if ( !$( "#formata_doc" ).length ) {
			if (!$('#formata').val() || $('#formata').val().replace(/\s+$/, '') == '') {
				alert("Please upload Format A document");
				$('#formata').focus();
				return false;
			}
		}
		if ( !$( "#formatb_doc" ).length ) {
			if (!$('#formatb').val() || $('#formatb').val().replace(/\s+$/, '') == '') {
				alert("Please upload Format B document");
				$('#formatb').focus();
				return false;
			}
		}
		document.getElementById('modesubmit').value = "SUBMIT";
		document.getElementById('form_acc').submit();
	}
	
}
function check_documents_closing(){
	if(validate()){
		if ( !$( "#formata_doc" ).length ) {
			if (!$('#formata').val() || $('#formata').val().replace(/\s+$/, '') == '') {
				alert("Please upload Format A document");
				$('#formata').focus();
				return false;
			}
		}
		if ( !$( "#formatb_doc" ).length ) {
			if (!$('#formatb').val() || $('#formatb').val().replace(/\s+$/, '') == '') {
				alert("Please upload Format B document");
				$('#formatb').focus();
				return false;
			}
		}
		
		if (!$('#claim_number').val() || $('#claim_number').val().replace(/\s+$/, '') == '') {
			alert("Please enter claim number");
			$('#claim_number').focus();
			return false;
		}
		if($('#claim_settled').val() == '0'){
			alert("Please select claim settled as paid or no claim");
			$('#claim_settled').focus();
			return false;
		}
		if($('#claim_settled').val() == '1'){
				if (!$('#death_amount').val() || $('#death_amount').val().replace(/\s+$/, '') == '') {
					alert("Please enter death amount");
					$('#death_amount').focus();
					return false;
				}
				else if (numOnly('death_amount')) {
					alert('Invalid characters used in amount for death');
					$('#death_amount').focus();
					return false;
				}
				if (!$('#injury_amount').val() || $('#injury_amount').val().replace(/\s+$/, '') == '') {
					alert("Please enter injury amount");
					$('#injury_amount').focus();
					return false;
				}
				else if (numOnly('injury_amount')) {
					alert('Invalid characters used in amount for injury');
					$('#injury_amount').focus();
					return false;
				}
				if (!$('#property_damage_amount').val() || $('#property_damage_amount').val().replace(/\s+$/, '') == '') {
					alert("Please enter property damage amount");
					$('#property_damage_amount').focus();
					return false;
				}
				else if (numOnly('property_damage_amount')) {
					alert('Invalid characters used in amount for property damage');
					$('#property_damage_amount').focus();
					return false;
				}
				if ( !$( "#acknowledgement_doc" ).length ) {
					if (!$('#acknowledgement').val() || $('#acknowledgement').val().replace(/\s+$/, '') == '') {
						alert("Please upload Acknowledgement document");
						$('#acknowledgement').focus();
						return false;
					}	
				}
				
			}
		document.getElementById('modesubmit').value = "CLOSE";
		document.getElementById('form_acc').submit();
	}
	
}
function alphaOnly(id) {
	if (id == '')
		return;
	var regex = /^[a-zA-Z ]*$/;
	var idval = document.getElementById(id).value;
	if (regex.test(idval) == false) {
		return true;
	}
	else
		return false;
}

function alphaNum(id) {
	if (id == '')
		return;
	var regex = /^[a-zA-Z0-9 ]*$/;
	var idval = document.getElementById(id).value;
	if (regex.test(idval) == false) {
		return true;
	}
	else
		return false;
}

function numOnly(id) {
	if (id == '')
		return;
	var regex = /^[0-9]*$/;
	var idval = document.getElementById(id).value;
	if (regex.test(idval) == false) {
		return true;
	}
	else
		return false;
}




var lastval = "";
var restimer;
function typingTrack(val)
{
	lastval = val;
	//var isanotherkey=true;
	try
	{
		clearTimeout(restimer);
		restimer = setTimeout("showSearchResults()", 500);
	}
	catch (e)
	{
	}
}

function showSearchResults()
{
	autoComplete(lastval, "auto_complete_div");
}

function  getAttrCause(v){
	url = "get_attr.php?id=" + v;
	div_id = "attr_div";
	ajaxpage(url, div_id);
}
function get_cause(v){
	document.getElementById('cause_attr').innerHTML = '<option>--select--</option>';
	url = "get_cause.php?id=" + v;
	div_id = "cause_div";
	ajaxpage(url, div_id);
}

function get_district(v){
	document.getElementById('distributor').innerHTML = '<option>--select--</option>';
	url = "get_district_by_state.php?id=" + v;
	div_id = "district_div";
	ajaxpage(url, div_id);
}
function get_major_supply_point(v)
{
	document.getElementById('major_supply_point').innerHTML = '<option>--select--</option>';
	url = "get_major_supply_point_by_state.php?id=" + v;
	div_id = "major_supply_point_div";
	ajaxpage(url, div_id);
}
function edit_attended()
{
	if(document.getElementById('any_leakage_complaint_lodged').value == '1')
	{
		$("#any_leakage_complaint_attended").attr( 'disabled', false )
	}
	else{
		$("#any_leakage_complaint_attended").attr( 'disabled', true )
	}
}
function getdistributors (v){
	url = "getdistributors.php?id=" + v;
	div_id = "dist_div";
	ajaxpage(url, div_id);
}
function ajaxpage(url, containerid)
{

	var page_request = false;
	if (window.XMLHttpRequest) // if Mozilla, Safari etc
		page_request = new XMLHttpRequest()
	else if (window.ActiveXObject) { // if IE

		try {
			page_request = new ActiveXObject("Msxml2.XMLHTTP")

		}
		catch (e) {
			try {
				page_request = new ActiveXObject("Microsoft.XMLHTTP")

			}
			catch (e) {
			}
		}
	}
	else
		return false

	page_request.onreadystatechange = function () {
		loadpage(page_request, containerid)
	}
	page_request.open('GET', url, true)
	page_request.send(null)
}

function loadpage(page_request, containerid){
	if (page_request.readyState == 4 && (page_request.status == 200 || window.location.href.indexOf("http") == -1)){
		document.getElementById(containerid).innerHTML = page_request.responseText;
	}
}
function showhide(val,div){
   if(val == '1'){
      document.getElementById(div).style.display = "block";
	  document.getElementById('reason_div').style.display = "none";
	 
   }
   else if(val == '0'){
	   document.getElementById('reason_div').style.display = "block";
	   document.getElementById(div).style.display = "none";
   }
   else{
	  document.getElementById(div).style.display = "none";
	  document.getElementById('reason_div').style.display = "none";
   }
}
</script>
<style type="text/css">
    .formcontainer input[type='text'], .passwordinput{
        width:230px;
    }
    .formcontainer span{
        color:#ff0000;
    }
    .registation_table{
        display:none;
    }

    .error_box {
        background: none repeat scroll 0 0 #fde4e1;
        border: 1px solid #b16a6c;
        border-radius: 3px;
        color: #630e18;
        margin: 0 0 10px;
        padding: 3px 10px;
    }

	#auto_complete_div {
        border-bottom: #C1C1C1 1px solid;
        border-left: #C1C1C1 1px solid;
        border-right: #C1C1C1 1px solid;
        background: #FFF;
        z-index: 1000;
        height: 100px;
        overflow-y: scroll;
        overflow-x: hidden;
        width: 255px;
        position: absolute;
        display: none;
    }

	#ifscCodeorMICRBox{
		display:none;
	}
	#iinNumberBox{
		display:none;
	}
	.formcontainer label { width:200px; float:left;}
	.disabledclass{
		cursor: not-allowed !important;
		background-color: #EEE !important;
		opacity: 1 !important;
	}
</style>
<?php
   // get state
   $distq = mysql_query("select id,dealer_name from gb_company_dealer where sz_id = '".$area_id."' and company_id = '1' and dealer_status = '1' order by dealer_name");
?>

<div class="innerheadercontainer2">
    <div class="borderbottom"></div>
    <div class="midcontainer innerheadermain">
        <div class="innerfortunelogo3"><img src="<?= SERVER_URL ?>images/fortunelogo.png"  alt="Fortune Logo" /></div>
        <div class="innerheaderleft">
            <h1>Accident Insurance</h1>
        </div>
    </div>
</div>
<div class="clear" style="height:20px;"></div>
<div class="innnerpagecontainer">
    <div class="midcontainer">
        <div class="innerpagecontent">
        <div class="panel">
        <div class="formcontainer">
             <?php
				if (is_array($errors) && count($errors) > 0) {
                    foreach ($errors as $errorval) {
                        echo '<div class="error_box">' . $errorval . '</div>';
                    }
                }

                if ($_SESSION['successMsg']) {
                    echo '<div style="color:#093; font-weight:bold; margin-bottom:10px;" align="center">' . $_SESSION['successMsg'] . '</div>';
                    unset($_SESSION['successMsg']);
                }
                ?>
        <form name='form_acc' action='<?php echo $self;?>' method='post' enctype="multipart/form-data" id="form_acc">
		<input name="mode" type="hidden" id="mode" value="<?php if(isset($acc_details)) { ?>Update<?php } else { ?>Save<?php } ?>">
		<input name="modesubmit" type="hidden" id= "modesubmit"/>
		<?php
		if(isset($_REQUEST['id'])){
			?>
			<input type="hidden" name="acc_id" id="acc_id" value="<?=$id;?>"/>
			<?php
		}
		?>
		<h2 style="text-align:center;font-size:20px;font-family:pt_sansbold; font-weight:normal; padding:8px 10px; color:#FFF; background:#ff741c; text-align:left; ">Accident</h2>
		<div class="clear" style="height:20px;"></div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablecontainer">
			<tr>
                <td valign="middle" width="50%"><label>State<span style="color:#ff0000;">*</span></label>
				 <select <?php echo(@$status_db != '0' && isset($acc_details))?"disabled":"";?> name="state_id" id="state_id" style="background:#FFF; margin-bottom:0px;width:253px;"  class="selectbox" onChange="get_district(this.value);">
				 <option value="">--select--</option>
                 <?php
					if(isset($acc_details) && $acc_details['state_id'] != ''){
						$sql = "select * from gb_state where id = '".$acc_details['state_id']."' order by state_name";
					}else{
						$sql = "select * from gb_state as t1 where EXISTS (select distinct state_id from gb_districts_all as t2 where t1.id = t2.state_id and EXISTS (select distinct district_id_new from gb_company_dealer as gcd where dealer_status = '1' and company_id = '1' $str and gcd.district_id_new = t2.id))  order by state_name";
					}
					
				    $res = mysql_query($sql);
					while ($row = mysql_fetch_array($res)) {
						$sel = '';
						if ($_REQUEST["state_id"] == $row["id"] && !isset($acc_details))
							$sel = 'selected="selected"';
						if ($acc_details["state_id"] == $row["id"])
							$sel = 'selected="selected"';
						echo '<option value="' . $row['id'] . '" ' . $sel . '>' . $row['state_name'] . '</option>';
					}
				 ?>
                </select>
				</td>
                <td valign="middle"><label>District<span style="color:#ff0000;">*</span></label>
				 <span id="district_div">  
					<?php
					if(isset($acc_details) && $acc_details['state_id'] != '' && !isset($_POST['state_id'])){
						$sqlde="select id,district_name from gb_districts_all  where state_id=".$acc_details['state_id'];
					}
					else if(isset($_REQUEST['state_id'])){
                        $sqlde="select id,district_name from gb_districts_all  where state_id=".$_POST['state_id'];
						
					}
					$res=mysql_query($sqlde);
                    ?>
                    <select <?php echo(@$status_db != '0' && isset($acc_details))?"disabled":"";?> name="district" id="district" style="background:#FFF; margin-bottom:0px;width:253px;"  class="selectbox" onChange="getdistributors(this.value)">									
                    	<option value="">--select--</option>
                         <?php  
							if($res){
								 while ($districts = mysql_fetch_array($res)) {
									 ?>
									 <option value="<?php echo $districts['id']; ?>" <?php echo (isset($acc_details['district']) && $acc_details['district'] == $districts['id'])?'selected':'';?><?php echo (isset($_REQUEST['district']) && $_REQUEST['district'] == $districts['id'] && !isset($acc_details))?'selected':'';?>><?php echo $districts['district_name']; ?></option>
									 <?php
								 }
							}
							?>
                    </select>
                </span>
				</td>

              </tr>
              <tr>
                <td valign="middle" width="50%"><label>Location of accident<span style="color:#ff0000;">*</span></label> <input <?php echo(@$status_db != '0' && isset($acc_details))?"readonly":"";?> type="text" style="background:#FFF; margin-bottom:0px;<?php echo(@$status_db != '0' && isset($acc_details))?"color:graytext":"";?>" name="location" id="location" value="<?php echo (isset($acc_details) && $acc_details['location'] != '')?$acc_details['location']:''; ?><?php echo (isset($_POST['location']) && $_POST['location'] != '' && !isset($acc_details))?$_POST['location']:''; ?>"/></td>
                <td valign="middle"><label>Name of Consumer<span style="color:#ff0000;">*</span></label> 
               <input type="text" <?php echo(@$status_db != '0' && isset($acc_details))?"readonly":"";?> style="background:#FFF; margin-bottom:0px;<?php echo(@$status_db != '0' && isset($acc_details))?"color:graytext":"";?>" name="consumer_name" id="consumer_name" value="<?php echo (isset($acc_details) && $acc_details['consumer_name'] != '')?$acc_details['consumer_name']:''; ?><?php echo (isset($_POST['consumer_name']) && $_POST['consumer_name'] != ''  && !isset($acc_details))?$_POST['consumer_name']:''; ?>"/>
                </td>
              </tr>
              <tr>
               <td valign="middle"><label>Consumer ID<span style="color:#ff0000;">*</span></label> <input onkeydown="return isNumber(event);" maxlength="16" <?php echo (@$status_db != '0' && isset($acc_details))?"readonly":"";?> type="text" style="background:#FFF; margin-bottom:0px;<?php echo (@$status_db != '0' && isset($acc_details))?"color:graytext":"";?>" name="consumer_number" id="consumer_number" value="<?php echo (isset($acc_details) && $acc_details['consumer_number'] != '')?$acc_details['consumer_number']:''; ?><?php echo (isset($_POST['consumer_number']) && $_POST['consumer_number'] != '' && !isset($acc_details))?$_POST['consumer_number']:''; ?>"/></td>
                <td valign="middle"><label>Name of Distributor<span style="color:#ff0000;">*</span></label> 
                <span id="dist_div">  
					<?php
					if(isset($acc_details) && $acc_details['district'] != '' && !isset($_POST['district'])){
						$sqlde = "select id,dealer_name from gb_company_dealer where district_id_new = '".$acc_details['district']."' and company_id = '1' and dealer_status = '1' order by dealer_name";
					}
                    else if(isset($_POST['district'])){
                        $sqlde = "select id,dealer_name from gb_company_dealer where district_id_new = '".$_POST['district']."' and company_id = '1' and dealer_status = '1' order by dealer_name";
						
					}
					
					$res=mysql_query($sqlde);
						
                    ?>
                    <select <?php echo(@$status_db != '0' && isset($acc_details))?"disabled":"";?> name="distributor" id="distributor" style="background:#FFF; margin-bottom:0px;width:253px;"  class="selectbox">									
                    	<option value="">--select--</option>
                         <?php  
							if($res){
								 while ($dist = mysql_fetch_array($res)) {
									 ?>
									 <option value="<?php echo $dist['id']; ?>" <?php echo (isset($acc_details['distributor']) && $acc_details['distributor'] == $dist['id'])?'selected':'';?><?php echo (isset($_POST['distributor']) && $_POST['distributor'] == $dist['id'] && !isset($acc_details))?'selected':'';?>><?php echo $dist['dealer_name']; ?></option>
									 <?php
								 }
							}
							?>
                    </select>
                </span>
                
                </td>
               
              </tr>
			  <tr>
					<td valign="middle" colspan="2">
						<label>Customer Category<span style="color:#ff0000;">*</span></label>
						<select name="customer_category" id="customer_category" style="background:#FFF; margin-bottom:0px;width:253px;"  class="selectbox" >
						<option value="">--select--</option>
						<option value="PMUY" <?php echo (isset($acc_details['customer_category']) && $acc_details['customer_category'] == 'PMUY')?'selected':'';?><?php echo (isset($_POST['customer_category']) && $_POST['customer_category'] == 'PMUY' && !isset($acc_details))?'selected':'';?>>PMUY</option>
						<option value="Non-PMUY" <?php echo (isset($acc_details['customer_category']) && $acc_details['customer_category'] == 'Non-PMUY')?'selected':'';?><?php echo (isset($_POST['customer_category']) && $_POST['customer_category'] == 'Non-PMUY' && !isset($acc_details))?'selected':'';?>>Non-PMUY</option>
					</select>
					</td>
			  </tr>
				<tr>
					<td valign="middle" width="50%">
						<label>SV Number<span style="color:#ff0000;">*</span></label> 
						<input  type="text" style="background:#FFF; margin-bottom:0px;<?php echo(@$status_db != '0' && isset($acc_details))?"color:graytext":"";?>" name="sv_number" id="sv_number" value="<?php echo (isset($acc_details) && $acc_details['sv_number'] != '')?$acc_details['sv_number']:''; ?><?php echo (isset($_POST['sv_number']) && $_POST['sv_number'] != '' && !isset($acc_details))?$_POST['sv_number']:''; ?>"/>
					</td>
                <td valign="middle">
					<label>Date of SV<span style="color:#ff0000;">*</span></label> 
					<input type="text" name="sv_date" id="sv_date" readonly class="requireddata" style="width:150px;" placeholder="Date of SV" maxlength="35" value="<?php echo (isset($acc_details) && $acc_details['sv_date'] != '')?$acc_details['sv_date']:''; ?><?php echo (isset($_POST['sv_date']) && $_POST['sv_date'] != ''&& !isset($acc_details))?$_POST['sv_date']:''; ?>" /> &nbsp;&nbsp; <img src="../manager/images/Cal.gif" name="cmdbusinessunit" id="cmdbusinessunit" onclick="return displayCalendar(document.form_acc.sv_date, 'dd/mm/yyyy', this)" style="margin:0 10px 0 0;" value=""/> 
					
                </td>
              </tr>
				
               <tr>
               
                <td valign="middle"><label>Date of Accident<span style="color:#ff0000;">*</span></label>  
				<input type="text" name="date_accident" id="date_accident" readonly class="requireddata" style="width:150px;<?php echo(@$status_db != '0' && isset($acc_details))?"color:graytext":"";?>" placeholder="Date of Accident" maxlength="35" value="<?php echo (isset($acc_details) && $acc_details['date_accident'] != '')?$acc_details['date_accident']:''; ?><?php echo (isset($_POST['date_accident']) && $_POST['date_accident'] != ''&& !isset($acc_details))?$_POST['date_accident']:''; ?>" /> &nbsp;&nbsp; <?php if((isset($acc_details) && $status_db == '0') || !isset($acc_details)) { ?><img src="../manager/images/Cal.gif" name="cmdbusinessunit" id="cmdbusinessunit" onclick="return displayCalendar(document.form_acc.date_accident, 'dd/mm/yyyy', this)" style="margin:0 10px 0 0;" value=""/> <?php } ?>
				</td>
				
				<td valign="middle">
				<?php 
				if(isset($acc_details) && $acc_details['time_accident'] != ''){
					$timeArr = explode(':',$acc_details['time_accident']);
					
					$hour_accident = trim($timeArr[0]);
					$time_accident = trim($timeArr[1]);
				}
				?>
				<label>Time of Accident<span style="color:#ff0000;">*</span></label>
				<select name="hour_accident" id="hour_accident" style="background:#FFF; margin-bottom:0px;width:80px;" class="selectbox">
					<option value="">--Hours--</option>
					<?php for($i=1;$i<=23;$i++){ 
					$i = str_pad($i, 2, '0', STR_PAD_LEFT);
					?>
					<option value="<?php echo $i;?>" <?php echo (isset($acc_details) && $hour_accident == $i)?'selected':'';?>><?php echo $i;?></option>
					<?php } ?>
				</select>
				:
				<select name="time_accident" id="time_accident" style="background:#FFF; margin-bottom:0px;width:88px;" class="selectbox">
					<option value="">--Minutes--</option>
					<?php for($j=0;$j<=59;$j++){
						$j = str_pad($j, 2, '0', STR_PAD_LEFT);
					?>
						<option value="<?php echo $j; ?>" <?php echo (isset($acc_details) && $time_accident == $j)?'selected':'';?>><?php echo $j; ?></option>
					<?php } ?>
				</select>
				</td>
				
               </tr>
				<tr>
               
                  <td ><label>Cylinder exploded<span style="color:#ff0000;">*</span></label>
					   <select <?php echo(@$status_db != '0' && isset($acc_details))?"disabled":"";?> name="cylinder_burst" id="cylinder_burst" style="background:#FFF; margin-bottom:0px;width:253px;"  class="selectbox" >
						<option value="">--select--</option>
						<option value="1" <?php echo (isset($acc_details['cylinder_burst']) && $acc_details['cylinder_burst'] == '1')?'selected':'';?><?php echo (isset($_POST['cylinder_burst']) && $_POST['cylinder_burst'] == '1' && !isset($acc_details))?'selected':'';?>>Yes</option>
						<option value="0" <?php echo (isset($acc_details['cylinder_burst']) && $acc_details['cylinder_burst'] == '0')?'selected':'';?><?php echo (isset($_POST['cylinder_burst']) && $_POST['cylinder_burst'] == '0' && !isset($acc_details))?'selected':'';?>>No</option>
					</select>
					</td>
               
               <td valign="middle"><label>Brief on Accident<span style="color:#ff0000;">*</span></label>
                <textarea <?php echo(@$status_db != '0' && isset($acc_details))?"readonly":"";?> name="brief_accident" id="brief_accident" rows="5" cols="20" style="width:228px !important;background:#fff;<?php echo(@$status_db != '0' && isset($acc_details))?"color:graytext":"";?>"><?php echo (isset($acc_details['brief_accident']) && $acc_details['brief_accident'] != '')?$acc_details['brief_accident']:'';?><?php echo (isset($_POST['brief_accident']) && $_POST['brief_accident'] != '' && !isset($acc_details))?$_POST['brief_accident']:'';?></textarea>
                </td>

              </tr>
			  <tr>
				<td valign="middle">
					<label>Installation done as per procedure
						<span style="color:#ff0000;">*</span>
					</label> 
					<select <?php echo(@$status_db != '0' && isset($acc_details))?"readonly":"";?> name="installation_done" id="installation_done" style="background:#FFF; margin-bottom:0px;width:253px;"  class="selectbox">
						<option value="">--select--</option>
						<option value="1" <?php echo (isset($acc_details['installation_done']) && $acc_details['installation_done'] == '1')?'selected':'';?><?php echo (isset($_POST['installation_done']) && $_POST['installation_done'] == '1' && !isset($acc_details))?'selected':'';?>>Yes</option>
						<option value="0" <?php echo (isset($acc_details['installation_done']) && $acc_details['installation_done'] == '0')?'selected':'';?><?php echo (isset($_POST['installation_done']) && $_POST['installation_done'] == '0' && !isset($acc_details))?'selected':'';?>>No</option>
					</select>
				</td>
				<td valign="middle">
					<label>Date of last refill supply 
						<span style="color:#ff0000;">*</span>
					</label> 
					<input type="text" name="last_refill_supply_date" id="last_refill_supply_date" readonly class="requireddata" style="width:150px;<?php echo(@$status_db != '0' && isset($acc_details))?"color:graytext":"";?>" placeholder="Date of last refill supply" maxlength="35" value="<?php echo (isset($acc_details) && $acc_details['last_refill_supply_date'] != '')?$acc_details['last_refill_supply_date']:''; ?><?php echo (isset($_POST['last_refill_supply_date']) && $_POST['last_refill_supply_date'] != ''&& !isset($acc_details))?$_POST['last_refill_supply_date']:''; ?>" /> &nbsp;&nbsp; <?php if((isset($acc_details) && $status_db == '0') || !isset($acc_details)) { ?><img src="../manager/images/Cal.gif" name="cmdbusinessunit" id="cmdbusinessunit" onclick="return displayCalendar(document.form_acc.last_refill_supply_date, 'dd/mm/yyyy', this)" style="margin:0 10px 0 0;" value=""/> <?php } ?>
				</td>
			  </tr>
			  <tr>
				<td valign="middle">
					<label>Date of last change of LPG Hose  
						<span style="color:#ff0000;">*</span>
					</label> 
					<input type="text" name="last_change_of_lpg_hose_date" id="last_change_of_lpg_hose_date" readonly class="requireddata" style="width:150px;<?php echo(@$status_db != '0' && isset($acc_details))?"color:graytext":"";?>" placeholder="Date of last change of LPG Hose" maxlength="35" value="<?php echo (isset($acc_details) && $acc_details['last_change_of_lpg_hose_date'] != '')?$acc_details['last_change_of_lpg_hose_date']:''; ?><?php echo (isset($_POST['last_change_of_lpg_hose_date']) && $_POST['last_change_of_lpg_hose_date'] != ''&& !isset($acc_details))?$_POST['last_change_of_lpg_hose_date']:''; ?>" /> &nbsp;&nbsp; <?php if((isset($acc_details) && $status_db == '0') || !isset($acc_details)) { ?><img src="../manager/images/Cal.gif" name="cmdbusinessunit" id="cmdbusinessunit" onclick="return displayCalendar(document.form_acc.last_change_of_lpg_hose_date, 'dd/mm/yyyy', this)" style="margin:0 10px 0 0;" value=""/> <?php } ?>
				</td>
				<td valign="middle">
					<label>Person using LPG at the time of accident 
						<span style="color:#ff0000;">*</span>
					</label> 
					<select <?php echo(@$status_db != '0' && isset($acc_details))?"readonly":"";?> name="person_using_lpg" id="person_using_lpg" style="background:#FFF; margin-bottom:0px;width:253px;"  class="selectbox">
						<option value="">--select--</option>
						<option value="Adult" <?php echo (isset($acc_details['person_using_lpg']) && $acc_details['person_using_lpg'] == 'Adult')?'selected':'';?><?php echo (isset($_POST['person_using_lpg']) && $_POST['person_using_lpg'] == 'Adult' && !isset($acc_details))?'selected':'';?>>Adult</option>
						<option value="Minor" <?php echo (isset($acc_details['person_using_lpg']) && $acc_details['person_using_lpg'] == 'Minor')?'selected':'';?><?php echo (isset($_POST['person_using_lpg']) && $_POST['person_using_lpg'] == 'Minor' && !isset($acc_details))?'selected':'';?>>Minor </option>
					</select>
				</td>
			  </tr>
			  <tr>
				<td valign="middle">
					<label>Was there any leakage complaint lodged immediately prior to accident 
						<span style="color:#ff0000;">*</span>
					</label> 
					<select <?php echo(@$status_db != '0' && isset($acc_details))?"readonly":"";?> name="any_leakage_complaint_lodged" id="any_leakage_complaint_lodged" style="background:#FFF; margin-bottom:0px;width:253px;"  class="selectbox" onChange="edit_attended()">
						<option value="">--select--</option>
						<option value="1" <?php echo (isset($acc_details['any_leakage_complaint_lodged']) && $acc_details['any_leakage_complaint_lodged'] == '1')?'selected':'';?><?php echo (isset($_POST['any_leakage_complaint_lodged']) && $_POST['any_leakage_complaint_lodged'] == '1' && !isset($acc_details))?'selected':'';?>>Yes</option>
						<option value="0" <?php echo (isset($acc_details['any_leakage_complaint_lodged']) && $acc_details['any_leakage_complaint_lodged'] == '0')?'selected':'';?><?php echo (isset($_POST['any_leakage_complaint_lodged']) && $_POST['any_leakage_complaint_lodged'] == '0' && !isset($acc_details))?'selected':'';?>>No</option>
					</select>
				</td>
				<td valign="middle">
					<label>Was it attended within prescribed time 
						<span style="color:#ff0000;">*</span>
					</label> 
					<select <?php echo(@$status_db != '0' && isset($acc_details))?"readonly":"";?> name="any_leakage_complaint_attended" id="any_leakage_complaint_attended" style="background:#FFF; margin-bottom:0px;width:253px;"  class="selectbox">
						<option value="">--select--</option>
						<option value="1" <?php echo (isset($acc_details['any_leakage_complaint_attended']) && $acc_details['any_leakage_complaint_attended'] == '1')?'selected':'';?><?php echo (isset($_POST['any_leakage_complaint_attended']) && $_POST['any_leakage_complaint_attended'] == '1' && !isset($acc_details))?'selected':'';?>>Yes</option>
						<option value="0" <?php echo (isset($acc_details['any_leakage_complaint_attended']) && $acc_details['any_leakage_complaint_attended'] == '0')?'selected':'';?><?php echo (isset($_POST['any_leakage_complaint_attended']) && $_POST['any_leakage_complaint_attended'] == '0' && !isset($acc_details))?'selected':'';?>>No</option>
					</select>
				</td>
			  </tr>	
			  <tr>
				<td valign="middle" width="50%"><label>State of Major Supply Point<span style="color:#ff0000;">*</span></label>
				 <select <?php echo(@$status_db != '0' && isset($acc_details))?"disabled":"";?> name="major_supply_point_state_id" id="major_supply_point_state_id" style="background:#FFF; margin-bottom:0px;width:253px;"  class="selectbox" onChange="get_major_supply_point(this.value);">
				 <option value="">--select--</option>
                 <?php
					if(isset($acc_details) && $acc_details['major_supply_point_state_id'] != ''){
						$sql = "select * from gb_state where id = '".$acc_details['major_supply_point_state_id']."' order by state_name";
					}else{
						$sql = "select * from gb_state as t1 where EXISTS (select distinct state_id from gb_bottling_point as t2 where t1.id = t2.state_id)  order by state_name";
					}
					
				    $res = mysql_query($sql);
					while ($row = mysql_fetch_array($res)) {
						$sel = '';
						if ($_REQUEST["major_supply_point_state_id"] == $row["id"] && !isset($acc_details))
							$sel = 'selected="selected"';
						if ($acc_details["major_supply_point_state_id"] == $row["id"])
							$sel = 'selected="selected"';
						echo '<option value="' . $row['id'] . '" ' . $sel . '>' . $row['state_name'] . '</option>';
					}
				 ?>
                </select>
				</td>
                <td valign="top" colspan="1">
					<label>Major Supply Point 
					<span style="color:#ff0000;">*</span>
					</label>
					<span id="major_supply_point_div">  
						<?php
						if(isset($acc_details) && $acc_details['major_supply_point_state_id'] != '' && !isset($_POST['major_supply_point_state_id'])){
							$sqlde="select id,bottling_point_name from gb_bottling_point  where state_id=".$acc_details['major_supply_point_state_id'];
						}
						else if(isset($_REQUEST['major_supply_point_state_id'])){
							$sqlde="select id,bottling_point_name from gb_bottling_point  where state_id=".$_POST['major_supply_point_state_id'];
							
						}
						$res=mysql_query($sqlde);
						?>
						<select <?php echo(@$status_db != '0' && isset($acc_details))?"disabled":"";?> name="major_supply_point" id="major_supply_point" style="background:#FFF; margin-bottom:0px;width:253px;"  class="selectbox" onChange="getdistributors(this.value)">									
							<option value="">--select--</option>
							 <?php  
								if($res){
									 while ($majorSupplyPoint = mysql_fetch_array($res)) {
										 ?>
										 <option value="<?php echo $majorSupplyPoint['id']; ?>" <?php echo (isset($acc_details['major_supply_point']) && $acc_details['major_supply_point'] == $majorSupplyPoint['id'])?'selected':'';?><?php echo (isset($_REQUEST['major_supply_point']) && $_REQUEST['major_supply_point'] == $majorSupplyPoint['id'] && !isset($acc_details))?'selected':'';?>><?php echo $majorSupplyPoint['bottling_point_name']; ?></option>
										 <?php
									 }
								}
								?>
						</select>
					</span>
				</td>
			  </tr>
              
              <tr>
               
                <td valign="top"><label>Cause of Accident<span style="color:#ff0000;">*</span></label><br/><br/> 
			   <select <?php echo(@$status_db != '0' && isset($acc_details))?"disabled":"";?> name="accident_category" id="accident_category" style="background:#FFF; margin-bottom:0px;width:122px;"  class="selectbox" onChange="get_cause(this.value);">
                	<option value="">--select--</option>
                    <?php 
					$sqlcat = mysql_query("select * from gb_accident_causes where parent = 0");
					if(mysql_num_rows($sqlcat) >= 0){
						while($row = mysql_fetch_array($sqlcat)){
							?>
								<option value="<?=$row['id'];?>" <?php echo (isset($acc_details['accident_category']) && $acc_details['accident_category'] == $row['id'])?'selected':'';?><?php echo (isset($_REQUEST['accident_category']) && $_REQUEST['accident_category'] == $row['id'] && !isset($acc_details))?'selected':'';?>><?=$row['name'];?></option>
							<?php
						}
					}
					?>
                </select>
				
				<span id="cause_div">  
					<?php
					if(isset($acc_details) && $acc_details['accident_category'] != '' && !isset($_POST['accident_category'])){
						$sqlde="select id,name from gb_accident_causes  where parent=".$acc_details['accident_category'];
						
					}
					else if(isset($_REQUEST['accident_category'])){
                        $sqlde="select id,name from gb_accident_causes  where parent=".$_POST['accident_category'];
						
					}
					$res=mysql_query($sqlde);
                    ?>
                    <select <?php echo(@$status_db != '0' && isset($acc_details))?"disabled":"";?> name="cause_accident" id="cause_accident" style="background:#FFF; margin-bottom:0px;width:150px;"  class="selectbox" onChange="getAttrCause(this.value)">									
                    	<option value="">--select--</option>
                         <?php  
							if($res){
								 while ($causes = mysql_fetch_array($res)) {
									 ?>
									 <option value="<?php echo $causes['id']; ?>" <?php echo (isset($acc_details['cause_accident']) && $acc_details['cause_accident'] == $causes['id'])?'selected':'';?><?php echo (isset($_REQUEST['cause_accident']) && $_REQUEST['cause_accident'] == $causes['id']  && !isset($acc_details))?'selected':'';?>><?php echo $causes['name']; ?></option>
									 <?php
								 }
							}
							?>
                    </select>
                </span>
				<span id="attr_div">  
					<?php
					if(isset($acc_details) && $acc_details['cause_accident'] != '' && !isset($_POST['cause_accident'])){
						$sqlde="select id,attr from gb_accident_causes  where id=".$acc_details['cause_accident'];
						
					}
					else if(isset($_REQUEST['cause_accident'])){
                        $sqlde="select id,attr from gb_accident_causes  where id=".$_POST['cause_accident'];
						
					}
					$res=mysql_query($sqlde);
                    ?>
                    <select <?php echo(@$status_db != '0' && isset($acc_details))?"disabled":"";?> name="cause_attr" id="cause_attr" style="background:#FFF; margin-bottom:0px;width:140px;"  class="selectbox">									
                    	<option value="">--select--</option>
                         <?php  
							if($res){
								 while ($causeattr = mysql_fetch_array($res)) {
									 ?>
									 <option value="<?php echo $causeattr['attr']; ?>" <?php echo (isset($acc_details['cause_attr']) && $acc_details['cause_attr'] == $causeattr['attr'])?'selected':'';?><?php echo (isset($_REQUEST['cause_attr']) && $_REQUEST['cause_attr'] == $causeattr['attr'] && !isset($acc_details))?'selected':'';?>><?php echo ($causeattr['attr'] == '1')?'Consumer':($causeattr['attr'] == '2'?'Equipment':''); ?></option>
									 <?php
								 }
							}
							?>
                    </select>
                </span>
				</td>
				<td valign="middle"><label>No. of fatalities<span style="color:#ff0000;">*</span></label> <input <?php echo(@$status_db != '0' && isset($acc_details))?"readonly":"";?> type="text" style="background:#FFF; margin-bottom:0px;<?php echo(@$status_db != '0' && isset($acc_details))?"color:graytext":"";?>" name="no_deaths" id="no_deaths" value="<?php echo (isset($acc_details['no_deaths']) && $acc_details['no_deaths'] != '')?$acc_details['no_deaths']:''; ?><?php echo (isset($_POST['no_deaths']) && $_POST['no_deaths'] != '' && !isset($acc_details))?$_POST['no_deaths']:''; ?>"/></td>
            </tr>

            <tr>
                
				<td valign="middle"><label>No. of persons injured<span style="color:#ff0000;">*</span></label> 
                  <input <?php echo(@$status_db != '0' && isset($acc_details))?"readonly":"";?> type="text" style="background:#FFF; margin-bottom:0px;<?php echo(@$status_db != '0' && isset($acc_details))?"color:graytext":"";?>" name="no_injuries" id="no_injuries" value="<?php echo (isset($acc_details['no_injuries']) && $acc_details['no_injuries'] != '')?$acc_details['no_injuries']:''; ?><?php echo (isset($_POST['no_injuries']) && $_POST['no_injuries'] != '' && !isset($acc_details))?$_POST['no_injuries']:''; ?>"/>
				</td>
				<td valign="middle"><label>Property damage<span style="color:#ff0000;">*</span></label> 
				<select <?php echo(@$status_db != '0' && isset($acc_details))?"readonly":"";?> name="property_damage" id="property_damage" style="background:#FFF; margin-bottom:0px;width:253px;"  class="selectbox">
                	<option value="">--select--</option>
                    <option value="1" <?php echo (isset($acc_details['property_damage']) && $acc_details['property_damage'] == '1')?'selected':'';?><?php echo (isset($_POST['property_damage']) && $_POST['property_damage'] == '1' && !isset($acc_details))?'selected':'';?>>Yes</option>
                    <option value="0" <?php echo (isset($acc_details['property_damage']) && $acc_details['property_damage'] == '0')?'selected':'';?><?php echo (isset($_POST['property_damage']) && $_POST['property_damage'] == '0' && !isset($acc_details))?'selected':'';?>>No</option>
			    </select>
				</td>
            </tr>
		    <tr>
				
				<td valign="middle"><label>Upload format A</label>
				<input type="file" name="formata" id="formata" <?php echo(@$status_db != '0' && isset($acc_details))?"disabled":"";?>/>
				<?php
				if(isset($acc_details) && $acc_details['formata'] != ''){
					?>
					<input type="hidden" name="formata_doc" id="formata_doc" value="<?=$acc_details['formata'];?>"/>
					<a href="<?php echo SERVER_URL ;?>cert_download.php?cidint=<?php echo $acc_details['id']; ?>&tabname=3" target="_blank">Format A</a>
					<?php
				}
				?>
                </td>
             <td>
               	 <label>Upload format B </label> 
                 <input type="file" name="formatb" id="formatb" <?php echo(@$status_db != '0' && isset($acc_details))?"disabled":"";?>/>
				 <?php
					if(isset($acc_details) && $acc_details['formatb'] != ''){
						?>
						<input type="hidden" name="formatb_doc" id="formatb_doc" value="<?=$acc_details['formatb'];?>"/>
						<a href="<?php echo SERVER_URL ;?>cert_download.php?cidint=<?php echo $acc_details['id']; ?>&tabname=4" target="_blank">Format B</a>
						<?php
					}
				 ?>
               </td>   
			</tr>
			
			</table>
			
            <div class="clear" style="height:20px;"></div>
			<h2 style="text-align:center;font-size:20px;font-family:pt_sansbold; font-weight:normal; padding:8px 10px; color:#FFF; background:#ff741c; text-align:left; ">Insurance</h2>
            <div class="clear" style="height:20px;"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablecontainer">
			
			<tr>
			<td>
				<table border="0">
					<tr>
						<td>
							<label>Intimation to Insurance Company by distributor under Policy taken by them<span style="color:#ff0000;">*</span></label> 
							<select <?php echo(@$status_db == '2' && isset($acc_details))?"disabled":"";?> name="intim_date_dist" id="intim_date_dist" style="background:#FFF; margin-bottom:0px;width:218px;"  class="selectbox">
								<option value="">--select--</option>
								<option value="1" <?php echo (isset($acc_details['intim_date_dist']) && $acc_details['intim_date_dist'] == '1')?'selected':'';?><?php echo (isset($_POST['intim_date_dist']) && $_POST['intim_date_dist'] == '1' && @$acc_details['intim_date_dist'] == '')?'selected':'';?>>Yes</option>
								<option value="0" <?php echo (isset($acc_details['intim_date_dist']) && $acc_details['intim_date_dist'] == '0')?'selected':'';?><?php echo (isset($_POST['intim_date_dist']) && $_POST['intim_date_dist'] == '0' && @$acc_details['intim_date_dist'] == '')?'selected':'';?>>No</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
						<label>Status of Settlement</label> 
						<select <?php echo(@$status_db == '2' && isset($acc_details))?"disabled":"";?> name="intim_date_dist_status" id="intim_date_dist_status" style="background:#FFF; margin-bottom:0px;width:218px;"  class="selectbox">
							<option value="">--select--</option>
							<option value="1" <?php echo (isset($acc_details['intim_date_dist_status']) && $acc_details['intim_date_dist_status'] == '1')?'selected':'';?><?php echo (isset($_POST['intim_date_dist_status']) && $_POST['intim_date_dist_status'] == '1' && @$acc_details['intim_date_dist_status'] == '')?'selected':'';?>>Paid</option>
							<option value="0" <?php echo (isset($acc_details['intim_date_dist_status']) && $acc_details['intim_date_dist_status'] == '0')?'selected':'';?><?php echo (isset($_POST['intim_date_dist_status']) && $_POST['intim_date_dist_status'] == '0' && @$acc_details['intim_date_dist_status'] == '')?'selected':'';?>>Pending</option>
						</select>
						</td>
					</tr>
					
				</table>
				
				</td>
				
				<td>
               
				<table>
					<tr>
						<td>
							<label>Date of intimation to Insurance Co by IOCL Under PLI Policy by IOC,BPC,HPC</label> 
							<input type="text" name="intimation_date" id="intimation_date" readonly class="requireddata" style="width:150px;background:#fff;<?php echo(@$status_db == '2' && isset($acc_details))?"color:graytext":"";?>" placeholder="Date of intimation" maxlength="35" value="<?php echo (isset($acc_details['intimation_date']) && $acc_details['intimation_date'] != '')?$acc_details['intimation_date']:''; ?><?php echo (isset($_POST['intimation_date']) && $_POST['intimation_date'] != '' && $acc_details['intimation_date'] == '')?$_POST['intimation_date']:''; ?>"/> &nbsp;&nbsp; 
							
							<img src="../manager/images/Cal.gif" name="cmdbusinessunit" id="cmdbusinessunit" onclick="return displayCalendar(document.form_acc.intimation_date, 'dd/mm/yyyy', this)" style="margin:0 10px 0 0;"/>
							
						</td>
					</tr>
					<tr>
						<td>
						<label>Name of Insurance Co. (Policy taken by IOC/BPC/HPC)<span style="color:#ff0000;">*</span></label> 
						<select <?php echo(@$status_db == '2' && isset($acc_details))?"disabled":"";?> name="insurance_company" id="insurance_company" style="background:#FFF; margin-bottom:0px;width:230px;"  class="selectbox">
							<option value="">--select--</option>
							<option value="1" <?php echo (isset($acc_details['insurance_company']) && $acc_details['insurance_company'] == '1')?'selected':'';?><?php echo (isset($_POST['insurance_company']) && $_POST['insurance_company'] == '1' && @$acc_details['insurance_company'] == '0')?'selected':'';?>>National Insurance Co. Ltd</option>
							<option value="2" <?php echo (isset($acc_details['insurance_company']) && $acc_details['insurance_company'] == '2')?'selected':'';?><?php echo (isset($_POST['insurance_company']) && $_POST['insurance_company'] == '2' && @$acc_details['insurance_company'] == '0')?'selected':'';?>>United India Insurance Co. Ltd</option>
							<option value="3" <?php echo (isset($acc_details['insurance_company']) && $acc_details['insurance_company'] == '3')?'selected':'';?><?php echo (isset($_POST['insurance_company']) && $_POST['insurance_company'] == '3' && @$acc_details['insurance_company'] == '0')?'selected':'';?>>ICICI Lombard Gen. Insurance Co. Ltd</option>
							<option value="4" <?php echo (isset($acc_details['insurance_company']) && $acc_details['insurance_company'] == '4')?'selected':'';?><?php echo (isset($_POST['insurance_company']) && $_POST['insurance_company'] == '4' && @$acc_details['insurance_company'] == '0')?'selected':'';?>>Oriental Insurance Co. Ltd</option>
							<option value="5" <?php echo (isset($acc_details['insurance_company']) && $acc_details['insurance_company'] == '5')?'selected':'';?><?php echo (isset($_POST['insurance_company']) && $_POST['insurance_company'] == '5' && @$acc_details['insurance_company'] == '0')?'selected':'';?>>New India Assurance Co. Ltd</option>
							<option value="6" <?php echo (isset($acc_details['insurance_company']) && $acc_details['insurance_company'] == '6')?'selected':'';?><?php echo (isset($_POST['insurance_company']) && $_POST['insurance_company'] == '6' && @$acc_details['insurance_company'] == '0')?'selected':'';?>>HDFC - ERGO Gen. Insurance Co. Ltd</option>
							<option value="7" <?php echo (isset($acc_details['insurance_company']) && $acc_details['insurance_company'] == '7')?'selected':'';?><?php echo (isset($_POST['insurance_company']) && $_POST['insurance_company'] == '7' && @$acc_details['insurance_company'] == '0')?'selected':'';?>>IFFCO-TOKIO Gen. Insurance Co. Ltd</option>
							<option value="8" <?php echo (isset($acc_details['insurance_company']) && $acc_details['insurance_company'] == '8')?'selected':'';?><?php echo (isset($_POST['insurance_company']) && $_POST['insurance_company'] == '8' && @$acc_details['insurance_company'] == '0')?'selected':'';?>>Reliance Gen. Insurance Co. Ltd</option>
							<option value="9" <?php echo (isset($acc_details['insurance_company']) && $acc_details['insurance_company'] == '9')?'selected':'';?><?php echo (isset($_POST['insurance_company']) && $_POST['insurance_company'] == '9' && @$acc_details['insurance_company'] == '0')?'selected':'';?>>Bajaj Allianz Gen. Insurance Co. Ltd</option>
							<option value="10" <?php echo (isset($acc_details['insurance_company']) && $acc_details['insurance_company'] == '10')?'selected':'';?><?php echo (isset($_POST['insurance_company']) && $_POST['insurance_company'] == '10' && @$acc_details['insurance_company'] == '0')?'selected':'';?>>TATA- AIG GIC Ltd.</option>
						</select>
						</td>
					</tr>
					<tr>
						<td>
						<label>Claim Number<span style="color:#ff0000;">*</span></label> 
							<input <?php echo(@$status_db == '2' && isset($acc_details))?"readonly":"";?> type="text" style="background:#FFF; margin-bottom:0px;width:208px;<?php echo(@$status_db == '2' && isset($acc_details))?"color:graytext":"";?>" name="claim_number" id="claim_number" value="<?php echo (isset($acc_details['claim_number']) && $acc_details['claim_number'] != '')?$acc_details['claim_number']:''; ?><?php echo (isset($_POST['claim_number']) && $_POST['claim_number'] != '' && @$acc_details['claim_number'] == '')?$_POST['claim_number']:''; ?>"/>
						</td>
					</tr>
					<tr>
			
					<td colspan="2">
						<label>Status of Settlement<span style="color:#ff0000;">*</span></label> 
						<select <?php echo(@$status_db == '2' && isset($acc_details))?"disabled":"";?> name="claim_settled" id="claim_settled" style="background:#FFF;margin-bottom:0px;width:218px;"  class="selectbox" onChange="showhide(this.value,'claim_div')">
							<option value="">--select--</option>
							<option value="1" <?php echo (isset($acc_details['claim_settled']) && $acc_details['claim_settled'] == '1')?'selected':'';?><?php echo (isset($_POST['claim_settled']) && $_POST['claim_settled'] == '1' && @$acc_details['claim_settled'] == '')?'selected':'';?>>Paid</option>
							<option value="0" <?php echo (isset($acc_details['claim_settled']) && $acc_details['claim_settled'] == '0')?'selected':'';?><?php echo (isset($_POST['claim_settled']) && $_POST['claim_settled'] == '0' && @$acc_details['claim_settled'] == '')?'selected':'';?>>Pending</option>
							<option value="2" <?php echo (isset($acc_details['claim_settled']) && $acc_details['claim_settled'] == '2')?'selected':'';?><?php echo (isset($_POST['claim_settled']) && $_POST['claim_settled'] == '2' && @$acc_details['claim_settled'] == '')?'selected':'';?>>No Claim (rejected by Insurance Co)</option>
						</select>
						<br/><br/>
						<div id="reason_div" <?php echo(@$status_db == '2' && isset($acc_details))?"disabled":"";?> style="<?php if(@$_POST['claim_settled'] == '0'){?>display:block;<?php } else if(isset($acc_details['claim_settled']) && $acc_details['claim_settled'] == '0') {?>display:block;<?php } else{ ?>display:none;<?php }?>">
							<label>Reason for pending<span style="color:#ff0000;">*</span></label> 
							<select name="reason_pending" id="reason_pending" style="background:#FFF; margin-bottom:0px;width:218px;"  class="selectbox">
								<option value="">--select--</option>
								<option value="Pending at Insurance Co" <?php echo (isset($acc_details['reason_pending']) && $acc_details['reason_pending'] == 'Pending at Insurance Co' && !isset($_POST['reason_pending']))?'selected':'';?><?php echo (isset($_POST['reason_pending']) && $_POST['reason_pending'] == 'Pending at Insurance Co')?'selected':'';?>>Pending at Insurance Co</option>
								<option value="Documents not submitted by consumer" <?php echo (isset($acc_details['reason_pending']) && $acc_details['reason_pending'] == 'Documents not submitted by consumer' && !isset($_POST['reason_pending']))?'selected':'';?><?php echo (isset($_POST['reason_pending']) && $_POST['reason_pending'] == 'Documents not submitted by consumer')?'selected':'';?>>Documents not submitted by consumer</option>
								<option value="IOCL reports  not submitted" <?php echo (isset($acc_details['reason_pending']) && $acc_details['reason_pending'] == 'IOCL reports  not submitted' && !isset($_POST['reason_pending']))?'selected':'';?><?php echo (isset($_POST['reason_pending']) && $_POST['reason_pending'] == 'IOCL reports  not submitted')?'selected':'';?>>IOCL reports  not submitted</option>
								<option value="Court Case" <?php echo (isset($acc_details['reason_pending']) && $acc_details['reason_pending'] == 'Court Case' && !isset($_POST['reason_pending']))?'selected':'';?><?php echo (isset($_POST['reason_pending']) && $_POST['reason_pending'] == 'Court Case')?'selected':'';?>>Court Case</option>
							</select>
						</div>
					</td>
					</tr>
					
				</table>
				
				</td>
			</tr>
			
			
					<tr>
					<td colspan="2">
						<div id="claim_div" style="float:left;margin-top:8px;<?php if(@$_POST['claim_settled'] == '1'){?>display:block;<?php } else if(isset($acc_details['claim_settled']) && $acc_details['claim_settled'] == '1') {?>display:block;<?php } else{ ?>display:none;<?php }?>" >
						<table width="100%">
						<tr>
							
							<td>Amount for fatalities</td>
							<td><input type="text" <?php echo(@$status_db == '2' && isset($acc_details))?"disabled":"";?> style="background:#FFF; margin-bottom:0px;" onkeypress="return isNumber(event)" name="death_amount" id="death_amount" value="<?php echo (isset($acc_details['death_amount']) && $acc_details['death_amount'] != '' && $acc_details['death_amount'] != 0)?$acc_details['death_amount']:''; ?><?php echo (isset($_POST['death_amount']) && $_POST['death_amount'] != '' && @$acc_details['death_amount'] == '0')?$_POST['death_amount']:''; ?>" onblur="updatetotal();"/></td>
							<td>Amount for injuries</td>
							<td><input <?php echo(@$status_db == '2' && isset($acc_details))?"disabled":"";?> type="text" style="background:#FFF; margin-bottom:0px;" onkeypress="return isNumber(event)" name="injury_amount" id="injury_amount" value="<?php echo (isset($acc_details['injury_amount']) && $acc_details['injury_amount'] != '' && $acc_details['injury_amount'] != 0 )?$acc_details['injury_amount']:''; ?><?php echo (isset($_POST['injury_amount']) && $_POST['injury_amount'] != '' && $acc_details['injury_amount'] == '0')?$_POST['injury_amount']:''; ?>" onblur="updatetotal();"/></td>
						</tr>
						<tr>
							
						</tr>
						<tr>
							<td>Amount for property damage</td>
							<td><input <?php echo(@$status_db == '2' && isset($acc_details))?"disabled":"";?> type="text" style="background:#FFF; margin-bottom:0px;" onkeypress="return isNumber(event)"  name="property_damage_amount" id="property_damage_amount" value="<?php echo (isset($acc_details['property_damage_amount']) && $acc_details['property_damage_amount'] != '' && $acc_details['property_damage_amount'] != 0)?$acc_details['property_damage_amount']:''; ?><?php echo (isset($_POST['property_damage_amount']) && $_POST['property_damage_amount'] != '' && $acc_details['property_damage_amount'] == '0')?$_POST['property_damage_amount']:''; ?>" onblur="updatetotal();"/></td>
							<td>Total</td>
							<td>
							<input type="hidden" <?php echo(@$status_db == '2' && isset($acc_details))?"disabled":"";?> style="background:#FFF; margin-bottom:0px;" onkeypress="return isNumber(event)" name="amount_paid" id="amount_paid" value="<?php echo (isset($acc_details['amount_paid']) && $acc_details['amount_paid'] != '')?$acc_details['amount_paid']:''; ?>"/>
							
							<span id="totalamount">
							<?php
								if(isset($_POST) && $_POST['death_amount'] != ''){
									echo ($_POST['death_amount']+$_POST['injury_amount']+$_POST['property_damage_amount']);
								}
								else if(isset($acc_details) && $acc_details['totalamount'] != '' && $acc_details['totalamount'] != 0){
									echo $acc_details['totalamount'];
								}
							?>
							</span></td>
						</tr>
						
						<tr>
						
						<td>
						
						<label>Acknowledgement - Claim Payment to consumer </label> </td>
						<td><input <?php echo(@$status_db == '2' && isset($acc_details))?"disabled":"";?> type="file" name="acknowledgement" id="acknowledgement"/>
						<?php
							if(isset($acc_details) && $acc_details['acknowledgement'] != ''){
								?>
								<input type="hidden" name="acknowledgement_doc" id="acknowledgement_doc" value="<?=$acc_details['acknowledgement'];?>"/>
								<a href="<?php echo SERVER_URL ;?>cert_download.php?cidint=<?php echo $acc_details['id']; ?>&tabname=5" target="_blank">Acknowledgement</a>
								<?php
							}
						?>
						</td>
						</tr>
						</table>
					</div>
					</td>
         
			</tr>
			
			
			<tr><td align="center" height="60" valign="middle" colspan="2">
					  <input name="mode" type="hidden" id="mode" value="<?php if(isset($acc_details)) { ?>Update<?php } else { ?>Save<?php } ?>">
					  <input type="button" style=" margin:0 10px 0 0; float:none;" class="inputbuttonblue" value="SAVE" name="submit_save" onclick="validateform();">
                      <input type="button" style=" margin:0 10px 0 0; float:none;" class="inputbuttonblue" value="SUBMIT" name="submit_submit" onclick="check_documents();">
					  <input type="button" style=" margin:0 10px 0 0; float:none;" class="inputbuttonblue" value="CLOSE" name="submit_close" onclick="check_documents_closing();">
					  
              </td>
            </tr>

       </table>
			
        </form>
</div>


        </div>


    </div>
</div>

<!-- <script type="text/javascript" src="<?=SERVER_URL?>js/jquery-1.11.3.min.js"></script>
<link rel="stylesheet" href="<?=SERVER_URL?>css/wickedpicker.css">
<script type="text/javascript" src="<?=SERVER_URL?>js/wickedpicker.js"></script>
<script type="text/javascript">
$('#time_accident').wickedpicker({twentyFour: true});
</script> -->

<?php include('includes/footer.php'); ?>