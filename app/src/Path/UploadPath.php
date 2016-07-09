<?php
namespace App\src\Path;
//use App\Http\Controllers;
//use App\src\Path\PathInterface;

class UploadPath implements PathInterface
{
    public function  getPath(){
        return config('parameters.uplPath');
    }
}