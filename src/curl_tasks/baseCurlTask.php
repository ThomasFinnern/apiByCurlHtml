<?php

namespace Finnern\apiByCurlHtml\src\curl_tasks;

use Exception;
use Finnern\apiByCurlHtml\src\tasksLib\option;

//use Finnern\apiByCurlHtml\src\tasksLib\option;

/**
 * Base class prepares for filename list
 */
class baseCurlTask
{
    // task name
    protected string $baseUrl = '';

    protected string $apiPath = "";

    protected string $httpFile = "";

    protected string $joomlaToken = "";
    protected string $accept = "application/vnd.api+json";
    protected string $contentType = "application/json";
//    protected string $yyy = "";
//    protected string $yyy = "";
//    protected string $yyy = "";
//    protected string $yyy = "";
//    protected string $yyy = "";

    protected \curlHandle|false $oCurl;

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct()
    {
        try {

            $this->oCurl = curl_init();

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
        }
        // print('exit __construct: ' . $hasError . "\r\n");
    }

    // Task name with options
    public function assignBaseOption(option $option): bool
    {
        $isBaseOption = false;

        switch (strtolower($option->name)) {
            case strtolower('baseUrl'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                $this->baseUrl = $option->value;
                $isBaseOption = true;
                break;

            case strtolower('apiPath'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                $this->apiPath = $option->value;
                $isBaseOption = true;
                break;

            case strtolower('httpFile'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");

                // accept it as it is handled before
                $isBaseOption = true;
                break;

            // joomla token should not be in *.tsk file when using git published
            case strtolower('joomlaToken'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                $this->joomlaToken = $option->value;
                $isBaseOption = true;
                break;

            case strtolower('joomlaTokenFile'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                $this->joomlaToken = $this->readTokenFromFile ($option->value);
                $isBaseOption = true;
                break;

            case strtolower('accept'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                $this->accept = $option->value;
                $isBaseOption = true;
                break;

            case strtolower('contentType'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                $this->contentType = $option->value;
                $isBaseOption = true;
                break;

//            case strtolower(''):
//                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
//                $this-> = $option->value;
//                $isBaseOption  = true;
//                break;


            //--- examples string int bool -----------------------------------------------------

//            case strtolower('testval'):
//                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
////                $this->testval = $option->value;
//                $isOption = true;
//                break;
//
//            case strtolower('count'):
//                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
////                $this->count = (int) $option->value;
//                $isOption = true;
//                break;
//
//            case strtolower('isDoNotUpdateCreationDate'):
//                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
////                $this->isDoNotUpdateCreationDate = boolval($option->value);
//                $isOption = true;
//                break;

        } // switch

        return $isBaseOption;
    }


    public function setStandardOptions()
    {

        if ($this->oCurl) {

            $options =
                [
                    // CURLOPT_URL => sprintf('%s/content/articles/%d', $url, $articleId),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => 'utf-8',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2TLS,
                    // CURLOPT_CUSTOMREQUEST => 'DELETE',
                    // CURLOPT_HTTPHEADER => $headers,
                ];

            $isOk = curl_setopt_array($this->oCurl, $options);

            //--- print ----------------------------------------

            $OutTxt =  "setStandardOptions finished: '" . ($isOk ? 'true' : 'false') . "'" . "\r\n";

            foreach ($options as $name => $value) {
                $OutTxt .= "   " .  $name . ": '" . $value ."'" . "\r\n";
            }
            print ($OutTxt);

        }
    }

    public function setHeaders(string $name='', string $value='')
    {
        if ($this->oCurl) {

            // HTTP request headers
            $headers = [
                'Accept: application/vnd.api+json',
                'Content-Type: application/json',
                sprintf('X-Joomla-Token: %s', trim($this->joomlaToken)),
            ];

            if (!empty($name)) {
                $headers [$name] = $value;
            }

            $isOk = curl_setopt($this->oCurl, CURLOPT_HTTPHEADER, $headers);

            //--- print ----------------------------------------

            $OutTxt =  "setHeaders finished: '" . ($isOk ? 'true' : 'false') . "'" . "\r\n";
            // $OutTxt .=  "joomlaToken: '" . $this->joomlaToken ."'" . "\r\n";

            foreach ($headers as $name => $value) {
                $OutTxt .= "   " .  $name . ": '" . $value ."'" . "\r\n";
            }
            print ($OutTxt);
        }
    }

    public function setRequest(string $name='')
    {
        if ($this->oCurl) {

            $isOk = curl_setopt($this->oCurl, CURLOPT_CUSTOMREQUEST, $name);


            $OutTxt =  "setRequest finished: '" . ($isOk ? 'true' : 'false') . "'" . "\r\n";
            // ToDo: show variables
            // $OutTxt .=  $ident . "baseUrl: '" . $this->baseUrl ."'" . "\r\n";
            print ($OutTxt);
        }
    }

    public function setUrl(string $urlPath='')
    {
        if ($this->oCurl) {

            if (empty($urlPath)) {
                $urlPath = sprintf('%s/%s', trim($this->baseUrl), trim($this->apiPath));
            }

            $isOk = curl_setopt($this->oCurl, CURLOPT_URL, $urlPath);


            $OutTxt =  "setUrl finished: '" . ($isOk ? 'true' : 'false') . "'" . "\r\n";
            $OutTxt .=  "baseUrl: '" . $urlPath ."'" . "\r\n";
            print ($OutTxt);
        }
    }

    private function readTokenFromFile(string $fileName) : string
    {
        $token = "";

        $lines = file($fileName);

        foreach ($lines as $line) {

            //--- comments and trim -------------------------------------------

            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            // ignore comments
            if (str_starts_with($line, '//')) {
                continue;
            }

            // expected to be 100
            if (strlen($line) >= 100) {
                $token = $line;
            }
        }

        return $token;
    }

    public function text(): string
    {
        $ident = "   ";
        $OutTxt = $ident . "------------------------------------------" . "\r\n";
//        $OutTxt .= "--- baseCurlTask --------" . "\r\n";

        $OutTxt .=  $ident . "baseUrl: '" . $this->baseUrl ."'" . "\r\n";
        $OutTxt .=  $ident . "apiPath: '" . $this->apiPath ."'" . "\r\n";
        $OutTxt .=  $ident . "httpFile: '" . $this->httpFile ."'" . "\r\n";
        $OutTxt .=  $ident . "joomlaToken: '" . $this->joomlaToken ."'" . "\r\n";
        $OutTxt .=  $ident . "accept: '" . $this->accept ."'" . "\r\n";
        $OutTxt .=  $ident . "contentType: '" . $this->contentType ."'" . "\r\n";
        if (empty($this->oCurl)) {
            $OutTxt .= $ident . "curlHandle: NO" . "\r\n";
        } else {
            $OutTxt .= $ident . "curlHandle: YES" . "\r\n";
        }

//        $OutTxt .=  $ident . "task: '" . $this->task ."'" . "\r\n";
//        $OutTxt .=  $ident . "task: '" . $this->task ."'" . "\r\n";

        return $OutTxt;
    }


} // class
