<?php
// ToDo: Make a test class using this 
//namespace Finnern\apiByCurlHtml\lib;
namespace Finnern\apiByCurlHtml\src\lib;

//use Finnern\apiByCurlHtml\src\lib\dirs;

$HELP_MSG = <<<EOT
    >>>
    class AutoSrcDstPathFileNames

    Take two file path definitions and create separate instances for path and 
    filename (srcPath, srcFileName, dstPath, dstFileName)
    The definitions for the file name may come inadvertently from a complete
    filepath in the (src/dst) file name variable. This is checked and matched 
    accordingly 
    
    The destination path will be build automatically from the source when 
    only the path or nothing is given           

    The destination file extension may be set separately
    <<<
    EOT;


class AutoSrcDstPathFileNames
{
    public string $srcPathName;
    public string $srcFileName;
    public string $srcExtension;

    public string $dstPathName;
    public string $dstFileName;
    public string $dstExtension;

    public function __construct(string $srcPathFileName = "", string $dstPathFileName = "")
    {

        if ($srcPathFileName != "") {
            $this->assignFilePaths($srcPathFileName, '', $dstPathFileName, '');
        }
    }

    public function init(): void
    {
        $this->srcPathName = '';
        $this->srcFileName = '';
        $this->srcExtension = '';

        $this->dstPathName = '';
        $this->dstFileName = '';
        $this->dstExtension = '';
    }

    public function assignFilePaths(string $srcFileName = "", string $srcPathName = "",
                                    string $dstFileName = "", string $dstPathName = "",
                                    string $dstExtension = ""): bool
    {
        $hasError = false;

        // reset all variables
        $this->init();

        //  [dirname], [basename], [extension]
        $srcFileInfo = pathinfo($srcFileName);

        $this->srcFileName = $srcFileInfo['filename'] ?? '';
        $this->srcExtension = $srcFileInfo['extension'] ?? '';

        // src Path not given -> use from file name
        if (empty($srcPathName)) {
            $this->srcPathName = $srcFileInfo['dirname'] ?? '';
        } else {
            $this->srcPathName = $srcPathName ?? '';
        }

        //  [dirname], [basename], [extension]
        $dstFileInfo = pathinfo($dstFileName);

        $this->dstFileName = $dstFileInfo['filename'] ?? '';
        $this->dstExtension = $dstFileInfo['extension'] ?? '';

        // src Path not given -> use from file name
        if (empty($dstPathName)) {
            $this->dstPathName = $dstFileInfo['dirname'] ?? '';
        } else {
            $this->dstPathName = $dstPathName;
        }

        $hasError = $this->updateDestinationName($dstExtension);

        return $hasError;
    }


    public function setDstFileExtension(string $extension): void
    {
        $this->dstExtension = $extension;
    }

    public function getSrcPathFileName(): string
    {
        $pathFileName = dirs::joinDirPath($this->srcPathName, $this->srcFileName . '.' . $this->srcExtension);
        return $pathFileName;
    }

    public function getDstPathFileName(): string
    {
        $pathFileName = dirs::joinDirPath($this->dstPathName, $this->dstFileName . '.' . $this->dstExtension);
        return $pathFileName;
    }

    /**
     * Copy missing parts in the destination from the source
     *
     * @param string $dstExtension
     * @return void
     */
    private function updateDestinationName(string $dstExtension): bool
    {
        $hasError = false;

        // use given extension name
        if ($dstExtension != '') {
            $this->dstExtension = $dstExtension;
        }

        // Use src if not defined
        if (empty($this->dstExtension)) {
            $this->dstExtension = $this->srcExtension;
        }
        // Use src if not defined
        if (empty($this->dstPathName)) {
            $this->dstPathName = $this->srcPathName;
        }
        // Use src if not defined
        if (empty($this->dstFileName)) {
            $this->dstFileName = $this->srcFileName;
        }

        // complete empty destination is not allowed
        if ($this->dstPathName == $this->srcPathName
            && $this->dstFileName == $this->srcExtension
            && $this->dstExtension == $this->srcExtension) {

            print (PHP_EOL . "!!! Error: Destination 'file name', 'path' and 'extension' are now same as source definition."
                . " At least part of the file name must be different to not overwrite the source file" . PHP_EOL . PHP_EOL);
            $hasError = true;
        }

        return $hasError;
    }

    public function text(): string
    {
        $outTxt = "=== AutoSrcDstPathFileNames ===" . PHP_EOL;

        $outTxt .= "--- Path/Filename/Extension parts ---" . PHP_EOL;
        $outTxt .= "srcPathName: " . $this->srcPathName . PHP_EOL;
        $outTxt .= "srcFileName: " . $this->srcFileName . PHP_EOL;
        $outTxt .= "srcExtension: " . $this->srcExtension . PHP_EOL;

        $outTxt .= "dstPathName: " . $this->dstPathName . PHP_EOL;
        $outTxt .= "dstFileName: " . $this->dstFileName . PHP_EOL;
        $outTxt .= "dstExtension: " . $this->dstExtension . PHP_EOL;

        $outTxt .= "--- Path/Filename/Extension result ---" . PHP_EOL;
        $outTxt .= "srcPathFileName: " . $this->getSrcPathFileName() . PHP_EOL;
        $outTxt .= "dstPathFileName: " . $this->getDstPathFileName() . PHP_EOL;

//        $outTxt .= ": " . $this-> . PHP_EOL;
//        $outTxt .= ": " . $this-> . PHP_EOL;

        return $outTxt;
    }


} // class

