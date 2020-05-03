<?php 
# composer dependencies 
require __DIR__.'/vendor/autoload.php'; 
 
$config = [ 
	's3-access' => [ 
		'key' => 'AWS KEY HERE', 
		'secret' => 'AWS SECRET HERE', 
		'bucket' => 'BUCKET NAME', 
		'region' => 'REGION', 
		'version' => 'latest', 
		'acl' => 'public-read', 
		'private-acl' => 'private' 
	] 
]; 
 
# initializing s3 
$s3 = Aws\S3\S3Client::factory([ 
	'credentials' => [ 
		'key' => $config['s3-access']['key'], 
		'secret' => $config['s3-access']['secret'] 
	], 
	'version' => $config['s3-access']['version'], 
	'region' => $config['s3-access']['region'] 
]); 
?> 