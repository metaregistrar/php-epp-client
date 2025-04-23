<?php
namespace Metaregistrar\EPP;

class sidnEppCancelDeleteRequest extends eppRequest {
    private array $units = [
        'y' => [1],
        'm' => [1,3,12]
    ];

    function __construct($domain, string $unit, int $period) {
        if( ! isset( $this->units[$unit] ) ) {
            throw new sidnEppException('invalid unit ' . $unit . ' specified' );
        }

        if( ! in_array( $period, $this->units[$unit] ) ) {
            throw new sidnEppException('invalid period ' . $period . ' for unit ' . $unit . ' specified' );
        }

        parent::__construct();

        $this->setNamespacesinroot( false );

        if ($domain instanceof eppDomain) {
            $this->addSidnExtension( $domain, $unit, $period );
        }
    }

    private function addSidnExtension(eppDomain $domain, $unit, $period) {
        $extension = $this->createElement('extension');

        $sidncommand = $this->createElement('sidn-ext-epp:command');
        $sidncommand->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
        $sidncommand->setAttribute("xmlns:sidn-ext-epp", "http://rxsd.domain-registry.nl/sidn-ext-epp-1.0");

        $cancelDelete = $this->createElement('sidn-ext-epp:domainCancelDelete');

        $cancelDelete->appendChild( $this->createElement('sidn-ext-epp:name', $domain->getDomainname() ) );

        $periodElement = $this->createElement('sidn-ext-epp:period', $period );
        $periodElement->setAttribute('unit', $unit );
        $cancelDelete->appendChild( $periodElement );

        $sidncommand->appendChild($cancelDelete);

        $sidncommand->appendChild( $this->appendChild($this->createElement('sidn-ext-epp:clTRID', $this->sessionid) ) );

        $extension->appendChild( $sidncommand);

        $this->getEpp()->appendChild($extension);
        $this->getEpp()->setAttribute("xmlns:xsi","http://www.w3.org/2001/XMLSchema-instance" );
    }

}
