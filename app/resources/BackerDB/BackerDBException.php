<?php

namespace App\resources\BackerDB;

class BackerDBException extends \Exception
{
  public $e;

  public function __construct($message, $code = 0, Exception $previous = null){
      self::log($message,$code);
      parent::__construct($message, $code, $previous);
  }

  private function log($message,$code){
     //TODO
  }
}