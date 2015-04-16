<?php
session_start();
if(!isset($_SESSION['username'])){
	header('Location: index.php');
};
include('includes/sql.php');
include('includes/defines.php');
include('includes/header.tpl');
$account_info=@$account->get_account_id($_SESSION['id']);
$account_id=$_SESSION['id'];
if($_POST['url']!=""){
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1>Information</h1>";
			$pass='0';
			//check sql
			if($account_info['url_name']!=""){
				echo "You already have a URL set, please contact an administrator if you wish to reset it.";
				echo "<br><a class='launch_small' href='account.php'>Ok</a>";
				die;
			};
			//check url
			$url=urlencode($_POST['url']);
			$url_dec=urldecode($url);
			$str_lenght=strlen($url);
			if($str_lenght>25){
				echo "Input URL is over 25 characters long.";
				echo "<br><a class='launch_small' href='acc_agree.php'>Try Again</a>";
				die;
			};
			preg_match_all("([a-zA-Z0-9_\-])", $url, $matches);
			$match_lenght=count($matches[0]);
			if($match_lenght===$str_lenght){
				//Check the string against ALL custom URLs and family names!
				$sql_check_url=mysql_query("SELECT id FROM account_info WHERE family_name='" . $url_dec . "' && server='" . $account_info['server'] . "' || url_name='" . $url_dec . "' && server='" . $account_info['server'] . "'");
				if(mysql_num_rows($sql_check_url)==0){
					//ok, you pass.
					$pass='1';
				}else{
					//there was a match, fuck this guy.
					echo "Input URL matched another players family name or custom url, if you believe this is an error please contact an administrator.";
					echo "<br><a class='launch_small' href='acc_agree.php'>Try Again</a>";
					die;
				};
			}else{
				echo "Input URL contains invalid characters. Please try again.";
				echo "<br><a class='launch_small' href='acc_agree.php'>Try Again</a>";
				die;
			};
			//update
			if($pass=='1'){
				$sql_update_url=mysql_query("UPDATE account_info SET url_name='" . $url . "' WHERE id='" . $account_id . "'");
			echo "Success! Your profile url has been updated.";
			echo "<br><a class='launch_small' href='account.php'>Ok</a>";
			};
		echo "</div>";
	echo "</div>";
};
if($_POST['agreecheck']!=""){
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1>Information</h1>";
			echo "<br>";
			echo "Allowed Characters: A-Z, a-z, 0-9, _, -<br><br>";
			echo "<form action='acc_agree.php' name='custom_url' id='custom_url' method='POST'>";
				echo "Custom URL (Max 25 Characters): <input type='text' size='25' name='url' value=''> ";
				echo "<input type='Submit' value='Submit'>";
			echo "</form>";
		echo "</div>";
	echo "</div>";
};
if($_POST['agreecheck']=="" && $_POST['url']==""){
$site_info=@$site->get_site_agreement(1);
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1>Information</h1>";
			if($account_info['url_name']==""){
?>
<form name='agreeform' onSubmit='return defaultagree(this)' method='POST'>
	<?php echo $site_info['agreement']; ?><br>
	<input name='agreecheck' type='checkbox' onClick='agreesubmit(this)'><b>I agree to the above terms</b><br>
	<input type='Submit' value='Submit' disabled>
</form>
<?php
			}else{
				header('Location: account.php');
			};
		echo "</div>";
	echo "</div>";
};
include('includes/footer.tpl');
?>