<?php
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