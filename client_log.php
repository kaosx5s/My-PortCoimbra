<?php
session_start();
if(!isset($_SESSION['username'])){
	header('Location: index.php');
};
$account_id=$_SESSION['id'];
include('includes/sql.php');
include('includes/defines.php');
include('includes/header.tpl');

//get log info
	$client_mod_cache=array();
	$sql_get_client_log_list=mysql_query("SELECT * FROM clientmod_cache WHERE account_id='" . $account_id . "'");
	if(!$sql_get_client_log_list){
    	die('Invalid query: ' . mysql_error());
	};
	while($row=mysql_fetch_array($sql_get_client_log_list, MYSQL_ASSOC)){
		array_push($client_mod_cache, $row);
	};
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1 style='background-color:#" . $color . ";'>Client Mod Log</h1>";
				echo "<p>";
				if(count($client_mod_cache)=='0'){
					echo "No data awaiting administration.";
					echo "<br><a class='launch_small' href='manage.php'>Back</a>";
				}else{
					echo "<a class='launch_small' href='client_mod_mgmt.php?action=purge'>Purge Cache</a>";
					echo "<table border='1'>";
						echo "<tr>";
							echo "<th>Date & Time Recieved";
							echo "<th>Description";
							echo "<th>Data";
							echo "<th>";
						echo "</tr>";
						//display data
						for($i=0,$size=count($client_mod_cache);$i<$size;$i++){
							echo "<tr>";
								echo "<td>Date: " .$client_mod_cache[$i][date]. "<br>Time: " .$client_mod_cache[$i][time]. "</td>";
								echo "<td>" .$client_mod_cache[$i][text_info]. "</td>";
							if($client_mod_cache[$i][accountinfo]!=""){
								//explode data
								$account_info_data=explode(';',$client_mod_cache[$i][accountinfo]);
								echo "<td align='left'>Family Level: " .$account_info_data[0]. "<br>Clan: " .$account_info_data[1]. "</td>";
							}else if($client_mod_cache[$i][charinfo]!=""){
								$character_info_data=explode(';',$client_mod_cache[$i][charinfo]);
								//$character_info_data[0] is the character internal ID number!
								if($character_info_data[4]=='0'){
									$char_pro="None";
								}else if($character_info_data[4]=='1'){
									$char_pro="Veteran";
								}else if($character_info_data[4]=='2'){
									$char_pro="Expert";
								}else if($character_info_data[4]=='3'){
									$char_pro="Master";
								};
								echo "<td align='left'>Character Name: " .$character_info_data[1]. "<br>Character Class: " .urldecode($character_info_data[2]). "<br>Character Level: " .$character_info_data[3]. "<br>Character Promotion Level: " .$char_pro. "</td>";
							}else if($client_mod_cache[$i][charexp]!=""){
								//get current char_list
								$sql_get_char_list=mysql_query("SELECT id,char_list FROM account_info WHERE id='" . $account_id . "'");
								if(!$sql_get_char_list){
								   	die('Invalid query: ' . mysql_error());
								};
								$char_list=mysql_fetch_array($sql_get_char_list, MYSQL_ASSOC);
								$char_list_blow=explode(';', $char_list[char_list]);
								$character_exp_data=explode(';',$client_mod_cache[$i][charexp]);
								echo "<td align='left'>Character Job: " .$character_exp_data[0]. "<br>Character Level: " .$character_exp_data[1]. "<br>Character EXP: " .$character_exp_data[2]. "%<br>Apply Data To: ";
								//find all characters with $char_exp_data[0]==char_lvl.
								echo "<form name='char_exp' action='client_mod_mgmt.php?log_id=" . $client_mod_cache[$i][id] . "&action=acc' method='post'>";
								echo "<select name='character' class='std'>";
								$char_data_arr = array();
								for($k=0,$size2=count($char_list_blow);$k<$size2;$k++){
									//Get Character Data
									$sql_get_char_data=mysql_query("SELECT char_id,char_name,char_job FROM character_info WHERE char_id='" . $char_list_blow[$k] . "'");
									if(!$sql_get_char_data){
						    			die('Invalid query: ' . mysql_error());
									};
									//Build array.
									while($row=mysql_fetch_array($sql_get_char_data, MYSQL_ASSOC)){
										if($row[char_job]==$character_exp_data[0]){
											array_push($char_data_arr, $row);
										};
									};
								};
								if(count($char_data_arr)=='0'){
									//no characters.
										echo "<option class='std' value='none'>No Matching Characters</option>";
								}else{
									for($n=0,$size3=count($char_data_arr);$n<$size3;$n++){
										echo "<option class='std' value='" . $char_data_arr[$n][char_id] . "'>" . $char_data_arr[$n][char_name] . "</option>";
									};
								};
								echo "</td>";
							}else if($client_mod_cache[$i][charbasestat]!=""){
								//get current char_list
								$sql_get_char_list=mysql_query("SELECT id,char_list FROM account_info WHERE id='" . $account_id . "'");
								if(!$sql_get_char_list){
								   	die('Invalid query: ' . mysql_error());
								};
								$char_list=mysql_fetch_array($sql_get_char_list, MYSQL_ASSOC);
								$char_list_blow=explode(';', $char_list[char_list]);
								echo "<td align='left'>";
								$character_basestat_data=explode(';',$client_mod_cache[$i][charbasestat]);
								echo "Character Job: " .$character_basestat_data[0]. "<br>Character Level: " .$character_basestat_data[1]. "<br>";
								if(count($character_basestat_data)==6){
								echo "DEX: " .$character_basestat_data[2]. "<br>";
								echo "INT: " .$character_basestat_data[3]. "<br>";
								echo "CHA: " .$character_basestat_data[4]. "<br>";
								}else{
								echo "STR: " .$character_basestat_data[2]. "<br>";
								echo "AGI: " .$character_basestat_data[3]. "<br>";
								echo "CON: " .$character_basestat_data[4]. "<br>";
								};
								echo "<form name='char_basestat' action='client_mod_mgmt.php?log_id=" . $client_mod_cache[$i][id] . "&action=acc' method='post'>";
								echo "<select name='character' class='std'>";
								$char_data_arr = array();
								for($k=0,$size4=count($char_list_blow);$k<$size4;$k++){
									//Get Character Data
									$sql_get_char_data=mysql_query("SELECT char_id,char_name,char_job FROM character_info WHERE char_id='" . $char_list_blow[$k] . "'");
									if(!$sql_get_char_data){
						    			die('Invalid query: ' . mysql_error());
									};
									//Build array.
									while($row=mysql_fetch_array($sql_get_char_data, MYSQL_ASSOC)){
										if($row[char_job]==$character_basestat_data[0]){
											array_push($char_data_arr, $row);
										};
									};
								};
								if(count($char_data_arr)=='0'){
									//no characters.
										echo "<option class='std' value='none'>No Matching Characters</option>";
								}else{
									for($n=0,$size5=count($char_data_arr);$n<$size5;$n++){
										echo "<option class='std' value='" . $char_data_arr[$n][char_id] . "'>" . $char_data_arr[$n][char_name] . "</option>";
									};
								};
								echo "</td>";
							}else if($client_mod_cache[$i][charbattlestatatk]!=""){
								echo "<td align='left'>" .$client_mod_cache[$i][charbattlestatatk]. "</td>";
							}else if($client_mod_cache[$i][charbattlestatdef]!=""){
								echo "<td align='left'>" .$client_mod_cache[$i][charbattlestatdef]. "</td>";
							}else if($client_mod_cache[$i][charresiststat]!=""){
								echo "<td align='left'>" .$client_mod_cache[$i][charresiststat]. "</td>";
							};
							echo "<td>";
							if($client_mod_cache[$i][charexp]!="" || $client_mod_cache[$i][charbasestat]!=""){
							echo "<input class='launch_small2' type='submit' value='Accept Data'>";
								echo "</form>";
							}else{
								echo "<a class='launch_small2' href='client_mod_mgmt.php?log_id=" . $client_mod_cache[$i][id] . "&action=acc'>Accept Data</a>";
							};
							echo "<a class='launch_small2' href='client_mod_mgmt.php?log_id=" . $client_mod_cache[$i][id] . "&action=del'>Decline Data</a></td>";
						};
							echo "</tr>";
					echo "</table>";
				};
				echo "</p>";
			echo "</div>";
		echo "</div>";
include('includes/footer.tpl');
?>