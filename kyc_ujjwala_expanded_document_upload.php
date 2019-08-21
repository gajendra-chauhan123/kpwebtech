<?php
ob_start();
set_time_limit(0);
include_once("includes/header.php");
include_once("../includes/comman_functions.php");
include_once("../includes/kyc_ujjwala_functions.php");

// echo "This service is disabled till further advice.";
// exit();
$dealer_code = $user['dealer_code'];
$document_size_allowed = 512000;

$errors=array();
$is_valid_kyc = false;
$ration_card_attached = $certificate_attached = $declaration_attached = false;
if(isset($_POST['searchkyc'])){
	$kyc_no_search = trim($_POST['kyc_no_search']);
	
	$query_data = mysql_query("select kyc_no,id, beneficiary_category, concat_ws(' ', first_name,middle_name,last_name) benf_name, date_of_birth, ration_card_number, ration_card_attach, document_attachment,aadhaar_attach_applicant,document_folder, bpl_document_attachment from gb_ujjwala_kyc_expanded where kyc_no='".mysql_real_escape_string($kyc_no_search)."' and dealer_code='".$dealer_code."'");
	if(mysql_num_rows($query_data)==0){
		$query_data = mysql_query("select kyc_no,id, beneficiary_category, concat_ws(' ', first_name,middle_name,last_name) benf_name, date_of_birth, ration_card_number, ration_card_attach, document_attachment,aadhaar_attach_applicant,document_folder, bpl_document_attachment from gb_ujjwala_kyc_expanded_backup where kyc_no='".mysql_real_escape_string($kyc_no_search)."' and dealer_code='".$dealer_code."'");	
	}
	
	if(mysql_num_rows($query_data)>0){
		$is_valid_kyc = true;
		$row_data = mysql_fetch_assoc($query_data);	
		
		if(!empty($row_data['ration_card_attach']))
			$ration_card_attached=true;
		
		if(!empty($row_data['document_attachment']))
			$certificate_attached=true;
		else if(!in_array($row_data['beneficiary_category'], array('SCST', 'MBC', 'OBC', 'OTHER')))
			$certificate_attached=true;
		
		if(!empty($row_data['bpl_document_attachment']))
			$declaration_attached=true;
	}
	else{
		$errors[] = 'No record found against this KYC.';	
	}
}

