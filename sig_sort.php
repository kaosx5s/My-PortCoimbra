<?php
session_start();
if(!isset($_SESSION['username'])){
	header('Location: index.php');
};
$action=$_POST['action'];
$update_sig_sort=($_POST);
$account_id=$_SESSION['id'];
$ip=$_SERVER['REMOTE_ADDR'];
include('includes/sql.php');
//security checks
//int pass as 0, wait for security_check to set it to 1.
$pass='0';
$pass=@$account->security_check($account_id,$ip);
if($pass=='1'){
	if($action=="update_char_sort_num"){
		for($i=0;$i<count($update_sig_sort[sort]);$i++){
			//echo "char_id=" . $update_sig_sort[sort][$i] . "<br>";
			//echo "sort_num=" . ($i+1) . "<br>";
			$account->edit_character_sort_num($update_sig_sort[sort][$i],($i+1));
		};
	};
};
?>