<?php
namespace Metaregistrar\TMCH;

class cnisTmchConnection extends tmchConnection {

    public function getCnis($key) {
        if (!is_string($key)) {
            throw new tmchException("Key must be filled when requesting CNIS information");
        }
        if (is_null(parent::getHostname()) || (parent::getHostname()=='')) {
            throw new tmchException("Hostname must be set when requesting CNIS information");
        }
        $url = "https://" . parent::getHostname() . "/" . $key . ".xml";
        if ($this->logging) {
            echo "Calling interface $url\n\n";
        }
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
        if ($this->logging) {
            echo "Interface returned response:\n$output\n\n";
        }
        if (strlen($output)==0) {
            throw new tmchException("Empty output received from CNIS service");
        }
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
        $breakchar = ($html?"<br/>":"\n");
        $result = "TRADEMARK NOTICE".$breakchar.$breakchar;
        $result .=  "You have received this Trademark Notice because you have applied for a domain name which matches at least one trademark record submitted to the Trademark Clearinghouse".$breakchar.$breakchar;
        $result .= "You may or may not be entitled to register the domain name depending on your intended use and whether it is the same or significantly overlaps with the trademarks listed below.".$breakchar;
        $result .= "Your rights to register this domain name may or may not be protected as noncommercial use or 'fair use' by the laws of your country.".$breakchar.$breakchar;
        $result .= "Please read the trademark information below carefully, including the trademarks, jurisdictions and goods and services for which the trademarks are registered. Please be aware that not all jurisdictions review trademark applications closely, so some of the trademark information below may exist in a national or regional registry which does not conduct a thorough or substantive review of trademark rights prior to registration. If you have questions, you may want to consult an attorney or legal expert on trademarks and intellectual property for guidance.".$breakchar.$breakchar;
        $result .= "If you continue with this registration, you represent that, you have received and you understand this notice and to the best of your knowledge, your registration and use of the requested domain name will not infringe on the trademark rights listed below. The following " . $claimData->getClaimCount() . " marks are listed in the Trademark Clearinghouse:".$breakchar.$breakchar;
        foreach ($claimData->getClaims() as $claim) {
            /* @var $claim tmchClaim */
            $result .= "Mark:           " . $claim->getMarkName() .$breakchar;
            $result .= "Jurisdiction:   " . $claim->getJurisdiction() .$breakchar;
            $result .= "Goods and services:\n                " . $claim->getGoodsAndServices() .$breakchar;
            if (is_array($claim->getClasses())) {
                $result .= "International Class of Goods and services or Equivalent if applicable:".$breakchar;
                foreach ($claim->getClasses() as $classid => $class) {
                    $result .= "                " . $classid . " - " . $class .$breakchar;
                }
            }
            $result .= "Trademark Registrant:".$breakchar;
            $h = $claim->getHolder();
            $result .= "                Name: " . $h['name'] .$breakchar;
            $result .= "                Organization: " . $h['organization'] .$breakchar;
            $result .= "                Address: " . $h['street'] .$breakchar;
            $result .= "                City: " . $h['city'] .$breakchar;
            $result .= "                Postal Code: " . $h['postcode'] .$breakchar;
            $result .= "                Country: " . $h['country'] .$breakchar;
            $result .= "Trademark Registrant Contact:".$breakchar;
            $c = $claim->getContact();
            $result .= "                Name: " . $c['name'] .$breakchar;
            $result .= "                Organization: " . $c['organization'] .$breakchar;
            $result .= "                Address: " . $c['street'] .$breakchar;
            $result .= "                City: " . $c['city'] .$breakchar;
            $result .= "                Postal Code: " . $c['postcode'] .$breakchar;
            $result .= "                Country: " . $c['country'] .$breakchar;
            $result .= "                Phone: " . $c['phone'] .$breakchar;
            $result .= "                E-mail: " . $c['email'] .$breakchar;
        }
        $result .= $breakchar;
        //echo "This domain name label has previously been found to be used or registered abusively against the following trademarks accoring to the referenced decisions:\n";
        $result .= "For more information concerning the records in this notice, contact your domain name registrar".$breakchar;
        return $result;
    }



}