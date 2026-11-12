<?php $this->extend('layout');

$this->section('main');

$link_label = '<i class="bi bi-eye"></i>';
$link_attrs = [
	'class' => "btn btn-sm btn-outline-primary"
];

$grp_attrs = [
	'class' => "form-control",
	'onChange' => "groupsedit.dirty(this)",
	'placeholder' => "group name",
];

$attrs = [
	'type' => "button",
	'class' => "btn btn-sm btn-success",
	'data-cmd' => "update",
	'onClick' => "groupsedit.update(this)",
	'title' => "Save",
];
$label = '<i class="bi bi-check"></i>';
$btn_save = trim(form_button($attrs, $label));

$attrs = [
	'type' => "button",
	'class' => "btn btn-sm btn-danger",
	'data-cmd' => "delete",
	'onClick' => "groupsedit.update(this)",
	'title' => "remove this group",
];
$label = '<i class="bi bi-trash"></i>';
$btn_delete = trim(form_button($attrs, $label));

$tbody = [];
foreach(model('Groups')->orderBy('GrpDes')->findAll() as $group) {
	$tr = [
		'group' => new \App\Cells\input("GrpDes", $group['GrpDes'], $grp_attrs),
		'_' => [
			anchor("setup/groups/{$group['GroupID']}", $link_label, $link_attrs),
			$btn_save,
			$btn_delete,
		],
	];
	
	$tr['_']= implode('&nbsp;', $tr['_']);
	$attrs = ['type' => "hidden"];
	$tr['_'] .= new \App\Cells\input('GroupID', $group['GroupID'], $attrs);
	
	$tbody[] = $tr;
}

// new entry
$attrs = [
	'type' => "button",
	'class' => "btn btn-sm btn-success",
	'data-cmd' => "insert",
	'onClick' => "groupsedit.update(this)",
	'title' => "Add new group",
];
$label = '<i class="bi bi-plus"></i>';
$btn_save = trim(form_button($attrs, $label));

$tr = [
	'group' => new \App\Cells\input("GrpDes", '', $grp_attrs),
	'_' => $btn_save,
];
$tbody[] = $tr;

$thead = array_keys($tr);
$last = array_key_last($thead);
$thead[$last] = '';
$table = new \App\Cells\Table('responsive');
$table->setHeading($thead);
echo $table->generate($tbody);

$this->endSection();

$this->section('bottom'); ?>
<script>
const groupsedit = {

update: function(el) {
	var input = new trinputs(el);
	if(input.cmd=='delete') {
		var confirmed = confirm('Delete this group?');
		if(!confirmed) return false;
	}
	
	input.dirty();
	var api = `<?php echo base_url("api/groups");?>/${input.cmd}`;
	el.disabled = true;
		
	$.post( api, apilib.post(input.inputs) )
	.done(function(response) {
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
