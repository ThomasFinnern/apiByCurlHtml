<?php


use Finnern\apiByCurlHtml\src\lib\AutoSrcDstPathFileNames;

require_once '../autoload/autoload.php';

/**
 * Tests to check if AutoSrcDstPathFileNames works as expected
 */
enum path_and_file
{
    case FULL_PATH;
    case PATH_FILENAME_SEPARATE;
}

class test
{
    public string $srcPathName = '';
    public string $srcFileName = '';

    public string $dstPathName = '';
    public string $dstFileName = '';

    public string $dstExtension = '';

    public string $srcExpected = '';
    public string $dstExpected = '';

    public function __construct()
    {
//        parent::__construct();
        $this->init();
    }

    public function init()
    {

        $this->srcPathName = '';
        $this->srcFileName = '';

        $this->dstExtension = '';

        $this->dstPathName = '';
        $this->dstFileName = '';

        $this->srcExpected = '';
        $this->dstExpected = '';
    }

    public function doTest($path_type = path_and_file::PATH_FILENAME_SEPARATE): bool
    {
        $hasError = false;

        $autoFileNames = new AutoSrcDstPathFileNames();
        $autoFileNames->init();

        if ($path_type == path_and_file::PATH_FILENAME_SEPARATE) {

            $autoFileNames->assignFilePaths($this->srcFileName, $this->srcPathName,
                $this->dstFileName, $this->dstPathName,
                $this->dstExtension);

        } else {

            $autoFileNames->assignFilePaths($this->srcFileName, "",
                $this->dstFileName, "",
                $this->dstExtension);
        }

        $srcFound = $autoFileNames->getSrcPathFileName();
        $dstFound = $autoFileNames->getDstPathFileName();

        if ($srcFound != $this->srcExpected) {
            print ("!!! Error: Source is different then expected: !!!" . PHP_EOL
                . "   Expected: '" . $this->srcExpected . "'" . PHP_EOL
                . "   Found:    '" . $srcFound . "'" . PHP_EOL)
            ;
            $hasError = true;
        }

        if ($dstFound != $this->dstExpected) {
            print ("!!! Error: Destin. is different then expected: !!!" . PHP_EOL
                . "   Expected: '" . $this->dstExpected . "'" . PHP_EOL
                . "   Found:    '" . $dstFound . "'" . PHP_EOL)
            ;
            $hasError = true;
        }

        return $hasError;
    }

} // class


// 1) Standard

$hasError = test_01();
printResult($hasError, 1);

$hasError = test_02();
printResult($hasError, 2);

//$hasError = test_03();
//printResult($hasError, 3);

//$hasError = test_04();
//printResult($hasError, 4);

//$hasError = test_05();
//printResult($hasError, 5);

//$hasError = test_06();
//printResult($hasError, 6);

//$hasError = test_07();
//printResult($hasError, 7);



// Further tests may be added as errors appear ;-)


/**
 * @param bool $hasError
 * @return void
 */
function printResult(bool $hasError, int $testNbr=999)
{

    if (!$hasError) {
        print (" - test ' . $testNbr . ' successful" . PHP_EOL);
    } else {
        print (" - test ' . $testNbr . ' unsuccessful" . PHP_EOL);
    }
}


function test_01():bool
{
    $test = new test();

    //--- prepare test -------------------------------------

    $test->srcFileName = 'd:\Entwickl\2025\_gitHub\apiByCurlHtml\src\curl_tsk_files\rsg2_getImages.tsk.http';
    $test->dstFileName = '';

    $test->dstPathName = '';
    $test->dstFileName = '';

    $test->dstExtension = 'tsk';

    $test->srcExpected = 'd:\Entwickl\2025\_gitHub\apiByCurlHtml\src\curl_tsk_files\rsg2_getImages.tsk.http';
    $test->dstExpected = 'd:\Entwickl\2025\_gitHub\apiByCurlHtml\src\curl_tsk_files\rsg2_getImages.tsk.tsk';

    //--- do test -------------------------------------

    $hasError = $test->doTest(path_and_file::FULL_PATH);
    // $hasError = $test->doTest(path_and_file::PATH_FILENAME_SEPARATE);
    if ($hasError) {
        // debug stop
        $hasError = $hasError;
    }

    return $hasError;
}

function test_02():bool
{
    $test = new test();

    //--- prepare test -------------------------------------

    $test->srcPathName = 'd:/Entwickl/2025/_gitHub/apiByCurlHtml/src/curl_tsk_files';
    $test->srcFileName = 'rsg2_getGallery.tsk';

    $test->dstPathName = 'd:/Entwickl/2025/_gitHub/apiByCurlHtml/src/curl_http_files';
    $test->dstFileName = '';

    $test->dstExtension = 'http';

    $test->srcExpected = 'd:\Entwickl\2025\_gitHub\apiByCurlHtml\src\curl_tsk_files\rsg2_getGallery.tsk';
    $test->dstExpected = 'd:\Entwickl\2025\_gitHub\apiByCurlHtml\src\curl_http_files\rsg2_getGallery.http';

    //--- do test -------------------------------------

    $hasError = $test->doTest(path_and_file::PATH_FILENAME_SEPARATE);
    // $hasError = $test->doTest(path_and_file::PATH_FILENAME_SEPARATE);
    if ($hasError) {
        // debug stop
        $hasError = $hasError;
    }

    return $hasError;
}

//function test_03()
//{
//    $test = new test();
//
//    //--- prepare test -------------------------------------
//
//    $test->srcPathName = '';
//    $test->srcFileName = '';
//
//    $test->dstPathName = '';
//    $test->dstFileName = '';
//
//    $test->dstExtension = '';
//
//    $test->srcExpected = '';
//    $test->dstExpected = '';
//
//    //--- do test -------------------------------------
//
//    $hasError = $test->doTest(path_and_file::FULL_PATH);
//    // $hasError = $test->doTest(path_and_file::PATH_FILENAME_SEPARATE);
//    if ($hasError) {
//        // debug stop
//        $hasError = $hasError;
//    }
//
//return $hasError;
//
//}


