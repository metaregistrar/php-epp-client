<?php
namespace Metaregistrar\EPP;


class eppDnssecInfoDomainResponse extends eppInfoDomainResponse
{
    /**
     * Retrieve the keyData elements from the info response
     *
    <secDNS:dsData>
        <secDNS:keyTag>12345</secDNS:keyTag>
        <secDNS:alg>3</secDNS:alg>
        <secDNS:digestType>1</secDNS:digestType>
        <secDNS:digest>49FD46E6C4B45C55D4AC</secDNS:digest>
    </secDNS:dsData>
     * @return array|null
     */
    public function getKeydata() {
        // Check if dnssec is enabled on this interface
        if ($this->findNamespace('secDNS')) {
            $xpath = $this->xPath();
            $result = $xpath->query('/epp:epp/epp:response/epp:extension/secDNS:infData/*');
            $keys = array();
            if (count($result) > 0) {
                foreach ($result as $keydata) {
                    /* @var $keydata \DOMElement */
                    // Check if the keyTag element is present. If not, use getKeys()
                    $test = $keydata->getElementsByTagName('keyTag');
                    if ($test->length > 0) {
                        $secdns = new eppSecdns();
                        $secdns->setKeytag($keydata->getElementsByTagName('keyTag')->item(0)->nodeValue);
                        $secdns->setAlgorithm($keydata->getElementsByTagName('alg')->item(0)->nodeValue);
                        $secdns->setDigestType($keydata->getElementsByTagName('digestType')->item(0)->nodeValue);
                        $secdns->setDigest($keydata->getElementsByTagName('digest')->item(0)->nodeValue);
                        $keys[] = $secdns;
                    }
                }
            }
            return $keys;
        }
        return null;
    }

    /**
     * Retrieve the keyTag elements from the info response
     *
    <secDNS:infData>
        <secDNS:keyData>
            <secDNS:flags>256</secDNS:flags>
            <secDNS:protocol>3</secDNS:protocol>
            <secDNS:alg>8</secDNS:alg>
            <secDNS:pubKey>AwEAAbWM8nWQZbDZgJjyq+tLZwPLEXfZZjfvlRcmoAVZHgZJCPn/Ytu/iOsgci+yWgDT28ENzREAoAbKMflFFdhc5DNV27TZxhv8nMo9n2f+cyyRKbQ6oIAvMl7siT6WxrLxEBIMyoyFgDMbqGScn9k19Ppa8fwnpJgv0VUemfxGqHH9</secDNS:pubKey>
        </secDNS:keyData>
    </secDNS:infData>
     * @return array|null
     */
    public function getKeys() {
        // Check if dnssec is enabled on this interface
        if ($this->findNamespace('secDNS')) {
            $xpath = $this->xPath();
            $result = $xpath->query('/epp:epp/epp:response/epp:extension/secDNS:infData/*');
            $keys = array();
            if (count($result) > 0) {
                foreach ($result as $keydata) {
                    /* @var $keydata \DOMElement */
                    // Check if the pubKey element is present. If not, use getKeydata();
                    $test = $keydata->getElementsByTagName('pubKey');
                    if ($test->length > 0) {
                        $secdns = new eppSecdns();
                        $flags = $keydata->getElementsByTagName('flags')->item(0)->nodeValue;
                        $algorithm = $keydata->getElementsByTagName('alg')->item(0)->nodeValue;
                        $pubkey = $keydata->getElementsByTagName('pubKey')->item(0)->nodeValue;
                        $secdns->setKey($flags, $algorithm, $pubkey);
                        $keys[] = $secdns;
                    }
                }
            }
            return $keys;
        }
        return null;
    }
}