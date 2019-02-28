<?php

    require_once '../../PhpExcel/PhpExcel.php';

    /*if (!array_key_exists ('v', $_REQUEST))
        die ('Vessel ID not present');

    if (!array_key_exists ('m', $_REQUEST))
        die ('Month not present');

    $vesselID = intval ($_REQUEST ['v']);
    $month    = intval ($_REQUEST ['m']);
    $year     = $month % 10000;
    $month    = round ($month * 0.0001 + 0.1);*/

    function genReport ($vesselID, $year, $month)
    {
        $tempName = '__'.time ().'__.xls';
        $tempPath = "../tempdoc/$tempName";

        //copy ('monthreport.xls', $tempPath);
        $xls = PHPExcel_IOFactory::load ('monthreport.xls');

        /*$xls->setActiveSheetIndex ($month - 1);

        foreach ($xls->getWorksheetIterator () as $worksheet)
        {
            echo $worksheet->getTitle ()."<br/>";
        }*/

        /*header ("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
        header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header ("Cache-Control: no-cache, must-revalidate");
        header ("Pragma: no-cache");
        header ("Content-type: application/excel");
        header ("Content-Disposition: attachment; filename=report.xls");*/
        
        $objWriter = new PHPExcel_Writer_Excel5 ($xls);
        $objWriter->save ($tempPath);

        return $tempPath;
    }
