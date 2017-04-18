<?php

namespace WellCommerce\Extra\AppBundle\Entity;

trait ShopExtraTrait {

	protected $invoiceMaturity = null;

	protected $invoiceProcessor = null;

	public function getInvoiceMaturity() {
		return $this->invoiceMaturity;
	}

	public function getInvoiceProcessor() {
		return $this->invoiceProcessor;
	}

	public function setInvoiceMaturity($invoiceMaturity) {
		$this->invoiceMaturity = $invoiceMaturity;
	}

	public function setInvoiceProcessor($invoiceProcessor) {
		$this->invoiceProcessor = $invoiceProcessor;
	}
}