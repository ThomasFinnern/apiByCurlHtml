<?php

namespace Finnern\apiByCurlHtml\src\task_http_file;

use Finnern\apiByCurlHtml\src\curl_tasks\baseCurlTask;
use Finnern\apiByCurlHtml\src\tasksLib\task;

class curlCmdLineData extends baseCurlTask
{
    //--- http php debug file content --------------------
    // file: galleriesGet.http
    //-----------------------------------------------------
    // ###
    // GET  http://127.0.0.1/joomla5x/api/index.php/v1/rsgallery2/galleries
    // Accept: application/vnd.api+json
    // Content-Type: application/json
    // X-Joomla-Token: "c2hhMjU2OjI5MzphYTZhMTcwZTY2ODM1MTZhMmNiYzlkZDg0NjE5NzkxYTZkYThhNTJjODFhZTVkNWViYmZmMjljMmY2ZTQ4NGYz"
    //-----------------------------------------------------

    public string $dataFile = '';
    public string $accept = "application/vnd.api+json";
    public string $contentType = "application/json";
    public string $joomlaToken = "";
    protected string $filePathName;

    // ToDo: joomla token file

    public function __construct($fileName = "", $isTarget = false)
    {
        $hasError = 0;

        parent::__construct();

        try
        {
//            print('*********************************************************' . PHP_EOL);
            print ("Construct curlCmdLineData: " . PHP_EOL);
            print ("fileName: " . $fileName . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

            $this->filePathName = $fileName;

            if (!empty ($fileName))
            {
                if (!$isTarget)
                {
                    if (file_exists($fileName))
                    {
                        // collect source task data

                        $task = new task();
                        $task->extractTaskFromFile($fileName); // does extract data
                        $this->assignTask($task);

                    } // ToDo:
                    else
                    {
                        $OutTxt = "!!! tskFileData source file found: '" . $fileName . "'" . PHP_EOL;
                        print ($OutTxt);
                    } // ToDo: else error
                }
            }

        }
        catch (\Exception $e)
        {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

    }

    /**
     * Extract single task from lines of file
     * See *.tsk (*.opt) for examples
     *
     * @param   string  $taskFile
     *
     * @return $this
     */
    public function extractTaskFromFile(string $taskFile): task
    {
        print('*********************************************************' . PHP_EOL);
        print ("extractTaskFromFile: " . $taskFile . PHP_EOL);
        print('---------------------------------------------------------' . PHP_EOL);

        $this->clear();

        try
        {
            if (!is_file($taskFile))
            {
                // not working $realPath = realpath($taskFile);
                throw new \Exception('Task file not found: "' . $taskFile . '"');
            }

            $content = file_get_contents($taskFile); //Get the file
            $lines   = explode("\n", $content); //Split the file by each line

            $this->extractTaskFromLines($lines);

        }
        catch (\Exception $e)
        {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        return $this;
    }

//    protected function extractFileData($lines = []): int
//    {
//
////        $content = file_get_contents($httpFile);
////        $lines = explode("\n", $content);
////        print ("Lines:" . $content);
////
//
//        //--- prepare standard  ------------------
//
//        $cmd         = 'GET';
//        $url         = 'http://127.0.0.1/joomla5x/api/index.php/v1/users';
//        $accept      = 'application/vnd.api+json';
//        $contentType = 'application/json';
//        $token       = '';
//        $dataFile    = '';
//
//        print ('--- lines:' . PHP_EOL);
//
//// ###
//// GET  http://127.0.0.1/joomla5x/api/index.php/v1/lang4dev/projects
//// Accept: application/vnd.api+json
//// Content-Type: application/json
//// X-Joomla-Token: "c2hhMjU..."
////  < ./input.txt
//
//        $isStartFound = false;
//        foreach ($lines as $idx => $line)
//        {
//
//            print ($line . PHP_EOL);
//
//            $line = trim($line);
//
//            if (str_starts_with($line, '###'))
//            {
//                $isStartFound = true;
//            }
//
//            // comment line
//            if (str_starts_with($line, '#'))
//            {
//                continue;
//            }
//
//            // empty line
//            $line = trim($line);
//            if (empty($line))
//            {
//                continue;
//            }
//
//            if (!$isStartFound)
//            {
//                continue;
//            }
//
//            //--- put/patch datafile ------------------------------
//
//            // comment line
//            if (str_starts_with($line, '<'))
//            {
//                $dataFile       = trim(substr($line, strlen('<')));
//                $this->dataFile = $dataFile;
//                continue;
//            }
//
//            //--- command -------------------------------------
//
//            $isCommand = false;
//            // $lowerLine  = strtolower($line);
//
//            $parts = explode(' ', $line, 2);
//
//            $testCmd = strtolower(trim($parts[0]));
//            if ($testCmd == 'get' || $testCmd == 'post' || $testCmd == 'put' || $testCmd == 'delete' || $testCmd == 'patch')
//            {
//                $isCommand = true;
//
//                $this->taskName = trim($parts[0]);
//
//                // url
//                if (count($parts) > 1)
//                {
//                    $this->assignBaseAndApiPath(trim($parts[1]));
//                }
//                continue;
//            }
//
//            // 'name': 'value' definition ?
//            if (str_contains($line, ':'))
//            {
//
//                $parts = explode(':', $line, 2);
//                if (count($parts) > 1)
//                {
//
//                    $value = trim($parts[1]);
//
//                    switch (strtolower($parts[0]))
//                    {
//                        case strtolower('Accept'):
//                            $this->accept = $value;
//                            break;
//                        case strtolower('Content-Type'):
//                            $this->contentType = $value;
//                            break;
//                        case strtolower('X-Joomla-Token'):
//                            $this->joomlaToken = $value;
//                            break;
//                        default:
//                            print ('!!! Error unknown line content: "' . $line . '" !!!' . PHP_EOL);
//                            break;
//                    }
//                }
//
//            }
//        }
//
//        print ('$cmd: "' . $this->taskName . '"' . PHP_EOL);
//        print ('$url: "' . $this->urlPath() . '"' . PHP_EOL);
//        print ('$accept: "' . $this->accept . '"' . PHP_EOL);
//        print ('$contentType: "' . $this->contentType . '"' . PHP_EOL);
//        print ('$token: "' . $this->joomlaToken . '"' . PHP_EOL . PHP_EOL);
//
//        return 0;
//    }

//    private function assignBaseAndApiPath(string $url)
//    {
//        $this->baseUrl = '';
////        $this->apiPath = '';
//
//        $search = 'api/index.php';
//        $len    = strlen($search);
//
//        $idx = strpos($url, $search);
//        if ($idx !== false)
//        {
//
//            $this->baseUrl = substr($url, 0, $idx + $len);
////            $this->apiPath = substr($url, $idx + $len + 1);
//        }
//
//    }

    /**
     *
     * https://curl.se/docs/httpscripting.html
     *
     * @return array
     */
    public function createFileLines(): array
    {
        // following lines have an indent behind 'curl '
        $tab = '     ';

        //--- prepare curl commandline data  ------------------------------------

        // Extract token from file and other *.tsk data
        $this->extactDataFromTaskFile();

        //--- create lines ----------------------------------------------

        $lines = [];

        $lines[] = '@ECHO OFF';
        $lines[] = 'REM Curl command: ' . $this->taskName;
        $lines[] = 'REM ';
        $lines[] = 'CLS';
//        $lines[] = '';

        //--- curl command ---------------------------------------

        $cmdLine    = $this->curlCommandLine();
        $cmdLines[] = $cmdLine;

        // ECHO curl command line
        $lines[] = 'ECHO ' . substr($cmdLine, 0, -1) . '....';

        //--- content transfer line -------------------------------

        if (isset ($this->params['content']))
        {
            //$cmdLines[]    = $tab . '-H Content-Type: multipart/form-data' . '\" ^';
            $cmdLines[]    = $tab . '-H \"Content-Type: multipart/mixed' . '\" ^';

            $savedFileName = $this->saveContent();
            $contentLine   = $tab . '-F content=@\"' . $savedFileName . '\" ^';
            $cmdLines[]    = $contentLine;

            if (!empty($contentLine))
            {
                $lines[] = 'ECHO ' . substr($contentLine, 0, -1) . '....';
            }
//            $lines[] = 'ECHO ' . substr($contentLine, 0, -1) . '....';
        }

        //--- curl parameter -------------------------------

        // Standard parameter
        if (!empty($this->params))
        {
            $parameterLines = $this->curlParameterLine();
            $cmdLines[]     = $parameterLines;

            // if (str_starts_with($parameterLines, '-d '))
            {
                $lines[] = 'ECHO ' . substr($parameterLines, 0, -1);
            }
        }

        //--- X-Token -----------------------------------------

        if (!empty($this->joomlaToken))
        {
            $xTokenLine = $tab . '-H "X-Joomla-Token: ' . $this->joomlaToken . '"' . ' ^';
            $cmdLines[] = $xTokenLine;
        }

        //--- collect curl command line  ---------------------------------------

        array_push($lines, ...$cmdLines);

        //--- pretty print result (python) ---------------------------------------

        $lines[] = '	 | python pyPrettyJson.py';

        $lines[] = '';
        $lines[] = 'ECHO Press any key';
        $lines[] = 'pause';
//        $lines[] = 'REM ECHO Press any key';
//        $lines[] = 'REM pause';
//        $lines[] = '';
//        $lines[] = '';
//

        $this->lines = $lines;

        return $lines;
    }

    private function curlCommandLine()
    {
        // $curlCmd[] = $this->taskName;

//        $lines[] = '###';
//        $lines[] = strtoupper($this->taskName) . ' ' . $this->urlPath();
//        $lines[] = 'Accept: ' . $this->accept;
//        $lines[] = 'Content-Type: ' . $this->contentType;
//        $lines[] = 'X-Joomla-Token: ' . $this->joomlaToken;
//
//        if (!empty ($this->params))
//        {
//            $jsonPara = $this->convertParams2Json();
//            $lines[]  = "\n" . $jsonPara;
//        }
//
//        if (empty($this->responseFile))
//        {
//            $this->responseFile = substr($this->filePathName, 0, -5) . '.json';
//        }
//        $lines[] = "\n" . '> ' . $this->responseFile;
//
//        $this->lines = $lines;

//        $curlCmd   = [];
//        $curlCmd[] = 'curl';
//


// curl -X POST
// --header 'Content-Type: application/json'
// --header 'Accept: application/json'
// -d '{ \
//   "email": "test.com", \
//   "password": "123456" \
// }' 'http://example.com/login'

        $line = "curl command ???";

//        switch (strtolower($this->taskName))
//        {
//            case 'get':
//                $line = 'curl -s --show-error --get "' . $this->baseUrl . '/' . $this->apiPath . '" ^';
//                break;
//
//            case 'post':
//                $line = 'curl -s --show-error -X POST "' . $this->baseUrl . '/' . $this->apiPath . '" ^';
//
//                break;
//
//            case 'patch':
//                $line = 'curl -s --show-error -X PATCH "' . $this->baseUrl . '/' . $this->apiPath . '" ^';
//
//                break;
//
//            case 'delete':
//                $line = 'curl -s --show-error -X DELETE "' . $this->baseUrl . '/' . $this->apiPath . '" ^';
//
//                break;
//        }
//
//        $oldLine = $line;

        $pre = 'curl -s --show-error ';
        $task = ' -X ' . strtoupper($this->taskName);
        $url = $this->urlPath();
        $post = ' ^';

        // $line = $pre . $task . ' ' . $url . $post;
        $line = $pre . $task . ' "' . $url . '"' . $post;

        return $line;
    }

    /**
     * @return string
     */
    private function curlParameterLine()
    {

        $line = "Parameters ???";

        // following lines have an indent behind 'curl '
        $tab = '     ';

        // Standard parameter
        if (!empty($this->params))
        {
            //--- collect params in one json line ----------------------

            // from given parameters to parameter object
            $params = $this->params;

            // remove base64 file content
            if (isset ($params['content']))
            {
                unset($params['content']);

//                //--- As form field parameters --------------------------------------
//
//                $line = $tab . '-F "';
//
//                foreach ($params as $key => $value)
//                {
//                    $line .=  $key . '=' . $value . ';';
//                }

                //--- json string parameters  ----------------------------------

                $encoded = json_encode($params);
                $slashes = addslashes($encoded);

                // "metadata={\"edipi\":123456789,\"firstName\":\"John\",\"lastName\":\"Smith\",\"email\":\"john.smith@gmail.com\"}
                //           ;type=application/json"
                $line = $tab . '-F "metadata=' . $slashes . ';type=application/json';

                $line .= '"' . ' ^';
            }
            else
            {
                //--- json string parameters  ----------------------------------

                $encoded = json_encode($params);
                $slashes = addslashes($encoded);

                //--- create params json line ----------------------

                $line = $tab . '-d "' . $slashes . '" ^';

            }

        }

        //--- additional parameters ----------------------------------------

//        // page offset or other parameter
//        if (!empty($this->urlRouterParams))
//        {
//            // from given parameters to parameter object
//            // create query : ?page[offset]=90&page[limit]=30
//            $test = '-d' . implode(" -d", $this->urlRouterParams);
//            $lines[] = $tab . '-d' . implode(" -d", $this->urlRouterParams) . ' ^';
//        }

        //  -o thatpage.html

        //--- Accept json -----------------------------------------

        // $lines[] = $tab . '-H "Accept: application/json"' . ' ^';


//        $lines[] = implode(" ", $curlCmd);

        return $line;
    }

    /**
     * Save base 64 content into file
     * Add '.base64' to given Filename
     * Tell about created filename
     *
     * @return string
     */
    private function saveContent()
    {
        //--- Create filename to save the base64 data ----------------------------------------------

        //$saveFileName = "d:\\Entwickl\\2026\\_gitHub\\RSGallery2_J4_Dev\\.apiTests\\dataFiles\\2014-12-29_00005.jpg.base64";
        //$saveFileName = "d:\\Entwickl\\2026\\_gitHub\\RSGallery2_J4_Dev\\.apiTests\\dataFiles\\transfer.base64";
        $saveFileName = "transfer.base64";

        $saveDir = dirname($this->filePathName);

        if (!empty($this->dataFile))
        {
            $saveFileName =  basename($this->dataFile) . '.base64';
        }

// Here other file creation ...
//        if (!empty($this->params['image']))
//        {
//            $saveFileName = $this->params['image'] . '.base64';
//        }
//

        try
        {
            print ("      Saving " . $saveFileName . PHP_EOL);
            $filePathName = $saveDir . '\\' . $saveFileName;
            print ("      Absolut:" . $filePathName . PHP_EOL);

            //--- save -------------------------------------------

            file_put_contents($filePathName, $this->params['content']);
        }
        catch (\Exception $e)
        {
            throw new \Exception ("saveContent: could not write base64 content to file: " . $saveFileName . PHP_EOL . $e->getMessage(), $e->getCode(), $e);
        }

        return $saveFileName;
    }
}
