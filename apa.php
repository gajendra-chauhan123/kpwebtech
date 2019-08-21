<?
include_once("includes/header.php");
ini_set('session.gc_maxlifetime', 1800);
ini_set("session.cookie_lifetime", 1800);
//echo '<pre>';print_r($_REQUEST);exit;
$distributer_status = '';

if (!isset($_REQUEST['arch_year'])) {
    $_REQUEST['arch_year'] = '2016';
}

$str = '';
if ($user['isp_type'] == 'SO') {
	$str .= " and so_id = '" . $area_id . "'";
}
if ($user['isp_type'] == 'AO') {
	$str .= " and ao_id = '" . $area_id . "'";
}
if ($user['isp_type'] == 'SZ') {
	$str .= " and sz_id = '" . $area_id . "'";
}

$selDistName = mysql_query("select dealer_name from gb_company_dealer where id = '" . $_REQUEST['id'] . "' $str") or die(mysql_error());
if(mysql_num_rows($selDistName) <= 0){
	echo '<script>window.location.href="viewemitra.php"</script>';
}
$getDistName = mysql_fetch_array($selDistName);
$dist_info = mysql_query("select *  from gb_ada_marks where distributor_id ='" . $_REQUEST["id"] . "' and gb_type= 'D' and year='" . $_REQUEST['arch_year'] . "' and status='1'");

$num_rows = mysql_num_rows($dist_info);
if ($num_rows > 0) {
    $distributer_status = 1;
}
else {
    $distributer_status = 0;
}

$distributer_record = '';

$dist_record = mysql_query("select *  from gb_ada_marks where distributor_id ='" . $_REQUEST["id"] . "'   and gb_type= 'SZ' and year='" . $_REQUEST['arch_year'] . "'");

$num_records = mysql_num_rows($dist_record);
if ($num_records > 0) {
    $distributer_record = 1;
}
else {
    $distributer_record = 0;
}



