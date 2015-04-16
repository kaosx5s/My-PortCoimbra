<?php
include('../includes/sql.php');
//post vars
$username=$_POST['username'];
$recovery=$_POST['password'];
$newpass=$_POST['newpass'];
// Mysql Injection Protection
$username = stripslashes($username);
$recovery = stripslashes($recovery);
$newpass = stripslashes($newpass);
$username = mysql_real_escape_string($username);
$recovery = mysql_real_escape_string($recovery);
$newpass = mysql_real_escape_string($newpass);
// Check password lenght before md5.
if(strlen($newpass)<'6'){
	include('../includes/defines.php');
	include('../includes/header.tpl');
	echo "<tr><td>";
		echo "<p>New Password must be six characters or more.</p>";
		echo "<a class='launch_small' href='../recovery.php'>Try Again</a>";
	echo "</tr></td>";
echo "</tbody>";
echo "</table>";
include('../includes/footer.tpl');
die;
}else{
	$newpass = md5($newpass);
	$sql_login=mysql_query("SELECT * FROM account WHERE email='" . $username . "' and recovery='" . $recovery . "'");
	if(!$sql_login){
		die('Invalid query: ' . mysql_error());
	};
	$count=mysql_num_rows($sql_login);
	$logid=mysql_fetch_array($sql_login, MYSQL_ASSOC);
};

if($count==1){
	//Set the new password.
	$sql_login=mysql_query("UPDATE account SET password='" . $newpass . "' WHERE email='" . $username . "' and recovery='" . $recovery . "'");
	if(!$sql_login){
		die('Invalid query: ' . mysql_error());
	};
	//clear recovery.
	$sql_login=mysql_query("UPDATE account SET recovery=NULL WHERE email='" . $username . "' and password='" . $newpass . "'");
	if(!$sql_login){
		die('Invalid query: ' . mysql_error());
	};
	include('../includes/defines.php');
	include('../includes/header.tpl');
	echo "<tr><td>";
		echo "<p>Success, your password has been reset.</p>";
		echo "<a class='launch_small' href='../index.php'>Return to Login Page</a>";
	echo "</tr></td>";
echo "</tbody>";
echo "</table>";
include('../includes/footer.tpl');
}else{
	include('../includes/defines.php');
	include('../includes/header.tpl');
	echo "<tr><td>";
		echo "<p>Email or Recovery Key Invalid.</p>";
		echo "<a class='launch_small' href='../recovery.php'>Try Again</a>";
	echo "</tr></td>";
echo "</tbody>";
echo "</table>";
include('../includes/footer.tpl');
};
?>