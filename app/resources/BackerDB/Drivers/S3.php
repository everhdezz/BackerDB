<?php

namespace App\resources\BackerDB\Drivers;

use App\resources\BackerDB\BackerDBException;
use Aws\S3\S3Client;

class S3
{
  private $parent;
  private $S3Client;
  private $bucket;

  function __construct($parent){
    $this->parent = $parent;
    $this->bucket = explode('/', $this->parent->bucket);

    $this->S3Client = new S3Client(array(
        'version'     => $_ENV->access('S3')['VERSION'],
        'region'      => $_ENV->access('S3')['REGION'],
        'credentials' => array(
            'key'         => $_ENV->access('S3')['KEY'],
            'secret'      => $_ENV->access('S3')['SECRET']
        )
    ));

    $this->touch_bucket();
  }

  function putBackup(){
    $this->result = $this->S3Client->putObject(array(
        'Bucket' => $this->bucket[0],
        'Key' => $this->getS3Directory() . $this->parent->dump_name,
        'SourceFile' => $this->parent->result_file,
    ));

    print $this->result;

    $this->limitBackups();
  }

  private function list_s3_files(){
    $objects = $this->S3Client->listObjects(array('Bucket' => $this->bucket[0], 'Prefix' => $this->getS3Directory()));
    
    $object = array();
    if(sizeof($objects) > 0 && isset($objects['Contents']))
      foreach ($objects['Contents'] as $bucket)
        $object[] = $bucket['Key'];
    asort($object);

    return $object;
  }

  private function limitBackups(){
    $backups = $this->list_s3_files();
    $backups_to_delete = array();

    for ($i=0; $i < count($backups) - $this->parent->get_limit('s3'); $i++)
      $backups_to_delete[]['Key'] = $backups[$i];
    
    if(count($backups_to_delete))
      $this->result = $this->S3Client->deleteObjects(array(
        'Bucket'  => $this->bucket[0],
        'Delete' => array (
          'Objects' => $backups_to_delete
        ),
      ));

    return $this;
  }

  private function getS3Directory(){
    return count($this->bucket) > 1 ? implode('/', array_slice($this->bucket, 1)) . '/' : '';
  }

  private function touch_bucket(){
    if(!$this->S3Client->doesBucketExist($this->bucket[0]))
      $this->S3Client->createBucket(array( 'Bucket' => $this->bucket[0] ));

    return $this;
  }
}