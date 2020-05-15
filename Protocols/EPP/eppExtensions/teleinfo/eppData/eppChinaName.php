<?php
// +----------------------------------------------------------------------
// | 在我们年轻的城市里，没有不可能的事！
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://srs.micang.com All rights reserved.
// +----------------------------------------------------------------------
// | Author : Jansen <jansen.shi@qq.com>
// +----------------------------------------------------------------------
namespace Metaregistrar\EPP;
class eppChinaName{
    /**
     * @var string
     */
    private $name = '';
    /**
     * @var string
     */
    private $rnvCode = null;
    /**
     * @var string
     */
    private $authorisationCode = null;

    /**
     * @param string $name
     * @param string $rnvCode
     * @param string $authorisationCode
     */
    public function __construct($name, $rnvCode = null, $authorisationCode = null) {
        if (strlen($name)) {
            $this->setName($name);
        } else {
            throw new eppException('Domain name not set');
        }
        if ($rnvCode){
            $this->setRNVCode($rnvCode);
        }
        if ($authorisationCode) {
            $this->setAuthorisationCode($authorisationCode);
        }
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return string name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $rnvCode
     */
    public function setRNVCode($rnvCode) {
        $this->rnvCode = $rnvCode;
    }

    /**
     * @return string
     */
    public function getRNVCode() {
        return $this->rnvCode;
    }

    /**
     * @param string $authorisationCode
     * @return void
     */
    public function setAuthorisationCode($authorisationCode) {
        $this->authorisationCode = $authorisationCode;
    }

    /**
     * @return string
     */
    public function getAuthorisationCode() {
        return $this->authorisationCode;
    }
}