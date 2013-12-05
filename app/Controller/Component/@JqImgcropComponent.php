<?php
 class JqImgcropComponent extends Component {

    function uploadImage($uploadedInfo, $uploadTo, $prefix){}

    function getHeight($image) {
        $sizes = getimagesize($image);
        $height = $sizes[1];
        return $height;
    }
    function getWidth($image) {
        $sizes = getimagesize($image);
        $width = $sizes[0];
        return $width;
    }

    function resizeImage($image,$width,$height,$scale) {
        $newImageWidth = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);
        $newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
$ext = strtolower(substr(basename($image), strrpos(basename($image), ".") + 1));
        $source = "";
        if($ext == "png"){
            $source = imagecreatefrompng($image);
        }elseif($ext == "jpg" || $ext == "jpeg"){
            $source = imagecreatefromjpeg($image);
        }elseif($ext == "gif"){
            $source = imagecreatefromgif($image);
        }
        imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
        if($ext == "png" || $ext == "PNG"){
            imagepng($newImage,$image,0);
        }elseif($ext == "jpg" || $ext == "jpeg" || $ext == "JPG" || $ext == "JPEG"){
            imagejpeg($newImage,$image,90);
        }elseif($ext == "gif" || $ext == "GIF"){
            imagegif($newImage,$image);
        }
        chmod($image, 0777);
        return $image;
    }

    function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
        $newImageWidth = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);
        $newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
        $ext = strtolower(substr(basename($image), strrpos(basename($image), ".") + 1));
        $source = "";
        if($ext == "png"){
            $source = imagecreatefrompng($image);
        }elseif($ext == "jpg" || $ext == "jpeg"){
            $source = imagecreatefromjpeg($image);
        }elseif($ext == "gif"){
            $source = imagecreatefromgif($image);
        }
        imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);

        if($ext == "png" || $ext == "PNG"){
            imagepng($newImage,$thumb_image_name,0);
        }elseif($ext == "jpg" || $ext == "jpeg" || $ext == "JPG" || $ext == "JPEG"){
            imagejpeg($newImage,$thumb_image_name,90);
        }elseif($ext == "gif" || $ext == "GIF"){
            imagegif($newImage,$thumb_image_name);
        }

        chmod($thumb_image_name, 0777);
        return $thumb_image_name;
    }

    function cropImage($thumb_width, $x1, $y1, $x2, $y2, $w, $h, $thumbLocation, $imageLocation){
          
	    $scale = $thumb_width/$w;
        $cropped = $this->resizeThumbnailImage(WWW_ROOT.str_replace("/", DS,$thumbLocation),$imageLocation,$w,$h,$x1,$y1,$scale);
        //return $cropped;
		return true;
    }
}
?> 