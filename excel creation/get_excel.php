<?php
require_once 'Classes/PHPExcel.php';
$objPHPExcel = new PHPExcel();

//require_once 'Classes/PHPExcel/Cell/AdvancedValueBinder.php';
//PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );


#/ Define Caching
$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
PHPExcel_Settings::setCacheStorageMethod($cacheMethod);

/////////////////////////////////////////////////////////////////////////////

#/ Set properties
$objPHPExcel->getProperties()->setCreator($consts['CLIENT_NAME'])
->setLastModifiedBy("Back-Office")
->setTitle("{$filename}")
->setSubject("Events Status")
->setCategory("Events Status");


/////////////////////////////////////////////////////////////////////////////
#/ Set Values
$sheet = $objPHPExcel->setActiveSheetIndex(0);
$sheet->setTitle("Events Status");
for($i=0; $i<(count($excel_arr)); $i++)
{
    $v1 = $excel_arr[$i];
    for($j=0; $j<(count($v1)); $j++)
    {
        $v2 = $v1[$j];
        $sheet->setCellValueByColumnAndRow(($j), ($i+1), $v2);

        #/Set Column Width
        if($i==0)
        {
            $sheet->getColumnDimensionByColumn($j)->setAutoSize(true);
        }
    }


    ####/ Set Display Style
    $cell_start = $sheet->getCellByColumnAndRow((0), ($i+1))->getCoordinate();
    $cell_end = $sheet->getCellByColumnAndRow(($j-1), ($i+1))->getCoordinate();
    $cell_style = $sheet->getStyle("{$cell_start}:{$cell_end}");

    #/ Setup Heading Cells
    if($i==0)
    {
        $styleArray = array(
        	'borders' => array(
                'inside' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN,
        		  'color' => array('argb' => 'FFFFFFFF'),
        		),
        	),

        	'fill' => array(
        		'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
        		'rotation' => 90,
        		'startcolor' => array(
        			'argb' => 'FF94B4D3',
        		),
        		'endcolor' => array(
        			'argb' => 'FFFFFFFF',
        		),
        	),

            'font' => array(
                'bold' => true,
            ),

            'alignment' => array(
                'wrap' => true,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
        );
        $cell_style->applyFromArray($styleArray);

    }//end if i=0.....



    #Setup Non-Header styles
    if($i>0)
    {
        $styleArray = array(
            'borders' => array(
                'inside' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN,
        		  'color' => array('rgb' => 'E9E9E9'),
        		),
        	),

            'fill' => array(
        		'type' => PHPExcel_Style_Fill::FILL_SOLID,
        		'color' => array(
        			'rgb' => 'FFFFFF',
        		),
        	),

            'alignment' => array(
                'wrap' => true,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
        );


        #/ Color alternate rows and set border
        if($i%2==0)
        {
            $styleArray['fill'] = array(
    		'type' => PHPExcel_Style_Fill::FILL_SOLID,
    		'color' => array('rgb' => 'F0F0F0')
    		);
        }

        $cell_style->applyFromArray($styleArray);
    }

    #/ Row heights
    if($i==0)
    $sheet->getRowDimension(($i+1))->setRowHeight(40);
    else
    $sheet->getRowDimension(($i+1))->setRowHeight(20);

}//end for.....


#/ Insert & Setup Heading Row
$sheet->insertNewRowBefore(1, 1);
$sheet->getRowDimension(1)->setRowHeight(17);
$sheet->setCellValueByColumnAndRow(0, 1, "{$filename}");

$c1_start = $sheet->getCellByColumnAndRow(0, 1)->getCoordinate();
$c1_end = $sheet->getCellByColumnAndRow(($j-1), 1)->getCoordinate();
$sheet->mergeCells("{$c1_start}:{$c1_end}");
$cell_style = $sheet->getStyle("{$c1_start}");

$styleArray = array(
'font' => array('bold' => true),
'alignment' => array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER),
);
$cell_style->applyFromArray($styleArray);
/////////////////////////////////////////////////////////////////////////////


#/ Force Download header
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
header('Cache-Control: max-age=0');


#/ Save and output excel
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setOffice2003Compatibility(true);
$objWriter->save('php://output');



#/ Clear Memory & Close Excel
$objPHPExcel->disconnectWorksheets();
unset($objPHPExcel);
?>