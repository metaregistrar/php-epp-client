<?php
namespace Metaregistrar\EPP;

class metaregInfoDomainOptionsType {
    const DNSBE_REQUEST_AUTHCODE = "dnsbe-request-authcode";

    public $type;

    public function __construct($type) {
        switch ($type) {
            default:
                throw new \Exception("Unknown metaregInfoDomainOptionsType: $type");
                break;
            case self::DNSBE_REQUEST_AUTHCODE:
                $this->type = $type;
                break;
        }
    }

    public function getType() {
        return $this->type;
    }

    /**
     *
     * @return metaregInfoDomainOptionsType
     */
    public static function getDnsbeRequestAuthcodeType() {
        return new metaregInfoDomainOptionsType(self::DNSBE_REQUEST_AUTHCODE);
    }
}