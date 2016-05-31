<?php

namespace App\Action;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class GetOrderReferenceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        $pwaConfig = array();
        if (isset($config['pwaConfig'])) {
            $pwaConfig = $config['pwaConfig'];
        }
        return new GetOrderReferenceAction($pwaConfig);
    }
}
