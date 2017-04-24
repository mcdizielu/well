<?php

namespace WellCommerce\Bundle\CoreBundle\Helper\Pdf;

use Knp\Snappy\GeneratorInterface;
use WellCommerce\Bundle\AppBundle\Manager\SystemConfigurationManager;

/**
 * Class PdfHelper
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class PdfHelper implements PdfHelperInterface
{
    /**
     * @var GeneratorInterface
     */
    private $generator;
    
    /**
     * @var SystemConfigurationManager
     */
    private $configurationManager;
    
    public function __construct(GeneratorInterface $generator, SystemConfigurationManager $configurationManager)
    {
        $this->generator            = $generator;
        $this->configurationManager = $configurationManager;
    }
}
