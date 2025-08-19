@echo Off
REM ----------------------------------------
REM Transform *.tsk files to *.http file format 

@echo Transform all *.tsk files to *.http file format 
@echo.

REM --- files and folders -------------------------------------------------


if "%1" NEQ "" (
	SET srcFile=%1
) else (
	set srcFile=rsg2_getGallery.tsk
)

if "%2" NEQ "" (
	SET srcPath=%2
) else (
REM	set srcPath=".\"
REM	set srcPath=.\
	set srcPath=d:\Entwickl\2025\_gitHub\apiByCurlHtml\src\curl_tasks_tsk
)

if "%3" NEQ "" (
	SET dstFile=%3
) else (
	set dstFile=%srcFile%.http
)

if "%4" NEQ "" (
	SET dstPath=%4
) else (
	set dstPath=%srcPath%
)

ECHO srcFile: %srcFile%
ECHO srcPath: %srcPath%
ECHO dstFile: %dstFile%
ECHO dstPath: %dstPath%

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

"%ExePath%php.exe" --version

ECHO ----------------------------------------------
ECHO.

echo --- "%ExePath%php.exe" ..\task_http_file\tsk2httpFileCmd.php -p %srcPath% -s %srcFile% -x %dstPath% -y %dstFile%
"%ExePath%php.exe" ..\task_http_file\tsk2httpFileCmd.php -p %srcPath% -s %srcFile% -x %dstPath% -y %dstFile%

ECHO ----------------------------------------------
@echo Transform of %srcFile% done
@echo.

GOTO :EOF


