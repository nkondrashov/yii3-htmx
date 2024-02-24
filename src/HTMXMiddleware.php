<?php

declare(strict_types=1);

namespace Nkondrashov\Yii3\Htmx;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\Json\Json;

class HTMXMiddleware implements MiddlewareInterface
{
    public function __construct(private HTMXHeaderManager $eventManager)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        foreach ($request->getHeaders() as $headerName => $value) {
            if (strpos($headerName, 'HX-') !== false){
                $this->eventManager->setRequestHeader($headerName, current($value));
            }
        }

        $response = $handler->handle($request);

        $events = $this->eventManager->getResponseEvents();
        foreach ($events as $header => $list)
            $response = $response->withHeader($header, Json::encode($list));

        $events = $this->eventManager->getTXHeaders();
        foreach ($events as $header => $list)
            $response = $response->withHeader($header, $list);

        return $response;
    }
}
