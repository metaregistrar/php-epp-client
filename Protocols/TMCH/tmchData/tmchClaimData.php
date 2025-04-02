<?php
namespace Metaregistrar\TMCH;
/**
 * The TMCH claim data Object
 *
 * This will hold the complete claim info received from a TMCH CNIS query
 * <?xml version="1.0" encoding="UTF-8"?>
<tmNotice:notice xmlns:tmNotice="urn:ietf:params:xml:ns:tmNotice-1.0">
<tmNotice:id>b23b32050000000000000244489</tmNotice:id>
<tmNotice:notBefore>2015-01-26T12:00:00.0Z</tmNotice:notBefore>
<tmNotice:notAfter>2015-01-28T12:00:00.0Z</tmNotice:notAfter>
<tmNotice:label>xn--coc-acok-i1a</tmNotice:label>
<tmNotice:claim>
<tmNotice:markName>c o c a c o k é</tmNotice:markName>
<tmNotice:holder entitlement="owner">
<tmNotice:org>holder inc</tmNotice:org>
<tmNotice:addr>
<tmNotice:street>holder st</tmNotice:street>
<tmNotice:city>los angeles</tmNotice:city>
<tmNotice:pc>90210</tmNotice:pc>
<tmNotice:cc>US</tmNotice:cc>
</tmNotice:addr>
</tmNotice:holder>
<tmNotice:contact type="agent">
<tmNotice:name>Network Information Center Mexico, S.C.</tmNotice:name>
<tmNotice:org>Network Information Center Mexico, S.C.</tmNotice:org>
<tmNotice:addr>
<tmNotice:street>Test Street</tmNotice:street>
<tmNotice:city>Test City</tmNotice:city>
<tmNotice:pc>12345</tmNotice:pc>
<tmNotice:cc>CC</tmNotice:cc>
</tmNotice:addr>
<tmNotice:voice>+1.2223333</tmNotice:voice>
<tmNotice:email>mgrimaldo@nic.mx</tmNotice:email>
</tmNotice:contact>
<tmNotice:jurDesc jurCC="US">United States of America</tmNotice:jurDesc>
<tmNotice:classDesc classNum="3">Bleaching preparations and other substances for laundry use; cleaning, polishing, scouring and abrasive preparations; soaps; perfumery, essential oils, cosmetics, hair lotions; dentifrices. </tmNotice:classDesc>
<tmNotice:classDesc classNum="7">Machines and machine tools; motors and engines (except for land vehicles); machine coupling and transmission components (except for land vehicles); agricultural implements other than hand-operated; incubators for eggs; automatic vending machines. </tmNotice:classDesc>
<tmNotice:classDesc classNum="8">Hand tools and implements (hand-operated); cutlery; side arms; razors. </tmNotice:classDesc>
<tmNotice:goodsAndServices>coke knockoff</tmNotice:goodsAndServices>
</tmNotice:claim>
</tmNotice:notice>
 *
 */

class tmchClaim extends \DOMDocument {
    private $markName = '';
    private $jurisdiction = '';
    private $goodsAndServices = '';
    private $classes = null;
    private $holder = null;
    private $contact = null;

    /**
     * @param \DOMElement $claim
     */
    public function setClaimData($claim) {
        $this->markName = $this->getValue($claim, 'markName');
        $this->jurisdiction = $this->getValue($claim, 'jurDesc');
        $this->goodsAndServices = $this->getValue($claim, 'goodsAndServices');
        $result = $claim->getElementsByTagName('classDesc');
        if (is_object($result) && ($result->length > 0)) {
            foreach ($result as $class) {
                /* @var \DOMElement $class */
                $classid = $class->getAttribute('classNum');
                $this->classes[$classid] = $class->nodeValue;
            }
        }
        $result = $claim->getElementsByTagName('holder');
        if (is_object($result) && ($result->length > 0)) {
            $h = $result->item(0);
            /* @var \DOMElement $h */
            $this->holder['entitlement'] = $h->getAttribute('entitlement');
            $this->holder['name'] = $this->getValue($h, 'name');
            $this->holder['organization'] = $this->getValue($h, 'org');
            $this->holder['street'] = $this->getValue($h, 'street');
            $this->holder['city'] = $this->getValue($h, 'city');
            $this->holder['postcode'] = $this->getValue($h, 'pc');
            $this->holder['country'] = $this->getValue($h, 'cc');

        }
        $result = $claim->getElementsByTagName('contact');
        if (is_object($result) && ($result->length > 0)) {
            $c = $result->item(0);
            /* @var \DOMElement $c */
            $this->contact['type'] = $c->getAttribute('type');
            $this->contact['name'] = $this->getValue($c, 'name');
            $this->contact['organization'] = $this->getValue($c, 'org');
            $this->contact['street'] = $this->getValue($c, 'street');
            $this->contact['city'] = $this->getValue($c, 'city');
            $this->contact['postcode'] = $this->getValue($c, 'pc');
            $this->contact['country'] = $this->getValue($c, 'cc');
            $this->contact['phone'] = $this->getValue($c, 'voice');
            $this->contact['email'] = $this->getValue($c, 'email');
        }

    }


