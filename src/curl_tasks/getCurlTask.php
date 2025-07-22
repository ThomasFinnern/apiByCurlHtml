<?php

namespace Finnern\apiByCurlHtml\src\curl_tasks;

use Exception;
use Finnern\apiByCurlHtml\src\tasksLib\executeTasksInterface;
use Finnern\apiByCurlHtml\src\tasksLib\task;

//use Finnern\apiByCurlHtml\src\tasksLib\option;

/**
 * get curl class
 */
class getCurlTask extends baseCurlTask
    implements executeTasksInterface
{
    // task name
    public string $taskName = '????';

    public string $srcRoot = "";

    public bool $isNoRecursion = false;


    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct(string $srcRoot = "", bool $isNoRecursion = false)
    {
        try {
//            print('*********************************************************' . "\r\n");
//            print ("srcRoot: " . $srcRoot . "\r\n");
//            print ("yearText: " . $yearText . "\r\n");
//            print('---------------------------------------------------------' . "\r\n");

//            $this->srcRoot       = $srcRoot;
//            $this->isNoRecursion = $isNoRecursion;

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
        }
        // print('exit __construct: ' . $hasError . "\r\n");
    }

    // Task name with options
    public function assignBaseOption(option $option): bool
    {
        $isBaseOption = false;

        switch (strtolower($option->name)) {
            case strtolower('srcroot'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
//                $this->srcRoot = $option->value;
                $isBaseOption  = true;
                break;

//            case strtolower('isnorecursion'):
//                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
//                $this->isNoRecursion = boolval($option->value);
//                $isBaseOption        = true;
//                break;
//

        } // switch

        return $isBaseOption;
    }


    public function assignTask(\Finnern\apiByCurlHtml\src\tasksLib\task $task): int
    {
        // TODO: Implement assignTask() method.
        return 0;
    }

    public function execute(): int
    {
        // TODO: Implement execute() method.
        return 0;
    }

    public function executeFile(string $filePathName): int
    {
        // TODO: Implement executeFile() method.
        return 0;
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
