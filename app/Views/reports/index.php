<?php $this->extend('layout');

$this->section('main'); ?>
<form method="GET" id="selector">
<div class="row my-1">
<div class="col-auto"><?php
$files = new \CodeIgniter\Files\FileCollection();
$files->addDirectory(__DIR__);
$files->removePattern('#index\.#');
$options = ['-'];
foreach($files as $file) {
	$basename = $file->getBasename('.php');
	$options[$basename] = $basename;
	
}
$attrs = [
	'class' => "form-control",
];
echo new \App\Cells\input\select('report', '', $options, $attrs);
?></div>

<div class="col-auto"><?php
$groups = [];
foreach(model('Groups')->orderBy('GrpDes')->findAll() as $group) { ?>
<div class="form-check">
<label class="form-check-label"><?php
	$ingroup = in_array($group['GroupID'], $groups);
	$attrs = ['value'=>$group['GroupID']];
	echo new \App\Cells\input\checkbox('GroupID', $ingroup, $attrs);
	echo $group['GrpDes'];
?></label>
</div>
<?php }
?></div>

<div class="col-auto">
<button class="btn btn-primary" type="button" onClick="reporting.select()">GO</button>
</div>

</div>
</form>
<script>
const reporting = {

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
