<?php

$now = (new \datetime)->format('Y-m-d');

$events = model('Events')
	->where('`RemDate` IS NOT NULl')
	->where("`RemDate` < '{$now}'")
	->orderBy('RemDate')
	->limit(20)->findAll();
$tbody = [];

$evtype_opts = [];
foreach(model('EventTypes')->options() as $val=>$label) {
	$evtype_opts[$val] = "[{$label}]";
}

$tr = [];
$date_format = 'd-M-y';
$des_format = '<div style="max-width:10em" class="text-nowrap overflow-hidden">%s</div>';
foreach($events as $event) {
	
	$href = "/view/{$event->person->HouseID}";
	$label = $event->EvDes ? 
		$event->EvDes : 
		$evtype_opts[$event->EvTypeID] ?? "??";
	$tr['description'] = sprintf($des_format, anchor($href, $label));
	
	$tr['date'] = [
		'data-fldname' => "evdate",
		'data' => $event->EvDate ? 
			$event->EvDate->format($date_format) : 
			sprintf('<em>%s</em>', $event->RemDate->format($date_format)),
	];
		
	$tr['_'] = $event->getRembtn('reminders');
	
	$tbody[] = $tr;
}

if($tbody) {
	$table = new \App\Cells\Table('list');
	$table->autoHeading = false;
	echo $table->generate($tbody);
}
