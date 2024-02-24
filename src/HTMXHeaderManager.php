<?php

declare(strict_types=1);

namespace Nkondrashov\Yii3\Htmx;

class HTMXHeaderManager
{
    private const AFTER_REQUEST_EVENT = 'HX-Trigger';
    private const AFTER_SETTLE_EVENT = 'HX-Trigger-After-Settle';
    private const AFTER_SWAP_EVENT = 'HX-Trigger-After-Swap';

    private array $responseCustonHeaders = [];

    private array $responseEvents = [
        self::AFTER_REQUEST_EVENT => [],
        self::AFTER_SETTLE_EVENT => [],
        self::AFTER_SWAP_EVENT => [],
    ];

    private array $requestHeaders = [];

    /**
     * Simply interface for send custom events in header `HX-Trigger` for execute as soon as the response is received.
     * More: https://htmx.org/headers/hx-trigger/
     *
     * @param string $eventName Event name
     * @param array|string $data Event data
     * @return $this
     */
    public function triggerCustomEventAfterRequest(string $eventName, array|string $data = ''): void
    {
        $this->responseEvents[self::AFTER_REQUEST_EVENT][$eventName] = $data;
    }

    /**
     * Simply interface for send custom events in header `HX-Trigger-After-Swap` for execute after the swap step.
     * More: https://htmx.org/headers/hx-trigger/
     *
     * @param string $eventName Event name
     * @param array|string $data Event data
     * @return $this
     */
    public function triggerCustomEventAfterSwap(string $eventName, array|string $data = ''): void
    {
        $this->responseEvents[self::AFTER_SWAP_EVENT][$eventName] = $data;
    }

    /**
     * Simply interface for send custom events in header `HX-Trigger-After-Settle` for execute after the settling step.
     * More: https://htmx.org/headers/hx-trigger/
     *
     * @param string $eventName Event name
     * @param array|string $data Event data
     * @return $this
     */
    public function triggerCustomEventAfterSettle(string $eventName, array|string $data = ''): void
    {
        $this->responseEvents[self::AFTER_SETTLE_EVENT][$eventName] = $data;
    }

    /**
     * Technical method for get headers to mirror-middleware
     *
     * @return array
     */
    public function getResponseEvents(): array
    {
        return array_filter($this->responseEvents, fn(array $item) => count($item) > 0);
    }

    /**
     * Simply interface for send any custom header HX-{$header}
     *
     * @param string $header Header name
     * @param string $data Header data
     * @return $this
     */
    public function sendHXHeader(string $header, string $data): void
    {
        $this->responseCustonHeaders['HX-' . $header] = $data;
    }

    /**
     * Technical method for get headers to mirror-middleware
     *
     * @return array
     */
    public function getTXHeaders(): array
    {
        return $this->responseCustonHeaders;
    }

    /**
     * Technical method for add headers to mirror-middleware
     *
     * @return void
     */
    public function setRequestHeader(string $header, string $value): void
    {
        $this->requestHeaders[$header] = $value;
    }

    /**
     * Get value of request header
     *
     * @return array
     */
    public function getRequestHeader($header): ?string
    {
        if (isset($this->requestHeaders[$header])) {
            return $this->requestHeaders[$header];
        }

        return null;
    }

    /**
     * Check current request
     *
     * @return bool is htmx request
     */
    public function isHtmxRequest(): bool
    {
        return isset($this->requestHeaders['HX-Request']);
    }
}
