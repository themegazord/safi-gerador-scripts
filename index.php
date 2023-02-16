<?php
require_once 'vendor/autoload.php';
require 'Options/MenuGeral.php';
use Options\MenuGeral;
//
//$file = 'excel.xlsx';
//$reader = \Ark4ne\XlReader\Factory::createReader($file);
//$reader->load();
//echo "<pre>";
//foreach($reader->read() as $row) {
//    var_dump($row);
//}

MenuGeral::geral_menu();