<?php

namespace WellCommerce\Extra\AppBundle\Entity;

trait ShopExtraTrait {

	protected $facebookConnectAppId = null;

	protected $facebookConnectAppSecret = null;

	protected $facebookConnectEnabled = null;

	protected $googleConnectAppId = null;

	protected $googleConnectAppSecret = null;

	protected $googleConnectEnabled = null;

	protected $invoiceMaturity = null;

	protected $invoiceProcessor = null;

	public function getFacebookConnectAppId() {
		return $this->facebookConnectAppId;
	}

	public function getFacebookConnectAppSecret() {
		return $this->facebookConnectAppSecret;
	}

	public function getFacebookConnectEnabled() {
		return $this->facebookConnectEnabled;
	}

	public function getGoogleConnectAppId() {
		return $this->googleConnectAppId;
	}

	public function getGoogleConnectAppSecret() {
		return $this->googleConnectAppSecret;
	}

	public function getGoogleConnectEnabled() {
		return $this->googleConnectEnabled;
	}

	public function getInvoiceMaturity() {
		return $this->invoiceMaturity;
	}

	public function getInvoiceProcessor() {
		return $this->invoiceProcessor;
	}

	public function setFacebookConnectAppId($facebookConnectAppId) {
		$this->facebookConnectAppId = $facebookConnectAppId;
	}

	public function setFacebookConnectAppSecret($facebookConnectAppSecret) {
		$this->facebookConnectAppSecret = $facebookConnectAppSecret;
	}

	public function setFacebookConnectEnabled($facebookConnectEnabled) {
		$this->facebookConnectEnabled = $facebookConnectEnabled;
	}

	public function setGoogleConnectAppId($googleConnectAppId) {
		$this->googleConnectAppId = $googleConnectAppId;
	}

	public function setGoogleConnectAppSecret($googleConnectAppSecret) {
		$this->googleConnectAppSecret = $googleConnectAppSecret;
	}

	public function setGoogleConnectEnabled($googleConnectEnabled) {
		$this->googleConnectEnabled = $googleConnectEnabled;
	}

	public function setInvoiceMaturity($invoiceMaturity) {
		$this->invoiceMaturity = $invoiceMaturity;
	}

	public function setInvoiceProcessor($invoiceProcessor) {
		$this->invoiceProcessor = $invoiceProcessor;
	}
}