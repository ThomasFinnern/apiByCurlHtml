@echo Off
REM ----------------------------------------
REM Transform all *.tsk files to *.http file format 
REM ----------------------------------------

@echo Transform all *.tsk files to *.http file format 
@echo.

REM Call transformation on each matching file
REM for /f %%f in ('dir /b *.tsk	') do ( 

for %%i in (*.tsk) do CALL :http2tskFile %%i

@ECHO -----------------------------------------------
@Echo all *.tsk files are transferred
@ECHO -----------------------------------------------
REM pause

goto :EOF

REM ----------------------------------------
REM transform all *.tsk files into *.html file (in other folder)
REm ----------------------------------------

:http2tskFile
Rem expected files and folders befor deleting .\progs\*.hel ...

ECHO http2tskFile: "%1"

REM if not exist %1\nul goto :EOF

CALL _task2http_file.bat %1

goto :EOF
