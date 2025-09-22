<?php

namespace Finnern\apiByCurlHtml\src;

use Exception;
use Finnern\apiByCurlHtml\src\curl_tasks\baseCurlTask;
use Finnern\apiByCurlHtml\src\curl_tasks\getCurlTask;
use Finnern\apiByCurlHtml\src\curl_tasks\putCurlTask;
use Finnern\apiByCurlHtml\src\curl_tasks\patchCurlTask;
use Finnern\apiByCurlHtml\src\curl_tasks\deleteCurlTask;
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

    public baseCurlTask $oCurlTask;

    public function __construct()
    {
        $hasError = 0;
        try {
            print('*********************************************************' . PHP_EOL);
            print ("Construct CurlApi_HttpCall: " . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

            // fallback
            $this->oCurlTask = new baseCurlTask();

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }
        // print('exit __construct: ' . $hasError . PHP_EOL);
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
                $this->oCurlTask = new getCurlTask();

                break;

            case strtolower('put'):
                $this->oCurlTask = new putCurlTask();
                break;

//            case strtolower('post'):
//                $this->curlTask = new putCurlTask();
//                //$this->buildPlugin();
//                break;

            case strtolower('patch'):
                $this->oCurlTask = new patchCurlTask();
                break;

            case strtolower('delete'):
                $this->oCurlTask = new deleteCurlTask();
                break;

            default:
//                    print ('!!! Default componentType: ' . $componentType . ', No build done !!!');
                $OutTxt = "!!! curltask class not defined: '" . $task->name . "'" . PHP_EOL;
                $OutTxt .= $this->task->text() . PHP_EOL;
                print ($OutTxt);
                return 1;

                // break;
        } // switch

        $this->oCurlTask->assignTask($task);

        $this->task = $task;

        return 0;
    }


    public function execute(): int
    {
        print('*********************************************************' . PHP_EOL);
        print ("Execute CurlApi_HttpCall: " . PHP_EOL);
        print('---------------------------------------------------------' . PHP_EOL);

        $this->oCurlTask->execute();

        return 0;
    }

    public function executeFile(string $filePathName): int
    {
        // not supported
        return 0;
    }

    // ToDo: use own class with task /  options as result
    private function httpFilOptions(string $fileName)
    {
        $options = [];
        try {
            print('*********************************************************' . PHP_EOL);
            print('httpFilOptions' . PHP_EOL);
            print ("FileName in: " . $fileName . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

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

                    echo 'Line: ' .  $line. PHP_EOL;
                }
            } else {
                echo 'httpFilOptions File does not exist: "' . $fileName . '"' . PHP_EOL;
            }
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        print('exit httpFilOptions: ' . $hasError . PHP_EOL);

        return $options;
    }

    public function text(): string
    {
        $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- CurlApi_HttpCall --------" . PHP_EOL;

//        $OutTxt .= "Not defined yet " . PHP_EOL;

        if (! empty ($this->oCurlTask)) {
            $OutTxt .= $this->oCurlTask->text();
        } else {
             $OutTxt .= "!!! no curltask class defined" . PHP_EOL;
             $OutTxt .= "task: '" . $this->task ."'" . PHP_EOL;
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

