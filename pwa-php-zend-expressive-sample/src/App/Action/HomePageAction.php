<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template;
use Zend\Expressive\ZendView\ZendViewRenderer;

class HomePageAction
{
    private $router;

    private $template;

    public function __construct(Router\RouterInterface $router, Template\TemplateRendererInterface $template = null, $pwaConfig)
    {
        $this->router = $router;
        $this->template = $template;
        $this->pwaConfig = $pwaConfig;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $data = [
            'seller_id' => isset($this->pwaConfig['merchant_id']) ? $this->pwaConfig['merchant_id'] : $this->pwaConfig['merchant_id'],
            'application_id' => isset($this->pwaConfig['client_id']) ? $this->pwaConfig['client_id'] : $this->pwaConfig['client_id'],
        ];

        return new HtmlResponse($this->template->render('app::home-page', $data));
    }
}
