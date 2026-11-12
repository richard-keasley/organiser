<?php $this->extend('layout');

$this->section('main'); ?>
<ul class="nav flex-column"><?php
$houses = model('Houses')->ingroup($group['GroupID']);
$format = '<li class="nav-item text-nowrap">%s</li>';
$attrs = ['class' => "nav-link"];
foreach($houses as $house) {
	$href = "view/{$house->HouseID}";
	$label = (string) ($house->HouseTitle ?? $house->HouseID);
	printf($format, anchor($href, $label, $attrs));
}
?></ul>
<?php $this->endSection();

$this->section('nav'); 
$attrs = [
	'class' => "btn btn-outline-dark",
];
$label = '<i class="bi bi-collection"></i>';
echo anchor("setup/groups", $label, $attrs);
$this->endSection();
