<?php
set_time_limit(0);
include_once dirname(__FILE__) . "/../classes/class.advertisement.php";
$adv_obj = new Advertisement();

$output_data = array();

// IP Check
$client_ip = $_SERVER['REMOTE_ADDR'];

$ip_lists = parse_ini_file("ip_whitelists.ini");
$ip_whitelist = array_map('trim', explode(",", $ip_lists['IPs']));

//file_put_contents("data.txt", json_encode($_POST)."\n".$_SERVER['REMOTE_ADDR']);

if(!in_array($client_ip, $ip_whitelist)){
	$output_data['Message'] = 'Invalid Request.';
	echo json_encode($output_data);
	die;
}

if(!isset($_POST['hash']) || trim($_POST['hash']) != WEBSERVICE_ACCESS_TOKEN){
	$output_data['Message'] = 'Authentication failed.';
	echo json_encode($output_data);
	die;
}

$response_codes = array(
					100=>'Success', 
					101=>'Invalid Location / Application Reference No.', 
					102=>'Winner already declared for this location', 
					103=>'Not updated',
					104=>'Draw not required for this location',
					105=>'Winner already declared for this location with same candidate'
					);

if(isset($_POST['data']) && !empty($_POST['data']))
{
	$draw_data = json_decode(trim($_POST['data']), true);

	if(is_array($draw_data) && isset($draw_data['LocationID']) && isset($draw_data['Winner_Application_Reference_No']))
	{
		$response_data = array();
		
		// Get Application Details	
		$get_application = $adv_obj->db_obj->query("select * from tbl_applications where application_ref_no='".$draw_data['Winner_Application_Reference_No']."' and roster_id='".$draw_data['LocationID']."' and is_eligible=1 and application_final_status=1");	
		if($adv_obj->db_obj->numRows($get_application)>0)
		{
			$application_details = $adv_obj->db_obj->fetchResult($get_application);
		
			// Get Location Details
			$rosterDetails = $adv_obj->rosterDetails($draw_data['LocationID']);
			if($rosterDetails[0]->draw_type!=1){
				$response_data = array('LocationID'=>$draw_data['LocationID'], 'Code'=>104, 'Description'=>$response_codes[104]);
			}
			else if($rosterDetails[0]->is_draw_done==1){
				$response_data = array('LocationID'=>$draw_data['LocationID'], 'Code'=>102, 'Description'=>$response_codes[102]);
			}
			else{
				// Update Draw Result
				$update_result = "update tbl_rosters set is_draw_done=1, draw_date='".date('Y-m-d H:i:s', strtotime($draw_data['Draw_Date']))."', draw_winner_application='".$application_details[0]->id."' where id='".$draw_data['LocationID']."'";
				if(!$adv_obj->db_obj->execute($update_result)){
					$response_data = array('LocationID'=>$draw_data['LocationID'], 'Code'=>103, 'Description'=>$response_codes[103]);
				}
				else{
					$update_app_status = "update tbl_applications set application_final_status=3 where id='".$application_details[0]->id."'";
					$adv_obj->db_obj->execute($update_app_status);
					
					$query_insert_sel = "insert into tbl_selection_process (roster_id, draw_type, draw_date, winner_application_id) values ('".$draw_data['LocationID']."', '1', '".date('Y-m-d H:i:s', strtotime($draw_data['Draw_Date']))."', '".$application_details[0]->id."')";
					$adv_obj->db_obj->execute($query_insert_sel);
					
					$response_data = array('LocationID'=>$draw_data['LocationID'], 'Code'=>100, 'Description'=>$response_codes[100]);
				}
			}
		}
		else
		{
			$get_application = $adv_obj->db_obj->query("select * from tbl_applications where application_ref_no='".$draw_data['Winner_Application_Reference_No']."' and roster_id='".$draw_data['LocationID']."' and is_eligible=1 and application_final_status=3");
			if($adv_obj->db_obj->numRows($get_application)==1)
			{
				$response_data = array('LocationID'=>$draw_data['LocationID'], 'Code'=>105, 'Description'=>$response_codes[105]);
			}
			else
			{
				$response_data = array('LocationID'=>$draw_data['LocationID'], 'Code'=>101, 'Description'=>$response_codes[101]);	
			}
		}
		
		
		$output_data['Response'] = $response_data;
	}
	else{
		$output_data['Message'] = 'Invalid JSON Data';	
	}
}
else{
	$output_data['Message'] = 'Data is empty.';
}

$response_data = json_encode($output_data);
echo $response_data;

//Insert Logs
$insert_logs = "insert into tbl_webservice_logs 
				(`web_service`, `request_params`, `response`, `ip_address`, `date_time`) 
				values 
				('Push_Draw_Data', '".json_encode($_POST)."', '".$adv_obj->db_obj->realEscapeString($response_data)."', '".$client_ip."', '".date('Y-m-d H:i:s')."')";
$adv_obj->db_obj->execute($insert_logs);
?>