if(isset($_POST['submitdocument']) && !empty($_POST['kyc_no'])){
	$all_files_valid = true;
	$file_type_allowed = array('application/pdf', 'image/png', 'image/jpg', 'image/jpeg');
	foreach ($_FILES as $file_details){
		if(!empty($file_details['type'])){
			if(!in_array($file_details['type'], $file_type_allowed))
				$all_files_valid = false;
		}
		
		if($file_details['size'] > $document_size_allowed)
			$all_files_valid = false;
	}
	
	if (isset($all_files_valid) && $all_files_valid==false) {
        $errors[] = 'Invalid document! All documents must be valid. Allowed type PDF/JPG/PNG and Max size per document is 500 KB.';
    }
	else{
		$refer_backup_table = false;
		$sql_details = mysql_query("select id, kyc_no, dealer_code, beneficiary_category, ration_card_attach, document_attachment, bpl_document_attachment, document_folder, created_on from gb_ujjwala_kyc_expanded where kyc_no='".mysql_real_escape_string($_POST['kyc_no'])."' and dealer_code='".$dealer_code."'");
		if(mysql_num_rows($sql_details)==0){
			$refer_backup_table = true;
			$sql_details = mysql_query("select id, kyc_no, dealer_code, beneficiary_category, ration_card_attach, document_attachment, bpl_document_attachment, document_folder, created_on from gb_ujjwala_kyc_expanded_backup where kyc_no='".mysql_real_escape_string($_POST['kyc_no'])."' and dealer_code='".$dealer_code."'");	
		}
		
		if(mysql_num_rows($sql_details)>0)
		{
			$kyc_details = mysql_fetch_assoc($sql_details);
			
			$document_folder = $kyc_details['document_folder'];
			$beneficiary_category = $kyc_details['beneficiary_category'];
			
			if(!empty($kyc_details['ration_card_attach']))
				$ration_card_attached=true;
			
			if(!empty($kyc_details['document_attachment']))
				$certificate_attached=true;
			
			if(!empty($kyc_details['bpl_document_attachment']))
				$declaration_attached=true;

			$ration_card_attach_file_name_new = $document_attachment_file_name_new = $bpl_doc_attachment_file_name_new = '';
			
			if($ration_card_attached==false){
				if (isset($_FILES['ration_card_attach']['tmp_name']) && !empty($_FILES['ration_card_attach']['tmp_name'])) {
					if($_FILES['ration_card_attach']['size'] <= $document_size_allowed){
						$ration_card_attach_file_name = $_FILES['ration_card_attach']['name'];
						$ration_card_attach_file_name_new = 'ration_' . date('ymdHis') . mt_rand(100, 1000) . '_' . preg_replace('/[^A-Za-z0-9.\-]/', '', str_replace(' ', '-', $ration_card_attach_file_name));
						$ration_card_attach_file_temp_name = $_FILES['ration_card_attach']['tmp_name'];
						$file_uploaded = upload_files($ration_card_attach_file_name_new, $ration_card_attach_file_temp_name, $document_folder);
						if ($file_uploaded == false)
							$errors[] = 'Ration Card document not uploaded. Please try again.';
					}
					else
						$errors[] = 'Ration card attachement size must be less.';
				}
				else{
					$errors[] = 'Ration card attachement is required. Please attach the ration card.';
				}
			}
			
			if ($certificate_attached==false && ($beneficiary_category == 'SCST' || $beneficiary_category == 'MBC' || $beneficiary_category == 'OBC' || $beneficiary_category == 'OTHER')) {
				if (isset($_FILES['document_attachment']['tmp_name']) && !empty($_FILES['document_attachment']['tmp_name'])) {
					if($_FILES['document_attachment']['size'] <= $document_size_allowed){
						$document_attachment_file_name = $_FILES['document_attachment']['name'];
						$document_attachment_file_name_new = 'document_' . date('ymdHis') . mt_rand(100, 1000) . '_' . preg_replace('/[^A-Za-z0-9.\-]/', '', str_replace(' ', '-', $document_attachment_file_name));
						$document_attachment_file_temp_name = $_FILES['document_attachment']['tmp_name'];
						$file_uploaded = upload_files($document_attachment_file_name_new, $document_attachment_file_temp_name, $document_folder);
						if ($file_uploaded == false)
							$errors[] = 'Caste Certificate Document not uploaded. Please try again.';
					}
					else
						$errors[] = 'Caste Certificate attachement size must be less.';
				}
				else{
					$errors[] = 'Caste Certificate attachement is required. Please attach.';
				}
			}
			
			if($declaration_attached==false){
				if (isset($_FILES['bpl_document_attachment']['tmp_name']) && !empty($_FILES['bpl_document_attachment']['tmp_name'])) {
					if($_FILES['bpl_document_attachment']['size'] <= $document_size_allowed){
						$bpl_doc_attachment_file_name = $_FILES['bpl_document_attachment']['name'];
						$bpl_doc_attachment_file_name_new = 'bpl_doc_' . date('ymdHis') . mt_rand(100, 1000) . '_' . preg_replace('/[^A-Za-z0-9.\-]/', '', str_replace(' ', '-', $bpl_doc_attachment_file_name));
						$bpl_doc_attachment_file_temp_name = $_FILES['bpl_document_attachment']['tmp_name'];
						$file_uploaded = upload_files($bpl_doc_attachment_file_name_new, $bpl_doc_attachment_file_temp_name, $document_folder);
						if ($file_uploaded == false)
							$errors[] = 'Undertaking Document not uploaded. Please try again.';
					}
					else
						$errors[] = 'Undertaking Document attachement size must be less.';
				}
				else{
					$errors[] = 'Undertaking Document attachement is required. Please attach.';
				}
			}
			
			if (count($errors) == 0) {
				if($refer_backup_table)
					$tablename = 'gb_ujjwala_kyc_expanded_backup';
				else
					$tablename = 'gb_ujjwala_kyc_expanded';
				
				$sql_update = "update ".$tablename." set ";
				$sql_update .= "document_folder='".$document_folder."'";
				
				if(!empty($ration_card_attach_file_name_new))
					$sql_update .= ",ration_card_attach='".$ration_card_attach_file_name_new."'";
				
				if(!empty($document_attachment_file_name_new))
					$sql_update .= ",document_attachment='".$document_attachment_file_name_new."'";
				
				if(!empty($bpl_doc_attachment_file_name_new))
					$sql_update .= ",bpl_document_attachment='".$bpl_doc_attachment_file_name_new."'";
				
				$sql_update .= " where id='".$kyc_details['id']."' and kyc_no='".$kyc_details['kyc_no']."'";
				if(mysql_query($sql_update)){
					$_SESSION['successMsg'] = 'Document(s) has been successfully uploaded.';	
					
					// Document Status Update (For mail received on 06/11/2018 09:26 AM)
					mysql_query("insert into gb_ujjwala_kyc_expanded_document_status (kyc_no, dealer_code, is_document_uploaded, kyc_submission_date, document_upload_date, date_time) values ('".$kyc_details['kyc_no']."', '".$kyc_details['dealer_code']."', 1, '".date('Y-m-d', strtotime($kyc_details['created_on']))."', '".date('Y-m-d')."', '".date('Y-m-d H:i:s')."')");
					
					header("location:kyc_ujjwala_expanded_document_upload.php");
					exit;
				}
			}
		}
	}
}




