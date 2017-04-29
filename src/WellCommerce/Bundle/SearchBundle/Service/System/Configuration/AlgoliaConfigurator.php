<?php

namespace WellCommerce\Bundle\SearchBundle\Service\System\Configuration;

use WellCommerce\Bundle\AppBundle\Service\System\Configuration\AbstractSystemConfigurator;
use WellCommerce\Component\Form\Elements\FormInterface;
use WellCommerce\Component\Form\FormBuilderInterface;
use WellCommerce\Component\Search\Adapter\Algolia\AlgoliaQueryBuilder;
use WellCommerce\Component\Search\Adapter\SearchAdapterConfiguratorInterface;

/**
 * Class AlgoliaConfigurator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AlgoliaConfigurator extends AbstractSystemConfigurator implements SearchAdapterConfiguratorInterface
{
    public function getAlias(): string
    {
        return 'algolia';
    }

    public function addFormFields(FormBuilderInterface $builder, FormInterface $form)
    {
        $algoliaSettings = $form->addChild($builder->getElement('nested_fieldset', [
            'name'  => 'algolia',
            'label' => 'algolia.fieldset.settings',
        ]));
        
        $algoliaSettings->addChild($builder->getElement('tip', [
            'tip' => 'algolia.tip.about',
        ]));
        
        $algoliaSettings->addChild($builder->getElement('text_field', [
            'name'  => 'appId',
            'label' => 'algolia.label.application_id',
        ]))->setValue($this->getParameter('appId'));
        
        $algoliaSettings->addChild($builder->getElement('text_field', [
            'name'  => 'apiKey',
            'label' => 'algolia.label.api_key',
        ]))->setValue($this->getParameter('apiKey'));
    }

    public function getDefaults(): array
    {
        return [
            'appId'  => 'C2VGT4PGWH',
            'apiKey' => 'b48fb9f6a4eee57f10e3079af095533b',
        ];
    }

    public function getSearchAdapterOptions(): array
    {
        return [
            'appId'         => $this->getParameter('appId'),
            'apiKey'        => $this->getParameter('apiKey'),
            'indexPrefix'   => $this->kernel->getContainer()->getParameter('search_index_prefix'),
            'termMinLength' => 3,
            'maxResults'    => 100,
            'builderClass'  => AlgoliaQueryBuilder::class,
        ];
    }
}
