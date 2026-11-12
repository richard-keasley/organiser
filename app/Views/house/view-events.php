<?php

$f = [
	'dot' => '<i title="%s" class="bi bi-circle-fill" style="color:%s"></i>',
	'date' => '<div title="%s" class="%s" style="width:5em;text-align:center;">%s</div>',
	'description' => '<span title="%s" class="%s">%s</span>',
];

$tbody = [];
foreach($house->events as $event) {
	$title = $event->type['EvTypeDes'];
	
	
	$description = $event->EvDes;
	$annual = $event->type['EvTypeFreq']===\App\Models\EventTypes::YEAR;
	if($annual) $description .= " ({$event->EvDate0->age})";
	if($event->due) $description .= '&nbsp;' . $event->getRembtn('house');
		
	$class = $event->due ? 'fw-bold' : '' ;
	$tbody[] = [
		sprintf($f['dot'], $title, $event->colour),
		sprintf($f['date'], $title, $class, $event->EvDate0->format('d-M-y')),
		sprintf($f['description'], $title, $class, $description),
	];
}

if($tbody) {
	$table = new \App\Cells\Table('list');
	$table->autoHeading = false;
	echo $table->generate($tbody);
}
