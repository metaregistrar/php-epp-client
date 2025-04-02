<?php
namespace Metaregistrar\EPP;

class eppException extends \Exception {
    /**
     * @var string
     */
    private $reason = null;
    /**
     * @var string
     */
    private $class = null;
    /**
     * @var string
     */
    private $lastcommand = null;
    /**
     * @var $response
     */
    private $response = null;

    /**
     * eppException constructor.
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     * @param string $reason
     * @param int $id
     * @param string $command
     * @param \Metaregistrar\EPP\eppResponse|null $response
     */
    public function __construct($message = "", $code = 0, $previous = null, $reason = null, $command = null, $response = null) {
        $this->reason = $reason;
        $trace = $this->getTrace();
        $this->class = null;
        if (isset($trace[0]['class'])) {
            $this->class = $trace[0]['class'];
        }
        if ($command) {
            /* @var $class \Metaregistrar\EPP\eppRequest */
            $this->lastcommand = $command;
        }
        $this->response = $response;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getLastCommand() {
        return $this->lastcommand;
    }

    /**
     * @return string
     */
    public function getClass() {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getReason() {
        return $this->reason;
    }

    /**
     * @return \Metaregistrar\EPP\eppResponse|null
     */
    public function getResponse() {
        return $this->response;
    }
}

