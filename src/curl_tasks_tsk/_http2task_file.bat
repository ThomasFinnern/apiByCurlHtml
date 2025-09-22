@echo Off
REM ----------------------------------------
REM Transform *.http file to *.tsk file format
REM %1 : path and name to source file
REM %2 : path to target file or path and name to target file
REM %3 : joomla token file or path
REM %4 : response file path
REM ----------------------------------------

Set CmdArgs=

ECHO ----------------------------------------------
@echo Transform *.http file to *.tsk file format
ECHO ----------------------------------------------
@echo.

REM task command http to tsk conversion
Call :AddNextArg -t "task:http2tskFile"

REM task command http to tsk conversion
Call :AddNextArg -e "tsk"


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
    set dstFileOrPath=""
)

REM source path
Call :AddNextArg -d %dstFileOrPath%

REM --- joomla token file or path ---------------------------------

if "%2" NEQ "" (
	SET jt_FileOrPath=%2
) else (
    set jt_FileOrPath=""
)

REM 
Call :AddNextArg -j %srcFile%


REM %4 : response file path

REM --- destination file and folder ---------------------------------

if "%2" NEQ "" (
	SET resFileOrPath=%2
) else (
    set resFileOrPath=""
)

REM 
Call :AddNextArg -r %resFileOrPath%


REM --- tell found options bat ------------------------

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


