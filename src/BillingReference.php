<?php
 

namespace CleverIt\UBL\Invoice;


use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class BillingReference implements XmlSerializable{
    private $AdditionalDocRefID;
    private $AdditionalDoctType;
    private $AdditionalDocAttachment  ;
    private $AdditionalDocMimeCode;
    private $AdditionalDocBinaryObject;
    /**
     * @var dditionalDoc
     */
    private $AdditionalDoc ;

     /**
     * @return mixed
     */
    public function getAdditionalDocRefID() {
        return $this->AdditionalDocRefID;
    }

    /**
     * @param mixed $AdditionalDoctType
     * @return AdditionalDoctType
     */
    public function setAdditionalDocRefID($additionalDocRefID) {
        $this->AdditionalDocRefID = $additionalDocRefID;
        return $this;
    }




    /**
     * @return mixed
     */
    public function getAdditionalDoctType() {
        return $this->AdditionalDoctType;
    }

    /**
     * @param mixed $AdditionalDoctType
     * @return AdditionalDoctType
     */
    public function setAdditionalDoctType($additionalDoctType) {
        $this->AdditionalDoctType = $additionalDoctType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdditionalDocAttachment() {
        return $this->AdditionalDocAttachment;
    }

    /**
     * @param mixed $additionalDocAttachment
     * @return Address
     */
    public function setAdditionalDocAttachment($additionalDocAttachment) {
        $this->AdditionalDocAttachment = $additionalDocAttachment;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdditionalDocMimeCode() {
        return $this->AdditionalDocMimeCode;
    }

    /**
     * @param mixed $AdditionalDocMimeCode
     * @return AdditionalDocMimeCode
     */
    public function setAdditionalDocMimeCode($additionalDocMimeCode) {
        $this->AdditionalDocMimeCode = $additionalDocMimeCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdditionalDocBinaryObject() {
        return $this->AdditionalDocBinaryObject;
    }

    /**
     * @param mixed $AdditionalDocBinaryObject
     * @return AdditionalDocBinaryObject
     */
    public function setAdditionalDocBinaryObject($additionalDocBinaryObject) {
        $this->AdditionalDocBinaryObject = $additionalDocBinaryObject;
        return $this;
    }

    /**
     * @return AdditionalDoc
     */
    public function getAdditionalDoc() {
        return $this->AdditionalDoc;
    }

    /**
     * @param AdditionalDoc $AdditionalDoc
     * @return AdditionalDoc
     */
    public function setAdditionalDoc($additionalDoc) {
        $this->AdditionalDoc = $additionalDoc;
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
            Schema::CBC.'ID' =>   $this->AdditionalDocRefID,
            Schema::CBC.'DocumentType' =>   $this->AdditionalDoctType  ,             
            Schema::CAC.'Attachment' =>  [
                    'name' => Schema::CBC . 'EmbeddedDocumentBinaryObject',
                    'value' =>  $this->AdditionalDocBinaryObject,
                    'attributes' => [
                        'mimeCode' => $this->AdditionalDocMimeCode
                    ]
                ],
           
        ]);
    }
}