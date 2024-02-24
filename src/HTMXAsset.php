<?php

declare(strict_types=1);

namespace Nkondrashov\Yii3\Htmx;

use Yiisoft\Assets\AssetBundle;

final class HTMXAsset extends AssetBundle
{
    public ?string $basePath = '@assets';

    public ?string $baseUrl = '@assetsUrl';

    public ?string $sourcePath = '@npm/htmx.org/dist';

    public array $js = [
        'htmx.js',
        'ext/json-enc.js',
        'ext/multi-swap.js',
        'ext/response-targets.js',
    ];

    public function __construct()
    {
        if (!str_starts_with(getenv('YII_ENV') ?: '', 'prod')) {
            $this->js[] = 'ext/debug.js';
        }
    }
}
