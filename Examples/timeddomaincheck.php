<?php
require('../autoloader.php');

/*
 * This script checks for the availability of domain names
 *
 * You can specify multiple domain names to be checked
 */


function GeneratePassword($crypt = false, $pass = null, $len = 8, $regexp = '/^[a-z]{1}[a-z0-9]*$/i') {
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
        list($salt) = Tools::GeneratePassword();
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
    $random = GeneratePassword(false, null, $length, "/^[a-z]{1}[a-z0-9]*$/");


    $return = implode('', $random);
    return $return;

}

for ($i = 0; $i < 1500; $i++) {
    $domains[] = randomstring(20) . '.nl';
}

echo "Checking " . count($domains) . " domain names\n";
try {
    $conn = new sidnEppConnection(true);

    // Connect to the EPP server
    $mtime = microtime();
    $mtime = explode(" ", $mtime);
    $starttime = $mtime[1] + $mtime[0];
    if ($conn->connect()) {
        if (login($conn)) {
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
            logout($conn);
        }
    } else {
        echo "ERROR CONNECTING\n";
    }
    $mtime = microtime();
    $mtime = explode(" ", $mtime);
    $endtime = $mtime[1] + $mtime[0];
    $totaltime = ($endtime - $starttime);
    echo "Checks performed in " . $totaltime . " seconds\n\n";
} catch (Metaregistrar\EPP\eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}


function checkdomains($conn, $domains) {
    try {
        $check = new Metaregistrar\EPP\eppCheckRequest($domains);
        if ((($response = $conn->writeandread($check)) instanceof Metaregistrar\EPP\eppCheckResponse) && ($response->Success())) {
            $checks = $response->getCheckedDomains();

#            foreach ($checks as $check)
#            {
#                echo $check['domainname']." is ".($check['available'] ? 'free' : 'taken')." (".$check['reason'].")\n";
#            }
        } else {
            echo "ERROR2\n";
        }
    } catch (Metaregistrar\EPP\eppException $e) {
        echo 'ERROR1';
        echo $e->getMessage() . "\n";
    }
}