<?php

class creditNoteClass
{
    public $customizationID;
    public $ProfileID;
    public $ID;
    public $issueDate;
    public $note;
    public $currencyAttrListID;
    public $currencyAttrAgencyID;
    public $docCurrencyCode;
#----------------------#
    public $InvoiceDocRefID;
    public $AdditionalDocRefID = array();
    public $AdditionalDoctType = array();
    public $AdditionalDocAttachment = array();
    public $AdditionalDocMimeCode = array();
    public $AdditionalDocBinaryObject = array();
    public $AdditionalDoc = array();

#----------------------#
    public $invoiceStartDate;
    public $invoiceEndDate;
    public $supplierPartyName;
    public $supplierPartyCityName;
    public $supplierPartyStreetName;
    public $supplierPartyBuildingNumber;
    public $supplierPartyZip;
    public $supplierPartyCountryCode;
    public $supplierPartyCountryCodeAttr;

    public $taxSchemeID;
    public $taxSchemeAttrID;
    public $companyID;
    public $companySchemeID;
    public $legalEntity;

    /* customer Node*/
    public $customerPartyName;
    public $customerPartyCityName;
    public $customerPartyStreetName;
    public $customerPartyBuildingNumber;
    public $customerPartyZip;
    public $customerPartyCountryCode;
    public $customerPartyCountryCodeAttr;
    public $customertaxSchemeID;
    public $customertaxSchemeAttrID;
    public $customercompanyID;
    public $customercompanySchemeID;
#----------------------#

    /* paymentMeans Node */
    public $paymentMeansCode;
    public $paymentMeansDueDate;
    public $paymentMeansFASchemaId;
    public $paymentMeansFASchemaVal;
    public $paymentMeansCodeAttr;
    /* Tax Node */
    public $taxCurrencyCode;
    public $taxAmount;
    public $taxTAmount = array();
    public $taxTableAmount = array();
    public $taxTCatId = array();
    public $taxTCatIdAttr = array();
    public $taxTCatName = array();
    public $taxTCatPercent = array();
    public $taxTCatSchemeID = array();
    public $taxTCatSchemeIDAttr = array();
    public $taxTCatSchemeVal = array();
    /* Invoice crdtLine  Node */

    public $crdtLineId = array();
    public $crdtLineQuantity = array();
    public $crdtLineQuantityAttr = array();
    public $crdtLineExtAmount = array();
    public $crdtLineItemName = array();
    public $crdtLineItemDesc = array();
    public $crdtLineSellerId = array();
    /*  Invoice crdtLine  >item */
    public $itemTaxCatID;
    public $itemTaxCatIdAttr;
    public $itemTaxCatName;
    public $itemTaxCatPercent;
    public $itemTaxCatSchemeID;
    public $itemTaxCatSchemeIdAttr;
    /***/
    public $LegalMonetaryExtAmount;
    public $LegalMonetaryTaxExcAmount;
    public $LegalMonetaryPayableAmt;
    public $LegalMonetaryAllowanceTotalAmt;

    public $priceAmount;
    public $baseQuantity;
    public $unitCode;


    public function generateXml()
    {


        $service = new Sabre\Xml\Service();
        $service->namespaceMap = [
            'urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2' => '',
            'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2' => 'cbc',
            'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2' => 'cac'
        ];

        $entry = $this->creditNoteGeneration();
        file_put_contents("ubl_creditnote.xml", $service->write('Invoice', $entry));
//return $this;
    }

