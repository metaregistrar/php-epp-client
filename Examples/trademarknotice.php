<?php
require("../autoloader.php");

$domain = 'nike';
$domainkey = null;
try {
    $dnl = new Metaregistrar\TMCH\dnlTmchConnection();
    $dnl->setConnectionDetails('.');
    $cnis = new Metaregistrar\TMCH\cnisTmchConnection();
    $cnis->setConnectionDetails('');
    $list = $dnl->getDnl();
    if (count($list)==1) {
        echo "empty list received\n";
        echo $list[0]."\n";
    } else {
        foreach ($list as $line) {
            if (strlen($line)>0) {
                list($domainname, $key) = explode(',', $line);
                if ($domainname == $domain) {
                    $domainkey = $key;
                }
            }
        }
        if ($domainkey) {
            echo $cnis->showWarning($cnis->getCnis($domainkey), false);
        } else {
            echo "Domain name not found in CNIS list\n";
        }

    }

} catch (Metaregistrar\TMCH\tmchException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}


