<?php

namespace Finnern\apiByCurlHtml\src\curl_tasks;

// --- curl error response types --------------------------------------
//
// direct curl
//    $errorCode    = curl_errno($this->oCurl);
//    $errorMessage = curl_error($this->oCurl);
//
// Single error in ->errors response object
// {
//    "errors": {
//        "code": 500,
//        "title": "Internal server error",
//        "detail": "Error: Call to a member function save() on false in E:\\wamp64\\www\\api_6x\\api\\components\\com_rsgallery2\\src\\Controller\\UploadimgfileController.php:126\nStack trace:\n#0 E:\\wamp64\\www\\api_6x\\libraries\\src\\MVC\\Controller\\ApiController.php(359): Rsgallery2\\Component\\Rsgallery2\\Api\\Controller\\UploadimgfileController->save()\n#1 E:\\wamp64\\www\\api_6x\\api\\components\\com_rsgallery2\\src\\Controller\\UploadimgfileController.php(96): Joomla\\CMS\\MVC\\Controller\\ApiController->add()\n#2 E:\\wamp64\\www\\api_6x\\libraries\\src\\MVC\\Controller\\BaseController.php(730): Rsgallery2\\Component\\Rsgallery2\\Api\\Controller\\UploadimgfileController->upload_image_file()\n#3 E:\\wamp64\\www\\api_6x\\libraries\\src\\Dispatcher\\ApiDispatcher.php(61): Joomla\\CMS\\MVC\\Controller\\BaseController->execute('upload_image_fi...')\n#4 E:\\wamp64\\www\\api_6x\\libraries\\src\\Component\\ComponentHelper.php(361): Joomla\\CMS\\Dispatcher\\ApiDispatcher->dispatch()\n#5 E:\\wamp64\\www\\api_6x\\libraries\\src\\Application\\ApiApplication.php(433): Joomla\\CMS\\Component\\ComponentHelper::renderComponent('com_rsgallery2')\n#6 E:\\wamp64\\www\\api_6x\\libraries\\src\\Application\\ApiApplication.php(116): Joomla\\CMS\\Application\\ApiApplication->dispatch()\n#7 E:\\wamp64\\www\\api_6x\\libraries\\src\\Application\\CMSApplication.php(320): Joomla\\CMS\\Application\\ApiApplication->doExecute()\n#8 E:\\wamp64\\www\\api_6x\\api\\includes\\app.php(50): Joomla\\CMS\\Application\\CMSApplication->execute()\n#9 E:\\wamp64\\www\\api_6x\\api\\index.php(31): require_once('E:\\\\wamp64\\\\www\\\\a...')\n#10 {main}"
//    }
// }
//
// Single error in ->error response object
// {
//    "error": "Error: You must install Joomla to use the API"
// }
//
// Multiple errors in ->errors in response object
// {
//    "errors": [
//        {
//            "code": 500,
//            "title": "Internal server error",
//            "detail": "InvalidArgumentException: Invalid controller class: uploadimgfile in E:\\wamp64\\www\\api_6x\\libraries\\src\\Dispatcher\\ComponentDispatcher.php:174\nStack trace:\n#0 E:\\wamp64\\www\\api_6x\\libraries\\src\\Dispatcher\\ApiDispatcher.php(59): Joomla\\CMS\\Dispatcher\\ComponentDispatcher->getController('uploadimgfile', 'Api', Array)\n#1 E:\\wamp64\\www\\api_6x\\libraries\\src\\Component\\ComponentHelper.php(361): Joomla\\CMS\\Dispatcher\\ApiDispatcher->dispatch()\n#2 E:\\wamp64\\www\\api_6x\\libraries\\src\\Application\\ApiApplication.php(433): Joomla\\CMS\\Component\\ComponentHelper::renderComponent('com_joomgallery')\n#3 E:\\wamp64\\www\\api_6x\\libraries\\src\\Application\\ApiApplication.php(116): Joomla\\CMS\\Application\\ApiApplication->dispatch()\n#4 E:\\wamp64\\www\\api_6x\\libraries\\src\\Application\\CMSApplication.php(320): Joomla\\CMS\\Application\\ApiApplication->doExecute()\n#5 E:\\wamp64\\www\\api_6x\\api\\includes\\app.php(50): Joomla\\CMS\\Application\\CMSApplication->execute()\n#6 E:\\wamp64\\www\\api_6x\\api\\index.php(31): require_once('E:\\\\wamp64\\\\www\\\\a...')\n#7 {main}"
//        }
//    ]
//}

