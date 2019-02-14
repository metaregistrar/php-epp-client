<?php
namespace Metaregistrar\EPP;

/**
 * Class chargeEppDomain
 * @package Metaregistrar\EPP
 */
class chargeEppDomain {
    /**
     * @var string
     */
    private $categoryname;
    /**
     * @var string
     */
    private $categoryid;
    /**
     * @var string
     */
    private $chargetype;
    /**
     * @var array
     */
    private $charges;

    /**
     * @return string
     */
    public function getCategoryname(): string {
        return $this->categoryname;
    }

    /**
     * @param string $categoryname
     */
    public function setCategoryname(string $categoryname) {
        $this->categoryname = $categoryname;
    }

    /**
     * @return string
     */
    public function getCategoryid(): string {
        return $this->categoryid;
    }

    /**
     * @param string $categoryid
     */
    public function setCategoryid(string $categoryid) {
        $this->categoryid = $categoryid;
    }

    /**
     * @return string
     */
    public function getChargetype(): string {
        return $this->chargetype;
    }

    /**
     * @param string $chargetype
     */
    public function setChargetype(string $chargetype) {
        $this->chargetype = $chargetype;
    }

    /**
     * @return array
     */
    public function getCharges(): array {
        return $this->charges;
    }

    /**
     * @param array $charges
     */
    public function setCharges(array $charges) {
        $this->charges = $charges;
    }

    /**
     * Return charge for one specific type
     * @param string $type
     * @return string|null
     */
    public function getCharge($type) {
        if (isset($this->charges[$type])) {
            return $this->charges[$type];
        } else {
            return null;
        }

    }

}