if (isset($_POST["mode"]) && $_POST["mode"] == "add") {

    //$fin_year=date('Y',mktime(0, 0, 0, date('m')-3, date('d'),date('y')))-1;
    $fin_year = $_REQUEST['arch_year'];

    $app_1 = trim($_REQUEST["app_1"]);
    $app_2 = trim($_REQUEST["app_2"]);
    $app_3 = trim($_REQUEST["app_3"]);
    $app_4 = trim($_REQUEST["app_4"]);
    $app_5 = trim($_REQUEST["app_5"]);
    $app_6 = trim($_REQUEST["app_6"]);
    $app_7 = trim($_REQUEST["app_7"]);
    $app_8 = trim($_REQUEST["app_8"]);
    $app_9 = trim($_REQUEST["app_9"]);
    $app_10 = trim($_REQUEST["app_10"]);
    $app_11 = trim($_REQUEST["app_11"]);

    $st_licnce_1 = trim($_REQUEST["st_licnce_1"]);
    $st_licnce_2 = trim($_REQUEST["st_licnce_2"]);
    $st_licnce_3 = trim($_REQUEST["st_licnce_3"]);


    $infr_1 = trim($_REQUEST["infr_1"]);
    $infr_2 = trim($_REQUEST["infr_2"]);
    $infr_3 = trim($_REQUEST["infr_3"]);
    $infr_4 = trim($_REQUEST["infr_4"]);
    $infr_5 = trim($_REQUEST["infr_5"]);
    $infr_6 = trim($_REQUEST["infr_6"]);
    $infr_7 = trim($_REQUEST["infr_7"]);


    $godown_1 = trim($_REQUEST["godown_1"]);
    $godown_2 = trim($_REQUEST["godown_2"]);
    $godown_3 = trim($_REQUEST["godown_3"]);
    $godown_4 = trim($_REQUEST["godown_4"]);
    $godown_5 = trim($_REQUEST["godown_5"]);
    $godown_6 = trim($_REQUEST["godown_6"]);
    $godown_7 = trim($_REQUEST["godown_7"]);
    $godown_8 = trim($_REQUEST["godown_8"]);
    $godown_9 = trim($_REQUEST["godown_9"]);
    $godown_10 = trim($_REQUEST["godown_10"]);
    $godown_11 = trim($_REQUEST["godown_11"]);
    $godown_12 = trim($_REQUEST["godown_12"]);
    $godown_13 = trim($_REQUEST["godown_13"]);
    $godown_14 = trim($_REQUEST["godown_14"]);


    $doc_1 = trim($_REQUEST["doc_1"]);
    $doc_2 = trim($_REQUEST["doc_2"]);
    $doc_3 = trim($_REQUEST["doc_3"]);
    $doc_4 = trim($_REQUEST["doc_4"]);
    $doc_5 = trim($_REQUEST["doc_5"]);
    $doc_6 = trim($_REQUEST["doc_6"]);
    $doc_7 = trim($_REQUEST["doc_7"]);
    $doc_8 = trim($_REQUEST["doc_8"]);


    $cust_update_1 = 0;
    $cust_update_2 = trim($_REQUEST["cust_update_2"]);
    $cust_update_3 = 0;
    $cust_update_4 = trim($_REQUEST["cust_update_4"]);


    $ndne_1 = trim($_REQUEST["ndne_1"]);
    $ndne_2 = trim($_REQUEST["ndne_2"]);

    $dom_1 = trim($_REQUEST["dom_1"]);

    $nfr_1 = trim($_REQUEST["nfr_1"]);
    $nfr_2 = trim($_REQUEST["nfr_2"]);

    $nde_1 = trim($_REQUEST["nde_1"]);


    $cust_orientation_1 = trim($_REQUEST["cust_orientation_1"]);
    $cust_orientation_2 = trim($_REQUEST["cust_orientation_2"]);
    $cust_orientation_3 = trim($_REQUEST["cust_orientation_3"]);
    $cust_orientation_4 = trim($_REQUEST["cust_orientation_4"]);
    $cust_orientation_5 = trim($_REQUEST["cust_orientation_5"]);
    $cust_orientation_6 = trim($_REQUEST["cust_orientation_6"]);

    $home_del_1 = trim($_REQUEST["home_del_1"]);
    $home_del_2 = trim($_REQUEST["home_del_2"]);
    $home_del_3 = trim($_REQUEST["home_del_3"]);

    $leakage_1 = trim($_REQUEST["leakage_1"]);
    $leakage_2 = trim($_REQUEST["leakage_2"]);
    $leakage_3 = trim($_REQUEST["leakage_3"]);

    $safety_1 = trim($_REQUEST["safety_1"]);
    $safety_2 = trim($_REQUEST["safety_2"]);
    $safety_3 = trim($_REQUEST["safety_3"]);
    $safety_4 = trim($_REQUEST["safety_4"]);

    $mang_1 = trim($_REQUEST["mang_1"]);
    $mang_2 = trim($_REQUEST["mang_2"]);
    $mang_3 = trim($_REQUEST["mang_3"]);

    $rmdg_1 = trim($_REQUEST["rmdg_1"]);
    $rmdg_2 = trim($_REQUEST["rmdg_2"]);
    $rmdg_3 = trim($_REQUEST["rmdg_3"]);
    $rmdg_4 = trim($_REQUEST["rmdg_4"]);

    $attitude = trim($_REQUEST["attitude"]);

    $inovative_init = trim($_REQUEST["inovative_init"]);


    $sql = mysql_query("select * from gb_ada_marks where gb_type='SZ' and distributor_id='" . $_REQUEST["id"] . "' and year='" . $fin_year . "'");

    $cnt_d = mysql_num_rows($sql);

    if ($cnt_d > 0) {

        $sql = "update gb_ada_marks set
					app_1='" . $app_1 . "',
					app_2='" . $app_2 . "',
					app_3='" . $app_3 . "',
					app_4='" . $app_4 . "',
					app_5='" . $app_5 . "',
					app_6='" . $app_6 . "',
					app_7='" . $app_7 . "',
					app_8='" . $app_8 . "',
					app_9='" . $app_9 . "',
					app_10='" . $app_10 . "',
					app_11='" . $app_11 . "',
					st_licnce_1='" . $st_licnce_1 . "',
					st_licnce_2='" . $st_licnce_2 . "',
					st_licnce_3='" . $st_licnce_3 . "',
					infr_1='" . $infr_1 . "',
					infr_2='" . $infr_2 . "',
					infr_3='" . $infr_3 . "',
					infr_4='" . $infr_4 . "',
					infr_5='" . $infr_5 . "',
					infr_6='" . $infr_6 . "',
					infr_7='" . $infr_7 . "',
					godown_1='" . $godown_1 . "',
					godown_2='" . $godown_2 . "',
					godown_3='" . $godown_3 . "',
					godown_4='" . $godown_4 . "',
					godown_5='" . $godown_5 . "',
					godown_6='" . $godown_6 . "',
					godown_7='" . $godown_7 . "',
					godown_8='" . $godown_8 . "',
					godown_9='" . $godown_9 . "',
					godown_10='" . $godown_10 . "',
					godown_11='" . $godown_1 . "',
					godown_12='" . $godown_12 . "',
					godown_13='" . $godown_13 . "',
					godown_14='" . $godown_14 . "',
					doc_1='" . $doc_1 . "',
					doc_2='" . $doc_2 . "',
					doc_3='" . $doc_3 . "',
					doc_4='" . $doc_4 . "',
					doc_5='" . $doc_5 . "',
					doc_6='" . $doc_6 . "',
					doc_7='" . $doc_7 . "',
					doc_8='" . $doc_8 . "',
					cust_update_1='" . $cust_update_1 . "',
					cust_update_2='" . $cust_update_2 . "',
					cust_update_3='" . $cust_update_3 . "',
					cust_update_4='" . $cust_update_4 . "',
					ndne_1='" . $ndne_1 . "',
					ndne_2='" . $ndne_2 . "',
					dom_1='" . $dom_1 . "',
					nfr_1='" . $nfr_1 . "',
					nfr_2='" . $nfr_2 . "',
					nde_1='" . $nde_1 . "',
					cust_orientation_1='" . $cust_orientation_1 . "',
					cust_orientation_2='" . $cust_orientation_2 . "',
					cust_orientation_3='" . $cust_orientation_3 . "',
					cust_orientation_4='" . $cust_orientation_4 . "',
					cust_orientation_5='" . $cust_orientation_5 . "',
					cust_orientation_6='" . $cust_orientation_6 . "',
					home_del_1='" . $home_del_1 . "',
					home_del_2='" . $home_del_2 . "',
					home_del_3='" . $home_del_3 . "',
					leakage_1='" . $leakage_1 . "',
					leakage_2='" . $leakage_2 . "',
					leakage_3='" . $leakage_3 . "',
					safety_1='" . $safety_1 . "',
					safety_2='" . $safety_2 . "',
					safety_3='" . $safety_3 . "',
					safety_4='" . $safety_4 . "',
					mang_1='" . $mang_1 . "',
					mang_2='" . $mang_2 . "',
					mang_3='" . $mang_3 . "',
					rmdg_1='" . $rmdg_1 . "',
					rmdg_2='" . $rmdg_2 . "',
					rmdg_3='" . $rmdg_3 . "',
					rmdg_4='" . $rmdg_4 . "',
					attitude='" . $attitude . "',
					inovative_init='" . $inovative_init . "'
					where gb_type='SZ' and distributor_id='" . $_REQUEST["id"] . "' and year='" . $fin_year . "' ";
        mysql_query($sql);
    }
    else {

        $sql = "insert into gb_ada_marks set
				    gb_type='SZ',
					distributor_id='" . $_REQUEST["id"] . "',
					examiner_id='" . $_SESSION["lspid"] . "',
					app_1='" . $app_1 . "',
					app_2='" . $app_2 . "',
					app_3='" . $app_3 . "',
					app_4='" . $app_4 . "',
					app_5='" . $app_5 . "',
					app_6='" . $app_6 . "',
					app_7='" . $app_7 . "',
					app_8='" . $app_8 . "',
					app_9='" . $app_9 . "',
					app_10='" . $app_10 . "',
					app_11='" . $app_11 . "',
					st_licnce_1='" . $st_licnce_1 . "',
					st_licnce_2='" . $st_licnce_2 . "',
					st_licnce_3='" . $st_licnce_3 . "',
					infr_1='" . $infr_1 . "',
					infr_2='" . $infr_2 . "',
					infr_3='" . $infr_3 . "',
					infr_4='" . $infr_4 . "',
					infr_5='" . $infr_5 . "',
					infr_6='" . $infr_6 . "',
					infr_7='" . $infr_7 . "',
					godown_1='" . $godown_1 . "',
					godown_2='" . $godown_2 . "',
					godown_3='" . $godown_3 . "',
					godown_4='" . $godown_4 . "',
					godown_5='" . $godown_5 . "',
					godown_6='" . $godown_6 . "',
					godown_7='" . $godown_7 . "',
					godown_8='" . $godown_8 . "',
					godown_9='" . $godown_9 . "',
					godown_10='" . $godown_10 . "',
					godown_11='" . $godown_1 . "',
					godown_12='" . $godown_12 . "',
					godown_13='" . $godown_13 . "',
					godown_14='" . $godown_14 . "',
					doc_1='" . $doc_1 . "',
					doc_2='" . $doc_2 . "',
					doc_3='" . $doc_3 . "',
					doc_4='" . $doc_4 . "',
					doc_5='" . $doc_5 . "',
					doc_6='" . $doc_6 . "',
					doc_7='" . $doc_7 . "',
					doc_8='" . $doc_8 . "',
					cust_update_1='" . $cust_update_1 . "',
					cust_update_2='" . $cust_update_2 . "',
					cust_update_3='" . $cust_update_3 . "',
					cust_update_4='" . $cust_update_4 . "',
					ndne_1='" . $ndne_1 . "',
					ndne_2='" . $ndne_2 . "',
					dom_1='" . $dom_1 . "',
					nfr_1='" . $nfr_1 . "',
					nfr_2='" . $nfr_2 . "',
					nde_1='" . $nde_1 . "',
					cust_orientation_1='" . $cust_orientation_1 . "',
					cust_orientation_2='" . $cust_orientation_2 . "',
					cust_orientation_3='" . $cust_orientation_3 . "',
					cust_orientation_4='" . $cust_orientation_4 . "',
					cust_orientation_5='" . $cust_orientation_5 . "',
					cust_orientation_6='" . $cust_orientation_6 . "',
					home_del_1='" . $home_del_1 . "',
					home_del_2='" . $home_del_2 . "',
					home_del_3='" . $home_del_3 . "',
					leakage_1='" . $leakage_1 . "',
					leakage_2='" . $leakage_2 . "',
					leakage_3='" . $leakage_3 . "',
					safety_1='" . $safety_1 . "',
					safety_2='" . $safety_2 . "',
					safety_3='" . $safety_3 . "',
					safety_4='" . $safety_4 . "',
					mang_1='" . $mang_1 . "',
					mang_2='" . $mang_2 . "',
					mang_3='" . $mang_3 . "',
					rmdg_1='" . $rmdg_1 . "',
					rmdg_2='" . $rmdg_2 . "',
					rmdg_3='" . $rmdg_3 . "',
					rmdg_4='" . $rmdg_4 . "',
					attitude='" . $attitude . "',
					inovative_init='" . $inovative_init . "',
					year='" . $fin_year . "'";
        mysql_query($sql);
    }


    $app_remark_1 = trim($_REQUEST["app_remark_1"]);
    $app_remark_2 = trim($_REQUEST["app_remark_2"]);
    $app_remark_3 = trim($_REQUEST["app_remark_3"]);
    $app_remark_4 = trim($_REQUEST["app_remark_4"]);
    $app_remark_5 = trim($_REQUEST["app_remark_5"]);
    $app_remark_6 = trim($_REQUEST["app_remark_6"]);
    $app_remark_7 = trim($_REQUEST["app_remark_7"]);
    $app_remark_8 = trim($_REQUEST["app_remark_8"]);
    $app_remark_9 = trim($_REQUEST["app_remark_9"]);
    $app_remark_10 = trim($_REQUEST["app_remark_10"]);
    $app_remark_11 = trim($_REQUEST["app_remark_11"]);

    $st_licnce_remark_1 = trim($_REQUEST["lcnce_remark_1"]);
    $st_licnce_remark_2 = trim($_REQUEST["lcnce_remark_2"]);
    $st_licnce_remark_3 = trim($_REQUEST["lcnce_remark_3"]);


    $infr_remark_1 = trim($_REQUEST["infr_remarks_1"]);
    $infr_remark_2 = trim($_REQUEST["infr_remarks_2"]);
    $infr_remark_3 = trim($_REQUEST["infr_remarks_3"]);
    $infr_remark_4 = trim($_REQUEST["infr_remarks_4"]);
    $infr_remark_5 = trim($_REQUEST["infr_remarks_5"]);
    $infr_remark_6 = trim($_REQUEST["infr_remarks_6"]);
    $infr_remark_7 = trim($_REQUEST["infr_remarks_7"]);


    $godown_remark_1 = trim($_REQUEST["gdwn_remark_1"]);
    $godown_remark_2 = trim($_REQUEST["gdwn_remark_2"]);
    $godown_remark_3 = trim($_REQUEST["gdwn_remark_3"]);
    $godown_remark_4 = trim($_REQUEST["gdwn_remark_4"]);
    $godown_remark_5 = trim($_REQUEST["gdwn_remark_5"]);
    $godown_remark_6 = trim($_REQUEST["gdwn_remark_6"]);
    $godown_remark_7 = trim($_REQUEST["gdwn_remark_7"]);
    $godown_remark_8 = trim($_REQUEST["gdwn_remark_8"]);
    $godown_remark_9 = trim($_REQUEST["gdwn_remark_9"]);
    $godown_remark_10 = trim($_REQUEST["gdwn_remark_10"]);
    $godown_remark_11 = trim($_REQUEST["gdwn_remark_11"]);
    $godown_remark_12 = trim($_REQUEST["gdwn_remark_12"]);
    $godown_remark_13 = trim($_REQUEST["gdwn_remark_13"]);
    $godown_remark_14 = trim($_REQUEST["gdwn_remark_14"]);


    $doc_remark_1 = trim($_REQUEST["doc_remark_1"]);
    $doc_remark_2 = trim($_REQUEST["doc_remark_2"]);
    $doc_remark_3 = trim($_REQUEST["doc_remark_3"]);
    $doc_remark_4 = trim($_REQUEST["doc_remark_4"]);
    $doc_remark_5 = trim($_REQUEST["doc_remark_5"]);
    $doc_remark_6 = trim($_REQUEST["doc_remark_6"]);
    $doc_remark_7 = trim($_REQUEST["doc_remark_7"]);
    $doc_remark_8 = trim($_REQUEST["doc_remark_8"]);


    $cust_update_remark_1 = trim($_REQUEST["cust_update_remark_1"]);
    $cust_update_remark_2 = trim($_REQUEST["update_remark_2"]);
    $cust_update_remark_3 = trim($_REQUEST["cust_update_remark_3"]);
    $cust_update_remark_4 = trim($_REQUEST["update_remark_4"]);


    $ndne_remark_1 = trim($_REQUEST["ndne_remark_1"]);
    $ndne_remark_2 = trim($_REQUEST["ndne_remark_2"]);

    $dom_remark_1 = trim($_REQUEST["dom_remark_1"]);

    $nfr_remark_1 = trim($_REQUEST["nfr_remark_1"]);
    $nfr_remark_2 = trim($_REQUEST["nfr_remark_2"]);

    $nde_remark_1 = trim($_REQUEST["nde_remark_1"]);


    $cust_orientation_remark_1 = trim($_REQUEST["orient_remark_1"]);
    $cust_orientation_remark_2 = trim($_REQUEST["orient_remark_2"]);
    $cust_orientation_remark_3 = trim($_REQUEST["orient_remark_3"]);
    $cust_orientation_remark_4 = trim($_REQUEST["orient_remark_4"]);
    $cust_orientation_remark_5 = trim($_REQUEST["orient_remark_5"]);
    $cust_orientation_remark_6 = trim($_REQUEST["orient_remark_6"]);

    $home_del_remark_1 = trim($_REQUEST["home_remark_1"]);
    $home_del_remark_2 = trim($_REQUEST["home_remark_2"]);
    $home_del_remark_3 = trim($_REQUEST["home_remark_3"]);

    $leakage_remark_1 = trim($_REQUEST["leakage_remark_1"]);
    $leakage_remark_2 = trim($_REQUEST["leakage_remark_2"]);
    $leakage_remark_3 = trim($_REQUEST["leakage_remark_3"]);

    $safety_remark_1 = trim($_REQUEST["safety_remark_1"]);
    $safety_remark_2 = trim($_REQUEST["safety_remark_2"]);
    $safety_remark_3 = trim($_REQUEST["safety_remark_3"]);
    $safety_remark_4 = trim($_REQUEST["safety_remark_4"]);

    $mang_remark_1 = trim($_REQUEST["mang_remark_1"]);
    $mang_remark_2 = trim($_REQUEST["mang_remark_2"]);
    $mang_remark_3 = trim($_REQUEST["mang_remark_3"]);

    $rmdg_remark_1 = trim($_REQUEST["rmdg_remark_1"]);
    $rmdg_remark_2 = trim($_REQUEST["rmdg_remark_2"]);
    $rmdg_remark_3 = trim($_REQUEST["rmdg_remark_3"]);
    $rmdg_remark_4 = trim($_REQUEST["rmdg_remark_4"]);

    $attitude_remark = trim($_REQUEST["attitude_remark"]);

    $inov_remark = trim($_REQUEST["inov_remark"]);




    $sql_re = mysql_query("select * from gb_ada_remarks where gb_type='SZ' and distributor_id='" . $_REQUEST["id"] . "' and year='" . $fin_year . "' ");

    $cnt_re = mysql_num_rows($sql_re);

    if ($cnt_re > 0) {

        $sql = "update gb_ada_remarks set
					app_remark_1='" . $app_remark_1 . "',
					app_remark_2='" . $app_remark_2 . "',
					app_remark_3='" . $app_remark_3 . "',
					app_remark_4='" . $app_remark_4 . "',
					app_remark_5='" . $app_remark_5 . "',
					app_remark_6='" . $app_remark_6 . "',
					app_remark_7='" . $app_remark_7 . "',
					app_remark_8='" . $app_remark_8 . "',
					app_remark_9='" . $app_remark_9 . "',
					app_remark_10='" . $app_remark_10 . "',
					app_remark_11='" . $app_remark_11 . "',
					st_licnce_remark_1='" . $st_licnce_remark_1 . "',
					st_licnce_remark_2='" . $st_licnce_remark_2 . "',
					st_licnce_remark_3='" . $st_licnce_remark_3 . "',
					infr_remark_1='" . $infr_remark_1 . "',
					infr_remark_2='" . $infr_remark_2 . "',
					infr_remark_3='" . $infr_remark_3 . "',
					infr_remark_4='" . $infr_remark_4 . "',
					infr_remark_5='" . $infr_remark_5 . "',
					infr_remark_6='" . $infr_remark_6 . "',
					infr_remark_7='" . $infr_remark_7 . "',
					godown_remark_1='" . $godown_remark_1 . "',
					godown_remark_2='" . $godown_remark_2 . "',
					godown_remark_3='" . $godown_remark_3 . "',
					godown_remark_4='" . $godown_remark_4 . "',
					godown_remark_5='" . $godown_remark_5 . "',
					godown_remark_6='" . $godown_remark_6 . "',
					godown_remark_7='" . $godown_remark_7 . "',
					godown_remark_8='" . $godown_remark_8 . "',
					godown_remark_9='" . $godown_remark_9 . "',
					godown_remark_10='" . $godown_remark_10 . "',
					godown_remark_11='" . $godown_remark_1 . "',
					godown_remark_12='" . $godown_remark_12 . "',
					godown_remark_13='" . $godown_remark_13 . "',
					godown_remark_14='" . $godown_remark_14 . "',
					doc_remark_1='" . $doc_remark_1 . "',
					doc_remark_2='" . $doc_remark_2 . "',
					doc_remark_3='" . $doc_remark_3 . "',
					doc_remark_4='" . $doc_remark_4 . "',
					doc_remark_5='" . $doc_remark_5 . "',
					doc_remark_6='" . $doc_remark_6 . "',
					doc_remark_7='" . $doc_remark_7 . "',
					doc_remark_8='" . $doc_remark_8 . "',
					cust_update_remark_1='" . $cust_update_remark_1 . "',
					cust_update_remark_2='" . $cust_update_remark_2 . "',
					cust_update_remark_3='" . $cust_update_remark_3 . "',
					cust_update_remark_4='" . $cust_update_remark_4 . "',
					ndne_remark_1='" . $ndne_remark_1 . "',
					ndne_remark_2='" . $ndne_remark_2 . "',
					dom_remark_1='" . $dom_remark_1 . "',
					nfr_remark_1='" . $nfr_remark_1 . "',
					nfr_remark_2='" . $nfr_remark_2 . "',
					nde_remark_1='" . $nde_remark_1 . "',
					cust_orientation_remark_1='" . $cust_orientation_remark_1 . "',
					cust_orientation_remark_2='" . $cust_orientation_remark_2 . "',
					cust_orientation_remark_3='" . $cust_orientation_remark_3 . "',
					cust_orientation_remark_4='" . $cust_orientation_remark_4 . "',
					cust_orientation_remark_5='" . $cust_orientation_remark_5 . "',
					cust_orientation_remark_6='" . $cust_orientation_remark_6 . "',
					home_del_remark_1='" . $home_del_remark_1 . "',
					home_del_remark_2='" . $home_del_remark_2 . "',
					home_del_remark_3='" . $home_del_remark_3 . "',
					leakage_remark_1='" . $leakage_remark_1 . "',
					leakage_remark_2='" . $leakage_remark_2 . "',
					leakage_remark_3='" . $leakage_remark_3 . "',
					safety_remark_1='" . $safety_remark_1 . "',
					safety_remark_2='" . $safety_remark_2 . "',
					safety_remark_3='" . $safety_remark_3 . "',
					safety_remark_4='" . $safety_remark_4 . "',
					mang_remark_1='" . $mang_remark_1 . "',
					mang_remark_2='" . $mang_remark_2 . "',
					mang_remark_3='" . $mang_remark_3 . "',
					rmdg_remark_1='" . $rmdg_remark_1 . "',
					rmdg_remark_2='" . $rmdg_remark_2 . "',
					rmdg_remark_3='" . $rmdg_remark_3 . "',
					rmdg_remark_4='" . $rmdg_remark_4 . "',
					attitude_remark ='" . $attitude_remark . "',
					inov_remark='" . $inov_remark . "'
					where gb_type='SZ' and distributor_id='" . $_REQUEST["id"] . "' and year='" . $fin_year . "' ";
        mysql_query($sql);
    }
    else {

        $sql = "insert into gb_ada_remarks set
				    gb_type='SZ',
					distributor_id='" . $_REQUEST["id"] . "',
					examiner_id='" . $_SESSION["lspid"] . "',
					app_remark_1='" . $app_remark_1 . "',
					app_remark_2='" . $app_remark_2 . "',
					app_remark_3='" . $app_remark_3 . "',
					app_remark_4='" . $app_remark_4 . "',
					app_remark_5='" . $app_remark_5 . "',
					app_remark_6='" . $app_remark_6 . "',
					app_remark_7='" . $app_remark_7 . "',
					app_remark_8='" . $app_remark_8 . "',
					app_remark_9='" . $app_remark_9 . "',
					app_remark_10='" . $app_remark_10 . "',
					app_remark_11='" . $app_remark_11 . "',
					st_licnce_remark_1='" . $st_licnce_remark_1 . "',
					st_licnce_remark_2='" . $st_licnce_remark_2 . "',
					st_licnce_remark_3='" . $st_licnce_remark_3 . "',
					infr_remark_1='" . $infr_remark_1 . "',
					infr_remark_2='" . $infr_remark_2 . "',
					infr_remark_3='" . $infr_remark_3 . "',
					infr_remark_4='" . $infr_remark_4 . "',
					infr_remark_5='" . $infr_remark_5 . "',
					infr_remark_6='" . $infr_remark_6 . "',
					infr_remark_7='" . $infr_remark_7 . "',
					godown_remark_1='" . $godown_remark_1 . "',
					godown_remark_2='" . $godown_remark_2 . "',
					godown_remark_3='" . $godown_remark_3 . "',
					godown_remark_4='" . $godown_remark_4 . "',
					godown_remark_5='" . $godown_remark_5 . "',
					godown_remark_6='" . $godown_remark_6 . "',
					godown_remark_7='" . $godown_remark_7 . "',
					godown_remark_8='" . $godown_remark_8 . "',
					godown_remark_9='" . $godown_remark_9 . "',
					godown_remark_10='" . $godown_remark_10 . "',
					godown_remark_11='" . $godown_remark_1 . "',
					godown_remark_12='" . $godown_remark_12 . "',
					godown_remark_13='" . $godown_remark_13 . "',
					godown_remark_14='" . $godown_remark_14 . "',
					doc_remark_1='" . $doc_remark_1 . "',
					doc_remark_2='" . $doc_remark_2 . "',
					doc_remark_3='" . $doc_remark_3 . "',
					doc_remark_4='" . $doc_remark_4 . "',
					doc_remark_5='" . $doc_remark_5 . "',
					doc_remark_6='" . $doc_remark_6 . "',
					doc_remark_7='" . $doc_remark_7 . "',
					doc_remark_8='" . $doc_remark_8 . "',
					cust_update_remark_1='" . $cust_update_remark_1 . "',
					cust_update_remark_2='" . $cust_update_remark_2 . "',
					cust_update_remark_3='" . $cust_update_remark_3 . "',
					cust_update_remark_4='" . $cust_update_remark_4 . "',
					ndne_remark_1='" . $ndne_remark_1 . "',
					ndne_remark_2='" . $ndne_remark_2 . "',
					dom_remark_1='" . $dom_remark_1 . "',
					nfr_remark_1='" . $nfr_remark_1 . "',
					nfr_remark_2='" . $nfr_remark_2 . "',
					nde_remark_1='" . $nde_remark_1 . "',
					cust_orientation_remark_1='" . $cust_orientation_remark_1 . "',
					cust_orientation_remark_2='" . $cust_orientation_remark_2 . "',
					cust_orientation_remark_3='" . $cust_orientation_remark_3 . "',
					cust_orientation_remark_4='" . $cust_orientation_remark_4 . "',
					cust_orientation_remark_5='" . $cust_orientation_remark_5 . "',
					cust_orientation_remark_6='" . $cust_orientation_remark_6 . "',
					home_del_remark_1='" . $home_del_remark_1 . "',
					home_del_remark_2='" . $home_del_remark_2 . "',
					home_del_remark_3='" . $home_del_remark_3 . "',
					leakage_remark_1='" . $leakage_remark_1 . "',
					leakage_remark_2='" . $leakage_remark_2 . "',
					leakage_remark_3='" . $leakage_remark_3 . "',
					safety_remark_1='" . $safety_remark_1 . "',
					safety_remark_2='" . $safety_remark_2 . "',
					safety_remark_3='" . $safety_remark_3 . "',
					safety_remark_4='" . $safety_remark_4 . "',
					mang_remark_1='" . $mang_remark_1 . "',
					mang_remark_2='" . $mang_remark_2 . "',
					mang_remark_3='" . $mang_remark_3 . "',
					rmdg_remark_1='" . $rmdg_remark_1 . "',
					rmdg_remark_2='" . $rmdg_remark_2 . "',
					rmdg_remark_3='" . $rmdg_remark_3 . "',
					rmdg_remark_4='" . $rmdg_remark_4 . "',
					attitude_remark ='" . $attitude_remark . "',
					inov_remark='" . $inov_remark . "',
					year='" . $fin_year . "'";
        mysql_query($sql);
    }
}

