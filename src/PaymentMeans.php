<?php
 

namespace CleverIt\UBL\Invoice;


use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class PaymentMeans implements XmlSerializable {
	private $paymentCode;
	private $paymentMeansCodeAttr;
	private $paymentDueDate;
	private $paymentAccount;

	/**
	 * @return mixed
	 */
	public function getPaymentMeansCode() {
		return $this->paymentCode;
	}

	/**
	 * @param mixed $paymentCode
	 * @return int
	 */
	public function setPaymentMeansCode($paymentCode,$paymentCodeAttr=false) {
		$this->paymentCode = $paymentCode;
		$this->paymentMeansCodeAttr = $paymentCodeAttr;
		return $this;



	}

	/**
	 * @return mixed
	 */
	public function getPaymentDueDate() {
		return $this->paymentDueDate;
	}

	/**
	 * @param mixed $paymentCode
	 * @return int
	 */
	public function setPaymentDueDate($paymentDueDate) {
		$this->paymentDueDate = $paymentDueDate;
		return $this;
	}
	/**
	 * @param mixed $paymentCode
	 * @return int
	 */
	public function setFinancialAccount($paymentAccount) {
		$this->paymentAccount = $paymentAccount;
		return $this;
	}
//setPayeeFinancialAccount

	function xmlSerialize(Writer $writer) {
		 $attrArray= [];
       if(isset($this->paymentMeansCodeAttr['listID']) &&  $this->paymentMeansCodeAttr['listID'] != ""){
        $attrArray['listID']= $this->paymentMeansCodeAttr['listID'];
        }
      if(isset($this->paymentMeansCodeAttr['listURI'])){           
                $attrArray['listURI']= $this->paymentMeansCodeAttr['listURI'];
        }
	  if(isset($this->paymentMeansCodeAttr['listName'])){           
                $attrArray['listName']= $this->paymentMeansCodeAttr['listName'];
        }

		$writer->write([
			 [
                    'name' => Schema::CBC . 'PaymentMeansCode',
                    'value' => $this->paymentCode,
                    'attributes' => $attrArray,

                ],
		 
			//Schema::CBC.'PaymentDueDate' =>   $this->paymentDueDate,         
           // Schema::CAC.'PayeeFinancialAccount' => $this->paymentAccount, 

		]);
		if(	isset( $this->paymentDueDate) &&  $this->paymentDueDate !=""){ 
			$writer->write([ Schema::CBC.'PaymentDueDate' =>   $this->paymentDueDate,  ]) ;

		}
		 if(	isset( $this->paymentAccount) &&  $this->paymentAccount !=""){
		  $writer->write([  Schema::CAC.'PayeeFinancialAccount' => $this->paymentAccount,  ]) ;
		}
	}		 
}