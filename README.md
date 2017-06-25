# spider_task3

Server routes: 
for ascessing the database http://localhost/phpmyadmin
for ascessing the homepage where the credentials have to be entered to login in or create a new user http://localhost/forum/connect.php

Step 1: Installation  of the WAMP server
#Download the WAMP server package from this page http://www.wampserver.com/en/#download-wrapper
#Choose the director you wish to store it under(wamp64 in the C drive), the default browser (Mozilla Firefox)and the text editor ( Sublime) by choosing their corresponing .exe files by also reffering to the wordpress link below.
https://make.wordpress.org/core/handbook/tutorials/installing-a-local-server/wampserver/
#Also change the username and password in the php.ini file by changing the username to 'root' and password = ''
#If you cannot connect: invalid settings error message, then you’ll need to edit the C:\wamp\apps\phpmyadmin3.5.1\config.inc.php file in a plain text editor (your version number may be different), and ensure this option is set to true:
  $cfg['Servers'][$i]['AllowNoPassword'] = true;
#Make changes in the php.ini file:
        1.  Set level of error reporting – remove the ; at beginning of line to enable:
          error_reporting = E_ALL ^ E_DEPRECATED (~line 112)
        2.  Log PHP errors – remove the ; at beginning of line to enable:
          error_log = "c:/wamp/logs/php_error.log" (~line 639)
        3. Increase maximum size of POST data that PHP will accept – change the value:
          post_max_size = 50M (~line 734)
        4. Increase maximum allowed size for uploaded files – change the value:
          upload_max_filesize = 50M (~line 886)
#Check to see if the server is configured by typing http://localhost . If you get a WAMP server homepage then it is configured
#Now type http://localhost/phpmyadmin to enter the username as 'root' and password ''  to get the MYSQL database page
#Now click on the WAMP desktop icon and you should be able to see the WAMP link go green and then you're all set to go.

Step2: Creating .php files in the wamp64 directory in the C: drive
#go to www directory in the wamp64 folder and create a directory called forum. This will be the directory we will store all our .php file from now on
#Create the first file connect.php  and to view the file extablish a link to the server using mysqli_connect() and then type http://localhost/forum/connect.php to view your file.

Step 3: Creating the MYSQL database
#Enter the username and password and create a new database called "first_db"
#Over the course of this task I will be using 3 tables in the database
#Table 1 : *Create a table called user_info that will store the details of the users and their passwords, their ascess levels and if they are a moderated user
Here a moderated admin level user will have the value of moderate_status as 0 and if he is a non moderated one his moderate_status =1. 
ascess_level value is 0 for a student, 1 for an professor/admin, 2 for a Class Representative. For table one we are creating an admin with an admin ascess level with the password hashed by MD5.

CREATE TABLE `user_info` ( 
           `id` INT NOT NULL AUTO_INCREMENT , 
           `username` TEXT NOT NULL ,
           `password` TEXT NOT NULL ,
           `moderate_status` INT NULL , 
           `ascess_level` INT NOT NULL ,
           PRIMARY KEY (`id`));
           
 
INSERT INTO 'user_info' (username, password, ascess_level, moderate_status) VALUES ('admin', MD5('midas'), '1', '1');   

#Table 2: To create a table called 'content' that will store all the notices, it will have columns id, title, info, image, priority
If the value of priority is 0 then it is a low priority one, if it is 1 then medium and 2 for high

CREATE TABLE  'content` ( 
          `id` INT NOT NULL AUTO_INCREMENT , 
          `title` TEXT NOT NULL ,
          `info` TEXT NOT NULL , 
          `image` LONGBLOB NOT NULL ,
          `priority` INT NOT NULL , 
          PRIMARY KEY (`id`));

#Table 3: To create a table called 'approval' that will store that notices that are created by moderated users so are pending for approval. This is similar to 'content' in structure
         
CREATE TABLE  'approval` ( 
          `id` INT NOT NULL AUTO_INCREMENT , 
          `title` TEXT NOT NULL ,
          `info` TEXT NOT NULL , 
          `image` LONGBLOB NOT NULL ,
          `priority` INT NOT NULL , 
          PRIMARY KEY (`id`));
