<?php

namespace Finnern\apiByCurlHtml\src\tasksLib;

// use CurlHandle;

//use Finnern\apiByCurlHtml\src\tasksLib\option;

/**
 * Not used originated from buildExtension project
 */
class exchangeData extends task implements executeTasksInterface
{
    public array $keepParams;
    public array $fetchParams;
    public string $exchangeFile;
    public array $exchangeParams;

    /**
     * Assign task name and  options
     *
     * @param   task  $task
     *
     * @return int
     */
    public function assignTask(task $task): int
    {
        $this->taskName = $task->name;

        $options = $task->options;

        $this->assignOptions($options, $task->name);

        return 0;
    }

    /**
     * @param   options  $options
     * @param   task     $task
     *
     * @return bool
     */
    public function assignOptions(options $options, $taskName): int
    {

        foreach ($options->options as $option)
        {

            $isParentOption = $this->assignOption($option);
            if (!$isParentOption)
            {
                print ('%%% warning: requested option is not supported: ' . $taskName . '.' . $option->name . ' !!!' . PHP_EOL);
            }
        }

        return 0;
    }

    /**
     * @param   option  $option
     *
     * @return bool true on option is consumed
     */
    public function assignOption(option $option): bool
    {
        $isOptionConsumed = false;
//        $isOptionConsumed = $this->fileNamesList->assignOption($option);

        if (!$isOptionConsumed)
        {

            switch (strtolower($option->name))
            {
                case strtolower('exchangeFile'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->exchangeFile = $option->value;

                    $this->readExchangeFile();

                    $isOptionConsumed = true;
                    break;

                case strtolower('keepParam'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->keepParams[] = $option->value;

                    $isOptionConsumed = true;
                    break;

                case strtolower('clearKeeps'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->keepParams = [];

                    $isOptionConsumed = true;
                    break;

                case strtolower('fetchParams'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->fetchParams[] = $option->value;

                    $isOptionConsumed = true;
                    break;

                case strtolower('clearFetchs'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->fetchParams = [];

                    $isOptionConsumed = true;
                    break;

//                case strtolower('exchangeParam'):
//                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
//                    $this->exchangeParams[] = ... $option->value;
//
//                    $isOptionConsumed      = true;
//                    break;
//
                case strtolower('clearExchangeParams'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->exchangeParams = [];

                    $this->writeExchangeFile();

                    $isOptionConsumed = true;
                    break;

                // debug or force to zero
                case strtolower('writeExchangeFile'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);

                    $this->writeExchangeFile();

                    $isOptionConsumed = true;
                    break;

            } // switch
        }

        return $isOptionConsumed;
    }

    public function readExchangeFile(): bool
    {
        $isOk = false;

        try
        {
            $exchangeParamsLines = file_get_contents($this->exchangeFile);

            $exchangeParams = json_decode($exchangeParamsLines, true);

            if (!empty ($exchangeParams))
            {
                $this->exchangeParams = $exchangeParams;

                $isOk = true;
            }
        }
        catch (\Exception $e)
        {
            echo '!!! Error: Exception in baseExecuteTasks::readExchangeFile: ' . $e->getMessage() . PHP_EOL;
        }

        return $isOk;
    }

    public function writeExchangeFile(): bool
    {
        $return = false;

        try
        {
            $exchangeParams = json_encode($this->exchangeParams, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            $return = file_put_contents($this->exchangeFile, $exchangeParams);
        }
        catch (\Exception $e)
        {
            echo '!!! Error: Exception in baseExecuteTasks::writeExchangeFile: ' . $e->getMessage() . PHP_EOL;
        }

        return $return;
    }

    /**
     * Execute action on one file
     *
     * @param   string  $filePathName
     *
     * @return int
     */
    public function executeFile(string $filePathName): int
    {
        $this->execute();

        return -1;
    }

    public function execute(): int
    {
        // TODO: Implement execute() method.
        return -1;
    }

    public function text(): string
    {
        // TODO: Implement text() method.
        return "Not implemented yet";
    }

} // class

