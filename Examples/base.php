<?php

function greet($conn) {
    // Set this to true to see the server greeting
    $showgreeting = false;
    try {
        $greeting = new Metaregistrar\EPP\eppHelloRequest();
        if ((($response = $conn->writeandread($greeting)) instanceof Metaregistrar\EPP\eppHelloResponse) && ($response->Success())) {
            if ($showgreeting) {
                echo "Welcome to " . $response->getServerName() . ", date and time: " . $response->getServerDate() . "\n";
                $languages = $response->getLanguages();
                if (is_array($languages)) {
                    echo "Supported languages:\n";
                    foreach ($languages as $language) {
                        echo "-" . $language . "\n";
                    }
                }
                $versions = $response->getVersions();
                if (is_array($versions)) {
                    echo "Supported versions:\n";
                    foreach ($versions as $version) {
                        echo "-" . $version . "\n";
                    }
                }
                $services = $response->getServices();
                if (is_array($services)) {
                    echo "Supported services:\n";
                    foreach ($services as $service) {
                        echo "-" . $service . "\n";
                    }
                }
                $extensions = $response->getExtensions();
                if (is_array($extensions)) {
                    echo "Supported extensions:\n";
                    foreach ($extensions as $extension) {
                        echo "-" . $extension . "\n";
                    }
                }
            }
            return true;
        }
    } catch (Metaregistrar\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
    return false;
}


function login($conn) {
    try {
        $login = new Metaregistrar\EPP\eppLoginRequest;
        if ((($response = $conn->writeandread($login)) instanceof Metaregistrar\EPP\eppLoginResponse) && ($response->Success())) {
            return true;
        }
    } catch (Metaregistrar\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
    return false;
}


function logout($conn) {
    try {
        $logout = new Metaregistrar\EPP\eppLogoutRequest();
        if ((($response = $conn->writeandread($logout)) instanceof Metaregistrar\EPP\eppLogoutResponse) && ($response->Success())) {
            return true;
        } else {
            echo "Logout failed with message " . $response->getResultMessage() . "\n";
            return false;
        }
    } catch (Metaregistrar\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
    return false;
}