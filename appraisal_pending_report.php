<?
require_once("../includes/connection.php");

if (!isset($_REQUEST['arch_year'])) {
    $_REQUEST['arch_year'] =  date('Y')-1;
}

if (isset($_GET["mode"]) && $_GET["mode"] == "delete") {
    $sql = "delete from gb_company_dealer where id=" . $_GET["delid"];
    mysql_query($sql);
    $sql = "delete from gb_office_relation where id2=" . $_GET["delid"];
    mysql_query($sql);

    if ($_GET["start"] == '') {
        $star1 = 1;
    }
    else {
        $star1 = $_GET["start"];
    }
    echo '<script>window.location="appraisal_pending_report.php?start=' . $star1 . '&alpha=' . $_GET["alpha"] . '&search=' . $_GET["alpha"] . '&state=' . $_GET["state"] . '&city=' . $_GET["city"] . '&map_dealer=' . $_GET["map_dealer"] . '";</script>';
    exit;
}


$self = "appraisal_pending_report.php"; //File Name
///////////parameter values/////////

if (!isset($_GET['start']))
    $start = 1;
else
    $start = $_GET['start'];

if (!isset($_REQUEST['search']))
    $search = "";
else
    $search = trim($_REQUEST['search']);

if (!isset($_REQUEST['alpha']))
    $alpha = "";
else
    $alpha = $_REQUEST['alpha'];
if (!isset($_REQUEST['state']))
    $state = "";
else
    $state = $_REQUEST['state'];
if (!isset($_REQUEST['city']))
    $city = "";
else
    $city = $_REQUEST['city'];

/////////////end parameter values/////////
/////////////////////query parameters/////////////

$str = "";

if ($search != "")
    $str = " and (dealer_name like '" . $search . "%' or dealer_code like '" . $search . "%' )";



else if ($alpha != "")
    $str = " and  dealer_name like '" . $alpha . "%' ";

if ($state != 0) {
    $str.= " and city_id in ( Select id from gb_city where state_id='" . $state . "') ";
}

if ($city != 0) {
    $str.= " and city_id ='" . $city . "'";
}


if (isset($_REQUEST["sale_zone"]) && $_REQUEST["sale_zone"] != "") {
    $sql = "select id2 from gb_office_relation where id1=" . $_REQUEST["sale_zone"];
    $temp_res = mysql_query($sql);
    $temp_array = array('0');
    while ($temp_row = mysql_fetch_row($temp_res)) {
        $temp_array[] = $temp_row[0];
    }

    $temp_string = implode(",", $temp_array);
    $str.=" and id in (" . $temp_string . ")";
}
else if (isset($_REQUEST["area_office"]) && $_REQUEST["area_office"] != "") {
    $sql = "select id2 from gb_office_relation where id1=" . $_REQUEST["area_office"];
    $temp_res = mysql_query($sql);
    $temp_array = array('0');
    while ($temp_row = mysql_fetch_row($temp_res)) {
        $temp_array[] = $temp_row[0];
    }

    $temp_string = implode(",", $temp_array);

    $sql = "select id2 from gb_office_relation where id1 in(" . $temp_string . ")";
    $temp_res = mysql_query($sql);
    $temp_array = array('0');
    while ($temp_row = mysql_fetch_row($temp_res)) {
        $temp_array[] = $temp_row[0];
    }

    $temp_string = implode(",", $temp_array);



    $str.=" and id in (" . $temp_string . ")";
}
else if (isset($_REQUEST["state_office"]) && $_REQUEST["state_office"] != "") {

    $sql = "select id2 from gb_office_relation where id1=" . $_REQUEST["state_office"];
    $temp_res = mysql_query($sql);
    $temp_array = array('0');
    while ($temp_row = mysql_fetch_row($temp_res)) {
        $temp_array[] = $temp_row[0];
    }

    $temp_string = implode(",", $temp_array);

    $sql = "select id2 from gb_office_relation where id1 in(" . $temp_string . ")";
    $temp_res = mysql_query($sql);
    $temp_array = array('0');
    while ($temp_row = mysql_fetch_row($temp_res)) {
        $temp_array[] = $temp_row[0];
    }

    $temp_string = implode(",", $temp_array);

    $sql = "select id2 from gb_office_relation where id1 in(" . $temp_string . ")";
    $temp_res = mysql_query($sql);
    $temp_array = array('0');
    while ($temp_row = mysql_fetch_row($temp_res)) {
        $temp_array[] = $temp_row[0];
    }

    $temp_string = implode(",", $temp_array);

    $str.=" and id in (" . $temp_string . ")";
}


if (isset($_REQUEST["dealer_status"]) && $_REQUEST["dealer_status"] != "") {
    $str.=" and dealer_status='" . $_REQUEST["dealer_status"] . "' ";
}


