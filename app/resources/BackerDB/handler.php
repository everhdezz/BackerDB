<?php

namespace App\resources\BackerDB;

class handler
{
  function __construct(){}

  function foreach(callable $function){
    try {
      foreach($_ENV->databases() as $database):
      	$db_instance = new database($database);

      	$db_instance->dump();
        	$function($db_instance);
        $db_instance->rm_temp_dump();
      endforeach;
    } catch (BackerDBException $e) {
      throw new Exception($e->getMessage());      
    }
  }
}