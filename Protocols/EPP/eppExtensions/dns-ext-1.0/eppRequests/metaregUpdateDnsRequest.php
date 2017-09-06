<?php
namespace Metaregistrar\EPP;

/**
 * Class metaregUpdateDnsRequest
 * @package Metaregistrar\EPP
 */
class metaregUpdateDnsRequest extends metaregDnsRequest {
    /**
     * @var array
     */
    private $addRecords;

    /**
     * @var array
     */
    private $remRecords;

    /**
     * EppCreateDnsRequest constructor.
     *
     * @param eppDomain    $domain
     * @param array        $addRecords
     * @param array        $remRecords
     * @param null|boolean $sign
     * @throws eppException
     */
    public function __construct(eppDomain $domain, $addRecords = null, $remRecords = null, $sign = null) {
        parent::__construct(eppRequest::TYPE_UPDATE);
        if (!strlen($domain->getDomainname())) {
            throw new eppException('Domain object does not contain a valid domain name');
        }
        $dname = $this->createElement('dns-ext:name', $domain->getDomainname());
        $this->dnsObject->appendChild($dname);
        if (!is_null($sign)) {
            $chg = $this->createElement('dns-ext:chg');
            $chg->appendChild($this->createElement('dns-ext:signed', ($sign ? 'true' : 'false')));
            $this->dnsObject->appendChild($chg);
        }
        $this->addRecords = $addRecords;
        $this->remRecords = $remRecords;
        if (!is_null($addRecords)) {
            $this->handleAdd($addRecords);
        }
        if (!is_null($remRecords)) {
            $this->handleRemove($remRecords);
        }
    }

    /**
     * @return array
     */
    public function getRemRecords()
    {
        return $this->remRecords;
    }

    /**
     * @return array
     */
    public function getAddRecords()
    {
        return $this->addRecords;
    }


    /**
     * @param array $addRecords
     * @return void
     */
    protected function handleAdd(array $addRecords) {
        if (count($addRecords) == 0) {
            return;
        }
        $add = $this->createElement('dns-ext:add');
        foreach ($addRecords as $record) {
            $recordElem = $this->createElement('dns-ext:content');
            $recordElem->appendChild($this->createElement('dns-ext:name', $record['name']));
            $recordElem->appendChild($this->createElement('dns-ext:type', $record['type']));
            $recordElem->appendChild($this->createElement('dns-ext:ttl', $record['ttl']));
            $recordElem->appendChild($this->createElement('dns-ext:content', $record['content']));
            if (isset($record['priority'])) {
                $recordElem->appendChild($this->createElement('dns-ext:priority', $record['priority']));
            }
            $add->appendChild($recordElem);
        }
        $this->dnsObject->appendChild($add);
    }

    /**
     * @param array $remRecords
     * @return void
     */
    protected function handleRemove(array $remRecords) {
        if (count($remRecords) == 0) {
            return;
        }
        $rem = $this->createElement('dns-ext:rem');
        foreach ($remRecords as $record) {
            $recordElem = $this->createElement('dns-ext:content');
            $recordElem->appendChild($this->createElement('dns-ext:name', $record['name']));
            $recordElem->appendChild($this->createElement('dns-ext:type', $record['type']));
            $recordElem->appendChild($this->createElement('dns-ext:ttl', $record['ttl']));
            $recordElem->appendChild($this->createElement('dns-ext:content', $record['content']));
            if ((isset($record['priority'])) && ($record['priority']!='')) {
                $recordElem->appendChild($this->createElement('dns-ext:priority', $record['priority']));
            }
            $rem->appendChild($recordElem);
        }
        $this->dnsObject->appendChild($rem);
    }
}