if (isset($_REQUEST["prefered_delivery"]) && $_REQUEST["prefered_delivery"] != "") {
    $str.=" and prefered_delivery='" . $_REQUEST["prefered_delivery"] . "' ";
}


if (isset($_REQUEST["activate"]) && $_REQUEST["activate"] != "") {
    $str.=" and activate='" . $_REQUEST["activate"] . "' ";
}



if (isset($_REQUEST["map_dealer"]) && $_REQUEST["map_dealer"] == 1) {
    $str.=" and point_lat!=''";
}
else if (isset($_REQUEST["map_dealer"]) && ($_REQUEST["map_dealer"] == 2 || $_REQUEST["map_dealer"] == '')) {
    $str.=" ";
}
else if (isset($_REQUEST["map_dealer"]) && $_REQUEST["map_dealer"] == 0) {
    $str.=" and (point_lat='' OR point_lat IS NULL )";
}

if (isset($_REQUEST["showall"])) {

    if ($_REQUEST["showall"] == 1)
        $str = "";
}
if (isset($_REQUEST['export']) && $_REQUEST['export']="Export")
{
	$downfilename = "Data-List-".date("m-d-Y").".xls";
	header("Content-type: application/vnd.ms-excel");
	header("Content-Type: application/download");
	header("Content-disposition: filename=".$downfilename."");			
	echo '<table border="1">'.getDataForDisplay($str).'</table>';exit();
}
include_once("includes/header.php");

//////////////end of query parameters///////////////////
/////////////////////////////////////////

$limit = 10;             // No of records to be shown per page.

$current = ($start - 1) * $limit; //record offset



$query1 = "SELECT id, dealer_name FROM gb_company_dealer where id!='0' $str";


$result1 = mysql_query($query1) or die(mysql_error() . " => " . $query1);



$nume = mysql_num_rows($result1);    //Max no of rows

$maxpage = ceil($nume / $limit);         //No of pages

