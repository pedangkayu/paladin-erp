$(function(){

	$('[name="duodate"]').datepicker();
	$('[name="tanggal"]').datepicker();
	
	getdata = function(index, tipe){

		if( tipe != 3){
			$('[data-valmethod="' + index + '"]').html('<option value="">Memuat...</option>');
			$.getJSON(_base_url + '/ajax/paymentmethod', {tipe : tipe}, function(json){
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
		
	remove_method = function(index){
		$('[data-urut="' + index + '"]').remove();
	}

	matematika = function(){
		var grandtotal = $('[name="grandtotal"]').val();
		var total = 0;
		$('[data-method="jumlah"]').each(function(i){
			var _total = $('[data-method="jumlah"]')[i].value;
			total += parseInt(_total);
		});
		var _format = number_format(total,0,',','.');
		$('.jumlah-bayar').html(_format);

		var kembalian = (total - grandtotal);
		if(kembalian > 0)
			$('.jumlah-kembalian').html(number_format(kembalian,0,',','.'));
		else
			$('.jumlah-kembalian').html(0);
	}

	$('.btn-add-payment').click(function(){

		var cnow = Math.random();
		var htm = '\
			<div class="payment-method form-group list-group-item" data-payment="" data-urut="' + cnow + '">\
				<button type="button" class="close" aria-label="Close" onclick="remove_method(' + cnow + ');">\
				  <span aria-hidden="true">&times;</span>\
				</button>\
				<div class="form-group">\
					<label>Jenis Pembayaran</label>\
					<select name="tipe[]" onchange="getdata(' + cnow + ', this.value);" style="width:100%;" required>\
						<option value="">- Pilih TIpe -</option>\
						<option value="1"> BANK </option>\
						<option value="2"> Asuransi </option>\
						<option value="3"> Cash </option>\
					</select>\
				</div>\
				<div class="form-group" data-tipemethod="' + cnow + '">\
					<label>Method</label>\
					<select name="method[]" id="method" style="width:100%;" data-valmethod="' + cnow + '">\
						<option value="">- Pilih Method -</option>\
					</select>\
				</div>\
				<div class="form-group no-asuransi hide" data-asuransi="' + cnow + '">\
					<label>No Asuransi</label>\
					<input type="text" name="no_asuransi[]" class="form-control" />\
				</div>\
				<div class="form-group">\
					<label>Jumlah</label>\
					<input type="number" name="jumlah[]" onkeyup="matematika();" data-method="jumlah" class="form-control text-right" required />\
				</div>\
			</div>\
		';
		var to = $('.item-payment-tambahan');

		to.append(htm);

	});
	
	remove_payment = function(id){

		var c = confirm("Anda yakin ingin menghapusnya ?");
		if(c == true){
			$('[data-removepayment="' + id + '"]').css('opacity', .3);
			$.post(_base_url + '/biling/delpaymentmethod', {id : id}, function(json){
				$('[data-removepayment="' + id + '"]').remove();

				setTimeout(function(){
					matematika();
				}, 1000);
			}, 'json');

		}
		
	}



	mtk = function(){
		var grandtotal = $('[name="grandtotal"]').val();

		var status_saldo =  $('#dgn_saldo').prop('checked');
		var saldo = $('[name="saldo"]').val();
		
		var total = status_saldo ? parseInt(saldo) : 0;
		$(':input[data-total="total"]').each(function(i){
			total += parseInt($(this).val());
		});
		
		var _grandtotal = parseInt(grandtotal) - parseInt(total);

		return _grandtotal < 0 ? 0 : _grandtotal;

	}

	item_pyment = function(){
		var tmp = $('#tmp-payments').html();
		var nrow =  Math.random();
		
		var __grandtotal = mtk();

		var $out = '<tr class="item-payment-tr" data-itempayment="' + nrow + '">\
			<td>\
				<select name="id_payment_method[]" onchange="type_method(' + nrow + ', this.value);" class="form-control" data-select="' + nrow + '">' + tmp + '</select>\
			</td>\
			<td>\
				<select name="id_payment_method_item[]" class="form-control" data-paymentmethod="' + nrow + '" required><option value="">-Pilih-</option></select>\
			</td>\
			<td><input type="text" name="referensi[]" class="form-control" placeholder="No Asuransi / Bank / dll." title="No Asuransi / Bank / dll." /></td>\
			<td><input type="number" value="' + __grandtotal + '" name="total_payment[]" class="form-control text-right" data-total="total" /></td>\
			<td><button class="btn btn-white btn-xs" onclick="del_payment(' + nrow + ');"><i class="fa fa-trash"></i></button></td>\
		</tr>';

		$('.item-payment').append($out);
	}

	item_pyment();

	$('#dgn_saldo').change(function(){
		var status = $(this).prop('checked');
		if(status){
			$('.dgn_saldo').removeClass('hide');
		}else{
			$('.dgn_saldo').addClass('hide');
		}

		$('.item-payment-tr').remove();
	});


	del_payment = function(id){
		$('[data-itempayment="' + id + '"]').remove();
	}
	
	type_method = function(id, id_payment){
		$('[data-paymentmethod="' + id + '"]').html('<option value="">Memuat...</option>');
		$.getJSON(_base_url + '/biling/tipemethod', { id_payment_method : id_payment }, function(json){
			$('[data-paymentmethod="' + id + '"]').html(json.content);
		});
	}

	validasi = function(id){

		var id = $('[name="id_pasien"]').val();

		console.log(id);
		swal({   
			title: "Anda yakin ?",   
			text: "Akan Melakukan Validasi!",   
			type: "warning",   
			showCancelButton: true,   
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "Yes, !",   
			closeOnConfirm: true
		}, function(){

		$('.btn-validasi').button('loading');
		$.post(_base_url + '/biling/validasi', {id : id}, function(json){
			$('.btn-validasi').addClass('hide');

			window.location = window.location.href;

			$('.btn-validasi').button('reset');
		}, 'json');
	});
}

});