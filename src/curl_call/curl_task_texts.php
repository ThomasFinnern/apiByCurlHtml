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

        $outTxt .= '---------------------------------------------------------' . "\r\n";
        $outTxt .= ">>> curl_exec with response: " . "\r\n";
        $outTxt .= '---------------------------------------------------------' . "\r\n";

        return $outTxt;
    }

    public function responseText(): string
    {
        $outTxt = "";

        // all in one line
        $outTxt .= $this->oCurlCall->responseJson . "\r\n";

        return $outTxt;
    }

    public function responseBeautifiedText(): string
    {
        $outTxt = "";

        // objects in more lines
        $outTxt .= $this->oCurlCall->responseJsonBeautified . "\r\n";

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
        $replaced = implode("\r\n", $splitted);

        return $replaced;
    }

    public function footerText(): string
    {
        $outTxt = "";

        $outTxt .= '---------------------------------------------------------' . "\r\n";
        $outTxt .= ">>> End curl_exec with response: " . "\r\n";
        $outTxt .= '---------------------------------------------------------' . "\r\n";

        return $outTxt;
    }

    public function errorCodeText(): string
    {
        $outTxt = "";

        $outTxt .= "\r\n";
        $outTxt .= '---------------------------------------------------------' . "\r\n";
        $outTxt .= "!!! curl_exec: has failed with error: '" . $this->oCurlCall->errorCode . "' !!!" . "\r\n";

        return $outTxt;
    }

    public function jsonErrorsCrLfText()
    {
        $outTxt = "";

        $outTxt .= "\r\n";
        $outTxt .= '---------------------------------------------------------' . "\r\n";
        $outTxt .= "!!! curl_exec: has failed with json errors: " . " !!!" . "\r\n";

        if (!empty($this->oCurlCall->oResponse->errors)) {

            // [errors]: exist in json
            $json_errors = new json_errors($this->oCurlCall->oResponse->errors);
            $outTxt .= $json_errors->text(); // . "\r\n";

        } else {

            $outTxt = "jsonErrorsCrLfText: oResponse does not contain errors" . "\r\n";
        }

        return $outTxt;
    }

}
