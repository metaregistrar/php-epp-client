<?php
/*
 * This object contains all the logic to create an EPP hello command
 */

class eppPollRequest extends eppRequest
{

    const POLL_REQ = 'req';
    const POLL_ACK = 'ack';

    function __construct($polltype,$messageid=null,$services=null,$extensions=null)
    {
        parent::__construct();

        #
        # sanity checks
        #
        if (($polltype!=self::POLL_REQ) && ($polltype!=self::POLL_ACK))
        {
            throw new eppException('Polltype needs to be REQ or ACK on poll request');
        }
        if (($polltype == self::POLL_ACK) && (!strlen($messageid)))
        {
            throw new eppException('Messageid needs to be filled on poll ACK request');
        }
        switch($polltype)
        {
            case self::POLL_REQ:
                $this->setRequest($polltype,$messageid);
                break;

            case self::POLL_ACK:
                $this->setRequest($polltype,$messageid);
            break;
        }
        $this->addSessionId();
    }

    function __destruct()
    {
        parent::__destruct();
    }


    public function setRequest($polltype,$messageid=null)
    {
        #
        # Create poll command
        #
        $poll = $this->createElement('poll');
        #
        # atrribute is req or ack
        #
        $poll->setAttribute('op',$polltype);
        if ($messageid)
        {
            $poll->setAttribute('msgID',$messageid);
        }
        $this->getCommand()->appendChild($poll);
    }
}