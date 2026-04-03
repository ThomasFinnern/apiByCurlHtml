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

    public curlErrResponse|null $oCurlErrResponse = null; // will be initialized with empty classo bject

    public bool $isHasError = false;
//    public bool $isHasCurlError = false;
//    public bool $isHasResponseError = false;


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
            [$this->response_json_text, $this->response_pre_text] = $this->extractResponseString($response);

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
    public function extractResponseString(string|null $response)
    {
        print("    * extractResponseString() " . PHP_EOL);

        $response_json     = '{}';
        $response_pre_text = ""; // warning

        try
        {

            if (!empty($response))
            {
                // Attention response can be
                // "Es konnte keine Verbindung hergestellt werden, da der Zielcomputer die Verbindung verweigerte"
                // "{"errors":[{"title":"Resource not found","code":404}]}
                // or
                // <br />
                // <b>Warning</b>:  array_flip(): Can only flip string and integer values, entry skipped in <b>E:\wamp64\www\joomla5x\libraries\src\Serializer\JoomlaSerializer.php</b> on line <b>85</b><br />

                //--- find first { ----------------------------

                // standard
                if (str_starts_with($response, '{'))
                {
                    $response_json = $response;
                }
                else
                {
                    $parts = explode("\n{", $response, 2);

                    $response_pre_text = $parts[0];
                    if (count($parts) > 1)
                    {
                        $response_json = '{' . $parts[1];
                    }
                }
            }
        }
        catch (\Exception $e)
        {
            echo '!!! Error: Exception in extractResponseString: ' . $e->getMessage() . PHP_EOL;
        }

        return [$response_json, $response_pre_text];
    }

    public function preResponseText(): string
    {
        $outText = "";

        $outText .= 'curl communication error' . PHP_EOL;
        $outText .= 'errCode' . $this->errCode . PHP_EOL;
        $outText .= 'errMessage' . $this->errMessage . PHP_EOL;

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
        $outText .= '"' . $this->response_pre_text . '"' . PHP_EOL;
        $outText .= '---------------------------------------------------------' . PHP_EOL;
        $outText .= PHP_EOL;

        return $outText;
    }

    public function createTestPretextFile(string $responseFile)
    {
        //------------------------------------------------------
        // create test response file to keep several errors (to be removed later)
        if (!empty($responseFileName))
        {
            $header = "";
            $header .= "--------------------------------------------------------" . PHP_EOL;
            $header .= "curl_response_pre_text: " . fileDateTime::stdFileDateTimeFormatString() . PHP_EOL;

            $outPreText = $this->response_pre_text . PHP_EOL;
            $outText    = json_encode($header . $outPreText, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            file_put_contents($responseFileName . '.pre.json', $outText);
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
    public function collectPretext2File()
    {
        $fileType = 'pretext';


        //------------------------------------------------------
        // create test response file to keep several errors (to be removed later)
        if ($this->isHasError)
        {
            $header = "";
            $header .= "--------------------------------------------------------" . PHP_EOL;
            $header .= "curl_response_pre_text: " . fileDateTime::stdFileDateTimeFormatString() . PHP_EOL;

            $outPreText = $this->response_pre_text . PHP_EOL;

            $fileName = __DIR__ . '/' . curlErrResponse::ERROR_COLLECTION_RELATIVE_PATH . curlErrResponse::ERROR_COLLECTION_NAME . '.' . $fileType . '.txt';
            $outText  = $header . $outPreText;

            file_put_contents($fileName, $outText, FILE_APPEND);
        }
    }


}