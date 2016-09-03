# dwbc
A very basic personal web app for book club's use. PHP, Apache, MySQL.
I am new to php. It's almost beyond me why I am doing this. :)

====================================
How to deploy to a server

- include/global.inc.php
 - replace global constants value.

<placeholder>

====================================
tutorials:
http://www.w3schools.com/php/default.asp
https://github.com/BranMci/simplePHPUserApp
http://buildinternet.com/2009/12/creating-your-first-php-application-part-1/

https://phpdelusions.net/pdo#prepared

references
http://php.net/docs.php

====================================
Questions:

1. the code in this tutorial:  
http://buildinternet.com/2009/12/creating-your-first-php-application-part-1/  
There is a DB class. I thought it was supposed to handle all DB interactions (it has functions to select, update, insert). However, in other classes (for example the userTools class), some of the DB actions are still performed by a mysql query.  
Design-wise, I don't understand it (why have a DB class that does not handle all DB actions? If other code needs to access DB, they sometimes can use DB object, sometimes have to query DB by themselves?)  
Effect-wise, would this open more connections to DB than needed?

2. same tutorial as above:  
Why does every page need to run DB->connect()? (in the global include php file.) (I haven't used mysqli.)  
As I'm copying this code but re-writing using PDO, I'm creating a new PDO DB handler in global.inc.php, PDO seems to be doing everything the DB.class in the example does. So I won't have a DB class.

3. why does my example code serialize the user object to be stored in $\_SESSION variable?  
I happen to be curious and tried if I could save an object - I can!  
Googled and I got this:
http://stackoverflow.com/questions/132194/php-storing-objects-inside-the-session  
I don't understand HTTP being 'stateless' (that is why we need to store states in session variable/cookie).  
I take it that I can just store objects in session variable.

====================================
PHP Cheatsheet

1. variable names are case sensitive!

2. Associative Array syntax
```php
$data = array("Obi-Wan Kenobi"=>"blue","Anakin Skywalker"=>"blue","Yoda"=>"green","Mace Windu"=>"purple")
foreach($data as $jedi => $lightsaber_color){
  echo $jedi . " - " $lightsaber_color . ".<br>";
}
```
