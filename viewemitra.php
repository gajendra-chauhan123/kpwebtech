<?php
include_once("includes/header.php");
include '../phpmailer/sendmail.php';
$self = "viewemitra.php"; //File Name viewemitra.php

if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST')) {
    if (isset($_POST['dealeridbank']) && !empty($_POST['dealeridbank']) && $_POST['dealeridbank'] != '0' && !isset($_POST['iddealer'])) {
        $getDealer = mysql_query("select id,dealer_email,dealer_name,dealer_code,dist_bank_name, dist_bank_ifsc, dist_bank_accountno, dist_name_as_per_bank, dist_bank_email,dist_bank_mobile,dist_bank_add_date from gb_company_dealer where id = '" . mysql_real_escape_string($_POST['dealeridbank']) . "' and dealer_status = '1' and company_id = '1'");
        if ($getDealer) {
            if (mysql_num_rows($getDealer) > 0) {
                while ($row = mysql_fetch_assoc($getDealer)) {
                    $dealer_id = mysql_real_escape_string($row['id']);
                    $dist_bank_name = mysql_real_escape_string($row['dist_bank_name']);
                    $dist_bank_ifsc = mysql_real_escape_string($row['dist_bank_ifsc']);
                    $dist_bank_accountno = mysql_real_escape_string($row['dist_bank_accountno']);
                    $dist_name_as_per_bank = mysql_real_escape_string($row['dist_name_as_per_bank']);
                    $dist_bank_email = mysql_real_escape_string($row['dist_bank_email']);
                    $dist_bank_mobile = mysql_real_escape_string($row['dist_bank_mobile']);
                    $dist_bank_add_date = date('Y-m-d H:i:s', strtotime($row['dist_bank_add_date']));
                    $modified_user_type = mysql_real_escape_string($isp_type);
                    $lspid = $_SESSION['lspid'];

                    $modifieddate = date('Y-m-d H:i:s');

                    $insertLogs = mysql_query("INSERT INTO `gb_dealer_bank_details_logs`(`dealer_id`, `dist_bank_name`, `dist_bank_ifsc`, `dist_bank_accountno`, `dist_name_as_per_bank`, `dist_bank_email`, `dist_bank_mobile`, `dist_bank_add_date`, `modified_by`, `modified_user_type`, `modify_date`) VALUES ('$dealer_id','$dist_bank_name','$dist_bank_ifsc','$dist_bank_accountno','$dist_name_as_per_bank','$dist_bank_email','$dist_bank_mobile','$dist_bank_add_date','$lspid','$modified_user_type','$modifieddate')");
                    if ($insertLogs) {
                        $updateQry = mysql_query("update gb_company_dealer set dist_bank_name = '',dist_bank_ifsc = '',dist_bank_accountno = '',dist_name_as_per_bank = '',dist_bank_email = '',dist_bank_mobile = '',dist_bank_status = '0',dist_bank_add_date = '0000-00-00 00:00:00' where id = '" . mysql_real_escape_string($_POST['dealeridbank']) . "'");
//$updateQry = true;
                        if ($updateQry) {
//                        $to = "manish.arora@cyfuture.com";
                            $to = $row['dealer_email'];
                            $subject = "Indane - Reseeding of Bank Details for Transferring Online Payment of New Connection";
                            $message = '<html>
										<head>
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
										<td align="left">As requested, your Bank A/c Details has been deleted by your Area Office ( ' . getOfficeName($area_id) . ' ) from indane.co.in.</td>
										</tr>
										<tr>
										<td style="height:20px;">&nbsp;</td>
										</tr>
										<tr>
										<td align="left">Now you can reseed your correct bank a/c details by using below link.</td>
										</tr>
										<tr>
										<td style="height:20px;">&nbsp;</td>
										</tr>
										<tr>
										<td><a href="http://indane.co.in/distadmin/" target="_blank">http://indane.co.in/distadmin</a></td>
										</tr>
										<tr>
										<td style="height:20px;">&nbsp;</td>
										</tr>                                                                                 
										<tr>
										<td align="left">Your login credentials are same as before.</td>
										</tr>
										<tr>
										<td style="height:20px;">&nbsp;</td>
										</tr>
										<tr>
										<td><span style="color:#ff0000;">Note :- If you are a project distributor , don’t seed your Bank a/c details.</span></td>
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
                            $bcc = 'manish.arora@cyfuture.com';
                            mailsend($message, $subject, $to, $cc, $bcc);
                            include '../saveemail.php';
                            $_SESSION['successmsg'] = "Mail sent successfully to " . $to . " [" . $row['dealer_code'] . " ]";
                            echo "<script type=\"text/javascript\">window.location = '$self';</script>";
                            exit;
                        } else {
                            $_SESSION['errormsg'] = 'Something unexpected happen. Please try again later';
                        }
                    } else {
                        $_SESSION['errormsg'] = 'Something unexpected happen. Please try again later';
                    }
                }
            } else {
                $_SESSION['errormsg'] = 'Distributor is not active.';
            }
        }
    }
    if (isset($_POST['iddealer']) && !empty($_POST['iddealer']) && !isset($_POST['dealeridbank'])) {
        if ($_POST['iddealer'] <= 0) {
            $_SESSION['errormsg'] = "Something unexpected happen. Please try again later.";
        }
        $selectDealer = mysql_query("select id,pymt_enable_refill_booking from gb_company_dealer where dealer_status = '1' and company_id = '1' and dist_bank_status = '1' and dist_bank_activated = '1' and id = '" . mysql_real_escape_string($_POST['iddealer']) . "'");
        if ($selectDealer) {
            if (mysql_num_rows($selectDealer) > 0) {
                $updatePaymt = '';
                $updateStatus = '';
                $rowDealer = mysql_fetch_assoc($selectDealer);
                if ($rowDealer['pymt_enable_refill_booking'] == 0) {
                    $updatePaymt = 1;
                    $updateStatus = "Enabled";
                } else if ($rowDealer['pymt_enable_refill_booking'] == 1) {
                    $updatePaymt = 0;
                    $updateStatus = "Disabled";
                }

                if ($updatePaymt !== '') {
                    $updateQry = mysql_query("update gb_company_dealer set pymt_enable_refill_booking = '$updatePaymt' where id = '" . $rowDealer['id'] . "'");
                    if ($updateQry) {
                        $_SESSION['successmsg'] = "Distributor online payment refill booking has been $updateStatus";
                        echo '<script type="text/javascript">window.location = "' . SERVER_URL . 'manager/' . $self . '";</script>';
                        exit;
                    } else {
                        $_SESSION['errormsg'] = "Something unexpected happen. Please try again later.";
                    }
                } else {
                    $_SESSION['errormsg'] = "Something unexpected happen. Please try again later.";
                }
            } else {
                $_SESSION['errormsg'] = "Invalid distributor. Please try again later.";
            }
        } else {
            $_SESSION['errormsg'] = "Something unexpected happen. Please try again later.";
        }
    }
}

