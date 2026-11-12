<?php

$f = [
	'dot' => '<i style="color:%s" class="bi bi-circle-fill"></i>',
	'date' => '<div class="%s" style="width:5em;text-align:center;">%s</div>',
	'description' => '<span class="%s">%s</span>',
];

$tbody = [];
foreach($house->events as $event) {
	$description = $event->EvDes;
	$annual = $event->type['EvTypeFreq']===\App\Models\EventTypes::YEAR;
	if($annual) $description .= " ({$event->EvDate0->age})";
	if($event->due) $description .= '&nbsp;' . $event->getRembtn('house');
		
	$class = $event->due ? 'fw-bold' : '' ;
	$tbody[] = [
		sprintf($f['dot'], $event->colour),
		sprintf($f['date'], $class, $event->EvDate0->format('d-M-y')),
		sprintf($f['description'], $class, $description),
	];
}

if($tbody) {
	$table = new \App\Cells\Table('list');
	$table->autoHeading = false;
	echo $table->generate($tbody);
}
