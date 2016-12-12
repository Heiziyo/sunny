<?php
class File {
    public static function getMineType($fileName) {
        $mimeMap = array(
            'jpg'   => 'image/jpeg',
            'gif'   => 'image/gif',
            'png'   => 'image/png',
            'flv'   => 'flv-application/octet-stream',
            'swf'   => 'application/x-shockwave-flash'
        );
        
        $fileExt = strtolower(FileUtils::getExtType($fileName));
        return isset($mimeMap[$fileExt]) ? $mimeMap[$fileExt] : 'unkown';
    }
    
    public static function getExtType($fileName) {
        return substr($fileName, strrpos($fileName, '.') + 1);
    }
    
    public static function makeDir($dirName) {
        $dirName = str_replace("\\","/", $dirName);
        $dirNames = explode('/', $dirName);
        $total = count($dirNames) ;
        $temp = '';
        for($i=0; $i<$total; $i++) {
            $temp .= $dirNames[$i].'/';
            if (!is_dir($temp)) {
                $oldmask = umask(0);
                if (!mkdir($temp, 0777)) exit("不能建立目录 $temp"); 
                umask($oldmask);
            }
        }
        return true;
    }
    
    /**
     * 
     * @param type $url
     * @param string $savePath
     * @return string
     */
    public static function download($url, $savePath, $fileName = '') {
        FileUtils::makeDir($savePath);
        
        if(substr($savePath, -1) != DS) {
            $savePath .= DS;
        }
        
        $newFileName    = '';
        if($fileName == '') {
            $newFileName = $savePath . basename($url);
        }
        else {
            $newFileName = $savePath . $fileName;
        }
        
        $srcFileHandle  = fopen($url, "rb");
        
        if ($srcFileHandle) {
            $newFileHandle   = fopen ($newFileName, "wb");
            
            if ($newFileHandle) {
                while(!feof($srcFileHandle)) {
                    if(fwrite($newFileHandle, fread($srcFileHandle, 1024), 1024) === false) {
                        return false;
                    }
                }
            }
        }
        
        if ($srcFileHandle) fclose($srcFileHandle);
        if ($newFileHandle) fclose($newFileHandle);
        
        return $newFileName;
    }
    
    public static function getUrlInfo($url) {
        $fileInfos  = array('exist' => false, 'size' => 0, 'fileType' => '');
        $urlAry     = parse_url($url);
        $fp         = @fsockopen($urlAry['host'], empty($urlAry['port']) ? 80 : $urlAry['port'], $error);
        if($fp) {
            fputs($fp, "GET " . (empty($urlAry['path']) ? '/' : $urlAry['path']) . " HTTP/1.1\r\n");
            fputs($fp, "Host: $urlAry[host]\r\n\r\n");
            while(!feof($fp)) {
                $tmp = fgets($fp);
                
                if(trim($tmp) == '') {
                    break;
                }
                else if(preg_match('/Content-Length:(.*)/si', $tmp, $arr)) {
                    $fileInfos['size'] = intval(trim($arr[1]));
                }
                else if(preg_match('|HTTP\/[0-9]\.[0-9] (\d+)|si', $tmp, $arr)) {
                    $fileInfos['exist'] = $arr[1] == '200' ? true : false;
                }
                else if(preg_match('/Content-Type:(.*)/si', $tmp, $arr)) {
                    $fileInfos['fileType'] = trim($arr[1]);
                }
            }
        }
        
        return $fileInfos;
    }

    public static function viewDir($dir,$tmpDir){
        $folders  = array();
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    //$file=iconv("gb2312","utf-8",$file);
                    $fileType = @filetype($dir .'/'. $file);
                    $path = str_replace("//","/", $tmpDir .DS .$file);
                    $host = 'http://' . str_replace("//","/",EMPLOYEE_HANDBOOK_HOST . DS . $tmpDir .DS .$file);
                    $dirPath = str_replace("//","/",$tmpDir . DS .$file . DS );
                    if($fileType) {
                        switch ($fileType) {
                            case 'dir':
                                if($file!='.' && $file!='..') $folders[] = array('name'=>$file,'type'=>'dir','size'=>'','modifytime'=>'','url' => $dirPath, 'path' => $dirPath);
                                break;
                            case 'file':
                                $folders[] = array('name'=>$file,'type'=>'file','size'=>self::getRealSize(filesize($dir .'/'. $file)),'modifytime'=>date ("Y-m-d H:i:s", filemtime($dir .'/'. $file)),'url' => $host, 'path' => $path);
                                break;
                            default:
                                $folders[] = array('name'=>$file,'type'=>'file','size'=>'','modifytime'=>'','url' => $host, 'path' => $path);
                                break;
                        }
                    }
                }
                closedir($dh);
            }
        }
        return $folders;
    }

    public static function deleteFile($fileName) {
        if(!is_file($fileName)) return 0;
        return @unlink($fileName);
    }

    public static function deleteDir($dir) {
        if(!is_dir($dir)) return 0;
        //先删除目录下的文件：
        $dh=opendir($dir);
        while ($file=readdir($dh)) {
            if($file!="." && $file!="..") {
                $fullpath=$dir."/".$file;
                if(!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    self::deleteDir($fullpath);
                }
            }
        }
        closedir($dh);
        //删除当前文件夹：
        if(rmdir($dir)) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function getRealSize($size){
        $kb = 1024;         // Kilobyte
        $mb = 1024 * $kb;   // Megabyte
        $gb = 1024 * $mb;   // Gigabyte
        $tb = 1024 * $gb;   // Terabyte

        if($size < $kb){
            return $size." B";
        }else if($size < $mb){
            return round($size/$kb,2)." KB";
        }else if($size < $gb){
            return round($size/$mb,2)." MB";
        }else if($size < $tb){
            return round($size/$gb,2)." GB";
        }else{
            return round($size/$tb,2)." TB";
        }
    }

}
?>
