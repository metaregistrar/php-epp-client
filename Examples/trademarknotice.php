<?php
require("../autoloader.php");

$domain = 'nike';
$domainkey = null;
try {
    $dnl = new Metaregistrar\TMCH\dnlTmchConnection();
    $cnis = new Metaregistrar\TMCH\cnisTmchConnection();
    $list = $dnl->getDnl();
    foreach ($list as $line) {
        list($domainname, $key, $datetime) = explode(',', $line);
        if ($domainname == $domain) {
            $domainkey = $key;
        }
    }
    if ($domainkey) {
        echo $cnis->showWarning($cnis->getCnis($domainkey), true);
    } else {
        echo "Domain name not found in CNIS list\n";
    }
} catch (Metaregistrar\TMCH\tmchException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}


