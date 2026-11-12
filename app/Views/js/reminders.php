<script>
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
