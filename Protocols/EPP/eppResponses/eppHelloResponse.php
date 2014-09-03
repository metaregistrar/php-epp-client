<?php

class eppHelloResponse extends eppResponse
{
    function __construct()
    {
        parent::__construct();
    }

    function __destruct()
    {
        parent::__destruct();
    }
    
    public function validateServices($language,$version,$services,$extensions)
    {
        $resultcode = $this->getResultCode();
        if ($resultcode != 1000)
        {
            $errormessage = $this->getResultMessage();
            $value = $this->getResultValue();
            $errorstring = "Error $resultcode: $errormessage ($value)";
            throw new eppException($errorstring);
        }
        $versions = $this->getVersions();
        $versionok = false;
        $supported = '';
        if (is_array($versions))
        {
            foreach($versions as $ver)
            {
                if ($ver == $version)
                {
                    $versionok = true;
                }
                $supported .= $ver.' ';
            }
        }
        if (!$versionok)
        {
            throw new eppException($this->version.' is not a supported version, supported versions: '.$supported);
        }
        $languages = $this->getLanguages();
        $languageok = false;
        $supported = '';
        if (is_array($languages))
        {
            foreach($languages as $lang)
            {
                if ($lang == $language)
                {
                    $languageok = true;
                }
                $supported .= $lang.' ';
            }
        }
        if (!$languageok)
        {
            throw new eppException($this->language.' is not a supported language, supported languages: '.$supported);
        }
        $servs = $this->getServices();
        if (is_array($this->objuri))
        {
            foreach ($this->objuri as $type=>$objuri)
            {
                if ($type!='epp')
                {
                    $objuriok = false;
                    $supported = '';
                    foreach ($servs as $service)
                    {
                        if ($service == $objuri)
                        {
                            $objuriok = true;
                        }
                        $supported .= $service.' ';
                    }
                    if (!$objuriok)
                    {
                        throw new eppException($objuri.' is not supported, supported services: '.$supported);
                    }
                }
            }
        }        
        $exts = $this->getExtensions();        
        if (is_array($this->exturi))
        {
            foreach ($this->exturi as $exturi)
            {
                $exturiok = false;
                $supported = '';
                foreach ($exts as $extension)
                {
                    if ($extension == $exturi)
                    {
                        $exturiok = true;
                    }
                    $supported .= $extension.' ';
                }
                if (!$exturiok)
                {
                    throw new eppException($exturi.' extension is not supported, supported extensions: '.$supported);
                }
            }
        }        
    }
    
    /**
     * Server name is returned by EPP greeting (hello)
     * @return type string
     */
    public function getServerName()
    {
       $xpath = $this->xPath();
       $servername = $xpath->query('/epp:epp/epp:greeting/epp:svID');
       return $servername->item(0)->nodeValue;
    }

    /**
     * Server date is returned by EPP greeting (hello)
     * @return type string
     */
    public function getServerDate()
    {
       $xpath = $this->xPath();
       $serverdate = $xpath->query('/epp:epp/epp:greeting/epp:svDate');
       return $serverdate->item(0)->nodeValue;
    }

    /**
     * Languages are returned by EPP greeting (hello)
     * @return type array of strings
     */
    public function getLanguages()
    {
       $xpath = $this->xPath();
       $languages = $xpath->query('/epp:epp/epp:greeting/epp:svcMenu/epp:lang');
       foreach($languages as $language)
       {
           $lang[] = $language->nodeValue;
       }
        return $lang;
    }

    /**
     * Services are returned by EPP greeting (hello)
     * @return type array of strings
     */
    public function getServices()
    {
        $xpath = $this->xPath();
        $services = $xpath->query('/epp:epp/epp:greeting/epp:svcMenu/epp:objURI');
        foreach ($services as $service)
        {
           $svcs[]=$service->nodeValue;
        }
        return $svcs;
    }

    /**
     * Extensions are returned by EPP greeting (hello)
     * @return type array of strings
     */
    public function getExtensions()
    {
        $xpath = $this->xPath();
        $extensions = $xpath->query('/epp:epp/epp:greeting/epp:svcMenu/epp:svcExtension/epp:extURI');
        foreach ($extensions as $extension)
        {
            $exts[] = $extension->nodeValue;
        }
        return $exts;
    }

    /**
     * Versions are returned by the EPP greeting
     * @return array of strings
     */
    public function getVersions()
    {
       $xpath = $this->xPath();
       $versions = $xpath->query('/epp:epp/epp:greeting/epp:svcMenu/epp:version');
       foreach($versions as $version)
       {
           $vers[] = $version->nodeValue;
       }
       return $vers;
    }
}
