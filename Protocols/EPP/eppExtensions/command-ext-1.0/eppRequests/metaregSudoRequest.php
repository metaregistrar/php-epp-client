<?php
/*
 * <epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:command-ext="http://www.metaregistrar.com/epp/command-ext-1.0" xmlns:ext="http://www.metaregistrar.com/epp/ext-1.0">
 *   <extension>
 *     <ext:sudo>
 *       <clID>client-id</clID>
 *       <command>EPP COMMAND IN HERE</command>
 *       <extension>EPP EXTENSION IN HERE</extension>
 *     </ext:sudo>
 * </epp>
 */
namespace Metaregistrar\EPP;

class metaregSudoRequest extends eppRequest {
   
    private $originalRequest;
    
    function __construct(eppRequest $request, $sudoUser)
    {
        $this->originalRequest = $request;
        parent::__construct();
        $ext = $this->createElement('extension');
        $extSudo = $this->createElement('ext:sudo');
        $ext->appendChild($extSudo);
        parent::getEpp()->appendChild($ext);
        $clID = $this->createElement('ext:clID');
        $clID->nodeValue = $sudoUser;
        $extSudo->appendChild($clID);
        
        $command = $request->getElementsByTagName('command');
        if ($command->length > 0) {
            $extCommand = $this->createElement('ext:command');
            $extSudo->appendChild($extCommand);
            foreach ($command as $child) {
                $node = $this->importNode($child, true);
                $extCommand->appendChild($node->firstChild);
                break;
            }
            $extension = $this->createElement('extension');
            $extensions = $request->getElementsByTagName('extension');
            if ($extensions->length > 0) {
                foreach ($extensions as $child) {
                    $node = $this->importNode($child, true);
                    $extension->appendChild($node->firstChild);
                    break;
                }
                $extCommand->appendChild($extension);
            }
        }
        else {
            $extCommand = $this->createElement('ext:extCommand');
            $extSudo->appendChild($extCommand);
            $command = $request->getElementsByTagName('ext:command');
            foreach ($command as $child) {
                $node = $this->importNode($child, true);
                $extCommand->appendChild($node->firstChild);
                break;
            }
        }
    }
    
    
    public function getOriginalRequest() {
        return $this->originalRequest;
    }
}