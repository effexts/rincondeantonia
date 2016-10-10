jQuery(document).ready(function($){

	// default state
	if ($('#mark_as_new').is(':checked')) {
		$("#new_start, #new_expiry").removeAttr('disabled');
	} else {
		$("#new_start, #new_expiry").attr('disabled', 'disabled');
	}
	
	// when mark as new is clicked
	$('#mark_as_new').click(function(){
		if ($(this).is(':checked')) {
			$("#new_start, #new_expiry").removeAttr('disabled');
		} else {
			$("#new_start, #new_expiry").attr('disabled', 'disabled');
		}
	});
	
	// pick date in product new mark
	$("#new_start, #new_expiry" ).datepicker({
		dateFormat: "yy-mm-dd",
		minDate: 0
	});
	
	// default stock
	if ($('#status').val() == 'sold') {
		$("#stock").attr('disabled', 'disabled');
	} else {
		$("#stock").removeAttr('disabled');
	}
	
	// when stock status is switched
	$('#status').change(function(){
		if ($(this).val() == 'sold') {
			$("#stock").attr('disabled', 'disabled');
		} else {
			$("#stock").removeAttr('disabled');
		}
	});

});