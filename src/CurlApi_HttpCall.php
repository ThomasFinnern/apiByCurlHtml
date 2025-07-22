<?php

namespace Finnern\apiByCurlHtml\src;

use Exception;
use Finnern\apiByCurlHtml\src\curl_tasks\baseCurlTask;
use Finnern\apiByCurlHtml\src\curl_tasks\getCurlTask;
use Finnern\apiByCurlHtml\src\tasksLib\baseExecuteTasks;
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

class CurlApi_HttpCall extends baseExecuteTasks
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
            parent::__construct($srcRoot, false);

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }
        // print('exit __construct: ' . $hasError . "\r\n");
    }

    public function assignTask(task $task): int
    {

        //put get ...
        switch (strtolower($task->name)) {
            case strtolower('get'):
                $this->curlTask = new getCurlTask();

                break;

            case strtolower('put'):
                //$this->buildModule();
                break;

            case strtolower('post'):
                //$this->buildPlugin();
                break;

            case strtolower('patch'):
                //$this->buildPlugin();
                break;

            case strtolower('delete'):
                //$this->buildPackage();
                break;

            default:
//                    print ('!!! Default componentType: ' . $componentType . ', No build done !!!');
                break;
        } // switch

        $this->curlTask->assignTask($task);


//        //--- http file variables options ----------------------------------
//
//        foreach ($task->options->options as $option) {
//
//            switch (strtolower($option->name)) {
//
//                case strtolower('builddir'):
//                    // add options from httpfile given in options
//                    $httpFilOptions = $this->httpFilOptions($option->value);
//
//                    break;
//
//
//            } // switch
//
//        }
//
//        //--- name and options ----------------------------------
//
//        $this->taskName = $task->name;
//
//        $options = $task->options;
//
//        foreach ($options->options as $option) {
//
//            $isBaseOption = $this->assignBaseOption($option);
//
//            // base options are already handled
//            if (!$isBaseOption) {
//
////                // $isVersionOption = $this->versionId->assignVersionOption($option);
////                // ToDo: include version better into manifest
////                // -> same increase flags should be ...
////                // if (!$isVersionOption) {
////                $isManifestOption = $this->manifestFile->assignManifestOption($option);
////                // }
////            }
////
//////            if (!$isBaseOption && !$isVersionOption && !$isManifestOption) {
//////            if (!$isBaseOption && !$isVersionOption) {
////            if (!$isBaseOption && !$isManifestOption) {
//
//                $this->assignOptions($option);
//                // $OutTxt .= $task->text() . "\r\n";
//            }
//        }

        $this->task = $task;

        return 0;
    }

    /**
     *
     * @param mixed $option
     * @param task $task
     *
     * @return void
     */
    public function assignOptions(mixed $option): bool
    {
        $isOption = false;

        switch (strtolower($option->name)) {
            case strtolower('testval'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
//                $this->testval = $option->value;
                $isOption = true;
                break;

            case strtolower('builddir'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
//                $this->buildDir = $option->value;
                $isOption = true;
                break;

            case strtolower('isDoNotUpdateCreationDate'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
//                $this->isDoNotUpdateCreationDate = boolval($option->value);
                $isOption = true;
                break;


            default:
                print ('!!! error: required option is not supported: ' . $option->name . ' !!!' . "\r\n");
        } // switch

        return $isOption;
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

        $this->curlTask->text();

        /**
         * $OutTxt .= "fileName: " . $this->fileName . "\r\n";
         * $OutTxt .= "fileExtension: " . $this->fileExtension . "\r\n";
         * $OutTxt .= "fileBaseName: " . $this->fileBaseName . "\r\n";
         * $OutTxt .= "filePath: " . $this->filePath . "\r\n";
         * $OutTxt .= "srcRootFileName: " . $this->srcRootFileName . "\r\n";
         * /**/

        return $OutTxt;
    }

}

