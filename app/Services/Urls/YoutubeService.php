<?php

namespace App\Services\Urls;

class YoutubeService extends UrlService
{
    /**
     * @var string
     */
    protected $matchRegex = '#^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$#';

    /**
     * Inspirado em
     * https://stackoverflow.com/questions/3392993/php-regex-to-get-youtube-video-id/3393008#3393008
     * @param string $url
     * @return false|mixed|string
     */
    public static function getIdFromUrl(string $url): ?string
    {
        $parts = parse_url($url);
        if (isset($parts['query'])) {
            parse_str($parts['query'], $qs);

            if (isset($qs['v'])) {
                return $qs['v'];
            } elseif (isset($qs['vi'])) {
                return $qs['vi'];
            }
        }

        if (isset($parts['path'])) {
            $path = explode('/', trim($parts['path'], '/'));
            return $path[count($path) - 1];
        }

        return false;
    }
}
