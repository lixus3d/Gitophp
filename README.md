Gitophp
=======

Yet another private github like interface in php.  
<i>NOTE : It's actually just in an early beta stage and it's absolutly not intended to be use in production context.</i>

Install
-------

Gitophp rely on PHP 5.3+ (with short_tag) and the Smally (v1) framework behind an Apache server with mod_rewrite.  
<i>Actually other httpd service must be compatible but not tested.</i> 

1. Clone Gitophp from Github in your Apache folder
> git clone git://github.com/lixus3d/Gitophp.git  

2. Copy configuration file
> cd Gitophp  
> cp config.php.example config.php  

3. Edit configuration file to match your need  
> $config['git']['paths']['cmd'] 				= 'git.exe';  
> $config['git']['paths']['repositories']		= 'c:\\pathtorepositories\\';  

4. Clone Smally from Github in a library folder  
<i>By default library folder must be at the same level of Gitophp folder</i>  
> cd ..  
> mkdir library  
> cd library  
> git clone git://github.com/lixus3d/Smally.git  

5. You must now have this  
> --Gitophp  
> ----<i>some folders</i>  
> ----config.php  
> --Library  
> ----Smally  
> ------<i>some files</i>

6. Access to Gitophp from your favorite browser  
> http://localhost/Gitophp/public/  

7. [optional] Modify folders  
You can edit some paths in boot.php if they don't match your needs by default
> cd Gitophp
> vim public/boot.php  

Todo
----
- Cleaning repo.php template and cutting it in sub part 
- Get commits messages and commits age for each item
- And many more 
