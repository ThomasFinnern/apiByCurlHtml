<?php

namespace Finnern\apiByCurlHtml\src\curl_tasks;

/**
 * Object inside the response part of curl
 *  The error object has the following items
 *    - code -> error code
 *    - title -> ?compressed? error message
 *    - detail -> Stack ...
 */
class curlErrorObject
{
    public string $errorCode = "";
    public string $title = "";
    public string $detail = "";

    public function __construct(array|\stdClass|null $errorObj = null)
    {
        if (!empty($errorObj))
        {
            $this->errorCode = $errorObj['code'];
            $this->title     = $errorObj['title'];
            $this->detail    = $errorObj['detail'];
        }
    }

    public function assignByStrings(string $errorCode = "", string $title = "", string $detail = "")
    {
        $this->errorCode = $errorCode;
        $this->title     = $title;
        $this->detail    = $detail;

    }

    public function asArrayObject()
    {
        $errorObj           = [];
        $errorObj['code']   = $this->errorCode;
        $errorObj['detail'] = $this->detail;
        $errorObj['title']  = $this->title;

        return $errorObj;
    }

    public static function convert_slash_N(string $detail)
    {
        $detailCorrected = str_replace('\n', PHP_EOL . "   ", $detail);

        return $detailCorrected;
    }

    public function errorText(bool $isConvert_slash_N = false)
    {
        $outText = "";

        $outText .= "errorCode: " . $this->errorCode . PHP_EOL;
        $outText .= "title:     " . $this->title . PHP_EOL;
        if ($isConvert_slash_N)
        {
            $outText .= "detail:    " . self::convert_slash_N($this->detail) . PHP_EOL;
        }
        else
        {
            $outText .= "detail:    " . $this->detail . PHP_EOL;
        }

        return $outText;
    }

}