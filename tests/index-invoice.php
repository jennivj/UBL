<?php
require_once "../vendor/autoload.php";
require_once "baseconeInvoiceClass.php"; 

$binvoice = new BaseconeInvoice;
 $binvoice->customizationID = 45;
$binvoice->ID = 2018112038;
$binvoice->issueDate  = '2018-07-03';
$binvoice->currencyAttrListID = 'ISO 4217 Alpha';
$binvoice->currencyAttrAgencyID= '6';
$binvoice->docCurrencyCode= 'EUR';
$binvoice->invoiceStartDate = '2018-08-03';
$binvoice->invoiceEndDate = '2018-08-03';
    /* Supplier Node */
$binvoice->supplierPartyName = "Basecone N.V.";
$binvoice->supplierPartyCityName= "BAARN";
$binvoice->supplierPartyStreetName= "Eemweg";
$binvoice->supplierPartyBuildingNumber= "8";
$binvoice->supplierPartyZip= "3742 LB";

$binvoice->companyID= "NL851645690B01";
$binvoice->companySchemeID = "NLVAT";
$binvoice->supplierPartyCountryCode = 'NL';
$binvoice->supplierPartyCountryCodeAttr = 'ISO3166-1';
$binvoice->taxSchemeID= "UN/ECE 5153";
$binvoice->taxSchemeAttrID= "NL851645690B01";

$binvoice->legalEntity= "Tset";

   /* PaymentMeans Node */
$binvoice->paymentMeansCode= "jettt"; 
$binvoice->paymentMeansDueDate= "Tset";    
$binvoice->paymentMeansFASchemaId = "IBAN";
$binvoice->paymentMeansFASchemaVal= "NL89INGB0007168173";
      /* Tax Node */
$binvoice->taxAmount = '100';
$binvoice->taxTAmount  = 23;
$binvoice->taxTableAmount  = 283;

$binvoice->taxCurrencyCode = "EUR";
$binvoice->taxTCatId = "N03";
$binvoice->taxTCatIdAttr['schemeAgencyID'] = '6';
$binvoice->taxTCatIdAttr['schemeID'] ='UN/ECE 5305';
$binvoice->taxTCatName = "Tax Name";
$binvoice->taxTCatPercent = "4.5";
$binvoice->taxTCatSchemeID  = "IB";
//$binvoice->taxTCatSchemeVal = "4564564";
$binvoice->taxTCatSchemeIDAttr['schemeAgencyID'] = '6';
$binvoice->taxTCatSchemeIDAttr['schemeID'] ='UN/ECE 530ffff';
/* 
$binvoice->taxTId[]= "N07";
$binvoice->taxTName[]= "Tax Name 2";
$binvoice->taxTPercent[]= "5";
$binvoice->taxTSchemeID[]= "IB2";
$binvoice->taxTSchemeVal[]= "445645645";
$binvoice->taxTAmount  =23;
$binvoice->taxTableAmount =23;
 */
  /* Invoice Inline  Node */
 
  for($i=0 ; $i<=1 ; $i++){

$binvoice->inLineId[]= "5b38b5b7c8e78b168049ec24";
$binvoice->inLineQuantity[] = "6";
$binvoice->inLineExtAmount[]= "67";
$binvoice->inLineItemName[] = "Active Company Exact Online KMO - " .$i;
$binvoice->inLineItemDesc[] = "Active Company Exact Online KMO";
$binvoice->inLineSellerId[]= "N07".$i;


  /* Invoice Inline >item  Node */
$binvoice->itemTaxCatID[] = "SS";
$binvoice->itemTaxCatIdAttr[$i]['schemeAgencyID']  = '68'.$i;
$binvoice->itemTaxCatIdAttr[$i]['schemeID']  ='UN/ECE 530dd' .$i;
 
$binvoice->itemTaxCatName []= "Tax Name Test" .$i;
$binvoice->itemTaxCatPercent[] = 21;
$binvoice->itemTaxCatSchemeID[] = "UN/ECE 5153" .$i;
 
$binvoice->itemTaxCatSchemeIdAttr[$i]['schemeAgencyID']  = '67' .$i;
$binvoice->itemTaxCatSchemeIdAttr[$i]['schemeID'] ='UN/ECE 53055' .$i;

}

$binvoice->LegalMonetaryExtAmount = 68;
$binvoice->LegalMonetaryTaxExcAmount = 7  ;
$binvoice->LegalMonetaryPayableAmt = 678  ;
$binvoice->LegalMonetaryAllowanceTotalAmt= "576" ;
$binvoice->priceAmount = 9;
$binvoice->baseQuantity = 9;
$binvoice->unitCode ="TEST" .$i;

 $binvoice->generateXml()  ;
 