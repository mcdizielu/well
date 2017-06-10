<?php


namespace WellCommerce\Bundle\ShipmentBundle\Adapter;

use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\ShipmentBundle\Entity\Shipment;
use WellCommerce\Component\Form\Elements\Fieldset\FieldsetInterface;
use WellCommerce\Component\Form\FormBuilderInterface;

/**
 * Interface ShipmentAdapterInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface ShipmentAdapterInterface
{
    public function getAlias(): string;
    
    public function addShipment(Shipment $shipment, array $formValues): Response;
    
    public function generateLabel(Shipment $shipment);
    
    public function getLabel(Shipment $shipment);
    
    public function getLabels(string $date);
    
    public function addFormFields(FieldsetInterface $fieldset, FormBuilderInterface $builder, Shipment $shipment);
}
