<?php
session_start();
if(!session_is_registered(username)){
	header('Location: index.php');
};
include('../includes/sql.php');
include('../includes/defines.php');
$account_id=$_SESSION['id'];
$reload=$_GET['reload'];
$sig_info=@$account->get_signature_information($account_id);
$unix_timestamp=time();
if($reload=='1'){
	if($sig_info['sig_type']==1){
		echo "<img src='" . WEBSITE . "/sig/saved/sig_" . $account_id . ".png?" . $unix_timestamp . "'>";
	}else{
		echo "<img src='" . WEBSITE . "/sig/saved/sig_" . $account_id . ".jpg?" . $unix_timestamp . "'>";
	};
};
if($_GET['grp']!=""){
	@$group_list=$account->get_groups($account_id);
	$group_list_blow=explode(';', $group_list['groups']);
	for($i=0,$size=count($group_list_blow);$i<$size;$i++){
		if($i>=1){
			echo "<br>";
		};
		if($sig_info['sig_type']==1){
			echo "<img src='" . WEBSITE . "/sig/saved/sig_" . $account_id . "_group_" . $i . ".png?" . $unix_timestamp . "'>";
		}else{
			echo "<img src='" . WEBSITE . "/sig/saved/sig_" . $account_id . "_group_" . $i . ".jpg?" . $unix_timestamp . "'>";
		};
		echo "<br>";
		echo "<font size='-1'>BBCode:</font>";
		echo "<br>";
		if($sig_info['sig_type']==1){
			echo "<input type='text' size='75' readonly='readonly' value='[img]" . WEBSITE . "/sig/saved/sig_" . $account_id . "_group_" . $i . ".png[/img]'></input>";
		}else{
			echo "<input type='text' size='75' readonly='readonly' value='[img]" . WEBSITE . "/sig/saved/sig_" . $account_id . "_group_" . $i . ".jpg[/img]'></input>";
		};
		echo "<br>";
	};

};
?>