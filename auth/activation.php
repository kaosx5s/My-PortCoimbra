<?php
include('../includes/sql.php');
//post vars
$username=$_POST['username'];
$recovery=$_POST['key'];
// Mysql Injection Protection
$username = stripslashes($username);
$recovery = stripslashes($recovery);
$username = mysql_real_escape_string($username);
$recovery = mysql_real_escape_string($recovery);
include('../includes/defines.php');
include('../includes/header.tpl');

$sql_login=mysql_query("SELECT * FROM account_info WHERE user='" . $username . "' and recovery='" . $recovery . "'");
if(!$sql_login){
	die('Invalid query: ' . mysql_error());
};
$count=mysql_num_rows($sql_login);
$logid=mysql_fetch_array($sql_login, MYSQL_ASSOC);

if($count==1){
	//Set active.
	$sql_login=mysql_query("UPDATE account_info SET active='1' WHERE user='" . $username . "' and recovery='" . $recovery . "'");
	if(!$sql_login){
		die('Invalid query: ' . mysql_error());
	};
	//clear recovery.
	$sql_login=mysql_query("UPDATE account_info SET recovery=NULL WHERE user='" . $username . "'");
	if(!$sql_login){
		die('Invalid query: ' . mysql_error());
	};
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1>Information</h1>";
				echo "<p>";
					echo "<p>Success, your account is now active.</p>";
					echo "<a class='launch_small' href='../index.php'>Return to Login Page</a>";
				echo "</p>";
			echo "</div>";
		echo "</div>";
}else{
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1>Information</h1>";
				echo "<p>";
					echo "<p>Email or Activation Key Invalid.</p>";
					echo "<a class='launch_small' href='../activate.php'>Try Again</a>";
				echo "</p>";
			echo "</div>";
		echo "</div>";
};
include('../includes/footer.tpl');
?>