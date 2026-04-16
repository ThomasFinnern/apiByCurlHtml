<?php

namespace Finnern\apiByCurlHtml\src\curl_tasks;

// --- curl error response types --------------------------------------
//
// Attention response can be like following
// "A connection could not be established because the target computer refused the connection."
// "{"errors":[{"title":"Resource not found","code":404}]}
// or
// <br />
// <b>Warning</b>:  array_flip(): Can only flip string and integer values, entry skipped in <b>E:\wamp64\www\joomla5x\libraries\src\Serializer\JoomlaSerializer.php</b> on line <b>85</b><br />

//--- find first {  or "{" ?----------------------------

use Finnern\apiByCurlHtml\src\fileNamesLib\fileDateTime;

class curlResponse
{

    protected \curlHandle|false $oCurl;

    // Complete response part
    protected string|null $response = null;

    // json part of response as php object
    public array|\stdClass|null $oResponse = null;

    public string $response_json_text;
    public string $response_pre_text;
    public string $response_post_text;
    public string $response_unknown_text;

    public curlErrResponse|null $oCurlErrResponse = null; // will be initialized with empty classo bject

    public bool $isHasError = false;
//    public bool $isHasCurlError = false;
//    public bool $isHasResponseError = false;

    public $isValidJsonData = false;
    public $isHasOutsideData = false;


    public function __construct(\curlHandle|false $oCurl = false, string|null $response = null)
    {
        $this->oCurl    = $oCurl;
        $this->response = $response;

        $this->oCurlErrResponse = new curlErrResponse();

        if (!empty($oCurl) && !empty($response))
        {
            $this->assign($oCurl, $response);
        }

    }

    public function assign(\curlHandle|false $oCurl, string|null $response)
    {
        print("    * assign() " . PHP_EOL);

        $this->oCurl    = $oCurl;
        $this->response = $response;

        $this->oCurlErrResponse = new curlErrResponse();

        //--- check curl communication error -----------------------------

        // communication error
        $curlError = \curl_errno($this->oCurl);
        if (!empty($curl_errno))
        {
            $curlMessage = \curl_error($this->oCurl);

            $this->oCurlErrResponse->assignCurlError($curlError, $curlMessage);
            $this->isHasError = $this->oCurlErrResponse->isHasCurlError;

            print(">>> curl_exec : " . $this->oCurlErrResponse->errorCommunicationText() . PHP_EOL);
        }
        else
        {
            //--- extract data from response json ------------------------------------

            // creates $response_json, $response_pre_text;
            // [$this->response_json_text, $this->response_pre_text, $this->response_post_text] =
            $this->extractResponseString($response);

            // json part of response as php object
            $this->oResponse = json_decode($this->response_json_text, true);

            //--- check for error as json --------------------------------------------

            // The response may be an error in json format
            // Check, Assign response to error object. error flag is set and data is collected
            $this->oCurlErrResponse->assignResponseWithErrorObject($this->oResponse);

        }
    }


    // {
    //    "parent_id": 1,
    //    "access": 1,
    //    "name": "By API 03",
    //    "note": "",
    //    "published": 1
    //}

