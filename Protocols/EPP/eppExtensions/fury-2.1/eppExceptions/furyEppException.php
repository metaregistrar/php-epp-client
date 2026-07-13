<?php
namespace Metaregistrar\EPP;

/**
 * Class furyEppException
 *
 * CIRA Fury 2.1 errors carry a registry-specific <fury:ciraCode> inside the
 * standard EPP <result><extValue> block, e.g.:
 *
 * <result code="2303">
 *   <msg>Object does not exist</msg>
 *   <extValue>
 *     <value><fury:ciraCode>8010</fury:ciraCode></value>
 *     <reason>Domain name does not exist</reason>
 *   </extValue>
 * </result>
 *
 * @package Metaregistrar\EPP
 */
class furyEppException extends eppException {

    public function getCiraCode() {
        return $this->getResponse()->queryPath('/epp:epp/epp:response/epp:result/epp:extValue/epp:value/fury:ciraCode');
    }

    public function getCiraReason() {
        return $this->getResponse()->queryPath('/epp:epp/epp:response/epp:result/epp:extValue/epp:reason');
    }
}
