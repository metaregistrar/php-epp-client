<?php
namespace Metaregistrar\EPP;


class atEppContactHandle extends eppContactHandle
{
    /**
     * Sets the contact handle
     * @param string $contactHandle
     * @return void
     */
    public function setContactHandle($contactHandle) {

        parent::setContactHandle($this->parseContactHandle($contactHandle));
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