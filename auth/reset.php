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
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1>Password Recovery</h1>";
				echo "<p>";
					echo "New Password must be six characters or more.";
					echo "<a class='launch_small' href='../recovery.php'>Try Again</a>";
				echo "</p>";
			echo "</div>";
		echo "</div>";
include('../includes/footer.tpl');
die;
}else{
	$newpass = md5($newpass);
	$sql_login=mysql_query("SELECT * FROM account_info WHERE user='" . $username . "' and recovery='" . $recovery . "'");
	if(!$sql_login){
		die('Invalid query: ' . mysql_error());
	};
	$count=mysql_num_rows($sql_login);
	$logid=mysql_fetch_array($sql_login, MYSQL_ASSOC);
};

if($count==1){
	//Set the new password.
	$sql_login=mysql_query("UPDATE account_info SET pass='" . $newpass . "' WHERE user='" . $username . "' and recovery='" . $recovery . "'");
	if(!$sql_login){
		die('Invalid query: ' . mysql_error());
	};
	//clear recovery.
	$sql_login=mysql_query("UPDATE account_info SET recovery=NULL WHERE user='" . $username . "' and pass='" . $newpass . "'");
	if(!$sql_login){
		die('Invalid query: ' . mysql_error());
	};
	include('../includes/defines.php');
	include('../includes/header.tpl');
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1>Password Recovery</h1>";
				echo "<p>";
					echo "Success, your password has been reset.";
					echo "<a class='launch_small' href='../index.php'>Return to Login Page</a>";
				echo "</p>";
			echo "</div>";
		echo "</div>";
include('../includes/footer.tpl');
}else{
	include('../includes/defines.php');
	include('../includes/header.tpl');
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1>Password Recovery</h1>";
				echo "<p>";
					echo "Email or Recovery Key Invalid.";
					echo "<a class='launch_small' href='../recovery.php'>Try Again</a>";
				echo "</p>";
			echo "</div>";
		echo "</div>";
include('../includes/footer.tpl');
};
?>