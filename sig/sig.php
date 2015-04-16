<?php
session_start();
if(!session_is_registered(username)){
	header('Location: ../index.php');
};
$account_id=$_SESSION['id'];
include('../includes/sql.php');
//set sig account flag
@$account->update_account_sig($account_id);
$region_info=@$account->get_account_region($account_id);
//Special function because PHP is retarded with alpha layers.
function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){
    if(!isset($pct)){
        return false;
    }
    $pct/=100;
    $w=imagesx($src_im);
    $h=imagesy($src_im);
    imagealphablending($src_im, false);
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
//set sig output
if($char_list['sig_type']==1){
	header("Content-Type: image/png");
}else{
	header("Content-Type: image/jpeg");
};
$char_data_arr=@$account->get_character_information_in_sig($char_list['char_list']);
foreach($char_data_arr as $key_in_sig => $row_in_sig){
	$data_in_sig[$key_in_sig] = $row_in_sig[sort_num];
}
array_multisort($data_in_sig, SORT_ASC, $char_data_arr);
$arr_num=count($char_data_arr);
$big=0;
//find out what type of background we are going to have
if($arr_num>10){
	$arr_size=11;
}else{
	$arr_size=$arr_num;
};
$background_img_url=@$account->get_sig_background_image($arr_size,$char_list['sig_type'],$char_list['show_bar']);
$img_bg = imagecreatefrompng("$background_img_url[image]");
$big=$background_img_url['big'];
$server_text=$background_img_url['server_text'];
$family_text=$background_img_url['family_text'];
$k=0;
if($char_list['show_bar']==1){
	$f=10;
}else{
	$f=0;
};
for($i=0;$i<=$arr_num;$i++){
	//Get Image Data
	$sql_get_img_data=mysql_query('SELECT image FROM datatable_job_' . $region_info[region] . ' WHERE Name="' . urldecode($char_data_arr[$i]['char_job']) . '" LIMIT 1');
	while($row_img=mysql_fetch_array($sql_get_img_data, MYSQL_ASSOC)){
		if($i>20){
			break;
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
		imagesavealpha($img_b[$i], true);
		imagecopymerge_alpha($img_a[$i], $img_b[$i], 0, 0, 0, 0, imagesx($img_b[$i]), imagesy($img_b[$i]),100);
		imagesavealpha($img_a[$i], true);
		imagecopymerge_alpha($img_a[$i], $img_c[$i], 0, 0, 0, 0, imagesx($img_c[$i]), imagesy($img_c[$i]),100);
		imagesavealpha($img_a[$i], true);
		//save the image as a single character
		//remove any UTF8 characters from the image name.
		$charname_utf8strip=preg_replace('/[^(\x20-\x7F)]*/','',$name);
		imagepng($img_a[$i],'single_cache/character_' . $account_id . '_' . $charname_utf8strip . '.png',9);
		//resume normal sig generation		
		imagecopyresized($img_bg, $img_a[$i], $k, $f, 0, 0, 46, 55, 46, 55);
		if($i<=8){
			$k+=47;
		};
		if($char_list['show_bar']==1){
			if($i==9){
				$k-=423;
				$f=65;
			};
			if($i>9){
				$k+=47;
				$f=65;
			};
		}else if($char_list['show_bar']==0){
			if($i==9){
				$k-=423;
				$f=55;
			};
			if($i>9){
				$k+=47;
				$f=55;
			};
		};
	};
};
if($char_list['show_bar']==1){
	//Family text
	$textcolor = imagecolorallocate($img_bg,255,255,255);
	$black = imagecolorallocate($img_bg,0,0,0);
	$font = imageloadfont('font/pc_font.gdf');
	//imagefilledrectangle($img_bg,0,0,469,10,$black);
	if($big==2){
		//to small, display nothing.
	}else if($big==1){
		//not enough space, just display family name.
		imagestring($img_bg,$font,$family_text, -4,'Family: ' . $char_list['family_name'] . '',$textcolor);
	}else{
		imagestring($img_bg,$font,$server_text, -4,'Server: ' . $char_list['server'] . '',$textcolor);
		imagestring($img_bg,$font,$family_text, -4,'Family: ' . $char_list['family_name'] . '',$textcolor);
	};
};
//Save alpha - last time.
imagesavealpha($img_bg, true);
// Output Image
if($char_list['sig_type']==1){
	imagepng($img_bg,'saved/sig_' . $account_id . '.png',9);
}else{
	imagejpeg($img_bg,'saved/sig_' . $account_id . '.jpg',100);
};
imagedestroy($img_bg);
header('Location: sig_regen.php?reload=1');
?>