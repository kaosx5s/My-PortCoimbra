<?php
function sql(){
	$db_connection = mysql_connect('localhost', 'dbo293117732', 'Dnh6PaFV');
	$db_selected = mysql_select_db('db293117732', $db_connection);
};
//connect to database
sql();
//client mod class
$client_mod=new client_mod();
class client_mod{
//ACCOUNT RELATED FUNCTIONS
	//Find user ID via IP address.
	function find_user_id($ip){
		$sql_ip_find=mysql_query("SELECT id,ip FROM account_info WHERE ip='" . $ip . "'");
		if(mysql_num_rows($sql_ip_find)=='1'){
			return mysql_fetch_array($sql_ip_find, MYSQL_ASSOC);
		}else{
			return 0;
		};
	}
	//Get account information
	function get_account_informaiton($id){
		$sql_get_acc_info=mysql_query("SELECT id,privacy,char_list FROM account_info WHERE id='" . $id . "'");
		return mysql_fetch_array($sql_get_acc_info, MYSQL_ASSOC);
	}
//CHARACTER RELATED FUNCTIONS
	//Get character info
	function get_base_character_info($char_id){
		$sql_get_char_data=mysql_query("SELECT char_id,char_name,char_job,char_lvl,char_base_stats,char_atk_stats,char_def_stats,char_res_stats FROM character_info WHERE char_id='" . $char_id . "'");
		return mysql_fetch_array($sql_get_char_data, MYSQL_ASSOC);
	}

//CHARACTER UPDATE RELATED FUNCTIONS
	function update_character_information_exp($char_id,$char_exp){
		$sql_update_char_data_exp=mysql_query("UPDATE character_info SET char_exp='" . $char_exp . "' WHERE char_id='" . $char_id . "'");
	}
	function update_character_information_base_data($char_id,$char_name,$char_job,$char_lvl,$char_pro){
		$sql_update_char_data_base=mysql_query("UPDATE character_info SET char_name='" . $char_name . "', char_job='" . $char_job . "', char_lvl='" . $char_lvl . "', char_pro='" . $char_pro . "' WHERE char_id='" . $char_id . "'");
	}
	//V1.1-1.2 Functions
	function update_character_information_basestats1($char_id,$basicstat_str,$basicstat_agi,$basicstat_con){
		$sql_update_char_data_basestat1=mysql_query("UPDATE character_info SET char_base_stats='" . $basicstat_str . ";" . $basicstat_agi . ";" . $basicstat_con . "' WHERE char_id='" . $char_id . "'");
	}
	function update_character_information_battlestats1($char_id,$battlestat_ar,$battlestat_catk,$battlestat_hr){
		$sql_update_char_data_battlestat1=mysql_query("UPDATE character_info SET char_atk_stats='" . $battlestat_ar . ";" . $battlestat_catk . ";" . $battlestat_hr . "' WHERE char_id='" . $char_id . "'");
	}
	function update_character_information_battlestatsdef1($char_id,$battlestat_dr,$battlestat_def,$battlestat_pr){
		$sql_update_char_data_battlestatdef1=mysql_query("UPDATE character_info SET char_def_stats='" . $battlestat_dr . ";" . $battlestat_def . ";" . $battlestat_pr . "' WHERE char_id='" . $char_id . "'");
	}
	function update_character_information_resistancestat1($char_id,$resistancestat_fire,$resistancestat_ice,$resistancestat_light){
		$sql_update_char_data_resistancestat1=mysql_query("UPDATE character_info SET char_res_stats='" . $resistancestat_fire . ";" . $resistancestat_ice . ";" . $resistancestat_light . "' WHERE char_id='" . $char_id . "'");
	}
	//V1.3 Functions
	function update_character_information_basestats_v13($char_id,$basicstat_str,$basicstat_agi,$basicstat_con,$basicstat_dex,$basicstat_int,$basicstat_cha){
		$sql_update_char_data_basestat_v13=mysql_query("UPDATE character_info SET char_base_stats='" . $basicstat_str . ";" . $basicstat_agi . ";" . $basicstat_con . ";" . $basicstat_dex . ";" . $basicstat_int . ";" . $basicstat_cha . "' WHERE char_id='" . $char_id . "'");
	}
	function update_character_information_battlestats_v13($char_id,$battlestat_ar,$battlestat_catk,$battlestat_hr,$battlestat_cspd,$battlestat_crt,$battlestat_defip){
		$sql_update_char_data_battlestat_v13=mysql_query("UPDATE character_info SET char_atk_stats='" . $battlestat_ar . ";" . $battlestat_catk . ";" . $battlestat_hr . ";" . $battlestat_cspd . ";" . $battlestat_crt . "'" . $battlestat_defip . "' WHERE char_id='" . $char_id . "'");
	}
	function update_character_information_battlestatsdef_v13($char_id,$battlestat_dr,$battlestat_def,$battlestat_pr,$battlestat_blk,$battlestat_imp){
		$sql_update_char_data_battlestatdef_v13=mysql_query("UPDATE character_info SET char_def_stats='" . $battlestat_dr . ";" . $battlestat_def . ";" . $battlestat_pr . ";" . $battlestat_blk . ";" . $battlestat_imp . "' WHERE char_id='" . $char_id . "'");
	}
	function update_character_information_resistancestat_v13($char_id,$resistancestat_fire,$resistancestat_ice,$resistancestat_light,$resistancestat_psy,$resistancestat_state){
		$sql_update_char_data_resistancestat_13=mysql_query("UPDATE character_info SET char_res_stats='" . $resistancestat_fire . ";" . $resistancestat_ice . ";" . $resistancestat_light . ";" . $resistancestat_psy . ";" . $resistancestat_state . "' WHERE char_id='" . $char_id . "'");
	}
};
?>