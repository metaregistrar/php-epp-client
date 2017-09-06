<?php
require('../autoloader.php');


use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppCheckDomainRequest;
use Metaregistrar\EPP\eppCheckDomainResponse;

/*
 * This script checks for the availability of domain names
 *
 * You can specify multiple domain names to be checked
 */


function GeneratePassword($crypt = false, $len = 8, $regexp = '/^[a-z]{1}[a-z0-9]*$/i') {
    $testpass = null;
    $pass = null;
    while (strlen($pass) < $len) {
        $testpass .= chr(rand(48, 122));
        if (preg_match($regexp, $testpass)) {
            $pass = $testpass;
        } else {
            $testpass = $pass;
        }
    }
    $array[] = $pass;
    if ($crypt) {
        list($salt) = GeneratePassword();
        $array[] = crypt($pass, '$1$' . $salt);
    }
    return $array;
}

function randomstring($length) {
    /*$c = "abcdefghijklmnopqrstuvwxyz";
    $rand = '';
    srand((double)microtime()*1000000);
    for ($i=0; $i<$length; $i++)
    {
        $rand .= $c[rand()%strlen($c)];
    }
    return $rand;*/
    $random = GeneratePassword(false, $length, "/^[a-z]{1}[a-z0-9]*$/");


    $return = implode('', $random);
    return $return;

}

for ($i = 0; $i < 1500; $i++) {
    $domains[] = randomstring(20) . '.nl';
}

echo "Checking " . count($domains) . " domain names\n";
try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('')) {
        // Connect to the EPP server
        $mtime = microtime();
        $mtime = explode(" ", $mtime);
        $starttime = $mtime[1] + $mtime[0];
        if ($conn->login()) {
            $counter = 0;
            while ($counter < count($domains)) {
                $list[] = $domains[$counter];
                $counter++;
                if (($counter % 10) == 0) {
                    $mstime = microtime();
                    $mstime = explode(" ", $mstime);
                    $mstime = $mstime[1] + $mstime[0];
                    $startstime = $mstime;
                    checkdomains($conn, $list);
                    $mstime = microtime();
                    $mstime = explode(" ", $mstime);
                    $endstime = $mstime[1] + $mstime[0];
                    $totalstime = ($endstime - $startstime);
                    echo "Check: " . $totalstime . " seconds\n\n";
                    unset($list);
                }
            }
            $conn->logout();
        }
        $mtime = microtime();
        $mtime = explode(" ", $mtime);
        $endtime = $mtime[1] + $mtime[0];
        $totaltime = ($endtime - $starttime);
        echo "Checks performed in " . $totaltime . " seconds\n\n";
    }
} catch (eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}

/**
 * @param $conn Metaregistrar\EPP\eppConnection
 * @param $domains array
 */
function checkdomains($conn, $domains) {
    try {
        $check = new eppCheckDomainRequest($domains);
        if ($response = $conn->request($check)) {
            /* @var $response eppCheckDomainResponse */
            $checks = $response->getCheckedDomains();
            foreach ($checks as $check) {
                echo $check['domainname']." is ".($check['available'] ? 'free' : 'taken')." (".$check['reason'].")\n";
            }
        } else {
            echo "ERROR2\n";
        }
    } catch (eppException $e) {
        echo 'ERROR1';
        echo $e->getMessage() . "\n";
    }
}