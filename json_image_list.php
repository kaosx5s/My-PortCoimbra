<?php
session_start();
if(!isset($_SESSION['username'])){
	header('Location: index.php');
};
include('includes/sql.php');
$account_id=$_SESSION['id'];
$char_id=$_POST['id'];
$char_id_img=$_POST['id_img'];
$region_info=@$account->get_account_region($account_id);
if($_POST['id']!=""){
	//get character information
	$char_job=@$account->get_single_character_job($char_id);
	if($char_job['char_job']=="M'Boma" || $char_job['char_job']=="M%27Boma"){
		$sql_get_char_img_alt=mysql_query("SELECT image,custom_image_list FROM datatable_job_" . $region_info[region] . " WHERE Name='M\'Boma'");
	}else{
		$sql_get_char_img_alt=mysql_query("SELECT image,custom_image_list FROM datatable_job_" . $region_info[region] . " WHERE Name='" . urldecode($char_job['char_job']) . "'");		
	};
	$char_alt_img=mysql_fetch_array($sql_get_char_img_alt, MYSQL_ASSOC);
	$char_list_alt_img=explode(';', $char_alt_img[custom_image_list]);
	for($k=0,$size_cust_img=count($char_list_alt_img);$k<$size_cust_img;$k++){
		$array["$char_list_alt_img[$k]"]="$char_list_alt_img[$k]";
	};
	$array["$char_alt_img[image]"]="$char_alt_img[image]";
	print json_encode($array);
};
if($_POST['id_img']!=""){
	//get character information
	$char_job=@$account->get_single_character_job($char_id_img);
	if($char_job['char_job']=="M'Boma" || $char_job['char_job']=="M%27Boma"){
		$sql_get_char_img_alt=mysql_query("SELECT image,custom_image_list FROM datatable_job_" . $region_info[region] . " WHERE Name='M\'Boma'");
	}else{
		$char_job=urldecode($char_job['char_job']);
		$sql_get_char_img_alt=mysql_query("SELECT image,custom_image_list FROM datatable_job_" . $region_info[region] . " WHERE Name='" . $char_job . "'");
	};
	echo "<div><table border='0'><tr>";
	$char_alt_img=mysql_fetch_array($sql_get_char_img_alt, MYSQL_ASSOC);
	if($char_alt_img[custom_image_list]!=""){
		$char_list_alt_img=explode(';', $char_alt_img[custom_image_list]);
		for($k=0,$size_cust_img=count($char_list_alt_img);$k<$size_cust_img;$k++){
			if($k==7){
				echo "</tr><tr>";
			};
			echo "<td><center>" . $char_list_alt_img[$k];
			echo "</center><img src='http://my.portcoimbra.com/sig/characters/" . $char_list_alt_img[$k] . ".png'></td>";
		};
	};
	echo "<td><center>" . $char_alt_img[image];
	echo "</center><img src='http://my.portcoimbra.com/sig/characters/" . $char_alt_img[image] . ".png'></td>";
	echo "</tr></table></div>";
};
if($_GET['joblist']=='1'){
	$job_list_arr=@$datatable_job->get_job_list($region_info[region]);
	for($i=0,$size=count($job_list_arr);$i<$size;$i++){
		$job_name=rawurlencode($job_list_arr[$i][Name]);
		$job_name_decode=urldecode($job_list_arr[$i][Name]);
		$array["$job_name"]="$job_name_decode";
	};
	print json_encode($array);
};
if($_GET['grouplist']=='1'){
	@$group_list=$account->get_groups($account_id);
	$group_list_blow=explode(';', $group_list['groups']);
		$array["NoGroup"]="No Group";
	for($k=0,$size_group=count($group_list_blow);$k<$size_group;$k++){
		$array["$k"]="$group_list_blow[$k]";
	};
	print json_encode($array);
};
?>