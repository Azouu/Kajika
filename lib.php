<?php
	// load required elements to log to connected server
	require 'vendor/autoload.php'; 
	// if there is no authentification required, leave the array empty
	$client = new MongoDB\Client('mongodb://localhost:27017', []);
	// specify the name of the database ($client->DBNAME)
	$database = $client->tanguyDB;
?>