<?php
session_start();
if(!isset($_SESSION['username'])){
	header('Location: index.php');
};
include('includes/sql.php');
$server = $_GET['server'];
$oldpass = $_GET['oldpass'];
$newpass = $_GET['newpass'];
$newpass2 = $_GET['newpass2'];
$account_id=$_SESSION['id'];
$account_info=@$account->get_account_id($account_id);

if(isset($server)){
	if($server!="" && $account_info['server']==''){
		$sql_upd_account=mysql_query("UPDATE account_info SET server='" . $server . "' WHERE id='" . $account_id . "'");
		//region select
		if($server=="Bristia" || $server=="Orpesia" || $server=="Illier"){
			$region="USA";
		};
		if($server=="Bach" || $server=="Rembrandt" || $server=="Giovanni"){
			$region="IAH";
		};
		if($server=="Draco" || $server=="Corona"){
			$region="AsiaSoft";
		};
		if($server=="Cortes"){
			$region="rGE";
		};
		if($server=="Hao Vong" || $server=="Huyen Thoai"){
			$region="vGE";
		};
		$sql_upd_account_region=mysql_query("UPDATE account_info SET region='" . $region . "' WHERE id='" . $account_id . "'");	
		echo "<b><font color='blue'>Saved!</b>";
		die;
	}else{
		echo "<b>You may not change your server again.</b>";
		die;
	};
};

if(isset($oldpass)){
	// Mysql Injection Protection & MD5
	$oldpass = stripslashes($oldpass);
	$oldpass = mysql_real_escape_string($oldpass);
	$oldpass = md5($oldpass);
	//Before we md5, lets make sure the new passes match.
	if($newpass==$newpass2){
		//make sure the new password is longer than 6 chars.
		if(strlen($newpass)>=6){
			//ok go
			$newpass = stripslashes($newpass);
			$newpass = mysql_real_escape_string($newpass);
			$newpass = md5($newpass);
		}else{
		echo "<b><font color='red'>Password needs to be longer than or equal to 6 characters!</b>";
		die;		
		};
	}else{
		echo "<b><font color='red'>New Passwords do not match!</b>";
		die;
	};
	if($oldpass!=""){
		//Pull original pass
		$sql_test_old=mysql_query("SELECT pass FROM account_info WHERE id='" . $account_id . "'");
		$really_old_pass=mysql_fetch_array($sql_test_old, MYSQL_ASSOC);
		//check if they match
		if($oldpass==$really_old_pass[0]){
			//ok good.
			$sql_upd_pass=mysql_query("UPDATE account_info SET pass='" . $newpass . "' WHERE id='" . $account_id . "'");
			echo "<b><font color='blue'>Saved!</b>";
			die;
		}else{
			echo "<b><font color='red'>Old Password Mismatch.</b>";
			die;
		};
	};
};
?>