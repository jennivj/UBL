<?php
 

namespace CleverIt\UBL\Invoice;


use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class PaymentMeans implements XmlSerializable {
	private $paymentCode;
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
	public function setPaymentMeansCode($paymentCode) {
		$this->paymentCode = $paymentCode;
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
		$writer->write([
			Schema::CBC.'PaymentMeansCode' => $this->paymentCode,
			Schema::CBC.'PaymentDueDate' =>   $this->paymentDueDate,         
            Schema::CAC.'PayeeFinancialAccount' => $this->paymentAccount, 

		]);
	}		 
}