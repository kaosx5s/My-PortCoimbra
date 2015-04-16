<?php
if(!isset($_SESSION['username'])){
	if($_SERVER['PHP_SELF']=='/profile.php'){
		//get profile color
	}else{
		$color="000000";
	};
}else{
	$account_color_info=@$account->get_account_color($_SESSION['id']);
	$color=$account_color_info['color'];
	if($color==""){
		$color="000000";
	};
};
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="-1">
<meta name="Author" content="AlphaOptix LLC.">
<meta name="robots" content="index,follow,ALL">
<meta name="revisit-after" content="3">
<title>My ~ PortCoimbra - Family Management System</title>
<!--[if lte IE 7]><link rel="stylesheet" type="text/css" href='http://my.portcoimbra.com/style_ie.css'><![endif]-->
<!--[if !lte IE 7]><![IGNORE[--><![IGNORE[]]><link rel="stylesheet" type="text/css" href='http://my.portcoimbra.com/style.css'><!--<![endif]-->
<script src="http://www.google.com/jsapi?key=ABQIAAAAO7gIeN3iS3eo-Gqf2xOMnRRLj4tpKXzQO0CAV9NjXSJaQCxZjBRLIAhbH6j3-5roF8bYsMDdrqAToQ" type="text/javascript"></script>
<script type="text/javascript">google.load("jquery", "1.4.2");google.load("jqueryui", "1.8.0");</script>
<?php
if($_SERVER['PHP_SELF']=='/account.php'){
	echo "<link rel='stylesheet' media='screen' type='text/css' href='" . WEBSITE . "/colorpicker.css'>";
	echo "<script type='text/javascript' src='" . WEBSITE . "/includes/colorpicker.js'></script>";
	echo "<script type='text/javascript' src='" . WEBSITE . "/includes/script.account.js'></script>";	
};
if($_SERVER['PHP_SELF']=='/manage.php'){
	//load manage script file.
	echo "<script type='text/javascript' src='" . WEBSITE . "/includes/jquery.qtip-1.0.min.js'></script>";
	echo "<script type='text/javascript' src='" . WEBSITE . "/includes/script.manage.js'></script>";
	echo "<script type='text/javascript' src='" . WEBSITE . "/includes/popup.js'></script>";
};
if($_SERVER['PHP_SELF']=='/fam_agree.php'){
	echo "<script type='text/javascript' src='" . WEBSITE . "/includes/script.agree.js'></script>";
};
if($_SERVER['PHP_SELF']=='/acc_agree.php'){
	echo "<script type='text/javascript' src='" . WEBSITE . "/includes/script.agree.js'></script>";
};
if($_SERVER['PHP_SELF']=='/profile.php'){
	echo "<script type='text/javascript' src='" . WEBSITE . "/includes/jquery.qtip-1.0.min.js'></script>";
};
?>
<style>
	#nav a:hover{background-color:#<?php echo $color; ?>;opacity:0.6;filter:alpha(opacity=60);}
	#banner{position:absolute;top:0;left:0;height:100px;width:100%;padding:0;margin:0;background-color:#<?php echo $color; ?>;}
	#nav{position:absolute;top:100px;left:0;height:36px;width:100%;padding:0;margin:0;background-color:#<?php echo $color; ?>;opacity:0.8;filter:alpha(opacity=80);}
</style>
</head>
<?php flush(); ?>
<body>
	<div id="banner">
		<a href='http://my.portcoimbra.com/'><img src='http://my.portcoimbra.com/img/banner.png' border='0' /></a>
	</div>
	<div id="nav">
<?php
		if(!session_is_registered(username)){
			echo "<a href='" . WEBSITE . "'>Login</a>";
			echo "<a href='" . WEBSITE . "/register.php'>Register</a>";
			echo "<a href='" . WEBSITE . "/recover.php'>Password Recovery</a>";
		}else{
			echo "<a href='" . WEBSITE . "/auth/logout.php'>Logout</a>";
			echo "<a href='" . WEBSITE . "/main.php'>News & Updates</a>";
		if(session_is_registered(username)){
			if($_SESSION['access_level']>'0'){
				echo "<a href='" . WEBSITE . "/mod/'>Mod Panel</a>";
			};
			echo "<a href='" . WEBSITE . "/manage.php'>Manage Family</a>";
			echo "<a href='" . WEBSITE . "/account.php'>Account Information</a>";
			echo "<a href='" . WEBSITE . "/download.php'>Downloads</a>";
		};
			echo "<a href='" . WEBSITE . "/faq.php'>FAQ</a>";
		};
			echo "<a href='http://forums.portcoimbra.com/viewforum.php?f=47'>Support / Report Bug</a>";
			echo "<a href='http://www.portcoimbra.com'>PortCoimbra.com</a>";
	
?>
	</div>
	<div id="container">