<?php
/**
 * Created by PhpStorm.
 * User: martinha
 * Date: 10/04/2019
 * Time: 09:17
 */

namespace Metaregistrar\EPP;

/**
 * Class atEppWithdrawRequest works only as adapter in order to map a atEppResponse.
 *
 * @package Metaregistrar\EPP
 */
class atEppWithdrawRequest extends eppRequest
{
    /**
     * atEppWithdrawRequest constructor.
     * @param $arguments
     *
     * @throws \atEppException
     */
    public function __construct($arguments)
    {
        $this->validateArguments($arguments);
        parent::__construct();
    }

    protected function validateArguments($arguments)
    {
        if (! key_exists('domain_name', $arguments) || ! key_exists('zone_deletion', $arguments))
        {
            throw new \atEppException(
                'atEppWithdrawRequest requires two arguments domain_name:string and zone_deletion:boolean.');
        }
    }
}