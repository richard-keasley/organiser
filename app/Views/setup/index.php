<?php $this->extend('layout');

$this->section('main'); ?>
<ul class="nav flex-column"><?php

$links = [
	'setup/groups' => "Groups",
	'setup/events' => "Events",
	'setup/contacts' => "Contacts",
	'setup/auth' => "Security",
];

$format = '<li class="nav-item"><a class="nav-link" href="%s">%s</a></li>';
foreach($links as $href=>$label) {
	printf($format, $href, $label);
}

?></ul>
<?php $this->endSection();
