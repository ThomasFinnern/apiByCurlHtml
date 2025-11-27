<?php

namespace Finnern\apiByCurlHtml\src;

require_once 'autoload/autoload.php';

use Finnern\apiByCurlHtml\src\tasksLib\commandLineLib;
use Finnern\apiByCurlHtml\src\tasksLib\tasks;

$HELP_MSG = <<<EOT
    >>>
    CurlApiTasksCmd module

    ToDo: option commands , example
    ToDo: \$tasks: ? type of input in swich 

    <<<
    EOT;

/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "t:c:f:o:h12345";
$isPrintArguments = false;

[$inArgs, $options] = commandLineLib::argsAndOptions($argv, $optDefinition, $isPrintArguments);

$LeaveOut_01 = true;
$LeaveOut_02 = true;
$LeaveOut_03 = true;
$LeaveOut_04 = true;
$LeaveOut_05 = true;

/*--------------------------------------------
variables
--------------------------------------------*/

$collectedTasks = new tasks;

$tasksLine = ' task:CurlApi_HttpCall'
    . ' /type=component'
    . ' /srcRoot="./../../RSGallery2_J4"'
//    . ' /isNoRecursion=true'
    . ' /buildDir="./../.packages"'
//    . ' /adminPath='
;

//$tasksFile = '../../apiByCurlHtml/src/curl_tsk_files/rsg2_getGalleries.tsk';
//$tasksFile = '../../apiByCurlHtml/src/curl_tsk_files/rsg2_getGallery.tsk';
//$tasksFile = '../../apiByCurlHtml/src/curl_tsk_files/rsg2_putGallery.tsk';
$tasksFile = '../../apiByCurlHtml/src/curl_tsk_files/rsg2_deleteGallery.tsk';
//$tasksFile = '../../apiByCurlHtml/src/curl_tsk_files/rsg2_getImages.tsk';
//$tasksFile = '../../apiByCurlHtml/src/curl_tsk_files/rsg2_getImage.tsk';
//$tasksFile = '../../apiByCurlHtml/src/curl_tsk_files/rsg2_putImage.tsk';

//$tasksFile = '../../apiByCurlHtml/src/curl_tsk_files/lang4dev_getProjects.tsk';

//$tasksFile = '../../apiByCurlHtml/src/curl_tsk_files/jg_getCategories.tsk';
//$tasksFile = '../../apiByCurlHtml/src/curl_tsk_files/jg_getCategory.tsk';
//$tasksFile = '../../apiByCurlHtml/src/curl_tsk_files/jg_putCategory.tsk';
//$tasksFile = '../../apiByCurlHtml/src/curl_tsk_files/jg_deleteCategory.tsk';
//$tasksFile = '../../apiByCurlHtml/src/curl_tsk_files/jg_getImages.tsk';
//$tasksFile = '../../apiByCurlHtml/src/curl_tsk_files/jg_getImage.tsk';
//$tasksFile = '../../apiByCurlHtml/src/curl_tsk_files/jg_putImage.tsk';
//$tasksFile = '../../apiByCurlHtml/src/curl_tsk_files/jg_getConfigs.tsk';
//$tasksFile = '../../apiByCurlHtml/src/curl_tsk_files/jg_getConfig.tsk';

//$tasksFile = '../../apiByCurlHtml/src/curl_tsk_files/j!_getConfigAll.tsk';
//$tasksFile = '../../apiByCurlHtml/src/curl_tsk_files/j!_getTest.tsk';
//$tasksFile = '../../apiByCurlHtml/src/curl_tsk_files/jg_patchCategory.tsk';
//$tasksFile = '../../apiByCurlHtml/src/curl_tsk_files/jg_patchCategory.tsk';

//$tasksFile = '../../RSGallery2_J4_Dev/.apiTests/rsg2_getGalleries.tsk';
//$tasksFile = '../../RSGallery2_J4_Dev/.apiTests/rsg2_putGallery.tsk';
$tasksFile = '../../RSGallery2_J4_Dev/.apiTests/rsg2_putGallerySet_01.tsk';

