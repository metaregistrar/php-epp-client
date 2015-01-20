<?php
// Base EPP commands: hello, login and logout
date_default_timezone_set('UTC');
require_once('base.php');

function autoloadData($className) {
    $fileName = str_replace('Metaregistrar\\EPP\\','',$className);
    $fileName = __DIR__.'\Protocols\\EPP\\eppData\\'.$fileName.'.php';
    //echo "Test autoload data $fileName\n";
    if (is_readable($fileName))
    {
        //echo "Autoload data $fileName\n";
        require($fileName);
    }

}

function autoloadRegistry($className) {
    $fileName = str_replace('Metaregistrar\\EPP\\','',$className);
    $fileName = __DIR__.'\\Registries\\'.$fileName.'\\eppConnection.php';
    if (is_readable($fileName))
    {
        //echo "Autoload registry $fileName\n";
        require($fileName);
    }
}

function autoloadProtocol($className) {
    $fileName = str_replace('Metaregistrar\\','',$className);
    $fileName = __DIR__.'\\Protocols\\'.$fileName.'.php';
    // Support for EPP Request file structure
    if (strpos($className,'Request'))
    {
        $fileName = str_replace('Protocols\\EPP\\','Protocols\\EPP\\eppRequests\\',$fileName);
    }
    // Support for EPP Response file structure
    if (strpos($className,'Response'))
    {
        $fileName = str_replace('Protocols\\EPP\\','Protocols\\EPP\\eppResponses\\',$fileName);
    }
    // Support for EPP launch-1.0 file structure
    if (strpos($className,'eppLaunch'))
    {
        $fileName = str_replace('Protocols\\EPP\\eppRequests\\','Protocols\\EPP\\eppRequests\\launch-1.0\\',$fileName);
        $fileName = str_replace('Protocols\\EPP\\eppResponses\\','Protocols\\EPP\\eppResponses\\launch-1.0\\',$fileName);

    }
    //echo "Test autoload protocol $fileName\n";
    if (is_readable($fileName))
    {
        //echo "Autoload protocol $fileName\n";
        require($fileName);
    }
}

spl_autoload_register('autoloadRegistry');
spl_autoload_register('autoloadProtocol');
spl_autoload_register('autoloadData');

?>