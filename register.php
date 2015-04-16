<?php
include('includes/defines.php');
include('includes/header.tpl');
	if($_POST['em']!= ""){
		include('includes/sql.php');
		//Check for dupe accounts.
		$sql_check_sql_dupe_account=mysql_query("SELECT user FROM account_info WHERE user='" . $_POST['em'] . "'");
		if(!$sql_check_sql_dupe_account){
			die('Invalid query: ' . mysql_error());
		};
		$dupe_account=mysql_num_rows($sql_check_sql_dupe_account);
		if($dupe_account>=1){
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1>Information</h1>";
				echo "<p>";
					echo "A duplicate account name has been found, please try again. If you believe this is an error please contact support.";
					echo "<br><br>";
					echo "<a class='launch_small' href='register.php'>Try Again</a>";
				echo "</p>";
			echo "</div>";
		echo "</div>";
			die;
		};
		$query=mysql_query("INSERT INTO account_info SET user='" . $_POST['em'] . "', pass='" . md5($_POST['psw1']) . "'");
		//Create activation key.
		$len = 8;
		$base='ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
		$max=strlen($base)-1;
		$activatecode='';
		mt_srand((double)microtime()*1000000);
		while (strlen($activatecode)<$len+1)
		$activatecode.=$base{mt_rand(0,$max)};
		//Set the activation key.
		$sql_login=mysql_query("UPDATE account_info SET recovery='" . $activatecode . "' WHERE user='" . $_POST['em'] . "'");
		if(!$sql_login){
			die('Invalid query: ' . mysql_error());
		};
		//Send some mail!
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$to      = "$_POST[em]";
		$subject = 'My~PortCoimbra Account Activation';
		$message = 'Hello,' . "<br>"
			. 'An account has been made for the <a href="http://my.portcoimbra.com/">My~PortCoimbra Family Management System</a> using this email address. If you believe this is an error then please disregard this email.' . "<br>"
			. 'If you did request this account creation then please follow the link below.' . "<br>"
			. '<a href="http://my.portcoimbra.com/activate.php">http://my.portcoimbra.com/activate.php</a>' . "<br>"
			. 'Your unique activation key is: <b>' . $activatecode . '</b>, please enter it in the space provided on the account activation page.' . "<br><br>"
			. 'Sincerely,' . "<br>"
			. 'My~PortCoimbra Account Activation Mailer';
		$message = wordwrap($message, 70);
		$headers .= 'From: activate@portcoimbra.com' . "\r\n" .
		'Reply-To: activate@portcoimbra.com' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
		mail($to, $subject, $message, $headers);
		if(!mysql_error()){
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1>Information</h1>";
				echo "<p>";
					echo "An email has been sent to <b>" . $_POST['em'] . "</b> with instructions on how to complete your registration.";
					echo "<br><br>";
					echo "<a class='launch_small' href='activate.php'>Continue to Activation</a>";
				echo "</p>";
			echo "</div>";
		echo "</div>";
			die;
		};
	};
?>

	<script type='text/javascript'>
		$(document).ready(function(){
			$("form").submit(function(){
				return false;
			});
		});
	
		function go(){
			$("#em_r").html("");
			if(checkEmail()){
				if(checkPassword()){
					goB();
				}
			}
			return false;
		}
		
		function goB(){
			var vdata = "em=" + document.getElementById("em").value;
			$.ajax({
				type: "POST",
				url: "/auth/reg_account.php",
				data: vdata,
				success: function(data){
					switch(data){
						case "true":
							document.form.submit();
							break;
						case "bemail":
							$("#em_r").html("<font color='red'>Email in use.</font>");
							break;
						default:
							alert("Unable to register your account at this time.");
							break;
					}
				}
			});
			return false;
		}
		
		function checkEmail() {
			var email = document.getElementById('em');
			var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if (filter.test(email.value)) {
				return true;
			}else{
				$("#em_r").html('<font color="red">Please provide a proper email.</font>');
				return false;
			}
		}
		
		function checkPassword(){
			var a = document.getElementById("psw1").value;
			var b = document.getElementById("psw2").value;
			if((a == b) && (a!="")){
				if(a.length >= 6){
					return true;
				}else{
					alert("Your password must be 6 or more characters in length.");
					return false;
				}
			}else{
				alert("Passwords do not match.");
				return false;
			}
		}
	</script>
		<div id='content'>
			<div id='simple'>
				<h1>Information</h1>
				<p>
					<form name='form' method='post' action=''>
						<table>
							<tbody>
								<tr><td align="right">Email:</td><td><input id="em" type="text" name="em"></input></td><td id="em_r"></td></tr>
								<tr><td align="right">Password:</td><td><input id="psw1" type="password" name="psw1"></input></td></tr>
								<tr><td align="right">Confirm Password:</td><td><input id="psw2" type="password" name="psw2"></input></td></tr>
								<tr><td colspan=2 align="right"><input type="button" value="Register" onclick="javascript:go();return false;"></input></td></tr>
							</tbody>
						</table>
					</form>
				</p>
			</div>
	</div>
<?php 
include('includes/footer.tpl');
?>