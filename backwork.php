<?php
// Get the Image name, size and background

$scale = $_POST['scale'];
$count = $_POST['count'];


$post_image = $_FILES['image']['name'];
$post_image_size = $_FILES['image']['size'];
$post_image_tmp = $_FILES['image']['tmp_name'];

$bg_image = $_FILES['bg_image']['name'];
$bg_image_size = $_FILES['bg_image']['size'];
$bg_image_tmp = $_FILES['bg_image']['tmp_name'];

// Errors or Success msgs are stored in these varibales
$msgImage= '';
$msgBgImg= '';

// Accessing parameters are into these variables
$imgUrl = '';
$bgUrl = '';

// Code for Image
if(!empty($post_image))
{
	$explode = explode('.',$post_image);
	$end = end($explode);
	$allowed_ext = array('jpg', 'jpeg', 'JPG', 'JPEG', 'png', 'PNG', 'gif', 'svg', 'SVG');

	if(in_array($end, $allowed_ext))
	{

		if($post_image_size <= 1024 * 1024)
		{
			$refined_image = md5(rand()).".".$end;
			$path = "img/".$refined_image;
			move_uploaded_file($post_image_tmp, $path); 

            $imgUrl = $path;
		}
		else
		{
			$msgImage = 'Size is greater than 1MB';
		}

	}
	else
	{
		$msgImage = 'Invalid image format';
	}

}
else
{
	$msgImage ='Please choose image';
}

// Code for background Image
if(!empty($bg_image))
{
	$explode = explode('.',$bg_image);
	$end = end($explode);
	$allowed_ext = array('jpg', 'jpeg', 'JPG', 'JPEG', 'png', 'PNG', 'gif', 'svg', 'SVG');

	if(in_array($end, $allowed_ext))
	{

		if($bg_image_size <= 1024 * 1024)
		{
			$refined_image = md5(rand()).".".$end;
			$bgpath = "img/".$refined_image;
			move_uploaded_file($bg_image_tmp, $bgpath); 

            $bgUrl = $bgpath;
		}
		else
		{
			$msgBgImg = 'Size is greater than 1MB';
		}

	}
	else
	{
		$msgBgImg = 'Invalid image format';
	}

}
else
{
	$msgBgImg ='Please choose image';
}

$compoundImage =  imagePool($scale,$imgUrl, $bgUrl, $count);

// Output int array
$output = array(
	'msgImage'   	=>  $msgImage,
	'imgUrl'   	    =>  $imgUrl,
    'msgBgImg'   	=>  $msgBgImg,
    'bgUrl'   	    =>  $bgUrl,
	'cp'			=> $compoundImage
);  

// output in JSON format and it would be accessed on AJAX call
echo json_encode($output);


