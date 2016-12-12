<?php
/**
 * flv 文件获取是秒针时间
 * 
 * @author  wanxiaokuo
 * @email   wanxiaokuo@adeaz.com
 * @time    2014-10-20
 * 
 * 调用方式： _get_time（$name） $name 文件路劲  返回单位 秒（s）
 */

class FlvTime {
    
     public function __construct()
    {
       ;
    }
    
    static private $instance = NULL;
    static function getInstance() 
    {
        if(self::$instance == NULL) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function _bigEndianInt($byteWord, $signed = false)
    {
        $intValue = 0;
        $byteWordlen = strlen($byteWord);
        for ($i = 0; $i < $byteWordlen; $i++) {
            $intValue += ord($byteWord{$i}) * pow(256, ($byteWordlen - 1 - $i));
        }
        if ($signed) {
            $signMaskBit = 0x80 << (8 * ($byteWordlen - 1));
            if ($intValue & $signMaskBit) {
                $intValue = 0 - ($intValue & ($signMaskBit - 1));
            }
        }
        return $intValue;
    }

    public function getTime($name)
    {
        if(!file_exists($name)) {
        	return;
        }
        $flvDataLength = filesize($name);
        $fp = @fopen($name, 'rb');
        $flvHeader = fread($fp, 5);
        fseek($fp, 5, SEEK_SET);
        $frameSizeDataLength = $this->_bigEndianInt(fread($fp, 4));
        $flvHeaderFrameLength = 9;
        if ($frameSizeDataLength > $flvHeaderFrameLength) {
            fseek($fp, $frameSizeDataLength - $flvHeaderFrameLength, SEEK_CUR);
        }
        $duration = 0;
        while ((ftell($fp) + 1) < $flvDataLength) {
            $thisTagHeader = fread($fp, 16);
            $dataLength = $this->_bigEndianInt(substr($thisTagHeader, 5, 3));
            $timestamp = $this->_bigEndianInt(substr($thisTagHeader, 8, 3));
            $nextOffset = ftell($fp) - 1 + $dataLength;
            if ($timestamp > $duration) {
                $duration = $timestamp;
            }
            fseek($fp, $nextOffset, SEEK_SET);
        }
        fclose($fp);
        return intval($duration/1000);
    }
    
    public function secondsToString($second)
    {
        $hours = intval($second / 3600);
        $minute = intval(($second % 3600) / 60);
        $second = intval(($second % 60 ));
        return $hours . ':' . $minute . ':' . $second ;
    }
}


?>