    public function creditNoteGeneration()
    {


        $entry = new \CleverIt\UBL\Invoice\CreditNote();
        $entry->UBLVersionID = $entry->UBLVersionID;
        $entry->CustomizationID = $this->customizationID;
        $entry->ProfileID = $this->ProfileID;
        $entry->ID = $entry->setId($this->ID);
        $entry->IssueDate = $this->issueDate;
        $entry->note = $this->note;
        $attrArrayCur = [];
        if (isset($this->currencyAttrListID)) {
            $attrArrayCur['listID'] = $this->currencyAttrListID;
        }
        if (isset($this->currencyAttrAgencyID)) {
            $attrArrayCur['listAgencyID'] = $this->currencyAttrAgencyID;
        }
        $currencyAttrArray = $attrArrayCur;
        $entry->DocumentCurrencyCode($this->docCurrencyCode, $currencyAttrArray);
        $entry->setInviceDocRefID($this->InvoiceDocRefID);
        for ($k = 0; $k < count($this->AdditionalDocRefID); $k++) {
            $arryAdditionRef[] = (new \CleverIt\UBL\Invoice\BillingReference())
                ->setAdditionalDocRefID($this->AdditionalDocRefID[$k])
                ->setAdditionalDoctType($this->AdditionalDoctType[$k])
                ->setAdditionalDocAttachment($this->AdditionalDocAttachment[$k])
                ->setAdditionalDocBinaryObject($this->AdditionalDocBinaryObject[$k])
                ->setAdditionalDocMimeCode($this->AdditionalDocMimeCode[$k]);

            /*[ 'ID' => $this->AdditionalDocRefID[$k]  ,
                                  'DocumentType' =>  $AdditionalDoctType[$k] ];
               */
        }
        print_r($arryAdditionRef);
        $entry->setAdditionalInvoiceDocRef($arryAdditionRef);

        $entry->InvoicePeriod($this->invoiceStartDate, $this->invoiceEndDate); // start and end date

        /* Supplier Node */
        $accountingSupplierParty = new \CleverIt\UBL\Invoice\Party();
        $accountingSupplierParty->setName($this->supplierPartyName);
        $supplierAddress = (new \CleverIt\UBL\Invoice\Address())
            ->setCityName($this->supplierPartyCityName)
            ->setStreetName($this->supplierPartyStreetName)
            ->setBuildingNumber($this->supplierPartyBuildingNumber)
            ->setPostalZone($this->supplierPartyZip)
            ->setCountry((new \CleverIt\UBL\Invoice\Country())
                ->setIdentificationCode($this->supplierPartyCountryCode)
                ->setIdentificationCodeAttr($this->supplierPartyCountryCodeAttr));

        $accountingSupplierParty->setPostalAddress($supplierAddress);
        $accountingSupplierParty->setTaxScheme((new \CleverIt\UBL\Invoice\TaxScheme())
            ->setId($this->taxSchemeID)
            ->setSchemeId($this->taxSchemeAttrID));
        $accountingSupplierParty->setCompanyId($this->companyID);
        $accountingSupplierParty->setCompanySchemeID($this->companySchemeID);

        $accountingSupplierParty->setLegalEntity($this->legalEntity);
        $accountingSupplierParty->setContact((new \CleverIt\UBL\Invoice\Contact())->setElectronicMail("info@cleverit.nl")->setTelephone("31402939003"));
        $entry->setAccountingSupplierParty($accountingSupplierParty);


        /* Customer Party  Node */
        $accountingCustomerParty = new \CleverIt\UBL\Invoice\Party();
        $accountingCustomerParty->setName($this->customerPartyName);
        $customerAddress = (new \CleverIt\UBL\Invoice\Address())
            ->setCityName($this->customerPartyCityName)
            ->setStreetName($this->customerPartyStreetName)
            ->setBuildingNumber($this->customerPartyBuildingNumber)
            ->setPostalZone($this->customerPartyZip)
            ->setCountry((new \CleverIt\UBL\Invoice\Country())
                ->setIdentificationCode($this->customerPartyCountryCode)
                ->setIdentificationCodeAttr($this->customerPartyCountryCodeAttr));

        $accountingCustomerParty->setPostalAddress($customerAddress);
        $accountingCustomerParty->setTaxScheme((new \CleverIt\UBL\Invoice\TaxScheme())
            ->setId($this->customertaxSchemeID)
            ->setSchemeId($this->customertaxSchemeAttrID));
        $accountingCustomerParty->setCompanyId($this->customercompanyID);
        $accountingCustomerParty->setCompanySchemeID($this->customercompanySchemeID);

        $entry->setAccountingCustomerParty($accountingCustomerParty);

        /* paymentMeans Node */
        $paymentMeans = (new \CleverIt\UBL\Invoice\PaymentMeans())
            ->setPaymentMeansCode($this->paymentMeansCode, $this->paymentMeansCodeAttr)
            ->setPaymentDueDate($this->paymentMeansDueDate)
            ->setFinancialAccount((new \CleverIt\UBL\Invoice\PayeeFinancialAccount())
                ->setSchema($this->paymentMeansFASchemaId, $this->paymentMeansFASchemaVal));
        $entry->setpaymentMeans($paymentMeans);

        /* Tax Node */
        $entry->TaxCurrencyCode((new \CleverIt\UBL\Invoice\CurrencyID())->currencyCode($this->taxCurrencyCode));


        $taxtotal = (new \CleverIt\UBL\Invoice\TaxTotal());
        $taxtotal->setTaxAmount($this->taxAmount);
        $taxSubTotalCnt = count($this->taxTCatId);

        $taxtotal->addTaxSubTotal((new \CleverIt\UBL\Invoice\TaxSubTotal())
            ->setTaxAmount($this->taxTAmount)
            ->setTaxableAmount($this->taxTableAmount)
            ->setTaxCategory((new \CleverIt\UBL\Invoice\TaxCategory())
                ->setId($this->taxTCatId, $this->taxTCatIdAttr)
                ->setName($this->taxTCatName)
                ->setPercent($this->taxTCatPercent)
                ->setTaxScheme((new \CleverIt\UBL\Invoice\TaxScheme())
                    ->setId($this->taxTCatSchemeID, $this->taxTCatSchemeIDAttr)
                )
            ));

        $taxtotal->setTaxAmount($this->taxTAmount);

        $entry->setTaxTotal($taxtotal);
        /* Invoice crdtLine  Node */


        $countcrdtLineId = count($this->crdtLineId);

        $invoiceLine = array();
        for ($k = 0; $k < $countcrdtLineId; $k++) {

            $invLineTaxtotal = (new \CleverIt\UBL\Invoice\TaxTotal());
            $invLineTaxtotal->setTaxAmount($this->taxAmount[$k]);
            $invLineTaxtotal->addTaxSubTotal((new \CleverIt\UBL\Invoice\TaxSubTotal())
                ->setTaxAmount($this->taxTAmount)
                ->setTaxableAmount($this->taxTableAmount)
                ->setTaxCategory((new \CleverIt\UBL\Invoice\TaxCategory())->setId($this->itemTaxCatID[$k], $this->itemTaxCatIdAttr[$k])
                    ->setName($this->itemTaxCatName[$k])
                    ->setPercent($this->itemTaxCatPercent[$k])
                    ->setTaxScheme((new \CleverIt\UBL\Invoice\TaxScheme())->setId($this->itemTaxCatSchemeID[$k], $this->itemTaxCatSchemeIdAttr[$k]))
                ));
            $item = (new \CleverIt\UBL\Invoice\Item())->setName($this->crdtLineItemName[$k])
                ->setDescription($this->crdtLineItemDesc[$k])
                ->setTaxCategory((new \CleverIt\UBL\Invoice\TaxCategory())
                    ->setId($this->itemTaxCatID[$k], $this->itemTaxCatIdAttr[$k])
                    //  ->setId($this->itemTaxCatID[$k]  )
                    ->setName($this->itemTaxCatName[$k])
                    ->setPercent($this->itemTaxCatPercent[$k])
                    ->setTaxScheme((new \CleverIt\UBL\Invoice\TaxScheme())
                        //->setId($this->itemTaxCatSchemeID[$k] )
                        ->setId($this->itemTaxCatSchemeID[$k], $this->itemTaxCatSchemeIdAttr[$k])
                    //->setSchemeId('jjjjj')
                    )
                )
                ->setSellersItemIdentification($this->crdtLineSellerId[$k]);

            $price = (new \CleverIt\UBL\Invoice\Price())->setPriceAmount($this->priceAmount[$k])
                ->setUnitCode($this->unitCode[$k])
                ->setBaseQuantity($this->baseQuantity[$k]);

            $invoiceLine[] = (new \CleverIt\UBL\Invoice\CreditLine())
                ->setId($this->crdtLineId[$k])
                ->setCreditedQuantity($this->crdtLineQuantity[$k], $this->crdtLineQuantityAttr[$k])
                ->setLineExtensionAmount($this->crdtLineExtAmount[$k])
                ->setTaxTotal($invLineTaxtotal)
                ->setPrice($price)
                ->setItem($item);


            /*
                 $invoiceLine[]  = (new \CleverIt\UBL\Invoice\InvoiceLine())
                ->setId($this->crdtLineId)
                ->setInvoicedQuantity($this->crdtLineQuantity )
                ->setLineExtensionAmount($this->crdtLineExtAmount)
                ->setTaxTotal($invLineTaxtotal)
                ->setPrice($price)
                ->setItem($item); */
        }


//$entry->setInvoiceLines([$invoiceLine]);

        $entry->setcreditLines($invoiceLine);
//$entry->setInvoiceLines([$invoiceLine]);


        //  echo '<pre>';
        //print_r($entry);

        /* LegalMonetaryTotal  Node */
        $entry->setLegalMonetaryTotal((new \CleverIt\UBL\Invoice\LegalMonetaryTotal())
            ->setLineExtensionAmount($this->LegalMonetaryExtAmount)
            ->setTaxExclusiveAmount($this->LegalMonetaryTaxExcAmount)
            ->setPayableAmount($this->LegalMonetaryPayableAmt)
            ->setAllowanceTotalAmount($this->LegalMonetaryAllowanceTotalAmt));

        return $entry;
    }


}  