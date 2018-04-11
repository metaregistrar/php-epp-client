<?php
namespace Metaregistrar\EPP;
/*

<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:ssl="http://www.metaregistrar.com/epp/ssl-1.0">
    <command>
        <renew>
            <ssl:renew>
                <!--Base64 encoded csr -->
                <ssl:certificateId>33</ssl:certificateId>
                <ssl:csr>LS0tLS1CRUdJTiBDRVJUSUZJQ0FURSBSRVFVRVNULS0tLS0KTUlJQy9EQ0NBZVFDQVFBd1lqRUxNQWtHQTFVRUJoTUNUa3d4RlRBVEJnTlZCQWdNREZwMWFXUWdhRzlzYkdGdQpaREVPTUF3R0ExVUVCd3dGUjI5MVpHRXhGakFVQmdOVkJBb01EVzFsZEdGeVpXZHBjM1J5WVhJeEZEQVNCZ05WCkJBTU1DMlY0WVcxd2JHVXVZMjl0TUlJQklqQU5CZ2txaGtpRzl3MEJBUUVGQUFPQ0FROEFNSUlCQ2dLQ0FRRUEKdzh1S1h5bFdTVCtkbG1ieHhPb09CQ2JsT1NXMG8rVmI2N0ZkN2VrYUtYMkJYeFJFM2E5ZkdVNHJMUWxkZlNFcApSUFhyMkRjVU01MHRscFhIdDNFOEpCZ2E0VlRxTGEvMkhrTVdjRzlBLzI1SUFYa0Q3TU42VFRwUU9MRkEycy9NCnBUSTNkeFBiSndNZWsvTUFYdG0xajNHUEJEQU9FQUtpdEtEbzd2MlJCZTVUOWx4YnRFOC9zSzUzL2pjR2dQUEEKNU1VMXovckZHY0IyVTZsTUw3cEF4VlM5bVdDL0JvNVhFVVhZaGtONzhQRG5HZDVZd09DY2I1N2Zsb2Z4d0dFVQpqdHAyQjI5c0w5WVdUSk12Ty9zYUVaL1JJekZzWStEb3QvY0pRSzRCKzQwaTFXT1oxMVlpZjB3THcyWTBhRmJ1CjNVWXo5RTN1R3Voa05VdDYralFxOXdJREFRQUJvRlV3VXdZSktvWklodmNOQVFrT01VWXdSREJDQmdOVkhSRUUKT3pBNWdoRjBaWE4wTVM1bGVHRnRjR3hsTG1OdmJZSVJkR1Z6ZERJdVpYaGhiWEJzWlM1amIyMkNFWFJsYzNRegpMbVY0WVcxd2JHVXVZMjl0TUEwR0NTcUdTSWIzRFFFQkN3VUFBNElCQVFDTlM2aGpSaUlkL0xRVEFiQ3hRRDF2Cnp3SmR6anlibjA3dzdYZ2hpaXhpb1Q2VkRmQzY4UGdGZVlPQ3RpQitBSHJ0UkZ6T1ROWmdSVTRyZFZIM0xJdmYKd1Q3SUxEcmtGTTFld0gxcWlnRUdsenFkTXZkSHNCeHdIM2VMcGVnVnVZaEYwajBUNkZCdG5ha1pFSVBxOVJreApCTUhuRFNUT3FSa3lvaldTcTJoblE4RFY0R1NiSnpmRXEwZTU4VTI2MDJSZlpjRFBpZjBPdDVVYnM5L2F1UVlhCmsxdWt0RU5QOFVIVnFCaW92S1lLa0NCOGFxR3hzZkZkMzVjRzV5ZEFrV3J3bTFxL2tqaTlEald1WDNUMklZZ0IKNlJwTnFYU1IwV1BRanQ0aWZ1SlBZeXRJa2tUNDBwMC95c2lYd0ZpbHRBN0lQelRZNGJCam9hQlRjTW9tV0ZmUQotLS0tLUVORCBDRVJUSUZJQ0FURSBSRVFVRVNULS0tLS0K</ssl:csr>
                <ssl:product>comodo_multi_ev</ssl:product>
                <ssl:years>1</ssl:years>
                <ssl:hosts>
                    <ssl:host>
                        <ssl:name>example.com</ssl:name>
                        <ssl:validation>EMAIL</ssl:validation>
                        <ssl:email>admin@example.com</ssl:email>
                    </ssl:host>
                    <ssl:host>
                        <ssl:name>test1.example.com</ssl:name>
                        <ssl:validation>DNS</ssl:validation>
                    </ssl:host>
                    <ssl:host>
                        <ssl:name>test2.example.com</ssl:name>
                        <ssl:validation>DNS</ssl:validation>
                    </ssl:host>
                    <ssl:host>
                        <ssl:name>test3.example.com</ssl:name>
                        <ssl:validation>DNS</ssl:validation>
                    </ssl:host>
                </ssl:hosts>
                <ssl:approver>
                    <ssl:email>hostmaster@example.com</ssl:email>
                    <ssl:saEmail>john@metaregistrar.com</ssl:saEmail>
                    <ssl:phone>+31.612345678</ssl:phone>
                    <ssl:firstName>John</ssl:firstName>
                    <ssl:lastName>De Tester</ssl:lastName>
                    <ssl:company>Metaregistrar</ssl:company>
                    <ssl:department>Infra</ssl:department>
                    <ssl:companyRegistration>57931224</ssl:companyRegistration>
                    <ssl:street>Zuidelijk Halfrond 1</ssl:street>
                    <ssl:street>Room 1.11</ssl:street>
                    <ssl:postalCode>2801 DD</ssl:postalCode>
                    <ssl:city>Gouda</ssl:city>
                </ssl:approver>
                <ssl:language>nl</ssl:language>

            </ssl:renew>
        </renew>
        <clTRID>5a27b8d011a0e</clTRID>
    </command>
</epp>

*/