function getDataForDisplay($serch_str, $data_for_limit=0,$pagination=0)
{
	$ii = 1;$color = 0;
	$output_tbl_str = '<tr>

                                                    <th width="4%" align="center" >S.No.</th>

                                                    <th width="26%" align="left" >Distributor Names</th>

                                                    <th width="20%" align="left" nowrap="nowrap">Distributor Code</th>
                                                    <th width="20%" align="left" nowrap="nowrap">Area Office</th>
                                                    <th width="15%" align="center">Self Appraisal</th>
                                                    <th width="15%" align="center">FO</th>
                                                    <th width="15%" align="center">AO</th>
                                                    <th width="15%" align="center">Status</th>
                                                </tr>';
	$query2str = "select id, dealer_name,dealer_status,ao_id,dealer_code,prefered_delivery,dealer_email,ifnull(B.Self_Appraisal,0) Self_Appraisal, ifnull(C.FO,0) FO, ifnull(D.AO,0) AO  from gb_company_dealer as A 
	left join (
	select distributor_id,1  as Self_Appraisal from gb_ada_marks where gb_type='D' and STATUS=1 and year='".$_REQUEST['arch_year']."'
	group by distributor_id) as B on A.id=B.distributor_id 
	left join (
	select distributor_id,1 as FO from gb_ada_marks where  gb_type='SZ' and STATUS=1 and year='".$_REQUEST['arch_year']."'
	group by distributor_id) as C on A.id=C.distributor_id 
	left join (
	select distributor_id,1 as AO from gb_ada_marks where  gb_type='AO' and STATUS=1 and year='".$_REQUEST['arch_year']."'
	group by distributor_id) as D on A.id=D.distributor_id 
where id!='0' and dealer_status='1' and activate=1 and is_available_in_sap=1 $serch_str order by dealer_name ";
//echo $query2str;
	$rowcount=0;
	global $start;
	if($data_for_limit==1)
	{
		$query2 = mysql_query($query2str);
		//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
		$limit = 10;             // No of records to be shown per page.
		$current = ($start - 1) * $limit; //record offset

		

		$nume = mysql_num_rows($query2);    //Max no of rows
		$maxpage = ceil($nume / $limit);         //No of pages
		mysql_free_result($query2);
		//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
		$query2str = $query2str . "  limit $current, $limit";
	}

	
//echo $query2str;
	$query2 = mysql_query($query2str);
	$TotalRsNet = 0;
	$distCountNet = 0;
	$distCountSZNet = 0;
	$distCountAONet = 0;

	$rowcount = $limit * $start - $limit;
	$result2Array = array();
	$i = 0;
	while ($result2 = mysql_fetch_array($query2)) {
		
		$sql1 =  "select lsp_address,id from gb_lsp where id=".$result2['ao_id']." and isp_type='AO'";
		$sql2 = mysql_query($sql1);
		$result3 = mysql_fetch_array($sql2);
		
		$output_tbl_str .= '<tr class="row0'.$color.'" >

                                                        <td  width="4%" align="center">'.($rowcount + $ii).'</td>

                    <td align="left"  style="text-align:left">'.$result2['dealer_name'].'</td>

                    <td align="left" >'.$result2['dealer_code'].'</td>';
if(!empty($result3['lsp_address'])) {					
 $output_tbl_str .='<td align="left" >'.$result3['lsp_address'].'</td>';
}
else
{
	$output_tbl_str .='<td align="left" >NA</td>';
}
      $output_tbl_str .= '<td align="center">';
		if($data_for_limit==1)
		{				
			if ($result2['Self_Appraisal']) {
				$output_tbl_str .=  "<img src='images/tick.png' />";
			}
			else {
				$output_tbl_str .=  "<img src='images/cross.png' />";
			}
		}
		else
		{
			$output_tbl_str .= $result2['Self_Appraisal']	;
		}
        $output_tbl_str .= '</td>
		<td align="center">';
		if($data_for_limit==1)
		{				
			if ($result2['FO']) {
				$output_tbl_str .=  "<img src='images/tick.png' />";
			}
			else {
				$output_tbl_str .=  "<img src='images/cross.png' />";
			}
		}
		else
		{
			$output_tbl_str .= $result2['FO']	;
		}
        $output_tbl_str .= '</td>
		<td align="center">';
		if($data_for_limit==1)
		{				
			if ($result2['AO']) {
				$output_tbl_str .=  "<img src='images/tick.png' />";
			}
			else {
				$output_tbl_str .=  "<img src='images/cross.png' />";
			}
		}
		else
		{
			$output_tbl_str .= $result2['AO']	;
		}
        $output_tbl_str .= '</td>';
                                                        
		$output_tbl_str .= '<td align="center">';
		if ($result2['Self_Appraisal'] && $result2['FO'] && $result2['AO']) 
		{
			$output_tbl_str .=  "Finalise";
		}
		else {
			$output_tbl_str .=  "Pending";
		} 
		$output_tbl_str .= '</td>
			</tr>';
		

		if ($color == 0) {
			$color = 1;
		}
		else {
			$color = 0;
		}
		$ii++;
							
    }//end while get news
	
	if($pagination==1)
	{
		$output_tbl_str .= '<tr>
								<td colspan="7"  align="left">';
		if ($nume != 0) 
		{ 
			$output_tbl_str .= '<form id="frmview2" name="frmview2" method="get" action="" >
									<table class="paginationtable" width="100%" cellpadding="0" cellspacing="0" border="0">

										<tr>

											<th><strong>&nbsp;Page :</strong>';

    
///////////////display page Numbers////////////////
	global $state,$city;
    @$search.='&state=' . $state . '&city=' . $city . '&state_office=' . $_REQUEST["state_office"] . '&area_office=' . $_REQUEST["area_office"] . '&sale_zone=' . $_REQUEST["sale_zone"] . '&activate=' . $_REQUEST["activate"] . '&dealer_status=' . $_REQUEST["dealer_status"] . '&prefered_delivery=' . $_REQUEST["prefered_delivery"] . '&map_dealer=' . $_REQUEST["map_dealer"];

//////////////////First Page///////////////////////

    if ($start <= 5) {

        if ($start == 1)
            $output_tbl_str .=  "First&nbsp;&nbsp; ";
        else
            $output_tbl_str .=  "<a href=$self?start=1&alpha=$alpha&search=$search>First</a>&nbsp;&nbsp;";
    }
    else
        $output_tbl_str .=  "<a href=$self?start=1&alpha=$alpha&search=$search>First</a>&nbsp;&nbsp;"; //goto first page


///////////////////////////////////////////



    $starting = ((int) (($start - 1) / 5) * 5) + 1;

    if ($starting > 5) {

        $startpoint = $starting - 1;

        $previous = $start - 1;

        $output_tbl_str .=  "<a href=$self?start=$previous&alpha=$alpha&search=$search>Previous</a>&nbsp;&nbsp;&nbsp;";
    }
    else {

        if ($start == 1)
            $output_tbl_str .=  "Previous&nbsp;&nbsp;&nbsp;";

        else {

            $previous = $start - 1;

            $output_tbl_str .=  "<a href=$self?start=$previous&alpha=$alpha&search=$search>Previous</a>&nbsp;&nbsp;&nbsp;";
        }
    }



    for ($i = $starting; $i <= $starting + 4; $i++) {

        if ($start == $i)
            $output_tbl_str .=  "$i&nbsp;";

        else {

            if ($i <= $maxpage)
                $output_tbl_str .=  "<a href=$self?start=$i&alpha=$alpha&search=$search>$i</a>&nbsp;";
            else
                break;
        }
    }

    if ($starting + 4 < $maxpage) {

        $nextstart = $i / 5;

        $next = $start + 1;

        $output_tbl_str .=  "&nbsp;&nbsp;&nbsp;<a href=$self?start=$next&alpha=$alpha&search=$search>Next</a>";
    }
    else {

        if ($start == $maxpage)
            $output_tbl_str .=  "&nbsp;&nbsp;&nbsp;Next";

        else {

            $next = $start + 1;

            $output_tbl_str .=  "&nbsp;&nbsp;&nbsp;<a href=$self?start=$next&alpha=$alpha&search=$search>Next</a>";
        }
    }

//}
////////////////////Last Page///////////////////////

    if ($start > $maxpage - 4) {

        if ($start == $maxpage)
            $output_tbl_str .=  "&nbsp;&nbsp;Last";
        else
            $output_tbl_str .=  "&nbsp;&nbsp;<a href=$self?start=" . $maxpage . "&alpha=$alpha&search=$search>Last</a>";
    }
    else
        $output_tbl_str .=  "&nbsp;&nbsp;<a href=$self?start=" . $maxpage . "&alpha=$alpha&search=$search>Last</a>"; //goto last page


///////////////////////////////////////////
///////////////end of displaying page Numbers////////////////
    
	$output_tbl_str .= '<select name="pagenumbers" onchange="gotopage(\''.$self.'\')">';

                                                                            
	for ($i = 1; $i <= $maxpage; $i++) {

		if ($start == $i)
			$output_tbl_str .= "<option value=$i selected>$i</option>";
		else
			$output_tbl_str .= "<option value=$i >$i</option>";
	}
    $output_tbl_str .= '</select>

						</th>

						<th width="200">

					<div align="right">

						Showing';

                                                                            
	if ($start * $limit > $nume) {
		$upperlimit = $nume;
		$output_tbl_str .= ($current + 1 . " - " . $upperlimit . " of " . $nume);
	}
	else
		$output_tbl_str .= ($current + 1 . " - " . $start * $limit . " of " . $nume);
	$output_tbl_str .= '</div></th>
						</tr>

					</table></form>';

							 }
							else $output_tbl_str .= "no records found"; 

	$output_tbl_str .= '<input name="mode" type="hidden" id="mode" value="0" />

			<input name="counter" type="hidden" id="counter" value="'.$ii.'" /></td>
	</tr>';

	}
	
	return $output_tbl_str;
}

