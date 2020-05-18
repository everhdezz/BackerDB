<?php

namespace App\resources\BackerDB;

class database
{
  private $db_conf;

  public $path;
  public $dump_name;
  public $result_file;

  function __construct($db_conf){
    $this->path = getcwd() . '/';
    $this->touchTempDir();

    $this->db_conf = $db_conf;
  }

  function driver($driver){
    $driver_class = 'App\resources\BackerDB\Drivers\\' . $driver;
    return new $driver_class($this);
  }

  function dump(){
    $this->dump_name = $this->db_name . '_' . date('Ymd_His') . '.sql';
    $this->result_file = $this->path . 'tmp/' . $this->dump_name;

    exec("mysqldump " .
          "--user={$_ENV->access('DB')['USER']} " .
          "--password={$_ENV->access('DB')['PASS']} " .
          "--host={$_ENV->access('DB')['HOST']} {$this->db_name} " .
          "--result-file={$this->result_file} 2>&1", $message);
    print json_encode($message) . PHP_EOL;

    return $this;
  }

  function rm_temp_dump(){
    unlink($this->result_file);

    return $this;
  }

  function __get($param){
    return $this->db_conf[$param];
  }

  private function touchTempDir(){
    return is_dir($this->path . 'tmp') || mkdir($this->path . 'tmp', 0775, true);
  }
}