$(function(){

	//close_sidebar();

	$('[name="tanggal"]').datepicker();
	$('[name="duodate"]').datepicker();

	customers = function(id){
		$.getJSON(_base_url + '/fakturpendapatan/customers', { select : id }, function(json){
			$('.customer').html('<select name="customer" required id="customer" style="width:100%;"><option value="">Memuat...</option></select>');
			$('[name="customer"]').html(json.content);
			$('[name="customer"]').select2();


			$('[name="customer"]').change(function(){
				var id = $(this).val();
				$('[name="alamat"]').val('').attr('placeholder', 'Memuat...');
				$.getJSON(_base_url + '/fakturpendapatan/alamat', {id : id}, function(json){
					$('[name="alamat"]').val(json.alamat).removeAttr('placeholder');;
				});
			});

		});
	}

	$('.simpan-pelanggan').click(function(){
		// console.log($alamat);
		var nm_payer = $('[name="nm_payer"]').val();
		var nm_last = $('[name="nm_last"]').val();
		var alamat = $('[name="alamat_customer"]').val();
		var telpon = $('[name="telpon"]').val();
		var fax = $('[name="fax"]').val();
		var email = $('[name="email"]').val();

		try{

			if(nm_payer.length < 1)
				throw "Nama Depan tidak boleh kosong!";
			if(nm_last.length < 1)
				throw "Nama Belakang tidak boleh kosong!";
			if(alamat.length < 1)
				throw "Nama Alamat tidak boleh kosong!";
			if(telpon.length < 1)
				throw "Nama Telpon tidak boleh kosong!";

    		$(this).button('loading');

			var param = {
				nm_payer : nm_payer,
				nm_last : nm_last,
				alamat : alamat,
				telpon : telpon,
				fax : fax,
				email : email,

			};

			$.post(_base_url + '/fakturpendapatan/addcustomer', param, function(json){
				customers(json.id_payer);
				$('[name="alamat"]').val(json.alamat);
				$('[data-toggle="input"]').val('');
				$('#pelanggan').modal('hide');
				$('.simpan-pelanggan').button('reset');
				swal('Sukes!', json.nm_payer + ' berhasil tersimpan!');
			}, 'json')

		}catch(e){
			swal('PERINGATAN!', e);
		}
	});
	
	$('.add-new-blank').click(function(){
		var $id = Math.random();
		var $htm = '\
			<tr onclick="id_delete(' + $id + ');" class="item-barang" data-item="' + $id + '">\
				<td><input type="hidden" value="0" name="id_barang[]">\
				<input type="text" name="deskripsi[]" class="form-control" required></td>\
				<td>\
					<input type="number" data-form="qty" value="1" name="qty[]" class="form-control text-right" required>\
					<input type="hidden" name="id_satuan[]" value="0" />\
				</td>\
				<td><input type="number" data-form="diskons" value="0" name="diskons[]" class="form-control text-right" required></td>\
				<td><input type="number" data-form="harga" value="0" name="harga[]" class="form-control text-right" required></td>\
				<td><input type="number" data-form="total" value="0" name="total[]" class="form-control text-right" readonly="readonly" required></td>\
			</tr>\
		';
		$('.content-item').append($htm);
		$('.form-control').keyup(function(e){
			matematika();
		});
	});

	$('.btn-hapus').click(function(){
		var $id = $('[name="id_delete"]').val();
		$('[data-item="' + $id + '"]').remove();
		$('[name="id_delete"]').val(0);
		$('.btn-hapus').hide();

		matematika();
	});

	$('#tab-4 a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});



	/* MATEMATIKA */
	// matematika = function(){
	// 	var subtotal = 0;
	// 	var diskon = $(':input[name="diskon"]').val();
		
	// 	$(':input[data-form="qty"]').each(function(i){
	// 		/* PENJUMLAHAN */
	// 		var harga = $(':input[data-form="harga"]')[i].value;
	// 		var diskons = ($(':input[data-form="harga"]')[i].value * $(':input[data-form="diskons"]')[i].value) / 100;
	// 		var aftdiskon = harga - diskons;
	// 		var kali = aftdiskon * $(this).val();

	// 		$(':input[data-form="total"]')[i].value = kali;
	// 		subtotal += kali;
	// 	});

	// 	var totaldiskon = (subtotal * diskon) / 100;
	// 	var gaftdiskon = subtotal - totaldiskon;
	// 	var ppn = gaftdiskon * $('[name="ppn"]').val() / 100;
	// 	var total = gaftdiskon + ppn + parseInt($(':input[name="adjustment"]').val());



	// 	$('.faktur-subtotal').html(number_format(subtotal,2,',','.'));
	// 	$('.faktur-ppn').html(number_format(ppn,2,',','.'));
	// 	$('.faktur-total').html(number_format(total,2,',','.'));

	// 	$(':input[name="subtotal"]').val(subtotal);
	// 	$(':input[name="grandtotal"]').val(total);
	// }

	matematika = function(){
		var subtotal = 0;
		var diskon = 0;
		
		$(':input[data-form="qty"]').each(function(i){
			/* PENJUMLAHAN */
			var harga = $(':input[data-form="harga"]')[i].value;
			var diskons = ($(':input[data-form="harga"]')[i].value * $(':input[data-form="diskons"]')[i].value) / 100;
			var aftdiskon = harga - diskons;
			var kali = aftdiskon * $(this).val();

			$(':input[data-form="total"]')[i].value = kali;
			subtotal += kali;
		});

		var totaldiskon = (subtotal * diskon) / 100;
		var gaftdiskon = subtotal - totaldiskon;
		var ppn = gaftdiskon * $('[name="ppn"]').val() / 100;
		var total = gaftdiskon + ppn + parseInt($(':input[name="adjustment"]').val());

		

		$('.faktur-subtotal').html(number_format(subtotal,2,',','.'));
		$('.faktur-diskon').html(number_format(totaldiskon,2,',','.'));
		$('.faktur-ppn').html(number_format(ppn,2,',','.'));
		$('.faktur-total').html(number_format(total,2,',','.'));
		$('[name="total_ppn"]').val(ppn);

		$(':input[name="subtotal"]').val(subtotal);
		$(':input[name="grandtotal"]').val(total);
		//$(':input[name="jumlah_pembayaran"]').val(total);
	}

	$('.form-control').keyup(function(e){
		matematika();
	});
	$('.form-control').change(function(e){
		matematika();
	});

	id_delete = function(id){
		$('[name="id_delete"]').val(id);
		$('.btn-hapus').show();
		$('.item-barang').css('background', 'none');
		$('[data-item="' + id + '"]').css('background', '#ddd');

	}
});