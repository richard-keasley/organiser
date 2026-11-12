<?php

$name_attrs = [
	'style' => "min-width:10em",
	'class' => "form-control",
	'onKeyup' => "houseedit.tr.dirty(this)",
];

$attrs = [
	'type' => "button",
	'class' => "btn btn-sm btn-success",
	'data-cmd' => "save",
	'onClick' => "houseedit.people.update(this)",
	'title' => "Save this person",
];
$label = '<i class="bi bi-check"></i>';
$btn_save = trim(form_button($attrs, $label));

$attrs = [
	'type' => "button",
	'class' => "btn btn-sm btn-danger",
	'data-cmd' => "delete",
	'onClick' => "houseedit.people.update(this)",
	'title' => "Delete this person",
];
$label = '<i class="bi bi-trash"></i>';
$btn_delete = trim(form_button($attrs, $label));

$tbody = [];
foreach($house->people as $person) {
	if($person->houseperson) continue;
	
	$tr = [
		'name 1' => new \App\Cells\input("Name1", $person->Name1, $name_attrs),
		'name 2' => new \App\Cells\input("Name2", $person->Name2, $name_attrs),
		'_' => [$btn_save, $btn_delete],
	];
	
	$tr['_']= implode('&nbsp;', $tr['_']);	
	$attrs = ['type'=>"hidden"];
	$tr['_'] .= new \App\Cells\input('PersonID', $person->PersonID, $attrs);
	
	$tbody[] = $tr;
}

// new entry
$tr = [
	'name 1' => new \App\Cells\input("Name1", '', $name_attrs),
	'name 2' => new \App\Cells\input("Name2", $house->defName2, $name_attrs),
	'_' => '',
];
$btn_attrs = [
	'type' => "button",
	'class' => "btn btn-sm btn-success",
	'data-cmd' => "insert",
	'onClick' => "houseedit.people.update(this)",
	'title' => "Add new person",
];
$btn_label = '<i class="bi bi-plus"></i>';
$tr['_'] .= form_button($btn_attrs, $btn_label);
$tbody[] = $tr;

$thead = array_keys($tr);
$last = array_key_last($thead);
$thead[$last] = '';
$table = new \App\Cells\Table('responsive');
$table->setHeading($thead);
echo $table->generate($tbody);
