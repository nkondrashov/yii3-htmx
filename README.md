<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://yiisoft.github.io/docs/images/yii_logo.svg" height="100px">
    </a>
    <a href="https://htmx.org/" target="_blank" rel="external">
        <img src="https://raw.githubusercontent.com/bigskysoftware/htmx/master/www/static/img/htmx_logo.1.png" height="100px">
    </a>
    <h1 align="center">Yii3 Framework htmx simple extension</h1>
    <br>
</p>

This [Yii Framework] extension encapsulates basic functions [htmx] and makes using in Yii applications extremely easy.

You can see examples in [yii3-demo-htmx](https://github.com/nkondrashov/yii3-demo-htmx) application.

[Yii Framework]:        http://www.yiiframework.com/
[</> htmx]:  https://htmx.org/docs/

Installation
------------

1. The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

```
php composer.phar require nkondrashov/yii3-htmx "^0.2"
```

2. Add `HTMXMiddleware.php` to router.
```php
->middleware(HTMXMiddleware::class)
```

3. Register asset in main layout or `AppAsset`
```php
$assetManager->register(HTMXAsset::class);
```

Warning!
------------
4. Add to `<body>` tag attribute `hx-headers='{"X-CSRF-Token":"<?=$csrf; ?>"}'` for success post requests. Like this:
```html
<body hx-headers='{"X-CSRF-Token":"<?=$csrf; ?>"}'>
```

## Install assets

Using the [npm-asset](https://www.npmjs.com/) package manager.

Run the following command at the root directory of your application.

```shell
npm i htmx.org@1.9.10
```

## General usage

Examples
-------

Simple:
```php
<?php
$tag = Yiisoft\Html\Html::button('[ X ]']);

$htmx = HTMX::make($tag)
            ->request(Yiisoft\Http\Method::DELETE, '/item/delete/' . $todo->id)
            ->setSwap('none')
            ->runOnClick();

if (!$todo->is_complete) {
    $htmx->addConfirm('Are you sure?');
}

echo $htmx;
 ?>
```

```php
<?= HTMX::make(Yiisoft\Html\Html::tag('div'))
        ->request(Yiisoft\Http\Method::GET, '/item/index')
        ->runOnCustomEvent('someCustomEvent', 'someCustomEvent2')
        ->runOnLoad();?>
```

More native:
```php
<?php
$tag = Yiisoft\Html\Html::button('[ X ]']);

$htmx = HTMX::make($tag)
            ->request(Yiisoft\Http\Method::DELETE, '/item/delete/' . $todo->id)
            ->addTriggers('click')
            ->setSwap('none');

if (!$todo->is_complete) {
    $htmx->addConfirm('Are you sure?');
}

echo $htmx;
 ?>
```

```php
<?= HTMX::make(Yiisoft\Html\Html::tag('div'))
        ->request(Yiisoft\Http\Method::GET, '/item/index')
        ->addTriggers('load','someCustomEvent from:body', 'someCustomEvent2 from:body');
?>
```

Max native:
```php
<?php
$tag = Yiisoft\Html\Html::button('[ X ]']);

$htmx = HTMX::make($tag)
            ->setHx('delete', '/item/delete/' . $todo->id)
            ->setHx('trigger', 'click')
            ->setHx('swap', 'none');

if (!$todo->is_complete) {
    $htmx->setHx('conf', 'Are you sure?');
}

echo $htmx;
 ?>
```

```php
<?= HTMX::make(Yiisoft\Html\Html::tag('div'))
        ->setHx('get', '/item/index')
        ->setHx('trigger', 'load, someCustomEvent from:body, someCustomEvent2 from:body');
?>
```

Send htmx headers in response:
```php
//Definition:
...
    public function __construct(private HTMXHeaderManager $headerManager)
    {}
...

//In code:
$this->headerManager->triggerCustomEventAfterSwap('updateList');
//or native:
$this->headerManager->sendHXHeader('Trigger', 'updateList');

//Detect htmx request:
if ($this->headerManager->isHtmxRequest()) {
    //Example: This request without htmx header
}

//Get data from header:
$value = $this->headerManager->getRequestHeader('Hx-Trigger-Name');
```



### Support the Yii3 project

[![Open Collective](https://img.shields.io/badge/Open%20Collective-sponsor-7eadf1?logo=open%20collective&logoColor=7eadf1&labelColor=555555)](https://opencollective.com/yiisoft)

### Follow Yii3 updates

[![Official website](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](https://www.yiiframework.com/)
[![Twitter](https://img.shields.io/badge/twitter-follow-1DA1F2?logo=twitter&logoColor=1DA1F2&labelColor=555555?style=flat)](https://twitter.com/yiiframework)
[![Telegram](https://img.shields.io/badge/telegram-join-1DA1F2?style=flat&logo=telegram)](https://t.me/yii3en)
[![Facebook](https://img.shields.io/badge/facebook-join-1DA1F2?style=flat&logo=facebook&logoColor=ffffff)](https://www.facebook.com/groups/yiitalk)
[![Slack](https://img.shields.io/badge/slack-join-1DA1F2?style=flat&logo=slack)](https://yiiframework.com/go/slack)

## License

The Yii Framework htmx Extension is free software. It is released under the terms of the BSD License.
Please see [`LICENSE`](./LICENSE.md) for more information.

This package maintained by Me ¯\_(ツ)_/¯
