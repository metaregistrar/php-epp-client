<?php

namespace Metaregistrar\EPP;

class dnsbeEppCheckDomainResponse extends eppCheckDomainResponse {
    function __construct() {
        parent::__construct();
    }

    public function getAdditionalInfo() {
        $xpath = $this->xPath();
        $nodes = $xpath->query('//dnsbe:cd[dnsbe:availableDate or dnsbe:status]');

        $result = [];

        foreach ($nodes as $node) {
            $nameNodes = $xpath->query('dnsbe:name', $node);
            if ($nameNodes === false || $nameNodes->length === 0) {
                continue;
            }

            $item = [
                'domain' => trim($nameNodes->item(0)->textContent),
            ];

            $dateNodes = $xpath->query('dnsbe:availableDate', $node);
            if ($dateNodes !== false && $dateNodes->length > 0) {
                $item['availableDate'] = trim($dateNodes->item(0)->textContent);
            }

            $statusNodes = $xpath->query('dnsbe:status', $node);
            if ($statusNodes !== false && $statusNodes->length > 0) {
                $statuses = [];
                foreach ($statusNodes as $statusNode) {
                    if ($statusNode instanceof DOMElement) {
                        $status = $statusNode->getAttribute('s');
                        if ($status !== '') {
                            $statuses[] = $status;
                        }
                    }
                }

                if (!empty($statuses)) {
                    $item['status'] = $statuses;
                }

            }

            $result[] = $item;
        }
        return $result;
    }
}
