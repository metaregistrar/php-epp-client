<?php

namespace Metaregistrar\EPP;

class plEppCreateDomainRequest extends eppCreateDomainRequest
{
  /**
   *
   * .Pl registry requires that host objects are sent as <domain:ns> elements (old RFC 3735) instead of <domain:hostObj> RFC 5731) elements,
   * so we need to override the default implementation of setDomain to move the host objects to the correct place in the XML structure.
   * @param eppDomain $domain
   * @return \DOMElement | null
   * @throws eppException
   */
  public function setDomain(eppDomain $domain)
  {
    parent::setDomain($domain);

    // Convert <domain:hostObj> elements to <domain:ns> elements as required by .pl registry
    $hostObjNodes = $this->domainobject->getElementsByTagName('domain:hostObj');
    $originalHostObjs = iterator_to_array($hostObjNodes);

    foreach ($originalHostObjs as $hostObj) {
      $hostname = trim($hostObj->nodeValue);
      if ($hostname === '') {
        continue;
      }

      $nsContainer = $hostObj->parentNode; // <domain:ns> wrapper
      $parent = $nsContainer->parentNode;
      $doc = $hostObj->ownerDocument;

      // Replace with a flat <domain:ns>hostname</domain:ns> element
      $parent->insertBefore($doc->createElement('domain:ns', $hostname), $nsContainer->nextSibling);
      $nsContainer->removeChild($hostObj);

      // Remove the wrapper immediately if it is now empty
      if (!$nsContainer->hasChildNodes()) {
        $parent->removeChild($nsContainer);
      }
    }

    return null;
  }
}
