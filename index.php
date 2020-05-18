<?php
include 'vendor/autoload.php';

use App\resources\BackerDB\handler as BackerDB;

try {
	$_ENV = new App\Configurations;
	$Backer = new BackerDB;

	$Backer->foreach(function($db){
		$db->driver('Local')->putBackup();	// Local backup
		$db->driver('S3')->putBackup();			// S3 backup
	});
} catch(Exception $e) { print $e->getMessage(); }