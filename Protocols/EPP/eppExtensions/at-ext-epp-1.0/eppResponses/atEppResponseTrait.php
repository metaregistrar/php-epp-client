<?php
namespace Metaregistrar\EPP;


trait atEppResponseTrait
{
    /**
     *
     * @return string client ticket request id
     */
    public function getClTrId() {

        $xpath = $this->xPath();
        $result = $xpath->query("/epp:epp/epp:response/epp:trID/epp:clTRID");

        if (is_object($result) && ($result->length > 0)) {
            return trim($result->item(0)->nodeValue);
        } else {
            return null;
        }
    }

    /**
     *
     * @return string server ticket request id
     */
    public function getSvTrId() {
        $xpath = $this->xPath();
        $result = $xpath->query("/epp:epp/epp:response/epp:trID/epp:svTRID");
        if (is_object($result) && ($result->length > 0)) {
            return trim($result->item(0)->nodeValue);
        } else {
            return null;
        }
    }

    /**
     *
     * @return array
     */
    public function getExtensionResult() {
        $xpath = $this->xPath();
        $xpath->registerNamespace ( "at-ext" , atEppConstants::atExtResultNamespaceUri );

        $result = $this->getXPathExtension($xpath);

        if (is_object($result) && ($result->length > 0)) {
            $resultList=[];
            for($i=0;$i < $result->length;$i++) {
                $xpathExtensionIndex = $i+1;
                $code_ = $this->getExtensionResultCode($xpathExtensionIndex);
                  $resultList[] =
                ['code' => $code_,'severity' => $this->getExtensionResultSeverity($xpathExtensionIndex),
                'message' => $this->getExtensionResultMessage($xpathExtensionIndex), 'details' =>  $this->getExtensionResultDetails($xpathExtensionIndex)];
            }
            return $resultList;
        } else {
            return null;
        }
    }

    private function getXPathExtension($xpath)
    {
        $xpath = $this->xPath();
        $xpath->registerNamespace ( "at-ext" , atEppConstants::atExtResultNamespaceUri );
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/at-ext:conditions/at-ext:condition');

        if (!is_object($result) || ($result->length == 0)) {
            $result = $xpath->query('/epp:epp/epp:response/epp:msgq/epp:extension/at-ext:conditions/at-ext:condition');
        }
        return $result;

    }


    /**
     *
     * @return string extension result code
     */
    public function getExtensionResultCode($xpathExtensionIndex=1) {
        $xpath = $this->xPath();
        $xpath->registerNamespace ( "at-ext" , atEppConstants::atExtResultNamespaceUri );
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/at-ext:conditions/at-ext:condition[' . $xpathExtensionIndex . ']/@code');

        if (is_object($result) && ($result->length > 0)) {
            return trim($result->item(0)->nodeValue);
        } else {
            $result = $xpath->query('/epp:epp/epp:response/epp:msgq/epp:extension/at-ext:conditions/at-ext:condition[' . $xpathExtensionIndex . ']/@code');
            if (is_object($result) && ($result->length > 0)) {
                return trim($result->item(0)->nodeValue);
            }
        }
        return null;
    }



    /**
     *
     * @return string extension result severity
     */
    public function getExtensionResultSeverity($xpathExtensionIndex=1) {
        $xpath = $this->xPath();
        $xpath->registerNamespace ( "at-ext" , atEppConstants::atExtResultNamespaceUri );
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/at-ext:conditions/at-ext:condition[' . $xpathExtensionIndex . ']/@severity');
        if (is_object($result) && ($result->length > 0)) {
            return trim($result->item(0)->nodeValue);
        } else {
            $result = $xpath->query('/epp:epp/epp:response/epp:msgq/epp:extension/at-ext:conditions/at-ext:condition[' . $xpathExtensionIndex . ']/@severity');
            if (is_object($result) && ($result->length > 0)) {
                return trim($result->item(0)->nodeValue);
            }
        }
        return null;
    }

