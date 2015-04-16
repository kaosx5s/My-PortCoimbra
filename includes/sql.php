<?php
function sql(){
	$db_connection = mysql_connect('localhost', 'dbo293117732', 'Dnh6PaFV');
	$db_selected = mysql_select_db('db293117732', $db_connection);
};
//connect to database
sql();
//account class
$account=new account();
class account{
	//ACCOUNT FUNCTIONS
	//WARNING: selects ALL from account, use wisely!	
	function get_account_id($id){
		$sql_acc=mysql_query("SELECT * FROM account_info WHERE id='" . $id . "'");
		return mysql_fetch_array($sql_acc, MYSQL_ASSOC);
	}
	function get_account_color($id){
		$sql_get_account_color_info=mysql_query("SELECT color FROM account_info WHERE id='" . $id . "'");
		return mysql_fetch_array($sql_get_account_color_info, MYSQL_ASSOC);
	}
	function set_account_color($account_id,$color){
		$sql_upd_color=mysql_query("UPDATE account_info SET color='" . $color . "' WHERE id='" . $account_id . "'");
	}
	function get_account_family_name($account_id){
		$sql_get_family_name=mysql_query("SELECT family_name FROM account_info WHERE id='" . $account_id . "'");
		return mysql_fetch_array($sql_get_family_name, MYSQL_ASSOC);
	}
	//security check (check account_id and ip_address).
	function security_check($id,$ip){
		$sql_sec_check=mysql_query("SELECT id,ip FROM account_info WHERE id='" . $id . "' && ip='" . $ip . "'");
		if(mysql_num_rows($sql_sec_check)=='1'){
			return 1;
		}else{
			return 0;
		}
	}
	//base account information (id and privacy).
	function base_account_information($id){
		$sql_get_acc_data=mysql_query("SELECT id,privacy,stat_privacy FROM account_info WHERE id='" . $id . "'");
		return mysql_fetch_array($sql_get_acc_data, MYSQL_ASSOC);
	}
	//get account region
	function get_account_region($id){
		$sql_get_acc_region=mysql_query("SELECT id,region FROM account_info WHERE id='" . $id . "'");
		return mysql_fetch_array($sql_get_acc_region, MYSQL_ASSOC);
	}
	//FAMILY PROFILE FUNCTIONS
	function profile_family_info($family_id){
		$sql_profile_get_fam_info=mysql_query("SELECT id,color,sig,grp_sig,sig_type,server,family_name,family_lvl,clan,privacy,stat_privacy,groups,char_list FROM account_info WHERE id='" . $family_id . "'");
		return mysql_fetch_array($sql_profile_get_fam_info, MYSQL_ASSOC);
	}
	//is real family?
	function check_if_family_is_real($family_name,$server_name){
		$sql_fam_check=mysql_query("SELECT id FROM account_info WHERE family_name='" . urldecode($family_name) . "' && server='" . $server_name . "' || url_name='" . $family_name . "' && server='" . $server_name . "'");
		return mysql_fetch_array($sql_fam_check, MYSQL_ASSOC);
	}
	//PRIVACY FUNCTION
	//update privacy (used on acc_mgmt.php).
	function update_privacy($finished_privacy,$id){
		$sql_updt_acc_privacy=mysql_query("UPDATE account_info SET privacy='" . $finished_privacy . "' WHERE id='" . $id . "'");
	}
	//update stat privacy (used on acc_mgmt.php).
	function update_stat_privacy($finished_stat_privacy,$id){
		$sql_updt_acc_stat_privacy=mysql_query("UPDATE account_info SET stat_privacy='" . $finished_stat_privacy . "' WHERE id='" . $id . "'");
	}
	//SIGNATURE RELATED FUNCTIONS
	//update sig settings.
	function update_sig_type($sig_type,$id){
		$sql_updt_sig_type=mysql_query("UPDATE account_info SET sig_type='" . $sig_type . "' WHERE id='" . $id . "'");
	}
	//update group sig size.
	function update_grp_sig_size($grp_sig_size,$id){
		$sql_updt_sig_grp_size=mysql_query("UPDATE account_info SET grp_sig_size='" . $grp_sig_size . "' WHERE id='" . $id . "'");
	}
	//set black bar
	function update_sig_set_black_bar($black_bar,$id){
		$sql_updt_sig_black_bar=mysql_query("UPDATE account_info SET show_bar='" . $black_bar . "' WHERE id='" . $id . "'");
	}
	//character list and sig information (used on manage.php)
	function get_signature_information($id){
		$sql_get_char_list=mysql_query("SELECT id,char_list,sig,grp_sig,grp_sig_size,sig_type,show_bar,server,family_name FROM account_info WHERE id='" . $id . "'");
		return mysql_fetch_array($sql_get_char_list, MYSQL_ASSOC);
	}
	function get_sig_background_image($arr_size,$sig_type,$sig_bar){
		$sql_get_sig_background_img=mysql_query("SELECT big,server_text,family_text,image FROM sig_config WHERE array_size='" . $arr_size . "' && sig_type='" . $sig_type . "' && bar_setting='" . $sig_bar . "'");
		return mysql_fetch_array($sql_get_sig_background_img, MYSQL_ASSOC);
	}
	function get_sig_background_image_grp($arr_size,$sig_type,$grp_size){
		$sql_get_sig_background_img_grp=mysql_query("SELECT image FROM sig_config_grp WHERE array_size='" . $arr_size . "' && sig_type='" . $sig_type . "' && grp_size='" . $grp_size . "'");
		return mysql_fetch_array($sql_get_sig_background_img_grp, MYSQL_ASSOC);
	}
	function get_group_signature_text_x($arr_size,$textlenght){
		$sql_get_grp_sig_text_x=mysql_query("SELECT text_x FROM sig_config_text_grp WHERE array_size='" . $arr_size . "' && text_lenght='" . $textlenght . "'");
		return mysql_fetch_array($sql_get_grp_sig_text_x, MYSQL_ASSOC);		
	}
	function set_character_grp_sig_num($account_id,$num){
			$sql_set_acc_char_grp=mysql_query("UPDATE account_info SET grp_sig='" . $num . "' WHERE id='" . $account_id . "'");
	}
	//CHARACTER RELATED FUNCTIONS
	function add_new_character($account_id,$char_name,$char_job,$char_lvl,$char_pro){
		//add this character.
		$sql_add_char=mysql_query("INSERT INTO character_info (`char_id`,`char_name`, `char_job`,`char_lvl`,`char_pro`) VALUES (NULL,'" . $char_name . "','" . $char_job . "','" . $char_lvl . "','" . $char_pro . "')");
		$last_id=mysql_insert_id();
		$sql_get_char_list=mysql_query("SELECT char_list FROM account_info WHERE id='" . $account_id . "'");
		$char_list=mysql_fetch_array($sql_get_char_list, MYSQL_ASSOC);
		//Update account table with new data.
		if($char_list['char_list'][0]==''){
			$sql_update_char_list=mysql_query('UPDATE account_info SET char_list="' . $last_id . '" WHERE id="' . $account_id . '"');
		}else{
			$sql_update_char_list=mysql_query('UPDATE account_info SET char_list="' . $char_list[char_list] . ';' . $last_id . '" WHERE id="' . $account_id . '"');
		}
	}
	function get_character_information_manage($char_list){
		$char_list_blow=explode(';', $char_list);
		$char_data_arr = array();
		for($i=0,$size=count($char_list_blow);$i<$size;$i++){
			//Get Character Data
			$sql_get_char_data=mysql_query("SELECT char_id,char_name,char_job,char_lvl,char_pro,char_exp,char_base_stats,sig,sort_num,grp_sort_num,grp,custom_image FROM character_info WHERE char_id='" . $char_list_blow[$i] . "'");
			//Build array.
			while($row=mysql_fetch_array($sql_get_char_data, MYSQL_ASSOC)){
				array_push($char_data_arr, $row);
			}
		}
		return $char_data_arr;
	}
	function get_character_information_profile($char_list){
		$char_list_blow=explode(';', $char_list);
		$char_data_arr = array();
		for($i=0,$size=count($char_list_blow);$i<$size;$i++){
			//Get Character Data
			$sql_get_char_data=mysql_query("SELECT char_id,char_name,char_job,char_lvl,char_pro,char_exp,char_base_stats,char_atk_stats,char_def_stats,char_res_stats,sig,sort_num,grp_sort_num,grp,custom_image FROM character_info WHERE char_id='" . $char_list_blow[$i] . "'");
			//Build array.
			while($row=mysql_fetch_array($sql_get_char_data, MYSQL_ASSOC)){
				array_push($char_data_arr, $row);
			}
		}
		return $char_data_arr;
	}
	function get_character_information_in_sig($char_list){
		$char_list_blow=explode(';', $char_list);
		$char_data_arr_in_sig = array();
		for($i=0,$size=count($char_list_blow);$i<$size;$i++){
			//Get Character Data
			$sql_get_char_data_in_sig=mysql_query("SELECT char_id,char_name,char_job,char_lvl,char_pro,sig,sort_num,custom_image FROM character_info WHERE char_id='" . $char_list_blow[$i] . "' && sig='1'");
			//Build array.
			while($row=mysql_fetch_array($sql_get_char_data_in_sig, MYSQL_ASSOC)){
				array_push($char_data_arr_in_sig, $row);
			}
		}
		return $char_data_arr_in_sig;
	}
	function get_character_information_in_grp($char_list,$group){
		$char_list_blow=explode(';', $char_list);
		$char_data_arr_in_grp = array();
		for($i=0,$size=count($char_list_blow);$i<$size;$i++){
			//Get Character Data
			$sql_get_char_data_in_grp=mysql_query("SELECT char_id,char_name,char_job,char_lvl,char_pro,grp,grp_sort_num,custom_image FROM character_info WHERE char_id='" . $char_list_blow[$i] . "' && grp='" . $group . "'");
			//Build array.
			while($row=mysql_fetch_array($sql_get_char_data_in_grp, MYSQL_ASSOC)){
				array_push($char_data_arr_in_grp, $row);
			}
		}
		return $char_data_arr_in_grp;
	}
	//get character job (mainly for json_image_list.php)
	function get_single_character_job($char_id){
		$sql_get_char_info_job=mysql_query("SELECT char_job FROM character_info WHERE char_id='" . $char_id . "'");
		return mysql_fetch_array($sql_get_char_info_job, MYSQL_ASSOC);
	}
	//get character group
	function get_single_character_group($char_id){
		$sql_get_char_grp=mysql_query("SELECT grp FROM character_info WHERE char_id='" . $char_id . "'");
		return mysql_fetch_array($sql_get_char_grp, MYSQL_ASSOC);
	}
	//get single character information
	function get_single_character_info($char_id){
		$sql_get_char_info=mysql_query("SELECT char_name,char_job,char_lvl,char_pro FROM character_info WHERE char_id='" . $char_id . "'");
		return mysql_fetch_array($sql_get_char_info, MYSQL_ASSOC);
	}
	//get single character information for profile
	function get_single_character_info_for_profile($char_id){
		$sql_get_char_info=mysql_query("SELECT * FROM character_info WHERE char_id='" . $char_id . "'");
		return mysql_fetch_array($sql_get_char_info, MYSQL_ASSOC);
	}
	//delete character
	function delete_character($account_id,$char_id){
		//delete character.
		$sql_delete_char=mysql_query("DELETE FROM character_info WHERE char_id='" . $char_id . "' LIMIT 1");
		//get char_list
		$sql_get_char_list=mysql_query("SELECT id,char_list,sig,grp_sig,sig_type FROM account_info WHERE id='" . $account_id . "'");
		$char_list=mysql_fetch_array($sql_get_char_list, MYSQL_ASSOC);
		$char_list_blow=explode(';', $char_list['char_list']);
		//delete from char_list.
		$key=array_search($char_id, $char_list_blow);
		unset($char_list_blow[$key]);
		$new_char_list=implode(';',$char_list_blow);
		$sql_update_char_list=mysql_query("UPDATE account_info SET char_list='" . $new_char_list . "' WHERE id='" . $account_id . "'");
	}
	//edit : change character name
	function edit_character_name($char_id,$new_char_name){
		$sql_updt_char_name=mysql_query("UPDATE character_info SET char_name='" . $new_char_name . "' WHERE char_id='" . $char_id . "'");
	}
	//edit : change character level
	function edit_character_level($char_id,$new_char_lvl){
		$sql_updt_char_lvl=mysql_query("UPDATE character_info SET char_lvl='" . $new_char_lvl . "' WHERE char_id='" . $char_id . "'");
	}
	//edit : change character promotion level
	function edit_character_promotion($char_id,$new_char_pro){
		$sql_updt_char_pro=mysql_query("UPDATE character_info SET char_pro='" . $new_char_pro . "' WHERE char_id='" . $char_id . "'");
	}
	//edit : change character in sig
	function edit_character_in_sig($account_id,$char_id,$new_in_sig){
		$sql_updt_char_in_sig=mysql_query("UPDATE character_info SET sig='" . $new_in_sig . "' WHERE char_id='" . $char_id . "'");
		$sql_set_account_sig=mysql_query("UPDATE account_info SET sig='1' WHERE id='" . $account_id . "'");
	}
	//edit : change character custom image
	function edit_character_custom_image($char_id,$new_custom_image){
		$sql_updt_char_custom_image=mysql_query("UPDATE character_info SET custom_image='" . $new_custom_image . "' WHERE char_id='" . $char_id . "'");
	}
	//edit : change character sort number
	function edit_character_sort_num($char_id,$new_sort_num){
		$sql_updt_char_sort_num=mysql_query("UPDATE character_info SET sort_num='" . $new_sort_num . "' WHERE char_id='" . $char_id . "'");
	}
	//edit : change character job
	function edit_character_job($char_id,$new_char_job){
		$sql_updt_char_job=mysql_query("UPDATE character_info SET char_job='" . $new_char_job . "' WHERE char_id='" . $char_id . "'");
	}
	//edit : change character in sig (manage.php ONLY)
	function character_change_insig($account_id,$char_id,$new_in_sig){
		$sql_updt_char_in_sig_mgt=mysql_query("UPDATE character_info SET sig='" . $new_in_sig . "' WHERE char_id='" . $char_id . "'");
		$sql_set_account_sigmgt=mysql_query("UPDATE account_info SET sig='1' WHERE id='" . $account_id . "'");
	}
	//set sig=1 (after sig creation).
	function update_account_sig($account_id){
		$sql_set_account_sig_regen=mysql_query("UPDATE account_info SET sig='1' WHERE id='" . $account_id . "'");
	}
	//GROUP RELATED FUNCTIONS
	function get_groups($id){
		$sql_get_group_list=mysql_query("SELECT groups FROM account_info WHERE id='" . $id . "'");
		return mysql_fetch_array($sql_get_group_list, MYSQL_ASSOC);
	}
	function add_new_group($account_id,$group_name){
		$sql_get_group_list=mysql_query("SELECT groups FROM account_info WHERE id='" . $account_id . "'");
		$group_list=mysql_fetch_array($sql_get_group_list, MYSQL_ASSOC);
		if($group_list['groups']==NULL){
			$sql_update_group_list_new=mysql_query("UPDATE account_info SET groups='" . $group_name . "' WHERE id='" . $account_id . "' LIMIT 1");
		}else{
			$sql_update_group_list=mysql_query("UPDATE account_info SET groups='" . $group_list['groups'] . ";" . $group_name . "' WHERE id='" . $account_id . "' LIMIT 1");
		}
	}
	function delete_group($account_id,$group_number){
		$sql_get_group_list=mysql_query("SELECT groups FROM account_info WHERE id='" . $account_id . "'");
		$group_list=mysql_fetch_array($sql_get_group_list, MYSQL_ASSOC);
		$group_list_blow=explode(';',$group_list['groups']);
		//find and unset specific group key.
		unset($group_list_blow[$group_number]);
		//implode the array.
		$slinky=implode(';',$group_list_blow);
		$wat=count($group_list_blow);
		//find out if this was the only group
		if($wat=='0'){
			//save with null value.
			$sql_set_group_list=mysql_query("UPDATE account_info SET groups=NULL WHERE id='" . $account_id . "'");
		}else if($wat>'0'){
			//save group string.
			$sql_set_group_list=mysql_query("UPDATE account_info SET groups='" . $slinky . "' WHERE id='" . $account_id . "' LIMIT 1");
		}
		//get list of characters.
		$sql_get_char_list=mysql_query("SELECT char_list FROM account_info WHERE id='" . $account_id . "'");
		$char_list=mysql_fetch_array($sql_get_char_list, MYSQL_ASSOC);
		//find list of characters with selected group.
		$char_list_blow=explode(';', $char_list[char_list]);
		$char_list_arr = array();
		for($i=0,$size=count($char_list_blow);$i<$size;$i++){
			//Get Character Data
			$sql_get_char_data=mysql_query("SELECT char_id,grp FROM character_info WHERE char_id='" . $char_list_blow[$i] . "' && grp='" . $group_number . "'");
			while($row=mysql_fetch_array($sql_get_char_data, MYSQL_ASSOC)){
				if($row['grp']!=''){
					array_push($char_list_arr, $row);
				}
			}
		}
		$arr_num=count($char_list_arr);
		for($j=0;$j<=$arr_num;$j++){
			//Update these characters man...
			$sql_uptd_chars=mysql_query("UPDATE character_info SET grp=NULL WHERE char_id='" . $char_list_arr[$j]['char_id'] . "' LIMIT 1");
		}
		//now find all characters who have a group greater than the group number and -1.
		$char_edit_arr = array();
		for($f=0,$size2=count($char_list_blow);$f<$size2;$f++){
			//Get Character Data
			$sql_get_char_data_extra=mysql_query("SELECT char_id,grp FROM character_info WHERE char_id='" . $char_list_blow[$f] . "' && grp>'" . $group_number . "'");
			while($row=mysql_fetch_array($sql_get_char_data_extra, MYSQL_ASSOC)){
				if($row['grp']!=''){
					array_push($char_edit_arr, $row);
				}
			}
		}
		//update these chars
		$arr_num_two=count($char_edit_arr);
		for($a=0;$a<=$arr_num_two;$a++){
			$sql_uptd_chars_again=mysql_query("UPDATE character_info SET grp='" . $char_edit_arr[$a]['grp'] . "'-1 WHERE char_id='" . $char_edit_arr[$i]['char_id'] . "' LIMIT 1");
		}
	}
	//set character group -> grp_mgmt.php
	function set_character_group($char_id,$new_group){
		if($new_group=="NoGroup"){
			$sql_set_char_group=mysql_query("UPDATE character_info SET grp=NULL WHERE char_id='" . $char_id . "'");
		}else{
			$sql_set_char_group=mysql_query("UPDATE character_info SET grp='" . $new_group . "' WHERE char_id='" . $char_id . "'");
		}
	}
	//edit : change character group sort number
	function edit_character_grp_sort_num($char_id,$new_sort_num){
		$sql_updt_char_grp_sort_num=mysql_query("UPDATE character_info SET grp_sort_num='" . $new_sort_num . "' WHERE char_id='" . $char_id . "'");
	}
	//set account to have grp_sig enabled (after sig regen).
	function update_account_grp_sig($account_id){
		$sql_set_account_grp_sig_regen=mysql_query("UPDATE account_info SET grp_sig='1' WHERE id='" . $account_id . "'");
	}
};
//datatable_job class
$datatable_job=new datatable_job();
class datatable_job{
	function get_job_list($region){
		$sql_get_job_list=mysql_query("SELECT Name FROM datatable_job_" . $region . " ORDER BY Name ASC");
		$job_list_arr = array();
		while($row=mysql_fetch_array($sql_get_job_list, MYSQL_ASSOC)){
			array_push($job_list_arr, $row);
		}
		return $job_list_arr;
	}
};
//site class
$site=new site();
class site{
	function get_site_agreement($id){
	$sql_get_agree=mysql_query("SELECT * FROM site_info WHERE id='" . $id . "'");
	return 	mysql_fetch_array($sql_get_agree, MYSQL_ASSOC);
	}
};
//mod class
$mod=new mod();
class mod{
	function get_account_info($id){
		$sql_acc_mod=mysql_query("SELECT * FROM account_info WHERE id='" . $id . "'");
		return mysql_fetch_array($sql_acc_mod, MYSQL_ASSOC);
	}
	function reset_account_password($id,$mod_id,$action){
		//create recovery key.
		$len = 8;
		$base='ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
		$max=strlen($base)-1;
		$activatecode='';
		mt_srand((double)microtime()*1000000);
		while (strlen($activatecode)<$len+1)
		$activatecode.=$base{mt_rand(0,$max)};
		//Set the recovery key.
		$sql_set_new_recovery_key=mysql_query("UPDATE account_info SET recovery='" . $activatecode . "' WHERE id='" . $id . "'");
		$sql_mod_log_account_password=mysql_query("INSERT INTO mod_log VALUES(NULL,'" . $mod_id . "','" . date('Y-m-d') . "','" . date('H:i:s') . "','" . $id . "','" . $action . ":" . $activatecode . "')");
		return $activatecode;
	}
	function reset_account_custom_url($id,$mod_id,$action){
		$sql_reset_custom_url=mysql_query("UPDATE account_info SET url_name=NULL WHERE id='" . $id . "'");
		$sql_mod_log_account_custom_url=mysql_query("INSERT INTO mod_log VALUES(NULL,'" . $mod_id . "','" . date('Y-m-d') . "','" . date('H:i:s') . "','" . $id . "','" . $action . "')");
		return "Custom URL has been reset.";
	}
	function reset_account_web_color($id,$mod_id,$action){
		$sql_reset_web_color=mysql_query("UPDATE account_info SET color='000000' WHERE id='" . $id . "'");
		$sql_mod_log_account_web_color=mysql_query("INSERT INTO mod_log VALUES(NULL,'" . $mod_id . "','" . date('Y-m-d') . "','" . date('H:i:s') . "','" . $id . "','" . $action . "')");
		return "Color has been reset to black.";
	}
	function reset_account_server($id,$mod_id,$action){
		$sql_reset_server=mysql_query("UPDATE account_info SET server=NULL WHERE id='" . $id . "'");
		$sql_mod_log_account_server=mysql_query("INSERT INTO mod_log VALUES(NULL,'" . $mod_id . "','" . date('Y-m-d') . "','" . date('H:i:s') . "','" . $id . "','" . $action . "')");
		return "Server has been reset.";
	}
	function reset_account_family_name($id,$mod_id,$action){
		$sql_reset_fam_name=mysql_query("UPDATE account_info SET family_name=NULL WHERE id='" . $id . "'");
		$sql_mod_log_account_family_name=mysql_query("INSERT INTO mod_log VALUES(NULL,'" . $mod_id . "','" . date('Y-m-d') . "','" . date('H:i:s') . "','" . $id . "','" . $action . "')");
		return "Family name has been reset.";
	}
	function reset_account_char_list($id,$mod_id,$action,$char_list){
		if($char_list==""){
			$sql_edit_character_list=mysql_query("UPDATE account_info SET char_list=NULL WHERE id='" . $id . "'");
			$sql_mod_log_account_character_list=mysql_query("INSERT INTO mod_log VALUES(NULL,'" . $mod_id . "','" . date('Y-m-d') . "','" . date('H:i:s') . "','" . $id . "','" . $action . ":NULL')");
		}else{
			$sql_edit_character_list=mysql_query("UPDATE account_info SET char_list='" . $char_list . "' WHERE id='" . $id . "'");
			$sql_mod_log_account_character_list=mysql_query("INSERT INTO mod_log VALUES(NULL,'" . $mod_id . "','" . date('Y-m-d') . "','" . date('H:i:s') . "','" . $id . "','" . $action . ":" . $char_list . "')");
		}
		return "Character list updated.";
	}
	function reset_account_grp_list($id,$mod_id,$action,$grp_list){
		if($grp_list==""){
			$sql_edit_group_list=mysql_query("UPDATE account_info SET groups=NULL WHERE id='" . $id . "'");
			$sql_mod_log_account_group_list=mysql_query("INSERT INTO mod_log VALUES(NULL,'" . $mod_id . "','" . date('Y-m-d') . "','" . date('H:i:s') . "','" . $id . "','" . $action . ":NULL')");
		}else{
			$sql_edit_group_list=mysql_query("UPDATE account_info SET groups='" . $grp_list . "' WHERE id='" . $id . "'");
			$sql_mod_log_account_group_list=mysql_query("INSERT INTO mod_log VALUES(NULL,'" . $mod_id . "','" . date('Y-m-d') . "','" . date('H:i:s') . "','" . $id . "','" . $action . ":" . $grp_list . "')");
		}
		return "Group list updated.";
	}
};
?>