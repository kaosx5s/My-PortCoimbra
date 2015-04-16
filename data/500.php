<?php
//old version, do not take anymore URI's.
die;
//Testing Split
//$uri='/data/extended_-Akamatsu_-14_-Clan.JPG';
//OLD client mod URL
//$uri='/data/char_-X5s_-(#?)0002699(##)Scout(?#)_-101.JPG';
//NEW client mod URL
//$uri='/data/char_-X5s_-(#?)0002699(##)Scout(?#)_-Veteran_-101.JPG';
//$uri='/data/charexp_-110_-0_-0.0370.JPG';
//$uri='/data/charbasestat_-110_-50_-60_-50_-100.JPG';
//$uri='/data/charbasestat2_-110_-30_-30.JPG';
$uri=$_SERVER['REQUEST_URI'];
include('../includes/sql.php');

//log incoming uri's
$server_date=date("m_d_y");
$fp = fopen("uri_log_" . $server_date . ".txt", 'a+');
fwrite($fp,"[" . $_SERVER['REMOTE_ADDR'] . "] - ");
fwrite($fp,$uri);
fwrite($fp,"\n");
fclose($fp);

//disreguard ui first load
if($uri=='/data/extended_-_-_-.JPG'){
	die;
};
if($uri=='/data/char_-_-_-_-.JPG'){
	die;
};
$current_ip = $_SERVER['REMOTE_ADDR'];
//If cookies are there set some vars.
if($_COOKIE['ACInfo']!=""){
	$cookie_ip=$_COOKIE['ACInfo'];
}else{
	$cookie_ip="";
};
if($_COOKIE['IDNUM']!=""){
	$cookie_id=$_COOKIE['IDNUM'];
}else{
	$cookie_id="";
};

//blow up the original URI
$blow_string=explode("_-", $uri);
if($blow_string[0]=='/data/charexp'){
	//global: time and date
	$date=date("m.d.y");
	$time=date("H:i:s");
	//check for half-send!
	if($blow_string[2]==''){
		//half send, disreguard data.
		die;
	};
	//IP address check.
	$sql_ip_find=mysql_query("SELECT id,ip,privacy FROM account_info WHERE ip='" . $current_ip . "'");
	if(!$sql_ip_find){
    	die('Invalid query: ' . mysql_error());
	};
	if(mysql_num_rows($sql_ip_find) == 0){
		//IP is not in database, die.
		die;
	};
	$char_account_ip=mysql_fetch_array($sql_ip_find, MYSQL_ASSOC);
	$account_id=$char_account_ip[id];
	//$blow_string[1]=char_lvl
	//$blow_string[2]=non pro exp
	//$blow_string[3]=pro exp
	$char_lvl=$blow_string[1];
	$blow_spl3=explode(".JPG", $blow_string[3]);
	if($blow_spl3[0]!=""){
		//use pro exp
		$char_exp=$blow_spl3[0];
	}else if($blow_spl3[0]==""){
		$char_exp=$blow_string[2];
	}else{
		//half send, die.
		die;
	};
	//send to clientmod_cache
	$sql_insert_char_exp_data=mysql_query("INSERT INTO clientmod_cache SET account_id='" . $account_id . "', text_info='Character EXP Data Received', date='" . $date . "', time='" . $time . "', charexp='" . $char_lvl . ";" . $char_exp . "'");
	if(!$sql_insert_char_exp_data){
   		die('Invalid query: ' . mysql_error());
	};
};
if($blow_string[0]=='/data/charbasestat'){
	$date=date("m.d.y");
	$time=date("H:i:s");
	//IP address check.
	$sql_ip_find=mysql_query("SELECT id,ip,privacy FROM account_info WHERE ip='" . $current_ip . "'");
	if(!$sql_ip_find){
    	die('Invalid query: ' . mysql_error());
	};
	if(mysql_num_rows($sql_ip_find) == 0){
		//IP is not in database, die.
		die;
	};
	$char_account_ip=mysql_fetch_array($sql_ip_find, MYSQL_ASSOC);
	$account_id=$char_account_ip[id];
	//break up the URI
	//$blow_string[1]=char_lvl
	//$blow_string[2]=basicStat.STR
	//$blow_string[3]=basicStat.AGI
	//$blow_string[4]=basicStat.CON
	//$blow_string[5]=basicStat.DEX
	$char_lvl=$blow_string[1];
	$basicstat_str=$blow_string[2];
	$basicstat_agi=$blow_string[3];
	$basicstat_con=$blow_string[4];
	$blow_spl5=explode(".JPG", $blow_string[5]);
	$basicstat_dex=$blow_spl5[0];
	//send to clientmod_cache
	$sql_insert_char_exp_data=mysql_query("INSERT INTO clientmod_cache SET account_id='" . $account_id . "', text_info='Character Base Stat Data Received', date='" . $date . "', time='" . $time . "', charbasestat='" . $char_lvl . ";" . $basicstat_str . ";" . $basicstat_agi . ";" . $basicstat_con . ";" . $basicstat_dex . "'");
	if(!$sql_insert_char_exp_data){
   		die('Invalid query: ' . mysql_error());
	};
};
if($blow_string[0]=='/data/charbasestat2'){
	$date=date("m.d.y");
	$time=date("H:i:s");
	//IP address check.
	$sql_ip_find=mysql_query("SELECT id,ip,privacy FROM account_info WHERE ip='" . $current_ip . "'");
	if(!$sql_ip_find){
    	die('Invalid query: ' . mysql_error());
	};
	if(mysql_num_rows($sql_ip_find) == 0){
		//IP is not in database, die.
		die;
	};
	$char_account_ip=mysql_fetch_array($sql_ip_find, MYSQL_ASSOC);
	$account_id=$char_account_ip[id];
	//break up the URI
	//$blow_string[1]=char_lvl
	//$blow_string[2]=basicStat.INTE
	//$blow_string[3]=basicStat.CHA
	$char_lvl=$blow_string[1];
	$basicstat_int=$blow_string[2];
	$blow_spl3=explode(".JPG", $blow_string[3]);
	$basicstat_cha=$blow_spl3[0];
	//send to clientmod_cache
	$sql_insert_char_exp_data=mysql_query("INSERT INTO clientmod_cache SET account_id='" . $account_id . "', text_info='Character Base Stat Data Received', date='" . $date . "', time='" . $time . "', charbasestat='" . $char_lvl . ";" . $basicstat_int . ";" . $basicstat_cha . "'");
	if(!$sql_insert_char_exp_data){
   		die('Invalid query: ' . mysql_error());
	};
};
if($blow_string[0]=='/data/charbattlestatatk'){
	die;
};
if($blow_string[0]=='/data/charbattlestatatk2'){
	die;
};
if($blow_string[0]=='/data/charbattlestatdef'){
	die;
};
if($blow_string[0]=='/data/charbattlestatdef2'){
	die;
};
if($blow_string[0]=='/data/charresiststat'){
	die;
};
if($blow_string[0]=='/data/charresiststat2'){
	die;
};

