<?php

namespace Finnern\apiByCurlHtml\src\task_http_file;

use Finnern\apiByCurlHtml\src\curl_tasks\baseCurlTask;
use Finnern\apiByCurlHtml\src\tasksLib\task;

class tskFileData extends baseCurlTask // baseHttpFileData // see baseCurlTask
{
    //--- http php debug file content --------------------
    // file: rsg2_getGalleries.tsk
    //-----------------------------------------------------
    // task:get
    // /baseUrl="http://127.0.0.1/joomla5x/api/index.php"
    // /apiPath="v1/rsgallery2/galleries"
    // /joomlaTokenFile="d:\Entwickl\2025\_gitHub\xTokenFiles\token_joomla5x.txt"
    // /responseFile="d:\Entwickl\2025\_gitHub\apiByCurlHtml\src\results/rsg2_getGalleries.json"
    //-----------------------------------------------------

    protected string $filePathName;

    // public string $taskName = '';

    public function __construct($fileName = "", $isTarget = false)
    {
        $hasError = 0;

        parent::__construct();

        try
        {
//            print('*********************************************************' . PHP_EOL);
            print ("Construct tskFileData: " . PHP_EOL);
            print ("fileName: " . $fileName . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

            $this->filePathName = $fileName;

            if (!$isTarget)
            {
                if (file_exists($fileName))
                {
                    // collect source task data

                    $task = new task();
                    $task->extractTaskFromFile($fileName); // does extract data
                    $this->assignTask($task);

                } // ToDo:
                else
                {
                    $OutTxt = "!!! tskFileData source file found: '" . $fileName . "'" . PHP_EOL;
                    print ($OutTxt);
                }
            }

        }
        catch (\Exception $e)
        {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

    }

    public function assignTask(task $task): int
    {
        $this->taskName = $task->name;

        $options = $task->options;

        foreach ($options->options as $option)
        {

            $isBaseOption = $this->assignBaseOption($option);

            // base options are already handled
            if (!$isBaseOption)
            {
                // $isOption = $this->assignLocalOption($option);
                $OutTxt = "!!! Error: tskFileData option not defined: '" . $option . "'" . PHP_EOL;
                print ($OutTxt);
            }
        }

        return 0;
    }

    public function createFileLines(): array
    {
        $lines = [];

//        // TODO: Implement createFileLines() method.
//
//        // task:get
//        $lines[] = 'task:' . $this->taskName;
//
//        // /baseUrl="http://127.0.0.1/joomla5x/api/index.php"
//        $lines[] = '/baseUrl="' . $this->baseUrl . '"';
//
//        // /apiPath="v1/rsgallery2/galleries/12"
//        $lines[] = '/apiPath="' . $this->apiPath . '"';
//
//        // /joomlaTokenFile="d:\Entwickl\2025\_gitHub\xTokenFiles\token_joomla5x.txt"
//        // find token file from token or given
//        $tokenFile = $this->getTokenFile();
//        if (!empty($tokenFile))
//        {
//            $lines[] = '/joomlaTokenFile="' . $tokenFile . '"';
//        }
//        else
//        {
//            $lines[] = '/token="' . $this->joomlaToken . '"';
//        }
//
//        // ToDo: use add "file function from lib to exchange the extension
//        // $this->responseFile = substr($this->filePathName, 0,-4) . '.json';
//
//        // /responseFile="d:\Entwickl\2025\_gitHub\apiByCurlHtml\src\results/rsg2_getGallery_12.json"
//        $lines[] = '/responseFile="' . $this->getResponseFileName($this->filePathName) . '"';
//        $lines[] = PHP_EOL;


        $this->lines = $lines;

        return $lines;
    }

}

