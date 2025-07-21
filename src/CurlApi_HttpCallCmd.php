<?php

namespace Finnern\apiByCurlHtml\src;

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

$taskFile = '../../RSGallery2_J4_Dev/.buildPHP_extern/build_plugin_rsg2_console.tsk';

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
    print ('... Zipping finished .......' . "\r\n");

}

commandLineLib::print_end($start);

print ("--- end  ---" . "\n");

