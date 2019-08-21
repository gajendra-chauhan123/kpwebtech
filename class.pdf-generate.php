<?php
require_once dirname(__FILE__) . "/../tcpdf/tcpdf.php";
require_once dirname(__FILE__) . "/class.user.php";
require_once dirname(__FILE__) . "/class.advertisement.php";

class CUSTOMPDF extends TCPDF{
	/*public function Header() {
        $image_file = K_PATH_IMAGES.'logo_example.jpg';
        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->SetFont('helvetica', 'B', 20);
        $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }*/

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }	
}

class MYPDF 
{
	protected $pdf;
	protected $user_obj;
	protected $adv_obj;
	
	public function __construct() {
		$this->user_obj = new User();
		$this->adv_obj = new Advertisement();
    }
	
	public function generateApplicationFormPDF($application_ref_no, $dest='I')
	{
		$marital_status_arr = array(1=>'Single', 2=>'Married', 3=>'Widow(er)', 4=>'Divorcee');
		$payment_mode = array('NB'=>'Net Banking', 'CC'=>'Credit Card', 'DC'=>'Debit Card');
		if(empty($application_ref_no))
			return;
		
		$application_details = $this->user_obj->getApplicationFormCompleteDetails($application_ref_no);
		if(empty($application_details))
			return;
			
		$qualification_details = $this->user_obj->getQualification(array("application_id" => $application_details[0]->id));
    	$godown_land_details = $this->user_obj->getGodownLand(array("application_id" => $application_details[0]->id));
		if($application_details[0]->payment_done==1){
			$payment_details = $this->user_obj->getPaymentDetails($application_details[0]->id);
			if(empty($payment_details))
				return;
			
			$amount=0;
			$txn_details = array();
			foreach($payment_details as $key=>$payment_details_value){
				$amount += $payment_details_value->amount;
				$txn_details[$key]['order_no'] = $payment_details_value->order_no;
				$txn_details[$key]['transaction_date_time'] = $payment_details_value->transaction_date_time;
				$txn_details[$key]['payment_through'] = $payment_details_value->payment_mode;	
			}
		}
		else
			$amount=0;
		
		$this->pdf = new CUSTOMPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$this->pdf->SetCreator(PDF_CREATOR);
		$this->pdf->SetAuthor('LPG Vitarak Chayan');
		$this->pdf->SetTitle('Application Form for Selection of LPG Distributor');
		$this->pdf->SetSubject('Application Form for Selection of LPG Distributor');
		$this->pdf->SetKeywords('LPG, Distributor, Application');
		
		//$this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		//$this->pdf->setFontSubsetting(true);
		$this->pdf->AddPage();
		
		$sn=0;
		
		if($application_details[0]->applicant_photo!='' && file_exists(BASE_PATH.$this->user_obj->userPicTarget.$application_details[0]->applicant_photo)){
			$applicant_photo_image = '<img src="'.BASE_PATH.$this->user_obj->userPicTarget.$application_details[0]->applicant_photo.'" height="100" />';
		}
		else{
			$applicant_photo_image = '';	
		}
		
		if($application_details[0]->applicant_sign!='' && file_exists(BASE_PATH.$this->user_obj->userSignTarget.$application_details[0]->applicant_sign)){
			$applicant_sign_image = '<img src="'.BASE_PATH.$this->user_obj->userSignTarget.$application_details[0]->applicant_sign.'" height="60" />';
		}
		else{
			$applicant_sign_image = '';	
		}
		
		$html = '<table width="100%" border="1" cellspacing="0" cellpadding="0">
				  <tr>
					<td align="center" style="font-size:12px; font-weight:bold;">APPLICATION FOR APPOINTMENT OF LPG DISTRIBUTOR<br/>
					  <span style="font-size:9px; font-weight:normal;">(Application submitted at '.$this->user_obj->dateTimeFormat($application_details[0]->final_submitted_on).', IP Address '.$application_details[0]->ip_address.')</span></td>
				  </tr>
				  <tr>
					<td align="left"><table width="100%" border="1" cellspacing="0" cellpadding="0">
						<tr>
						  <td width="100%"><table width="100%" border="1" cellspacing="0" cellpadding="2">';
		
		$html .= '<tr>
					<td colspan="8" rowspan="2" align="center" style="font-size:12px; font-weight:bold;">For Office use<br />
					  <span style="font-size:8px;">not to be filled by applicant</span></td>
					'.$this->user_obj->create_td($application_details[0]->application_ref_no, 22).'
					<td colspan="9" rowspan="5" align="center" style="font-size:13px; font-weight:bold;">'.$applicant_photo_image.'</td>
				  </tr>
			  <tr>
				<td colspan="22" style="font-size:8px;" align="center" valign="middle">Application Reference No.</td>
			  </tr>
			  <tr>
				<td style="font-size:12px; font-weight:bold;" align="center" valign="middle">'.++$sn.'</td>
				<td style="font-size:12px; font-weight:bold;" align="left" valign="middle" colspan="29">Particulars of Advertised Location</td>
			  </tr>';
		
		// Oil Company
		$html .= '<tr>
					<td style="font-size:11px" align="center" valign="middle">a</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="17">Application for LPG Distributorship of<br />
					  <span style="font-size:10px; color:#666;"> (Name of Oil Company - IOC/BPC/HPC)</span></td>
					'.$this->user_obj->create_td($application_details[0]->oil_comapny_name, 12).'
				  </tr>';
		
		// Advertisement Date
		$html .= '<tr>
					<td style="font-size:11px" align="center" valign="middle">b</td>
					<td style="font-size:11px; font-weight:bold;" colspan="17">Advertised on <span style="font-size:10px; color:#666;">(Date of advertisement)</span></td>
					'.$this->user_obj->create_td(date('d-m-Y', strtotime($application_details[0]->advertisement_date)), 12).'
				  </tr>';
		
		// Name of Newspaper
		$html .= '<tr>
					<td style="font-size:11px" align="center" valign="middle">c</td>
					<td style="font-size:11px; font-weight:bold;" colspan="10">Name of Newspaper</td>
					'.$this->user_obj->create_td($application_details[0]->news_paper, 28).'
				  </tr>';
		
		// Distributorship type
		$html .= '<tr>
					<td style="font-size:11px" align="center" valign="middle">d</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10">Type of Distributorship</td>
					'.$this->user_obj->create_td($application_details[0]->distributorship_type, 28).'
				  </tr>';
		
		// Category
		$html .= '<tr>
					<td style="font-size:11px" align="center" valign="middle">e</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10">Category / Sub-category</td>
					'.$this->user_obj->create_td($application_details[0]->adv_category, 28).'
				  </tr>
				  <tr>
					<td style="font-size:9px" align="center" valign="middle"></td>
					<td style="font-size:9px;" colspan="38" >Please attach copy of the Eligibility Certificate (as per applicable attached Annexure) issued by the Competent authority for the category / sub-category applied for.</td>
				  </tr>';
		
		// Name of the Location
		$html .= '<tr>
					<td style="font-size:11px" align="center" valign="middle">f</td>
					<td style="font-size:11px; font-weight:bold;" colspan="10">Name of the Location</td>
					'.$this->user_obj->create_td($application_details[0]->location_name, 28).'
				  </tr>
				  <tr>
					<td style="font-size:11px" align="center" valign="middle"></td>
					<td style="font-size:11px; color:#666;" colspan="15"> (or locality if specified) as per advertisement</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
				  </tr>';
		
		// Gram Panchayat
		$html .= '<tr>
					<td style="font-size:11px" align="center" valign="middle">g</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="7">Gram Panchayat</td>
					'.$this->user_obj->create_td($application_details[0]->gram_panchayat, 31).'
				  </tr>';
		
		// Revenue/Sub Division
		$html .= '<tr>
					<td style="font-size:11px" align="center" valign="middle">h</td>
					<td style="font-size:11px; font-weight:bold;" colspan="10">Revenue Sub Division</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle">&nbsp;</td>
				  </tr>';
		
		// Block
		$html .= '<tr>
					<td style="font-size:11px" align="center" valign="middle">i</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10">Block</td>
					'.$this->user_obj->create_td($application_details[0]->block, 28).'
				  </tr>';
		
		//District
		$html .= '<tr>
					<td style="font-size:11px" align="center" valign="middle">j</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10">District</td>
					'.$this->user_obj->create_td($application_details[0]->adv_district, 28).'
				  </tr>';
		
		// State
		$html .= '<tr>
					<td style="font-size:11px" align="center" valign="middle">k</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10">State / UT</td>
					'.$this->user_obj->create_td($application_details[0]->adv_state, 28).'
				  </tr>';
		
		// Application Fee Perticulars
		$html .= '<tr>
					<td style="font-size:11px; font-weight:bold;" align="center" valign="middle"><strong>'.++$sn.'</strong></td>
					<td style="font-size:11px;" colspan="40"><table width="100%" border="1" cellspacing="0" cellpadding="2">
						<tr>
						  <td colspan="4" style="font-weight:bold;">Particulars of Application fee</td>
						</tr>
						<tr>
						  <td colspan="4" style="font-size:10px; font-weight:normal;" valign="middle"><i>Note : Kindly pay Application Fee as per the type of Location/Distributorship and category as given below. Applicants belonging to SC/ST category applying for Open Category should submit a certificate at the time of Field Verification. If they are unable to do so, at the time of verification, they will disqualify for the lower application fee.</i></td>
						</tr>
						<tr>
						  <td style="font-size:10px;" align="center" valign="middle" rowspan="2">Type of Location</td>
						  <td style="font-size:10px;" align="center" valign="middle" colspan="3">Application Fee</td>
						</tr>
						<tr>
						  <td style="font-size:10px;" align="center" valign="middle">Open category</td>
						  <td style="font-size:10px;" align="center" valign="middle">OBC Category</td>
						  <td style="font-size:10px;" align="center" valign="middle">SC/ST Category</td>
						</tr>
						<tr>
						  <td style="font-size:10px;" align="left" valign="middle">Sheheri & Rurban Vitrak</td>
						  <td style="font-size:10px;" align="center" valign="middle">Rs.10,000</td>
						  <td style="font-size:10px;" align="center" valign="middle">Rs. 5,000</td>
						  <td style="font-size:10px;" align="center" valign="middle">Rs.3,000</td>
						</tr>
						<tr>
						  <td style="font-size:10px;" align="left" valign="middle">Gramin & DKV</td>
						  <td style="font-size:10px;" align="center" valign="middle">Rs.8,000</td>
						  <td style="font-size:10px;" align="center" valign="middle">Rs. 4,000</td>
						  <td style="font-size:10px;" align="center" valign="middle">Rs. 2,500</td>
						</tr>
					  </table></td>
				  </tr>
				  <tr>
					<td style="font-size:11px" align="center" valign="middle">a</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10">Application fee submitted</td>
					<td style="font-size:10px; font-weight:bold;" align="center" valign="middle" colspan="2">Rs.</td>
					'.$this->user_obj->create_td($amount, 6).'
					<td style="font-size:10px;" align="center" valign="middle" colspan="22">'.strtoupper($this->user_obj->convertNumberToWords($amount)).' RUPEES</td>
				  </tr>';
		
		foreach($txn_details as $txn_details_value){
			$html .= '<tr>
						<td style="font-size:11px" align="center" valign="middle"></td>
						<td style="font-size:11px; font-weight:bold;" colspan="10">Transaction No.</td>
						<td style="font-size:10px;" align="center" valign="middle" colspan="5">'.$txn_details_value['order_no'].'</td>
						<td style="font-size:10px;" align="center" valign="middle" colspan="8"><b>Transaction Date/Time</b></td>
						<td style="font-size:10px;" align="center" valign="middle" colspan="6">'.$this->user_obj->dateTimeFormat($txn_details_value['transaction_date_time']).'</td>
						<td style="font-size:10px;" align="center" valign="middle" colspan="5"><b>Payment Mode</b></td>
						<td style="font-size:10px;" align="center" valign="middle" colspan="4">'.$payment_mode[$txn_details_value['payment_through']].'</td>
					  </tr>';
		}
		
		// Applicatnt perticulars
		$html .= '<tr>
					<td style="font-size:12px" align="center" valign="middle"><strong>'.++$sn.'</strong></td>
					<td  style="font-size:12px; font-weight:bold;" colspan="38" align="left">Particulars of applicant</td>
				  </tr>
				  <tr>
					<td align="center" valign="middle" style="font-size:11px">a</td>
					<td colspan="10" style="font-size:11px; font-weight:bold;">Name</td>
					'.$this->user_obj->create_td($application_details[0]->applicant_name, 28).'
				  </tr>
				  <tr>
					<td align="center" valign="middle" style="font-size:11px">b</td>
					<td colspan="10" style="font-size:11px; font-weight:bold;">Father’s / Husband’s Name</td>
					'.$this->user_obj->create_td(($application_details[0]->father_first_name." ".$application_details[0]->father_middle_name." ".$application_details[0]->father_last_name), 28).'
				  </tr>';
		
		$html .= '<tr>
					<td style="font-size:11px" align="center" valign="middle" rowspan="2">c</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10" rowspan="2">Residence Address <br />
					  <span style="font-weight:normal; font-size:9px; color:#666;">(Mandatory)</span></td>
					'.$this->user_obj->create_td(($application_details[0]->applicant_address1." ".$application_details[0]->applicant_address2), 28).'
				  </tr>
				  <tr>
					'.$this->user_obj->create_td($application_details[0]->applicant_address3, 28).'
				  </tr>';
		
		$same_gram_panchayat = $same_revenue_subdivision = '';
		if($application_details[0]->distributorship_type_id==3){  
			$same_gram_panchayat = $application_details[0]->same_gram_panchayat==1 ? 'Yes' : 'No';
			$same_revenue_subdivision = $application_details[0]->same_revenue_subdivision==1 ? 'Yes' : 'No';
		}
		
		$html .= '<tr>
					<td style="font-size:11px" align="center" valign="middle">d</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="30">Whether Gram Panchayat in which applicant resides is the same as Gram Panchayat of the advertisement location?</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="8">'.$same_gram_panchayat.'</td>
				  </tr> 
				  <tr>
					<td style="font-size:11px" align="center" valign="middle">e</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="30">Whether the Sub Division where applicant residence is located is same as the Sub Division of the advertisement location?</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="8">'.$same_revenue_subdivision.'</td>
				  </tr>'; 
				  
		$html .= '<tr>
					<td style="font-size:11px" align="center" valign="middle">f</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10">District <span style="font-weight:normal; font-size:9px; color:#666;">(Mandatory)</span></td>
					'.$this->user_obj->create_td($application_details[0]->applicant_district, 28).'
				  </tr>
				  <tr>
					<td style="font-size:11px" align="center" valign="middle">g</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10">State <span style="font-weight:normal; font-size:9px; color:#666;">(Mandatory)</span></td>
					'.$this->user_obj->create_td($application_details[0]->applicant_state, 17).'
					<td style="font-size:10px;" align="center" valign="middle" colspan="5"><b>Pin Code</b></td>
					'.$this->user_obj->create_td($application_details[0]->applicant_pin_code, 6).'
				  </tr>';
		
		// Applicant Details
		$age_from = new DateTime($application_details[0]->applicant_dob);
		$age_to = new DateTime($application_details[0]->advertisement_date);
		$html .= '<tr>
					<td style="font-size:11px" align="center" valign="middle">h</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10">Mobile No. <span style="font-weight:normal; font-size:9px; color:#666;">(Mandatory)</span></td>
					'.$this->user_obj->create_td($application_details[0]->applicant_mobile_no, 10).'
					<td style="font-size:10px;" align="center" valign="middle" colspan="18"></td>
				  </tr>
				  <tr>
					<td style="font-size:11px" align="center" valign="middle">i</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10">Email ID <span style="font-weight:normal; font-size:9px; color:#666;">(Mandatory)</span></td>
					'.$this->user_obj->create_td($application_details[0]->applicant_email_id, 28).'
				  </tr>
				  <tr>
					<td style="font-size:11px" align="center" valign="middle">j</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10">Aadhaar No. <span style="font-weight:normal; font-size:9px;">(12 digits)</span></td>
					'.$this->user_obj->create_td($this->user_obj->maskAadhaar($application_details[0]->applicant_aadhaar_no), 12).'
					<td style="font-size:10px;" align="center" valign="middle" colspan="17">&nbsp;</td>
				  </tr>
				  <tr>
					<td style="font-size:11px" align="center" valign="middle">k</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10">PAN <span style="font-weight:normal; font-size:9px;">(10 digits)</span></td>
					'.$this->user_obj->create_td($application_details[0]->applicant_pan_no, 10).'
					<td style="font-size:10px;" align="center" valign="middle" colspan="19">&nbsp;</td>
				  </tr>
				  <tr>
					<td style="font-size:11px" align="center" valign="middle">l</td>
					<td style="font-size:11px; font-weight:bold;" colspan="10">Indian Citizen</td>
					<td style="font-size:10px;" align="center" valign="middle" colspan="5">'.($application_details[0]->is_indian==1 ? 'Yes' : 'No').'</td>
					<td style="font-size:10px;" align="center" valign="middle" colspan="3" bgcolor="#F0F0F0">&nbsp;</td>
					<td style="font-size:10px;" align="center" valign="middle" colspan="3"><b>Sex</b></td>
					<td style="font-size:10px;" align="center" valign="middle" colspan="10">'.$application_details[0]->applicant_gender.'</td>
					<td style="font-size:10px;" align="center" valign="middle" colspan="7" bgcolor="#F0F0F0">&nbsp;</td>
				  </tr>  
				  <tr>
					<td style="font-size:11px" align="center" valign="middle" rowspan="2">m</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10" rowspan="2">Date of Birth</td>
					'.$this->user_obj->create_td(date('d-m-Y', strtotime($application_details[0]->applicant_dob)), 10).'
					<td style="font-size:10px;" align="center" valign="middle" colspan="2"><b>Age</b></td>
					'.$this->user_obj->create_td($age_from->diff($age_to)->y, 2).'
					<td style="font-size:10px;" align="center" valign="middle" colspan="3">Years</td>
					'.$this->user_obj->create_td($age_from->diff($age_to)->m, 2).'
					<td style="font-size:10px;" align="center" valign="middle" colspan="4">Months</td>
					'.$this->user_obj->create_td($age_from->diff($age_to)->d, 2).'
					<td style="font-size:10px;" align="center" valign="middle" colspan="4">Days</td>
				  </tr>
				  <tr>
					<td style="font-size:8px; color:#666;" align="center">D</td>
					<td style="font-size:8px; color:#666;" align="center">D</td>
					<td style="font-size:8px;" align="center" bgcolor="#f0f0f0">-</td>
					<td style="font-size:8px; color:#666;" align="center">M</td>
					<td style="font-size:8px; color:#666;" align="center">M</td>
					<td style="font-size:8px;" align="center" bgcolor="#f0f0f0">-</td>
					<td style="font-size:8px; color:#666;" align="center">Y</td>
					<td style="font-size:8px; color:#666;" align="center">Y</td>
					<td style="font-size:8px; color:#666;" align="center">Y</td>
					<td style="font-size:8px; color:#666;" align="center">Y</td>
					<td style="font-size:8px;" colspan="19"> Age as on the date of advertisement - Notice for Appointment of LPG Distributors.</td>
				  </tr>';
		
		// Applicant Details
		$html .= '<tr>
					<td style="font-size:11px" align="center" valign="middle">n</td>
					<td style="font-size:11px; font-weight:bold;"  valign="middle" colspan="10">Marital Status</td>
					<td style="font-size:10px;" align="center" valign="middle" colspan="28">'.$marital_status_arr[$application_details[0]->marital_status].'</td>
				  </tr>
				  <tr>
					<td align="center" valign="middle" style="font-size:11px">o</td>
					<td colspan="10" style="font-size:11px; font-weight:bold;">Name of Spouse(if married)</td>
					'.$this->user_obj->create_td($application_details[0]->spouse_first_name." ".$application_details[0]->spouse_middle_name." ".$application_details[0]->spouse_last_name, 28).'
				  </tr>
				  <tr>
					<td style="font-size:11px" align="center" valign="middle" rowspan="'.(count($qualification_details)+1).'">p</td>
					<td style="font-size:11px; font-weight:bold;" colspan="10">Education</td>
					<td style="font-size:11px;" align="center" valign="middle" colspan="16">School</td>
					<td style="font-size:11px;" align="center" valign="middle" colspan="7">Board</td>
					<td style="font-size:11px;;" align="center" valign="middle" colspan="5">Year of Passing</td>
				  </tr>';
		
		if ($qualification_details){
			foreach ($qualification_details as $qualification){
				  $html .= '<tr>
							<td style="font-size:10px;" colspan="10">Details of Xth Std. or equivalent<br /></td>
							<td style="font-size:10px;" align="center" valign="middle" colspan="16">'.strtoupper($qualification->school_name).'</td>
							<td style="font-size:10px;" align="center" valign="middle" colspan="7">'.strtoupper($qualification->board).'</td>
							<td style="font-size:10px;;" align="center" valign="middle" colspan="5">'.$qualification->passing_year.'</td>
						  </tr>';
			}
		}
		
		//Applicant Deatsils
		$html .= '<tr>
					<td align="center" valign="top" style="font-size:11px" rowspan="2">q</td>
					<td style="font-size:11px;" colspan="35">Have you ever been convicted or any charges have been framed by Court of Law for a criminal offence involving moral turpitude and / or economic offence (other than freedom struggle)?</td>
					<td style="font-size:10px;" align="center" valign="middle" colspan="3">'.($application_details[0]->is_convicted==1 ? 'Yes' : 'No').'</td>
				  </tr>
				  <tr>
					<td style="font-size:11px;" colspan="39"><i>(Applicant should enclose Self Declaration as per the format given in <strong>Appendix - 1)</strong></i></td>
				  </tr>';
			
		// Government Personnels	
		if(strpos($application_details[0]->adv_category, '(GP)')!==false)
		{
			$gp1_ticked=$gp2_ticked=$gp3_ticked=$gp4_ticked='';
			if($application_details[0]->sub_category=='GP-1')
				$gp1_ticked = '<img src="'.BASE_PATH.'assets/images/check-mark-16x16.jpg" />';
			else if($application_details[0]->sub_category=='GP-2')
				$gp2_ticked = '<img src="'.BASE_PATH.'assets/images/check-mark-16x16.jpg" />';
			else if($application_details[0]->sub_category=='GP-3')
				$gp3_ticked = '<img src="'.BASE_PATH.'assets/images/check-mark-16x16.jpg" />';
			else if($application_details[0]->sub_category=='GP-4')
				$gp4_ticked = '<img src="'.BASE_PATH.'assets/images/check-mark-16x16.jpg" />';
			
			$html .= '<tr>
						<td align="center" valign="middle" style="font-size:11px" rowspan="6"><strong>'.++$sn.'</strong></td>
						<td colspan="38" style="font-size:11px; font-weight:bold;">Applicable to applicants applying under Government Personnel (GP) category viz., Open (GP), SC(GP), ST(GP) and OBC(GP).</td>
					  </tr>
					  <tr>
						<td colspan="9" style="font-size:11px; font-weight:bold;" rowspan="4">Please tick in the<br/>
						  applicable box,<br/>
						  against the sub-category to<br/>
						  which you belong.</td>
						<td colspan="26" style="font-size:11px;">Widows / Dependants of personnel of Armed Forces / Central Paramilitary forces / Central or State Special forces who died while performing their duties</td>
						<td colspan="3" style="font-size:11px;" align="center">'.$gp1_ticked.'</td>
					  </tr>
					  <tr>
						<td colspan="26" style="font-size:11px;">Disabled personnel of Armed Forces or Central Paramilitary forces / Central or State Special forces while performing their duties</td>
						<td colspan="3" style="font-size:11px;" align="center">'.$gp2_ticked.'</td>
					  </tr>
					  <tr>
						<td colspan="26" style="font-size:11px;">Ex-Servicemen of the Armed Forces</td>
						<td colspan="3" style="font-size:11px;" align="center">'.$gp3_ticked.'</td>
					  </tr>
					  <tr>
						<td colspan="26" style="font-size:11px;">Widows / Dependants of Central / State Government / Public Sector Undertaking personnel who died while performaing their duties and such diisabled personnel of Central/State Government & Public Sector undertakings causes attributable to performance of duties.</td>
						<td colspan="3" style="font-size:11px;" align="center">'.$gp4_ticked.'</td>
					  </tr>
					  <tr>
						<td colspan="39" style="font-size:10px;">PS : Candidates who are applying against GP category and are categorized as Defence personnel (Army, Navy, Air Force) and will cover widows/dependents of those who died in war, war disabled, disabled during performance of official duty, widows/ dependents of those members of armed forces who died in harness due to attributable causes and diabled in peace due to attributable causes and Ex-serviceman may ubmit respective eligibility certificate in original, at the time of verification</td>
					  </tr>';
		}
				
		// Godown Land	  
		$html .= '<tr>
					<td align="center" valign="middle" style="font-size:11px" rowspan="4"><strong>'.++$sn.'</strong></td>
					<td colspan="39" style="font-size:11px;">Provide following details of the plot(s) of land for construction of LPG godown or constructed LPG godown owned or registered lease for minimum 15 years in the name of applicant / member of Family Unit commencing on any date from the date of advertisement upto the last date of submission of application as specified either in the advertisement or in the Corrigendum (if any) and meeting the norms specified.<br/>
					  <br/>
					  Note : 1. Applicants having registered lease deed commenicng on any date prioir to the date of advertisement will also be considered provided the lease is valid for a minimum period of 15 years from the date of advertisement. The offered land will be verified during Field Verification. In case of Durgam Kshetriya Vitrak, the location for Godown land should be within the Village / cluster of Village limits as per the advertised location. 2. In case land belongs to member of Family Unit, attach Declaration by family member as per <strong>Appendix -2</strong>.</td>
				  </tr>
				  <tr>
					<td colspan="39" style="font-size:10px;"><table width="100%" border="1" cellspacing="0" cellpadding="2">
						<tr>
						  <td rowspan="2" align="center" style="font-size:11px; font-weight:bold;">Name(s) of the owner of Land / Leaseholder</td>
						  <td rowspan="2" align="center" style="font-size:11px; font-weight:bold;">Relationship with applicant</td>
						  <td rowspan="2" align="center" style="font-size:11px; font-weight:bold;">Date of registration of sale deed/gift deed/ registered lease deed/ date of mutation</td>
						  <td rowspan="2" align="center" style="font-size:11px; font-weight:bold;">Address of the location of the land for LPG Godown</td>
						  <td rowspan="2" align="center" style="font-size:11px; font-weight:bold;">Khasra No./ Survey No.</td>
						  <td colspan="2" align="center" style="font-size:11px; font-weight:bold;">Dimensions of land *#</td>
						  <td rowspan="2" align="center" style="font-size:11px; font-weight:bold;">Distance from location in km</td>
						</tr>
						<tr>
						  <td style="font-size:11px; font-weight:bold;" align="center">Length in<br />
							metre</td>
						  <td style="font-size:11px; font-weight:bold;" align="center">Breadth in<br />
							metre</td>
						</tr>';
				
				if($godown_land_details){
					foreach($godown_land_details as $godown_land){
						$html .= '<tr>
								  <td style="font-size:10px;" align="center">'.strtoupper($godown_land->godown_land_owner).'</td>
								  <td style="font-size:10px;" align="center">'.strtoupper($godown_land->godown_owner_rel_with_applicant).'</td>
								  <td style="font-size:10px;" align="center">'.$godown_land->godown_land_reg_date.'</td>
								  <td style="font-size:10px;" align="center">'.strtoupper($godown_land->godown_land_address).'</td>
								  <td style="font-size:10px;" align="center">'.$godown_land->godown_land_khasra_no.'</td>
								  <td style="font-size:10px;" align="center">'.$godown_land->godown_length.'</td>
								  <td style="font-size:10px;" align="center">'.$godown_land->godown_breadth.'</td>
								  <td style="font-size:10px;" align="center">'.$godown_land->godown_distance.'</td>
								</tr>';
					}
				}
			
			$html .= '</table></td>
				  </tr>
				  <tr>
					<td colspan="39" style="font-size:10px;">Note:(1) If you are applying for Sheheri or Rurban Vitrak, the plot of land for Godown should have minimum dimension of 25 M X 30 M or the constructed LPG Godown should have a minimum storage capacity of 8000 Kg. LPG.(2) If you are applying for Gramin Vitrak, the plot of land for Godown should have minimum dimension of 21 M X 26 M or the constructed LPG Godown should have a minimum storage capacity of 5000 Kg. LPG (1) If you are applying for Durgam Kshetriya Vitrak, the plot of land for Godown should have minimum dimension of 15 M X 16 M or the constructed LPG Godown should have a minimum storage capacity of 3000 Kg. LPG (2) In case the applicant has more than the one land the details of the same can also be provided, if required in additional sheet. (3) The land shown above should not be offered by any other applicant for this location and in case it is found at any stage that the same land is offered by more than one applicant, then all such applications shall be rejected or if any selction has been done, the same would be cancelled.</td>
				  </tr>
				  <tr>
					<td colspan="39" style="font-size:10px;">* Provide dimensions of the plot that will be used for proposed godown out of the total land owned.<br />
					# Provide dimensions of the plot in metre (M) only.<br />
					** For Kerala State, land dimensions vary from the above and applicants are advised to refer brochure for the minimum dimension of land for LPG Godown.
					</td>
				  </tr>';
		
		// Showroom Land
		$html .= '<tr>
					<td align="center" valign="middle" style="font-size:11px" rowspan="2"><strong>'.++$sn.'</strong></td>
					<td colspan="39" style="font-size:11px;">If you are applying for Sheheri Vitrak, Rurban Vitrak and Gramin Vitrak, provide the following details of land for Showroom or Ready Built Showroom at the advertised location (owned or leased for minmum 15 years). In case land belongs to member of Family Unit, attach Undertaking as per <strong>Appendix - 4.</strong></td>
				  </tr>
				  <tr>
					<td colspan="39"><table width="100%" border="1" cellspacing="0" cellpadding="2">
						<tr>
						  <td rowspan="2" align="center" style="font-size:11px; font-weight:bold;">Name(s) of the owner of Land /showroom or leaseholder</td>
						  <td rowspan="2" align="center" style="font-size:11px; font-weight:bold;">Relationship with applicant</td>
						  <td rowspan="2" align="center" style="font-size:11px; font-weight:bold;">Date of registration of sale deed/gift / lease/ date of</td>
						  <td rowspan="2" align="center" style="font-size:11px; font-weight:bold;">Address of the location of the land for showroom</td>
						  <td rowspan="2" align="center" style="font-size:11px; font-weight:bold;">Khasra No / Survey No</td>
						  <td colspan="2" align="center" style="font-size:11px; font-weight:bold;">Dimensions #</td>
						</tr>
						<tr>
						  <td style="font-size:11px; font-weight:bold;" align="center">Length in<br />
							metre</td>
						  <td style="font-size:11px; font-weight:bold;" align="center">Breadth in <br />
							metre</td>
						</tr>
						<tr>
						  <td style="font-size:10px;" align="center">'.strtoupper($application_details[0]->showroom_land_owner).'</td>
						  <td style="font-size:10px;" align="center">'.strtoupper($application_details[0]->showroom_owner_rel_with_applicant).'</td>
						  <td style="font-size:10px;" align="center">'.$application_details[0]->showroom_land_reg_date.'</td>
						  <td style="font-size:10px;" align="center">'.strtoupper($application_details[0]->showroom_land_address).'</td>
						  <td style="font-size:10px;" align="center">'.$application_details[0]->showroom_khasra_no.'</td>
						  <td style="font-size:10px;" align="center">'.$application_details[0]->showroom_length.'</td>
						  <td style="font-size:10px;" align="center">'.$application_details[0]->showroom_breadth.'</td>
						</tr>
					  </table></td>
				  </tr>';
		
		// SKO Dealer
		if($application_details[0]->is_sko_dealer==1){
		$html .= '<tr>
					<td align="center" valign="middle" style="font-size:12px"><strong>'.++$sn.'</strong></td>
					<td colspan="39" style="font-size:12px; font-weight:bold;">Additional Information : (To be filled in by SKO Dealers)</td>
				  </tr>
				  <tr>
					<td style="font-size:11px" align="center" valign="middle">a.</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10">Name of the SKO Dealership</td>
					'.$this->user_obj->create_td($application_details[0]->sko_dealer_name, 28).'
				  </tr>
				  <tr>
					<td style="font-size:11px" align="center" valign="middle">b.</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10">Location</td>
					'.$this->user_obj->create_td($application_details[0]->sko_dealer_location, 28).'
				  </tr>
				  <tr>
					<td style="font-size:11px" align="center" valign="middle">c.</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10">District</td>
					'.$this->user_obj->create_td($this->user_obj->getDistrictName($application_details[0]->sko_district), 28).'
				  </tr>
				  <tr>
					<td style="font-size:11px" align="center" valign="middle">d.</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10">State</td>
					'.$this->user_obj->create_td($this->user_obj->getStateName($application_details[0]->sko_district), 28).'
				  </tr>';
				  
		$html .= '<tr>
					<td style="font-size:11px" align="center" valign="middle">e.</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10">Category of dealership</td>
					'.$this->user_obj->create_td($application_details[0]->sko_dealer_category, 28).'
				  </tr>
				  <tr>
					<td style="font-size:11px" align="center" valign="middle">f.</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10">Name of the Oil Company</td>
					'.$this->user_obj->create_td($application_details[0]->sko_dealer_oil_com, 28).'
				  </tr>
				  <tr>
					<td style="font-size:11px" align="center" valign="middle">g.</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10">Constitution of the dealership</td>
					'.$this->user_obj->create_td($application_details[0]->sko_dealer_constitution, 28).'
				  </tr>
				  <tr>
					<td rowspan="2" align="center" valign="middle" style="font-size:11px">h.</td>
					<td colspan="33" rowspan="2"  style="font-size:11px; font-weight:bold;">Average monthly SKO allocation during the preceding 12 months prior to the month of advertisement for this LPG Distributorship</td>
					<td style="font-size:8px; font-weight:bold;" align="center" valign="middle" colspan="6">Av. KL per mth.</td>
				  </tr>
				  <tr>
					<td style="font-size:10px;" align="center" valign="middle" colspan="6">'.htmlentities($application_details[0]->sko_allocation).'</td>
				  </tr>';
		}
		
		//NDNE Dealer
		if($application_details[0]->is_ndne_dealer==1){
		$html .= '<tr>
					<td align="center" valign="middle" style="font-size:12px"><strong>'.++$sn.'</strong></td>
					<td colspan="39" style="font-size:12px; font-weight:bold;">Additional Information : (To be filled in by NDNE LPG Retailers / Distributors)</td>
				  </tr>
				  <tr>
					<td style="font-size:11px" align="center" valign="middle">a.</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10">Name of the LPG NDNE Retailer / Distributor</td>
					'.$this->user_obj->create_td($application_details[0]->ndne_dealer_name, 28).'
				  </tr>
				  <tr>
					<td style="font-size:11px" align="center" valign="middle">b.</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10">Location</td>
					'.$this->user_obj->create_td($application_details[0]->ndne_dealer_location, 28).'
				  </tr>
				  <tr>
					<td style="font-size:11px" align="center" valign="middle">c.</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10">District</td>
					'.$this->user_obj->create_td($this->user_obj->getDistrictName($application_details[0]->ndne_dealer_district), 28).'
				  </tr>
				  <tr>
					<td style="font-size:11px" align="center" valign="middle">d.</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10">State</td>
					'.$this->user_obj->create_td($this->user_obj->getStateName($application_details[0]->ndne_dealer_state), 28).'
				  </tr>
				  <tr>
					<td style="font-size:11px" align="center" valign="middle">e.</td>
					<td  style="font-size:11px; font-weight:bold;" colspan="10">Name of the Oil Company</td>
					'.$this->user_obj->create_td($application_details[0]->ndne_dealer_comp, 28).'
				  </tr>';
		}
		
		// Declaration
		$html .= '<tr>
					<td colspan="40"><span style="font-size:12px; font-weight:bold;">'.++$sn.'. DECLARATION BY THE APPLICANT</span><br/>
					  <br/>
					  <span style="font-size:9px;">I am aware that eligibility for LPG distributorship will be decided based on the information provided by me in my application. On verification by the Oil Company if it is found that the information provided by me is incorrect/ false/ misrepresented then my candidature will stand cancelled and I will be declared ineligible for LPG Distributorship.<br/>
					  <br/>
					  I also confirm that if selected, I will present all the supporting documents in original in respect of the information given by me in this application and failure to present these documents in original will result in cancellation of selection.<br/>
					  <br/>
					  I am fully aware that if I am unable to provide LPG Godown duly approved by the Office of Chief Controller Of Explosives, Petroleum & Explosives Safety Organisation and / or Showroom as per the Oil Company’s standard layout, then the allotment of distributorship made to me will be cancelled.<br/>
					  <br/>
					  I am aware that in case the same land offered by me in my application for provision of LPG Godown and showroom facility is also offered by any other applicant, for the same location, my candidature for LPG Distributorship will be rejected.<br/>
					  <br/>
					  I am fully aware that I will not be appointed as LPG distributor if I am employed. I shall have to resign from the service and produce proof of acceptance of my resignation from my employer before issuance of Letter of Appointment. Failure to do so shall lead to cancellation of my selection. <br/>
					  <br/>
					  I am also aware that I cannot draw any salary / perks / emoluments (other than the pension received) from the state / Central governments and I have to forego these benefits at the time of appointment as LPG Distributor. Failure to comply to this condition will lead to cancellation of my selection.<br/>
					  <br/>
					  I am fully aware that I have to personally manage the operation of LPG Distributorship.<br/>
					  <br/>
					  I am aware that if selected in the draw, I have to provide all weather motorable approach road to the Godown within the timelines given in the Letter Of Intent and an undertaking, as per the prescribed format in the form of a Notarized affidavit will have to be provided at the time of Field Verification Of Credentials (FVC).<br/>
					  <br/>
					  I am aware that if selected, I have to deposit 10% of the applicable security deposit before the FVC is carried out failing which my candidature will be cancelled. In case, if it is found the information given by me is incorrect / false / misrepresentated then my candidature is laible to be cancelled along with forfeiture of the amount deposited before FVC.<br/>
					  <br/>
					  That, if selected, I undertake that I will be depositing an interest free Security deposit as per the policy of the Corporation.<br/>
					  <br/>
					  I have read the terms and conditions applicable for the LPG Distributorships mentioned in the advertisement / Brochure and confirm that I fulfil the eligibility criteria for the LPG distributorship I have applied for in this application<br/>
					  <br/>
					  That, if selected, I undertake that I will submit at the time of Field Verification Of Crendentilas( FVC), duly notarized affidavits, for all the self declarations made in my application with regard to selection of LPG Distributorship<br/>
					  <br/>
					  The checklist at Point No. 10 which is a part of this application has been verified by me before the submissiion of this application form and the same is true and correct 
					 </span> 
					 </td>
				  </tr>';
		
		// Undertaking
		$html .= '<tr>
					<td colspan="40" style="font-size:11px;"><span style="font-size:11px; font-weight:bold; text-align:center; line-height:25px;"><u>Undertaking</u></span><br / ><br />
					<span style="line-height:20px;">
					I, __________ <b>'.strtoupper($application_details[0]->applicant_name).'</b> ________ '. ($application_details[0]->applicant_gender=='Male' ? ' son of ' : ' daughter of / wife of ') .'Shri__________ <b>'.strtoupper($application_details[0]->father_first_name.' '.$application_details[0]->father_middle_name.' '.$application_details[0]->father_last_name).'</b> _________ hereby confirm that the information given above is true and correct. Any wrong information /misrepresentation/ suppression of facts will make me ineligible for this LPG distributorship. </span></td>
				  </tr>
				  <tr>
					<td colspan="40" style="font-size:11px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
						  <td colspan="4" height="20"></td>
						</tr>
						<tr>
						  <td  style="font-size:11px; font-weight:bold;" width="10%">Place :</td>
						  <td style="font-size:10px;" width="40%">'.$application_details[0]->application_place.'</td>
						  <td  style="font-size:11px; font-weight:bold;">Signature of applicant </td>
						  <td style="font-size:10px;" valign="top">'.$applicant_sign_image.'</td>
						</tr>
						<tr>
						  <td colspan="4" height="10"></td>
						</tr>
						<tr>
						  <td  style="font-size:11px; font-weight:bold;" width="10%">Date :</td>
						  <td style="font-size:10px;" align="left" width="40%"><table width="50%" border="1" cellspacing="0" cellpadding="2" align="left">
							  <tr>
								'.$this->user_obj->create_td(date('d-m-Y', strtotime($application_details[0]->final_submitted_on)), 10).'
							  </tr>
							</table></td>
						  <td  style="font-size:11px; font-weight:bold;">Name of applicant<br/>
							<span style="font-size:9px;">(Name in block letters)</span></td>
						  <td style="font-size:10px;">'.strtoupper($application_details[0]->applicant_name).'</td>
						</tr>
					  </table></td>
				  </tr>';
		
		$html .= '</table></td>
				</tr>
			  </table></td>
		  </tr>
		</table>';
		if($dest == 'H')
		{
			echo $html;
		}
		
		$this->pdf->writeHTML($html);
		
		$filename = "Application_Form_".$application_details[0]->application_ref_no.".pdf";
				
		if($dest=='F')
			$this->pdf->Output(APPLICATION_FORM_PDF_FOLDER."/".$filename, $dest); // $dest: F-File Save, I-Show, D-Download	
		else
			$this->pdf->Output($filename, $dest); // $dest: F-File Save, I-Show, D-Download	
		
		$this->pdf = NULL;
		return $filename;
	}
	
