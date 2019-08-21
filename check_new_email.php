<?php
include_once("../includes/connection.php");
//include_once("secure_path.php");

$sql="select consumer__email from gb_consumer where consumer__email='".trim($_REQUEST["email"])."'";
$res=mysql_query($sql);

//$sql="select id from gb_new_consumer where email='".trim($_REQUEST["email"])."'";
//$res1=mysql_query($sql);

$flag=false;

if(mysql_num_rows($res)>0)
{
	echo '0';
}
else
{
	echo '1';
}
?>