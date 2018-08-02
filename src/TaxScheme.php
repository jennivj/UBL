<?php
 

namespace CleverIt\UBL\Invoice;


use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class TaxScheme implements XmlSerializable {
	private $id;
	private $schemeID;
	

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 * @return int
	 */
 
	 public function setId($id,$idAttr=false) {
        $this->id = $id;
        $this->idAttr = $idAttr; 
        return $this;
    }
	/**
	 * @param mixed $id
	 * @return int
	 */
	public function setSchemeId($schemeID) {
		$this->schemeID = $schemeID;
		return $this;
	}

	function xmlSerialize(Writer $writer) {
	/*	$writer->write([
			 Schema::CAC.'TaxScheme' => [Schema::CAC.'ID' => $this->taxScheme]
			//Schema::CAC.'ID' => $this->id
		]);

		*/  
		   $attrArray= [];
        if(isset($this->idAttr['schemeID'])){

        $attrArray['schemeID']= $this->idAttr['schemeID'];
        }
      if(isset($this->idAttr['schemeAgencyID'])){           
                $attrArray['schemeAgencyID']= $this->idAttr['schemeAgencyID'];
        }
        $writer->write([       
              [
                    'name' => Schema::CBC . 'ID',
                    'value' => $this->id,
                    'attributes' => $attrArray,

                ],
           
           
        ]);
	}
}