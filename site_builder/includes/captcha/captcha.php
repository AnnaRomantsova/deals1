<?

/**
 * картинка для механизма CAPTCHA
 * @package ALL
 */

 session_start();
 mt_srand((double)microtime()*1000000);
 $num = mt_rand('111','999');
// echo $num.'<br />';
 $num=str_replace('7','0',$num);
// echo $num;
 // Запишем номер в сессию
 $_SESSION['secure_code']=md5($num);
 // Создадим рисунок размером 50x15
 $img = imagecreate('100', '20');
 // Зададим задний цвет (серый) по RGB
 $back = imagecolorallocate($img, 218, 218 ,218);
 // Зададим черный цвет
 $black = imagecolorallocate($img, 0, 0, 0);
 $gray = imagecolorallocate($img, 100, 100, 100);
 // Рисуем бордюр
 imageline($img, 0, 0, 99, 0, $black);
 imageline($img, 0, 0, 0, 19 , $black);
 imageline($img, 0, 19, 99, 19 , $black);
 imageline($img, 99, 0, 99, 19 , $black);

 imageline($img, rand('1','10'), rand('1','7'), rand('90','99'), rand('11','19'), $gray);
 imageline($img, rand('1','10'), rand('5','11'), rand('90','99'), rand('5','11'), $gray);
 imageline($img, rand('10','20'), rand('11','14'), rand('90','99'), rand('11','14'), $gray);
 imageline($img, rand('10','30'), rand('1','14'), rand('90','99'),  rand('1','14'), $gray);

 
 // Рисуем цифры
$gdi = gd_info();
if ('with freetype' == $gdi['FreeType Linkage']) {
 for ($i=0; $i<strlen($num); $i++)
  { $angl=40-rand('0','80');
   
    imageTTFText($img,12,$angl,23+($i*26),15,$black,$_SERVER['DOCUMENT_ROOT'].'/site_builder/includes/captcha/font.ttf',substr($num,$i,1));
  }

}
else
	imagestring($img,5,5,3,$num,$black);
  

 // Выводим рисунок
if (function_exists("imagegif")) {
    header ("Content-type: image/gif");
    imagegif ($img);
}
elseif (function_exists("imagejpeg")) {
    header ("Content-type: image/jpeg");
    imagejpeg ($img);
}
elseif (function_exists("imagepng")) {
    header ("Content-type: image/png");
    imagepng ($img);
}
elseif (function_exists("imagewbmp")) {
    header ("Content-type: image/vnd.wap.wbmp");
    imagewbmp ($img);
}
imagedestroy($img);

?>