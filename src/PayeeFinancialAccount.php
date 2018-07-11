<?php
 

namespace CleverIt\UBL\Invoice;


use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class PayeeFinancialAccount implements  XmlSerializable {
 
    private $schemaId;
    private $schemaVal;

 
    /**
     * @param mixed $Id
     * @return schemaId, schemaVal
     */
  

  public function setSchema($schemaId,$schemaVal) {
        $this->schemaId = $schemaId;
         $this->schemaVal = $schemaVal;
        return $this;
    }


    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    function xmlSerialize(Writer $writer) {

           $writer->write([
               
               [
               'name' =>     Schema::CBC.'ID', 'value' =>   $this->schemaVal ,
               'attributes' =>[ 'schemeID' =>  $this->schemaId ]  ,
               ] 
              
               
            ]);
  
    }


}