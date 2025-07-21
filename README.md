# apiByCurlHtml

WIP 2025.07.19

*php scripts to load *.html file for curl calls like in PHP Storm
*.tsk files are used to fill out additional data 

## General structure of task function / creation

Sources in src folder include 'Task'.php, 'Task'Cmd.php, 'Task'.bat, 'Task'.tsk, files  

* 'Task'.php    : Executing the task(s)
* 'Task'Cmd.php : Handling commandline parameter for the task
* 'Task'.[README.md](../apiByCurlHtml/README.md)bat    : Call a 'Task'Cmd.php  with tasks and options 
* 'Task'.tsk    : Commandline task and options as lines in file

### Interface to scripts

- Command line options with '-'
  Example: ``` /srcRoot="./../../RSGallery2_J4"```    
  (sorry no spaces before/after '=' yet) 
- Task lists  
  Example: ```task:exchangeAll_actCopyrightYear```
- Task files 
  Containing task name and option in lines
- Options file
  containing list of Options in files
  
### Task and option files

Options and task lists can be loaded from a common file
In each 'Task'Cmd.php are prepared commented 'command/option lines' as example 
Also Example bats exist in the src folder


## Tasks code

### Tasks base code

Code in folder tasksLib handles complete tasks and option interpretation into objects

### *lib folders
WIP
Each folder handles a special task 



##  tasks related

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