?>
<!-- Light box css or js files -->
<link href="../css_js/gbscript/gb_styles.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript"> var GB_ROOT_DIR = "../css_js/gbscript/";</script>
<script type="text/javascript" src="../css_js/gbscript/AJS.js"></script>
<script type="text/javascript" src="../css_js/gbscript/AJS_fx.js"></script>
<script type="text/javascript" src="../css_js/gbscript/gb_scripts.js"></script>
<!-- End file including  -->

<script language="javascript" type="text/javascript" src="../js/ajax.js"></script>
<script language="javascript" type="text/javascript" src="../js/ajax-dynamic-content.js"></script>


<script language="javascript" type="text/javascript">
    function back1() {

        window.location = "adddealer.php";

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

        if (document.frmview.search.value == "" && document.frmview.state.value == "0" && document.frmview.city.value == "0")

        {

            alert("Please enter search ");

            document.frmview.search.focus();

            return false;

        }

        else

        {

            document.frmview.action = "viewdealer.php?start=1";

            document.frmview.submit();

        }

    }





    function gotopage(path)

    {

        window.location = path + '?search=<?= $search ?>&alpha=<?= $alpha ?>&start=' + document.frmview.pagenumbers.value;

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
            document.getElementById(containerid + "_div").innerHTML = page_request.responseText;
            document.getElementById(containerid + "_img").style.visibility = "hidden";
        }
    }



    function get_area_office()
    {
        id = document.getElementById("state_office").value;
        url = "get_area_office.php?id=" + id;
        document.getElementById("area_office_img").style.visibility = "visible";
        ajaxpage(url, "area_office");

        url = "get_sale_zone.php?id=0";
        document.getElementById("sale_zone_img").style.visibility = "visible";
        ajaxpage(url, "sale_zone");
    }


    function get_sale_zone()
    {
//	if(document.getElementById("state_office").value=="")
//		return;
        id = document.getElementById("area_office").value;
        url = "get_sale_zone.php?id=" + id;
        document.getElementById("sale_zone_img").style.visibility = "visible";
        ajaxpage(url, "sale_zone");
    }


    function Printdiv()
    {
        obj_win = window.open("print_distributor.php?search=<?= @$_REQUEST["search"] ?>&state=<?= @$_REQUEST["state"] ?>&city=<?= @$_REQUEST["city"] ?>&state_office=<?= @$_REQUEST["state_office"] ?>&area_office=<?= @$_REQUEST["area_office"] ?>&sale_zone=<?= @$_REQUEST["sale_zone"] ?>&alpha=<?= @$alpha ?>&activate=<?= @$_REQUEST["activate"] ?>&prefered_delivery=<?= @$_REQUEST["prefered_delivery"] ?>&dealer_status=<?= @$_REQUEST["dealer_status"] ?>&map_dealer=<?= @$_REQUEST["map_dealer"] ?>", "new_win", "toolbars=no,maximize=yes,scrollbars=yes,menubar=no,width=600 ,height=380,directories=no,resizable=1,screenX=0,screenY=0,left=0,top=0");

    }

