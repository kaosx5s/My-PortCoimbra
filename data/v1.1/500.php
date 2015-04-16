<?php
//Testing Split
//$uri='/data/v1.1/extended_-Akamatsu_-14_-Clan.JPG';
//OLD client mod URL
//$uri='/data/v1.1/char_-X5s_-(#?)0002699(##)Scout(?#)_-101.JPG';
//NEW client mod URL
//$uri='/data/v1.1/char_-X5s_-(#?)0002699(##)Scout(?#)_-Veteran_-101.JPG';
//V1.1
//$uri='/data/v1.1/char_-X5s_-(#?)0034355(##)Calypso(?#)_-Veteran_-61.JPG';
//$uri='/data/v1.1/charbasestat_-(#?)0034355(##)Calypso(?#)_-61_-50_-70_-50.JPG';
//$uri='/data/v1.1/charbasestat2_-(#?)0034355(##)Calypso(?#)_-61_-70_-30_-30.JPG';
//$uri='/data/v1.1/charexp_-(#?)0034355(##)Calypso(?#)_-61_-26_-.JPG';
//$uri='/data/v1.1/charbattlestatatk_-(#?)0034353(##)CalypsoT(?#)_-61_-28_-23_-100.JPG';
//$uri='/data/v1.1/charbattlestatatk2_-(#?)0034353(##)CalypsoT(?#)_-61_-83_-20.JPG';
//$uri='/data/v1.1/charbattlestatdef_-(#?)0034353(##)CalypsoT(?#)_-61_-28_-0_-0.JPG';
//$uri='/data/v1.1/charbattlestatdef2_-(#?)0034353(##)CalypsoT(?#)_-61_-0_-0.JPG';
//$uri='/data/v1.1/charresiststat_-(#?)0034353(##)CalypsoT(?#)_-61_-0_-0_-0.JPG';
//$uri='/data/v1.1/charresiststat2_-(#?)0034353(##)CalypsoT(?#)_-61_-44_-0.JPG';
//V1.2
//$uri='/data/v1.1/extended_-Akamatsu_-14_-Clan_-78.JPG';
$uri=$_SERVER['REQUEST_URI'];
//disreguard ui first load
if($uri=='/data/v1.1/extended_-_-_-.JPG'){
	die;
};
if($uri=='/data/v1.1/char_-_-_-_-.JPG'){
	die;
};
include('../../includes/sql_cm.php');
//log incoming uri's
$server_date=date("m_d_y");
$fp = fopen("uri_log_" . $server_date . ".txt", 'a+');
fwrite($fp,"[" . $_SERVER['REMOTE_ADDR'] . "] - ");
fwrite($fp,$uri);
fwrite($fp,"\n");
fclose($fp);

//global vars
$current_ip=$_SERVER['REMOTE_ADDR'];
$date=date("m.d.y");
$time=date("H:i:s");

function check_job_name($initial_char_job){
	//aparently the special upc's do not have formatting, lets deal with that.
	if($initial_char_job=='Sharif' || $initial_char_job=='Claude' || $initial_char_job=='M\'boma' || $initial_char_job=='Lisa' || $initial_char_job=='Rescue%20Kinght' || $initial_char_job=='Lorch' || $initial_char_job=='Idge' || $initial_char_job=='Scout' || $initial_char_job=='Elementalist' || $initial_char_job=='Scout' || $initial_char_job=='Berneli'){
		$char_job=$initial_char_job;
	}else{
		$spl2 = preg_split("/(\(#\?\))([0-9]*)(\(##\))/", $initial_char_job);
		//Take split and extract job name.
		$blow_spl2=explode("(?#)", $spl2[1]);
		$char_job=$blow_spl2[0];
	}
	return $char_job;
}

//IP address check.
$account_base_info=@$client_mod->find_user_id($current_ip);
if($account_base_info=='0'){
	//IP is not in database, die.
	die;
}else{
	$account_id=$account_base_info[id];
	include('mod_siggen.php');
	include('mod_siggen_single.php');
};
//Get Account Information
$account_info=@$client_mod->get_account_informaiton($account_id);
//Check privacy settings
$blow_privacy=explode(";", $account_info[privacy]);
if($blow_privacy[3]=='1'){
	//User has disabled dynamic updates, die.
	die;
};

