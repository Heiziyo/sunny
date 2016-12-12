<?php

/**
 * Created by PhpStorm.
 * User: ycm
 * Date: 2016/10/27
 * Time: 16:09
 */

date_default_timezone_set('Asia/Shanghai');

require_once(dirname(dirname(dirname(__DIR__))) . '/php_ci_core/lib/PHPExcel.php');
class Export
{


   public static function readexcel($filePath) {
        $PHPExcel = PHPExcel_IOFactory::load($filePath);

        $currentSheet = $PHPExcel->getSheet(0);  /**取得一共有多少列*/
        $allColumn = $currentSheet->getHighestColumn();     /**取得一共有多少行*/
        $allRow = $currentSheet->getHighestRow();

        $all = array();
        for( $currentRow = 1 ; $currentRow <= $allRow ; $currentRow++){
            $flag = 0;
            $col = array();
            for($currentColumn='A'; ord($currentColumn) <= ord($allColumn) ; $currentColumn++){
                $address = $currentColumn.$currentRow;
                $string = $currentSheet->getCell($address)->getValue();
//            $col[$flag] = (string)$string;
                $col[$flag] = $string;
                $flag++;
            }
            $all[] = $col;
        }
        return $all;
    }

   public static function exportExcel($renderTitles, $renderDatas, $excelTitle, $filename='01simple.xlsx',$save = FALSE){
        //写excel表
        $objPHPExcel = new PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");


        // Add some data
        $activeSheet = $objPHPExcel->setActiveSheetIndex(0);

        $ascode = 65;
        foreach ($renderTitles as $renderTitle){
            $renderKey = chr($ascode).'1';
            $activeSheet->setCellValue($renderKey, $renderTitle);
            $ascode++;
        }


        $i = 2;
        foreach ($renderDatas as $renderData){
            // Miscellaneous glyphs, UTF-8
            $activeSheet = $objPHPExcel->setActiveSheetIndex(0);

            $ascode = 65;
            foreach ($renderData as $key2=>$value2){
                $renderKey = chr($ascode).$i;
                $activeSheet->setCellValue($renderKey, $value2);
                $ascode++;
            }
            $i++;
        }


        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($excelTitle);

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
       if(!$save){
           $objWriter->save('php://output');
       }else{
           $objWriter->save(date('YmdHis').$filename);
       }
    }



}