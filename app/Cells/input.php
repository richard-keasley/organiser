<?php
namespace App\Cells;

class input implements \stringable {
	
protected $attrs = [];

function __construct($name=null, $value=null, $attrs=[]) {
	$this->attrs = $attrs + $this->attrs;
	if($value!==null) $this->attrs['value'] = $value;
	if($name!==null) $this->attrs['name'] = $name;
}

function __tostring() {
	return form_input($this->attrs);
}
	
}