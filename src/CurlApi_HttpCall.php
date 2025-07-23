<?php

namespace Finnern\apiByCurlHtml\src;

use Exception;
use Finnern\apiByCurlHtml\src\curl_tasks\baseCurlTask;
use Finnern\apiByCurlHtml\src\curl_tasks\getCurlTask;
use Finnern\apiByCurlHtml\src\curl_tasks\putCurlTask;
use Finnern\apiByCurlHtml\src\curl_tasks\patchCurlTask;
use Finnern\apiByCurlHtml\src\tasksLib\executeTasksInterface;
use Finnern\apiByCurlHtml\src\tasksLib\task;

$HELP_MSG = <<<EOT
    >>>
    class CurlApi_HttpCall

    ToDo: option commands , example

    <<<
    EOT;


/*================================================================================
Class CurlApi_HttpCall
================================================================================*/

class CurlApi_HttpCall
    implements executeTasksInterface
{
    protected task $task;

    protected baseCurlTask $curlTask;

    public function __construct($srcRoot = "")
    {
        $hasError = 0;
        try {
            print('*********************************************************' . "\r\n");
            print ("Construct CurlApi_HttpCall: " . "\r\n");
            print('---------------------------------------------------------' . "\r\n");

            // fallback
            $this->curlTask = new baseCurlTask();

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }
        // print('exit __construct: ' . $hasError . "\r\n");
    }

    public function assignTask(task $task): int
    {
        $this->task = $task;

//        //--- http file variables options ----------------------------------
//
//        foreach ($task->options->options as $option) {
//
//            if (strtolower($option->name) == strtolower('httpFile')) {
//
//                // $this->extractHttpFileData ($this->httpFile);
//                $this->extractHttpFileData ($option->value);
//
//                // or
//
//                // create class
//
//                // assign task
//
//
//                // assign options
//
//
//            } // switch
//
//        }
//

        //put get ...
        switch (strtolower($task->name)) {

            case strtolower('get'):
                $this->curlTask = new getCurlTask();

                break;

            case strtolower('put'):
                $this->curlTask = new putCurlTask();
                break;

//            case strtolower('post'):
//                $this->curlTask = new putCurlTask();
//                //$this->buildPlugin();
//                break;

            case strtolower('patch'):
                $this->curlTask = new patchCurlTask();
                break;

            case strtolower('delete'):
                //$this->buildPackage();
                break;

            default:
//                    print ('!!! Default componentType: ' . $componentType . ', No build done !!!');
                break;
        } // switch

        $this->curlTask->assignTask($task);


        $this->task = $task;

        return 0;
    }


    public function execute(): int
    {
        print('*********************************************************' . "\r\n");
        print ("Execute CurlApi_HttpCall: " . "\r\n");
        print('---------------------------------------------------------' . "\r\n");

        $this->curlTask->execute();

        return 0;
    }

    public function executeFile(string $filePathName): int
    {
        // not supported
        return (0);
    }

    // ToDo: use own class with task /  options as result
    private function httpFilOptions(string $fileName)
    {
        $options = [];
        try {
            print('*********************************************************' . "\r\n");
            print('httpFilOptions' . "\r\n");
            print ("FileName in: " . $fileName . "\r\n");
            print('---------------------------------------------------------' . "\r\n");

            //--- read XML -----------------------------------------------------------
            // file does  exist
            if (is_file($fileName)) {

                $lines = file($fileName);
                $outLines = [];
                $isExchanged = false;

// ###
//GET  http://127.0.0.1/joomla5x/api/index.php/v1/rsgallery2/galleries
//Accept: application/vnd.api+json
//Content-Type: application/json
//X-Joomla-Token: "c2hhMjU2OjI5MzphYTZhMTcwZTY2ODM1MTZhMmNiYzlkZDg0NjE5NzkxYTZkYThhNTJjODFhZTVkNWViYmZmMjljMmY2ZTQ4NGYz"
//

                foreach ($lines as $line) {

                    //
                    // split and assign

                    echo 'Line: ' .  $line. "\r\n";
                }
            } else {
                echo 'httpFilOptions File does not exist: "' . $fileName . '"' . "\r\n";
            }
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        print('exit httpFilOptions: ' . $hasError . "\r\n");

        return $options;
    }

    public function text(): string
    {
        $OutTxt = "------------------------------------------" . "\r\n";
        $OutTxt .= "--- CurlApi_HttpCall --------" . "\r\n";

//        $OutTxt .= "Not defined yet " . "\r\n";

        if (! empty ($this->curlTask)) {
            $OutTxt .= $this->curlTask->text();
        } else {
             $OutTxt .= "!!! no curltask class defined" . "\r\n";
             $OutTxt .= "task: '" . $this->task ."'" . "\r\n";
        }

        return $OutTxt;
    }

    // ToDo: check for file command in calling code, append to options
    // Content http file:
    // ###
    //GET  http://127.0.0.1/joomla5x/api/index.php/v1/lang4dev/projects
    //Accept: application/vnd.api+json
    //Content-Type: application/json
    //X-Joomla-Token: "c2hhMjU2Ojc3OTo3MDIxODdiNTE0N2NjMDY0ZjVlNGY3OTk5NmNiOWZhZTcxYWRkNWVmOWJjZDA0YjYxZTVjNWEwMmEwZTVkZmY5"

    private function extractHttpFileData(string $fileName) : void
    {
//        // ToDo: Extract file variables (? as class ? )
//
//        $httpData = new httpFileData($fileName);
//
//        $task        =  $httpData.command;
//        $accept      = $httpData.accept;
//        $contentType = $httpData.contentType;
//
//        $baseUrl = $httpData.baseUrl;
//        $apiPath = $httpData.apiPath;
//
//
//        $this->httpFile = $fileName;
//
//        // ToDo: Finish as contains command ...Get
//        $lines = file($fileName);
//
//        foreach ($lines as $line) {
//
//            //--- comments and trim -------------------------------------------
//
//            $line = trim($line);
//            if (empty($line)) {
//                continue;
//            }
//
//            // ignore comments
//            if (str_starts_with($line, '#')) {
//                continue;
//            }
//
//            // switch ($line)
//
//
//
//        }

        return;
    }

} // class

