<?php

return [

            /* my config variable path for upload folder where we create/save/update files structure */
                'uplPath' => public_path() . '/upl',

            /* upload folder name */
                'uplName' => 'upl',

            /* count files limit containing in one folder */
                'countContentFiles' => '2',

            /* name of editor */
                'nameEditor' => 'editor1',

            /* text - editor content, editorContent */
                'editorContent' => '$_POST["nameEditor"]',

            /* variables for uploading file, uplTempNamePath, uplNamePath */

         /*1
          *
                 'uplTempNamePath' => '$_FILES["uploadfile"]["tmp_name"]',
                'uplNamePath' => '$_FILES["uploadfile"]["name"]',
*/



                /*
                'paramUploadFile' => 'uploadfile',
                'paramTmpName' => 'tmp_name',
                'paramName' => 'name',
                'uplTempNamePath' => '$_FILES["paramUploadFile"]["paramTmpName"]',
                'uplNamePath' => '$_FILES["paramUploadFile"]["paramName"]',
                */


            //if (is_uploaded_file($_FILES['uploadfile']['tmp_name'])) {
    //'uplTempNamePath' => '$_FILES[' . "paramUploadFile" . '][' . "paramTmpName" . ']',
/*
    'uplTempNamePath' => $_FILES["uploadfile"]["tmp_name"],
    'uplNamePath' => $_FILES["uploadfile"]["name"],
*/
    //'uplNamePath' => '$_FILES["paramUploadFile"]["paramName"]',
    //            'uplTempNamePath' => '$_FILES[' . "uploadfile" . '][' . "tmp_name" . ']',






/*
    'uplTempNamePath' => '$_FILES["uploadfile"]["tmp_name"]',
    'uplNamePath' => '$_FILES["uploadfile"]["name"]',
*/







//2
 /* 'uploadfile' => 'uploadfile',
    'tmp_name' => 'tmp_name',
    'name' => 'name',


    'uplTempNamePath' => '$_FILES["uploadfile"]["tmp_name"]',
    'isUploadedFile' => 'is_uploaded_file($_FILES["uploadfile"]["tmp_name"])',
*/


//3
  /*      'paramUploadFile' => 'uploadfile',
        'paramTmpName' => 'tmp_name',
        'paramName' => 'name',

         //'uplTempNamePath' => '$_FILES[' . "paramUploadFile" . '][' . "paramTmpName" . ']',

        'uplTempNamePath' => $_FILES["paramUploadFile"]["paramTmpName"],
        'uplNamePath' => $_FILES["paramUploadFile"]["paramName"],
*/


        'uplTempNamePath' => $_FILES["uploadfile"]["tmp_name"],
        'uplNamePath' => $_FILES["uploadfile"]["name"],

        ];