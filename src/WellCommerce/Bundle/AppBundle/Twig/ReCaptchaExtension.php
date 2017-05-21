<?php

namespace WellCommerce\Bundle\AppBundle\Twig;

use WellCommerce\Bundle\AppBundle\Service\ReCaptcha\Helper\ReCaptchaHelper;

/**
 * Class ReCaptchaExtension
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ReCaptchaExtension extends \Twig_Extension
{
    /**
     * @var ReCaptchaHelper
     */
    private $helper;
    
    public function __construct(ReCaptchaHelper $helper)
    {
        $this->helper = $helper;
    }
    
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('recaptcha', [$this, 'render'], ['is_safe' => ['html']]),
        ];
    }
    
    public function render(): string
    {
        return $this->helper->render();
    }
    
    public function getName()
    {
        return 'recaptcha';
    }
}
