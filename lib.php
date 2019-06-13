<?php
	//Load required elements to log to connected server
	require 'vendor/autoload.php'; 
	$client = new MongoDB\Client("mongodb://localhost:27017");
	$database = $client->tanguyDB;
?>