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
//            print('*********************************************************' . "\r\n");
            print ("Construct httpFileData: " . "\r\n");
            print ("fileName: " . $fileName . "\r\n");
//            print('---------------------------------------------------------' . "\r\n");

            $this->FilePathName = $fileName;

            if (!empty ($fileName)) {
                if (!$isTarget) {
                    if (file_exists($fileName)) {
                        $this->readFile(); // does extract data
                    } // ToDo: else error
                }
            }

        } catch (\Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

    }

    protected function extractFileData($lines = []): int
    {
        // TODO: Implement extractFileData() method.

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
}


