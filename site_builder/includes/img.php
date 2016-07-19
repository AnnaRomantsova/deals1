<?php
//сжтие картинки
 function image_resize($filename,$new_width,$new_height){
  if ($filename !=='' )  {
            $filename=substr($filename,1);
            list($width, $height) = getimagesize($filename);

            $ratio_orig = $width/$height;

            if ($new_width/$new_height > $ratio_orig) {
               $new_width = $new_height*$ratio_orig;
            } else {
               $new_height = $new_width/$ratio_orig;
            }

            $image_p = imagecreatetruecolor($new_width, $new_height);
            $image = imagecreatefromjpeg($filename);
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagejpeg($image_p, $filename, 100); //50% это качество 0-100%
  };
  //echo $width;
  //exif_thumbnail($filename, $new_width, $new_height, 'jpeg');
  //die;
 };

 //сжтие картинки
 //$filename - $_SERVER['DOCUMENT_ROOT'].$r1->result('image1')
 function image_resize_admin($filename,$new_width,$new_height){
  if ($filename !=='' )  {
            $filename = $_SERVER['DOCUMENT_ROOT'].$filename;
            //$filename=substr($filename,1);
            list($width, $height) = getimagesize($filename);

            $ratio_orig = $width/$height;

            if ($new_width/$new_height > $ratio_orig) {
               $new_width = $new_height*$ratio_orig;
            } else {
               $new_height = $new_width/$ratio_orig;
            }

            $image_p = imagecreatetruecolor($new_width, $new_height);
            $image = imagecreatefromjpeg($filename);
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagejpeg($image_p, $filename, 100); //50% это качество 0-100%
  };
  //echo $width;
  //exif_thumbnail($filename, $new_width, $new_height, 'jpeg');
  //die;
 };
?>