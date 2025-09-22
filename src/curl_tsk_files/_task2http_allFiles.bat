@echo Off
REM ----------------------------------------
REM Transform all *.tsk files to *.http file format 
REM %1 : path to target file 
REM ----------------------------------------

REM --- destination file and folder ---------------------------------

if "%1" NEQ "" (
	SET dstPath=%2
) else (
    set dstPath=""
)



@echo Transform all *.tsk files to *.http file format 
@echo.

REM Call transformation on each matching file
REM for /f %%f in ('dir /b *.tsk	') do ( 

for %%i in (*.tsk) do CALL :tsk2httpFile %%i

@ECHO -----------------------------------------------
@Echo all *.tsk files are transferred
@ECHO -----------------------------------------------
REM pause

goto :EOF

REM ----------------------------------------
REM transform all *.tsk files into *.html file (in other folder)
REm ----------------------------------------

:tsk2httpFile
Rem expected files and folders befor deleting .\progs\*.hel ...

ECHO tsk2httpFile: "%1"

REM if not exist %1\nul goto :EOF

CALL _task2http_file.bat %1 %dstPath%

goto :EOF
