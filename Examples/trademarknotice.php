<?php
require("../autoloader.php");

use Metaregistrar\TMCH\dnlTmchConnection;
use Metaregistrar\TMCH\cnisTmchConnection;
use Metaregistrar\TMCH\tmchException;

$domain = 'nike';
$domainkey = null;
try {
    $dnl = new dnlTmchConnection();
    $dnl->setConnectionDetails('.');
    $cnis = new cnisTmchConnection();
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

} catch (tmchException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}


