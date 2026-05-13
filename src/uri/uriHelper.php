<?php

namespace Finnern\apiByCurlHtml\src\uri;

/**
 * URI (Uniform Resource Identifier)
 *
 * Supports the use of URI related functions. Does not keep a URI definition itself
 *
 * Used for exchanging the URI reserved characters
 */

class uriHelper extends uriReservedChars
{



    // URL with UTF-8 chars in the url.



//    // Encode the URL (so UTF-8 chars are encoded), revert the encoding in the reserved uri characters and parse the url.
//$parts = parse_url(strtr(urlencode($url), $reservedUriCharactersMap), $component);
//
//    // With a well formed url decode the url (so UTF-8 chars are decoded).
//return $parts ? array_map('urldecode', $parts) : $parts;

    /**
     * Encode square brackets in the URI query, according to JSON API specification.
     *
     * Borrowed from joomla! project (2026)
     *
     * @param   string  $query  The URI query
     *
     * @return  string
     *
     * @since   4.0.0
     */
    public static function queryEncode($query)
    {
        return str_replace(['[', ']'], ['%5B', '%5D'], $query);
    }

    /**
    * Extra cleanup to remove invalid chars in the URL to prevent injections through the Host header
    */
    public static function uriEncode($uri)
    {
        // ToDo: may use uriReservedChars:: reserved_Uri2CharactersMap_all / ..._utf8
        return str_replace(["'", '"', '<', '>'], ['%27', '%22', '%3C', '%3E'], $uri);
    }

    // ??? Build the reserved uri encoded characters map.

//    /**
//     * Merges and encodes a query array with a query string.
//     *
//     * Borrowed from joomla! project (2026)
//     * @throws \InvalidArgumentException When an invalid query-string value is passed
//     */
//    private static function mergeQueryString(?string $queryString, array $queryArray, bool $replace): ?string
//    {
//        if (!$queryArray) {
//            return $queryString;
//        }
//
//        $query = [];
//
//        // ...
//        if (null !== $queryString) {
//            foreach (explode('&', $queryString) as $v) {
//                if ('' !== $v) {
//                    $k = urldecode(explode('=', $v, 2)[0]);
//                    $query[$k] = (isset($query[$k]) ? $query[$k].'&' : '').$v;
//                }
//            }
//        }
//
//        // ...
//        if ($replace) {
//            foreach ($queryArray as $k => $v) {
//                if (null === $v) {
//                    unset($query[$k]);
//                }
//            }
//        }
//
//        // ...
//        $queryString = http_build_query($queryArray, '', '&', \PHP_QUERY_RFC3986);
//        $queryArray = [];
//
//        if ($queryString) {
//            if (str_contains($queryString, '%')) {
//                // https://tools.ietf.org/html/rfc3986#section-2.3 + some chars not encoded by browsers
//                $queryString = strtr($queryString, [
//                    '%21' => '!',
//                    '%24' => '$',
//                    '%28' => '(',
//                    '%29' => ')',
//                    '%2A' => '*',
//                    '%2F' => '/',
//                    '%3A' => ':',
//                    '%3B' => ';',
//                    '%40' => '@',
//                    '%5B' => '[',
//                    '%5D' => ']',
//                ]);
//            }
//
//            foreach (explode('&', $queryString) as $v) {
//                $queryArray[rawurldecode(explode('=', $v, 2)[0])] = $v;
//            }
//        }
//
//        return implode('&', $replace ? array_replace($query, $queryArray) : ($query + $queryArray));
//    }




}