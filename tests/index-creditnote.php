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

        if ( $row["TauxTVA"] >= 0) {


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
                $currencyAttrListID = 'ISO 4217 Alpha';  //ISO 4217 is the International std for currency codes
                $countryCodeAttr = 'ISO3166-1';  //ISO 3166 is the Intl Standard for country codes and codes
                $taxSchemeID = "UN/ECE 5153"; //Vat Tax Code ;  for Tax codes, such as "VAT".
                $taxCatSchemeID = "UN/ECE 5305";
                $currencyCode = $row["Devise"]; // EUR
 
                $binvoice = new creditNoteClass;;
                $binvoice->customizationID = strtotime($row["DtEmis"]);
                 $binvoice->ID = $row['RefDoc'];
                $binvoice->ProfileID  = $row['RefDoc'];
                $binvoice->issueDate = $row["DtEmis"];// '2018-07-03';
                      $binvoice->note = $row["des"];// 'desc3';


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


                $binvoice->supplierPartyName = $row["Societe"];
                $binvoice->supplierPartyCityName = $rowClient['Ville'];
                $binvoice->supplierPartyStreetName = $rowClient['Adresse'];
                $binvoice->supplierPartyBuildingNumber = $building_number;
                $binvoice->supplierPartyZip = $rowClient['CP'];

                $binvoice->companyID = $rowClient['TVA'];
                $binvoice->companySchemeID = $rowClient['Pays'] . "VAT";
                $binvoice->supplierPartyCountryCode = $rowClient['Pays'];
                $binvoice->supplierPartyCountryCodeAttr = $countryCodeAttr;
                $binvoice->taxSchemeID = "VAT";
                $binvoice->taxSchemeAttrID = $rowClient['TVA'];             
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
                
/* Billing Reference Node */

$binvoice->InvoiceDocRefID = '456456';
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

                /* PaymentMeans Node */
                $binvoice->paymentMeansCode = "1";
                $binvoice->paymentMeansCodeAttr['listID'] = '6';
                $binvoice->paymentMeansCodeAttr['listURI'] = 'UN/ECE 5305 listURI';
                $binvoice->paymentMeansCodeAttr['listName'] = 'listName';
                $binvoice->paymentMeansDueDate = $row["DtEcheance"];
                $binvoice->paymentMeansFASchemaId = "";
                $binvoice->paymentMeansFASchemaVal = "";
                /* Tax Node */
                $binvoice->taxAmount = (float)$row["TotalTTC"];
                $binvoice->taxTAmount = (float)$row["TotalTTC"];
                $binvoice->taxTableAmount = (float)$row["TotalHT"];


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
                    for ($i = 0; $i < count($rowDtlArr); $i++) {
                        $binvoice->crdtLineId[]       = $rowDtlArr[$i]['RefDetail'];
                        $binvoice->crdtLineQuantity[] = $rowDtlArr[$i]['Quantite'];

                         $binvoice->crdtLineQuantityAttr[$i]['unitCodeListID'] = '68w' . $i;
                         $binvoice->crdtLineQuantityAttr[$i]['unitCode'] = 'UN/ECE 530wwwww ' . $i;

                        $binvoice->crdtLineExtAmount[]= $rowDtlArr[$i]['Reduc'];
                        $binvoice->crdtLineItemName[] = $rowDtlArr[$i]['CodeArt'];
                        $binvoice->crdtLineItemDesc[] = $conn->real_escape_string($rowDtlArr[$i]['Description']);                
  //$binvoice->crdtLineSellerId[] = "N07" . $i;

                        /* Invoice Inline >item  Node */
                        $in_percent = (isset($rowDtlArr['TauxTVA']) != "") ? $rowDtlArr['TauxTVA'] : 0;
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

                    }
                }


                $binvoice->LegalMonetaryExtAmount = 68;
                $binvoice->LegalMonetaryTaxExcAmount = 7;
                $binvoice->LegalMonetaryPayableAmt = 678;
                $binvoice->LegalMonetaryAllowanceTotalAmt = "576";
              

                $binvoice->generateXml();
            }
        }
    }
}