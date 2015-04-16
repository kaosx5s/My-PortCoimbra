<?php
session_start();
if(!session_is_registered(username)){
	header('Location: ../index.php');
};
$account_id=$_SESSION['id'];
include('../includes/sql.php');
$character_id=$_GET['char'];
//set grp_sig account flag
@$account->update_account_grp_sig($account_id);
$region_info=@$account->get_account_region($account_id);
//Special function because PHP is retarded with alpha layers.
function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){
    if(!isset($pct)){
        return false;
    }
    $pct/=100;
    $w=imagesx($src_im);
    $h=imagesy($src_im);
    imagealphablending( $src_im, false );
    $minalpha=127;
    for($x=0;$x<$w;$x++)
		for($y=0;$y<$h;$y++){
			$alpha=(imagecolorat($src_im, $x, $y)>>24)&0xFF;
			if($alpha<$minalpha){
				$minalpha=$alpha;
			}
		}
		for($x=0;$x<$w;$x++){
			for($y=0;$y<$h;$y++){
				$colorxy=imagecolorat($src_im, $x, $y);
				$alpha=($colorxy>>24)&0xFF;
				if($minalpha!==127){
					$alpha=127+127*$pct*($alpha-127)/(127-$minalpha);
				}else{
					$alpha+=127*$pct;
				}
				$alphacolorxy=imagecolorallocatealpha($src_im, ($colorxy>>16)&0xFF, ($colorxy>>8)&0xFF, $colorxy&0xFF, $alpha);
				if(!imagesetpixel($src_im, $x, $y, $alphacolorxy)){
					return false;
				}
			}
		}
	imagecopy($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);
};
$char_list=@$account->get_signature_information($account_id);
//group sig size limitation
if($char_list['grp_sig_size']=='0'){
	$grp_size='20';
}else{
	$grp_size='70';
};
//set sig output
if($char_list['sig_type']==1){
	header("Content-Type: image/png");
}else{
	header("Content-Type: image/jpeg");
};
//check $_GET['char'] and get its group!
$char=@$account->get_single_character_group($character_id);
$char_data_arr=@$account->get_character_information_in_grp($char_list['char_list'],$char[grp]);
foreach($char_data_arr as $key_in_sig => $row_in_sig){
	$data_in_sig[$key_in_sig] = $row_in_sig[grp_sort_num];
}
array_multisort($data_in_sig, SORT_ASC, $char_data_arr);
//Groups
$group_list=@$account->get_groups($account_id);
$group_list_blow=explode(';', $group_list['groups']);
$selected_group=$group_list_blow[$char[grp]];

