<?php
/**
 * Created by PhpStorm.
 * User: Максим
 * Date: 10.02.2016
 * Time: 12:05
 */

namespace App\Http;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\File;

class Dirs {

    public function GetDirContent($dir,$offs,&$content){
        if ($d=opendir($dir))
        {
            while ($file=readdir($d))
            {
                if (($file=='.') or ($file=='..'))
                {
                    continue;
                }
                if (is_dir($dir."/".$file))
                {
                    $content[] = "<BR>$offs <B>$dir/$file</B>";
                    $this->GetDirContent($dir."/".$file, $offs."-",$content);
                }
                else
                {
                    $content[] = "<a href=/$dir/$file><BR> $offs $dir/$file</a> || <span path= /$dir/$file>$dir/$file</span> || <a href=/$dir/$file download>   Download</a> ||";
                }
            }
        }
        closedir($d);
        if (empty($content)){
            $content[] = "<h1 class='text-center'><i style='color:orange'>- folder is empty!!!</i></h1>";
        }
        return($content);
    }

    /*
     * выводит массив на экран
     */
    public function display($content){
        if (!empty($content)) {
            foreach ($content as $cont) {
                echo $cont;
            }
        }
        else{
            echo "<h1>folder is empty!!!</h1>";
        }
    }

    public function count_files($dir){
        $c=0; // количество файлов. Считаем с нуля
        $d=dir($dir); //
        while($str=$d->read()){
            if($str{0}!='.'){
                if(is_dir($dir.'/'.$str)) $c+=$this->count_files($dir.'/'.$str);
                else $c++;
            };
        }
        $d->close(); // закрываем директорию
        return $c;
    }

    public function count_dirs($dir){
        $c=0; // количество папок. Считаем с нуля
        $d=dir($dir); //

        while ($file=$d->read()) {
            if (($file=='.') or ($file=='..')) {
                continue;
            }
            if (is_dir($dir."/".$file)) {
                 $c++;
               //  $c+=$this->count_dirs($dir.'/'.$file); учитываем вложенные папки
            }

        }
        $d->close(); // закрываем директорию
        return $c;
    }

    /* загружает файл, возвращает параметры загрузки - папку и имя файла $params[$dirName, $fileName] */
    public function uplFile($dirName, $dirPath, $fileName, $uploadFile ){
        if (file_exists($uploadFile)){
            $fileName = str_ireplace('.', '_(2).', $fileName);//str_replace
            $uploadFile = $dirPath . "/" . $fileName;
        }
		if(copy($_FILES['uploadfile']['tmp_name'], $uploadFile)) {
            echo "file successfully copied $uploadFile". "<br />";
            echo 'count_files = '.$this->count_files($dirPath). "<br />";
            $params[] = $dirName;
            $params[] = $fileName;
		}
        return $params;
    }

    /* создаёт папку */
    public function makeDir($dirUpl, $dirName){
        chdir($_SERVER['DOCUMENT_ROOT'] . "/" . $dirUpl);
        if (mkdir($dirName, 0777)) {
            echo "new folder was created $dirName" . "<br />";
            return true;
        }
        else {
            echo "new folder was NOT created $dirName" . "<br />";
            return false;
        }
    }

    public function initUplParams($dirUpl, $countDirs, $dirNum){
        if ($countDirs == 0) {
            $dirName = '1';
        }
        else {
            $dirName = $dirNum;
        }
        $initParams['dirName'] = $dirName;
        $initParams['dirPath'] = $_SERVER['DOCUMENT_ROOT'] . "/" . $dirUpl . "/" . $dirName;
        $initParams['fileName'] = basename($_FILES['uploadfile']['name']);
        $initParams['uploadFile'] = $initParams['dirPath'] . "/" . $initParams['fileName'];

        return $initParams;
    }

    public function loadNoteToDir($dirUpl){
        if (is_uploaded_file($_FILES['uploadfile']['tmp_name'])) {
            $fileName =  basename($_FILES['uploadfile']['name']);
            $info = new SplFileInfo($fileName,'','');

            if ($info->getExtension()!=='txt'){
                $params[0] = null;
                $params[1] = null;
                $params['message'] = "  -  We can use only txt-extension files($fileName)";

               return $params;
            }
            else{
                if ($d=opendir($dirUpl)) {
                    $dirNum = 0;
                    $dirsObj = new Dirs;
                    $countDirs = $dirsObj->count_dirs($dirUpl);
                    echo '$countDirs = '.$countDirs."<br />";

                    if ($countDirs == 0){
                        $initParams = $dirsObj->initUplParams($dirUpl, $countDirs, $dirNum);
                        if($dirsObj->makeDir($dirUpl, $initParams['dirName'])){                                                                                                    // 1 makeDir(}
                            return $dirsObj->uplFile($initParams['dirName'], $initParams['dirPath'], $initParams['fileName'], $initParams['uploadFile']);                          // 1 uplFile()
                        }
                    }


                    while ($file=readdir($d)) {
                        if (($file=='.') or ($file=='..')) {
                            continue;
                        }
                        if (is_dir($dirUpl."/".$file)) {
                            echo $dirUpl . "/" . $file. "<br />";
                            $dirNum++;
                            echo ' $dirNum = '.$dirNum. "<br />";

                            if ($dirNum <= $countDirs) {
                                $initParams = $dirsObj->initUplParams($dirUpl, $countDirs, $dirNum);
                                if ($dirsObj->count_files($initParams['dirPath']) < 2) {                                                                                            //<10 <4
                                    echo 'count_files = '.$dirsObj->count_files($initParams['dirPath']). "<br />";
                                    return $dirsObj->uplFile($initParams['dirName'], $initParams['dirPath'], $initParams['fileName'], $initParams['uploadFile']);                   // 2 uplFile()
                                } else {
                                    if ($dirNum == $countDirs) {
                                        echo '$dirNum == $countDirs ';
                                        $dirNum++;
                                        $initParams = $dirsObj->initUplParams($dirUpl, $countDirs, $dirNum);
                                        echo $initParams['dirName']. "<br />";
                                        if($dirsObj->makeDir($dirUpl, $initParams['dirName'])){                                                                                     // 2 makeDir(}
                                         return $dirsObj->uplFile($initParams['dirName'], $initParams['dirPath'], $initParams['fileName'], $initParams['uploadFile']);              // 3 uplFile()
                                        }
                                    }
                                    else {
                                        continue;
                                    }
                                }
                            }//if ($dirNum <= $countDirs)
                        }//is_dir
                    }// while ($file=readdir($d)) {
                }// if ($d=opendir($dir)) {
            } //else
        }//if (is_uploaded_file
    }

}