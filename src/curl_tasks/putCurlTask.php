<?php

namespace Finnern\apiByCurlHtml\src\curl_tasks;


use Finnern\apiByCurlHtml\src\tasksLib\executeTasksInterface;
use Finnern\apiByCurlHtml\src\tasksLib\option;

/**
 * put curl class
 */
class putCurlTask extends baseCurlTask implements executeTasksInterface
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
        try
        {
//            print('*********************************************************' . PHP_EOL);
//            print ("srcRoot: " . $srcRoot . PHP_EOL);
//            print ("yearText: " . $yearText . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

//            $this->srcRoot       = $srcRoot;
//            $this->isNoRecursion = $isNoRecursion;

            parent::__construct();

        }
        catch (\Exception $e)
        {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
        }
        // print('exit __construct: ' . $hasError . PHP_EOL);
    }


    public function assignTask(\Finnern\apiByCurlHtml\src\tasksLib\task $task): int
    {
        $this->taskName = $task->name;

        $options = $task->options;

        foreach ($options->options as $option)
        {

            $isBaseOption = $this->assignBaseOption($option);

            // base options are already handled
            if (!$isBaseOption)
            {
                $isOption = $this->assignLocalOption($option);
            }
        }

        return 0;
    }

    /**
     *
     * @param   option  $option
     *
     * @return void
     */
    private function assignLocalOption(option $option): bool
    {
        $isBuildExtensionOption = false;

        switch (strtolower($option->name))
        {
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
        print("Execute putCurlTask: " . PHP_EOL);
        print('---------------------------------------------------------' . PHP_EOL);

        // ToDo: Error on missing token

        $this->prepareDataFromFiles();
        $jsonPara = $this->convertParams2Json();

        if ($this->oCurl)
        {
            //--- prepare curl params --------------------------------

            // ToDO: put should match get with single request
            // How is it done in manual.joomla.org ..

            $this->setRequest('PUT');   // not supported by joomla ? so use post
//            $this->setRequest('POST');

            $this->setUrl();
            $this->setHeaders('Content-Length: ' . mb_strlen($jsonPara));
            $this->setStandardOptions();
            $this->setDataString($jsonPara);

            /*=============================================================
            Curl call
            =============================================================*/

            $response = curl_exec($this->oCurl);

            //--- handle result -------------------------------------------

            $this->handleJsonResult($response); // $this->oCurl

            print(PHP_EOL);
            print("!!! putCurlTask:execute: put is not defined in joomla! !!!" . PHP_EOL);
            print(PHP_EOL);
        }
        else
        {

            print('---------------------------------------------------------' . PHP_EOL);
            print("putCurlTask:execute: oCurl is not defined" . PHP_EOL);
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
        $ident = "   ";

        $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- putCurlTask --------" . PHP_EOL;

//        $OutTxt .= "Not defined yet " . PHP_EOL;

        $OutTxt .= parent::text();

        $OutTxt .= $ident . "baseUrl: '" . $this->baseUrl . "'" . PHP_EOL;

        return $OutTxt;
    }

}
