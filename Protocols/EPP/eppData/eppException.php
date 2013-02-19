<?php

class eppException extends Exception
{
    private $reason;
    private $id;

    public function __construct($message="", $code=0 , Exception $previous = null, $reason = null, $id = null)
    {
        $this->reason = $reason;
        $this->id = $id;
        parent::__construct($message, $code, $previous);               
    }
    
    public function getId()
    {
        return $this->id;
    }
    

    public function getReason()
    {
        return $this->reason;
    }
}

