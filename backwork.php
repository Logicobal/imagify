<?php
// Get the Image name, size and background
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

// Output int array
$output = array(  
	'msgImage'   	=>  $msgImage,
	'imgUrl'   	    =>  $imgUrl,
    'msgBgImg'   	=>  $msgBgImg,
    'bgUrl'   	    =>  $bgUrl,
);  

// output in JSON format and it would be accessed on AJAX call
echo json_encode($output);

?>