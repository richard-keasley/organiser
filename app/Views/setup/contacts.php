<?php 

use \App\Models\EventTypes;

$this->extend('layout');

$this->section('main'); ?>
<p class="alert alert-info">This can't work yet. Once we are not using MS Access, I will change this table so it can be edited.</p>
<?php
$contypes = model('Contypes')->findAll();
if($contypes) {
	$tr = $contypes[0];
	$thead = array_keys($tr);
	$last = array_key_last($thead);
	# $thead[$last] = '';
	$table = new \App\Cells\Table('responsive');
	$table->setHeading($thead);
	echo $table->generate($contypes);
}
$this->endSection();

$this->section('bottom'); ?>
<script>
const contypesedit = {

update: function(el) {
	var input = new trinputs(el);
	if(input.cmd=='delete') {
		var confirmed = confirm('Delete this contact type?');
		if(!confirmed) return false;
	}
	
	input.dirty();
	var api = `<?php echo base_url("api/contypes");?>/${input.cmd}`;
	el.disabled = true;
	
	console.log(api, input.inputs);
	
	$.post( api, apilib.post(input.inputs) )
	.done(function(response) {
		console.log(response);
		switch(input.cmd) {
			case 'insert':
			window.location.reload();
			break;
			
			case 'delete':
			input.tr.remove();
			break;
			
			default:
			input.clean();
		}
	})
	.fail(function(xhr) { 
		apilib.error(xhr);
	})
	.always(function() {
		el.disabled = false;
	});
},

dirty: function(el) {
	(new trinputs(el)).dirty();
},
	
};

</script>
<?php $this->endSection();
