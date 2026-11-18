<script>
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

api: '<?php echo base_url('api/events/remdate');?>',
	
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
<?php

$links = [
[
	'src' => "https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js",
	'integrity' => "sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI",
	'crossorigin' => "anonymous",
],
];
foreach($links as $attrs) echo script_tag($attrs);
