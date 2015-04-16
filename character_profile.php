<?php
session_start();
if(!isset($_SESSION['username'])){
	header('Location: index.php');
};
$account_id=$_SESSION['id'];
$char_num=$_GET['char'];
include('includes/sql.php');
include('includes/defines.php');
$account_color_info=@$account->get_account_color($_SESSION['id']);
$color=$account_color_info['color'];
if($color==""){
	$color="000000";
};
//get character information array
$character=@$account->get_single_character_info_for_profile($char_num);
$group_list=@$account->get_groups($account_id);
$group_list_blow=explode(';', $group_list['groups']);
$unix_timestamp=date(sB);
function ae_detect_ie(){
	if(isset($_SERVER['HTTP_USER_AGENT'])&&(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)){
        return true;
    }else{
        return false;
	}
};
if(ae_detect_ie()){
	?>
		<script src="http://www.google.com/jsapi?key=ABQIAAAAO7gIeN3iS3eo-Gqf2xOMnRRLj4tpKXzQO0CAV9NjXSJaQCxZjBRLIAhbH6j3-5roF8bYsMDdrqAToQ" type="text/javascript"></script>
		<script type="text/javascript">google.load("jquery", "1.4.2");google.load("jqueryui", "1.8.0");</script>
		<script type='text/javascript' src='<?php " . WEBSITE . " ?>/includes/jquery.qtip-1.0.min.js'></script>
		<span>
	<?php
};
	echo "<script type='text/javascript' src='" . WEBSITE . "/includes/jquery.jeditable.mini.js'></script>";
	echo "<script type='text/javascript' src='" . WEBSITE . "/includes/script.character.js'></script>";
						echo "<div id='character_profile'>";
							echo "<a class='exit_button' href='#' name='" . $character[char_id] . "' id='popupClose'>x</a>";
							echo "<div>";
							$in_sig=$character[sig];
							$sort_num=$character[sort_num];
							$grp_sort_num=$character[grp_sort_num];
								echo "<h1 style='background-color:#" . $color . ";'>Basic Character Information</h1>";
								echo "<span style='float:right;' align='right'><img alt='Delete' border='0' src='/img/del_icon.jpg'><br><a href='sig_mgmt.php?del=" . $character[char_id] . "'>[Delete]</a></span>";
								$charname_utf8strip=preg_replace('/[^(\x20-\x7F)]*/','',$character[char_name]);
								echo "<img align='left' src='" . WEBSITE . "/sig/single_cache/character_" . $account_id . "_" . $charname_utf8strip . ".png'>";
								echo "<p style='text-align:center;'>Character Name: <b class='edit' style='cursor:pointer;' id='" . $character[char_id] . "_-char_name'>" . $character[char_name] . "</b>";
								echo "<br>Character Level: <b class='edit' style='cursor:pointer;' id='" . $character[char_id] . "_-char_lvl'>" . $character[char_lvl] . "</b>";
								echo "<br>Character Promotion Level: ";
									$lvl=$character[char_lvl];
									$pro=$character[char_pro];
									switch ($lvl){
										case ($pro==0 && $lvl<100):
											echo "<b class='edit_dropdown_pro' style='cursor:pointer;' id='" . $character[char_id] . "_-char_pro'>None</b>";
										break;
					    				case ($lvl>=99 && $pro==0):
											echo "<b class='edit_dropdown_pro' style='cursor:pointer;' id='" . $character[char_id] . "_-char_pro'>None</b>";
										break;
										case ($lvl>=100 && $lvl<110 && $pro==1):
											echo "<b class='edit_dropdown_pro' style='cursor:pointer;' id='" . $character[char_id] . "_-char_pro'>Veteran</b>";
										break;
										case ($lvl>=110 && $lvl<120 && $pro==2):
											echo "<b class='edit_dropdown_pro' style='cursor:pointer;' id='" . $character[char_id] . "_-char_pro'>Expert</b>";
										break;
										case ($lvl>=120 && $lvl<130 && $pro==3):
											echo "<b class='edit_dropdown_pro' style='cursor:pointer;' id='" . $character[char_id] . "_-char_pro'>Master</b>";
										break;
										case ($lvl>=130 && $lvl<140 && $pro==4):
											echo "<b class='edit_dropdown_pro' style='cursor:pointer;' id='" . $character[char_id] . "_-char_pro'>High Master</b>";
										break;
										case ($lvl>=140 && $lvl<150 && $pro==5):
											echo "<b class='edit_dropdown_pro' style='cursor:pointer;' id='" . $character[char_id] . "_-char_pro'>Grand Master</b>";
										break;
										case ($lvl>=150):
											//show as grand master, despite the fact they are higher.
											echo "<b class='edit_dropdown_pro' style='cursor:pointer;' id='" . $character[char_id] . "_-char_pro'>Grand Master</b>";
										break;
										default:
											//if all else fails, just show the characters level.
											echo "<b class='edit_dropdown_pro' style='cursor:pointer;' id='" . $character[char_id] . "_-char_pro'>None</b>";
										break;
									};
							echo "</p></div>";
							echo "<div>";
								echo "<h1 style='background-color:#" . $color . ";'>Extended Character Information</h1>";
								if($character[char_exp]!=""){
									echo "<br>Character EXP: " . $character[char_exp] . "%";
								};
								if($character[char_job]!='M\'Boma'){
								echo "<br>Character Job: <b class='edit_dropdown_job_list' style='cursor:pointer;' id='" . $character[char_id] . "_-char_job'>" . urldecode($character[char_job]) . "</b>";
								}else{
								echo "<br>Character Job: M'Boma";
								};
							echo "</div>";
							if($character[char_base_stats]!=""){
								echo "<br><div>";
									echo "<h1 style='background-color:#" . $color . ";'>Character Stats</h1><br>";
									echo "<div style='float:left;margin-left:45px;'>";
									$character_base_stat_data=explode(';',$character[char_base_stats]);
										echo "<b>Character Base Stats:</b><br>";
										echo "STR: " . $character_base_stat_data[0] . "<br>";
										echo "AGI: " . $character_base_stat_data[1] . "<br>";
										echo "CON: " . $character_base_stat_data[2] . "<br>";
										echo "DEX: " . $character_base_stat_data[3] . "<br>";
										echo "INT: " . $character_base_stat_data[4] . "<br>";
										echo "CHA: " . $character_base_stat_data[5] . "<br>";
									echo "</div>";
									if($character[char_atk_stats]!=""){
									$character_battlestatatk_data=explode(';',$character[char_atk_stats]);
									echo "<div style='margin-left:15px;'>";
										echo "<b>Character Attack Stats:</b><br>";
										echo "AR: " .$character_battlestatatk_data[0]. "<br>";
										echo "ATK: " .$character_battlestatatk_data[1]. "<br>";
										echo "ACC: " .$character_battlestatatk_data[2]. "<br>";
										echo "Speed: " .$character_battlestatatk_data[3]. "<br>";
										echo "Critical: " .$character_battlestatatk_data[4]. "<br>";
									echo "</div>";
									};
									if($character[char_def_stats]!=""){
									$character_battlestatdef_data=explode(';',$character[char_def_stats]);
									echo "<div style='clear:both;float:left;margin-left:45px;'>";
										echo "<br><b>Character Defense Stats:</b><br>";
										echo "DR: " .$character_battlestatdef_data[0]. "<br>";
										echo "DEF: " .$character_battlestatdef_data[1]. "<br>";
										echo "Evasion: " .$character_battlestatdef_data[2]. "<br>";
										echo "Block: " .$character_battlestatdef_data[3]. "<br>";
										echo "Absorb: " .$character_battlestatdef_data[4]. "<br>";
									echo "</div>";
									};
									if($character[char_res_stats]!=""){
									$character_resiststat_data=explode(';',$character[char_res_stats]);
									echo "<div style='margin-left:15px;'>";
										echo "<br><br><b>Character Resistance Stats:</b><br>";
										echo "Fire: " .$character_resiststat_data[0]. "<br>";
										echo "Ice: " .$character_resiststat_data[1]. "<br>";
										echo "Lightning: " .$character_resiststat_data[2]. "<br>";
										echo "Sonic: " .$character_resiststat_data[3]. "<br>";
										echo "Mind: " .$character_resiststat_data[4]. "<br>";
									echo "</div>";
									};
								echo "</div>";
								};
							echo "<br><div id='signature_place'>";
								echo "<h1 style='background-color:#" . $color . ";'>Signature Information</h1><br>";
								if($in_sig=='1'){
									$in_sig_txt="Yes";
								}else{
									$in_sig_txt="No";
								};
								echo "Display In Sig: <b class='edit_dropdown_in_sig' style='cursor:pointer;' id='" . $character[char_id] . "_-in_sig'>" . $in_sig_txt . "</b>";
								echo "<br><span image_list_tooltip='" . $character[char_id] . "'>UPC Image: ";
								if($character[char_job]=='M\'Boma'){
								};
								if($character[custom_image]!=""){
									echo "<b name='" . $character[char_id] . "' class='edit_dropdown_custom_sig_image' style='cursor:pointer;' id='" . $character[char_id] . "_-custom_image'>" . $character[custom_image] . "</b>";
								}else{
									echo "<b name='" . $character[char_id] . "' class='edit_dropdown_custom_sig_image' style='cursor:pointer;' id='" . $character[char_id] . "_-custom_image'>Default</b>";
								};
							echo "</div>";
							if($group_list['groups']!=""){
								echo "<br><div>";
									echo "<h1 style='background-color:#" . $color . ";'>Group Information</h1><br>";
									echo "Assigned Group: ";
								if($character[grp]==""){
									echo "<b class='edit_dropdown_group_list' style='cursor:pointer;' id='" . $character[char_id] . "_-group'>None</b>";
								}else{
									echo "<b class='edit_dropdown_group_list' style='cursor:pointer;' id='" . $character[char_id] . "_-group'>" . $group_list_blow[$character[grp]] . "</b>";
									echo "<br>Place In Group Signature: ";
									if($character[grp_sort_num]=='71' || $character[grp_sort_num]==''){
										echo "<b class='edit_dropdown_grp_sort_num' style='cursor:pointer;' id='" . $character[char_id] . "_-grp_sort_num'>Unsorted</b>";
									}else{
										echo "<b class='edit_dropdown_grp_sort_num' style='cursor:pointer;' id='" . $character[char_id] . "_-grp_sort_num'>" . $character[grp_sort_num] . "</b>";
									}
								};
								echo "</div>";
							};
						echo "</div>";
if(ae_detect_ie()){
	?>
		</span>
	<?php
};
?>