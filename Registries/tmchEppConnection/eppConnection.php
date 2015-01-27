<?php
namespace Metaregistrar\EPP;

class tmchEppConnection extends eppConnection {

    private $lastinfo = null;

    public function __construct($logging=false) {
        parent::__construct($logging);
        if ($settings = $this->loadSettings(dirname(__FILE__))) {
            parent::setHostname($settings['hostname']);
            parent::setPort($settings['port']);
            parent::setUsername($settings['userid']);
            parent::setPassword($settings['password']);
        }
    }

    public function getCnis($key) {
        if (!is_string($key)) {
            throw new eppException("Key must be filled when requesting CNIS information");
        }
        $url = "https://".parent::getHostname()."/".$key.".xml";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, parent::getUsername().":".parent::getPassword());
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if(curl_errno($ch)) {
            throw new eppException(curl_error($ch));
        }
        $output = curl_exec($ch);
        $this->setLastInfo(curl_getinfo($ch));
        curl_close($ch);
        $result = new tmchClaimData();
        $result->loadXML($output);
        $result->setClaims();
        return $result;
    }


    public function showWarning($claimData) {
        echo "TRADEMARK NOTICE\n\n";
        echo "You have received this Trademark Notice because you have applied for a domain name which matches at least one trademark record submitted to the Trademark Clearinghouse\n\n";
        echo "You may or may not be entitled to register the domain name depending on your intended use and whether it is the same or significantly overlaps with the trademarks listed below.\n";
        echo "Your rights to register this domain name may or may not be protected as noncommercial use or 'fair use' by the laws of your country.\n\n";
        echo "Please read the trademark information below carefully, including the trademarks, jurisdictions and goods and services for which the trademarks are registered. Please be aware that not all jurisdictions review trademark applications closely, so some of the trademark information below may exist in a national or regional registry which does not conduct a thorough or substantive review of trademark rights prior to registration. If you have questions, you may want to consult an attorney or legal expert on trademarks and intellectual property for guidance.\n\n";
        echo "If you continue with this registration, you represent that, you have received and you understand this notice and to the best of your knowledge, your registration and use of the requested domain name will not infringe on the trademark rights listed below. The following ".$claimData->getClaimCount()." marks are listed in the Trademark Clearinghouse:\n\n";
        foreach ($claimData->getClaims() as $claim) {
            echo "Mark:           ".$claim->getMarkName()."\n";
            echo "Jurisdiction:   ".$claim->getJurisdiction()."\n";
            echo "Goods and services:\n                ".$claim->getGoodsAndServices()."\n";
            if (is_array($claim->getClasses())) {
                echo "International Class of Goods and services or Equivalent if applicable:\n";

                foreach ($claim->getClasses() as $classid => $class) {
                    echo "                ".$classid." - ".$class."\n";
                }
            }
            echo "Trademark Registrant:\n";
            $h =$claim->getHolder();
            echo "                Name: ".$h['name']."\n";
            echo "                Organization: ".$h['organization']."\n";
            echo "                Address: ".$h['street']."\n";
            echo "                City: ".$h['city']."\n";
            echo "                Postal Code: ".$h['postcode']."\n";
            echo "                Country: ".$h['country']."\n";
            echo "Trademark Registrant Contact:\n";
            $c =$claim->getContact();
            echo "                Name: ".$c['name']."\n";
            echo "                Organization: ".$c['organization']."\n";
            echo "                Address: ".$c['street']."\n";
            echo "                City: ".$c['city']."\n";
            echo "                Postal Code: ".$c['postcode']."\n";
            echo "                Country: ".$c['country']."\n";
            echo "                Phone: ".$c['phone']."\n";
            echo "                E-mail: ".$c['email']."\n";
        }
        echo "\n";
        //echo "This domain name label has previously been found to be used or registered abusively against the following trademarks accoring to the referenced decisions:\n";
        echo "For more information concerning the records in this notice, contact mijndomein.nl\n";


        echo "";
    }
    /**
     * @param null $lastinfo
     */
    public function setLastinfo($lastinfo) {
        $this->lastinfo = $lastinfo;
    }

    /**
     * @return null
     */
    public function getLastinfo() {
        return $this->lastinfo;
    }

}