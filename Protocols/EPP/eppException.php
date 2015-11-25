<?php
namespace Metaregistrar\EPP;

class eppException extends \Exception {
    private $reason;
    private $id;
    private $class;

    public function __construct($message = "", $code = 0, \Exception $previous = null, $reason = null, $id = null) {
        $this->reason = $reason;
        $this->id = $id;
        $trace = $this->getTrace();
        $this->class = $trace[0]['class'];
        parent::__construct($message, $code, $previous);
    }

    public function getClass() {
        return $this->class;
    }

    public function getId() {
        return $this->id;
    }


    public function getReason() {
        return $this->reason;
    }
}

