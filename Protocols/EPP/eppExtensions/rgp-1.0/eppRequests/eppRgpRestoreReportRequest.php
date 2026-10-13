<?php
namespace Metaregistrar\EPP;

/**
 * Class eppRgpRestoreReportRequest
 */
class eppRgpRestoreReportRequest extends eppUpdateDomainRequest
{
	/**
	 * eppRgpRestoreReportRequest constructor.
	 * @param eppDomain      $objectname
	 * @param ?string        $delTime
     * @param ?string        $resTime
	 * @param ?string        $message
	 */
	public function __construct(eppDomain $objectname, $delTime = null, $resTime = null, $message = null)
	{
		parent::__construct($objectname,null,null,$objectname);
		$rgp = $this->createElement('rgp:update');
		$restore = $this->createElement('rgp:restore');
		$restore->setAttribute('op', 'report');
		$report = $this->createElement('rgp:report');
		$report->appendChild($this->createElement('rgp:preData',''));
		$report->appendChild($this->createElement('rgp:postData',''));
		if ($delTime) {
			$report->appendChild($this->createElement('rgp:delTime',date('Y-m-d\TH:i:s\Z',strtotime($delTime))));
		}
		if ($resTime) {
			$report->appendChild($this->createElement('rgp:resTime',date('Y-m-d\TH:i:s\Z',strtotime($resTime))));
		}
		if ($message) {
			$report->appendChild($this->createElement('rgp:resReason',$message));
		} else {
			$report->appendChild($this->createElement('rgp:resReason','Customer wants this restore to be done.'));
		}
		$report->appendChild($this->createElement('rgp:statement','I confirm that this restore complies with registry policies.'));
		$report->appendChild($this->createElement('rgp:statement','I confirm that this data is accurate.'));
		$restore->appendChild($report);
		$rgp->appendChild($restore);
		$this->getExtension()->appendChild($rgp);
		$this->addSessionId();
	}
}