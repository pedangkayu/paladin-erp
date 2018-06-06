$(function(){

	$('[name="tgl_faktur"]').datepicker();
	$('[name="duodate"]').datepicker();
	$('#tgl_rinap').datepicker();
	

	$.getJSON(_base_url + '/biling/pasiens', {}, function(json){
		//console.log(json);
		$pasien = $('#pasien');
		$pasien.typeahead({
		  source: json
		});

		$pasien.change(function(){
			var current = $pasien.typeahead("getActive");
		    if (current) {
		        // Some item from your model is active!
		        if (current.name == $pasien.val()) {
		        	console.log(current);
		        	$('#alamat').val(current.alamat);
		        	$('#no_registrasi').val(current.id);
		        	$('#tgl_lahir').val(current.tgl_lahir);
		        	$('[name="id_pasien"]').val(current.id);
		            // This means the exact match is found. Use toLowerCase() if you want case insensitive match.

		            load_paket();

		        } else {

		        	$('.load').html('-');
			        $('.load').val('');
			        $('#pasien').val('');
			        $('#alamat').val('');
			        $('#no_registrasi').val('');
		        	$('#tgl_lahir').val('');
			        $('[name="id_pasien"]').val('');

		        	swal('PERINGATAN!', 'Tidak ditemukan!');

		            // This means it is only a partial match, you can either add a new item 
		            // or take the active if you don't want new items
		        }
		    } else {
		        $('.load').html('-');
		        $('.load').val('');
		        $('#pasien').val('');
		        $('#alamat').val('');
		        $('#no_registrasi').val('');
		        $('#tgl_lahir').val('');
		        $('[name="id_pasien"]').val('');
		        swal('PERINGATAN!', 'Tidak ditemukan!');
		    }
		});
	});
	

	hapus_pake = function(_class){
		$('.' + _class).remove();
		btn_hitung();
	}

	load_paket = function(){

		var id = $('[name="id_pasien"]').val();
		//console.log(id);
		if(id.length > 0){
			$('.content-item').html('<div class="well"><h4><i class="fa fa-cog fa-spin"></i> Memuat...</h4></div>');
	        $.getJSON(_base_url + '/biling/loadinvoice', {id : id}, function(json){

	        	console.log(json);
	        	$('.content-item').html(json.content);
	        	$('.total').html(json.total);
	        	$('.terbilang').html(json.bilang);
	        	$('.btn-reload').removeClass('hide');
	        	
	        	$('.grandtotal').html(json.total);

	        	$('[name="subtotal"]').val(json.grandtotal);
	        	$('[name="grandtotal"]').val(json.grandtotal);

	        	$('[name="diskon_all"]').val(0);
	        	$('[name="adjustment"]').val(0);

	        	if(json.unverified > 0){
	        		$('.btn-validasi').removeClass('hide');
	        	}else{
	        		$('.btn-validasi').addClass('hide');
	        	}

	        	if(json.unverified == 0){
	        		$('.verified').removeClass('hide');
	        	}else{
	        		$('.verified').addClass('hide');
	        	}

	        	//console.log(json.urut);

	        });
		}else{
			$('.btn-reload').addClass('hide');
			swal('PERINGATAN!', 'Pasien belum ditentukan!');
		}

		
	}

	$('#pasien').focus();

	/* MATEMATIKA */

	btn_hitung = function(){
		$('.btn-hitung').removeClass('hide');
		$('.btn-bayar').addClass('hide');
	}

	fisinsh_hitung = function(){
		$('.btn-hitung').addClass('hide');
		$('.btn-bayar').removeClass('hide');
	}

	matematika = function(){
		var load = $('.btn-hitung').button('loading');
		var grandtotal = 0;
		var no = 1;
		
		$(':input[data-nilai="subtotal"]').each(function(i){

			var tarif_dasar = $(':input[data-nilai="tarif_dasar"]')[i].value;
			var qty = $(':input[data-nilai="qty"]')[i].value;
			var hasil = parseInt(tarif_dasar) * parseInt(qty);
			var harga_dr = ($(':input[data-nilai="persen_dr"]')[i].value * hasil) / 100;
			var harga_rs = ($(':input[data-nilai="persen_rs"]')[i].value * hasil) / 100;
			var diskon = $(':input[data-nilai="diskon"]')[i].value;
			//console.log(tarif_dasar, harga_dr, harga_rs);
			var total = parseInt(harga_dr) + parseInt(harga_rs);
			var aftdiskon = (parseInt(harga_dr) * parseInt(diskon)) / 100;
			var subtotal = parseInt(total) - parseInt(aftdiskon);

			// var persen_rs = 100 - $(':input[data-nilai="persen_dr"]')[i].value;
			// $(':input[data-nilai="persen_rs"]')[i].value = persen_rs;


			$(':input[data-nilai="tarif_dr"]')[i].value = harga_dr;
			// View
			$(':input[data-view="tarif_dr"]')[i].value = number_format(harga_dr,0,',','.');
			$(':input[data-view="tarif_rs"]')[i].value = number_format(harga_rs,0,',','.');

			$(':input[data-nilai="subtotal"]')[i].value = subtotal;
			$(':input[data-view="subtotal"]')[i].value = number_format(subtotal,0,',','.');

			grandtotal += parseInt(subtotal);
			//console.log(subtotal);
			no++;
		});

		//console.log('grand Total', grandtotal);

		var adj = $('[name="adjustment"]').val();
		var _grandtotal = grandtotal + parseInt(adj);

		var nilai_format = 'RP ' + number_format(_grandtotal,0,',','.');
		//$('.total').html(nilai_format);
		$('.grandtotal').html(nilai_format);
		$('[name="grandtotal"]').val(_grandtotal);
		fisinsh_hitung();
		load.button('reset');
	}


	$('[name="adjustment"]').keyup(function(){
		btn_hitung();
	});

	$('[name="diskon_all"]').keyup(function(){
		$(':input[data-nilai="diskon"]').val($(this).val());
		btn_hitung();
	});

	persen_dr = function(){
		btn_hitung();
	}

	diskon = function(){
		$('[name="diskon_all"]').val(0);
		btn_hitung();
	}

	hitung = function(){
		btn_hitung();
	}

	$('.btn-hitung').click(function(){
		matematika();
	});

	/* END MATEMATIKA */

	update_rinap_in_out = function(rinap_id){
		var cekin = $('[data-cekin="' + rinap_id + '"]').val();
		var cekout = $('[data-cekout="' + rinap_id + '"]').val();
		$('.rinap-' + rinap_id).css('opacity', .3);
		$.post(_base_url + '/biling/updateinoutrinap', {

			id_rinap : rinap_id,
			in : cekin,
			out : cekout

		}, function(json){
			$('.rinap-' + json.param.id_rinap).css('opacity', 1);
			$('.rinap-' + json.param.id_rinap).html(json.out);
			btn_hitung();

		}, 'json');
	}

	remove_add_tindakan = function(rand){
		$('.remove_' + rand).remove();
		btn_hitung();
	}

	function randomReal(xmin,xmax) { 
	    // random real number in range {min, max}, including min but excluding max
	    return Math.random() * (xmax - xmin) + xmin;
	}

	hitung_add = function(rand){
		btn_hitung();
	}

	$('.btn-add-tindakan').click(function(){
		var content = $('.content-item-add');

		var def = '<div class="grid simple">\
				<div class="grid-title no-border"></div>\
				<div class="grid-body no-border">\
					<table class="table">\
						<thead>\
							<tr>\
								<th width="35%">Uraian</th>\
								<th width="15%">Qty</th>\
								<th width="15%">Biaya</th>\
								<th width="15%">Diskon</th>\
								<th width="15%">Jumlah</th>\
								<th width="5%"></th>\
							</tr>\
						</thead>\
						<tbody class="content-add-tindakan"></tbody>\
					</table>\
				</div>\
			</div>';

		var rand = Math.floor(randomReal(1,9999999999999999));
		var htm = '<tr class="remove_' + rand + '">\
			<td><input type="text" required name="add_tindakan_uraian[]" style="width:100%;" /></td>\
			<td><input type="number" required value="1" onkeyup="hitung_add(' + rand + ');" name="add_tindakan_qty[]" data-nilai="qty" style="width:100%;" /></td>\
			<td><input type="number" required onkeyup="hitung_add(' + rand + ');" data-nilai="tarif_dasar" value="0" name="add_tindakan_biaya[]" style="width:100%;" /></td>\
			<td>\
				<div class="input-group">\
					<input type="number" required value="0" onkeyup="hitung_add(' + rand + ');" name="add_tindakan_diskon[]" data-nilai="diskon" style="width:100%;" />\
					<span class="input-group-addon">%</span>\
				</div>\
			</td>\
			<td>\
				<input type="text" readonly value="0" style="width:100%;" data-view="subtotal" />\
				<input type="hidden" data-nilai="persen_dr" value="50">\
				<input type="hidden" data-nilai="persen_rs" value="50">\
				<input type="hidden" data-nilai="tarif_dr" value="0">\
				<input type="hidden" data-nilai="subtotal" name="add_tindakan_jumlah[]" value="0">\
				<input type="hidden" data-view="tarif_rs">\
				<input type="hidden" data-view="tarif_dr">\
			</td>\
			<td>\
				<button type="button" class="close" onclick="remove_add_tindakan(\'' + rand + '\');" aria-label="Close">\
				  <span aria-hidden="true">&times;</span>\
				</button>\
			</td>\
		</tr>';

		var tindakan = $('.content-add-tindakan');
		if(content.html().length < 1){
			content.html(def);
		}

		tindakan.append(htm);


	});
	
	var id_pasien  = $('[name="id_pasien"]').val();
	if(id_pasien.length > 0){

		$.getJSON(_base_url + '/biling/pasiens', {id_pasien : id_pasien}, function(json){
			$('#pasien').val(json[0].name);
			$('#alamat').val(json[0].alamat);
			$('#no_registrasi').val(json[0].id);
		    $('#tgl_lahir').val(json[0].tgl_lahir);
		});


		load_paket();
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
			swal('Validasi berhasil', 'Mengambil ulang data paket');

			load_paket();
			$('.btn-validasi').button('reset');
		}, 'json');
	});
}

});