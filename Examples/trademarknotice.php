<?php
require("../autoloader.php");

$domain = strtolower($_GET['domainname']);
$domainkey = null;

try {
    $tmchdnl = new Metaregistrar\EPP\tmchDnlConnection();
    $tmch = new Metaregistrar\EPP\tmchEppConnection();
    $list = $tmchdnl->getDnl();
    foreach ($list as $line) {
        list($domainname, $key, $datetime) = explode(',', $line);
        if ($domainname == $domain) {
            $domainkey = $key;
        }
    }
    if ($domainkey) {
        echo $tmch->showWarning($tmch->getCnis($domainkey), true);
    } else {
        echo "Domain name not found in CNIS list\n";
    }


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
    $info = curl_getinfo($ch);
    curl_close($ch);
    var_dump($output);
}