//blow up the original URI
$blow_string=explode("_-", $uri);

if($blow_string[0]=='/data/v1.1/charexp'){
	if($account_info[char_list]==""){
		//cannot find character, die.
		die;
	};
	//global: time and date
	//check for half-send!
	if($blow_string[2]==''){
		//half send, disreguard data.
		die;
	};

	//$blow_string[1]=char_job
	//$blow_string[2]=char_lvl
	//$blow_string[3]=non pro exp
	//$blow_string[4]=pro exp
	$char_job=@check_job_name($blow_string[1]);
	$char_lvl=$blow_string[2];
	$blow_spl4=explode(".JPG", $blow_string[4]);
	if($blow_spl4[0]!=""){
		//use pro exp
		$char_exp=$blow_spl4[0];
	}else if($blow_spl4[0]==""){
		$char_exp=$blow_string[3];
	}else{
		//half send, die.
		die;
	};

	//time to play... find that character!
	$char_list_blow=explode(';', $account_info[char_list]);
	for($i=0,$size=count($char_list_blow);$i<$size;$i++){
		//Get Character Data
		$character_data=@$client_mod->get_base_character_info($char_list_blow[$i]);
		//If character is in the list, update them.
		if($char_job==$character_data[char_job] && $char_lvl==$character_data[char_lvl]){
			//character found! Update data..
			@$client_mod->update_character_information_exp($character_data[char_id],$char_exp);
			//we found the character, no longer a need to continue this loop.
			$found=1;
			break;
		}else{
			$found=0;
		};
	};
	if($found==0){
		//no matches found, send to clientmod_cache
		$sql_insert_char_exp_data=mysql_query("INSERT INTO clientmod_cache SET account_id='" . $account_id . "', text_info='Character EXP Data Received', date='" . $date . "', time='" . $time . "', charexp='" . $char_job . ";" . $char_lvl . ";" . $char_exp . "'");
	};
	register_shutdown_function('siggen_single',$character_data[char_id],$account_id);
	register_shutdown_function('siggen',$account_id);
};
if($blow_string[0]=='/data/v1.1/charbasestat'){
	if($account_info[char_list]==""){
		//cannot find character, die.
		die;
	};
	$char_list_blow=explode(';', $account_info[char_list]);
	//break up the URI
	//$blow_string[1]=char_job
	//$blow_string[2]=char_lvl
	//$blow_string[3]=basicStat.STR
	//$blow_string[4]=basicStat.AGI
	//$blow_string[5]=basicStat.CON
	$char_job=@check_job_name($blow_string[1]);
	$char_lvl=$blow_string[2];
	$basicstat_str=$blow_string[3];
	$basicstat_agi=$blow_string[4];
	$blow_spl5=explode(".JPG", $blow_string[5]);
	$basicstat_con=$blow_spl5[0];

	for($i=0,$size=count($char_list_blow);$i<$size;$i++){
		//Get Character Data
		$character_data=@$client_mod->get_base_character_info($char_list_blow[$i]);
		//If character is in the list, update them.
		if($char_job==$character_data[char_job] && $char_lvl==$character_data[char_lvl]){
			//character found! Update data..
			@$client_mod->update_character_information_basestats1($character_data[char_id],$basicstat_str,$basicstat_agi,$basicstat_con);
			//we found the character, no longer a need to continue this loop.
			$found=1;
			break;
		}else{
			$found=0;
		};
	};
	if($found==0){
		//send to clientmod_cache
		$sql_insert_char_base_data=mysql_query("INSERT INTO clientmod_cache SET account_id='" . $account_id . "', text_info='Character Base Stat Data Received', date='" . $date . "', time='" . $time . "', charbasestat='" . $char_job . ";" . $char_lvl . ";" . $basicstat_str . ";" . $basicstat_agi . ";" . $basicstat_con . "'");
	};
	register_shutdown_function('siggen_single',$character_data[char_id],$account_id);
	register_shutdown_function('siggen',$account_id);
};
if($blow_string[0]=='/data/v1.1/charbasestat2'){
	if($account_info[char_list]==""){
		//cannot find character, die.
		die;
	};
	$char_list_blow=explode(';', $account_info[char_list]);
	//break up the URI
	//$blow_string[1]=char_job
	//$blow_string[2]=char_lvl
	//$blow_string[3]=basicStat.DEX
	//$blow_string[4]=basicStat.INTE
	//$blow_string[5]=basicStat.CHA
	$char_job=@check_job_name($blow_string[1]);
	$char_lvl=$blow_string[2];
	$basicstat_dex=$blow_string[3];
	$basicstat_int=$blow_string[4];
	$blow_spl5=explode(".JPG", $blow_string[5]);
	$basicstat_cha=$blow_spl5[0];

	for($i=0,$size=count($char_list_blow);$i<$size;$i++){
		$character_data=@$client_mod->get_base_character_info($char_list_blow[$i]);
		//If character is in the list, update them.
		if($char_job==$character_data[char_job] && $char_lvl==$character_data[char_lvl]){
			//character found! Update data..
			$char_data_arr_base_stats=explode(';', $character_data[char_base_stats]);
			if(count($char_data_arr_base_stats)==3){
				//append data
				$sql_update_char_info_basestat=mysql_query("UPDATE character_info SET char_base_stats='" . $character_data[char_base_stats] . ";" . $basicstat_dex . ";" . $basicstat_int . ";" . $basicstat_cha . "' WHERE char_id='" . $character_data[char_id] . "'");
			}else{
				//unset last two array key's
				array_splice($char_data_arr_base_stats,3,3,array($basicstat_dex,$basicstat_int,$basicstat_cha));
				$sql_update_char_info_basestat=mysql_query("UPDATE character_info SET char_base_stats='" . $char_data_arr_base_stats[0] . ";" . $char_data_arr_base_stats[1] . ";" . $char_data_arr_base_stats[2] . ";" . $char_data_arr_base_stats[3] . ";" . $char_data_arr_base_stats[4] . ";" . $char_data_arr_base_stats[5] . "' WHERE char_id='" . $character_data[char_id] . "'");
			};
			//we found the character, no longer a need to continue this loop.
			$found=1;
			break;
		};
	};
	if($found==0){
		//send to clientmod_cache
		$sql_insert_char_base2_data=mysql_query("INSERT INTO clientmod_cache SET account_id='" . $account_id . "', text_info='Character Base Stat Data Received', date='" . $date . "', time='" . $time . "', charbasestat='" . $char_job . ";" . $char_lvl . ";" . $basicstat_int . ";" . $basicstat_cha . "'");
	};
	register_shutdown_function('siggen_single',$character_data[char_id],$account_id);
	register_shutdown_function('siggen',$account_id);
};
if($blow_string[0]=='/data/v1.1/charbattlestatatk'){
	if($account_info[char_list]==""){
		//cannot find character, die.
		die;
	};
	$char_list_blow=explode(';', $account_info[char_list]);
	//break up the URI
	//$blow_string[1]=char_job
	//$blow_string[2]=char_lvl
	//$blow_string[3]=battleStat.AR
	//$blow_string[4]=battleStat.CATK
	//$blow_string[5]=battleStat.HR
	$char_job=@check_job_name($blow_string[1]);
	$char_lvl=$blow_string[2];
	$battlestat_ar=$blow_string[3];
	$battlestat_catk=$blow_string[4];
	$blow_spl5=explode(".JPG", $blow_string[5]);
	$battlestat_hr=$blow_spl5[0];

	for($i=0,$size=count($char_list_blow);$i<$size;$i++){
		//Get Character Data
		$character_data=@$client_mod->get_base_character_info($char_list_blow[$i]);
		//If character is in the list, update them.
		if($char_job==$character_data[char_job] && $char_lvl==$character_data[char_lvl]){
			//character found! Update data..
			@$client_mod->update_character_information_battlestats1($character_data[char_id],$battlestat_ar,$battlestat_catk,$battlestat_hr);
			//we found the character, no longer a need to continue this loop.
			$found=1;
			break;
		}else{
			$found=0;
		};
	};
	if($found==0){
		//send to clientmod_cache
		$sql_insert_char_atk_data=mysql_query("INSERT INTO clientmod_cache SET account_id='" . $account_id . "', text_info='Character Attack Stat Data Received', date='" . $date . "', time='" . $time . "', charbattlestatatk='" . $char_job . ";" . $char_lvl . ";" . $battlestat_ar . ";" . $battlestat_catk . ";" . $battlestat_hr . "'");
	};
	register_shutdown_function('siggen_single',$character_data[char_id],$account_id);
	register_shutdown_function('siggen',$account_id);
};
if($blow_string[0]=='/data/v1.1/charbattlestatatk2'){
	if($account_info[char_list]==""){
		//cannot find character, die.
		die;
	};
	$char_list_blow=explode(';', $account_info[char_list]);
	//break up the URI
	//$blow_string[1]=char_job
	//$blow_string[2]=char_lvl
	//$blow_string[3]=battleStat.CSPD
	//$blow_string[4]=battleStat.CRT
	$char_job=@check_job_name($blow_string[1]);
	$char_lvl=$blow_string[2];
	$battlestat_cspd=$blow_string[3];
	$blow_spl4=explode(".JPG", $blow_string[4]);
	$battlestat_crt=$blow_spl4[0];

	for($i=0,$size=count($char_list_blow);$i<$size;$i++){
		$character_data=@$client_mod->get_base_character_info($char_list_blow[$i]);
		//If character is in the list, update them.
		if($char_job==$character_data[char_job] && $char_lvl==$character_data[char_lvl]){
			//character found! Update data..
			$char_data_arr_atk_stats=explode(';', $character_data[char_atk_stats]);
			if(count($char_data_arr_atk_stats)==3){
				//append data
				$sql_update_char_info_atk_stats=mysql_query("UPDATE character_info SET char_atk_stats='" . $character_data[char_atk_stats] . ";" . $battlestat_cspd . ";" . $battlestat_crt . "' WHERE char_id='" . $character_data[char_id] . "'");
			}else{
				//unset last two array key's
				array_splice($char_data_arr_atk_stats,3,2,array($battlestat_cspd,$battlestat_crt));
				$sql_update_char_info_atk_stats=mysql_query("UPDATE character_info SET char_atk_stats='" . $char_data_arr_atk_stats[0] . ";" . $char_data_arr_atk_stats[1] . ";" . $char_data_arr_atk_stats[2] . ";" . $char_data_arr_atk_stats[3] . ";" . $char_data_arr_atk_stats[4] . "' WHERE char_id='" . $character_data[char_id] . "'");
			};
			//we found the character, no longer a need to continue this loop.
			$found=1;
			break;
		};
	};
	if($found==0){
		//send to clientmod_cache
		$sql_insert_char_atk2_data=mysql_query("INSERT INTO clientmod_cache SET account_id='" . $account_id . "', text_info='Character Attack Stat Data Received', date='" . $date . "', time='" . $time . "', charbattlestatatk='" . $char_job . ";" . $char_lvl . ";" . $battlestat_cspd . ";" . $battlestat_crt . "'");
	};
	register_shutdown_function('siggen_single',$character_data[char_id],$account_id);
	register_shutdown_function('siggen',$account_id);
};
if($blow_string[0]=='/data/v1.1/charbattlestatdef'){
	if($account_info[char_list]==""){
		//cannot find character, die.
		die;
	};
	$char_list_blow=explode(';', $account_info[char_list]);
	//break up the URI
	//$blow_string[1]=char_job
	//$blow_string[2]=char_lvl
	//$blow_string[3]=battleStat.DR
	//$blow_string[4]=battleStat.DEF
	//$blow_string[5]=battleStat.PR
	$char_job=@check_job_name($blow_string[1]);
	$char_lvl=$blow_string[2];
	$battlestat_dr=$blow_string[3];
	$battlestat_def=$blow_string[4];
	$blow_spl5=explode(".JPG", $blow_string[5]);
	$battlestat_pr=$blow_spl5[0];

	for($i=0,$size=count($char_list_blow);$i<$size;$i++){
		//Get Character Data
		$character_data=@$client_mod->get_base_character_info($char_list_blow[$i]);
		//If character is in the list, update them.
		if($char_job==$character_data[char_job] && $char_lvl==$character_data[char_lvl]){
			//character found! Update data..
			@$client_mod->update_character_information_battlestatsdef1($character_data[char_id],$battlestat_dr,$battlestat_def,$battlestat_pr);
			//we found the character, no longer a need to continue this loop.
			$found=1;
			break;
		}else{
			$found=0;
		};
	};
	if($found==0){
		//send to clientmod_cache
		$sql_insert_char_def_data=mysql_query("INSERT INTO clientmod_cache SET account_id='" . $account_id . "', text_info='Character Defense Stat Data Received', date='" . $date . "', time='" . $time . "', charbattlestatdef='" . $char_job . ";" . $char_lvl . ";" . $battlestat_dr . ";" . $battlestat_def . ";" . $battlestat_pr . "'");
	};
	register_shutdown_function('siggen_single',$character_data[char_id],$account_id);
	register_shutdown_function('siggen',$account_id);
};
if($blow_string[0]=='/data/v1.1/charbattlestatdef2'){
	if($account_info[char_list]==""){
		//cannot find character, die.
		die;
	};
	$char_list_blow=explode(';', $account_info[char_list]);
	//break up the URI
	//$blow_string[1]=char_job
	//$blow_string[2]=char_lvl
	//$blow_string[3]=battleStat.BLK
	//$blow_string[4]=battleStat.IMP
	$char_job=@check_job_name($blow_string[1]);
	$char_lvl=$blow_string[2];
	$battlestat_blk=$blow_string[3];
	$blow_spl4=explode(".JPG", $blow_string[4]);
	$battlestat_imp=$blow_spl4[0];

	for($i=0,$size=count($char_list_blow);$i<$size;$i++){
		$character_data=@$client_mod->get_base_character_info($char_list_blow[$i]);
		//If character is in the list, update them.
		if($char_job==$character_data[char_job] && $char_lvl==$character_data[char_lvl]){
			//character found! Update data..
			$char_data_arr_def_stats=explode(';', $character_data[char_def_stats]);
			if(count($char_data_arr_def_stats)==3){
				//append data
				$sql_update_char_info_def_stats=mysql_query("UPDATE character_info SET char_def_stats='" . $character_data[char_def_stats] . ";" . $battlestat_blk . ";" . $battlestat_imp . "' WHERE char_id='" . $character_data[char_id] . "'");
			}else{
				//unset last two array key's
				array_splice($char_data_arr_def_stats,3,2,array($battlestat_cspd,$battlestat_crt));
				$sql_update_char_info_def_stats=mysql_query("UPDATE character_info SET char_def_stats='" . $char_data_arr_def_stats[0] . ";" . $char_data_arr_def_stats[1] . ";" . $char_data_arr_def_stats[2] . ";" . $char_data_arr_def_stats[3] . ";" . $char_data_arr_def_stats[4] . "' WHERE char_id='" . $character_data[char_id] . "'");
			};
			//we found the character, no longer a need to continue this loop.
			$found=1;
			break;
		};
	};
	if($found==0){
		//send to clientmod_cache
		$sql_insert_char_def2_data=mysql_query("INSERT INTO clientmod_cache SET account_id='" . $account_id . "', text_info='Character Defense Stat Data Received', date='" . $date . "', time='" . $time . "', charbattlestatdef='" . $char_job . ";" . $char_lvl . ";" . $battlestat_blk . ";" . $battlestat_imp . "'");
	};
	register_shutdown_function('siggen_single',$character_data[char_id],$account_id);
	register_shutdown_function('siggen',$account_id);
};
if($blow_string[0]=='/data/v1.1/charresiststat'){
	if($account_info[char_list]==""){
		//cannot find character, die.
		die;
	};
	$char_list_blow=explode(';', $account_info[char_list]);
	//break up the URI
	//$blow_string[1]=char_job
	//$blow_string[2]=char_lvl
	//$blow_string[3]=resistanceStat.rFire
	//$blow_string[4]=resistanceStat.rIce
	//$blow_string[5]=resistanceStat.rLight
	$char_job=@check_job_name($blow_string[1]);
	$char_lvl=$blow_string[2];
	$resistancestat_fire=$blow_string[3];
	$resistancestat_ice=$blow_string[4];
	$blow_spl5=explode(".JPG", $blow_string[5]);
	$resistancestat_light=$blow_spl5[0];

	for($i=0,$size=count($char_list_blow);$i<$size;$i++){
		//Get Character Data
		$character_data=@$client_mod->get_base_character_info($char_list_blow[$i]);
		//If character is in the list, update them.
		if($char_job==$character_data[char_job] && $char_lvl==$character_data[char_lvl]){
			//character found! Update data..
			@$client_mod->update_character_information_resistancestat1($character_data[char_id],$resistancestat_fire,$resistancestat_ice,$resistancestat_light);
			//we found the character, no longer a need to continue this loop.
			$found=1;
			break;
		}else{
			$found=0;
		};
	};
	if($found==0){
		//send to clientmod_cache
		$sql_insert_char_res_data=mysql_query("INSERT INTO clientmod_cache SET account_id='" . $account_id . "', text_info='Character Resistance Stat Data Received', date='" . $date . "', time='" . $time . "', charresiststat='" . $char_job . ";" . $char_lvl . ";" . $resistancestat_fire . ";" . $resistancestat_ice . ";" . $resistancestat_light . "'");
	};
	register_shutdown_function('siggen_single',$character_data[char_id],$account_id);
	register_shutdown_function('siggen',$account_id);
};
if($blow_string[0]=='/data/v1.1/charresiststat2'){
	if($account_info[char_list]==""){
		//cannot find character, die.
		die;
	};
	$char_list_blow=explode(';', $account_info[char_list]);
	//break up the URI
	//$blow_string[1]=char_job
	//$blow_string[2]=char_lvl
	//$blow_string[3]=resistanceStat.rPsy
	//$blow_string[4]=resistanceStat.rState
	$char_job=@check_job_name($blow_string[1]);
	$char_lvl=$blow_string[2];
	$resistancestat_psy=$blow_string[3];
	$blow_spl4=explode(".JPG", $blow_string[4]);
	$resistancestat_state=$blow_spl4[0];

	for($i=0,$size=count($char_list_blow);$i<$size;$i++){
		$character_data=@$client_mod->get_base_character_info($char_list_blow[$i]);
		//If character is in the list, update them.
		if($char_job==$character_data[char_job] && $char_lvl==$character_data[char_lvl]){
			//character found! Update data..
			$char_data_arr_res_stats=explode(';', $character_data[char_res_stats]);
			if(count($char_data_arr_res_stats)==3){
				//append data
				$sql_update_char_info_res_stats=mysql_query("UPDATE character_info SET char_res_stats='" . $character_data[char_res_stats] . ";" . $resistancestat_psy . ";" . $resistancestat_state . "' WHERE char_id='" . $character_data[char_id] . "'");
			}else{
				//unset last two array key's
				array_splice($char_data_arr_res_stats,3,2,array($resistancestat_psy,$resistancestat_state));
				$sql_update_char_info_res_stats=mysql_query("UPDATE character_info SET char_res_stats='" . $char_data_arr_res_stats[0] . ";" . $char_data_arr_res_stats[1] . ";" . $char_data_arr_res_stats[2] . ";" . $char_data_arr_res_stats[3] . ";" . $char_data_arr_res_stats[4] . "' WHERE char_id='" . $character_data[char_id] . "'");
			};
			//we found the character, no longer a need to continue this loop.
			$found=1;
			break;
		};
	};
	if($found==0){
		//send to clientmod_cache
		$sql_insert_char_res2_data=mysql_query("INSERT INTO clientmod_cache SET account_id='" . $account_id . "', text_info='Character Resistance Stat Data Received', date='" . $date . "', time='" . $time . "', charresiststat='" . $char_job . ";" . $char_lvl . ";" . $resistancestat_psy . ";" . $resistancestat_state . "'");
	};
	register_shutdown_function('siggen_single',$character_data[char_id],$account_id);
	register_shutdown_function('siggen',$account_id);
};

