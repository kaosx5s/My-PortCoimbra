<?php
session_start();
if(!isset($_SESSION['username'])){
	header('Location: index.php');
};
//global vars
$ip=$_SERVER['REMOTE_ADDR'];
//session vars
$account_id=$_SESSION['id'];
//get vars
$log_id=$_GET['log_id'];
$action=$_GET['action'];
$char_id=$_POST['character'];
if($id==''){
	//no id, die.
	header('Location: manage.php');
};
include('includes/sql.php');

//security checks
$pass='0';
$sql_sec_check=mysql_query("SELECT id,ip FROM account_info WHERE id='" . $account_id . "' && ip='" . $ip . "'");
if(!$sql_sec_check){
   	die('Invalid query: ' . mysql_error());
};
if(mysql_num_rows($sql_sec_check)=='1'){
	$pass='1';
	//Get account info.
	$sql_get_acc_data=mysql_query("SELECT id FROM account_info WHERE id='" . $account_id . "'");
	if(!$sql_get_acc_data){
    	die('Invalid query: ' . mysql_error());
	};
	$acc_data_arr=mysql_fetch_array($sql_get_acc_data, MYSQL_ASSOC);
};
//cleared!
if($pass=='1' && $action=='acc'){
	//Get log_id information
	$sql_get_log_info=mysql_query("SELECT * FROM clientmod_cache WHERE id='" . $log_id . "' && account_id='" . $account_id . "'");
	if(!$sql_get_log_info){
    	die('Invalid query: ' . mysql_error());
	};	
	$log_data_arr=mysql_fetch_array($sql_get_log_info, MYSQL_ASSOC);
	//figure out what type of data we are going to accept.
	if($log_data_arr[accountinfo]!=""){
		//explode account info
		$account_info_data=explode(';',$log_data_arr[accountinfo]);
		//update character info
		$sql_update_acc_info=mysql_query("UPDATE account_info SET family_lvl='" . $account_info_data[0] . "', clan='" . $account_info_data[1] . "' WHERE id='" . $account_id . "'");
		if(!$sql_update_acc_info){
			die('Invalid query: ' . mysql_error());
		};
	};
	if($log_data_arr[charinfo]!=""){
		//get current char_list
		$sql_get_char_list=mysql_query("SELECT id,char_list FROM account_info WHERE id='" . $account_id . "'");
		if(!$sql_get_char_list){
	    	die('Invalid query: ' . mysql_error());
		};
		$char_list=mysql_fetch_array($sql_get_char_list, MYSQL_ASSOC);
		//explode character info
		$character_info_data=explode(';',$log_data_arr[charinfo]);
		//is this a new char?
		if($character_info_data[0]=="0"){
			//insert new character
			$sql_insert_new_char_no_list=mysql_query("INSERT INTO character_info (`char_id`,`char_name`, `char_job`,`char_lvl`,`char_pro`) VALUES (NULL,'" . $character_info_data[1] . "','" . $character_info_data[2] . "','" . $character_info_data[3] . "','" . $character_info_data[4] . "')");
			if(!$sql_insert_new_char_no_list){
				die('Invalid query: ' . mysql_error());
			};
			$last_id=mysql_insert_id();
			//Update account table with new data.
			$sql_update_char_list_new=mysql_query("UPDATE account_info SET char_list='" . $char_list[char_list] . ";" . $last_id . "' WHERE id='" . $account_id . "'");
			if(!$sql_update_char_list_new){
				die('Invalid query: ' . mysql_error());
			};
		}else{
			//update character info
			$sql_update_char_info=mysql_query("UPDATE character_info SET char_name='" . $character_info_data[1] . "', char_job='" . $character_info_data[2] . "', char_lvl='" . $character_info_data[3] . "', char_pro='" . $character_info_data[4] . "' WHERE char_id='" . $character_info_data[0] . "'");
			if(!$sql_update_char_info){
		    	die('Invalid query: ' . mysql_error());
			};
		};
	};
	if($log_data_arr[charbasestat]!=""){
		$character_base_stat_data=explode(';',$log_data_arr[charbasestat]);
		if(count($character_base_stat_data)==6){
			//this is the 2nd set of stats!
			//get the current char_base_stats so we can append this data to it!
			$sql_get_base_stat_data=mysql_query("SELECT char_id,char_base_stats FROM character_info WHERE char_id='" . $char_id . "'");
			if(!$sql_get_base_stat_data){
			   	die('Invalid query: ' . mysql_error());
			};
			$char_base_stat_data_org=mysql_fetch_array($sql_get_base_stat_data, MYSQL_ASSOC);
			if(count($char_base_stat_data_org)==3){
				//append data
				$sql_update_char_info_basestat=mysql_query("UPDATE character_info SET char_base_stats='" . $char_base_stat_data_org[char_base_stats] . ";" . $character_base_stat_data[3] . ";" . $character_base_stat_data[4] . ";" . $character_base_stat_data[5] . "' WHERE char_id='" . $char_id . "'");
				if(!$sql_update_char_info_basestat){
				  	die('Invalid query: ' . mysql_error());
				};
			}else{
				//unset last three array key's
				array_splice($char_base_stat_data_org,3,3,$character_base_stat_data);
				//blow shit up
				$character_base_stat_data_explode=explode(';',$char_base_stat_data_org[char_base_stats]);
				$sql_update_char_info_basestat=mysql_query("UPDATE character_info SET char_base_stats='" . $character_base_stat_data_explode[0] . ";" . $character_base_stat_data_explode[1] . ";" . $character_base_stat_data_explode[2] . ";" . $char_base_stat_data_org[2] . ";" . $char_base_stat_data_org[3] . ";" . $char_base_stat_data_org[4] . "' WHERE char_id='" . $char_id . "'");
				if(!$sql_update_char_info_basestat){
				  	die('Invalid query: ' . mysql_error());
				};
			};
		}else{
			//we can only update character so get on with it!
			$sql_update_char_info_basestat=mysql_query("UPDATE character_info SET char_base_stats='" . $character_base_stat_data[2] . ";" . $character_base_stat_data[3] . ";" . $character_base_stat_data[4] . "' WHERE char_id='" . $char_id . "'");
			if(!$sql_update_char_info_basestat){
			  	die('Invalid query: ' . mysql_error());
			};
		};
	};
	//character exp update
	if($log_data_arr[charexp]!=""){
		$character_exp_data=explode(';',$log_data_arr[charexp]);
		//update character where POST['character']=character id.
		$sql_update_char_info_exp=mysql_query("UPDATE character_info SET char_exp='" . $character_exp_data[1] . "' WHERE char_id='" . $char_id . "'");
		if(!$sql_update_char_info_exp){
		  	die('Invalid query: ' . mysql_error());
		};	
	};
	//ok updated, now delete the log.
	$sql_del_client_mod_id=mysql_query("DELETE FROM clientmod_cache WHERE id='" . $log_id . "' && account_id='" . $account_id . "' LIMIT 1");
	if(!$sql_del_client_mod_id){
    	die('Invalid query: ' . mysql_error());
	};
};
if($pass=='1' && $action=='del'){
	//Action del (delete client mod data).
	$sql_del_client_mod_id=mysql_query("DELETE FROM clientmod_cache WHERE id='" . $log_id . "' && account_id='" . $account_id . "' LIMIT 1");
	if(!$sql_del_client_mod_id){
    	die('Invalid query: ' . mysql_error());
	};
header('Location: client_log.php');
die;
};
if($pass=='1' && $action=='purge'){
	//purge current accounts cache.
	$sql_purge_client_mod_log=mysql_query("DELETE FROM clientmod_cache WHERE account_id='" . $account_id . "'");
	if(!$sql_purge_client_mod_log){
    	die('Invalid query: ' . mysql_error());
	};
};
header('Location: manage.php');
?>