$basePath = "..\\..\\RSGallery2_J4_Dev";


foreach ($options as $idx => $option) {
    print ("idx: " . $idx . PHP_EOL);
    print ("option: " . $option . PHP_EOL);

    switch ($idx) {
        case 't':
            $tasksLine = $option;
            break;

        case 'f':
            $tasksFile = $option;
            break;

        // separate list of task files
        case 'c':
            $collectedTasks->extractTasksFromFile($option);
            break;

        case 'o':
            $optionFiles[] = $option;
            break;

        case "h":
            exit($HELP_MSG);

        case "1":
            $LeaveOut_01 = true;
            print("LeaveOut_01");
            break;
        case "2":
            $LeaveOut_02 = true;
            print("LeaveOut__02");
            break;
        case "3":
            $LeaveOut_03 = true;
            print("LeaveOut__03");
            break;
        case "4":
            $LeaveOut_04 = true;
            print("LeaveOut__04");
            break;
        case "5":
            $LeaveOut_05 = true;
            print("LeaveOut__05");
            break;

        default:
            print("Option not supported '" . $option . "'");
            break;
    }
}

$start = commandLineLib::print_header($options, $inArgs);

/*----------------------------------------------------------
   collect task
----------------------------------------------------------*/

//--- create class object ---------------------------------

$oCurlApiTask = new curlApiTasks(); // $basePath, $tasksLine
$tasks = new tasks();

//--- extract tasks from string or file ------------------

if ( ! empty ($tasksFile)) {
    print ("taskFile found: " . $tasksFile . PHP_EOL);
    $tasks = $tasks->extractTasksFromFile($tasksFile);
} else {
    if ($collectedTasks->count() > 0) {
        $tasks->assignTasks($collectedTasks);
    } else {
        print ("taskFile empty, TaskLine: " . $tasksLine . PHP_EOL);
        $testTasks = $tasks->extractTasksFromString($tasksLine);
        if (!empty ($hasError)) {
            print ("!!! Error on function extractTasksFromString:" . $hasError
                . ' path: ' . $basePath . PHP_EOL);
        }
    }
}

print ($tasks->text());

/*----------------------------------------------------------
   assign tasks to CurlApiTask class
----------------------------------------------------------*/

// //--- extract tasks from string or file ---------------------------------

// if ($tasksFile != "") {
    // $hasError = $oCurlApiTask->extractTasksFromFile($tasksFile);
    // if (!empty ($hasError)) {
        // print ("!!! Error on function extractTasksFromFile:" . $hasError
            // . ' path: ' . $basePath . PHP_EOL);
    // }

// } else {
    // if ($collectedTasks->count() > 0) {
        // $testTasks = $oCurlApiTask->assignTasks($collectedTasks);
    // } else {
        // $testTasks = $oCurlApiTask->extractTasksFromString($tasksLine);
        // //if (!empty ($hasError)) {
        // //    print ("!!! Error on function extractTasksFromString:" . $hasError
        // //        . ' path: ' . $basePath . PHP_EOL);
        // //}
    // }
// }

// print ($oCurlApiTask->tasksText());

/*--------------------------------------------------
   execute tasks
--------------------------------------------------*/

if (empty ($hasError)) {

	//--- assign tasks ---------------------------------

    $oCurlApiTask->assignTasks($tasks);
	
	//--- execute tasks ---------------------------------

    // create task classes, when task execute is issued the task does execute
    $hasError = $oCurlApiTask->execute();

    if ($hasError) {
        print ("%%% doFileTaskCmd Error: " . $hasError . " on execute task: " . $oCurlApiTask->actTaskName . PHP_EOL);
    }

    if (! $hasError) {
        print ($oCurlApiTask->text() . PHP_EOL);
    }

}

commandLineLib::print_end($start);

print ("--- end  ---" . PHP_EOL);
