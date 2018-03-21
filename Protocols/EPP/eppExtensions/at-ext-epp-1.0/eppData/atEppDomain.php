<?php
namespace Metaregistrar\EPP;


class atEppDomain extends eppDomain
{
    /**
     *
     * @param string $registrant
     */
    public function setRegistrant($registrant) {
        if ($registrant instanceof eppContactHandle) {
           parent::setRegistrant($registrant);
        } else {
            parent::setRegistrant($this->parseContactHandle($registrant));
        }
    }

    /**
     * Removes a possible -NICAT suffix, -NICAT is appended by
     * registry automatically
     *
     * @param $handle
     * @return string
     */
    protected function parseContactHandle($handle)
    {
        if(!empty($handle)) {
            $handle = strtoupper($handle);
            $handle = str_replace("-NICAT", "", $handle);
        }
        return $handle;
    }
}