if(isset($_GET['f']))
{
		$query_dwn = mysql_query("select ration_card_attach, document_attachment,aadhaar_attach_applicant,document_folder, bpl_document_attachment from gb_ujjwala_kyc_expanded where id='".base64_decode($_GET['id'])."' ");
		if(mysql_num_rows($query_dwn)==0){
		$query_dwn = mysql_query("select   ration_card_attach, document_attachment,aadhaar_attach_applicant,document_folder, bpl_document_attachment from gb_ujjwala_kyc_expanded_backup where id='".base64_decode($_GET['id'])."' ");	
		}
		$row_dwn = mysql_fetch_assoc($query_dwn);	
		
		//include "../../includes/ftp.class.php";
//$ftp_obj=new ftpClass();
		if($_GET['f']=='ration_card_attach')
		{
		//$file = $row_dwn['document_folder'].'/'.$row_dwn['ration_card_attach'];
		
		
			$result=download_files($row_dwn['document_folder'], $row_dwn['ration_card_attach']);	

		}
		if($_GET['f']=='bpl_document_attachment')
		{
		// $file = $row_dwn['document_folder'].'/'.$row_dwn['bpl_document_attachment'];exit;
        //$ftp_obj->ftp_download_to_browser($file, '111.118.188.215', 'Ration Card');	
		$result=download_files($row_dwn['document_folder'], $row_dwn['bpl_document_attachment']);

		}
		if($_GET['f']=='aadhaar_attach_applicant')
		{
		//$file = $row_dwn['document_folder'].'/'.$row_dwn['aadhaar_attach_applicant'];
        //$ftp_obj->ftp_download_to_browser($file, '111.118.188.215', 'Aadhaar Card');	
         $result=download_files($row_dwn['document_folder'], $row_dwn['aadhaar_attach_applicant']);
		}
		
	
}
?>


<script type="text/javascript">
function validate_search(){
	if (!$('#kyc_no_search').val() || $('#kyc_no_search').val().replace(/\s+$/, '') == '') {
		alert("Please Enter Kyc no.");
		$('#kyc_no_search').focus();
		return false;
	}
}

function validate_final(){
	if ($('#ration_card_attach').is(':enabled') && !$('#ration_card_attach').val()) {
		 alert("Please Attach Ration Card Document");
		 $('#ration_card_attach').focus();
		 return false;
	 }
	 else if($('#ration_card_attach').is(':enabled') && document.getElementById('ration_card_attach').files[0].size > 512000){
		alert("Ration Card attachment size must be less than 500 KB");
		 $('#ration_card_attach').focus();
		 return false;	 
	 }
	 
	 if ($('#document_attachment').is(':enabled') && !$('#document_attachment').val()) {
		 alert("Please Attach Caste Certificate Document");
		 $('#document_attachment').focus();
		 return false;
	 }
	 else if($('#document_attachment').is(':enabled') && document.getElementById('document_attachment').files[0].size > 512000){
		 alert("Caste Certificate Document attachment size must be less than 500 KB");
		 $('#document_attachment').focus();
		 return false;	 
	 }
	 
	 if ($('#bpl_document_attachment').is(':enabled') && !$('#bpl_document_attachment').val()) {
		 alert("Please Attach Undertaking Document");
		 $('#bpl_document_attachment').focus();
		 return false;
	 }
	 else if($('#bpl_document_attachment').is(':enabled') && document.getElementById('bpl_document_attachment').files[0].size > 512000){
		 alert("Undertaking Document attachment size must be less than 500 KB");
		 $('#bpl_document_attachment').focus();
		 return false;	 
	 }
}

