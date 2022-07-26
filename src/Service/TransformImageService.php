<?php

namespace App\Service;

class TransformImageService {

    function createPreview($image_path) {

        header('Content-Type: image/png');
        $thumb = imagecreatetruecolor(350, 350);
        $source = imagecreatefrompng($image_path);
    
        list($width, $height) = getimagesize($image_path);
    
        imagecopyresized($thumb, $source, 0, 0, 0, 0, 350, 350, $width, $height);
    
        return imagepng($thumb,"img.png");
    }

}