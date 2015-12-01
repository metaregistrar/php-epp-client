<?php
/**
 * Created by PhpStorm.
 * User: thomasm
 * Date: 17.09.2015
 * Time: 14:03
 */

namespace Metaregistrar\EPP;


class atEppUpdateDomainRequest extends eppUpdateDomainRequest
{
    use \Metaregistrar\EPP\atEppCommandTrait;

    protected $atEppExtensionChain = null;

    function __construct($objectname, $addinfo = null, $removeinfo = null, $updateinfo = null, $forcehostattr=false,atEppExtensionChain $atEppExtensionChain=null) {
        $this->atEppExtensionChain = $atEppExtensionChain;
        parent::__construct($objectname, $addinfo , $removeinfo , $updateinfo , $forcehostattr);
        $this->addSessionId();

    }

    public function updateDomain($domainname, $addInfo, $removeInfo, $updateInfo) {
        parent::updateDomain($domainname, $addInfo, $removeInfo, $updateInfo);
        $this->setAtExtensions();
    }
}