if (!isset($_GET['start']))
    $start = 1;
else
    $start = $_GET['start'];

if (!isset($_REQUEST['search']))
    $search = "";
else
    $search = trim($_REQUEST['search']);

if (!isset($_REQUEST['sap_code']))
    $sap_code = "";
else
    $sap_code = trim($_REQUEST['sap_code']);

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
    $str.=" and t1.so_id = '" . $_REQUEST["state_office"] . "'";
}
if (isset($_REQUEST["area_office"]) && $_REQUEST["area_office"] != '') {
    $str.=" and t1.ao_id = '" . $_REQUEST["area_office"] . "'";
}
if (isset($_REQUEST["sale_zone"]) && $_REQUEST["sale_zone"] != '') {
    $str.=" and t1.sz_id = '" . $_REQUEST["sale_zone"] . "'";
}

if ($str == '') {
    if ($isp_type == 'SO') {
        $str .= " and t1.so_id = '" . $area_id . "'";
    }
    if ($isp_type == 'AO') {
        $str .= " and t1.ao_id = '" . $area_id . "'";
    }
    if ($isp_type == 'SZ') {
        $str .= " and t1.sz_id = '" . $area_id . "'";
    }
}

if ($search != "")
    $str .= " and t1.dealer_name like '" . $search . "%' ";

else if ($alpha != "")
    $str .= " and t1.dealer_name like '" . $alpha . "%' ";


if ($sap_code != "")
    $str .= " and t1.dealer_code = '" . $sap_code . "' ";

if (isset($_REQUEST["showall"])) {
    if ($_REQUEST["showall"] == 1)
        $str = "";
}

$limit = 10;             // No of records to be shown per page.
$current = ($start - 1) * $limit; //record offset

$activate_str = '';

if (isset($_REQUEST["prefered_delivery"]) && $_REQUEST["prefered_delivery"] != '')
    $activate_str.=" and t1.prefered_delivery='" . $_REQUEST["prefered_delivery"] . "' ";

