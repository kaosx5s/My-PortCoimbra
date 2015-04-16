<?php
session_start();
if(!session_is_registered(username)){
	header('Location: index.php');
};
//CHECK FOR MOD STATUS EVERY SINGLE FUCKING TIME.
if($_SESSION['access_level']>'1'){
	header('Location: ../index.php');
};
$user_id=$_POST['user_id'];
$mod_id=$_SESSION['id'];
$action=$_POST['action'];
$char_list=$_POST['char_list'];
$grp_list=$_POST['grp_list'];
include('../includes/defines.php');
include('../includes/sql.php');
include('../includes/header.tpl');
$account_info=@$mod->get_account_info($user_id);
if($account_info['access_level']>=$_SESSION['access_level']){
	echo "You cannot edit a mod or admin!";
	die;
};
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1 style='background-color:#" . $color . ";'>Results</h1>";
				echo "<p>";
					if($action=='rst_pass'){
						echo "Recovery Key:" . @$mod->reset_account_password($user_id,$mod_id,$action);
					};
					if($action=='rst_c_url'){
						echo @$mod->reset_account_custom_url($user_id,$mod_id,$action);
					};
					if($action=='rst_web_clr'){
						echo @$mod->reset_account_web_color($user_id,$mod_id,$action);
					};
					if($action=='rst_srv'){
						echo @$mod->reset_account_server($user_id,$mod_id,$action);
					};
					if($action=='rst_fam_name'){
						echo @$mod->reset_account_family_name($user_id,$mod_id,$action);
					};
					if($action=='edit_char_list'){
						echo @$mod->reset_account_char_list($user_id,$mod_id,$action,$char_list);
					};
					if($action=='edit_grp_list'){
						echo @$mod->reset_account_grp_list($user_id,$mod_id,$action,$grp_list);
					};
				echo "<br>";
				echo "<a class='launch_small2' href='" . WEBSITE . "/mod/index.php'>Back</a>";
				echo "</p>";
			echo "</div>";
		echo "</div>";
include('../includes/footer.tpl');
?>