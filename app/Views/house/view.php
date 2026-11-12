<?php $this->extend('layout');

$this->section('main'); ?>

<fieldset class="border p-2 my-1">
<legend>People</legend>
<ul class="nav flex-column"><?php
$format = '<li>%s</li>';
foreach($house->people as $person) {
	if($person->houseperson) continue;
	printf($format, $person->name);
}
?></ul>
</fieldset>

<fieldset class="border p-2 my-1">
<legend>Contacts</legend>
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
</fieldset>

<fieldset class="border p-2 my-1">
<legend>Groups</legend>
<ul class="nav flex-column"><?php
$format = '<li class="nav-item">%s</li>';
$attrs = ['class' => "nav-link"];
foreach($house->groups as $group) {
	$link = anchor("setup/groups/{$group['GroupID']}", $group['GrpDes'], $attrs);
	printf($format, $link);
}
?></ul>
</fieldset>

<fieldset class="border p-2 my-1">
<legend>Events</legend>
<div id="reminders-container"><?php
echo $this->include('house/view-events');
?></div>
</fieldset>
<?php 
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
