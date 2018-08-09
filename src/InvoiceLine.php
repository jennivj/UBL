<?php
 
namespace CleverIt\UBL\Invoice;


use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class InvoiceLine implements XmlSerializable {
    private $id;
    private $invoicedQuantity;
    private $lineExtensionAmount;
    private $unitCode ;
    private $idAttr;
    /**
     * @var TaxTotal
     */
    private $taxTotal;
    /**
     * @var Item
     */
    private $item;
    /**
     * @var Price
     */
    private $price;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return InvoiceLine
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInvoicedQuantity() {
        return $this->invoicedQuantity;
    }

    /**
     * @param mixed $invoicedQuantity
     * @return InvoiceLine
     */
    public function setInvoicedQuantity($invoicedQuantity,$idAttr=false) {
        
       $this->idAttr = $idAttr;         
        $this->invoicedQuantity = $invoicedQuantity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLineExtensionAmount() {
        return $this->lineExtensionAmount;
    }

    /**
     * @param mixed $lineExtensionAmount
     * @return InvoiceLine
     */
    public function setLineExtensionAmount($lineExtensionAmount) {
        $this->lineExtensionAmount = $lineExtensionAmount;
        return $this;
    }

    /**
     * @return TaxTotal
     */
    public function getTaxTotal() {
        return $this->taxTotal;
    }

    /**
     * @param TaxTotal $taxTotal
     * @return InvoiceLine
     */
    public function setTaxTotal($taxTotal) {
        $this->taxTotal = $taxTotal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getItem() {
        return $this->item;
    }

    /**
     * @param mixed $item
     * @return InvoiceLine
     */
    public function setItem($item) {
        $this->item = $item;
        return $this;
    }

    /**
     * @return Price
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * @param Price $price
     * @return InvoiceLine
     */
    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnitCode() {
        return $this->unitCode;
    }

    /**
     * @param mixed $unitCode
     * @return InvoiceLine
     */
    public function setUnitCode($unitCode) {
        $this->unitCode = $unitCode;
        return $this;
    }


    /**
     * The xmlSerialize method is called during xml writing.
     * @param Writer $writer
     * @return void
     */
    function xmlSerialize(Writer $writer) {

          $attrArray= [];
        if(isset($this->idAttr['unitCode'])){

        $attrArray['unitCode']= $this->idAttr['unitCode'];
        }
    
        $writer->write([
            Schema::CBC . 'ID' => $this->id,
            [
                'name' => Schema::CBC . 'InvoicedQuantity',
                'value' => $this->invoicedQuantity,
                  'attributes' => $attrArray,
              /*   
                */
            ],
            [
                'name' => Schema::CBC . 'LineExtensionAmount',
                'value' => number_format($this->lineExtensionAmount,2),
                'attributes' => [
                    'currencyID' => currencyID::$currencyID
                ]
            ],
       
         
        
        ]);

      if ($this->taxTotal !== null && $this->taxTotal !="" ) {
          $writer->write(
                [
                       Schema::CAC . 'TaxTotal' => $this->taxTotal,
                ]);
      }

         if ($this->item !== null && $this->item !="" ) {
           $writer->write(
                [
                         Schema::CAC . 'Item' => $this->item,
                ]);
            }


    if ($this->price !== null) {
            $writer->write(
                [
                    Schema::CAC . 'Price' => $this->price
                ]
            );
        }
        
    }
}