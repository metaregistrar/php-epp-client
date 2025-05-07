<?php
// +----------------------------------------------------------------------
// | 在我们年轻的城市里，没有不可能的事！
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://srs.micang.com All rights reserved.
// +----------------------------------------------------------------------
// | Author : Jansen <jansen.shi@qq.com>
// +----------------------------------------------------------------------
namespace Metaregistrar\EPP;
class eppRealName{
    const NAME_ROLE_PERSON = 'person';
    const NAME_ROLE_ORG = 'org';
    
    const NAME_PROOF_CITIZEN = 'poc';
    const NAME_PROOF_ENTERPRISE = 'poe';
    const NAME_PROOF_OTHER_TYPES = 'poot';

    /**
     * @var string $role
     */
    private $role = self::NAME_ROLE_PERSON;
    /**
     * @var string $name
     */
    private $name = '';
    /**
     * @var string $number
     */
    private $number = '';
    /**
     * @var string $proof
     */
    private $proof = self::NAME_PROOF_CITIZEN;
    /**
     * @var array $documents
     */
    private $documents = [];
    /**
     * @var string $authorisationCode
     */
    private $authorisationCode = null;
    /**
     * eppRealName constructor.
     *
     * @param string $role
     * @param string $name
     * @param string $number
     * @param string $proof
     * @param array  $documents
     * @param string $authorisationCode
     */
    public function __construct(string $role=self::NAME_ROLE_PERSON, string $name='', string $number='', string $proof=self::NAME_PROOF_CITIZEN, array $documents=[], ?string $authorisationCode=null){
        !empty($role) && $this->setRole($role);
        !empty($name) && $this->setName($name);
        !empty($number) && $this->setNumber($number);
        !empty($proof) && $this->setProof($proof);
        count($documents)>0 && $this->setDocuments($documents);
        !empty($authorisationCode) && $this->setAuthorisationCode($authorisationCode);
    }
    public function setRole(string $role){
        if (($role == self::NAME_ROLE_PERSON) || ($role == self::NAME_ROLE_ORG)) {
            $this->role = $role;
        } else {
            throw new eppException("Name role " . $role . " is invalid, only person or org allowed");
        }
    }
    public function getRole(){
        return $this->role;
    }
    public function setName(string $name){
        $this->name = $name;
    }
    public function getName(){
        return $this->name;
    }
    public function setNumber(string $number){
        $this->number = $number;
    }
    public function getNumber(){
        return $this->number;
    }
    public function setProof(string $proof){
        if (($proof == self::NAME_PROOF_CITIZEN) || ($proof == self::NAME_PROOF_ENTERPRISE) || ($proof == self::NAME_PROOF_OTHER_TYPES)) {
            $this->proof = $proof;
        } else {
            throw new eppException("Proof Type" . $proof . " is invalid, only poc/poe/poot allowed");
        }
    }
    public function getProof(){
        return $this->proof;
    }
    public function setDocuments(array $documents){
        foreach($documents as $doc){
            if (!isset($doc['type']) || !isset($doc['content'])){
                throw new eppException("Documents must be a two-dimensional array, and must contain type and content elements.");
            }
        }
        $this->documents = $documents;
    }
    public function getDocuments(){
        return $this->documents;
    }
    public function setAuthorisationCode($authorisationCode) {
        $this->authorisationCode = $authorisationCode;
    }
    public function getAuthorisationCode() {
        return $this->authorisationCode;
    }
}