<?php

namespace Finnern\apiByCurlHtml\src\task_http_file;

use Finnern\apiByCurlHtml\src\tasksLib\baseExecuteTasks;

abstract class baseHttpFileData extends baseExecuteTasks
{
    protected string $FilePathName;
    protected array $lines;

//    abstract protected function createSrcDstPath ();
    abstract protected function extractFileData ($lines = []) : int;
    abstract public function createFileLines () : array;

    protected  function readFile (string $fileName = '') : int
    {
        if (empty($fileName)) {
            $fileName = $this->FilePathName;
        }

        if (! is_file($fileName)) {

            print ('File not found: ' . $fileName . "\n");
            print ('File not found: ' . realpath($fileName) . "\n");
            return -789;
        }

        $content = file_get_contents($fileName); //Get the file
        $lines = explode("\n", $content); //Split the file by each line

        $this->extractFileData($lines);

        // ToDo: try catch , return $hasError
        return 0;
    }

    public  function writeFile (string $fileName = '', $lines=[]) : int
    {
        if (empty($fileName)) {
            $fileName = $this->FilePathName;
        }

        if (empty($lines)) {
            $lines = $this->lines;
        }

        $content = implode("\r\n", $lines);

        file_put_contents ($fileName, $content);

        // ToDo: try catch , return $hasError
        return 0;
    }

}