if($blow_string[0]=='/data/extended'){
	//global: time and date
	$date=date("m.d.y");
	$time=date("H:i:s");
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

	//Array[3] = Family Clan and .jpg extension hack.
	$blow_spl3=explode(".JPG", $blow_string[3]);
	$fam_clan=$blow_spl3[0];

	//SQL Stuff
	//Check IP and ID From Cookie!
	//We honestly dont care about the cookie, but do check the IP.
	$sql_ip_check=mysql_query("SELECT id,ip,privacy FROM account_info WHERE ip='" . $current_ip . "'");
	if(!$sql_ip_check){
    	die('Invalid query: ' . mysql_error());
	};
	if(mysql_num_rows($sql_ip_check) == 0){
		//IP is not in database, die.
		die;
	};
	$db_sql_ip=mysql_fetch_array($sql_ip_check, MYSQL_ASSOC);
	$blow_privacy=explode(";", $db_sql_ip['privacy']);
	if($blow_privacy[3]=='1'){
		//User has disabled dynamic updates, die.
		die;
	};
	if($db_sql_ip[id]!=""){
			//ok ip is in the db, continue.
			//write to cache system
			$sql_update_family_info=mysql_query("INSERT INTO clientmod_cache SET account_id='" . $db_sql_ip[id] . "', text_info='Account Data Received', date='" . $date . "', time='" . $time . "', accountinfo='" . $fam_lvl . ";" . $fam_clan . "'");
			if(!$sql_update_family_info){
    			die('Invalid query: ' . mysql_error());
			};
	}else{
		//ip is not in db, check cookie ip.
		$sql_ip_check_cookie=mysql_query("SELECT id,ip FROM account_info WHERE ip='" . $cookie_ip . "'");
		if(!$sql_ip_check_cookie){
    		die('Invalid query: ' . mysql_error());
		};
		$db_sql_ip_cookie=mysql_fetch_array($sql_ip_check_cookie, MYSQL_ASSOC);
		if($db_sql_ip_cookie[id]!=""){
			//No matches, disguard data.
			die;
		};
	};
};
if($blow_string[0]=='/data/char'){
	//global: get date and time
	$date=date("m.d.y");
	$time=date("H:i:s");
	//IP address check.
	$sql_ip_find=mysql_query("SELECT id,ip,privacy FROM account_info WHERE ip='" . $current_ip . "'");
	if(!$sql_ip_find){
    	die('Invalid query: ' . mysql_error());
	};
	if(mysql_num_rows($sql_ip_find) == 0){
		//IP is not in database, die.
		die;
	};
	$char_account_ip=mysql_fetch_array($sql_ip_find, MYSQL_ASSOC);
	$blow_privacy=explode(";", $char_account_ip['privacy']);
	if($blow_privacy[3]=='1'){
		//User has disabled dynamic updates, die.
		die;
	};
	$account_id=$char_account_ip[id];
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
	};
	//Take the array in parts and split them, ignore 0.
	//Array[1] = Character Name.
	$char_name=$blow_string[1];
	//Array[2] = Job with formatting.
	//split formatting.
	//aparently the special upc's do not have formatting, lets deal with that.
	if($blow_string[2]=='Sharif' || $blow_string[2]=='Claude' || $blow_string[2]=='M\'boma' || $blow_string[2]=='Lisa' || $blow_string[2]=='Rescue%20Kinght' || $blow_string[2]=='Lorch' || $blow_string[2]=='Idge' || $blow_string[2]=='Scout' || $blow_string[2]=='Elementalist' || $blow_string[2]=='Scout' || $blow_string[2]=='Berneli'){
		$char_job=$blow_string[2];
	}else{
		$spl2 = preg_split("/(\(#\?\))([0-9]*)(\(##\))/", $blow_string[2]);
		//Take split and extract job name.
		$blow_spl2=explode("(?#)", $spl2[1]);
		$char_job=$blow_spl2[0];
	};
	//Array[3] = Character promotion level.
	if($blow_string[3]==""){
		$char_pro='0';
	}else if($blow_string[3]=="Veteran"){
		$char_pro='1';
	}else if($blow_string[3]=="Expert"){
		$char_pro='2';
	}else if($blow_string[3]=="Master"){
		$char_pro='3';
	};
	//Array[4] = Job level and .jpg extension hack.
	$blow_spl4=explode(".JPG", $blow_string[4]);
	$char_lvl=$blow_spl4[0];
	//Check if char already exsists for this user.
	//Get Character List
	$sql_get_char_list=mysql_query("SELECT id,char_list FROM account_info WHERE id='" . $account_id . "'");
	if(!$sql_get_char_list){
    	die('Invalid query: ' . mysql_error());
	};
	$char_list=mysql_fetch_array($sql_get_char_list, MYSQL_ASSOC);
	if($char_list[char_list]==""){
		//no characters, skip to insert.
		//Insert as new character.
		//Insert into cache system
		$sql_insert_new_char_no_list=mysql_query("INSERT INTO clientmod_cache SET account_id='" . $account_id . "', text_info='Character Data Received', date='" . $date . "', time='" . $time . "', charinfo='0;" . $char_name . ";" . $char_job . ";" . $char_lvl . ";" . $char_pro . "'");
		if(!$sql_insert_new_char_no_list){
			die('Invalid query: ' . mysql_error());
		};
	}else{
		//user has characters, lets check them.
		$char_list_blow=explode(';', $char_list[char_list]);
		$add=0;
		for($i=0,$size=count($char_list_blow);$i<$size;$i++){
			//Get Character Data
			$sql_get_char_data=mysql_query("SELECT char_id,char_name FROM character_info WHERE char_id='" . $char_list_blow[$i] . "'");
			if(!$sql_get_char_data){
    			die('Invalid query: ' . mysql_error());
			};
			$char_data_arr[$i]=mysql_fetch_array($sql_get_char_data, MYSQL_ASSOC);
			//If character is in the list, update them.
			if($char_name==$char_data_arr[$i][char_name]){
				//character name is the same, cache update data.
				$sql_update_char_data=mysql_query("INSERT INTO clientmod_cache SET account_id='" . $account_id . "', text_info='Character Data Received', date='" . $date . "', time='" . $time . "', charinfo='" . $char_data_arr[$i][char_id] . ";" . $char_name . ";" . $char_job . ";" . $char_lvl . ";" . $char_pro . "'");
				if(!$sql_update_char_data){
    				die('Invalid query: ' . mysql_error());
				};
				//done, unset the array.
				unset($char_data_arr);
				$add=1;
			};
		};
		if($add==0){
			//character name is not in the list, add new character.
			//Insert as new character.
			$sql_insert_new_char=mysql_query("INSERT INTO clientmod_cache SET account_id='" . $account_id . "', text_info='Character Data Received', date='" . $date . "', time='" . $time . "', charinfo='0;" . $char_name . ";" . $char_job . ";" . $char_lvl . ";" . $char_pro . "'");
			if(!$sql_insert_new_char){
				die('Invalid query: ' . mysql_error());
			};
		};
	};
};
?>