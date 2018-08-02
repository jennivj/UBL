<?php
 
namespace CleverIt\UBL\Invoice;


use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class CreditLine implements XmlSerializable {
    private $id;
    private $creditedQuantity;
    private $crdtLineQuantityAttr;
    private $lineExtensionAmount;
    private $unitCode ;
    private $unitCodeListID;
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
    public function getCreditedQuantity() {
        return $this->creditedQuantity;
    }

    /**
     * @param mixed $invoicedQuantity
     * @return InvoiceLine
     */
    public function setCreditedQuantity($creditedQuantity,$crdtLineQuantityAttr=false) {
        $this->creditedQuantity = $creditedQuantity;
        $this->crdtLineQuantityAttr = $crdtLineQuantityAttr;

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
        if(isset($this->crdtLineQuantityAttr['unitCode'])){

        $attrArray['unitCode']= $this->crdtLineQuantityAttr['unitCode'];
        }
        if(isset($this->crdtLineQuantityAttr['unitCodeListID'])){           
                $attrArray['unitCodeListID']= $this->crdtLineQuantityAttr['unitCodeListID'];
        }

        $writer->write([
            Schema::CBC . 'ID' => $this->id,
            [
                'name' => Schema::CBC . 'CreditedQuantity',
                'value' => $this->creditedQuantity,
               'attributes' =>    $attrArray,
               
            ],
            [
                'name' => Schema::CBC . 'LineExtensionAmount',
                'value' => number_format($this->lineExtensionAmount,2),
                'attributes' => [
                    'currencyID' => currencyID::$currencyID
                ]
            ],
            Schema::CAC . 'TaxTotal' => $this->taxTotal,
            Schema::CAC . 'Item' => $this->item,
        
        ]);

        if ($this->price !== null) {
            $writer->write(
                [
                    Schema::CAC . 'Price' => $this->price
                ]
            );
        }
    }
}