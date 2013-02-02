<?php namespace Saferpay;

class Helper
{
    /**
     * Determine if a given string ends with a given needle.
     *
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    public static function ends_with($haystack, $needle)
    {
        return $needle == substr($haystack, strlen($haystack) - strlen($needle));
    }

    /**
     * Determine if a string starts with a given needle.
     *
     * @param  string  $haystack
     * @param  string  $needle
     * @return bool
     */
    public static function starts_with($haystack, $needle)
    {
        return strpos($haystack, $needle) === 0;
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
        libxml_use_internal_errors(true);
        if($xml && $xml = @simplexml_load_string($xml)) {
            $data = (array)$xml->attributes();
            return $data['@attributes'];
        }
        return array();
    }
}
