$(function(){

	//close_sidebar();
	$('[name="tanggal"]').datepicker();
	$('[name="duodate"]').datepicker();

	$('.cari-pasien').click(function(){
		// loadpamy();
	});



	$('.btn-hapus').click(function(){
		var $id = $('[name="id_delete"]').val();
		$('[data-item="' + $id + '"]').remove();
		$('[name="id_delete"]').val(0);
		$('.btn-hapus').hide();

	});
	$('.btn-delete').click(function(){
		var $id = $('[name="id_hapus"]').val();
		$('[data-item="' + $id + '"]').remove();
		$('[name="id_hapus"]').val(0);
		$('.btn-delete').hide();

	});
	$('.btn-deletecampur').click(function(){
		var $id = $('[name="id_hapuscampur"]').val();
		$('[data-campur="' + $id + '"]').remove();
		$('[name="id_hapuscampur"]').val(0);
		$('.btn-deletecampur').hide();

		// matematika();
	});

	$('#tab-4 a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});

	/* Load data Barang */
	loaditems = function(page){

		var kode = $('[name="modal-kode-item"]').val();
		var barang = $('[name="modal-barang-item"]').val();

		var param = {
			page : page,
			kode : kode,
			barang : barang
		};

		$('.modal-items-list').css('opacity', .3);

		$.getJSON(_base_url + '/resep/loaditems', param, function(json){

			$('.modal-items-list').html(json.content);
			$('.modal-items-pagin').html(json.pagin);
			$('.modal-items-list').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('div.modal-items-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				loaditems($page);
			});
		});
	}
	$('[name="modal-kode-item"], [name="modal-barang-item"]').keyup(function(e){
		if(e.keyCode == 13)add
			loaditems(1);
	});
	$('.btn-search-item').click(function(){
		loaditems(1);
	});

	/*obat campur*/
loadcam = function(page){

		var kode = $('[name="modal-kode-itemc"]').val();
		var barang = $('[name="modal-barang-itemc"]').val();

		var param = {
			page : page,
			kode : kode,
			barang : barang
		};

		$('.modal-cam-list').css('opacity', .3);

		$.getJSON(_base_url + '/resep/loadcam', param, function(json){

			$('.modal-cam-list').html(json.content);
			$('.modal-cam-pagin').html(json.pagin);
			$('.modal-cam-list').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('div.modal-cam-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				loadcam($page);
			});
		});
	}
	$('[name="modal-kode-itemc"], [name="modal-barang-itemc"]').keyup(function(e){
		if(e.keyCode == 13)add
			loadcam(1);
	});
	$('.btn-search-itemc').click(function(){
		loadcam(1);
	});
	/* Load  nama pasien dari mssql */
	loadpa = function(page){

		var ID_PASIEN = $('[name="modal-id_pasien"]').val();
		var NAMA_PASIEN = $('[name="modal-nama_pasien"]').val();
		var param = {
			page : page,
			ID_PASIEN : ID_PASIEN,
			NAMA_PASIEN : NAMA_PASIEN
		};
		$('.modal-po-list').css('opacity', .3);

		$.getJSON(_base_url + '/resep/loadpa', param, function(json){

			$('.modal-po-list').html(json.content);
			$('.modal-po-pagin').html(json.pagin);
			$('.modal-po-list').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('div.modal-po-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				loadpa($page);
			});
		});
	}
	$('[name="modal-id_pasien"]').keyup(function(e){
		if(e.keyCode == 13)
			loadpa(1);
	});
	$('[name="modal-nama_pasien"]').change(function(){
		loadpa(1);
	});
	$('.btn-search-pasien').click(function(){
		loadpa(1);
	});


	// //obat campur//
	loaditemso = function(page){

		var kode = $('[name="modal-kode-itemo"]').val();
		var barang = $('[name="modal-barang-itemo"]').val();

		var param = {
			page : page,
			kode : kode,
			barang : barang
		};

		$('.modal-items-listo').css('opacity', .3);

		$.getJSON(_base_url + '/resep/loaditemso', param, function(json){

			$('.modal-items-listo').html(json.content);
			$('.modal-items-pagin').html(json.pagin);
			$('.modal-items-listo').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('div.modal-items-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				loaditemso($page);
			});
		});
	}
	$('[name="modal-kode-itemo"], [name="modal-barang-itemo"]').keyup(function(e){
		if(e.keyCode == 13)
			loaditemso(1);
	});
	$('.btn-search-item').click(function(){
		loaditemso(1);
	});
	/*  Penambaha Item  */
	add_item = function(id){
		$('.barang-' + id).css('opacity', .3);
		$('.btn-item-' + id).remove();
		$('.item-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/resep/additem', {id : id}, function(json){
			var $htm = '\
				<tr onclick="id_delete(' + json.item.id_barang + ');" class="item-barang" data-item="' + json.item.id_barang + '">\
					<td>\
						<input type="hidden" value="' + json.item.id_barang + '" name="id_barang[]">\
						<input type="hidden" value="' + json.item.id_item_gudang + '" name="id_item_gudang[]">\
						<input type="text" value="' + json.item.nm_barang + '" name="nm_barang[]" readonly="readonly" class="form-control" required></td>\
					<td>\
						<div class="input-group input-group-sm">\
							<input type="number" min="1" data-form="jumlah_out" onchange="changeqty(this.value, ' + (json.item.masuk - json.item.keluar) + ');" value="0" name="jumlah_out[]" class="form-control text-right" required>\
						  	<span class="input-group-addon">' + json.item.nm_satuan + '</span>\
						  	<input type="hidden" name="id_satuan[]" value="' + json.item.id_satuan + '" />\
						</div>\
					</td>\
					<td>\
						<select name="id_resep_aturan[]" class="form-control">' + json.pakai + '</select>\
						<input type="hidden" data-form="harga_jual" value="' + json.item.harga_jual + '" name="harga_jual[]" class="form-control text-right" required>\
						<input type="hidden" data-form="status_obat" value="1" name="status_obat[]" class="form-control text-right" required>\
						<input type="hidden" readonly="readonly"  value="' + json.item.id_gudang + '" name="id_gudang[]" class="form-control text-right" required>\
					</td>\
					<td>\
						<input type="text" name="keterangan[]" value="" class="form-control" >\
					</td>\
					<td>\
						<input type="text" readonly="readonly" data-form="total" value="' + number_format(json.item.harga_jual,0,'','') + '" name="total[]" class="form-control text-right" required>\
				</td>\
				</tr>\
			';
			$('.content-item').append($htm);
			$('.barang-' + json.item.id_barang).remove();
			$('[name="nm_barang"]').val(json.item.nm_barang);
			$('[name="id_barang"]').val(json.item.id_barang);
			$('.form-control').keyup(function(e){
					perhitungan();
				});
				$('.form-control').change(function(e){
					perhitungan();
				});
				perhitungan();
			});
	}
	 // hitung hitungan total harga obat di apotik
		perhitungan = function(){
				var subtotal = 0;

				$(':input[data-form="jumlah_out"]').each(function(i){
					/* PENJUMLAHAN */
					var harga = $(':input[data-form="harga_jual"]')[i].value;
					var kali = harga * $(this).val();

					$(':input[data-form="total"]')[i].value = kali;
					subtotal += kali;
				});

				$('.resep-subtotal').html(number_format(subtotal,2,',','.'));
					$(':input[name="grand_total"]').val(subtotal);
			}

			$('.form-control').keyup(function(e){
				perhitungan();
			});
			$('.form-control').change(function(e){
				perhitungan();
			});

		perhitungan1 = function(){
			var subtotalcampur = 0;

				$(':input[data-form="jumlah_out_campur"]').each(function(i){
					/* PENJUMLAHAN */
					var harga_campur = $(':input[data-form="harga_jual_campur"]')[i].value;
					var kali_campur = harga_campur * $(this).val();

					$(':input[data-form="total_campur"]')[i].value = kali_campur;
					subtotalcampur += kali_campur;
				});

				$('.resep-subtotalcampur').html(number_format(subtotalcampur,2,',','.'));
				$(':input[name="grand_totalcampur"]').val(subtotalcampur);
			}

			$('.form-control').keyup(function(e){
				perhitungan1();
			});
			$('.form-control').change(function(e){
				perhitungan1();
			});

	add_itemc = function(id){


		var $index = 0;
		$('[data-count="campur"]').each(function(i){
			$index++;
		});
		// console.log($index);
		$('.barangc-' + id).css('opacity', .3);
		$('.btn-itemc-' + id).remove();
		$('.itemc-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/resep/additemc', {id : id}, function(json){
			var $htm = '\
				<tr onclick="id_hapus(' + json.item.id_barang + ');" class="item-barang" data-item="' + json.item.id_barang + '">\
					<td>\
						' + json.item.kode + '\
						<input type="hidden" value="' + json.item.id_barang + '" name="id_barang_campur[' + $index + '][]">\
						<input type="hidden" value="' + json.item.id_item_gudang + '" name="id_item_gudang_campur[' + $index + '][]">\
					</td>\
					<td><input type="text" value="' + json.item.nm_barang + '" name="nm_barang_campur[' + $index + '][]" readonly="readonly" class="form-control" required></td>\
					<td>\
						<div class="input-group input-group-sm">\
							<input type="number" min="1" data-form="jumlah_out_campur" onchange="changeqty(this.value, ' + (json.item.masuk - json.item.keluar) + ');" value="0" name="jumlah_out_campur[' + $index + '][]" class="form-control text-right" required>\
						  	<span class="input-group-addon">' + json.item.nm_satuan + '</span>\
						  	<input type="hidden" name="id_satuan_campur[' + $index + '][]" value="' + json.item.id_satuan + '" />\
						</div>\
						<input type="hidden" data-form="harga_jual_campur" value="' + json.item.harga_jual + '" name="harga_jual_campur[' + $index + '][]" class="form-control text-right" required>\
						<input type="hidden" data-form="status_obat" value="2" name="status_obat_campur[' + $index + '][]" class="form-control text-right" required>\
						<input type="hidden" readonly="readonly"  value="' + json.item.id_gudang + '" name="id_gudang_campur[' + $index + '][]" class="form-control text-right" required>\
					</td>\
					<td>\
					<input type="text" readonly="readonly" data-form="total_campur" value="' + number_format(json.item.harga_jual,0,'','') + '" name="total_campur[]" class="form-control text-right" required>\
				</td>\
				</tr>\
			';
			$('.content-itemc').append($htm);
			$('.barangc-' + json.item.id_barang).remove();
			// $('#p').modal('hide');
			$('.form-control').keyup(function(e){
				perhitungan1();
			});
			$('.form-control').change(function(e){
				perhitungan1();
			});

			perhitungan1();
		});
	}
	kirim = function(){

				var $index = 0;
		$('[data-count="campur"]').each(function(i){
			$index++;
		});
		var $urut = $index + 1;
		var content = $('.content-itemc').html();
		//console.log(content);
		var ket = $(".keterangan-campur").html();
		var $_htm = '\
		<table class="table data-count="campur" onclick="id_hapuscampur(' + $index+ ');" class="item-campur" data-campur="' + $index+ '">\
			<tr><td><h4><b>Obat Campur ' + $urut + '</b></h4></td><td><input type="text" name="campur[' + $index + ']" value="" required placeholder="Nama Obat campur" /></td></tr>\
			<thead>\
				<tr>\
					<th width="15%">Kode</th>\
					<th width="35%" class="text-left">Nama Obat</th>\
					<th width="20%" class="text-right">Jumlah </th>\
					<th width="20%">Harga</th>\
				</tr>\
			</thead>\
			<tbody>\
				' + content + '\
			</tbody>\
		</tr>\
			' + ket + '</table>';
		$('.obat-campur').append($_htm);
		$('.content-itemc').html('');

		$('.form-control').keyup(function(e){
			perhitungan1();
		});
		$('.form-control').change(function(e){
			perhitungan1();
		});

		perhitungan1();
	}

	changeqty = function(val, current){

		if(val > current)
			swal('PERINGATAN!', 'StOk tidak cukup! .');
	}

	// $('.form-control').keyup(function(e){
	// 	matematika();
	// });

	add_itempa = function(id){
		$('.pa-' + id).css('opacity', .3);
		$('.btn-pasien-' + id).remove();
		$('.pasien-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/resep/additempa', {id : id}, function(json){
			// console.log(json);
			$htm = '';
			$('.content-item').append($htm);
			$('.pa-' + json.pas.ID_PASIEN).remove();
			$('.pa-' + json.pa.id_pasien).css('opacity', 1);
			$('[name="NAMA_PASIEN"]').val(json.pas.NAMA_PASIEN);
			$('[name="ID_PASIEN"]').val(json.pa.id_pasien_hc);
			$('[name="id_pasien"]').val(json.pa.id_pasien);
			$('[name="alamat_pasien"]').val(json.pas.ALAMAT_PASIEN);
			$('[name="tgllahir_pasien"]').val(json.tgl);
			$('#pasien').modal('hide');
		});
	}
	/*ad data pasien dari mysql*/
	add_itempamy = function(id){
		$('.pamy-' + id).css('opacity', .3);
		$.getJSON(_base_url + '/resep/additempamy', {id : id}, function(json){

			console.log(json);
			$htm = '';
			$('.content-item').append($htm);
			//$('.pa-' + json.pa.ID_PASIEN).remove(); <-- Jangan dihapus
			$('.pa-' + json.pa.id_pasien).css('opacity', 1);
			$('[name="NAMA_PASIEN"]').val(json.pa.nama_pasien);
			$('[name="ID_PASIEN"]').val(json.pa.id_pasien);
			$('#pasien').modal('hide');
		});
	}
	id_delete = function(id){
		$('[name="id_delete"]').val(id);
		$('.btn-hapus').show();
		$('.item-barang').css('background', 'none');
		$('[data-item="' + id + '"]').css('background', '#ddd');
		loaditems(1);
	}
		id_hapus = function(id){
		$('[name="id_hapus"]').val(id);
		$('.btn-delete').show();
		$('.item-barang').css('background', 'none');
		$('[data-item="' + id + '"]').css('background', '#ddd');
		loadcam(1);
	}
		id_hapuscampur = function(id){
		$('[name="id_hapuscampur"]').val(id);
		$('.btn-deletecampur').show();
		$('.item-campur').css('background', 'none');
		$('[data-campur="' + id + '"]').css('background', '#ddd');
		loadcam(1);
	}
	// loadcam(1);
	// loaditems(1);
	// loadpa(1);
});
