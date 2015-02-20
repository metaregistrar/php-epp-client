<?php
require("../autoloader.php");
/* This test file retrieves the latest test-domain-name-list (DNL) and gets the claim notice from the first item of this list. */

try {
    $tmchdnl = new Metaregistrar\EPP\tmchDnlConnection();
    $tmch = new Metaregistrar\EPP\tmchEppConnection();
    $list = $tmchdnl->getDnl();
    $linecounter = -1;
    foreach ($list as $line) {
        if (($linecounter > 0) && (strlen($line) > 0)) {
            list($domainname, $key, $datetime) = explode(',', $line);
            if ($domainname != '1' and $domainname != 'DNL') {
                echo $linecounter . ": " . $domainname . "\n";
                $k[$linecounter] = $key;
            }
        }
        $linecounter++;
    }
    echo "Select the number from one of the labels above to display the warning notice for this label\n:";
    $number = (int)fgets(STDIN);
    echo $tmch->showWarning($tmch->getCnis($k[$number]));

} catch (Metaregistrar\EPP\eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

function get_cnis($id) {
    $username = 'cnis143';
    $password = 'NcL$6#0MmCMIa2O3';
    $url = "https://test.tmcnis.org/cnis/$id.xml";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    $output = curl_exec($ch);
    curl_close($ch);
    var_dump($output);
}
