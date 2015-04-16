<?php
function get_single_character_info($char_id){
	$sql_get_char_info=mysql_query("SELECT char_id,char_name,char_job,char_lvl,char_pro,custom_image FROM character_info WHERE char_id='" . $char_id . "'");
	return mysql_fetch_array($sql_get_char_info, MYSQL_ASSOC);
}
function get_account_region($id){
	$sql_get_acc_region=mysql_query("SELECT id,region FROM account_info WHERE id='" . $id . "'");
	return mysql_fetch_array($sql_get_acc_region, MYSQL_ASSOC);
}
function siggen_single($char_id,$account_id){
	$region_info=get_account_region($account_id);
	function imagecopymerge_alpha2($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){
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
	$char_data=get_single_character_info($char_id);
	$img_bg = imagecreatefrompng('../../sig/bg/grp_one.png');
	$sql_get_img_data=mysql_query('SELECT image FROM datatable_job_' . $region_info[region] . ' WHERE Name="' . urldecode($char_data['char_job']) . '" LIMIT 1');
		while($row_img=mysql_fetch_array($sql_get_img_data, MYSQL_ASSOC)){
			if($i>1){
				break;
			};
			$lvl=$char_data['char_lvl'];
			$name=$char_data['char_name'];
			$pro=$char_data['char_pro'];
			switch ($lvl){
	    		case ($lvl<=99):
					$img_a = imagecreatefrompng('../../sig/bg/BG_lv1-100.png');
					$img_c = imagecreatefrompng('../../sig/text/text_' . $lvl . '.png');
					break;
				case ($lvl>=100 && $pro==0):
					$img_a = imagecreatefrompng('../../sig/bg/BG_lv1-100.png');
					$img_c = imagecreatefrompng('../../sig/text/text_' . $lvl . '.png');
					break;
				case ($lvl>=100 && $lvl<110 && $pro==1):
					$img_a = imagecreatefrompng('../../sig/bg/BG_veteran.png');
					$img_c = imagecreatefrompng('../../sig/text/text_veteran.png');
					break;
				case ($lvl>=110 && $lvl<120 && $pro==2):
					$img_a = imagecreatefrompng('../../sig/bg/BG_expert.png');
					$img_c = imagecreatefrompng('../../sig/text/text_expert.png');
					break;
				case ($lvl>=120 && $lvl<130 && $pro==3):
					$img_a = imagecreatefrompng('../../sig/bg/BG_master.png');
					$img_c = imagecreatefrompng('../../sig/text/text_master.png');
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
					$img_a = imagecreatefrompng('../../sig/bg/BG_lv1-100.png');
					$img_c = imagecreatefrompng('../../sig/text/text_' . $lvl . '.png');
					break;
			};
			if($char_data['custom_image']!=""){
				$img_b = imagecreatefrompng('../../sig/characters/' . $char_data['custom_image'] . '.png');				
			}else{
				$img_b = imagecreatefrompng('../../sig/characters/' . $row_img['image'] . '.png');
			};
			imagesavealpha($img_b, true);
			imagecopymerge_alpha2($img_a, $img_b, 0, 0, 0, 0, imagesx($img_b), imagesy($img_b),100);
			imagesavealpha($img_a, true);
			imagecopymerge_alpha2($img_a, $img_c, 0, 0, 0, 0, imagesx($img_c), imagesy($img_c),100);
			imagesavealpha($img_a, true);
			imagepng($img_a,'../../sig/single_cache/character_' . $account_id . '_' . $name . '.png',9);
		};
	imagedestroy($img_bg);
}
?>