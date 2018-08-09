<?php
 

namespace CleverIt\UBL\Invoice;


use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class Item implements XmlSerializable {
    private $description;
    private $name;
    private $sellersItemIdentification;
    private $taxCategory;

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return Item
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Item
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSellersItemIdentification() {
        return $this->sellersItemIdentification;
    }

    /**
     * @param mixed $sellersItemIdentification
     * @return Item
     */
    public function setSellersItemIdentification($sellersItemIdentification) {
        $this->sellersItemIdentification = $sellersItemIdentification;
        return $this;
    }


    /**
     * @param TaxCategory $taxCategory
     * @return TaxSubTotal
     */
    public function setTaxCategory($taxCategory) {
        $this->taxCategory = $taxCategory;
        return $this;
    }


    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    function xmlSerialize(Writer $writer) {
         if(isset($this->name)  ){
           $itemName = ($this->name =="")?'NA':$this->name;
              $writer->write([           
           Schema::CBC.'Name' =>    $itemName,        
        
        ]);
         }
           if(isset($this->description) &&  $this->description !=""){
              $writer->write([           
             Schema::CBC.'AdditionalInformation' =>  $this->description,        
        
        ]);
         }        

   

           if(isset($this->sellersItemIdentification)){
             $writer->write([            
               Schema::CAC.'SellersItemIdentification' => [
               Schema::CBC.'ID' => $this->sellersItemIdentification
           ],
       
           
        
        ]);
         }
          if(isset($this->taxCategory)){
              $writer->write([           
             Schema::CAC.'ClassifiedTaxCategory' =>$this->taxCategory,
        
        ]);
         }
    }
}