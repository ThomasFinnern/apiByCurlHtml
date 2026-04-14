<?php

namespace Finnern\apiByCurlHtml\src\curl_tasks;

use AllowDynamicProperties;
use Exception;
use Finnern\apiByCurlHtml\src\lib\dirs;
use Finnern\apiByCurlHtml\src\tasksLib\baseExecuteTasks;
use Finnern\apiByCurlHtml\src\tasksLib\option;

//use Finnern\apiByCurlHtml\src\tasksLib\option;

enum eDataFileType: string
{
    case json = 'json';
    case base64 = 'base64';
}


/**
 * Base class prepares for filename list
 */
#[AllowDynamicProperties]
class baseCurlTask extends baseExecuteTasks
{
    // task name
    public string $baseUrl = '';

    public string $apiPath = "";
    public string $joomlaToken = "";
    public string $joomlaTokenFile = "";
    public string $accept = "application/vnd.api+json";
    public string $contentType = "application/json";
    public string $responseFile = "";

    //public \stdClass $params;
    public $params = [];
    public string $paramsFile = "";

    public string $dataFile = "";
    public eDataFileType $dataFileType = eDataFileType::json;
    protected string $httpFile = "";

    // ToDo: address as parameters instead (then update *.tsk afterwards)
    protected string $page_offset = ""; // page[offset] => page%5Boffset%5D
    protected string $page_limit = ""; // page[limit] => page%5Blimit%5D

    //protected bool $isExitOnErrorResult = false;
    protected bool $isExitOnErrorResult = true;
    protected bool $isLoadResponseFile = true;
    protected bool $isKeepResponseJson = true;


// ToDo:    protected array $urlParams = [];  // list of additional parameters multiple lines  of test01=1]

//    protected string $yyy = "";
//    protected string $yyy = "";
//    protected string $yyy = "";
//    protected string $yyy = "";
//    protected string $yyy = "";
//    protected string $yyy = "";

    protected \curlHandle|false $oCurl;

    //  file lines
    protected array $lines = [];

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct()
    {
        parent::__construct();

        try
        {

            $this->oCurl = curl_init();

            // $this->params = new \stdClass();

        }
        catch (Exception $e)
        {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
        }
        // print('exit __construct: ' . $hasError . PHP_EOL);
    }

