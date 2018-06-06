$(function(){
	close_sidebar();
	$('[name="duodate"]').datepicker();

	matematika = function(){

		var _adjustment = $('input[name="adjustment"]').val();

		var _subtotal = 0;
		var _grandtotal = 0;
		$(':input[data-nilai="qty"]').each(function(i){
			var qty = $(':input[data-nilai="qty"]')[i].value;
			var total = $(':input[data-nilai="total"]')[i].value;
			var diskon = $(':input[data-nilai="diskon"]')[i].value;
			
			var _total = qty * total;
			var _aftdiskon = (_total * diskon) / 100;
			var subtotal = _total - _aftdiskon;

			//console.log(i, "qty : " + qty, "total : " + total, "diskon : " + diskon, "subtotal : " + subtotal);

			$(':input[data-nilai="subtotal"]')[i].value = subtotal;
			_subtotal += subtotal;
		});
		
		_grandtotal = parseInt(_subtotal) + parseInt(_adjustment);

		//console.log("grandtotal : " + _grandtotal);
		fisinsh_hitung();


		$('[name="subtotal"]').val(_subtotal);
		$('[name="grandtotal"]').val(_grandtotal);

		$('.view-total').html(number_format(_subtotal,0,',','.'));
		$('.view-grandtotal').html(number_format(_grandtotal,0,',','.'));
	}

	btn_hitung = function(){
		$('.btn-hitung').removeClass('hide');
		$('.btn-update').addClass('hide');
	}
	fisinsh_hitung = function(){
		$('.btn-hitung').addClass('hide');
		$('.btn-update').removeClass('hide');
	}

	$('[type="number"]').keyup(function(){
		btn_hitung();
	});

	$('.btn-hitung').click(function(){
		matematika();
	});

});