function imagePool($scale, $srcImg, $destImg, $count){

	$extBgImg = pathinfo($destImg, PATHINFO_EXTENSION);
	
	$extImg = pathinfo($srcImg, PATHINFO_EXTENSION);
	
	$wrapperWidth = 312*$scale;
	
	$wrapperHeight = 123*$scale;
	
	
	$dstY = 40;
	
	// Create Transparent Image
	
	$imgWrapper = imagecreatetruecolor($wrapperWidth, $wrapperHeight);
	
	imagesavealpha($imgWrapper, true);
	
	$color = imagecolorallocatealpha($imgWrapper, 0, 0, 0, 127);
	
	imagefill($imgWrapper, 0, 0, $color);
	
	// imagecopymerge($imgWrapper, $image, 0, 0, 0, 0, $width, $height, 100);
	
	
	// Background Image
	
	if(!empty($destImg)){
	
	switch ($extBgImg) {
		case "jpg":
			$bgImage = imagecreatefromjpeg($destImg);
		  break;
		case "jpeg":
			$bgImage = imagecreatefromjpeg($destImg);
		  break;
		case "png":
			$bgImage = imagecreatefrompng($destImg);
		  break;
	
		case "svg":
			// $image = imagecreatefrompng ( $filename );
			$bgImage = new Imagick();
			$bgImage->readImageBlob(file_get_contents($destImg));
			$bgImage->setImageFormat("png24");
			
			$random = md5(rand()).'.png';
	
			$bgImage->writeImage(__DIR__.'/img/'.$random);
	
			$bgImage = imagecreatefrompng ( __DIR__.'/img/'.$random );
			
		  break;
		default:
			$bgImage = imagecreatefromjpeg($destImg);
	  }
	
	  
	
	// BG Cropping
	if($extBgImg == 'svg'){
		list($bgWidth, $bgHeight) = getimagesize(__DIR__.'/img/'.$random);
	}else{
		list($bgWidth, $bgHeight) = getimagesize($destImg);
	}
	
	$msgDimesion = ($bgWidth/$bgHeight) > 1.3 ? '' : 'Background aspect ratio would be 3/2'; 
	
	
	// if()
	
	$bgr = $bgWidth / $bgHeight;
	
	if ($wrapperWidth/$wrapperHeight > $bgr) {
		$newwidth = $wrapperHeight*$bgr;
		$newheight = $wrapperHeight;
	} else {
		$newheight = $wrapperWidth/$bgr;
		$newwidth = $wrapperWidth;
	}
	
	// $w=312*$scale; $h=123*$scale;
	
	
	
	// End Background Image
	
	// imagecopy($imgWrapper, $bgImage, 0, 0, 0, 0, $wrapperWidth, $wrapperHeight);
	
	$dst = imagecreatetruecolor($newwidth, $newheight);
	
	imagecopyresampled($dst, $bgImage, 0, 0, 0, 0, $newwidth, $newheight, $bgWidth, $bgHeight);
	
	}
	
	// Image CROPING
	
	$filename = $srcImg;
	$width = $height = 40 * $scale;
	
	switch ($extImg) {
		case "jpg":
			$image = imagecreatefromjpeg ( $filename );
		  break;
		case "jpeg":
			$image = imagecreatefromjpeg ( $filename );
		  break;
		case "png":
			$image = imagecreatefrompng ( $filename );
		  break;
		case "svg":
	
			// $image = imagecreatefrompng ( $filename );
			$image = new Imagick();
			$image->readImageBlob(file_get_contents($filename));
			$image->setImageFormat("png24");
	
			$random = md5(rand()).'.png';
	
			$image->writeImage(__DIR__.'/img/'.$random);
	
			$image = imagecreatefrompng ( __DIR__.'/img/'.$random );
		  break;
		default:
			$image = imagecreatefromjpeg ( $filename );
	  }
	  
	
	$new_image = imagecreatetruecolor ( $width, $height ); // new wigth and height
	
	imagealphablending($new_image , false);
	
	imagesavealpha($new_image , true);
	
	imagecopyresampled ( $new_image, $image, 0, 0, 0, 0, $width, $height, imagesx ( $image ), imagesy ( $image ) );
	
	$image = $new_image;
	
	imagecolortransparent($image, imagecolorallocate($image, 255, 0, 255));
	
	// create a circular mask and use it to crop the source image.
	$mask = imagecreatetruecolor($width, $height);
	
	$black = imagecolorallocate($mask, 0, 0, 0);
	
	$magenta = imagecolorallocate($mask, 255, 0, 255);
	
	imagefill($mask, 0, 0, $magenta);
	
	$r = min($width, $height);
	
	imagefilledellipse($mask, ($width / 2), ($height / 2), $r, $r, $black);
	
	imagecolortransparent($mask, $black);
	
	$orange = imagecolorallocate($mask, 255, 200, 0);
	
	imagesetthickness($mask, 5);
	
	imageellipse($mask, $width/2, $height/2, $width, $height, $orange);
	
	imagecopymerge($image, $mask, 0, 0, 0, 0, $width, $height, 100);
	
	
	
	$img = imagecreatetruecolor($width, $height);
	
	imagesavealpha($img, true);
	
	$color = imagecolorallocatealpha($img, 0, 0, 0, 127);
	
	$orange = imagecolorallocate($img, 255, 200, 0);
	
	imagefill($img, 0, 0, $color);
	
	imagecopy($img, $image, 0, 0, 0, 0, $width, $height);
	
	// imageellipse($img, $width/2, $height/2, $width, $height, $orange);
	
	
	// Image into Background Image
	
	if(empty($destImg)){
	
		$j = 1;
	for($i=1; $i<=$count; $i++){
	
	
			// $cx = $i == 0 ? $dstX : $dstX * $i;
			// Starts from 0
			if($scale == 1){
			$cy = $i % 2 != 0 ? $dstY * 0.3: $dstY * 1.8;
			}
	
			if($scale == 2){
			
				$cy = $i % 2 != 0 ? $dstY - 15 : $dstY * 3.5;
	
		   }
	
			if($scale == 3){
			
				$cy = $i % 2 != 0 ? $dstY: $dstY * 5;
	
		   }
			
			if( ($i % 2) != 0){
				// if( $i == 1){
				//     $cx = $newwidth / ($i * $count * 0.5);
				// }else{
	
					$cx = 2*(($wrapperWidth / ($i * $count )) * ( $i * ($i-$j)));
					
					$j++;
				   
				// }   
			}else{
			   // $cx = $i % 2 != 0 ? ($w  / ($i + 1)) : ($w / $i);    
			}
	
			// $cy = $i % 2 != 0 ? $h*4 : $h;
			// $cx = $i % 2 != 0 ? ($w  / ($i + 1)) : ($w / $i);
	
	   
			imagecopy($imgWrapper, $img, $cx, $cy, 0, 0, $width, $height);
	
		// imagecopymerge($dest, $src, $dstX, $dstY, $srcX, 0, $src_width, $src_height, $pct);
	}
	
	}else{
	
		$j = 1;
	for($i=1; $i<=$count; $i++){
	
	
			// $cx = $i == 0 ? $dstX : $dstX * $i;
			// Starts from 0
			if($scale == 1){
			$cy = $i % 2 != 0 ? $dstY * 0.3: $dstY * 1.8;
			}
	
			if($scale == 2){
			
				$cy = $i % 2 != 0 ? $dstY - 15 : $dstY * 3.5;
	
		   }
	
			if($scale == 3){
			
				$cy = $i % 2 != 0 ? $dstY: $dstY * 5;
	
		   }
			
			if( ($i % 2) != 0){
				// if( $i == 1){
				//     $cx = $newwidth / ($i * $count * 0.5);
				// }else{
	
					$cx = 2*(($newwidth / ($i * $count )) * ( $i * ($i-$j)));
					
					$j++;
				   
				// }   
			}else{
			   // $cx = $i % 2 != 0 ? ($w  / ($i + 1)) : ($w / $i);    
			}
	
			// $cy = $i % 2 != 0 ? $h*4 : $h;
			// $cx = $i % 2 != 0 ? ($w  / ($i + 1)) : ($w / $i);
	
	   
			imagecopy($dst, $img, $cx, $cy, 0, 0, $width, $height);
	
		// imagecopymerge($dest, $src, $dstX, $dstY, $srcX, 0, $src_width, $src_height, $pct);
	}
	
	}
	
	$random = md5(rand()).'.png';
	
	header('Content-type: image/png');
	if(!empty($destImg)){
		if(empty($msgDimesion)){
			imagepng ($dst, 'img/'.$random);
			return $random;
		}else{
			return $msgDimesion;
		}
	}else{
		imagepng ( $imgWrapper,  'img/'.$random);
		return $random;
	}
	
	}

?>