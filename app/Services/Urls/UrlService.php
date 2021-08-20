<?php

namespace App\Services\Urls;

use App\Services\Urls\Interfaces\UrlInterface;
use App\Services\Urls\Traits\UrlTrait;

class UrlService implements UrlInterface
{
    use UrlTrait;

    /**
     * @var string|null
     */
    protected $matchRegex = null;

    /**
     * @param string $url
     * @return bool
     */
    public static function match(string $url): bool
    {
        $match = (new static)->matchRegex;

        if (empty($match)) {
            return false;
        }

        return preg_match($match, $url);
    }

    /**
     * @inheritDoc
     */
    public static function getIdFromUrl(string $url): ?string
    {
        return false;
    }
}
