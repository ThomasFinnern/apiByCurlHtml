<?php

namespace Finnern\apiByCurlHtml\src\uri;

/**
 *  Keeps URI reserved chars translation list
 *
 * Implemented as actually known by developer (New to URI ...). May not be complete or valid
 * Borrowed code from joomla! project (2026)
 */
class uriReservedChars
{
    protected array $reserved_Uri2CharactersMap_all = [
        '%21' => '!',
        '%2A' => '*',
        '%27' => "'",
        '%28' => '(',
        '%29' => ')',
        '%3B' => ';',
        '%3A' => ':',
        '%40' => '@',
        '%26' => '&',
        '%3D' => '=',
        '%24' => '$',
        '%2C' => ',',
        '%2F' => '/',
        '%3F' => '?',
        '%23' => '#',
        '%5B' => '[',
        '%5D' => ']',
    ];

    protected array $reserved_Characters2UriMap_all = [
        '!' => '%21',
        '*' => '%2A',
        "'" => '%27',
        '(' => '%28',
        ')' => '%29',
        ';' => '%3B',
        ':' => '%3A',
        '@' => '%40',
        '&' => '%26',
        '=' => '%3D',
        '$' => '%24',
        ',' => '%2C',
        '/' => '%2F',
        '?' => '%3F',
        '#' => '%23',
        '[' => '%5B',
        ']' => '%5D',
    ];

    protected array $reserved_Uri2CharactersMap_part = [
        '%21' => '!',
        '%24' => '$',
        '%28' => '(',
        '%29' => ')',
        '%2A' => '*',
        '%2F' => '/',
        '%3A' => ':',
        '%3B' => ';',
        '%40' => '@',
        '%5B' => '[',
        '%5D' => ']',
    ];

    protected array $reserved_Characters2UriMap_Utf8 = [
        '!' => '%21',
        '$' => '%24',
        '(' => '%28',
        ')' => '%29',
        '*' => '%2A',
        '/' => '%2F',
        ':' => '%3A',
        ';' => '%3B',
        '@' => '%40',
        '[' => '%5B',
        ']' => '%5D',
    ];


}