function checkfiletype(val, id) {
	var filesize = document.getElementById(id).files[0].size;
	var ext = val.split('.').pop().toLowerCase();
	if ($.inArray(ext, ['png', 'jpg', 'jpeg', 'pdf']) == -1) {
		alert('Invalid file! Please upload a valid file (png/jpg/jpeg/pdf).');
		if ($.browser.msie) {
			$('#' + id).replaceWith($('#' + id).clone(true));

		} else {
			$('#' + id).val('');
		}
	} else {
		if (filesize > 512000) {	//500 KB allowed only
			alert('File size must be less than or equal to 500 KB. Allowed PDF/JPG/PNG only.');
			if ($.browser.msie) {
				$('#' + id).replaceWith($('#' + id).clone(true));
			} else {
				$('#' + id).val('');
			}
		}
	}
}
</script>
<style type="text/css">
fieldset {
	margin-top: 10px;
	margin-bottom: 10px;
	padding-top: 0.35em;
	padding-bottom: 0.625em;
	padding-left: 0.75em;
	padding-right: 0.75em;
	border: 1px #bbb solid;
}
fieldset legend {
	padding-left: 5px;
	padding-right: 5px;
	font-weight: bold;
}
span.required {
	color: #F00;
	font-size: 14px;
}
</style>
<div class="con_text">
  <div class="innerheadercontainer2">
    <div class="borderbottom"></div>
    <div class="midcontainer innerheadermain">
      <div class="innerheaderleft">
        <h1>EXPANDED PMUY DOCUMENT UPLOAD</h1>
      </div>
    </div>
  </div>
  <div class="innnerpagecontainer" style="width:96%">
    <div class="midcontainer">
      <div class="innerpagecontent" style="padding:20px; overflow:hidden; width:1024px; min-height:400px;">
        <?php
		if (isset($_SESSION['successMsg'])) {
			echo '<div class="success_box">' . $_SESSION['successMsg'] . '</div>';
			unset($_SESSION['successMsg']);
		} 
		else if (count($errors) > 0) {
			echo '<div class="error_box">';
			foreach ($errors as $viewerror) {
				echo $viewerror . ' <br/>';
			}
			echo '</div>';
		}
		?>
        <form name="search_form" action="" method="post" onsubmit="return validate_search()">
          <fieldset style="margin-bottom:20px;">
              <legend>Search KYC</legend>
              <table width="98%" border="0" cellspacing="0" cellpadding="0" style="line-height:30px;">
                <tr>
                  <td width="20%">KYC Number <span class="required">*</span></td>
                  <td><input type="text" name="kyc_no_search" id="kyc_no_search" style="width:200px;" value="<?php echo isset($_POST['kyc_no_search']) ? $_POST['kyc_no_search'] : ''; ?>" maxlength="15" /></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><button type="submit" name="searchkyc">Search</button></td>
                </tr>
              </table>
            </fieldset>
        </form>
        <?php
		if($is_valid_kyc){
		?>
        <form name="ujjwala_expanded_document_form" action="" method="post" enctype="multipart/form-data" id="ujjwala_expanded_document_form" onsubmit="return validate_final()">
          <input type="hidden" name="kyc_no" value="<?php echo $row_data['kyc_no']; ?>" />
          <fieldset style="margin-bottom:20px;">
            <legend>KYC Details</legend>
            <table width="98%" border="0" cellspacing="0" cellpadding="0" style="line-height:30px;">
              <tr>
                <td width="20%">KYC No.</td>
                <td><?php echo $row_data['kyc_no']; ?></td>
              </tr>
              <tr>
                <td width="20%">Beneficiary Category</td>
                <td><?php echo $row_data['beneficiary_category']; ?></td>
              </tr>
              <tr>
                <td width="20%">Ration Card No.</td>
                <td><?php echo $row_data['ration_card_number']; ?></td>
              </tr>
              <tr>
                <td width="20%">Beneficiary Name</td>
                <td><?php echo $row_data['benf_name']; ?></td>
              </tr>
              <tr>
                <td width="20%">DOB</td>
                <td><?php echo date('d/m/Y', strtotime($row_data['date_of_birth'])); ?></td>
              </tr>
            </table>
          </fieldset>
          <fieldset style="margin-bottom:20px;">
            <legend>Uploaded Documents</legend>
            <?php
			$color = 0;
			$rashan = 0;
			$aadhar = 0;
			$bpl = 0;
			if($ration_card_attached==true && $certificate_attached==true && $declaration_attached==true){ 
			
			$query_olddata = mysql_query("select * from  gb_old_document_record where kyc_number='".$row_data['kyc_no']."'");
							 
			
			
			 $sql1 = "select document_type as doctype from  gb_old_document_record where kyc_number='".$row_data['kyc_no']."' and document_type='Ration Card'";
			$query_olddata_rasan_card = mysql_query($sql1);
			if(mysql_num_rows($query_olddata_rasan_card)>0){
				$rashan=1;
			}
			$sql2 = "select document_type as doctype from  gb_old_document_record where kyc_number='".$row_data['kyc_no']."' and document_type='Aadhaar'";
			$query_olddata_adhar_card = mysql_query($sql2);
			if(mysql_num_rows($query_olddata_adhar_card)>0){
				$adhar=1;
			}
			
			$sql3 = "select document_type as doctype from  gb_old_document_record where kyc_number='".$row_data['kyc_no']."' and document_type='Bpl'";
			$query_olddata_bpl_card = mysql_query($sql3);
			if(mysql_num_rows($query_olddata_bpl_card)>0){
				$bpl=1;
			}
			
						
							
			?>
				
				<tr>
                            <td><table width="100%" cellpadding="0" cellspacing="0" class="tablecontainer" style="width:100%; word-wrap: break-word;table-layout: fixed;">
                                    <tr>
                                        
                                        <th align="left">Document Name</th>
                                        <th align="left">File Name</th>
                                        <th align="left" nowrap="nowrap">Action</a></th>
                                    </tr>

                               
				 <tr>                                         
                  <? if($row_data['ration_card_attach']){?>                                
       <td align="left" style="text-align:left; padding:0px 3px 0px 5px;">Ration Card</td> 
       
       <td align="left" style="text-align:left; padding:0px 3px 0px 5px;"><? echo  $row_data['ration_card_attach']; ?></td> 
        <td align="left" style="text-align:left; padding:0px 3px 0px 5px;"><a href="kyc_ujjwala_expanded_document_upload.php?f=ration_card_attach&id=<? echo base64_encode($row_data['id']); ?>">Download</a>&nbsp; <?php   if($rashan!=1){?>|&nbsp;<a href="document_edit.php?dwid=<? echo  base64_encode($row_data['kyc_no']); ?>&field=ration_card_attach">Edit</a><?php }?></td> 
       </tr>
       <? }?>
       <? if($row_data['aadhaar_attach_applicant']){?> 
       <tr>
              <td align="left" style="text-align:left; padding:0px 3px 0px 5px;">Aadhaar Card</td>
              
              <td align="left" style="text-align:left; padding:0px 3px 0px 5px;"><? echo  $row_data['aadhaar_attach_applicant']; ?></td> 
              <td align="left" style="text-align:left; padding:0px 3px 0px 5px;"><a href="kyc_ujjwala_expanded_document_upload.php?f=aadhaar_attach_applicant&id=<? echo base64_encode($row_data['id']); ?>">Download</a><?php if($adhar!=1){?>|&nbsp;<a href="document_edit.php?dwid=<? echo base64_encode($row_data['kyc_no']); ?>&field=aadhaar_attach_applicant">Edit</a><?php }?></td> 
       </tr>
       <? }?>
       <? if($row_data['bpl_document_attachment']){?> 
              <tr>
              <td align="left" style="text-align:left; padding:0px 3px 0px 5px;">14 Point Declaration </td> 
                              <td align="left" style="text-align:left; padding:0px 3px 0px 5px;"><? echo  $row_data['bpl_document_attachment']; ?></td> 
                              <td align="left" style="text-align:left; padding:0px 3px 0px 5px;"><a href="kyc_ujjwala_expanded_document_upload.php?f=bpl_document_attachment&id=<? echo base64_encode($row_data['id']); ?>">Download</a>&nbsp;<?php if($bpl!=1){?>|<a href="document_edit.php?dwid=<? echo  base64_encode($row_data['kyc_no']); ?>&field=bpl_document_attachment">Edit</a><?php }?></td> 
       </tr>  
       <? }?>                 
      </table></td>
                        </tr>
                        
                        
                        
                        
           <fieldset style="margin-bottom:20px;">
            <legend>Old Documents</legend>
           
				<tr>
                            <td><table width="100%" cellpadding="0" cellspacing="0" class="tablecontainer" style="width:100%; word-wrap: break-word;table-layout: fixed;">
                                    <tr>
                                        
                                        <th align="left">Document Name</th>
                                        <th align="left">File Name</th>
                                        <th align="left" nowrap="nowrap">Action</a></th>
                                    </tr>

                     <?php
					 if(mysql_num_rows($query_olddata)>0){
						while($data = mysql_fetch_array($query_olddata)){
							$pathDownload='';
							if($data['document_type']=="Ration Card"){
								$pathDownload = 'ration_card_attach';
							}else if($data['document_type']=="Aadhaar"){
								$pathDownload = 'aadhaar_attach_applicant';
							}else if($data['document_type']=="Bpl")
							     $pathDownload = 'bpl_document_attachment';
							?>
							<tr>                                         
                                                  
       <td align="left" style="text-align:left; padding:0px 3px 0px 5px;"><?php if($data['document_type']=='Bpl'){
       	echo '14 Point Declaration';
       }else{
       	echo $data['document_type'];
       } ?></td> 
       
       <td align="left" style="text-align:left; padding:0px 3px 0px 5px;"><? echo  $data['document_name']; ?></td> 
        <td align="left" style="text-align:left; padding:0px 3px 0px 5px;"><a href="kyc_ujjwala_expanded_document_upload.php?f=<?php echo $pathDownload;?>&id=<? echo base64_encode($row_data['id']); ?>">Download</a></td> 
       </tr>
							<?php
						 }	
						}else{ ?>
							<tr>                                         
                                                  
       <td align="left" style="text-align:left; padding:0px 3px 0px 5px;">No Record Found</td> 
       
       <td align="left" style="text-align:left; padding:0px 3px 0px 5px;"></td> 
        <td align="left" style="text-align:left; padding:0px 3px 0px 5px;"></td> 
       </tr>
						<?php }

					 ?>
              
      </table></td>
                        </tr>            
                        
                        
                        
                        
				<? 
			}
			else{
			?>
                <font style="font-size:12px; font-weight:bold; font-style:italic;">(Allowed PDF/JPG/PNG only. Max size 500 KB per document)</font>
                <table width="98%" border="0" cellspacing="0" cellpadding="0" style="line-height:30px;margin-top:15px;">
                  <?php
				  if($ration_card_attached==false){
				  ?>
                      <tr>
                        <td width="20%">Ration Card <span class="required">*</span></td>
                        <td><input type="file" name="ration_card_attach" id="ration_card_attach" onchange="checkfiletype(this.value, this.id)" accept=".pdf,.png,.jpg,.jpeg" /></td>
                      </tr>
                  <?php
				  }
				  
                  if ($certificate_attached==false && ($row_data['beneficiary_category'] == 'SCST' || $row_data['beneficiary_category'] == 'MBC' ||  $row_data['beneficiary_category'] == 'OBC' || $row_data['beneficiary_category'] == 'OTHER')) {
                  ?>
                      <tr>
                        <td width="20%">Caste Certificate <span class="required">*</span></td>
                        <td><input type="file" name="document_attachment" id="document_attachment" onchange="checkfiletype(this.value, this.id)" accept=".pdf,.png,.jpg,.jpeg" /></td>
                      </tr>
                  <?php	
                  }
				  
				  if($declaration_attached==false){
                  ?>
                      <tr>
                        <td width="20%">Supplementary KYC <span class="required">*</span></td>
                        <td><input type="file" name="bpl_document_attachment" id="bpl_document_attachment" onchange="checkfiletype(this.value, this.id)" accept=".pdf,.png,.jpg,.jpeg" /></td>
                      </tr>
                  <?php
				  }
				  ?>
                  <tr>
                    <td>&nbsp;</td>
                    <td><button type="submit" name="submitdocument" class="inputbuttonblue">Submit</button></td>
                  </tr>
                </table>
            <?php
			}
			?>
          </fieldset>
        </form>
        <?php
		}
		?>
      </div>
    </div>
  </div>
  <div class="clr"></div>
</div>
<?php
mysql_close($link);
include_once("includes/footer.php");
?>