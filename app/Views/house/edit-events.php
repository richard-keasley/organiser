<?php

$dates = [];
$arr = ['start'=>"EvDate0", 'next'=>"EvDate", 'reminder'=>"RemDate"];
foreach($arr as $colname=>$dbkey) {
	$dates[$colname] = [
		'class' => "form-control",
		'name' => $dbkey,
		'onChange' => "houseedit.tr.dirty(this)",
	];
}

$evtype_opts = model('EventTypes')->options();
$people_opts = $house->peopleopts;
$ev_attrs = [
	'class' => "form-control",
	'onChange' => "houseedit.events.description(this)",
	'data-fldname' => "evdes_select",
	'style' => "min-width:6em",
];
$des_attrs = [
	'style' => "width:15em",
	'class' => "form-control",
	'onKeyup' => "houseedit.tr.dirty(this)",
];

$attrs = [
	'type' => "button",
	'class' => "btn btn-sm btn-danger",
	'onClick' => "houseedit.events.update(this)",
	'data-cmd' => "delete"
];
$label = '<i class="bi bi-trash"></i>';
$btn_delete = trim(form_button($attrs, $label));

$attrs = [
	'type' => "button",
	'onClick' => "houseedit.events.update(this)",
	'class' => "btn btn-sm btn-success",
	'data-cmd' => "save",
	'title' => "save",
];
$label = '<i class="bi bi-check"></i></button>';
$btn_save = trim(form_button($attrs, $label));

$tbody = [];
foreach($house->events as $event) {	
	$tr = [
		'person' => new \App\Cells\input\select("PersonID", $event->PersonID, $people_opts, $ev_attrs),
		'event' => new \App\Cells\input\select("EvTypeID", $event->EvTypeID, $evtype_opts, $ev_attrs),
		'description' => new \App\Cells\input("EvDes", $event->EvDes, $des_attrs),
	];
	foreach($dates as $colname=>$attrs) {
		$dbkey = $attrs['name'];
		$tr[$colname] = new \App\Cells\input\date($dbkey, $event->{$dbkey}, $attrs);
	}
	
	$buttons = [$btn_save];
	
	$nextdate = $event->nextdate;
	$nextdate = $nextdate ? $nextdate->format('Y-m-d') : '' ;
	$attrs = [
		'type' => "button",
		'onClick' => "houseedit.events.nextdate(this)",
		'class' => "btn btn-sm btn-info",
		'title' => "Next reminder for this event",
		'data-nextdate' => $nextdate,
	];
	$label = '<i class="bi bi-skip-end"></i>';
	$buttons[] = trim(form_button($attrs, $label));
	
	$buttons[] = $btn_delete;
		
	$tr['_'] = implode('&nbsp;', $buttons);
	$attrs = ['type' => 'hidden'];
	$tr['_'] .= new \App\Cells\input("EventID", $event->EventID, $attrs);
		
	$tbody[] = $tr;
}

// new entry
$tr = [
	'person' => new \App\Cells\input\select("PersonID", '', $people_opts, $ev_attrs),
	'event' => new \App\Cells\input\select("EvTypeID", '', $evtype_opts, $ev_attrs),
	'description' => new \App\Cells\input("EvDes", '', $des_attrs),
	'start' => new \App\Cells\input\date('EvDate0', '', $dates['start']),
];

$attrs = [
	'type' => "button",
	'class' => "btn btn-sm btn-success",
	'data-cmd' => "insert",
	'onClick' => "houseedit.events.update(this)",
	'title' => "Add new event",
];
$label = '<i class="bi bi-plus"></i>';
$tr['next'] = trim(form_button($attrs, $label));
$tr['reminder'] = '';
$tr['_'] = '';
$tbody[] = $tr;


$thead = array_keys($tr);
$last = array_key_last($thead);
$thead[$last] = '';
$table = new \App\Cells\Table('responsive');
$table->setHeading($thead);
echo $table->generate($tbody);