//---------------------------------------------------------------------------
use Finnern\apiByCurlHtml\src\fileNamesLib\fileDateTime;
use stdClass;

/**
 * Error number and message response direct from curl is a
 * curl communication error.
 * Errors from 'service' will be contained in an errors object the response json
 * The error object can be a single error in itself with items
 *   - code -> error code
 *   - title -> ?compressed? error message
 *   - detail -> Stack ...
 * or multiple sub objects of code/title/detail objects
 *
 * Detail: does contain '\n' as string for newlines which may be
 * converted to real PHP_EOL for immediate reading on 'cmd' sreen
 * or in the file
 *
 */
class curlErrResponse
{
// ToDo: Response file class

    // Curl direct error code (connection related)
    public string $errCode = '0';
    public string $errMessage = '';

    // Complete response part
    protected array|string|null $response = null;

    public bool $isHasError = false;
    public bool $isHasCurlError = false;
    public bool $isHasResponseError = false;

    /** @var $oErrors curlErrorObject [] */
    protected $oErrors = [];

    const ERROR_COLLECTION_RELATIVE_PATH = "../../xErrCollections/";      // RIGHT - Works INSIDE of a class definition.
    const ERROR_COLLECTION_NAME = "err_collection";

    public function __construct(string $errorCode = '0', $errMessage = '', string|null $response = null)
    {
        $this->errCode    = $errorCode;
        $this->errMessage = $errMessage;

        $this->response = $response;
        if ($response != null)
        {
            $this->assignResponseWithErrorObject($response);
        }
    }

    /**
     * Handles connection related curl error
     * Keeps only number and message from curl execution
     *
     * @param   string  $errorCode
     * @param           $errMessage
     *
     * @return void
     */
    public function assignCurlError(string $errorCode = '0', $errMessage = '')
    {
        print("    * assignCurlError() " . PHP_EOL);

        $this->errCode    = $errorCode;
        $this->errMessage = $errMessage;

        if ($this->errCode != '0')
        {
            $this->isHasError     = true;
            $this->isHasCurlError = true;
        }

    }

    /**
     * Take complete response and
     *
     * @param   string|null  $response
     *
     * @return void
     */
    public function assignResponseWithErrorObject(array|string|null $response = null)
    {
        print("    * assignResponseWithErrorObject() " . PHP_EOL);

        // 01: $response['errors']
        //     0:array ('code', 'detail', 'title')
        //
        // 02: $response->errors


        // Reduce to errors: single error
        if (!empty($response['error']))
        {
            $this->response = $response;

            //$this->assignResponseError($response['error']);
            $this->assignResponseError($response);
        }

        // Reduce to errors: error object
        if (!empty($response['errors']))
        {
            $this->response = $response;

            $this->assignResponseErrors($response['errors']);
        }
    }

    /**
     * Take the Errors object of the response and assign objects to
     * list
     *
     * @param   \stdClass|null  $responseErrors
     *
     * @return void
     */
    public function assignResponseErrors(array|\stdClass|null $responseErrors)
    {

        if (!empty($responseErrors))
        {
            $this->isHasError         = true;
            $this->isHasResponseError = true;

            // ToDo: detect if errors is direct or as array

            // Single Error
            if (!empty($responseErrors['title']))
            {
                $this->oErrors [] = new curlErrorObject ($responseErrors);
            }
            else
            {
                // Multiple error form
                foreach ($responseErrors as $oCurlError)
                {
                    $this->oErrors [] = new curlErrorObject ($oCurlError);
                }
            }

        }
    }

    /**
     * Take the Error object of the response and assign objects to
     * list
     *
     * @param   \stdClass|null  $responseError
     *
     * @return void
     */
    public function assignResponseError(array|\stdClass|null $responseError)
    {
        if (!empty($responseError))
        {
            $this->isHasError         = true;
            $this->isHasResponseError = true;

            // ToDo: detect if errors is direct or as array

            // Single Error
//            if (!empty($responseErrors['title']))
            {
                $curlErrorObject = new curlErrorObject ();
                $curlErrorObject->assignByStrings('', '', '', $responseError['error']);
                $this->oErrors [] = $curlErrorObject;
            }
//            else
//            {
//                // Multiple error form
//                foreach ($responseErrors as $oCurlError)
//                {
//                    $this->oErrors [] = new curlErrorObject ($oCurlError);
//                }
//            }

        }
    }

