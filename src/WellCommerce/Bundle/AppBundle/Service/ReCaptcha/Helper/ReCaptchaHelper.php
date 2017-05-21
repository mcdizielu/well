<?php

namespace WellCommerce\Bundle\AppBundle\Service\ReCaptcha\Helper;

use ReCaptcha\ReCaptcha;
use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\AppBundle\Service\System\Configuration\SystemConfiguratorInterface;
use WellCommerce\Bundle\CoreBundle\Helper\Security\SecurityHelperInterface;

/**
 * Class ReCaptchaHelper
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ReCaptchaHelper
{
    const RECAPTCHA_JS_URL = 'https://www.google.com/recaptcha/api.js';
    
    /**
     * @var SystemConfiguratorInterface
     */
    private $configurator;
    
    /**
     * @var SecurityHelperInterface
     */
    private $securityHelper;
    
    public function __construct(SystemConfiguratorInterface $configurator, SecurityHelperInterface $securityHelper)
    {
        $this->configurator   = $configurator;
        $this->securityHelper = $securityHelper;
    }
    
    public function render(): string
    {
        $content = '';
        if ($this->isEnabled()) {
            $content .= '<script src="' . self::RECAPTCHA_JS_URL . '"></script>';
            $content .= '<div class="g-recaptcha" data-sitekey="' . $this->configurator->getParameter('siteKey') . '"></div>';
        }
        
        return $content;
    }
    
    public function isValid(): bool
    {
        if ($this->isEnabled()) {
            try {
                $captcha  = new ReCaptcha($this->configurator->getParameter('secretKey'));
                $response = $captcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                
                return $response->isSuccess();
            } catch (\Exception $exception) {
                return true;
            }
        }
        
        return true;
    }
    
    private function isEnabled(): bool
    {
        if ($this->securityHelper->getCurrentClient() instanceof Client) {
            return $this->configurator->getParameter('enabledForClient');
        }
        
        return $this->configurator->getParameter('enabledForGuest');
    }
}
