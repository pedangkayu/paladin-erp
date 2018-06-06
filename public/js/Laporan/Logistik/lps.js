$(function(){

	$('.btn-proses').click(function(){

		var gudang = $('[name="gudang"]').val();

		try{

			$.getJSON(_base_url + '/reportlogistik/lpsajax', {gudang : gudang}, function(json){
				$('.result').html(json.content);
				$('.total').html(json.total);
			});

		}catch(e){
			swal(e);
		}

		

	});

});