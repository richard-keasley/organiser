<?php $this->extend('layout');

$this->section('main'); ?>
<fieldset style="max-width:25em">
<div id="house-info">
<div class="input-group my-1">
	<label class="input-group-text">House</label>
	<?php
	$attrs = [
		'name' => "HouseTitle",
		'value' => $house->HouseTitle,
		'class' => "form-control",
		'onKeyup' => "houseedit.house.dirty(this)",
	];
	echo form_input($attrs);
	?>
</div>
<div class="input-group my-1">
	<label class="input-group-text">Last name</label>
	<?php
	$attrs = [
		'name' => "defName2",
		'value' => $house->defName2,
		'class' => "form-control",
		'onKeyup' => "houseedit.house.dirty(this)",
	];
	echo form_input($attrs);
	?>
</div>
</div>
<div class="navbar">
<button type="button" onclick="houseedit.house.update(this)" class="btn btn-sm btn-success" data-cmd="save" title="save house details"><i class="bi bi-check"></i></button>
</div>
</fieldset>

<fieldset>
<legend>People</legend>
<div class="houseedit-table"><?php 
echo $this->include('house/edit-people');
?></div>
</fieldset>

<fieldset>
<legend>Contacts</legend>
<div class="houseedit-table"><?php 
echo $this->include('house/edit-contacts');
?></div>
</fieldset>

<fieldset>
<legend>Groups</legend>
<div class="houseedit-table"><?php 
echo $this->include('house/edit-groups');
?></div>
</fieldset>

<fieldset>
<legend>Events</legend>
<div class="houseedit-table"><?php 
echo $this->include('house/edit-events');
?></div>
</fieldset>
<?php $this->endSection();

$this->section('nav');
$href = "view/{$house->HouseID}";
$label = '<i class="bi bi-eye"></i>';
$attrs = [
	'class' => "btn btn-outline-dark",
	'title' => "done",
];
echo anchor($href, $label, $attrs);

$label = '<i class="bi bi-trash"></i>';
$attrs = [
	'class' => "btn btn-danger",
	'title' => "delete this house",
	'data-bs-toggle' => "modal",
	'data-bs-target' => "#delModal",
];
echo form_button($attrs, $label);

$this->endSection();

