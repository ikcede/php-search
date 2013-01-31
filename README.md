php-search
==========

A Basic Search API for PHP
v. 0.1

Set up for a basic search API with a Search Model capable of constructing complex queries 
for a mysql database.

Installation: 
1. Configure the database with your username and password (go to Models/dbconfig.mysql.php)
2. Go to search.php:

	$fields = array(
	

	);

	$search = new Search("test");

There, add fields and change the parameter "test" to your table name.

Now you can make GET requests to .../search.php and it will search your database and return a JSON.

Ex. $.get(".../search.php",{id:"0"},function(html) {alert(html)});
Where fields is configured $fields = array("id"=>"="); will return rows in the table where id = 0;