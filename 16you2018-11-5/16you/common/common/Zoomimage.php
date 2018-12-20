<?php

namespace common\common;
use yii\db\ActiveRecord;

/**
 *  @author junlin 
 * 参数说明：
	$src_file 图片对象
	$maxwidth 定义生成图片的最大宽度（单位：像素）
	$maxheight 生成图片的最大高度（单位：像素）
	$name 生成的图片名
 *
 */
class Zoomimage extends ActiveRecord{
	function resizeImage($src_file,$maxwidth,$maxheight,$name)
	{
		if (! file_exists ( $src_file )) {
			echo $src_file . " is not exists !";
			exit ();
		}
		// 图像类型
		$type = exif_imagetype ( $src_file );
		$support_type = array (
				IMAGETYPE_JPEG,
				IMAGETYPE_PNG,
				IMAGETYPE_GIF
		);
		if (! in_array ( $type, $support_type, true )) {
			echo "this type of image does not support! only support jpg , gif or png";
			exit ();
		}
		// Load image
		switch ($type) {
			case IMAGETYPE_JPEG :
				$im = imagecreatefromjpeg ( $src_file );
				break;
			case IMAGETYPE_PNG :
				$im = imagecreatefrompng ( $src_file );//从 PNG 文件或 URL 新建一图像
				break;
			case IMAGETYPE_GIF :
				$im = imagecreatefromgif ( $src_file );
				break;
			default :
				echo "Load image error!";
				exit ();
		}
		
		$pic_width = imagesx($im);
		$pic_height = imagesy($im);
		if(($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight))
		{
			$resizewidth_tag = false;
			$resizeheight_tag = false;
			if($maxwidth && $pic_width>$maxwidth)
			{
				$widthratio = $maxwidth/$pic_width;
				$resizewidth_tag = true;
			}
	
			if($maxheight && $pic_height>$maxheight)
			{
				$heightratio = $maxheight/$pic_height;
				$resizeheight_tag = true;
			}
	
			if($resizewidth_tag && $resizeheight_tag)
			{
				if($widthratio>$heightratio){
					$ratio = $widthratio;
				    $ratioh = $heightratio;
				}else{
					$ratio = $heightratio;
					$ratioh = $heightratio;
				}
			}
	
			if($resizewidth_tag && !$resizeheight_tag){
				$ratio = $widthratio;
			    $ratioh = $heightratio;
			}
			if($resizeheight_tag && !$resizewidth_tag){
				$ratio = $heightratio;
				$ratioh = $heightratio;
			}
			$newwidth = $pic_width * $ratio;
			$newheight = $pic_height * $ratioh;
	
			if(function_exists("imagecopyresampled"))
			{
				$newim = imagecreatetruecolor($newwidth,$newheight);
				imagecopyresampled($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
			}
			else
			{
				$newim = imagecreate($newwidth,$newheight);
				imagecopyresized($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
			}
	
			
			imagejpeg($newim,$name);
			imagedestroy($newim);
		}
		else
		{
			imagejpeg($im,$name);
		}
	}
}

?>