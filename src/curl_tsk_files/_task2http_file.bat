@echo Off
REM ----------------------------------------
REM Transform *.tsk files to *.http file format
REM %1 : path and name to source file
REM %2 : path to target file or path and name to target file
REM ----------------------------------------

Set CmdArgs=

ECHO ----------------------------------------------
@echo Transform *.tsk file to *.http file format
ECHO ----------------------------------------------
@echo.

REM task command http to tsk conversion
Call :AddNextArg -t "task:tsk2httpFile"

REM task command http to tsk conversion
Call :AddNextArg -e "http"


REM --- source file and folder ---------------------------------

if "%1" NEQ "" (
	SET srcFile=%1
) else (
	set srcFile=rsg2_getGallery.http
)

REM source path
Call :AddNextArg -s %srcFile%

REM --- destination file and folder ---------------------------------

if "%2" NEQ "" (
	SET dstFileOrPath=%2
) else (
    REM set dstFileOrPath=""
    set dstFileOrPath=d:\Entwickl\2025\_gitHub\apiByCurlHtml\src\curl_http_files
)

REM source path
Call :AddNextArg -d %dstFileOrPath%

ECHO srcFile: %srcFile%
ECHO dstFileOrPath: %dstFileOrPath%

REM --- Php exe path -------------------------------------------------

REM Path for calling
set ExePath=e:\wamp64\bin\php\php8.4.5\
REM ECHO ExePath: "%ExePath%"

if exist "%ExePath%php.exe" (
    REM path known (WT)
    ECHO ExePath: "%ExePath%"
) else (
    REM Direct call
    ECHO PHP in path variable
    set ExePath=
)

ECHO.
ECHO ----------------------------------------------
"%ExePath%php.exe" --version

ECHO ----------------------------------------------
ECHO.

echo --- "%ExePath%php.exe" ..\task_http_file\tsk2httpFileCmd.php %CmdArgs%
"%ExePath%php.exe" ..\task_http_file\tsk2httpFileCmd.php %CmdArgs%

ECHO ----------------------------------------------
@echo Transform of %srcFile% done
@echo.

pause

GOTO :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg
	Set NextArg=%*
	Set CmdArgs=%CmdArgs% %NextArg%
	ECHO  '%NextArg%'
GOTO :EOF


