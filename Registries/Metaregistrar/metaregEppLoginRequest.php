<?php

class metaregEppLoginRequest extends eppLoginRequest
{
    /**
     * This variable indicates if the login is tokenized or not. If so, the password must be changed to something default
     * @var boolean $tokenizedlogin
     */
    private $tokenizedlogin = false;

    /**
     * Support tokenized login for the Metaregistrar interface
     * @param string $token
     */
    function __construct($token=null)
    {
        /*
         * <epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:command-ext="http://www.metaregistrar.com/epp/command-ext-1.0" xmlns:ext="http://www.metaregistrar.com/epp/ext-1.0">
         *   <command>
         *     <login>
         *       <clID>client-id</clID>
         *       <pw>tokenized</pw>
         *     </login>
         *     <extension>
         *       <command-ext:command-ext>
         *         <command-ext:login>
         *           <command-ext:token>djekdjekdjekjkejdkjekdekje</command-ext:token>
         *         </command-ext:login>
         *       </command-ext:command-ext>
         *     </extension>
         *   </command>
         * </epp>
         */
        parent::__construct();
        if ($token)
        {
            $ext = $this->createElement('extension');
            $commandext = $this->createElement('command-ext:command-ext');
            $loginext = $this->createElement('command-ext:login');
            $loginext->appendChild($this->createElement('command-ext:token',$token));
            $commandext->appendChild($loginext);
            $ext->appendChild($commandext);
            $this->command->appendChild($ext);
            $this->tokenizedlogin = true;
        }
        $this->addSessionId();
    }

    /*
     * @override
     * This function is overridden from eppLoginRequest to remove the password when a token is used for login.
     */
    function addPassword($password)
    {
        if ($this->tokenizedlogin)
        {
            $this->login->appendChild($this->createElement('pw','tokenized'));
        }
        else
        {
            parent::addPassword($password);
        }
    }
}