<?php

namespace Finnern\apiByCurlHtml\src\curl_tasks;

/**
 * Object inside the response part of curl
 *  The error object has the following items
 *    - code -> error code
 *    - title -> ?compressed? error message
 *    - detail -> Stack ...
 * sometimes it has no 'title' or other indicator {"error":"You must install Joomla to use the API"}
 */
class curlErrorObject
{
    public string $errorCode = "";
    public string $title = "";
    public string $detail = "";
    public string $error = "";
    public string $message = "";

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
            if (!empty ($errorObj['error']))
            {
                $this->error = $errorObj['error'];
            }

            // Check for unexpected items in response

            foreach ($errorObj as $key => $value)
            {

                switch ($key)
                {

                    case 'code':
                    case 'error':
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

    public function assignByStrings(string $errorCode = "", string $title = "", string $detail = "", string $error = "")
    {
        $this->errorCode = $errorCode;
        $this->title     = $title;
        $this->detail    = $detail;
        $this->error     = $error;

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
        if (!empty($this->error))
        {
            $errorObj['error'] = $this->error;
        }

        return $errorObj;
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

        if ($isConvert_slash_N)
        {
            $outText .= "error:    " . self::convert_slash_N($this->error) . PHP_EOL;
        }
        else
        {
            $outText .= "error:    " . $this->error . PHP_EOL;
        }

        return $outText;
    }

    public static function convert_slash_N(string $detail)
    {
        $detailCorrected = str_replace('\n', PHP_EOL . "   ", $detail);

        return $detailCorrected;
    }

}