	public function generateAcknowledgementSlip($order_no, $dest='I')
	{
		if(empty($order_no))
			return;
		
		$order_details = $this->user_obj->getPaymentDetails(0, $order_no);
		if(empty($order_details))
			return;
		
		$payment_mode = array('NB'=>'Net Banking', 'CC'=>'Credit Card', 'DC'=>'Debit Card');
		
		$this->pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$this->pdf->SetCreator(PDF_CREATOR);
		$this->pdf->SetAuthor('LPG Vitarak Chayan');
		$this->pdf->SetTitle('Acknowledgement Slip');
		$this->pdf->SetSubject('Acknowledgement Slip');
		//$this->pdf->SetKeywords('LPG, Distributor, Application');

		$this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		//$this->pdf->setFontSubsetting(true);
		$this->pdf->AddPage();
		
		$html = '<table width="100%" border="1" cellspacing="0" cellpadding="0" style="font-family: Arial, Helvetica, sans-serif;">
				  <tr>
					<td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
						  <td><img src="'.BASE_PATH.'assets/images/ack-slip-logo.png" width="500" /></td>
						</tr>
						<tr bgcolor="#2c4c87" align="center" style="color:#FFF; font-size:14px;">
						  <td><table width="700" border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td>Acknowledgement Slip</td>
							  </tr>
							  <tr>
								<td height="2"></td>
							  </tr>
							</table></td>
						</tr>
						<tr>
						  <td><table width="100%" border="1" cellspacing="0" cellpadding="5">
							  <tr>
								<td width="50%" style="font-size:11px; font-weight:bold;" align="left">Application Reference No.</td>
								<td style="font-size:11px;" align="left">'.$order_details[0]->application_ref_no.'</td>
							  </tr>
							  <tr bgcolor="#f0f0f0">
								<td style="font-size:11px; font-weight:bold;" align="left">Order No.</td>
								<td style="font-size:11px;" align="left">'.$order_details[0]->order_no.'</td>
							  </tr>
							  <tr>
								<td style="font-size:11px; font-weight:bold;" align="left">Amount</td>
								<td style="font-size:11px;" align="left">Rs. '.$order_details[0]->amount.'</td>
							  </tr>
							  <tr bgcolor="#f0f0f0">
								<td style="font-size:11px; font-weight:bold;" align="left">Transaction Status</td>
								<td style="font-size:11px;" align="left">Success</td>
							  </tr>
							  <tr>
								<td style="font-size:11px; font-weight:bold;" align="left">Transaction Date & Time</td>
								<td style="font-size:11px;" align="left">'.$this->user_obj->dateTimeFormat($order_details[0]->payment_date_time).'</td>
							  </tr>
							  <tr bgcolor="#f0f0f0">
								<td style="font-size:11px; font-weight:bold;" align="left">Payment Mode</td>
								<td style="font-size:11px;" align="left">'. (isset($payment_mode[$order_details[0]->payment_mode]) ? $payment_mode[$order_details[0]->payment_mode] : "") . '</td>
							  </tr>
							  <tr>
								<td colspan="2" style="font-size:11px;" align="center" >This is a system generated message and does not required any signature.</td>
							  </tr>
							</table></td>
						</tr>
					  </table></td>
				  </tr>
				</table>';
		$this->pdf->writeHTML($html);
		
		$filename = "Acknowledgement_Slip_".$order_details[0]->application_ref_no.".pdf";
		
		if($dest=='F')
			$this->pdf->Output(ACKNOWLEDGEMENT_SLIP_PDF_FOLDER."/".$filename, $dest); // $dest: F-File Save, I-Show, D-Download	
		else
			$this->pdf->Output($filename, $dest); // $dest: F-File Save, I-Show, D-Download	
		
		return $filename;
	}
	
