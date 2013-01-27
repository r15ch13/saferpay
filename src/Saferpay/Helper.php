<?php namespace Saferpay;

class Helper
{
    public static function string_starts_with($needle, $haystack) {
        return !strncmp($haystack, $needle, strlen($needle));
    }

    public static function string_ends_with($needle, $haystack) {
        return substr($haystack, -strlen($needle)) === $needle;
    }

    public static function appendParamsToUrl($url, array $params)
    {
        foreach ($params as $k => $v) {

            $url .= strpos($url, '?') === false ? '?' : '&';
            if(is_string($k)) {
                $url .= sprintf("%s=%s", $k, urlencode($v));
            } else {
                $url .= urlencode($v);
            }
        }
        return $url;
    }

    public static function parseResponseXml($xml)
    {
        if($xml && $xml = @simplexml_load_string($xml)) {
            $data = (array)$xml->attributes();
            return $data['@attributes'];
        }
        return array();
    }
}
