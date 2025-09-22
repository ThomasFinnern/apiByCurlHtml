# apiByCurlHtml

WIP 2025.07.22

*.php scripts to load *.http files for curl calls like in PHP Storm
*.tsk files are used to define the curl task and their options. 
*.http files can be used in PhpStorm to debug API calls if they are referenced in the debug setup

Php file CurlApi_HttpCallCmd.php is the startpoint from the commandline and accepts with parameter '-f' as *.tsk file telling the task options and starts it

For example a external call shall issue a HTTP get on RSGallery2 API URL:  
In the task file *.tsk the command and parameter for the call is defined as Joomla user token, the API path and the base url.

The CurlApi_HttpCallCmd.php can now be called with -f *.tsk file and it will issue the call.
More examples see folder ```src/curl_tsk_files```

##  curl tasks supported

GET, POST, PUT, PATCH, DELETE

https://mglaman.dev/blog/post-or-put-patch-and-delete-urls-are-cheap-api-design-matters

Generally, HTTP methods represent the following actions:

GET retrieves data.
POST creates data.
PUT updates data entirely.
PATCH allows partially updating data.
DELETE removes data.

APIs definitions use either but not PUT and PATCH.

Joomla ? PUT or PATCH ?

## *.tsk files 

*.tsk files are used to define the curl task and their options. 

curl_tsk_files

Example:
```
task:get
/baseUrl="http://127.0.0.1/joomla5x/api/index.php"
/apiPath="v1/rsgallery2/galleries"
/joomlaTokenFile="d:\Entwickl\2025\_gitHub\xTokenFiles\token_joomla5x.txt"
/responseFile="d:\Entwickl\2025\_gitHub\apiByCurlHtml\src\results/rsg2_getGalleries.json"
```

The Joomla token file is hidden in a parallel path to avoid being uploaded to GitHub

The token may be taken directly with option /joomlaToken="<token>"
A double slash at the beginning defines a comment line.  
Example: ```// This is a comment line ...```

## *.tsk files option 

Attention: Actually no space onleft or right side of '='. Example  /option=option value

### Task line
The first line is the task definition (here subtask)
task: get, post, put, patch, delete

### Option lines 

A option starts with a slash the option token and optional a value
Example: ```/apiPath="v1/rsgallery2/galleries"```

**Defined options for curl tasks**

**WIP**

| Option | description | 
|---|---|
| /baseUrl | % | 
| /apiPath | % | 
| /httpFile | % | 
| /joomlaToken | % | 
| /joomlaTokenFile | % | 
| /accept | % | 
| /contentType | % | 
| /responseFile | % | 
| /dataFile | % | 
| /page_offset | % | 
| /page_limit | % | 


## *.http files 

*.tsk files can be converted to *.http files. Destination prepared: curl_http_files
*.http files can be used in PhpStorm to debug API calls if they are referenced in the debug setup

Example:
```
###
GET  http://127.0.0.1/joomla5x/api/index.php/v1/rsgallery2/galleries
Accept: application/vnd.api+json
Content-Type: application/json
X-Joomla-Token: "c2hhMjU2OjI5MzphYTZhMTcwZTY2ODM1MTZhMmNiYzlkZDg0NjE5NzkxYTZkYThhNTJjODFhZTVkNWViYmZmMjljMmY2ZTQ4NGYz"
```

Sadly the joomla token must be present but we do not want to upload them to github so the files are restricted in .gitignore

## curl tasks code

### Tasks base code

Code in folder tasksLib handles complete tasks and option interpretation into objects

### *lib folders
WIP
Each folder handles a special task


# Task system

The script system (above) is relaying on a library to extract task and options from the *.tsk file

## General structure of task function / creation

Sources in src folder use php files in following naming: 'Task'.php, 'Task'Cmd.php, 'Task'.bat, 'Task'.tsk, files  

* 'Task'.php    : Executing the task(s)
* 'Task'Cmd.php : Handling commandline parameter for the task
* 'Task'.bat    : Call a 'Task'Cmd.php  with tasks and options 
* 'Task'.tsk    : Commandline task and options as lines in file

### Interface to scripts

- Command line options with '/'
  Example: ``` /srcRoot="./../../RSGallery2_J4"```    
  (sorry no spaces before/after '=' yet) 
- Task definition line 
  One line containing the complete *.tsk file  
  Example: ```task:exchangeAll_actCopyrightYear```
- Task definition file
  Containing task name and option in sveral lines
- Options file
  Containing list of Options in file. This option can be called multiple times for different files
  This helps to include a option definition (set) into multiple similar tasks or even combine several options
  
### Task and option files

Options and task lists can be loaded from a common file
In each 'Task'Cmd.php are prepared commented 'command/option lines' as example 
Also Example bats exist in the src folder


---
=======================================================================================
# ToDo:
* Short description for each Task
* Short description for base option see above 
* Tasks and Options as package

=======================================================================================