    /**
     * @param \DOMElement $node
     * @param string $tagname
     * @return null|string
     */
    private function getValue($node, $tagname) {
        $result = $node->getElementsByTagName($tagname);
        if (is_object($result) && ($result->length > 0)) {
            /* @var \DOMNodeList $result */
            return $result->item(0)->nodeValue;
        }
        return null;
    }


    public function getMarkName() {
        return $this->markName;
    }

    public function getJurisdiction() {
        return $this->jurisdiction;
    }

    public function getGoodsAndServices() {
        return $this->goodsAndServices;
    }

    public function getClasses() {
        return $this->classes;
    }

    public function getHolder() {
        return $this->holder;
    }

    public function getContact() {
        return $this->contact;
    }
}


class tmchClaimData extends \DOMDocument {

    /**
     * @var array tmchClaim $claim
     */
    private $claims;
    /*
     * @var int $claimCount
     */
    private $claimCount = 0;
    /**
     * @var string $publicnamespace
     */
    private $publicnamespace;

    public function __construct() {
        parent::__construct();
        $this->formatOutput = true;
        #$this->validateOnParse = true;
    }

    public function __destruct() {
    }

    /**
     * @param \DOMNode|null $node
     * @param null $options
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function saveXML(\DOMNode $node = null, $options = null) {
        return str_replace("\t", '  ', parent::saveXML($node, LIBXML_NOEMPTYTAG));
    }


    public function xPath() {
        $xpath = new \DOMXpath($this);
        $this->publicnamespace = $this->documentElement->lookupNamespaceUri(null);
        $xpath->registerNamespace('tmNotice', $this->publicnamespace);
        return $xpath;
    }


    public function getClaimCount() {
        return $this->claimCount;
    }

    public function getClaims() {
        return $this->claims;
    }

    public function setClaims() {
        $xpath = $this->xPath();
        $result = $xpath->query('/tmNotice:notice/tmNotice:claim');
        $this->claimCount = $result->length;
        foreach ($result as $claim) {
            $c = new tmchClaim();
            $this->claims[] = $c;
            $c->setClaimData($claim);
        }
    }

    /**
     *
     * @return string
     */
    public function getNoticeId() {
        $xpath = $this->xPath();
        $result = $xpath->query('/tmNotice:notice/tmNotice:id');
        if (is_object($result) && ($result->length > 0)) {
            return trim($result->item(0)->nodeValue);
        }
        return null;
    }

    /**
     *
     * @return string
     */
    public function getLabel() {
        $xpath = $this->xPath();
        $result = $xpath->query('/tmNotice:notice/tmNotice:label');
        if (is_object($result) && ($result->length > 0)) {
            return trim($result->item(0)->nodeValue);
        }
        return null;
    }

    public function getNotBefore() {
        $xpath = $this->xPath();
        $result = $xpath->query('/tmNotice:notice/tmNotice:notBefore');
        if (is_object($result) && ($result->length > 0)) {
            return trim($result->item(0)->nodeValue);
        }
        return null;
    }

    public function getNotAfter() {
        $xpath = $this->xPath();
        $result = $xpath->query('/tmNotice:notice/tmNotice:notAfter');
        if (is_object($result) && ($result->length > 0)) {
            return trim($result->item(0)->nodeValue);
        }
        return null;
    }
}
