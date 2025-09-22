<?php

namespace Finnern\apiByCurlHtml\src\curl_call;

class json_errors
{
    protected array $errors;

    /**
     * @param array $jsonErrors
     */
    public function __construct(array $jsonErrors){
        $this->assignErrors($jsonErrors);
    }

    /**
     * @param array $jsonErrors
     * @return void
     */
    public function assignErrors(array $jsonErrors) : void
    {
        // toDo: try catch ...
        foreach ($jsonErrors as $jsonError) {

            $this->errors[] = new json_error($jsonError);
        }
    }

    public function text(): string
    {
        $outTxt = "";

        foreach ($this->errors as $error) {

            $outTxt .= '---------------------------------------------------------' . PHP_EOL;
            $outTxt .= $error->text() . PHP_EOL;
        }

        return $outTxt;
    }


}


