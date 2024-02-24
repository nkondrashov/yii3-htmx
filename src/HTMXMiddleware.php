<?php

declare(strict_types=1);

namespace Nkondrashov\Yii3\Htmx;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\Json\Json;

final class HTMXMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        if ($request->hasHeader('Hx-Response-Events')) {
            $HTMXResponseHeader = $request->getHeader('Hx-Response-Events');
            $value = current($HTMXResponseHeader);
            $decoded = Json::decode($value);
            foreach ($decoded as $header => $events)
                $response = $response->withHeader($header, Json::encode($events));
        }

        return $response;
    }
}
