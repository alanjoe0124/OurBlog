<?php

class Util
{
    public static function http_referer_validate()
    {
        if (!isset($_SERVER['HTTP_REFERER'])) {
            throw new InvalidArgumentException('Missing HTTP REFERER');
        }
        $referer = preg_match('#^http://([^/]+)#', $_SERVER['HTTP_REFERER'], $match);
        $domainName = $match[1];
        if (strlen($domainName) > 70) {
            throw new InvalidArgumentException('Domain name invalid');
        }
    }
}
