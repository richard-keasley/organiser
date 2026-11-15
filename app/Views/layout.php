<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="description" content="Event and date organiser">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo esc($title);?></title>

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
<?php $this->renderSection('head') ;?>
</head>
<body>

<header class="container-fluid bg-body-tertiary position-relative d-print-none" style="min-width:0">
<?php
$attrs = [
	'class' => "position-absolute",
	'src' => site_url('app/brand.png'),
	'style' => "height:8em;right:1em;top:0;opacity:0.05",
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

<main class="overflow-scroll"><div class="container-fluid"><?php 

if(service('settings')->get("Auth.allowRegistration")) { ?>
<div class="alert alert-danger">App is open for new users
(<a href="<?php echo base_url('setup/auth');?>">alter security settings</a>).
</div>
<?php }

echo $this->renderSection('main') ;
?></div></main> 

<footer class="container-fluid bg-body-tertiary"><?php 

echo $this->renderSection('footer') ;

if(ENVIRONMENT!='production') { ?>
<div class="row">
	<div class="col-6">{elapsed_time} sec / {memory_usage} MB</div>
	<div class="col-6">Environment: <?php echo ENVIRONMENT; ?></div>
</div>
<?php }
?></footer>

<?php echo $this->renderSection('bottom') ;?>

<script {csp-script-nonce}>

const apilib = {
	
error: function(jqXHR) {
	console.error(jqXHR);
	var arr = jqXHR.responseJSON;
	var msg = arr.message ?? null;
	if(!msg) {
		var tmp = arr.messages ?? {};
		msg = tmp.error ?? null;
	}
	if(!msg) {
		msg = jqXHR.responseText ?? null;
	}
	if(!msg) msg = 'undefined error';
	alert(msg);
},

post: function(request={}) { <?php
	// add security fields to API post
	$fields = [
		csrf_token() => csrf_hash(),
		config('Honeypot')->name => ""
	];
	$format = "request['%s']='%s';";
	foreach($fields as $key=>$val) printf($format, $key, $val);
	?> return request;
},

showfilter: function(search_el, show_selector) {
	var value = $(search_el).val().toLowerCase().trim();
	$(show_selector).each(function() {
		if($(this).text().toLowerCase().search(value) > -1) {
			$(this).show();
		}
		else {
			$(this).hide();
		}
	});
},

};

function trinputs(el) {
	this.tr = $(el).closest('tr');
	this.dataset = JSON.parse(JSON.stringify(el.dataset));
	this.cmd = this.dataset.cmd ?? 'save';			
	
	var buffer = {};		
	var elnames = ['input', 'select', 'textarea'];
	for(let elname of elnames) {
		this.tr.find(elname).each(function() {
			fldname = $(this).attr("name");
			fldvalue = $(this).val();
			buffer[fldname] = fldvalue;
		});			
	}
	this.inputs = buffer;
	
	this.dirty = function() {
		this.tr.addClass('table-warning');
	};
	
	this.clean = function() {
		this.tr.removeClass('table-warning');
	};
};

function chkinput(el) {
	this.container = $(el).closest('.form-check');
	this.name = el.name ?? '';
	this.checked = el.checked ? 1 : 0;
	
	this.dirty = function() {
		this.container.addClass('bg-warning bg-opacity-25');
	};
	
	this.clean = function() {
		this.container.removeClass('bg-warning bg-opacity-25');
	};
};

const reminders = {

api: '<?php echo base_url('api/event/remdate');?>',
	
update: function(btn) {
	var input = new trinputs(btn);
	input.dirty();
	
	$.post(this.api, apilib.post(input.dataset) )
	.done(function(response) {
		$('#reminders-container').html(response);
	})
	.fail(function(xhr) { 
		apilib.error(xhr);
		input.clean();
	});
}

};
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body> 
</html>
