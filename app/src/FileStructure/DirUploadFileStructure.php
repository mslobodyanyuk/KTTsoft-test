<?php

namespace App\src\FileStructure;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\File;

class DirUploadFileStructure {

    /**
     *  getDirContent put content file-structure containing in upload folder($dir) into array $content
     *  @return array $content
     */
/*    public function getDirContent($dir,$offs,&$content){
        if ($d=opendir($dir)) {
            while ($file=readdir($d)) {
                if (($file=='.') or ($file=='..')) {
                    continue;
                }
                if (is_dir($dir."/".$file)) {
                    $content[] = "brake  $offs  boldOpen $dir/$file  boldClose";
                    $this->getDirContent($dir."/".$file, $offs."-",$content);
                } else {
                    $content[] = "ahrefOpen /$dir/$file  closeBracket  brake  $offs $dir/$file  ahrefClose   || spanOpen /$dir/$file>$dir/$file  spanClose || ahrefOpen /$dir/$file aCloseDownload Download ahrefClose ||";
                }
            }
        }
        closedir($d);
        if (empty($content)){
            $content['message'] = '- folder is empty!!!';
        }
        return($content);
    }
*/
    public function getDirContent($dir,&$content){
        if ($d=opendir($dir)) {

            while ($file=readdir($d)) {
                static  $cnt = 0;

                if (($file=='.') or ($file=='..')) {
                    continue;
                }

                if (is_dir($dir."/".$file)) {
                    $cnt = $cnt++;
                    $nodeData["nodeId"] = $cnt;
                    $nodeData["parentId"] = 0;
                    $nodeData["type"] = 'DIR';
                    $nodeData["parentFolderName"] = $dir;
                    $nodeData["nodeName"] = $file;
                    $content[] = $nodeData;
                    $this->getDirContent($dir."/".$file,$content);
                } else {
                    $cnt = $cnt++;
                    $nodeData["nodeId"] = $cnt;
                    $nodeData["parentId"] = $dir;
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
        $initParams['fileName'] = basename($_FILES['uploadfile']['name']);
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
		if(copy($_FILES['uploadfile']['tmp_name'], $uploadFile)) {
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
        if (is_uploaded_file($_FILES['uploadfile']['tmp_name'])) {
            $fileName = basename($_FILES['uploadfile']['name']);
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