$arr_num=count($char_data_arr);
$textlenght=strlen($group_list_blow[$char[grp]]);
$offset=0;
$big=0;
if($arr_num>10){
	$arr_size=10;
}else{
	$arr_size=$arr_num;
};
//special cases
if(($arr_num==1 && $textlenght>4) || ($arr_num==2 && $textlenght>9)){
	$big=1;
};
$get_text_pos=@$account->get_group_signature_text_x($arr_size,$textlenght);
$text_x=$get_text_pos['text_x'];
//array size grp case switch
switch($arr_num){
	case ($arr_num>=10 && $arr_num<=20):
		$array_size_grp=11;
		break;
	case ($arr_num>=21 && $arr_num<=30):
		$array_size_grp=12;
		break;
	case ($arr_num>=31 && $arr_num<=40):
		$array_size_grp=13;
		break;
	case ($arr_num>=41 && $arr_num<=50):
		$array_size_grp=14;
		break;
	case ($arr_num>=51 && $arr_num<=60):
		$array_size_grp=15;
		break;
	case ($arr_num>=61 && $arr_num<=70):
		$array_size_grp=16;
		break;
	default:
		$array_size_grp=$arr_num;
		break;
};
if($arr_num<=20){
	$grp_size=0;
};
$background_img_url=@$account->get_sig_background_image_grp($array_size_grp,$char_list['sig_type'],$grp_size);
$img_bg = imagecreatefrompng("$background_img_url[image]");
$k=0;
$f=10;
for($i=0;$i<=$arr_num;$i++){
	//Get Image Data
	$sql_get_img_data=mysql_query('SELECT image FROM datatable_job_' . $region_info[region] . ' WHERE Name="' . urldecode($char_data_arr[$i]['char_job']) . '" LIMIT 1');
	while($row_img=mysql_fetch_array($sql_get_img_data, MYSQL_ASSOC)){
		if($grp_size=='20'){
			//no more than 20 characters.
			if($i>19){
				break;
			};
		};
		if($grp_size=='70'){
			//no more than 70 characters.
			if($i>69){
				break;
			};
		};
		$lvl=$char_data_arr[$i]['char_lvl'];
		$name=$char_data_arr[$i]['char_name'];
		$pro=$char_data_arr[$i]['char_pro'];
		switch ($lvl){
    		case ($lvl<=99):
				$img_a[$i] = imagecreatefrompng('bg/BG_lv1-100.png');
				$img_c[$i] = imagecreatefrompng('text/text_' . $lvl . '.png');
			break;
			case ($lvl>=100 && $pro==0):
				$img_a[$i] = imagecreatefrompng('bg/BG_lv1-100.png');
				$img_c[$i] = imagecreatefrompng('text/text_' . $lvl . '.png');
			break;
			case ($lvl>=100 && $lvl<110 && $pro==1):
				$img_a[$i] = imagecreatefrompng('bg/BG_veteran.png');
				$img_c[$i] = imagecreatefrompng('text/text_veteran.png');
			break;
			case ($lvl>=110 && $lvl<120 && $pro==2):
				$img_a[$i] = imagecreatefrompng('bg/BG_expert.png');
				$img_c[$i] = imagecreatefrompng('text/text_expert.png');
			break;
			case ($lvl>=120 && $lvl<130 && $pro==3):
				$img_a[$i] = imagecreatefrompng('bg/BG_master.png');
				$img_c[$i] = imagecreatefrompng('text/text_master.png');
			break;
			case ($lvl>=130 && $lvl<140 && $pro==4):
				$img_a[$i] = imagecreatefrompng('bg/BG_high_master.png');
				$img_c[$i] = imagecreatefrompng('text/text_high_master.png');
				break;
			case ($lvl>=140 && $lvl<150 && $pro==5):
				$img_a[$i] = imagecreatefrompng('bg/BG_grand_master.png');
				$img_c[$i] = imagecreatefrompng('text/text_grand_master.png');
				break;
			case ($lvl>=150 && $pro==5):
				$img_a[$i] = imagecreatefrompng('bg/BG_grand_master.png');
				$img_c[$i] = imagecreatefrompng('text/text_grand_master.png');
				break;
			default:
				$img_a[$i] = imagecreatefrompng('bg/BG_lv1-100.png');
				$img_c[$i] = imagecreatefrompng('text/text_' . $lvl . '.png');
				break;
		};
		if($char_data_arr[$i]['custom_image']!=""){
			$img_b[$i] = imagecreatefrompng('characters/' . $char_data_arr[$i]['custom_image'] . '.png');				
		}else{
			$img_b[$i] = imagecreatefrompng('characters/' . $row_img['image'] . '.png');
		};
		//Save Image
		imagecopymerge_alpha($img_a[$i], $img_b[$i], 0, 0, 0, 0, imagesx($img_b[$i]), imagesy($img_b[$i]),100);
		imagecopymerge_alpha($img_a[$i], $img_c[$i], 0, 0, 0, 0, imagesx($img_c[$i]), imagesy($img_c[$i]),100);
		imagesavealpha($img_a[$i], true);
		//save the image as a single character
		//remove any UTF8 characters from the image name.
		$charname_utf8strip=preg_replace('/[^(\x20-\x7F)]*/','',$name);
		imagepng($img_a[$i],'single_cache/character_' . $account_id . '_' . $charname_utf8strip . '.png',9);
		imagecopyresized($img_bg, $img_a[$i], $k, $f, 0, 0, 46, 55, 46, 55);
		//single row
		if($i<=8){
			$k+=47;
		};
		//double row
		if($i==9){
			$k-=423;
			$f=65;
		};
		if($i>9 && $i<=18){
			$k+=47;
			$f=65;
		};
		//triple row
		if($i==19){
			$k-=423;
			$f=120;
		};
		if($i>19 && $i<=28){
			$k+=47;
			$f=120;
		};
		//four row
		if($i==29){
			$k-=423;
			$f=175;
		};
		if($i>29 && $i<=38){
			$k+=47;
			$f=175;
		};
		//five row
		if($i==39){
			$k-=423;
			$f=230;
		};
		if($i>39 && $i<=48){
			$k+=47;
			$f=230;
		};
		//six row
		if($i==49){
			$k-=423;
			$f=285;
		};
		if($i>49 && $i<=58){
			$k+=47;
			$f=285;
		};
		//seven row
		if($i==59){
			$k-=423;
			$f=340;
		};
		if($i>59 && $i<=68){
			$k+=47;
			$f=340;
		};
	};
};
//Group
$textcolor = imagecolorallocate($img_bg, 255, 255, 255);
$black = imagecolorallocate($img_bg, 0, 0, 0);
$font = imageloadfont('font/pc_font.gdf');
//imagefilledrectangle($img_bg, 0, 0, 469, 10, $black);
if($big==1){
	//to much text, not enough room.
}else{
	imagestring($img_bg, $font, $text_x, -4, '' . $group_list_blow[$char[grp]] . '', $textcolor);
};
//Save alpha
imagesavealpha($img_bg, true);
if($char_list['sig_type']==1){
	imagepng($img_bg,'saved/sig_' . $account_id . '_group_' . $char[grp] . '.png' ,9);
}else{
	imagejpeg($img_bg,'saved/sig_' . $account_id . '_group_' . $char[grp] . '.jpg' ,100);
};
imagedestroy($img_bg);
header('Location: sig_regen.php?grp=1');
?>