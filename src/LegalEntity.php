<?php
 

namespace CleverIt\UBL\Invoice;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class LegalEntity implements XmlSerializable {

	/**
	 * @var string
	 */
	private $registrationName;

	/**
	 * @var int
	 */
	private $companyId;

	public function getRegistrationName() {
		return $this->registrationName;
	}

	public function setRegistrationName($registrationName) {
		$this->registrationName = $registrationName;
		return $this;
	}

	public function getCompanyId() {
		return $this->companyId;
	}

	public function setCompanyId($companyId) {
		$this->companyId = $companyId;
		return $this;
	}

	function xmlSerialize(Writer $writer) {
		$writer->write([
			Schema::CBC.'RegistrationName' => $this->registrationName,
			Schema::CBC.'CompanyID' => $this->companyId
		]);
	}
}