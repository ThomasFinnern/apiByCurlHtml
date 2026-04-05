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
            if (!empty ($errorObj['code']))
            {
                $this->errorCode = $errorObj['code'];
            }
            if (!empty ($errorObj['title']))
            {
                $this->title = $errorObj['title'];
            }
            if (!empty ($errorObj['detail']))
            {
                $this->detail = $errorObj['detail'];
            }

            // Check for unexpected items in response

            foreach ($errorObj as $key => $value)
            {

                switch ($key)
                {

                    case 'code':
                    case 'message':
                    case 'title':
                    case 'detail':
                        break;

                    default:
                        print ('??? unexpected item: ' . $key . ': "' . $value . '" ???' . PHP_EOL);
                        break;
                }
            }
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
        $errorObj = [];
        if (!empty($this->errorCode))
        {
            $errorObj['code'] = $this->errorCode;
        }
        if (!empty($this->detail))
        {
            $errorObj['detail'] = $this->detail;
        }
        if (!empty($this->title))
        {
            $errorObj['title'] = $this->title;
        }

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

        $outText .= "title:     " . $this->title . PHP_EOL;
        $outText .= "code: " . $this->errorCode . PHP_EOL;
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