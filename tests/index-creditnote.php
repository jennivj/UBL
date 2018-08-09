<?php
require_once "../vendor/autoload.php";
require_once "creditNoteClass.php";

require_once "db-connection.php";


 

 //======================================================
 

   $sqlDoc = "SELECT *  FROM `sysdocuments`   WHERE  TypeDoc ='INV' AND TotalHT<0  limit 0, 1";
$resultDoc = $conn->query($sqlDoc);


if ($resultDoc->num_rows > 0) {
    // output data of each row
    while ($row = $resultDoc->fetch_assoc()) {

      //  if ( $row["TauxTVA"] >= 0) {

 
            $sqlclient = " SELECT * FROM `sysclients`   WHERE RefClient=" . $row["RefClient"] . " ";
            $resultClient = $conn->query($sqlclient);
            while ($rowClient = $resultClient->fetch_assoc()) {
                $cust_name = ($rowClient['NomClient'] != "") ? $rowClient['NomClient'] : $rowClient['Societe'];
                $in_amnt = ($row["TotalTTC"] != "") ? $row["TotalTTC"] : 0;

                # query for currency attributes list id

                $sqlList = "SELECT RefListe    FROM `syslistes`  where Groupe= 'Devise' AND TxtOpt='" . $row["Devise"] . "' ";
                $resultList = $conn->query($sqlList);

                $rowList = $resultList->fetch_assoc();
                $currencyAttrAgencyID = $rowList['RefListe'];
                $currencyAttrListID = 'ISO 4217 Alpha-3';  //ISO 4217 is the International std for currency codes
                $countryCodeAttr = 'ISO3166-1';  //ISO 3166 is the Intl Standard for country codes and codes
                $taxSchemeID = "UN/ECE 5153"; //Vat Tax Code ;  for Tax codes, such as "VAT".
                $taxCatSchemeID = "UN/ECE 5305";
                $currencyCode =  ($row["Devise"] != "")?$row["Devise"]:'EUR'; // EUR
 
                $binvoice = new creditNoteClass;;
                $binvoice->customizationID = strtotime($row["DtEmis"]);
                $binvoice->ID = $row['RefDoc'];
                $binvoice->ProfileID  = $row['RefDoc'];
                $binvoice->issueDate = $row["DtEmis"];// <cbc:IssueDate>  ;
                $binvoice->DueDate = $row["DtEmis"];// <cbc:IssueDate> '2018-07-03';
                $binvoice->InvoiceTypeCode = 381;
            
                $binvoice->note = $row["Notes"];// 'desc3';


                $binvoice->currencyAttrListID = $currencyAttrListID; //ISO 4217 is the International Standard for currency codes
                $binvoice->currencyAttrAgencyID = $currencyAttrAgencyID;
                $binvoice->docCurrencyCode = $currencyCode;
             //   $binvoice->invoiceStartDate = $row["DtEmis"];
              //  $binvoice->invoiceEndDate = $row["DtEcheance"];
                /* Supplier Node */
                /* iterating building number from address */
                $address_str = $rowClient['Adresse'] . ' , ' . $rowClient['Adresse2'];
                preg_match_all('!\d+!', $address_str, $building_number);
                $building_number = isset($building_number) ? $building_number : '';

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
                $binvoice->leRegName =  '=' .getsysVarValues('NomSociete') ; // Need to clarify
                $binvoice->leComId =  ' =' .getsysVarValues('NomSociete') ; // Need to clarify          
                /* customer */

                $binvoice->customerPartyName = $cust_name;
                $binvoice->customerPartyCityName = $rowClient['Ville'];
                $binvoice->customerPartyStreetName = $rowClient['Adresse'];
                $binvoice->customerPartyBuildingNumber = $building_number;
                $binvoice->customerPartyZip = $rowClient['CP'];

                $binvoice->customercompanyID = $rowClient['TVA'];
                $binvoice->customercompanySchemeID = $rowClient['Pays'] . "VAT";
                $binvoice->customerPartyCountryCode = $rowClient['Pays'];
                $binvoice->customerPartyCountryCodeAttr = $rowClient['Pays'];
                $binvoice->customertaxSchemeID = $taxSchemeID;
                $binvoice->customertaxSchemeAttrID = $rowClient['TVA'];
                $binvoice->contactEmail =  $rowClient['Email'];
                $phoneNo = ( $rowClient['Phone'] !="")?$rowClient['Phone']:$rowClient['GSM'];
                $binvoice->contactPhone = $phoneNo;
 
                $binvoice->legEntityCompanyID =   '=' . $rowClient['Societe'] ; 
                $binvoice->legEntityRegName =    '=' . $rowClient['Societe']  ;

                
/* Billing Reference Node */
$binvoice->InvoiceDocRefID =   $row["RefDoc"];
  $sqlAddDocRef = " SELECT * FROM `sysgrandlivre`   WHERE  Montant < 0 AND RefDoc=" . $row["RefDoc"] . " ";
            $resultAddDocRef = $conn->query($sqlAddDocRef);
            while ($rowAddDocRef = $resultAddDocRef->fetch_assoc()) {
            	//----------------
            	$binvoice->AdditionalDocRefID[] =  $rowAddDocRef['RefMove'];
				$binvoice->AdditionalDoctType[] =  $rowAddDocRef['TypeMove'];
				$binvoice->AdditionalDocAttachment[] = '';
		    	$binvoice->AdditionalDocMimeCode[] = 'mime/pdf';
			    $binvoice->AdditionalDocBinaryObject[] = 'obj';
				$binvoice->AdditionalDoc[] = 'Test ';
                //-------------------

            	}
/*

   $sqlDocAdd = "SELECT *  FROM `sysdocuments`   WHERE  TypeDoc ='INV' AND TotalHT>0 AND DtPaye != 0000-00-00";
   $resultDocAdd = $conn->query( $sqlDocAdd );

if ($resultDocAdd->num_rows > 0) {
   
    while ($rowAdd = $resultDocAdd->fetch_assoc()) {
        //----------------
                $binvoice->AdditionalDocRefID[] = "JENN".  $rowAdd['RefDoc'];
                $binvoice->AdditionalDoctType[] =  $rowAdd['TypeDoc'];
                $binvoice->AdditionalDocAttachment[] = '';
                $binvoice->AdditionalDocMimeCode[] = 'mime/pdf';
                $binvoice->AdditionalDocBinaryObject[] = ' ';
                $binvoice->AdditionalDoc[] = $rowAdd['Notes'];
                //-------------------
}
}
*/
/*
$binvoice->AdditionalDocRefID[] = '456456';
$binvoice->AdditionalDoctType[] = ' credit note ';
$binvoice->AdditionalDocAttachment[] = 'Testcase19 credit note with invoice ref';
$binvoice->AdditionalDocMimeCode[] = 'mime/pdf';
$binvoice->AdditionalDocBinaryObject[] = 'obj';
$binvoice->AdditionalDoc[] = 'Testcase19 credit note with invoice ref';
//-------------------
$binvoice->AdditionalDocRefID[] = '456456';
$binvoice->AdditionalDoctType[] = ' credit note ';
$binvoice->AdditionalDocAttachment[] = 'Testcase19 credit note with invoice ref';
$binvoice->AdditionalDocMimeCode[] = 'mime/pdf';
$binvoice->AdditionalDocBinaryObject[] = 'obj';
$binvoice->AdditionalDoc[] = 'Testcase19 credit note with invoice ref';
*/
                /* PaymentMeans Node */
                $binvoice->paymentMeansCode = "1";
                $binvoice->paymentMeansCodeAttr['listID'] = '6';
                $binvoice->paymentMeansCodeAttr['listURI'] = 'UN/ECE 5305 listURI';
                $binvoice->paymentMeansCodeAttr['listName'] = 'listName';
                $binvoice->paymentMeansDueDate = $row["DtEcheance"];   #<cbc:PaymentDueDate>
                $binvoice->paymentMeansFASchemaId = "";
                $binvoice->paymentMeansFASchemaVal = "";
                /* Tax Node */
              

                $binvoice->taxAmount =  (float)$row["TotalTTC"]   ; #<cbc:TaxAmount
                $binvoice->taxTAmount =   (float)$row["TotalTTC"] -  (float)$row["TotalHT"]; // (float)$row["TotalTTC"]; #<cac:TaxSubtotal><cbc:TaxableAmount
                $binvoice->taxTableAmount  =  (float)$row["TotalHT"] ; //(float)$row["TotalTTC"] ; #<cbc:TaxableAmount 

                $binvoice->taxCurrencyCode = $currencyCode;
                $binvoice->taxTCatId = "S";
                $binvoice->taxTCatIdAttr['schemeAgencyID'] = $currencyAttrAgencyID;
                $binvoice->taxTCatIdAttr['schemeID'] = $taxCatSchemeID;
                $binvoice->taxTCatName = "VAT";
                $binvoice->taxTCatPercent = $row["TauxTVA"];
                $binvoice->taxTCatSchemeID = "VAT";
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
                        $binvoice->crdtLineId[]       = $rowDtlArr[$i]['RefDetail'];
                        $binvoice->crdtLineQuantity[] = $rowDtlArr[$i]['Quantite'];

                         $binvoice->crdtLineQuantityAttr[$i]['unitCodeListID'] = '' ;
                         $binvoice->crdtLineQuantityAttr[$i]['unitCode'] =  'C62';
 
                     
                             $binvoice->CLtaxTableAmount[]= $rowDtlArr[$i]['PUnitaire']   ;
                                     $binvoice->CLtaxTAmount[]= $rowDtlArr[$i]['TauxTVA'];
                                     //$rowDtlArr[$i]['PUnitaire'] * $rowDtlArr[$i]['TauxTVA'] /100 ;

                        $binvoice->crdtLineExtAmount[]= $rowDtlArr[$i]['Reduc'];
                        $binvoice->crdtLineItemName[] = $rowDtlArr[$i]['CodeArt'];
                        $binvoice->crdtLineItemDesc[] = $conn->real_escape_string($rowDtlArr[$i]['Description']);                
                         $binvoice->crdtLineSellerId[] = ""  ;

                        /* Invoice Inline >item  Node */
                        $in_percent = (isset($rowDtlArr[$i]['TauxTVA']) != "") ? $rowDtlArr[$i]['TauxTVA'] : 0;
                        $taxCatName = "VAT";
                        $binvoice->itemTaxCatID[] = "S";
                        $binvoice->itemTaxCatIdAttr[$i]['schemeAgencyID'] = $currencyAttrAgencyID;;
                        $binvoice->itemTaxCatIdAttr[$i]['schemeID'] =   $taxCatSchemeID;

                        $binvoice->itemTaxCatName[] =  $taxCatName ;
                        $binvoice->itemTaxCatPercent[] =  $in_percent;
                        $binvoice->itemTaxCatSchemeID[] = $taxSchemeID;

                        $binvoice->itemTaxCatSchemeIdAttr[$i]['schemeAgencyID'] = $currencyAttrAgencyID;
                        $binvoice->itemTaxCatSchemeIdAttr[$i]['schemeID'] = $taxSchemeID;
                        $binvoice->priceAmount[] = $rowDtlArr[$i]['PUAchat'];
		                $binvoice->baseQuantity[] = $rowDtlArr[$i]['Quantite'];
		                $binvoice->unitCode[] = "";
                          /* Sum of each values*/
                        $lineExtensionAmt +=  $rowDtlArr[$i]['PUnitaire'];
                        $inLineExtAmount +=  $rowDtlArr[$i]['Reduc'];
                        $sumTauxTVA  +=  $rowDtlArr[$i]['TauxTVA'];

                    }
                $binvoice->LegalMonetaryExtAmount = $lineExtensionAmt; #<cbc:LineExtensionAmount
                $binvoice->LegalMonetaryTaxExcAmount = $lineExtensionAmt -  $inLineExtAmount; #<cbc:TaxExclusiveAmount>
                $binvoice->LegalMonetaryTaxIncAmount = $lineExtensionAmt + $sumTauxTVA; #<cbc:TaxInclusiveAmount
                $binvoice->LegalMonetaryPrePaidAmt = $prePayableAmt;     #<cbc:PrePaidAmount      
                $binvoice->LegalMonetaryPayableAmt = ( $lineExtensionAmt + $sumTauxTVA) - $prePayableAmt; #<cbc:PayableAmount 
                }         
              
                $binvoice->generateXml();
            }
        }
   // }
}