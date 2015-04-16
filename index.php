<?php
session_start();
//Cookie Stuff
$ip = $_SERVER['REMOTE_ADDR'];
include('includes/defines.php');
//find out if this is a returning user.
if($_COOKIE['remkey']!='0'){
	//this may be a returning user, get sql.
	include('includes/sql.php');
	$sql_login_return=mysql_query("SELECT id,user,pass,access_level FROM account_info WHERE user='" . $_COOKIE['username'] . "' && remkey='" . $_COOKIE['remkey'] . "'");
	$count_sql_login_return=mysql_num_rows($sql_login_return);
	$login_information=mysql_fetch_array($sql_login_return, MYSQL_ASSOC);
	if($count_sql_login_return==1){
		//user information found, set session and send them to main.php.
		//Bind IP
		$sql_bind=mysql_query("UPDATE account_info SET ip='" . $ip . "' WHERE user='" . $login_information['user'] . "'");
		setcookie("ip",$ip,time()+604800,"/",".my.portcoimbra.com",0,1);
		$_SESSION['username']=$login_information['user'];
		$_SESSION['password']=$login_information['pass'];
		$_SESSION['id']=$login_information['id'];
		$_SESSION['access_level']=$login_information['access_level'];
		header('Location: main.php');
	};
};
setcookie("ACInfo", $ip, time()+1209600, "/", ".my.portcoimbra.com");
include('includes/header.tpl');
?>
	<div id="content">
    	<div id="simple">
        	<h1>Information</h1>
            <p>
            	<table align="center">
                	<tbody>
						Please Login:<br><br>
						<form name='form' method='post' action='auth/login.php'>
                    	<tr><td>Email Address:</td><td><input name='username' type='text' id='username' /></td></tr>
                        <tr><td>Password:</td><td><input name='password' type='password' id='password' /></td></tr>
						<tr><td colspan='2' align='right'>Keep me logged in <input type='checkbox' name='remember_me' checked='checked' value='1' /></td></tr>
                        <tr><td colspan='2' align='right'><input type='submit' value='Submit' /></td></tr>
						</form>		
                    </tbody>
                </table>
            </p>
        </div>
    </div>
<?php 
include('includes/footer.tpl');
?>