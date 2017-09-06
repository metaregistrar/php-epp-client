<?php
namespace Metaregistrar\EPP;


class metaregInfoDnsResponse extends eppResponse {
    const RESPONSE_BASEXPATH = '/epp:epp/epp:response/epp:resData/dns-ext:infData';

    /**
     * @return string
     */
    public function getName()
    {
        $xpath = $this->xPath();
        $test= $xpath->query(self::RESPONSE_BASEXPATH . '/dns-ext:name');
        if ($test->length>0) {
            return $test->item(0)->textContent;
        }
        return null;
    }

    /**
     * @return array
     */
    public function getKeyData()
    {
        $xpath = $this->xPath();
        $keydata = $xpath->query(self::RESPONSE_BASEXPATH . '/dns-ext:keyData');
        $response = [];
        for ($i = 0; $i < $keydata->length; $i++) {
            $item = $keydata->item($i);
            /* @var $item \DOMElement */
            $row = [
                "flags" => $this->getValueForTag('flags', $item),
                "protocol" => $this->getValueForTag('protocol', $item),
                "alg" => $this->getValueForTag('alg', $item),
                "pubKey" => $this->getValueForTag('pubKey', $item),
                'pubkeyDisplay' => implode("\n<br>", str_split($this->getValueForTag('pubKey', $item), 30))
            ];
            $response[] = $row;
        }
        return $response;
    }

    /**
     * @return array
     */
    public function getContent()
    {
        $xpath = $this->xPath();
        $content = $xpath->query(self::RESPONSE_BASEXPATH . '/dns-ext:content');
        $response = [];
        for ($i = 0; $i < $content->length; $i++) {
            $item = $content->item($i);
            /* @var $item \DOMElement */
            $row = [
                "name" => $this->getValueForTag('name', $item),
                "content" => $this->getValueForTag('content', $item),
                "type" => $this->getValueForTag('type', $item),
                "ttl" => $this->getValueForTag('ttl', $item),
                "priority" => $this->getValueForTag('priority', $item)
            ];
            $response[$row['type']][] = $row;
        }
        ksort($response);
        $out = [];
        foreach (array_values($response) as $rows) {
            $out = array_merge($out, $rows);
        }
        return $out;
    }

    /**
     * @param string      $tag
     * @param \DOMElement $item
     * @return string
     */
    private function getValueForTag($tag, \DOMElement $item)
    {
        $items = $item->getElementsByTagName($tag);
        if ($items->length == 0) {
            return '';
        }
        return $items->item(0)->nodeValue;
    }
}
