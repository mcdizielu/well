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
namespace WellCommerce\Bundle\AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;
use WellCommerce\Bundle\CoreBundle\Helper\Templating\TemplatingHelperInterface;

/**
 * Class ExceptionSubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @var KernelInterface
     */
    protected $kernel;
    
    /**
     * @var TemplatingHelperInterface
     */
    protected $templatingHelper;
    
    public function __construct(KernelInterface $kernel, TemplatingHelperInterface $templatingHelper)
    {
        $this->kernel           = $kernel;
        $this->templatingHelper = $templatingHelper;
    }
    
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 0],
        ];
    }
    
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if ($this->kernel->getEnvironment() !== 'dev') {
            $exception = $event->getException();
            if ($exception instanceof HttpExceptionInterface) {
                $content = $this->templatingHelper->render('WellCommerceAppBundle:Front/Exception:404.html.twig', [
                    'message' => $exception->getMessage(),
                    'code'    => $exception->getCode(),
                ]);
            } else {
                $content = $this->templatingHelper->render('WellCommerceAppBundle:Front/Exception:500.html.twig', [
                    'message' => $exception->getMessage(),
                    'code'    => $exception->getCode(),
                ]);
            }
            
            $response = new Response();
            $response->setContent($content);
            
            if ($exception instanceof HttpExceptionInterface) {
                $response->setStatusCode($exception->getStatusCode());
                $response->headers->replace($exception->getHeaders());
            } else {
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            
            $event->setResponse($response);
        }
    }
}
