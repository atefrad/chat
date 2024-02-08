<?php

namespace App\Http\Controllers;

class ImageController
{
    public function store(string $imagePath, string $imageName, string $folder = 'images')
    {
        $imageName = time() . '-' . $imageName;

        $basePath = dirname(__FILE__,4);

        $uploadPath = '/public/' . $folder . '/' . $imageName;

        if(move_uploaded_file($imagePath, $basePath . $uploadPath))
        {
            return $uploadPath;
        }else{
            return false;
        }
    }
}