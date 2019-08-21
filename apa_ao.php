<?php
include_once("includes/header.php");
//print_r($_SESSION);exit;
//print_r($_REQUEST);exit;

if (!isset($_REQUEST['arch_year'])) {
    $_REQUEST['arch_year'] = date('Y')-1;
}

//echo $_POST["mode"];exit();
$fin_year = $_REQUEST['arch_year'];
if (isset($_POST["mode"]) && $_POST["mode"] == "add") {
    
    if($isp_type != 'AO'){
        echo '<script type="text/javascript">window.location="main.php";</script>';
        exit;
    }
    
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


    $sql = mysql_query("select * from gb_ada_marks where gb_type='AO' and distributor_id='" . $_REQUEST["id"] . "' and year='" . $fin_year . "'");

    $cnt_d = mysql_num_rows($sql);
    //echo $cnt_d;exit();
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
					where gb_type='AO' and distributor_id='" . $_REQUEST["id"] . "' and year='" . $fin_year . "' ";
        mysql_query($sql);
    }
    else {

        $sql = "insert into gb_ada_marks set
				    gb_type='AO',
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
        //echo $sql;exit();
        mysql_query($sql) or die(mysql_error());
    }
}


if (isset($_POST["disablestatus"]) && $_POST["disablestatus"] == 1) {

    //$fin_year=date('Y',mktime(0, 0, 0, date('m')-3, date('d'),date('y')))-1;
    $fin_year = $_REQUEST['arch_year'];
    mysql_query("update gb_ada_marks set status='1' where distributor_id ='" . $_REQUEST["id"] . "' and year='" . $fin_year . "'");
}
if (isset($_POST["Unfreeze"]) && $_POST["Unfreeze"] == 1) {
    mysql_query("update gb_ada_marks set status='0' where distributor_id ='" . $_REQUEST["id"] . "' and gb_type='D' ");
}
$salesofficer_status = '';
//echo "select *  from gb_ada_marks where distributor_id ='" . $_REQUEST["id"] . "' and   gb_type= 'SZ' and status='1' and year='" . $fin_year . "' ";
$dist_info = mysql_query("select *  from gb_ada_marks where distributor_id ='" . $_REQUEST["id"] . "' and   gb_type= 'SZ' and status='1' and year='" . $fin_year . "' ");

$num_rows = mysql_num_rows($dist_info);
if ($num_rows > 0) {
    $salesofficer_status = 1;
}
else {
    $salesofficer_status = 0;
}

$salesofficer_record = '';

$dist_record = mysql_query("select *  from gb_ada_marks where distributor_id ='" . $_REQUEST["id"] . "'   and gb_type= 'AO' ");

