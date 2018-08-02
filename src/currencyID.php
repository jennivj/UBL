<?php
 
namespace CleverIt\UBL\Invoice;  

use Sabre\Xml\Service;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;
class currencyID   {
  
 public static $currencyID; 

public function currencyCode($currencyCode){ 
 self::$currencyID = $currencyCode; 
}

 

}