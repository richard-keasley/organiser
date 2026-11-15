<?php $this->extend('layout');

$groups = []; $supergroups=[];
foreach(model('Groups')->orderBy('GrpDes')->findAll() as $group) {
	$token = strtolower(strtok($group['GrpDes'], ' '));
	$supergroups[$token] = $token;
	$group['supergroup'] = $token;
	$groups[] = $group;
} 

$this->section('main'); ?>
<form method="GET" id="selector">
<div class="row my-1 gap-3">

<div class="col-auto">
<section class="nav flex-column gap-1"><?php

$format = '<button %s>%s</button>';
$attrs = [
	'onClick' => "reporting.supergroup(this)",
	'data-sgname' => '',
	'class' => "btn btn-outline-primary",
];

foreach($supergroups as $sgname) {
	$attrs['data-sgname'] = $sgname;
	$label = humanize($sgname);
	echo form_button($attrs, $label);
}
$attrs['data-sgname'] = '_';
$label = '<i class="bi bi-x"></i>';
echo form_button($attrs, $label);

?></section>
</div>

<div class="col-auto" id="sg-checks"><?php
foreach($groups as $group) { ?>
<div class="form-check">
<label class="form-check-label"><?php
	$attrs = [
		'value' => $group['GroupID'],
		'data-supergroup' => $group['supergroup'],
	];
	echo new \App\Cells\input\checkbox('GroupID', false, $attrs);
	echo $group['GrpDes'];
?></label>
</div>
<?php } 
?>
</div>

<div class="col-auto"><?php
$files = new \CodeIgniter\Files\FileCollection();
$files->addDirectory(__DIR__);
$files->removePattern('#index\.#');
$options = ['-'];
foreach($files as $file) {
	$basename = $file->getBasename('.php');
	$options[$basename] = humanize($basename);
}
$attrs = [
	'class' => "form-control",
];
echo new \App\Cells\input\select('report', '', $options, $attrs);
?></div>

<div class="col-auto">
<div>
<button class="btn btn-primary" type="button" onClick="reporting.select()">GO</button>
</div>
</div>

</div>
</form>
<script>
const reporting = {
	
supergroup: function (el) {
	var sgname = el.dataset.sgname;
	console.log(`#${sgname}#`);
	if(sgname=='_') {	
		$(`#sg-checks [type=checkbox]`).prop('checked', false);
	}
	else {
		$(`#sg-checks [data-supergroup=${sgname}]`).prop('checked', true);
	}
},

select: function() {
	var groups = [];
	$("#selector [name=GroupID]").each(function(index) {
		if(this.checked) groups.push(this.value);
	});
	
	var url = [
		'<?php echo base_url('reports/view');?>',
		$("#selector [name=report]").val(),
		groups.join('-'),
	];
	if(url[1]==='0') return; 
	if(!url[2]) return;
	
	url = url.join('/');
	window.location = url;	
},
	
};

</script>
<?php $this->endSection();
