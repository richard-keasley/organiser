<?php
namespace App\Cells\input;

class select extends \App\Cells\input {

function __construct($name=null, $selected=null, $options=[], $attrs=[]) {
	if($name!==null) $attrs['name'] = $name;
	if($selected!==null) $attrs['selected'] = $selected;
	$attrs['options'] = $options;	
	$this->attrs = $attrs;
}

function __tostring() {
	return form_dropdown($this->attrs);
}
	
}