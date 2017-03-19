<?php

namespace WellCommerce\Extra\CatalogBundle\Entity;

trait ProductExtraTrait
{
    private $featureSet = null;
    
    private $features = null;
    
    public function getFeatures()
    {
        return $this->features;
    }
    
    public function getFeatureSet()
    {
        return $this->featureSet;
    }
    
    public function setFeatures($features)
    {
        $this->features = $features;
    }
    
    public function setFeatureSet($featureSet)
    {
        $this->featureSet = $featureSet;
    }
}
