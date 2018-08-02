<?php
 
namespace CleverIt\UBL\Invoice;  

use Sabre\Xml\Service;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;
class CreditNote  implements XmlSerializable{
  
    public $UBLVersionID = '2.1';   
 
    public $ID;
    public $CustomizationID;
    public $ProfileID;
    public $IssueDate;
    public $note;
    public $DocumentCurrencyCode;
    public static $currencyID;
    public   $InvoiceDocRefID;
    public $AdditionalDocRefs;



    public $creditLines;        
    public $paymentMeans;
    public $CurrencyCodeAttr;
    public $InvoicePeriodSubNode_StartDate;
    public $InvoicePeriodSubNode_EndDate;
    public  $accountingSupplierParty ;
    public $accountingCustomerParty;
    public $taxTotal;
    public $legalMonetaryTotal;


public function  InvoicePeriod($s, $e){
  $this->InvoicePeriodSubNode_StartDate = $s;
  $this->InvoicePeriodSubNode_EndDate = $e;
  return    $this;
}

public function DocumentCurrencyCode($currency, $attr){
  $this->DocumentCurrencyCode =  $currency;
  $this->CurrencyCodeAttr = $attr ;
    return    $this;
}

      /**
        * @return TaxCurrencyCode
       */
public function TaxCurrencyCode($currencyCode){
 

 self::$currencyID = $currencyCode; 
 //print_r(  self::$currencyID ) ;

}

        /**
        * @return  inviceDocRefID
       */
public function setInviceDocRefID($id){
  $this->InvoiceDocRefID =  $id; 
    return    $this;
}
     /**
        * @return  inviceDocRefID
       */
public function  setAdditionalInvoiceDocRef($argArray){
 
  $this->AdditionalDocRefs   =  $argArray;
 return    $this;
}

        /**
        * @return LegalMonetaryTotal
       */
     public function getLegalMonetaryTotal() {
         return $this->legalMonetaryTotal;
       }
  
      /**
       * @param LegalMonetaryTotal $legalMonetaryTotal
       * @return Invoice
       */
      public function setLegalMonetaryTotal($legalMonetaryTotal) {
          $this->legalMonetaryTotal = $legalMonetaryTotal;
           return $this;
      }

          /**
       * @param  paymentMeans $paymentMeans
       * @return Invoice
       */
      public function setpaymentMeans($paymentMeans) {
          $this->paymentMeans = $paymentMeans;
           return $this;
      }

 /**
     * @param Party $accountingSupplierParty
     * @return Invoice
     */
    public function setAccountingSupplierParty($accountingSupplierParty) {
        $this->accountingSupplierParty = $accountingSupplierParty;
        return $this;
    }

     /**
     * @param Party $accountingSupplierParty
     * @return Invoice
     */
    public function setAccountingCustomerParty($accountingCustomerParty) {
        $this->accountingCustomerParty = $accountingCustomerParty;
        return $this;
    }

    /**
     * @return creditLine[]
     */
    public function getcreditLines() {
        return $this->creditLines;
    }

    /**
     * @param creditLine[] $creditLines
     * @return Invoice
     */
    public function setcreditLines($creditLines) {
        $this->creditLines = $creditLines;
        return $this;
    }
/* */
   
public function taxTotal(){
  $res= [
            Schema::CAC . 'TaxTotal' => $this->taxTotal
        ];
          return  $res;
        }

 /**
     * @param TaxTotal $taxTotal
     * @return Invoice
     */
    public function setTaxTotal($taxTotal) {
        $this->taxTotal = $taxTotal;
        return $this;
    }



    function xmlSerialize(Writer $writer) {
        $cbc = '{urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2}';
        $cac = '{urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2}';

         $attrArrayCur =[];
    if(isset( $this->CurrencyCodeAttr['listID'] )){
      $attrArrayCur['listID'] = $this->CurrencyCodeAttr['listID'] ;
    }
        if(isset($this->CurrencyCodeAttr['listAgencyID'])){
      $attrArrayCur['listAgencyID'] = $this->CurrencyCodeAttr['listAgencyID'];
    }
   $currencyAttrArray =$attrArrayCur;
          $invoice =    [

             $cbc . 'UBLVersionID' => $this->UBLVersionID,
             $cbc . 'CustomizationID' => $this->CustomizationID,
             $cbc . 'ProfileID' =>  $this->ProfileID,
             $cbc . 'ID' => $this->ID,
             $cbc . 'IssueDate' =>$this->IssueDate,
             $cbc . 'Note' =>$this->note,
              [
               'name' =>   $cbc  . 'DocumentCurrencyCode', 'value' => $this->DocumentCurrencyCode,
             //  ['listID'=>  $this->currencyAttrListID ,  'listAgencyID' => $this->currencyAttrAgencyID ];
               'attributes' =>   $currencyAttrArray,
               ],
             ];
             $writer->write(  $invoice);

            
/* BillingReference node write */

         $billingRef =  [ $cac .'BillingReference' =>[
                 $cac.'InvoiceDocumentReference' => [  $cac.'ID' => $this->InvoiceDocRefID],                

               ],
             ];

       
               $addDocReferenceArr = [];

          foreach ($this->AdditionalDocRefs as $addn) { 
            $addDocReferenceArr[] = [  $cac . 'AdditionalDocumentReference' => $addn ];
          
        }
          array_push(  $billingRef[$cac .'BillingReference']  ,   $addDocReferenceArr );
 
  $writer->write(  $billingRef);
               /*
            
             $cac . 'InvoicePeriod' => [  $cbc . 'StartDate' =>  $this->InvoicePeriodSubNode_StartDate,
                                          $cbc . 'EndDate'   =>  $this->InvoicePeriodSubNode_EndDate ],
   */
              $invoice =  [   $cac . 'AccountingSupplierParty' => [$cac . "Party" =>  $this->accountingSupplierParty],

             $cac . 'AccountingCustomerParty' => [$cac . "Party" =>  $this->accountingCustomerParty],


 
             $cac.'PaymentMeans'   => $this->paymentMeans  ,  
             $cac . 'TaxTotal' => $this->taxTotal  ,   
          
            
             $cac . 'LegalMonetaryTotal' => $this->legalMonetaryTotal  , 
            //   $cac . 'creditLine' => $this->creditLines  ,  

               
                       //  $cac . 'AccountingSupplierParty' => [$cac . "Party" =>  $this->accountingSupplierParty],                      
               /* $cbc . 'UBLVersionID' => '',   
                 $cbc . 'UBLVersionID' => '',  
                  $cbc . 'UBLVersionID' => '',  
                  */
             //-----------------------
         /*   $cbc  . 'title' => $this->title,
            [
               'name' =>   $cbc  . 'link',
               'attributes' => ['href' => $this->link]
            ],
              $cbc  . 'updated' => $this->updated,
             // $cbc . 'id' => 'urn:uuid:1225c695-cfb8-4ebb-aaaa-80da344efa6a',
              $cbc  . 'summary' =>  $this->summary,
              */
        ];

        $writer->write(  $invoice);

      

         foreach ($this->creditLines as $creditLine) {
            $writer->write([
                    $cac . 'creditLine' => $creditLine
            ]);
        }
      /*    */


    }

  
    /**
     * @param int $id
     * @return Invoice
     */
    public function setId($id) {
        $this->ID = $id;
        return     $this->ID;
    }

}