</script>

<?php /* ?><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td align="left"><h3 class="heading2">Distributor</h3></td>
  <td width="61%">&nbsp;</td>
  <td><a href="adddealer.php" ><input type="button" class="mybutton" value="ADD" onclick="window.location='adddealer.php'"  /></a></td>
  <td><a href="javascript:void(0);" onclick="Printdiv();" ><input type="button" class="mybutton" value="PRINT"  /></a></td>
  <td align="right"><a href="exportactivatedealer.php?search=<?=@$_GET["search"]?>&state=<?=@$_GET["state"]?>&city=<?=@$_GET["city"]?>&state_office=<?=@$_GET["state_office"]?>&area_office=<?=@$_GET["area_office"]?>&sale_zone=<?=@$_GET["sale_zone"]?>&alpha=<?=@$_GET["alpha"]?>&activate=<?=@$_REQUEST["activate"]?>&prefered_delivery=<?=@$_REQUEST["prefered_delivery"]?>&dealer_status=<?=@$_REQUEST["dealer_status"]?>&map_dealer=<? if($_REQUEST["map_dealer"]=='') { echo '3';} else { echo $_REQUEST["map_dealer"];}?>"><input type="button" class="mybutton" value="EXPORT TO EXCEL"  /></a></td>
  <!--<td ><a href="export_dealer.php?search=<?=@$_GET["search"]?>&state=<?=@$_GET["state"]?>&city=<?=@$_GET["city"]?>&state_office=<?=@$_GET["state_office"]?>&area_office=<?=@$_GET["area_office"]?>&sale_zone=<?=@$_GET["sale_zone"]?>&alpha=<?=@$_GET["alpha"]?>&activate=<?=@$_REQUEST["activate"]?>&prefered_delivery=<?=@$_REQUEST["prefered_delivery"]?>&dealer_status=<?=@$_REQUEST["dealer_status"]?>">Export Offices</a></td>-->
  <td>&nbsp;</td>
  </tr>
  </table><?php */ ?>

<div class="innerheadercontainer2">
    <!--Bread Crame Start here-->
    <div class="borderbottom"></div>

    <!--Bread Crame End here-->
    <div class="midcontainer innerheadermain">
        <div class="innerfortunelogo3"><img src="<?= SERVER_URL ?>images/fortunelogo.png"  alt="Fortune Logo" /></div>

        <div class="innerheaderleft">
            <h1>Appraisal Pending Report  </h1>
        </div>
    </div>
</div>


<div class="innnerpagecontainer">
    <div class="midcontainer">
        <div class="innerpagecontent" style="padding:20px;">
            <div class="panel padding10px">
                <form id="frmview" name="frmview" method="get" action="" class="formcontainer" >
                    <table width="100%">
                        <tr>
                            <td valign="top">
                                <table bgcolor="#FFFFFF" class="adminlist" width="100%">

                                    <tr>

                                        <td width="32%" align="left" valign="top"><table cellpadding="3" cellspacing="0" border="0">
                                                <tr>
                                                    <td nowrap="nowrap" style="border:none;" width="80">Select Year </td>
                                                    <td style="border:none;">

                                                        <div class="selectbox" style="float:left; width:250px;">
                                                            <select name="arch_year" id="arch_year" style="width:250px;" >
                                                                <option value="2010" <?php if (@$_REQUEST['arch_year'] == "2010") { ?> selected="selected" <? } ?>>2010-11</option>
                                                                <option value="2011" <?php if (@$_REQUEST['arch_year'] == "2011") { ?> selected="selected" <? } ?>>2011-12</option>
                                                                <option value="2012" <?php if (@$_REQUEST['arch_year'] == "2012") { ?> selected="selected" <? } ?>>2012-13</option>
                                                                <option value="2013" <?php if (@$_REQUEST['arch_year'] == "2013") { ?> selected="selected" <? } ?>>2013-14</option>
                                                                <option value="2014" <?php if (@$_REQUEST['arch_year'] == "2014") { ?> selected="selected" <? } ?>>2014-15</option>
                                                                <option value="2015" <?php if (@$_REQUEST['arch_year'] == "2015") { ?> selected="selected" <? } ?>>2015-16</option>
                                                                <option value="2016" <?php if (@$_REQUEST['arch_year'] == "2016") { ?> selected="selected" <? } ?>>2016-17</option>
                                                                <option value="2017" <?php if (@$_REQUEST['arch_year'] == "2017") { ?> selected="selected" <? } ?>>2017-18</option>
																 <option value="2018" <?php if (@$_REQUEST['arch_year'] == "2018") { ?> selected="selected" <? } ?>>2018-19</option>
                                                            </select>

                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td nowrap="nowrap" style="border:none;">Name/Code </td>
                                                    <td style="border:none;"><input name="search" type="text" id="search" value="<?= @$_REQUEST["search"] ?>" style="width:230px;" />
                                                        <input name="showall" type="hidden" id="showall" value="0" />              </td>
                                                </tr>

                                            </table>
                                            <table cellpadding="3" cellspacing="0" border="0">
                                                <tr>
                                                    <td nowrap="nowrap" style="border:none;" width="80">State Office</td>
                                                    <td style="border:none;">
                                                        <div class="selectbox" style="float:left; width:250px;">
                                                            <div id="state_office_div">
                                                                <select name="state_office" id="state_office" onchange="get_area_office()" style="width:250px;">
                                                                    <option value=""> --All--</option>
