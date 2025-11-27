<?php

namespace Finnern\apiByCurlHtml\src\curl_tasks;

use Exception;
use Finnern\apiByCurlHtml\src\tasksLib\executeTasksInterface;
use Finnern\apiByCurlHtml\src\tasksLib\task;

use Finnern\apiByCurlHtml\src\tasksLib\option;

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
//            print('*********************************************************' . PHP_EOL);
//            print ("srcRoot: " . $srcRoot . PHP_EOL);
//            print ("yearText: " . $yearText . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

//            $this->srcRoot       = $srcRoot;
//            $this->isNoRecursion = $isNoRecursion;

            parent::__construct();

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . PHP_EOL;
        }
        // print('exit __construct: ' . $hasError . PHP_EOL);
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
//                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
//                $this->buildDir = $option->value;
//                $isBuildExtensionOption = true;
//                break;

            default:
                print ('!!! error: required option is not supported: ' . $option->name . ' !!!' . PHP_EOL);
        } // switch

        return $isBuildExtensionOption;
    }

    public function execute(): int
    {
        // ToDo: has error ....
        $hasError = 0;

        print('*********************************************************' . PHP_EOL);
        print("Execute getCurlTask: " . PHP_EOL);
        print('---------------------------------------------------------' . PHP_EOL);

        // ToDo: Error on missing token

        if ($this->oCurl) {

            $this->setRequest('GET');

            $this->setUrl();
            $this->setHeaders();
            $this->setStandardOptions();

            $response = curl_exec($this->oCurl);

            // curl_errno — Return the last error number
            $errorCode = curl_errno($this->oCurl);

            if ($errorCode == 0) {
                print('---------------------------------------------------------' . PHP_EOL);
                print(">>> curl_exec with response: " . PHP_EOL);
                // Attention response can be
                // "Es konnte keine Verbindung hergestellt werden, da der Zielcomputer die Verbindung verweigerte"
                // "{"errors":[{"title":"Resource not found","code":404}]}

                // ToDo: Format response
                $oResponse =  json_decode ($response);
                // $oResponse =  json_decode ($response->body);
                // $oResponse =  json_decode ($response->data);

                $responseJsonBeautified = json_encode($oResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                print( $responseJsonBeautified . "\n");
                print('---------------------------------------------------------' . PHP_EOL);
                print(PHP_EOL);

                if ( ! empty($this->responseFile)) {
                    // ToDo: Response to file if requested
                    //file_put_contents("results\\projects.json", $responseJsonBeautified);
                    file_put_contents($this->responseFile, $responseJsonBeautified);
                }
            } else {
                print('---------------------------------------------------------' . PHP_EOL);
                // curl_error — Return a string containing the last error for the current session
                $errorMessage = curl_error($this->oCurl);

                print(PHP_EOL);
                print("!!! curl_exec: has failed with error: '" . $errorCode ."' !!!" . PHP_EOL);
                print("Message: '" . $errorMessage ."'" . PHP_EOL);
                print('---------------------------------------------------------' . PHP_EOL);
                print(PHP_EOL);
            }

            // PHP 8.5 deprecated, needs PHP 8.0
            // curl_close($this->oCurl);

        } else {

            print('---------------------------------------------------------' . PHP_EOL);
            print("getCurlTask:execute: oCurl is not defined" . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

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
        $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- getCurlTask --------" . PHP_EOL;

//        $OutTxt .= "Not defined yet " . PHP_EOL;

        $OutTxt .= parent::text();

        return $OutTxt;
    }

}