	public function generateEligibleCandidatesPDF($roster_id, $dest='I')
	{
		if(empty($roster_id))
			return;
		
		$roster_details = $this->adv_obj->getRosters(0, $roster_id, 1);
		
		$area_office_details = $this->adv_obj->getRosterAreaOffice($roster_id);

		$eligible_candidate_data = $this->adv_obj->getAllEligibleCandidates($roster_id);
		
		if(empty($roster_details)){
			echo 'Invalid Location!';
			return;	
		}
		
		$location_name = $roster_details[0]->location_name;
		$area_office_name = $area_office_details[0]->ro_name;
		
		if($roster_details[0]->eligible_candidate_shortlisted!=1){
			echo 'Eligible candidates are not shortlisted for the Location <b>'.$location_name.'</b>';
			return;
		}
		
		if(empty($eligible_candidate_data)){
			echo 'No Application available for the Location <b>'.$location_name.'</b>';
			return;
		}
		
		$this->pdf = new CUSTOMPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$this->pdf->SetCreator(PDF_CREATOR);
		$this->pdf->SetAuthor('LPG Vitarak Chayan');
		$this->pdf->SetTitle('Eligible Candidates');
		$this->pdf->SetSubject('Eligible Candidates');

		$this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$this->pdf->AddPage();
		
		$draw_text = $roster_details[0]->redraw==1 ? 'Re-Draw' : 'Draw';
		
		$html = '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family: Arial, Helvetica, sans-serif; border-top:0 #fff;">
				  <tr>
					<td align="center" style="font-size:16px; font-weight:bold;">List of Applicants Found Eligible for '.$draw_text.' for Selection of LPG Distributor</td>
			      </tr>
				  <tr>
					<td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
						  <td colspan="2">&nbsp;</td>
						</tr>
						<tr>
						  <td style="font-size:12px; font-weight:bold;" align="left" colspan="2">Name of Area/ Territory/ Regional Office: '.$area_office_name . ' ('.$roster_details[0]->oil_comapny_name.')</td>
						</tr>
						<tr>
						  <td colspan="2">&nbsp;</td>
						</tr>
						<tr>
						  <td style="font-size:12px;" align="left">Name of Location: '.strtoupper($roster_details[0]->location_name).' </td>
						  <td style="font-size:12px;" align="left">Category: '.$roster_details[0]->category_name.'</td>
						</tr>
						<tr>
						  <td style="font-size:12px;" align="left">Oil Company: '.$roster_details[0]->oil_comapny_name.' </td>
						  <td style="font-size:12px;" align="left">Type of Distributorship: '.$roster_details[0]->dist_type_details.'</td>
						</tr>
						<tr>
						  <td style="font-size:12px;" align="left">Name of District: '.$roster_details[0]->district.'</td>
						  <td style="font-size:12px;" align="left">MKT Plan: '.$roster_details[0]->marketing_plan.'</td>
						</tr>
						<tr>
						  <td colspan="2">&nbsp;</td>
						</tr>';
		
		$table_header = '<table width="100%" border="1" cellspacing="0" cellpadding="5">
						  <tr>
							<td style="font-size:11px; font-weight:bold;" align="left" width="10%">S.NO.</td>
							<td style="font-size:11px; font-weight:bold;" align="left" width="30%">NAME OF PERSON</td>
							<td style="font-size:11px; font-weight:bold;" align="left" width="30%">FATHER / HUSBAND\'S NAME</td>
							<td style="font-size:11px; font-weight:bold;" align="left" width="30%">APPLICATION REFERENCE NO.</td>
						  </tr>';
		
		$table_footer = '</table>';

		$gp_categories = array(6,9,11,17);
		if($roster_details[0]->dist_type_id==3 || in_array($roster_details[0]->category_id, $gp_categories))
		{
			if(!in_array($roster_details[0]->category_id, $gp_categories)){
				$listDetails = $this->adv_obj->getAllListDetailsForDkvOrGP($roster_details[0]->dist_type_id, 0);
			}
			else{
				$listDetails = $this->adv_obj->getAllListDetailsForDkvOrGP($roster_details[0]->dist_type_id, 1);	
			}
			
			foreach($listDetails as $key=>$list_value){
				$html .= '<tr><td colspan="2" style="font-size:14px; font-weight:bold;">Inter se\' Priority List '.$list_value->list_no.'</td></tr>';
				$html .= '<tr><td colspan="2" style="font-size:12px; font-weight:bold;">'.$list_value->list_title.'<br /></td></tr>';
				$html .= '<tr><td colspan="2">';
				$html .= $table_header;
				$eligible_candidate_data = $this->adv_obj->getAllEligibleCandidates($roster_id, array('list_no'=>$list_value->list_no));
				if(count($eligible_candidate_data)>0){
					$i=0;
					foreach($eligible_candidate_data as $key=>$eligible_candidate)
					{
						$html .= '<tr>
								  <td style="font-size:11px;" align="left">'.++$i.'</td>
								  <td style="font-size:11px;" align="left">'.strtoupper($eligible_candidate->applicant_name).'</td>
								  <td style="font-size:11px;" align="left">'.strtoupper($eligible_candidate->applicant_father_name).'</td>
								  <td style="font-size:11px;" align="left">'.$eligible_candidate->application_ref_no.'</td>
								  </tr>';	
					}
				}
				else{
					$html .= '<tr><td colspan="4" style="font-size:11px;" align="left">No applicants.</td></tr>';	
				}
				$html .= $table_footer;
				$html .= '</td></tr>';
				$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
			}
		}
		else{
			$html .= '<tr><td colspan="2">';
			$html .= $table_header;
			$i=0;
			foreach($eligible_candidate_data as $key=>$eligible_candidate)
			{
				$html .= '<tr>
						  <td style="font-size:11px;" align="left">'.++$i.'</td>
						  <td style="font-size:11px;" align="left">'.strtoupper($eligible_candidate->applicant_name).'</td>
						  <td style="font-size:11px;" align="left">'.strtoupper($eligible_candidate->applicant_father_name).'</td>
						  <td style="font-size:11px;" align="left">'.$eligible_candidate->application_ref_no.'</td>
						  </tr>';	
			}	
			$html .= $table_footer;	
			$html .= '</td></tr>';
		}
				
		$html .= '</table></td>
				  </tr>
				  <tr>
				    <td>&nbsp;</td>
				  </tr>
				  <tr>
				    <td style="font-size:10px;">As this is a computer generated document, it does not require any signature.</td>
				  </tr>
				  <tr>
				    <td>&nbsp;</td>
				  </tr>
				  <tr>
				    <td style="font-size:10px;"><b>Note:-</b> If one candidate has submitted multiple applications for same location; only one application will be considered at the time of Draw.</td>
				  </tr>
				</table>';
		
		$this->pdf->writeHTML($html);
		
		$filename = "List_Of_Eligible_Candidate_".$location_name.".pdf";
		ob_clean();
		$this->pdf->Output($filename, $dest); // $dest: F-File Save, I-Show, D-Download	
		
		return $filename;
	}

