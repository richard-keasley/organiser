<!DOCTYPE html>
<html lang="en">
<head><?php 
echo $this->include('layout/head');
echo $this->renderSection('head'); 
?></head>
<body id="body-app">

<header class="container-fluid bg-body-tertiary position-relative d-print-none" style="min-width:0">
<?php
$attrs = [
	'class' => "img-brand",
	'src' => site_url('app/brand.png'),
];
echo img($attrs);
?>
<h1 class="overflow-x-hidden text-nowrap"><?php 
echo esc($heading ?? $title);
?></h1>

<nav class="d-flex justify-content-between">
<div class="navbar gap-1"><?php 
$href = '/';
$label = '<i class="bi bi-house"></i>';
$attrs = [
	'class' => "btn btn-outline-dark",
	'title' => "home",
];
echo anchor($href, $label, $attrs);

$label = '<i class="bi bi-arrow-clockwise"></i>';
$attrs = [
	'class' => "btn btn-outline-primary",
	'title' => "reload",
	'onClick' => "window.location.href = window.location.href;",
];
echo form_button($attrs, $label);

echo $this->renderSection('nav');
?></div>

<div class="navbar gap-1"><?php

$href = 'reports';
$label = '<i class="bi bi-file-text"></i>';
$attrs = [
	'class' => "btn btn-outline-secondary",
	'title' => "reports",
];
echo anchor($href, $label, $attrs);

$href = 'setup';
$label = '<i class="bi bi-gear"></i>';
$attrs = [
	'class' => "btn btn-outline-secondary",
	'title' => "setup",
];
echo anchor($href, $label, $attrs);

$href = 'auth/logout';
$label = '<i class="bi bi-box-arrow-right"></i>';
$attrs = [
	'class' => "btn btn-primary",
	'title' => "logout",
];
echo anchor($href, $label, $attrs);
?></div>

</nav>

</header>

<main class="overflow-scroll" role="main"><div class="container-fluid"><?php 

if(service('settings')->get("Auth.allowRegistration")) { ?>
<div class="alert alert-danger">App is open for new users
(<a href="<?php echo base_url('setup/auth');?>">alter security settings</a>).
</div>
<?php }

echo $this->renderSection('main') ;
?></div></main> 

<?php 
echo $this->renderSection('bottom');
echo $this->include('layout/bottom');
?>
</body> 
</html>
