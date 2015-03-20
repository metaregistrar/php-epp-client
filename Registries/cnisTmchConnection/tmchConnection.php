<?php
namespace Metaregistrar\TMCH;

class cnisTmchConnection extends tmchConnection {

    public function __construct() {
        if ($settings = $this->loadSettings(dirname(__FILE__))) {
            parent::setHostname($settings['hostname']);
            parent::setPort($settings['port']);
            parent::setUsername($settings['userid']);
            parent::setPassword($settings['password']);
        }
    }

    public function getCnis($key) {
        if (!is_string($key)) {
            throw new tmchException("Key must be filled when requesting CNIS information");
        }
        $url = "https://" . parent::getHostname() . "/" . $key . ".xml";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, parent::getUsername() . ":" . parent::getPassword());
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if (curl_errno($ch)) {
            throw new tmchException(curl_error($ch));
        }
        $output = curl_exec($ch);
        $this->setLastInfo(curl_getinfo($ch));
        curl_close($ch);
        if (strpos($output,'404 Not Found')!==false)
        {
            throw new tmchException("Requested URL was not found on this server");
        }
        $result = new tmchClaimData();
        $result->loadXML($output);
        $result->setClaims();
        return $result;
    }


    public function showWarning(tmchClaimData $claimData, $html = false) {
        $result = "TRADEMARK NOTICE\n\n";
        $result .=  "You have received this Trademark Notice because you have applied for a domain name which matches at least one trademark record submitted to the Trademark Clearinghouse\n\n";
        $result .= "You may or may not be entitled to register the domain name depending on your intended use and whether it is the same or significantly overlaps with the trademarks listed below.\n";
        $result .= "Your rights to register this domain name may or may not be protected as noncommercial use or 'fair use' by the laws of your country.\n\n";
        $result .= "Please read the trademark information below carefully, including the trademarks, jurisdictions and goods and services for which the trademarks are registered. Please be aware that not all jurisdictions review trademark applications closely, so some of the trademark information below may exist in a national or regional registry which does not conduct a thorough or substantive review of trademark rights prior to registration. If you have questions, you may want to consult an attorney or legal expert on trademarks and intellectual property for guidance.\n\n";
        $result .= "If you continue with this registration, you represent that, you have received and you understand this notice and to the best of your knowledge, your registration and use of the requested domain name will not infringe on the trademark rights listed below. The following " . $claimData->getClaimCount() . " marks are listed in the Trademark Clearinghouse:\n\n";
        foreach ($claimData->getClaims() as $claim) {
            /* @var $claim tmchClaim */
            $result .= "Mark:           " . $claim->getMarkName() . "\n";
            $result .= "Jurisdiction:   " . $claim->getJurisdiction() . "\n";
            $result .= "Goods and services:\n                " . $claim->getGoodsAndServices() . "\n";
            if (is_array($claim->getClasses())) {
                $result .= "International Class of Goods and services or Equivalent if applicable:\n";
                foreach ($claim->getClasses() as $classid => $class) {
                    $result .= "                " . $classid . " - " . $class . "\n";
                }
            }
            $result .= "Trademark Registrant:\n";
            $h = $claim->getHolder();
            $result .= "                Name: " . $h['name'] . "\n";
            $result .= "                Organization: " . $h['organization'] . "\n";
            $result .= "                Address: " . $h['street'] . "\n";
            $result .= "                City: " . $h['city'] . "\n";
            $result .= "                Postal Code: " . $h['postcode'] . "\n";
            $result .= "                Country: " . $h['country'] . "\n";
            $result .= "Trademark Registrant Contact:\n";
            $c = $claim->getContact();
            $result .= "                Name: " . $c['name'] . "\n";
            $result .= "                Organization: " . $c['organization'] . "\n";
            $result .= "                Address: " . $c['street'] . "\n";
            $result .= "                City: " . $c['city'] . "\n";
            $result .= "                Postal Code: " . $c['postcode'] . "\n";
            $result .= "                Country: " . $c['country'] . "\n";
            $result .= "                Phone: " . $c['phone'] . "\n";
            $result .= "                E-mail: " . $c['email'] . "\n";
        }
        $result .= "\n";
        //echo "This domain name label has previously been found to be used or registered abusively against the following trademarks accoring to the referenced decisions:\n";
        $result .= "For more information concerning the records in this notice, contact mijndomein.nl\n";
        return $result;
    }



}