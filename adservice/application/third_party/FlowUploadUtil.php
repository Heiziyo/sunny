<?php
class FlowUploadUtil{

    public $file = null;
    public $savePath = null;
    public $error = array();
    public $size = 5000000;//5M
    public $sysType = array('png','jpg','jpeg','gif','txt','doc','docx','xls','xlsx','pdf','word', 'rar');
    public $newName = '';

    public function __construct($savePath){
        $this->file = $_FILES['files'];
        $this->savePath = $savePath;
    }

    public function validate(){
        $file = $this->file;
        if(empty($file)){
            $this->setError('file','没有上传任何文件');
            return false;
        }
        $fileInfo = pathinfo($file['name']);

        $sysType = $this->sysType;
        $sysSize = $this->size;
        //上传错误处理
        if ($file['error'] > 0) {
            $errInfo = $file['error'];
            if ($errInfo == 1) {
                $this->setError('phpSize','文件上传超过php设置');
            } else if ($errInfo == 2) {
                $this->setError('sysSize','文件上传超过限制');
            } else if ($errInfo == 3) {
                $this->setError('upfail','文件只有部分上传');
            } else if ($errInfo == 4) {
                $this->setError('file','文件只有部分上传');
            } else if ($errInfo == 6) {
                $this->setError('upfail','找不到临时文件');
            } else if ($errInfo == 7) {
                $this->setError('sys','文件写入失败');
            } else {
                $this->setError('sys','无法预知的错误');
            }
            return false;
        }else{
            //大小过滤
            if ($file['size'] > $sysSize) {
                $this->setError('sysSize','文件上传超过5M');
                return false;
            }
            //类型过滤
            if (!in_array(strtolower($fileInfo['extension']), $sysType)) {
                $this->setError('type','不支持上传文件类型');
                return false;
            }
        }
        //$filename = date('YmdHis') ."." .$fileInfo['extension'];
        if(! $file['tmp_name']){
            $this->setError('upfail','没有指定的上传文件');
            return false;
        }

        $this->size = $file['size'];

        return  true;
    }

    public function save($fileName = null){
        if(!$this->validate()){
            return false;
        }

        $path = $this->savePath;
        if(!file_exists($path)){
            File::makeDir($path);
        }

        $file = $this->file;
        $fileInfo = pathinfo($file['name']);

        $fileInfo = pathinfo($file['name']);

        if( ! $fileName ){
            $fileName = $file['name'];
        }

        $fileName = $this->nameToUTF8($fileName);

        $this->newName = $fileName;

        $pathName = $fileName;
        $filename = $path .DS . $fileName;

        if(file_exists($filename)){
            $this->setError('sysSize','文件已经存在');
            return false;
        }

        if (!move_uploaded_file($file['tmp_name'], $filename)) {
            $this->setError('save','保存文件失败');
            return false;
        }


        return true;
    }

    public function nameToUTF8($name){
        $encode  = mb_detect_encoding($name,array("ASCII",'UTF-8','GB2312','GBK','BIG5'));
        if($encode != 'UTF-8'){
            $name = @ iconv('UTF-8',$encode,$name);
        }

        return $name;
    }

    public function setError($label,$error){
        $this->error[$label] = $error;
    }

    public function getError($conLabel = false){
        $errors = $this->error ;
        if(count($errors) > 0) {
            $info = array();
            if (!$conLabel) {
                $info = implode("; ",array_values($errors));
            } else {
                foreach($errors as $key => $err){
                    $info[] = "{$key} : {$err};";
                }
                $info = implode(" ",$info);
            }
            return $info;
        }else{
            return null;
        }
    }
}