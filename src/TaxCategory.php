<?php
 

namespace CleverIt\UBL\Invoice;


use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class TaxCategory implements XmlSerializable {
    private $id;
    private $idAttr;
    private $name;
    private $percent;
    private $taxScheme;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return TaxCategory
     */
    public function setId($id,$idAttr=false) {
        $this->id = $id;
       $this->idAttr = $idAttr; 
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
     * @return TaxCategory
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPercent() {
        return $this->percent;
    }

    /**
     * @param mixed $percent
     * @return TaxCategory
     */
    public function setPercent($percent) {
        $this->percent = $percent;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTaxScheme() {
        return $this->taxScheme;
    }

    /**
     * @param mixed $taxScheme
     * @return TaxCategory
     */
    public function setTaxScheme($taxScheme) {
        $this->taxScheme = $taxScheme;
        return $this;
    }



    public function validate() {
        if ($this->id === null) {
            throw new \InvalidArgumentException('Missing taxcategory id');
        }

        if ($this->name === null) {
            throw new \InvalidArgumentException('Missing taxcategory name');
        }

        if ($this->percent === null) {
            throw new \InvalidArgumentException('Missing taxcategory percent');
        }
    }

    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    function xmlSerialize(Writer $writer) {
        $this->validate();
          $attrArray= [];
        if(isset($this->idAttr['schemeID'])){

        $attrArray['schemeID']= $this->idAttr['schemeID'];
        }
      if(isset($this->idAttr['schemeAgencyID'])){           
                $attrArray['schemeAgencyID']= $this->idAttr['schemeAgencyID'];
        }
        $writer->write([
            //Schema::CBC.'ID' => $this->id,
              [
                    'name' => Schema::CBC . 'ID',
                    'value' => $this->id,
                    'attributes' => $attrArray,

                ],
           
           
        ]);

   if($this->name != null){
            $writer->write([ Schema::CBC.'Name' => $this->name]);
        }
 if($this->percent != null){
            $writer->write([  Schema::CBC.'Percent' => $this->percent,]);
        }

        if($this->taxScheme != null){
            $writer->write([Schema::CAC.'TaxScheme' => $this->taxScheme]);
        }
    }
}