    /**
     * extracts parts of $response text
     * creates $response_json, $response_pre_text;
     *
     * @param   string|null  $response
     *
     * @return void
     */
    public function extractResponseString(string|null $response): array
    {
        print("    * extractResponseString() " . PHP_EOL);

        $response_json     = '{}';
        $response_pre_text = ""; // warning

        try
        {
            $this->response_json_text = $this->response_pre_text = $this->response_post_text = $this->response_unknown_text = '';

            if (!empty($response))
            {
                //============================================================================
                // Attention response can be following types:
                //============================================================================
                // -----
                // "Es konnte keine Verbindung hergestellt werden, da der Zielcomputer die Verbindung verweigerte"
                // "{"errors":[{"title":"Resource not found","code":404}]}
                // -----
                // or
                // -----
                // <br />
                // <b>Warning</b>:  array_flip(): Can only flip string and integer values, entry skipped in <b>E:\wamp64\www\joomla5x\libraries\src\Serializer\JoomlaSerializer.php</b> on line <b>85</b><br />
                // -----
                // or
                // -----
                // {"links":{"self":"http:\/\/127.0.0.1\/api_6x\/api\/index.php\/v1\/rsgallery2\/version"},"data":{"type":"version","id":"0","attributes":{"version":"5.1.2.17","creationDate":"2026.04.13"}}}<!-- Could not find template &quot;system&quot;. (500 Whoops, looks like something went wrong.) -->
                //<!DOCTYPE html>
                //<html lang="en">
                //    <head>
                //        <meta charset="UTF-8" />
                //        <meta name="robots" content="noindex,nofollow" />
                //        <meta name="viewport" content="width=device-width,initial-scale=1" />
                //        <title>Could not find template &quot;system&quot;. (500 Whoops, looks like something went wrong.)</title>
                //        <link rel="icon" type="image/png" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAgCAYAAAABtRhCAAADVUlEQVRIx82XX0jTURTHLYPyqZdefQx66CEo80+aYpoIkqzUikz6Z5klQoWUWYRIJYEUGpQ+lIr9U5dOTLdCtkmWZis3rbnC5fw/neYW002307mX/cZvP3/7o1PwwOdh95x7vnf39zvnd29AgBer2xO6DclAXiMqZAqxIiNIN/IYSUS2BPhjmGATchUxI+ADWiRhpWK7HKuHFVBFdmU5YvnI4grFGCaReF/EBH4KsZlGgj2JBTuCYBWRIYF8YoEOJ6wBt/gEs7mBbyOjQXruPLSdOgPCiEiPSUUHDoL8Ug5IUo9B/d5wrt+G7OAKNrODPuVdB6vRCIzN6SdBlpW9RIgk/1FeAXabzRlrUPVCS/JhbmwudztnGeeH9AyXBIwtmM3wLinZJZHifjHw2V+NBoRh+9ixQrbgbnaSIcl7cGea6hoXQbNe7za241oeO5Z0p42M4BV2EqP2D50wo+6HzvwC6C4sApNOR8cmOrtcnhtj2kYRyC9eBvXzKrBZrXSs72kFd1t3MoKVbMekQkEnSNKOO8fac3LpmK6l1TlGtsxmsdKFsecPYgwxst0cwROMYDXboSotg0WLBRqjY51jLYcENElXwW2XJKPydvoI2GN9T8rBtrAArYIUruBJXkFheCQYlCpQP6uk5dAQFQNaUROMSGVQFxLmkoQsxDJrhLbTZ+nvVsERME9MgPJRKV/58AsyomTSzE813WLFvWK++qI0xSfQl8k8Pg46sYRuv5t6dS+4RqxDwaa4BGjYH+NTQvKScIp9+YL/hoZh3jDtLRHtt2C3g6bmhX+CpsFBWg7ilDSPgj0lD2ncr5ev/BP8VvyAJhqVyZeUhPOrEhEFxgEtjft846Z/guQTNT89Q5P9flMLoth4F7808wKtWWKzAwNQHxrh/1vaid2F+XpYTSbQf1XA2McOmOpROnvpvMEA4tSjq1cW0sws2gCYxswY6TKkvzYnJq1NHZLnRU4BX+4U0uburvusu8Kv8iHY7qefkM4IFngJHEOUXmLEPgiGsI8YnlZILit3vSSLRTQe/MPIZva5pshNIEmyFQlCvruJKXPkCEfmePzkphXHdzZNQdoRI9KPlBAxlj/I8U97ERPS5bjGbWDFbEdqHVe5caTBeZZx2H/IMvzeN15yoQAAAABJRU5ErkJggg==" />
                //        <style>/* This file is based on WebProfilerBundle/Resources/views/Profiler/profiler.css.twig.
                //   If you make any change in this file, verify the same change is needed in the other file. */
                //:root {
                //    --font-sans-serif: Helvetica, Arial, sans-serif;
                //    --page-background: #f9f9f9;
                //    ...
                // -----
                // or
                // -----
                //

                $this->isValidJsonData  = false;
                $this->isHasOutsideData = false;

                //--- find first { ----------------------------

                // standard
                if (str_starts_with($response, '{'))
                {
                    $test_json             = json_decode($response, true);
                    $this->isValidJsonData = !empty ($test_json);

                    if ($this->isValidJsonData)
                    {
                        $response_json = $response;
                    }
                    else
                    {
                        // has json on start but unknown data behind

                        // try to extract first json part,
                        [$this->response_json_text, $this->response_post_text, $this->response_unknown_text] = $this->tryExtractJson($response);

                        $this->isHasOutsideData = true;

                        $test_json             = json_decode($this->response_json_text, true);
                        $this->isValidJsonData = !empty ($test_json);
                    }
                }
                else
                {
                    // has json after unknown data
                    $parts = explode("\n{", $response, 2);

                    $this->isHasOutsideData = true;

                    $this->response_pre_text = $parts[0];
                    if (count($parts) > 1)
                    {
                        $this->response_json_text = '{' . $parts[1];

                        $test_json             = json_decode($this->response_json_text, true);
                        $this->isValidJsonData = !empty ($test_json);
                    }
                }
            }
        }
        catch (\Exception $e)
        {
            echo '!!! Error: Exception in extractResponseString: ' . $e->getMessage() . PHP_EOL;
        }

        return [$this->response_json_text, $this->response_pre_text, $this->response_post_text, $this->response_unknown_text];
    }

    public function preResponseText(): string
    {
        $outText = "";


        return $outText;
    }

