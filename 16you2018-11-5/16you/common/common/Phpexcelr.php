<?php
namespace common\common;
use yii;

class Phpexcelr{
    /**
     *  @DESC 数据导
     *  @notice 解决了上面导出列数过多的问题
     *  @example 
     *  $data = [1, '小明', '25'];
     *  $header = ['id', '姓名', '年龄'];
     *  Myhelpers::exportData($data, $header);
     *  @return void, Browser direct output
     */
    public static function exportData ($data, $header, $title = 'simple', $filename = 'data'){
        //require relation class files
        require(Yii::getAlias('@common').'/PHPExcel/Classes/PHPExcel.php');
        require(Yii::getAlias('@common').'/PHPExcel/Classes/PHPExcel/Writer/Excel5.php');
        
        if (!is_array ($data) || !is_array ($header)) return false;

        $objPHPExcel = new \PHPExcel();

        // Set properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw");
        $objPHPExcel->getProperties()->setLastModifiedBy("Maarten Balliauw");
        $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
        $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
        $objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");

        // Add some data
        $objPHPExcel->setActiveSheetIndex(0);
        //合并单元格
        // $objPHPExcel->getActiveSheet()->mergeCells('A1:I2');
        //左右垂直居中
        $alignment = array('horizontal'=>\PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'=>\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $filename)->getStyle('A1')->applyFromArray(array(
        //             'font' => array('bold'=>true,'size'=>20),//字体
        //             'alignment' => $alignment,//居中
        //     ));
        //添加头部
        $hk = 0;
        foreach ($header as $k => $v){
            $colum = \PHPExcel_Cell::stringFromColumnIndex($hk);
            $objPHPExcel->getActiveSheet()->setCellValue($colum.'1', $v)->getStyle($colum.'1')->applyFromArray(array('font'=>array('bold'=>true,'size'=>13),'alignment' => $alignment));
            $c_num = ($colum=='G'|| $colum=='F')?28:17;//单元格宽度
            $objPHPExcel->getActiveSheet()->getColumnDimension($colum)->setWidth($c_num); 
            $hk += 1;
        }
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();
        foreach($data as $key => $rows) {  //行写入
            $span = 0;
            foreach($rows as $keyName => $value) { // 列写入
                $j = \PHPExcel_Cell::stringFromColumnIndex($span);
                $objActSheet->setCellValue($j.$column, $value)->getStyle($j.$column)->applyFromArray(array('alignment' => $alignment));
                $span++;
            }
            $column++;
        }

        // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle($title);

        // Save Excel 2007 file
        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
        header('Pragma:public');
        header("Content-Type:application/x-msexecl;name=\"{$filename}.xls\"");
        header("Content-Disposition:inline;filename=\"{$filename}.xls\"");

        $objWriter->save('php://output');
    
    }


    /**
     * 导入excel表
     * @param  [type] $name [description]
     * @return [arr]  array [description]
     */
    public static function importExcel($name){
        if(isset($_FILES[$name]) && $_FILES[$name]["error"]==0){
            //require relation class files
            require(Yii::getAlias('@common').'/PHPExcel/Classes/PHPExcel.php');
            require(Yii::getAlias('@common').'/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php');

            $PHPExcel = new \PHPExcel();
            $file = "assets/file.xls";
            move_uploaded_file($_FILES[$name]["tmp_name"],$file);
            // echo $_SERVER['DOCUMENT_ROOT'].'/'.$file;
            $reader = \PHPExcel_IOFactory::createReader('Excel2007'); // 读取 excel 文件
            $excel = \PHPExcel_IOFactory::load($_SERVER['DOCUMENT_ROOT'].'/'.$file);
            // $excel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $sheet = $excel->getSheet(0);
            $count = $sheet->getHighestRow();//拿到行数
            return array($sheet,$count);
        }
    }
}