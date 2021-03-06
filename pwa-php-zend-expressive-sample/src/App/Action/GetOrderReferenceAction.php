<?php

namespace App\Action;

use Zend\Expressive\Router;
use Zend\Expressive\Template;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use PayWithAmazon\Client as Client;

class GetOrderReferenceAction
{
    public function __construct($pwaConfig)
    {
        $this->pwaConfig = $pwaConfig;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $id = $request->getAttribute('order_id');

        // Instantiate the client object with the configuration
        $client = new Client($this->pwaConfig);
        $requestParameters = array();
        $requestParameters['amazon_order_reference_id'] = $id;
        $orderReferenceResponse = $client->getOrderReferenceDetails($requestParameters);

        if ($client->success) {
            return new JsonResponse($orderReferenceResponse->toArray());
        }

        return $response->withStatus(400);
    }
}