<?php

namespace App\Services\Urls\Interfaces;

interface UrlInterface
{
    /**
     * @param string $url
     * @return bool
     */
    public static function match(string $url): bool;

    /**
     * @param string $url
     * @return string|null
     */
    public static function getIdFromUrl(string $url): ?string;
}
