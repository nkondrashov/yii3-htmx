<?php
declare(strict_types=1);

namespace nkondrashov\yii3\htmx;

use Yiisoft\Html\Tag\Base\Tag;
use Yiisoft\Json\Json;

/**
 * This is class-decorator for Yii3 tags provide htmx parameters.
 */
class HTMX
{
    private const AFTER_REQUEST_EVENT = 'HX-Trigger';
    private const AFTER_SETTLE_EVENT = 'HX-Trigger-After-Settle';
    private const AFTER_SWAP_EVENT = 'HX-Trigger-After-Swap';

    private array $events = [
        self::AFTER_REQUEST_EVENT => [],
        self::AFTER_SETTLE_EVENT => [],
        self::AFTER_SWAP_EVENT => [],
    ];

    private array $triggers = [];

    private array $extensions = [];

    public function __construct(private Tag $tag)
    {
    }

    /**
     * Start tag decoration for htmx
     *
     * @param Tag $tag Only Yii class tags
     * @return static
     */
    public static function make(Tag $tag): self
    {
        return new self($tag);
    }

    /**
     * The core of htmx is a set of attributes that allow you to issue AJAX requests directly from HTML.
     * More: https://htmx.org/docs/#ajax
     *
     * @param string $method Method name GET, POST, PUT, PATCH, DELETE
     * @param string $url Url for request
     * @return $this
     */
    public function request(string $method, string $url): self
    {
        $attributes = [
            'hx-' . strtolower($method) => $url
        ];
        $this->tag = $this->tag->addAttributes($attributes);

        return $this;
    }

    /**
     * Method provide filter for the parameters that will be submitted with an AJAX request.
     * More: https://htmx.org/attributes/hx-params/
     *
     * @param string $params Params for request
     * @return $this
     */
    public function setRequestParams(string $params): static
    {
        $this->tag = $this->tag->addAttributes(['hx-params' => $params]);

        return $this;
    }

    /**
     * Method allows you to add to the parameters that will be submitted with an AJAX request.
     * More: https://htmx.org/attributes/hx-vals/
     *
     * @param array $vals key-value pairs
     * @return $this
     * @throws \JsonException
     */
    public function setRequestValues(array $vals):self
    {
        $this->tag = $this->tag->addAttributes(['hx-vals' => Json::encode($vals)]);

        return $this;
    }

    /**
     * Method allows you to switch the request encoding from the usual application/x-www-form-urlencoded
     * More: https://htmx.org/attributes/hx-encoding/
     *
     * @param string $encoding Encoding for request
     * @return $this
     */
    public function setRequestEncoding(string $encoding): static
    {
        $this->tag = $this->tag->addAttributes(['hx-encoding' => $encoding]);

        return $this;
    }

    /**
     * This is simply way to activate lazy-loading for element, like $this->addTriggers('load');
     *
     * @return $this
     */
    public function runOnLoad(): self
    {
        return $this->addTriggers('load');
    }

    /**
     * This is simply way to activate click-loading for element, like $this->addTriggers('click');
     *
     * @return $this
     */
    public function runOnClick(): self
    {
        return $this->addTriggers('click');
    }

    /**
     * This is simply way to activate change-loading for element, like $this->addTriggers('change');
     *
     * @return $this
     */
    public function runOnChange(): self
    {
        return $this->addTriggers('change');
    }

    /**
     * This is simply way to activate event-loading for element,
     * like $this->addTriggersOnCustomEvents('click') or $this->addTriggers('customEvent from:body');
     *
     * @return $this
     */
    public function runOnCustomEvent(...$event): self
    {
        return $this->addTriggersOnCustomEvents(...$event);
    }

    /**
     * Method allows you to specify how the response will be swapped in relative to the target of an AJAX request.
     * More: https://htmx.org/attributes/hx-swap/
     *
     * @param string $type Place for swap
     * @return $this
     */
    public function setSwap(string $type): self
    {
        $this->tag = $this->tag->addAttributes(['hx-swap' => $type]);

        return $this;
    }

