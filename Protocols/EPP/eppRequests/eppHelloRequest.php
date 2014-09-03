<?php


class eppHelloRequest extends eppRequest
{
    function __construct()
    {
        parent::__construct();
        $this->getEpp()->appendChild($this->createElement('hello'));
    }

    function __destruct()
    {
        parent::__destruct();
    }

}