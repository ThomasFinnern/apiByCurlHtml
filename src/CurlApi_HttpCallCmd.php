<?php

namespace Finnern\apiByCurlHtml\src;

require_once 'autoload/autoload.php';

use Exception;
use Finnern\apiByCurlHtml\src\tasksLib\task;
use Finnern\apiByCurlHtml\src\tasksLib\commandLineLib;

$HELP_MSG = <<<EOT
    >>>
    class CurlApi_HttpCall

    ToDo: option commands , example

    <<<
    EOT;

/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "t:f:o:h12345";
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
$tasksLine = ' task:CurlApi_HttpCall'
    . ' /type=component'
    . ' /srcRoot="./../../RSGallery2_J4"'
//    . ' /isNoRecursion=true'
    . ' /buildDir="./../.packages"'
//    . ' /adminPath='
;

//$taskFile = '../../apiByCurlHtml/src/curl_tasks_tsk/rsg2_getGalleries.tsk';
//$taskFile = '../../apiByCurlHtml/src/curl_tasks_tsk/rsg2_getGallery.tsk';
//$taskFile = '../../apiByCurlHtml/src/curl_tasks_tsk/rsg2_putGallery.tsk';
//$taskFile = '../../apiByCurlHtml/src/curl_tasks_tsk/rsg2_getImages.tsk';
//$taskFile = '../../apiByCurlHtml/src/curl_tasks_tsk/rsg2_getImage.tsk';
//$taskFile = '../../apiByCurlHtml/src/curl_tasks_tsk/rsg2_putImage.tsk';

//$taskFile = '../../apiByCurlHtml/src/curl_tasks_tsk/lang4dev_getProjects.tsk';

//$taskFile = '../../apiByCurlHtml/src/curl_tasks_tsk/jg_getCategories.tsk';
//$taskFile = '../../apiByCurlHtml/src/curl_tasks_tsk/jg_getCategory.tsk';
$taskFile = '../../apiByCurlHtml/src/curl_tasks_tsk/jg_putCategory.tsk';
//$taskFile = '../../apiByCurlHtml/src/curl_tasks_tsk/jg_getImages.tsk';
//$taskFile = '../../apiByCurlHtml/src/curl_tasks_tsk/jg_getImage.tsk';
//$taskFile = '../../apiByCurlHtml/src/curl_tasks_tsk/jg_putImage.tsk';
//$taskFile = '../../apiByCurlHtml/src/curl_tasks_tsk/jg_getConfigs.tsk';
//$taskFile = '../../apiByCurlHtml/src/curl_tasks_tsk/jg_getConfig.tsk';

foreach ($options as $idx => $option) {
    print ("idx: " . $idx . "\r\n");
    print ("option: " . $option . "\r\n");

    switch ($idx) {
        case 't':
            $tasksLine = $option;
            break;

        case 'f':
            $taskFile = $option;
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

// for start / end diff
$start = commandLineLib::print_header($options, $inArgs);

/*----------------------------------------------------------
   collect task
----------------------------------------------------------*/

//--- create class object ---------------------------------

$task = new task();

//--- extract tasks from string or file ------------------

if ( ! empty ($taskFile)) {
    $task = $task->extractTaskFromFile($taskFile);
} else {
    $task = $task->extractTaskFromString($tasksLine);
}

//--- extract options from file(s) ------------------

if ( ! empty($optionFiles) ) {
    foreach ($optionFiles as $optionFile) {
        $task->extractOptionsFromFile($optionFile);
    }
}

print ($task->text());

/*--------------------------------------------------
   execute task
--------------------------------------------------*/

if (empty ($hasError)) {

	$oCurlApi_HttpCall = new CurlApi_HttpCall();

	//--- assign tasks ---------------------------------

	$hasError = $oCurlApi_HttpCall->assignTask($task);
    if ($hasError) {
        print ("%%% Error on function assignTask:" . $hasError) . "\n";
    } else {
        print ($oCurlApi_HttpCall->text() . "\r\n");
    }

	//--- execute tasks ---------------------------------

	if (!$hasError) {
	    $hasError = $oCurlApi_HttpCall->execute();
	    if ($hasError) {
	        print ("%%% Error on function execute:" . $hasError) . "\n";
	    }
	}
	
//	print ($oCurlApi_HttpCall->text() . "\r\n");
    print ("\r\n" . '----------------------------' . "\r\n");
    print ('... CurlApi_HttpCall finished .......' . "\r\n");
}

commandLineLib::print_end($start);

print ("--- end  ---" . "\n");

