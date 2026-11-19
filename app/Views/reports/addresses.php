<?php $this->extend('layout');

$this->section('main');

$houses = []; $orderby = [];
foreach($groups as $group) {
	$grp_houses = model('Houses')->ingroup($group['GroupID']);
	foreach($grp_houses as $house) {
		$id = $house->HouseID;
		$houses[$id] = $house;
		$orderby[$id] = $house->HouseTitle;
	}
}
array_multisort($orderby, $houses);

$ico_format = '<i class="bi bi-%s"></i>';
foreach($houses as $house) { 
	$tbody = [];
	foreach($house->contacts as $contact) {
		$tbody[] = [
			sprintf($ico_format, $contact->type['icon']),
			$contact->ConDes,
			$contact->link,
		];
	}
	
	if($tbody) {
		echo '<section>';
		echo "<h3>{$house->HouseTitle}</h3>";
		$table = new \App\Cells\Table('list');
		$table->autoHeading = false;
		echo $table->generate($tbody);
		echo '</section>';
	}
}

$this->endSection();
