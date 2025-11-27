<?php

namespace Finnern\apiByCurlHtml\src\curl_call;

/**
 * Handles the call and beautifies the result
 */
class curl_call
{
    // Keep it simple , keep complete object and go through array on print

    protected \curlHandle|false $oCurl;
    public string $responseJson;
    public string $responseJsonBeautified;
    public int $errorCode;
    public bool $isJsonHasErrors; // json


    public mixed $oResponse;
    public mixed $responseArray;

    public function curl_exec(\curlHandle|false $curlHandle): int
    {
        $this->oCurl = $curlHandle;

        //=============================================
        // call curl
        //============================================
        $this->responseJson = curl_exec($this->oCurl);

        // curl_errno — Return the last error number
        $this->errorCode = curl_errno($this->oCurl);

        // PHP 8.5 deprecated, needs PHP 8.0
        // curl_close($this->oCurl);

        //--- Format response -------------------------------------------

        $this->oResponse = json_decode($this->responseJson); // object
        $this->responseArray = json_decode($this->responseJson, true); // object

        $this->responseJsonBeautified = json_encode($this->oResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n";

        //--- json error ? -----------------------------------

        $this->isJsonHasErrors = false;
        if (!empty($this->oResponse->errors)) {

            $this->isJsonHasErrors = true;
        }

        return $this->errorCode > 0 || $this->isJsonHasErrors;
    }

    public function responseJsonBeautified_CRLF_text(): string
    {
        return str_replace("\n", PHP_EOL, $this->responseJsonBeautified);
    }

    public function text(): string
    {
        $outTxt = "";
//
//        //$outTxt .= '§§§ -----------------------------' . PHP_EOL;
//        foreach ($this->error as $key => $value) {
//
//            $valueText = str_replace("\n","\n\r",$value);
//
//            $outTxt .= '"' . $key . '": "' . $valueText . '"' . PHP_EOL;
//        }
//
        return $outTxt;
    }

}


