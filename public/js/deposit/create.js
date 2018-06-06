$(function(){

	$('[name="duodate"]').datepicker();
	getdata = function(index, tipe){
		// alert('hayoo');
			if( tipe != 3){
				$('[data-valmethod="' + index + '"]').html('<option value="">Memuat...</option>');
				$.getJSON(_base_url + '/ajax/paymentmethoddeposit', {tipe : tipe}, function(json){
					$('[data-valmethod="' + index + '"]').html(json.content);
				});
			}
			if(tipe == 2) {
			$('[data-asuransi="' + index + '"]').removeClass('hide').attr('required', 'required');
			$('[data-tipemethod="' + index + '"]').removeClass('hide').attr('required', 'required');
		}else if(tipe == 3){
			$('[data-tipemethod="' + index + '"]').addClass('hide').removeAttr('required');;
		}else{
			$('[data-asuransi="' + index + '"]').addClass('hide').removeAttr('required');
			$('[data-tipemethod="' + index + '"]').removeClass('hide').attr('required', 'required');
		}
		}	

});