<?php
require('../autoloader.php');

$xml = '<?xml version="1.0" encoding="utf-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
	<response>
		<result code="1000">
			<msg>Command completed successfully</msg>
		</result>
		<resData>
			<domain:chkData
				xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
				<domain:cd>
					<domain:name avail="1">example.com</domain:name>
				</domain:cd>
				<domain:cd>
					<domain:name avail="1">example.net</domain:name>
				</domain:cd>
				<domain:cd>
					<domain:name avail="1">example.xyz</domain:name>
				</domain:cd>
			</domain:chkData>
		</resData>
		<extension>
			<fee:chkData
				xmlns:fee="urn:ietf:params:xml:ns:fee-1.0">
				<fee:currency>USD</fee:currency>
				<fee:cd avail="1">
					<fee:objID>example.com</fee:objID>
					<fee:class>Premium</fee:class>
					<fee:command name="create">
						<fee:period unit="y">2</fee:period>
						<fee:fee
							description="Registration Fee"
							refundable="1"
							grace-period="P5D">10.00</fee:fee>
					</fee:command>
					<fee:command name="renew">
						<fee:period unit="y">1</fee:period>
						<fee:fee
							description="Renewal Fee"
							refundable="1"
							grace-period="P5D">5.00</fee:fee>
					</fee:command>
					<fee:command name="transfer">
						<fee:period unit="y">1</fee:period>
						<fee:fee
							description="Transfer Fee"
							refundable="1"
							grace-period="P5D">5.00</fee:fee>
					</fee:command>
					<fee:command name="restore">
						<fee:fee
							description="Redemption Fee">5.00</fee:fee>
					</fee:command>
				</fee:cd>
				<fee:cd avail="1">
					<fee:objID>example.net</fee:objID>
					<fee:command name="create">
						<fee:period unit="y">2</fee:period>
						<fee:fee
							description="Registration Fee"
							refundable="1"
							grace-period="P5D">10.00</fee:fee>
					</fee:command>
					<fee:command name="renew">
						<fee:period unit="y">1</fee:period>
						<fee:fee
							description="Renewal Fee"
							refundable="1"
							grace-period="P5D">5.00</fee:fee>
					</fee:command>
					<fee:command name="transfer">
						<fee:period unit="y">1</fee:period>
						<fee:fee
							description="Transfer Fee"
							refundable="1"
							grace-period="P5D">5.00</fee:fee>
					</fee:command>
					<fee:command name="restore">
						<fee:fee
							description="Redemption Fee">5.00</fee:fee>
					</fee:command>
				</fee:cd>
				<fee:cd avail="0">
					<fee:objID>example.xyz</fee:objID>
					<fee:command name="create">
						<fee:period unit="y">2</fee:period>
						<fee:reason>Only 1 year registration periods are
							valid.</fee:reason>
					</fee:command>
				</fee:cd>
			</fee:chkData>
		</extension>
		<trID>
			<clTRID>ABC-12345</clTRID>
			<svTRID>54322-XYZ</svTRID>
		</trID>
	</response>
</epp>';

$conn = Metaregistrar\EPP\eppConnection::create('settings.ini');
$conn->useExtension('fee-1.0');
$feeresponse = new Metaregistrar\EPP\feeEppCheckDomainResponse();
$feeresponse->loadXML($xml);
print_r($feeresponse->getFees());