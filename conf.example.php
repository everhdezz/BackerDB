<?php
return [
	'ACCESS' => [
		'DB' => [
			'HOST' => 'localhost',
			'USER' => 'root',
			'PASS' => ''
		],
		'S3' => [
			'KEY' => '',
			'SECRET' => '',
			'REGION' => 'us-west-2',
			'VERSION' => 'latest'
		],
	],
	'DATABASES' => [
		[ 'db_name' => 'master', 'bucket' => 'Backer/master', 'limits' => [ 'local' => 7, 's3' => 7 ] ]
	]
];