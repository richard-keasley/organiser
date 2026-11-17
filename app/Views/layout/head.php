<meta charset="UTF-8">
<meta name="description" content="Event and date organiser">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php
echo (empty($this->sections['title'])) ? 
	esc($title ?? 'organiser') : 
	$this->renderSection('title') ;
?></title>
<link href="https://fonts.googleapis.com" rel="preconnect">
<link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Didact+Gothic&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.2/font/bootstrap-icons.min.css" rel="stylesheet" type="text/css">
<?php

$links = [
	[
		'href' => "app/custom.css",
	],
	[
		'rel' => "shortcut icon",
		'type' => "image/png",
		'href' => "favicon.ico",
	],
];
foreach($links as $attrs) echo link_tag($attrs);

?>
<script src="https://code.jquery.com/jquery-4.0.0.min.js" integrity="sha256-OaVG6prZf4v69dPg6PhVattBXkcOWQB62pdZ3ORyrao=" crossorigin="anonymous"></script>
