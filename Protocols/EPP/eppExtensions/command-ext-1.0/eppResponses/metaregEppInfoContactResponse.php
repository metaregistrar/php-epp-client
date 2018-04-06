<?php
namespace Metaregistrar\EPP;

/**
 * <response>
    <result code="1000">
        <msg>Command completed successfully</msg>
    </result>
    <resData>
        <contact:infData>
            <contact:id><![CDATA[privacyprotect]]></contact:id>
            <contact:roid><![CDATA[privacyprotect]]></contact:roid>
            <contact:status xmlns="urn:ietf:params:xml:ns:contact-1.0" s="ok"><![CDATA[No changes pending]]></contact:status>
            <contact:postalInfo xmlns:default="urn:ietf:params:xml:ns:contact-1.0" type="loc">
                <contact:name><![CDATA[Privacy Protect]]></contact:name>
                <contact:org><![CDATA[Metaregistrar BV]]></contact:org>
                <contact:addr xmlns:default="urn:ietf:params:xml:ns:contact-1.0">
                <contact:street xmlns="urn:ietf:params:xml:ns:contact-1.0">
                <![CDATA[Zuidelijk Halfrond 1]]></contact:street>
                <contact:city><![CDATA[Gouda]]></contact:city>
                <contact:pc><![CDATA[2801 DD]]></contact:pc>
                <contact:cc><![CDATA[NL]]></contact:cc>
                </contact:addr>
            </contact:postalInfo>
            <contact:voice><![CDATA[+31.858885692]]></contact:voice>
            <contact:email><![CDATA[domains@metaregistrar.com]]></contact:email>
            <contact:clID><![CDATA[metaregistrar]]></contact:clID>
            <contact:crID><![CDATA[metaregistrar]]></contact:crID>
            <contact:crDate><![CDATA[2017-10-03T10:03:47.000000+0000]]></contact:crDate>
            <contact:upID><![CDATA[metaregistrar]]></contact:upID>
            <contact:upDate><![CDATA[2017-10-03T10:03:47.000000+0000]]></contact:upDate>
        </contact:infData>
    </resData>
    <extension>
        <command-ext-contact:extContactInfData>
            <command-ext-contact:property>
                <command-ext-contact:registry><![CDATA[Dnsbe]]></command-ext-contact:registry>
                <command-ext-contact:name><![CDATA[vat]]></command-ext-contact:name>
                <command-ext-contact:value><![CDATA[1219884]]></command-ext-contact:value>
            </command-ext-contact:property>
            <command-ext-contact:property>
                <command-ext-contact:registry><![CDATA[Dnsbe]]></command-ext-contact:registry>
                <command-ext-contact:name><![CDATA[lang]]></command-ext-contact:name>
                <command-ext-contact:value><![CDATA[nl]]></command-ext-contact:value>
            </command-ext-contact:property>
        </command-ext-contact:extContactInfData>
    </extension>
    <trID>
    <clTRID>5ac77af86ab56</clTRID>
    <svTRID>MTR_2732e366a948128758fd1a487aa428639942f640a97f</svTRID>
    </trID>
</response>
 */

/**
 * Created by PhpStorm.
 * User: ewout
 * Date: 06-04-18
 * Time: 16:41
 */

class metaregEppInfoContactResponse extends eppInfoContactResponse  {

    /**
     * metaregEppInfoContactResponse constructor.
     * @param null $originalrequest
     */
    public function __construct($originalrequest) {
        parent::__construct($originalrequest);
    }


    /**
     * @param $registry
     * @param $propertyname
     */
    public function getContactProperty($registry, $propertyname) {
        $xpath = $this->xPath();
        $properties = $xpath->query('/epp:epp/epp:response/epp:extension/command-ext-contact:extContactInfData/*');
        foreach ($properties as $property) {
            /* @var $property \DOMElement */
            if ($property->getElementsByTagName('registry')->item(0)->nodeValue==$registry) {
                if ($property->getElementsByTagName('name')->item(0)->nodeValue==$propertyname) {
                    return  $property->getElementsByTagName('value')->item(0)->nodeValue;
                }
            }
        }
        return null;
    }

    public function getContactProperties(){
        $out = [];
        $properties = $this->xPath()->query('/epp:epp/epp:response/epp:extension/command-ext-contact:extContactInfData/*');
        foreach ($properties as $property) {
            /* @var $property \DOMElement */
            $registry = $property->getElementsByTagName('registry')->item(0)->nodeValue;
            $name = $property->getElementsByTagName('name')->item(0)->nodeValue;
            $value = $property->getElementsByTagName('value')->item(0)->nodeValue;
            $out[$registry][$name] = $value;
        }
        return $out;
    }


}