<?php
include_once("includes/header.php");
include '../phpmailer/sendmail.php';

$self = "distributors_bank_email.php";
 //File Name
// Comment code by Manish Arora. Suggest by: Manish Sarthak. Purpose: No Requirement of below code because access by Location incharge only. Date: 06-July-2016.
/*if($user['isp_type'] != 'SO'){
	echo '<script>window.location.href="https://indane.co.in"</script>';
}*/


function randomDigits($length)
{
    $numbers = range(0,9);
    shuffle($numbers);
    for($i = 0;$i < $length;$i++)
       $digits .= $numbers[$i];
    return $digits;
}


if(isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST')){
    if(isset($_POST['sender_id']) && !empty($_POST['sender_id'])){
        $getDealer = mysql_query("select dealer_username, dealer_bank_password, dealer_name, dealer_code, dealer_email from gb_company_dealer where id = '".  mysql_real_escape_string($_POST['sender_id'])."'");
        
		
		if($getDealer){
			
			
            if(mysql_num_rows($getDealer) > 0){           
                    while ($row = mysql_fetch_assoc($getDealer))
						{  
                        $dealer_bank_password  = $row['dealer_bank_password'];	 			
                       
                        if($dealer_bank_password == '')
						{
							$dealerpass = randomDigits(8);
							mysql_query("update gb_company_dealer set dealer_bank_password = '".mysql_real_escape_string($dealerpass)."' where 
							id = '".mysql_real_escape_string($_POST['sender_id'])."'");
                        }
						else
						{
							
							  $dealerpass  =  $row['dealer_bank_password'];	
							
						}
                        
						
   
                       
						
						
						$to = $row['dealer_email'];
                        $subject = "Indane - Submission of Bank Details for Transferring Online Payment of Online Services";
                        $message = '<html><head>
                                                <style>
                                                        body{
                                                                font-family:arial;
                                                                font-size:13px;
                                                        }
                                                        a{
                                                                color:#D1581D;
                                                        }
                                                </style>
                                                </head>
                                                <body style="margin:0px: padding:0px;" bgcolor="#E7E8E2" topmargin="0" bottommargin="0">
                                                <table width="700" cellpadding="0" cellspacing="0" border="0" style="border: solid 5px #ffffff; font-family:arial; color:#000033;" align="center" bgcolor="#FBF5E4">
                                                        <tr>
                                                                <td align="center" valign="top">
                                                                        <img src="' . SERVER_URL . 'images/indanemaillogo.jpg" />
                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <td style="padding-left:20px; padding-right:20px;" >
                                                                        <table width="100%" style="margin-top:20px;" align="center">
                                                                                <tr>
                                                                                        <td style="height:20px;">&nbsp;</td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td align="left">M/s ' . $row['dealer_name'] . ' [' . $row['dealer_code'] . '],</td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td style="height:20px;">&nbsp;</td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td align="left">Online payment facility for new connection is launched for the customers of Delhi on 01.05.15. As per the MoP&NG advise this facility is planned to be extended in your state by 01.07.15.</td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td style="height:20px;">&nbsp;</td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td align="left">For this purpose your bank a/c details will be required by the payment gateway for transferring of funds receive for new connection from the customers.</td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td style="height:20px;">&nbsp;</td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td align="left">So please enter bank a/c details using the link provided by your Area Office & by using following login credentials.</td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td style="height:20px;">&nbsp;</td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td align="left">
                                                                                                Username:   ' . $row['dealer_username'] . '
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td align="left">
                                                                                                Password:   ' . $dealerpass. '
                                                                                        </td>
                                                                                </tr>
                                                                                <tr><td style="height:20px;">&nbsp;</td></tr>
                                                                                <tr>
                                                                                        <td>After login from the link given by area office, a dashboard will be displayed. Then Go to Bank Details Menu available under Distributor Menu and Click. After this a form will appear which has to be filled and submitted.</td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td style="height:20px;">&nbsp;</td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td align="left">Kindly ensure that correct bank details are entered by you as these will be captured for fund transfer. And do not share this email with anyone.</td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td style="height:20px;">&nbsp;</td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td align="left">With Regards,</td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td align="left">Team Indane</td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td style="height:20px;">&nbsp;</td>
                                                                                </tr>
                                                                        </table>
                                                                </td>
                                                        </tr>
                                                </table>
                                                </body>
                                                </html>';
                        $cc = '';
                        $bcc = 'gajendra.singh@cyfuture.com'; 
                        if(mailsendForBankseed($message, $subject,$to, $cc, $bcc))
						{
						include '../saveemail.php';
                        $_SESSION['successmsg'] = "Mail sent successfully to " . $to . " [" . $row['dealer_code'] . " ]";     
                        echo "<script type=\"text/javascript\">window.location = 'distributors_bank_email.php';</script>"; 
                        exit;
						}
						else{
						include '../saveemail.php';
                        $_SESSION['successmsg'] = "Mail not sent  to " . $to . " [" . $row['dealer_code'] . " ]. Please try again";     
                        echo "<script type=\"text/javascript\">window.location = 'distributors_bank_email.php';</script>"; 
                        exit;
							
						}
						
						
                    }
            }
            else{
                $_SESSION['errormsg'] = 'Distributor is not active.';
            }
        }

		
		
    }
}


if (!isset($_GET['start']))
    $start = 1;
else
    $start = $_GET['start'];

if(!isset($_REQUEST['dealerCode']))
	$dealerCode = "";
else
	$dealerCode = trim($_REQUEST['dealerCode']);

if (!isset($_REQUEST['search']))
    $search = "";
else
    $search = trim($_REQUEST['search']);

if (!isset($_REQUEST['alpha'])) 
    $alpha = "";
else
    $alpha = $_REQUEST['alpha']; 

$sqltype = "select * from gb_lsp where id=" . $_SESSION["lspid"];
$restype = mysql_query($sqltype);
$rowType = mysql_fetch_array($restype);
$lsp_type = $rowType["isp_type"];

$str = "";

if (isset($_REQUEST["state_office"]) && $_REQUEST["state_office"] != '') {
    $str.=" and gb_company_dealer.so_id = '" . $_REQUEST["state_office"] . "'";
}
if (isset($_REQUEST["area_office"]) && $_REQUEST["area_office"] != '') {
    $str.=" and gb_company_dealer.ao_id = '" . $_REQUEST["area_office"] . "'";
}
if (isset($_REQUEST["sale_zone"]) && $_REQUEST["sale_zone"] != '') {
    $str.=" and gb_company_dealer.sz_id = '" . $_REQUEST["sale_zone"] . "'";
}

if ($str == '') {
    if ($isp_type == 'SO') {
        $str .= " and gb_company_dealer.so_id = '" . $area_id . "'";
    }
    if ($isp_type == 'AO') {
        $str .= " and gb_company_dealer.ao_id = '" . $area_id . "'";
    }
    if ($isp_type == 'SZ') {
        $str .= " and gb_company_dealer.sz_id = '" . $area_id . "'";
    }
}

if($dealerCode!=''){
	 $str .= " and dealer_code = '" . $dealerCode . "' ";	
}

if ($search != "")
    $str .= " and dealer_name like '" . $search . "%' ";

else if ($alpha != "")
    $str .= " and dealer_name like '" . $alpha . "%' ";

if (isset($_REQUEST["showall"])) {
    if ($_REQUEST["showall"] == 1)
        $str = "";
}

$limit = 10;             // No of records to be shown per page.
$current = ($start - 1) * $limit; //record offset

$cities_array = get_inner_cities();

$cities = implode(",", $cities_array);

$activate_str = '';


if (isset($_REQUEST["map_dealer"]) && $_REQUEST["map_dealer"] == 1) {
    $str.=" and point_lat!=''";
}
else if (isset($_REQUEST["map_dealer"]) && ($_REQUEST["map_dealer"] == 2 || $_REQUEST["map_dealer"] == '')) {
    $str.=" ";
}
else if (isset($_REQUEST["map_dealer"]) && $_REQUEST["map_dealer"] == 0) {
    $str.=" and (point_lat='' OR point_lat IS NULL )";
}

$query1 = "select * from gb_company_dealer where dealer_status=1 and company_id = '1' and dist_bank_status = '0' " .$str;

$result1 = mysql_query($query1) or die(); 

$nume = mysql_num_rows($result1);    //Max no of rows
$maxpage = ceil($nume / $limit);         //No of pages

function getSalesZoneName($sz_id){
	$sqlSZ = "select office_name, sz_code, status from gb_offices where id='".$sz_id."'";
	$qrySZ = mysql_query($sqlSZ);
	if(mysql_num_rows($qrySZ)>0)
	{
		$rowSZ = mysql_fetch_assoc($qrySZ);
		if($rowSZ['status']==1){
			$officeName = $rowSZ['office_name'];
			if($rowSZ['sz_code']!='0'){
				$officeName .= " (".$rowSZ['sz_code'].")";	
			}
			return $officeName;
		}else{
			return "SZ not active";
		}
	}
	else 
	{
		return NULL;	
	}
}
?>
<script language="javascript" type="text/javascript">
    function checknum(e)
    {
        evt = e || window.event;
        var keypressed = evt.which || evt.keyCode;
        if (keypressed != "48" && keypressed != "49" && keypressed != "50" && keypressed != "51" && keypressed != "52" && keypressed != "53" && keypressed != "54" && keypressed != "55" && keypressed != "8" && keypressed != "56" && keypressed != "57" && keypressed != "45")
        {
            return false;
        }
    }
    function formsubmit(val)

    {

        document.frmview.action = "<?=$self?>?order=" + val;

        document.frmview.submit();

    }
    function checkchar(e)
    {
        evt = e || window.event;
        var keypressed = evt.which || evt.keyCode;
        for (var i = 65; i < 91; i++)
        {
            if (keypressed == i)
            {
                return true;
            }
        }
        for (var j = 97; j < 123; j++)
        {
            if (keypressed == j)
            {
                return true;
            }
        }
        if (keypressed == 8)
        {
            return true;
        }
        if (keypressed == 32)
        {
            return true;
        }
        return false;
    }

    function check()
    {
        var flagcheck = 0;
        var i = 1;
        while (i < document.frmview.counter.value)
        {
            if (document.getElementById("chk" + i).checked)
            {
                flagcheck = 1;
            }
            i++;
        }
        if (flagcheck == 1)
        {
            if (confirm("Are you sure you want to delete selected item(s)"))
            {
                document.frmview.mode.value = "delete";
                //document.frmview.submit;
                return true;
            }
            return false;
        }
        else
        {
            alert("Please select at least one record");
            return false;
        }
    }

    function searched()
    {
    }
	
    function gotopage(path)
    {
<?
$search = $search . "&state_office=" . $_REQUEST["state_office"] . "&area_office=" . $_REQUEST["area_office"] . "&sale_zone=" . $_REQUEST["sale_zone"] . "&map_dealer=" . $_REQUEST["map_dealer"];
?>
        window.location = path + '?search=<?= $search ?>&alpha=<?= $alpha ?>&bgacity=<?= $_REQUEST["bgacity"] ?>&office=<?= $_REQUEST["office"] ?>&activate=<?= $_REQUEST["activate"] ?>&start=' + document.frmview.pagenumbers.value;
    }
    function toggleselect()
    {
        var i = 1;
        if (document.frmview.chkselectall.checked)
        {
            while (i < document.frmview.counter.value)
            {
                document.getElementById("chk" + i).checked = true;
                i++;
            }
        }
        else
        {
            while (i < document.frmview.counter.value)
            {
                document.getElementById("chk" + i).checked = false;
                i++;
            }
        }
    }
    
    
    /**
 * Function for send bank login credentials to dealer email
 */
function sendmailtodistributor(dealerid) {
    if(dealerid !== '' && dealerid !== 0){
        var sure = confirm('Are you sure, you want to send bank admin login credentials email to distributor.');
        if(sure){
            document.getElementById('sender_id').value = dealerid;
            document.mailsend.submit();
        }
        else{
            return false;
        }
    }
}

function get_sub_offices(type, v)
{
    if (type == "SZ")
        return false;
    url = "get_sub_offices.php?type=" + type + "&id=" + v;
    div_id = "area_td";
    if (type == 'AO')
        div_id = "sale_td";
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

function loadpage(page_request, containerid)
{
    if (page_request.readyState == 4 && (page_request.status == 200 || window.location.href.indexOf("http") == -1))
    {
        document.getElementById(containerid).innerHTML = page_request.responseText;
    }
}
  


</script>
<style>
.tablecontainer tr td {
	padding-left: 0px;
	padding-right: 0px;
}
</style>

<div class="innerheadercontainer2"> 
  <!--Bread Crame Start here-->
  <div class="borderbottom"></div>
  
  <!--Bread Crame End here-->
  <div class="midcontainer innerheadermain">
    <div class="innerfortunelogo3"><img src="<?= SERVER_URL ?>images/fortunelogo.png"  alt="Fortune Logo" /></div>
    <div class="innerheaderleft">
      <h1>Send Distributors Bank Password Details</h1> 
    </div>
  </div>
</div>
<div class="clear" style="height:20px;"></div>
<div class="innnerpagecontainer">
  <div class="midcontainer">
    <div class="innerpagecontent" style="padding:20px;">
        <div><form action="" name="mailsend" method="post"><input type="hidden" name="sender_id" id="sender_id" /></form></div>
        <form id="frmview" name="frmview" method="get" action="" class="formcontainer"  >
          <table cellpadding="0" cellspacing="0" width="100%"  >
            <?php if (isset($_SESSION['successmsg']) || isset($_SESSION['errormsg'])) { ?>
              <tr>
                <td align="center"><strong>
                  <?php
					if (isset($_SESSION['successmsg'])) {
						echo '<span style="color:#00af43">'.$_SESSION['successmsg'].'</span>';
                                                unset($_SESSION['successmsg']);
					}
					else if (isset($_SESSION['errormsg'])) {
						echo '<span style="color:#ff0000">'.$_SESSION['errormsg'].'</span>';
                                                unset($_SESSION['errormsg']);
					}
					?>
                  </strong></td>
              </tr>
              <?php } ?>
            <tr>
              <td align="left"><table >
              	  <tr>
                    <td>Dealer Code</td>
                    <td><input name="dealerCode"  type="text" id="dealerCode" value="<?= @$_REQUEST["dealerCode"] ?>" style="width:230px;" class="textbox" /></td>
                  </tr>
                  
                  <tr>
                    <td>Search by Name</td>
                    <td><input name="search"  type="text" id="search" value="<?= @$_REQUEST["search"] ?>" style="width:230px;" class="textbox" />
                      <input name="showall" type="hidden" id="showall" value="0" /></td>
                  </tr>
                   <?
				if ($isp_type == 'HO' || $isp_type == 'SO') 
				{
				?>
                  <tr>
                    <td>Area Office</td>
                    <td id="area_td"><div class="selectbox" style="width:250px;">
                        <select name="area_office" style="width:250px;" id="area_office" onChange="get_sub_offices('AO', this.value);"  class="textbox" >
                          <option value="">--All--</option>
                          <?
							if ((isset($_REQUEST["area_office"]) && $_REQUEST["area_office"] != '') || (isset($_REQUEST["state_office"]) && $_REQUEST["state_office"] != '') || $isp_type == "SO") {
								if (isset($_REQUEST["state_office"]) && $_REQUEST["state_office"] != '')
									$area_id = $_REQUEST["state_office"];
	
								$sql = "select * from gb_office_relation where id1=" . $area_id;
								$res = mysql_query($sql);
								$temp_array = array("0");
								while ($row = mysql_fetch_array($res)) {
									$temp_array[] = $row["id2"];
								}
	
								$temp_string = implode(",", $temp_array);
	
								$sql = "select id, office_name from gb_offices where status=1 and office_type='AO' and id in (" . $temp_string . ")  order by office_name";
								$res = mysql_query($sql);
								while ($row = mysql_fetch_array($res)) {
									$sel = '';
									if ($_REQUEST["area_office"] == $row["id"])
										$sel = 'selected="selected"';
									echo '<option value="' . $row["id"] . '" ' . $sel . '>' . $row["office_name"] . '</option>';
								}
							}
							?>
                        </select>
                      </div></td>
                  </tr>
                  <?
				}
				?>
                  
                <?
				if ($isp_type == 'HO' || $isp_type == 'SO' || $isp_type == 'AO') 
				{
				?>
                  <tr>
                    <td>Sales Zone</td>
                    <td id="sale_td"><div style="width:250px;; float:left;" class="selectbox">
                        <select name="sale_zone" style="width:250px;" id="sale_zone"  class="textbox" >
                          <option value="">--All--</option>
                          <?
							if ((isset($_REQUEST["area_office"]) && $_REQUEST["area_office"] != '') || (isset($_REQUEST["sale_zone"]) && $_REQUEST["sale_zone"] != '') || $isp_type == "AO") {
								if (isset($_REQUEST["area_office"]) && $_REQUEST["area_office"] != '')
									$area_id = $_REQUEST["area_office"];
	
	
								$sql = "select * from gb_office_relation where id1=" . $area_id;
								$res = mysql_query($sql);
								$temp_array = array("0");
								while ($row = mysql_fetch_array($res)) {
									$temp_array[] = $row["id2"];
								}
	
								$temp_string = implode(",", $temp_array);
	
								$sql = "select id,office_name from gb_offices where status=1 and oil_company_code = '1' and office_type='SZ' and id in (" . $temp_string . ")  order by office_name";
								$res = mysql_query($sql);
								while ($row = mysql_fetch_array($res)) {
									$sel = '';
									if ($_REQUEST["sale_zone"] == $row["id"])
										$sel = 'selected="selected"';
									echo '<option value="' . $row["id"] . '" ' . $sel . '>' . $row["office_name"] . '</option>';
								}
							}
							?>
                        </select>
                      </div></td>
                  </tr>
                  <?
				}
				?>
                                    
                  <tr>
                    <td></td>
                    <td style="border:0px;"><a href="javascript://" onClick="return searched();" >
                      <input type="submit" value="Search" class="inputbuttonblue"  style="margin-right:10px;"  />
                      </a> 
                         <!--<a class="submitbutton" href="export_distributors_bank.php?<?= $_SERVER['QUERY_STRING'] ?>">Export</a> -->
                      
					</td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td  align="left" class="adminlist" >
			   <?
				echo("<table class='tablecontainer2'><tr>");
				echo("<td><a href='".$self."'><b>Show All</b></a>&nbsp;&nbsp;&nbsp;</td>");
				for ($z = 65; $z <= 90; $z++) {
					if (@$_GET["alpha"] == chr($z))
						echo("<td>" . chr($z) . "</td>");
					else
						echo("<td><a class='atozlinks' href=" . $self . "?alpha=" . chr($z) . ">" . chr($z) . "</a></td>");
				}
				echo("</tr></table>");
				?>
                <input type="hidden" name="status"  id="status" value="" /></td>
            </tr>
            <tr>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td><table width="100%" cellpadding="0" cellspacing="0" class="tablecontainer" style="width:100%; word-wrap: break-word;table-layout: fixed;">
                  <tr>
                    <th width="5%" align="center">S.No.</th>
                    <th width="15%" align="left"><span onClick="formsubmit(2);" style="cursor:pointer">Distributor Name</span></th>
                    <?
					switch (@$_REQUEST["order"]) {
						case 1 :
							$order = "dealer_name";
							break;
						default:
							$order = "dealer_name";
					}					
					?>
                    <th width="8%" align="left" nowrap="nowrap">Sap Code</th>
                    <th width="15%" align="left" nowrap="nowrap">Dealer Email</th>
                    <th width="10%" align="left" nowrap="nowrap">Phone No.</th>
                    <th width="10%" align="left" nowrap="nowrap">Action</th> 
                  </tr>
                  <?
					$ii = 1;
					$color = 0;
					$sql = "select * from gb_company_dealer where dealer_status=1 and company_id = '1' and dist_bank_status = '0' " . $str . " order by " . $order . " limit " . $current . "," . $limit;
					$query2 = mysql_query($sql);
					$rowcount = 10 * $start - 10;
					while ($result2 = mysql_fetch_array($query2)) {
					?>
                  <tr class="row0<?= $color ?>" >
                    <td  width="3%" align="center"><? echo $rowcount + $ii ?></td>
                    <td align="left" style="text-align:left; padding:0px 3px 0px 5px;"><?=$result2["dealer_name"]?></td>
                    <td align="left" style="text-align:left; padding:0px 3px 0px 5px;"><?=$result2["dealer_code"]?></td>
                    <td align="left" style="text-align:left; padding:0px 3px 0px 5px;" ><?=$result2["dealer_email"]?></td>
                    <td align="left" style="text-align:left; padding:0px 3px 0px 5px;" ><?=$result2["dealer_phone1"]?></td>
                    <td align="center" valign="middle" style="text-align:center; padding:0px 3px 0px 5px;" ><a style="cursor:pointer;" onclick="sendmailtodistributor('<?=$result2['id']?>');" title="Send Bank Admin Details"><input type="button" name="sendemail" value="Resend Login Details" /></a></td>                    
                  </tr>
                  <?
				if ($color == 0)
					$color = 1;
				else
					$color = 0;
				$ii++;
			}//end while get news
			?>                  
                </table></td>
            </tr>
            <tr>
              <td  align="left" ><? if ($nume != 0) { ?>
                <table class="paginationtable" width="100%" cellpadding="0" cellspacing="0" border="0" align="left" >
                  <tr>
                    <th style="text-align:left;"><strong>&nbsp;Page :</strong>
                    <?
					if ($start <= 5) {
						if ($start == 1)
							echo "First&nbsp;&nbsp; ";
						else
							echo "<a href=$self?start=1&alpha=$alpha&search=$search&bgacity=" . $_REQUEST["bgacity"] . "&office=" . $_REQUEST["office"] . "&map_dealer=" . $_REQUEST["map_dealer"] . ">First</a>&nbsp;&nbsp;";
					}
					else
						echo "<a href=$self?start=1&alpha=$alpha&search=$search&bgacity=" . $_REQUEST["bgacity"] . "&office=" . $_REQUEST["office"] . "&map_dealer=" . $_REQUEST["map_dealer"] . ">First</a>&nbsp;&nbsp;"; //goto first page

					$starting = ((int) (($start - 1) / 5) * 5) + 1;
					if ($starting > 5) {
						$startpoint = $starting - 1;
						$previous = $start - 1;
						echo "<a href=$self?start=$previous&alpha=$alpha&search=$search&bgacity=" . $_REQUEST["bgacity"] . "&office=" . $_REQUEST["office"] . "&map_dealer=" . $_REQUEST["map_dealer"] . ">Previous</a>&nbsp;&nbsp;&nbsp;";
					}
					else {
						if ($start == 1)
							echo "Previous&nbsp;&nbsp;&nbsp;";
						else {
							$previous = $start - 1;
							echo "<a href=$self?start=$previous&alpha=$alpha&search=$search&bgacity=" . $_REQUEST["bgacity"] . "&office=" . $_REQUEST["office"] . "&map_dealer=" . $_REQUEST["map_dealer"] . ">Previous</a>&nbsp;&nbsp;&nbsp;";
						}
					}

					for ($i = $starting; $i <= $starting + 4; $i++) {
						if ($start == $i)
							echo "$i&nbsp;";
						else {
							if ($i <= $maxpage)
								echo "<a href=$self?start=$i&alpha=$alpha&search=$search&bgacity=" . $_REQUEST["bgacity"] . "&office=" . $_REQUEST["office"] . "&map_dealer=" . $_REQUEST["map_dealer"] . ">$i</a>&nbsp;";
							else
								break;
						}
					}
					if ($starting + 4 < $maxpage) {
						$nextstart = $i / 5;
						$next = $start + 1;
						echo "&nbsp;&nbsp;&nbsp;<a href=$self?start=$next&alpha=$alpha&search=$search&bgacity=" . $_REQUEST["bgacity"] . "&office=" . $_REQUEST["office"] . "&map_dealer=" . $_REQUEST["map_dealer"] . ">Next</a>";
					}
					else {
						if ($start == $maxpage)
							echo "&nbsp;&nbsp;&nbsp;Next";
						else {
							$next = $start + 1;
							echo "&nbsp;&nbsp;&nbsp;<a href=$self?start=$next&alpha=$alpha&search=$search&bgacity=" . $_REQUEST["bgacity"] . "&office=" . $_REQUEST["office"] . "&map_dealer=" . $_REQUEST["map_dealer"] . ">Next</a>";
						}
					}
//}
////////////////////Last Page///////////////////////
					if ($start > $maxpage - 4) {
						if ($start == $maxpage)
							echo "&nbsp;&nbsp;Last";
						else
							echo "&nbsp;&nbsp;<a href=$self?start=" . $maxpage . "&alpha=$alpha&search=$search&bgacity=" . $_REQUEST["bgacity"] . "&office=" . $_REQUEST["office"] . "&map_dealer=" . $_REQUEST["map_dealer"] . ">Last</a>";
					}
					else
						echo "&nbsp;&nbsp;<a href=$self?start=" . $maxpage . "&alpha=$alpha&search=$search&bgacity=" . $_REQUEST["bgacity"] . "&office=" . $_REQUEST["office"] . "&map_dealer=" . $_REQUEST["map_dealer"] . ">Last</a>"; //goto last page
///////////////////////////////////////////
///////////////end of displaying page Numbers////////////////
					?>
                      <select name="pagenumbers" onChange="gotopage('<?= $self ?>')">
                        <?
						for ($i = 1; $i <= $maxpage; $i++) {
							if ($start == $i)
								echo("<option value=$i selected>$i</option>");
							else
								echo("<option value=$i >$i</option>");
						}
						?>
                      </select></th>
                    <th width="200"> <div align="right"> Showing
                    <?
					if ($start * 10 > $nume) {
						$upperlimit = $nume;

						echo ($current + 1 . " - " . $upperlimit . " of " . $nume);
					}
					else
						echo ($current + 1 . " - " . $start * 10 . " of " . $nume);
					?>
                      </div></th>
                  </tr>
                </table>
                <?
                    }
                    else
                        echo ("<div align='center'>No Records Found</div>");
                    ?>
                <input name="mode" type="hidden" id="mode" value="0" />
                <input name="counter" type="hidden" id="counter" value="<?= $ii ?>" /></td>
            </tr>
          </table>
        </form>
    </div>
  </div>
</div>
<?
include_once("includes/footer.php");
?>
