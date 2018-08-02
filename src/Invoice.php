<?php
 
namespace CleverIt\UBL\Invoice;  

use Sabre\Xml\Service;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;
class Invoice  implements XmlSerializable{
  
    public $UBLVersionID = '2.1';   
    public $CurrencyID;
    public $ID;
    public $CustomizationID;
    public $IssueDate;
    public $DocumentCurrencyCode;
    public static $currencyID;

    public $invoiceLines;        
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
}


public function TaxCurrencyCode($currencyCode){
//$this->TaxCurrencyCode =  $currencyCode; 
 self::$currencyID = $currencyCode; 
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
     * @return InvoiceLine[]
     */
    public function getInvoiceLines() {
        return $this->invoiceLines;
    }

    /**
     * @param InvoiceLine[] $invoiceLines
     * @return Invoice
     */
    public function setInvoiceLines($invoiceLines) {
        $this->invoiceLines = $invoiceLines;
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

     
          $invoice =    [

             $cbc . 'UBLVersionID' => $this->UBLVersionID,
             $cbc . 'CustomizationID' => $this->CustomizationID,
             $cbc . 'ID' => $this->ID,
             $cbc . 'IssueDate' =>$this->IssueDate,
              [
               'name' =>   $cbc  . 'DocumentCurrencyCode', 'value' => $this->DocumentCurrencyCode,
             //  ['listID'=>  $this->currencyAttrListID ,  'listAgencyID' => $this->currencyAttrAgencyID ];
               'attributes' => ['listID'=> $this->CurrencyCodeAttr['listID'] , 'listAgencyID'=> $this->CurrencyCodeAttr['listAgencyID']],
               ],
         
             $cac . 'InvoicePeriod' => [  $cbc . 'StartDate' =>  $this->InvoicePeriodSubNode_StartDate,
                                          $cbc . 'EndDate'   =>  $this->InvoicePeriodSubNode_EndDate ],
             $cac . 'AccountingSupplierParty' => [$cac . "Party" =>  $this->accountingSupplierParty],

             $cac . 'AccountingCustomerParty' => [$cac . "Party" =>  $this->accountingCustomerParty],


 
             $cac.'PaymentMeans'   => $this->paymentMeans  ,  
             $cac . 'TaxTotal' => $this->taxTotal  ,   
          
            
             $cac . 'LegalMonetaryTotal' => $this->legalMonetaryTotal  , 
            //   $cac . 'InvoiceLine' => $this->invoiceLines  ,  

               
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

         foreach ($this->invoiceLines as $invoiceLine) {
            $writer->write([
                    $cac . 'InvoiceLine' => $invoiceLine
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