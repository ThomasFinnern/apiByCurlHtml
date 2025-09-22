<?php

namespace Finnern\apiByCurlHtml\src\curl_call;

// Examples
//------
//    "code": 500,
//    "title": "Internal server error",
//    "detail": "RuntimeException: There was an error deleting the item. in E:\\wamp64\\www\\joomla5x\\libraries\\src\\MVC\\Controller\\ApiController.php:305\nStack trace:\n#0 E:\\wamp64\\www\\joomla5x\\libraries\\src\\MVC\\Controller\\BaseController.php(730): Joomla\\CMS\\MVC\\Controller\\ApiController->delete()\n#1 E:\\wamp64\\www\\joomla5x\\libraries\\src\\Dispatcher\\ApiDispatcher.php(61): Joomla\\CMS\\MVC\\Controller\\BaseController->execute('delete')\n#2 E:\\wamp64\\www\\joomla5x\\libraries\\src\\Component\\ComponentHelper.php(361): Joomla\\CMS\\Dispatcher\\ApiDispatcher->dispatch()\n#3 E:\\wamp64\\www\\joomla5x\\libraries\\src\\Application\\ApiApplication.php(433): Joomla\\CMS\\Component\\ComponentHelper::renderComponent('com_rsgallery2')\n#4 E:\\wamp64\\www\\joomla5x\\libraries\\src\\Application\\ApiApplication.php(116): Joomla\\CMS\\Application\\ApiApplication->dispatch()\n#5 E:\\wamp64\\www\\joomla5x\\libraries\\src\\Application\\CMSApplication.php(304): Joomla\\CMS\\Application\\ApiApplication->doExecute()\n#6 E:\\wamp64\\www\\joomla5x\\api\\includes\\app.php(50): Joomla\\CMS\\Application\\CMSApplication->execute()\n#7 E:\\wamp64\\www\\joomla5x\\api\\index.php(31): require_once('E:\\\\wamp64\\\\www\\\\j...')\n#8 {main}"
//  }
//------
// "errors": [
//        {
//            "title": "Resource not found",
//            "code": 404
//        }
//------
//     "errors": [
//        {
//            "code": 500,
//            "title": "Internal server error",
//            "detail": "RuntimeException: There was an error deleting the item. in E:\\wamp64\\www\\joomla5x\\libraries\\src\\MVC\\Controller\\ApiController.php:305\nStack trace:\n#0 E:\\wamp64\\www\\joomla5x\\libraries\\src\\MVC\\Controller\\BaseController.php(730): Joomla\\CMS\\MVC\\Controller\\ApiController->delete()\n#1 E:\\wamp64\\www\\joomla5x\\libraries\\src\\Dispatcher\\ApiDispatcher.php(61): Joomla\\CMS\\MVC\\Controller\\BaseController->execute('delete')\n#2 E:\\wamp64\\www\\joomla5x\\libraries\\src\\Component\\ComponentHelper.php(361): Joomla\\CMS\\Dispatcher\\ApiDispatcher->dispatch()\n#3 E:\\wamp64\\www\\joomla5x\\libraries\\src\\Application\\ApiApplication.php(433): Joomla\\CMS\\Component\\ComponentHelper::renderComponent('com_rsgallery2')\n#4 E:\\wamp64\\www\\joomla5x\\libraries\\src\\Application\\ApiApplication.php(116): Joomla\\CMS\\Application\\ApiApplication->dispatch()\n#5 E:\\wamp64\\www\\joomla5x\\libraries\\src\\Application\\CMSApplication.php(304): Joomla\\CMS\\Application\\ApiApplication->doExecute()\n#6 E:\\wamp64\\www\\joomla5x\\api\\includes\\app.php(50): Joomla\\CMS\\Application\\CMSApplication->execute()\n#7 E:\\wamp64\\www\\joomla5x\\api\\index.php(31): require_once('E:\\\\wamp64\\\\www\\\\j...')\n#8 {main}"
//        }
//------
//
//------
//
//------
//

class json_error
{
    // Keep it simple , keep complete object and go through array on print

    protected object $error;

    public function __construct(object $jsonError)
    {
        $this->assignError($jsonError);
    }

    public function assignError(object $jsonError)
    {
        $this->error = $jsonError;
    }

    public function text(): string
    {
        $outTxt = "";

        //$outTxt .= '----------------------------------------' . PHP_EOL;
        foreach ($this->error as $key => $value) {

            $valueText = str_replace("\n", PHP_EOL, $value);

            $outTxt .= '"' . $key . '": "' . $valueText . '"' . PHP_EOL;
        }

        return $outTxt;
    }

}


