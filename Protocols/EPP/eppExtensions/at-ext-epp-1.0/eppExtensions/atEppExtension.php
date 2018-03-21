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
    protected $additionaEppExtension=null;

    function __construct(atEppExtensionChain $additionaEppExtension=null) {

        $this->additionaEppExtension = $additionaEppExtension;
    }

    public function setEppRequestExtension(eppRequest $request,\DOMElement $extension)
    {


    }
}