<?php
include 'vendor/autoload.php';

use App\resources\BackerDB\handler as BackerDB;

try {
	$_ENV = new App\Configurations;
	$Backer = new BackerDB;

	$Backer->foreach(function($db){
		$db->driver('Local')->putBackup();	// Local backup
		//$db->driver('S3')->putBackup();			// S3 backup
	});

	/*$Backer = new BackerDB($_ENV->access('S3'), $_ENV->access('DB'));

	foreach ($_ENV->databases() as $db):
		$Backer->Server['DB'] = $db['db_name'];
		$Backer->Bucket = $db['bucket'];

		$Backer->mysqldump()																	// Dump database selected 
						->touch_bucket()															// Create Bucket if not exist
							->putBackupInS3()														// Send backup to s3
								->limitLocalBackups($db['limits']['local'])		// Remove backups in local
									->limitS3Backups($db['limits']['s3']);	// Remove backups in S3
	endforeach;*/
//} catch(Aws\Exception\AwsException $e) { print $e->getMessage(); }
} catch(Exception $e) { print $e->getMessage(); }