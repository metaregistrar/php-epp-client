<?php
/**
  C:<?xml version="1.0" encoding="UTF-8" standalone="no"?>
  C:<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  C:  <command>
  C:   <check>
  C:    <domain:check
  C:     xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
  C:      <domain:name>example1.tld</domain:name>
  C:      <domain:name>example2.tld</domain:name>
  C:    </domain:check>
  C:   </check>
  C:   <extension>
  C:    <launch:check
  C:     xmlns:launch="urn:ietf:params:xml:ns:launch-1.0"
  C:     type="avail">
  C:      <launch:phase name="idn-release">custom</launch:phase>
  C:    </launch:check>
  C:   </extension>
  C:   <clTRID>ABC-12345</clTRID>
  C:  </command>
  C:</epp>
*/


class eppLaunchCheckRequest extends eppCheckRequest
{
    function __construct($checkrequest,$phase)
    {
        parent::__construct($checkrequest);
        $launch = $this->createElement('launch:check');
        $launch->setAttribute('xmlns:launch','urn:ietf:params:xml:ns:launch-1.0');
        $launch->setAttribute('type','avail');
        $launchphase = $this->createElement('launch:phase',$phase);
        //$launchphase->setAttribute('name',$phase);
        $launch->appendChild($launchphase);
        $this->getExtension()->appendchild($launch);
        $this->addSessionId();
    }

}

