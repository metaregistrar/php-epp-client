<?php
namespace Metaregistrar\EPP;
/**
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:ssl="http://www.metaregistrar.com/epp/ssl-1.0">
    <command>
        <delete>
            <ssl:delete>
                <ssl:certificateId>54</ssl:certificateId>
                <ssl:reason><![CDATA[Yolo]]></ssl:reason>
            </ssl:delete>
        </delete>
    <clTRID>5a27b8d011a0e</clTRID>
    </command>
</epp>
 */

/**
 * Class metaregSslDeleteRequest
 * @package Metaregistrar\EPP
 */
class metaregSslDeleteRequest extends eppRequest {

    private $delete;

    /**
     * metaregSslDeleteRequest constructor.
     * @param int $certificateId
     * @param string $reason
     */
    public function __construct($certificateId, $reason) {
        parent::__construct();
        $delete = $this->createElement('delete');
        $this->delete = $this->createElement('ssl:delete');
        if (!$this->rootNamespaces()) {
            $this->delete->setAttribute('xmlns:ssl','http://www.metaregistrar.com/epp/ssl-1.0');
        }
        $this->delete->appendChild($this->createElement('ssl:certificateId',$certificateId));
        $this->delete->appendChild($this->createElement('ssl:reason',$reason));
        $delete->appendChild($this->delete);
        $this->getCommand()->appendChild($delete);
        parent::addSessionId();
    }
}