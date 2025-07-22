<?php

namespace Finnern\apiByCurlHtml\src\curl_tasks;

use Exception;
//use Finnern\apiByCurlHtml\src\tasksLib\option;

/**
 * Base class prepares for filename list
 */
class baseCurlTask
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

}
