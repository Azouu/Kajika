<?php
	// load required elements to log to connected server
	require 'vendor/autoload.php'; 

	$client = new MongoDB\Client('mongodb://localhost:27017',
	[ // if there is no authentification required, leave the array empty
	  /* 'username' => 'root',
		'password' => '',
		"connectTimeoutMS" => 60000
		*/
	]);

	// specify the name of the database ($client->DBNAME)
	$database = $client->tanguyDB;
?>