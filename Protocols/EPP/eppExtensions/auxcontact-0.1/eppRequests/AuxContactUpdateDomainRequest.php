<?php
namespace Metaregistrar\EPP;

/**
 * Class AuxContactUpdateDomainRequest
 * @package Metaregistrar\EPP
 *
 * <extension>
 *  <auxcontact:update xmlns:auxcontact="urn:ietf:params:xml:ns:auxcontact-0.1">
 *      <auxcontact:rem>
 *          <auxcontact:contact type="dns-operator">ClientZ</auxcontact:contact>
 *      </auxcontact:rem>
 *      <auxcontact:add>
 *          <auxcontact:contact type="dns-operator">ClientXYZ</auxcontact:contact>
 *      </auxcontact:add>
 *  </auxcontact:update>
 * </extension>
 */
class AuxContactUpdateDomainRequest extends eppUpdateDomainRequest
{
    /**
     * @var array Auxiliary contacts to add
     */
    private $addAuxContacts = [];

    /**
     * @var array Auxiliary contacts to remove
     */
    private $removeAuxContacts = [];

    /**
     * AuxContactUpdateDomainRequest constructor
     *
     * @param eppDomain $objectname Domain object to update
     * @param eppDomain $addinfo Elements to add
     * @param eppDomain $removeinfo Elements to remove
     * @param eppDomain $updateinfo Elements to update
     * @param boolean $forcehostattr Force host attributes
     * @param boolean $namespacesinroot Namespaces in root
     * @param boolean $usecdata Use CDATA
     * @param array $addAuxContacts Auxiliary contacts to add [['type' => 'type', 'id' => 'id'], ...]
     * @param array $removeAuxContacts Auxiliary contacts to remove [['type' => 'type', 'id' => 'id'], ...]
     */
    function __construct($objectname, $addinfo = null, $removeinfo = null, $updateinfo = null, $forcehostattr = false, $namespacesinroot = true, $usecdata = true, $addAuxContacts = [], $removeAuxContacts = [])
    {
        parent::__construct($objectname, $addinfo, $removeinfo, $updateinfo, $forcehostattr, $namespacesinroot, $usecdata);

        // Store contacts to add and remove
        $this->addAuxContacts = $addAuxContacts;
        $this->removeAuxContacts = $removeAuxContacts;

        // Add the extension to the request
        $this->addAuxContactExtension();

        // Add session ID
        $this->addSessionId();
    }

    /**
     * Add auxiliary contact to add list
     *
     * @param string $type Contact type (e.g., 'abuse', 'dns-operator')
     * @param string $contactId Contact ID
     * @return $this
     */
    public function addAuxContact($type, $contactId)
    {
        $this->addAuxContacts[] = [
            'type' => $type,
            'id' => $contactId
        ];

        // Refresh extension
        $this->addAuxContactExtension();

        return $this;
    }

    /**
     * Add auxiliary contact to remove list
     *
     * @param string $type Contact type (e.g., 'abuse', 'dns-operator')
     * @param string $contactId Contact ID
     * @return $this
     */
    public function removeAuxContact($type, $contactId)
    {
        $this->removeAuxContacts[] = [
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
        // Skip if no contacts to add or remove
        if (empty($this->addAuxContacts) && empty($this->removeAuxContacts)) {
            return;
        }

        // Create the auxcontact:update element
        $updateElement = $this->createElement('auxcontact:update');
        $this->setNamespace('auxcontact', 'urn:ietf:params:xml:ns:auxcontact-0.1', $updateElement);

        // Add contacts to remove
        if (!empty($this->removeAuxContacts)) {
            $remElement = $this->createElement('auxcontact:rem');

            foreach ($this->removeAuxContacts as $contact) {
                $contactElement = $this->createElement('auxcontact:contact', htmlspecialchars($contact['id']));
                $contactElement->setAttribute('type', htmlspecialchars($contact['type']));
                $remElement->appendChild($contactElement);
            }

            $updateElement->appendChild($remElement);
        }

        // Add contacts to add
        if (!empty($this->addAuxContacts)) {
            $addElement = $this->createElement('auxcontact:add');

            foreach ($this->addAuxContacts as $contact) {
                $contactElement = $this->createElement('auxcontact:contact', htmlspecialchars($contact['id']));
                $contactElement->setAttribute('type', htmlspecialchars($contact['type']));
                $addElement->appendChild($contactElement);
            }

            $updateElement->appendChild($addElement);
        }

        // Add to the extension element
        $this->getExtension()->appendChild($updateElement);
    }
}
