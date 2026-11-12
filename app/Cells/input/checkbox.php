<?php
namespace App\Cells\input;

class checkbox extends \App\Cells\input {

protected $attrs = [
	'type' => "checkbox",
	'value' => "1",
	'class' => "form-check-input",
];

function __construct($name=null, $checked=false, $attrs=[]) {
	if($checked) $attrs['checked'] = "checked";
	parent::__construct($name, null, $attrs);
}
	
}