    /**
     *
     * @return string extension result severity
     */
    public function getExtensionResultMessage($xpathExtensionIndex=1) {
        $xpath = $this->xPath();
        $xpath->registerNamespace ( "at-ext" , atEppConstants::atExtResultNamespaceUri );
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/at-ext:conditions/at-ext:condition[' . $xpathExtensionIndex . ']/at-ext:msg');
        if (is_object($result) && ($result->length > 0)) {
            return trim($result->item(0)->nodeValue);
        } else {
            $result = $xpath->query('/epp:epp/epp:response/epp:msgq/epp:extension/at-ext:conditions/at-ext:condition[' . $xpathExtensionIndex . ']/at-ext:msg');
            if (is_object($result) && ($result->length > 0)) {
                return trim($result->item(0)->nodeValue);
            } else {
                return null;
            }
        }
    }

    /**
     *
     * @return string extension result severity
     */
    public function getExtensionResultDetails($xpathExtensionIndex=1) {
        $xpath = $this->xPath();
        $xpath->registerNamespace ( "at-ext" , atEppConstants::atExtResultNamespaceUri );
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/at-ext:conditions/at-ext:condition[' . $xpathExtensionIndex . ']/at-ext:details');
        if (is_object($result) && ($result->length > 0)) {
            return trim($result->item(0)->nodeValue);
        } else {
            $result = $xpath->query('/epp:epp/epp:response/epp:msgq/epp:extension/at-ext:conditions/at-ext:condition[' . $xpathExtensionIndex . ']/at-ext:details');
            if (is_object($result) && ($result->length > 0)) {
                return trim($result->item(0)->nodeValue);
            } else {
                return null;
            }
        }
    }


    /**
     *
     * @return string result created domain
     */
    public function getDomainCreated() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:creData/domain:name');
        if (is_object($result) && ($result->length > 0)) {
            return trim($result->item(0)->nodeValue);
        } else {
            return null;
        }
    }

    /**
     *
     * @return string result create date
     */
    public function getDomainCreateDate() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:creData/domain:crDate');
        if (is_object($result) && ($result->length > 0)) {
            return trim($result->item(0)->nodeValue);
        } else {
            return null;
        }
    }


    public function Success() {
        $resultcode = $this->getResultCode();
        $success = ($resultcode[0] == '1');
        if (!$success) {
            switch ($resultcode[1]) {
                case '0':
                    $this->setProblemtype('syntax');
                    break;
                case '1':
                    $this->setProblemtype('implementation-specific');
                    break;
                case '2':
                    $this->setProblemtype('security');
                    break;
                case '3':
                    $this->setProblemtype('data management');
                    break;
                case '4':
                    $this->setProblemtype('server system');
                    break;
                case '5':
                    $this->setProblemtype('connection management');
                    break;
            }
            $resultmessage = $this->getResultMessage();

            $errorstring = "Error $resultcode: $resultmessage";
            $id = null;
            $value = $this->getResultValue();
            if ($value) {
                $id = 'value:' . $value;
            }
            $resultcontactid = $this->getResultContactId();
            if ($resultcontactid) {
                $id = 'contactid:' . $resultcontactid;
            }
            $resulthostname = $this->getResultHostName();
            if ($resulthostname) {
                $id = 'hostname:' . $resulthostname;
            }
            $resultdomainname = $this->getResultDomainName();
            if ($resultdomainname) {
                $id = 'domainname:' . $resultdomainname;
            }
            $resultstatus = $this->getResultHostStatus();
            if ($resultstatus) {
                $id = 'status:' . $resultstatus;
            }
            $resultaddr = $this->getResultHostAddr();
            if ($resultaddr) {
                $id = 'hostaddr:' . $resultaddr;
            }
            if ($id) {
                $errorstring .= '; ' . $id;
            }
            $resultreason = $this->getResultReason();
            if (strlen($resultreason)) {
                $errorstring .= ' (' . $resultreason . ')';
            }

            $extendedReason_ = json_encode($this->getExtensionResult());

            throw new eppException($errorstring, $resultcode, null, $extendedReason_, $id);
        } else {
            return true;
        }
    }

}