if (isset($_REQUEST["map_dealer"]) && $_REQUEST["map_dealer"] == 1) {
    $str.=" and t1.point_lat!=''";
} else if (isset($_REQUEST["map_dealer"]) && ($_REQUEST["map_dealer"] == 2 || $_REQUEST["map_dealer"] == '')) {
    $str.=" ";
} else if (isset($_REQUEST["map_dealer"]) && $_REQUEST["map_dealer"] == 0) {
    $str.=" and (t1.point_lat='' OR t1.point_lat IS NULL)";
}

if (isset($_REQUEST["ivrs_status"]) && $_REQUEST["ivrs_status"] == 0) {
    
} else if (isset($_REQUEST["ivrs_status"]) && ($_REQUEST["ivrs_status"] ==  'Y')) {
    $str.=" and t1.is_ivrs_available = '".  mysql_real_escape_string($_REQUEST["ivrs_status"])."'";
} else if (isset($_REQUEST["ivrs_status"]) && $_REQUEST["ivrs_status"] == 'N') {
    $str.=" and t1.is_ivrs_available = '".  mysql_real_escape_string($_REQUEST["ivrs_status"])."'";
}

$query1 = "select count(t1.id) as totaldealers from gb_company_dealer t1 join gb_offices t2 on t1.sz_id = t2.id where t1.dealer_status='1' " . $activate_str . $str;
$result1 = mysql_query($query1);
$row1 = mysql_fetch_assoc($result1);
$nume = $row1['totaldealers']; //Max no of rows

$maxpage = ceil($nume / $limit);         //No of pages

/* function getSalesZoneName($sz_id) {
  $sqlSZ = "select office_name, sz_code, status from gb_offices where id='" . $sz_id . "'";
  $qrySZ = mysql_query($sqlSZ);
  if (mysql_num_rows($qrySZ) > 0) {
  $rowSZ = mysql_fetch_assoc($qrySZ);
  if ($rowSZ['status'] == 1) {
  $officeName = $rowSZ['office_name'];
  if ($rowSZ['sz_code'] != '0') {
  $officeName .= " (" . $rowSZ['sz_code'] . ")";
  }
  return $officeName;
  } else {
  return "SZ not active";
  }
  } else {
  return NULL;
  }
  } */
