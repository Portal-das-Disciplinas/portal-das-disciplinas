<?php

namespace App\Services\Urls;

class GoogleDriveService extends UrlService
{
    /**
     * @var string
     */
    protected $matchRegex = '#https://drive\.google\.com/file/d/(.*?)/.*?\?usp=sharing#';

    /**
     * Inspirado em
     * https://stackoverflow.com/questions/3392993/php-regex-to-get-youtube-video-id/3393008#3393008
     * @param string $url
     * @return false|mixed|string
     */
    public static function getIdFromUrl(string $url): ?string
    {
        $parts = explode("/", $url);

        if(isset($parts[5])) {
            return $parts[5];
        }

        return false;
    }
}
