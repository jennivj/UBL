<?php

class BaseconeInvoice
{
    public $customizationID;
    public $ID;
    public $issueDate;
    public $InvoiceTypeCode;
    public $currencyAttrListID;
    public $currencyAttrAgencyID;
    public $docCurrencyCode;
    public $invoiceStartDate;
    public $invoiceEndDate;
    public $supplierPartyName;
    public $supplierPartyCityName;
    public $supplierPartyStreetName;
    public $supplierPartyBuildingNumber;
    public $supplierPartyZip;
    public $supplierPartyCountryCode;
    public $supplierPartyCountryCodeAttr;
    public $contactEmail;
    public $contactPhone;
    public $taxSchemeID;
    public $taxSchemeAttrID;
    public $companyID;
    public $companySchemeID;
    public $legalEntity;
    public $leComId;
    public $leRegName;
    /* paymentMeans Node */
    public $paymentMeansCode;
    public $paymentMeansDueDate;
    public $paymentMeansFASchemaId;
    public $paymentMeansFASchemaVal;
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
    /* Invoice Inline  Node */

    public $inLineId = array();
    public $inLineQuantity = array();
    public $inLineQuantityAttr = array();
    public $inLineExtAmount = array();
    public $inLineItemName = array();
    public $inLineItemDesc = array();
    public $inLineSellerId = array();
    /*  Invoice Inline  >item */
    public $itemTaxCatID;
    public $itemTaxCatIdAttr;
    public $itemTaxCatName;
    public $itemTaxCatPercent;
    public $itemTaxCatSchemeID;
    public $itemTaxCatSchemeIdAttr;
    /***/
    public $LegalMonetaryExtAmount;
    public $LegalMonetaryTaxExcAmount;
    public $LegalMonetaryTaxIncAmount;
    public $LegalMonetaryPayableAmt;
    public $LegalMonetaryAllowanceTotalAmt;

    public $priceAmount= array();
    public $baseQuantity= array();
    public $unitCode= array();

    public $legEntityCompanyID;
    public $legEntityRegName;

    public $customertaxSchemeAttrID = array();

    public function generateXml($RefDoc)
    {

        $service = new Sabre\Xml\Service();
        $service->namespaceMap = [
            'urn:oasis:names:specification:ubl:schema:xsd:Invoice-2' => '',
            'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2' => 'cbc',
            'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2' => 'cac'
        ];

        $entry = $this->invoiceGeneration();
        $fileName = 'ubl_invoice_'. $RefDoc .'.xml' ;
        file_put_contents( $fileName , $service->write('Invoice', $entry));
//return $this;
    }

    public function invoiceGeneration()
    {
        $entry = new \CleverIt\UBL\Invoice\Invoice();
        $entry->UBLVersionID = $entry->UBLVersionID;
        $entry->CustomizationID = $this->customizationID;
        $entry->ID = $entry->setId($this->ID);
        $entry->IssueDate = $this->issueDate;
          $entry->DueDate = $this->DueDate;
        
        $entry->InvoiceTypeCode = $this->InvoiceTypeCode;
        
        $currencyAttrArray = ['listID' => $this->currencyAttrListID, 'listAgencyID' => $this->currencyAttrAgencyID];
        $entry->DocumentCurrencyCode($this->docCurrencyCode, $currencyAttrArray);
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
            ->setId($this->taxSchemeID ,$this->taxSchemeAttrID)
            ->setSchemeId($this->taxSchemeAttrID));
        $accountingSupplierParty->setCompanyId($this->companyID);
        $accountingSupplierParty->setCompanySchemeID($this->companySchemeID);

