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
$funt=$_GET['privacy'];
$color=$_POST['color'];
if($color==''){
	if($funt==''){
		//no character id, die.
		header('Location: account.php');
	};
};
include('includes/sql.php');
//int pass as 0, wait for security_check to set it to 1.
$pass='0';
$pass=@$account->security_check($account_id,$ip);
if($pass=='1'){
	//get account info
	$acc_data_arr=@$account->base_account_information($account_id);
	//Blow up privacy.
	$blow_privacy=explode(";", $acc_data_arr['privacy']);
	$blow_stat_privacy=explode(";", $acc_data_arr['stat_privacy']);
};
if($pass=='1' && $color!=''){
	//change account color.
	@$account->set_account_color($account_id,$color);
};
if($pass=='1' && $funt=='10'){
	//Change required key.
	//function 10 (allow family level view).
	$new_privacy=array(1, $blow_privacy[1], $blow_privacy[2], $blow_privacy[3], $blow_privacy[4], $blow_privacy[5]);
	$finished_privacy=implode(";", $new_privacy);
	@$account->update_privacy($finished_privacy,$account_id);
};
if($pass=='1' && $funt=='11'){
	//function 11 (deny family level view).
	$new_privacy=array(0, $blow_privacy[1], $blow_privacy[2], $blow_privacy[3], $blow_privacy[4], $blow_privacy[5]);
	$finished_privacy=implode(";", $new_privacy);
	@$account->update_privacy($finished_privacy,$account_id);
};
if($pass=='1' && $funt=='20'){
	//function 20 (allow family clan view).
	$new_privacy=array($blow_privacy[0], 1, $blow_privacy[2], $blow_privacy[3], $blow_privacy[4], $blow_privacy[5]);
	$finished_privacy=implode(";", $new_privacy);
	@$account->update_privacy($finished_privacy,$account_id);
};
if($pass=='1' && $funt=='21'){
	//function 21 (deny family clan view).
	$new_privacy=array($blow_privacy[0], 0, $blow_privacy[2], $blow_privacy[3], $blow_privacy[4], $blow_privacy[5]);
	$finished_privacy=implode(";", $new_privacy);
	@$account->update_privacy($finished_privacy,$account_id);
};
if($pass=='1' && $funt=='30'){
	//function 30 (allow family char list view).
	$new_privacy=array($blow_privacy[0], $blow_privacy[1], 1, $blow_privacy[3], $blow_privacy[4], $blow_privacy[5]);
	$finished_privacy=implode(";", $new_privacy);
	@$account->update_privacy($finished_privacy,$account_id);
};
if($pass=='1' && $funt=='31'){
	//function 31 (deny family char list view).
	$new_privacy=array($blow_privacy[0], $blow_privacy[1], 0, $blow_privacy[3], $blow_privacy[4], $blow_privacy[5]);
	$finished_privacy=implode(";", $new_privacy);
	@$account->update_privacy($finished_privacy,$account_id);
};
//Locking feature
if($pass=='1' && $funt=='40'){
	$new_privacy=array($blow_privacy[0], $blow_privacy[1], $blow_privacy[2], 1, $blow_privacy[4], $blow_privacy[5]);
	$finished_privacy=implode(";", $new_privacy);
	@$account->update_privacy($finished_privacy,$account_id);
};
if($pass=='1' && $funt=='41'){
	$new_privacy=array($blow_privacy[0], $blow_privacy[1], $blow_privacy[2], 0, $blow_privacy[4], $blow_privacy[5]);
	$finished_privacy=implode(";", $new_privacy);
	@$account->update_privacy($finished_privacy,$account_id);
};
//Export Type
if($pass=='1' && $funt=='50'){
	$sig_type='1';
	@$account->update_sig_type($sig_type,$account_id);
};
if($pass=='1' && $funt=='51'){
	$sig_type='0';
	@$account->update_sig_type($sig_type,$account_id);
};
//Ignore group size limitation (20 or 70)
if($pass=='1' && $funt=='60'){
	$grp_sig_size='1';
	@$account->update_grp_sig_size($grp_sig_size,$account_id);
};
if($pass=='1' && $funt=='61'){
	$grp_sig_size='0';
	@$account->update_grp_sig_size($grp_sig_size,$account_id);
};
//Show signature in profile
if($pass=='1' && $funt=='70'){
	$new_privacy=array($blow_privacy[0], $blow_privacy[1], $blow_privacy[2], $blow_privacy[3], 1, $blow_privacy[5]);
	$finished_privacy=implode(";", $new_privacy);
	@$account->update_privacy($finished_privacy,$account_id);
};
if($pass=='1' && $funt=='71'){
	$new_privacy=array($blow_privacy[0], $blow_privacy[1], $blow_privacy[2], $blow_privacy[3], 0, $blow_privacy[5]);
	$finished_privacy=implode(";", $new_privacy);
	@$account->update_privacy($finished_privacy,$account_id);
};
//Show group sigs in profile
if($pass=='1' && $funt=='80'){
	$new_privacy=array($blow_privacy[0], $blow_privacy[1], $blow_privacy[2], $blow_privacy[3], $blow_privacy[4], 1);
	$finished_privacy=implode(";", $new_privacy);
	@$account->update_privacy($finished_privacy,$account_id);
};
if($pass=='1' && $funt=='81'){
	$new_privacy=array($blow_privacy[0], $blow_privacy[1], $blow_privacy[2], $blow_privacy[3], $blow_privacy[4], 0);
	$finished_privacy=implode(";", $new_privacy);
	@$account->update_privacy($finished_privacy,$account_id);
};
//Show info bar (black box) on sigs
if($pass=='1' && $funt=='90'){
	$black_bar='1';
	@$account->update_sig_set_black_bar($black_bar,$account_id);
};
if($pass=='1' && $funt=='91'){
	$black_bar='0';
	@$account->update_sig_set_black_bar($black_bar,$account_id);
};
//stat privacy settings (base)
if($pass=='1' && $funt=='100'){
	$new_stat_privacy=array(1, $blow_stat_privacy[1], $blow_stat_privacy[2], $blow_stat_privacy[3]);
	$finished_stat_privacy=implode(";", $new_stat_privacy);
	@$account->update_stat_privacy($finished_stat_privacy,$account_id);
};
if($pass=='1' && $funt=='101'){
	$new_stat_privacy=array(0, $blow_stat_privacy[1], $blow_stat_privacy[2], $blow_stat_privacy[3]);
	$finished_stat_privacy=implode(";", $new_stat_privacy);
	@$account->update_stat_privacy($finished_stat_privacy,$account_id);
};
//stat privacy settings (atk)
if($pass=='1' && $funt=='110'){
	$new_stat_privacy=array($blow_stat_privacy[0], 1, $blow_stat_privacy[2], $blow_stat_privacy[3]);
	$finished_stat_privacy=implode(";", $new_stat_privacy);
	@$account->update_stat_privacy($finished_stat_privacy,$account_id);
};
if($pass=='1' && $funt=='111'){
	$new_stat_privacy=array($blow_stat_privacy[0], 0, $blow_stat_privacy[2], $blow_stat_privacy[3]);
	$finished_stat_privacy=implode(";", $new_stat_privacy);
	@$account->update_stat_privacy($finished_stat_privacy,$account_id);
};
//stat privacy settings (def)
if($pass=='1' && $funt=='120'){
	$new_stat_privacy=array($blow_stat_privacy[0], $blow_stat_privacy[1], 1, $blow_stat_privacy[3]);
	$finished_stat_privacy=implode(";", $new_stat_privacy);
	@$account->update_stat_privacy($finished_stat_privacy,$account_id);
};
if($pass=='1' && $funt=='121'){
	$new_stat_privacy=array($blow_stat_privacy[0], $blow_stat_privacy[1], 0, $blow_stat_privacy[3]);
	$finished_stat_privacy=implode(";", $new_stat_privacy);
	@$account->update_stat_privacy($finished_stat_privacy,$account_id);
};
//stat privacy settings (res)
if($pass=='1' && $funt=='130'){
	$new_stat_privacy=array($blow_stat_privacy[0], $blow_stat_privacy[1], $blow_stat_privacy[2], 1);
	$finished_stat_privacy=implode(";", $new_stat_privacy);
	@$account->update_stat_privacy($finished_stat_privacy,$account_id);
};
if($pass=='1' && $funt=='131'){
	$new_stat_privacy=array($blow_stat_privacy[0], $blow_stat_privacy[1], $blow_stat_privacy[2], 0);
	$finished_stat_privacy=implode(";", $new_stat_privacy);
	@$account->update_stat_privacy($finished_stat_privacy,$account_id);
};
header('Location: account.php');
?>