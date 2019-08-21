<?php
ob_start();

include_once("includes/header.php");
include_once("../includes/comman_functions.php");
// if($user['isp_type'] != 'SZ'){
    // header('Location:https://indane.co.in');
    // exit;
// }
$self = "safety-clinic-awareness-program.php"; //File Name
if(isset($_REQUEST['action']) && $_REQUEST['action']!='')
{
	$action = mysql_real_escape_string($_REQUEST['action']);
	$month = mysql_real_escape_string($_REQUEST['month']);
	$year = mysql_real_escape_string($_REQUEST['year']);
	if($action=='delete' && $month!='' && $year!='')
	{
		mysql_query("insert into gb_safety_awareness_program_conduct_by_dist_archive
				SELECT *,now() as archive_date FROM gb_safety_awareness_program_conduct_by_dist
				where lsp_id=".$user['id']." and year=".$year." and month =".$month) ;
		mysql_query("insert into gb_safety_clinic_awareness_program_master_archive
				SELECT *,now() as archive_date FROM gb_safety_clinic_awareness_program_master
				where lsp_id=".$user['id']." and year=".$year." and month =".$month) ;
		mysql_query("insert into gb_safety_clinic_awareness_program_details_archive
				SELECT *,now() as archive_date FROM gb_safety_clinic_awareness_program_details
				where lsp_id=".$user['id']." and year=".$year." and month =".$month) ;
				
		mysql_query("delete FROM gb_safety_clinic_awareness_program_details
				where lsp_id=".$user['id']." and year=".$year." and month =".$month) ;		
		mysql_query("delete FROM gb_safety_awareness_program_conduct_by_dist
				where lsp_id=".$user['id']." and year=".$year." and month =".$month) ;		
		mysql_query("delete FROM gb_safety_clinic_awareness_program_master
				where lsp_id=".$user['id']." and year=".$year." and month =".$month) ;
		
		header("location:$self");
		die;
	}
}
$startYear=2017;
$allowed_months = array();
if((int)date('m')>4)
{
	$startYear = date('Y');
	for($i=4;$i<=(int)date('m');$i++)
	{
			$allowed_months[] = $i;
	}
}
elseif((int)date('m')<=3)
{
	$startYear = date('Y')-1;
}

$table_fields=array('lsp_id', 'year', 'month', 'no_of_programs','conducted_events','participants');
$col_query=array();
$form_values=array();
$form_conducted_events = array();
$form_participants = array();
foreach($table_fields as $v)
{
	
	if($v=='no_of_programs')
	{
		$form_no_of_programs = array();
	}
	elseif($v=='conducted_events')
	{
		$form_conducted_events = array();
	}
	elseif($v=='participants')
	{
		$form_participants = array();
	}
	elseif($v=='navchetnapro')
	{
		$form_navchetnapro = array();
	}
	else
	{
		$form_values[$v] = '';
	}
}

$area_id = $user['area_id'];

/* if ($user['isp_type'] == 'SO') {
    $str = " and gcd.so_id = '" . $area_id . "'";
}
if ($user['isp_type'] == 'AO') {
    $str = " and gcd.ao_id = '" . $area_id . "'";
} */
if ($user['isp_type'] == 'SZ') {
    $str = " and gcd.sz_id = '" . $area_id . "'";
}



//echo $isp_type;


$query="select sz_office.id,sz_office.office_name sz_name,ao_office.office_name ao_name,so_office.office_name so_name from gb_offices sz_office
			left join gb_offices ao_office on ao_office.office_type='AO' and sz_office.status=ao_office.status and ao_office.ao_code=sz_office.ao_code and sz_office.oil_company_code=ao_office.oil_company_code 
			left join gb_offices so_office on so_office.office_type='SO' and sz_office.status=so_office.status and so_office.so_code=ao_office.so_code and so_office.oil_company_code=ao_office.oil_company_code 
			where sz_office.office_type='SZ' and sz_office.oil_company_code=1 and sz_office.status=1 and sz_office.id=$area_id";
			
$sz_office_result = mysql_query($query);
$sz_office_details=array();
if(mysql_num_rows($sz_office_result)==1)
{
	$sz_office_details = mysql_fetch_assoc($sz_office_result);
}
$program_type=array();			
$program_type_qry = mysql_query("select * from gb_safety_clinic_awareness_program_type_master where section='A' and is_active=1");
while ($program_type_row = mysql_fetch_array($program_type_qry))
{
	$program_type[$program_type_row['id']] = $program_type_row['program_type_name'];
}

$program_event=array();			
$program_event_qry = mysql_query("select * from gb_safety_awareness_program_event_master where is_active=1");
while ($program_event_row = mysql_fetch_array($program_event_qry))
{
	$program_event[$program_event_row['id']] = $program_event_row['event_name'];
}


$program_navchetna=array();			
$program_navchetna_qry = mysql_query("select * from gb_safety_clinic_awareness_program_type_master where section='C' and is_active=1");
while ($program_nav_row = mysql_fetch_array($program_navchetna_qry))
{
	$program_navchetna[$program_nav_row['id']] = $program_nav_row['program_type_name'];
}


$errors = array();


if (isset($_POST['mode']) && $_POST['mode'] == 'Next') 
{
	$extension = array('jpg', 'jpeg','png','bmp');
    $only_image_extension = array('jpg', 'jpeg','png','bmp');
	$col_query['lsp_id']=$user['id'];
	foreach($_POST as $field=>$value)
	{
		if(in_array($field,$table_fields))
		{
			if($field == "no_of_programs")
			{
				$form_no_of_programs = $value;
			}
			elseif($field == "conducted_events")
			{
				$form_conducted_events = $value;
			}
			elseif($field == "participants")
			{
				$form_participants = $value;
			}
			elseif($field == "navchetnapro")
			{
				$form_navchetnapro = $value;
			}
			else
			{
				if($value=="")
				{
					$errors[] = ucfirst(str_replace('_',' ',$field))." cannot be blank.";
				}
				$col_query[$field] = sanitizeInputs($value);
			}
		}
	}
	if(count($table_fields) != count($col_query)+3) //+3 for program_type,conducted_events,participants
	{
		$errors[]="Please fill all the fields.";
	}
	$total_no_of_programs=0;
	if(count($form_no_of_programs) == count(array_merge($program_type,$program_navchetna)))
	{
		foreach($program_type as $k=>$v)
		{
			if($form_no_of_programs[$k] == '')
			{
				$errors[]="Please fill no of $v .";
			}
			else
			{
				$total_no_of_programs += $form_no_of_programs[$k];
			}
		}
	}
	else
	{
		$errors[]="Please fill all the program count.";
	}
	$total_conducted_events = 0;
	if(count($form_conducted_events) == count($program_event))
	{
		//print_r($program_event);
		//print_r($form_conducted_events);
		
		foreach($program_event as $k=>$v)
		{
			//echo $form_conducted_events[$k];
			if($form_conducted_events[$k] == '')
			{
				$errors[]="Please fill no of event conducted $v .";
			}
			else
			{
				$total_conducted_events += $form_conducted_events[$k];
			}
		}
	}
	else
	{
		$errors[]="Please fill all the no of event conducted.";
	}
	
	if(count($form_participants) == count($program_event))
	{
		foreach($program_event as $k=>$v)
		{
			if($form_participants[$k] == '')
			{
				$errors[]="Please fill no of Partcipants $v .";
			}
		}
	}
	else
	{
		$errors[]="Please fill all the no of Participants.";
	}
	
	
	if($total_no_of_programs == 0)
	{
		//$errors[]="Please fill correct no of programs.";
	}
	if($total_conducted_events == 0)
	{
		//$errors[]="Please fill correct no of event conducted.";
	}
	
	
	
	if(count($errors)==0)
	{
		if(count($errors)==0)
		{
			foreach($form_no_of_programs as $k=>$v)
			{
				$col_query['program_type']= mysql_real_escape_string($k);
				$col_query['no_of_programs']= mysql_real_escape_string($v);
				
				$insertIntoDB="insert into gb_safety_clinic_awareness_program_master
						(".implode(',',array_keys($col_query)).")
						value('".implode("','",$col_query)."')";
				//echo $insertIntoDB;		
				if (mysql_query($insertIntoDB)) {
						//$_SESSION['successMsg'] = 'Your details are successfully Submitted.';
						//' <a href="export_bankseed_covernote.php?refno=' . $referenceNo . '">Click here</a> to download Cover Note.';
						
					}
					else {
						$error[] = "Program not submitted.";
					}
				unset($col_query['program_type']);
				unset($col_query['no_of_programs']);
			}
			foreach($program_event as $k=>$v)
			{
				$col_query['event_id']=mysql_real_escape_string($k);
				$col_query['conducted_events']=mysql_real_escape_string($form_conducted_events[$k]);
				$col_query['participants']=mysql_real_escape_string($form_participants[$k]);
				
				$insertIntoDB="insert into gb_safety_awareness_program_conduct_by_dist
						(".implode(',',array_keys($col_query)).")
						value('".implode("','",$col_query)."')";
				//echo $insertIntoDB;		
				if (mysql_query($insertIntoDB)) {
						//$_SESSION['successMsg'] = 'Your details are successfully Submitted.';
						//' <a href="export_bankseed_covernote.php?refno=' . $referenceNo . '">Click here</a> to download Cover Note.';
						
					}
					else {
						$error[] = "Program not submitted.";
					}
				unset($col_query['event_id']);
				unset($col_query['conducted_events']);
				unset($col_query['participants']);
			}
			
			
		      
			header("location:safety-clinic-awareness-program_details.php?year=".$col_query['year']."&month=".$col_query['month']);
			die;
		}
		
	}
	
$form_values=$col_query;

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
<link rel="stylesheet" href="<?=SERVER_URL?>js/dhtmlgoodies_calendar.css?random=20051112" media="screen"/>
<script type="text/javascript" src="<?=SERVER_URL?>js/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript">
function isNumber(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode == 46)
		return true;
	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	}
	return true;
}
function validate_safety()
{
	var no_of_programs_arr = document.getElementsByClassName('no_of_programs_class');
	var conducted_events_arr = document.getElementsByClassName('conducted_events_class');
	var no_of_programs_navchetnapro_arr = document.getElementsByClassName('no_of_programs_navchetnapro');
	var total_programs = total_conducted_events = total_programs_navchetnapro = 0 ;
	for(var i=0;i<no_of_programs_arr.length; i++)
	{
		
		if(no_of_programs_arr[i].value!='')
		{
			total_programs = total_programs+no_of_programs_arr[i].value;
			
		}
	}
	for(var i=0;i<conducted_events_arr.length; i++)
	{
		if(conducted_events_arr[i].value!='')
		{
			total_conducted_events = total_conducted_events+conducted_events_arr[i].value;
		}
	}
	for(var i=0;i<no_of_programs_navchetnapro_arr.length; i++)
	{
		if(no_of_programs_navchetnapro_arr[i].value!='')
		{
			total_programs_navchetnapro = total_programs_navchetnapro+no_of_programs_navchetnapro_arr[i].value;
		}
	}

	if(total_conducted_events == 0 || total_programs == 0 || total_programs_navchetnapro == 0)
	{
		return confirm("Are you sure to sumit 'NIL' report");
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



<div class="innerheadercontainer2">
    <div class="borderbottom"></div>
    <div class="midcontainer innerheadermain">
        <div class="innerfortunelogo3"><img src="<?= SERVER_URL ?>images/fortunelogo.png"  alt="Fortune Logo" /></div>
        <div class="innerheaderleft">
            <h1>Safety Clinic & Nav-Chetna Report</h1>
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
		
		<?php
		
		
		 function getCurrectYearMonth($year,$area_id)
		 {
			 $prev_filled_months = array();
			  $filled_months_query="select year,month, freeze_status, sum(no_of_programs) as total_program from gb_safety_clinic_awareness_program_master where office_id=".$area_id." && year=".$year."";

					$filled_months = mysql_query($filled_months_query);
					$prev_filled_months = array();
						if(mysql_num_rows($filled_months)>0)
						{
						
							while($filled_months_row = mysql_fetch_assoc($filled_months))
							{
                                $prev_filled_months[] = $filled_months_row['month'];
							}
					    }
			     return $prev_filled_months;
		 }
			
			$filled_months_query="select year,month, freeze_status, sum(no_of_programs) as total_program from gb_safety_clinic_awareness_program_master where office_id=".$area_id." -- and year>=$startYear 
			group by year,month";
			//echo $filled_months_query;
			$filled_months = mysql_query($filled_months_query);
			$prev_filled_months = array();
			if(mysql_num_rows($filled_months)>0)
			{
				echo '<div>Previous Filled details</div>';
				echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablecontainer">
				<tr><th>Year</th><th>Month</th><th>No. of Programs</th><th>Action</th></tr>';
				while($filled_months_row = mysql_fetch_assoc($filled_months))
				{
					if($filled_months_row['year'] == $startYear)
					{
						$prev_filled_months[] = $filled_months_row['month'];
					}
					echo '<tr>
							<td>'.$filled_months_row['year'].'</td>
							<td>'.DateTime::createFromFormat('!m', $filled_months_row['month'])->format('F').'</td>
							<td>'.$filled_months_row['total_program'].'</td>';
					if($filled_months_row['freeze_status']=='1')
					{
						echo "<td>Finalised</td>";
					}
					else
					{						
						echo '<td><a href="safety-clinic-awareness-program_details.php?year='.$filled_months_row['year'].'&month='.$filled_months_row['month'].'">View</a> | ';
						echo ' <a onClick="return confirm(\'Are you sure you want to delete.\')" href="'.$self.'?action=delete&year='.$filled_months_row['year'].'&month='.$filled_months_row['month'].'">Delete for Re-enter</a></td>';
					}
					echo '</tr>';
				}
				echo '</table><br/><br/>';
			}
			
		
			
			for($i=4;$i<=12;$i++)
			{
				if(!(in_array($i,$prev_filled_months)) && ((int)date('m')<=4))
				{
					if($i == 4 && date('m')=='4' && $startYear!=date('Y')) continue;
					$allowed_months[] = $i;
				}
			}
			for($i=1;$i<=(int)date('m') && (int)date('m')<4;$i++)
			{
				$allowed_months[] = $i;
			}
			
			
		 $arrMonthDate   = array('1'  => 'January',
								 '2'  => 'February',
								 '3'  => 'March',
								 '4'  => 'April',
								 '5'  => 'May',
								 '6'  => 'June',
								 '7'  => 'July',
								 '8'  => 'August',
								 '9'  => 'September',
								 '10'  => 'October',
								 '11'  => 'November',
                                  '12'  => 'December');			
		function getFilledMonth($area_id,$year)
		{		
		 			  
		 $filled_months_query1="select year,month from gb_safety_clinic_awareness_program_master where office_id=".$area_id." && year=".$year." group by month";
         $filled_months1 = mysql_query($filled_months_query1);
		if(mysql_num_rows($filled_months1)>0)
		{
			while($filled_months_row1 = mysql_fetch_assoc($filled_months1))
				{
					
						$prev_filled_months1[] = $filled_months_row1['month'];
					
				}	
				
			
		 }
		 
		   return $prev_filled_months1;
		 
		}
			
			
	   
			
		?>
		<form name='myform' action='' method='post' enctype="multipart/form-data" id="myform" onSubmit="return validate_safety();">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablecontainer">
				<tr>
					<th colspan="2">
						Add New
					</th>
				</tr>
				<tr>
					<td colspan="2">
						<strong>A. Safety Clinics conducted by FO for Ujjwala Consumers, Structure Safety training for Distributor Mechanic and Distributor Deliverymen-MOU Parameter 2017-18</strong>
					</td>
				</tr>
				<tr>
					<td>State Office</td>
					<td><?php echo $sz_office_details['so_name'];?></td>
				</tr>
				<tr>
					<td>Area Office</td>
					<td><?php echo $sz_office_details['ao_name'];?></td>
				</tr>
				<tr>
					<td>Sales Zone</td>
					<td><?php echo $sz_office_details['sz_name'];?> </td>
				</tr>
				<tr>
					<td>Year & Month <span style="color:#ff0000;">*</span></td>
					<td>
						<select id="year" name="year" onchange="return fill_months(this.value);" class="selectbox" style="width:70px">
						<?php
						
						for($i=$startYear;$i<=(int)date('Y');$i++)
						{
							$select="";
							
							if($form_values['year']==$i)
							{
								$select = "selected";
							}
							elseif($startYear==$i)
							{
								$select = "selected";
							}
							echo "<option $select value='$i'>". $i."</option>";
						}
						?>
						</select>
						
						<?php 
					
						?>
		<select id="month1" name="month" class="selectbox">
			<option value=''>--select--</option>
			<?php
				foreach($arrMonthDate as $key=>$value)
			{
				   
					if(!in_array($key,getFilledMonth($area_id,2017)))
					{
							
								// $select="";
								
								// if($form_values['month']==$i)
								// {
									// $select = "selected";
								// }
								
							
								
								 echo "<option $select value='$key'>". $value."</option>";
							
						 }
			}		
						?>
						</select>
						
					</td>
				</tr>
				<tr>
					<td><strong>Type of Programme </strong></td>
					<td><strong>No of Programs </strong> &nbsp; ( If Nil please mention it as "0") </td>
				</tr>
				<?php 
				
				  
					foreach ($program_type as $program_key=>$program_value)
					{
						
					 
						echo '<tr>
								<td>'.$program_value.'<span style="color:#ff0000;">*</span></td>
								<td><input onkeypress="return isNumber(event);" type="text" class="no_of_programs_class" name="no_of_programs['.$program_key.']" value="'.$form_no_of_programs[$program_key].'"></td>
							  </tr>';
					 
				    }	
				?>
				<tr>
					<td colspan="2">
					<strong>B. Safety Awareness Progrmas conducted by DISTRIBUTORS  </strong>
					<table width="100%">
						<tr>
							<td><strong>Event Name</strong></td>
							<td><strong>No's Conducted(No's)</strong></td>
							<td><strong>No of Participants(No's)</strong></td>
						</tr>
						<?php
							foreach($program_event as $event_key=>$event_value)
							{
						?>
						<tr>
							<td><?php echo $event_value;?></td>
							<td><input onkeypress="return isNumber(event);" type="text" class="conducted_events_class" name="conducted_events[<?php echo $event_key;?>]" value="<?php echo $form_conducted_events[$event_key];?>"></td>
							<td><input onkeypress="return isNumber(event);" type="text" name="participants[<?php echo $event_key;?>]" value="<?php echo $form_participants[$event_key];?>"></td>
						</tr>
						<?php
							}
						?>
						
					</table>
					</td>
				</tr>
				
				
				
				
				<tr>
					<td colspan="2">
					<strong>C. Nav Chetna Details  </strong>
					<table width="100%">
						<tr>
							<td><strong>Type of Program </strong></td>
							<td><strong> No. of Programs</strong></td>
							
						</tr>
						<?php
							foreach ($program_navchetna as $program_key=>$program_value)
							{
								
								echo '<tr>
										<td>'.$program_value.'<span style="color:#ff0000;">*</span></td>
										<td><input onkeypress="return isNumber(event);" type="text" class="no_of_programs_navchetnapro" name="no_of_programs['.$program_key.']" value="'.$form_no_of_programs[$program_key].'"></td>
									  </tr>';
							 
							}
						?>
						
					</table>
					</td>
				</tr>
				
				
				
				
				<tr>
					<td colspan="2" align="center">
					<input type="submit" style=" margin:0 10px 0 0; float:none;" class="inputbuttonblue" value="Next" name="mode">
					</td>
				</tr>
			</table>
		</form>
</div>


        </div>


    </div>
</div>
<script>
function fill_months(y1)
  {

	
	 $option_str='';
	 var d = new Date();
     var y2 = d.getFullYear();
	 var years1  =  parseInt(y2)-parseInt(y1);
   
	 if(years1 == 0)
	 {
        <?php
		$option_str='';
		$year   =  2019;
		$month  =  date('m');		 
		foreach($arrMonthDate as $key=>$value)
		{
		  
			  if(!in_array($key,getFilledMonth($area_id,$year)))
				{	   
				
				       if($key < $month)
					   {
					     $option_str.=   "<option  value='$key'>". $value."</option>";
					   }	 
								
				}	
		  
		}
		
		?>
		
		  option_str= "<?php echo $option_str;?>";
		
	 }
	 else if(years1 == 1)
	 {
		<?php
		$option_str='';
		$year   =  2018;
		$month  =  date('m');		 
		foreach($arrMonthDate as $key=>$value)
		{
		  
			  if(!in_array($key,getFilledMonth($area_id,$year)))
				{	   
				
				       
					     $option_str.=   "<option  value='$key'>". $value."</option>";
					   	 
								
				}	
		  
		}
		
		?>
		 option_str= "<?php echo $option_str;?>";
		   
	 }
	  else
	 {
		<?php
		$option_str='';
		$year   =  2017;
		$month  =  date('m');		 
		foreach($arrMonthDate as $key=>$value)
		{
		  
			  if(!in_array($key,getFilledMonth($area_id,$year)))
				{	   
				
				       
					     $option_str.=   "<option  value='$key'>". $value."</option>";
					   	 
								
				}	
		  
		}
		
		?>
		 option_str= "<?php echo $option_str;?>";
		   
	 }
	 
	
		 
		   $('#month1').html(option_str);
		      
		   
  }
	



</script>
<?php include('includes/footer.php'); ?>