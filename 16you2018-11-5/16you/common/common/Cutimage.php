<?php

namespace common\common;
use yii\db\ActiveRecord;

/**
 * 裁剪图像
 * @author junlin
 *
 */
class Cutimage extends ActiveRecord{
	/*
	 * 说明：函数功能是把一个图像裁剪为任意大小的图像，图像不变形 参数说明：输入 需要处理图片的 文件名，生成新图片的保存文件名，生成新图片的宽，生成新图片的高 written by smallchicken time 
	*/
	function my_image_resize($src_file, $dst_file, $new_width, $new_height) {
		$new_width = rtrim($new_width,'px');
		$new_height = rtrim($new_height,'px');
		if ($new_width < 1 || $new_height < 1) {
			echo "params width or height error !";
			exit ();
		}
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
				$src_img = imagecreatefromjpeg ( $src_file );
				break;
			case IMAGETYPE_PNG :
				$src_img = imagecreatefrompng ( $src_file );//从 PNG 文件或 URL 新建一图像
				break;
			case IMAGETYPE_GIF :
				$src_img = imagecreatefromgif ( $src_file );
				break;
			default :
				echo "Load image error!";
				exit ();
		}
		$w = imagesx ( $src_img ); //取得图像的宽
		$h = imagesy ( $src_img ); //取得图像的高
		/*  if($new_width>$w){
			$new_width = $w;
		}
		if($new_height>$h){
			$new_height = $h;
		}  */
		$ratio_w = 1.0 * $new_width / $w;
		$ratio_h = 1.0 * $new_height / $h;
		$ratio = 1.0;
		// 生成的图像的高宽比原来的都小，或都大 ，原则是 取大比例放大，取大比例缩小（缩小的比例就比较小了）
		if (($ratio_w < 1 && $ratio_h < 1) || ($ratio_w > 1 && $ratio_h > 1)) {
			if ($ratio_w < $ratio_h) {
				$ratio = $ratio_h; // 情况一，宽度的比例比高度方向的小，按照高度的比例标准来裁剪或放大
			} else {
				$ratio = $ratio_w;
			}
			// 定义一个中间的临时图像，该图像的宽高比 正好满足目标要求
			$inter_w = ( int ) ($new_width / $ratio);
			$inter_h = ( int ) ($new_height / $ratio);
			$inter_img = imagecreatetruecolor ( $inter_w, $inter_h );//建立的是一幅大小为 x和 y的黑色图像(默认为黑色)
			imagecopy ( $inter_img, $src_img, 0, 0, 0, 0, $inter_w, $inter_h );// 函数用于拷贝图像或图像的一部分。
			// 生成一个以最大边长度为大小的是目标图像$ratio比例的临时图像
			// 定义一个新的图像rtrim($str, 'px');
	
			$new_img = imagecreatetruecolor ( rtrim($new_width,'px'), rtrim($new_height,'px') );//新建一个真彩色图像,代表了一幅大小为 x_size 和 y_size 的黑色图像
			imagecopyresampled ( $new_img, $inter_img, 0, 0, 30, 30, rtrim($new_width,'px'), rtrim($new_height,'px'), $inter_w, $inter_h );//其像素插值算法得到的图像边缘比较平滑.质量较好(但该函数的速度比 ImageCopyResized() 慢).
			switch ($type) {
				case IMAGETYPE_JPEG :
					imagejpeg ( $new_img, $dst_file, 100 ); // 存储图像
					break;
				case IMAGETYPE_PNG :
					imagepng ( $new_img, $dst_file, 100 );
					break;
				case IMAGETYPE_GIF :
					imagegif ( $new_img, $dst_file, 100 );
					break;
				default :
					break;
			}
		} 	// end if 1
		// 2 目标图像 的一个边大于原图，一个边小于原图 ，先放大平普图像，然后裁剪
		// =if( ($ratio_w < 1 && $ratio_h > 1) || ($ratio_w >1 && $ratio_h <1) )
		else {
			$ratio = $ratio_h > $ratio_w ? $ratio_h : $ratio_w; // 取比例大的那个值
			// 定义一个中间的大图像，该图像的高或宽和目标图像相等，然后对原图放大
			$inter_w = ( int ) ($w * $ratio);
			$inter_h = ( int ) ($h * $ratio);
			$inter_img = imagecreatetruecolor ( $inter_w, $inter_h );
			// 将原图缩放比例后裁剪
			imagecopyresampled ( $inter_img, $src_img, 0, 0, 0, 0, $inter_w, $inter_h, $w, $h );
			// 定义一个新的图像
			$new_img = imagecreatetruecolor ( $new_width, $new_height );
			imagecopy ( $new_img, $inter_img, 0, 0, 30, 30, $new_width, $new_height );
			switch ($type) {
				case IMAGETYPE_JPEG :
					imagejpeg ( $new_img, $dst_file, 100 ); // 存储图像
					break;
				case IMAGETYPE_PNG :
					imagepng ( $new_img, $dst_file, 100 );
					break;
				case IMAGETYPE_GIF :
					imagegif ( $new_img, $dst_file, 100 );
					break;
				default :
					break;
			}
		} 
	} 
}

?>