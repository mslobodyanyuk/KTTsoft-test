<?php

namespace App\src\FileStructure;

use App\Http\Requests;
use Request;
use Symfony\Component\HttpFoundation\File;



class DirUploadFileStructure {

    /**
     *  getDirContent put content file-structure containing in upload folder($dir) into array $content
     *  @return array $content
     */
    public function getDirContent($dir, $forFileParentId, &$content){
        static $nodeNum = 0;
        if ($d=opendir($dir)) {

            while ($file=readdir($d)) {
                if (($file=='.') or ($file=='..')) {
                    continue;
                }

                if (is_dir($dir."/".$file)) {
                    $nodeData["nodeId"] = ++$nodeNum;
                    $nodeData["parentId"] = 0;
                    $nodeData["type"] = 'DIR';
                    $nodeData["parentFolderName"] = $dir;
                    $nodeData["nodeName"] = $file;
                    $content[] = $nodeData;

                    $forFileParentId = $nodeData["nodeId"];
                    $this->getDirContent($dir."/".$file, $forFileParentId ,$content);

                } else {
                    $nodeData["nodeId"] = ++$nodeNum;
                    $nodeData["parentId"] = $forFileParentId;
                    $nodeData["type"] = 'FILE';
                    $nodeData["parentFolderName"] = $dir;
                    $nodeData["nodeName"] = $file;
                    $content[] = $nodeData;
                }
            }
        }
        closedir($d);
        return($content);
    }

    /**
     *  checkNotTxtExtension(); return $params if file extension not txt
     *  @return array $params
     */
    public function checkNotTxtExtension($fileName, $fileExtension){
        if ( 'txt' !== $fileExtension ) {
            $params[0] = null;
            $params[1] = null;
            $params['message'] = "  -  We can use only txt-extension files($fileName)";
            return $params;
        }else {
            return false;
        }
    }

    /**
    *  count_dirs counting folders in upload folder
    *  @return int
    */
    public function countDirs($dir){
        $c=0; // count files, beginning from zero
        $d=dir($dir); //
        while ($file=$d->read()) {
            if (($file=='.') or ($file=='..')) {
                continue;
            }
            if (is_dir($dir."/".$file)) {
                 $c++;
               //  $c+=$this->countDirs($dir.'/'.$file); including count subdirectories
            }
        }
        $d->close(); // close directory
        return $c;
    }

    /**
     *  initUplParams init params to upload files
     *  @return array $initParams['dirName','dirPath','fileName','uploadFile'];
     */
    public function initUplParams($uplPath, $dirNum, $pathName, $originalFileName){
        $initParams['dirName'] = $dirNum;
        $initParams['dirPath'] = $uplPath . "/" . $dirNum;
        $initParams['fileName'] = $originalFileName;
        $initParams['pathName'] = $pathName;
        $initParams['uploadFile'] = $initParams['dirPath'] . "/" . $initParams['fileName'];
        return $initParams;
    }

    /**
     *  makeDir create folder
     *  @return Bool
     */
    public function makeDir($dirPath){
        if (mkdir($dirPath, 0777)) {
            return true;
        }else {
            return false;
        }
    }

    /**
     *  uplFile upload file into appropriate folder
     *  @return array $params[$dirName, $fileName];
     */
    public function uplFile($dirName, $dirPath, $fileName, $pathName, $uploadFile ){
        if (file_exists($uploadFile)){
            $fileName = str_ireplace('.', '_(2).', $fileName);
            $uploadFile = $dirPath . "/" . $fileName;
        }

        if(copy($pathName, $uploadFile)) {
            $params[] = $dirName;
            $params[] = $fileName;
		}
        return $params;
    }

    /**
     *  count_files counting files in folder
     *  @return int
     */
    public function countFiles($dir){
        $c=0; // count files, beginning from zero
        $d=dir($dir); //
        while($str=$d->read()){
            if($str{0}!='.'){
                if(is_dir($dir.'/'.$str)) $c+=$this->countFiles($dir.'/'.$str);
                else $c++;
            };
        }
        $d->close(); // close directory
        return $c;
    }

    /**
     *  createInitDirUplFile($dirPath, $countDirs, $dirNum);
     *
     *  initUplParams init params to upload files
     *  @return array $initParams['dirName','dirPath','fileName','uploadFile'];
     *
     *  makeDir create folder
     *  @return Bool
     *
     *  uplFile upload file into appropriate folder
     *  @return array $params[$dirName, $fileName];
     */

    public function createInitDirUplFile($dirPath, $dirNum, $pathName, $originalFileName){
        $dirNum++;
        $initParams = $this->initUplParams($dirPath, $dirNum, $pathName, $originalFileName);
        $this->makeDir($initParams['dirPath']);
        return $this->uplFile($initParams['dirName'], $initParams['dirPath'], $initParams['fileName'], $initParams['pathName'] , $initParams['uploadFile']);
    }

    /**
     * @param $dirPath
     * @param $countContentFiles
     * @return bool
     */
    public function isNotFilledDir($dirPath, $countContentFiles){
        return ($this->countFiles($dirPath) < $countContentFiles);
    }

    /**
     * @param $dirNum
     * @param $countDirs
     * @return bool
     * where $dirUpl is empty ($countDirs == 0) or existing dirs filled of files
     */
    public function isNeedCreateNewUploadFolder($dirNum, $countDirs){
        return ($dirNum == $countDirs);
    }

    /**
     *  loadNoteToDir creating file structure, create directories and load files in upload folder
     *  @return array $params['directory_name','name'];
     */
    public function loadNoteToDir($dirUpl, $dirPath, $countContentFiles, $pathName, $originalFileName, $fileExtension){
        if (is_uploaded_file($pathName)) {// $_FILES["uploadfile"]['tmp_name'] is_uploaded_file - * Moves an uploaded file to a new location
            if($this->checkNotTxtExtension($originalFileName, $fileExtension)) {
                return $this->checkNotTxtExtension($originalFileName, $fileExtension);
            }

            if ($d=opendir($dirUpl)){
                $dirNum = 0;
                $countDirs = $this->countDirs($dirUpl);
                if ($this->isNeedCreateNewUploadFolder($dirNum, $countDirs)){
                    return $this->createInitDirUplFile($dirPath, $dirNum, $pathName, $originalFileName);                // 1 makeDir() // 1 uplFile()
                }

                while ($file=readdir($d)) {
                    if (($file=='.') or ($file=='..')) {
                        continue;
                    }
                    if (is_dir($dirUpl."/".$file)) {
                        $dirNum++;
                        $initParams = $this->initUplParams($dirPath, $dirNum, $pathName, $originalFileName);
                        if ($this->isNotFilledDir($initParams['dirPath'], $countContentFiles)) {                        // $countContentFiles < 2 // <10
                            return $this->uplFile($initParams['dirName'], $initParams['dirPath'], $initParams['fileName'], $initParams['pathName'], $initParams['uploadFile']); // 2 uplFile()
                        }
                        if ($this->isNeedCreateNewUploadFolder($dirNum, $countDirs)) {// FilledDir!!!
                            return $this->createInitDirUplFile($dirPath, $dirNum, $pathName, $originalFileName);        // 2 makeDir() // 3 uplFile()
                        }
                    }//is_dir
                }// while ($file=readdir($d)) {

            }// if ($d=opendir($dir)) {

        }//if (is_uploaded_file
    }

}