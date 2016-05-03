<?php
/**
 * Created by PhpStorm.
 * User: thomasm
 * Date: 17.09.2015
 * Time: 12:26
 */

namespace Metaregistrar\EPP;


trait atEppCommandTrait
{

    protected function setAtExtensions()
    {
        if(!is_null($this->atEppExtensionChain)) {
            /* @var atEppExtensionChain $this->atEppExtensionChain */
            $this->atEppExtensionChain->setEppRequestExtension($this, $this->getExtension());
            $this->addExtension('xmlns:xsi', atEppConstants::w3SchemaLocation);
        }
    }
}