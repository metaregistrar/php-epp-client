<?php
/**
 * The EPP Secdns Object
 *
 * This will hold the secdns data for a domain name
 *
 *
 *
 */

class eppSecdns
{
    /**
     *
     * @var string 
     */
    private $keytag='';    
    /**
     *
     * @var string 
     */
    private $siglife='';    
    /**
     *
     * @var string 
     */
    private $digestType='';
    /**
     *
     * @var string 
     */
    private $digest='';    
    /**
     * 
	 * @var string
	 */
	private $flags='';
	/**
	 *
	 * @var string
	 */
	private $protocol = '3';
    /**
     *
     * @var string 
     */
    private $algorithm='';
    /**
     *
     * @var string 
     */
    private $pubkey='';
    
	/**
	 *
	 * @return void
	 */
	public function  __construct()
	{
	}
    
    public function setKey($flags, $algorithm, $pubkey)
    {
        $this->setFlags($flags);           
        $this->setAlgorithm($algorithm);
        $this->setPubkey($pubkey);
    }
    
    public function setData($keyTag,$digestType,$digest)
    {
        $this->setKeytag($keyTag);
        $this->setDigestType($digestType);
        $this->setDigest($digest);
    }

	/**
	 * Gets the digest
	 * @return string
	 */
	public function getDigest()
	{
        return $this->digest;
	}
    
	/**
	 * Sets the digest
	 * @param string $digest
	 * @return void
	 */
	public function setDigest($digest)
	{

        $this->digest = $digest;
	} 
	/**
	 * Gets the digestType
	 * @return string
	 */
	public function getDigestType()
	{
        return $this->digestType;
	}
    
	/**
	 * Sets the digestType
	 * @param string $digestType
	 * @return void
	 */
	public function setDigestType($digestType)
	{

        $this->digestType = $digestType;
	}       
	/**
	 * Gets the siglife
	 * @return string
	 */
	public function getSiglife()
	{
        return $this->siglife;
	}
    
	/**
	 * Sets the siglife
	 * @param string $siglife
	 * @return void
	 */
	public function setSiglife($siglife)
	{

        $this->siglife = $siglife;
	}   
    
	/**
	 * Gets the keytag
	 * @return string
	 */
	public function getKeytag()
	{
        return $this->keytag;
	}
	/**
	 * Sets the keytag
	 * @param string $keytag
	 * @return void
	 */
	public function setKeytag($keytag)
	{

        $this->keytag = $keytag;
	}     
    
	/**
	 * Gets the protocol
	 * @return string
	 */
	public function getProtocol()
	{
        return $this->protocol;
	}
	/**
	 * Sets the protocol
	 * @param string $protocol
	 * @return void
	 */
	public function setProtocol($protocol)
	{

        $this->protocol = $protocol;
	}    
    
	/**
	 * Gets the flags
	 * @return string
	 */
	public function getFlags()
	{
        return $this->flags;
	}
	/**
	 * Sets the flags
	 * @param string $flags
	 * @return void
	 */
	public function setFlags($flags)
	{

        $this->flags = $flags;
	}
    
	/**
	 * Gets the public key
	 * @return string
	 */
	public function getPubkey()
	{
        return $this->pubkey;
	}
    
	/**
	 * Sets the public key
	 * @param string $pubkey
	 * @return void
	 */
	public function setPubkey($pubkey)
	{
        $this->pubkey = $pubkey;
	}
   
	/**
	 * Gets the algorithm
	 * @return string
	 */
	public function getAlgorithm()
	{
        return $this->algorithm;
	}
	/**
	 * Sets the algorithm
	 * @param string $algorithm
	 * @return void
	 */
	public function setAlgorithm($algorithm)
	{
        $this->algorithm = $algorithm;
	}

    // Copy data from a similar object to this one, with safeguards
    public function copy($object)
    {
        $this->setPubkey($object->getPubkey());
        $this->setProtocol($object->getProtocol());
        $this->setFlags($object->getFlags());
        $this->setAlgorithm($object->getAlgorithm());
        $this->setDigest($object->getDigest());
        $this->setDigestType($object->getDigestType());
        $this->setKeytag($object->getKeytag());
        $this->setSiglife($object->getSiglife());        
    }

    public function equals($object)
    {
        $equals = true;

        if ($this->getPubkey()!=$object->getPubkey())
        {
            $equals = false;
        }
        if ($this->getProtocol()!=$object->getProtocol())
        {
            $equals = false;
        }
        if ($this->getFlags()!=$object->getFlags())
        {
            $equals = false;
        }
        if ($this->getAlgorithm()!=$object->getAlgorithm())
        {
            $equals = false;
        }
        if ($this->getDigest()!=$object->getDigest())
        {
            $equals = false;
        }
        if ($this->getDigestType()!=$object->getDigestType())
        {
            $equals = false;
        }
        if ($this->getKeytag()!=$object->getKeytag())
        {
            $equals = false;
        }
        if ($this->getSiglife()!=$object->getSiglife())
        {
            $equals = false;
        }
        return $equals;
    }
}


