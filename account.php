<?php
session_start();
if(!isset($_SESSION['username'])){
	header('Location: index.php');
};
include('includes/sql.php');
include('includes/defines.php');
include('includes/header.tpl');
$account_info=@$account->get_account_id($_SESSION['id']);
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1 style='background-color:#" . $color . ";'>Account Information</h1>";
				echo "<p>";
					//Unchangable Info
					echo "Username & Email Address: <b>" .$account_info['user'] ."</b><br>";
					echo "Password: <a href='#' id='toggle2'>Edit</a><br>";
?>
					<div id='edit_password'>
						<form action='javascript:get(document.getElementById('edit_password'));' name='password_edit' id='edit_password'>
							Old Password: <input type='password' name='oldpass' value=''><br>
							New Password: <input type='password' name='newpass' value=''><br>
							Repeat New:   <input type='password' name='newpass2' value=''><br>
							<input type='button' name='button' value='Save' onclick='javascript:get(this.parentNode);'>
						</form>
						<span name='info' id='return_info'></span>
					</div>
<?php
					if($account_info['url_name']!=""){
						echo "Custom Profile URL: <b>" . $account_info['url_name'] ."</b><br>";
					}else if($account_info['family_name']==""){
						echo "Custom Profile URL: <i>None</i> - Create a Family first!<br>";
					}else{
						echo "Custom Profile URL: <i>None</i> - <a href='acc_agree.php'>Add</a><br>";
					};
					echo "Show Info Bar on Signatures: ";
					if($account_info['show_bar']=='1'){
						echo "<b>Yes</b> - <a href='acc_mgmt.php?privacy=91'>Change</a><br>";
					}else{
						echo "<b>No</b> - <a href='acc_mgmt.php?privacy=90'>Change</a><br>";
					};
					echo "Image Export Method: ";
					if($account_info['sig_type']=='1'){
						echo "<b>PNG</b> - <a href='acc_mgmt.php?privacy=51'>Change</a><br>";
					}else{
						echo "<b>JPG / JPEG</b> - <a href='acc_mgmt.php?privacy=50'>Change</a><br>";
					};
					echo "Group Size Limitation: ";
					if($account_info['grp_sig_size']=='1'){
						echo "<b>70</b> - <a href='acc_mgmt.php?privacy=61'>Change</a><br>";
					}else{
						echo "<b>20</b> - <a href='acc_mgmt.php?privacy=60'>Change</a><br>";
					};
					echo "<br>";
					echo "Webiste Color Picker: ";
					echo "<div id='colorselector' name='" . $color ."'><div style='background-color: #0000ff'></div></div>";
					echo "<br>";
				echo "</p>";
			echo "</div>";
			//PRIVACY SETTINGS
			echo "<div id='simple'>";
				echo "<h1 style='background-color:#" . $color . ";'>Privacy Settings</h1>";
				echo "<p>";
					$blow_privacy=explode(";", $account_info['privacy']);
					$blow_stat_privacy=explode(";", $account_info['stat_privacy']);
					echo "Profile View Show Character Base Stats: ";
					if($blow_stat_privacy[0]=='1'){
						echo "<b>Allowed</b> - <a href='acc_mgmt.php?privacy=101'>Change</a><br>";
					}else{
						echo "<b>Denied</b> - <a href='acc_mgmt.php?privacy=100'>Change</a><br>";
					};
					echo "Profile View Show Character Attack Stats: ";
					if($blow_stat_privacy[1]=='1'){
						echo "<b>Allowed</b> - <a href='acc_mgmt.php?privacy=111'>Change</a><br>";
					}else{
						echo "<b>Denied</b> - <a href='acc_mgmt.php?privacy=110'>Change</a><br>";
					};
					echo "Profile View Show Character Defense Stats: ";
					if($blow_stat_privacy[2]=='1'){
						echo "<b>Allowed</b> - <a href='acc_mgmt.php?privacy=121'>Change</a><br>";
					}else{
						echo "<b>Denied</b> - <a href='acc_mgmt.php?privacy=120'>Change</a><br>";
					};
					echo "Profile View Show Character Resist Stats: ";
					if($blow_stat_privacy[3]=='1'){
						echo "<b>Allowed</b> - <a href='acc_mgmt.php?privacy=131'>Change</a><br>";
					}else{
						echo "<b>Denied</b> - <a href='acc_mgmt.php?privacy=130'>Change</a><br>";
					};
					echo "Profile View Family Level: ";
					if($blow_privacy[0]=='1'){
						echo "<b>Allowed</b> - <a href='acc_mgmt.php?privacy=11'>Change</a><br>";
					}else{
						echo "<b>Denied</b> - <a href='acc_mgmt.php?privacy=10'>Change</a><br>";
					};
					echo "Profile View Clan: ";
					if($blow_privacy[1]=='1'){
						echo "<b>Allowed</b> - <a href='acc_mgmt.php?privacy=21'>Change</a><br>";
					}else{
						echo "<b>Denied</b> - <a href='acc_mgmt.php?privacy=20'>Change</a><br>";
					};
					echo "Profile View Character List: ";
					if($blow_privacy[2]=='1'){
						echo "<b>Allowed</b> - <a href='acc_mgmt.php?privacy=31'>Change</a><br>";
					}else{
						echo "<b>Denied</b> - <a href='acc_mgmt.php?privacy=30'>Change</a><br>";
					};
					echo "Profile View Signature: ";
					if($blow_privacy[4]=='1'){
						echo "<b>Allowed</b> - <a href='acc_mgmt.php?privacy=71'>Change</a><br>";
					}else{
						echo "<b>Denied</b> - <a href='acc_mgmt.php?privacy=70'>Change</a><br>";
					};
					echo "Profile View Group Signature(s): ";
					if($blow_privacy[5]=='1'){
						echo "<b>Allowed</b> - <a href='acc_mgmt.php?privacy=81'>Change</a><br>";
					}else{
						echo "<b>Denied</b> - <a href='acc_mgmt.php?privacy=80'>Change</a><br>";
					};
					if($account_info['url_name']!=""){
						echo "Profile Link: <a href='http://my.portcoimbra.com/profile/" . $account_info['server'] . "/" . $account_info['url_name'] . "/' target='blank'>http://my.portcoimbra.com/profile/" . $account_info['server'] . "/" . $account_info['url_name'] . "/</a><br>";
					}else if($account_info['server']=='' || $account_info['family_name']==''){
						echo "Profile Link: <i>Server and Family Name must be set to use profiles.</i><br>";
					}else{
						echo "Profile Link: <a href='http://my.portcoimbra.com/profile/" . $account_info['server'] . "/" . $account_info['family_name'] . "/' target='blank'>http://my.portcoimbra.com/profile/" . $account_info['server'] . "/" . $account_info['family_name'] . "/</a><br>";
					};
				echo "</p>";
			echo "</div>";
			//Changable GE Info
			//GRANADO ESPADA INFORMATION
			echo "<div id='simple'>";
				echo "<h1 style='background-color:#" . $color . ";'>Granado Espada Information</h1>";
				echo "<p>";
					//ok I lied, servers are going to be locked.
					if($account_info['server']==""){
						echo "Server: <b>" .$account_info['server'] ."</b> - <a href='#' id='toggle'>Edit</a> ";
?>
					<div id='edit_server'>
						<form action='javascript:get(document.getElementById('server_change'));' name='server_edit' id='server_change'>
							<select name='server'>
	  							<option value='Bristia'>SotNW - Bristia</option>
	  							<option value='Orpesia'>SotNW - Orpesia</option>
	  							<option value='Illier'>SotNW - Illier</option>
	  							<option value='Bach'>sGE - Bach</option>
	  							<option value='Rembrandt'>sGE - Rembrandt</option>
	  							<option value='Draco'>Thai - Draco</option>
	  							<option value='Corona'>Thai - Corona</option>
	  							<option value='Cortes'>rusGE - Cortes</option>
	  							<option value='Hao Vong'>vGE - Hao Vong</option>
	  							<option value='Huyen Thoai'>vGE - Huyen Thoai</option>
							</select>
							<input type='button' name='button' value='Save' onclick='javascript:get(this.parentNode);'>
						</form>
						<span name='info' id='return_info2'></span>
					</div>
<?php
						echo "<br>";
					}else{
						echo "Server: <b>" .$account_info['server'] ."</b> - <i>Locked</i><br>";
					};
					//Unchangeable GE Info
					if($account_info['family_name']==''){
						echo "Family Name: <i>None</i> - <a href='fam_agree.php'>Add</a><br>";
					}else{
						echo "Family Name: <b>" .$account_info['family_name'] ."</b><br>";
					};
					echo "Family Level: <b>" .$account_info['family_lvl'] ."</b><br>";
					echo "Clan: <b>" .$account_info['clan'] ."</b>";
					echo "<br>";
				echo "</p>";
			echo "</div>";
			//CLIENT MOD SETTINGS
			echo "<div id='simple'>";
				echo "<h1 style='background-color:#" . $color . ";'>MyPC Client Mod Settings</h1>";
				echo "<p>";
					echo "Client Mod Based Updates: ";
					if($blow_privacy[3]=='1'){
						echo "<b>Deny</b> - <a href='acc_mgmt.php?privacy=41'>Change</a><br>";
					}else{
						echo "<b>Allow</b> - <a href='acc_mgmt.php?privacy=40'>Change</a><br>";
					};
				echo "</p>";
			echo "</div>";
		echo "</div>";
include('includes/footer.tpl');
?>