if (isset($_POST["disablestatus"]) && $_POST["disablestatus"] != '') {

    //$fin_year=date('Y',mktime(0, 0, 0, date('m')-3, date('d'),date('y')))-1;
    $fin_year = $_REQUEST['arch_year'];

    mysql_query("update gb_ada_marks set status='" . $_POST["disablestatus"] . "' where distributor_id ='" . $_REQUEST["id"] . "' and gb_type='SZ'  and year='" . $fin_year . "' ");
}
?>
<style type="text/css">
    #onlineadvertise
    {
        float:left;

        width:535px;
        height:auto;
        position:absolute;
        top:0px;
        right:0px;

        z-index:999999999;
        /*margin-left:100px;*/
        background:#ffffff;
        margin-top:-500px;
    }

    #onlineadvertise td
    {
        background-color:#FFFFFF;
        font-size:10px;
        font-family:Arial,Helvetica,sans-serif;
    }
    .center
    {text-align:right;
    }

    .div_bordr
    {
        width:900px;
    }


    .first
    {
        display:none;
        padding:5px;
        border:1px solid #CCCCCC;
    }
    #title_padding
    {padding-left:10px;
    }
    .bck_color
    {
        background-color:#CCCCCC;
        border:#FFFFFF 1px solid;
        padding-top:5px;
        padding-bottom:5px;
        padding-left:20px;
        cursor:pointer;
        font-size:12px;
        /*background-image:url(../images/plus.gif);*/
        background-image:url(../images/minus.gif);
        background-repeat:no-repeat;
        /* background-position:840px;*/
        background-position:5px;
    }

    .bck_color_expand
    {
        background-color:#CCCCCC;
        border:#FFFFFF 1px solid;
        padding-top:5px;
        padding-bottom:5px;
        padding-left:20px;
        cursor:pointer;
        font-size:12px;
        /* background-image:url(../images/minus.gif);*/
        background-image:url(../images/plus.gif);
        background-repeat:no-repeat;
        /*background-position:840px;*/
        background-position:5px;

    }

    INPUT{
        text-align:right;
    }


