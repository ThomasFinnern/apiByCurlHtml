<?php

namespace Finnern\apiByCurlHtml\src\task_http_file;

use Exception;
use Finnern\apiByCurlHtml\src\lib\AutoSrcDstPathFileNames;
use Finnern\apiByCurlHtml\src\tasksLib\baseExecuteTasks;
use Finnern\apiByCurlHtml\src\tasksLib\executeTasksInterface;
use Finnern\apiByCurlHtml\src\tasksLib\options;
use Finnern\apiByCurlHtml\src\tasksLib\task;
use Finnern\apiByCurlHtml\src\curl_tasks\baseCurlTask;

$HELP_MSG = <<<EOT
    >>>
    class apiByCurlHtml

    ToDo: option commands , example

    <<<
    EOT;


/*================================================================================
Class apiByCurlHtml
================================================================================*/

class tsk2httpFile extends baseExecuteTasks implements executeTasksInterface
{
    // internal
    //public task $task;
    public bool $hasError;
    private string $srcPath = '';
    private string $srcFile = '';
    private string $dstPath = '';
    private string $dstFile = '';
    private string $dstExtension = '';


    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    // ToDo: a lot of parameters ....

    public function __construct($srcRoot = "")
    {
        parent::__construct();

        $hasError = 0;
        try
        {
//            print('*********************************************************' . PHP_EOL);
            print ("Construct apiByCurlHtml: " . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

//            parent::__construct($srcRoot, false);

//            $this->srcFile = $srcFile;
//            $this->dstFile = $dstFile;

//            $this->task     = new task();
            $this->hasError = false;


        }
        catch (Exception $e)
        {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }
        // print('exit __construct: ' . $hasError . PHP_EOL);
    }

    /**
     * @param   options  $options
     * @param   task     $task
     *
     * @return bool
     */
    public function assignOptions(options $options, $taskName): int
    {
    // Task name with options
//    public function assignTask(task $task): int
 //   {

        foreach ($options->options as $option)
        {
            $isParentOption = parent::assignOption($option);
            if (!$isParentOption)
            {
                $this->assignLocalOption($option);
            }
        }

        return 0;
    }

    /**
     *
     * @param   mixed  $option
     * @param   task   $task
     *
     * @return void
     */
    public function assignLocalOption(mixed $option): bool
    {
        $isLocalExtensionOption = false;

        switch (strtolower($option->name))
        {
            // may contain complete path
            case strtolower('srcPath'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->srcPath          = $option->value;
                $isLocalExtensionOption = true;
                break;

            // com_rsgallery2'
            case strtolower('srcFile'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->srcFile          = $option->value;
                $isLocalExtensionOption = true;
                break;


            // may contain complete path
            case strtolower('dstPath'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->dstPath          = $option->value;
                $isLocalExtensionOption = true;
                break;

            // com_rsgallery2'
            case strtolower('dstFile'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->dstFile          = $option->value;
                $isLocalExtensionOption = true;
                break;

            // com_rsgallery2'
            case strtolower('dstExtension'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->dstExtension     = $option->value;
                $isLocalExtensionOption = true;
                break;

            case strtolower('isCreateAutoResponseFile'):
                // ignore but accept flag isCreateAutoResponseFile
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                // $this->dstExtension     = $option->value;
                $isLocalExtensionOption = true;
                break;

            default:
                print ('!!! error: required option is not supported: ' . $option->name . ' !!!' . PHP_EOL);
        } // switch

        return $isLocalExtensionOption;
    }

    public function execute(): int // $hasError
    {
        $hasError = false;

        print('*********************************************************' . PHP_EOL);
        print("Execute apiByCurlHtml: " . PHP_EOL);
        print('---------------------------------------------------------' . PHP_EOL);


        switch (strtolower($this->taskName))
        {
            case strtolower('tsk2httpFile'):
                $hasError = $this->tsk2httpFile();

                break;

            case strtolower('http2tskFile'):
                $hasError = $this->http2tskFile();

                break;

            default:
                print ('!!! Task name: "' . $this->taskName . '" not supported !!!' . PHP_EOL);
                $hasError = true;
        } // switch


        return $hasError ? 1 : 0;
    }

    public function tsk2httpFile(): bool  // ToDo: string $tskFilePathName='', string $httpFilePathName=''
    {
        // file, path
        [$srcPath, $dstPath] = $this->createFilePaths();

        if ($this->hasError == false)
        {

            //--- transform data ----------------------------------------

            print ("------------------------------------------" . PHP_EOL);
            print ("--- transform tsk data 2 http-------------" . PHP_EOL);
            print ("------------------------------------------" . PHP_EOL);

            //--- read files data -----------------------

            print (">>read file data: " . PHP_EOL);

            $tskFileData  = new tskFileData($srcPath); // calls extract data
            $httpFileData = new httpFileData($dstPath, true);

            //--- transfer data ---------------------------------

            print (">>transfer *.tsk data 2 http data: " . PHP_EOL);

            // $httpFileData->assignBaseCurlData((baseCurlTask) $tskFileData);

            $httpFileData->assignBaseCurlData($tskFileData);

            //--- local task data ---------------------------------

            print (">>local task data 2 http data: " . PHP_EOL);

//            $httpFileData->baseUrl = $baseUrl;
//            $httpFileData->apiPath = $apiPath;

            //--- create and save data -----------------------

            print (">>save httpFileData: " . PHP_EOL);

            $httpFileData->createFileLines();
            $httpFileData->writeFile();

            print ("------------------------------------------" . PHP_EOL);
            print ("--- transform done -----------------------" . PHP_EOL);
            print ("------------------------------------------" . PHP_EOL);

        }

        return $this->hasError;
    }

    public function createFilePaths(): array
    {
        $srcPath = '';
        $dstPath = '';

        $autoNames = new AutoSrcDstPathFileNames();
        $hasError  = $autoNames->assignFilePaths($this->srcFile, $this->srcPath, $this->dstFile, $this->dstPath, $this->dstExtension);

        print ($autoNames->text());

        if (!$hasError)
        {
            $srcPath = $autoNames->getSrcPathFileName();
            $dstPath = $autoNames->getDstPathFileName();

            // not supported
        }
        $this->hasError = $hasError;

        return [$srcPath, $dstPath];
    }

    public function text(): string
    {
        $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- apiByCurlHtml --------" . PHP_EOL;

        $OutTxt .= "Not defined yet " . PHP_EOL;

        /**
         * $OutTxt .= "fileName: " . $this->fileName . PHP_EOL;
         * $OutTxt .= "fileExtension: " . $this->fileExtension . PHP_EOL;
         * $OutTxt .= "fileBaseName: " . $this->fileBaseName . PHP_EOL;
         * $OutTxt .= "filePath: " . $this->filePath . PHP_EOL;
         * $OutTxt .= "srcRootFileName: " . $this->srcRootFileName . PHP_EOL;
         * /**/

        return $OutTxt;
    }

    public function http2tskFile(string $filePathName = ''): bool
    {
        //file, path
        [$srcPath, $dstPath] = $this->createFilePaths();

        if ($this->hasError == false)
        {

            //--- transform data ----------------------------------------

            print ("------------------------------------------" . PHP_EOL);
            print ("--- transform http data 2 tsk ------------" . PHP_EOL);
            print ("------------------------------------------" . PHP_EOL);

            //--- read files data -----------------------

            print ("read file data: " . PHP_EOL);

            $httpFileData = new httpFileData($srcPath);
            $tskFileData  = new tskFileData($dstPath, true);

            //--- transfer data ---------------------------------

            print (">>transfer *.tsk data 2 http data: " . PHP_EOL);

//            $tskFileData->assignBaseCurlData((baseCurlTask) $httpFileData);
            $tskFileData->assignBaseCurlData($httpFileData);

            //--- local task data ---------------------------------

            print (">>local task data 2 http data: " . PHP_EOL);

//            $httpFileData->baseUrl = $baseUrl;
//            $httpFileData->apiPath = $apiPath;

            //--- create and save data -----------------------

            print (">>save tskFileData: " . PHP_EOL);

            $tskFileData->createFileLines();
            $tskFileData->writeFile();

            print ("------------------------------------------" . PHP_EOL);
            print ("--- transform done -----------------------" . PHP_EOL);
            print ("------------------------------------------" . PHP_EOL);

        }

        return $this->hasError;
    }

    public function executeFile(string $filePathName): int
    {
        // not supported
        return 0;
    }

} // apiByCurlHtml


//function join_paths()
//{
//    $paths = [];
//
//    foreach (func_get_args() as $arg) {
//        if ($arg !== '') {
//            $paths[] = $arg;
//        }
//    }
//
//    return preg_replace('#/+#', '/', join('/', $paths));
//}

