<?php

namespace Metaregistrar\EPP;

/*
   <?xml version="1.0" encoding="UTF-8" standalone="no"?>
   <epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
     <command>
       <info>
         <org:info
           xmlns:org="urn:ietf:params:xml:ns:epp:org-1.0">
           <org:id>res1523</org:id>
         </org:info>
       </info>
       <clTRID>ABC-12345</clTRID>
     </command>
   </epp>
*/

class orgEppInfoRequest extends eppRequest {
	function __construct(string $orgid) {
		parent::__construct();
		$command = $this->getCommand();
		$info = $this->createElement('info');
		$orginfo = $this->createElement('org:info');
		$id = $this->createElement('org:id',$orgid);
		$orginfo->appendChild($id);
		$info->appendChild($orginfo);
		$command->appendChild($info);
	}
}