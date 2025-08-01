<?php

namespace Finnern\apiByCurlHtml\src\task_http_file;

use Exception;
use Finnern\apiByCurlHtml\src\tasksLib\baseExecuteTasks;
use Finnern\apiByCurlHtml\src\tasksLib\executeTasksInterface;
use Finnern\apiByCurlHtml\src\tasksLib\task;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use ZipArchive;

$HELP_MSG = <<<EOT
    >>>
    class apiByCurlHtml

    ToDo: option commands , example

    <<<
    EOT;


/*================================================================================
Class apiByCurlHtml
================================================================================*/

class tsk2httpFile extends baseExecuteTasks
    implements executeTasksInterface
{
    // internal
    private string $srcPath = '';
    private string $srcFile = '';

    private string $dstPath = '';
    private string $dstFile = '';

    public task $task;

//    private tskFileData $tskFileData;
//    private tskFileData $httpFileData;


    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    // ToDo: a lot of parameters ....

    public function __construct($srcRoot = "")
    {
        $hasError = 0;
        try {
//            print('*********************************************************' . "\r\n");
            print ("Construct apiByCurlHtml: " . "\r\n");
//            print('---------------------------------------------------------' . "\r\n");

//            parent::__construct($srcRoot, false);

//            $this->srcFile = $srcFile;
//            $this->dstFile = $dstFile;

            $this->task = new task();


        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }
        // print('exit __construct: ' . $hasError . "\r\n");
    }

    // Task name with options
    public function assignTask(task $task): int
    {
        $isBaseOption = false;

        $options = $task->options;

        foreach ($options->options as $option) {

//            $isBaseOption = $this->assignBaseOption($option);
//
////            if (!$isBaseOption && !$isVersionOption) {
//            if (!$isBaseOption && !$isManifestOption) {
//            if (!$isBaseOption) {

                $this->assignLocalOption($option);
                // $OutTxt .= $task->text() . "\r\n";
//            }
        }

        return 0;
    }

    /**
     *
     * @param mixed $option
     * @param task $task
     *
     * @return void
     */
    public function assignLocalOption(mixed $option): bool
    {
        $isLocalExtensionOption = false;

        switch (strtolower($option->name)) {
            case strtolower('srcPath'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                $this->srcPath = $option->value;
                $isLocalExtensionOption = true;
                break;

            // com_rsgallery2'
            case strtolower('srcFile'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                $this->srcFile = $option->value;
                $isLocalExtensionOption = true;
                break;


            case strtolower('dstPath'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                $this->dstPath = $option->value;
                $isLocalExtensionOption = true;
                break;

            // com_rsgallery2'
            case strtolower('dstFile'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                $this->dstFile = $option->value;
                $isLocalExtensionOption = true;
                break;

            default:
                print ('!!! error: required option is not supported: ' . $option->name . ' !!!' . "\r\n");
        } // switch

        return $isLocalExtensionOption;
    }

    public function execute(): int // $hasError
    {
        print('*********************************************************' . "\r\n");
        print("Execute apiByCurlHtml: " . "\r\n");
        print('---------------------------------------------------------' . "\r\n");


        switch (strtolower($this->task->name)) {
            case strtolower('tsk2httpFile'):
                $this->tsk2httpFile();

                break;

            case strtolower('http2tskFile'):
                $this->http2tskFile();

                break;

            default:
                print ('!!! Task name: "' . $this->task->name . '" not supported !!!');
        } // switch


        return 0;
    }

    public function tsk2httpFile(): int  // ToDo: string $tskFilePathName='', string $httpFilePathName=''
    {
        // file, path
        [$srcPath, $dstPath] = $this->createFilePaths ();

        //--- transform data ----------------------------------------

        print ("------------------------------------------" . "\r\n");
        print ("--- transform data -----------------------" . "\r\n");
        print ("------------------------------------------" . "\r\n");

        //--- read files data -----------------------

        print (">>read file data: " . "\r\n");

        $tskFileData = new tskFileData($srcPath); // calls extract data
        $httpFileData = new httpFileData($dstPath, true);

        //--- retrieve ---------------------------------

        print (">>get tskFileData: " . "\r\n");

        $command = strtoupper($tskFileData->taskName);
        $accept = $tskFileData->oBaseCurlTask->accept;
        $contentType = $tskFileData->oBaseCurlTask->contentType;
        $joomlaToken = $tskFileData->oBaseCurlTask->joomlaToken;

        $baseUrl = $tskFileData->oBaseCurlTask->baseUrl;
        $apiPath = $tskFileData->oBaseCurlTask->apiPath;

        //--- store ---------------------------------

        print (">>put httpFileData: " . "\r\n");

        $httpFileData->command = $command;
        $httpFileData->accept = $accept;
        $httpFileData->contentType = $contentType;
        $httpFileData->joomlaToken = $joomlaToken;

        $httpFileData->baseUrl = $baseUrl;
        $httpFileData->apiPath = $apiPath;

        //--- save data -----------------------

        print (">>save httpFileData: " . "\r\n");

        $httpFileData->createFileLines ();
        $httpFileData->writeFile ();

        print ("------------------------------------------" . "\r\n");
        print ("--- transform done -----------------------" . "\r\n");
        print ("------------------------------------------" . "\r\n");

        return 0;
    }


    public function http2tskFile(string $filePathName=''): int
    {
        //file, path
        [$srcPath, $dstPath] = $this->createFilePaths ();

        //--- transform data ----------------------------------------

        print ("------------------------------------------" . "\r\n");
        print ("--- transform data -----------------------" . "\r\n");
        print ("------------------------------------------" . "\r\n");

        //--- read files data -----------------------

        print ("read file data: " . "\r\n");

        $httpFileData = new httpFileData($srcPath);
        $tskFileData = new tskFileData($dstPath, true);

        //--- retrieve ---------------------------------

        print (">>get httpFileData: " . "\r\n");

        $command = $httpFileData->command;
        $accept = $httpFileData->accept;
        $contentType = $httpFileData->contentType;
        $joomlaToken= $httpFileData->joomlaToken;

        $baseUrl = $httpFileData->baseUrl;
        $apiPath = $httpFileData->apiPath;

        //--- store ---------------------------------

        print (">>put tskFileData: " . "\r\n");

        $tskFileData->taskName = strtolower($command);
        $tskFileData->oBaseCurlTask->accept = $accept;
        $tskFileData->oBaseCurlTask->contentType = $contentType;
        $tskFileData->oBaseCurlTask->joomlaToken = $joomlaToken;

        $tskFileData->oBaseCurlTask->baseUrl = $baseUrl;
        $tskFileData->oBaseCurlTask->apiPath = $apiPath;

        //--- save data -----------------------

        print (">>save tskFileData: " . "\r\n");

        $tskFileData->createFileLines ();
        $tskFileData->writeFile ();

        print ("------------------------------------------" . "\r\n");
        print ("--- transform done -----------------------" . "\r\n");
        print ("------------------------------------------" . "\r\n");

        return 0;
    }


    public function executeFile(string $filePathName): int
    {
        // not supported
        return 0;
    }

    public function createFilePaths() : array
    {
        $srcPath = '';
        $dstPath = '';

        // ToDo try catch ...
        // ToDo: create dst filename by source name if it does not exist

        // ToDo: create dst filename with time data stamp

        $srcPath = $this->srcPath . '/' . $this->srcFile;
        $dstPath = $this->dstPath . '/' . $this->dstFile;

        // not supported
        return [$srcPath, $dstPath];
    }



    public function text(): string
    {
        $OutTxt = "------------------------------------------" . "\r\n";
        $OutTxt .= "--- apiByCurlHtml --------" . "\r\n";

        $OutTxt .= "Not defined yet " . "\r\n";

        /**
         * $OutTxt .= "fileName: " . $this->fileName . "\r\n";
         * $OutTxt .= "fileExtension: " . $this->fileExtension . "\r\n";
         * $OutTxt .= "fileBaseName: " . $this->fileBaseName . "\r\n";
         * $OutTxt .= "filePath: " . $this->filePath . "\r\n";
         * $OutTxt .= "srcRootFileName: " . $this->srcRootFileName . "\r\n";
         * /**/

        return $OutTxt;
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

