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
$data=$_POST['id'];
$value=$_POST['value'];
$new_char_name=$_POST['char_name'];
$new_char_lvl=$_POST['char_lvl'];
$new_char_job=$_POST['char_job'];
$new_char_pro=$_POST['char_pro'];
//get vars
$del_char_id=$_GET['del'];

//break up the data
$blow_string=explode("_-", $data);

//blow_string[0]=character id
//blow_string[1]=action

if($new_char_name==""){
	if($data=="" || $value==""){
		//check get also!
		if($del_char_id==""){
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
	if($new_char_name!=""){
		//new character, check some stuff first
		//Check Name
		$name_lenght=strlen($_POST['char_name']);
		preg_match_all("([a-zA-Z0-9])", $_POST['char_name'], $matches);
		$match_lenght=count($matches[0]);
		if($match_lenght===$name_lenght){
			//no utf8 characters found, continue.
			if($name_lenght>'25'){
				echo "Character Name is longer than 25 characters.<br>";
				die;
			};
		}else{
			echo "Character Name contains invalid characters.<br>";
			die;
		};
		//Check Level
		$lvl_lenght=strlen($_POST['char_lvl']);
		preg_match_all("([0-9])", $_POST['char_lvl'], $lvl_matches);
		$lvl_match_lenght=count($lvl_matches[0]);
		if($lvl_match_lenght===$lvl_lenght){
			//no utf8 characters found, continue.
			if($lvl_lenght>'3'){
				echo "Character Level is longer than 3 characters.<br>";
				die;
			};
		}else{
			echo "Character Level contains invalid characters.<br>";
			die;
		};
		//Limit to level 150.
		if($_POST['char_lvl']>150){
			echo "Character Level cannot exceed 150.<br>";
			die;
		};
		//update this character.
		@$account->add_new_character($account_id,$new_char_name,$new_char_job,$new_char_lvl,$new_char_pro);
		echo "<font color='green'>Success!</font>";
	};
	if($blow_string[1]=="char_name"){
		$name_lenght=strlen($value);
		preg_match_all("([a-zA-Z0-9])", $value, $matches);
		$match_lenght=count($matches[0]);
		if($match_lenght===$name_lenght){
			//no utf8 characters found, continue.
			if($name_lenght>'25'){
				echo "<font color='red'>Error: Character name cannot be longer than 25 characters.</font>";
				die;
			}else{
				//good to go, edit the character.
				@$account->edit_character_name($char_id,$value);
				echo $value;
				die;
			};
		}else{
			echo "<font color='red'>Error: Character name contains invalid characters!</font>";
			die;
		};
	};
	if($del_char_id!=""){
		//make sure this character belongs to this account!
		$key=array_search($del_char_id, $char_list_blow);
		if($key!==''){
			@$account->delete_character($account_id,$del_char_id);
		};
	header('Location: manage.php');
	die;
	};
	if($blow_string[1]=="char_lvl"){
		//Limit to level 135.
		if($value>150){
			echo "<font color='red'>Character Level cannot exceed 150.</font>";
			die;
		};
		$lvl_lenght=strlen($value);
		preg_match_all("([0-9])", $value, $lvl_matches);
		$lvl_match_lenght=count($lvl_matches[0]);
		if($lvl_match_lenght===$lvl_lenght){
			//no utf8 characters found, continue.
			if($lvl_lenght>'3'){
				echo "<font color='red'>Character Level is longer than 3 characters.</font>";
				die;
			}else{
				//good to go, edit level
				@$account->edit_character_level($char_id,$value);
				echo $value;
			};
		}else{
			echo "<font color='red'>Character Level contains invalid characters.</font>";
			die;
		};
	};
	if($blow_string[1]=="char_pro"){
		@$account->edit_character_promotion($char_id,$value);
		switch($value){
			case '0':
				$new_pro="None";
				break;
			case '1':
				$new_pro="Veteran";
				break;
			case '2':
				$new_pro="Expert";
				break;
			case '3':
				$new_pro="Master";
				break;
			case '4':
				$new_pro="High Master";
				break;
			case '5':
				$new_pro="Grand Master";
				break;
		};
		echo $new_pro;
		die;
	};
	if($blow_string[1]=="in_sig"){
		@$account->edit_character_in_sig($account_id,$char_id,$value);
		if($value=='1'){
			$sig_txt="Yes";
		}else{
			$sig_txt="No";
		};
		echo $sig_txt;
		die;
	};
	if($blow_string[1]=="custom_image"){
		if($value=="Default"){
			$real_value='';
		}else{
			$real_value=$value;
		};
		@$account->edit_character_custom_image($char_id,$real_value);
		echo $value;
		die;
	};
	if($blow_string[1]=="sort_num"){
		@$account->edit_character_sort_num($char_id,$value);
		if($value=='21'){
			echo "Unsorted";
		}else{
			echo $value;
		};
		die;
	};
	if($blow_string[1]=="char_job"){
		@$account->edit_character_job($char_id,$value);
		echo urldecode($value);
		die;
	};
};
?>