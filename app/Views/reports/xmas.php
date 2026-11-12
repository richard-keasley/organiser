<?php $this->extend('layout');

$this->section('main'); ?>
<h1>Christmas card list</h1>

<div class="columns-2"><?php

$boxes = sprintf('<div class="pt-1">%s</div>', new \App\Cells\grid(3, 1));
$header = '<h3>%s</h3>';

foreach($groups as $group) {
	printf($header, $group['GrpDes']);
	$houses = model('Houses')->ingroup($group['GroupID']);
	if(!$houses) continue;
	?>
	<div class="d-grid gap-1" style="grid-template-columns: 3em 1fr"><?php
	foreach($houses as $house) { 
		echo $boxes;
		
		$label = $house->HouseTitle;
		
		$suffix = [];
		$people = model('People')->inhouse($house->HouseID);
		foreach($people as $person) {
			if($person->houseperson) continue;
			$suffix[] = $person->Name1;
		}
		if($suffix) {
			$label .= sprintf(' (%s)', implode(', ', $suffix));
		}
		printf('<div>%s</div>', $label);
		
	}
	?></div>
	<?php
}
?></div>

<?php
$this->endSection();
