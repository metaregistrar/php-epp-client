<?php
/**
 * The EPP Contact Handle Object
 *
 * This will hold the complete contact info the provider can receive and give you
 *
 *
 *
 */

class eppContactHandle
{
	const CONTACT_TYPE_REGISTRANT	= 'reg';
	const CONTACT_TYPE_ADMIN	= 'admin';
	const CONTACT_TYPE_TECH		= 'tech';
	const CONTACT_TYPE_BILLING	= 'billing';
	/**
	 * @var string
	 */
	private $contactHandle;
	/**
	 *
	 * @var string
	 */
	private $contactType;
	/**
	 *
	 * @param string $contactHandle
	 * @param SidnContactInfo $contactInfo
	 * @param string $contactType
	 * @return void
	 */
	public function  __construct($contactHandle, $contactType=null)
	{
            $this->setContactHandle($contactHandle);
            if ($contactType)
            {
                $this->setContactType($contactType);
            }
            if (($contactType!=null) && ($contactType!=self::CONTACT_TYPE_ADMIN) && ($contactType!=self::CONTACT_TYPE_REGISTRANT) && ($contactType!=self::CONTACT_TYPE_BILLING)  && ($contactType!=self::CONTACT_TYPE_TECH))
            {
                throw new eppException('Invalid contact type: '.$contactType);
            }
	}

	/**
	 * Gets the contact handle
	 * @return string
	 */
	public function getContactHandle()
	{
            return $this->contactHandle;
	}
	/**
	 * Sets the contact handle
	 * @param string $contactHandle
	 * @return void
	 */
	public function setContactHandle($contactHandle)
	{
            if (!strlen($contactHandle))
            {
                throw new eppException('Contact handle specified is not valid: '.$contactHandle);
            }
            $this->contactHandle = $contactHandle;
	}
	/**
	 * Gets the contact handle
	 * @return string
	 */
	public function getContactType()
	{
            return $this->contactType;
	}
	/**
	 * Sets the contact type
	 * @param string $contactType
	 * @return void
	 */
	public function setContactType($contactType)
	{
            $this->contactType = $contactType;
	}



}


