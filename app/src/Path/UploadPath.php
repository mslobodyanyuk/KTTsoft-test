<?php
namespace App\src\Path;


class UploadPath implements PathInterface
{
    public function  getPath(){
        return config('parameters.uplPath');
    }
}