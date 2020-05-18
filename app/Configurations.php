<?php

namespace App;

class Configurations
{
	private $configurations;

	function __construct($conf_file = './conf.php'){
		$this->load_file($conf_file);
	}

	function load_file($conf_file){
		$this->configurations = include $conf_file;
	}

	function set($param, $value = ''){

		$this->configurations[$strtoupper( $param )] = $value;
		return $this;
	}

	function __call($param, $value){
		if(empty($value))
			return $this->configurations[strtoupper( $param )];
		else
			return $this->configurations[strtoupper( $param )][$value[0]];
	}
}