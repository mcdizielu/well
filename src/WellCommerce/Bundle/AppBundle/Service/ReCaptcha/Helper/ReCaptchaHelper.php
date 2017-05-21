<?php

namespace WellCommerce\Bundle\AppBundle\Service\ReCaptcha\Helper;

use ReCaptcha\ReCaptcha;
use WellCommerce\Bundle\AppBundle\Service\System\Configuration\SystemConfiguratorInterface;

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
    
    public function __construct(SystemConfiguratorInterface $configurator)
    {
        $this->configurator = $configurator;
    }
    
    public function render(): string
    {
        $content = '';
        if ($this->configurator->getParameter('enabled')) {
            $content .= '<script src="' . self::RECAPTCHA_JS_URL . '"></script>';
            $content .= '<div class="g-recaptcha" data-sitekey="' . $this->configurator->getParameter('siteKey') . '"></div>';
        }
        
        return $content;
    }
    
    public function isValid(): bool
    {
        if ($this->configurator->getParameter('enabled')) {
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
}
