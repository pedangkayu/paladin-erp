
$(function(){

detail_jasa = function(id){
		$('.detail-jasa').html('Memuat...');
			$('.service').html('');
		 // console.log(json);
		$.post(_base_url + '/mastertreatment/detailjasa', {id : id}, function(json){
			console.log(json);
			$('.detail-jasa').html(json.content);
			$('.service').html(json.service);
		}, 'json');
	}
// ---------------//
	$('#add-new-blank-jasa').click(function(event) {
		//alert('y');
		var $id = Math.random();
		$.getJSON(_base_url + '/mastertreatment/addblankjasa', function(res){
			$('.content-item').append(res.content)
		});
	});

	$(document).on('click', '.btn-hapus-jasa', function(event) {
		event.preventDefault();
		// $('#btn-hapus').removeAttr('style');
		$(this).closest('.baris_jasa').remove();
		/* Act on the event */
	});
	
});