<?
require_once("../includes/connection.php");
$apa_year = date('Y')-1;

if (!isset($_REQUEST['arch_year'])) {
    $_REQUEST['arch_year'] = date('Y')-1;
}
if(is_numeric($_REQUEST['arch_year']))
{
	$apa_year = $_REQUEST['arch_year'];
}
else{
	exit;
}
if (isset($_REQUEST['export']) && $_REQUEST['export']="Export")
{
	$downfilename = "Data-LIst-".date("m-d-Y").".xls";
	header("Content-type: application/vnd.ms-excel");
	header("Content-Type: application/download");
	header("Content-disposition: filename=".$downfilename."");			
	echo '<table border="1">'.getDataForDisplay().'</table>';exit();
}
include_once("includes/header.php");


$strStateList = "";
$strAOList = "";

$self = "apa_summery_report.php"; //File Name
///////////parameter values/////////

if (!isset($_REQUEST['start']))
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
function funreaddata($sqlstr) {
    $temp_resRsNew = mysql_query($sqlstr);
    $temp_stringNew = "";
    if (count(mysql_num_rows($temp_resRsNew)) > 0) {
        while ($temp_rowNew = mysql_fetch_row($temp_resRsNew)) {
            $temp_arrayNew[] = $temp_rowNew[0];
        }
        $temp_stringNew = implode(",", $temp_arrayNew);
    }
    mysql_free_result($temp_resRsNew);
    return $temp_stringNew;
}

/////////////////////query parameters/////////////

$str = "";

if ($search != "")
    $str = " and (dealer_name like '" . $search . "%' or dealer_code like '" . $search . "%' )";



else if ($alpha != "")
    $str = " and  dealer_name like '" . $alpha . "%' ";

