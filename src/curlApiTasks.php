<?php

namespace Finnern\apiByCurlHtml\src;

use Finnern\apiByCurlHtml\src\curl_tasks\deleteCurlTask;
use Finnern\apiByCurlHtml\src\curl_tasks\getCurlTask;
use Finnern\apiByCurlHtml\src\curl_tasks\patchCurlTask;
use Finnern\apiByCurlHtml\src\curl_tasks\putCurlTask;
use Finnern\apiByCurlHtml\src\tasksLib\executeTasksInterface;

use Finnern\apiByCurlHtml\src\tasksLib\task;
use Finnern\apiByCurlHtml\src\tasksLib\tasks;
use Finnern\apiByCurlHtml\src\fileNamesLib\fileNamesList;

$HELP_MSG = <<<EOT
    >>>
    curlApiTask class

    ToDo: option commands , example

    <<<
    EOT;


/*================================================================================
Class curlApiTask
================================================================================*/

class curlApiTasks
{

    /**
     * @var tasks
     */
    public tasks $tasks;

    public executeTasksInterface $actTask;
    public string $actTaskName = 'no task defined';
    /**
     * @var fileNamesList
     */
    public fileNamesList $fileNamesList;

    //
    public string $basePath = "";


    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct($basePath = "", $tasksLine = "")
    {
        $hasError = 0;
        try {
//            print('*********************************************************' . PHP_EOL);
//            print("basePath: " . $basePath . PHP_EOL);
//            print("tasks: " . $tasksLine . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

            $this->basePath = $basePath;
            $this->tasks = new tasks();
            $this->fileNamesList = new fileNamesList();

            if (strlen($tasksLine) > 0) {
                $this->tasks = $this->tasks->extractTasksFromString($tasksLine);
            }
            // print ($this->tasksText ());
        } catch (\Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }
        // print('exit __construct: ' . $hasError . PHP_EOL);
    }

    /*--------------------------------------------------------------------
    applyTasks
    --------------------------------------------------------------------*/

    public function extractTasksFromString(mixed $tasksLine): void
    {
        $tasks = new tasks();
        $this->assignTasks($tasks->extractTasksFromString($tasksLine));
    }

    public function assignTasks(tasks $tasks): tasks
    {
        $this->tasks = $tasks;

        return $tasks;
    }