?>
<script language="javascript" type="text/javascript">
    /**
     * Function for enable or disable online refill booking payment details
     */
    function refillPayment(dealerid) {
        if (dealerid != '' && dealerid > 0) {
            var result = confirm('Are you sure, you want to change status of Online Payment Refill booking of distributor.');
            if (result) {
                document.getElementById('iddealer').value = dealerid;
                if (document.getElementById('iddealer').value > 0) {
                    document.pymtenablefrm.submit();
                }
                else
                    return false;
            }
            else {
                document.getElementById('iddealer').value = 0;
                return false;
            }
        }
    }
    /**
     * Function for send bank login credentials to dealer email
     */
    function dealerbankdeactive(dealerid) {
        if (dealerid !== '' && dealerid !== 0) {
            var sure = confirm('Are you sure, you want to deactivate bank details of distributor for modification.');
            if (sure) {
                document.getElementById('dealeridbank').value = dealerid;
                document.bankdeactivation.submit();
            }
            else {
                return false;
            }
        }
    }
    function checknum(e)
    {
        evt = e || window.event;
        var keypressed = evt.which || evt.keyCode;
        if (keypressed != "48" && keypressed != "49" && keypressed != "50" && keypressed != "51" && keypressed != "52" && keypressed != "53" && keypressed != "54" && keypressed != "55" && keypressed != "8" && keypressed != "56" && keypressed != "57" && keypressed != "45")
        {
            return false;
        }
    }

    /*function formsubmit(val)
     {
     document.frmview.action = "viewemitra.php?order=" + val;
     document.frmview.submit();
     }*/

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
$search = $search . "&sap_code=" . $sap_code . "&state_office=" . $_REQUEST["state_office"] . "&area_office=" . $_REQUEST["area_office"] . "&sale_zone=" . $_REQUEST["sale_zone"] . "&map_dealer=" . $_REQUEST["map_dealer"] . "&ivrs_status=" . $_REQUEST["ivrs_status"];
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

    function back1()
    {
        window.location = "addemitra.php";
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

    function open_window(id)
    {
        strPath = "mylocation_distrib.php?DID=" + id;
        win = open(strPath, "DPPOPEN", "toolbars=no,scrollbars=1,menubar=no,width=640,height=600,directories=no,resizable=0,screenX=20,screenY=20,left=0,top=0");

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
            <h1>All Distributors</h1>
        </div>
    </div>
</div>
<div class="clear" style="height:20px;"></div>
<div class="innnerpagecontainer">
    <div class="midcontainer">
        <div class="innerpagecontent" style="padding:20px;">
            <div>
                <form action="" name="bankdeactivation" method="post">
                    <input type="hidden" name="dealeridbank" id="dealeridbank" value="0" />
                </form>
                <form action="" method="post" name="pymtenablefrm" id="pymtenablefrm">
                    <input type="hidden" name="iddealer" id="iddealer" value="0" />
                </form>
            </div>
            <div>
                <form id="frmview" name="frmview" method="get" action="" class="formcontainer"  >
                    <table cellpadding="0" cellspacing="0" width="100%"  >
<?php if (isset($_SESSION['successmsg']) || isset($_SESSION['errormsg'])) { ?>
                            <tr>
                                <td align="center"><strong>
                            <?php
                            if (isset($_SESSION['successmsg'])) {
                                echo '<span style="color:#00af43">' . $_SESSION['successmsg'] . '</span>';
                                unset($_SESSION['successmsg']);
                            } else if (isset($_SESSION['errormsg'])) {
                                echo '<span style="color:#ff0000">' . $_SESSION['errormsg'] . '</span>';
                                unset($_SESSION['errormsg']);
                            }
                            ?>
                                    </strong></td>
                            </tr>
                                    <?php } ?>
                                    <?php if (isset($_REQUEST['msg'])) { ?>
                            <tr>
                                <td align="center"><strong>
                            <?php
                            if ($_REQUEST['msg'] == '1') {
                                echo '<span style="color:#00af43">Dealer added successfully.</span>';
                            } else if ($_REQUEST['msg'] == '2') {
                                echo '<span style="color:#00af43">Dealer updated successfully.</span>';
                            }
                            ?>
                                    </strong></td>
                            </tr>
                                    <?php } ?>
                        <tr>
                            <td align="left"><table >
                                    <tr>
                                        <td>Search By Name</td>
                                        <td><input name="search"  type="text" id="search" value="<?= @$_REQUEST["search"] ?>" style="width:230px;" class="textbox" />
                                            <input name="showall" type="hidden" id="showall" value="0" /></td>
                                    </tr>
                                    <tr>
                                        <td>Search By Sap Code</td>
                                        <td><input name="sap_code" type="text" id="sap_code" value="<?= @$_REQUEST["sap_code"] ?>" style="width:230px;" class="textbox" /></td>
                                    </tr>
<?php
if ($isp_type == 'HO') {
    ?>
                                        <tr>
                                            <td>State Office</td>
                                            <td id="state_td"><div style="width:250px; float:left;" class="selectbox">
                                                    <select name="state_office"  id="state_office" onChange="get_sub_offices('SO', this.value);"  style="width:250px;" class="textbox" >
                                                        <option value="">--All--</option>
    <?php
    $sql = "select id,office_name from gb_offices where status=1 and office_type='SO' order by office_name";
    $res = mysql_query($sql);
    while ($row = mysql_fetch_array($res)) {
        $sel = '';
        if ($_REQUEST["state_office"] == $row["id"])
            $sel = 'selected="selected"';
        echo '<option value="' . $row["id"] . '" ' . $sel . '>' . $row["office_name"] . '</option>';
    }
    ?>
                                                    </select>
                                                </div></td>
                                        </tr>
                                                        <?
                                                    }

                                                    if ($isp_type == 'HO' || $isp_type == 'SO') {
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

                                                    if ($isp_type == 'HO' || $isp_type == 'SO' || $isp_type == 'AO') {
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

        $sql = "select id,office_name from gb_offices where status=1 and office_type='SZ' and id in (" . $temp_string . ")  order by office_name";
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
                                    <?php
                                    $chk21 = '';
                                    $chk22 = '';
                                    $chk23 = '';
                                    if (isset($_REQUEST["prefered_delivery"]) && $_REQUEST["prefered_delivery"] == "1") {
                                        $chk21 = 'checked="checked"';
                                    } else if (isset($_REQUEST["prefered_delivery"]) && $_REQUEST["prefered_delivery"] == "0") {
                                        $chk22 = 'checked="checked"';
                                    } else
                                        $chk23 = 'checked="checked"';
                                    
                                    $chk31 = '';
                                    $chk32 = '';
                                    $chk33 = '';
                                    if (isset($_REQUEST["ivrs_status"]) && $_REQUEST["ivrs_status"] == "Y") {
                                        $chk31 = 'checked="checked"';
                                    } else if (isset($_REQUEST["ivrs_status"]) && $_REQUEST["ivrs_status"] == "N") {
                                        $chk32 = 'checked="checked"';
                                    } else {
                                        $chk33 = 'checked="checked"';
                                    }

                                    $chk41 = '';
                                    $chk42 = '';
                                    $chk43 = '';
                                    if (isset($_REQUEST["map_dealer"]) && $_REQUEST["map_dealer"] == "1") {
                                        $chk41 = 'checked="checked"';
                                    } else if (isset($_REQUEST["map_dealer"]) && $_REQUEST["map_dealer"] == "0") {
                                        $chk42 = 'checked="checked"';
                                    } else {
                                        $chk43 = 'checked="checked"';
                                    }
                                    ?>
                                        <td style="border:0px;" nowrap="nowrap"> Preferred Time Status </td>
                                        <td><table class="tablecontainer2">
                                                <tr>
                                                    <td nowrap="nowrap" style="border:0px;"><input type="radio" name="prefered_delivery" id="prefered_delivery1" value="" <?= $chk23 ?> style="border:0px;"  onclick="if (document.getElementById('dealer_status3').checked == true)
                                      return false;"  />
                                                        <label for="prefered_delivery1">All</label></td>
                                                    <td nowrap="nowrap" style="border:0px;"><input type="radio" name="prefered_delivery" id="prefered_delivery2" value="1" <?= $chk21 ?> style="border:0px;" onClick="if (document.getElementById('dealer_status3').checked == true)
                                      return false;"    />
                                                        <label for="prefered_delivery2">On</label></td>
                                                    <td nowrap="nowrap" style="border:0px;"><input type="radio" name="prefered_delivery" id="prefered_delivery3" value="0" <?= $chk22 ?>  style="border:0px;"   />
                                                        <label for="prefered_delivery3">Off</label></td>
                                                </tr>
                                            </table></td>
                                    </tr>
                                    <tr>
                                        <td style="border:0px;"> IVRS Availability </td>
                                        <td><table class="tablecontainer2">
                                                <tr>
                                                    <td nowrap="nowrap" style="border:0px;"><input type="radio" value="0" name="ivrs_status" id="ivrs_status3" <?= $chk33 ?> style="border:0px;"  />
                                                        <label for="map_dealer3">All</label></td>
                                                    <td nowrap="nowrap" style="border:0px;"><input type="radio" value="Y" name="ivrs_status" id="ivrs_status1" <?= $chk31 ?> style="border:0px;"  />
                                                        <label for="map_dealer1">On</label></td>
                                                    <td nowrap="nowrap" style="border:0px;"><input type="radio" value="N" name="ivrs_status" id="ivrs_status2" <?= $chk32 ?> style="border:0px;"  />
                                                        <label for="map_dealer2">Off</label></td>
                                                    <td style="border:0px;"></td>
                                                </tr>
                                            </table></td>
                                    </tr>
                                    <tr>
                                        <td style="border:0px;"> Geo Mapping </td>
                                        <td><table class="tablecontainer2">
                                                <tr>
                                                    <td nowrap="nowrap" style="border:0px;"><input type="radio" value="2" name="map_dealer" id="map_dealer3" <?= $chk43 ?> style="border:0px;"  />
                                                        <label for="map_dealer3">All</label></td>
                                                    <td nowrap="nowrap" style="border:0px;"><input type="radio" value="1" name="map_dealer" id="map_dealer1" <?= $chk41 ?> style="border:0px;"  />
                                                        <label for="map_dealer1">Mapped</label></td>
                                                    <td nowrap="nowrap" style="border:0px;"><input type="radio" value="0" name="map_dealer" id="map_dealer2" <?= $chk42 ?> style="border:0px;"  />
                                                        <label for="map_dealer2">Not Mapped</label></td>
                                                    <td style="border:0px;"></td>
                                                </tr>
                                            </table></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="border:0px;"><a href="javascript://" onClick="return searched();" >
                                                <input type="submit" value="Search" class="inputbuttonblue"  style="margin-right:10px;"  />
                                            </a> <a class="submitbutton" href="export_viewemitra.php?<?= $_SERVER['QUERY_STRING'] ?>">Export</a>
<?php
if ($isp_type == 'SO' || $isp_type == 'AO') {
    ?>
                                                <!--<a href="adddealerao.php"><input type="button" name="Submit" class="inputbuttonblue" value="Add New"  onclick="window.location = 'adddealerao.php'"  style="margin-left:10px;" /></a>--> 
                                                <a href="addnewdealerao.php" class="inputbuttonblue" title="Add New Dealer of Other OMCs" style="margin-left:10px;">Add New Dealer of Other OMC</a>
    <?php
}
?>
                                            <a href="viewnewdistributor.php" class="inputbuttonblue" title="View Newly Added Distributors" style="margin-left:10px;">View Newly Added Distributors</a></td>
                                    </tr>
                                </table></td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                            <td  align="left" class="adminlist" ><?
echo("<table class='tablecontainer2'><tr>");
echo("<td><a href='viewemitra.php'><b>Show All</b></a>&nbsp;&nbsp;&nbsp;</td>");
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
                                        <th width="20" align="center">SN</th>
                                        <th align="left">Distributor Name</th>
                                        <th align="left" width="100" nowrap="nowrap">Sales Zone</th>
                                        <th align="left" nowrap="nowrap">E-mail</th>
                                        <th align="center" width="80">Google Map</th>
<? if ($isp_type == 'SZ' || ($isp_type == 'AO' && $isLocationIncharge == true)) { ?>
                                            <th align="center" width="50">APA FORM</th>
<? } ?>
                                        <th align="center" width="60">Booking Rule</th>
                                        <th align="center" width="50">Edit Dealer</th>
<?php if ($isp_type == 'AO' && $isLocationIncharge == true) { ?>
                                            <th align="center" width="80">Deactivate/Change Bank Details</th>
                                            <th width="80" align="center">Online Pymt Refill Booking Status</th>
                                        <?php } ?>

                                    </tr>
<?
$ii = 1;
$color = 0;
$sql = "select t1.*, t2.office_name from gb_company_dealer t1 join gb_offices t2 on t1.sz_id=t2.id where t1.dealer_status = '1' " . $activate_str . $str . " order by t1.company_id, t1.dealer_name limit " . $current . "," . $limit;
//echo $sql;die;
$query2 = mysql_query($sql);
$rowcount = 10 * $start - 10;
while ($result2 = mysql_fetch_array($query2)) {
    ?>
                                        <tr class="row0<?= $color ?>" >
                                            <td  width="3%" align="center"><? echo $rowcount + $ii ?></td>
                                            <td align="left" style="text-align:left; padding:0px 3px 0px 5px;"><?
                                        if ($result2["company_id"] == 1) {
                                            ?>
                                                    <a href="dealer_detail.php?id=<?= $result2["id"] ?>">
                                            <?= $result2["dealer_name"] ?>
                                                    </a>
        <?
    } else {
        echo $result2["dealer_name"];
    }
    ?></td>
                                            <td align="left" style="text-align:left; padding:0px 3px 0px 5px;" ><?php
                                                    echo $result2["office_name"];
                                                    ?></td>
                                            <td align="left" style="text-align:left; padding:0px 3px 0px 5px;" ><a href="mailto:<?= $result2["dealer_email"] ?>">
                                                <?= $result2["dealer_email"] ?>
                                                </a></td>
                                            <td align="left" style="padding:0px 3px 0px 5px;">
                                                <?php
                                                if ($result2["company_id"] == 1) {
                                                    ?>
                                                    <a href="javascript:void(0);" onclick="open_window('<?= $result2["id"] ?>');" style="text-decoration:none;" >
                                                        <?
                                                        if ($result2['point_lat'] != '') {
                                                            echo 'Edit Location';
                                                        } else {
                                                            echo 'Map Location';
                                                        }
                                                        ?>
                                                    </a>
                                                    <input type="hidden" name="DAddress" id="DAddress" value="<?= $result2["dealer_address"] ?>" />
                                                    <input type="hidden" name="dealer_id" id="dealer_id" value="<?= $result2["id"] ?>" />
                                                        <?
                                                    } else {
                                                        echo 'Other OMC';
                                                    }
                                                    ?></td>
                                                    <? if ($isp_type == 'SZ') { ?>
                                                <td align="center"><a href="apa.php?id=<?= $result2["id"] ?>">APA</a></td>
        <?
    } elseif ($isp_type == 'AO' && $isLocationIncharge == true) {
        ?>
                                                <td align="center" ><?php if ($result2["company_id"] == 1) { ?>
                                                        <a href="apa_ao.php?id=<?= $result2["id"] ?>">APA</a>
                                                        <?
                                                    } else {
                                                        echo 'Other OMC';
                                                    }
                                                    ?>
                                                </td>
                                            <? } ?>
                                            <td align="center" ><?php if ($result2["company_id"] == 1) { ?>
                                                    <a title="Edit Booking Rule" href="dealerbookingrules.php?deaer_id=<?= $result2["id"] ?>">Edit</a>
                                                    <?
                                                } else {
                                                    echo 'Other OMC';
                                                }
                                                ?></td>
                                            <td align="center" ><?php if ($result2["company_id"] == 1) { ?>
                                                    <a title="Edit Dealer" href="editnewdealerao.php?id=<?= $result2["id"] ?>">Edit</a>
                                                <?
                                            } else {
                                                echo 'Other OMC';
                                            }
                                            ?>
                                            </td>
                                                <?php
                                                if ($isp_type == 'AO' && $isLocationIncharge == true) {
                                                    ?>
                                                <td align="center" >
                                                    <?php
                                                    if ($result2["company_id"] == 1) {
                                                        if ($result2['dist_bank_status'] == 1) {
                                                            ?>
                                                            <a title="Deactivate Bank Details" href="javascript:void(0);" onclick="dealerbankdeactive('<?= $result2['id'] ?>');"><img src="images/icon-inactive.png" title="Deactivate Bank Details" /></a>
                                                            <?
                                                        } else {
                                                            ?>
                                                            Bank details not found.
                                                        <?php
                                                    }
                                                } else {
                                                    echo 'Other OMC';
                                                }
                                                ?>
                                                </td>
                                                <td align="center" ><?
                                            if ($result2['dealer_status'] == 1) {

                                                if ($result2['is_ivrs_available'] == 'Y') {

                                                    if ($result2['dist_bank_status'] == '1' && $result2['dist_bank_activated'] == 1) {

                                                        if ($result2['dealer_code'] != "000000" && $result2['dealer_code'] != "" && $result2['pymt_enable_refill_booking'] == 1) {
                                                            echo '<img src="../images/icon-active.png" border="0" class="noborder" title="Online Payment Refill Booking Enabled" onclick="refillPayment(' . $result2['id'] . ')" style="cursor:pointer;" />';
                                                        } else if ($result2['dealer_code'] != "000000" && $result2['dealer_code'] != "" && $result2['pymt_enable_refill_booking'] == 0) {
                                                            echo '<img src="../images/icon-inactive.png" border="0" class="noborder" title="Online Payment Refill Booking Disabled" onclick="refillPayment(' . $result2['id'] . ')" style="cursor:pointer;" />';
                                                        }
                                                    } else {
                                                        echo 'Bank details not found.';
                                                    }
                                                } else {
                                                    echo 'IVRS Facility not available.';
                                                }
                                            } elseif ($result2['dealer_status'] == 0) {
                                                if ($result2['dealer_code'] != "000000" && $result2['dealer_code'] != "") {
                                                    echo 'Inactive';
                                                }
                                            }
                                                ?></td>
                                                <?php } ?>
                                        </tr>
                                                <?
                                                if ($color == 0)
                                                    $color = 1;
                                                else
                                                    $color = 0;
                                                $ii++;
                                            }
                                            ?>
                                    <input type="hidden" name="srvId" value="<?= $result2["id"] ?>" />
                                </table></td>
                        </tr>
                        <tr>
                            <td  align="left" ><? if ($nume != 0) { ?>
                                    <table class="paginationtable" width="100%" cellpadding="0" cellspacing="0" border="0" align="left" >
                                        <tr>
                                            <th style="text-align:left;"><strong>&nbsp;Page :</strong>
                                        <?php
                                        if ($start <= 5) {
                                            if ($start == 1)
                                                echo "First&nbsp;&nbsp; ";
                                            else
                                                echo "<a href=$self?start=1&alpha=$alpha&search=$search&bgacity=" . $_REQUEST["bgacity"] . "&office=" . $_REQUEST["office"] . "&map_dealer=" . $_REQUEST["map_dealer"] . "&ivrs_status=" . $_REQUEST["ivrs_status"] . ">First</a>&nbsp;&nbsp;";
                                        } else
                                            echo "<a href=$self?start=1&alpha=$alpha&search=$search&bgacity=" . $_REQUEST["bgacity"] . "&office=" . $_REQUEST["office"] . "&map_dealer=" . $_REQUEST["map_dealer"] . "&ivrs_status=" . $_REQUEST["ivrs_status"] . ">First</a>&nbsp;&nbsp;"; //goto first page

                                        $starting = ((int) (($start - 1) / 5) * 5) + 1;
                                        if ($starting > 5) {
                                            $startpoint = $starting - 1;
                                            $previous = $start - 1;
                                            echo "<a href=$self?start=$previous&alpha=$alpha&search=$search&bgacity=" . $_REQUEST["bgacity"] . "&office=" . $_REQUEST["office"] . "&map_dealer=" . $_REQUEST["map_dealer"] . "&ivrs_status=" . $_REQUEST["ivrs_status"] . ">Previous</a>&nbsp;&nbsp;&nbsp;";
                                        } else {
                                            if ($start == 1)
                                                echo "Previous&nbsp;&nbsp;&nbsp;";
                                            else {
                                                $previous = $start - 1;
                                                echo "<a href=$self?start=$previous&alpha=$alpha&search=$search&bgacity=" . $_REQUEST["bgacity"] . "&office=" . $_REQUEST["office"] . "&map_dealer=" . $_REQUEST["map_dealer"] . "&ivrs_status=" . $_REQUEST["ivrs_status"] . ">Previous</a>&nbsp;&nbsp;&nbsp;";
                                            }
                                        }

                                        for ($i = $starting; $i <= $starting + 4; $i++) {
                                            if ($start == $i)
                                                echo "$i&nbsp;";
                                            else {
                                                if ($i <= $maxpage)
                                                    echo "<a href=$self?start=$i&alpha=$alpha&search=$search&bgacity=" . $_REQUEST["bgacity"] . "&office=" . $_REQUEST["office"] . "&map_dealer=" . $_REQUEST["map_dealer"] . "&ivrs_status=" . $_REQUEST["ivrs_status"] . ">$i</a>&nbsp;";
                                                else
                                                    break;
                                            }
                                        }
                                        if ($starting + 4 < $maxpage) {
                                            $nextstart = $i / 5;
                                            $next = $start + 1;
                                            echo "&nbsp;&nbsp;&nbsp;<a href=$self?start=$next&alpha=$alpha&search=$search&bgacity=" . $_REQUEST["bgacity"] . "&office=" . $_REQUEST["office"] . "&map_dealer=" . $_REQUEST["map_dealer"] . "&ivrs_status=" . $_REQUEST["ivrs_status"] . ">Next</a>";
                                        } else {
                                            if ($start == $maxpage)
                                                echo "&nbsp;&nbsp;&nbsp;Next";
                                            else {
                                                $next = $start + 1;
                                                echo "&nbsp;&nbsp;&nbsp;<a href=$self?start=$next&alpha=$alpha&search=$search&bgacity=" . $_REQUEST["bgacity"] . "&office=" . $_REQUEST["office"] . "&map_dealer=" . $_REQUEST["map_dealer"] . "&ivrs_status=" . $_REQUEST["ivrs_status"] . ">Next</a>";
                                            }
                                        }

                                        if ($start > $maxpage - 4) {
                                            if ($start == $maxpage)
                                                echo "&nbsp;&nbsp;Last";
                                            else
                                                echo "&nbsp;&nbsp;<a href=$self?start=" . $maxpage . "&alpha=$alpha&search=$search&bgacity=" . $_REQUEST["bgacity"] . "&office=" . $_REQUEST["office"] . "&map_dealer=" . $_REQUEST["map_dealer"] . "&ivrs_status=" . $_REQUEST["ivrs_status"] . ">Last</a>";
                                        } else
                                            echo "&nbsp;&nbsp;<a href=$self?start=" . $maxpage . "&alpha=$alpha&search=$search&bgacity=" . $_REQUEST["bgacity"] . "&office=" . $_REQUEST["office"] . "&map_dealer=" . $_REQUEST["map_dealer"] . "&ivrs_status=" . $_REQUEST["ivrs_status"] . ">Last</a>"; //goto last page
                                        ?>
                                                <select name="pagenumbers" onChange="gotopage('<?= $self ?>')">
                                                <?php
                                                for ($i = 1; $i <= $maxpage; $i++) {
                                                    if ($start == $i)
                                                        echo("<option value=$i selected>$i</option>");
                                                    else
                                                        echo("<option value=$i >$i</option>");
                                                }
                                                ?>
                                                </select></th>
                                            <th width="200"> <div align="right"> Showing
                                                <?php
                                                if ($start * 10 > $nume) {
                                                    $upperlimit = $nume;

                                                    echo ($current + 1 . " - " . $upperlimit . " of " . $nume);
                                                } else
                                                    echo ($current + 1 . " - " . $start * 10 . " of " . $nume);
                                                ?>
                                        </div></th>
                            </tr>
                        </table>
                                                    <?php
                                                } else
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
</div>
                                        <?php
                                        include_once("includes/footer.php");
                                        ?>