        $accountingSupplierParty->setLegalEntity( 
            (new \CleverIt\UBL\Invoice\LegalEntity())
            ->setCompanyId($this->leComId)
            ->setRegistrationName( $this->leRegName )  );
        $accountingSupplierParty->setContact( (new \CleverIt\UBL\Invoice\Contact())->setElectronicMail($this->contactEmail )->setTelephone( $this->contactPhone));
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
            ->setId($this->customertaxSchemeID , $this->customertaxSchemeAttrID )
            );
        $accountingCustomerParty->setCompanyId($this->customercompanyID);
        $accountingCustomerParty->setCompanySchemeID($this->customercompanySchemeID);
        $accountingCustomerParty->setLegalEntity( 
            (new \CleverIt\UBL\Invoice\LegalEntity())
            ->setCompanyId(  $this->legEntityCompanyID)
            ->setRegistrationName( $this->legEntityRegName)  );
        $entry->setAccountingCustomerParty($accountingCustomerParty);

        /* paymentMeans Node */
        $paymentMeans = (new \CleverIt\UBL\Invoice\PaymentMeans())
            ->setPaymentMeansCode($this->paymentMeansCode)
            ->setPaymentDueDate($this->paymentMeansDueDate)
            ->setFinancialAccount((new \CleverIt\UBL\Invoice\PayeeFinancialAccount())
                ->setSchema($this->paymentMeansFASchemaId, $this->paymentMeansFASchemaVal));
        $entry->setpaymentMeans($paymentMeans);

        /* Tax Node */
//$entry->TaxCurrencyCode($this->taxCurrencyCode) ;
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
        /* Invoice Inline  Node */


        $countInLineId = count($this->inLineId);

        $invoiceLine = array();
        for ($k = 0; $k < $countInLineId; $k++) {

            $invLineTaxtotal = (new \CleverIt\UBL\Invoice\TaxTotal());
            $invLineTaxtotal->setTaxAmount($this->taxAmount[$k]);
            // $invLineTaxtotal->addTaxSubTotal( (new \CleverIt\UBL\Invoice\TaxSubTotal() )
            //  ->setTaxAmount( $this->taxTAmount )
            // ->setTaxableAmount( $this->taxTableAmount  )
            //  );
            $item = (new \CleverIt\UBL\Invoice\Item())->setName($this->inLineItemName[$k])
               // ->setDescription($this->inLineItemDesc[$k])
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
                );
               // ->setSellersItemIdentification($this->inLineSellerId[$k]);

            $price = (new \CleverIt\UBL\Invoice\Price())->setPriceAmount($this->priceAmount[$k])
                ->setUnitCode($this->unitCode[$k])
                ->setBaseQuantity($this->baseQuantity[$k]);

            $invoiceLine[] = (new \CleverIt\UBL\Invoice\InvoiceLine())
                ->setId($this->inLineId[$k])
                ->setInvoicedQuantity($this->inLineQuantity[$k] , $this->inLineQuantityAttr[$k] )
                ->setLineExtensionAmount($this->inLineExtAmount[$k])
               // ->setTaxTotal($invLineTaxtotal)
                ->setPrice($price)
                ->setItem($item);


            /*
                 $invoiceLine[]  = (new \CleverIt\UBL\Invoice\InvoiceLine())
                ->setId($this->inLineId)
                ->setInvoicedQuantity($this->inLineQuantity )
                ->setLineExtensionAmount($this->inLineExtAmount)
                ->setTaxTotal($invLineTaxtotal)
                ->setPrice($price)
                ->setItem($item); 

            */
        }


//$entry->setInvoiceLines([$invoiceLine]);

        $entry->setInvoiceLines($invoiceLine);
//$entry->setInvoiceLines([$invoiceLine]);


        echo '<pre>';
        print_R($entry);
        /* LegalMonetaryTotal  Node */
        $entry->setLegalMonetaryTotal((new \CleverIt\UBL\Invoice\LegalMonetaryTotal())
            ->setLineExtensionAmount($this->LegalMonetaryExtAmount)
            ->setTaxExclusiveAmount($this->LegalMonetaryTaxExcAmount)
            ->setTaxInclusiveAmount($this->LegalMonetaryTaxIncAmount)
            ->setPrePaidAmount($this->LegalMonetaryPrePaidAmt)
            ->setPayableAmount($this->LegalMonetaryPayableAmt)
            ->setAllowanceTotalAmount($this->LegalMonetaryAllowanceTotalAmt));

        return $entry;
    }


}  