<?php
session_start();
if(!session_is_registered(username)){
	header('Location: index.php');
};
//CHECK FOR MOD STATUS EVERY SINGLE FUCKING TIME.
if($_SESSION['access_level']>'1'){
	header('Location: ../index.php');
};
$user_id=$_GET['id'];
include('../includes/sql.php');
include('../includes/defines.php');
include('../includes/header.tpl');
$account_info=$mod->get_account_info($user_id);
if($account_info['access_level']>=$_SESSION['access_level']){
	echo "You cannot edit a mod or admin!";
	die;
};
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1 style='background-color:#" . $color . ";'>Account Information</h1>";
				echo "<p>";
					//Unchangable Info
					echo "Username & Email Address: <b>" . $account_info['user'] . "</b><br>";
					echo "Account Number: <b>" . $account_info['id'] . "</b><br>";
						echo "<form name='mod_tools' action='mod_action.php' method='post'>";
							echo "<select name='action'>";
	  							echo "<option value='rst_pass'>Reset Password</option>";
	  							echo "<option value='rst_c_url'>Reset Custom URL</option>";
	  							echo "<option value='rst_web_clr'>Reset Web Color</option>";
	  							echo "<option value='rst_srv'>Reset Server</option>";
	  							echo "<option value='rst_fam_name'>Reset Family Name</option>";
							echo "</select>";
							echo "<input type='hidden' name='user_id' value='" . $account_info['id'] . "'>";
							echo "<input type='submit' value='Submit'>";
						echo "</form>";
					echo "<br><b><font color='red'>These are raw lists, refer to the guide if you don't understand how to edit these fields!</font color></b>";
					echo "<br>Edit Character List:<br>";
						echo "<form name='mod_tools_edit_char_list' action='mod_action.php' method='post'>";
							echo "<input type='hidden' name='action' value='edit_char_list'>";
							echo "<textarea cols='25' rows='5' name='char_list'>" . $account_info['char_list'] . "</textarea><br>";
							echo "<input type='hidden' name='user_id' value='" . $account_info['id'] . "'>";
							echo "<input type='submit' value='Submit'>";
						echo "</form>";
					echo "<br>Edit Groups:<br>";
						echo "<form name='mod_tools_edit_group_list' action='mod_action.php' method='post'>";
							echo "<input type='hidden' name='action' value='edit_grp_list'>";
							echo "<textarea cols='25' rows='5' name='grp_list'>" . $account_info['groups'] . "</textarea><br>";
							echo "<input type='hidden' name='user_id' value='" . $account_info['id'] . "'>";
							echo "<input type='submit' value='Submit'>";
						echo "</form>";
					echo "<br>";
				echo "</p>";
			echo "</div>";
		echo "</div>";
include('../includes/footer.tpl');
?>