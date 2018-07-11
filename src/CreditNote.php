<?php
 
namespace CleverIt\UBL\Invoice;  

use Sabre\Xml\Service;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;
class CreditNote  implements XmlSerializable{

    public $title;
    public $link;
    public $id;
    public $updated;
    public $summary;




    function xmlSerialize(Writer $writer) {
        $cbc = '{urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2}';
        $cac = '{urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2}';

 
          $invoice =    [$cbc  . 'title' => $this->title,
            [
               'name' =>   $cbc  . 'link', 'value' =>'qwqweqw',
               'attributes' => ['href' => $this->link , 'test' =>' valtest']
            ],
              $cbc  . 'updated' => $this->updated,
              $cbc . 'id' => 'urn:uuid:1225c695-cfb8-4ebb-aaaa-80da344efa6a',
              $cbc  . 'summary' =>  $this->summary,
        ];

        $writer->write(  $invoice);

    }

    

}

 




echo "CreditNote";




 