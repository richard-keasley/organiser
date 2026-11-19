<?php $this->extend('layout');

$this->section('main'); ?>
<div class="d-grid gap-1" style="grid-template-columns:1fr 1fr">
<?php

$houses = []; 
$tree = []; $orderby = [];

foreach($groups as $group) {
	$grp_houses = model('Houses')->ingroup($group['GroupID']);
	foreach($grp_houses as $house) {
		$id = $house->HouseID;
		if(in_array($id, $houses)) continue;
		$houses[] = $id;
		foreach($house->events as $event) {
			if($event->type['EvTypeFreq']!==\App\Models\EventTypes::YEAR) continue;
			
			$secname = $event->EvDate0->format('F');
			if(empty($tree[$secname])) {
				$tree[$secname] = [];
				$orderby[$secname] = $event->EvDate0->format('m');
			}
			$tree[$secname][] = $event;
		}
	}
}
	
if($tree) {
	array_multisort($orderby, $tree);
			
	$col_format = '<i class="bi bi-circle-fill" style="color:%s"></i>';
	foreach($tree as $secname=>$branch) {
		echo '<section>';
		echo "<h3>{$secname}</h3>";
		$tbody = []; $orderby = [];
		foreach($branch as $event) {
			base_url("view/{$event->house->HouseID}");
			$tbody[] = [
				$event->EvDate0->format('j'),
				sprintf($col_format, $event->colour),
				anchor("view/{$event->house->HouseID}", "{$event->EvDes} ({$event->EvDate0->age})"),
			];
			$orderby[] = $event->EvDate0->format('d');
		}
		array_multisort($orderby, $tbody);

		$table = new \App\Cells\Table('list');
		$table->autoHeading = false;
		echo $table->generate($tbody);
		echo '</section>';
	}
}
?>
</div>
<?php
$this->endSection();