    /**
     * Like setSwap() but for plugin 'multi-swap'.
     * More: https://htmx.org/extensions/multi-swap/
     *
     * @param string ...$items Places for swap
     * @return $this
     */
    public function setMultiSwap(string ...$items): self
    {
        $this->tag = $this->tag->addAttributes(['hx-swap' => implode(', ', $items)]);

        return $this->enableMultiSwap();
    }


    /**
     * This method provide you to specify that some content in a response should be swapped
     * into the DOM somewhere other than the target, that is “Out of Band”.
     * More: https://htmx.org/attributes/hx-swap-oob/
     *
     * @param string|bool $target Out of band place for swap
     * @return $this
     */
    public function setSwapOutOfBand(string|bool $target = true): static
    {
        $this->tag = $this->tag->addAttributes(['hx-swap-oob' => $target]);

        return $this;
    }

    /**
     * Method provide you to target a different element for swapping than the one issuing the AJAX request.
     * More: https://htmx.org/attributes/hx-target/
     *
     * @param string $target Target for request result
     * @return $this
     */
    public function setTarget(string $target): self
    {
        $this->tag = $this->tag->addAttributes(['hx-target' => $target]);

        return $this;
    }

    /**
     * Methos provide interface for extension allow you to specify different target elements to be swapped when
     * different HTTP response codes are received.
     * More: https://htmx.org/extensions/response-targets/
     *
     * @param string $code HTTP-code of response
     * @param string $target Target element
     * @return $this
     */
    public function setTargetOnError(string $code, string $target): self
    {
        $this->tag = $this->tag->addAttributes(['hx-target-' . $code => $target]);

        return $this;
    }

    /**
     * Method allows you to synchronize AJAX requests between multiple elements.
     * More: https://htmx.org/attributes/hx-sync/
     *
     * @param string $with
     * @return $this
     */
    public function setSync(string $with): static
    {
        $this->tag = $this->tag->addAttributes(['hx-sync' => $with]);

        return $this;
    }

    /**
     * Method allows you to confirm an action before issuing a request.
     * More: https://htmx.org/attributes/hx-confirm/
     *
     * @param string $text Question for user
     * @return $this
     */
    public function addConfirm(string $text): static
    {
        $this->tag = $this->tag->addAttributes(['hx-confirm' => $text]);

        return $this;
    }

    /**
     * Method allows you to specify the element that will have the
     * htmx-request class added to it for the duration of the request.
     * More: https://htmx.org/attributes/hx-indicator/
     *
     * @param string $id ID of indicator element
     * @return $this
     */
    public function addIndicator(string $id): static
    {
        $this->tag = $this->tag->addAttributes(['hx-indicator' => $id]);

        return $this;
    }

    /**
     * Method will disable htmx processing for a given element and all its children.
     * More: https://htmx.org/attributes/hx-disable/
     *
     * @return $this
     */
    public function disableHtmxProcessing(): static
    {
        $this->tag = $this->tag->addAttributes(['hx-disable' => '']);

        return $this;
    }

    /**
     * Method will disable to prevent sensitive data being saved to the localStorage cache when
     * htmx takes a snapshot of the page state.
     * More: https://htmx.org/attributes/hx-history/
     *
     * @return $this
     */
    public function disableHistory(): static
    {
        $this->tag = $this->tag->addAttributes(['hx-history' => false]);

        return $this;
    }

    /**
     * Method allows you to specify what triggers an AJAX request. A trigger value can be one of the following:
     * More: https://htmx.org/attributes/hx-trigger/
     *
     * @param string ...$trigger Triggers for element
     * @return $this
     */
    public function addTriggers(string ...$trigger): self
    {
        $this->triggers = array_merge($this->triggers, $trigger);
        $this->tag = $this->tag->addAttributes(['hx-trigger' => implode(', ', $this->triggers)]);

        return $this;
    }

    /**
     * Simply interface for subscribe element to custom events
     * More: https://htmx.org/attributes/hx-trigger/
     *
     * @param string ...$event List of events
     * @return $this
     */
    public function addTriggersOnCustomEvents(string ...$event)
    {
        $prepared = [];
        foreach ($event as $evnt) {
            $prepared[] = $evnt . ' from:body';
        }

        return $this->addTriggers(...$prepared);
    }

