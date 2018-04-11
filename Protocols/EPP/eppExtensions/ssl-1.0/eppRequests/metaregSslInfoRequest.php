<?php
namespace Metaregistrar\EPP;
/**
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:ssl="http://www.metaregistrar.com/epp/ssl-1.0">
    <command>
        <info>
            <ssl:info>
                <ssl:certificateId>53</ssl:certificateId>
            </ssl:info>
        </info>
        <clTRID>5a27b8d011a0e</clTRID>
    </command>
</epp>
 */

/**
 * Class metaregSslInfoRequest
 * @package Metaregistrar\EPP
 */
class metaregSslInfoRequest extends eppRequest {

    private $info;

    /**
     * metaregSslInfoRequest constructor.
     * @param $certificateId
     */
    public function __construct($certificateId) {
        parent::__construct();
        $info = $this->createElement('info');
        $this->info = $this->createElement('ssl:info');
        if (!$this->rootNamespaces()) {
            $this->info->setAttribute('xmlns:ssl','http://www.metaregistrar.com/epp/ssl-1.0');
        }
        $this->info->appendChild($this->createElement('ssl:certificateId',$certificateId));
        $info->appendChild($this->info);
        $this->getCommand()->appendChild($info);
        parent::addSessionId();
    }
}