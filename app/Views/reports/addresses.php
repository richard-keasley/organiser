<?php $this->extend('layout');

$this->section('main');

d($groups);

$houses = [];
foreach($groups as $group) {
	$houses = model('Houses')->ingroup($group['GroupID']);
	foreach($houses as $house) {
		$id = $house->HouseID;
		$houses[$id] = $house;
	}
}
d($houses);


$this->endSection();