class metaregSslRenewRequest extends eppRequest {

    const VALIDATION_DNS = 'DNS';
    const VALIDATION_FILE = 'FILE';
    const VALIDATION_EMAIL = 'EMAIL';

    private $renew = null;
    private $hosts = null;
    private $approver = null;
    private $language = null;

    function __construct($certificateid, $csr, $product, $language='en', $years = 1, $reissue = false) {
        parent::__construct();
        $renew = $this->createElement('renew');
        $this->renew = $this->createElement('ssl:renew');
        if (!$this->rootNamespaces()) {
            $this->renew->setAttribute('xmlns:ssl','http://www.metaregistrar.com/epp/ssl-1.0');
        }
        if (base64_encode(base64_decode($csr)) !== $csr){
            throw new eppException("CSR data must be base64-encoded upon metaregSslRenewRequest call");
        }
        $this->renew->appendChild($this->createElement('ssl:certificateId',$certificateid));
        if ($reissue) {
            $this->renew->appendChild($this->createElement('ssl:type','reissue'));
        }
        $this->renew->appendChild($this->createElement('ssl:csr',$csr));
        $this->renew->appendChild($this->createElement('ssl:product',$product));
        $this->renew->appendChild($this->createElement('ssl:years',$years));

        $renew->appendChild($this->renew);
        $this->getCommand()->appendChild($renew);
        $this->language = $language;
        parent::addSessionId();
    }

    /**
     * @param string $hostname
     * @param string $validation
     * @param string|null $email
     * @throws eppException
     */
    public function addHost($hostname, $validation, $email=null) {
        if ($this->renew) {
            if (!in_array($validation,[self::VALIDATION_DNS,self::VALIDATION_EMAIL,self::VALIDATION_FILE])) {
                throw new eppException("Validation must be DNS, FILE or EMAIL on ssl addHosts request");
            }
            if (!$this->hosts) {
                $this->hosts = $this->createElement('ssl:hosts');
                $this->renew->appendChild($this->hosts);
            }
            $host = $this->createElement('ssl:host');
            $host->appendChild($this->createElement('ssl:name',$hostname));
            $host->appendChild($this->createElement('ssl:validation',$validation));
            if ($validation == self::VALIDATION_EMAIL) {
                if (!$email) {
                    throw new eppException("Email address must be valid on SSL addHosts request when VALIDATION_EMAIL is selected");
                }
                $host->appendChild($this->createElement('ssl:email',$email));
            }
            $this->hosts->appendChild($host);
        }

    }

    /**
     * @param string $email
     * @param string $phone
     * @param string $firstname
     * @param string $lastname
     * @param string $street
     * @param string $postalcode
     * @param string $city
     * @param string|null $saemail
     * @param string|null $company
     * @param string|null $companyregistration
     * @param string|null $department
     * @throws eppException
     */
    public function setApprover($email, $phone, $firstname, $lastname, $street, $postalcode, $city, $saemail=null, $company=null, $companyregistration=null, $department=null ) {
        if ($this->approver) {
            throw new eppException("Only one approver may be set on sslRenewRequest, more than one is not possible");
        }
        if ($this->renew) {
            $this->approver = $this->createElement('ssl:approver');
            $this->approver->appendChild($this->createElement('ssl:email',$email));
            if ($saemail) {
                $this->approver->appendChild($this->createElement('ssl:saEmail',$saemail));
            }
            $this->approver->appendChild($this->createElement('ssl:phone',$phone));
            $this->approver->appendChild($this->createElement('ssl:firstName',$firstname));
            $this->approver->appendChild($this->createElement('ssl:lastName',$lastname));
            $this->approver->appendChild($this->createElement('ssl:street',$street));
            $this->approver->appendChild($this->createElement('ssl:postalCode',$postalcode));
            $this->approver->appendChild($this->createElement('ssl:city',$city));
            if ($company) {
                $this->approver->appendChild($this->createElement('ssl:company',$company));
            }
            if ($companyregistration) {
                $this->approver->appendChild($this->createElement('ssl:companyRegistration',$companyregistration));
            }
            if ($department) {
                $this->approver->appendChild($this->createElement('ssl:department',$department));
            }
            $this->renew->appendChild($this->approver);
            $this->renew->appendChild($this->createElement('ssl:language',$this->language));
        }
    }

}