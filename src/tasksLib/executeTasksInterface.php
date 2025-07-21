<?php
//namespace \Vendor\App\DatabaseAccess;
namespace Finnern\apiByCurlHtml\src\tasksLib;

use Finnern\apiByCurlHtml\src\fileNamesLib\fileNamesList;
use Finnern\apiByCurlHtml\src\tasksLib\task;

/*================================================================================
interface executeTasksInterface
================================================================================*/

interface executeTasksInterface
{
//    // List of filenames to use
//    public function assignFilesNames(fileNamesList $fileNamesList): int;

    // Task with options
    public function assignTask(task $task): int;

    public function execute(): int; // $hasError

    public function executeFile(string $filePathName): int;


}