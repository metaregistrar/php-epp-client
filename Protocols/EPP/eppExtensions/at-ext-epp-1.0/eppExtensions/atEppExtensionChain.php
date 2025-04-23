<?php
namespace Metaregistrar\EPP;

/**
 * Created by PhpStorm.
 * User: thomasm
 * Date: 11.09.2015
 * Time: 09:27
 */
abstract class atEppExtensionChain
{
    protected $additionalEppExtension=null;

    function __construct(?atEppExtensionChain $additionalEppExtension=null) {

        $this->additionalEppExtension = $additionalEppExtension;
    }

    public function setEppRequestExtension(eppRequest $request,\DOMElement $extension)
    {


    }
}