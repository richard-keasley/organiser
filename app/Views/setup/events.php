<?php 

use \App\Models\EventTypes;

$this->extend('layout');

$this->section('main');

$des_attrs = [
	'class' => "form-control",
	'placeholder' => "description",
	'onChange' => "evtypesedit.dirty(this)",
	'style' => "width:10em;",
];
$rem_attrs = [
	'class' => "form-control",
	'type' => "number",
	'style' => "width:5em",
	'onChange' => "evtypesedit.dirty(this)",
];
$col_attrs = [
	'class' => "form-control",
	'type' => "color",
	'onChange' => "evtypesedit.dirty(this)",
];
$per_opts = [
	EventTypes::NONE => '-',
	EventTypes::YEAR => 'year',
	EventTypes::MONTH => 'month',
	EventTypes::WEEK => 'week',
];
$per_attrs = [
	'class' => "form-control",
	'style' => "width:5em",
	'onChange' => "evtypesedit.dirty(this)",
];

$attrs = [
	'type' => "button",
	'class' => "btn btn-sm btn-success",
	'data-cmd' => "update",
	'onClick' => "evtypesedit.update(this)",
	'title' => "Save",
];
$label = '<i class="bi bi-check"></i>';
$btn_save = trim(form_button($attrs, $label));

$attrs = [
	'type' => "button",
	'class' => "btn btn-sm btn-danger",
	'data-cmd' => "delete",
	'onClick' => "evtypesedit.update(this)",
	'title' => "remove this group",
];
$label = '<i class="bi bi-trash"></i>';
$btn_delete = trim(form_button($attrs, $label));

$evtypes = model('EventTypes')->orderBy('EvTypeDes')->findAll();
$tbody = [];
foreach($evtypes as $evtype) {
	$hexcol = colour_dechex($evtype['EvTypeFontColour']);
	
	$tr = [
		'description' => new \App\Cells\input('EvTypeDes', $evtype['EvTypeDes'], $des_attrs),
		'period' => new \App\Cells\input\select('EvTypeFreq', $evtype['EvTypeFreq'], $per_opts, $per_attrs),
		'reminder' => new \App\Cells\input('EvTypeRemDays', $evtype['EvTypeRemDays'], $rem_attrs),
		'colour' => new \App\Cells\input('EvTypeFontColour', $hexcol, $col_attrs),
		'_' => [$btn_save, $btn_delete],	
	];
	
	$tr['_']= implode('&nbsp;', $tr['_']);
	$attrs = ['type' => "hidden"];
	$tr['_'] .= new \App\Cells\input('EvTypeID', $evtype['EvTypeID'], $attrs);
		
	$tbody[] = $tr;
}

// new entry
$attrs = [
	'type' => "button",
	'class' => "btn btn-sm btn-success",
	'data-cmd' => "insert",
	'onClick' => "evtypesedit.update(this)",
	'title' => "Add new event type",
];
$label = '<i class="bi bi-plus"></i>';
$btn_save = trim(form_button($attrs, $label));

$tr = [
	'description' => new \App\Cells\input('EvTypeDes', '', $des_attrs),
	'period' => new \App\Cells\input\select('EvTypeFreq', '', $per_opts, $per_attrs),
	'reminder' => new \App\Cells\input('EvTypeRemDays', '1', $rem_attrs),
	'colour' => new \App\Cells\input('EvTypeFontColour', '', $col_attrs),
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
const evtypesedit = {

update: function(el) {
	var input = new trinputs(el);
	if(input.cmd=='delete') {
		var confirmed = confirm('Delete this event type?');
		if(!confirmed) return false;
	}
	
	input.dirty();
	var api = `<?php echo base_url("api/evtypes");?>/${input.cmd}`;
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
