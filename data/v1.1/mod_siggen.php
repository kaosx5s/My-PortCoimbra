<?php
function update_account_sig($account_id){
	$sql_set_account_sig_regen=mysql_query("UPDATE account_info SET sig='1' WHERE id='" . $account_id . "'");
}
function get_signature_information($id){
	$sql_get_char_list=mysql_query("SELECT id,char_list,sig,grp_sig,grp_sig_size,sig_type,show_bar,server,family_name FROM account_info WHERE id='" . $id . "'");
	return mysql_fetch_array($sql_get_char_list, MYSQL_ASSOC);
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
function get_sig_background_image($arr_size,$sig_type,$sig_bar){
	$sql_get_sig_background_img=mysql_query("SELECT big,server_text,family_text,image FROM sig_config WHERE array_size='" . $arr_size . "' && sig_type='" . $sig_type . "' && bar_setting='" . $sig_bar . "'");
	return mysql_fetch_array($sql_get_sig_background_img, MYSQL_ASSOC);
}
function siggen($account_id){
	$region_info=get_account_region($account_id);
	update_account_sig($account_id);
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
	$char_list=get_signature_information($account_id);
	$char_data_arr=get_character_information_in_sig($char_list['char_list']);
	foreach($char_data_arr as $key_in_sig => $row_in_sig){
		$data_in_sig[$key_in_sig] = $row_in_sig[sort_num];
	}
	array_multisort($data_in_sig, SORT_ASC, $char_data_arr);
	$arr_num=count($char_data_arr);
	$big=0;
	if($arr_num>10){
		$arr_size=11;
	}else{
		$arr_size=$arr_num;
	};
	$background_img_url=get_sig_background_image($arr_size,$char_list['sig_type'],$char_list['show_bar']);
	$img_bg = imagecreatefrompng('../../sig/' . $background_img_url[image] . '');
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
					$img_a[$i] = imagecreatefrompng('../../sig/bg/BG_lv1-100.png');
					$img_c[$i] = imagecreatefrompng('../../sig/text/text_' . $lvl . '.png');
					break;
				case ($lvl>=100 && $pro==0):
					$img_a[$i] = imagecreatefrompng('../../sig/bg/BG_lv1-100.png');
					$img_c[$i] = imagecreatefrompng('../../sig/text/text_' . $lvl . '.png');
					break;
				case ($lvl>=100 && $lvl<110 && $pro==1):
					$img_a[$i] = imagecreatefrompng('../../sig/bg/BG_veteran.png');
					$img_c[$i] = imagecreatefrompng('../../sig/text/text_veteran.png');
					break;
				case ($lvl>=110 && $lvl<120 && $pro==2):
					$img_a[$i] = imagecreatefrompng('../../sig/bg/BG_expert.png');
					$img_c[$i] = imagecreatefrompng('../../sig/text/text_expert.png');
					break;
				case ($lvl>=120 && $lvl<130 && $pro==3):
					$img_a[$i] = imagecreatefrompng('../../sig/bg/BG_master.png');
					$img_c[$i] = imagecreatefrompng('../../sig/text/text_master.png');
					break;
				case ($lvl>=130 && $lvl<140 && $pro==4):
					$img_a[$i] = imagecreatefrompng('../../sig/bg/BG_high_master.png');
					$img_c[$i] = imagecreatefrompng('../../sig/text/text_high_master.png');
					break;
				case ($lvl>=140 && $lvl<150 && $pro==5):
					$img_a[$i] = imagecreatefrompng('../../sig/bg/BG_grand_master.png');
					$img_c[$i] = imagecreatefrompng('../../sig/text/text_grand_master.png');
					break;
				case ($lvl>=150 && $pro==5):
					$img_a[$i] = imagecreatefrompng('../../sig/bg/BG_grand_master.png');
					$img_c[$i] = imagecreatefrompng('../../sig/text/text_grand_master.png');
					break;
				default:
					$img_a[$i] = imagecreatefrompng('../../sig/bg/BG_lv1-100.png');
					$img_c[$i] = imagecreatefrompng('../../sig/text/text_' . $lvl . '.png');
					break;
			};
			if($char_data_arr[$i]['custom_image']!=""){
				$img_b[$i] = imagecreatefrompng('../../sig/characters/' . $char_data_arr[$i]['custom_image'] . '.png');				
			}else{
				$img_b[$i] = imagecreatefrompng('../../sig/characters/' . $row_img['image'] . '.png');
			};
			imagesavealpha($img_b[$i], true);
			imagecopymerge_alpha($img_a[$i], $img_b[$i], 0, 0, 0, 0, imagesx($img_b[$i]), imagesy($img_b[$i]),100);
			imagesavealpha($img_a[$i], true);
			imagecopymerge_alpha($img_a[$i], $img_c[$i], 0, 0, 0, 0, imagesx($img_c[$i]), imagesy($img_c[$i]),100);
			imagesavealpha($img_a[$i], true);
			//remove any UTF8 characters from the image name.
			$charname_utf8strip=preg_replace('/[^(\x20-\x7F)]*/','',$name);
			imagepng($img_a[$i],'../../sig/single_cache/character_' . $account_id . '_' . $charname_utf8strip . '.png',9);
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
		$textcolor = imagecolorallocate($img_bg,255,255,255);
		$black = imagecolorallocate($img_bg,0,0,0);
		$font = imageloadfont('../../sig/font/pc_font.gdf');
		//imagefilledrectangle($img_bg,0,0,469,10,$black);
		if($big==2){
		}else if($big==1){
			imagestring($img_bg,$font,$family_text, -4,'Family: ' . $char_list['family_name'] . '',$textcolor);
		}else{
			imagestring($img_bg,$font,$server_text, -4,'Server: ' . $char_list['server'] . '',$textcolor);
			imagestring($img_bg,$font,$family_text, -4,'Family: ' . $char_list['family_name'] . '',$textcolor);
		};
	};
	imagesavealpha($img_bg, true);
	if($char_list['sig_type']==1){
		imagepng($img_bg,'../../sig/saved/sig_' . $account_id . '.png',9);
	}else{
		imagejpeg($img_bg,'../../sig/saved/sig_' . $account_id . '.jpg',100);
	};
	imagedestroy($img_bg);
}
?>