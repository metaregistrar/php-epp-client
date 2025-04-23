<?php
namespace Metaregistrar\EPP;


class atEppCreateDomainRequest extends eppCreateDomainRequest
{
    use atEppCommandTrait;

    protected $atEppExtensionChain = null;

    function __construct($createinfo,?atEppExtensionChain $atEppExtensionChain=null, $forcehostattr = true) {
        $this->atEppExtensionChain = $atEppExtensionChain;
        parent::__construct($createinfo,$forcehostattr);
    }


    public function setDomain(eppDomain $domain) {
        parent::setDomain($domain);
        $this->setAtExtensions();
    }


}