<?php
session_start();
if(!isset($_SESSION['username'])){
	header('Location: index.php');
};
if($_GET['reload']=='1'){
	$account_id=$_SESSION['id'];
	include('includes/sql.php');
	include('includes/defines.php');
	$family_name=@$account->get_account_family_name($account_id);
	$account_color_info=@$account->get_account_color($account_id);
	$color=$account_color_info['color'];
	if($color==""){
		$color="000000";
	};
	echo "<script type='text/javascript' src='" . WEBSITE . "/includes/script.manage.js'></script>";
	$char_list=@$account->get_signature_information($account_id);
	$group_list=@$account->get_groups($account_id);
	$group_list_blow=explode(';', $group_list['groups']);
};
$unix_timestamp=date('sB');
						$char_data_arr=@$account->get_character_information_manage($char_list['char_list']);
						if($char_list['char_list']==''){
							//no characters, die.
									echo "You do not have any characters.";
							die;
						};
						foreach($char_data_arr as $key => $row){
							$data[$key] = $row['sort_num'];
						}
						array_multisort($data, SORT_ASC, $char_data_arr);
						for($i=0,$size=count($char_data_arr);$i<$size;$i++){
							echo "<div tooltip='" . $char_data_arr[$i]['char_name'] . " " . $family_name['family_name'] . "' name='" . $char_data_arr[$i]['char_id'] . "' style='float:left;' id='character_image_" . $account_id . "_" . $char_data_arr[$i]['char_name'] . "'>";
								echo "<ul id='jsddm' name='" . $char_data_arr[$i]['char_id'] . "'>";
									echo "<li><a href='#'><img src='/img/dropdown.png' border='0'></a>";
									echo "<ul>";
										echo "<li><a id='edit_character' name='" . $char_data_arr[$i]['char_id'] . "' href='#'>Edit</a></li>";
											if($char_data_arr[$i][sig]=='0'){
												echo "<li><a href='manage_insig.php?id=" . $char_data_arr[$i]['char_id'] . "&action=add'><img src='/img/plus_icon.png' border='0'></a></li>";
											}else{
												echo "<li><a href='manage_insig.php?id=" . $char_data_arr[$i]['char_id'] . "&action=remove'><img src='/img/minus_icon.png' border='0'></a></li>";
											};
									echo "</ul>";
									echo "</li>";
								echo "</ul>";
								$charname_utf8strip=preg_replace('/[^(\x20-\x7F)]*/','',$char_data_arr[$i]['char_name']);
								$image="sig/single_cache/character_" . $account_id . "_" . $charname_utf8strip . ".png";
								clearstatcache();
								if(file_exists($image)){
									echo "<img src='" . WEBSITE . "/sig/single_cache/character_" . $account_id . "_" . $charname_utf8strip . ".png?" . $unix_timestamp . "'>";
								}else{
									echo "<img src='" . WEBSITE . "/sig/single_cache/no_image.png'>";
								};
							echo "</div>";
						};
						echo "<div class='popup_character_info' id='character_info'></div>";
						echo "<div id='backgroundPopup'></div>";
?>