<?php
// Base EPP commands: hello, login and logout
date_default_timezone_set('UTC');
require_once('Examples/base.php');

function autoloadData($className) {


}

function autoloadRegistry($className) {
    $fileName = str_replace('Metaregistrar\\EPP\\', '', $className);
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $fileName = __DIR__ . '\\Registries\\' . $fileName . '\\eppConnection.php';
    } else {
        $fileName = __DIR__ . '/Registries/' . $fileName . '/eppConnection.php';
    }
    //echo "Test autoload registry $fileName\n";
    if (is_readable($fileName)) {
        //echo "Autoloaded registry $fileName\n";
        require($fileName);
    }
}

function autoloadProtocol($className) {
    // First load data elements
    $fileName = str_replace('Metaregistrar\\EPP\\', '', $className);
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $fileName = __DIR__ . '\\Protocols\\EPP\\eppData\\' . $fileName . '.php';
    } else {
        $fileName = __DIR__ . '/Protocols/EPP/eppData/' . $fileName . '.php';
    }
    //echo "Test autoload data $fileName\n";
    if (is_readable($fileName)) {
        //echo "Autoloaded data $fileName\n";
        require($fileName);
    }
    // Then load protocol files
    $fileName = str_replace('Metaregistrar\\', '', $className);
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $fileName = __DIR__ . '\\Protocols\\' . $fileName . '.php';
        // Support for EPP Request file structure
        if (strpos($className, 'Request')) {
            $fileName = str_replace('Protocols\\EPP\\', 'Protocols\\EPP\\eppRequests\\', $fileName);
        }
        // Support for EPP Response file structure
        if (strpos($className, 'Response')) {
            $fileName = str_replace('Protocols\\EPP\\', 'Protocols\\EPP\\eppResponses\\', $fileName);
        }
    } else {
        $fileName = __DIR__ . '/Protocols/' . str_replace('\\', '/', $fileName) . '.php';
        // Support for EPP Request file structure
        if (strpos($className, 'Request')) {
            $fileName = str_replace('Protocols/EPP/', 'Protocols/EPP/eppRequests/', $fileName);
        }
        // Support for EPP Response file structure
        if (strpos($className, 'Response')) {
            $fileName = str_replace('Protocols/EPP/', 'Protocols/EPP/eppResponses/', $fileName);
        }
    }

    //echo "Test autoload protocol $fileName\n";
    if (is_readable($fileName)) {
        //echo "Autoloaded protocol $fileName\n";
        require($fileName);
    }
}

spl_autoload_register('autoloadProtocol');
spl_autoload_register('autoloadRegistry');
