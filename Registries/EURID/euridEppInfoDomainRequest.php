<?php
/*
<extension>
    <eurid:ext xmlns:eurid="http://www.dns.be/xml/epp/eurid-1.0">
        <eurid:info>
            <eurid:domain version="2.0"/>
        </eurid:info>
    </eurid:ext>
</extension>


*/
class euridEppInfoDomainRequest extends eppInfoDomainRequest
{
    function __construct($infodomain, $hosts = null)
    {
        parent::__construct($infodomain, $hosts);
//        $this->addEURIDExtension();
        $this->addSessionId();
    }


    public function addEURIDExtension()
    {
        $this->addExtension('xmlns:eurid','http://www.eurid.eu/xml/epp/eurid-1.0');
        $ext = $this->createElement('extension');
        $sidnext = $this->createElement('eurid:ext');
        $info = $this->createElement('eurid:info');
        $infodomain = $this->createElement('eurid:domain');
        $infodomain->setAttribute('version', '2.0');
        $info->appendChild($infodomain);
        $sidnext->appendChild($info);
        $ext->appendChild($sidnext);
        $this->command->appendChild($ext);

    }

}