$num_records = mysql_num_rows($dist_record);
if ($num_records > 0) {
    $salesofficer_record = 1;
}
else {
    $salesofficer_record = 0;
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
    {
        text-align:right;
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
        background-color:#e5e5e5;
        border:#FFFFFF 1px solid;
        padding-top:5px;
        padding-bottom:5px;
        padding-left:20px;
        cursor:pointer;
        font-size:13px;
        /*background-image:url(../images/plus.gif);*/
        background-image:url(../images/minus.gif);
        background-repeat:no-repeat;
        /*background-position:840px;*/
        background-position:5px;
    }

    .bck_color_expand
    {
        background-color:#e5e5e5;
        border:#FFFFFF 1px solid;
        padding-top:5px;
        padding-bottom:5px;
        padding-left:20px;
        cursor:pointer;
        font-size:13px;
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
		jQuery('form#apaform input').each(
			function(index){  
				var input = jQuery(this);
				if (input.attr('type') == "text" && input.val() == "" && input.attr("readonly")!="readonly" ) {
					allow_submit = 0;
				}
			}
		);
		
		if (allow_submit == 0) {
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
            if (txtObjList[i].getAttribute("type") == "text" && (!txtObjList[i].hasAttribute("readonly") && txtObjList[i].getAttribute("readonly") != "readonly")) {
                if (document.getElementById(txtObjList[i].getAttribute("id")).value != "") {
                    val = 1;
                }
                else
                {
                   //// alert(" Your APA form is not completed yet.Please  fill all the marks and press save button. ");
                    ////return false;
					 val = 1;
                }
            }
        }
        if (val == 1)
            return true;

    }  */

    function validate(key)
    {
        var keycode = (key.which) ? key.which : key.keyCode;
        //alert(keycode);
        if (keycode == 47)
        {
            return false;
        }
        else if ((keycode > 44 && keycode < 58) || (keycode == 8))
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

        if (val == 1) {
            var aa = confirm('This  will save the values given by you in the form and will freeze it. You will not be able to edit and continue filling this form once freezed by you.');
        }

        if (aa) {

            document.getElementById('disablestatus').value = val;
            var check = validationform();
            if (check == true)
            {
                document.freezeform.submit();
            }
        }
    }
    function unfreeze(val) {
        if (confirm('Are you sure, you want to unfreeze this distributer ?'))
        {
            document.getElementById('Unfreeze').value = val;
            document.apaform.submit();
        }

    }

    /*function submit_form(val){

     if(val==1){
     var aa=confirm('Are you sure, you want to freeze this form ?');
     }else{

     var aa=confirm('Are you sure, you want to unfreeze this form again ?');
     }

     if(aa){

     document.getElementById('disablestatus').value=val;

     document.freezeform.submit();
     }

     }
     */
    function getarchyear() {

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

//	$readonly='readonly';
    //$nodeditable=1;
}
else {

    //$fin_year=date('Y',mktime(0, 0, 0, date('m')-3, date('d'),date('y')))-1;
    $fin_year = $_REQUEST['arch_year'];

    $fin_year_add = date('y', mktime(0, 0, 0, date('m') - 3, date('d'), date('y')));
    //$fin_year_add="2011";
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
<?
$sql = "select dealer_name from gb_company_dealer where id= " . $_REQUEST["id"] . "  ";
$query2 = mysql_query($sql);
$result = mysql_fetch_array($query2);
?>
                <h3 class="heading1"><?= $result["dealer_name"] ?> - Annual Performance Appraisal FORM - <?php echo $fin_year; ?>-<?php echo $fin_year + 1; ?></h3>
                <div class="panel padding30px"  style="width:910px;">


<?php
$sql_admin = mysql_query("select * from gb_ada_marks where id='1'");

$row_1 = mysql_fetch_assoc($sql_admin);



// $readonly='';

$sql_disable = mysql_query("select * from gb_ada_marks where distributor_id='" . $_REQUEST["id"] . "' and status='1' and gb_type='AO' and year='" . $fin_year . "'");

$cnt_dis = mysql_num_rows($sql_disable);
if ($cnt_dis > 0) {
    //$readonly='readonly';
}



$sql_ao = mysql_query("select * from gb_ada_remarks where distributor_id='" . $_REQUEST["id"] . "' and year='" . $fin_year . "' group by id");

$cnt_ao = mysql_num_rows($sql_ao);

if ($cnt_ao > 0) {


    $apa_remark = mysql_fetch_assoc($sql_ao);
}
else {
    $apa_remark = '';
}



$sql = mysql_query("select *,sum(app_1 + app_2+ app_3+ app_4+ app_5+ app_6+ app_7+ app_8+ app_9+ app_10+ app_11+ st_licnce_1+ st_licnce_2+ st_licnce_3+ infr_1+ infr_2+ infr_3+ infr_4+ infr_5+ infr_6+ infr_7+ godown_1+  godown_2+  godown_3+ godown_4+ godown_5+ godown_6+ godown_7+ godown_8+ godown_9+ godown_10+ godown_1+ godown_12+ godown_13+ godown_14+ doc_1+ doc_2+ doc_3+ doc_4+ doc_5+  doc_6+ doc_7+ doc_8+ cust_update_1+ cust_update_2+ cust_update_3+ cust_update_4+ ndne_1+ ndne_2+ dom_1+ nfr_1+ nfr_2+ nde_1+ cust_orientation_1+ cust_orientation_2+ cust_orientation_3+ cust_orientation_4+ cust_orientation_5+ cust_orientation_6+ home_del_1+ home_del_2+ home_del_3+ leakage_1+ leakage_2+ leakage_3+ safety_1+ safety_2+ safety_3+ safety_4+ mang_1+ mang_2+ mang_3+ rmdg_1+ rmdg_2+ rmdg_3+ rmdg_4+ attitude+ inovative_init) as sum_d,sum(app_1 + app_2+ app_3+ app_4+ app_5+ app_6+ app_7+ app_8+ app_9+ app_10+ app_11) as  sales_app,sum(st_licnce_1+ st_licnce_2+ st_licnce_3) as sales_st_licnce,sum(infr_1+ infr_2+ infr_3+ infr_4+ infr_5+ infr_6+ infr_7) as sales_infr,sum(godown_1+  godown_2+  godown_3+ godown_4+ godown_5+ godown_6+ godown_7+ godown_8+ godown_9+ godown_10+ godown_1+ godown_12+ godown_13+ godown_14) as sales_godown,sum(doc_1+ doc_2+ doc_3+ doc_4+ doc_5+  doc_6+ doc_7+ doc_8) as sales_doc,sum(cust_update_1+ cust_update_2+ cust_update_3+ cust_update_4) as sales_cust_update,sum(ndne_1+ ndne_2) as sales_ndne,sum(nfr_1+ nfr_2) as sales_nfr, sum(cust_orientation_1+ cust_orientation_2+ cust_orientation_3+ cust_orientation_4+ cust_orientation_5+ cust_orientation_6) as sales_cust_orientation, sum(home_del_1+ home_del_2+ home_del_3) as sales_home_del,sum(leakage_1+ leakage_2+ leakage_3) as sales_leakage,sum(safety_1+ safety_2+ safety_3+ safety_4) as sales_safety, sum(mang_1+ mang_2+ mang_3) as sales_mang, sum(rmdg_1+ rmdg_2+ rmdg_3+ rmdg_4) as sales_rmdg  from gb_ada_marks where gb_type='AO' and distributor_id='" . $_REQUEST["id"] . "' and year='" . $fin_year . "' group by id");

$cnt = mysql_num_rows($sql);

if ($cnt > 0) {

    $row_55 = mysql_fetch_assoc($sql);

    if ($row_55['status'] == 1) {
        $hidden = ' style="visibility:hidden" ';
        $disable = ' disabled="disabled" ';
    }
}
else {
    $row_55 = '';
}


//echo "select *,sum(app_1 + app_2+ app_3+ app_4+ app_5+ app_6+ app_7+ app_8+ app_9+ app_10+ app_11+ st_licnce_1+ st_licnce_2+ st_licnce_3+ infr_1+ infr_2+ infr_3+ infr_4+ infr_5+ infr_6+ infr_7+ godown_1+  godown_2+  godown_3+ godown_4+ godown_5+ godown_6+ godown_7+ godown_8+ godown_9+ godown_10+ godown_1+ godown_12+ godown_13+ godown_14+ doc_1+ doc_2+ doc_3+ doc_4+ doc_5+  doc_6+ doc_7+ doc_8+ cust_update_1+ cust_update_2+ cust_update_3+ cust_update_4+ ndne_1+ ndne_2+ dom_1+ nfr_1+ nfr_2+ nde_1+ cust_orientation_1+ cust_orientation_2+ cust_orientation_3+ cust_orientation_4+ cust_orientation_5+ cust_orientation_6+ home_del_1+ home_del_2+ home_del_3+ leakage_1+ leakage_2+ leakage_3+ safety_1+ safety_2+ safety_3+ safety_4+ mang_1+ mang_2+ mang_3+ rmdg_1+ rmdg_2+ rmdg_3+ rmdg_4+ attitude+ inovative_init) as sum_d,sum(app_1 + app_2+ app_3+ app_4+ app_5+ app_6+ app_7+ app_8+ app_9+ app_10+ app_11) as  dist_app,sum(st_licnce_1+ st_licnce_2+ st_licnce_3) as dist_st_licnce,sum(infr_1+ infr_2+ infr_3+ infr_4+ infr_5+ infr_6+ infr_7) as dist_infr,sum(godown_1+  godown_2+  godown_3+ godown_4+ godown_5+ godown_6+ godown_7+ godown_8+ godown_9+ godown_10+ godown_1+ godown_12+ godown_13+ godown_14) as dist_godown,sum(doc_1+ doc_2+ doc_3+ doc_4+ doc_5+  doc_6+ doc_7+ doc_8) as dist_doc,sum(cust_update_1+ cust_update_2+ cust_update_3+ cust_update_4) as dist_cust_update,sum(ndne_1+ ndne_2) as dist_ndne,sum(nfr_1+ nfr_2) as dist_nfr, sum(cust_orientation_1+ cust_orientation_2+ cust_orientation_3+ cust_orientation_4+ cust_orientation_5+ cust_orientation_6) as dist_cust_orientation, sum(home_del_1+ home_del_2+ home_del_3) as dist_home_del,sum(leakage_1+ leakage_2+ leakage_3) as dist_leakage,sum(safety_1+ safety_2+ safety_3+ safety_4) as dist_safety, sum(mang_1+ mang_2+ mang_3) as dist_mang, sum(rmdg_1+ rmdg_2+ rmdg_3+ rmdg_4) as dist_rmdg  from gb_ada_marks where gb_type='D' and distributor_id='".$_REQUEST["id"]."' and year='".$fin_year."' group by id ";
$sql_sz = mysql_query("select *,sum(app_1 + app_2+ app_3+ app_4+ app_5+ app_6+ app_7+ app_8+ app_9+ app_10+ app_11+ st_licnce_1+ st_licnce_2+ st_licnce_3+ infr_1+ infr_2+ infr_3+ infr_4+ infr_5+ infr_6+ infr_7+ godown_1+  godown_2+  godown_3+ godown_4+ godown_5+ godown_6+ godown_7+ godown_8+ godown_9+ godown_10+ godown_1+ godown_12+ godown_13+ godown_14+ doc_1+ doc_2+ doc_3+ doc_4+ doc_5+  doc_6+ doc_7+ doc_8+ cust_update_1+ cust_update_2+ cust_update_3+ cust_update_4+ ndne_1+ ndne_2+ dom_1+ nfr_1+ nfr_2+ nde_1+ cust_orientation_1+ cust_orientation_2+ cust_orientation_3+ cust_orientation_4+ cust_orientation_5+ cust_orientation_6+ home_del_1+ home_del_2+ home_del_3+ leakage_1+ leakage_2+ leakage_3+ safety_1+ safety_2+ safety_3+ safety_4+ mang_1+ mang_2+ mang_3+ rmdg_1+ rmdg_2+ rmdg_3+ rmdg_4+ attitude+ inovative_init) as sum_d,sum(app_1 + app_2+ app_3+ app_4+ app_5+ app_6+ app_7+ app_8+ app_9+ app_10+ app_11) as  dist_app,sum(st_licnce_1+ st_licnce_2+ st_licnce_3) as dist_st_licnce,sum(infr_1+ infr_2+ infr_3+ infr_4+ infr_5+ infr_6+ infr_7) as dist_infr,sum(godown_1+  godown_2+  godown_3+ godown_4+ godown_5+ godown_6+ godown_7+ godown_8+ godown_9+ godown_10+ godown_1+ godown_12+ godown_13+ godown_14) as dist_godown,sum(doc_1+ doc_2+ doc_3+ doc_4+ doc_5+  doc_6+ doc_7+ doc_8) as dist_doc,sum(cust_update_1+ cust_update_2+ cust_update_3+ cust_update_4) as dist_cust_update,sum(ndne_1+ ndne_2) as dist_ndne,sum(nfr_1+ nfr_2) as dist_nfr, sum(cust_orientation_1+ cust_orientation_2+ cust_orientation_3+ cust_orientation_4+ cust_orientation_5+ cust_orientation_6) as dist_cust_orientation, sum(home_del_1+ home_del_2+ home_del_3) as dist_home_del,sum(leakage_1+ leakage_2+ leakage_3) as dist_leakage,sum(safety_1+ safety_2+ safety_3+ safety_4) as dist_safety, sum(mang_1+ mang_2+ mang_3) as dist_mang, sum(rmdg_1+ rmdg_2+ rmdg_3+ rmdg_4) as dist_rmdg  from gb_ada_marks where gb_type='D' and distributor_id='" . $_REQUEST["id"] . "' and year='" . $fin_year . "' group by id ");

$cnt_sz = mysql_num_rows($sql_sz);

if ($cnt_sz > 0) {

    $row_sz = mysql_fetch_assoc($sql_sz);
}
else {
    $row_sz = '';
}

$total_sh = $row_sz['dist_app'] + $row_sz['dist_st_licnce'] + $row_sz['dist_infr'] + $row_sz['dist_godown'];
$total_op = $row_sz['dist_doc'] + $row_sz['dist_cust_update'];
$total_sls = $row_sz['dist_ndne'] + $row_sz['dist_nfr'] + $row_sz['dist_cust_orientation'] + $row_sz['dist_home_del'] + $row_sz['dist_leakage'] + $row_sz['dist_safety'] + $row_sz['dist_mang'] + $row_sz['dist_rmdg'] + $row_sz['dom_1'] + $row_sz['nde_1'];

$sql_ao = mysql_query("select * ,sum(app_1 + app_2+ app_3+ app_4+ app_5+ app_6+ app_7+ app_8+ app_9+ app_10+ app_11+ st_licnce_1+ st_licnce_2+ st_licnce_3+ infr_1+ infr_2+ infr_3+ infr_4+ infr_5+ infr_6+ infr_7+ godown_1+  godown_2+  godown_3+ godown_4+ godown_5+ godown_6+ godown_7+ godown_8+ godown_9+ godown_10+ godown_1+ godown_12+ godown_13+ godown_14+ doc_1+ doc_2+ doc_3+ doc_4+ doc_5+  doc_6+ doc_7+ doc_8+ cust_update_1+ cust_update_2+ cust_update_3+ cust_update_4+ ndne_1+ ndne_2+ dom_1+ nfr_1+ nfr_2+ nde_1+ cust_orientation_1+ cust_orientation_2+ cust_orientation_3+ cust_orientation_4+ cust_orientation_5+ cust_orientation_6+ home_del_1+ home_del_2+ home_del_3+ leakage_1+ leakage_2+ leakage_3+ safety_1+ safety_2+ safety_3+ safety_4+ mang_1+ mang_2+ mang_3+ rmdg_1+ rmdg_2+ rmdg_3+ rmdg_4+ attitude+ inovative_init) as sum_d,sum(app_1 + app_2+ app_3+ app_4+ app_5+ app_6+ app_7+ app_8+ app_9+ app_10+ app_11) as d_app,sum(st_licnce_1+ st_licnce_2+ st_licnce_3) as d_licnce,sum(infr_1+ infr_2+ infr_3+ infr_4+ infr_5+ infr_6+ infr_7) as d_infr,sum(godown_1+  godown_2+  godown_3+ godown_4+ godown_5+ godown_6+ godown_7+ godown_8+ godown_9+ godown_10+ godown_1+ godown_12+ godown_13+ godown_14) as d_godown,sum(doc_1+ doc_2+ doc_3+ doc_4+ doc_5+  doc_6+ doc_7+ doc_8) as d_doc,sum(cust_update_1+ cust_update_2+ cust_update_3+ cust_update_4) as d_cust_update,sum(ndne_1+ ndne_2) as d_ndne,sum(nfr_1+ nfr_2) as d_nfr, sum(cust_orientation_1+ cust_orientation_2+ cust_orientation_3+ cust_orientation_4+ cust_orientation_5+ cust_orientation_6) as d_cust_orientation, sum(home_del_1+ home_del_2+ home_del_3) as d_home_del,sum(leakage_1+ leakage_2+ leakage_3) as d_leakage,sum(safety_1+ safety_2+ safety_3+ safety_4) as d_safety, sum(mang_1+ mang_2+ mang_3) as d_mang, sum(rmdg_1+ rmdg_2+ rmdg_3+ rmdg_4) as d_rmdg from gb_ada_marks where gb_type='SZ' and distributor_id='" . $_REQUEST["id"] . "' and year='" . $fin_year . "' group by id");

//echo "select * ,sum(app_1 + app_2+ app_3+ app_4+ app_5+ app_6+ app_7+ app_8+ app_9+ app_10+ app_11+ st_licnce_1+ st_licnce_2+ st_licnce_3+ infr_1+ infr_2+ infr_3+ infr_4+ infr_5+ infr_6+ infr_7+ godown_1+  godown_2+  godown_3+ godown_4+ godown_5+ godown_6+ godown_7+ godown_8+ godown_9+ godown_10+ godown_1+ godown_12+ godown_13+ godown_14+ doc_1+ doc_2+ doc_3+ doc_4+ doc_5+  doc_6+ doc_7+ doc_8+ cust_update_1+ cust_update_2+ cust_update_3+ cust_update_4+ ndne_1+ ndne_2+ dom_1+ nfr_1+ nfr_2+ nde_1+ cust_orientation_1+ cust_orientation_2+ cust_orientation_3+ cust_orientation_4+ cust_orientation_5+ cust_orientation_6+ home_del_1+ home_del_2+ home_del_3+ leakage_1+ leakage_2+ leakage_3+ safety_1+ safety_2+ safety_3+ safety_4+ mang_1+ mang_2+ mang_3+ rmdg_1+ rmdg_2+ rmdg_3+ rmdg_4+ attitude+ inovative_init) as sum_d,sum(app_1 + app_2+ app_3+ app_4+ app_5+ app_6+ app_7+ app_8+ app_9+ app_10+ app_11) as d_app,sum(st_licnce_1+ st_licnce_2+ st_licnce_3) as d_licnce,sum(infr_1+ infr_2+ infr_3+ infr_4+ infr_5+ infr_6+ infr_7) as d_infr,sum(godown_1+  godown_2+  godown_3+ godown_4+ godown_5+ godown_6+ godown_7+ godown_8+ godown_9+ godown_10+ godown_1+ godown_12+ godown_13+ godown_14) as d_godown,sum(doc_1+ doc_2+ doc_3+ doc_4+ doc_5+  doc_6+ doc_7+ doc_8) as d_doc,sum(cust_update_1+ cust_update_2+ cust_update_3+ cust_update_4) as d_cust_update,sum(ndne_1+ ndne_2) as d_ndne,sum(nfr_1+ nfr_2) as d_nfr, sum(cust_orientation_1+ cust_orientation_2+ cust_orientation_3+ cust_orientation_4+ cust_orientation_5+ cust_orientation_6) as d_cust_orientation, sum(home_del_1+ home_del_2+ home_del_3) as d_home_del,sum(leakage_1+ leakage_2+ leakage_3) as d_leakage,sum(safety_1+ safety_2+ safety_3+ safety_4) as d_safety, sum(mang_1+ mang_2+ mang_3) as d_mang, sum(rmdg_1+ rmdg_2+ rmdg_3+ rmdg_4) as d_rmdg from gb_ada_marks where gb_type='SZ' and distributor_id='".$_REQUEST["id"]."' and year='".$fin_year."' group by id";
$cnt_ao = mysql_num_rows($sql_ao);

if ($cnt_ao > 0) {

    $row_ao = mysql_fetch_assoc($sql_ao);
}
else {
    $row_ao = '';
}
$total_sales_sh = $row_ao['d_app'] + $row_ao['d_licnce'] + $row_ao['d_infr'] + $row_ao['d_godown'];
$total_sales_op = $row_ao['d_doc'] + $row_ao['d_cust_update'];
$total_sales_sls = $row_ao['d_ndne'] + $row_ao['d_nfr'] + $row_ao['d_cust_orientation'] + $row_ao['d_home_del'] + $row_ao['d_leakage'] + $row_ao['d_safety'] + $row_ao['d_mang'] + $row_ao['d_rmdg'] + $row_ao['dom_1'] + $row_ao['nde_1'];
$total_d = 0;

if ($cnt_dis > 0) {
    echo '<span style="color:#FF0000;font-weight:bold;">NOTE : You cannot edit this form now.</span>';
}
?>
                    <? if ($nodeditable == 0) { ?>

    <div style="margin:7px;">Distributor's Status : <? if ($row_sz["status"] == 1) { ?><strong>Finalised</strong><!--<img src="images/icon_status_green.gif" border="0"  />--><? }
                else { ?><strong>Pending Finalization</strong><!--<img src="images/icon_status_red.gif" border="0"  />--><? } ?></div>

    <div style="margin:7px;">Sales Officer's Status : <? if ($row_ao["status"] == 1) { ?><strong>Finalised</strong><!--<img src="images/icon_status_green.gif" border="0"  />--><? }
                else { ?><strong>Pending Finalization</strong><!--<img src="images/icon_status_red.gif" border="0"  />--><? } ?></div>

                    <? } ?>

                    <?php /* ?>   <? if($row_sz["status"]==1 && $row_ao["status"]==1) { ?>

                      <div style="margin:7px;">Form Status : <? if($row_55["status"]==1){ ?><img src="images/icon_status_red.gif" border="0"  /><? }else{ ?><a href="apa_ao.php?id=<?=$_REQUEST["id"]?>&disablestatus=1" onclick="return confirm('Are you sure, you want to freege editing for this distributor ?');"><img src="images/icon_status_green.gif" border="0"  /></a> <? } ?></div>
                      <? }else{ ?>11

                      <div style="margin:7px;">Form Status : <? if($row_55["status"]==1){ ?><img src="images/icon_status_red.gif" border="0"  /><? }else{ ?><a onclick=" alert('Distributor or sales officer status is not freege.')"><img src="images/icon_status_green.gif" border="0"  /></a> <? } ?></div>


                      <? }?><?php */ ?>


                    <? if ($nodeditable == 1) { ?>
                        <style>
                            INPUT{
                                /* border:none;*/
                            }

                        </style>
                    <? } ?>


                    <form name='freezeform' action='' method='post' >
                        <input type="hidden" name="arch_year" id="arch_year" value="<?= @$_REQUEST['arch_year'] ?>" />
                        <input name="disablestatus" type="hidden" id="disablestatus" value="">

                    </form>

                    <form name='archiveddata' action='' method='post' class="formcontainer" >

                        <input name="arch" type="hidden" id="arch" value="" >
                        Select Year :  <div class="selectbox" ><select name="arch_year" id="arch_year" onchange="getarchyear()">

                                <option value="2010" <?php if (@$_REQUEST['arch_year'] == "2010") { ?> selected="selected" <? } ?>>2010-2011</option>
                                <option value="2011" <?php if (@$_REQUEST['arch_year'] == "2011") { ?> selected="selected" <? } ?>>2011-2012</option>
                                <option value="2012" <?php if (@$_REQUEST['arch_year'] == "2012") { ?> selected="selected" <? } ?>>2012-2013</option>
                                <option value="2013" <?php if (@$_REQUEST['arch_year'] == "2013") { ?> selected="selected" <? } ?>>2013-2014</option>
                                <option value="2014" <?php if (@$_REQUEST['arch_year'] == "2014") { ?> selected="selected" <? } ?>>2014-2015</option>
                                <option value="2015" <?php if (@$_REQUEST['arch_year'] == "2015") { ?> selected="selected" <? } ?>>2015-2016</option>
                                <option value="2016" <?php if (@$_REQUEST['arch_year'] == "2016") { ?> selected="selected" <? } ?>>2016-2017</option>
                                <option value="2017" <?php if (@$_REQUEST['arch_year'] == "2017") { ?> selected="selected" <? } ?>>2017-2018</option>
								<option value="2018" <?php if (@$_REQUEST['arch_year'] == "2018") { ?> selected="selected" <? } ?>>2018-2019</option>
                                <!--         <?
                    /*          $sql_year=mysql_query("select year from gb_ada_marks where year!='2010' group by year");
                      if(mysql_num_rows($sql_year)>0) {
                      $row_year=mysql_fetch_assoc($sql_year);
                      {
                      $year_1=substr($row_year['year'],2,2) +1; */
                    ?>
                                         <option value="<?= $row_year['year'] ?>"  <? if (@$_REQUEST["arch_year"] == $row_year['year']) {
                        echo 'selected="selected"';
                    } ?> ><?php echo $row_year['year'] . ' - ' . $year_1; ?></option>

<? //} } ?>-->
                                <!-- <option value="2011">2011</option>-->
                            </select>
                        </div>

                    </form>



                    <form name='apaform' action='' method='post' enctype="multipart/form-data" id="apaform"     >
                        <input type="hidden" name="arch_year" id="arch_year" value="<?= @$_REQUEST['arch_year'] ?>" />
                        <div>
                            <div style="float:right; height:50px;">
                                <table width="370" border="0" cellpadding="0" cellspacing="0"  class="tablebordernone">
                                    <tr><td width="80" align="right">
                                            <b>MAX MARKS</b></td>
                                        <td width="80" align="right">
                                            <b>DISTRIBUTOR</b>                       </td>
                                        <td width="85" align="right">
                                            <b>SALES OFFICER</b>          </td>
                                        <td width="85" align="right"><b>AREA OFFICER </b></td>
                                    </tr></table>
                            </div>

                            <div style="clear:both;"></div>
                            <div id="sh_room" class="div_bordr">
                                <div class="bck_color_expand" id="one"><b><span id="title_padding">SHOWROOM</span></b>
                                    <table border="0" cellpadding="0" cellspacing="0" style="float:right;">
                                        <tr>
                                            <td width="100" align="right">
                                                <b>35</b>                    </td>
                                            <td width="100" align="right">
                                                <b><? echo $total_sh; ?></b>                    </td>
                                            <td width="100" align="right">
                                                <b><? echo $total_sales_sh; ?></b>                    </td>
                                            <td width="120" align="right">&nbsp;</td>
                                        </tr>
                                    </table>
                                </div>
                                <table  id="shroom_list"  border="0" cellpadding="2" cellspacing="1" class="tablecontainer2 first"  >
                                    <tr bgcolor="#edab55">
                                        <th  align="center" nowrap="nowrap"><b>SNO</b></th>
                                        <th  align="center" nowrap="nowrap"><b>ITEM</b></th>
                                        <th  align="center" nowrap="nowrap"><b>MAX MARKS</b></th>
                                        <th  align="center" nowrap="nowrap"><b>DISTRIBUTOR</b></th>
                                        <th  align="center" nowrap="nowrap"><b>SALES OFFICER</b></th>
                                        <th  align="center" nowrap="nowrap"><b>SALES
                                                OFFICER
                                                REMARKS</b>                </th>
                                    </tr>
                                    <tr >
                                        <td ><b>APPEARANCE</b></td>
                                        <td >&nbsp;</td>
                                        <td align="right" class="center"><b>11</b></td>
                                        <td class="center"><input type="text" name='dist_total' id="dist_total" size="2" maxlength="3" value="<?= $row_sz['dist_app'] ?>" style=" border:none; font-weight:bold;" readonly /></td>
                                        <td  align="right"><input type="text" size="2" maxlength="3" readonly style="border:none; font-weight:bold;" id="sl_total" name="sl_total" value="<?= $row_ao['d_app'] ?>"></td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Sign board as per norm,clean & prominently visible</td>
                                        <td align="right" class="center"><?= $row_1['app_1'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['app_1'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['app_1'] ?></td>
                                        <td class="center"><?php echo $apa_remark['app_remark_1']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Standard display-charges, mrtp notification,esc,csc,fo tel.no &amp; address availabe visible</td>
                                        <td align="right" class="center"><?= $row_1['app_2'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['app_2'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['app_2'] ?></td>
                                        <td class="center"><?php echo $apa_remark['app_remark_2']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Colour scheme as per standard</td>
                                        <td align="right" class="center"><?= $row_1['app_3'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['app_3'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['app_3'] ?></td>
                                        <td class="center"><?php echo $apa_remark['app_remark_3']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Showroom-good-upkeep clean &amp;bright</td>
                                        <td align="right" class="center"><?= $row_1['app_4'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['app_4'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['app_4'] ?></td>
                                        <td class="center"><?php echo $apa_remark['app_remark_4']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Adequacy of computers/printers(@ one computer + one printer for every 200 refill sales per day)</td>
                                        <td align="right" class="center"><?= $row_1['app_5'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['app_5'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['app_5'] ?></td>
                                        <td class="center"><?php echo $apa_remark['app_remark_5']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Ups backup for atleast 1 hr/generator/power backup</td>
                                        <td align="right" class="center"><?= $row_1['app_6'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['app_6'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['app_6'] ?></td>
                                        <td class="center"><?php echo $apa_remark['app_remark_6']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Ideal indane installation</td>
                                        <td align="right" class="center"><?= $row_1['app_7'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['app_7'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['app_7'] ?></td>
                                        <td class="center"><?php echo $apa_remark['app_remark_7']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Green Label hotplate display with rates</td>
                                        <td align="right" class="center"><?= $row_1['app_8'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['app_8'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['app_8'] ?></td>
                                        <td class="center"><?php echo $apa_remark['app_remark_8']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Showroom staff in uniform</td>
                                        <td align="right" class="center"><?= $row_1['app_9'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['app_9'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['app_9'] ?></td>
                                        <td class="center"><?php echo $apa_remark['app_remark_9']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Drinking water facility</td>
                                        <td align="right" class="center"><?= $row_1['app_10'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['app_10'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['app_10'] ?></td>
                                        <td class="center"><?php echo $apa_remark['app_remark_10']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Proper seating arrangement</td>
                                        <td align="right" class="center"><?= $row_1['app_11'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['app_11'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['app_11'] ?></td>
                                        <td class="center"><?php echo $apa_remark['app_remark_11']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>STATUTORY &amp; OTHER lICENCE</b></td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>2</b></td>
                                        <td align="right" valign="middle" class="center"><input type="text" size="2" maxlength="3" id="dist_licnce" name="dist_licnce" value"<?= $row_sz['dist_st_licnce'] ?>" readonly="readonly" style="border:none; font-weight:bold;" /></td>
                                        <td align="right" valign="middle" class="center"><input type="text" size="2" maxlength="3" readonly style="border:none;  font-weight:bold;" id="sl_stlicnce_total" name="sl_stlicnce_total" value="<?= $row_ao['d_licnce'] ?>"> </td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>&nbsp;</td>
                                        <td></td>
                                        <td align="right" valign="middle" class="center">&nbsp;</td>
                                        <td align="right" valign="middle"></td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Insurance - adequate &amp; valid (Cyls-120% of godown capacity)</td>
                                        <td align="right" class="center"><?= $row_1['st_licnce_1'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['st_licnce_1'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['st_licnce_1'] ?></td>
                                        <td class="center"><?php echo $apa_remark['st_licnce_remark_1']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Instances of delay in renewal of explosive, from-b, insurance licences</td>
                                        <td align="right" class="center"><?= $row_1['st_licnce_2'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['st_licnce_2'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['st_licnce_2'] ?></td>
                                        <td class="center"><?php echo $apa_remark['st_licnce_remark_2']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Interbase Licences</td>
                                        <td align="right" class="center"><?= $row_1['st_licnce_3'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['st_licnce_3'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['st_licnce_3'] ?></td>
                                        <td class="center"><?php echo $apa_remark['st_licnce_remark_3']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>INFRASTRUCTURE</b></td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>8</b></td>
                                        <td align="right" valign="middle" class="center"><input type="text" size="2" maxlength="3" readonly style="border:none;  font-weight:bold;" id="dist_infr_total" name="dist_infr_total" value="<?= $row_sz['dist_infr'] ?>"/></td>
                                        <td align="right" valign="middle" class="center"><input type="text" size="2" maxlength="3" readonly style="border:none;  font-weight:bold;" id="sl_infr_total" name="sl_infr_total" value="<?= $row_ao['d_infr'] ?>"/> </td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Showroom staff (Mgr +2 for 7000 refill sales/mth)</td>
                                        <td align="right" class="center"><?= $row_1['infr_1'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['infr_1'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['infr_1'] ?></td>
                                        <td class="center"><?php echo $apa_remark['infr_remark_1']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Mechanics:(1 for every 4000 customers)</td>
                                        <td align="right" class="center"><?= $row_1['infr_2'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['infr_2'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['infr_2'] ?></td>
                                        <td class="center"><?php echo $apa_remark['infr_remark_2']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Delivery vehicles:Adequte</td>
                                        <td align="right" class="center"><?= $row_1['infr_3'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['infr_3'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['infr_3'] ?></td>
                                        <td class="center"><?php echo $apa_remark['infr_remark_3']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Delivery vehicles:Owend</td>
                                        <td align="right" class="center"><?= $row_1['infr_4'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['infr_4'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['infr_4'] ?></td>
                                        <td class="center"><?php echo $apa_remark['infr_remark_4']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Sign boards ondDelivery vehicles(Agency Names, RSP)</td>
                                        <td align="right" class="center"><?= $row_1['infr_5'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['infr_5'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['infr_5'] ?></td>
                                        <td class="center"><?php echo $apa_remark['infr_remark_5']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Telephones-Adequacy-1 for every 7000 Customers</td>
                                        <td align="right" class="center"><?= $row_1['infr_6'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['infr_6'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['infr_6'] ?></td>
                                        <td class="center"><?php echo $apa_remark['infr_remark_6']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>SMS Facility</td>
                                        <td align="right" class="center"><?= $row_1['infr_7'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['infr_7'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['infr_7'] ?></td>
                                        <td class="center"><?php echo $apa_remark['infr_remark_7']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>GODOWN</b></td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>14</b></td>
                                        <td align="right" valign="middle" class="center"><input type="text" size="2" maxlength="3" readonly style="border:none;  font-weight:bold;" id="dist_godown_total" name="dist_godown_total" value="<?= $row_sz['dist_godown'] ?>"/></td>
                                        <td align="right" valign="middle" class="center"><input type="text" size="2" maxlength="3" readonly style="border:none;  font-weight:bold;" id="sl_godown_total" name="sl_godown_total" value="<?= $row_ao['d_godown'] ?>"> </td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Godown as per approved plan-Yes/No</td>
                                        <td align="right" class="center"><?= $row_1['godown_1'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['godown_1'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['godown_1'] ?></td>
                                        <td class="center"><?php echo $apa_remark['godown_remark_1']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Copy of plan displayed</td>
                                        <td align="right" class="center"><?= $row_1['godown_2'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['godown_2'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['godown_2'] ?></td>
                                        <td class="center"><?php echo $apa_remark['godown_remark_2']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Width of gate as per approval</td>
                                        <td align="right" class="center"><?= $row_1['godown_3'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['godown_3'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['godown_3'] ?></td>
                                        <td class="center"><?php echo $apa_remark['godown_remark_3']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Vents properly covered with standard wire mesh.</td>
                                        <td align="right" class="center"><?= $row_1['godown_4'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['godown_4'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['godown_4'] ?></td>
                                        <td class="center"><?php echo $apa_remark['godown_remark_4']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Safety zone-free of dry vegetation.</td>
                                        <td align="right" class="center"><?= $row_1['godown_5'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['godown_5'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['godown_5'] ?></td>
                                        <td class="center"><?php echo $apa_remark['godown_remark_5']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Mastic flooring in proper condition</td>
                                        <td align="right" class="center"><?= $row_1['godown_6'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['godown_6'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['godown_6'] ?></td>
                                        <td class="center"><?php echo $apa_remark['godown_remark_6']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>No smoking sign displayed</td>
                                        <td align="right" class="center"><?= $row_1['godown_7'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['godown_7'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['godown_7'] ?></td>
                                        <td class="center"><?php echo $apa_remark['godown_remark_7']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>PDC record maintained.</td>
                                        <td align="right" class="center"><?= $row_1['godown_8'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['godown_8'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['godown_8'] ?></td>
                                        <td class="center"><?php echo $apa_remark['godown_remark_8']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Proper stacking of cylinders with segregation.</td>
                                        <td align="right" class="center"><?= $row_1['godown_9'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['godown_9'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['godown_9'] ?></td>
                                        <td class="center"><?php echo $apa_remark['godown_remark_9']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Special tools/utilities like trolleys for U/L and loadin</td>
                                        <td align="right" class="center"><?= $row_1['godown_10'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['godown_10'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['godown_10'] ?></td>
                                        <td class="center"><?php echo $apa_remark['godown_remark_10']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Fire extinguisher adequate and in working condition</td>
                                        <td align="right" class="center"><?= $row_1['godown_11'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['godown_11'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['godown_11'] ?></td>
                                        <td class="center"><?php echo $apa_remark['godown_remark_11']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Sand &amp; buckets available and properly maintained</td>
                                        <td align="right" class="center"><?= $row_1['godown_12'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['godown_12'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['godown_12'] ?></td>
                                        <td class="center"><?php echo $apa_remark['godown_remark_12']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Weighing machine in working condition</td>
                                        <td align="right" class="center"><?= $row_1['godown_13'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['godown_13'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['godown_13'] ?></td>
                                        <td class="center"><?php echo $apa_remark['godown_remark_13']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Godown stock not exceed the licenced capacitty in last 12 Mths</td>
                                        <td align="right" class="center"><?= $row_1['godown_14'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['godown_14'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['godown_14'] ?></td>
                                        <td class="center"><?php echo $apa_remark['godown_remark_14']; ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div id="op_distribu" class="div_bordr">
                                <div class="bck_color_expand" id="two"><b><span id="title_padding">OPERATION OF DISTRIBUTORSHIP</span></b>
                                    <table border="0" cellpadding="0" cellspacing="0"  style="float:right;">
                                        <tr>
                                            <td width="100" align="right">
                                                <b>12</b>                    </td>
                                            <td width="100" align="right">
                                                <b><? echo $total_op; ?></b>                    </td>
                                            <td width="100" align="right"><b><? echo $total_sales_op; ?></b></td>
                                            <td width="120" align="right">&nbsp;</td>
                                        </tr>
                                    </table>
                                </div>

                                <table class="tablecontainer2 first"   id="opr_distr_list" border="0" cellpadding="2" cellspacing="1">
                                    <tr bgcolor="#edab55">
                                        <th width="145" align="center" nowrap="nowrap"><b>SNO</b></th>
                                        <th width="350" align="center" nowrap="nowrap"><b>ITEM</b></th>
                                        <th width="60" align="center" nowrap="nowrap"><b>MAX MARKS</b></th>
                                        <th width="90" align="center" nowrap="nowrap"><b>DISTRIBUTOR</b></th>
                                        <th width="70" align="center" nowrap="nowrap"><b>SALES OFFICER</b></th>
                                        <th width="120" align="center" nowrap="nowrap"><b>SALES
                                                OFFICER
                                                REMARKS</b>                </th>
                                    </tr>
                                    <tr>
                                        <td><b>DOCUMENTATION</b></td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>8</b></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" readonly style="border:none;  font-weight:bold;" id="dist_doc_total" name="dist_doc_total" value="<?= $row_sz['dist_doc'] ?>"/></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" readonly style="border:none;  font-weight:bold;" id="sl_doc_total" name="sl_doc_total" value="<?= $row_ao['d_doc'] ?>"> </td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>

                                        <td>Mandatory manual stock book updated and tallies with Indosoft.</td>
                                        <td class="center"><?= $row_1['doc_1'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['doc_1'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['doc_1'] ?></td>
                                        <td class="center"><?php echo $apa_remark['doc_remark_1']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Regular uploading of data in CMS</td>
                                        <td class="center"><?= $row_1['doc_2'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['doc_2'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['doc_2'] ?></td>
                                        <td class="center"><?php echo $apa_remark['doc_remark_2']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Regular use of e-ledger</td>
                                        <td class="center"><?= $row_1['doc_3'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['doc_3'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['doc_3'] ?></td>
                                        <td class="center"><?php echo $apa_remark['doc_remark_3']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Proper filling of SV/TV documents along with back up papers</td>
                                        <td class="center"><?= $row_1['doc_4'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['doc_4'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['doc_4'] ?></td>
                                        <td class="center"><?php echo $apa_remark['doc_remark_4']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Acknowledge cash memos</td>
                                        <td class="center"><?= $row_1['doc_5'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['doc_5'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['doc_5'] ?></td>
                                        <td class="center"><?php echo $apa_remark['doc_remark_5']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Issuance of bills for all charges.</td>
                                        <td class="center"><?= $row_1['doc_6'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['doc_6'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['doc_6'] ?></td>
                                        <td class="center"><?php echo $apa_remark['doc_remark_6']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Documents signed by distributor</td>
                                        <td class="center"><?= $row_1['doc_7'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['doc_7'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['doc_7'] ?></td>
                                        <td class="center"><?php echo $apa_remark['doc_remark_7']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Weekly remittances in time</td>
                                        <td class="center"><?= $row_1['doc_8'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['doc_8'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['doc_8'] ?></td>
                                        <td class="center"><?php echo $apa_remark['doc_remark_8']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>CUSTOMER DATA UPDATION</b></td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>4</b></td>
                                        <td align="right" class="center"><input type="text" size="2" maxlength="3" readonly style="border:none;  font-weight:bold;" id="dist_custupdate_total" name="dist_custupdate_total" value="<?= $row_sz['dist_cust_update'] ?>"/></td>
                                        <td align="right" valign="middle" class="center"><input type="text" size="2" maxlength="3" readonly style="border:none;  font-weight:bold;" id="sl_custupdate_total" name="sl_custupdate_total" value="<?= $row_ao['d_cust_update'] ?>"> </td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                      <!--              <tr>
                                      <td>&nbsp;</td>
                                      <td>% of customers with Tel. Nos updated&gt;50%-1Marks</td>
                                      <td class="center"><?= $row_1['cust_update_1'] ?></td>
                                      <td align="right" class="salez"><?= $row_sz['cust_update_1'] ?></td>
                                      <td align="right" valign="middle" class="areao"><?= $row_ao['cust_update_1'] ?></td>
                                      <td class="center"><?php echo $apa_remark['cust_update_remark_1']; ?></td>
                                    </tr>-->
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>% of customers with Tel. Nos updated &gt;50% and &lt;80% = 1 Marks</td>
                                        <td class="center"><?= $row_1['cust_update_2'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['cust_update_2'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['cust_update_2'] ?></td>
                                        <td class="center"><?php echo $apa_remark['cust_update_remark_2']; ?></td>
                                    </tr>
                      <!--              <tr>
                                      <td>&nbsp;</td>
                                      <td>% of customers with complete address updated&gt;50%-1Marks</td>
                                      <td class="center"><?= $row_1['cust_update_3'] ?></td>
                                      <td align="right" class="salez"><?= $row_sz['cust_update_3'] ?></td>
                                      <td align="right" valign="middle" class="areao"><?= $row_ao['cust_update_3'] ?></td>
                                      <td class="center"><?php echo $apa_remark['cust_update_remark_3']; ?></td>
                                    </tr>-->
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>% of customers with complete address updated &gt;50% and &lt;80% = 1 Marks</td>
                                        <td class="center"><?= $row_1['cust_update_4'] ?></td>
                                        <td align="right" class="salez"><?= $row_sz['cust_update_4'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['cust_update_4'] ?></td>
                                        <td class="center"><?php echo $apa_remark['cust_update_remark_4']; ?></td>
                                    </tr>
                                </table>


                            </div>
                            <div id="sales" class="div_bordr">
                                <div class="bck_color_expand" id="three"><b><span id="title_padding">SALES</span></b>
                                    <table border="0" cellpadding="0" cellspacing="0"  style="float:right;">
                                        <tr>
                                            <td width="100" align="right">
                                                <b>43</b>                    </td>
                                            <td width="100" align="right"><b><? echo $total_sls; ?></b></td>
                                            <td width="100" align="right"><b><? echo $total_sales_sls; ?></b></td>
                                            <td width="120" align="right">&nbsp;</td>
                                        </tr>
                                    </table>
                                </div>

                                <table  id="sales_list" border="0" cellpadding="2" cellspacing="1" class="tablecontainer2 first" >
                                    <tr bgcolor="#edab55">
                                        <th width="145" align="center" nowrap="nowrap"><b>SNO</b></th>
                                        <th width="350" align="center" nowrap="nowrap"><b>ITEM</b></th>
                                        <th width="60" align="center" nowrap="nowrap"><b>MAX MARKS</b></th>
                                        <th width="90" align="center" nowrap="nowrap"><b>DISTRIBUTOR</b></th>
                                        <th width="70" align="center" nowrap="nowrap"><b>SALES OFFICER</b></th>
                                        <th width="120" align="center"nowrap="nowrap" ><b>SALES
                                                OFFICER
                                                REMARKS</b>                </th>
                                    </tr>
                                    <tr>
                                        <td><b>NDNE</b></td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>10</b></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" readonly style="border:none;  font-weight:bold;" id="dist_ndne_total" name="dist_ndne_total" value="<?= $row_sz['dist_ndne'] ?>"/></td>
                                        <td class="center"><input type="text" size="2" maxlength="3" readonly style="border:none;  font-weight:bold;" id="sl_ndne_total" name="sl_ndne_total" value="<?= $row_ao['d_ndne'] ?>"> </td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Utilization of equipment- Ratio of refills to cylinders&gt;AreaOffice average</td>
                                        <td class="center"><?= $row_1['ndne_1'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['ndne_1'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['ndne_1'] ?></td>
                                        <td  class="center"><?php echo $apa_remark['ndne_remark_1']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Registered Gth in % of NDNE sales to total sale over LY</td>
                                        <td class="center"><?= $row_1['ndne_2'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['ndne_2'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['ndne_2'] ?></td>
                                        <td class="center"><?php echo $apa_remark['ndne_remark_2']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td align="right" valign="middle" class="salez">&nbsp;</td>
                                        <td align="right" valign="middle" class="areao">&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><b>DOM</b></td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>5</b></td>
                                        <td align="right" valign="middle" class="center"><input type="text" size="2" maxlength="3" id="dist_dom" name="_dist_dom" readonly value="<?= $row_sz['dom_1'] ?>" style="border:none;  font-weight:bold;"/></td>
                                        <td align="right" valign="middle" class="center"><input type="text" size="2" maxlength="3" id="dist_dom" name="_dist_dom" readonly value="<?= $row_ao['dom_1'] ?>" style="border:none;  font-weight:bold;" /></td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Backlog free without increase Per Cap</td>
                                        <td class="center"><?= $row_1['dom_1'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['dom_1'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['dom_1'] ?></td>
                                        <td class="center"><?php echo $apa_remark['dom_remark_1']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td align="right" valign="middle">&nbsp;</td>
                                        <td align="right" valign="middle">&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td align="right" valign="middle">&nbsp;</td>
                                        <td align="right" valign="middle">&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><b>NFR</b></td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>3</b></td>
                                        <td align="right" valign="middle" class="center"><input type="text" size="2" maxlength="3" readonly style="border:none; font-weight:bold;" id="dist_godown" name="dist_godown" value="<?= $row_sz['dist_nfr'] ?>"/></td>
                                        <td align="right" valign="middle" class="center"><input type="text" size="2" maxlength="3" readonly style="border:none; font-weight:bold;" id="sl_nfr_total" name="sl_nfr_total" value="<?= $row_ao['d_nfr'] ?>"> </td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Growth over last year</td>
                                        <td class="center"><?= $row_1['nfr_1'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['nfr_1'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['nfr_1'] ?></td>
                                        <td class="center"><?php echo $apa_remark['nfr_remark_1']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Same as last tear</td>
                                        <td class="center"><?= $row_1['nfr_2'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['nfr_2'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['nfr_2'] ?></td>
                                        <td class="center"><?php echo $apa_remark['nfr_remark_2']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td align="right" valign="middle" class="salez">&nbsp;</td>
                                        <td align="right" valign="middle" class="areao">&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><b>NDE</b></td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>1</b></td>
                                        <td align="right" valign="middle" class="center"><input type="text" size="2" maxlength="3" id="dist_nde_total" name="dist_nde_total" readonly value="<?= $row_sz['nde_1'] ?>" style="border:none;  font-weight:bold;"/></td>
                                        <td align="right" valign="middle" class="center"> <input type="text" size="2" maxlength="3" id="nd_total" name="nd_total" readonly="readonly" value="<?= $row_ao['nde_1'] ?>" style="border:none;  font-weight:bold;"/></td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Cheque payment from NDE customers</td>
                                        <td class="center"><?= $row_1['nde_1'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['nde_1'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['nde_1'] ?></td>
                                        <td class="center"><?php echo $apa_remark['nde_remark_1']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td align="right" valign="middle" class="center">&nbsp;</td>
                                        <td align="right" valign="middle">&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><b>CUSTOMER ORIENTATION</b></td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>7</b></td>
                                        <td align="right" valign="middle" class="center"><input type="text" size="2" maxlength="3" id="dist_cust_orientation" name="dist_cust_orientation" readonly value="<?= $row_sz['dist_cust_orientation'] ?>" style="border:none; font-weight:bold;"/></td>
                                        <td align="right" valign="middle" class="center"><input type="text" size="2" maxlength="3" readonly style="border:none; font-weight:bold;" id="sl_orientation_total" name="sl_orientation_total" value="<?= $row_ao['d_cust_orientation'] ?>"></td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Confirmation of TV through e-Ledger</td>
                                        <td class="center"><?= $row_1['cust_orientation_1'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['cust_orientation_1'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['cust_orientation_1'] ?></td>
                                        <td class="center"><?php echo $apa_remark['cust_orientation_remark_1']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>% os SMS/IVRS/WEB/Call Center-booking in total &gt;=30%</td>
                                        <td class="center"><?= $row_1['cust_orientation_2'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['cust_orientation_2'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['cust_orientation_2'] ?></td>
                                        <td class="center"><?php echo $apa_remark['cust_orientation_remark_2']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Availabilitty of suggestion &amp; complaint book</td>
                                        <td class="center"><?= $row_1['cust_orientation_3'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['cust_orientation_3'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['cust_orientation_3'] ?></td>
                                        <td class="center"><?php echo $apa_remark['cust_orientation_remark_3']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Nil complaint in toll free number</td>
                                        <td class="center"><?= $row_1['cust_orientation_4'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['cust_orientation_4'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['cust_orientation_4'] ?></td>
                                        <td class="center"><?php echo $apa_remark['cust_orientation_remark_4']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Delivery confirmation through SMS</td>
                                        <td class="center"><?= $row_1['cust_orientation_5'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['cust_orientation_5'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['cust_orientation_5'] ?></td>
                                        <td class="center"><?php echo $apa_remark['cust_orientation_remark_5']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Pre delivery checks of weight and leakage at customer premises</td>
                                        <td class="center"><?= $row_1['cust_orientation_6'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['cust_orientation_6'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['cust_orientation_6'] ?></td>
                                        <td class="center"><?php echo $apa_remark['cust_orientation_remark_6']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td align="right" valign="middle" class="center">&nbsp;</td>
                                        <td align="right" valign="middle">&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><b>HOME DELIVERY</b></td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>4</b></td>
                                        <td align="right" valign="middle" class="center"><input type="text" size="2" maxlength="3" id="dist_del_total" name="dist_del_total" readonly value="<?= $row_sz['dist_home_del'] ?>" style="border:none; font-weight:bold;"/></td>
                                        <td align="right" valign="middle" class="center"><input type="text" size="2" maxlength="3" readonly style="border:none; font-weight:bold;" id="sl_home_total" name="sl_home_total" value="<?= $row_ao['d_home_del'] ?>"></td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>100% including authorised CNC</td>
                                        <td class="center"><?= $row_1['home_del_1'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['home_del_1'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['home_del_1'] ?></td>
                                        <td class="center"><?php echo $apa_remark['home_del_remark_1']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&lt; 100% including authorised CNC</td>
                                        <td class="center"><?= $row_1['home_del_2'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['home_del_2'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['home_del_2'] ?></td>
                                        <td class="center"><?php echo $apa_remark['home_del_remark_2']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Delivery hours adjusted for convenience of working couples</td>
                                        <td class="center"><?= $row_1['home_del_3'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['home_del_3'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['home_del_3'] ?></td>
                                        <td class="center"><?php echo $apa_remark['home_del_remark_3']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td align="right" valign="middle" class="salez">&nbsp;</td>
                                        <td align="right" valign="middle" class="areao">&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><b>LEAKAGE</b></td>
                                        <td>&nbsp;</td>
                                        <td  class="center"><b>2</b></td>
                                        <td align="right" valign="middle" class="center"><input type="text" size="2" maxlength="3" id="dist_lekage_total" name="dist_lekage_total" readonly value="<?= $row_sz['dist_leakage'] ?>" style="border:none;  font-weight:bold;"/></td>
                                        <td align="right" valign="middle" class="center"><input type="text" size="2" maxlength="3" readonly style="border:none;  font-weight:bold;" id="sl_leakage_total" name="sl_leakage_total" value="<?= $row_ao['d_leakage'] ?>"></td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Leakage complaints attended within one to two hour</td>
                                        <td class="center"><?= $row_1['leakage_1'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['leakage_1'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['leakage_1'] ?></td>
                                        <td class="center"><?php echo $apa_remark['leakage_remark_1']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Beyond 2 to 8 hours</td>
                                        <td class="center"><?= $row_1['leakage_2'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['leakage_2'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['leakage_2'] ?></td>
                                        <td class="center"><?php echo $apa_remark['leakage_remark_2']; ?></td>
                                    </tr>
                                    <tr>

                                        <td>&nbsp;</td>
                                        <td>Beyond 8 hours</td>
                                        <td class="center"><?= $row_1['leakage_3'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['leakage_3'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['leakage_3'] ?></td>
                                        <td class="center"><?php echo $apa_remark['leakage_remark_3']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td align="right" valign="middle" class="center">&nbsp;</td>
                                        <td align="right" valign="middle" class="areao">&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><b>SAFETY</b></td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>6</b></td>
                                        <td align="right" valign="middle" class="center"><input type="text" size="2" maxlength="3" id="dist_safety_total" name="dist_safety_total" readonly value="<?= $row_sz['dist_safety'] ?>" style="border:none;  font-weight:bold;"/></td>
                                        <td align="right" valign="middle" class="center"><input type="text" size="2" maxlength="3" readonly style="border:none; font-weight:bold;" id="sl_safety_total" name="sl_safety_total" value="<?= $row_ao['d_safety'] ?>"></td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>No. of O ring leak complaints from customers</td>
                                        <td class="center"><?php //echo $row_1['safety_1']; ?></td>
                                        <td align="right" valign="middle" class="salez"><?php //echo $row_sz['safety_1']; ?></td>
                                        <td align="right" valign="middle" class="areao"><? //$row_ao['safety_1'] ?></td>
                                        <td class="center"><?php //echo $apa_remark['safety_remark_1'];  ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&lt;2 % of deliveries =1 Marks</td>
                                        <td class="center"><?= $row_1['safety_2'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['safety_2'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['safety_2'] ?></td>
                                        <td class="center"><?php echo $apa_remark['safety_remark_2']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&gt;2 % of deliveries = -1 Marks</td>
                                        <td class="center"><?= $row_1['safety_3'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['safety_3'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['safety_3'] ?></td>
                                        <td class="center"><?php echo $apa_remark['safety_remark_3']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Customer education programs- Min 3 programs1 marks, for each addl. 1 more upto a max of 5</td>
                                        <td class="center"><?= $row_1['safety_4'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['safety_4'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['safety_4'] ?></td>
                                        <td class="center"><?php echo $apa_remark['safety_remark_4']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td align="right" valign="middle" class="center">&nbsp;</td>
                                        <td align="right" valign="middle">&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><p><b>MANAGEMENT</b></p>               </td>
                                        <td>&nbsp;</td>
                                        <td class="center"><b>5</b></td>
                                        <td align="right" valign="middle" class="center"><input type="text" size="2" maxlength="3" id="dist_mang_total" name="dist_mang_total" readonly value="<?= $row_sz['dist_mang'] ?>" style="border:none; font-weight:bold;"/></td>
                                        <td align="right" valign="middle" class="center"><input type="text" size="2" maxlength="3" readonly style="border:none; font-weight:bold;" id="sl_mang_total" name="sl_mang_total" value="<?= $row_ao['d_mang'] ?>"></td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><p>&nbsp;</p>               </td>
                                        <td>Management by distributor</td>
                                        <td align="right">&nbsp;</td>
                                        <td align="right" valign="middle" class="center">&nbsp;</td>
                                        <td align="right" valign="middle">&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Personal full time</td>
                                        <td class="center"><?= $row_1['mang_1'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['mang_1'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['mang_1'] ?></td>
                                        <td class="center"><?php echo $apa_remark['mang_remark_1']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Half day</td>
                                        <td class="center"><?= $row_1['mang_2'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['mang_2'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['mang_2'] ?></td>
                                        <td class="center"><?php echo $apa_remark['mang_remark_2']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Less than half day</td>
                                        <td class="center"><?= $row_1['mang_3'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['mang_3'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['mang_3'] ?></td>
                                        <td class="center"><?php echo $apa_remark['mang_remark_3']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td align="right" valign="middle" class="salez">&nbsp;</td>
                                        <td align="right" valign="middle" class="areao">&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><b>RMDG IMPOSTION</b></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td align="right" valign="middle" class="center"><input type="text" size="2" maxlength="3" id="dist_rmdg_total" name="dist_rmdg_total" readonly value="<?= $row_sz['dist_rmdg'] ?>" style="border:none; font-weight:bold;"/></td>
                                        <td align="right" valign="middle" class="center"><input type="text" size="2" maxlength="3" readonly style="border:none; font-weight:bold;" id="sl_rmdg_total" name="sl_rmdg_total" value="<?= $row_ao['d_rmdg'] ?>"></td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>For each instance of MDG impostion-3 marks(Major-3,Minor-1)</td>
                                        <td class="center"><?= $row_1['rmdg_1'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['rmdg_1'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['rmdg_1'] ?></td>
                                        <td class="center"><?php echo $apa_remark['rmdg_remark_1']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Shortage of equipment</td>
                                        <td class="center"><?php //echo $row_1['rmdg_2']; ?></td>
                                        <td align="right" valign="middle" class="salez"><?php //echo $row_sz['rmdg_2']; ?></td>
                                        <td align="right" valign="middle" class="areao"><?php //echo $row_ao['rmdg_2']; ?></td>
                                        <td class="center"><?php //echo $apa_remark['rmdg_remark_2'];  ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>Reported theft - 1st theft =0</td>
                                        <td class="center"><?= $row_1['rmdg_3'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['rmdg_3'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['rmdg_3'] ?></td>
                                        <td class="center"><?php echo $apa_remark['rmdg_remark_3']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td style="padding-left:90px;">2nd theft =-3  </td>
                                        <td class="center"><?= $row_1['rmdg_4'] ?></td>
                                        <td align="right" valign="middle" class="salez"><?= $row_sz['rmdg_4'] ?></td>
                                        <td align="right" valign="middle" class="areao"><?= $row_ao['rmdg_4'] ?></td>
                                        <td class="center"><?php echo $apa_remark['rmdg_remark_4']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                    </tr>
                                </table>
                            </div>
                            <div id="attitude" class="div_bordr">
                                <div class="bck_color_expand" id="four"><b><span id="title_padding">ATTITUDE</span></b>
                                    <table border="0" cellpadding="0" cellspacing="0" style="float:right;">
                                        <tr>
                                            <td width="100" align="right"><b>5</b>  </td>
                                            <td width="100" align="right"><b><? echo $row_sz['attitude']; ?></b></td>
                                            <td width="100" align="right"><b><? echo $row_ao['attitude']; ?></b></td>
                                            <td width="120" align="right"></td>
                                        </tr>
                                    </table>
                                </div>

                                <table  id="attitude_list" cellpadding="2" cellspacing="1" border="0" class="tablecontainer2 first">
                                    <tr bgcolor="#edab55">
                                        <th width="145" align="center" nowrap="nowrap"><b>SNO</b></th>
                                        <th width="350" align="center" nowrap="nowrap"><b>ITEM</b></th>
                                        <th width="60" align="center" nowrap="nowrap"><b>MAX MARKS</b></th>
                                        <th width="90" align="center" nowrap="nowrap"><b>DISTRIBUTOR</b></th>
                                        <th width="70" align="center" nowrap="nowrap"><b>SALES OFFICER</b></th>
                                        <th width="120" align="center" nowrap="nowrap" ><b>SALES
                                                OFFICER
                                                REMARKS</b>                </th>
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
                                        <td align="right" ><b>
<?= $row_1['attitude'] ?>
                                            </b></td>
                                        <td align="right" class="salez"><b><?= $row_sz['attitude'] ?></b></td>
                                        <td  class="center"><span class="areao">
                                                <b><?= $row_ao['attitude'] ?></b>
                                            </span></td>
                                        <td  class="center"><?php echo $apa_remark['attitude_remark']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td class="center">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
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
                                            <td width="100" align="right">
                                                <b>5</b>                    </td>
                                            <td width="100" align="right"><b><? echo $row_sz['inovative_init']; ?></b></td>
                                            <td width="100" align="right"><b><? echo $row_ao['inovative_init']; ?></b></td>
                                            <td width="120" align="right"><b><?php echo $row_55['inovative_init']; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        </tr>
                                    </table>
                                </div>

                                <table class="tablecontainer2 first" id="inov_list" cellspacing="1" cellpadding="2" border="0" >
                                    <tr bgcolor="#edab55">
                                        <th width="85" align="center" nowrap="nowrap"><b>SNO</b></th>
                                        <th width="470" align="center" nowrap="nowrap"><b>ITEM</b></th>
                                        <th width="45" align="center" nowrap="nowrap"><b>MAX MARKS</b></th>
                                        <th width="60" align="center" nowrap="nowrap"><b>DISTRIBUTOR</b></th>
                                        <th width="80" align="center" nowrap="nowrap"><b>SALES OFFICER</b></th>
                                        <th width="90" align="center" nowrap="nowrap"><b>SALES
                                                OFFICER
                                                REMARKS</b></td>
                                        <th width="60" align="center" class="center"><b>AREA OFFICER</b></th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            To be assigned by office based on claim from distributor recommened by FO               </td>
                                        <td align="right" ><b>
<?= $row_1['inovative_init'] ?>
                                            </b></td>
                                        <td align="right" class="salez"><b><?= $row_sz['inovative_init'] ?></b></td>
                                        <td align="right" class="areao"><b><?= $row_ao['inovative_init'] ?></b></td>
                                        <td  class="center"><? echo $apa_remark['inov_remark']; ?></td>
                                        <td  class="center"><input type="text" name="inovative_init" <?= $disable ?> id="inovative_init" size="2" maxlength="3" onkeypress="return validate(event);" onblur="ck_value(this.id, '<?= $row_1['inovative_init'] ?>');
                       " value="<?php echo $row_55['inovative_init']; ?>" <?= $readonly ?>/></td>
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
    <td class="center"><?= $row_ao['sum_d']; ?></td>
    <td class="center"></td>
    <td class="center"></td>
 </tr>-->
                                </table>

                            </div>
                        </div>
                        <div>
                            <table border="0" cellpadding="0" cellspacing="0" width="400px" style="margin-left:470px;">
                                <tr>
                                    <td width="90"><b>Grand Total</b></td>
                                    <td width="50" align="right"><b>100</b> </td>
                                    <td width="135" align="right">
                                        <b><? echo $total_sh + $total_op + $total_sls + $row_sz['inovative_init'] + $row_sz['attitude']; ?></b>
                                    </td>
                                    <td width="135" align="right">
                                        <b><?
echo $total_sales_sh + $total_sales_op + $total_sales_sls + $row_ao['attitude'] + $row_ao['inovative_init'];
$totalSO = $total_sales_sh + $total_sales_op + $total_sales_sls + $row_ao['attitude'] + $row_ao['inovative_init'];
?></b>
                                    </td>

                                    <td width="120" align="right">
                                        <b><?php echo $row_55['inovative_init']; ?></b>&nbsp;&nbsp;
                                    </td>
                                </tr>
                                            <?php if ($row_55['inovative_init'] != '') { ?>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td align="right">&nbsp;</td>
                                        <td align="right">&nbsp;</td>
                                        <td align="right"><span style="font-weight: bold">Final Rating :</span> </td>
                                        <td align="right"><strong><?= $totalSO + $row_55['inovative_init'] ?></strong>&nbsp;&nbsp;</td>
                                    </tr>
                                <? } ?>
                            </table>
                        </div>

                        <table width="40%" border="0" align="center" cellpadding="0" cellspacing="0">

                            <tr>

                                <td>&nbsp;</td>

                                <td>&nbsp;</td>

                                <td>&nbsp;</td>
                            </tr>
<? if ($nodeditable == 0) { ?>
                                <tr>
<?php if($isp_type == 'AO'){ ?>
                                    <td width="70"  align="left"><? if ($cnt_dis < 1) { ?><label><input type="submit" name="Submit" class="inputbuttonblue" value="Save"  style="float:none; margin-right:10px;" /></label><? } ?></td>
<?php } ?>


                                    <td  align="left"><?php
                                    if($isp_type == 'AO'){
    //echo  $salesofficer_status . "==".$salesofficer_record;
    if ($salesofficer_status == 1 && $salesofficer_record == 1) {
        ?>
                                            <input type="button" <?= $hidden ?>  name="Submit_form" class="inputbutton" value="Freeze"  onclick="submit_form('1');" />
    <? }
    else if ($salesofficer_status == 0) { ?>
                                            <input type="button" name="Submit_form" class="inputbutton" value="Freeze"  onclick=" alert('Distributor or sales officer status is not freezed.')" />
    <? }
    else if ($salesofficer_record == 0) { ?>
                                            <input type="button" name="Submit_form2" class="inputbutton" value="Freeze"  onclick=" alert('Please fill the form first .')" />
                                        <? } ?>		  </td>
                                    <td width="5%">&nbsp;</td>
                                        <? if ($row_ao["status"] == 0 && $row_sz["status"] == 1) { ?>
                                        <td align="left">
                                            <input name="Unfreeze" type="hidden" id="Unfreeze" value="">
                                            <input type="button" name="Unfreez" class="inputbutton" value="Unfreeze for distributor"  onclick="unfreeze(1)" />          </td>
                                        <?
                                        }
                                        else
                                        if ($row_ao["status"] == 1 && $row_sz["status"] == 1) {
                                            ?>
                                        <td align="center" width="300" style="color:#FF4800;"><? echo "You cannot Unfreeze this distributor because Sales Officer already freezed it."; ?></td>
                                    <? } ?>
                                </tr>
                                <? } ?>
                        </table>
                        <input name="mode" type="hidden" id="mode" value="add">
<?php } ?>
                    </form>

                </div>
            </div>


        </div>
    </div>
</div>
<?
include_once("includes/footer.php");
?>