$this->section('bottom'); ?>
<script>
const houseedit = {

tr: {
	dirty: function(el) {
		$(el).closest('tr').addClass('table-warning');
	},

	clean: function(input, values) {
		switch(input.cmd) {
			case 'delete':
			input.tr.remove();
			break;
			
			default:		
			for(const name in values) {
				input.tr.find(`[name=${name}]`).val(values[name]);
			}
			input.clean();
		}
	},

	update: function(el, api) {
		var input = new trinputs(el);
		if(input.cmd=='delete') {
			var confirmed = confirm('Delete this row?');
			if(!confirmed) return false;
		}
		
		input.dirty();
		input.inputs.HouseID = <?php echo $house->HouseID;?>;
		
		api = `${api}/${input.cmd}`;
		$.post( api, apilib.post(input.inputs) )
		.done(function(response) {
			switch(input.cmd) {
				case 'insert':
				$(el).closest('.houseedit-table').html(response);
				break;
				
				default:
				houseedit.tr.clean(input, response);
			}
		})
		.fail(function(xhr) { 
			apilib.error(xhr);
		});
	},
	
	update2: function(el, api) {
		return new Promise(function(resolve, reject) {
			var input = new trinputs(el);
			
			if (input.cmd == 'delete') {
				var confirmed = confirm('Delete this row?');
				if (!confirmed) {
					reject(new Error('Delete cancelled by user.'));
					return;
				}
			}
			
			input.dirty();
			input.inputs.HouseID = <?php echo $house->HouseID;?>;
			
			api = `${api}/${input.cmd}`;
			
			$.post(api, apilib.post(input.inputs))
				.done(function(response) {
					switch(input.cmd) {
						case 'insert':
						$(el).closest('.houseedit-table').html(response);
						break;
							
						default:
						houseedit.tr.clean(input, response);
					}
					resolve();
				})
				.fail(function(xhr) {
					apilib.error(xhr);
					reject(xhr);
				});
		});
	}
},

house: {
	dirty: function(el) {
		var container = $(el).closest('fieldset');
		container.addClass('bg-warning bg-opacity-25');
	},
	
	update: function(el) {
		var $src = $('#house-info');
		var fldname, fldvalue;
		var postvals = {};
		var elnames = ['input'];
		var api = '<?php echo base_url("api/house/update/{$house->HouseID}");?>';
		el.disabled = true;
		
		for(let elname of elnames) {
			$src.find(elname).each(function() {
				fldname = $(this).attr("name");
				fldvalue = $(this).val();
				postvals[fldname] = fldvalue;
			});			
		}
		
		$.post( api, apilib.post(postvals) )
		.done(function(response) {
			window.location.reload();
		})
		.fail(function(xhr) { 
			apilib.error(xhr);
		})
		.always(function() {
			el.disabled = false;
		});
	},
},

events: {
	api: '<?php echo base_url('api/event'); ?>',
	
	description: function(el) {
		var input = new trinputs(el);
		input.dirty();
		
		var evdes = [];
		input.tr.find('[data-fldname=evdes_select]').each(function() {
			evdes.push( $( this ).find(":selected").text() );
		});
		input.tr.find('[name=EvDes]').val(evdes.join(': '));
		
		if(el.name=='EvTypeID') {
			input.tr.find('[name=EvDate]').val('');
			input.tr.find('[name=RemDate]').val('');
		}
	},
	
	nextdate: function(el) {
		var nextdate = el.dataset.nextdate ?? null;
		if(!nextdate) return;

		var input = new trinputs(el);
		input.dirty();
		input.tr.find('[name=EvDate]').val(nextdate);
		input.tr.find('[name=RemDate]').val('');
	},

	update: function(el) {
		houseedit.tr.update(el, this.api);
	},
},

contacts: {
	api: '<?php echo base_url('api/contact'); ?>',
	
	description: function(el) {
		var input = new trinputs(el);
		input.dirty();
		
		var rowdes = [];
		input.tr.find('[data-fldname=des_select]').each(function() {
			rowdes.push( $( this ).find(":selected").text() );
		});
		input.tr.find('[name=ConDes]').val(rowdes.join(': '));
		
		var $src = input.tr.find('[name=ConAdd]');
		var srcval = $src.val();
		var contypeid = input.tr.find('[name=ConTypeID]').val();
		var template = $(`template#tmpl-contype-${contypeid}`).html();
		var $td = $src.closest('td');
		
		$td.html(template);
		$td.find('[name=ConAdd]').val(srcval);
	},
	
	update: function(el) {
		houseedit.tr.update(el, this.api);
	},
},

groups: {
	api: '<?php echo base_url("api/house/group/{$house->HouseID}");?>',
	
	update: function(el) {
		var input = new chkinput(el);
		input.dirty();
	
		var postvals = {
			group: parseInt(input.name),
			value: input.checked
		};
		
		$.post( this.api, apilib.post(postvals) )
		.done(function(response) {
			$(el).closest('.houseedit-table').html(response);
		})
		.fail(function(xhr) { 
			apilib.error(xhr);
		});
	},
},

people: {
	api: '<?php echo base_url("api/person");?>',
	
	update: function(el) {
		houseedit.tr.update2(el, this.api)
		.then(function() {
			console.log('OK');
		})
		.catch(function(error) {
			console.error(error);
			alert(error.message);
		});
	},
},

};
</script>

<!-- Modal -->
<div class="modal fade" id="delModal" tabindex="-1" aria-hidden="true">
<div class="modal-dialog">
<?php 
$hidden = ['delhouse' => (string) $house->HouseID];
$attrs = ['class' => "modal-content"];
echo form_open(base_url(), $attrs, $hidden);
?>

<div class="modal-header">
<h5 class="modal-title">Delete <?php echo esc($house->HouseTitle);?></h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
<p>Are you sure you want to delete this house and all related records. There is no way to "undo" this.</p>
</div>

<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
<button type="submit" class="btn btn-danger">Delete</button>
</div>

<?php echo form_close(); ?>
</div>
</div>
<?php $this->endSection();
