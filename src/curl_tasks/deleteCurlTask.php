<?php

namespace Finnern\apiByCurlHtml\src\curl_tasks;

use Exception;
use Finnern\apiByCurlHtml\src\curl_call\curl_call;
use Finnern\apiByCurlHtml\src\curl_call\curl_task_texts;
use Finnern\apiByCurlHtml\src\tasksLib\executeTasksInterface;
use Finnern\apiByCurlHtml\src\tasksLib\option;

/**
 * delete curl class
 */
class deleteCurlTask extends baseCurlTask
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

            parent::__construct();

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
        }
        // print('exit __construct: ' . $hasError . "\r\n");
    }


    public function assignTask(\Finnern\apiByCurlHtml\src\tasksLib\task $task): int
    {
        $this->taskName = $task->name;

        $options = $task->options;

        foreach ($options->options as $option) {

            $isBaseOption = $this->assignBaseOption($option);

            // base options are already handled
            if (!$isBaseOption) {
                $isOption = $this->assignLocalOption($option);
            }
        }

        return 0;
    }

    /**
     *
     * @param option $option
     *
     * @return void
     */
    private function assignLocalOption(option $option): bool
    {
        $isBuildExtensionOption = false;

        switch (strtolower($option->name)) {
//            case strtolower('builddir'):
//                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
//                $this->buildDir = $option->value;
//                $isBuildExtensionOption = true;
//                break;

            default:
                print ('!!! error: required option is not supported: ' . $option->name . ' !!!' . "\r\n");
        } // switch

        return $isBuildExtensionOption;
    }

    public function execute(): int
    {
        // ToDo: has error ....
        $hasError = 0;

        print('*********************************************************' . "\r\n");
        print("Execute deleteCurlTask: " . "\r\n");
        print('---------------------------------------------------------' . "\r\n");

        // ToDo: Error on missing token


        if ($this->oCurl) {

            $this->setRequest('DELETE');

            $this->setUrl();
            $this->setHeaders();
            $this->setStandardOptions();

            $curl_call = new curl_call();

            // prepare prints
            $curl_task_texts = new curl_task_texts($curl_call);

            // print start
            print ($curl_task_texts->headerText());

            //=============================================
            // call curl
            //============================================

            $isHasErrors = $curl_call->curl_exec($this->oCurl);

            // valid response
            //if ($curl_call->errorCode == 0 && ! $curl_call->isJsonHasErrors) {
            if (!$isHasErrors) {

                if (!empty($this->responseFile)) {
                    file_put_contents($this->responseFile, $curl_call->responseJsonBeautified);
                }

                print ($curl_task_texts->responseBeautifiedText());

            } else {

                //--- invalid response --------------------------------

                if (!empty($this->responseFile)) {
                    if ($curl_call->isJsonHasErrors) {
                        file_put_contents($this->responseFile, $curl_task_texts->responseCrLfText());
                    } else {
                        file_put_contents($this->responseFile, $curl_call->responseJsonBeautified);
                    }
                }

                // print error stack in newlines
                print ($curl_task_texts->responseCrLfText());

                if ($curl_call->isJsonHasErrors) {
                    print ($curl_task_texts->jsonErrorsCrLfText());
                } else {
                    print ($curl_task_texts->jsonErrorsCrLfText());
                }

            }

            // print end
            print ($curl_task_texts->footerText());

        } else {

            print('---------------------------------------------------------' . "\r\n");
            print("deleteCurlTask:execute: oCurl is not defined" . "\r\n");
            print('---------------------------------------------------------' . "\r\n");

        }

        return $hasError;
    }

    public function executeFile(string $filePathName): int
    {
        // TODO: Implement execute() method.
        return 0;
    }

    public function text(): string
    {
        $OutTxt = "------------------------------------------" . "\r\n";
        $OutTxt .= "--- deleteCurlTask --------" . "\r\n";

//        $OutTxt .= "Not defined yet " . "\r\n";

        $OutTxt .= parent::text();

        return $OutTxt;
    }

}
