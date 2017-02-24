<?php


namespace WellCommerce\Component\Layout\Model;

/**
 * Interface LayoutBoxInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface LayoutBoxInterface
{
    public function getBoxType(): string;
    
    public function setBoxType(string $boxType);
    
    public function getSettings(): array;
    
    public function setSettings(array $settings);
    
    public function getIdentifier(): string;
    
    public function setIdentifier(string $identifier);
    
    public function getBoxName(): string;
    
    public function getBoxContent(): string;
}
