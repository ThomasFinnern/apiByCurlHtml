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
    public string $baseUrl = '';

    public string $apiPath = "";

    protected string $httpFile = "";

    public string $joomlaToken = "";
    public string $joomlaTokenFile = "";
    public string $accept = "application/vnd.api+json";
    public string $contentType = "application/json";
    protected string $responseFile = "";
    protected string $dataFile = "";

    protected string $page_offset = ""; // page[offset] => page%5Boffset%5D
    protected string $page_limit = ""; // page[limit] => page%5Blimit%5D

// ToDo:    protected array $urlParams = [];  // list of additional parameters multiple lines  of test01=1]

//    protected string $yyy = "";
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
            echo 'Message: ' . $e->getMessage() . PHP_EOL;
        }
        // print('exit __construct: ' . $hasError . PHP_EOL);
    }

    // Task name with options
    public function assignBaseOption(option $option): bool
    {
        $isBaseOption = false;

        switch (strtolower($option->name)) {
            case strtolower('baseUrl'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->baseUrl = $option->value;
                $isBaseOption = true;
                break;

            case strtolower('apiPath'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->apiPath = $option->value;
                $isBaseOption = true;
                break;

            case strtolower('httpFile'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);

                // accept it as it is handled before
                $isBaseOption = true;
                break;

            // joomla token should not be in *.tsk file when using git published
            case strtolower('joomlaToken'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->joomlaToken = $option->value;
                $isBaseOption = true;
                break;

            case strtolower('joomlaTokenFile'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->joomlaTokenFile = $option->value;
                $this->joomlaToken = $this->readTokenFromFile($option->value);
                $isBaseOption = true;
                break;

            case strtolower('accept'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->accept = $option->value;
                $isBaseOption = true;
                break;

            case strtolower('contentType'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->contentType = $option->value;
                $isBaseOption = true;
                break;

            case strtolower('responseFile'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->responseFile = $option->value;
                $isBaseOption = true;
                break;

            case strtolower('dataFile'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->dataFile = $option->value;
                $isBaseOption = true;
                break;

            case strtolower('page_offset'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->page_offset = $option->value;
                $isBaseOption = true;
                break;


            case strtolower('page_limit'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->page_limit = $option->value;
                $isBaseOption = true;
                break;


//            case strtolower(''):
//                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
//                $this-> = $option->value;
//                $isBaseOption  = true;
//                break;


            //--- examples string int bool -----------------------------------------------------

//            case strtolower('testval'):
//                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
////                $this->testval = $option->value;
//                $isOption = true;
//                break;
//
//            case strtolower('count'):
//                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
////                $this->count = (int) $option->value;
//                $isOption = true;
//                break;
//
//            case strtolower('isDoNotUpdateCreationDate'):
//                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
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

            $OutTxt = "setStandardOptions finished: '" . ($isOk ? 'true' : 'false') . "'" . PHP_EOL;

            foreach ($options as $name => $value) {
                $OutTxt .= "   " . $name . ": '" . $value . "'" . PHP_EOL;
            }
            print ($OutTxt);

        }
    }

    public function setHeaders(string $line = '')
    {
        if ($this->oCurl) {

            // HTTP request headers
            $headers = [
                'Accept: application/vnd.api+json',
                'Content-Type: application/json',
                sprintf('X-Joomla-Token: %s', trim($this->joomlaToken)),
            ];

            if (!empty($name)) {
                $headers [] = $line;
            }

            $isOk = curl_setopt($this->oCurl, CURLOPT_HTTPHEADER, $headers);

            //--- print ----------------------------------------

            $OutTxt = "setHeaders finished: '" . ($isOk ? 'true' : 'false') . "'" . PHP_EOL;
            // $OutTxt .=  "joomlaToken: '" . $this->joomlaToken . "'" . PHP_EOL;

            foreach ($headers as $name => $value) {
                $OutTxt .= "   " . $name . ": '" . $value . "'" . PHP_EOL;
            }
            print ($OutTxt);
        }
    }

    public function setRequest(string $name = '')
    {
        if ($this->oCurl) {

            $isOk = curl_setopt($this->oCurl, CURLOPT_CUSTOMREQUEST, $name);


            $OutTxt = "setRequest finished: '" . ($isOk ? 'true' : 'false') . "'" . PHP_EOL;
            // ToDo: show variables
            // $OutTxt .=  $ident . "baseUrl: '" . $this->baseUrl . "'" . PHP_EOL;
            print ($OutTxt);
        }
    }

    public function setDataString(string $dataString = '')
    {
        if ($this->oCurl) {

            $isOk = curl_setopt($this->oCurl, CURLOPT_POSTFIELDS, $dataString);


            $OutTxt = "setData finished: '" . ($isOk ? 'true' : 'false') . "'" . PHP_EOL;
            // ToDo: show variables
            // $OutTxt .=  $ident . "baseUrl: '" . $this->baseUrl . "'" . PHP_EOL;
            $OutTxt .= "   DataString: '" . $dataString . "'" . PHP_EOL;

            //  ToDo: count lines in DataString ('\n'?) : On only one beautify and print ...
            $OutTxt .= "   DataString: '" . $dataString . "'" . PHP_EOL;
            print ($OutTxt);
        }
    }

    public function setUrl(string $urlPath = '')
    {
        if ($this->oCurl) {

            if (empty($urlPath)) {
                $urlPath = sprintf('%s/%s', trim($this->baseUrl), trim($this->apiPath));
            }

            //--- additional parameters ----------------------------------------

            $urlParams = '';

            // page offset
            if (strlen($this->page_offset) > 0) {
                $urlParams .= '&page%5Boffset%5D=' . $this->page_offset;
            }

            // page limit
            if (strlen($this->page_limit) > 0) {
                $urlParams .= '&page%5Blimit%5D=' . $this->page_limit;
            }

            if (strlen($urlParams)) {

                // append but remove leading &
                $urlPath .= '?' . substr($urlParams, 1);
            }

            //=== Assign URL ===============================================

            $isOk = curl_setopt($this->oCurl, CURLOPT_URL, $urlPath);

            $OutTxt = "setUrl finished: '" . ($isOk ? 'true' : 'false') . "'" . PHP_EOL;
            $OutTxt .= "baseUrl: '" . $urlPath . "'" . PHP_EOL;
            print ($OutTxt);
        }
    }

    private function readTokenFromFile(string $fileName): string
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

        $OutTxt = $ident . "------------------------------------------" . PHP_EOL;
//        $OutTxt .= "--- baseCurlTask --------" . PHP_EOL;

        $OutTxt .= $ident . "baseUrl: '" . $this->baseUrl . "'" . PHP_EOL;
        $OutTxt .= $ident . "apiPath: '" . $this->apiPath . "'" . PHP_EOL;
        $OutTxt .= $ident . "httpFile: '" . $this->httpFile . "'" . PHP_EOL;
        $OutTxt .= $ident . "joomlaToken: '" . $this->joomlaToken . "'" . PHP_EOL;
        $OutTxt .= $ident . "accept: '" . $this->accept . "'" . PHP_EOL;
        $OutTxt .= $ident . "contentType: '" . $this->contentType . "'" . PHP_EOL;
        if (empty($this->oCurl)) {
            $OutTxt .= $ident . "curlHandle: NO" . PHP_EOL;
        } else {
            $OutTxt .= $ident . "curlHandle: YES" . PHP_EOL;
        }

//        $OutTxt .=  $ident . "task: '" . $this->task . "'" . PHP_EOL;
//        $OutTxt .=  $ident . "task: '" . $this->task . "'" . PHP_EOL;

        return $OutTxt;
    }

    // $path = 'data/movies-10.json';
    //$jsonString = file_get_contents($path);
    //$jsonData = json_decode($jsonString, true);
    //var_dump($jsonData);

    protected function readDataFile()
    {
        $jsonString = false;

        if (is_file($this->dataFile)) {
            $jsonStringIn = file_get_contents($this->dataFile);

            // replace PHP_EOL
            $jsonData = json_decode($jsonStringIn, true);
            $jsonString = json_encode($jsonData);

            $jsonStringPretty = json_encode($jsonData, JSON_PRETTY_PRINT);
        }

        return $jsonString;
    }

    public function getTokenFile(): string
    {
        $tokenFile = '';

        if (is_file($this->joomlaTokenFile)) {
            $tokenFile = $this->joomlaTokenFile;
        } else {
            // token exists
            if (!empty ($this->joomlaToken)) {
                // path given
                if (is_dir($this->joomlaTokenFile)) {

                    // search given path
                    $tokenFile = $this->findTokenFile($this->joomlaTokenFile, $this->joomlaToken);

                } else {
                    // search standard path
                    $tokenFile = $this->findTokenFile("d:/Entwickl/2025/_gitHub/xTokenFiles", $this->joomlaToken);
                }
            }
        }

        return $tokenFile;
    }

    private function findTokenFile(string $tokenPath, string $joomlaToken)
    {
        $joomlaTokenFile = "";

        // search in path for file with token

        $files = scandir($tokenPath);

        if (!empty($files)) {

            foreach ($files as $file) {
                $testFile = $tokenPath . "/" . $file;
                if (is_file($testFile)) {
                    if (strpos(file_get_contents($testFile), $joomlaToken) !== false) {
                        $joomlaTokenFile = $testFile;
                        break;
                    }
                }
            }
        }

        return $joomlaTokenFile;
    }

} // class
