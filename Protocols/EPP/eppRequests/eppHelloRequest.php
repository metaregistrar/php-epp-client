<?php
include_once(dirname(__FILE__).'/../eppRequest.php');
/*
 * This object contains all the logic to create an EPP hello command
 */

class eppHelloRequest extends eppRequest
{
    function __construct()
    {
        parent::__construct();
        $this->hello = $this->createElement('hello');
        $this->epp->appendChild($this->hello);
    }

    function __destruct()
    {
        parent::__destruct();
    }

}