<?php
require_once "../vendor/autoload.php";
require_once "baseconeInvoiceClass.php";
require_once "db-connection.php";



 $sqlDoc = "SELECT *  FROM `sysdocuments`   WHERE  TypeDoc ='INV' AND TotalHT>0   limit 4,1 ";
$resultDoc = $conn->query($sqlDoc);
 
 
if ($resultDoc->num_rows > 0) {
    // output data of each row
    while ($row = $resultDoc->fetch_assoc()) {

       // if (  $row["TauxTVA"] >= 0) {


         echo   $sqlclient = " SELECT * FROM `sysclients`   WHERE RefClient=" . $row["RefClient"] . " ";
            $resultClient = $conn->query($sqlclient);
            while ($rowClient = $resultClient->fetch_assoc()) {
                $cust_name = ($rowClient['NomClient'] != "") ? $rowClient['NomClient'] : $rowClient['Societe'];
                $in_amnt = ($row["TotalTTC"] != "") ? $row["TotalTTC"] : 0;

                # query for currency attributes list id

                $sqlList = "SELECT RefListe    FROM `syslistes`  where Groupe= 'Devise' AND TxtOpt='" . $row["Devise"] . "' ";
                $resultList = $conn->query($sqlList);

                $rowList = $resultList->fetch_assoc();
                $currencyAttrAgencyID = (string)$rowList['RefListe'];
                $currencyAttrListID = 'ISO 4217 Alpha';  //ISO 4217 is the International std for currency codes
                $countryCodeAttr = 'ISO3166-1';  //ISO 3166 is the Intl Standard for country codes and codes
                $taxSchemeID = "UN/ECE 5153"; //Vat Tax Code ;  for Tax codes, such as "VAT".
                $taxCatSchemeID = "UN/ECE 5305"; // Category Scheme ID
                $currencyCode =  ($row["Devise"] != "")?$row["Devise"]:'EUR'; // EUR
 
                $binvoice = new BaseconeInvoice;
                $binvoice->customizationID = strtotime($row["DtEmis"]); #<cbc:CustomizationID>
                $binvoice->ID = $row['RefDoc']; #<cbc:ID>
                $binvoice->issueDate = $row["DtEmis"];// <cbc:IssueDate> '2018-07-03';
                
                 $binvoice->DueDate = $row["DtEmis"];// <cbc:IssueDate> '2018-07-03';
                 $binvoice->InvoiceTypeCode = 380;
                $binvoice->currencyAttrListID = $currencyAttrListID; //ISO 4217 is the International Standard for currency codes <cbc:DocumentCurrencyCode listAgencyID="6" listID="ISO 4217 Alpha">
                $binvoice->currencyAttrAgencyID = $currencyAttrAgencyID;
                $binvoice->docCurrencyCode = $currencyCode;
              /*<cac:InvoicePeriod> */
                $binvoice->invoiceStartDate = $row["DtEmis"];  #<cbc:StartDate>
                $binvoice->invoiceEndDate = $row["DtEcheance"]; #<cbc:EndDate>
                /* Supplier Node */
              
  /* iterating building number from address */
                $zipCity_str = getsysVarValues('CPVille');
                 preg_match_all('!\d+!', $zipCity_str, $zip);
                 $zip = isset(  $zip) ?   $zip : '';
                 $city =  $zipCity_str ;

                $binvoice->supplierPartyName = getsysVarValues('NomSociete') ; #-<cac:PartyName> <cbc:Name>
                 /* -<cac:PostalAddress>  */
                $binvoice->supplierPartyCityName =  $city; #<cbc:CityName>
                $binvoice->supplierPartyStreetName = getsysVarValues('Adresse1')  ; //  ; #<cbc:StreetName
                $binvoice->supplierPartyBuildingNumber = getsysVarValues('Adresse2')  ; ; //$building_number; #<cbc:BuildingNumber>
                $binvoice->supplierPartyZip =  $zip; #<cbc:PostalZone>
                $binvoice->companyID =  getsysVarValues('TVAintra') ; //  # <cac:PartyTaxScheme><cbc:CompanyID
                $binvoice->companySchemeID =     getsysVarValues('Pays') . "VAT" ; // $rowClient['Pays'] . "VAT"; #<cbc:CompanyID attribute
                $binvoice->supplierPartyCountryCode = getsysVarValues('Pays'); #<cbc:IdentificationCode
                $binvoice->supplierPartyCountryCodeAttr =  $countryCodeAttr;
                $binvoice->taxSchemeID = "VAT";
                $binvoice->taxSchemeAttrID =  getsysVarValues('TVAintra');
                $binvoice->contactEmail  =  getsysVarValues('Email');
                $binvoice->contactPhone =  getsysVarValues('Tel');
                $binvoice->leRegName = getsysVarValues('NomSociete') ; // Need to clarify
                $binvoice->leComId = getsysVarValues('NomSociete') ; // Need to clarify
                /* customer */

                 /* iterating building number from address */
                $address_str = $rowClient['Adresse'] . ' , ' . $rowClient['Adresse2'];
                preg_match_all('!\d+!', $address_str, $building_number);
                $building_number = isset($building_number) ? trim($building_number) : '';
 
                $binvoice->customerPartyName = $cust_name; #-<cac:PartyName> <cbc:Name>
                $binvoice->customerPartyCityName = $rowClient['Ville']; #<cbc:CityName>
                $binvoice->customerPartyStreetName = utf8_encode($rowClient['Adresse']); #<cbc:StreetName
                $binvoice->customerPartyBuildingNumber = $building_number; #<cbc:BuildingNumber>
                $binvoice->customerPartyZip = $rowClient['CP']; #<cbc:PostalZone>
                $binvoice->customercompanyID =   $rowClient['TVA']; # <cac:PartyTaxScheme><cbc:CompanyID
                $binvoice->customercompanySchemeID = $rowClient['Pays'] . "VAT"; # <cac:PartyTaxScheme><cbc:CompanyID > attribute SchemeID 
                $binvoice->customerPartyCountryCode =   $rowClient['Pays']; #<cbc:IdentificationCode
                $binvoice->customerPartyCountryCodeAttr =  $countryCodeAttr; #<cbc:IdentificationCode> attr
                $binvoice->customertaxSchemeID =  'VAT';
                $binvoice->customertaxSchemeAttrID['schemeID']  = $taxSchemeID; 
                $binvoice->legEntityCompanyID = $rowClient['Societe'] ; 
                $binvoice->legEntityRegName =  $rowClient['Societe']  ;
                 



             /*   $binvoice->contactEmail =  $rowClient['Email']; #<cbc:ElectronicMail>
                $phoneNo = ( $rowClient['Phone'] !="")?$rowClient['Phone']:$rowClient['GSM'];
                $binvoice->contactPhone = $phoneNo; #<cbc:Telephone>
                */

                /* PaymentMeans Node  -<cac:PaymentMeans> */
                $binvoice->paymentMeansCode = "1"; #<cbc:PaymentMeansCode>
             //$binvoice->paymentMeansDueDate = $row["DtEcheance"]; #<cbc:PaymentDueDate>
                $binvoice->paymentMeansFASchemaId = ""; #<cac:PayeeFinancialAccount> <cbc:ID schemeID="IBAN">
                $binvoice->paymentMeansFASchemaVal = "";#<cac:PayeeFinancialAccount> <cbc:ID> val
                /* Tax Node */

              
                $binvoice->taxAmount = (float)$row["TotalTTC"]  ; #<cbc:TaxAmount
                $binvoice->taxTAmount = (float)$row["TotalTTC"] -  (float)$row["TotalHT"]; // (float)$row["TotalTTC"]; #<cac:TaxSubtotal><cbc:TaxableAmount
                $binvoice->taxTableAmount  = (float)$row["TotalHT"] ; //(float)$row["TotalTTC"] ; #<cbc:TaxableAmount 


                $binvoice->taxCurrencyCode = $currencyCode; #<cbc:TaxableAmount> attribute currencyID
                $binvoice->taxTCatId = "S"; #<cac:TaxCategory> <cbc:ID>
                $binvoice->taxTCatIdAttr['schemeAgencyID'] = 'BE'; #<cac:TaxCategory> <cbc:ID> attribute schemeAgencyID
                $binvoice->taxTCatIdAttr['schemeID'] = $taxCatSchemeID; #<cac:TaxCategory> <cbc:ID> attribute schemeID
               // $binvoice->taxTCatName = "VAT";
                $binvoice->taxTCatPercent = $row["TauxTVA"];  #<cbc:Percent>
                $binvoice->taxTCatSchemeID = "VAT"; #<cac:TaxScheme>
//$binvoice->taxTCatSchemeVal = "4564564";
                $binvoice->taxTCatSchemeIDAttr['schemeAgencyID'] = $currencyAttrAgencyID;
                $binvoice->taxTCatSchemeIDAttr['schemeID'] = $taxSchemeID;
                /*    */
#==================================

                $sqlDtl = "SELECT *  FROM `sysdetails`  WHERE RefDoc=" . $row["RefDoc"] . " ";
                $resultDtl = $conn->query($sqlDtl);
                $rowDtlArr = [];

                while ($rowDtldup = $resultDtl->fetch_assoc()) {
                    # Converting each column to UTF8
                    $rowDtl = array_map('utf8_encode', $rowDtldup); // for special char error
                    array_push($rowDtlArr, $rowDtl);
                }

            
 
                if ($resultDtl->num_rows > 0) {
                	$lineExtensionAmt = 0;
                	$inLineExtAmount =0;
                	$sumTauxTVA =0;
                	$prePayableAmt =0;
                    for ($i = 0; $i < count($rowDtlArr); $i++) {
                        $binvoice->inLineId[]       = $rowDtlArr[$i]['RefDetail'];
                        $binvoice->inLineQuantity[] =  $rowDtlArr[$i]['Quantite'];
                         $binvoice->inLineQuantityAttr[$i]['unitCode'] =  'C62';
                        $binvoice->inLineExtAmount[]= $rowDtlArr[$i]['PUnitaire'];
                        $binvoice->inLineItemName[] = $rowDtlArr[$i]['CodeArt'];
                        $binvoice->inLineItemDesc[] =  utf8_encode($rowDtlArr[$i]['Description']);
                          // $conn->real_escape_string($rowDtlArr[$i]['Description']);                


                        /* Invoice Inline >item  Node */
                        $in_percent = (isset($rowDtlArr[$i]['TauxTVA']) != "") ? $rowDtlArr[$i]['TauxTVA'] : 0;
                        $taxCatName = "VAT"; //static val
                        $binvoice->itemTaxCatID[] = "S"; // static val
                        $binvoice->itemTaxCatIdAttr[$i]['schemeAgencyID'] = $currencyAttrAgencyID;;
                        $binvoice->itemTaxCatIdAttr[$i]['schemeID'] =   $taxCatSchemeID;

                    //  $binvoice->itemTaxCatName[] =  $taxCatName ;


                      
                        $binvoice->itemTaxCatPercent[] =  $in_percent ;  
                        $binvoice->itemTaxCatSchemeID[] = $taxSchemeID;

                        $binvoice->itemTaxCatSchemeIdAttr[$i]['schemeAgencyID'] = $currencyAttrAgencyID;
                        $binvoice->itemTaxCatSchemeIdAttr[$i]['schemeID'] = $taxSchemeID;
                        $binvoice->priceAmount[] =  (float)($rowDtlArr[$i]['PUAchat'] );
		                $binvoice->baseQuantity[] = $rowDtlArr[$i]['Quantite'];
		                $binvoice->unitCode[] = "";
		                /* Sum of each values*/
		                $lineExtensionAmt +=  $rowDtlArr[$i]['PUnitaire'];
                        $inLineExtAmount +=  $rowDtlArr[$i]['Reduc'];
		                $sumTauxTVA  +=   $rowDtlArr[$i]['TauxTVA'];

                    }

                $binvoice->LegalMonetaryExtAmount = $lineExtensionAmt; #<cbc:LineExtensionAmount
                $binvoice->LegalMonetaryTaxExcAmount = $lineExtensionAmt -  $inLineExtAmount; #<cbc:TaxExclusiveAmount>
                $binvoice->LegalMonetaryTaxIncAmount = $lineExtensionAmt + $sumTauxTVA; #<cbc:TaxInclusiveAmount
                $binvoice->LegalMonetaryPrePaidAmt = $prePayableAmt;     #<cbc:PrePaidAmount      
                $binvoice->LegalMonetaryPayableAmt =   ( $lineExtensionAmt + $sumTauxTVA) - $prePayableAmt; #<cbc:PayableAmount 
                    // $binvoice->LegalMonetaryPayableAmt = 0;
                    // $binvoice->LegalMonetaryAllowanceTotalAmt = 0;
                }            
             
                $binvoice->generateXml($row['RefDoc']);
            }
       // }
    }
}