</style>
<script type="text/javascript">
    $(document).ready(function () {
        $("#one").click(function () {
            if ($("#shroom_list").is(":hidden"))
            {

                $("#shroom_list").show('slow', function () {
                });

                $('#one').addClass('bck_color');
                $('#one').removeClass('bck_color_expand');

            }
            else
            {

                $("#shroom_list").hide('slow', function () {
                });
                $('#one').removeClass('bck_color');
                $('#one').addClass('bck_color_expand');

            }
        });

        $("#two").click(function () {
            if ($("#opr_distr_list").is(":hidden"))
            {
                $("#opr_distr_list").show('slow', function () {
                });
                $('#two').addClass('bck_color');
                $('#two').removeClass('bck_color_expand');
            }
            else
            {
                $("#opr_distr_list").hide('slow', function () {
                });
                $('#two').removeClass('bck_color');
                $('#two').addClass('bck_color_expand');
            }
        });

        $("#three").click(function () {
            if ($("#sales_list").is(":hidden"))
            {
                $("#sales_list").show('slow', function () {
                });
                $('#three').addClass('bck_color');
                $('#three').removeClass('bck_color_expand');
            }
            else
            {
                $("#sales_list").hide('slow', function () {
                });
                $('#three').removeClass('bck_color');
                $('#three').addClass('bck_color_expand');
            }
        });
        $("#four").click(function () {
            if ($("#attitude_list").is(":hidden"))
            {
                $("#attitude_list").show('slow', function () {
                });
                $('#four').addClass('bck_color');
                $('#four').removeClass('bck_color_expand');
            }
            else
            {
                $("#attitude_list").hide('slow', function () {
                });
                $('#four').removeClass('bck_color');
                $('#four').addClass('bck_color_expand');
            }
        });
        $("#five").click(function () {
            if ($("#inov_list").is(":hidden"))
            {
                $("#inov_list").show('slow', function () {
                });
                $('#five').addClass('bck_color');
                $('#five').removeClass('bck_color_expand');
            }
            else {
                $("#inov_list").hide('slow', function () {
                });
                $('#five').removeClass('bck_color');
                $('#five').addClass('bck_color_expand');
            }
        });
    });
	
	 function validationform()
    {
		var allow_submit = 1;
		jQuery("form#apaform")
		jQuery('form#apaform input:not(.areaoremark)').each(function(index){  
			var input = jQuery(this);
			if (input.attr('type') == "text" && input.val() == "" && input.attr("readonly")!="readonly" ) 
			{
				allow_submit = 0;
			}
		});
		
		
		
		if (allow_submit == 0) 
		{
			alert("Your APA form is not completed yet.Please fill all the marks and press save button.");
            return false;
		}
		else {
			return true;
		}

		/*
        var txtObjList = document.getElementsByTagName("input");
        var val = 0;
        for (var i = 0; i < txtObjList.length; i++) {
            if (txtObjList[i].getAttribute("type") == "text" && txtObjList[i].getAttribute("readonly") != "readonly") {
				
                if (document.getElementById(txtObjList[i].getAttribute("id")).value != "") {
                    val = 1;
                }
                else
                {
					alert(document.getElementById(txtObjList[i].getAttribute("id")).name);
					alert(document.getElementById(txtObjList[i].getAttribute("id")).value);
                    alert("Your APA form is not completed yet.Please fill all the marks and press save button.");
                    return false;
                }
            }
        }
        if (val == 1)
            return true;
		*/

    }

   /* function validationform()
    {
        var txtObjList = document.getElementsByTagName("input");
        var val = 0;
        for (var i = 0; i < txtObjList.length; i++) {
            if (txtObjList[i].getAttribute("type") == "text" && txtObjList[i].getAttribute("readonly") != "readonly") {
                if (document.getElementById(txtObjList[i].getAttribute("id")).value != "") {
                    val = 1;
                }
                else
                {
                    //alert("Your APA form is not completed yet.Please fill all the marks and press save button.");
                    //return false ;
                    val = 1;
                }
            }
        }
        if (val == 1)
            return true;

    } */

    function validate(key)
    {
        var keycode = (key.which) ? key.which : key.keyCode;
        //alert(keycode);
        if (keycode == 47)
        {
            return false;
        }
        else if ((keycode > 44 && keycode < 58) || (keycode == 8) || (keycode == 9))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function ck_value(id, num)
    {
        //alert(id);

        if (num < 0)
        {
            if ((document.getElementById(id).value != num) && (document.getElementById(id).value != 0))
            {
                alert('Please Enter correct value');
                window.setTimeout(function () {
                    document.getElementById(id).focus();
                }, 0);
                document.getElementById(id).value = '';
            }

        }
        else
        {
            if (document.getElementById(id).value > num) {
                alert('Enter Value Less Than or Equal To Marks');
                window.setTimeout(function () {
                    document.getElementById(id).focus();
                }, 0);
                document.getElementById(id).value = '';
            }
        }
    }

    function all_app_total(str, total)
    {
        var sum = 0;
        //alert(str);
        //alert(total);
        for (i = 1; i <= total; i++)
        {
            //alert(document.getElementById(str+i).value);
            if (document.getElementById(str + i).value == '')
            {
                continue;
            }
            else
            {
                sum = sum + parseInt(document.getElementById(str + i).value);
            }
        }
        document.getElementById(str + 'total').value = sum;
    }
    function ck_value_range(range1, range2)
    {

        if ((document.getElementById('rmdg_1').value < range1) || (document.getElementById('rmdg_1').value > range2))
        {
            alert('Eneter Value in range');
            document.getElementById('rmdg_1').value = '';
            window.setTimeout(function () {
                document.getElementById(id).focus();
            }, 0);

        }
    }



    function submit_form(val) {

        var aa = confirm('This  will save the values given by you in the form and will freeze it. You will not be able to edit and continue filling this form once freezed by you.');
        if (aa)
        {
            document.getElementById('disablestatus').value = val;
            var check = validationform();
            if (check == true)
                document.freezeform.submit();
        }
        else
        {
            return false;
        }
    }

    /*if(val==1){
     var aa=confirm('Are you sure, you want to freeze this form ?');
     }else{

     var aa=confirm('Are you sure, you want to unfreeze this form again ?');
     }

     if(aa){

     document.getElementById('disablestatus').value=val;

     document.freezeform.submit();
     }

     }*/
    function getarchyear() {

//alert("year");
        document.archiveddata.submit();
    }
</script>

<?
$readonly = '';
$nodeditable = 0;
//$fin_year=date('Y',mktime(0, 0, 0, date('m')-3, date('d'),date('y')))-1;
$fin_year = $_REQUEST['arch_year'];

if (@$_REQUEST['arch_year'] != 0 && @$_REQUEST['arch_year'] != '' && @$_REQUEST['arch_year'] != $fin_year) {

    $fin_year = $_REQUEST['arch_year'];
    $fin_year_add = substr($_REQUEST['arch_year'], 2, 2);

    //$readonly='readonly';

    $nodeditable = 1;
}
else {

    //$fin_year=date('Y',mktime(0, 0, 0, date('m')-3, date('d'),date('y')))-1;
    $fin_year = $_REQUEST['arch_year'];

    $fin_year_add = date('y', mktime(0, 0, 0, date('m') - 3, date('d'), date('y')));
}
?>
<div class="innerheadercontainer2">
    <!--Bread Crame Start here-->
    <div class="borderbottom"></div>

    <!--Bread Crame End here-->
    <div class="midcontainer innerheadermain">
        <div class="innerfortunelogo3"><img src="<?= SERVER_URL ?>images/fortunelogo.png"  alt="Fortune Logo" /></div>
        <div class="innerheaderleft">
            <h1>Annual Performance Appraisal</h1>
        </div>
    </div>
</div>

<div class="clear" style="height:20px;"></div>

<div class="innnerpagecontainer">
    <div class="midcontainer">
        <div class="innerpagecontent" style="padding:20px;">

            <div>
                <h3 class="heading1"><!--Distributor--><?= $getDistName['dealer_name'] ?> Annual Performance Appraisal FORM  - <?php echo $fin_year; ?>-<?php echo $fin_year + 1; ?> </h3>
                <div class="panel padding30px"  style="width:910px;">
<?php
$sql_admin = mysql_query("select * from gb_ada_marks where id='1'");

$row_1 = mysql_fetch_assoc($sql_admin);



//echo $fin_year;

$sql_disable = mysql_query("select * from gb_ada_marks where distributor_id='" . $_REQUEST["id"] . "' and status='1' and gb_type='SZ' and year='" . $fin_year . "'");

$cnt_dis = mysql_num_rows($sql_disable);

if ($cnt_dis > 0) {
    // $readonly='readonly';
}



$sql = mysql_query("select *,sum(app_1 + app_2+ app_3+ app_4+ app_5+ app_6+ app_7+ app_8+ app_9+ app_10+ app_11+ st_licnce_1+ st_licnce_2+ st_licnce_3+ infr_1+ infr_2+ infr_3+ infr_4+ infr_5+ infr_6+ infr_7+ godown_1+  godown_2+  godown_3+ godown_4+ godown_5+ godown_6+ godown_7+ godown_8+ godown_9+ godown_10+ godown_1+ godown_12+ godown_13+ godown_14+ doc_1+ doc_2+ doc_3+ doc_4+ doc_5+  doc_6+ doc_7+ doc_8+ cust_update_1+ cust_update_2+ cust_update_3+ cust_update_4+ ndne_1+ ndne_2+ dom_1+ nfr_1+ nfr_2+ nde_1+ cust_orientation_1+ cust_orientation_2+ cust_orientation_3+ cust_orientation_4+ cust_orientation_5+ cust_orientation_6+ home_del_1+ home_del_2+ home_del_3+ leakage_1+ leakage_2+ leakage_3+ safety_1+ safety_2+ safety_3+ safety_4+ mang_1+ mang_2+ mang_3+ rmdg_1+ rmdg_2+ rmdg_3+ rmdg_4+ attitude+ inovative_init) as sum_d,sum(app_1 + app_2+ app_3+ app_4+ app_5+ app_6+ app_7+ app_8+ app_9+ app_10+ app_11) as  sales_app,sum(st_licnce_1+ st_licnce_2+ st_licnce_3) as sales_st_licnce,sum(infr_1+ infr_2+ infr_3+ infr_4+ infr_5+ infr_6+ infr_7) as sales_infr,sum(godown_1+  godown_2+  godown_3+ godown_4+ godown_5+ godown_6+ godown_7+ godown_8+ godown_9+ godown_10+ godown_1+ godown_12+ godown_13+ godown_14) as sales_godown,sum(doc_1+ doc_2+ doc_3+ doc_4+ doc_5+  doc_6+ doc_7+ doc_8) as sales_doc,sum(cust_update_1+ cust_update_2+ cust_update_3+ cust_update_4) as sales_cust_update,sum(ndne_1+ ndne_2) as sales_ndne,sum(nfr_1+ nfr_2) as sales_nfr, sum(cust_orientation_1+ cust_orientation_2+ cust_orientation_3+ cust_orientation_4+ cust_orientation_5+ cust_orientation_6) as sales_cust_orientation, sum(home_del_1+ home_del_2+ home_del_3) as sales_home_del,sum(leakage_1+ leakage_2+ leakage_3) as sales_leakage,sum(safety_1+ safety_2+ safety_3+ safety_4) as sales_safety, sum(mang_1+ mang_2+ mang_3) as sales_mang, sum(rmdg_1+ rmdg_2+ rmdg_3+ rmdg_4) as sales_rmdg   from gb_ada_marks where gb_type='SZ' and distributor_id='" . $_REQUEST["id"] . "' and year='" . $fin_year . "' group by id");

$cnt = mysql_num_rows($sql);

if ($cnt > 0) {

    $row = mysql_fetch_assoc($sql);

    // if($row['status']==1)
    //        $readonly='readonly';
    if ($row['status'] == 1) {
        $disable = ' disabled="disabled" ';
    }
}
else {
    $row = '';
}

/*  $total_sh=$row['sales_app']+$row['sales_st_licnce']+$row['sales_infr']+$row['sales_godown'];
  $total_op=$row['sales_doc']+$row['sales_cust_update'];
  $total_sls=$row['sales_ndne']+$row['sales_nfr']+$row['sales_cust_orientation']+$row['sales_home_del']+$row['sales_leakage']+$row['sales_safety']+$row['sales_mang']+$row['sales_rmdg']+$row['dom_1']+$row['nde_1']; */

$total_sales_sh = $row['sales_app'] + $row['sales_st_licnce'] + $row['sales_infr'] + $row['sales_godown'];
$total_sales_op = $row['sales_doc'] + $row['sales_cust_update'];
$total_sales_sls = $row['sales_ndne'] + $row['sales_nfr'] + $row['sales_cust_orientation'] + $row['sales_home_del'] + $row['sales_leakage'] + $row['sales_safety'] + $row['sales_mang'] + $row['sales_rmdg'] + $row['dom_1'] + $row['nde_1'];



$sql_sz = mysql_query("select *,sum(app_1 + app_2+ app_3+ app_4+ app_5+ app_6+ app_7+ app_8+ app_9+ app_10+ app_11+ st_licnce_1+ st_licnce_2+ st_licnce_3+ infr_1+ infr_2+ infr_3+ infr_4+ infr_5+ infr_6+ infr_7+ godown_1+  godown_2+  godown_3+ godown_4+ godown_5+ godown_6+ godown_7+ godown_8+ godown_9+ godown_10+ godown_1+ godown_12+ godown_13+ godown_14+ doc_1+ doc_2+ doc_3+ doc_4+ doc_5+  doc_6+ doc_7+ doc_8+ cust_update_1+ cust_update_2+ cust_update_3+ cust_update_4+ ndne_1+ ndne_2+ dom_1+ nfr_1+ nfr_2+ nde_1+ cust_orientation_1+ cust_orientation_2+ cust_orientation_3+ cust_orientation_4+ cust_orientation_5+ cust_orientation_6+ home_del_1+ home_del_2+ home_del_3+ leakage_1+ leakage_2+ leakage_3+ safety_1+ safety_2+ safety_3+ safety_4+ mang_1+ mang_2+ mang_3+ rmdg_1+ rmdg_2+ rmdg_3+ rmdg_4+ attitude+ inovative_init) as sum_d,sum(app_1 + app_2+ app_3+ app_4+ app_5+ app_6+ app_7+ app_8+ app_9+ app_10+ app_11) as  dist_app,sum(st_licnce_1+ st_licnce_2+ st_licnce_3) as dist_st_licnce,sum(infr_1+ infr_2+ infr_3+ infr_4+ infr_5+ infr_6+ infr_7) as dist_infr,sum(godown_1+  godown_2+  godown_3+ godown_4+ godown_5+ godown_6+ godown_7+ godown_8+ godown_9+ godown_10+ godown_1+ godown_12+ godown_13+ godown_14) as dist_godown,sum(doc_1+ doc_2+ doc_3+ doc_4+ doc_5+  doc_6+ doc_7+ doc_8) as dist_doc,sum(cust_update_1+ cust_update_2+ cust_update_3+ cust_update_4) as dist_cust_update,sum(ndne_1+ ndne_2) as dist_ndne,sum(nfr_1+ nfr_2) as dist_nfr, sum(cust_orientation_1+ cust_orientation_2+ cust_orientation_3+ cust_orientation_4+ cust_orientation_5+ cust_orientation_6) as dist_cust_orientation, sum(home_del_1+ home_del_2+ home_del_3) as dist_home_del,sum(leakage_1+ leakage_2+ leakage_3) as dist_leakage,sum(safety_1+ safety_2+ safety_3+ safety_4) as dist_safety, sum(mang_1+ mang_2+ mang_3) as dist_mang, sum(rmdg_1+ rmdg_2+ rmdg_3+ rmdg_4) as dist_rmdg  from gb_ada_marks where gb_type='D' and distributor_id='" . $_REQUEST["id"] . "' and year='" . $fin_year . "' group by id ");
//echo "select *,sum(app_1 + app_2+ app_3+ app_4+ app_5+ app_6+ app_7+ app_8+ app_9+ app_10+ app_11+ st_licnce_1+ st_licnce_2+ st_licnce_3+ infr_1+ infr_2+ infr_3+ infr_4+ infr_5+ infr_6+ infr_7+ godown_1+  godown_2+  godown_3+ godown_4+ godown_5+ godown_6+ godown_7+ godown_8+ godown_9+ godown_10+ godown_1+ godown_12+ godown_13+ godown_14+ doc_1+ doc_2+ doc_3+ doc_4+ doc_5+  doc_6+ doc_7+ doc_8+ cust_update_1+ cust_update_2+ cust_update_3+ cust_update_4+ ndne_1+ ndne_2+ dom_1+ nfr_1+ nfr_2+ nde_1+ cust_orientation_1+ cust_orientation_2+ cust_orientation_3+ cust_orientation_4+ cust_orientation_5+ cust_orientation_6+ home_del_1+ home_del_2+ home_del_3+ leakage_1+ leakage_2+ leakage_3+ safety_1+ safety_2+ safety_3+ safety_4+ mang_1+ mang_2+ mang_3+ rmdg_1+ rmdg_2+ rmdg_3+ rmdg_4+ attitude+ inovative_init) as sum_d,sum(app_1 + app_2+ app_3+ app_4+ app_5+ app_6+ app_7+ app_8+ app_9+ app_10+ app_11) as  dist_app,sum(st_licnce_1+ st_licnce_2+ st_licnce_3) as dist_st_licnce,sum(infr_1+ infr_2+ infr_3+ infr_4+ infr_5+ infr_6+ infr_7) as dist_infr,sum(godown_1+  godown_2+  godown_3+ godown_4+ godown_5+ godown_6+ godown_7+ godown_8+ godown_9+ godown_10+ godown_1+ godown_12+ godown_13+ godown_14) as dist_godown,sum(doc_1+ doc_2+ doc_3+ doc_4+ doc_5+  doc_6+ doc_7+ doc_8) as dist_doc,sum(cust_update_1+ cust_update_2+ cust_update_3+ cust_update_4) as dist_cust_update,sum(ndne_1+ ndne_2) as dist_ndne,sum(nfr_1+ nfr_2) as dist_nfr, sum(cust_orientation_1+ cust_orientation_2+ cust_orientation_3+ cust_orientation_4+ cust_orientation_5+ cust_orientation_6) as dist_cust_orientation, sum(home_del_1+ home_del_2+ home_del_3) as dist_home_del,sum(leakage_1+ leakage_2+ leakage_3) as dist_leakage,sum(safety_1+ safety_2+ safety_3+ safety_4) as dist_safety, sum(mang_1+ mang_2+ mang_3) as dist_mang, sum(rmdg_1+ rmdg_2+ rmdg_3+ rmdg_4) as dist_rmdg  from gb_ada_marks where gb_type='D' and distributor_id='".$_REQUEST["id"]."' and year='".$fin_year."' group by id ";
$cnt_sz = mysql_num_rows($sql_sz);

if ($cnt_sz > 0) {

    $row_sz = mysql_fetch_assoc($sql_sz);
}
else {
    $row_sz = '';
}



/*  $total_sales_sh=$row_sz['dist_app']+$row_sz['dist_st_licnce']+$row_sz['dist_infr']+$row_sz['dist_godown'];
  $total_sales_op=$row_sz['dist_doc']+$row_sz['dist_cust_update'];
  $total_sales_sls=$row_sz['dist_ndne']+$row_sz['dist_nfr']+$row_sz['dist_cust_orientation']+$row_sz['dist_home_del']+$row_sz['dist_leakage']+$row_sz['dist_safety']+$row_sz['dist_mang']+$row_sz['dist_rmdg']+$row_sz['dom_1']+$row_sz['nde_1']; */
$total_sh = $row_sz['dist_app'] + $row_sz['dist_st_licnce'] + $row_sz['dist_infr'] + $row_sz['dist_godown'];
$total_op = $row_sz['dist_doc'] + $row_sz['dist_cust_update'];
$total_sls = $row_sz['dist_ndne'] + $row_sz['dist_nfr'] + $row_sz['dist_cust_orientation'] + $row_sz['dist_home_del'] + $row_sz['dist_leakage'] + $row_sz['dist_safety'] + $row_sz['dist_mang'] + $row_sz['dist_rmdg'] + $row_sz['dom_1'] + $row_sz['nde_1'];

/*    $sql_ao=mysql_query("select * ,sum(app_1 + app_2+ app_3+ app_4+ app_5+ app_6+ app_7+ app_8+ app_9+ app_10+ app_11+ st_licnce_1+ st_licnce_2+ st_licnce_3+ infr_1+ infr_2+ infr_3+ infr_4+ infr_5+ infr_6+ infr_7+ godown_1+  godown_2+  godown_3+ godown_4+ godown_5+ godown_6+ godown_7+ godown_8+ godown_9+ godown_10+ godown_1+ godown_12+ godown_13+ godown_14+ doc_1+ doc_2+ doc_3+ doc_4+ doc_5+  doc_6+ doc_7+ doc_8+ cust_update_1+ cust_update_2+ cust_update_3+ cust_update_4+ ndne_1+ ndne_2+ dom_1+ nfr_1+ nfr_2+ nde_1+ cust_orientation_1+ cust_orientation_2+ cust_orientation_3+ cust_orientation_4+ cust_orientation_5+ cust_orientation_6+ home_del_1+ home_del_2+ home_del_3+ leakage_1+ leakage_2+ leakage_3+ safety_1+ safety_2+ safety_3+ safety_4+ mang_1+ mang_2+ mang_3+ rmdg_1+ rmdg_2+ rmdg_3+ rmdg_4+ attitude+ inovative_init) as sum_d,sum(app_1 + app_2+ app_3+ app_4+ app_5+ app_6+ app_7+ app_8+ app_9+ app_10+ app_11) as area_app,sum(st_licnce_1+ st_licnce_2+ st_licnce_3) as area_licnce,sum(infr_1+ infr_2+ infr_3+ infr_4+ infr_5+ infr_6+ infr_7) as area_infr,sum(godown_1+  godown_2+  godown_3+ godown_4+ godown_5+ godown_6+ godown_7+ godown_8+ godown_9+ godown_10+ godown_1+ godown_12+ godown_13+ godown_14) as area_godown,sum(doc_1+ doc_2+ doc_3+ doc_4+ doc_5+  doc_6+ doc_7+ doc_8) as area_doc,sum(cust_update_1+ cust_update_2+ cust_update_3+ cust_update_4) as area_cust_update,sum(ndne_1+ ndne_2) as area_ndne,sum(nfr_1+ nfr_2) as area_nfr, sum(cust_orientation_1+ cust_orientation_2+ cust_orientation_3+ cust_orientation_4+ cust_orientation_5+ cust_orientation_6) as area_cust_orientation, sum(home_del_1+ home_del_2+ home_del_3) as area_home_del,sum(leakage_1+ leakage_2+ leakage_3) as area_leakage,sum(safety_1+ safety_2+ safety_3+ safety_4) as area_safety, sum(mang_1+ mang_2+ mang_3) as area_mang, sum(rmdg_1+ rmdg_2+ rmdg_3+ rmdg_4) as area_rmdg from gb_ada_marks where gb_type='AO' and distributor_id='".$_REQUEST["id"]."' group by id");

  $cnt_ao=mysql_num_rows($sql_ao);

  if($cnt_ao>0){

  $row_ao=mysql_fetch_assoc($sql_ao);
  }else{
  $row_ao='';
  } */

$sql_ao = mysql_query("select * from gb_ada_remarks where distributor_id='" . $_REQUEST["id"] . "' and year='" . $fin_year . "' group by id");

$cnt_ao = mysql_num_rows($sql_ao);

if ($cnt_ao > 0) {

    $apa_remark = mysql_fetch_assoc($sql_ao);
}
else {
    $apa_remark = '';
}



$total_d = 0;
$sql_disable = mysql_query("select * from gb_ada_marks where distributor_id='" . $_REQUEST["id"] . "' and status='1' and gb_type='AO' and year='" . $fin_year . "'");

$cnt_ao = mysql_num_rows($sql_disable);

if ($cnt_ao > 0) {
    $disable = ' disabled="disabled" ';
    //$readonly='readonly';
}



$sql_aos = mysql_query("select * from gb_ada_marks where distributor_id='" . $_REQUEST["id"] . "' and gb_type='AO' and year='" . $fin_year . "'");

$row_aos = mysql_fetch_assoc($sql_aos);

if ($cnt_ao > 0) {
    echo '<span style="color:#FF0000;font-weight:bold;">NOTE : You cannot edit this form now.</span>';
}
?>
                    <? if ($nodeditable == 1) {
                        ?>
                        <style>
                            INPUT{
                                /* border:none;*/
                            }

                        </style>
                    <? } ?>
                    <form name='freezeform' action='' method='post' class="formcontainer" >

                        <input name="disablestatus" type="hidden" id="disablestatus" value="">
                        <input type="hidden" name="arch_year" id="arch_year" value="<?= @$_REQUEST['arch_year'] ?>" />

                    </form>


                    <form name='archiveddata' action='' method='post' class="formcontainer" >

                        <input name="arch" type="hidden" id="arch" value="" >
                        Select Year :  <div class="selectbox" style="width:200px; "><select name="arch_year" id="arch_year" onchange="getarchyear()" style="width:200px;">
                                <option value="2010" <?php if (@$_REQUEST['arch_year'] == "2010") { ?> selected="selected" <? } ?>>2010-2011</option>
                                <option value="2011" <?php if (@$_REQUEST['arch_year'] == "2011") { ?> selected="selected" <? } ?>>2011-2012</option>
                                <option value="2012" <?php if (@$_REQUEST['arch_year'] == "2012") { ?> selected="selected" <? } ?>>2012-2013</option>
                                <option value="2013" <?php if (@$_REQUEST['arch_year'] == "2013") { ?> selected="selected" <? } ?>>2013-2014</option>
                                <option value="2014" <?php if (@$_REQUEST['arch_year'] == "2014") { ?> selected="selected" <? } ?>>2014-2015</option>
                                <option value="2015" <?php if (@$_REQUEST['arch_year'] == "2015") { ?> selected="selected" <? } ?>>2015-2016</option>
                                <option value="2016" <?php if (@$_REQUEST['arch_year'] == "2016") { ?> selected="selected" <? } ?>>2016-2017</option>
								<option value="2017" <?php if (@$_REQUEST['arch_year'] == "2017") { ?> selected="selected" <? } ?>>2017-2018</option>
								 <option value="2018" <?php if (@$_REQUEST['arch_year'] == "2018") { ?> selected="selected" <? } ?>>2018-2019</option>
<?
/* $sql_year=mysql_query("select year from gb_ada_marks where year!='2010' group by year");
  if(mysql_num_rows($sql_year)>0) {
  $row_year=mysql_fetch_assoc($sql_year);
  {
  $year_1=substr($row_year['year'],2,2) +1; */
?>
                                <!--<option value="<?= $row_year['year'] ?>"  <? if (@$_REQUEST["arch_year"] == $row_year['year']) {
    echo 'selected="selected"';
} ?> ><?php echo $row_year['year'] . ' - ' . $year_1; ?></option>
                                -->
<? //} }  ?>
                                <!--<option value="2011">2011</option>-->
                            </select></div>

                    </form>

<?php /* ?><?  if($cnt_ao <1){?>
  <div style="margin:7px;">Form Status : <? if($row["status"]==1){ ?><a href="apa.php?disablestatus=0&id=<?=$_REQUEST["id"]?>" onclick="return confirm('Are you sure, you want to unfreeze this form ?');"><img src="images/icon_status_red.gif" border="0"  /></a><? }else{ ?><a href="apa.php?disablestatus=1&id=<?=$_REQUEST["id"]?>" onclick="return confirm('Are you sure, you want to freeze this form ?');"><img src="images/icon_status_green.gif" border="0"  /></a> <? } ?></div>
  <? }else{ ?>
  <div style="margin:7px;">Form Status : <img src="images/icon_status_red.gif" border="0"  /></div>

  <? }?><?php */ ?>


                    <form name='apaform' action='' method='post' enctype="multipart/form-data" id="apaform"  >
                        <input type="hidden" name="arch_year" id="arch_year" value="<?= @$_REQUEST['arch_year'] ?>" />
                        <div>
                            <div>
                                <table border="0" cellpadding="0" cellspacing="0" style="margin-left:500px;">
                                    <tr>
                                        <td width="75" align="right">
                                            <b>MAX MARKS</b></td>
                                        <td width="100" align="right">
                                            <b>DISTRIBUTOR</b>
                                        </td>
                                        <td width="100" align="right">
                                            <b>SALES OFFICER</b>
                                        </td>
                                        <td width="100" align="right"><b>AREA OFFICER </b></td>
                                    </tr>
                                </table>
                            </div>
                            <div id="sh_room" class="div_bordr">
                                <div class="bck_color_expand" id="one"><b><span id="title_padding">SHOWROOM</span></b>
                                    <table border="0" cellpadding="0" cellspacing="0" style="float:right;">
                                        <tr>
                                            <td width="100" align="right">
                                                <b>35</b>
                                            </td>
                                            <td width="100" align="right">
                                                <b><? echo $total_sh; ?></b>
                                            </td>
                                            <td width="100" align="right">
                                                <b><? echo $total_sales_sh; ?></b>
                                            </td>
                                            <td width="150" align="right"></td>
                                        </tr>
                                    </table></div>
                                <table class="first" id="shroom_list"  border="0" cellpadding="2" cellspacing="1" >
                                    <tr bgcolor="#edab55">
                                        <td width="100" align="center" ><b>SNO</b></td>
                                        <td width="350" align="center"><b>ITEM</b></td>
                                        <td width="60" align="center" ><b>MAX MARKS</b></td>
                                        <td width="100" align="center" ><b>DISTRIBUTOR</b></td>
                                        <td width="70" align="center"><b>SALES OFFICER</b></td>
                                        <td width="120" align="center"><b>REMARKS</b></td>
                                    </tr>
                                    <tr>
                                        <td ><b>APPEARANCE</b></td>
                                        <td >&nbsp;</td>
                                        <td class="center"><b>11</b></td>
                                        <td class="center"><input type="text" name='dist_total' id="dist_total" size="2" maxlength="3" value="<?= $row_sz['dist_app'] ?>" style=" border:none; font-weight:bold; text-align:right;" readonly /></td>
                                        <td class="center"><input type="text" name='app_total' id="app_total" size="2" maxlength="3" value="<?= $row['sales_app'] ?>" style=" border:none; font-weight:bold;" readonly /></td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Sign board as per norm,clean & prominently visible</td>
                                        <td class="center"><?= $row_1['app_1'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['app_1'] ?></td>
                                        <td class="center"><input type="text" name="app_1" size="2" id="app_1" maxlength="3" <?= $disable ?> onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['app_1'] ?>');
                       all_app_total('app_', '11');" style="text-align:right;" value="<?php echo $row['app_1']; ?>"  <?= $readonly ?> /></td>

                                        <td align="center" class="areao"><input type="text"  class="areaoremark" style="text-align:left;" <?= $disable ?> id="app_remark_1" name="app_remark_1" maxlength="50" size="20" value="<? echo $apa_remark['app_remark_1']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Standard display-charges, mrtp notification,esc,csc,fo tel.no &amp; address availabe visible</td>
                                        <td class="center"><?= $row_1['app_2'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['app_2'] ?></td>
                                        <td class="center"><input type="text" name="app_2" id="app_2" size="2" maxlength="3" <?= $disable ?> onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['app_2'] ?>');
                       all_app_total('app_', '11');" value="<?php echo $row['app_2']; ?>" <?= $readonly ?>/></td>

                                        <td align="center" class="areao"><input class="areaoremark"  style="text-align:left;" type="text" <?= $disable ?> id="app_remark_2" name="app_remark_2" maxlength="50" size="20" value="<? echo $apa_remark['app_remark_2']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Colour scheme as per standard</td>
                                        <td class="center"><?= $row_1['app_3'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['app_3'] ?></td>
                                        <td class="center"><input type="text" id="app_3" name="app_3" size="2" maxlength="3" <?= $disable ?> onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['app_3'] ?>');
                       all_app_total('app_', '11');"  value="<?php echo $row['app_3']; ?>" <?= $readonly ?> /></td>
                                        <td align="center" class="areao"><input type="text"  class="areaoremark" id="app_remark_3" <?= $disable ?> name="app_remark_3" maxlength="50" size="20"  style="text-align:left;"  value="<? echo $apa_remark['app_remark_3']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Showroom-good-upkeep clean &amp;bright</td>
                                        <td class="center"><?= $row_1['app_4'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['app_4'] ?></td>
                                        <td class="center"><input type="text" id="app_4" name="app_4" size="2" maxlength="3" <?= $disable ?> onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['app_4'] ?>');
                       all_app_total('app_', '11');" value="<?php echo $row['app_4']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" id="app_remark_4" <?= $disable ?> name="app_remark_4"  style="text-align:left;"  maxlength="50" size="20" value="<? echo $apa_remark['app_remark_4']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Adequacy of computers/printers(@ one computer + one printer for every 200 refill sales per day)</td>
                                        <td class="center"><?= $row_1['app_5'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['app_5'] ?></td>
                                        <td class="center"><input type="text" id="app_5" name="app_5" size="2" maxlength="3" <?= $disable ?> onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['app_5'] ?>');
                       all_app_total('app_', '11');" value="<?php echo $row['app_5']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" id="app_remark_5" <?= $disable ?> name="app_remark_5"  style="text-align:left;"  maxlength="50" size="20" value="<? echo $apa_remark['app_remark_5']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Ups backup for atleast 1 hr/generator/power backup</td>
                                        <td class="center"><?= $row_1['app_6'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['app_6'] ?></td>
                                        <td class="center"><input type="text" id="app_6" name="app_6" size="2" maxlength="3" <?= $disable ?> onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['app_6'] ?>');
                       all_app_total('app_', '11');"  value="<?php echo $row['app_6']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" id="app_remark_6" <?= $disable ?> name="app_remark_6" maxlength="50" size="20"  style="text-align:left;"  value="<? echo $apa_remark['app_remark_6']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Ideal indane installation</td>
                                        <td class="center"><?= $row_1['app_7'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['app_7'] ?></td>
                                        <td class="center"><input type="text" id="app_7" name="app_7" size="2" <?= $disable ?> maxlength="3" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['app_7'] ?>');
                       all_app_total('app_', '11');" value="<?php echo $row['app_7']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" id="app_remark_7" <?= $disable ?> name="app_remark_7" maxlength="50" size="20"  style="text-align:left;"  value="<? echo $apa_remark['app_remark_7']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Green Label hotplate display with rates</td>
                                        <td class="center"><?= $row_1['app_8'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['app_8'] ?></td>
                                        <td class="center"><input type="text" id="app_8" name="app_8" size="2" maxlength="3" <?= $disable ?> onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['app_8'] ?>');
                       all_app_total('app_', '11');"  value="<?php echo $row['app_8']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" id="app_remark_8" <?= $disable ?> name="app_remark_8"  style="text-align:left;"  maxlength="50" size="20" value="<? echo $apa_remark['app_remark_8']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Showroom staff in uniform</td>
                                        <td class="center"><?= $row_1['app_9'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['app_9'] ?></td>
                                        <td class="center"><input type="text" id="app_9" name="app_9" size="2" <?= $disable ?> maxlength="3" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['app_9'] ?>');
                       all_app_total('app_', '11');" value="<?php echo $row['app_9']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" id="app_remark_9" <?= $disable ?> name="app_remark_9" maxlength="50" size="20"  style="text-align:left;"  value="<? echo $apa_remark['app_remark_9']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Drinking water facility</td>
                                        <td class="center"><?= $row_1['app_10'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['app_10'] ?></td>
                                        <td class="center"><input type="text" id="app_10" name="app_10" <?= $disable ?> size="2" maxlength="3" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['app_10'] ?>');
                       all_app_total('app_', '11');" value="<?php echo $row['app_10']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" <?= $disable ?> id="app_remark_10" name="app_remark_10" maxlength="50" size="20"  style="text-align:left;"  value="<? echo $apa_remark['app_remark_10']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Proper seating arrangement</td>
                                        <td class="center"><?= $row_1['app_11'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['app_11'] ?></td>
                                        <td class="center"><input type="text" id="app_11" name="app_11" <?= $disable ?> size="2" maxlength="3" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['app_11'] ?>');
                       all_app_total('app_', '11');" value="<?php echo $row['app_11']; ?>" <?= $readonly ?> /></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" id="app_remark_11" <?= $disable ?> name="app_remark_11" maxlength="50" size="20"  style="text-align:left;" value="<? echo $apa_remark['app_remark_11']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td><b>STATUTORY &amp; OTHER lICENCE</b></td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>2</b></td>
                                        <td align="right" class="center"><input type="text" size="2"  maxlength="3" id="dist_licnce" name="dist_licnce" value"<?= $row_sz['dist_st_licnce'] ?>" readonly="readonly" style="border:none; font-weight:bold; text-align:right;" />                   </td>
                                        <td class="center"><input type="text" name="st_licnce_total" id="st_licnce_total" size="2" maxlength="3" readonly style="border:none; font-weight:bold;" value="<?= $row['sales_st_licnce']; ?>"/></td>
                                        <td class="center"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>&nbsp;</td>
                                        <td></td>
                                        <td align="right" class="center">&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Insurance - adequate &amp; valid (Cyls-120% of godown capacity)</td>
                                        <td class="center"><?= $row_1['st_licnce_1'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['st_licnce_1'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="st_licnce_1" name="st_licnce_1" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['st_licnce_1'] ?>');
                       all_app_total('st_licnce_', '3');"  value="<?php echo $row['st_licnce_1']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" size="20" <?= $disable ?>  maxlength="50" id="lcnce_remark_1" name="lcnce_remark_1"  style="text-align:left;" value="<? echo $apa_remark['st_licnce_remark_1']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Instances of delay in renewal of explosive, from-b, insurance licences</td>
                                        <td class="center"><?= $row_1['st_licnce_2'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['st_licnce_2'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="st_licnce_2" name="st_licnce_2" onkeypress="return validate(event);" onblur="ck_value(this.id,<?= $row_1['st_licnce_2'] ?>);
                       all_app_total('st_licnce_', '3');" value="<?php echo $row['st_licnce_2']; ?>"  <?= $readonly ?> /></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" size="20" <?= $disable ?> maxlength="50" id="lcnce_remark_2" name="lcnce_remark_2"  style="text-align:left;"  value="<? echo $apa_remark['st_licnce_remark_2']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Interbase Licences</td>
                                        <td class="center"><?= $row_1['st_licnce_3'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['st_licnce_3'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="st_licnce_3" name="st_licnce_3" onkeypress="return validate(event);" onblur="ck_value(this.id,<?= $row_1['st_licnce_3'] ?>);
                       all_app_total('st_licnce_', '3');" value="<?php echo $row['st_licnce_3']; ?>"  <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text"  class="areaoremark" size="20" <?= $disable ?> maxlength="50" id="lcnce_remark_3" name="lcnce_remark_3"  style="text-align:left;"  value="<? echo $apa_remark['st_licnce_remark_3']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td><b>INFRASTRUCTURE</b></td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>8</b></td>
                                        <td align="right" class="center"><input type="text" size="2" maxlength="3"  readonly="readonly" style="border:none; font-weight:bold; text-align:right;" id="dist_infr_total" name="dist_infr_total" value="<?= $row_sz['dist_infr'] ?>"/></td>
                                        <td class="center"><input type="text" size="2" maxlength="3"   name="infr_total" id="infr_total" readonly style="border:none; font-weight:bold;" value="<?= $row['sales_infr'] ?>"/></td>
                                        <td class="center"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Showroom staff (Mgr +2 for 7000 refill sales/mth)</td>
                                        <td class="center"><?= $row_1['infr_1'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['infr_1'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="infr_1" name="infr_1" onkeypress="return validate(event);" onblur="ck_value(this.id,<?= $row_1['infr_1'] ?>);
                       all_app_total('infr_', '7');"  value="<?php echo $row['infr_1']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" size="20" <?= $disable ?> maxlength="20" id="infr_remarks_1" name="infr_remarks_1"  style="text-align:left;" value="<? echo $apa_remark['infr_remark_1']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Mechanics:(1 for every 4000 customers)</td>
                                        <td class="center"><?= $row_1['infr_2'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['infr_2'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="infr_2" name="infr_2" onkeypress="return validate(event);" onblur="ck_value(this.id,<?= $row_1['infr_2'] ?>);
                       all_app_total('infr_', '7');"  value="<?php echo $row['infr_2']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" size="20" <?= $disable ?> maxlength="20" id="infr_remarks_2" name="infr_remarks_2"  style="text-align:left;"  value="<? echo $apa_remark['infr_remark_2']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Delivery vehicles:Adequte</td>
                                        <td class="center"><?= $row_1['infr_3'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['infr_3'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="infr_3" name="infr_3" onkeypress="return validate(event);" onblur="ck_value(this.id,<?= $row_1['infr_3'] ?>);
                       all_app_total('infr_', '7');" value="<?php echo $row['infr_3']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" size="20" <?= $disable ?> maxlength="20" id="infr_remarks_3" name="infr_remarks_3"  style="text-align:left;"  value="<? echo $apa_remark['infr_remark_3']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Delivery vehicles:Owend</td>
                                        <td class="center"><?= $row_1['infr_4'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['infr_4'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="infr_4" name="infr_4" onkeypress="return validate(event);" onblur="ck_value(this.id,<?= $row_1['infr_4'] ?>);
                       all_app_total('infr_', '7');"   value="<?php echo $row['infr_4']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" size="20" <?= $disable ?> maxlength="20" id="infr_remarks_4" name="infr_remarks_4"  style="text-align:left;"  value="<? echo $apa_remark['infr_remark_4']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Sign boards ondDelivery vehicles(Agency Names, RSP)</td>
                                        <td class="center"><?= $row_1['infr_5'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['infr_5'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="infr_5" name="infr_5" onkeypress="return validate(event);" onblur="ck_value(this.id,<?= $row_1['infr_5'] ?>);
                       all_app_total('infr_', '7');"  value="<?php echo $row['infr_5']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" size="20" <?= $disable ?> maxlength="20" id="infr_remarks_5" name="infr_remarks_5"  style="text-align:left;"  value="<? echo $apa_remark['infr_remark_5']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Telephones-Adequacy-1 for every 7000 Customers</td>
                                        <td class="center"><?= $row_1['infr_6'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['infr_6'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="infr_6" name="infr_6" onkeypress="return validate(event);" onblur="ck_value(this.id,<?= $row_1['infr_6'] ?>);
                       all_app_total('infr_', '7');" value="<?php echo $row['infr_6']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" size="20" <?= $disable ?> maxlength="20" id="infr_remarks_6" name="infr_remarks_6"  style="text-align:left;" value="<? echo $apa_remark['infr_remark_6']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>SMS Facility</td>
                                        <td class="center"><?= $row_1['infr_7'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['infr_7'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="infr_7" name="infr_7" onkeypress="return validate(event);" onblur="ck_value(this.id,<?= $row_1['infr_7'] ?>);
                       all_app_total('infr_', '7');" value="<?php echo $row['infr_7']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" size="20" <?= $disable ?> maxlength="20" id="infr_remarks_7" name="infr_remarks_7"  style="text-align:left;"  value="<? echo $apa_remark['infr_remark_7']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td><b>GODOWN</b></td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>14</b></td>
                                        <td align="right" class="center"><input type="text" size="2" maxlength="3" readonly style="border:none; font-weight:bold;" id="dist_godown_total" name="dist_godown_total" value="<?= $row_sz['dist_godown'] ?>"/></td>
                                        <td class="center"><input type="text" size="2" maxlength="3"  id="godown_total" name="godown_total" readonly value="<?= $row['sales_godown'] ?>" style="border:none; font-weight:bold;"/></td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Godown as per approved plan-Yes/No</td>
                                        <td class="center"><?= $row_1['godown_1'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['godown_1'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="godown_1" name="godown_1" onkeypress="return validate(event);" onblur="all_app_total('godown_', '14');"  value="<?php echo $row['godown_1']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" size="20" <?= $disable ?> maxlength="50" id="gdwn_remark_1" name="gdwn_remark_1"  style="text-align:left;" value="<? echo $apa_remark['godown_remark_1']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Copy of plan displayed</td>
                                        <td class="center"><?= $row_1['godown_2'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['godown_2'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="godown_2" name="godown_2" onkeypress="return validate(event);" onblur="ck_value(this.id,<?= $row_1['godown_2'] ?>);
                       all_app_total('godown_', '14');" value="<?php echo $row['godown_2']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" size="20" <?= $disable ?> maxlength="50" id="gdwn_remark_2" name="gdwn_remark_2"  style="text-align:left;"  value="<? echo $apa_remark['godown_remark_2']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Width of gate as per approval</td>
                                        <td class="center"><?= $row_1['godown_3'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['godown_3'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" id="godown_3" <?= $disable ?> name="godown_3" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['godown_3'] ?>');
                       all_app_total('godown_', '14');" value="<?php echo $row['godown_3']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text"  class="areaoremark" size="20" <?= $disable ?> maxlength="50" id="gdwn_remark_3" name="gdwn_remark_3"  style="text-align:left;"  value="<? echo $apa_remark['godown_remark_3']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Vents properly covered with standard wire mesh.</td>
                                        <td class="center"><?= $row_1['godown_4'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['godown_4'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="godown_4" name="godown_4" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['godown_4'] ?>');
                       all_app_total('godown_', '14');" value="<?php echo $row['godown_4']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" <?= $disable ?> size="20" maxlength="50" id="gdwn_remark_4" name="gdwn_remark_4"  style="text-align:left;"  value="<? echo $apa_remark['godown_remark_4']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Safety zone-free of dry vegetation.</td>
                                        <td class="center"><?= $row_1['godown_5'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['godown_5'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="godown_5" name="godown_5" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['godown_5'] ?>');
                       all_app_total('godown_', '14');" value="<?php echo $row['godown_5']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" size="20" <?= $disable ?> maxlength="50" id="gdwn_remark_5" name="gdwn_remark_5"  style="text-align:left;"  value="<? echo $apa_remark['godown_remark_5']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Mastic flooring in proper condition</td>
                                        <td class="center"><?= $row_1['godown_6'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['godown_6'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="godown_6" name="godown_6" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['godown_6'] ?>');
                       all_app_total('godown_', '14');" value="<?php echo $row['godown_6']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" size="20" <?= $disable ?> maxlength="50" id="gdwn_remark_6" name="gdwn_remark_6"  style="text-align:left;"  value="<? echo $apa_remark['godown_remark_6']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>No smoking sign displayed</td>
                                        <td class="center"><?= $row_1['godown_7'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['godown_7'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="godown_7" name="godown_7" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['godown_7'] ?>');
                       all_app_total('godown_', '14');" value="<?php echo $row['godown_7']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" <?= $disable ?> size="20" maxlength="50" id="gdwn_remark_7" name="gdwn_remark_7"  style="text-align:left;"  value="<? echo $apa_remark['godown_remark_7']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>PDC record maintained.</td>
                                        <td class="center"><?= $row_1['godown_8'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['godown_8'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="godown_8" name="godown_8" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['godown_8'] ?>');
                       all_app_total('godown_', '14');" value="<?php echo $row['godown_8']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" <?= $disable ?> size="20" maxlength="50" id="gdwn_remark_8" name="gdwn_remark_8"  style="text-align:left;"  value="<? echo $apa_remark['godown_remark_8']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Proper stacking of cylinders with segregation.</td>
                                        <td class="center"><?= $row_1['godown_9'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['godown_9'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="godown_9" name="godown_9" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['godown_9'] ?>');
                       all_app_total('godown_', '14');" value="<?php echo $row['godown_9']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" size="20" <?= $disable ?> maxlength="50" id="gdwn_remark_9" name="gdwn_remark_9"  style="text-align:left;"  value="<? echo $apa_remark['godown_remark_9']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Special tools/utilities like trolleys for U/L and loadin</td>
                                        <td class="center"><?= $row_1['godown_10'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['godown_10'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="godown_10" name="godown_10" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['godown_10'] ?>');
                       all_app_total('godown_', '14');" value="<?php echo $row['godown_10']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" size="20" <?= $disable ?> maxlength="50" id="gdwn_remark_10" name="gdwn_remark_10" style="text-align:left;"  value="<? echo $apa_remark['godown_remark_10']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Fire extinguisher adequate and in working condition</td>
                                        <td class="center"><?= $row_1['godown_11'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['godown_11'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="godown_11" name="godown_11" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['godown_11'] ?>');
                       all_app_total('godown_', '14');" value="<?php echo $row['godown_11']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" size="20" <?= $disable ?> maxlength="50" id="gdwn_remark_11" name="gdwn_remark_11" style="text-align:left;"  value="<? echo $apa_remark['godown_remark_11']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Sand &amp; buckets available and properly maintained</td>
                                        <td class="center"><?= $row_1['godown_12'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['godown_12'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="godown_12" name="godown_12" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['godown_12'] ?>');
                       all_app_total('godown_', '14');" value="<?php echo $row['godown_12']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" size="20" <?= $disable ?> maxlength="50" id="gdwn_remark_12" name="gdwn_remark_12"  style="text-align:left;" value="<? echo $apa_remark['godown_remark_12']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Weighing machine in working condition</td>
                                        <td class="center"><?= $row_1['godown_13'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['godown_13'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="godown_13" name="godown_13" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['godown_13'] ?>');
                       all_app_total('godown_', '14');" value="<?php echo $row['godown_13']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" size="20" <?= $disable ?> maxlength="50" id="gdwn_remark_13" name="gdwn_remark_13"  style="text-align:left;"  value="<? echo $apa_remark['godown_remark_13']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Godown stock not exceed the licenced capacitty in last 12 Mths</td>
                                        <td class="center"><?= $row_1['godown_14'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['godown_14'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="godown_14" name="godown_14" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['godown_14'] ?>');
                       all_app_total('godown_', '14');"  value="<?php echo $row['godown_14']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" size="20" <?= $disable ?> maxlength="50" id="gdwn_remark_14" name="gdwn_remark_14"  style="text-align:left;"  value="<? echo $apa_remark['godown_remark_14']; ?>"/></td>
                                    </tr>
                                </table>
                            </div>
                            <div id="op_distribu" class="div_bordr">
                                <div class="bck_color_expand" id="two"><b><span id="title_padding">OPERATION OF DISTRIBUTORSHIP</span></b>
                                    <table border="0" cellpadding="0" cellspacing="0" style="float:right;">
                                        <tr>
                                            <td width="100" align="right">
                                                <b>12</b>
                                            </td>
                                            <td width="100" align="right">
                                                <b><? if ($total_op < 10) {
                                            echo '&nbsp;&nbsp;';
                                        } echo $total_op; ?></b>
                                            </td>
                                            <td width="100" align="right">
                                                <b><? if ($total_sales_op < 10) {
                                            echo '&nbsp;&nbsp;';
                                        } echo $total_sales_op; ?></b>
                                            </td>
                                            <td width="150" align="right"></td>
                                        </tr>
                                    </table></div>

                                <table class="first" id="opr_distr_list" border="0" cellpadding="2" cellspacing="1">
                                    <tr bgcolor="#edab55">
                                        <td width="100" align="center" ><b>SNO</b></td>
                                        <td width="350" align="center" ><b>ITEM</b></td>
                                        <td width="60" align="center" ><b>MAX MARKS</b></td>
                                        <td width="100" align="center"><b>DISTRIBUTOR</b></td>
                                        <td width="70" align="center" ><b>SALES OFFICER</b></td>
                                        <td width="120" align="center"><b>REMARKS</b></td>
                                    </tr>
                                    <tr>
                                        <td><b>DOCUMENTATION</b></td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>8</b></td>
                                        <td align="right" class="center"><input type="text" size="2" maxlength="3" readonly style="border:none; font-weight:bold;" id="dist_doc_total" name="dist_doc_total" value="<?= $row_sz['dist_doc'] ?>"/></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" id="doc_total" name="doc_total" readonly value="<?= $row['sales_doc'] ?>" style="border:none; font-weight:bold;"/></td>
                                        <td class="center"></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>

                                        <td>Mandatory manual stock book updated and tallies with Indosoft.</td>
                                        <td class="center"><?= $row_1['doc_1'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['doc_1'] ?></td>
                                        <td class="center"><input type="text" size="2" <?= $disable ?> maxlength="3" id="doc_1" name="doc_1" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['doc_1'] ?>');
                        all_app_total('doc_', '8');" value="<?php echo $row['doc_1']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" <?= $disable ?> size="20" maxlength="50" id="doc_remark_1" name="doc_remark_1"  style="text-align:left;"  value="<? echo $apa_remark['doc_remark_1']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Regular uploading of data in CMS</td>
                                        <td class="center"><?= $row_1['doc_2'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['doc_2'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="doc_2" name="doc_2" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['doc_2'] ?>');
                        all_app_total('doc_', '8');"  value="<?php echo $row['doc_2']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" size="20" <?= $disable ?> maxlength="50" id="doc_remark_2" name="doc_remark_2"  style="text-align:left;" value="<? echo $apa_remark['doc_remark_2']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Regular use of e-ledger</td>
                                        <td class="center"><?= $row_1['doc_3'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['doc_3'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" id="doc_3" <?= $disable ?> name="doc_3" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['doc_3'] ?>');
                        all_app_total('doc_', '8');" value="<?php echo $row['doc_3']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" size="20" maxlength="50" <?= $disable ?> id="doc_remark_3" name="doc_remark_3"  style="text-align:left;"  value="<? echo $apa_remark['doc_remark_3']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Proper filling of SV/TV documents along with back up papers</td>
                                        <td class="center"><?= $row_1['doc_4'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['doc_4'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="doc_4" name="doc_4" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['doc_4'] ?>');
                        all_app_total('doc_', '8');" value="<?php echo $row['doc_4']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input type="text" class="areaoremark" size="20" <?= $disable ?> maxlength="50" id="doc_remark_4" name="doc_remark_4"  style="text-align:left;" value="<? echo $apa_remark['doc_remark_4']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Acknowledge cash memos</td>
                                        <td class="center"><?= $row_1['doc_5'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['doc_5'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="doc_5" name="doc_5" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['doc_5'] ?>');
                        all_app_total('doc_', '8');" value="<?php echo $row['doc_5']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="doc_remark_5" name="doc_remark_5"  style="text-align:left;" value="<? echo $apa_remark['doc_remark_5']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Issuance of bills for all charges.</td>
                                        <td class="center"><?= $row_1['doc_6'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['doc_6'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="doc_6" name="doc_6" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['doc_6'] ?>');
                        all_app_total('doc_', '8');" value="<?php echo $row['doc_6']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="doc_remark_6" name="doc_remark_6"  style="text-align:left;" value="<? echo $apa_remark['doc_remark_6']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Documents signed by distributor</td>
                                        <td class="center"><?= $row_1['doc_7'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['doc_7'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="doc_7" name="doc_7" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['doc_7'] ?>');
                        all_app_total('doc_', '8');" value="<?php echo $row['doc_7']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="doc_remark_7" name="doc_remark_7" style="text-align:left;"  value="<? echo $apa_remark['doc_remark_7']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Weekly remittances in time</td>
                                        <td class="center"><?= $row_1['doc_8'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['doc_8'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="doc_8" name="doc_8" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['doc_8'] ?>');
                        all_app_total('doc_', '8');"  value="<?php echo $row['doc_8']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="doc_remark_8" name="doc_remark_8"  style="text-align:left;" value="<? echo $apa_remark['doc_remark_8']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td><b>CUSTOMER DATA UPDATION</b></td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>4</b></td>
                                        <td align="right" class="center"><input type="text" size="2"  maxlength="3" readonly style="border:none; font-weight:bold;" id="dist_custupdate_total" name="dist_custupdate_total" value="<?= $row_sz['dist_cust_update'] ?>"/></td>
                                        <td class="center"><input type="text" size="2" maxlength="3"  id="cust_update_total" name="cust_update_total" readonly value="<?= $row['sales_cust_update'] ?>" style="border:none; font-weight:bold;"/></td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <!--<tr>
                                      <td>&nbsp;</td>
                                      <td>% of customers with Tel. Nos updated&gt;50%-1Marks</td>
                                      <td class="center"><?= $row_1['cust_update_1'] ?></td>
                                      <td align="right" class="salez"><?= $row_sz['cust_update_1'] ?></td>
                                      <td class="center"><input type="text" size="2" maxlength="3" id="cust_update_1" name="cust_update_1" onkeypress="return validate(event);" onblur="ck_value(this.id,'<?= $row_1['cust_update_1'] ?>'); all_app_total('cust_update_','4');" value="<?php echo $row['cust_update_1']; ?>" <?= $readonly ?>/></td>
                                      <td align="center" class="areao"><input type="text" size="20" maxlength="50" id="update_remark_1" name="update_remark_1" style="text-align:left;"  value="<? echo $apa_remark['cust_update_remark_1']; ?>"/></td>
                                    </tr>-->
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>% of customers with Tel. Nos updated &gt;50% and &lt;80% = 1 Marks</td>
                                        <td class="center"><?= $row_1['cust_update_2'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['cust_update_2'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="cust_update_2" name="cust_update_2" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['cust_update_2'] ?>');
                        all_app_total('cust_update_', '4');" value="<?php echo $row['cust_update_2']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" <?= $disable ?> size="20" maxlength="50" id="update_remark_2" name="update_remark_2"  style="text-align:left;" value="<? echo $apa_remark['cust_update_remark_2']; ?>"/></td>
                                    </tr>
                                    <!--<tr>
                                      <td>&nbsp;</td>
                                      <td>% of customers with complete address updated&gt;50%-1Marks</td>
                                      <td class="center"><?= $row_1['cust_update_3'] ?></td>
                                      <td align="right" class="salez"><?= $row_sz['cust_update_3'] ?></td>
                                      <td class="center"><input type="text" size="2" maxlength="3" id="cust_update_3" name="cust_update_3" onkeypress="return validate(event);" onblur="ck_value(this.id,'<?= $row_1['cust_update_3'] ?>'); all_app_total('cust_update_','4');" value="<?php echo $row['cust_update_3']; ?>" <?= $readonly ?>/></td>
                                      <td align="center" class="areao"><input type="text" size="20" maxlength="50" id="update_remark_3" name="update_remark_3" style="text-align:left;"  value="<? echo $apa_remark['cust_update_remark_3']; ?>"/></td>
                                    </tr>-->
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>% of customers with complete address updated &gt;50% and &lt;80% = 1 Marks</td>
                                        <td class="center"><?= $row_1['cust_update_4'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['cust_update_4'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="cust_update_4" name="cust_update_4" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['cust_update_4'] ?>');
                        all_app_total('cust_update_', '4');" value="<?php echo $row['cust_update_4']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text"<?= $disable ?> size="20" maxlength="50" id="update_remark_4" name="update_remark_4"  style="text-align:left;" value="<? echo $apa_remark['cust_update_remark_4']; ?>"/></td>
                                    </tr>
                                </table>


                            </div>
                            <div id="sales" class="div_bordr">
                                <div class="bck_color_expand" id="three"><b><span id="title_padding">SALES</span></b>
                                    <table border="0" cellpadding="0" cellspacing="0"  style="float:right;">
                                        <tr>
                                            <td width="100"align="right">
                                                <b>45</b>
                                            </td>
                                            <td width="100" align="right">
                                                <b><? if ($total_sls < 10) {
                                            echo '&nbsp;&nbsp;';
                                        } echo $total_sls; ?></b>
                                            </td>
                                            <td width="100" align="right">
                                                <b><? if ($total_sales_sls < 10) {
                                            echo '&nbsp;&nbsp;';
                                        } echo $total_sales_sls; ?></b>
                                            </td>
                                            <td width="150" align="right"></td>
                                        </tr>
                                    </table></div>

                                <table class="first" id="sales_list" border="0" cellpadding="2" cellspacing="1">
                                    <tr bgcolor="#edab55">
                                        <td width="100" align="center" ><b>SNO</b></td>
                                        <td width="350" align="center" ><b>ITEM</b></td>
                                        <td width="60" align="center"><b>MAX MARKS</b></td>
                                        <td width="100" align="center"><b>DISTRIBUTOR</b></td>
                                        <td width="60" align="center"><b>SALES OFFICER</b></td>
                                        <td width="80" align="center"><b>REMARKS</b></td>
                                    </tr>
                                    <tr>
                                        <td><b>NDNE</b></td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>10</b></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" readonly style="border:none; font-weight:bold;" id="dist_ndne_total" name="dist_ndne_total" value="<?= $row_sz['dist_ndne'] ?>"/></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" id="ndne_total" name="ndne_total" value="<?= $row['sales_ndne'] ?>" readonly style="border:none; font-weight:bold;"/></td>
                                        <td class="center"></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Utilization of equipment- Ratio of refills to cylinders&gt;AreaOffice average</td>
                                        <td class="center"><?= $row_1['ndne_1'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['ndne_1'] ?></td>
                                        <td  class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> name="ndne_1" id="ndne_1" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['ndne_1'] ?>');
                       all_app_total('ndne_', '2');"  value="<?php echo $row['ndne_1']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="ndne_remark_1" name="ndne_remark_1" style="text-align:left;"  value="<? echo $apa_remark['ndne_remark_1']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Registered Gth in % of NDNE sales to total sale over LY</td>
                                        <td class="center"><?= $row_1['ndne_2'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['ndne_2'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> name="ndne_2" id="ndne_2" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['ndne_2'] ?>');
                       all_app_total('ndne_', '2');"  value="<?php echo $row['ndne_2']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="ndne_remark_2" name="ndne_remark_2" style="text-align:left;"  value="<? echo $apa_remark['ndne_remark_2']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td align="right" class="salez">&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td align="center" class="areao">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><b>DOM</b></td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>5</b></td>
                                        <td align="right" class="center"><input type="text" size="2" maxlength="3" id="dist_dom" name="_dist_dom" readonly value="<?= $row['dom_1'] ?>" style="border:none; font-weight:bold;"/></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" id="dom_total" name="dom_total" readonly value="<?= $row['dom_1'] ?>" style="border:none; font-weight:bold;"/></td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Backlog free without increase Per Cap</td>
                                        <td class="center"><?= $row_1['dom_1'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['dom_1'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> name="dom_1" id="dom_1" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['dom_1'] ?>');
                       all_app_total('dom_', '1');"  value="<?php echo $row['dom_1']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" size="20"<?= $disable ?> maxlength="50" id="dom_remark_1" name="dom_remark_1"  style="text-align:left;" value="<? echo $apa_remark['dom_remark_1']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td align="right">&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td align="right">&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><b>NFR</b></td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>4</b></td>
                                        <td align="right" class="center"><input type="text" size="2" maxlength="3" readonly style="border:none; font-weight:bold;" id="dist_godown" name="dist_godown" value="<?= $row_sz['dist_nfr'] ?>"/></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" id="nfr_total" name="nfr_toatal" readonly value="<?= $row['sales_nfr'] ?>" style="border:none; font-weight:bold;"/></td>
                                        <td class="center"></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Growth over last year</td>
                                        <td class="center"><?= $row_1['nfr_1'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['nfr_1'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="nfr_1" name="nfr_1" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['nfr_1'] ?>');
                       all_app_total('nfr_', '2');"  value="<?php echo $row['nfr_1']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="nfr_remark_1" name="nfr_remark_1"  style="text-align:left;" value="<? echo $apa_remark['nfr_remark_1']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Same as last tear</td>
                                        <td class="center"><?= $row_1['nfr_2'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['nfr_2'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="nfr_2" name="nfr_2" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['nfr_2'] ?>');
                       all_app_total('nfr_', '2');"  value="<?php echo $row['nfr_2']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="nfr_remark_2" name="nfr_remark_2"  style="text-align:left;"  value="<? echo $apa_remark['nfr_remark_2']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td align="right" class="salez">&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td align="center" class="areao">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><b>NDE</b></td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>1</b></td>
                                        <td align="right" class="center"><input type="text" size="2" maxlength="3" id="dist_nde_total" name="dist_nde_total" readonly value="<?= $row['nde_1'] ?>" style="border:none; font-weight:bold;"/></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" id="nde_total" name="nde_total" readonly value="<?= $row['nde_1'] ?>" style="border:none; font-weight:bold;"/></td>
                                        <td class="center"></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Cheque payment from NDE customers</td>
                                        <td class="center"><?= $row_1['nde_1'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['nde_1'] ?></td>
                                        <td class="center"><input type="text" id="nde_1" name="nde_1" <?= $disable ?> size="2" maxlength="3" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['nde_1'] ?>');
                       all_app_total('nde_', '1');"   value="<?php echo $row['nde_1']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="nde_remark_1" name="nde_remark_1" style="text-align:left;"  value="<? echo $apa_remark['nde_remark_1']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td align="right" class="center">&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><b>CUSTOMER ORIENTATION</b></td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>7</b></td>
                                        <td align="right" class="center"><input type="text" size="2"  maxlength="3" id="dist_cust_orientation" name="dist_cust_orientation" readonly value="<?= $row_sz['dist_cust_orientation'] ?>" style="border:none; font-weight:bold;"/></td>
                                        <td class="center"><input type="text" size="2" maxlength="3"  id="cust_orientation_total" name="cust_orientation_total" readonly value="<?= $row['sales_cust_orientation'] ?>" style="border:none; font-weight:bold;"></td>
                                        <td class="center"></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Confirmation of TV through e-Ledger</td>
                                        <td class="center"><?= $row_1['cust_orientation_1'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['cust_orientation_1'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="cust_orientation_1" name="cust_orientation_1" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['cust_orientation_1'] ?>');
                       all_app_total('cust_orientation_', '6');"  value="<?php echo $row['cust_orientation_1']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input  class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="orient_remark_1" name="orient_remark_1"  style="text-align:left;"  value="<? echo $apa_remark['cust_orientation_remark_1']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>% os SMS/IVRS/WEB/Call Center-booking in total &gt;=30%</td>
                                        <td class="center"><?= $row_1['cust_orientation_2'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['cust_orientation_2'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="cust_orientation_2" name="cust_orientation_2" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['cust_orientation_2'] ?>');
                       all_app_total('cust_orientation_', '6');" value="<?php echo $row['cust_orientation_2']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" size="20" maxlength="50" <?= $disable ?> id="orient_remark_2" name="orient_remark_2"  style="text-align:left;"  value="<? echo $apa_remark['cust_orientation_remark_2']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Availabilitty of suggestion &amp; complaint book</td>
                                        <td class="center"><?= $row_1['cust_orientation_3'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['cust_orientation_3'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="cust_orientation_3" name="cust_orientation_3" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['cust_orientation_3'] ?>');
                       all_app_total('cust_orientation_', '6');" value="<?php echo $row['cust_orientation_3']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" <?= $disable ?> size="20" maxlength="50" id="orient_remark_3" name="orient_remark_3" style="text-align:left;"  value="<? echo $apa_remark['cust_orientation_remark_3']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Nil complaint in toll free number</td>
                                        <td class="center"><?= $row_1['cust_orientation_4'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['cust_orientation_4'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="cust_orientation_4" name="cust_orientation_4" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['cust_orientation_4'] ?>');
                       all_app_total('cust_orientation_', '6');"  value="<?php echo $row['cust_orientation_4']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input  class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="orient_remark_4" name="orient_remark_4" style="text-align:left;"  value="<? echo $apa_remark['cust_orientation_remark_4']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Delivery confirmation through SMS</td>
                                        <td class="center"><?= $row_1['cust_orientation_5'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['cust_orientation_5'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="cust_orientation_5" name="cust_orientation_5" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['cust_orientation_5'] ?>');
                       all_app_total('cust_orientation_', '6');" value="<?php echo $row['cust_orientation_5']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="orient_remark_5" name="orient_remark_5" style="text-align:left;"  value="<? echo $apa_remark['cust_orientation_remark_5']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Pre delivery checks of weight and leakage at customer premises</td>
                                        <td class="center"><?= $row_1['cust_orientation_6'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['cust_orientation_6'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="cust_orientation_6" name="cust_orientation_6" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['cust_orientation_6'] ?>');
                       all_app_total('cust_orientation_', '6');" value="<?php echo $row['cust_orientation_6']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="orient_remark_6" name="orient_remark_6" style="text-align:left;"  value="<? echo $apa_remark['cust_orientation_remark_6']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td align="right" class="center">&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><b>HOME DELIVERY</b></td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>4</b></td>
                                        <td align="right" class="center"><input type="text" size="2" maxlength="3" id="dist_del_total" name="dist_del_total" readonly value="<?= $row_sz['dist_home_del'] ?>" style="border:none; font-weight:bold;"/></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" id="home_del_total" name="home_del_total" readonly value="<?= $row['sales_home_del'] ?>" style="border:none; font-weight:bold;"/></td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>100% including authorised CNC</td>
                                        <td class="center"><?= $row_1['home_del_1'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['home_del_1'] ?></td>
                                        <td class="center"><input type="text" name="home_del_1" <?= $disable ?> id="home_del_1" size="2" maxlength="3" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['home_del_1'] ?>');
                       all_app_total('home_del_', '3');" value="<?php echo $row['home_del_1']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" <?= $disable ?> size="20" maxlength="50" id="home_remark_1" name="home_remark_1"  style="text-align:left;"  value="<? echo $apa_remark['home_del_remark_1']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&lt; 100% including authorised CNC</td>
                                        <td class="center"><?= $row_1['home_del_2'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['home_del_2'] ?></td>
                                        <td class="center"><input type="text" name="home_del_2" id="home_del_2" size="2" <?= $disable ?> maxlength="3" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['home_del_2'] ?>');
                       all_app_total('home_del_', '3');" value="<?php echo $row['home_del_2']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="home_remark_2" name="home_remark_2"  style="text-align:left;"  value="<? echo $apa_remark['home_del_remark_2']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Delivery hours adjusted for convenience of working couples</td>
                                        <td class="center"><?= $row_1['home_del_3'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['home_del_3'] ?></td>
                                        <td class="center"><input type="text" name="home_del_3" id="home_del_3" size="2" <?= $disable ?> maxlength="3" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['home_del_3'] ?>');
                       all_app_total('home_del_', '3');" value="<?php echo $row['home_del_3']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="home_remark_3" name="home_remark_3"  style="text-align:left;"  value="<? echo $apa_remark['home_del_remark_3']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td align="right" class="salez">&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td align="center" class="areao">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><b>LEAKAGE</b></td>
                                        <td>&nbsp;</td>
                                        <td  class="center"><b>3</b></td>
                                        <td align="right" class="center"><input type="text" size="2"  maxlength="3" id="dist_lekage_total" name="dist_lekage_total" readonly value="<?= $row_sz['dist_leakage'] ?>" style="border:none; font-weight:bold;"/></td>
                                        <td class="center"><input type="text" size="2" maxlength="3"  id="leakage_total" name="leakage_total" readonly value="<?= $row['sales_leakage'] ?>" style="border:none; font-weight:bold;"/></td>
                                        <td class="center"></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Leakage complaints attended within one to two hour</td>
                                        <td class="center"><?= $row_1['leakage_1'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['leakage_1'] ?></td>
                                        <td class="center"><input type="text" name="leakage_1" id="leakage_1" <?= $disable ?> size="2" maxlength="3" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['leakage_1'] ?>');
                       all_app_total('leakage_', '3');" value="<?php echo $row['leakage_1']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="leakage_remark_1" name="leakage_remark_1"  style="text-align:left;"  value="<? echo $apa_remark['leakage_remark_1']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Beyond 2 to 8 hours</td>
                                        <td class="center"><?= $row_1['leakage_2'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['leakage_2'] ?></td>
                                        <td class="center"><input type="text" name="leakage_2" <?= $disable ?> id="leakage_2" size="2" maxlength="3" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['leakage_2'] ?>');
                       all_app_total('leakage_', '3');"  value="<?php echo $row['leakage_2']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" <?= $disable ?> size="20" maxlength="50" id="leakage_remark_2" name="leakage_remark_2"  style="text-align:left;" value="<? echo $apa_remark['leakage_remark_2']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Beyond 8 hours</td>
                                        <td class="center"><?= $row_1['leakage_3'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['leakage_3'] ?></td>
                                        <td class="center"><input type="text" name="leakage_3" <?= $disable ?> id="leakage_3" size="2" maxlength="3" onkeypress="return validate(event);"  onblur="ck_value(this.id, '<?= $row_1['leakage_3'] ?>');
                       all_app_total('leakage_', '3');"  value="<?php echo $row['leakage_3']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input  class="areaoremark" type="text"<?= $disable ?> size="20" maxlength="50" id="leakage_remark_3" name="leakage_remark_3"  style="text-align:left;"  value="<? echo $apa_remark['leakage_remark_3']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td align="right" class="center">&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td align="center" class="areao">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><b>SAFETY</b></td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>6</b></td>
                                        <td align="right" class="center"><input type="text" size="2" maxlength="3" id="dist_safety_total" name="dist_safety_total" readonly value="<?= $row_sz['dist_safety'] ?>" style="border:none; font-weight:bold;"/></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" id="safety_total" name="safety_total" readonly value="<?= $row['sales_safety'] ?>" style="border:none; font-weight:bold;"/></td>
                                        <td class="center"></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>No. of O ring leak complaints from customers</td>
                                        <td class="center"><? //=$row_1['safety_1'] ?></td>
                                        <td align="right" class="salez"><? //=$row_sz['safety_1'] ?></td>
                                        <td class="center"><input type="hidden" size="2" maxlength="3" <?= $disable ?> id="safety_1" name="safety_1" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['safety_1'] ?>');
                       all_app_total('safety_', '4');" value="0" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input  class="areaoremark" type="hidden" size="20" <?= $disable ?> maxlength="50" id="safety_remark_1" name="safety_remark_1"  style="text-align:left;"  value="<? echo $apa_remark['safety_remark_1']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&lt;2 % of deliveries =1Marks</td>
                                        <td class="center"><?= $row_1['safety_2'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['safety_2'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="safety_2" name="safety_2" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['safety_2'] ?>');
                       all_app_total('safety_', '4');"  value="<?php echo $row['safety_2']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="safety_remark_2" name="safety_remark_2"  style="text-align:left;" value="<? echo $apa_remark['safety_remark_2']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&gt;2 % of deliveries =-1Marks</td>
                                        <td class="center"><?= $row_1['safety_3'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['safety_3'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="safety_3" name="safety_3" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['safety_3'] ?>');
                       all_app_total('safety_', '4');"  value="<?php echo $row['safety_3']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="safety_remark_3" name="safety_remark_3" style="text-align:left;"  value="<? echo $apa_remark['safety_remark_3']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Customer education programs- Min 3 programs1 marks, for each addl. 1 more upto a max of 5</td>
                                        <td class="center"><?= $row_1['safety_4'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['safety_4'] ?></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" <?= $disable ?> id="safety_4" name="safety_4" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['safety_4'] ?>');
                       all_app_total('safety_', '4');" value="<?php echo $row['safety_4']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="safety_remark_4" name="safety_remark_4"  style="text-align:left;" value="<? echo $apa_remark['safety_remark_4']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td align="right" class="center">&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><p><b>MANAGEMENT</b></p>               </td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>5</b></td>
                                        <td align="right" class="center"><input type="text" size="2" maxlength="3" id="dist_mang_total" name="dist_mang_total" readonly value="<?= $row_sz['dist_mang'] ?>" style="border:none; font-weight:bold;"/></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" id="mang_total" name="mang_total" readonly value="<?= $row['sales_mang'] ?>" style="border:none; font-weight:bold;"/></td>
                                        <td class="center"></td>
                                    </tr>
                                    <tr>
                                        <td><p>&nbsp;</p>               </td>
                                        <td>Management by distributor</td>
                                        <td align="right">&nbsp;</td>
                                        <td align="right" class="center">&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Personal full time</td>
                                        <td class="center"><?= $row_1['mang_1'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['mang_1'] ?></td>
                                        <td class="center"><input type="text" id="mang_1" <?= $disable ?> name="mang_1" size="2" maxlength="3" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['mang_1'] ?>');
                       all_app_total('mang_', '3');"  value="<?php echo $row['mang_1']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input  class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="mang_remark_1" name="mang_remark_1" style="text-align:left;"  value="<? echo $apa_remark['mang_remark_1']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Half day</td>
                                        <td class="center"><?= $row_1['mang_2'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['mang_2'] ?></td>
                                        <td class="center"><input type="text" id="mang_2" name="mang_2" <?= $disable ?> size="2" maxlength="3" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['mang_2'] ?>');
                       all_app_total('mang_', '3');" value="<?php echo $row['mang_2']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="mang_remark_2" name="mang_remark_2" style="text-align:left;"  value="<? echo $apa_remark['mang_remark_2']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Less than half day</td>
                                        <td class="center"><?= $row_1['mang_3'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['mang_3'] ?></td>
                                        <td class="center"><input type="text" id="mang_3" name="mang_3" size="2" <?= $disable ?> maxlength="3" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['mang_3'] ?>');
                       all_app_total('mang_', '3');" value="<?php echo $row['mang_3']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="mang_remark_3" name="mang_remark_3" style="text-align:left;"  value="<? echo $apa_remark['mang_remark_3']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td align="right" class="salez">&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td align="center" class="areao">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><b>RMDG IMPOSTION</b></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td align="right" class="center"><input type="text" size="2" maxlength="3" id="dist_rmdg_total" name="dist_rmdg_total" readonly value="<?= $row_sz['dist_rmdg'] ?>" style="border:none; font-weight:bold;"/></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" id="rmdg_total" name="rmdg_total" readonly value="<?= $row['sales_rmdg'] ?>" style="border:none; font-weight:bold;"/></td>
                                        <td class="center"></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>For each instance of MDG impostion-3 marks(Major-3,Minor-1)</td>
                                        <td class="center"><?= $row_1['rmdg_1'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['rmdg_1'] ?></td>
                                        <td class="center"><input type="text" id="rmdg_1" name="rmdg_1" <?= $disable ?>  size="2" maxlength="3" onkeypress="return validate(event);" onblur="ck_value_range(-3, 0);
                       all_app_total('rmdg_', '4');" value="<?php echo $row['rmdg_1']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="rmdg_remark_1" name="rmdg_remark_1" style="text-align:left;"  value="<? echo $apa_remark['rmdg_remark_1']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Shortage of equipment</td>
                                        <td class="center"><?php //echo $row_1['rmdg_2']; ?></td>
                                        <td align="right" class="salez"><?php //echo $row_sz['rmdg_2']; ?></td>
                                        <td class="center"><input type="hidden" id="rmdg_2" name="rmdg_2" <?= $disable ?> size="2" maxlength="3" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['rmdg_2'] ?>');
                       all_app_total('rmdg_', '4');"  value="0" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="hidden" size="20" maxlength="50" id="rmdg_remark_2" name="rmdg_remark_2" style="text-align:left;"  value=""/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Reported theft 1st theft =0</td>
                                        <td class="center"><?= $row_1['rmdg_3'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['rmdg_3'] ?></td>
                                        <td class="center"><input type="text" id="rmdg_3" name="rmdg_3" <?= $disable ?>  size="2" maxlength="3" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['rmdg_3'] ?>');
                       all_app_total('rmdg_', '4');"  value="<?php echo $row['rmdg_3']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="rmdg_remark_3" name="rmdg_remark_3"  style="text-align:left;"  value="<? echo $apa_remark['rmdg_remark_3']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td style="padding-left:90px;">2nd theft = -3  </td>
                                        <td class="center"><?= $row_1['rmdg_4'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['rmdg_4'] ?></td>
                                        <td class="center"><input type="text" id="rmdg_4" name="rmdg_4" <?= $disable ?> size="2" maxlength="3" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['rmdg_4'] ?>');
                       all_app_total('rmdg_', '4');" value="<?php echo $row['rmdg_4']; ?>" <?= $readonly ?>/></td>
                                        <td align="center" class="areao"><input class="areaoremark" type="text" size="20" <?= $disable ?> maxlength="50" id="rmdg_remark_4" name="rmdg_remark_4"  style="text-align:left;" value="<? echo $apa_remark['rmdg_remark_4']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                            </div>
                            <div id="attitude" class="div_bordr">
                                <div class="bck_color_expand" id="four"><b><span id="title_padding">ATTITUDE</span></b>
                                    <table border="0" cellpadding="0" cellspacing="0"  style="float:right;">
                                        <tr>
                                            <td width="100" align="right">&nbsp;&nbsp;
                                                <b>5</b>
                                            </td>
                                            <td width="100" align="right">&nbsp;&nbsp;
                                                <b><? echo $row_sz['attitude']; ?></b>
                                            </td>
                                            <td width="100" align="right">&nbsp;&nbsp;
                                                <b><? echo $row['attitude']; ?></b>
                                            </td>
                                            <td width="150" align="right"></td>
                                        </tr>
                                    </table></div>

                                <table class="first" id="attitude_list" cellpadding="2" cellspacing="1" border="0">
                                    <tr bgcolor="#edab55">
                                        <td width="100" align="center" ><b>SNO</b></td>
                                        <td width="360" align="center"><b>ITEM</b></td>
                                        <td width="70" align="center"><b>MAX MARKS</b></td>
                                        <td width="100" align="center"><b>DISTRIBUTOR</b></td>
                                        <td width="60" align="center" ><b>SALES OFFICER</b></td>
                                        <td width="80" align="center"><b>REMARKS</b></td>
                                    </tr>
                                    <tr>
                                        <td><b>ATTITUDE</b></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td >&nbsp;</td>
                                        <td >Complaince of shortcomings and non-repetition</td>
                                        <td align="right" ><?= $row_1['attitude'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['attitude'] ?></td>
                                        <td  class="center"><input type="text" id="attitude" name="attitude" size="2" maxlength="3" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['attitude'] ?>');" <?= $disable ?> value="<?php echo $row['attitude']; ?>" <?= $readonly ?>/> </td>

                                        <td align="center" class="areao" width="120"><input class="areaoremark" type="text" <?= $disable ?> size="20" maxlength="50" id="attitude_remark" name="attitude_remark"  style="text-align:left;" value="<? echo $apa_remark['attitude_remark']; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>

                                </table>
                            </div>
                            <div id="innovative_initiative" class="div_bordr">
                                <div class="bck_color_expand" id="five"><b><span id="title_padding">INNOVATIVE INITATIVES</span></b>
                                    <table border="0" cellpadding="0" cellspacing="0" style="float:right;">
                                        <tr>
                                            <td width="100" align="right">&nbsp;&nbsp;
                                                <b>5</b>
                                            </td>
                                            <td width="100" align="right">&nbsp;&nbsp;
                                                <b><? echo $row_sz['inovative_init']; ?></b>
                                            </td>
                                            <td width="100" align="right">&nbsp;&nbsp;
                                                <b><? echo $row['inovative_init']; ?></b>
                                            </td>
                                            <td width="150" align="center" ><strong><?= $row_aos['inovative_init'] ?></strong></td>
                                        </tr>
                                    </table></div>

                                <table class="first" id="inov_list" cellspacing="1" cellpadding="2" border="0">
                                    <tr bgcolor="#edab55">
                                        <td width="100" align="center" ><b>SNO</b></td>
                                        <td width="350" align="center" ><b>ITEM</b></td>
                                        <td width="60" align="center" ><b>MAX MARKS</b></td>
                                        <td width="100" align="center" ><b>DISTRIBUTOR</b></td>
                                        <td width="70" align="center" ><b>SALES OFFICER</b></td>
                                        <td width="80" align="center"><b>REMARKS</b></td>
                                        <td width="70" align="center"><b>AREA OFFICER</b></td>
                                    </tr>
                                    <tr>
                                        <td >                       </td>
                                        <td >
                                            To be assigned by office based on claim from distributor recommened by FO               </td>
                                        <td align="right" ><?= $row_1['inovative_init'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['inovative_init'] ?></td>
                                        <td  class="center"><input type="text" name="inovative_init" <?= $disable ?> id="inovative_init" size="2" maxlength="3" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['inovative_init'] ?>');
                      " value="<?php echo $row['inovative_init']; ?>" /></td>

                                        <td align="center" class="areao" width="120"><input class="areaoremark" type="text" <?= $disable ?> size="20" maxlength="50" id="inov_remark"  name="inov_remark"  style="text-align:left;"  value="<? echo $apa_remark['inov_remark']; ?>"/></td>
                                        <td align="right" style="padding-right:25px;"><?= $row_aos['inovative_init'] ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>

    <!--<tr>
      <td></td>
      <td align="right"><strong>Total</strong></td>
      <td>&nbsp;</td>
      <td class="center"><strong>100</strong></td>

      <td class="center"><?= $row_sz['sum_d']; ?></td>
       <td class="center"><?= $row['sum_d']; ?></td>
      <td class="center"></td>
    </tr>-->
                                </table>

                            </div>
                        </div>
                        <div>
                            <table border="0" cellpadding="0" cellspacing="0" width="425px" style="margin-left:400px;">
                                <tr>
                                    <td width="90" align="right"><b>Grand Total</b></td>
                                    <td width="100" align="right"><b>100</b> </td>
                                    <td width="140" align="right">&nbsp;&nbsp;
                                        <b><? echo $total_sh + $total_op + $total_sls + $row_sz['inovative_init'] + $row_sz['attitude']; ?></b>
                                    </td>
                                    <td width="140" align="right">&nbsp;&nbsp;
                                        <b><? echo $total_sales_sh + $total_sales_op + $total_sales_sls + $row['attitude'] + $row['inovative_init']; ?></b>
                                    </td>
                                    <td width="100" align="right"><strong><?= $row_aos['inovative_init'] ?></strong></td>
                                </tr>
                            </table>
                        </div>

                        <table width="40%" border="0" align="center" cellpadding="0" cellspacing="0">

                            <tr>

                                <td>&nbsp;</td>

                                <td>&nbsp;</td>

                                <td>&nbsp;</td>
                            </tr>

<? //echo $cnt_ao."==".$nodeditable;
if ($cnt_ao < 1 && $nodeditable == 0) {
    ?>
                                <tr>

                                    <td width="150" align="right"><? if ($cnt_dis < 1) { ?><label><input type="submit" name="Submit" class="inputbuttonblue" style="margin-right:10px;" value="Save"  /></label><? } ?></td>

                                    <td  align="left" colspan="2"><?
    //echo $row_sz['status'];
    if ($distributer_status == 1 && $distributer_record == 1) {
        if ($row['status'] == 0) {
            ?> <input type="button" name="Submit_form" <?= $disable ?> class="inputbutton" value="Freeze"  onclick="submit_form('1');" />
                                    <? }
                                    else if ($row_sz['status'] == 1) {
                                        echo "<span style='color:#FF4800;width:50px;padding-left:-50px;'>APA form has been freezed.</span> ";
                                    }
                                }
                                else if ($distributer_status == 0) { 
										echo 'Freeze option is available once distributer freezed his status.';
                                            /*<input type="button" name="Submit_form" class="inputbutton" value="Freeze"  onclick=" alert('Distributor status is not freezed.')" />*/
                                         }
                                        else if ($distributer_record == 0) { 
										echo '';
                                            /*<input type="button" name="Submit_form" class="inputbutton" value="Freeze"  onclick=" alert('Please fill your form first.')" />*/
                                         } ?>
                                    </td>

            <!--  <td  align="left"><? if ($row["status"] == 1) { ?> <input type="button" name="Submit_form" class="mybutton" value="Unfreeze"  onclick="submit_form('0');" /><? }
                                else { ?><input type="button" name="Submit_form" class="mybutton" value="Submit"  onclick="submit_form('1');" /><? } ?></td>-->
                                </tr>
<? } ?>
                        </table>
                        <input name="mode" type="hidden" id="mode" value="add">

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>



<?
include_once("includes/footer.php");
?>

