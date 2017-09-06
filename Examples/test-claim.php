<?php
require("../autoloader.php");

use Metaregistrar\TMCH\dnlTmchConnection;
use Metaregistrar\TMCH\cnisTmchConnection;
use Metaregistrar\TMCH\tmchException;
/* This test file retrieves the latest test-domain-name-list (DNL) and gets the claim notice from the first item of this list. */

try {
    $dnl = new dnlTmchConnection();
    $dnl->setConnectionDetails('');
    $cnis = new cnisTmchConnection();
    $cnis->setConnectionDetails('');
    $list = $dnl->getDnl();
    $linecounter = -1;
    $k = array();
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
    echo "Enter the number from one of the labels above to display the warning notice for this label\n:";
    $number = (int)fgets(STDIN);
    echo $cnis->showWarning($cnis->getCnis($k[$number]));

} catch (tmchException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

