<?php
namespace App\Cells\input;

class date extends \App\Cells\input {

protected $attrs = ['type'=>"date"];

function __construct($name=null, $value=null, $attrs=[]) {
	try {
		if(!$value) throw new \exception('empty');
		$value = $value->format('Y-m-d');
	}
	catch(\throwable $ex) {
		$value = '';
	}
	parent::__construct($name, $value, $attrs);
}
	
}