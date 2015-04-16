<?php
session_start();
if(!isset($_SESSION['username'])){
	header('Location: index.php');
};
$account_id=$_SESSION['id'];
include('includes/sql.php');
include('includes/defines.php');
include('includes/header.tpl');
echo "<script type='text/javascript' src='" . WEBSITE . "/includes/script.manage.sig.js'></script>";
$unix_timestamp=time();
//Sig check prefix & character list
$char_list=@$account->get_signature_information($account_id);
$family_name=@$account->get_account_family_name($account_id);
//Groups
$group_list=@$account->get_groups($account_id);
$group_list_blow=explode(';', $group_list['groups']);
$char_data_arr_in_sig=@$account->get_character_information_in_sig($char_list['char_list']);
if(count(char_data_arr_in_sig)>0){
	foreach($char_data_arr_in_sig as $key_in_sig => $row_in_sig){
		$data_in_sig[$key_in_sig] = $row_in_sig[sort_num];
	}
	@array_multisort($data_in_sig, SORT_ASC, $char_data_arr_in_sig);
};
		//CURRENT SIG
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1 style='background-color:#" . $color . ";'>Current Signature</h1>";
					if($char_list['sig']=='0'){
						echo "You currently have no signature, add characters to the sig list below.<br>";
					}else if($char_list['sig']=='1'){
						echo "<div id='signature'>";
							if($char_list['sig_type']==1){
								echo "<img src='" . WEBSITE . "/sig/saved/sig_" . $account_id . ".png?" . $unix_timestamp . "'>";
							}else{
								echo "<img src='" . WEBSITE . "/sig/saved/sig_" . $account_id . ".jpg?" . $unix_timestamp . "'>";
							};
						echo "</div>";
						//Signature Sorting
						echo "<div id='SigPosition'>";
							echo "<ul id='sort'>";
							for($map=0;$map<=19;$map++){
								if($map<count($char_data_arr_in_sig)){
										if($map=='10'){
											echo "<br style='clear:both;'>";
										};
										$charname_utf8strip=preg_replace('/[^(\x20-\x7F)]*/','',$char_data_arr_in_sig[$map][char_name]);
										echo "<li id='sort_" . $char_data_arr_in_sig[$map][char_id] . "'><img src='" . WEBSITE . "/sig/single_cache/character_" . $account_id . "_" . $charname_utf8strip . ".png?" . $unix_timestamp . "'></li>";
								};
							};
							echo "</ul>";
						echo "</div>";
						echo "<br style='clear:both;'>";
						echo "<br>";
						echo "<font size='-1'>Signature BBCode (for forums signature useage):</font>";
						echo "<br>";
						if($char_list['sig_type']==1){
							echo "<input type='text' size='75' readonly='readonly' value='[img]" . WEBSITE . "/sig/saved/sig_" . $account_id . ".png[/img]'></input>";
						}else{
							echo "<input type='text' size='75' readonly='readonly' value='[img]" . WEBSITE . "/sig/saved/sig_" . $account_id . ".jpg[/img]'></input>";
						};
						echo "<br>";
					};
					echo "<a class='launch_small3' onclick='ShowHide_SigSort(); return false;' href='#'>Sort Signature</a> | ";
					echo "<a class='launch_small3' onclick='ShowHide_AddNew(); return false;' href='#'>Add Character Manually</a> | ";
					echo "<a class='launch_small3' href='client_log.php'>Manage Client Mod Log</a>";
			echo "</div>";		
			//Manaually add character
			echo "<div id='add_new_character'>";
				include('manage_addnew.php');
			echo "</div>";
			//GROUP SIG
			echo "<div id='simple_manage_group'>";
				include('manage_grouptable.php');
			echo "</div>";
			//MANAGEMENT
			echo "<div id='simple_manage_pre'>";
				echo "<h1 style='background-color:#" . $color . ";'>Barracks</h1>";
			echo "</div>";
			echo "<div id='simple_manage'>";
				include('manage_familytable.php');
			echo "</div>";
		echo "</div>";
include('includes/footer.tpl');
?>