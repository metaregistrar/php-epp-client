<?php

namespace Metaregistrar\EPP;

class plEppUpdateDomainRequest extends eppUpdateDomainRequest
{
  /**
   *
   * @param \domElement $element
   * @param eppDomain $domain
   */
  protected function addDomainChanges($element, eppDomain $domain)
  {

    parent::addDomainChanges($element, $domain);

    // Convert <domain:hostObj> elements to <domain:ns> elements as required by .pl registry
    $hostObjNodes = $element->getElementsByTagName('domain:hostObj');
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
  }
}
