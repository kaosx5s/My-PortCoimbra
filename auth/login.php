<?php
session_start();
include('../includes/sql.php');
$ip = $_SERVER['REMOTE_ADDR'];
//post vars
$username=$_POST['username'];
$password=$_POST['password'];
$remember_me=$_POST['remember_me'];
// Mysql Injection Protection
$username = stripslashes($username);
$password = stripslashes($password);
$username = mysql_real_escape_string($username);
$password = mysql_real_escape_string($password);
$password = md5($password);

$sql_login=mysql_query("SELECT id,user,pass,remkey,access_level,active FROM account_info WHERE user='" . $username . "' && pass='" . $password . "'");
$count=mysql_num_rows($sql_login);
$logid=mysql_fetch_array($sql_login, MYSQL_ASSOC);
//Get date
$timestamp=date("m,d,Y");
$sql_stamp=mysql_query("UPDATE account_info SET lastlogin='" . $timestamp . "' WHERE user='" . $username . "'");
//Bind IP
$sql_bind=mysql_query("UPDATE account_info SET ip='" . $ip . "' WHERE user='" . $username . "'");
//super amazing math functions for encryption.
	$remkey=floor(sinh(strlen($password))/atan(strlen($username)));
	$remkey=($remkey+(mt_rand(12,37)))*log($remkey);
	$remkey=dechex($remkey);
	$remkey_2=mt_rand(15,43);
	$remkey_2=dechex($remkey_2);
	$remkey=substr($remkey,0,8);
	$remkey=$remkey . $remkey_2;
$sql_set_remkey=mysql_query("UPDATE account_info SET remkey='" . $remkey . "' WHERE user='" . $username . "'");


if($count=='1' && $logid['active']=='0'){
	include('../includes/defines.php');
	include('../includes/header.tpl');
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1>Information</h1>";
				echo "<p>";
					echo "Account is not actiavted. If this is an error please contact support.";
					echo "<br><a class='launch_small' href='../activate.php'>Activate Account</a>";
				echo "</p>";
			echo "</div>";
		echo "</div>";
	include('../includes/footer.tpl');
	die;
};
if($count=='1' && $logid['active']=='1'){
	$_SESSION['username']=$username;
	$_SESSION['password']=$password;
	//set vars
	$_SESSION['id']=$logid['id'];
	$_SESSION['access_level']=$logid['access_level'];
	if($remember_me=='1'){
		//set cookies for remember me
		setcookie("username",$username,time()+604800,"/",".my.portcoimbra.com",0,1);
		setcookie("remkey",$remkey,time()+604800,"/",".my.portcoimbra.com",0,1);
		setcookie("ip",$ip,time()+604800,"/",".my.portcoimbra.com",0,1);
	};
	header('Location: ../main.php');
}else{
	include('../includes/defines.php');
	include('../includes/header.tpl');
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1>Information</h1>";
				echo "<p>";
					echo "Wrong Username or Password.";
					echo "<a class='launch_small' href='../index.php'>Try Again</a>";
				echo "</p>";
			echo "</div>";
		echo "</div>";
	include('../includes/footer.tpl');
};
?>