<?php
namespace Metaregistrar\EPP;
/*

<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:ssl="http://www.metaregistrar.com/epp/ssl-1.0">
    <command>
        <renew>
            <ssl:renew>
                <!--Base64 encoded csr -->
                <ssl:certificateId>126</ssl:certificateId>
                <ssl:type>reissue</ssl:type>
                <ssl:csr>LS0tLS1CRUdJTiBDRVJUSUZJQ0FURSBSRVFVRVNULS0tLS0KTUlJQy9EQ0NBZVFDQVFBd1lqRUxNQWtHQTFVRUJoTUNUa3d4RlRBVEJnTlZCQWdNREZwMWFXUWdhRzlzYkdGdQpaREVPTUF3R0ExVUVCd3dGUjI5MVpHRXhGakFVQmdOVkJBb01EVzFsZEdGeVpXZHBjM1J5WVhJeEZEQVNCZ05WCkJBTU1DMlY0WVcxd2JHVXVZMjl0TUlJQklqQU5CZ2txaGtpRzl3MEJBUUVGQUFPQ0FROEFNSUlCQ2dLQ0FRRUEKdzh1S1h5bFdTVCtkbG1ieHhPb09CQ2JsT1NXMG8rVmI2N0ZkN2VrYUtYMkJYeFJFM2E5ZkdVNHJMUWxkZlNFcApSUFhyMkRjVU01MHRscFhIdDNFOEpCZ2E0VlRxTGEvMkhrTVdjRzlBLzI1SUFYa0Q3TU42VFRwUU9MRkEycy9NCnBUSTNkeFBiSndNZWsvTUFYdG0xajNHUEJEQU9FQUtpdEtEbzd2MlJCZTVUOWx4YnRFOC9zSzUzL2pjR2dQUEEKNU1VMXovckZHY0IyVTZsTUw3cEF4VlM5bVdDL0JvNVhFVVhZaGtONzhQRG5HZDVZd09DY2I1N2Zsb2Z4d0dFVQpqdHAyQjI5c0w5WVdUSk12Ty9zYUVaL1JJekZzWStEb3QvY0pRSzRCKzQwaTFXT1oxMVlpZjB3THcyWTBhRmJ1CjNVWXo5RTN1R3Voa05VdDYralFxOXdJREFRQUJvRlV3VXdZSktvWklodmNOQVFrT01VWXdSREJDQmdOVkhSRUUKT3pBNWdoRjBaWE4wTVM1bGVHRnRjR3hsTG1OdmJZSVJkR1Z6ZERJdVpYaGhiWEJzWlM1amIyMkNFWFJsYzNRegpMbVY0WVcxd2JHVXVZMjl0TUEwR0NTcUdTSWIzRFFFQkN3VUFBNElCQVFDTlM2aGpSaUlkL0xRVEFiQ3hRRDF2Cnp3SmR6anlibjA3dzdYZ2hpaXhpb1Q2VkRmQzY4UGdGZVlPQ3RpQitBSHJ0UkZ6T1ROWmdSVTRyZFZIM0xJdmYKd1Q3SUxEcmtGTTFld0gxcWlnRUdsenFkTXZkSHNCeHdIM2VMcGVnVnVZaEYwajBUNkZCdG5ha1pFSVBxOVJreApCTUhuRFNUT3FSa3lvaldTcTJoblE4RFY0R1NiSnpmRXEwZTU4VTI2MDJSZlpjRFBpZjBPdDVVYnM5L2F1UVlhCmsxdWt0RU5QOFVIVnFCaW92S1lLa0NCOGFxR3hzZkZkMzVjRzV5ZEFrV3J3bTFxL2tqaTlEald1WDNUMklZZ0IKNlJwTnFYU1IwV1BRanQ0aWZ1SlBZeXRJa2tUNDBwMC95c2lYd0ZpbHRBN0lQelRZNGJCam9hQlRjTW9tV0ZmUQotLS0tLUVORCBDRVJUSUZJQ0FURSBSRVFVRVNULS0tLS0K</ssl:csr>
                <ssl:product>COMO.MUL.OV</ssl:product>
                <ssl:hosts>
                    <ssl:host>
                        <ssl:name>example.com</ssl:name>
                        <ssl:validation>EMAIL</ssl:validation>
                        <ssl:email>admin@example.com</ssl:email>
                    </ssl:host>
                    <ssl:host>
                        <ssl:name>test1.example.com</ssl:name>
                        <ssl:validation>DNS</ssl:validation>
                    </ssl:host>
                    <ssl:host>
                        <ssl:name>test2.example.com</ssl:name>
                        <ssl:validation>DNS</ssl:validation>
                    </ssl:host>
                    <ssl:host>
                        <ssl:name>test3.example.com</ssl:name>
                        <ssl:validation>DNS</ssl:validation>
                    </ssl:host>
                    <ssl:host>
                        <ssl:name>*.example.com</ssl:name>
                        <ssl:validation>DNS</ssl:validation>
                    </ssl:host>
                    <ssl:host>
                        <ssl:name>www.example.nl</ssl:name>
                        <ssl:validation>DNS</ssl:validation>
                    </ssl:host>
                    <ssl:host>
                        <ssl:name>*.example.nl</ssl:name>
                        <ssl:validation>DNS</ssl:validation>
                    </ssl:host>
                    <ssl:host>
                        <ssl:name>examples.nl</ssl:name>
                        <ssl:validation>DNS</ssl:validation>
                    </ssl:host>
                    <ssl:host>
                        <ssl:name>*.examples.nl</ssl:name>
                        <ssl:validation>DNS</ssl:validation>
                    </ssl:host>
                </ssl:hosts>
                <ssl:approver>
                    <ssl:email>hostmaster@example.com</ssl:email>
                    <ssl:saEmail>john@metaregistrar.com</ssl:saEmail>
                    <ssl:phone>+31.612345678</ssl:phone>
                    <ssl:firstName>John</ssl:firstName>
                    <ssl:lastName>De Tester</ssl:lastName>
                    <ssl:company>Metaregistrar</ssl:company>
                    <ssl:department>Infra</ssl:department>
                    <ssl:companyRegistration>57931224</ssl:companyRegistration>
                    <ssl:street>Zuidelijk Halfrond 1</ssl:street>
                    <ssl:street>Room 1.11</ssl:street>
                    <ssl:postalCode>2801 DD</ssl:postalCode>
                    <ssl:city>Gouda</ssl:city>
                </ssl:approver>
                <ssl:language>nl</ssl:language>
            </ssl:renew>
        </renew>
        <clTRID>5a27b8d011a0e</clTRID>
    </command>
</epp>

*/

class metaregSslReissueRequest extends metaregSslRenewRequest {

    function __construct($certificateid, $csr, $product, $language, $years) {
        parent::__construct($certificateid, $csr, $product, $language, $years, true);

    }

}