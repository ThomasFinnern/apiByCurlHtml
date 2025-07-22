<?php
/**
 * @package         apiByCurlHtml
 * @subpackage      apiByCurlHtml
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2025-2025 Finnern
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */

// namespace Finnern\apiByCurlHtml\autoload;

/**
 * An example of a project-specific implementation.
 *
 * @param string $class The fully-qualified class name.
 * @return void
 */
function CurlApi_autoloader(string $class) {

    $vendorProject = 'Finnern\apiByCurlHtml';

    // Remove vendor/project Finnern\apiByCurlHtml\
    $classPath = substr($class, strlen($vendorProject));


    // replace namespace separators with directory separators in the relative 
    // class name, append with .php
    $class_path = str_replace('\\', '/', $classPath);
    
    $file =  __DIR__ . '/../..' . $class_path . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    } else {

        print ("Class $class not found\n");
        print ("File $file not found\n");
        $debugStop = 'error';
    }
}

spl_autoload_register('CurlApi_autoloader');

