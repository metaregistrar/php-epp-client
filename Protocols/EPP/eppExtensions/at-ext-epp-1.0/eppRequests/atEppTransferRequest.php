<?php
/**
 * Created by PhpStorm.
 * User: thomasm
 * Date: 02.10.2015
 * Time: 09:52
 */

namespace Metaregistrar\EPP;


class atEppTransferRequest extends eppTransferRequest
{
    use \Metaregistrar\EPP\atEppCommandTrait;

    protected $atEppExtensionChain = null;

    function __construct($operation, $object,atEppExtensionChain $atEppExtensionChain=null)
    {
        $this->atEppExtensionChain = $atEppExtensionChain;
        parent::__construct($operation, $object);
        $this->setAtExtensions();
        $this->addSessionId();
    }

}