<?php
$uri=$_SERVER['REQUEST_URI'];
include('includes/defines.php');
//Take the request URI and strip it into sections.
//blow up the original URI
$blow_string=explode("/", $uri);
	//Array[1] = discard.
	//Array[2] = Server Name.
	$server_name=$blow_string[2];
	//Array[3] = Family Name OR Custom URL.
	$family_name=$blow_string[3];
	//Array[4] = discard.

//Check for array strings before including sql.
if($server_name && $family_name!=""){
	include('includes/sql.php');
//	$utf8=mysql_query("SET NAMES 'utf8'");
	//Check to see if this is a real family.
	$is_real=@$account->check_if_family_is_real($family_name,$server_name);
	if($is_real=='0' || $is_real==""){
		//it is not, so die.
		echo "The family profile you are looking for was not found.";
		die;
	};
	//DO NOT RELY ON THE URI ANYMORE!
	//get family information
	$family_info=@$account->profile_family_info($is_real['id']);
	$color=$family_info['color'];
	$char_list=@$account->get_signature_information($family_info['id']);
	$group_list=@$account->get_groups($family_info['id']);
	$group_list_blow=explode(';', $group_list['groups']);

include('includes/header.tpl');
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1 style='background-color:#" . $color . ";'>Family Information </h1>";
				echo "<p>";
	$blow_privacy=explode(";", $family_info['privacy']);
	$blow_stat_privacy=explode(";", $family_info['stat_privacy']);
	$show_fam_level=$blow_privacy[0];
	$show_fam_clan=$blow_privacy[1];
	$show_fam_chars=$blow_privacy[2];
	//3 = family lock feature, not used here.
	$show_fam_sig=$blow_privacy[4];
	$show_fam_grp_sigs=$blow_privacy[5];
	//group stuff
	$group_name=explode(';', $family_info['groups']);
	echo "<b>Family Name:</b> " . $family_info['family_name'] . "<br>";
	if($show_fam_level=='1'){
		echo "<b>Family Level:</b> " . $family_info['family_lvl'] . "<br>";
	};
	echo "<b>Server:</b> " . $family_info['server'] . "<br>";
	if($show_fam_clan=='1'){
		echo "<b>Clan:</b> " . $family_info['clan'] . "<br>";
	};
				echo "</p>";
			echo "</div>";
	//Signature Info
	if($show_fam_sig=='1'){
			echo "<br><div id='simple'>";
				echo "<h1 style='background-color:#" . $color . ";'>Current Signature</h1>";
				echo "<p>";
						if($family_info['sig']=='1'){
							if($family_info['sig_type']=='1'){
								echo "<img src='" . WEBSITE . "/sig/saved/sig_" . $family_info['id'] . ".png'>";
							}else{
								echo "<img src='" . WEBSITE . "/sig/saved/sig_" . $family_info['id'] . ".jpg'>";
							};
						}else{
							echo "<i>This family has not created a signature yet.</i>";
						};
				echo "</p>";
			echo "</div>";
		echo "<br>";
	};
	//Group Signature Info
	if($show_fam_grp_sigs=='1'){
			echo "<div id='simple'>";
				echo "<h1 style='background-color:#" . $color . ";'>Current Group Signature(s)</h1>";
				echo "<p>";
						if($family_info['groups']==''){
							echo "<b>Group Signature(s):</b> <i>This user currently has no groups.</i>";
						}else if($family_info['grp_sig']=='1'){
							for($i=0,$size=count($group_name);$i<$size;$i++){
								if($family_info['sig_type']=='1'){
									echo "<img src='" . WEBSITE . "/sig/saved/sig_" . $family_info['id'] . "_group_" . $i . ".png'>";
								}else{
									echo "<img src='" . WEBSITE . "/sig/saved/sig_" . $family_info['id'] . "_group_" . $i . ".jpg'>";
								};
								echo "<br>";
							};
						}else if($char_list['grp_sig']=='0'){
							echo "<b>Group Signature(s):</b> <i>This user currently has no groups.</i>";
						};
				echo "</p>";
			echo "</div>";
	};
	//Character Info
	if($show_fam_chars=='1'){
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1 style='background-color:#" . $color . ";'>" . $family_info['family_name'] . "'s Barracks</h1>";
				echo "<p>";
					if($family_info['char_list']==''){
						echo "<b>Characters:</b> <i>This family has no characters.</i><br>";
					};
					if($show_fam_chars=='1' && $family_info['char_list']!=''){
						//user has characters, lets check them.
						$char_list_blow=explode(';', $family_info['char_list']);
						$char_data_arr=@$account->get_character_information_profile($char_list['char_list']);
						foreach($char_data_arr as $key => $row){
							$data_in_sig[$key] = $row[sig];
							$data_sort_num[$key] = $row[sort_num];
							$data_sort_lvl[$key] = $row[char_lvl];
						}
						array_multisort($data_in_sig, SORT_DESC, $data_sort_num, SORT_ASC, $data_sort_lvl, SORT_DESC, $char_data_arr);
					for($i=0,$size=count($char_list_blow);$i<$size;$i++){
						echo "<div style='float:left;' id='character_info_" . $family_info['id'] . "_" . $char_data_arr[$i][char_name] . "'>";
							$image="sig/single_cache/character_" . $family_info['id'] . "_" . $char_data_arr[$i][char_name] . ".png";
							clearstatcache();
							if(file_exists($image)){
								echo "<img src='" . WEBSITE . "/sig/single_cache/character_" . $family_info['id'] . "_" . $char_data_arr[$i][char_name] . ".png'>";
							}else{
								echo "<img src='" . WEBSITE . "/sig/single_cache/no_image.png'>";
							};
						echo "</div>";
						echo "<script>";
						echo "$('#character_info_" . $family_info['id'] . "_" . $char_data_arr[$i][char_name] . "')";
						echo ".qtip({content:\"";
						echo "<div id='character_profile_template'>";
							echo "<div id='character_defaults'>";
								echo "<div class='name'>" . $char_data_arr[$i][char_name] . "</div><br>";
								echo "<div class='job'>" . urldecode($char_data_arr[$i][char_job]) . "</div><br>";
								echo "<div class='level'>";
								$lvl=$char_data_arr[$i][char_lvl];
								$pro=$char_data_arr[$i][char_pro];
								switch ($lvl){
									case ($pro==0):
										echo "Lvl: " . $char_data_arr[$i][char_lvl];
									break;
				    				case ($lvl>=99 && $pro==0):
										echo "Lvl: " . $char_data_arr[$i][char_lvl];
									break;
									case ($lvl>=100 && $lvl<110 && $pro==1):
										echo "Promotion Lvl: Veteran";
									break;
									case ($lvl>=110 && $lvl<120 && $pro==2):
										echo "Promotion Lvl: Expert";
									break;
									case ($lvl>=120 && $lvl<130 && $pro==3):
										echo "Promotion Lvl: Master";
									break;
									case ($lvl>=130):
										//show as master, despite the fact they are higher.
										echo "Promotion Lvl: Master";
									break;
									default:
										//if all else fails, just show the characters level.
										echo "Lvl: " . $char_data_arr[$i][char_lvl];
									break;
								};
								echo "</div>";
							echo "</div>";
							if($char_data_arr[$i][char_base_stats]!="" && $blow_stat_privacy[0]=='1'){
							echo "<div id='character_base_stats'>";
								$character_base_stat_data=explode(';',$char_data_arr[$i][char_base_stats]);
								echo "<div class='str'>" . $character_base_stat_data[0] . "</div>";
								echo "<div class='agi'>" . $character_base_stat_data[1] . "</div>";
								echo "<div class='con'>" . $character_base_stat_data[2] . "</div>";
								echo "<div class='dex'>" . $character_base_stat_data[3] . "</div>";
								echo "<div class='int'>" . $character_base_stat_data[4] . "</div>";
								echo "<div class='cha'>" . $character_base_stat_data[5] . "</div>";
							}else{
								echo "<div id='character_base_stats_n'>";
							};
							echo "</div>";
							if($char_data_arr[$i][char_atk_stats]!="" && $blow_stat_privacy[1]=='1'){
							echo "<div id='character_atk_stats'>";
								$character_atk_stat_data=explode(';',$char_data_arr[$i][char_atk_stats]);
								echo "<div class='ar'>" . $character_atk_stat_data[0] . "</div>";
								echo "<div class='atk'>" . $character_atk_stat_data[1] . "</div>";
								echo "<div class='acc'>" . $character_atk_stat_data[2] . "</div>";
								echo "<div class='speed'>" . $character_atk_stat_data[3] . "</div>";
								echo "<div class='critical'>" . $character_atk_stat_data[4] . "</div>";
							}else{
								echo "<div id='character_atk_stats_n'>";
							};
							echo "</div>";
							if($char_data_arr[$i][char_def_stats]!="" && $blow_stat_privacy[2]=='1'){
							echo "<div id='character_def_stats'>";
								$character_def_stat_data=explode(';',$char_data_arr[$i][char_def_stats]);
								echo "<div class='dr'>" . $character_def_stat_data[0] . "</div>";
								echo "<div class='def'>" . $character_def_stat_data[1] . "</div>";
								echo "<div class='evasion'>" . $character_def_stat_data[2] . "</div>";
								echo "<div class='block'>" . $character_def_stat_data[3] . "</div>";
								echo "<div class='absorb'>" . $character_def_stat_data[4] . "</div>";
							}else{
								echo "<div id='character_def_stats_n'>";
							};
							echo "</div>";
							if($char_data_arr[$i][char_res_stats]!="" && $blow_stat_privacy[3]=='1'){
							echo "<div id='character_res_stats'>";
								$character_res_stat_data=explode(';',$char_data_arr[$i][char_res_stats]);
								echo "<div class='fire'>" . $character_res_stat_data[0] . "</div>";
								echo "<div class='ice'>" . $character_res_stat_data[1] . "</div>";
								echo "<div class='lightning'>" . $character_res_stat_data[2] . "</div>";
								echo "<div class='sonic'>" . $character_res_stat_data[3] . "</div>";
								echo "<div class='mind'>" . $character_res_stat_data[4] . "</div>";
							}else{
								echo "<div id='character_res_stats_n'>";
							};
							echo "</div>";
						echo "</div>";
						echo "\",show:'mouseover',hide:'mouseout',style:{textAlign:'center',width:'265'},position:{corner:{target:'topMiddle',tooltip:'bottomMiddle'}}})";
						echo "</script>";

					};
				};
				echo "</p>";
			echo "</div>";
		echo "</div>";
	};
};
include('includes/footer.tpl');
?>