    /**
     * Technical method for send headers to mirror-middleware
     *
     * @return $this
     */
    private function prepareAndSetCustomEvents(): static
    {
        $events = array_filter($this->events, fn(array $item) => count($item) > 0);
        $eventsEncoded = Json::encode($events);
        $this->tag = $this->tag->addAttributes(['hx-headers' => ['Hx-Response-Events' => $eventsEncoded]]);

        return $this;
    }

    /**
     * Simply interface for send custom events to mirror-middleware for execute as soon as the response is received.
     * More: https://htmx.org/headers/hx-trigger/
     *
     * @param string $eventName Event name
     * @param array|string $data Event data
     * @return $this
     */
    public function triggerCustomEventAfterRequest(string $eventName, array|string $data = ''): self
    {
        $this->events[self::AFTER_REQUEST_EVENT][$eventName] = $data;

        return $this->prepareAndSetCustomEvents();
    }

    /**
     * Simply interface for send custom events to mirror-middleware for execute after the swap step.
     * More: https://htmx.org/headers/hx-trigger/
     *
     * @param string $eventName Event name
     * @param array|string $data Event data
     * @return $this
     */
    public function triggerCustomEventAfterSwap(string $eventName, array|string $data = ''): self
    {
        $this->events[self::AFTER_SWAP_EVENT][$eventName] = $data;

        return $this->prepareAndSetCustomEvents();
    }

    /**
     * Simply interface for send custom events to mirror-middleware for execute after the settling step.
     * More: https://htmx.org/headers/hx-trigger/
     *
     * @param string $eventName Event name
     * @param array|string $data Event data
     * @return $this
     */
    public function triggerCustomEventAfterSettle(string $eventName, array|string $data = ''): self
    {
        $this->events[self::AFTER_SETTLE_EVENT][$eventName] = $data;

        return $this->prepareAndSetCustomEvents();
    }

    /**
     * Technical method for connect extensions to elements
     *
     * @return $this
     */
    private function prepareAndSetExtensions(): static
    {
        $this->tag = $this->tag->addAttributes(['hx-ext' => implode(', ', $this->extensions)]);

        return $this;
    }

    /**
     * This extension includes log all htmx events for the element it is on, either through
     * the console.debug function or through the console.log function with a DEBUG: prefix.
     * More: https://htmx.org/extensions/debug/
     *
     * @return $this
     */
    private function enableDebugMode(): static
    {
        $this->extensions[] = 'debug';

        return $this->prepareAndSetExtensions();
    }

    /**
     * This extension encodes parameters in JSON format instead of url format.
     * More: https://htmx.org/extensions/json-enc/
     *
     * @return $this
     */
    public function enableJsonMode(): static
    {
        $this->extensions[] = 'json-enc';

        return $this->prepareAndSetExtensions();
    }

    /**
     * Alows to swap multiple elements with different swap methods
     * More: https://htmx.org/extensions/multi-swap/
     *
     * @return $this
     */
    public function enableMultiSwap(): static
    {
        $this->extensions[] = 'multi-swap';

        return $this->prepareAndSetExtensions();
    }

    /**
     * The hx-boost attribute allows you to “boost” normal anchors and form tags to use AJAX instead.
     * This has the nice fallback that, if the user does not have javascript enabled, the site will continue to work.
     * More: https://htmx.org/attributes/hx-boost/
     *
     * @return $this
     */
    public function enableBoosting(): static
    {
        $this->tag = $this->tag->addAttributes(['hx-boost' => true]);

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->tag->__toString();
    }

    /**
     * Method additional instructions for htmx
     *
     * @param string $name Name hx-{name}
     * @param string|array|bool $value Value  for attribute
     * @return $this
     */
    public function setHx(string $name, string|array|bool $value): self
    {
        $this->tag = $this->tag->addAttributes(['hx-'.$name => $value]);

        return $this;
    }
}
