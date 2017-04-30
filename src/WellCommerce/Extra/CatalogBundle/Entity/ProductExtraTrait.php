<?php

namespace WellCommerce\Extra\CatalogBundle\Entity;

trait ProductExtraTrait {

	protected $enableReviews = null;

	protected $featureSet = null;

	protected $features = null;

	public function getEnableReviews() {
		return $this->enableReviews;
	}

	public function getFeatures() {
		return $this->features;
	}

	public function getFeatureSet() {
		return $this->featureSet;
	}

	public function setEnableReviews($enableReviews) {
		$this->enableReviews = $enableReviews;
	}

	public function setFeatures($features) {
		$this->features = $features;
	}

	public function setFeatureSet($featureSet) {
		$this->featureSet = $featureSet;
	}
}