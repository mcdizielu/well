<?php

namespace WellCommerce\Extra\OrderBundle\Entity;

trait OrderExtraTrait {

	protected $invoices = null;

	public function getInvoices() {
		return $this->invoices;
	}

	public function setInvoices($invoices) {
		$this->invoices = $invoices;
	}
}