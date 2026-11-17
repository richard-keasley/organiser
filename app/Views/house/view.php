<?php $this->extend('layout');

$this->section('main'); 

$f_open = '<fieldset class="border p-2 my-1"><legend>%s</legend>';
$f_close = '</fieldset>';

$people = [];
foreach($house->people as $person) {
	if(!$person->houseperson) $people[] = $person;
}
if($people) {
	printf($f_open, 'People'); ?>
	<ul class="nav flex-column"><?php
	$format = '<li>%s</li>';
	foreach($people as $person) {
		printf($format, $person->name);
	}
	?></ul>
	<?php echo $f_close;
}

if($house->contacts) {
printf($f_open, 'Contacts'); ?>
<div class="d-grid gap-2" style="grid-template-columns: 1em auto;">
<?php
$format = '%s<div class="text-secondary text-opacity-75">%s</div>';

foreach($house->contacts as $contact) {
	echo $contact->icon;
	?>
	<div class="d-md-flex gap-2"><?php
	printf($format, $contact->link, $contact->ConDes);
	?></div>
	<?php
}
?>
</div>
<?php echo $f_close;
}

if($house->groups) {
printf($f_open, 'Groups'); ?>
<ul class="nav flex-column"><?php
$format = '<li class="nav-item">%s</li>';
$attrs = ['class' => "nav-link"];
foreach($house->groups as $group) {
	$link = anchor("setup/groups/{$group['GroupID']}", $group['GrpDes'], $attrs);
	printf($format, $link);
}
?></ul>
<?php echo $f_close; 
}

if($house->events) {
printf($f_open, 'Events'); ?>
<div id="reminders-container"><?php
echo $this->include('house/view-events');
?></div>
<?php echo $f_close; 
}

$this->endSection();

$this->section('nav');
$href = "edit/{$house->HouseID}";
$label = '<i class="bi bi-pencil-square"></i>';
$attrs = [
	'class' => "btn btn-outline-dark",
	'title' => "edit this",
];
echo anchor($href, $label, $attrs);
$this->endSection();
