<?php
	//Load required elements to log to connected server
	require 'vendor/autoload.php'; 

	$client = new MongoDB\Client('mongodb://localhost:27017',
	[
		'username' => 'michel',
		'password' => 'ange',
	]);

	//specify the name of the database ($client->DBNAME)
	$database = $client->tanguyDB;
?>