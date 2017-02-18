<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Component\Form;

use WellCommerce\Bundle\DoctrineBundle\Entity\EntityInterface;
use WellCommerce\Component\Form\Dependencies\DependencyInterface;
use WellCommerce\Component\Form\Elements\ElementInterface;
use WellCommerce\Component\Form\Elements\FormInterface;
use WellCommerce\Component\Form\Filters\FilterInterface;

/**
 * Interface FormBuilderInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface FormBuilderInterface
{
    /**
     * Returns the alias
     *
     * @return string
     */
    public function getAlias(): string;
    
    /**
     * Creates the form, triggers init event and then populates form with values
     *
     * @param EntityInterface|null $defaultData
     * @param array                $options
     *
     * @return FormInterface
     */
    public function createForm(EntityInterface $defaultData = null, array $options = []): FormInterface;
    
    /**
     * Returns an element object by its type
     *
     * @param string $type
     * @param array  $options
     *
     * @return ElementInterface
     */
    public function getElement(string $type, array $options = []): ElementInterface;
    
    /**
     * Returns a filter object by its type
     *
     * @param string $type
     * @param array  $options
     *
     * @return FilterInterface
     */
    public function getFilter(string $type, array $options = []): FilterInterface;
    
    /**
     * Returns a dependency object by its type
     *
     * @param string $type
     * @param array  $options
     *
     * @return DependencyInterface
     */
    public function getDependency(string $type, array $options = []): DependencyInterface;
}
