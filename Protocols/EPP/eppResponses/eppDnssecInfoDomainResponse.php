<?php

#
# rfc5910
#
/**
 *    <extension>
      <secDNS:infData>
        <secDNS:keyData>
          <secDNS:flags>257</secDNS:flags>
          <secDNS:protocol>3</secDNS:protocol>
          <secDNS:alg>8</secDNS:alg>
          <secDNS:pubKey>AwEAAdHSD0dsAPliSTZAhP4wesBbotNO4TtQSre5ZCiliWXe3h6VAoe+Msd3UQV4/DXgYF1kuy6RdNncbElqnQNdoF8/65cHJJI8hKaDOgqWRDCKp7/2/j/etLDhuyv+ybVo8gIRLnUS55J44PyhiuHeTIsgI5oroKdRhHw1lxpZRdjiue/cZ6E6rVWx2x9p3BUZO3ygZy6pnSQxO5oj7zklTKjyKu4/Bx7sRoZ5FCVKYIx+ENVg52ly4hLLQyFCuOEaI8+hXap0ooNEeCWP7NMH4nIZGKRMSZi485dqLyvIZLMqBxuVOMiUuCTyRggAk7It6X1APDV6dUEoaoEIqWXiCJ8=</secDNS:pubKey>
        </secDNS:keyData>
      </secDNS:infData>
  
  
     <extension>
      <secDNS:infData xmlns:secDNS="urn:ietf:params:xml:ns:secDNS-1.1">
        <secDNS:keyData>
          <secDNS:flags>257</secDNS:flags>
          <secDNS:protocol>3</secDNS:protocol>
          <secDNS:alg>8</secDNS:alg>
          <secDNS:pubKey>AwEAAaQOLIYKhaBDAFDCJk8+ubGyRU0bRxXrB6TQ/MaD6N5ut5pVzbv4YE9AKHBJ36q2lyuLvFObz/xSLd+E0cNDeEYOoNHY53T0LINX6iFs2euyiMbqVPDXksa0C/ZYEx7EVnVTJfLBFI56VxV2Sj8WjrpeKv9Bl+kDg7TLlX7VQunBtPxQrKQyXpfYKHMeGP7+V6wJdDBh6M9EElu7Wi7OgS/FDfO8z7dGSAmSY6xIq/d+DyCQPd5eBkNWRyL3zjyOqa0r3pg2PBmL2+j5KPAsJqN2d0a/g4Ikvv2PnZ/Xvrhrq7NkoWzFxvWEdYxKvNEjoo4rLAjohsL0HBM8tLUiayU=</secDNS:pubKey>
        </secDNS:keyData>
        <secDNS:keyData>
          <secDNS:flags>257</secDNS:flags>
          <secDNS:protocol>3</secDNS:protocol>
          <secDNS:alg>8</secDNS:alg>
          <secDNS:pubKey>AwEAAbqDt56Ez75fGvlA6QRyJaZ8kblN13jW19smEly1N8Wt+plL/ELU5j6cLxhTV6FBHecrVbvkpQY/v848hAXzG5vEUO5rmAib1aZvr9EpFQWW9TPhvusPf5kuM5cv0ypehXP7R1skF5ez8Lroub3RkwoJl0sultyalrMI84DN4eZZr/MtXAYy7X2yaBK9sSXTY7I1Ou2msmXtYXljjOOJ3Pcig7tmrdDPe2Sd2gvuFiwoPj86Ko/L0iCjNIZT6hmvCgCuc6s7sUz4jRJxH/EKmL70c+eoaaCzFouNIw6hz860/ZFBDhMLKhlNTkDpP+sqbc8bhEPlLiYgzVDXmq4uCik=</secDNS:pubKey>
        </secDNS:keyData>
      </secDNS:infData>
    </extension>

 */

class eppDnssecInfoDomainResponse extends eppInfoDomainResponse
{
    function __construct()
    {
        parent::__construct();
    }

    function __destruct()
    {
        parent::__destruct();
    }

    public function getKeydata()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/secDNS:infData/*');
        $keys = array();
        if (count($result)>0)
        {
            foreach ($result as $keydata)
            {
                $secdns = new eppSecdns();
                $secdns->setFlags($result->item(0)->getElementsByTagName('flags')->item(0)->nodeValue);
                $secdns->setAlgorithm($result->item(0)->getElementsByTagName('alg')->item(0)->nodeValue);
                $secdns->setProtocol($result->item(0)->getElementsByTagName('protocol')->item(0)->nodeValue);
                $secdns->setPubkey($result->item(0)->getElementsByTagName('pubKey')->item(0)->nodeValue);
                $keys[] = $secdns;
            }
        }
        return $keys;        
    }
        
    
}