$(function(){

	//close_sidebar();

	$('#add-new-blank').click(function(event) {
		//alert('gv');
		var $id = Math.random();
		$.getJSON(_base_url + '/Setinghc/addblankform',  function(res){
			$('.content-item').append(res.content);
		});
	});

	$(document).on('click', '.btn-hapus', function(event) {
		event.preventDefault();
		// $('#btn-hapus').removeAttr('style');
		$(this).closest('.baris_form').remove();
		/* Act on the event */
	});
	
});