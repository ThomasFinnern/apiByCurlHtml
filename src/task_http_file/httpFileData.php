<?php

namespace Finnern\apiByCurlHtml\src\task_http_file;


class httpFileData extends baseHttpFileData
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

    public string $baseUrl = '';
    public string $apiPath = "";

    public string $command = '';

    public string $accept = "application/vnd.api+json";
    public string $contentType = "application/json";

    public string $joomlaToken = "";

//    public string $command = '';
//    public string $command = '';
//    public string $command = '';

    public function __construct($fileName = "", $isTarget = false)
    {
        $hasError = 0;

        try {
//            print('*********************************************************' . PHP_EOL);
            print ("Construct httpFileData: " . PHP_EOL);
            print ("fileName: " . $fileName . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

            $this->filePathName = $fileName;

            if (!empty ($fileName)) {
                if (!$isTarget) {
                    if (file_exists($fileName)) {
                        $this->readFile(); // does extract data
                    } // ToDo: else error
                }
            }

        } catch (\Exception $e) {
            echo 'Message: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

    }

    protected function extractFileData($lines = []): int
    {

//        $content = file_get_contents($httpFile);
//        $lines = explode("\n", $content);
//        print ("Lines:" . $content);
//

        //--- prepare standard  ------------------

        $cmd = 'GET';
        $url = 'http://127.0.0.1/joomla5x/api/index.php/v1/users';
        $accept = 'application/vnd.api+json';
        $contentType = 'application/json';
        $token = '';

        print ('--- lines:' . PHP_EOL);

// ###
//GET  http://127.0.0.1/joomla5x/api/index.php/v1/lang4dev/projects
//Accept: application/vnd.api+json
//Content-Type: application/json
//X-Joomla-Token: "c2hhMjU..."

        $isStartFound = false;
        foreach ($lines as $idx => $line) {

            print ($line . PHP_EOL);

            if (str_starts_with($line, '###')) {
                $isStartFound = true;
            }

            // comment line
            if (str_starts_with($line, '#')) {
                continue;
            }

            // empty line
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            if (!$isStartFound) {
                continue;
            }

            //--- command -------------------------------------

            $isCommand = false;
            // $lowerLine  = strtolower($line);

            $parts = explode(' ', $line, 2);

            $testCmd = strtolower(trim($parts[0]));
            if ($testCmd == 'get'
                || $testCmd == 'post'
                || $testCmd == 'put'
                || $testCmd == 'delete'
                || $testCmd == 'patch'
            ) {
                $isCommand = true;

                $this->command = trim($parts[0]);

                // url
                if (count($parts) > 1) {
                    $this->assignBaseAndApiPath(trim($parts[1]));
                }
                continue;
            }

            // 'name': 'value' definition ?
            if (str_contains($line, ':')) {

                $parts = explode(':', $line, 2);
                if (count($parts) > 1) {

                    $value = trim($parts[1]);

                    switch (strtolower($parts[0])) {
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

        print ('$cmd: "' . $this->command . '"' . PHP_EOL);
        print ('$url: "' . $this->apiPath() . '"' . PHP_EOL);
        print ('$accept: "' . $this->accept . '"' . PHP_EOL);
        print ('$contentType: "' . $this->contentType . '"' . PHP_EOL);
        print ('$token: "' . $this->joomlaToken . '"' . PHP_EOL . PHP_EOL);

        return 0;
    }

    public function createFileLines(): array
    {
        $lines = [];

        $lines[] = '###';
        $lines[] = $this->command . ' ' . $this->apiPath();
        $lines[] = 'Accept: ' . $this->accept;
        $lines[] = 'Content-Type: ' . $this->contentType;
        $lines[] = 'X-Joomla-Token: ' . $this->joomlaToken;

        $this->lines = $lines;

        return $lines;
    }

    private function apiPath()
    {
        $apiPath = $this->baseUrl . '/' . $this->apiPath;

        return $apiPath;
    }

    private function assignBaseAndApiPath(string $url)
    {
        $this->baseUrl = '';
        $this->apiPath = '';

        $search = 'api/index.php';
        $len = strlen($search);

        $idx = strpos($url, $search);
        if ($idx !== false) {

            $this->baseUrl = substr($url, 0, $idx + $len);
            $this->apiPath = substr($url, $idx + $len + 1);
        }

    }
}


