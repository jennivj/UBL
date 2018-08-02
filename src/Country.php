<?php
 

namespace CleverIt\UBL\Invoice;


use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class Country implements  XmlSerializable {
    private $identificationCode;
    private $identificationCodeAttr;

    /**
     * @return mixed
     */
    public function getIdentificationCode() {
        return $this->identificationCode;
    }

    /**
     * @param mixed $identificationCode
     * @return Country
     */
    public function setIdentificationCode($identificationCode) {
        $this->identificationCode = $identificationCode;
        return $this;
    }


 /**
     * @param mixed $identificationCodeAttr
     * @return identificationCodeAttr
     */
    public function setIdentificationCodeAttr($identificationCodeAttr) {
        $this->identificationCodeAttr = $identificationCodeAttr;
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
                'name' => Schema::CBC . 'IdentificationCode',
                'value' =>  $this->identificationCode,
                'attributes' => [
                    'listID' => $this->identificationCodeAttr,
                ]
            ],
        ]);
    }


}