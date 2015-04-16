<?php
session_start();
if(!isset($_SESSION['username'])){
	header('Location: index.php');
};
echo "<script type='text/javascript' src='" . WEBSITE . "/includes/script.manage.groups.js'></script>";
if($_GET['reload']=='1'){
	$account_id=$_SESSION['id'];
	include('includes/sql.php');
	include('includes/defines.php');
	$account_color_info=$account->get_account_color($account_id);
	$color=$account_color_info['color'];
	if($color==""){
		$color="000000";
	};
	echo "<script type='text/javascript' src='" . WEBSITE . "/includes/script.manage.groups.js'></script>";
	@$char_list=$account->get_signature_information($account_id);
	@$group_list=$account->get_groups($account_id);
	$group_list_blow=explode(';', $group_list['groups']);
};
$unix_timestamp=time();
				echo "<h1 style='background-color:#" . $color . ";'>Group Based Signatures</h1>";
				echo "<p>";
					if($group_list['groups']==''){
						echo "You currently have no groups.";
					}else if($char_list['grp_sig']=='1'){
						echo "<div id='group_signature'>";
						for($i=0,$size=count($group_list_blow);$i<$size;$i++){
							if($i>=1){
								echo "<br>";
							};
							if($char_list['sig_type']==1){
								echo "<img src='" . WEBSITE . "/sig/saved/sig_" . $account_id . "_group_" . $i . ".png?" . $unix_timestamp . "'>";
							}else{
								echo "<img src='" . WEBSITE . "/sig/saved/sig_" . $account_id . "_group_" . $i . ".jpg?" . $unix_timestamp . "'>";
							};
							echo "<br>";
							echo "<font size='-1'>BBCode:</font>";
							echo "<br>";
							if($char_list['sig_type']==1){
								echo "<input type='text' size='75' readonly='readonly' value='[img]" . WEBSITE . "/sig/saved/sig_" . $account_id . "_group_" . $i . ".png[/img]'></input>";
							}else{
								echo "<input type='text' size='75' readonly='readonly' value='[img]" . WEBSITE . "/sig/saved/sig_" . $account_id . "_group_" . $i . ".jpg[/img]'></input>";
							};
							echo "<br>";
						};
						echo "</div>";
					};
				echo "</p>";
					if($char_list['char_list']!=''){
						echo "<a class='launch_small3' onclick='ShowHide_AddGroup(); return false;' href='#'>Add Group</a>";
						if($group_list['groups']!=''){
						echo " | <a class='launch_small3' onclick='ShowHide_DelGroup(); return false;' href='#'>Delete Group</a>";
						};
					};
			//Manaually add group
			echo "<div id='add_new_group'>";
				if(isset($Error2)){
					echo '<span class="error2">'.$Error2.'</span>';
				};
				echo "<div id='simple'>";
					echo "<h1 style='background-color:#" . $color . ";'>Add Group</h1>";
					echo "<p>";
						echo "Allowed Characters: A-Z, a-z, 0-9, _, -<br><br>";
						echo "<form id='add_new_group_form' action='' method='post'>";
							echo "Group Name (Max 16 Characters): <input type='text' size='25' id='group_name' name='group_name' value=''> ";
							echo "<input id='submit_add_group' class='launch_small2' type='Submit' value='Submit'>";
						echo "</form>";
					echo "<br></p>";
				echo "</div>";
			echo "</div>";
			echo "<div id='delete_group'>";
				echo "<div id='simple'>";
					echo "<h1 style='background-color:#" . $color . ";'>Delete Group</h1>";
					echo "<p>";
					echo "<form action='' id='delete_group_form' method='POST'>";
						echo "<select class='std' id='group'>";
							for($k=0,$size_grp=count($group_list_blow);$k<$size_grp;$k++){
								echo "<option class='std' value=" . $k . ">" . $group_list_blow[$k] . "</option>";
							};
						echo "</select>";
						echo "<input id='submit_delete_group' class='launch_small2' type='Submit' value='Submit'>";
					echo "</form>";
					echo "<br></p>";
				echo "</div>";
			echo "</div>";
?>