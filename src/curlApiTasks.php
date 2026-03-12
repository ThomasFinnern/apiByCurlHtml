<?php

namespace Finnern\apiByCurlHtml\src;

use Finnern\apiByCurlHtml\src\curl_tasks\deleteCurlTask;
use Finnern\apiByCurlHtml\src\curl_tasks\getCurlTask;
use Finnern\apiByCurlHtml\src\curl_tasks\patchCurlTask;
use Finnern\apiByCurlHtml\src\curl_tasks\postCurlTask;
use Finnern\apiByCurlHtml\src\curl_tasks\putCurlTask;
use Finnern\apiByCurlHtml\src\fileNamesLib\fileNamesList;
use Finnern\apiByCurlHtml\src\tasksLib\executeTasksInterface;
use Finnern\apiByCurlHtml\src\tasksLib\task;
use Finnern\apiByCurlHtml\src\tasksLib\tasks;

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
        try
        {
//            print('*********************************************************' . PHP_EOL);
//            print("basePath: " . $basePath . PHP_EOL);
//            print("tasks: " . $tasksLine . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

            $this->basePath      = $basePath;
            $this->tasks         = new tasks();
            $this->fileNamesList = new fileNamesList();

            if (strlen($tasksLine) > 0)
            {
                $this->tasks = $this->tasks->extractTasksFromString($tasksLine);
            }
            // print ($this->tasksText ());
        }
        catch (\Exception $e)
        {
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

        try
        {
            print('*********************************************************' . PHP_EOL);
            print('applyTasks/create classes' . PHP_EOL);
            // print ("task: " . $actTask . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

            foreach ($this->tasks->tasks as $actTask)
            {
                // print ("--- apply task: " . $actTask->name . PHP_EOL);
                print (">>>---------------------------------" . PHP_EOL);

                $this->actTaskName = $actTask->name;


                $taskName = strtolower($actTask->name);

                // Check and execute filetask or standard 'task' related calls
                [$isHandled, $hasError] = $this->handleFileTasks($actTask);

                //--- let the task run -------------------------

                switch ($taskName)
                {

                    //=== real task definitions =================================

                    //--- curl standard tasks --------------------------------------------------

                    case strtolower('get'):
                        $this->actTask = $this->createTask(new getCurlTask(), $actTask);
                        break;

                    case strtolower('put'):
                        $this->actTask = $this->createTask(new putCurlTask(), $actTask);
                        break;

                    case strtolower('post'):
                        $this->actTask = $this->createTask(new postCurlTask(), $actTask);
                        break;

                    case strtolower('patch'):
                        $this->actTask = $this->createTask(new patchCurlTask(), $actTask);
                        break;

                    case strtolower('delete'):
                        $this->actTask = $this->createTask(new deleteCurlTask(), $actTask);
                        break;

//                    case strtolower('abc'):
//                        $this->actTask = $this->createTask(new  (), $actTask);
//                        break;
//
//                    case strtolower('abc'):
//                        $this->actTask = $this->createTask(new  (), $actTask);
//                        break;
//
//                    case strtolower('abc'):
//                        $this->actTask = $this->createTask(new  (), $actTask);
//                        break;
//
//                    case strtolower('abc'):
//                        $this->actTask = $this->createTask(new  (), $actTask);
//                        break;

                    default:
                        print ('!!! Execute unknown task: "' . $actTask->name . '" !!!' . PHP_EOL);
                        throw new \Exception('!!! Execute unknown task: "' . $actTask->name . '" !!!');
                } // switch

                // run task
                $hasError = $this->actTask->execute();

                // $OutTxt .= $task->text() . PHP_EOL;
            }
        }
        catch (\Exception $e)
        {
            echo '!!! applyTasks: Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        print('exit applyTasks: ' . $hasError . PHP_EOL);

        return $hasError;
    }

    /**
     * Collect filenames, execute on separate task
     *
     * @param   task[]  $tasks
     *
     * @return array
     */
    private function handleFileTasks($task = [])
    {
        $isHandled = false;
        $hasError  = 0;

        //--- let the task run -------------------------

        $taskName = strtolower($task->name);

        switch ($taskName)
        {
            //=== supporting tasks content  ===============================

            case strtolower('execute'):
                print ('>>> Call execute task: "' // . $this->actTask->name
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
                print ('Execute task: ' . $task->name . PHP_EOL);

                $this->actTask = $this->createTask(new fileNamesList (), $task);
                // run task
                $hasError = $this->actTask->execute();

                print ('createFilenamesList count: ' . count($this->fileNamesList->fileNames) . PHP_EOL);

                break;

            //--- add more files to task -----------------------

            case strtolower('add2filenameslist'):
                print ('Execute task: ' . $task->name . PHP_EOL);
                $filenamesList = new fileNamesList ();
                $filenamesList->assignTask($task);
                $filenamesList->execute();

                if (empty($this->fileNamesList))
                {
                    $this->fileNamesList = new fileNamesList ();
                }

                print ('add2FilenamesList count: ' . count($filenamesList->fileNames) . PHP_EOL);

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

            case strtolower('???xxx'):
//                        $this->actTask = $this->createTask(new exchangeAll_licenseLines (), $textTask);
                break;

            default:
                // handled outside
                break;
        } // switch

        return [$isHandled, $hasError];
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

        if (!empty ($this->actTask))
        {
            $OutTxt .= $this->actTask->text();
        }
        else
        {
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

    public function extractTasksFromFile(mixed $taskFile): curlApiTasks
    {
        $tasks = new tasks();
        $this->assignTasks($tasks->extractTasksFromFile($taskFile));

        return $this;
    }

} // curlApiTask

