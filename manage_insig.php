<?php
session_start();
if(!isset($_SESSION['username'])){
	header('Location: index.php');
};
//session vars
$account_id=$_SESSION['id'];
//global vars
$ip=$_SERVER['REMOTE_ADDR'];
//get var
$char_id=$_GET['id'];
$action=$_GET['action'];

if($char_id=="" || $action==""){
	//no data
	die;
};
include('includes/defines.php');
include('includes/sql.php');
include('includes/header.tpl');
//security checks
//int pass as 0, wait for security_check to set it to 1.
$pass='0';
$pass=@$account->security_check($account_id,$ip);
if($pass=='1'){
	echo "<script type='text/javascript' src='" . WEBSITE . "/includes/script.manage.insig.js'></script>";
	echo "<div id='signature'></div>";
	//get account info
	$char_list=@$account->get_signature_information($account_id);
	$char_list_blow=explode(';', $char_list[char_list]);
	if($char_id!=""){
		//make sure this character belongs to this account!
		$key=array_search($char_id, $char_list_blow);
		if($key!==''){
			if($action=="add"){
				$action=1;
			}else{
				$action=0;
			};
			@$account->character_change_insig($account_id,$char_id,$action);
		};
	echo "Generating signature....";
	header('refresh:0;url=manage.php');
	exit;
	die;
	};
};
include('includes/footer.tpl');
?>