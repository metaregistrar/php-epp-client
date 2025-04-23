<?php
/**
 * Created by PhpStorm.
 * User: martinha
 * Date: 01/04/2019
 * Time: 14:56
 */

namespace Metaregistrar\EPP;


class atEppDomainDeleteExtension extends atEppExtensionChain
{
    /*
    |--------------------------------------------------------------------------
    | atEppDomainDeleteExtension
    |--------------------------------------------------------------------------
    |
    | Adds the at-exz-domain:scheduledate extension.
    | This can be either 'now' or 'expiration'
    |
    */
    protected $deleteExtArguments=[];

    /**
     * Domain extension part of the atEppExtensionChain
     *
     * @param array $domainExtArguments
     * @param atEppExtensionChain|null $additionalEppExtension
     */
    public function __construct(array $domainExtArguments=[], ?atEppExtensionChain $additionalEppExtension = null)
    {
        $this->validateExtensionChain($domainExtArguments);
        if (!is_null($additionalEppExtension))
        {
            parent::__construct($additionalEppExtension);
        }
        $this->deleteExtArguments= $domainExtArguments;
    }

    /**
     * Extends the atEppExtensionChain by a delete schedule-date element.
     *
     * @param eppRequest $request
     * @param \DOMElement $extension
     */
    public function setEppRequestExtension(eppRequest $request, \DOMElement $extension)
    {
        $atDomainFacets = $request->createElement('at-ext-domain:delete');
        $atDomainFacets->setAttribute('xmlns:at-ext-domain', atEppConstants::namespaceAtExtDomain);
        $atDomainFacets->setAttribute('xsi:schemaLocation', atEppConstants::schemaLocationAtExtDomain);

        if (isset($this->deleteExtArguments['schedule_date']))
        {
            /* No attributre name for schedule-date, see: http://www.nic.at/xsd/at-ext-domain-1.0 */
            $scheduleDate = $request->createElement('at-ext-domain:scheduledate');
            $scheduleDate->appendChild(new \DOMText($this->deleteExtArguments['schedule_date']));
            $atDomainFacets->appendChild($scheduleDate);
        }

        $extension->appendChild($atDomainFacets);

        if (!is_null($this->additionalEppExtension))
        {
            $this->additionalEppExtension->setEppRequestExtension($request, $extension);
        }
    }

    /**
     * Validates the extension parameter against the allowed values;
     *
     * @param $atEppExtensionChain
     * @throws \atEppException, If the the request contained an invalid parameter.
     */
    protected function validateExtensionChain($arguments)
    {
        if (
            $arguments != null &&
            (
                0 == strcmp($arguments['schedule_date'], atEppConstants::domainDeleteScheduleNow) ||
                0 == strcmp($arguments['schedule_date'], atEppConstants::domainDeleteScheduleExpiration)
            )

        ) return;

        throw new \atEppException(
            "Invalid parameter for schedule date in domain delete request extension. Must be either \n" .
            "Value must be either" . atEppConstants::domainDeleteScheduleNow . " or " . atEppConstants::domainDeleteScheduleExpiration
        );
    }
}