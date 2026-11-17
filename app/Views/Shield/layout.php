<!DOCTYPE html>
<html lang="en">
<head><?php 
echo $this->include('layout/head');
echo $this->renderSection('pageStyles') 
?></head>
<body id="body-auth">
<main role="main" class="container"><?php
$attrs = [
	'class' => "img-brand",
	'src' => site_url('app/brand.png'),
];
echo img($attrs);
echo $this->renderSection('main');
?></main>
<?php echo $this->renderSection('pageScripts'); ?>
</body>
</html>
