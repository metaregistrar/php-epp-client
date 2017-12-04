<?php
namespace Metaregistrar\EPP;


class atEppUpdateDomainRequest extends eppUpdateDomainRequest
{
    use atEppCommandTrait;

    protected $atEppExtensionChain = null;

    function __construct($objectname, $addinfo = null, $removeinfo = null, $updateinfo = null, $forcehostattr=false,atEppExtensionChain $atEppExtensionChain=null)
    {
        $this->atEppExtensionChain = $atEppExtensionChain;
        parent::__construct($objectname, $addinfo, $removeinfo, $updateinfo, $forcehostattr);
        $this->epp->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $this->setDsContainer($addinfo, $removeinfo);
        $this->addSessionId();
    }


    /**
     * if there are ds changes create the necessary containers
     *
     * @param $addinfo
     * @param $removeinfo
     * @throws eppException
     */
    private function setDsContainer($addinfo,$removeinfo){
        $dsChangeCounter=0;
        $secdns = $this->createElement('secDNS:update');
        $secdns->setAttribute('xmlns:secDNS', 'urn:ietf:params:xml:ns:secDNS-1.1');
        $secdns->setAttribute('xsi:schemaLocation', 'urn:ietf:params:xml:ns:secDNS-1.1 secDNS-1.1.xsd');
        if ($removeinfo instanceof eppDomain) {
            $dnssecs = $removeinfo->getSecdns();
            $dsChangeCounter = $this->updateDsContainer('secDNS:rem',$secdns,$dnssecs);
        }
        if ($addinfo instanceof eppDomain) {
            $dnssecs = $addinfo->getSecdns();
            $dsChangeCounter += $this->updateDsContainer('secDNS:add',$secdns,$dnssecs);
        }
        if ($dsChangeCounter > 0) {
            $this->getExtension()->appendchild($secdns);
        }

    }

    /**
     * creates the add/remove dnssec part
     *
     * @param $elementName
     * @param $dsContainer
     * @param $dnssecList
     * @return int
     */
    private function updateDsContainer($elementName,$dsContainer,$dnssecList){
        $element = $this->createElement($elementName);
        $counter=0;
        foreach ($dnssecList as $dnssec) {
            /* @var $dnssec eppSecdns */

            if (strlen($dnssec->getPubkey()) > 0) {
                $keydata = $this->createElement('secDNS:keyData');
                $keydata->appendChild($this->createElement('secDNS:flags', $dnssec->getFlags()));
                $keydata->appendChild($this->createElement('secDNS:protocol', $dnssec->getProtocol()));
                $keydata->appendChild($this->createElement('secDNS:alg', $dnssec->getAlgorithm()));
                $keydata->appendChild($this->createElement('secDNS:pubKey', $dnssec->getPubkey()));
                $element->appendChild($keydata);
            }
            if (strlen($dnssec->getKeytag()) > 0) {
                $dsdata = $this->createElement('secDNS:dsData');
                $dsdata->appendChild($this->createElement('secDNS:keyTag', $dnssec->getKeytag()));
                $dsdata->appendChild($this->createElement('secDNS:alg', $dnssec->getAlgorithm()));
                if (strlen($dnssec->getSiglife()) > 0) {
                    $dsdata->appendChild($this->createElement('secDNS:maxSigLife', $dnssec->getSiglife()));
                }
                $dsdata->appendChild($this->createElement('secDNS:digestType', $dnssec->getDigestType()));
                $dsdata->appendChild($this->createElement('secDNS:digest', $dnssec->getDigest()));

                $element->appendChild($dsdata);
            }
            $counter++;
        }
        if ($counter > 0) {
            $dsContainer->appendChild($element);
        }
        return $counter;
    }


    /**
     * parent:update / rewriteAuthorisationCode / setAtExtensions
     *
     * @param string $domainname
     * @param eppDomain $addInfo
     * @param eppDomain $removeInfo
     * @param eppDomain $updateInfo
     */
    public function updateDomain($domainname, $addInfo, $removeInfo, $updateInfo) {
        parent::updateDomain($domainname, $addInfo, $removeInfo, $updateInfo);
        $this->rewriteAuthorisationCode($updateInfo);
        $this->setAtExtensions();
    }

    /**
     * rewrite the authinfo element if existent
     * authinfo is specialchar encoded and surrounded by a CDATA Tag by metaregistrar
     * decode the authinfo before it is put into a CDATA Tag
     *
     * @param $updateInfo
     */
    protected function rewriteAuthorisationCode($updateInfo){
        if (strlen($updateInfo->getAuthorisationCode())) {
            $authInfoList_ = $this->getElementsByTagName("update")->item(0)->getElementsByTagName("domain:authInfo");
            $pwdList = $authInfoList_->item(0)->getElementsByTagName("domain:pw");

            $pw = $this->createElement('domain:pw');
            $pw->appendChild($this->createCDATASection(htmlspecialchars_decode($updateInfo->getAuthorisationCode())));

            $authInfoList_->item(0)->removeChild($pwdList->item(0));
            $authInfoList_->item(0)->appendChild($pw);

        }
    }
}