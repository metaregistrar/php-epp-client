<?php
/** @noinspection PhpMissingReturnTypeInspection */

namespace Metaregistrar\EPP;

use DomElement;
use DOMException;

/**
 *
 * @author christoph.goedel-pavlik@helloly.com
 * @package Metaregistrar\EPP
 *
 */
class atEppWithdrawRequest extends eppRequest
{
    /**
     * @throws DOMException
     */
    public function __construct(eppDomain $domain, bool $zoneDelete = false)
    {
        parent::__construct();

        $domainZd = $this->createElement('domain:zd');
        $domainZd->setAttribute('value', intval($zoneDelete));

        $domainWithdraw = $this->createElement('domain:withdraw');
        $domainWithdraw->setAttribute('xmlns:domain', atEppConstants::namespaceAtExtDomain);
        $domainWithdraw->setAttribute('xsi:schemaLocation', atEppConstants::schemaLocationAtExtDomain);
        $domainWithdraw->appendChild($this->createElement('domain:name', $domain->getDomainname()));
        $domainWithdraw->appendChild($domainZd);

        $withdraw = $this->createElement('withdraw');
        $withdraw->appendChild($domainWithdraw);

        $this->getCommand()->appendChild($withdraw);
        $this->addSessionId();
    }

    /**
     * Needs to be overwritten, because relation of extension and command is inverted in withdraw command.
     *
     * @return DomElement
     * @throws DOMException
     */
    public function getExtension()
    {
        if (!$this->extension) {
            #
            # If it's not there, then create extension structure
            #
            $this->extension = $this->createElement('extension');
            $this->getEpp()->appendChild($this->extension);
        }
        return $this->extension;
    }


    /**
     * Get the command element of the epp structure.
     * Needs to be overwritten, because relation of extension and command is inverted in withdraw command.
     *
     * @return DomElement
     * @throws DOMException
     */
    protected function getCommand() {
        if (!$this->command) {
            #
            # If it's not there, then create command structure
            #
            $this->command = $this->createElement('command');
            $this->command->setAttribute('xmlns', atEppConstants::namespaceAtExt);
            $this->command->setAttribute('xsi:schemaLocation', atEppConstants::schemaLocationAtExt);
            $this->getExtension()->appendChild($this->command);
        }
        return $this->command;
    }

    /**
     * Get the epp element of the epp structure
     * Overwrite necessary because error occurs if 'xmlns:xsi' attribute is not supplied.
     *
     * @return DomElement
     */
    public function getEpp() {
        parent::getEpp()->setAttribute('xmlns:xsi', atEppConstants::w3SchemaLocation);
        return $this->epp;
    }
}