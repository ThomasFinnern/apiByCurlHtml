<?php

/**
 * curlApi_HttpCall
 * Uses options from a task (*.tsk) file and calls joomla api by given path
 *
 * It supports commands "get, post, put, delete, patch" as separate tasks
 * each in its code file in .\curl_tasks folder
 *
 * https://manual.joomla.org/docs/general-concepts/webservices/
 * https://joomla.stackexchange.com/questions/32218/joomla-4-api-question/32296#32296
 *
 * ---------------------------------
 * Example task file
 * ---------------------------------
 * task:get
 * /baseUrl="http://127.0.0.1/joomla5x/api/index.php"
 * /apiPath="v1/config/application"
 * /joomlaTokenFile="d:\Entwickl\2025\_gitHub\xTokenFiles\token_joomla5x.txt"
 * /responseFile="d:\Entwickl\2025\_gitHub\JoomGallery_fith_dev\.apiTests/j!_getConfigAll.json"
 * ---------------------------------
 *
 */

namespace Finnern\apiByCurlHtml\src\curl_tasks;

use Exception;
use Finnern\apiByCurlHtml\src\tasksLib\baseExecuteTasks;
use Finnern\apiByCurlHtml\src\tasksLib\executeTasksInterface;
use Finnern\apiByCurlHtml\src\tasksLib\option;
use Finnern\apiByCurlHtml\src\tasksLib\task;

$HELP_MSG = <<<EOT
    >>>
    class curlApi_HttpCall

    ToDo: option commands , example

    <<<
    EOT;


/*================================================================================
Class curlApi_HttpCall
================================================================================*/

class curlApi_HttpCall extends baseExecuteTasks //
    implements executeTasksInterface
{
    public baseCurlTask $oCurlTask;
    protected task $task;

    public function __construct()
    {
        parent::__construct();

        try
        {
            print('*********************************************************' . PHP_EOL);
            print ("Construct curlApi_HttpCall: " . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

            // fallback
            $this->oCurlTask = new baseCurlTask();

        }
        catch (Exception $e)
        {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
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
        switch (strtolower($task->name))
        {

            case strtolower('get'):
                $this->oCurlTask = new getCurlTask();

                break;

            case strtolower('put'):
                $this->oCurlTask = new putCurlTask();
                break;

            case strtolower('post'):
                $this->oCurlTask = new postCurlTask();
                //$this->buildPlugin();
                break;

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

    /**
     * @param   option  $option
     *
     * @return bool true on option is consumed
     */
//    public function assignOption(option $option): bool
//    {
//        // $isOptionConsumed = $this->fileNamesList->assignOption($option);
//        $isOptionConsumed = $this->fileNamesList->assignOption($option);
//
//        if (!$isOptionConsumed) {
//
//            switch (strtolower($option->name)) {
////                case strtolower('srcroot'):
////                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
////                    $this->srcRoot = $option->value;
////                    $this->filenamesList->srcRoot = $this->srcRoot;
////
////                    $isOptionConsumed = true;
////                    break;
//
////                case strtolower('callerProjectId'):
////                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
////                    $this->callerProjectId = $option->value;
////                    $isOptionConsumed = true;
////                    break;
//
////                case strtolower('isnorecursion'):
////                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
////                    $this->isNoRecursion = boolval($option->value);
////                    $isOptionConsumed = true;
////                    break;
//
//            } // switch
//        }
//
//        return $isOptionConsumed;
//    }
    public function text(): string
    {
        $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- curlApi_HttpCall --------" . PHP_EOL;

//        $OutTxt .= "Not defined yet " . PHP_EOL;

        if (!empty ($this->oCurlTask))
        {
            $OutTxt .= $this->oCurlTask->text();
        }
        else
        {
            $OutTxt .= "!!! no curltask class defined" . PHP_EOL;
            $OutTxt .= "task: '" . $this->task . "'" . PHP_EOL;
        }

        return $OutTxt;
    }


    public function execute(): int
    {
        print('*********************************************************' . PHP_EOL);
        print ("Execute curlApi_HttpCall: " . PHP_EOL);
        print('---------------------------------------------------------' . PHP_EOL);

        $this->oCurlTask->execute();

        return 0;
    }

    // ToDo: use own class with task /  options as result

    public function executeFile(string $filePathName): int
    {
        // not supported
        return 0;
    }

    private function httpFileOptions(string $fileName)
    {
        $options = [];
        try
        {
            print('*********************************************************' . PHP_EOL);
            print('httpFileOptions' . PHP_EOL);
            print ("FileName in: " . $fileName . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

            //--- read XML -----------------------------------------------------------
            // file does  exist
            if (is_file($fileName))
            {

                $lines       = file($fileName);
                $outLines    = [];
                $isExchanged = false;

// ###
//GET  http://127.0.0.1/joomla5x/api/index.php/v1/rsgallery2/galleries
//Accept: application/vnd.api+json
//Content-Type: application/json
//X-Joomla-Token: "c2hhMjU2OjI5MzphYTZhMTcwZTY2ODM1MTZhMmNiYzlkZDg0NjE5NzkxYTZkYThhNTJjODFhZTVkNWViYmZmMjljMmY2ZTQ4NGYz"
//

                foreach ($lines as $line)
                {

                    //
                    // split and assign

                    echo 'Line: ' . $line . PHP_EOL;
                }
            }
            else
            {
                echo 'httpFileOptions File does not exist: "' . $fileName . '"' . PHP_EOL;
            }
        }
        catch (Exception $e)
        {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        print('exit httpFileOptions: ' . $hasError . PHP_EOL);

        return $options;
    }

    // ToDo: check for file command in calling code, append to options
    // Content http file:
    // ###
    //GET  http://127.0.0.1/joomla5x/api/index.php/v1/lang4dev/projects
    //Accept: application/vnd.api+json
    //Content-Type: application/json
    //X-Joomla-Token: "c2hhMjU2Ojc3OTo3MDIxODdiNTE0N2NjMDY0ZjVlNGY3OTk5NmNiOWZhZTcxYWRkNWVmOWJjZDA0YjYxZTVjNWEwMmEwZTVkZmY5"

    private function extractHttpFileData(string $fileName): void
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

