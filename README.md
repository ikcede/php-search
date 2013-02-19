php-search
==========

A Basic Search API for PHP
v. 0.2

Set up for a basic search API with a Search Model capable of constructing complex queries 
for a mysql database.

As of v. 0.2, 
- Support for int queries (fixed a bug where >, <, etc. didn't work)
- Wildcards for LIKE queries (using *)
- Query limit
- Searches on multiple tables, all through one php file
- Returns empty on expressionless search (because we don't want people accidentally getting the whole table)

Installation: 
1. Configure the search database with your username and password (go to Models/dbconfig.mysql.php)
2. Go to search.php:

	$all_fields = array(

	);
	...
	...
	$search = new Search("test",$limit);

There, add fields and change the parameter "test" to your table name.

Now you can make GET requests to .../search.php and it will search your database and return a JSON.

Ex. $.get(".../search.php",{id:"0"},function(html) {alert(html)});
Where fields is configured $fields = array("id"=>"="); will return rows in the table where id = 0

Param: search_type -- will define which table you're searching on and the fields and operators used
limit -- will define the limit of search