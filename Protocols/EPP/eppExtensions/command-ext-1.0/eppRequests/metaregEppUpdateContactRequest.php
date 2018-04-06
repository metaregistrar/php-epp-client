<?php
namespace Metaregistrar\EPP;
/**
<command>
<update>
<contact:update>
<contact:id>privacyprotect</contact:id>
</contact:update>
</update>
<extension>
<command-ext:command-ext>
    <command-ext-contact:contact>
        <command-ext-contact:update>
            <command-ext-contact:property>
                <command-ext-contact:registry>Dnsbe</command-ext-contact:registry>
                <command-ext-contact:name>vat</command-ext-contact:name>
                <command-ext-contact:value>1219884</command-ext-contact:value>
            </command-ext-contact:property>
            <command-ext-contact:property>
                <command-ext-contact:registry>Dnsbe</command-ext-contact:registry>
                <command-ext-contact:name>lang</command-ext-contact:name>
                <command-ext-contact:value>nl</command-ext-contact:value>
            </command-ext-contact:property>
        </command-ext-contact:update>
    </command-ext-contact:contact>
</command-ext:command-ext>
</extension>
<clTRID>5ac77af78738a</clTRID>
</command>
 */
/**
 * Class metaregEppUpdateContactRequest
 * @package Metaregistrar\EPP
 */
class metaregEppUpdateContactRequest extends eppUpdateContactRequest {

    private $commandext = null;
    private $contactupdate = null;
    /**
     * metaregEppUpdateContactRequest constructor.
     * @param $objectname
     * @param null $addinfo
     * @param null $removeinfo
     * @param null $updateinfo
     * @param bool $namespacesinroot
     */
    public function __construct($objectname, $addinfo=null, $removeinfo=null, $updateinfo=null, $namespacesinroot=false) {
        parent::__construct($objectname, $addinfo, $removeinfo, $updateinfo, $namespacesinroot);

    }

    /**
     * @param string $registry
     * @param string $propertyname
     * @param string $propertyvalue
     */
    public function addContactProperty($registry, $propertyname, $propertyvalue) {
        if (!$this->commandext) {
            $this->commandext = $this->createElement('command-ext:command-ext');
            if (!$this->rootNamespaces()) {
                $this->commandext->setAttribute('xmlns:command-ext', 'http://www.metaregistrar.com/epp/command-ext-1.0');
            }
            $contactext = $this->createElement('command-ext-contact:contact');
            if (!$this->rootNamespaces()) {
                $contactext->setAttribute('xmlns:command-ext-contact', 'http://www.metaregistrar.com/epp/command-ext-contact-1.0');
            }
            $this->contactupdate = $this->createElement('command-ext-contact:update');
            $contactext->appendChild($this->contactupdate);
            $this->commandext->appendChild($contactext);
            $this->getExtension()->appendChild($this->commandext);
        }
        $property = $this->createElement('command-ext-contact:property');
        $property->appendChild($this->createElement('command-ext-contact:registry',$registry));
        $property->appendChild($this->createElement('command-ext-contact:name',$propertyname));
        $property->appendChild($this->createElement('command-ext-contact:value',$propertyvalue));
        $this->contactupdate->appendChild($property);
        $this->addSessionId();
    }
}