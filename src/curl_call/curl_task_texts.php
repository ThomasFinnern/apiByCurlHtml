<?php

namespace Finnern\apiByCurlHtml\src\curl_call;

/**
 *
 */
class curl_task_texts
{
    // Keep it simple , keep complete object and go through array on print

    protected curl_call $oCurlCall;

    public function __construct(curl_call|false $oCurlCall)
    {
        $this->oCurlCall = $oCurlCall;
    }

    public function headerText(): string
    {
        $outTxt = "";

        $outTxt .= '---------------------------------------------------------' . PHP_EOL;
        $outTxt .= ">>> curl_exec with response: " . PHP_EOL;
        $outTxt .= '---------------------------------------------------------' . PHP_EOL;

        return $outTxt;
    }

    public function responseText(): string
    {
        $outTxt = "";

        // all in one line
        $outTxt .= $this->oCurlCall->responseJson . PHP_EOL;

        return $outTxt;
    }

    public function responseBeautifiedText(): string
    {
        $outTxt = "";

        // objects in more lines
        $outTxt .= $this->oCurlCall->responseJsonBeautified . PHP_EOL;

        return $outTxt;
    }


    /**
     * The json response has "\n" for general items and in th text '\n'
     * We replace the '\n' to achive crlf for newline in files and print
     * @return string
     */
    public function responseCrLfText(): string
    {
        $response = (string) $this->oCurlCall->responseJsonBeautified;

        $splitted = explode('\n', $response);
        $replaced = implode(PHP_EOL, $splitted);

        return $replaced;
    }

    public function footerText(): string
    {
        $outTxt = "";

        $outTxt .= '---------------------------------------------------------' . PHP_EOL;
        $outTxt .= ">>> End curl_exec with response: " . PHP_EOL;
        $outTxt .= '---------------------------------------------------------' . PHP_EOL;

        return $outTxt;
    }

    public function errorCodeText(): string
    {
        $outTxt = "";

        $outTxt .= PHP_EOL;
        $outTxt .= '---------------------------------------------------------' . PHP_EOL;
        $outTxt .= "!!! curl_exec: has failed with error: '" . $this->oCurlCall->errorCode . "' !!!" . PHP_EOL;

        return $outTxt;
    }

    public function jsonErrorsCrLfText()
    {
        $outTxt = "";

        $outTxt .= PHP_EOL;
        $outTxt .= '---------------------------------------------------------' . PHP_EOL;
        $outTxt .= "!!! curl_exec: has failed with json errors: " . " !!!" . PHP_EOL;

        if (!empty($this->oCurlCall->oResponse->errors)) {

            // [errors]: exist in json
            $json_errors = new json_errors($this->oCurlCall->oResponse->errors);
            $outTxt .= $json_errors->text(); // . PHP_EOL;

        } else {

            $outTxt = "jsonErrorsCrLfText: oResponse does not contain errors" . PHP_EOL;
        }

        return $outTxt;
    }

}