if($blow_string[0]=='/data/v1.1/extended'){
	//Take the array in parts and split them, ignore 0.
	//Array[1] = Family Name.
	$fam_name=$blow_string[1];
	//Check for blank strings.
	if($fam_name==""){
		//Half send or dead send, die.
		die;
	};
	//Array[2] = Family Level.
	$fam_lvl=$blow_string[2];

	//version 1.2 update -> if there is an Array[4], else 1.1
	if($blow_string[4]!=""){
		$fam_clan=$blow_string[3];
		//Array[4] = Family exp percent and .jpg extension hack.
		$blow_spl4=explode(".JPG", $blow_string[4]);
		$fam_exp=$blow_spl4[0];
	}else{
		//Array[3] = Family Clan and .jpg extension hack.
		$blow_spl3=explode(".JPG", $blow_string[3]);
		$fam_clan=$blow_spl3[0];
	};

	//update some account data bro!
	//write to cache system.. for now
	$sql_update_family_info=mysql_query("INSERT INTO clientmod_cache SET account_id='" . $account_id . "', text_info='Account Data Received', date='" . $date . "', time='" . $time . "', accountinfo='" . $fam_lvl . ";" . $fam_clan . "'");
	register_shutdown_function('siggen',$account_id);
};

if($blow_string[0]=='/data/v1.1/char'){
	//Check for blank strings.
	$arr_check=count($blow_string);
	//what type of client am I?
	if($arr_check=='4'){
		//This user is using the old client mod.
		die;
	};
	if($arr_check<'2'){
		//Half send or dead send, die.
		die;
	};
	if($blow_string[1]=="" && $blow_string[2]==""){
		//dead send, die.
		die;
	};
	//Take the array in parts and split them, ignore 0.
	//Array[1] = Character Name.
	$char_name=$blow_string[1];
	//Array[2] = Job with formatting.
	//split formatting.
	//aparently the special upc's do not have formatting, lets deal with that.
	$char_job=@check_job_name($blow_string[2]);
	//Array[3] = Character promotion level.
	if($blow_string[3]==""){
		$char_pro='0';
	}else if($blow_string[3]=="Veteran" && $blow_string[4]>=100){
		$char_pro='1';
	}else if($blow_string[3]=="Expert" && $blow_string[4]>=110){
		$char_pro='2';
	}else if($blow_string[3]=="Master" && $blow_string[4]>=120){
		$char_pro='3';
	}else{
		$char_pro='0';
	};
	//Array[4] = Job level and .jpg extension hack.
	$blow_spl4=explode(".JPG", $blow_string[4]);
	$char_lvl=$blow_spl4[0];
	//Check if char exists
	if($account_info[char_list]==""){
		//no characters, insert as new character into cache system
		$sql_insert_new_char_no_list=mysql_query("INSERT INTO clientmod_cache SET account_id='" . $account_id . "', text_info='Character Data Received', date='" . $date . "', time='" . $time . "', charinfo='0;" . $char_name . ";" . $char_job . ";" . $char_lvl . ";" . $char_pro . "'");
	}else{
		//user has characters, lets check them.
		$char_list_blow=explode(';', $account_info[char_list]);
		for($i=0;$i<count($char_list_blow);$i++){
			//Get Character Data
			$character_data=@$client_mod->get_base_character_info($char_list_blow[$i]);
			//If character is in the list, update them.
			if($character_data[char_name]==$char_name){
				//character found! Update data..
				@$client_mod->update_character_information_base_data($character_data[char_id],$char_name,$char_job,$char_lvl,$char_pro);
				//we found the character, no longer a need to continue this loop.
				$found=1;
				break;
			}else{
				$found=0;
			};
		};
	};
	if($found==0){
		//character name is not in the list, add new character to the cache.
		$sql_insert_new_char=mysql_query("INSERT INTO clientmod_cache SET account_id='" . $account_id . "', text_info='Character Data Received', date='" . $date . "', time='" . $time . "', charinfo='0;" . $char_name . ";" . $char_job . ";" . $char_lvl . ";" . $char_pro . "'");
	};
	register_shutdown_function('siggen_single',$character_data[char_id],$account_id);
	register_shutdown_function('siggen',$account_id);
};
?>