    // Task name with options
    public function assignBaseOption(option $option): bool
    {
        $isBaseOption = false;

        switch (strtolower($option->name))
        {
            case strtolower('baseUrl'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->baseUrl = $option->value;
                $isBaseOption  = true;
                break;

            case strtolower('apiPath'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->apiPath = $option->value;
                $isBaseOption  = true;
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
                $isBaseOption      = true;
                break;

            case strtolower('joomlaTokenFile'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->joomlaTokenFile = $option->value;
                $this->joomlaToken     = $this->readTokenFromFile($option->value);
                $isBaseOption          = true;
                break;

            case strtolower('accept'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->accept = $option->value;
                $isBaseOption = true;
                break;

            case strtolower('contentType'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->contentType = $option->value;
                $isBaseOption      = true;
                break;

            case strtolower('responseFile'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);

                $this->responseFile = $option->value;
//                if ($this->responseFile == '') {
//                    $this->responseFile = $this->getResponseFileName($this->filePathName);
//                }
                $isBaseOption = true;
                break;

            case strtolower('param'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);

                $json_value = '{' . $option->value . '}';
                $paramJson  = json_decode($json_value);
                if (!empty($paramJson))
                {
                    foreach ($paramJson as $key => $value)
                    {
                        $this->params[$key] = $value;
                    }
                }
                else
                {
                    print('!!! error in baseCurlTast:assignBaseOption:param !!!' . PHP_EOL);
                    print('    json value could not be decoded "' . $json_value . '"' . PHP_EOL);
                    print('    ? Missing ".." around string ?' . PHP_EOL);
                    throw new \ErrorException("json value could not be decoded");
                }
                $isBaseOption = true;
                break;

            case strtolower('paramsFile'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->paramsFile = $option->value;
                $isBaseOption     = true;
                break;


            case strtolower('dataFile'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->dataFile = $option->value;
                $isBaseOption   = true;
                break;

            case strtolower('dataFileType'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
//                $this->dataFileType = $option->value;
                $this->dataFileType = eDataFileType::tryFrom(strtolower($option->value)) ?? eDataFileType::json;
                $isBaseOption       = true;
                break;

            case strtolower('page_offset'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->page_offset = $option->value;
                $isBaseOption      = true;
                break;


            case strtolower('page_limit'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->page_limit = $option->value;
                $isBaseOption     = true;
                break;

            case strtolower('isCreateAutoResponseFile'):
                // ignore but accept flag isCreateAutoResponseFile
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                // $this->dstExtension     = $option->value;
                $isBaseOption = true;
                break;

            case strtolower('isExitOnErrorResult'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->isExitOnErrorResult = boolval($option->value);
                $isOptionConsumed          = true;
                break;

            case strtolower('isLoadResponseFile'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->isLoadResponseFile = boolval($option->value);
                $isOptionConsumed         = true;
                break;

            case strtolower('isKeepResponseJson'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->isKeepResponseJson = boolval($option->value);
                $isOptionConsumed         = true;
                break;

            case strtolower('is'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->is         = boolval($option->value);
                $isOptionConsumed = true;
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

    private function readTokenFromFile(string $fileName): string
    {
        $token = "";

        $lines = file($fileName);

        foreach ($lines as $line)
        {

            //--- comments and trim -------------------------------------------

            $line = trim($line);
            if (empty($line))
            {
                continue;
            }

            // ignore comments
            if (str_starts_with($line, '//'))
            {
                continue;
            }

            // expected to be 100
            if (strlen($line) >= 100)
            {
                $token = $line;
            }
        }

        return $token;
    }

    public function setStandardOptions()
    {

        if ($this->oCurl)
        {

            $options = [// CURLOPT_URL => sprintf('%s/content/articles/%d', $url, $articleId),
                CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => 'utf-8', CURLOPT_MAXREDIRS => 10, //                CURLOPT_TIMEOUT => 30,
                CURLOPT_TIMEOUT        => 360,  // 3 minutes
                CURLOPT_FOLLOWLOCATION => true, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2TLS, // CURLOPT_CUSTOMREQUEST => 'DELETE',
                // CURLOPT_HTTPHEADER => $headers,
            ];

            $isOk = curl_setopt_array($this->oCurl, $options);

            //--- print ----------------------------------------

            $OutTxt = "setStandardOptions finished: '" . ($isOk ? 'true' : 'false') . "'" . PHP_EOL;

            foreach ($options as $name => $value)
            {
                $OutTxt .= "   " . $name . ": '" . $value . "'" . PHP_EOL;
            }
            print ($OutTxt);

        }
    }

    public function setHeaders(string $line = '')
    {
        if ($this->oCurl)
        {

            // HTTP request headers
            $headers = ['Accept: application/vnd.api+json', 'Content-Type: application/json', sprintf('X-Joomla-Token: %s', trim($this->joomlaToken)),];

            if (!empty($name))
            {
                $headers [] = $line;
            }

            $isOk = curl_setopt($this->oCurl, CURLOPT_HTTPHEADER, $headers);

            //--- print ----------------------------------------

            $OutTxt = "setHeaders finished: '" . ($isOk ? 'true' : 'false') . "'" . PHP_EOL;
            // $OutTxt .=  "joomlaToken: '" . $this->joomlaToken . "'" . PHP_EOL;

            foreach ($headers as $name => $value)
            {
                $OutTxt .= "   " . $name . ": '" . $value . "'" . PHP_EOL;
            }
            print ($OutTxt);
        }
    }

    public function setRequest(string $name = '')
    {
        if ($this->oCurl)
        {

            $isOk = curl_setopt($this->oCurl, CURLOPT_CUSTOMREQUEST, $name);


            $OutTxt = "setRequest finished: '" . ($isOk ? 'true' : 'false') . "'" . PHP_EOL;
            // ToDo: show variables
            // $OutTxt .=  $ident . "baseUrl: '" . $this->baseUrl . "'" . PHP_EOL;
            print ($OutTxt);
        }
    }

    public function setDataString(string $dataString = '')
    {
        if ($this->oCurl)
        {
            //--- json output -----------------------------------------

//            if ($this->dataFileType == eDataFileType::json)
//            {
            $isOk   = curl_setopt($this->oCurl, CURLOPT_POSTFIELDS, $dataString);
            $OutTxt = "setDataString finished: '" . ($isOk ? 'true' : 'false') . "'" . PHP_EOL;

            // ToDo: show variables
            // $OutTxt .=  $ident . "baseUrl: '" . $this->baseUrl . "'" . PHP_EOL;
            $OutTxt .= "   DataString: '" . substr($dataString, 0, 240) . "'";
            if (strlen($OutTxt) > 240)
            {
                $OutTxt .= " ... ";
            }
            $OutTxt .= PHP_EOL;

            //  ToDo: count lines in DataString ('\n'?) : On only one beautify and print ...
            // $OutTxt .= "   DataString: '" . $dataString . "'" . PHP_EOL;
            print ($OutTxt);
//            }
//            else
//            {
//                //--- base64 output -----------------------------------------
//
//                if ($this->dataFileType == eDataFileType::base64)
//                {
//                    $isOk = curl_setopt($this->oCurl, CURLOPT_POSTFIELDS, $dataString);
//
//                    $OutTxt = "setDataString finished: '" . ($isOk ? 'true' : 'false') . "'" . PHP_EOL;
//                    $OutTxt .= "   DataString: '" . substr($dataString, 0, 40) . "'" . PHP_EOL;
//                    print ($OutTxt);
//                }
//                else
//                {
//
//                    $OutTxt = "!!! Error: Wrong dataFiletype " . $this->dataFileType->value . PHP_EOL;
//                    print ($OutTxt);
//
//
//                }
//            }
        }
    }

    public function setUrl(string $urlPath = '')
    {
        if ($this->oCurl)
        {

            if (empty($urlPath))
            {
                $urlPath = sprintf('%s/%s', trim($this->baseUrl), trim($this->apiPath));
            }

            //--- additional parameters ----------------------------------------

            $urlParams = '';

            // page offset
            if (strlen($this->page_offset) > 0)
            {
                $urlParams .= '&page%5Boffset%5D=' . $this->page_offset;
            }

            // page limit
            if (strlen($this->page_limit) > 0)
            {
                $urlParams .= '&page%5Blimit%5D=' . $this->page_limit;
            }

            if (strlen($urlParams))
            {

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
        if (empty($this->oCurl))
        {
            $OutTxt .= $ident . "curlHandle: NO" . PHP_EOL;
        }
        else
        {
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

    /**
     * use given token file or find file from given token
     * @return string  '' if not found
     */
    public function getTokenFile(): string
    {
        $tokenFile = '';

        if (is_file($this->joomlaTokenFile))
        {
            $tokenFile = $this->joomlaTokenFile;
        }
        else
        {
            // token exists
            if (!empty ($this->joomlaToken))
            {
                // path given
                if (is_dir($this->joomlaTokenFile))
                {

                    // search given path
                    $tokenFile = $this->findTokenFile($this->joomlaTokenFile, $this->joomlaToken);

                }
                else
                {
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

        if (!empty($files))
        {

            foreach ($files as $file)
            {
                $testFile = $tokenPath . "/" . $file;
                if (is_file($testFile))
                {
                    if (strpos(file_get_contents($testFile), $joomlaToken) !== false)
                    {
                        $joomlaTokenFile = $testFile;
                        break;
                    }
                }
            }
        }

        return $joomlaTokenFile;
    }

    public function getResponseFileName(string $filePathName)
    {
        $responseFile = "";

        if (empty ($this->responseFile))
        {
            $responseFile = substr($filePathName, 0, -4) . '.json';
        }
        else
        {
            if (is_dir($this->responseFile))
            {
                $srcFileInfo = pathinfo($filePathName);
                $newName     = $srcFileInfo['filename'] . '.json';

                $responseFile = dirs::joinDirPath($this->responseFile, $newName);
            }
            else
            {
                $responseFile = $this->responseFile;
            }
        }

        return $responseFile;
    }

    public function assignBaseCurlData(baseCurlTask $srcData)
    {
        $this->taskName = $srcData->taskName;

        $this->baseUrl = $srcData->baseUrl;

        $this->apiPath         = $srcData->apiPath;
        $this->joomlaToken     = $srcData->joomlaToken;
        $this->joomlaTokenFile = $srcData->joomlaTokenFile;
        $this->accept          = $srcData->accept;
        $this->contentType     = $srcData->contentType;
        $this->responseFile    = $srcData->responseFile;

        $this->params     = $srcData->params;
        $this->paramsFile = $srcData->paramsFile;

        $this->dataFile     = $srcData->dataFile;
        $this->dataFileType = $srcData->dataFileType;
        $this->httpFile     = $srcData->httpFile;
        $this->page_offset  = $srcData->page_offset;
        $this->page_limit   = $srcData->page_limit;

        $this->oCurl = $srcData->oCurl;

    }

    // data for gallery
//        $data = [
//            'parent_id' => '0',
//            'access' => '1',
//            'name' => 'By API',
//            'note'=> "",
//            'published' => '1',
//        ];
//
//        $dataString = json_encode($data);

    public function writeFile(string $fileName = '', $lines = []): int
    {
        if (empty($fileName))
        {
            $fileName = $this->filePathName;
        }

        if (empty($lines))
        {
            $lines = $this->lines;
        }

        $content = implode(PHP_EOL, $lines);

        file_put_contents($fileName, $content);

        // ToDo: try catch , return $hasError
        return 0;
    }

    /**
     * Collect response in a class object ->
     * Collect errors and keep in usefu format -> curlErrResponse
     * Tell about results
     *
     * @param   string|null  $response
     *
     * @return void
     */
    public function handleJsonResult(string|null $response)
    {
        print('---------------------------------------------------------' . PHP_EOL);
        print(">>> curl_exec handle response: " . PHP_EOL);


        print("    create response object" . PHP_EOL);

        // ToDo: ? global this class variable ?
        $oCurlResponse = new curlResponse($this->oCurl, $response);

        print('---------------------------------------------------------' . PHP_EOL);
        print(">>> curl response results: " . PHP_EOL);


        //'--- tell json response found ------------------------------------------------------'

        print('---------------------------------------------------------' . PHP_EOL);

        $isHasError = $oCurlResponse->oCurlErrResponse->isHasError;

        if (!$isHasError)
        {
            print(">>> valid response data " . PHP_EOL);
        }
        else
        {
            print(">>> !!! has error response data !!! " . PHP_EOL);
        }

        //--- create user response file ----------------------------------------------------

        // make pretty on no error
        $responseJsonBeautified = true;
//        if (!$oCurlResponse->isHasError)
//        {
//            $responseJsonBeautified = false;
//        }

        // write to file
        $oCurlResponse->createResponseFile($this->responseFile, $responseJsonBeautified);

        if (!$oCurlResponse->oCurlErrResponse->isHasError)
        {
            // pretty print to screen
            print ($oCurlResponse->beautifiedResponseJsonText(true) . PHP_EOL);

        }

        //--- tell surprising pre text -------------------------------------------

        if (!empty($oCurlResponse->response_pre_text))
        {

            print('---------------------------------------------------------' . PHP_EOL);
            // print("!!! >>> Prepend text found (warning/error) !!!" . PHP_EOL);

            // collect special cases in one file
            $oCurlResponse->collectPretext2File();

            // pretty print to screen
            print(">>> curl_exec : " . $oCurlResponse->prependWarningText() . PHP_EOL);

            // create file parallel to user response file
            if (!empty($this->responseFile))
            {
                $oCurlResponse->createTestPretextFile($this->responseFile);
            }

            //===================================================================================
            // ToDo: After some time allowing for collection remove following
            print("" . PHP_EOL);
            print("°°°" . PHP_EOL);
            print("°°°" . PHP_EOL);

            print("°°° ToDo: Use Prepend text example for demo file                  °°°" . PHP_EOL);

            print("°°°" . PHP_EOL);
            print("°°°" . PHP_EOL);
            print("" . PHP_EOL);

        }

        //'--- tell error found ------------------------------------------------------'

        if ($oCurlResponse->oCurlErrResponse->isHasError)
        {
            print('---------------------------------------------------------' . PHP_EOL);
            print("!!! >>> Error text found (warning/error) !!!" . PHP_EOL);

            // collect special cases in one file
            $oCurlResponse->oCurlErrResponse->collectError2File();

            // pretty print to screen
            print ($oCurlResponse->oCurlErrResponse->allErrorsJsonText(true) . PHP_EOL);

            // create user response file with error data
            if (!empty($this->responseFile))
            {
                $oCurlResponse->oCurlErrResponse->createTestErrorFile($this->responseFile);
            }

            //===================================================================================
            // ToDo: After some time allowing for collection remove following
            print("" . PHP_EOL);
            print("°°°" . PHP_EOL);
            print("°°°" . PHP_EOL);

            print("°°° ToDo: Use error text example for demo file                  °°°" . PHP_EOL);

            print("°°°" . PHP_EOL);
            print("°°°" . PHP_EOL);
            print("" . PHP_EOL);

        }

        print('--- Done ------------------------------------------------------' . PHP_EOL);
//        print('---  ------------------------------------------------------' . PHP_EOL);

        // PHP 8.5 deprecated, needs PHP 8.0
        // curl_close($this->oCurl);
    }

    // ToDo:separate class


    protected function readDataFile(): string
    {
        $fileData = "";

        if (is_file($this->dataFile))
        {
            $OutTxt = "   dataFile: '" . $this->dataFile . "'" . PHP_EOL;
            print ($OutTxt);


            if ($this->dataFileType == eDataFileType::json)
            {
                $jsonStringIn = file_get_contents($this->dataFile);

                // replace PHP_EOL
                $jsonData   = json_decode($jsonStringIn, true);
                $jsonString = json_encode($jsonData);

                $jsonStringPretty = json_encode($jsonData, JSON_PRETTY_PRINT);

                $fileData = $jsonStringPretty;
                // $fileData = $jsonString;
            }
            else
            {

                if ($this->dataFileType == eDataFileType::base64)
                {
                    // ToDo: use pathinfo ?
                    // $path = 'myfolder/myimage.png';
                    // $type = pathinfo($path, PATHINFO_EXTENSION);
                    // $data = file_get_contents($path);
                    // $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

                    $mimeType = mime_content_type($this->dataFile);
                    $OutTxt   = "   mimeType: '" . $mimeType . "'" . PHP_EOL;
                    print ($OutTxt);

                    $fileDataIn = file_get_contents($this->dataFile);

                    $base64Data = 'data:image/' . $mimeType . ';base64,' . base64_encode($fileDataIn);

                    $fileData = $base64Data;
                }
            }
        }

        return $fileData;
    }

    protected function prepareDataFromFiles(): void
    {
        try
        {
            //--- prepare data from files ------------------------------------

            // X-token from file
            if (!empty ($this->joomlaTokenFile) && empty($this->joomlaToken))
            {
                // $this->getTokenFile();
                $this->joomlaToken = $this->readTokenFromFile($this->joomlaTokenFile);
            }

            // params from file
            if (!empty($this->paramsFile))
            {
                $this->assignParamsFromFile();
            }

            if (!empty($this->dataFile))
            {
                $this->assignContentFromFile();
            }

        }
        catch (Exception $e)
        {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
        }

    }

    /**
     * @return void
     */
    public function assignParamsFromFile(): void
    {
        try
        {
            if (is_file($this->paramsFile))
            {
                $OutTxt = "   paramsFile: '" . $this->paramsFile . "'" . PHP_EOL;
                print ($OutTxt);

                $jsonStringIn = file_get_contents($this->paramsFile);

                // replace PHP_EOL
                $jsonData = json_decode($jsonStringIn, true);

                foreach ($jsonData as $key => $value)
                {
                    $this->params[$key] = $value;
                }
            }
            else
            {
                echo '!!! Error: assignParamsFromFile file does not exist: "' . $this->paramsFile . '"' . PHP_EOL;
            }
        }
        catch (Exception $e)
        {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
        }

    }

    /**
     *
     *
     *
     * @return void
     */
    public function assignContentFromFile(): void
    {

        try
        {
            if (is_file($this->dataFile))
            {
                $fileData   = file_get_contents($this->dataFile);
                $base64Data = base64_encode($fileData);

                $this->params['content'] = $base64Data;
            }
            else
            {
                echo '!!! Error: assignContentFromFile file does not exist: "' . $this->dataFile . '"' . PHP_EOL;
            }
        }
        catch (Exception $e)
        {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
        }

    }

    protected function convertParams2Json(): string
    {
        $dataString = '';

        try
        {
            //--- prepare json data  ------------------------------------

            $dataString = json_encode($this->params, JSON_PRETTY_PRINT);
            //$dataString = json_encode($this->params);
        }
        catch (Exception $e)
        {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
        }

        return $dataString;
    }


} // class
