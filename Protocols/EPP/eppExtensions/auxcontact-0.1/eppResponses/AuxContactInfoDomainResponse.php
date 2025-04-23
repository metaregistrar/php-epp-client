<?php

namespace Metaregistrar\EPP;

/**
 * Class AuxContactInfoDomainResponse
 * @package Metaregistrar\EPP
 *
 *
 * <extension>
 *  <auxcontact:infData xmlns:auxcontact="urn:ietf:params:xml:ns:auxcontact-0.1">
 *      <auxcontact:contact type="abuse">sh8013</auxcontact:contact>
 *      <auxcontact:contact type="dns-operator">ClientZ</auxcontact:contact>
 *  </auxcontact:infData>
 * </extension>
 */
class AuxContactInfoDomainResponse extends eppInfoDomainResponse
{
    /**
     * @var array Auxiliary contacts
     */
    private $auxContacts = [];

    /**
     * Get the auxiliary contacts
     *
     * @return array
     */
    public function getAuxContacts()
    {
        if (empty($this->auxContacts)) {
            $this->extractAuxContacts();
        }
        return $this->auxContacts;
    }

    /**
     * Extract the auxiliary contacts from the response
     */
    private function extractAuxContacts()
    {
        $xpath = $this->xPath();

        // Register the namespace
        $xpath->registerNamespace('auxcontact', 'urn:ietf:params:xml:ns:auxcontact-0.1');

        // Extract auxiliary contacts
        $contacts = $xpath->query('/epp:epp/epp:response/epp:extension/auxcontact:infData/auxcontact:contact');

        if ($contacts->length > 0) {
            foreach ($contacts as $contact) {
                $type = $contact->getAttribute('type');
                $id = $contact->nodeValue;

                $this->auxContacts[] = [
                    'type' => $type,
                    'id' => $id
                ];
            }
        }
    }

    /**
     * Get auxiliary contacts of a specific type
     *
     * @param string $type
     * @return array
     */
    public function getAuxContactsByType($type)
    {
        if (empty($this->auxContacts)) {
            $this->extractAuxContacts();
        }

        $result = [];

        foreach ($this->auxContacts as $contact) {
            if ($contact['type'] === $type) {
                $result[] = $contact['id'];
            }
        }

        return $result;
    }

    /**
     * Get the abuse contact ID if it exists
     *
     * @return string|null
     */
    public function getAbuseContact()
    {
        $contacts = $this->getAuxContactsByType('abuse');
        return count($contacts) > 0 ? $contacts[0] : null;
    }

    /**
     * Get the DNS operator contact ID if it exists
     *
     * @return string|null
     */
    public function getDnsOperatorContact()
    {
        $contacts = $this->getAuxContactsByType('dns-operator');
        return count($contacts) > 0 ? $contacts[0] : null;
    }
}
