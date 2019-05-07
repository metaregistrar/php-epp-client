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
    public function getCategoryname() {
        return $this->categoryname;
    }

    /**
     * @param string $categoryname
     */
    public function setCategoryname($categoryname) {
        $this->categoryname = $categoryname;
    }

    /**
     * @return string
     */
    public function getCategoryid() {
        return $this->categoryid;
    }

    /**
     * @param string $categoryid
     */
    public function setCategoryid($categoryid) {
        $this->categoryid = $categoryid;
    }

    /**
     * @return string
     */
    public function getChargetype() {
        return $this->chargetype;
    }

    /**
     * @param string $chargetype
     */
    public function setChargetype($chargetype) {
        $this->chargetype = $chargetype;
    }

    /**
     * @return array
     */
    public function getCharges() {
        return $this->charges;
    }

    /**
     * @param array $charges
     */
    public function setCharges($charges) {
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