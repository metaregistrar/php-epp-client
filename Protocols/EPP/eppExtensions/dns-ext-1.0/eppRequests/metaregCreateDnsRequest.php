<?php
namespace Metaregistrar\EPP;

/**
 * Class metaregCreateDnsRequest
 * @package Metaregistrar\EPP
 *
 * format of dns records: array with keys type, name, content, ttl, priority
 * $records[] = ['type'=>'','name'=>'', 'content=>'', 'ttl'=>'', 'priority=>''];
 */
class metaregCreateDnsRequest extends metaregDnsRequest {
    /**
     * @var array
     */
    private $records = [];
    /**
     * EppCreateDnsRequest constructor.
     *
     * @param eppDomain $domain
     * @param array     $records
     * @throws eppException
     *
     * format of dns records: array with keys type, name, content, ttl, priority
     */
    public function __construct(eppDomain $domain, array $records, bool $premium = false) {
        parent::__construct(eppRequest::TYPE_CREATE);
        if (!strlen($domain->getDomainname())) {
            throw new eppException('Domain object does not contain a valid domain name');
        }
        $dname = $this->createElement('dns-ext:name', $domain->getDomainname());
        $this->dnsObject->appendChild($dname);
        if ($premium) {
            $dpremium = $this->createElement('dns-ext:premium','true');
            $this->dnsObject->appendChild($dpremium);
        }

        $this->records = $records;
        foreach ($records as $record) {
            $recordElem = $this->createElement('dns-ext:content');
            $recordElem->appendChild($this->createElement('dns-ext:name', $record['name']));
            $recordElem->appendChild($this->createElement('dns-ext:type', $record['type']));
            $recordElem->appendChild($this->createElement('dns-ext:ttl', $record['ttl']));
            $recordElem->appendChild($this->createElement('dns-ext:content', $record['content']));
            if (isset($record['priority'])) {
                $recordElem->appendChild($this->createElement('dns-ext:priority', $record['priority']));
            }
            $this->dnsObject->appendChild($recordElem);
        }
        $this->addSessionId();
    }

    /**
     * @return array
     */
    public function getRecords() {
        return $this->records;
    }

    /**
     * @param array $records
     * @return metaregCreateDnsRequest
     */
    public function setRecords(array $records) {
        $this->records = $records;
        return $this;
    }

}
