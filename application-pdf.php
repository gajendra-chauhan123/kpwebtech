<?php
ob_end_clean();
include_once "classes/class.pdf-generate.php";
include_once "classes/class.user.php";
$pdf_obj = new MYPDF();
$user_obj = new User();

$app_id = $_REQUEST['id'];

if (!$user_obj->isRefererOk() || empty($app_id)) {
    $user_obj->redirect("my-applications.php");
}

$application_details = $user_obj->getApplicationFormDetails(array('id'=>$app_id, 'payment_done'=>1, 'applicant_id'=>$_SESSION['user_session']['user_id']));

if($application_details){
	if(!$pdf_obj->generateApplicationFormPDF($application_details[0]->application_ref_no, 'D'))
		$user_obj->redirect("my-applications.php");
}
else
	$user_obj->redirect("my-applications.php");
?>