<?
$sql = "select * from gb_offices where status=1 and office_type='SO' order by office_name";
$res = mysql_query($sql);
while ($row = mysql_fetch_array($res)) {
    $sel = '';
    if ($row["id"] == $_REQUEST["state_office"])
        $sel = "selected='selected'";
    echo '<option ' . $sel . ' value="' . $row["id"] . '">' . $row["office_name"] . '</option>';
}
?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="border:none;">
                                                        <img src="../images/ajax_small_load.gif" id="area_office_img" style="visibility:hidden;"  />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td nowrap="nowrap" style="border:none;">Area Office</td>
                                                    <td style="border:none;">
                                                        <div class="selectbox" style="float:left; width:250px;">
                                                            <div id="area_office_div">
                                                                <select name="area_office" id="area_office" onchange="get_sale_zone();" style="width:250px;">
                                                                    <option value=""> --All--</option>
<?
if (isset($_REQUEST["state_office"]) && $_REQUEST["state_office"] != "") {
    $sql = "select * from gb_offices where status=1 and office_type='AO' and id in(select id2 from gb_office_relation where id1 =" . $_REQUEST["state_office"] . ") order by office_name";
    $res = mysql_query($sql);
    while ($row = mysql_fetch_array($res)) {
        $sel = '';
        if ($row["id"] == $_REQUEST["area_office"])
            $sel = "selected='selected'";
        echo '<option ' . $sel . ' value="' . $row["id"] . '">' . $row["office_name"] . '</option>';
    }
}
?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="border:none;">
                                                        <img src="../images/ajax_small_load.gif" id="sale_zone_img" style="visibility:hidden;"   />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td nowrap="nowrap" style="border:none;">Sales Zone</td>
                                                    <td style="border:none;">
                                                        <div class="selectbox" style="float:left; width:250px;">
                                                            <div id="sale_zone_div">
                                                                <select name="sale_zone" id="sale_zone"  style="width:250px;">
                                                                    <option value=""> --All--</option>
                                                                    <?
                                                                    if (isset($_REQUEST["area_office"]) && $_REQUEST["area_office"] != "") {
                                                                        $sql = "select * from gb_offices where status=1 and office_type='SZ' and id in(select id2 from gb_office_relation where id1 =" . $_REQUEST["area_office"] . ") order by office_name";
                                                                        $res = mysql_query($sql);
                                                                        while ($row = mysql_fetch_array($res)) {
                                                                            $sel = '';
                                                                            if ($row["id"] == $_REQUEST["sale_zone"])
                                                                                $sel = "selected='selected'";
                                                                            echo '<option ' . $sel . ' value="' . $row["id"] . '">' . $row["office_name"] . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="border:none;">&nbsp;


                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="2"><input type="submit" value="Search" class="inputbutton" style="border:none; margin-left:80px;"  /><input type="submit" name="export" value="Export" class="inputbutton" style="border:none; margin-left:80px;"  /></td>
                                                </tr>

                                                <tr>
                                                    <td colspan="2">&nbsp;</td>
                                                </tr>

                                            </table>
                                        </td>

                                        <td width="36%" align="left" valign="top">



                                        </td>
                                        <td width="3%" align="left" valign="top" colspan="3">&nbsp;


                                        </td>


                                    </tr>

                                    <tr>

                                        <td colspan="5"  align="left" ><?
///////////For Alphabatic Display ////////////////////

                                                                    /* echo("<table><tr>");

                                                                      echo("<td><a href='viewdealer.php'><b>Show All</b></a>&nbsp;&nbsp;&nbsp;</td>");

                                                                      for($z=65;$z<=90;$z++)

                                                                      {

                                                                      if(@$_GET["alpha"]==chr($z))

                                                                      echo("<td>".chr($z)."</td>");

                                                                      else

                                                                      echo("<td><a class='atozlinks' href=".$self."?alpha=".chr($z).">".chr($z)."</a></td>");

                                                                      }

                                                                      echo("</tr></table>"); */

/////////////end of Alphabatic Display///////////////
                                                                    ?>          <input type="hidden" name="status"  id="status" value="" /></td>
                                    </tr>



                                    <tr>

                                        <td colspan="5"><table width="100%"  cellspacing="0" class="tablecontainer">
<?php echo getDataForDisplay($str, 1,1);?>
                                                <?php ////////////////// below work in function so removed by prashant 06/04/2014
												/*<tr>

                                                    <th width="4%" align="center" >S.No.</th>

                                                    <th width="45%" align="left" >Distributor  Names</th>

                                                    <th width="7%" align="left" nowrap="nowrap">Distributor Code</th>
                                                    <!--<th width="9%" align="center" >Email</th>
                                                                        <th width="10%" align="center" nowrap="nowrap" >Preferred Time</th>
                                                    <th width="10%" align="center" >Status</th>
                                                                        <th width="7%" align="center" >Delete</th>
                                                    <th width="9%" align="center" >APA Form</th>-->
                                                    <th align="center">Self Appraisal</th>
                                                    <th align="center">FO</th>
                                                    <th align="center">AO</th>
                                                    <th align="center">Status</th>
                                                </tr>

<?
$ii = 1;

$color = 0;


$query2 = mysql_query("select id, dealer_name,dealer_status,dealer_code,prefered_delivery,dealer_email  from gb_company_dealer where id!='0' $str order by dealer_name limit $current, $limit");


$rowcount = 10 * $start - 10;

while ($result2 = mysql_fetch_array($query2)) {
    ?>

                                                    <tr class="row0<?= $color ?>" >

                                                        <td  width="4%" align="center"><? echo $rowcount + $ii ?></td>

                    <td align="left"  style="text-align:left"><!--<a href="editdealer.php?id=<?= $result2["id"] ?>">--><?= $result2["dealer_name"] ?><!--</a>--></td>

                    <td align="left" ><?= $result2['dealer_code'] ?><!--<a href="editdealer.php?id=<?= $result2["id"] ?>"><img src="images/edit.gif" title="Edit"/></a>--></td>

                                                        <td align="center"><?
                                                    $dis_status = mysql_query('SELECT STATUS
						FROM gb_ada_marks
						WHERE distributor_id =' . $result2["id"] . '
						AND gb_type = "D"  and year = "' . $_REQUEST['arch_year'] . '"
						GROUP BY gb_type');
                                                    if (!$dis_status) {
                                                        die('Invalid query: ' . mysql_error());
                                                    }

                                                    $dis_row1 = mysql_fetch_array($dis_status);
                                                    if ($dis_row1['STATUS']) {
                                                        echo "<img src='images/tick.png' />";
                                                    }
                                                    else {
                                                        echo "<img src='images/cross.png' />";
                                                    }
                                                    ?></td>
                                                        <td align="center">
    <?
    $sz_status = mysql_query('SELECT STATUS
						FROM gb_ada_marks
						WHERE distributor_id =' . $result2["id"] . '
						AND gb_type = "SZ"  and year = "' . $_REQUEST['arch_year'] . '"
						GROUP BY gb_type');
    if (!$sz_status) {
        die('Invalid query: ' . mysql_error());
    }

    $sz_row1 = mysql_fetch_array($sz_status);
    if ($sz_row1['STATUS']) {
        echo "<img src='images/tick.png' />";
    }
    else {
        echo "<img src='images/cross.png' />";
    }
    ?>
                                                        </td>
                                                        <td align="center">
                                                            <?
                                                            $ao_status = mysql_query('SELECT STATUS
						FROM gb_ada_marks
						WHERE distributor_id =' . $result2["id"] . '
						AND gb_type = "AO"  and year = "' . $_REQUEST['arch_year'] . '"
						GROUP BY gb_type');
                                                            if (!$ao_status) {
                                                                die('Invalid query: ' . mysql_error());
                                                            }
                                                            $ao_row1 = mysql_fetch_array($ao_status);
                                                            if ($ao_row1['STATUS']) {
                                                                echo "<img src='images/tick.png' />";
                                                            }
                                                            else {
                                                                echo "<img src='images/cross.png' />";
                                                            }
                                                            ?>
                                                        </td>
                                                        <td align="center"><? if ($ao_row1['STATUS'][0] && $sz_row1['STATUS'][0] && $dis_row1['STATUS'][0]) {
                                                            echo "Finalise";
                                                        }
                                                        else {
                                                            echo "Pending";
                                                        } ?></td>

                                                    </tr><?
                                                            if ($color == 0)
                                                                $color = 1;
                                                            else
                                                                $color = 0;

                                                            $ii++;
                                                        }//end while get news
                                                        ?>

                                                <input type="hidden" name="srvId" value="<?= $result2["id"] ?>" />

                                            </table></td>
                                    </tr>



                                    <tr>

                                        <td colspan="5"  align="left"><? if ($nume != 0) { ?><table class="paginationtable" width="100%" cellpadding="0" cellspacing="0" border="0">

                                                    <tr>

                                                        <th><strong>&nbsp;Page :</strong>

    <?
///////////////display page Numbers////////////////

    @$search.='&state=' . $state . '&city=' . $city . '&state_office=' . $_REQUEST["state_office"] . '&area_office=' . $_REQUEST["area_office"] . '&sale_zone=' . $_REQUEST["sale_zone"] . '&activate=' . $_REQUEST["activate"] . '&dealer_status=' . $_REQUEST["dealer_status"] . '&prefered_delivery=' . $_REQUEST["prefered_delivery"] . '&map_dealer=' . $_REQUEST["map_dealer"] . '&arch_year=' . $_REQUEST['arch_year'];

//////////////////First Page///////////////////////

    if ($start <= 5) {

        if ($start == 1)
            echo "First&nbsp;&nbsp; ";
        else
            echo "<a href=$self?start=1&alpha=$alpha&search=$search>First</a>&nbsp;&nbsp;";
    }
    else
        echo "<a href=$self?start=1&alpha=$alpha&search=$search>First</a>&nbsp;&nbsp;"; //goto first page


///////////////////////////////////////////



    $starting = ((int) (($start - 1) / 5) * 5) + 1;

    if ($starting > 5) {

        $startpoint = $starting - 1;

        $previous = $start - 1;

        echo "<a href=$self?start=$previous&alpha=$alpha&search=$search>Previous</a>&nbsp;&nbsp;&nbsp;";
    }
    else {

        if ($start == 1)
            echo "Previous&nbsp;&nbsp;&nbsp;";

        else {

            $previous = $start - 1;

            echo "<a href=$self?start=$previous&alpha=$alpha&search=$search>Previous</a>&nbsp;&nbsp;&nbsp;";
        }
    }



    for ($i = $starting; $i <= $starting + 4; $i++) {

        if ($start == $i)
            echo "$i&nbsp;";

        else {

            if ($i <= $maxpage)
                echo "<a href=$self?start=$i&alpha=$alpha&search=$search>$i</a>&nbsp;";
            else
                break;
        }
    }

    if ($starting + 4 < $maxpage) {

        $nextstart = $i / 5;

        $next = $start + 1;

        echo "&nbsp;&nbsp;&nbsp;<a href=$self?start=$next&alpha=$alpha&search=$search>Next</a>";
    }
    else {

        if ($start == $maxpage)
            echo "&nbsp;&nbsp;&nbsp;Next";

        else {

            $next = $start + 1;

            echo "&nbsp;&nbsp;&nbsp;<a href=$self?start=$next&alpha=$alpha&search=$search>Next</a>";
        }
    }

//}
////////////////////Last Page///////////////////////

    if ($start > $maxpage - 4) {

        if ($start == $maxpage)
            echo "&nbsp;&nbsp;Last";
        else
            echo "&nbsp;&nbsp;<a href=$self?start=" . $maxpage . "&alpha=$alpha&search=$search>Last</a>";
    }
    else
        echo "&nbsp;&nbsp;<a href=$self?start=" . $maxpage . "&alpha=$alpha&search=$search>Last</a>"; //goto last page


///////////////////////////////////////////
///////////////end of displaying page Numbers////////////////
    ?>

                                                            <select name="pagenumbers" onchange="gotopage('<?= $self ?>')">

                                                            <?
                                                            for ($i = 1; $i <= $maxpage; $i++) {

                                                                if ($start == $i)
                                                                    echo("<option value=$i selected>$i</option>");
                                                                else
                                                                    echo("<option value=$i >$i</option>");
                                                            }
                                                            ?>
                                                            </select></th>

                                                        <th width="200">

                                                    <div align="right">

                                                        Showing

                                                            <?
                                                            if ($start * 10 > $nume) {

                                                                $upperlimit = $nume;



                                                                echo ($current + 1 . " - " . $upperlimit . " of " . $nume);
                                                            }
                                                            else
                                                                echo ($current + 1 . " - " . $start * 10 . " of " . $nume);
                                                            ?></div></th>
                                        </tr>

                                    </table>

                                                        <? }
                                                        else echo ("no records found"); ?>

                                <input name="mode" type="hidden" id="mode" value="0" />

                                <input name="counter" type="hidden" id="counter" value="<?= $ii ?>" /></td>
                        </tr>*/?>
										</table></td>
									</tr>
					</table>
                    </td>
                    </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

                                                        <?
                                                        include_once("includes/footer.php");
                                                        ?>