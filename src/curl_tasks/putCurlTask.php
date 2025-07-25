<?php

namespace Finnern\apiByCurlHtml\src\curl_tasks;

use Exception;
use Finnern\apiByCurlHtml\src\tasksLib\executeTasksInterface;
use Finnern\apiByCurlHtml\src\tasksLib\task;

use Finnern\apiByCurlHtml\src\tasksLib\option;

/**
 * put curl class
 */
class putCurlTask extends baseCurlTask
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
                $isOption = $this->assignLocalOption ($option);
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
        print("Execute putCurlTask: " . "\r\n");
        print('---------------------------------------------------------' . "\r\n");

        // ToDo: Error on missing token

        // data for gallery
//        $data = [
//            'parent_id' => '0',
//            'access' => '1',
//            'name' => 'By API',
//            'note'=> "",
//            'published' => '1',
//        ];
//
//        $dataString = json_encode($data);

        $dataString = $this->readDataFile();

        if ($this->oCurl) {

            $this->setRequest('POST');

            $this->setUrl();
            $this->setHeaders('Content-Length: ' . mb_strlen($dataString));
            $this->setStandardOptions();
            $this->setDataString($dataString);

            $response = curl_exec($this->oCurl);

            // curl_errno — Return the last error number
            $errorCode = curl_errno($this->oCurl);

            if ($errorCode == 0) {
                print('---------------------------------------------------------' . "\r\n");
                print(">>> curl_exec with response: " . "\r\n");
                // Attention response can be
                // "Es konnte keine Verbindung hergestellt werden, da der Zielcomputer die Verbindung verweigerte"
                // "{"errors":[{"title":"Resource not found","code":404}]}

                // ToDo: Format response
                $oResponse =  json_decode ($response);
                // $oResponse =  json_decode ($response->body);
                // $oResponse =  json_decode ($response->data);

                $responseJsonBeautified = json_encode($oResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                print( $responseJsonBeautified . "\n");
                print('---------------------------------------------------------' . "\r\n");
                print("\r\n");

                if ( ! empty($this->responseFile)) {
                    // ToDo: Response to file if requested
                    //file_put_contents("results\\projects.json", $responseJsonBeautified);
                    file_put_contents($this->responseFile, $responseJsonBeautified);
                }
            } else {
                print('---------------------------------------------------------' . "\r\n");
                // curl_error — Return a string containing the last error for the current session
                $errorMessage = curl_error($this->oCurl);

                print("\r\n");
                print("!!! curl_exec: has failed with error: '" . $errorCode ."' !!!" . "\r\n");
                print("Message: '" . $errorMessage ."'" . "\r\n");
                print('---------------------------------------------------------' . "\r\n");
                print("\r\n");
            }

            curl_close($this->oCurl);

        } else {

            print('---------------------------------------------------------' . "\r\n");
            print("putCurlTask:execute: oCurl is not defined" . "\r\n");
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
        $ident = "   ";

        $OutTxt = "------------------------------------------" . "\r\n";
        $OutTxt .= "--- putCurlTask --------" . "\r\n";

//        $OutTxt .= "Not defined yet " . "\r\n";

        $OutTxt .= parent::text();

        $OutTxt .=  $ident . "baseUrl: '" . $this->baseUrl ."'" . "\r\n";

        return $OutTxt;
    }

}
