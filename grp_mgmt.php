<?php
session_start();
if(!isset($_SESSION['username'])){
	header('Location: index.php');
};
//session vars
$account_id=$_SESSION['id'];
//global vars
$ip=$_SERVER['REMOTE_ADDR'];
//post vars
$group_name=$_POST['group_name'];
$data=$_POST['id'];
$value=$_POST['value'];
$group_del_value=$_POST['group_del_name'];

//break up the data
$blow_string=explode("_-", $data);

//blow_string[0]=character id
//blow_string[1]=action
if($data==""){
	if($group_del_value==""){
		if($group_name==""){
			//something went wrong...
			echo "Error!";
			die;
		};
	};
};
include('includes/sql.php');
//security checks
//int pass as 0, wait for security_check to set it to 1.
$pass='0';
$pass=@$account->security_check($account_id,$ip);
if($pass=='1'){
	//get account info
	$char_list=@$account->get_signature_information($account_id);
	$char_list_blow=explode(';', $char_list[char_list]);
	//use char_id to get character details
	$char_id=$blow_string[0];
	$char_data=@$account->get_single_character_info($char_id);
	@$group_list=$account->get_groups($account_id);
	$group_list_blow=explode(';', $group_list['groups']);
	if($group_name!=""){
		@$account->add_new_group($account_id,$group_name);
		@$account->set_character_grp_sig_num($account_id,1);
		echo "<font color='green'>Success! A group has been added.</font>";
		die;
	};
	if($blow_string[1]=="group"){
		if($value=="NoGroup"){
			//set group to null.
			@$account->set_character_group($char_id,$value);
			@$account->set_character_grp_sig_num($account_id,0);
			echo "None";
			die;
		}else{
			@$account->set_character_group($char_id,$value);
			@$account->set_character_grp_sig_num($account_id,1);
			echo $group_list_blow[$value];
			die;
		};
	};
	if($group_del_value!=""){
		//delete image
		$del_file='sig/saved/sig_' . $account_id . '_group_' . $group_del_value . '.png';
		$del_file2='sig/saved/sig_' . $account_id . '_group_' . $group_del_value . '.jpg';
		@unlink($del_file);
		@unlink($del_file2);	
		@$account->delete_group($account_id,$group_del_value);
		echo "<font color='green'>Success! Group has been deleted.</font>";
		die;
	};
	if($blow_string[1]=="grp_sort_num"){
		@$account->edit_character_grp_sort_num($char_id,$value);
		if($value=='71'){
			echo "Unsorted";
		}else{
			echo $value;
		};
		die;
	};
};
?>