	public function generateSecurityLetterPDF($roster_details, $dest='I')
	{
		$AdvertisedCategoryDocDetails = array(
			'OPEN'=>'
					<li>Self-attested copy of photo ID as proof of identity, issued by any Government authority. The self-attested copy will be verified with the original.</li>
					<li>Declaration by the applicant as per Appendix-1 <strong>(in original)</strong></li>
					<li>Proof of date of birth i.e. - School Leaving Certificate / Birth Certificate / Passport / PAN Card. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Proof of educational qualification i.e. - Copy of Certificate of passing X Std. or equivalent. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Declaration as per format given in Appendix -2 / Undertaking as per format given in Appendix -4 whichever is applicable. <strong>(in original)</strong></li>
					<li>Land documents: Documents pertaining to land/godown/ showroom in the name of applicant or member of &lsquo;family unit&rsquo; Registered Sale Deed/Gift Deed/Lease Deed (15yrs minimum)/ Mutation and Government record.<strong> (self-attested photocopy of the original)</strong></li>', 
			'SC'=>'
					<li>Self-attested copy of photo ID as proof of identity, issued by any Government authority. The self-attested copy will be verified with the original.</li>
					<li>Declaration by the applicant as per Appendix-1 <strong>(in original)</strong></li>
					<li>Proof of date of birth i.e. - School Leaving Certificate / Birth Certificate / Passport / PAN Card. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Proof of educational qualification i.e. - Copy of Certificate of passing X Std. or equivalent. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Declaration as per format given in Appendix -2 / Undertaking as per format given in Appendix -4 whichever is applicable. <strong>(in original)</strong></li>
					<li>Land documents: Documents pertaining to land/godown/ showroom in the name of applicant or member of &lsquo;family unit&rsquo; Registered Sale Deed/Gift Deed/Lease Deed (15yrs minimum)/ Mutation and Government record.<strong> (self-attested photocopy of the original)</strong></li>
					<li>Eligibility certificate from competent authority as per Appendix- 3a <strong>(self-attested photocopy of the original)</strong></li>', 
			'OBC'=>'
					<li>Self-attested copy of photo ID as proof of identity, issued by any Government authority. The self-attested copy will be verified with the original.</li>
					<li>Declaration by the applicant as per Appendix-1 <strong>(in original)</strong></li>
					<li>Proof of date of birth i.e. - School Leaving Certificate / Birth Certificate / Passport / PAN Card. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Proof of educational qualification i.e. - Copy of Certificate of passing X Std. or equivalent. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Declaration as per format given in Appendix -2 / Undertaking as per format given in Appendix -4 whichever is applicable. <strong>(in original)</strong></li>
					<li>Land documents: Documents pertaining to land/godown/ showroom in the name of applicant or member of &lsquo;family unit&rsquo; Registered Sale Deed/Gift Deed/Lease Deed (15yrs minimum)/ Mutation and Government record.<strong> (self-attested photocopy of the original)</strong></li>
					<li>Eligibility certificate from competent authority as per Appendix -3b / Declaration / Undertaking as per Appendix- 3c for confirmation of Non-Creamy Layer.<strong> (original)</strong></li>',
			'SC(GP)'=>'
					<li>Self-attested copy of photo ID as proof of identity, issued by any Government authority. The self-attested copy will be verified with the original.</li>
					<li>Declaration by the applicant as per Appendix-1 <strong>(in original)</strong></li>
					<li>Proof of date of birth i.e. - School Leaving Certificate / Birth Certificate / Passport / PAN Card. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Proof of educational qualification i.e. - Copy of Certificate of passing X Std. or equivalent. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Declaration as per format given in Appendix -2 / Undertaking as per format given in Appendix -4 whichever is applicable. <strong>(in original)</strong></li>
					<li>Land documents: Documents pertaining to land/godown/ showroom in the name of applicant or member of &lsquo;family unit&rsquo; Registered Sale Deed/Gift Deed/Lease Deed (15yrs minimum)/ Mutation and Government record.<strong> (self-attested photocopy of the original)</strong></li>
					<li>Eligibility certificate from competent authority as per Appendix- 3a <strong>(self-attested photocopy of the original)</strong></li>
					<li>If applying under Defence Personnel, to submit s<strong>elf attested copy of the Eligibility Certificate</strong> issued from the Directorate General of Resettlement (DGR), Ministry of Defence, sponsoring the candidate for the LPG Distributorship location for which he/she has applied.</li>
					<li>If applying under Central Para Military Forces/Special Forces Or Government Personnel &amp; Central/State Government Public Sector Undertakings, submit <strong>(self-attested photocopy of the original Eligibility Certificate</strong> (format as per Appendix -3e)</li>',
			'OBC(GP)'=>'
					<li>Self-attested copy of photo ID as proof of identity, issued by any Government authority. The self-attested copy will be verified with the original.</li>
					<li>Declaration by the applicant as per Appendix-1 <strong>(in original)</strong></li>
					<li>Proof of date of birth i.e. - School Leaving Certificate / Birth Certificate / Passport / PAN Card. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Proof of educational qualification i.e. - Copy of Certificate of passing X Std. or equivalent. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Declaration as per format given in Appendix -2 / Undertaking as per format given in Appendix -4 whichever is applicable. <strong>(in original)</strong></li>
					<li>Land documents: Documents pertaining to land/godown/ showroom in the name of applicant or member of &lsquo;family unit&rsquo; Registered Sale Deed/Gift Deed/Lease Deed (15yrs minimum)/ Mutation and Government record.<strong> (self-attested photocopy of the original)</strong></li>
					<li>Eligibility certificate from competent authority as per Appendix -3b / Declaration / Undertaking as per Appendix- 3c for confirmation of Non-Creamy Layer.<strong> (original)</strong>&nbsp;</li>
					<li>If applying under Defence Personnel, to submit self attested copy of the Eligibility Certificate issued from the Directorate General of Resettlement (DGR), Ministry of Defence, sponsoring the candidate for the LPG Distributorship location for which he/she has applied.<strong> (self-attested photocopy of the original)</strong></li>
					<li>If applying under Central Para Military Forces/Special Forces Or Government Personnel &amp; Central/State Government Public Sector Undertakings, submit Eligibility Certificate as per Appendix -3e <strong>(original)</strong></li>',
			'OPEN(GP)'=>'
					<li>Self-attested copy of photo ID as proof of identity, issued by any Government authority. The self-attested copy will be verified with the original.</li>
					<li>Declaration by the applicant as per Appendix-1 <strong>(in original)</strong></li>
					<li>Proof of date of birth i.e. - School Leaving Certificate / Birth Certificate / Passport / PAN Card. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Proof of educational qualification i.e. - Copy of Certificate of passing X Std. or equivalent. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Declaration as per format given in Appendix -2 / Undertaking as per format given in Appendix -4 whichever is applicable. <strong>(in original)</strong></li>
					<li>Land documents: Documents pertaining to land/godown/ showroom in the name of applicant or member of &lsquo;family unit&rsquo; Registered Sale Deed/Gift Deed/Lease Deed (15yrs minimum)/ Mutation and Government record.<strong> (self-attested photocopy of the original)</strong></li>
					<li>If applying under Defence Personnel, to submit self attested copy of the Eligibility Certificate issued from the Directorate General of Resettlement (DGR), Ministry of Defence, sponsoring the candidate for the LPG Distributorship location for which he/she has applied.</li>
					<li>If applying under Central Para Military Forces/Special Forces Or Government Personnel &amp; Central/State Government Public Sector Undertakings, submit Eligibility Certificate as per Appendix -3e <strong>(original)</strong>&nbsp;</li>',
			'SC(PH)'=>'
					<li>Self-attested copy of photo ID as proof of identity, issued by any Government authority. The self-attested copy will be verified with the original.</li>
					<li>Declaration by the applicant as per Appendix-1 <strong>(in original)</strong></li>
					<li>Proof of date of birth i.e. - School Leaving Certificate / Birth Certificate / Passport / PAN Card. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Proof of educational qualification i.e. - Copy of Certificate of passing X Std. or equivalent. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Declaration as per format given in Appendix -2 / Undertaking as per format given in Appendix -4 whichever is applicable. <strong>(in original)</strong></li>
					<li>Land documents: Documents pertaining to land/godown/ showroom in the name of applicant or member of &lsquo;family unit&rsquo; Registered Sale Deed/Gift Deed/Lease Deed (15yrs minimum)/ Mutation and Government record.<strong> (self-attested photocopy of the original)</strong></li>
					<li>Eligibility certificate from competent authority as per Appendix- 3a <strong>(self-attested photocopy of the original)</strong></li>
					<li>Eligibility certificate from competent authority as per Appendix -3b / Declaration / Undertaking as per Appendix- 3c for confirmation of Non-Creamy Layer.</li>
					<li>Disability Certificate as per Appendix-3d.( Form II, Form III, Form IV as applicable). <strong>(original)</strong></li>',
			'OBC(PH)'=>'
					<li>Self-attested copy of photo ID as proof of identity, issued by any Government authority. The self-attested copy will be verified with the original.</li>
					<li>Declaration by the applicant as per Appendix-1 <strong>(in original)</strong></li>
					<li>Proof of date of birth i.e. - School Leaving Certificate / Birth Certificate / Passport / PAN Card. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Proof of educational qualification i.e. - Copy of Certificate of passing X Std. or equivalent. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Declaration as per format given in Appendix -2 / Undertaking as per format given in Appendix -4 whichever is applicable. <strong>(in original)</strong></li>
					<li>Land documents: Documents pertaining to land/godown/ showroom in the name of applicant or member of &lsquo;family unit&rsquo; Registered Sale Deed/Gift Deed/Lease Deed (15yrs minimum)/ Mutation and Government record.<strong> (self-attested photocopy of the original)</strong></li>
					<li>Eligibility certificate from competent authority as per Appendix- 3a <strong>(self-attested photocopy of the original)</strong></li>
					<li>Eligibility certificate from competent authority as per Appendix -3b / Declaration / Undertaking as per Appendix- 3c for confirmation of Non-Creamy Layer.</li>
					<li>Disability Certificate as per Appendix-3d.( Form II, Form III, Form IV as applicable). <strong>(original)</strong></li>',
			'OPEN(PH)'=>'
					<li>Self-attested copy of photo ID as proof of identity, issued by any Government authority. The self-attested copy will be verified with the original.</li>
					<li>Declaration by the applicant as per Appendix-1 <strong>(in original)</strong></li>
					<li>Proof of date of birth i.e. - School Leaving Certificate / Birth Certificate / Passport / PAN Card. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Proof of educational qualification i.e. - Copy of Certificate of passing X Std. or equivalent. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Declaration as per format given in Appendix -2 / Undertaking as per format given in Appendix -4 whichever is applicable. <strong>(in original)</strong></li>
					<li>Land documents: Documents pertaining to land/godown/ showroom in the name of applicant or member of &lsquo;family unit&rsquo; Registered Sale Deed/Gift Deed/Lease Deed (15yrs minimum)/ Mutation and Government record.<strong> (self-attested photocopy of the original)</strong></li>
					<li>Disability Certificate as per Appendix-3d.( Form II, Form III, Form IV as applicable). <strong>(original)</strong>&nbsp;</li>',
			'OPEN(CC)'=>'
					<li>Self-attested copy of photo ID as proof of identity, issued by any Government authority. The self-attested copy will be verified with the original.</li>
					<li>Declaration by the applicant as per Appendix-1<strong>(original)</strong></li>
					<li>Proof of date of birth ie- School Leaving Certificate / Birth Certificate / Passport / PAN Card<strong>(self attested photocopy)</strong></li>
					<li>Proof of educational qualification i.e.- Copy of Certificate of passing X Std. or equivalent <strong>(self attested photocopy)</strong></li>
					<li>Declaration as per Appendix -2 / Undertaking as per Appendix -4 if applicable. <strong>(original)</strong></li>
					<li>Land documents: Documents pertaining to land/godown/ showroom in the name of applicant or member of &lsquo;family unit&rsquo; Registered Sale Deed/Gift Deed/Lease Deed (15yrs minimum)/ Mutation and Government record. <strong>(self attested photocopy)</strong></li>
					<li>If applying as OSP (Outstanding Sports Person), to produce a certificate from the Recognized National Federation Organizing National Championships (as recognized by Department of Youth Affairs and Sports, Govt. of India) or from the Dept of Youth Affairs and Sports, Govt. of India.<strong> (original)</strong></li>
					<li>If applying under FF (Freedom Fighter) category should attach a certificate or Tamrapatra or an attested copy of the Pension Order issued by the Accountant General in pursuance of the sanction letter from the Ministry of Home Affairs, Govt. of India of their having been Freedom Fighters.</li>'
			);
		$AdvertisedCategoryDocDetails['ST(GP)']=$AdvertisedCategoryDocDetails['SC(GP)'];
		$AdvertisedCategoryDocDetails['ST(PH)']=$AdvertisedCategoryDocDetails['SC(PH)'];

		$AdvertisedCategoryDocDetails['OPEN(W)']=$AdvertisedCategoryDocDetails['OPEN'];
		$AdvertisedCategoryDocDetails['OBC(W)']=$AdvertisedCategoryDocDetails['OBC'];
		$AdvertisedCategoryDocDetails['ST']=$AdvertisedCategoryDocDetails['ST(W)']=$AdvertisedCategoryDocDetails['SC(W)']=$AdvertisedCategoryDocDetails['SC'];
		
		$application_ref_no = $roster_details->application_ref_no;
		
		$application_details = $this->user_obj->getApplicationFormCompleteDetails($application_ref_no);
		
		$oil_company_result = $this->user_obj->db_obj->query("select * from tbl_master_oil_companies where id = " . $roster_details->oil_company );
		if ($this->user_obj->db_obj->numRows($oil_company_result) == 1) {
			$oil_comapny_deatail = $this->user_obj->db_obj->fetchResult($oil_company_result);
		}
		else{
			return false;
		}

		$distributorship_fees_result = $this->user_obj->db_obj->query("select * from tbl_master_application_fees where caste_cat_id = '" . $application_details[0]->location_caste_cat_id . "' and dist_type_id = '" . $roster_details->dist_type . "'");
		if ($this->user_obj->db_obj->numRows($distributorship_fees_result) == 1) {
			$distributorship_fees_deatail = $this->user_obj->db_obj->fetchResult($distributorship_fees_result);
		}
		else{
			return false;
		}
		
		$template_var_array = array('{$AppRefNo}'=>$application_details[0]->application_ref_no,
									'{$IssueDate}'=>date('d/m/Y'),
									'{$ApplicantName}'=>strtoupper($application_details[0]->applicant_first_name.' '.$application_details[0]->applicant_middle_name.' '.$application_details[0]->applicant_last_name),
									'{$ApplicantAddress}'=>strtoupper($application_details[0]->applicant_address1.',<br />'.$application_details[0]->applicant_address2.',<br />'.$application_details[0]->applicant_address3),
									'{$ApplicantDistrict}'=>strtoupper($application_details[0]->applicant_district),
									'{$ApplicantState}'=>strtoupper($application_details[0]->applicant_state.', Pincode - '.$application_details[0]->applicant_pin_code),
									'{$OMCName}'=>$application_details[0]->oil_comapny_name,
									'{$AdvertisedLocation}'=>$application_details[0]->location_name,
									'{$AdvertisedDistrict}'=>$application_details[0]->adv_district,
									'{$AdvertisedCategory}'=>$application_details[0]->adv_category,
									'{$AdvertisedDate}'=>$this->adv_obj->dateFormat($application_details[0]->advertisement_date),
									'{$AdvertisedDistributerType}'=>$application_details[0]->distributorship_type,
									'{$SecurityAmount}'=>$distributorship_fees_deatail[0]->security_amount,
									'{$SecurityAmountInWords}'=>ucwords($this->user_obj->getIndianCurrency($distributorship_fees_deatail[0]->security_amount)),
									'{$OMCFullName}'=>$oil_comapny_deatail[0]->oil_company_full_name,
									'{$PlaceOfAreaOffice}'=>$roster_details->location_for_dd,
									'{$AM_TM_RM_name}'=>$roster_details->manager_name,
									'{$Designation}'=>$roster_details->manager_designation,
									'{$OfficerContactNo}'=>$roster_details->manager_contact,
									'{$AreaOfficeName}'=>$roster_details->ro_name,
									'{$AreaOfficeAddress}'=>$roster_details->manager_address,
									'{$distributorship_fees}'=>$distributorship_fees_deatail[0]->distributorship_fees,
									'{$distributorship_feesInWords}'=>ucwords($this->user_obj->getIndianCurrency($distributorship_fees_deatail[0]->distributorship_fees)),
									'{$AdvertisedCategoryDocDetails}'=>$AdvertisedCategoryDocDetails[$application_details[0]->adv_category],
									'{$MobileNo}'=>$application_details[0]->applicant_mobile_no,
									'{$DrawTime}'=>date('H:i',strtotime($roster_details->draw_date_expected)).' hrs',
									'{$DrawDate}'=>$this->adv_obj->dateFormat($roster_details->draw_date_expected),
									'{$OmcLogo}'=>''
								);
		
		if($roster_details->draw_type == 3){
			$template_var_array['{$selectionMessageText}'] = 'We are pleased to inform you that you have been declared as selected candidate for the LPG Distributorship at subject location as you are found to be the lone eligible candidate in the inter se\' priority list.';	
		}
		else if($roster_details->draw_type == 2){
			$template_var_array['{$selectionMessageText}'] = 'We are pleased to inform you that you have been declared as selected candidate for the LPG Distributorship at subject location as you are found to be the lone eligible candidate.';	
		}
		else{
			$template_var_array['{$selectionMessageText}'] = 'We are pleased to inform you that you have been declared as successful candidate in the draw of lots conducted on '.$this->adv_obj->dateFormat($roster_details->draw_date_expected).' at '.date('H:i',strtotime($roster_details->draw_date_expected)).' hrs for selection of LPG Distributor at the subject location.';	
		}
		
		if($roster_details->oil_company==1){
			$template_var_array['{$OmcLogo}'] = '<img src="'.BASE_PATH.'assets/images/IOCL_Logo.png" alt="" height="75" />';
		}
		else if($roster_details->oil_company==2){
			$template_var_array['{$OmcLogo}'] = '<img src="'.BASE_PATH.'assets/images/BPCL_Logo.png" alt="" height="85" />';
		}
		else if($roster_details->oil_company==3){
			$template_var_array['{$OmcLogo}'] = '<img src="'.BASE_PATH.'assets/images/HPCL_Logo.png" alt="" height="85" />';
		}
		
		$this->pdf = new CUSTOMPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$this->pdf->SetCreator(PDF_CREATOR);
		$this->pdf->SetAuthor('LPG Vitarak Chayan');
		$this->pdf->SetTitle('Security Deposit Letter for Selection of LPG Distributor');
		$this->pdf->SetSubject('Security Deposit Letter for Selection of LPG Distributor');
		$this->pdf->SetKeywords('LPG, Distributor, Security Deposit Letter');
		$this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$this->pdf->setPrintHeader(false);
		$this->pdf->AddPage();
		
		$html = file_get_contents(SECURITY_DEPOSIT_PDF_FOLDER."/".'Security_Deposit_Template.html');
		
		$html = strtr($html, $template_var_array);
		//echo $html; exit;
		$this->pdf->writeHTML($html);
		$html = utf8_encode($html);
		$filename = "Security_Deposit_".$application_ref_no.".pdf";
		$filename = str_replace("/", "", $filename);
		
		if($dest=='F')
			$this->pdf->Output(SECURITY_DEPOSIT_PDF_FOLDER."/".$filename, $dest); // $dest: F-File Save, I-Show, D-Download	
		else
			$this->pdf->Output($filename, $dest); // $dest: F-File Save, I-Show, D-Download	
		
		$this->pdf = NULL;
		return $filename;
	}
		
	public function generateDrawIntimationLetterPDF($roster_details, $dest='I')
	{
		$application_ref_no = $roster_details->application_ref_no;
		//$OMC_Details = $this->adv_obj->getMasterOilCompanyData_1(array('id' => $roster_details->oil_company));
		
		$application_details = $this->user_obj->getApplicationFormCompleteDetails($application_ref_no);
		
		$oil_company_result = $this->user_obj->db_obj->query("select * from tbl_master_oil_companies where id = " . $roster_details->oil_company );
        if ($this->user_obj->db_obj->numRows($oil_company_result) == 1) 
		{
			$oil_comapny_deatail = $this->user_obj->db_obj->fetchResult($oil_company_result);
		}
		else
		{
			return false;
		}
		
		if($application_details[0]->is_draw_intimation_mail_sent == '1')
		{
			return false;
		}
		$template_var_array = array('{$AppRefNo}'=>$application_details[0]->application_ref_no,
									'{$IssueDate}'=>date('d/m/Y'),
									'{$ApplicantName}'=>strtoupper($application_details[0]->applicant_first_name.' '.$application_details[0]->applicant_middle_name.' '.$application_details[0]->applicant_last_name),
									'{$ApplicantAddress}'=>strtoupper($application_details[0]->applicant_address1.', '.$application_details[0]->applicant_address2.', '.$application_details[0]->applicant_address3),
									'{$ApplicantDistrict}'=>strtoupper($application_details[0]->applicant_district),
									'{$ApplicantState}'=>strtoupper($application_details[0]->applicant_state.', Pincode - '.$application_details[0]->applicant_pin_code),
									'{$OMCName}'=>$application_details[0]->oil_comapny_name,
									'{$AdvertisedLocation}'=>$application_details[0]->location_name,
									'{$AdvertisedDistrict}'=>$application_details[0]->adv_district,
									'{$AdvertisedCategory}'=>$application_details[0]->adv_category,
									'{$AdvertisedDate}'=>$application_details[0]->advertisement_date,
									'{$AdvertisedDistributerType}'=>$application_details[0]->distributorship_type,
									'{$OMCFullName}'=>$oil_comapny_deatail[0]->oil_company_full_name,
									'{$AM_TM_RM_name}'=>$roster_details->manager_name,
									'{$Designation}'=>$roster_details->manager_designation,
									'{$OfficerContactNo}'=>$roster_details->manager_contact,
									'{$AreaOfficeName}'=>$roster_details->ro_name,
									'{$AreaOfficeAddress}'=>$roster_details->manager_address,
									'{$DrawAddress}'=>$roster_details->draw_venue,
									'{$DrawTime}'=>date('H:i',strtotime($roster_details->draw_date_expected)).' hrs',
									'{$DrawDate}'=>$this->adv_obj->dateFormat($roster_details->draw_date_expected),
									'{$OmcLogo}'=>'',
									'{$AdditionalNote}'=>''
								);
								
		
		
		/*if($roster_details->id == '2556'){
			$template_var_array['{$AdditionalNote}'] = '<b>"The draw and selection process is being carried out conditionally and the process of selection for the LPG distributorship shall be subject to final outcome of petitions i.e. CWP No.13992 of 2018 and CWP No.20074 of 2018 pending before Hon\'ble High Court of Punjab & Haryana at Chandigarh."</b>';
		}*/
		if($roster_details->oil_company==1){
			$template_var_array['{$OmcLogo}'] = '<img src="'.BASE_PATH.'assets/images/IOCL_Logo.png" alt="" height="75" />';
		}
		else if($roster_details->oil_company==2){
			$template_var_array['{$OmcLogo}'] = '<img src="'.BASE_PATH.'assets/images/BPCL_Logo.png" alt="" height="85" />';
		}
		else if($roster_details->oil_company==3){
			$template_var_array['{$OmcLogo}'] = '<img src="'.BASE_PATH.'assets/images/HPCL_Logo.png" alt="" height="85" />';
		}
		
		$this->pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$this->pdf->SetCreator(PDF_CREATOR);
		$this->pdf->SetAuthor('LPG Vitarak Chayan');
		$this->pdf->SetTitle('Draw Intimation Letter for Selection of LPG Distributor');
		$this->pdf->SetSubject('Draw Intimation Letter for Selection of LPG Distributor');
		$this->pdf->SetKeywords('LPG, Distributor, Draw Intimation Letter');
		$this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$this->pdf->setPrintHeader(false);
		
		$this->pdf->AddPage();
		
		$html = file_get_contents(BASE_PATH."uploads/draw-intemation-letters/".'Draw_Intimation_Template.html');
		$html = strtr($html, $template_var_array);
		//echo $html; exit;
		$html = utf8_encode($html);
		//echo $html; exit;
		$this->pdf->writeHTML($html);
		
		$filename = "Draw_Intimation_".$application_ref_no.".pdf";
		$filename = str_replace("/", "", $filename);
		
		if($dest=='F')
			$this->pdf->Output(BASE_PATH."uploads/draw-intemation-letters/".$filename, $dest); // $dest: F-File Save, I-Show, D-Download	
				
		else
			$this->pdf->Output($filename, $dest); // $dest: F-File Save, I-Show, D-Download	
		
		$this->pdf = NULL;
		return $filename;
	}
	
	public function generateSuspectApplicationLetterPDF($application_details, $dest='I')
	{
		$application_ref_no = $application_details->application_ref_no;
		
		if($application_details->oil_company==1){
			$OmcLogo = '<img src="'.BASE_PATH.'assets/images/IOCL_Logo.png" alt="" height="75" />';
		}
		else if($application_details->oil_company==2){
			$OmcLogo = '<img src="'.BASE_PATH.'assets/images/BPCL_Logo.png" alt="" height="85" />';
		}
		else if($application_details->oil_company==3){
			$OmcLogo = '<img src="'.BASE_PATH.'assets/images/HPCL_Logo.png" alt="" height="85" />';
		}
		
		$this->pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$this->pdf->SetCreator(PDF_CREATOR);
		$this->pdf->SetAuthor('LPG Vitarak Chayan');
		$this->pdf->SetTitle('Suspect Application Letter for Selection of LPG Distributor');
		$this->pdf->SetSubject('Suspect Application Letter for Selection of LPG Distributor');
		$this->pdf->SetKeywords('LPG, Distributor, Suspect Application Letter');
		$this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$this->pdf->setPrintHeader(false);
		
		$this->pdf->AddPage();
		
		$html = '<p style="text-align:right;">'.$OmcLogo.'</p>
				<p style="text-align:right">Ref. No. <strong>'.$application_ref_no.'</strong><br/>
				Date: <strong>'.date('d/m/Y').'</strong></p>
				
				<p>'.strtoupper($application_details->applicant_name).'<br>
				'.strtoupper($application_details->applicant_address1).'<br>
				'.strtoupper($application_details->applicant_address2).'<br>
				'.strtoupper($application_details->applicant_address3).'<br>
				'.strtoupper($application_details->applicant_district).',<br>
				'.strtoupper($application_details->applicant_state).'</p>
				
				<p style="text-align:justify;"><strong>Subject: Application for award of LPG Distributorship of '.$application_details->oil_comapny_name.' at '.$application_details->location_name.' District '.$application_details->district_name.' under '.$application_details->category_name.' category Advertised on '.date('d-m-Y', strtotime($application_details->advertisement_date)).'</strong></p>
				<p style="text-align:justify;">Type of LPG Distributorship: '.$application_details->type_details.'</p>
				<p style="text-align:justify;">Please refer to the brochure available in the website, www.lpgvitarakchayan.com wherein it is categorically mentioned that an applicant can submit only one application for one location.</p>
				<p style="text-align:justify;">From the perusal of the details it is observed that you have submitted more than one application for the subject location. Details of your application references are as under - </p>';
				
				$i=0;
				$html .= '<table border="1" cellpadding="5" cellspacing="0">
							<tr><th width="50">S.N.</th><th>Application Reference Number</th><th>Date of Application</th></tr>';
				$html .= '<tr><td width="50">'.++$i.'</td><td>'.$application_details->application_ref_no.'</td><td>'.$this->user_obj->dateTimeFormat($application_details->final_submitted_on).'</td></tr>';	
										  
				$suspect_applications = explode(",", $application_details->suspect_applications);
				$suspect_application_final_submitted = explode(",", $application_details->suspect_application_final_submitted);
				$suspect_application_data = array_combine($suspect_applications, $suspect_application_final_submitted);
				foreach($suspect_application_data as $application_no=>$final_submited){
					$html .= '<tr><td width="50">'.++$i.'</td><td>'.$application_no.'</td><td>'.$this->user_obj->dateTimeFormat($final_submited).'</td></tr>';
				}			  
				$html .= '</table>';
				
		$html .= '<p>In line with the guidelines, it may please be noted that only one application will be considered for the draw. However, if you are selected in the draw, all the above applications submitted will be clubbed and the information contained in your applications matching the eligibility criteria will be considered in the Field verification of credentials. The above is for your kind information please.</p>
				<p>Thanking you</p>
				<p>Yours Faithfully,<br />
				'.$application_details->manager_designation.'<br />
				'.$application_details->ao_name.'<br />
				'.$application_details->oil_company_full_name.'<br />
				Tel. No. '.$application_details->manager_contact.'</p>
				<p style="font-size:12px;">This email does not require a signature as it is a computer generated document.</p>';
		//echo $html; die;
		$this->pdf->writeHTML($html);
		
		$filename = "Suspect_Applications_".$application_ref_no.".pdf";
		$filename = str_replace("/", "", $filename);
		
		if($dest=='F')
			$this->pdf->Output(BASE_PATH."uploads/suspect_application_letters/".$filename, $dest); // $dest: F-File Save, I-Show, D-Download	
				
		else
			$this->pdf->Output($filename, $dest); // $dest: F-File Save, I-Show, D-Download	
		
		$this->pdf = NULL;
		return $filename;
	}

	public function generateReminderFVC_Fee_Doc_PDF($roster_details, $reminder_for, $dest='I')
	{
		$application_ref_no = $roster_details->application_ref_no;
		
		$application_details = $this->user_obj->getApplicationFormCompleteDetails($application_ref_no);
		
		$oil_company_result = $this->user_obj->db_obj->query("select * from tbl_master_oil_companies where id = " . $roster_details->oil_company );
		if ($this->user_obj->db_obj->numRows($oil_company_result) == 1) {
			$oil_comapny_deatail = $this->user_obj->db_obj->fetchResult($oil_company_result);
		}
		else{
			return false;
		}

		$distributorship_fees_result = $this->user_obj->db_obj->query("select * from tbl_master_application_fees where caste_cat_id = '" . $application_details[0]->location_caste_cat_id . "' and dist_type_id = '" . $roster_details->dist_type . "'");
		if ($this->user_obj->db_obj->numRows($distributorship_fees_result) == 1) {
			$distributorship_fees_deatail = $this->user_obj->db_obj->fetchResult($distributorship_fees_result);
		}
		else{
			return false;
		}
		
		$fvc_fee_deposit_query = $this->user_obj->db_obj->query("select security_fee_letter_date, fvc_fee_deposit_date, is_fvc_fee_deposited from tbl_selection_process where roster_id = '" . $roster_details->id . "' and winner_application_id = '" . $roster_details->draw_winner_application . "' and  is_process_active=1");
		$fvc_fee_deposit_date=$security_fee_letter_date='';
		if ($this->user_obj->db_obj->numRows($fvc_fee_deposit_query) == 1) {
			$fvc_fees_deposit_result = $this->user_obj->db_obj->fetchResult($fvc_fee_deposit_query);
			if($fvc_fees_deposit_result[0]->is_fvc_fee_deposited == '1')
			{
				$fvc_fee_deposit_date = $fvc_fees_deposit_result[0]->fvc_fee_deposit_date;
			}
			
			$security_fee_letter_date = $fvc_fees_deposit_result[0]->security_fee_letter_date;
		}
		
		$template_var_array = array('{$AppRefNo}'=>$application_details[0]->application_ref_no,
									'{$IssueDate}'=>date('d/m/Y'),
									'{$ApplicantName}'=>strtoupper($application_details[0]->applicant_first_name.' '.$application_details[0]->applicant_middle_name.' '.$application_details[0]->applicant_last_name),
									'{$ApplicantAddress}'=>strtoupper($application_details[0]->applicant_address1.',<br />'.$application_details[0]->applicant_address2.',<br />'.$application_details[0]->applicant_address3),
									'{$ApplicantDistrict}'=>strtoupper($application_details[0]->applicant_district),
									'{$ApplicantState}'=>strtoupper($application_details[0]->applicant_state.', Pincode - '.$application_details[0]->applicant_pin_code),
									'{$OMCName}'=>$application_details[0]->oil_comapny_name,
									'{$AdvertisedLocation}'=>$application_details[0]->location_name,
									'{$AdvertisedDistrict}'=>$application_details[0]->adv_district,
									'{$AdvertisedCategory}'=>$application_details[0]->adv_category,
									'{$AdvertisedDate}'=>$this->adv_obj->dateFormat($application_details[0]->advertisement_date),
									'{$AdvertisedDistributerType}'=>$application_details[0]->distributorship_type,
									'{$SecurityAmount}'=>$distributorship_fees_deatail[0]->security_amount,
									'{$SecurityAmountInWords}'=>ucwords($this->user_obj->getIndianCurrency($distributorship_fees_deatail[0]->security_amount)),
									'{$OMCFullName}'=>$oil_comapny_deatail[0]->oil_company_full_name,
									'{$PlaceOfAreaOffice}'=>$roster_details->location_for_dd,
									'{$AM_TM_RM_name}'=>$roster_details->manager_name,
									'{$Designation}'=>$roster_details->manager_designation,
									'{$OfficerContactNo}'=>$roster_details->manager_contact,
									'{$AreaOfficeName}'=>$roster_details->ro_name,
									'{$AreaOfficeAddress}'=>$roster_details->manager_address,
									'{$distributorship_fees}'=>$distributorship_fees_deatail[0]->distributorship_fees,
									'{$distributorship_feesInWords}'=>ucwords($this->user_obj->getIndianCurrency($distributorship_fees_deatail[0]->distributorship_fees)),
									'{$AdvertisedCategoryDocDetails}'=>$this->getDocumentList($application_details[0]->adv_category),
									'{$MobileNo}'=>$application_details[0]->applicant_mobile_no,
									'{$DrawTime}'=>date('H:i',strtotime($roster_details->draw_date_expected)).' hrs',
									'{$DrawDate}'=>$this->adv_obj->dateFormat($roster_details->draw_date_expected),
									'{$OmcLogo}'=>'',
									'{$SecurityDepositLetterSentDate}'=>$this->adv_obj->dateFormat($security_fee_letter_date),
									'{$FVCFeeDepositDate}'=>$this->adv_obj->dateFormat($fvc_fee_deposit_date)
								);
		
		if($roster_details->oil_company==1){
			$template_var_array['{$OmcLogo}'] = '<img src="'.BASE_PATH.'assets/images/IOCL_Logo.png" alt="" height="75" />';
		}
		else if($roster_details->oil_company==2){
			$template_var_array['{$OmcLogo}'] = '<img src="'.BASE_PATH.'assets/images/BPCL_Logo.png" alt="" height="85" />';
		}
		else if($roster_details->oil_company==3){
			$template_var_array['{$OmcLogo}'] = '<img src="'.BASE_PATH.'assets/images/HPCL_Logo.png" alt="" height="85" />';
		}
		
		$this->pdf = new CUSTOMPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$this->pdf->SetCreator(PDF_CREATOR);
		$this->pdf->SetAuthor('LPG Vitarak Chayan');
		$this->pdf->SetTitle('Reminder for Security Deposit for Selection of LPG Distributor');
		$this->pdf->SetSubject('Reminder for Security Deposit for Selection of LPG Distributor');
		$this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$this->pdf->setPrintHeader(false);
		$this->pdf->AddPage();
		
		if($reminder_for=='FeeDoc'){
			$html = file_get_contents(BASE_PATH."uploads/pdf_templates/".'Reminder_FVC_Fee_Template.html');
			$filename = "Reminder_FVC_Fee_".$application_ref_no.".pdf";
		}
		else{
			$html = file_get_contents(BASE_PATH."uploads/pdf_templates/".'Reminder_FVC_Doc_Template.html');
			$filename = "Reminder_FVC_Doc_".$application_ref_no.".pdf";
		}
		
		$html = strtr($html, $template_var_array);
		//echo $html; exit;
		$this->pdf->writeHTML($html);
		
		$filename = str_replace("/", "", $filename);
		
		if($dest=='F')
			$this->pdf->Output(BASE_PATH."uploads/Reminder_FVC/".$filename, $dest); // $dest: F-File Save, I-Show, D-Download	
		else
			$this->pdf->Output($filename, $dest); // $dest: F-File Save, I-Show, D-Download	
		
		$this->pdf = NULL;
		return $filename;
	}
	
	public function generateCancelSecurityLetterPDF($roster_details, $dest='I')
	{
		$application_ref_no = $roster_details->application_ref_no;
		
		$application_details = $this->user_obj->getApplicationFormCompleteDetails($application_ref_no);
		
		$oil_company_result = $this->user_obj->db_obj->query("select * from tbl_master_oil_companies where id = " . $roster_details->oil_company );
		if ($this->user_obj->db_obj->numRows($oil_company_result) == 1) {
			$oil_comapny_deatail = $this->user_obj->db_obj->fetchResult($oil_company_result);
		}
		else{
			return false;
		}

		$distributorship_fees_result = $this->user_obj->db_obj->query("select * from tbl_master_application_fees where caste_cat_id = '" . $application_details[0]->location_caste_cat_id . "' and dist_type_id = '" . $roster_details->dist_type . "'");
		if ($this->user_obj->db_obj->numRows($distributorship_fees_result) == 1) {
			$distributorship_fees_deatail = $this->user_obj->db_obj->fetchResult($distributorship_fees_result);
		}
		else{
			return false;
		}
		
		$fvc_fee_deposit_query = $this->user_obj->db_obj->query("select security_fee_letter_date, fvc_fee_deposit_date, is_fvc_fee_deposited from tbl_selection_process_archive where roster_id = '" . $roster_details->id . "' and winner_application_id = '" . $roster_details->winner_application_id . "' and  is_process_active=1");
		$fvc_fee_deposit_date=$security_fee_letter_date='';
		if ($this->user_obj->db_obj->numRows($fvc_fee_deposit_query) == 1) {
			$fvc_fees_deposit_result = $this->user_obj->db_obj->fetchResult($fvc_fee_deposit_query);
			if($fvc_fees_deposit_result[0]->is_fvc_fee_deposited == '1')
			{
				$fvc_fee_deposit_date = $fvc_fees_deposit_result[0]->fvc_fee_deposit_date;
			}
			
			$security_fee_letter_date = $fvc_fees_deposit_result[0]->security_fee_letter_date;
		}
		
		$template_var_array = array('{$AppRefNo}'=>$application_details[0]->application_ref_no,
									'{$IssueDate}'=>date('d/m/Y'),
									'{$ApplicantName}'=>strtoupper($application_details[0]->applicant_first_name.' '.$application_details[0]->applicant_middle_name.' '.$application_details[0]->applicant_last_name),
									'{$ApplicantAddress}'=>strtoupper($application_details[0]->applicant_address1.',<br />'.$application_details[0]->applicant_address2.',<br />'.$application_details[0]->applicant_address3),
									'{$ApplicantDistrict}'=>strtoupper($application_details[0]->applicant_district),
									'{$ApplicantState}'=>strtoupper($application_details[0]->applicant_state.', Pincode - '.$application_details[0]->applicant_pin_code),
									'{$OMCName}'=>$application_details[0]->oil_comapny_name,
									'{$AdvertisedLocation}'=>$application_details[0]->location_name,
									'{$AdvertisedDistrict}'=>$application_details[0]->adv_district,
									'{$AdvertisedCategory}'=>$application_details[0]->adv_category,
									'{$AdvertisedDate}'=>$this->adv_obj->dateFormat($application_details[0]->advertisement_date),
									'{$AdvertisedDistributerType}'=>$application_details[0]->distributorship_type,
									'{$SecurityAmount}'=>$distributorship_fees_deatail[0]->security_amount,
									'{$SecurityAmountInWords}'=>ucwords($this->user_obj->getIndianCurrency($distributorship_fees_deatail[0]->security_amount)),
									'{$OMCFullName}'=>$oil_comapny_deatail[0]->oil_company_full_name,
									'{$PlaceOfAreaOffice}'=>$roster_details->location_for_dd,
									'{$AM_TM_RM_name}'=>$roster_details->manager_name,
									'{$Designation}'=>$roster_details->manager_designation,
									'{$OfficerContactNo}'=>$roster_details->manager_contact,
									'{$AreaOfficeName}'=>$roster_details->ro_name,
									'{$AreaOfficeAddress}'=>$roster_details->manager_address,
									'{$distributorship_fees}'=>$distributorship_fees_deatail[0]->distributorship_fees,
									'{$distributorship_feesInWords}'=>ucwords($this->user_obj->getIndianCurrency($distributorship_fees_deatail[0]->distributorship_fees)),
									'{$AdvertisedCategoryDocDetails}'=>$this->getDocumentList($application_details[0]->adv_category),
									'{$MobileNo}'=>$application_details[0]->applicant_mobile_no,
									'{$DrawTime}'=>date('H:i',strtotime($roster_details->draw_date_expected)).' hrs',
									'{$DrawDate}'=>$this->adv_obj->dateFormat($roster_details->draw_date_expected),
									'{$OmcLogo}'=>'',
									'{$FVCFeeDepositDateArchive}'=>$this->adv_obj->dateFormat($security_fee_letter_date)
									
								);
		
		if($roster_details->oil_company==1){
			$template_var_array['{$OmcLogo}'] = '<img src="'.BASE_PATH.'assets/images/IOCL_Logo.png" alt="" height="75" />';
		}
		else if($roster_details->oil_company==2){
			$template_var_array['{$OmcLogo}'] = '<img src="'.BASE_PATH.'assets/images/BPCL_Logo.png" alt="" height="85" />';
		}
		else if($roster_details->oil_company==3){
			$template_var_array['{$OmcLogo}'] = '<img src="'.BASE_PATH.'assets/images/HPCL_Logo.png" alt="" height="85" />';
		}
		
		$this->pdf = new CUSTOMPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$this->pdf->SetCreator(PDF_CREATOR);
		$this->pdf->SetAuthor('LPG Vitarak Chayan');
		$this->pdf->SetTitle('Cancellation for Security Deposit for Selection of LPG Distributor');
		$this->pdf->SetSubject('Cancellation for Security Deposit for Selection of LPG Distributor');
		$this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$this->pdf->setPrintHeader(false);
		$this->pdf->AddPage();
		
		$html = file_get_contents(BASE_PATH."uploads/pdf_templates/".'Cancel_Security_Deposit_Template.html');
		$filename = "Cancel_Security_Deposit_".$application_ref_no.".pdf";
		
		
		$html = strtr($html, $template_var_array);
		//echo $html; exit;
		$this->pdf->writeHTML($html);
		
		$filename = str_replace("/", "", $filename);
		
		if($dest=='F')
			$this->pdf->Output(BASE_PATH."uploads/Cancel_Security_Deposit/".$filename, $dest); // $dest: F-File Save, I-Show, D-Download	
		else
			$this->pdf->Output($filename, $dest); // $dest: F-File Save, I-Show, D-Download	
		
		$this->pdf = NULL;
		return $filename;
	}
	
	private function getDocumentList($category)
	{
		$AdvertisedCategoryDocDetails = array(
			'OPEN'=>'
					<li>Self-attested copy of photo ID as proof of identity, issued by any Government authority. The self-attested copy will be verified with the original.</li>
					<li>Declaration by the applicant as per Appendix-1 <strong>(in original)</strong></li>
					<li>Proof of date of birth i.e. - School Leaving Certificate / Birth Certificate / Passport / PAN Card. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Proof of educational qualification i.e. - Copy of Certificate of passing X Std. or equivalent. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Declaration as per format given in Appendix -2 / Undertaking as per format given in Appendix -4 whichever is applicable. <strong>(in original)</strong></li>
					<li>Land documents: Documents pertaining to land/godown/ showroom in the name of applicant or member of &lsquo;family unit&rsquo; Registered Sale Deed/Gift Deed/Lease Deed (15yrs minimum)/ Mutation and Government record.<strong> (self-attested photocopy of the original)</strong></li>', 
			'SC'=>'
					<li>Self-attested copy of photo ID as proof of identity, issued by any Government authority. The self-attested copy will be verified with the original.</li>
					<li>Declaration by the applicant as per Appendix-1 <strong>(in original)</strong></li>
					<li>Proof of date of birth i.e. - School Leaving Certificate / Birth Certificate / Passport / PAN Card. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Proof of educational qualification i.e. - Copy of Certificate of passing X Std. or equivalent. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Declaration as per format given in Appendix -2 / Undertaking as per format given in Appendix -4 whichever is applicable. <strong>(in original)</strong></li>
					<li>Land documents: Documents pertaining to land/godown/ showroom in the name of applicant or member of &lsquo;family unit&rsquo; Registered Sale Deed/Gift Deed/Lease Deed (15yrs minimum)/ Mutation and Government record.<strong> (self-attested photocopy of the original)</strong></li>
					<li>Eligibility certificate from competent authority as per Appendix- 3a <strong>(self-attested photocopy of the original)</strong></li>', 
			'OBC'=>'
					<li>Self-attested copy of photo ID as proof of identity, issued by any Government authority. The self-attested copy will be verified with the original.</li>
					<li>Declaration by the applicant as per Appendix-1 <strong>(in original)</strong></li>
					<li>Proof of date of birth i.e. - School Leaving Certificate / Birth Certificate / Passport / PAN Card. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Proof of educational qualification i.e. - Copy of Certificate of passing X Std. or equivalent. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Declaration as per format given in Appendix -2 / Undertaking as per format given in Appendix -4 whichever is applicable. <strong>(in original)</strong></li>
					<li>Land documents: Documents pertaining to land/godown/ showroom in the name of applicant or member of &lsquo;family unit&rsquo; Registered Sale Deed/Gift Deed/Lease Deed (15yrs minimum)/ Mutation and Government record.<strong> (self-attested photocopy of the original)</strong></li>
					<li>Eligibility certificate from competent authority as per Appendix -3b / Declaration / Undertaking as per Appendix- 3c for confirmation of Non-Creamy Layer.<strong> (original)</strong></li>',
			'SC(GP)'=>'
					<li>Self-attested copy of photo ID as proof of identity, issued by any Government authority. The self-attested copy will be verified with the original.</li>
					<li>Declaration by the applicant as per Appendix-1 <strong>(in original)</strong></li>
					<li>Proof of date of birth i.e. - School Leaving Certificate / Birth Certificate / Passport / PAN Card. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Proof of educational qualification i.e. - Copy of Certificate of passing X Std. or equivalent. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Declaration as per format given in Appendix -2 / Undertaking as per format given in Appendix -4 whichever is applicable. <strong>(in original)</strong></li>
					<li>Land documents: Documents pertaining to land/godown/ showroom in the name of applicant or member of &lsquo;family unit&rsquo; Registered Sale Deed/Gift Deed/Lease Deed (15yrs minimum)/ Mutation and Government record.<strong> (self-attested photocopy of the original)</strong></li>
					<li>Eligibility certificate from competent authority as per Appendix- 3a <strong>(self-attested photocopy of the original)</strong></li>
					<li>If applying under Defence Personnel, to submit s<strong>elf attested copy of the Eligibility Certificate</strong> issued from the Directorate General of Resettlement (DGR), Ministry of Defence, sponsoring the candidate for the LPG Distributorship location for which he/she has applied.</li>
					<li>If applying under Central Para Military Forces/Special Forces Or Government Personnel &amp; Central/State Government Public Sector Undertakings, submit <strong>(self-attested photocopy of the original Eligibility Certificate</strong> (format as per Appendix -3e)</li>',
			'OBC(GP)'=>'
					<li>Self-attested copy of photo ID as proof of identity, issued by any Government authority. The self-attested copy will be verified with the original.</li>
					<li>Declaration by the applicant as per Appendix-1 <strong>(in original)</strong></li>
					<li>Proof of date of birth i.e. - School Leaving Certificate / Birth Certificate / Passport / PAN Card. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Proof of educational qualification i.e. - Copy of Certificate of passing X Std. or equivalent. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Declaration as per format given in Appendix -2 / Undertaking as per format given in Appendix -4 whichever is applicable. <strong>(in original)</strong></li>
					<li>Land documents: Documents pertaining to land/godown/ showroom in the name of applicant or member of &lsquo;family unit&rsquo; Registered Sale Deed/Gift Deed/Lease Deed (15yrs minimum)/ Mutation and Government record.<strong> (self-attested photocopy of the original)</strong></li>
					<li>Eligibility certificate from competent authority as per Appendix -3b / Declaration / Undertaking as per Appendix- 3c for confirmation of Non-Creamy Layer.<strong> (original)</strong>&nbsp;</li>
					<li>If applying under Defence Personnel, to submit self attested copy of the Eligibility Certificate issued from the Directorate General of Resettlement (DGR), Ministry of Defence, sponsoring the candidate for the LPG Distributorship location for which he/she has applied.<strong> (self-attested photocopy of the original)</strong></li>
					<li>If applying under Central Para Military Forces/Special Forces Or Government Personnel &amp; Central/State Government Public Sector Undertakings, submit Eligibility Certificate as per Appendix -3e <strong>(original)</strong></li>',
			'OPEN(GP)'=>'
					<li>Self-attested copy of photo ID as proof of identity, issued by any Government authority. The self-attested copy will be verified with the original.</li>
					<li>Declaration by the applicant as per Appendix-1 <strong>(in original)</strong></li>
					<li>Proof of date of birth i.e. - School Leaving Certificate / Birth Certificate / Passport / PAN Card. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Proof of educational qualification i.e. - Copy of Certificate of passing X Std. or equivalent. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Declaration as per format given in Appendix -2 / Undertaking as per format given in Appendix -4 whichever is applicable. <strong>(in original)</strong></li>
					<li>Land documents: Documents pertaining to land/godown/ showroom in the name of applicant or member of &lsquo;family unit&rsquo; Registered Sale Deed/Gift Deed/Lease Deed (15yrs minimum)/ Mutation and Government record.<strong> (self-attested photocopy of the original)</strong></li>
					<li>If applying under Defence Personnel, to submit self attested copy of the Eligibility Certificate issued from the Directorate General of Resettlement (DGR), Ministry of Defence, sponsoring the candidate for the LPG Distributorship location for which he/she has applied.</li>
					<li>If applying under Central Para Military Forces/Special Forces Or Government Personnel &amp; Central/State Government Public Sector Undertakings, submit Eligibility Certificate as per Appendix -3e <strong>(original)</strong>&nbsp;</li>',
			'SC(PH)'=>'
					<li>Self-attested copy of photo ID as proof of identity, issued by any Government authority. The self-attested copy will be verified with the original.</li>
					<li>Declaration by the applicant as per Appendix-1 <strong>(in original)</strong></li>
					<li>Proof of date of birth i.e. - School Leaving Certificate / Birth Certificate / Passport / PAN Card. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Proof of educational qualification i.e. - Copy of Certificate of passing X Std. or equivalent. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Declaration as per format given in Appendix -2 / Undertaking as per format given in Appendix -4 whichever is applicable. <strong>(in original)</strong></li>
					<li>Land documents: Documents pertaining to land/godown/ showroom in the name of applicant or member of &lsquo;family unit&rsquo; Registered Sale Deed/Gift Deed/Lease Deed (15yrs minimum)/ Mutation and Government record.<strong> (self-attested photocopy of the original)</strong></li>
					<li>Eligibility certificate from competent authority as per Appendix- 3a <strong>(self-attested photocopy of the original)</strong></li>
					<li>Eligibility certificate from competent authority as per Appendix -3b / Declaration / Undertaking as per Appendix- 3c for confirmation of Non-Creamy Layer.</li>
					<li>Disability Certificate as per Appendix-3d.( Form II, Form III, Form IV as applicable). <strong>(original)</strong></li>',
			'OBC(PH)'=>'
					<li>Self-attested copy of photo ID as proof of identity, issued by any Government authority. The self-attested copy will be verified with the original.</li>
					<li>Declaration by the applicant as per Appendix-1 <strong>(in original)</strong></li>
					<li>Proof of date of birth i.e. - School Leaving Certificate / Birth Certificate / Passport / PAN Card. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Proof of educational qualification i.e. - Copy of Certificate of passing X Std. or equivalent. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Declaration as per format given in Appendix -2 / Undertaking as per format given in Appendix -4 whichever is applicable. <strong>(in original)</strong></li>
					<li>Land documents: Documents pertaining to land/godown/ showroom in the name of applicant or member of &lsquo;family unit&rsquo; Registered Sale Deed/Gift Deed/Lease Deed (15yrs minimum)/ Mutation and Government record.<strong> (self-attested photocopy of the original)</strong></li>
					<li>Eligibility certificate from competent authority as per Appendix- 3a <strong>(self-attested photocopy of the original)</strong></li>
					<li>Eligibility certificate from competent authority as per Appendix -3b / Declaration / Undertaking as per Appendix- 3c for confirmation of Non-Creamy Layer.</li>
					<li>Disability Certificate as per Appendix-3d.( Form II, Form III, Form IV as applicable). <strong>(original)</strong></li>',
			'OPEN(PH)'=>'
					<li>Self-attested copy of photo ID as proof of identity, issued by any Government authority. The self-attested copy will be verified with the original.</li>
					<li>Declaration by the applicant as per Appendix-1 <strong>(in original)</strong></li>
					<li>Proof of date of birth i.e. - School Leaving Certificate / Birth Certificate / Passport / PAN Card. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Proof of educational qualification i.e. - Copy of Certificate of passing X Std. or equivalent. <strong>(self-attested photocopy of the original)</strong></li>
					<li>Declaration as per format given in Appendix -2 / Undertaking as per format given in Appendix -4 whichever is applicable. <strong>(in original)</strong></li>
					<li>Land documents: Documents pertaining to land/godown/ showroom in the name of applicant or member of &lsquo;family unit&rsquo; Registered Sale Deed/Gift Deed/Lease Deed (15yrs minimum)/ Mutation and Government record.<strong> (self-attested photocopy of the original)</strong></li>
					<li>Disability Certificate as per Appendix-3d.( Form II, Form III, Form IV as applicable). <strong>(original)</strong>&nbsp;</li>',
			'OPEN(CC)'=>'
					<li>Self-attested copy of photo ID as proof of identity, issued by any Government authority. The self-attested copy will be verified with the original.</li>
					<li>Declaration by the applicant as per Appendix-1<strong>(original)</strong></li>
					<li>Proof of date of birth ie- School Leaving Certificate / Birth Certificate / Passport / PAN Card<strong>(self attested photocopy)</strong></li>
					<li>Proof of educational qualification i.e.- Copy of Certificate of passing X Std. or equivalent <strong>(self attested photocopy)</strong></li>
					<li>Declaration as per Appendix -2 / Undertaking as per Appendix -4 if applicable. <strong>(original)</strong></li>
					<li>Land documents: Documents pertaining to land/godown/ showroom in the name of applicant or member of &lsquo;family unit&rsquo; Registered Sale Deed/Gift Deed/Lease Deed (15yrs minimum)/ Mutation and Government record. <strong>(self attested photocopy)</strong></li>
					<li>If applying as OSP (Outstanding Sports Person), to produce a certificate from the Recognized National Federation Organizing National Championships (as recognized by Department of Youth Affairs and Sports, Govt. of India) or from the Dept of Youth Affairs and Sports, Govt. of India.<strong> (original)</strong></li>
					<li>If applying under FF (Freedom Fighter) category should attach a certificate or Tamrapatra or an attested copy of the Pension Order issued by the Accountant General in pursuance of the sanction letter from the Ministry of Home Affairs, Govt. of India of their having been Freedom Fighters.</li>'
			);
		$AdvertisedCategoryDocDetails['ST(GP)']=$AdvertisedCategoryDocDetails['SC(GP)'];
		$AdvertisedCategoryDocDetails['ST(PH)']=$AdvertisedCategoryDocDetails['SC(PH)'];

		$AdvertisedCategoryDocDetails['OPEN(W)']=$AdvertisedCategoryDocDetails['OPEN'];
		$AdvertisedCategoryDocDetails['OBC(W)']=$AdvertisedCategoryDocDetails['OBC'];
		$AdvertisedCategoryDocDetails['ST']=$AdvertisedCategoryDocDetails['ST(W)']=$AdvertisedCategoryDocDetails['SC(W)']=$AdvertisedCategoryDocDetails['SC'];
		return $AdvertisedCategoryDocDetails[$category];
	}
	
}
?>