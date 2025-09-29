<?php
namespace Finnern\apiByCurlHtml\src\task_http_file;

require_once '../autoload/autoload.php';

use Finnern\apiByCurlHtml\src\tasksLib\commandLineLib;
use Finnern\apiByCurlHtml\src\tasksLib\option;
use Finnern\apiByCurlHtml\src\tasksLib\task;

$HELP_MSG = <<<EOT
    >>>
    file: tsk2httpFileCmd.php

    ToDo: option commands , example
    ToDo: Test result -> "total-pages": 6 see next below
        "links": {
                "self": "http://127.0.0.1/joomla5x/api/index.php/v1/config/application",
                "next": "http://127.0.0.1/joomla5x/api/index.php/v1/config/application?page%5Boffset%5D=20&page%5Blimit%5D=20",
                "last": "http://127.0.0.1/joomla5x/api/index.php/v1/config/application?page%5Boffset%5D=100&page%5Blimit%5D=20"
            },
                  

    <<<
    EOT;


/*---------------------------------------------------------------------------
functions
---------------------------------------------------------------------------*/

/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "t:f:o:s:d:j:r:e:y:h12345";
$isPrintArguments = false;
//$isPrintArguments = true;

[$inArgs, $options] = commandLineLib::argsAndOptions($argv, $optDefinition, $isPrintArguments);

$LeaveOut_01 = true;
$LeaveOut_02 = true;
$LeaveOut_03 = true;
$LeaveOut_04 = true;
$LeaveOut_05 = true;

/*--------------------------------------------
variables
--------------------------------------------*/

// $tasksLine = ' task:tsk2httpFile';

//$tasksLine = ' task:tsk2httpFile'
//    . ' /srcPath=d:/Entwickl/2025/_gitHub/apiByCurlHtml/src/curl_tsk_files'
//    . ' /srcFile="rsg2_getGallery.tsk"'
//    . ' /dstPath=d:/Entwickl/2025/_gitHub/apiByCurlHtml/src/curl_http_files'
//    . ' /dstFile="rsg2_getGallery.http"';


//// ToDo:
//$tasksLine = ' task:http2tskFile'
//    . ' /srcPath=d:/Entwickl/2025/_gitHub/apiByCurlHtml/src/curl_http_files'
//    . ' /srcFile="./../../rsg2_getGallery2.http"'
//    . ' /dstPath=d:/Entwickl/2025/_gitHub/apiByCurlHtml/src/curl_tsk_files'
//    . ' /dstFile="./../../rsg2_getGallery2.tsk"';

$tasksLine = ' task:tsk2httpFile'
    . ' /srcPath=d:\Entwickl\2025\_gitHub\JoomGallery_fith_dev\.apiTests'
    . ' /srcFile="jg_patchImage_title.tsk"'
    . ' /dstPath=d:\Entwickl\2025\_gitHub\JoomGallery_fith_dev\.apiTests\curl_http_files'
    . ' /dstFile="jg_patchImage_title.http"'
;




//$taskFile = '../../apiByCurlHtml/src/curl_tsk_files/rsg2_getGalleries.tsk';
//$taskFile = '../../apiByCurlHtml/src/curl_tsk_files/rsg2_getGallery.tsk';
//$taskFile = '../../apiByCurlHtml/src/curl_tsk_files/j!_getConfigAll.tsk';
//$taskFile = '../../apiByCurlHtml/src/curl_tsk_files/j!_getTest.tsk';

$srcFile = "";
$dstFile = "";
$responseFile = "";
$joomlaTokenFile = "";
$dstExtension = '';

foreach ($options as $idx => $option) {
    print ("idx: " . $idx . PHP_EOL);
    print ("option: " . $option . PHP_EOL);

    switch ($idx) {
        case 't':
            $tasksLine = $option;
            break;

        case 'f':
            $taskFile = $option;
            break;

        case 'r':
            $responseFile = $option;
            break;

        case 'j':
            $joomlaTokenFile = $option;
            break;

        case 'o':
            $optionFiles[] = $option;
            break;

        case 's':
            $srcFile = $option;
            break;

        case 'd':
            $dstPath = $option;
            break;

        case 'e':
            $dstExtension = $option;
            break;

        case 'y':
            $dstFile = $option;
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

if (!empty ($taskFile)) {
    $task = $task->extractTaskFromFile($taskFile);
} else {
    $task = $task->extractTaskFromString($tasksLine);
}

//--- extract options from file(s) ------------------

if (!empty($optionFiles)) {
    foreach ($optionFiles as $optionFile) {
        $task->extractOptionsFromFile($optionFile);
    }
}

// add options from command line

if (!empty($srcFile)) {
    $task->options->addOption(new option("srcFile", $srcFile));
}

if (!empty($dstFile)) {
    $task->options->addOption(new option("dstFile", $dstFile));
}

if (!empty($dstPath)) {
    $task->options->addOption(new option("dstPath", $dstPath));
}

if (!empty($responseFile)) {
    $task->options->addOption(new option("responseFile", $responseFile));
}

if (!empty($joomlaTokenFile)) {
    $task->options->addOption(new option("joomlaTokenFile", $joomlaTokenFile));
}

if (!empty($dstExtension)) {
    $task->options->addOption(new option("dstExtension", $dstExtension));
}


print ($task->text());

/*--------------------------------------------------
   execute task
--------------------------------------------------*/

if (empty ($hasError)) {

    $oTsk2HttpFile = new tsk2httpFile();
    $oTsk2HttpFile->task = $task;

    //--- assign tasks ---------------------------------

    $hasError = $oTsk2HttpFile->assignTask($task);
    if ($hasError) {
        print ("%%% Error on function assignTask:" . $hasError) . "\n";
    } else {
        print ($oTsk2HttpFile->text() . PHP_EOL);
    }

    //--- execute tasks ---------------------------------

    if (!$hasError) {
        $hasError = $oTsk2HttpFile->execute();
        if ($hasError) {
            print ("%%% Error on function execute:" . $hasError) . "\n";
        }
    }

//    print (PHP_EOL . '-------------------------------------' . PHP_EOL);
    print (          '... CurlApi_HttpCall finished .......' . PHP_EOL);
//    print (PHP_EOL . '-------------------------------------' . PHP_EOL);
}

commandLineLib::print_end($start);

print ("--- end  ---" . "\n");