    public function execute(): int
    {
        $hasError = 0;

        try {
            print('*********************************************************' . PHP_EOL);
            print('applyTasks/create classes' . PHP_EOL);
            // print ("task: " . $textTask . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

            foreach ($this->tasks->tasks as $textTask) {
                // print ("--- apply task: " . $textTask->name . PHP_EOL);
                print (">>>---------------------------------" . PHP_EOL);

                $this->actTaskName = $textTask->name;

                //--- let the task run -------------------------

                switch (strtolower($textTask->name)) {

                    //=== real task definitions =================================

                    //--- curl standard tasks --------------------------------------------------

                    case strtolower('get'):
                        $this->actTask = $this->createTask(new getCurlTask(), $textTask);

                        // run task
                        $hasError = $this->actTask->execute();

                        break;

                    case strtolower('put'):
                        $this->actTask = $this->createTask(new putCurlTask(), $textTask);

                        // run task
                        $hasError = $this->actTask->execute();

                        break;

//                    case strtolower('post'):
//                        $this->curlTask = $this->createTask(new posCurlTask(), $textTask);
//                        // run task
//                        $hasError = $this->actTask->execute();
//                        break;

                    case strtolower('patch'):
                        $this->actTask = $this->createTask(new patchCurlTask(), $textTask);

                        // run task
                        $hasError = $this->actTask->execute();

                        break;

                    case strtolower('delete'):
                        $this->actTask = $this->createTask(new deleteCurlTask(), $textTask);

                        // run task
                        $hasError = $this->actTask->execute();

                        break;

                    case strtolower('???'):
//                        ToDo: $this->actTask = $this->createTask (new clean4release (), $textTask);
//                        $this->actTask = $this->createTask(new increaseVersionId (), $textTask);
//                        // run task
//                        $hasError = $this->actTask->execute();
                        break;

                    //--- xxx type tasks --------------------------------------------------

                    case strtolower('???xxx'):
//                        $this->actTask = $this->createTask(new exchangeAll_licenseLines (), $textTask);
//                        // run task
//                        $hasError = $this->actTask->execute();
                        break;

                    //--- yyyy type tasks --------------------------------------------------

                    case strtolower('???yyy'):
//                        $this->actTask = $this->createTask(new exchangeAll_licenseLines (), $textTask);
//                        // run task
//                        $hasError = $this->actTask->execute();
                        break;

                    //=== supporting tasks content  ===============================

                    case strtolower('execute'):
                        print ('>>> Call execute task: "'
                            // . $this->actTask->name
                            . '"  >>>' . PHP_EOL);

                        // ToDo: dummy task
//                        if (empty ($this->actTask)){
//                            $this->actTask = new executeTasksInterface ();
//                        }

                        // prepared filenames list
                        $this->actTask->assignFilesNames($this->fileNamesList);

                        // run task
                        $hasError = $this->actTask->execute();

//                        // stop after first task
//                        exit (99);

                        break;

                    //--- assign files to task -----------------------

                    case strtolower('fileNamesList'):
                    case strtolower('createFileNamesList'):
                        print ('Execute task: ' . $textTask->name . PHP_EOL);

                        $this->actTask = $this->createTask(new fileNamesList (), $textTask);
                        // run task
                        $hasError = $this->actTask->execute();

                        print ('createFilenamesList count: ' . count ($this->fileNamesList->fileNames) . PHP_EOL);

                        break;

                    //--- add more files to task -----------------------

                    case strtolower('add2filenameslist'):
                        print ('Execute task: ' . $textTask->name . PHP_EOL);
                        $filenamesList = new fileNamesList ();
                        $filenamesList->assignTask($textTask);
                        $filenamesList->execute();

                        if (empty($this->fileNamesList)) {
                            $this->fileNamesList = new fileNamesList ();
                        }

                        print ('add2FilenamesList count: ' . count ($filenamesList->fileNames) . PHP_EOL);

                        $this->fileNamesList->addFilenames($filenamesList->fileNames);
                        break;

                    case strtolower('clearfilenameslist'):
                        $this->fileNamesList = new fileNamesList();
                        break;

                    case strtolower('printfilenameslist'):
                        print ($this->fileNamesList->text_listFileNames());

                        // stop after print files to check the files
                        // exit (98);
                        break;

                    default:
                        print ('!!! Execute unknown task: "' . $textTask->name . '" !!!' . PHP_EOL);
                        throw new \Exception('!!! Execute unknown task: "' . $textTask->name . '" !!!');
                } // switch

                // $OutTxt .= $task->text() . PHP_EOL;
            }
        } catch (\Exception $e) {
            echo '!!! applyTasks: Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        print('exit applyTasks: ' . $hasError . PHP_EOL);

        return $hasError;
    }

    private function createTask(executeTasksInterface $execTask, task $textTask): executeTasksInterface
    {
        print ('Assign task: ' . $textTask->name . PHP_EOL);

        $execTask->assignTask($textTask);

        return $execTask;
    }

    public function tasksText(): string
    {
        // $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt = "";

        $OutTxt .= "--- curlApiTask: Tasks ---" . PHP_EOL;

        // $OutTxt .= "Tasks count: " . $this->textTasks->count() . PHP_EOL;

        $OutTxt .= $this->tasks->text() . PHP_EOL;

        return $OutTxt;
    }

    public function text(): string
    {
        $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- curlApiTask ==> " . $this->actTaskName . " ---" . PHP_EOL;

        if ( !empty ($this->actTask)) {
            $OutTxt .= $this->actTask->text();
        } else {
            $OutTxt .= ">>> text(): object actTask is not defined" . PHP_EOL;
        }
        /**
         * $OutTxt .= "fileName: " . $this->fileName . PHP_EOL;
         * $OutTxt .= "fileExtension: " . $this->fileExtension . PHP_EOL;
         * $OutTxt .= "fileBaseName: " . $this->fileBaseName . PHP_EOL;
         * $OutTxt .= "filePath: " . $this->filePath . PHP_EOL;
         * $OutTxt .= "srcPathFileName: " . $this->srcPathFileName . PHP_EOL;
         * /**/

        return $OutTxt;
    }

    public function extractTasksFromFile(mixed $taskFile) : curlApiTask
    {
        $tasks = new tasks();
        $this->assignTasks($tasks->extractTasksFromFile($taskFile));

        return $this;
    }

} // curlApiTask

