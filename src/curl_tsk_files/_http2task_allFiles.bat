@echo Off
REM ----------------------------------------
REM Transform all *.http files to *.http file format 
REM %1 : path to target file 
REM %2 : path to source folder
REM ----------------------------------------

CLS 

REM setlocal EnableExtensions DisableDelayedExpansion


REM --- destination file and folder ---------------------------------

if "%1" NEQ "" (
	SET dstPath=%1
) else (
    REM set dstPath=""
    set dstPath=d:\Entwickl\2025\_gitHub\apiByCurlHtml\src\curl_tsk_files\new
)

REM --- source folder folder ---------------------------------

if "%2" NEQ "" (
	SET srcPath=%2
) else (
    set srcPath="."
)

ECHO "for %%i in (%srcPath%\*.http) do CALL :http2tskFile %%i"
for %%i in (%srcPath%\*.http) do CALL :http2tskFile %%i 


@echo Transform all *.http files to *.http file format 
@echo.

REM Call transformation on each matching file
REM for /f %%f in ('dir /b *.http	') do ( 

for %%i in (%srcPath%\*.http) do CALL :http2tskFile %%i 

REM endlocal

@ECHO -----------------------------------------------
@Echo all *.http files are transferred
@ECHO -----------------------------------------------
REM pause

goto :EOF

REM ----------------------------------------
REM transform all *.http files into *.html file (in other folder)
REm ----------------------------------------

:http2tskFile
Rem expected files and folders befor deleting .\progs\*.hel ...

ECHO http2tskFile: "%1"

REM if not exist %1\nul goto :EOF

SET NO_PAUSE=true
CALL _http2task_file.bat %1 %dstPath%
SET NO_PAUSE=

goto :EOF
