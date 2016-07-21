<?php

namespace App\src\FileStructure;
use Symfony\Component\Finder\SplFileInfo;
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
    public function checkNotTxtExtension($fileName){
        $info = new SplFileInfo($fileName, '', '');
        if ($info->getExtension() !== 'txt') {
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
    public function initUplParams($uplPath, $countDirs, $dirNum){
        if ($countDirs == 0) {
            $dirName = '1';
        }
        else {
            $dirName = $dirNum;
        }

        $initParams['dirName'] = $dirName;
        $initParams['dirPath'] = $uplPath . "/" . $dirName;

        $uplNamePath = config('parameters.uplNamePath');
        $initParams['fileName'] = basename($uplNamePath);
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
    public function uplFile($dirName, $dirPath, $fileName, $uploadFile ){
        if (file_exists($uploadFile)){
            $fileName = str_ireplace('.', '_(2).', $fileName);
            $uploadFile = $dirPath . "/" . $fileName;
        }
        $uplTempNamePath = config('parameters.uplTempNamePath');
		if(copy($uplTempNamePath, $uploadFile)) {
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
    public function createInitDirUplFile($dirPath, $countDirs, $dirNum){
        $initParams = $this->initUplParams($dirPath, $countDirs, $dirNum);
        $this->makeDir($initParams['dirPath']);
        return $this->uplFile($initParams['dirName'], $initParams['dirPath'], $initParams['fileName'], $initParams['uploadFile']);
    }

    /**
     *  loadNoteToDir creating file structure, create directories and load files in upload folder
     *  @return array $params['directory_name','name'];
     */
    public function loadNoteToDir($dirUpl, $dirPath, $countContentFiles){
        $uplTempNamePath = config('parameters.uplTempNamePath');
        $uplNamePath = config('parameters.uplNamePath');


echo "<pre> uplTempNamePath =", var_dump($uplTempNamePath) ,"</pre>";
echo "<pre> uplNamePath =", var_dump($uplNamePath) ,"</pre>";



        if (is_uploaded_file($uplTempNamePath)) {
        //if (is_uploaded_file($_FILES['uploadfile']['tmp_name'])) {

            $fileName = basename($uplNamePath);
            //$fileName = basename($_FILES['uploadfile']['name']);

echo "<pre>!!!uplTempNamePath =", var_dump($uplTempNamePath) ,"</pre>";
echo "<pre> fileName =", var_dump($fileName) ,"</pre>";

            if($this->checkNotTxtExtension($fileName)) {
                return $this->checkNotTxtExtension($fileName);
            }
            if ($d=opendir($dirUpl)){
                $dirNum = 0;
                $countDirs = $this->countDirs($dirUpl);
                if ($countDirs == 0){
                    return $this->createInitDirUplFile($dirPath, $countDirs, $dirNum);                                                      // 1 makeDir() // 1 uplFile()
                }
                while ($file=readdir($d)) {
                    if (($file=='.') or ($file=='..')) {
                        continue;
                    }
                    if (is_dir($dirUpl."/".$file)) {
                        $dirNum++;
                        if ($dirNum <= $countDirs) {
                            $initParams = $this->initUplParams($dirPath, $countDirs, $dirNum);
                            if ($this->countFiles($initParams['dirPath']) < $countContentFiles) {   //$countContentFiles < 2                // <10
                                return $this->uplFile($initParams['dirName'], $initParams['dirPath'], $initParams['fileName'], $initParams['uploadFile']); // 2 uplFile()
                            } else {
                                    if ($dirNum == $countDirs) {
                                        $dirNum++;
                                        return $this->createInitDirUplFile($dirPath, $countDirs, $dirNum);                                  // 2 makeDir() // 3 uplFile()
                                    } else {
                                                continue;
                                    }
                            }
                        }//if ($dirNum <= $countDirs)
                    }//is_dir
                }// while ($file=readdir($d)) {
            }// if ($d=opendir($dir)) {
        }//if (is_uploaded_file
    }

}