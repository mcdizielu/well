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

namespace WellCommerce\Bundle\CoreBundle\Helper\Translator;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Translation\MessageCatalogueInterface;

/**
 * Class TranslatorHelper
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class TranslatorHelper implements TranslatorHelperInterface
{
    /**
     * @var Translator
     */
    private $translator;
    
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }
    
    public function trans(string $message, array $params = [], string $domain = self::DEFAULT_TRANSLATION_DOMAIN): string
    {
        return $this->translator->trans($message, $params, $domain);
    }
    
    public function getMessages(string $locale, string $domain = self::DEFAULT_TRANSLATION_DOMAIN): array
    {
        $catalogue = $this->getCatalogue($locale);
        
        return $catalogue->all($domain);
    }
    
    protected function getCatalogue(string $locale): MessageCatalogueInterface
    {
        return $this->translator->getCatalogue($locale);
    }
}
