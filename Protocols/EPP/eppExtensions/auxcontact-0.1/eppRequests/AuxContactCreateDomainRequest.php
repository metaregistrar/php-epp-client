<?php
namespace Metaregistrar\EPP;

/**
 * Class AuxContactCreateDomainRequest
 * @package Metaregistrar\EPP
 *
 * <extension>
 *  <auxcontact:create xmlns:auxcontact="urn:ietf:params:xml:ns:auxcontact-0.1">
 *      <auxcontact:contact type="abuse">sh8013</auxcontact:contact>
 *      <auxcontact:contact type="dns-operator">ClientZ</auxcontact:contact>
 *  </auxcontact:create>
 * </extension>
 */
class AuxContactCreateDomainRequest extends eppCreateDomainRequest
{
    /**
     * @var array Auxiliary contacts to include in domain creation
     */
    private $auxContacts = [];

    /**
     * AuxContactCreateDomainRequest constructor
     *
     * @param eppDomain $createinfo Domain object to create
     * @param boolean $forcehostattr Force host attributes
     * @param boolean $namespacesinroot Namespaces in root
     * @param boolean $usecdata Use CDATA
     * @param array $auxContacts Auxiliary contacts to add [['type' => 'type', 'id' => 'id'], ...]
     */
    function __construct($createinfo, $forcehostattr = false, $namespacesinroot = true, $usecdata = true, $auxContacts = [])
    {
        parent::__construct($createinfo, $forcehostattr, $namespacesinroot, $usecdata);

        // Store contacts
        $this->auxContacts = $auxContacts;

        // Add the extension to the request
        $this->addAuxContactExtension();

        // Add session ID
        $this->addSessionId();
    }

    /**
     * Add auxiliary contact to the request
     *
     * @param string $type Contact type (e.g., 'abuse', 'dns-operator')
     * @param string $contactId Contact ID
     * @return $this
     */
    public function addAuxContact($type, $contactId)
    {
        $this->auxContacts[] = [
            'type' => $type,
            'id' => $contactId
        ];

        // Refresh extension
        $this->addAuxContactExtension();

        return $this;
    }

    /**
     * Add the auxiliary contact extension to the request
     */
    private function addAuxContactExtension()
    {
        // Skip if no contacts to add
        if (empty($this->auxContacts)) {
            return;
        }

        // Create the auxcontact:create an element
        $createElement = $this->createElement('auxcontact:create');
        $this->setNamespace('auxcontact', 'urn:ietf:params:xml:ns:auxcontact-0.1', $createElement);

        // Add contacts
        foreach ($this->auxContacts as $contact) {
            $contactElement = $this->createElement('auxcontact:contact', htmlspecialchars($contact['id']));
            $contactElement->setAttribute('type', htmlspecialchars($contact['type']));
            $createElement->appendChild($contactElement);
        }

        // Add to the extension element
        $this->getExtension()->appendChild($createElement);
    }
}