if ($state != 0) {
    $StrReturn = funreaddata("Select id from gb_city where state_id='" . $state . "'");
    if (StrReturn != "") {
        $str.= " and city_id in (" . StrReturn . ")";
    }
    else {
        $str.= " and city_id in (-1)";
    }
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

//////////////end of query parameters///////////////////

function getDataForDisplay($data_for_limit=0,$pagination=0)
{
	global $apa_year;
	$ii = 1;$color = 0;
	$output_tbl_str = '<tr>

                                                    <th width="4%" align="center" >S.No.</th>

                                                    <th width="26%" align="left" >Office Names</th>

                                                    <th width="17%" align="left" nowrap="nowrap">Total Distributors</th>
                                                    
                                                    <th width="20%" align="center">Pending with Distributor</th>
                                                    <th width="17%" align="center">Pending with FO</th>
                                                    <th width="16%" align="center">Pending with AO</th>
                                                </tr>';
	$officeFileter = " so_id ";
	$StrCondition='';
	if (isset($_REQUEST["sale_zone"]) && $_REQUEST["sale_zone"] != "") {
		$officeFileter = " sz_id ";
		$StrCondition = " and sz_id='".mysql_real_escape_string($_REQUEST["sale_zone"])."' ";
	}
	else if (isset($_REQUEST["area_office"]) && $_REQUEST["area_office"] != "") {
		$officeFileter = " sz_id ";
		$StrCondition = " and ao_id='".mysql_real_escape_string($_REQUEST["area_office"])."' ";
	}
	else if (isset($_REQUEST["state_office"]) && $_REQUEST["state_office"] != "" && $_REQUEST["state_office"] != "0") {
		$officeFileter = " ao_id ";
		$StrCondition = " and so_id='".mysql_real_escape_string($_REQUEST["state_office"])."' ";
	}
	else {
		$officeFileter = " so_id ";
	}
	$query2str = "select $officeFileter,office_name ,count(1) as tot_dist,sum(if(dist_apa_status=0,1,0)) pending_distributor, sum(if(fo_status=0,1,0)) pending_fo, sum(if(ao_status=0,1,0)) pending_ao from(
				select A.company_id, dealer_name,dealer_status,dealer_code,prefered_delivery,dealer_email,so_id,ao_id,sz_id,ifnull(B.dist_apa_status,0) dist_apa_status, ifnull(C.fo_status,0) fo_status, ifnull(D.ao_status,0) ao_status from gb_company_dealer as A 
					left join ( select distributor_id,1 as dist_apa_status from gb_ada_marks where gb_type='D' and status=1 and year='".$apa_year."' group by distributor_id) as B on A.id=B.distributor_id 
					left join ( select distributor_id,1 as fo_status from gb_ada_marks where gb_type='SZ' and STATUS=1 and year='".$apa_year."' group by distributor_id) as C on A.id=C.distributor_id 
					left join ( select distributor_id,1 as ao_status from gb_ada_marks where gb_type='AO' and STATUS=1 and year='".$apa_year."' group by distributor_id) as D on A.id=D.distributor_id 
				where A.id!='0' and dealer_status='1' and activate=1 and is_available_in_sap=1 and dealer_add_datetime<'".date('Y')."-04-01'
				) as Z
				left join gb_offices E on Z.$officeFileter=E.id
				where 1 $StrCondition
				group by $officeFileter,office_name";
	$rowcount=0;
	//echo $query2str;
	global $start;
	if($data_for_limit==1)
	{
		$query2 = mysql_query($query2str);
		//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
		$limit = 5;             // No of records to be shown per page.
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
	
	while ($result2 = mysql_fetch_array($query2)) 
	{
		$rsId = $result2["id"];
		
		$output_tbl_str .=  '<tr class="row0'.$color.'" >
							<td style="border-right:none">'.($rowcount+$ii).'</td>
							<td  align="right">'.$result2['office_name'].'</td>
							<td align="center" >'.$result2['tot_dist'].'</td>
							<td align="center">'.$result2['pending_distributor'].'</td>
							<td align="center"> '.$result2['pending_fo'].'</td>
							<td align="center">  '.$result2['pending_ao'].'</td>
						</tr>';
						
		$TotalRsNet += $result2['tot_dist'];
		$distCountNet += $result2['pending_distributor'];
		$distCountSZNet += $result2['pending_fo'];
		$distCountAONet += $result2['pending_ao'];
		if ($color == 0) {
			$color = 1;
		}
		else {
			$color = 0;
		}
		$ii++;
	}//end while get news
	$output_tbl_str .=  '<tr class="row0'.$color.'" >
							<td style="border-right:none">&nbsp;</td>
							<td  align="right" style="border-left:none"><b>Total : </b></td>
							<td align="center" ><b>'.$TotalRsNet.'</b></td>
							<td align="center"><b>'.$distCountNet.'</b></td>
							<td align="center"> <b>'.$distCountSZNet.'</b></td>
							<td align="center">  <b>'.$distCountAONet.'</b></td>
						</tr>';
	if($pagination==1)
	{
		$output_tbl_str .= '<tr>
								<td colspan="6"  align="left">';
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

function getDataForDisplay_archive($data_for_limit=0,$pagination=0)
{
	$ii = 1;$color = 0;
	$output_tbl_str = '<tr>

                                                    <th width="4%" align="center" >S.No.</th>

                                                    <th width="26%" align="left" >Office Names</th>

                                                    <th width="17%" align="left" nowrap="nowrap">Total Distributors</th>
                                                    
                                                    <th width="20%" align="center">Pending with Distributor</th>
                                                    <th width="17%" align="center">Pending with FO</th>
                                                    <th width="16%" align="center">Pending with AO</th>
                                                </tr>';
	$officeFileter = " so_id ";
	if (isset($_REQUEST["sale_zone"]) && $_REQUEST["sale_zone"] != "") {
		$officeFileter = " sz_id ";
		$query2str = "select office_name,id from gb_offices where status=1 and office_type='SZ' and id =" . $_REQUEST["sale_zone"] . " order by office_name";
	}
	else if (isset($_REQUEST["area_office"]) && $_REQUEST["area_office"] != "") {
		$officeFileter = " ao_id ";
		if ($strAOList == "") {
			$strAOList = FunGetAreaOfficeList();
		}
		$temp_stringNew = $strAOList;
		if ($temp_stringNew == "") {
			$temp_stringNew = "-1";
		}
		$query2str = "select office_name,id from gb_offices where status=1 and office_type='SZ' and id in(" . $temp_stringNew . ") order by office_name";
	}
	else if (isset($_REQUEST["state_office"]) && $_REQUEST["state_office"] != "" && $_REQUEST["state_office"] != "0") {
		if ($strStateList == "") {
			$strStateList = FunGetStateList();
		}
		$temp_stringNew = $strStateList;
		if ($temp_stringNew == "") {
			$temp_stringNew = "-1";
		}
		$officeFileter = " so_id ";
		$query2str = "select office_name,id from gb_offices
								where status=1 and office_type='AO' and id in(" . $temp_stringNew . ") order by office_name";
	}
	else {
		$query2str = "select office_name,id from gb_offices where status=1 and office_type='SO' order by office_name";
	}
	$rowcount=0;
	global $start;
	if($data_for_limit==1)
	{
		$query2 = mysql_query($query2str);
		//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
		$limit = 5;             // No of records to be shown per page.
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
		$rsId = $result2["id"];
		$rsOfficeName = $result2['office_name'];



		if (isset($_REQUEST["sale_zone"]) && $_REQUEST["sale_zone"] != "") {
			$StrCondition = FunGetSelRs($rsId);
		}
		else if (isset($_REQUEST["area_office"]) && $_REQUEST["area_office"] != "") {
			$StrCondition = getsalesZonSelect($rsId);
		}
		else if (isset($_REQUEST["state_office"]) && $_REQUEST["state_office"] != "" && $_REQUEST["state_office"] != "0") {
			$StrCondition = getAreaid($rsId);
		}
		else {
			$StrCondition = getsalesZon($rsId);
		}

		$distCount = 0;
		$distCountAO = 0;
		$distCountSZ = 0;
		$TotalRs = 0;
		if ($StrCondition != "and id in (0)") {
			$sqlstr = "select id from gb_company_dealer where id!='0'  and dealer_status='1' and activate=1 and is_available_in_sap=1 and dealer_add_datetime<'" . ($_REQUEST['arch_year']+1) . "-04-01' " . $StrCondition;
			//echo $sqlstr; exit;
			$testQry = mysql_query($sqlstr);
			$TotalRs = mysql_num_rows($testQry);
			while ($temp_row2 = mysql_fetch_row($testQry)) {
				$temp_array2 = $temp_row2[0];
				
			}
		}

		$output_tbl_str .= '<tr class="row0'. $color .'" >
								<td  width="4%" align="center">'. ($rowcount + $ii) .'</td>
								<td align="left"  style="text-align:left">'. $rsOfficeName .'</td>
								<td align="center" >'. $TotalRs.'                </td>
								<td align="center">';
								
			$qdistCount = mysql_query("SELECT count(*) as TotalRec FROM `gb_ada_marks` WHERE `distributor_id` IN ( SELECT id FROM gb_company_dealer WHERE $officeFileter = '" . $result2["id"] . "'  and dealer_status='1' and activate=1 and is_available_in_sap=1) AND `gb_type` = 'D' AND `year` = '" . $_REQUEST['arch_year'] . "' and status='1'") or die(mysql_error());
			$getcounts = mysql_fetch_array($qdistCount);
			$distCount = $getcounts['TotalRec'];
		$output_tbl_str .= $TotalRs - $distCount;
        $output_tbl_str .= '</td>
						<td align="center">' ;
					$qdistCount = mysql_query("SELECT count(*) as TotalRec FROM `gb_ada_marks` WHERE `distributor_id` IN ( SELECT id FROM gb_company_dealer WHERE $officeFileter = '" . $result2["id"] . "'  and dealer_status='1' and activate=1 and is_available_in_sap=1) AND `gb_type` = 'SZ' AND `year` = '" . $_REQUEST['arch_year'] . "' and status='1'") or die(mysql_error());
					$getcounts = mysql_fetch_array($qdistCount);
					$distCountSZ = $getcounts['TotalRec'];
		$output_tbl_str .= $TotalRs - $distCountSZ;
		$output_tbl_str .= '</td>
						<td align="center">';  
					$qdistCount = mysql_query("SELECT count(*) as TotalRec FROM `gb_ada_marks` WHERE `distributor_id` IN ( SELECT id FROM gb_company_dealer WHERE $officeFileter = '" . $result2["id"] . "'  and dealer_status='1' and activate=1 and is_available_in_sap=1) AND `gb_type` = 'AO' AND `year` = '" . $_REQUEST['arch_year'] . "' and status='1'") or die(mysql_error());
					$getcounts = mysql_fetch_array($qdistCount);
					$distCountAO = $getcounts['TotalRec'];
		$output_tbl_str .=  $TotalRs - $distCountAO;
		$output_tbl_str .=  '</td>
					</tr>';
							
		$TotalRsNet = $TotalRsNet + $TotalRs;
		$distCountNet = $distCountNet + ($TotalRs - $distCount);
		$distCountSZNet = $distCountSZNet + ($TotalRs - $distCountSZ);
		$distCountAONet = $distCountAONet + ($TotalRs - $distCountAO);

		if ($color == 0) {
			$color = 1;
		}
		else {
			$color = 0;
		}
		$ii++;
							
    }//end while get news
	$output_tbl_str .=  '<tr class="row0'.$color.'" >
							<td style="border-right:none">&nbsp;</td>
							<td  align="right" style="border-left:none"><b>Total : </b></td>
							<td align="center" ><b>'.$TotalRsNet.'</b></td>
							<td align="center"><b>'.$distCountNet.'</b></td>
							<td align="center"> <b>'.$distCountSZNet.'</b></td>
							<td align="center">  <b>'.$distCountAONet.'</b></td>
						</tr>';
	if($pagination==1)
	{
		$output_tbl_str .= '<tr>
								<td colspan="6"  align="left">';
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

function getAreaid($areaId) {
    $sql = "select id2 from gb_office_relation where id1=" . $areaId;
    $temp_res = mysql_query($sql);
    $temp_array = array('0');
    $temp_string = "";
    $str = "";
    if (count(mysql_num_rows($temp_res)) > 0) {
        while ($temp_row = mysql_fetch_row($temp_res)) {
            $temp_array[] = $temp_row[0];
        }
        $temp_string = implode(",", $temp_array);

        $sql = "select id2 from gb_office_relation where id1 in(" . $temp_string . ")";
        $temp_res2 = mysql_query($sql);
        $temp_array2 = array('0');
        $temp_string = "";
        if (count(mysql_num_rows($temp_res2)) > 0) {
            while ($temp_row2 = mysql_fetch_row($temp_res2)) {
                $temp_array2[] = $temp_row2[0];
            }
            $temp_string = implode(",", $temp_array2);
            $str.=" and id in (" . $temp_string . ")";
        }
        mysql_free_result($temp_res2);
    }
    mysql_free_result($temp_res);
    return $str;
}

function getsalesZon($areaId) {
    $str = "";
    $sql = "select id2 from gb_office_relation where id1=" . $areaId;
    $temp_res = mysql_query($sql);
    $temp_array = array('0');
    if (count(mysql_num_rows($temp_res)) > 0) {
        while ($temp_row = mysql_fetch_row($temp_res)) {
            $temp_array[] = $temp_row[0];
        }
        $temp_string = implode(",", $temp_array);
        if ($temp_string != "") {
            $sql = "select id2 from gb_office_relation where id1 in(" . $temp_string . ")";
            $temp_res = mysql_query($sql);
            $temp_array = array('0');
            if (count(mysql_num_rows($temp_res)) > 0) {
                while ($temp_row = mysql_fetch_row($temp_res)) {
                    $temp_array[] = $temp_row[0];
                }
                $temp_string = implode(",", $temp_array);
                if ($temp_string != "") {
                    $sql = "select id2 from gb_office_relation where id1 in(" . $temp_string . ")";
                    $temp_res = mysql_query($sql);
                    $temp_array = array('0');
                    if (count(mysql_num_rows($temp_res)) > 0) {
                        while ($temp_row = mysql_fetch_row($temp_res)) {
                            $temp_array[] = $temp_row[0];
                        }
                        $temp_string = implode(",", $temp_array);
                        if ($temp_string != "") {
                            $str.=" and id in (" . $temp_string . ")";
                        }
                    }
                }
            }
        }
    }
    return $str;
}

function getsalesZonSelect($areaId) {
    $sql = "select id2 from gb_office_relation where id1=" . $areaId;
    $temp_res = mysql_query($sql);
    $temp_array = array('0');
    if (count(mysql_num_rows($temp_res)) > 0) {
        while ($temp_row = mysql_fetch_row($temp_res)) {
            $temp_array[] = $temp_row[0];
        }
        $temp_string = implode(",", $temp_array);
        if ($temp_string != "") {
            $str.=" and id in (" . $temp_string . ")";
        }
    }
    mysql_free_result($temp_res);
    return $str;
}

function FunGetSelRs($areaId) {
    $sqlstr = " SELECT id2 FROM gb_office_relation WHERE id1 ='" . $areaId . "'";
    $temp_res = mysql_query($sqlstr);
    $temp_array = array('0');
    $str = "";
    if (count(mysql_num_rows($temp_res)) > 0) {
        while ($temp_row = mysql_fetch_row($temp_res)) {
            $temp_array[] = $temp_row[0];
        }
        $temp_string = implode(",", $temp_array);
        if ($temp_string != "") {
            $sql = "select id  from gb_company_dealer where id!='0' and id in (" . $temp_string . ")";
            $temp_res2 = mysql_query($sql);
            $temp_array = array('0');
            if (count(mysql_num_rows($temp_res2)) > 0) {
                while ($temp_row2 = mysql_fetch_row($temp_res2)) {
                    $temp_array2[] = $temp_row2[0];
                }
                $temp_string = implode(",", $temp_array2);
                if ($temp_string != "") {
                    $str.=" and id in (" . $temp_string . ")";
                }
            }
            mysql_free_result($temp_res2);
        }
    }
    mysql_free_result($temp_res);
    return $str;
}

/////////////////

function FunGetNextrs($dlrid, $condition) {
    if ($dlrid == "") {
        return 0;
    }
    $sqlstr = "SELECT `id` FROM `gb_ada_marks` WHERE `distributor_id` =" . $dlrid . "   and `status`=1 and `gb_type`='" . $condition . "'";

    $temp_res = mysql_query($sqlstr);
    $inrs = mysql_num_rows($temp_res);
    mysql_free_result($temp_res);
    return $inrs;
}

/////////////////////////////////////////
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
    function submitfrm()
    {
        if (document.frmview.state_office.value == "0")
        {
            // alert("Select State office");
            // document.frmview.state_office.focus();
            // return false;
			return true;
        }
    }
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
    function ExportToExcel()
    {
        document.getElementById("fldexporttoexcel").value = document.getElementById("mainsearchtable").innerHTML;
        document.forms["frmexporttoexcel"].submit();
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


        window.location = path + '?sale_zone=<?= $_REQUEST['sale_zone'] ?>&state=<?= $_REQUEST['state'] ?>&city=<?= $_REQUEST['city'] ?>&search=<?= $search ?>&alpha=<?= $alpha ?>&start=' + document.frmview2.pagenumbers.value;

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
            <h1>APA Summary Report  </h1>
        </div>
    </div>
</div>

<div class="innnerpagecontainer">
    <div class="midcontainer">
        <div class="innerpagecontent" style="padding:20px;">
            <div class="panel padding10px">

                <table width="100%">
                    <tr>
                        <td align="left">&nbsp;</td>
                         <td align="right" ><a href="export_area_office_report_1.php?mode=xls"><!--<input type="button" class="mybutton" value="EXPORT TO EXCEL"  />--></a></td>
                    </tr>
                    <tr>
                        <td align="left">&nbsp;</td>
                        <td align="right" >&nbsp;</td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <table bgcolor="#FFFFFF" class="adminlist" width="100%">

                                <tr>

                                    <td width="50%" align="left" valign="top"><table cellpadding="3" cellspacing="0" border="0">
                                    <!--  <tr>
                                        <td nowrap="nowrap" style="border:none;">Name/Code </td>
                                        <td style="border:none;"><input name="search" type="text" id="search" value="<?= @$_REQUEST["search"] ?>" style="width:150px;" />
                                            <input name="showall" type="hidden" id="showall" value="0" />
                                        </td>
                                      </tr>-->
                                        </table>
                                        <form id="frmview" name="frmview" method="get" action=""  onSubmit="">
                                            <table cellpadding="3" cellspacing="0" border="0">
                                                <tr>
                                                    <td nowrap="nowrap" style="border:none;" width="100">Select Year </td>
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
                                                    <td style="border:none;" colspan="2">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td nowrap="nowrap" style="border:none;">State Office</td>
                                                    <td style="border:none;">
                                                        <div class="selectbox" style="float:left; width:250px;">
                                                            <div id="state_office_div">
                                                                <select name="state_office" id="state_office" onchange="get_area_office()" style="width: 250px;">
                                                                    <option value="0" <? if ($_REQUEST['state_office'] == 0 || $_REQUEST['state_office'] == "") {
    echo'selected="selected"';
} ?> >--All--</option>
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
                                                            </div>  </div>            </td>
                                                    <td style="border:none;" colspan="2">
                                                        <img src="../images/ajax_small_load.gif" id="area_office_img" style="visibility:hidden;"  />			  </td>
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
    if ($strStateList == "") {
        $strStateList = FunGetStateList();
    }
    $sql = "select * from gb_offices
							where status=1 and office_type='AO' and id in(" . $strStateList . ") order by office_name";


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
                                                            </div>  </div>            </td>
                                                    <td style="border:none;" colspan="2">
                                                        <img src="../images/ajax_small_load.gif" id="sale_zone_img" style="visibility:hidden;"   />			  </td>
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
    if ($strAOList == "") {
        $strAOList = FunGetAreaOfficeList();
    }
    $sql = "select * from gb_offices where status=1 and office_type='SZ' and id in(" . $strAOList . ") order by office_name";
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
                                                            </div> </div>             </td>

                                                </tr>
                                                <tr>
                                                    <td colspan="2"><input type="submit"  value="Search" class="inputbutton" style="border:none; margin-left:70px; margin-top:0px; margin-right:10px;" onclick="return submitfrm();" /> <input type="submit" name="export"  value="Export" class="inputbutton" style="border:none; margin-left:70px; margin-top:0px; margin-right:10px;"  /><a href="apa_summery_report.php" style="margin-top:5px;">Show All</a>   </td>
                                                </tr>

                                                <tr><td colspan="2">&nbsp;</td></tr>

                                            </table>
                                        </form>
                                    </td>
                                    <td width="50%"  colspan="4" align="left" valign="top">

<?
$strCriteria = "";
if (isset($_REQUEST["state_office"]) && $_REQUEST["state_office"] != "") {
    $sql = "select office_name from gb_offices where status=1 and office_type='SO' and id='" . $_REQUEST["state_office"] . "'";
    $res = mysql_query($sql);
    if (mysql_num_rows($res) > 0) {
        $row = mysql_fetch_array($res);

        $strCriteria = $strCriteria . '<tr>
								<th colspan="2" align="right" >State Office</th>
								<td  colspan="4" align="left" >' . $row["office_name"] . '</td>
							</tr>';
    }
}
if (isset($_REQUEST["area_office"]) && $_REQUEST["area_office"] != "") {
    if ($strStateList == "") {
        $strStateList = FunGetStateList();
    }

    $sql = "select office_name from gb_offices where status=1 and office_type='AO'
														  and id in(" . $strStateList . ") and id='" . $_REQUEST["area_office"] . "'";
    $res = mysql_query($sql);
    if (mysql_num_rows($res) > 0) {
        $row = mysql_fetch_array($res);
        $strCriteria = $strCriteria . '<tr>
									<th colspan="2" align="right" >Area Office</th>
									<td  colspan="4" align="left" >' . $row["office_name"] . '</td>
								</tr>';
    }
}
if (isset($_REQUEST["sale_zone"]) && $_REQUEST["sale_zone"] != "") {
    if ($strAOList == "") {
        $strAOList = FunGetAreaOfficeList();
    }
    $sql = "select office_name from gb_offices where status=1 and
									office_type='SZ' and id in(" . $strAOList . ")  and id='" . $_REQUEST["sale_zone"] . "'";
    $res = mysql_query($sql);
    if (mysql_num_rows($res) > 0) {
        $row = mysql_fetch_array($res);
        $strCriteria = $strCriteria . '<tr>
									<th colspan="2" align="right" >Sales Office</th>
									<td  colspan="4" align="left" >' . $row["office_name"] . '</td>
								</tr>';
    }
}
if ($strCriteria != "") {
    $strCriteria = "<table>" . $strCriteria . "</table>";
}
?>

                                        <div id="errorbox_reg_export" style="visibility:hidden;display:none"></div>
                                        <!--<form name="frmexporttoexcel" id="frmexporttoexcel" action="exporttoexcel.php" target="_blank" method="post" >
                                            <textarea name="fldexporttoexcel2" id="fldexporttoexcel2" style="visibility:hidden;display:none"><?= $strCriteria; ?></textarea>
                                            <textarea name="fldexporttoexcel" id="fldexporttoexcel" style="visibility:hidden;display:none"></textarea>
                                            <a href="javascript:void(0);" onclick="ExportToExcel()" class="click">Export To Excel</a>
                                        </form>-->
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

                                    <td height="48" colspan="5">
                                        <div id="mainsearchtable">
                                            <table width="100%"  cellspacing="0" class="tablecontainer">
											<?php echo getDataForDisplay(1,1); ?>
											
											
                                            </table>
                                        </div>
                                    </td>
                                </tr>



                            </table>
                        </td>
                    </tr>
                    <!--XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX-->

                    <!--XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX-->
                </table>

            </div>
        </div>
    </div>
</div>
                                                                        <?

                                                                        function FunGetStateList() {
                                                                            $StateList = funreaddata("select id2 from gb_office_relation where id1 =" . $_REQUEST["state_office"]);
                                                                            return $StateList;
                                                                        }

                                                                        function FunGetAreaOfficeList() {
                                                                            $List = funreaddata("select id2 from gb_office_relation where id1 =" . $_REQUEST["area_office"]);
                                                                            return $List;
                                                                        }

                                                                        include_once("includes/footer.php");
                                                                        ?>