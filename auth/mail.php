<?php
include('../includes/sql.php');
//post vars
$username=$_POST['username'];

// Mysql Injection Protection
$username = stripslashes($username);
$username = mysql_real_escape_string($username);
	
$sql_login=mysql_query("SELECT * FROM account_info WHERE user='" . $username . "'");
	if(!$sql_login){
    	die('Invalid query: ' . mysql_error());
	};
$count=mysql_num_rows($sql_login);
$logid=mysql_fetch_array($sql_login);

if($count==1){
	//Account exsists, create recovery key.
	$len = 8;
	$base='ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
	$max=strlen($base)-1;
	$activatecode='';
	mt_srand((double)microtime()*1000000);
	while (strlen($activatecode)<$len+1)
	$activatecode.=$base{mt_rand(0,$max)};

	//Set the recovery key.
$sql_login=mysql_query("UPDATE account_info SET recovery='" . $activatecode . "' WHERE user='" . $username . "'");
	if(!$sql_login){
    	die('Invalid query: ' . mysql_error());
	};
	//Send some mail!
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$to      = "$username";
	$subject = 'Password Reset';
	$message = 'Hello,' . "<br>"
				. 'You have requested a password reset for your My~PortCoimbra account. If you believe this is an error then please reply to this email.' . "<br>"
				. 'If you requested this password reset then please follow the link below.' . "<br>"
				. '<a href="http://my.portcoimbra.com/recover.php">http://my.portcoimbra.com/recover.php</a>' . "<br>"
				. 'Your unique recovery key is: <b>' . $activatecode . '</b>, please enter it in the space provided on the recovery page.' . "<br><br>"
				. 'Sincerely,' . "<br>"
				. 'My~PortCoimbra Password Recovery Mailer';
	$message = wordwrap($message, 70);
	$headers .= 'From: recovery@portcoimbra.com' . "\r\n" .
    'Reply-To: recovery@portcoimbra.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

	mail($to, $subject, $message, $headers);
	include('../includes/defines.php');
	include('../includes/header.tpl');
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1>Password Recovery</h1>";
				echo "<p>";
				echo "Email sent! Please check your email address for further instructions.";
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
				echo "Email address not in our system.";
				echo "<a class='launch_small' href='../forgot.php'>Try Again</a>";
				echo "</p>";
			echo "</div>";
		echo "</div>";
	include('../includes/footer.tpl');
};
?>