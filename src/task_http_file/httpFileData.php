<?php

namespace Finnern\apiByCurlHtml\src\task_http_file;

use Finnern\apiByCurlHtml\src\curl_tasks\baseCurlTask;

class httpFileData extends baseCurlTask // baseHttpFileData // see baseCurlTask
{
    //--- http php debug file content --------------------
    // file: galleriesGet.http
    //-----------------------------------------------------
    // ###
    // GET  http://127.0.0.1/joomla5x/api/index.php/v1/rsgallery2/galleries
    // Accept: application/vnd.api+json
    // Content-Type: application/json
    // X-Joomla-Token: "c2hhMjU2OjI5MzphYTZhMTcwZTY2ODM1MTZhMmNiYzlkZDg0NjE5NzkxYTZkYThhNTJjODFhZTVkNWViYmZmMjljMmY2ZTQ4NGYz"
    //-----------------------------------------------------

    protected string $filePathName;

    public string $dataFile = '';

    public string $accept = "application/vnd.api+json";
    public string $contentType = "application/json";

    public string $joomlaToken = "";

    // ToDo: joomla token file

    public function __construct($fileName = "", $isTarget = false)
    {
        $hasError = 0;

        parent::__construct();

        try
        {
//            print('*********************************************************' . PHP_EOL);
            print ("Construct httpFileData: " . PHP_EOL);
            print ("fileName: " . $fileName . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

            $this->filePathName = $fileName;

            if (!empty ($fileName))
            {
                if (!$isTarget)
                {
                    if (file_exists($fileName))
                    {
                        $this->readFile(); // does extract data
                    } // ToDo: else error
                }
            }

        }
        catch (\Exception $e)
        {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

    }

    public function createFileLines(): array
    {
        //--- prepare json data  ------------------------------------

        $this->prepareDataFromFiles();

        //--- create lines ----------------------------------------------

        $lines = [];

        $lines[] = '###';
        $lines[] = strtoupper($this->taskName) . ' ' . $this->apiPath();
        $lines[] = 'Accept: ' . $this->accept;
        $lines[] = 'Content-Type: ' . $this->contentType;
        $lines[] = 'X-Joomla-Token: ' . $this->joomlaToken;

        if (!empty ($this->params))
        {
            $jsonPara = $this->convertParams2Json();
            $lines[]  = "\n" . $jsonPara;
        }

        if (empty($this->responseFile))
        {
            $this->responseFile = substr($this->filePathName, 0,-5) . '.json';
        }
        $lines[] = "\n" . '> ' . $this->responseFile;

        $this->lines = $lines;

        return $lines;
    }

    protected function extractFileData($lines = []): int
    {

//        $content = file_get_contents($httpFile);
//        $lines = explode("\n", $content);
//        print ("Lines:" . $content);
//

        //--- prepare standard  ------------------

        $cmd         = 'GET';
        $url         = 'http://127.0.0.1/joomla5x/api/index.php/v1/users';
        $accept      = 'application/vnd.api+json';
        $contentType = 'application/json';
        $token       = '';
        $dataFile    = '';

        print ('--- lines:' . PHP_EOL);

// ###
// GET  http://127.0.0.1/joomla5x/api/index.php/v1/lang4dev/projects
// Accept: application/vnd.api+json
// Content-Type: application/json
// X-Joomla-Token: "c2hhMjU..."
//  < ./input.txt

        $isStartFound = false;
        foreach ($lines as $idx => $line)
        {

            print ($line . PHP_EOL);

            $line = trim($line);

            if (str_starts_with($line, '###'))
            {
                $isStartFound = true;
            }

            // comment line
            if (str_starts_with($line, '#'))
            {
                continue;
            }

            // empty line
            $line = trim($line);
            if (empty($line))
            {
                continue;
            }

            if (!$isStartFound)
            {
                continue;
            }

            //--- put/patch datafile ------------------------------

            // comment line
            if (str_starts_with($line, '<'))
            {
                $dataFile       = trim(substr($line, strlen('<')));
                $this->dataFile = $dataFile;
                continue;
            }

            //--- command -------------------------------------

            $isCommand = false;
            // $lowerLine  = strtolower($line);

            $parts = explode(' ', $line, 2);

            $testCmd = strtolower(trim($parts[0]));
            if ($testCmd == 'get' || $testCmd == 'post' || $testCmd == 'put' || $testCmd == 'delete' || $testCmd == 'patch')
            {
                $isCommand = true;

                $this->taskName = trim($parts[0]);

                // url
                if (count($parts) > 1)
                {
                    $this->assignBaseAndApiPath(trim($parts[1]));
                }
                continue;
            }

            // 'name': 'value' definition ?
            if (str_contains($line, ':'))
            {

                $parts = explode(':', $line, 2);
                if (count($parts) > 1)
                {

                    $value = trim($parts[1]);

                    switch (strtolower($parts[0]))
                    {
                        case strtolower('Accept'):
                            $this->accept = $value;
                            break;
                        case strtolower('Content-Type'):
                            $this->contentType = $value;
                            break;
                        case strtolower('X-Joomla-Token'):
                            $this->joomlaToken = $value;
                            break;
                        default:
                            print ('!!! Error unknown line content: "' . $line . '" !!!' . PHP_EOL);
                            break;
                    }
                }

            }
        }

        print ('$cmd: "' . $this->taskName . '"' . PHP_EOL);
        print ('$url: "' . $this->apiPath() . '"' . PHP_EOL);
        print ('$accept: "' . $this->accept . '"' . PHP_EOL);
        print ('$contentType: "' . $this->contentType . '"' . PHP_EOL);
        print ('$token: "' . $this->joomlaToken . '"' . PHP_EOL . PHP_EOL);

        return 0;
    }

    private function assignBaseAndApiPath(string $url)
    {
        $this->baseUrl = '';
//        $this->apiPath = '';

        $search = 'api/index.php';
        $len    = strlen($search);

        $idx = strpos($url, $search);
        if ($idx !== false)
        {

            $this->baseUrl = substr($url, 0, $idx + $len);
//            $this->apiPath = substr($url, $idx + $len + 1);
        }

    }

    private function apiPath()
    {
        $apiPath = $this->baseUrl . '/' . $this->apiPath;

        return $apiPath;
    }

    protected function readFile(string $fileName = ''): int
    {
        if (empty($fileName))
        {
            $fileName = $this->filePathName;
        }

        if (!is_file($fileName))
        {

            print ('File not found: ' . $fileName . "\n");
            print ('File not found: ' . realpath($fileName) . "\n");

            return -789;
        }

        $content = file_get_contents($fileName); //Get the file
        $lines   = explode("\n", $content); //Split the file by each line

        $this->extractFileData($lines);

        // ToDo: try catch , return $hasError
        return 0;
    }


}


