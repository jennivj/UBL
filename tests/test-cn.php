<?php
require_once "../vendor/autoload.php";
//$invoice = new \CleverIt\UBL\Invoice\Invoice();

 

$service = new Sabre\Xml\Service();
$service->namespaceMap = [
    'urn:oasis:names:specification:ubl:schema:xsd:Invoice-2' => '',
    'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2' => 'cbc',
    'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2' => 'cac'
];

   $entry = new \CleverIt\UBL\Invoice\Invoice(); 
   $entry->UBLVersionID =  $entry->UBLVersionID;
   $entry->CustomizationID =  'u';
   $entry->ID =  $entry->setId(2018112038);
   $entry->IssueDate = '2018-07-03';
   $currencyAttrArray = ['listID'=> "ISO 4217 Alpha" ,  'listAgencyID' => '555' ];
   $entry->DocumentCurrencyCode('EUR' , $currencyAttrArray); 


   $entry->InvoicePeriod('2018-06-01','2018-08-01'); // start and end date


$accountingSupplierParty = new \CleverIt\UBL\Invoice\Party();
$accountingSupplierParty->setName('dddddddddddddddd');
$supplierAddress = (new \CleverIt\UBL\Invoice\Address())
    ->setCityName("Eindhoven")

    ->setStreetName("Keizersgracht")
    ->setBuildingNumber("15")
    ->setPostalZone("5600 AC")
    ->setCountry((new \CleverIt\UBL\Invoice\Country())->setIdentificationCode("NL"));

$accountingSupplierParty->setPostalAddress($supplierAddress);
$accountingSupplierParty->setTaxScheme(( new \CleverIt\UBL\Invoice\TaxScheme() )
                                       ->setId('kkkk')
                                       ->setSchemeId('jjjjj'));
$accountingSupplierParty->setCompanyId('jenn');
$accountingSupplierParty->setLegalEntity('jenn');
$entry->setAccountingSupplierParty($accountingSupplierParty);
$entry->setAccountingCustomerParty($accountingSupplierParty);
$paymentMeans = (new \CleverIt\UBL\Invoice\PaymentMeans())
    ->setPaymentMeansCode('30')
    ->setPaymentDueDate('31-03-2018') 
     ->setFinancialAccount((new \CleverIt\UBL\Invoice\PayeeFinancialAccount())     
        ->setSchema('IBAN' ,'444444444444'));
   


$taxtotal = (new \CleverIt\UBL\Invoice\TaxTotal())

    ->setTaxAmount(30)
    ->addTaxSubTotal((new \CleverIt\UBL\Invoice\TaxSubTotal())
        ->setTaxAmount(21)
        ->setTaxableAmount(100)
        ->setTaxCategory((new \CleverIt\UBL\Invoice\TaxCategory())
            ->setId("H")
            ->setName("NL, Hoog Tarief")
            ->setPercent(21.00)))
    ->addTaxSubTotal((new \CleverIt\UBL\Invoice\TaxSubTotal())
            ->setTaxAmount(9)
            ->setTaxableAmount(100)
            ->setTaxCategory((new \CleverIt\UBL\Invoice\TaxCategory())
            ->setId("X")
            ->setName("NL, Laag Tarief")
            ->setPercent(9.00) 
            ->setTaxScheme(( new \CleverIt\UBL\Invoice\TaxScheme() )
                                       ->setId('kkkk')
                                       ->setSchemeId('jjjjj'))
        ) );
          /*  ->setTaxScheme(( new \CleverIt\UBL\Invoice\TaxScheme() )
                                       ->setId('kkkk')
                                       ->setSchemeId('jjjjj')));
     */


    $entry->TaxCurrencyCode('ERND') ;
    $entry->setpaymentMeans($paymentMeans);
    $entry->setTaxTotal($taxtotal);

$invoiceLine = (new \CleverIt\UBL\Invoice\InvoiceLine())
    ->setId(1)
    ->setInvoicedQuantity(1)
    ->setLineExtensionAmount(100)
    ->setTaxTotal($taxtotal)
    ->setItem((new \CleverIt\UBL\Invoice\Item())->setName("Test item")->setDescription("test item description")->setSellersItemIdentification("1ABCD"));

$entry->setInvoiceLines([$invoiceLine]);

$entry->setLegalMonetaryTotal((new \CleverIt\UBL\Invoice\LegalMonetaryTotal())
          ->setLineExtensionAmount(100)
          ->setTaxExclusiveAmount(100)
          ->setPayableAmount(-1000)
          ->setAllowanceTotalAmount(50));
 
echo "=============";
print_R($entry);

 //$invoice->xmlSerialize();
file_put_contents("ubl_invoice.xml",  $service->write('Invoice' , $entry) );

/*

class InvoiceEntry implements Sabre\Xml\XmlSerializable {

    public $title;
    public $link;
    public $id;vendor
    public $updated;
    public $summary;




    function xmlSerialize(Sabre\Xml\Writer $writer) {
        $cbc = '{urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2}';
        $cac = '{urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2}';

 
          $invoice =    [$cbc  . 'title' => $this->title,
            [
               'name' =>   $cbc  . 'link',
               'attributes' => ['href' => $this->link]
            ],
              $cbc  . 'updated' => $this->updated,
              $cbc . 'id' => 'urn:uuid:1225c695-cfb8-4ebb-aaaa-80da344efa6a',
              $cbc  . 'summary' => 'Some text asdasd asdasd .'
        ];

        $writer->write(  $invoice);

    }

}
*/











exit(); 