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

namespace WellCommerce\Bundle\CoreBundle\Helper\Flash;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use WellCommerce\Bundle\CoreBundle\Helper\Translator\TranslatorHelperInterface;

/**
 * Class FlashHelper
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class FlashHelper implements FlashHelperInterface
{
    /**
     * @var SessionInterface
     */
    protected $session;
    
    /**
     * @var TranslatorHelperInterface
     */
    protected $translatorHelper;
    
    public function __construct(SessionInterface $session, TranslatorHelperInterface $translatorHelper)
    {
        $this->session          = $session;
        $this->translatorHelper = $translatorHelper;
    }
    
    public function addSuccess(string $message, array $params = [])
    {
        $this->addMessage(FlashHelperInterface::FLASH_TYPE_SUCCESS, $message, $params);
    }
    
    public function addNotice(string $message, array $params = [])
    {
        $this->addMessage(FlashHelperInterface::FLASH_TYPE_NOTICE, $message, $params);
    }
    
    public function addError(string $message, array $params = [])
    {
        $this->addMessage(FlashHelperInterface::FLASH_TYPE_ERROR, $message, $params);
    }
    
    private function addMessage(string $type, string $message, array $params)
    {
        $message = $this->translate($message, $params);
        
        $this->getFlashBag()->add($type, $message);
    }
    
    private function translate(string $message, array $params): string
    {
        return $this->translatorHelper->trans($message, $params, 'wellcommerce');
    }
    
    private function getFlashBag(): FlashBag
    {
        return $this->session->getBag(FlashHelperInterface::FLASHES_NAME);
    }
}
