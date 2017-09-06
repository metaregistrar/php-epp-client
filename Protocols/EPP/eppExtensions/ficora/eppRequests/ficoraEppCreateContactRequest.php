<?php
namespace Metaregistrar\EPP;
/*
 *
 * FICORA has not made extensions, but adapted the standard EPP commands to their needs
 * SEE BELOW
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
    <epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
        <command>
            <create>
                <contact:create xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
                    <contact:id>1</contact:id>
                    <contact:role>5</contact:role>
                    <contact:type>1</contact:type>
                    <contact:postalInfo type="loc">
                        <contact:firstname>John</contact:firstname>
                        <contact:lastname>Doe</contact:lastname>
                        <contact:name>HR</contact:name>
                        <contact:org>Company name<contact:org>
                        <contact:birthDate>2005-04-03T22:00:00.0Z</contact:birthDate>
                        <contact:identity>123423A123F</contact:identity>
                        <contact:registernumber>1234312-5</contact:registernumber>
                        <contact:addr>
                            <contact:street>123 Example Dr.</contact:street>
                            <contact:street>Suite 100</contact:street>
                            <contact:street>Suite 100</contact:street>
                            <contact:city>Dulles</contact:city>
                            <contact:sp>VA</contact:sp>
                            <contact:pc>20166-6503</contact:pc>
                            <contact:cc>US</contact:cc>
                        </contact:addr>
                    </contact:postalInfo>
                    <contact:voice x="1234">+358401231234</contact:voice>
                    <contact:fax>+04040as</contact:fax>
                    <contact:email>jdoe@example.com</contact:email>
                    <contact:legalemail>jdoe@example.com</contact:legalemail>
                    <contact:authInfo>
                        <contact:pw>2fooBAR</contact:pw>
                    </contact:authInfo>
                    <contact:disclose flag="0">
                        <contact:addr/>
                        <contact:email/>
                    </contact:disclose>
                </contact:create>
            </create>
            <clTRID>ABC-12345</clTRID>
        </command>
    </epp>
*/
class ficoraEppCreateContactRequest extends eppCreateContactRequest {

    CONST FI_CONTACT_ROLE_ADMIN =2;
    CONST FI_CONTACT_ROLE_RESELLER = 3;
    CONST FI_CONTACT_ROLE_TECHNICAL = 4;
    CONST FI_CONTACT_ROLE_REGISTRANT = 5;

    CONST FI_CONTACT_TYPE_PRIVATE = 0;
    CONST FI_CONTACT_TYPE_COMPANY = 1;
    CONST FI_CONTACT_TYPE_CORPORATION = 2;
    CONST FI_CONTACT_TYPE_INSTITUTION = 3;
    CONST FI_CONTACT_TYPE_POLITICAL = 4;
    CONST FI_CONTACT_TYPE_TOWNSHIP = 5;
    CONST FI_CONTACT_TYPE_GOVERNMENT = 6;
    CONST FI_CONTACT_TYPE_COMMUNITY = 7;


    function __construct($createinfo, $contacttype='licensee') {
        
        parent::__construct($createinfo);
        // Ficora needs the xmlns attribute in the contact object
        $this->contactobject->setAttribute('xmlns:contact','urn:ietf:params:xml:ns:contact-1.0');
        $this->addSessionId();
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname) {
        if ($postalinfo = $this->contactobject->getElementsByTagName('contact:postalInfo')->item(0)) {
            $postalinfo->appendChild($this->createElement('contact:firstname',$firstname));
        }
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname) {
        $postalinfo = $this->contactobject->getElementsByTagName('contact:postalInfo')->item(0);
        $postalinfo->appendChild($this->createElement('contact:lastname',$lastname));
    }

    /**
     * @param int $role
     * @throws eppException
     */
    public function setRole($role) {
        if (($role < 2) || ($role > 5)) {
            throw new eppException('Ficora role may be 2,3,4 or 5');
        }
        $this->contactobject->appendChild($this->createElement('contact:role',$role));

    }

    /**
     * @param int $type
     * @throws eppException
     */
    public function setType($type) {
        if (($type < 0) || ($type > 7)) {
            throw new eppException('Ficora type may be 0, 1, 2, 3, 4, 5, 6 or 7');
        }
        $this->contactobject->appendChild($this->createElement('contact:type',$type));
    }

    /**
     * @param string $date
     * @throws eppException
     */
    public function setBirthdate($date) {
        if (($d = strtotime($date)) === false) {
            throw new eppException('setBirthdate requires a valid date');
        }
        if ($postalinfo = $this->contactobject->getElementsByTagName('contact:postalInfo')->item(0)) {
            $date = date('Y-m-d\TH:i:s.0\Z',$d);
            $postalinfo->appendChild($this->createElement('contact:birthDate',$date));
        }
    }

    /**
     * @param bool $bool
     */
    public function setIsfinnish($bool) {
        if ($postalinfo = $this->contactobject->getElementsByTagName('contact:postalInfo')->item(0)) {
            if ($bool) {
                $postalinfo->appendChild($this->createElement('contact:isfinnish','1'));
            } else {
                $postalinfo->appendChild($this->createElement('contact:isfinnish','0'));
            }
        }
    }

    /**
    * @param string $email
    */
    public function setLegalemail($email) {
        $this->contactobject->appendChild($this->createElement('contact:legalemail',$email));
    }

    /**
     * @param string $identity
     */
    public function setIdentity($identity) {
        if ($postalinfo = $this->contactobject->getElementsByTagName('contact:postalInfo')->item(0)) {
            $postalinfo->appendChild($this->createElement('contact:identity',$identity));
        }
    }

    /**
     * @param string $number
     */
    public function setRegisternumber($number) {
        if ($postalinfo = $this->contactobject->getElementsByTagName('contact:postalInfo')->item(0)) {
            $postalinfo->appendChild($this->createElement('contact:registernumber',$number));
        }
    }

    /**
     * @param $contactdisclose
     */
    public function setDisclose($contactdisclose) {
        if (!is_null($contactdisclose)) {
            $disclose = $this->createElement('contact:disclose');
            $disclose->setAttribute('flag',$contactdisclose);
            $disclose->appendChild($this->createElement('contact:addr'));
            $disclose->appendChild($this->createElement('contact:email'));
            $this->contactobject->appendChild($disclose);
        }
    }

}