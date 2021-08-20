<?php

namespace App\Services\Urls\Traits;

trait UrlTrait
{
    /**
     * @param string $url
     * @return false|int
     */
    public static function match(string $url) {
        $match = (new static)->matchRegex;

        if (empty($match)) {
            return false;
        }

        return preg_match($match, $url);
    }
}