    public function createResponseFile(string $responseFileName, $responseJsonBeautified = false)
    {
        if (!empty($responseFileName))
        {
            // ToDo: header like in error with date
            $beautified = $this->beautifiedResponseJsonText($responseJsonBeautified);

            file_put_contents($responseFileName, $beautified);
        }

    }

    public function beautifiedResponseJsonText($responseJsonBeautified = false)
    {
        // raw
        $beautified = $this->response_json_text;

        if ($responseJsonBeautified)
        {
            $decoded = json_decode($beautified);

            if (!empty($decoded))
            {
                $beautified = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); //
            }
        }

        return $beautified;
    }

    public function prependWarningText()
    {
        $outText = '';

        $outText .= PHP_EOL;

        $outText .= '---------------------------------------------------------' . PHP_EOL;
        $outText .= "!!! >>> Prepend text found (warning/error) !!!" . PHP_EOL;
        $outText .= '---------------------------------------------------------' . PHP_EOL;
        $outText .= '"' . $this->response_pre_text . '"' . PHP_EOL;
        $outText .= '<<< Prepend End <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<' . PHP_EOL;
        $outText .= PHP_EOL;

        return $outText;
    }

    public function createTestPretextFile(string $responseFileName)
    {
        //------------------------------------------------------
        // create test response file to keep several errors (to be removed later)
        if (!empty($responseFileName))
        {
            $fileType = 'outside';

            $outResponseText = $this->outResponseText();

            // Write text to file
            $fileName = __DIR__ . '/' . curlErrResponse::ERROR_COLLECTION_RELATIVE_PATH . curlErrResponse::ERROR_COLLECTION_NAME . '.' . $fileType . '.txt';

            file_put_contents($responseFileName . $fileType . '.txt', $outResponseText);
        }
    }

    /**
     * ToDo: Collect in separate file when filename not given
     * ToDo: where is it/shall it be used
     *
     * @param   string  $responseFileName
     *
     * @return void
     */
    public function collectOutsideData2File()
    {
        //------------------------------------------------------
        // create test response file to keep several errors (to be removed later)
        // if ($this->isHasError)

        $fileType = 'outside';

        $outResponseText = $this->outResponseText();

        // Write text to file
        $fileName = __DIR__ . '/' . curlErrResponse::ERROR_COLLECTION_RELATIVE_PATH . curlErrResponse::ERROR_COLLECTION_NAME . '.' . $fileType . '.txt';

        file_put_contents($fileName, $outResponseText, FILE_APPEND);
    }

    private function tryExtractJson(string $response): array
    {

        $depth                 = 0;
        $response_json_text    = '';
        $response_post_text    = '';
        $response_unknown_text = '';

        try
        {
            print("    * tryExtractJson() " . PHP_EOL);

            $chars = preg_split("//u", $response, 0, PREG_SPLIT_NO_EMPTY);

            foreach ($chars as $idx => $char)
            {
                // next level
                if ($char == '{')
                {
                    $depth++;
                }

                // previous level
                if ($char == '}')
                {
                    $depth--;

                    // outside json ?
                    if ($depth == 0)
                    {
                        $response_json_text = substr($response, 0, $idx+1);
                        $response_post_text = substr($response, $idx+1);
                        break;
                    }
                }
            }

            // json not found
            if ($depth != 0)
            {
                $response_unknown_text = $response;
            }

        }
        catch (\Exception $e)
        {
            echo '!!! Error: Exception in tryExtractJson: ' . $e->getMessage() . PHP_EOL;
        }

        return [$response_json_text, $response_post_text, $response_unknown_text];
    }

    /**
     * @return string
     */
    public function outResponseText(): string
    {
        $outResponseText = '';

        $outResponseText .= "--------------------------------------------------------" . PHP_EOL;
        $outResponseText .= "Outside data found: " . fileDateTime::stdFileDateTimeFormatString() . PHP_EOL;

        if (!empty($this->response_pre_text))
        {
            $outResponseText .= "--------------------------------------------------------" . PHP_EOL;
            $outResponseText .= "Pre text found:" . PHP_EOL;
            $outResponseText .= $this->response_pre_text . PHP_EOL;
        }

        if (!empty($this->response_post_text))
        {
            $outResponseText .= "--------------------------------------------------------" . PHP_EOL;
            $outResponseText .= "Post text found:" . PHP_EOL;
            $outResponseText .= $this->response_post_text . PHP_EOL;
        }

        if (!empty($this->response_unknown_text))
        {
            $outResponseText .= "--------------------------------------------------------" . PHP_EOL;
            $outResponseText .= "Response unknown text found:" . PHP_EOL;
            $outResponseText .= $this->response_unknown_text . PHP_EOL;
        }

        $outResponseText .= "--------------------------------------------------------" . PHP_EOL;

        return $outResponseText;
    }


}