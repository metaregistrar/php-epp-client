<?php
namespace Metaregistrar\EPP;
/**
 * DEPRECATED!!!!
 *
 * Please use eppTransferDomainRequest instead
 * There is no transfer contact request any more
 *
 * Class eppTransferRequest
 * @package Metaregistrar\EPP
 */
class eppTransferRequest extends eppDomainRequest {
    const OPERATION_QUERY = 'query';
    const OPERATION_REQUEST = 'request';
    const OPERATION_APPROVE = 'approve';
    const OPERATION_REJECT = 'reject';
    const OPERATION_CANCEL = 'cancel';

    function __construct($operation, $object) {
        if (($operation != self::OPERATION_QUERY) &&
            ($operation != self::OPERATION_REQUEST) &&
            ($operation != self::OPERATION_APPROVE) &&
            ($operation != self::OPERATION_REJECT) &&
            ($operation != self::OPERATION_CANCEL)) {
            throw new eppException('Operation parameter needs to be QUERY, REQUEST, CANCEL, APPROVE or REJECT on eppTransferRequest');
        }
        if (defined("NAMESPACESINROOT")) {
            $this->setNamespacesinroot(NAMESPACESINROOT);
        }
        parent::__construct('transfer');
        if ($object instanceof eppDomain) {
            if (!strlen($object->getDomainname())) {
                throw new eppException('Domain object does not contain a valid domain name on eppTransferRequest');
            }
        }
        $transfer = $this->getCommand()->firstChild;
        $transfer->setAttribute('op', self::OPERATION_QUERY);
        $this->domainobject->appendChild($this->createElement('domain:name', $object->getDomainname()));
        if (strlen($object->getAuthorisationCode())) {
            $authinfo = $this->createElement('domain:authInfo');
            $authinfo->appendChild($this->createElement('domain:pw', $object->getAuthorisationCode()));
            $this->domainobject->appendChild($authinfo);
        }
        $this->addSessionId();
    }

    function __destruct() {
        parent::__destruct();
    }



}