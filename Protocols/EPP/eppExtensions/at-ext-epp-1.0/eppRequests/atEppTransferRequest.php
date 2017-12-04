<?php
namespace Metaregistrar\EPP;


class atEppTransferRequest extends eppTransferRequest
{
    use atEppCommandTrait;

    protected $atEppExtensionChain = null;

    function __construct($operation, $object,atEppExtensionChain $atEppExtensionChain=null)
    {
        $this->atEppExtensionChain = $atEppExtensionChain;
        parent::__construct($operation, $object);
        $this->setAtExtensions();
        $this->addSessionId();
    }

}