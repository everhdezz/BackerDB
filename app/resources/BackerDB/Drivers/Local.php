<?php

namespace App\resources\BackerDB\Drivers;

use App\resources\BackerDB\BackerDBException;

class Local
{
  private $parent;

  function __construct($parent){
    $this->parent = $parent;

    $this->touchLocalDir();
  }

  function putBackup(){
    if(!copy($this->parent->path . 'tmp/' . $this->parent->dump_name, $this->parent->path . 'backups/' .$this->parent->dump_name))
      throw new BackerDBException("local backup could not be generated", 1);

    $this->limitBackups();
  }

  private function limitBackups(){
    $backups = glob("{$this->parent->path}backups/{$this->parent->db_name}_*.sql");

    for ($i=0; $i < count($backups) - $this->getLimit(); $i++)
      unlink($backups[$i]);

    return $this; 
  }

  private function getLimit(){
    return !isset($this->parent->limits['local']) ? 7 : $this->parent->limits['local'];
  }

  private function touchLocalDir(){
    return is_dir($this->parent->path . 'backups') || mkdir($this->parent->path . 'backups', 0775, true);
  }
}