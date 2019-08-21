<?php
include_once "classes/class.advertisement.php";
include_once "classes/class.user.php";
$adv_obj = new Advertisement();
$user_obj = new User();

include "includes/header.php";
?>
<style type="text/css">
.app_status { background:#daefff; padding:7px; line-height:18px; font-size:13px; margin-top:7px;}
.statustxt { width:auto; display:inline-block; margin-right:5px; color:#23527c;}
.statusmsg {width:80%; display:inline-block;}
</style>
<div class="bodycontent">
  <div class="container bxshadow">
    <div class="headerformtitle">
      <h1>My Applications</h1>
    </div>
    <div class="breadcrumb-li" style="border-bottom:1px solid #ccc; width:100%;"><a href="advertisement-list.php">Home</a>/<span>My Applications</span></div>
    <div class="clear" style="height:5px;"></div>
    <?php
        echo $adv_obj->getErrorMessages();
		echo $adv_obj->getSessionErrorMessages();
        echo $adv_obj->getSuccessMessages();
		echo $adv_obj->getInfoMessages();
        $adv_obj->unsetSessionMessages();
        ?>
    <div class="dashboardcontent" style="min-height:330px;">
      <div class="borderline"></div>
      <?php
		$saved_application_data = $adv_obj->getAllApplications($_SESSION['user_session']['user_id']);
		if ($saved_application_data) {
			foreach ($saved_application_data as $application) {
				//echo '<pre>';
				//print_r($application);
			?>
      <div class="col-sm-6 col-xs-12 col-md-4" style="margin-bottom:15px;">
        <div class="advertismentbox">
          <h3>
		  <?php 
		  echo '<a href="application-for-lpg-distributorship.php?app_id='.$application->application_id.'">'?><?php echo $application->advertisement_title."</a>"; 
		  ?>
          </h3>
          <ul>
            <li><span>Location </span>: <?php echo $application->location_name; ?> </li>
            <li><span>Last Updated</span> : <?php echo $adv_obj->dateTimeFormat($application->last_updated); ?> </li>
            <li><span>Reference No</span> : <?php echo $application->application_ref_no; ?> </li>
            <li>
			<?php
			if($application->application_final_status==0){
				if($application->is_final_submit==1 && $application->payment_done==1){
					echo "<span class='greentext'>Payment Successfull.</span>"; 
					echo '<br /><a href="application-pdf.php?id='.$application->application_id.'">Download Application PDF</a>';
				}
				else{
					if(strtotime($application->last_date) < strtotime(date("Y-m-d H:i:s"))){
						echo "<span class='redtext'>Last Date/Time is Over!</span>";	
					}
					else if($application->is_final_submit==1 && $application->payment_done==0){
						echo "<span class='redtext'>Status: Pending for Payment.</span>";
						echo '<br /><a href="proceed-payment.php?id='.$application->application_id.'"><img src="assets/images/btn-payonline.jpg" alt="#" width="150" /></a>';
					}
					else if($application->is_final_submit==1 && $application->payment_done==2){
						echo "<span class='redtext'>Status: Pending for Full Payment.</span>";
						echo '<br /><a href="proceed-payment.php?id='.$application->application_id.'&pp=true"><img src="assets/images/btn-payonline.jpg" alt="#" width="150" /></a>';
					}
					else{
						echo "<span class='redtext'>Status: Pending for Final Submission.</span>";
					}
				}
			}
			else{
				echo '<a href="application-pdf.php?id='.$application->application_id.'">Download Application PDF</a><br />';
				echo '<div class="app_status">';
				echo '<div class="statustxt"><b>Status :</b></div><div class="statusmsg"> '.$application->application_final_status_text.'</div>';	
				echo '</div>';
			}
            ?>
            </li>
          </ul>
        </div>
      </div>
      		<?php 
	  		}
		}
		else{
			echo "No Applications!";
		}
		?>
      <div class="clear height40"></div>
    </div>
  </div>
</div>
<?php
include "includes/footer.php";
?>