    public function allErrorsText(bool $isConvert_slash_N = false)
    {
        $outText = '';

        if (!empty($this->oErrors))
        {
            $outText .= PHP_EOL;
            $outText .= '---------------------------------------------------------' . PHP_EOL;
            $outText .= "!!! >>> Errors found (warning/error) !!!" . PHP_EOL;
            $outText .= '---------------------------------------------------------' . PHP_EOL;
            $outText .= PHP_EOL;

            if ($this->isHasCurlError)
            {
                $outText .= $this->errorCommunicationText() . PHP_EOL;
            }

            if ($this->isHasResponseError)
            {
                $outText .= $this->errorResponseText($isConvert_slash_N) . PHP_EOL;
            }
        }

        return $outText;
    }

    public function errorCommunicationText(): string
    {
        $outText = "";

        $outText .= 'curl communication error' . PHP_EOL;
        $outText .= 'errCode: ' . $this->errCode . PHP_EOL;
        $outText .= 'errMessage: "' . $this->errMessage . '"' . PHP_EOL;

        return $outText;
    }

    /**
     * ToDo: Collect in separate file when filename not given
     * ToDo: where is it/shall it be used
     *
     * @param   string  $responseFileName
     *
     * @return void
     */
    public function createTestErrorFile(string $responseFileName = '', bool $isConvert_slash_N = true)
    {
        //------------------------------------------------------
        // create test response file to keep several errors (to be removed later)
        if (!empty($responseFileName))
        {
            $allErrorsJsonText = $this->allErrorsJsonText($isConvert_slash_N);
            file_put_contents($responseFileName . '.err.json', $allErrorsJsonText);
        }
    }

    /**
     * ToDo: Collect in separate file when filename not given
     * ToDo: where is it/shall it be used
     *
     * @param   string  $responseFileName
     *
     * @return bool
     */
    public function collectError2File()
    {
        // $fileType = 'all';
        $isWritten = false;

        if ($this->isHasCurlError)
        {
            $fileType = 'curl';
        }
        else
        {
            if ($this->isHasResponseError)
            {
                $fileType = 'std';
            }
        }

        //------------------------------------------------------
        // create test response file to keep several errors (to be removed later)
        if ($this->isHasError)
        {
            $header = "";
            $header .= "--------------------------------------------------------" . PHP_EOL;
            $header .= "curlErrResponse: " . fileDateTime::stdFileDateTimeFormatString() . PHP_EOL;

            $allErrorsText = $this->allErrorsText();

            $fileName = __DIR__ . '/' . self::ERROR_COLLECTION_RELATIVE_PATH . self::ERROR_COLLECTION_NAME . '.' . $fileType . '.txt';
            //
            $outText = $header . $allErrorsText;

            $isWritten = file_put_contents($fileName, $outText, FILE_APPEND);
            // $isWritten = file_put_contents($fileName, $outText);
        }

        return $isWritten;
    }

    private function errorResponseText(bool $isConvert_slash_N = false)
    {
        $outText = "";

// ToDO: how to and when to use

        $outText .= 'curl response json errors' . PHP_EOL;

        foreach ($this->oErrors as $oError)
        {
            $outText .= $oError->errorText($isConvert_slash_N) . PHP_EOL;
        }

        return $outText;

    }

    /**
     * Create beautified Json string from response errors
     * On request convert '\n' to PHP_EOL for beter visibility
     *
     * @param   bool  $isConvert_slash_N
     *
     * @return string
     */
    public function allErrorsJsonText(bool $isConvert_slash_N = false)
    {
        $outText = "";

        try
        {

            //--- collect into standard object ------------------------------

            $oErr4Json = new stdClass();

            if (count($this->oErrors) > 0)
            {
                if (count($this->oErrors) == 1)
                {
                    $oErr4Json = $this->oErrors[0]->asArrayObject();

                }
                else
                {
                    foreach ($this->oErrors as $oError)
                    {
                        $oErr4Json [] = $oError->asArrayObject();
                    }
                }
            }

            // convert to json string and beautify
            $oJsonRaw = json_encode($oErr4Json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            // ? convert '\n' -> PHP_EOL
            if ($isConvert_slash_N)
            {
                $oJsonEol = curlErrorObject::convert_slash_N($oJsonRaw);
            }
            else
            {
                $oJsonEol = $oJsonRaw;
            }

            // to text
            $outText .= $oJsonEol;
        }
        catch (\Exception $e)
        {
            echo '!!! Error: allErrorsJsonText Exception: ' . $e->getMessage() . PHP_EOL;
        }


        return $outText;
    }

}