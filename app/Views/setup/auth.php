<?php $this->extend('layout');

$this->section('main');

$attrs = [
	'id' => "auth-form",
	'class' => "border p-3 my-3",
];
echo form_open(attributes:$attrs);

$fldnames = [
	'allowRegistration' => 'Allow new users',
	'allowMagicLinkLogins' => "Enabled magic login links"
];
$attrs = [
	'onClick' => "authedit.settings(this)"
];
foreach($fldnames as $fldname=>$label) { ?>
	<p class="form-check"><label class="form-check-label"><?php 
	$checked = service('settings')->get("Auth.{$fldname}");
	echo new \App\Cells\input\checkbox($fldname, $checked, $attrs);
	echo $label;
	?></label></p>
<?php }
echo form_close();
?>

<section id="auth-users" class="border my-1 p-2"><?php 
echo $this->include('setup/auth-users');
?></section>
<?php 
$this->endSection();

$this->section('nav'); 
$this->endSection();

$this->section('nav');?> 
<script>
const authedit = {
	
settings: function(el) {
	var input = new chkinput(el);
	var inputs = {
		name: `Auth.${input.name}`,
		value: input.checked,
		type: 'boolean',
	};
	var api = '<?php echo base_url("api/settings/set");?>';		
	
	input.dirty();
	$.post(api, apilib.post(inputs) )
	.done(function(response) {
		input.clean();
	})		
	.fail(function(xhr) { 
		apilib.error(xhr);
	});
},

user: {
	input: null,
	modal: null,
	fldnames: <?php echo json_encode(array_keys($user_fields));?>,
	fields: null,
	
	delete: function(el) {
		var confirmed = confirm('Delete this user?');
		if(!confirmed) return false;
		
		this.input = new trinputs(el);
		this.input.dirty();
		
		var api = '<?php echo base_url('api/user/delete');?>';
		$.post( api, apilib.post(this.input.inputs) )
		.done(function(response) {
			$('#auth-users').html(response);
		})
		.fail(function(xhr) { 
			apilib.error(xhr);
			authedit.user.input.clean();
		});
	},
	
	edit: function(el) {
		this.input = new trinputs(el);
		this.modal = new bootstrap.Modal('#user-modal', {});
		this.fields = $('#user-modal .modal-body');
		
		this.input.tr.find('td').each(function(idx, td) {
			var fldname = td.dataset.fldname ?? '';
			if(authedit.user.fldnames.includes(fldname)) {
				authedit.user.fields.find(`input[name=${fldname}]`).val(td.innerHTML);
			}
		});
		this.fields.find('input[type=password]').val('');
		this.fields.find('input[name=id]').val(this.input.inputs.id);
		this.modal.show();
	},
	
	save: function(el) {
		this.input.dirty();
		this.modal.hide();
				
		var inputs = {};
		this.fields.find(`input`).each(function(idx, el) {
			inputs[el.name] = el.value;
		});
		
		var api = '<?php echo base_url('api/user/save');?>';
		$.post( api, apilib.post(inputs) )
		.done(function(response) {
			$('#auth-users').html(response);
		})
		.fail(function(xhr) { 
			apilib.error(xhr);
			authedit.user.input.clean();
		});
	},
},
	
};
</script>

<div class="modal fade" id="user-modal" tabindex="-1" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Edit user details</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
<form autocomplete="off" class="d-grid gap-1" style="grid-template-columns: auto auto;"><?php 

$attrs = ['type' => "hidden"];
echo new \App\Cells\input('id', '', $attrs);

$format = '<label class="form-label">%s</label>%s';
foreach($user_fields as $fldname=>$label) {
	$attrs = match($fldname) {
		'email' => [
			'type' => "email",
		],
		'password' => [
			'type' => "password", 
			'autocomplete' => "new-password",
		],
		default => [
			'type' => "text"
		]
	};
	$input = new \App\Cells\input($fldname, '', $attrs);
	
	printf($format, $label, $input);

}

?></form>
<p>Leave password blank if you don't need to change it.</p>
</div>

<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
<button type="button" onClick="authedit.user.save()" class="btn btn-primary">Save changes</button>
</div>

</div>
</div>
</div>
<?php $this->endSection();
