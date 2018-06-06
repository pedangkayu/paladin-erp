$(function(){

	//close_sidebar();
	$('[name="tanggal"]').datepicker();
	$('[name="duodate"]').datepicker();
	$('.cari-pasien').click(function(){
		// loadpamy();
	});
	//Hapus form tindakan //
	$('.btn-hapus2').click(function(){
		var $id = $('[name="id_delete"]').val();
		$('[data-tindakanaturan="' + $id + '"]').remove();
		$('[name="id_delete"]').val(0);
		$('.btn-hapus2').hide();
	});
	$('.btn-hapusjasaku').click(function(){
		var $id = $('[name="id_hapusjasaku"]').val();
		$('[data-jasaku="' + $id + '"]').remove();
		$('[name="id_hapusjasaku"]').val(0);
		$('.btn-hapusjasaku').hide();
	});
	$('.btn-hapus4').click(function(){
		var $id = $('[name="id_delete4"]').val();
		$('[data-itematuran="' + $id + '"]').remove();
		$('[name="id_delete4"]').val(0);
		$('.btn-hapus4').hide();
	});
		$('.btn-hapus5').click(function(){
		var $id = $('[name="id_hapuspaket"]').val();
		$('[data-paket="' + $id + '"]').remove();
		$('[name="id_hapuspaket"]').val(0);
		$('.btn-hapus5').hide();
	});

	$('#tab-4 a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
	
	
	loadtindakanatur1 = function(page){
		var tindakan = $('[name="modal-kode-tindakan1"]').val();
		var grup = $('[name="modal-nama-tindakan1"]').val();
		var usg = $('[name="modal-grup-tindakan1"]').val();
		var unit_jasa	=$('[name="unit_jasa"]').val();

		var param = {
			page : page,
			grup : grup,
			tindakan : tindakan,
			usg : usg,
			unit_jasa	:unit_jasa
			
		};
		$('.modal-tindakanatur-list1').css('opacity', .3);
		$.getJSON(_base_url + '/treatment/loadtindakanatur1', param, function(json){
			$('.modal-tindakanatur-list1').html(json.content);
			$('.modal-tindakanatur-pagin1').html(json.pagin);
			$('.modal-tindakanatur-list1').css('opacity', 1);
			$('body').css('cursor', 'default');
			onDataCancel();
			$('div.modal-tindakanatur-pagin1 > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				loadtindakanatur1($page);
			});
		});
	}
	$('[name="modal-kode-tindakan1"]').keyup(function(e){
		if(e.keyCode == 13)
			loadtindakanatur1(1);
	});
	$('[name="modal-nama-tindakan1"]').change(function(){
		loadtindakanatur1(1);
	});
	$('[name="modal-grup-tindakan1"]').change(function(){
		loadtindakanatur1(1);
	});
	$('.btn-search-tindakan1').click(function(){
		loadtindakanatur1(1);
	});
	reseptretment = function(service_kode,page,kode_service){
		 $('#reseptreatment').modal('show');

		 // Load data BHP
		var kode = $('[name="modal-kode-itematuran"]').val();
		var barang = $('[name="modal-barang-itematuran"]').val();
		var unit	=$('[name="unit"]').val();

		var param = {
			page : page,
			kode : kode,
			barang : barang,
			kode_service :kode_service,
			unit : unit
		};

		$('.modal-itemsaturan-list').css('opacity', .3);

		$.getJSON(_base_url + '/treatment/loaditemsaturan', param, function(json){

			$('.modal-itemesaturan-list').html(json.content);
			$('.modal-itemsaturan-pagin').html(json.pagin);
			$('.modal-itemsaturan-list').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('[name="service_kode"]').val(service_kode);

			$('div.modal-itemsaturan-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				reseptretment(service_kode,$page);
			});
		});
	}
	$('[name="modal-kode-itematuran"], [name="modal-barang-itematuran"]').keyup(function(e){
		if(e.keyCode == 13)add
			loaditemsaturan(1);
	});
	$('[name="unit"]').change(function(){
		loaditemsaturan(1);
	});
	$('.btn-search-itematuran').click(function(){
		loaditemsaturan(1);
	});
	
	/* Load data Barang */
	loaditemsaturan = function(page){

		var kode = $('[name="modal-kode-itematuran"]').val();
		var barang = $('[name="modal-barang-itematuran"]').val();
		var unit	=$('[name="unit"]').val();

		var param = {
			page : page,
			kode : kode,
			barang : barang,
			unit	:unit
		};

		$('.modal-itemsaturan-list').css('opacity', .3);

		$.getJSON(_base_url + '/treatment/loaditemsaturan', param, function(json){

			$('.modal-itemsaturan-list').html(json.content);
			$('.modal-itemsaturan-pagin').html(json.pagin);
			$('.modal-itemsaturan-list').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('div.modal-itemsaturan-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				loaditemsaturan($page);
			});
		});
	}
	$('[name="modal-kode-itematuran"], [name="modal-barang-itematuran"]').keyup(function(e){
		if(e.keyCode == 13)
			loaditemsaturan(1);
	});
	$('[name="unit"]').change(function(){
		loaditemsaturan(1);
	});
	$('.btn-search-itematuran').click(function(){
		loaditemsaturan(1);
	});

	
		add_itematuran = function(id){
		var $index = $('[name="service_kode"]').val();
		$('.barangaturan-' + id).css('opacity', .3);
		$('.btn-itematuran-' + id).remove();
		$('.itematuran-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/treatment/additematuran', {id : id}, function(json){
			var $htm = '\
				<tr class="baris_formbhp">\
					<td class="col-sm-3">\
						' + json.item.kode + '\
						<input type="hidden" value="' + json.item.id_barang + '" name="id_barang_item[' + $index + '][]">\
						<input type="hidden" value="' + json.item.id_item_gudang + '" name="id_item_gudang[' + $index + '][]">\
					</td>\
					<td class="col-sm-4">\
						' + json.item.nm_barang + '\
					</td>\
					<td class="col-sm-3">\
						<div class="input-group input-group-sm">\
							<input type="number" data-form="jumlah_out" min="0"  onchange="changeqty(this.value, ' + (json.item.masuk - json.item.keluar) + ');" value="1" name="jumlah_out[' + $index + '][]" class="form-control text-left"  required placeholder="Masukan Jumlahnya"/>\
						  	<span class="input-group-addon">' + json.item.nm_satuan + '</span>\
						  	<input type="hidden" name="tipe['+ $index+'][]" value="1" >\
						  	<input type="hidden" name="id_satuan['+ $index+'][]" value="'+json.item.id_satuan+ '">\
							<input type="hidden" data-form="harga_jual" name="harga_jual[' + $index +'][]" value="' +  json.item.harga_jual+ '"/>\
						</div>\
						<input type="hidden" readonly="readonly" data-form="total" value="" name="total[' + $index + '][]" class="form-control text-right" required>\
					</td>\
					<td class="col-sm-3">\
                        <input type="radio"  name="pakek['+$index+'][]'+json.item.id_barang+'"  value="1">Ya\
                       <input type="radio"  name="pakek['+$index+'][]'+json.item.id_barang+'"  checked="checked" value="0">Tidak<br>\
                       '+json.warna+''+json.akhir+' '+json.item.nm_satuan+'</spin>\
					</td>\
					<td class="col-sm-3">\
					<button title="Hapus BHP Tambahan" type="button" class="btn btn-danger btn-hapus34bhp"><i class="fa fa-trash"></i></button>\
					<input type="hidden"  name="stok['+$index+'][]"  value="'+json.akhir+'"></td>\
				</tr>\
			';
			$('.item-' + $index ).append($htm);
			$('.barangaturan-' + json.item.id_item_gudang).remove();
			// $('.form-control').keyup(function(e){
			// 	perhitungan();
			// });
			// $('.form-control').change(function(e){
			// 	perhitungan();
			// });

			// perhitungan();
				});
			}
	
	add_bhp = function(id){

	$('.barang1-' + id).css('opacity', .3);
	$('.btn-itembarang1-' + id).remove();
	$('.itembarang1-loading-' + id).removeClass('hide');
	$.getJSON(_base_url + '/treatment/addbhp', {id : id}, function(json){
		var $htm = '\
			<tr class="bhp">\
				<td>\
						' + json.item.kode + '\
				</td>\
				 <td>\
					<input type="hidden" value="' + json.item.id_barang + '" name="id_barang[]">\
					<input type="hidden" value="' + json.item.id_item_gudang + '" name="id_item_gudang[]">\
					' + json.item.nm_barang + '\
				</td>\
				<td >\
						<input type="number" data-form="jumlah_out" min="0"  onchange="changeqty(this.value, ' + (json.item.masuk - json.item.keluar) + ');" value=""  class="form-control" placeholder="Masukan Jumlahnya" name="jumlah_out[]"  required>\
					  	<input type="hidden" name="id_satuan[]" value="' + json.item.id_satuan + '" />\
						<input type="hidden" data-form="harga_jual" name="harga_jual[]" value="' +  json.item.harga_jual+ '"/>\
						<input type="hidden" readonly="readonly" data-form="total" value="'+ json.item.harga_jual+'" name="tota[]" class="form-control text-right" required>\
					<input type="hidden" name="tipe[]" value="2" />\
				</td>\
				<td>\
				'+json.item.nm_satuan+'\
				</td>\
				<td><button title="Hapus" type="button" class="btn btn-danger btn-hapus345"><i class="fa fa-trash"></i></button></td>\
			</tr>\
		';

		$('.content-barang1').append($htm);
		$('.barang1-' + json.item.id_barang).remove();
		$('[name="nm_barang"]').val(json.item.nm_barang);
		$('[name="id_barang"]').val(json.item.id_barang);
		});
}

	add_item = function(id){
		$('.barang-' + id).css('opacity', .3);
		$('.btn-item-' + id).remove();
		$('.item-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/resep/additem', {id : id}, function(json){
			var $htm = '\
				<tr onclick="id_hapus(' + json.item.id_barang + ');" class="item-barang" data-item="' + json.item.id_barang + '">\
					<td>\
						' + json.item.kode + '\
						<input type="hidden" value="' + json.item.id_barang + '" name="id_barang_item[]">\
						<input type="hidden" value="' + json.item.id_item_gudang + '" name="id_item_gudang[]">\
					</td>\
					<td><input type="text" value="' + json.item.nm_barang + '" name="nm_barang[]" readonly="readonly" class="form-control" required></td>\
					<td>\
						<div class="input-group input-group-sm">\
							<input type="number" data-form="jumlah_out" min="0" onchange="changeqty(this.value, ' + (json.item.masuk - json.item.keluar) + ');" value="0" name="jumlah_out[]" class="form-control text-right" required>\
						  	<span class="input-group-addon">' + json.item.nm_satuan + '</span>\
						  	<input type="hidden" name="id_satuan[]" value="' + json.item.id_satuan + '" />\
						</div>\
						<input type="hidden" data-form="harga_jual" value="' + json.item.harga_jual + '" name="harga_jual[]" class="form-control text-right" required>\
						<input type="hidden" data-form="status_obat" value="1" name="status_obat[]" class="form-control text-right" required>\
						<input type="hidden" readonly="readonly" data-form="total" value="" name="total[]" class="form-control text-right" required>\
					</td>\
				</tr>\
			';
			$('.content-items').append($htm);
			$('.barang-' + json.item.id_barang).remove();
			$('[name="nm_barang"]').val(json.item.nm_barang);
			$('[name="id_barang"]').val(json.item.id_barang);
		});
	}
	// tambahbhp = function(id_service,page,kode_service){
	// 	 $('#tambahbhp').modal('show');

	// 	 // Load data BHP
	// 	var kode = $('[name="modal-kode-bhp"]').val();
	// 	var barang = $('[name="modal-barang-bhp"]').val();

	// 	var param = {
	// 		page : page,
	// 		kode : kode,
	// 		barang : barang,
	// 		kode_service :kode_service
	// 	};

	// 	$('.modal-bhp-list').css('opacity', .3);

	// 	$.getJSON(_base_url + '/treatment/loadbhp', param, function(json){

	// 		$('.modal-bhp-list').html(json.content);
	// 		$('.modal-bhp-pagin').html(json.pagin);
	// 		$('.modal-bhp-list').css('opacity', 1);
	// 		$('body').css('cursor', 'default');

	// 		onDataCancel();

	// 		$('[name="id_service"]').val(id_service);

	// 		$('div.modal-bhp-pagin > ul.pagination > li > a').click(function(e){
	// 			e.preventDefault();
	// 			var $link 	= $(this).attr('href');
	// 			var $split 	= $link.split('?page=');
	// 			var $page 	= $split[1];
	// 			tambahbhp(id_service,$page);
	// 		});
	// 	});
	// }
	// $('[name="modal-kode-bhp"], [name="modal-barang-bhp"]').keyup(function(e){
	// 	if(e.keyCode == 13)add
	// 		loadbhp(1);
	// });
	// $('.btn-search-bhp').click(function(){
	// 	loadbhp(1);
	// });
		/* Load data Barang */
	// loadbhp = function(page){

	// 	var kode = $('[name="modal-kode-bhp"]').val();
	// 	var barang = $('[name="modal-barang-bhp"]').val();

	// 	var param = {
	// 		page : page,
	// 		kode : kode,
	// 		barang : barang
	// 	};

	// 	$('.modal-bhp-list').css('opacity', .3);

	// 	$.getJSON(_base_url + '/treatment/loadbhp', param, function(json){

	// 		$('.modal-bhp-list').html(json.content);
	// 		$('.modal-bhp-pagin').html(json.pagin);
	// 		$('.modal-bhp-list').css('opacity', 1);
	// 		$('body').css('cursor', 'default');

	// 		onDataCancel();

	// 		$('div.modal-bhp-pagin > ul.pagination > li > a').click(function(e){
	// 			e.preventDefault();
	// 			var $link 	= $(this).attr('href');
	// 			var $split 	= $link.split('?page=');
	// 			var $page 	= $split[1];
	// 			loadbhp($page);
	// 		});
	// 	});
	// }
	// $('[name="modal-kode-bhp"], [name="modal-barang-bhp"]').keyup(function(e){
	// 	if(e.keyCode == 13)
	// 		loadbhp(1);
	// });
	// $('.btn-search-bhp').click(function(){
	// 	loadbhp(1);
	// });

	changeqty = function(val, current){
			
		if(val > current)
			swal('PERINGATAN!', 'StOk Anda tidak cukup! Silahkan Cek qty yang diinput sama stok yang tersedia .');
	}
	
		
	loadpaket = function(page){
		// var kode_paket = $('[name="modal-kode-tindakan"]').val();
		var tindakan = $('[name="modal-nama-paket"]').val();
		var unit_jasa	=$('[name="unit_jasa"]').val();
		var param = {
			page : page,
			tindakan : tindakan,
			unit_jasa	:unit_jasa
		};
		$('.modal-paket-list').css('opacity', .3);
		$.getJSON(_base_url + '/treatment/loadpaket', param, function(json){
			$('.modal-paket-list').html(json.content);
			$('.modal-paket-pagin').html(json.pagin);
			$('.modal-paket-list').css('opacity', 1);
			$('body').css('cursor', 'default');
			onDataCancel();
			$('div.modal-paket-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				loadpaket($page);
			});
		});
	}

	$('[name="modal-kode-paket"], [name="modal-nama-paket"]').keyup(function(e){
		if(e.keyCode == 13)add
			loadpaket(1);
	});
	$('[name="unit_jasa"]').change(function(){
		loadpaket(1);
	});
	$('.btn-search-paket').click(function(){
		loadpaket(1);
	});
	add_paket = function(id){
		$('.paket-' + id).css('opacity', .3);
		$('.btn-paket-' + id).remove();
		$('.paket-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/treatment/addpaket', {id : id}, function(json){
			// console.log(json);
			$('.contn-treatment').append(json.content);
			$('.paket-' + json.paket.id_service).remove();	
			$('#produks').modal('hide');
	
		});
	}


			
	 add_dokter = function(id) {
		$.getJSON(_base_url + '/treatment/adddokter', {id : id}, function(json){
			 // console.log(json);
			var $htm = '\
			<tr class="baris_form">\
				 <td>\
				</td>\
				<td>\
					<select name="id_dr['+id+'][]" class="form-control">' + json.dokter + '</select>\
				</td>\
				<td>\
				<select class="form-control" id="jabatan" required name="jabatan['+id+'][]">\
				<option value="">Pilih Jabatan Dokter </option>\
					<option value="1">DPJP</option>\
					<option value="2" selected="selected">Anggota/OPERATOR</option>\
				</select>\
				</td>\
				<td><button title="Hapus" type="button" class="btn btn-danger btn-hapus34"><i class="fa fa-trash"></i></button></td>\
			</tr>\
		';
			$('.contn-' + id).append($htm);
		});
	}
	loadjasa = function(page){
			var tindakan = $('[name="modal-nama-jasa"]').val();
			var unit_jasa	=$('[name="unit_jasa"]').val();
		var param = {
			page : page,
			tindakan : tindakan,
			unit_jasa :unit_jasa
		};

		$('.modal-jasa-list').css('opacity', .3);
		$.getJSON(_base_url + '/treatment/loadjasa', param, function(json){
 
			$('.modal-jasa-list').html(json.content);
			$('.modal-jasa-pagin').html(json.pagin);
			$('.modal-jasa-list').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('div.modal-jasa-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				loadjasa($page);
			});
		});
	}
	$('[name="modal-nama-jasa"]').keyup(function(e){
		if(e.keyCode == 13)add
			loadjasa(1);
	});
	$('[name="modal-nama-jasa"]').change(function(){
		loadjasa(1);
	});
	$('[name="unit_jasa"]').change(function(){
		loadjasa(1);
	});
	$('.btn-search-jasa').click(function(){
		loadjasa(1);
	});

	add_jasaku = function(id){
		$('.jasaku-' + id).css('opacity', .3);
		$('.btn-jasaku-' + id).remove();
		$('.jasaku-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/treatment/addjasaku', {id : id}, function(json){
			// console.log(json);
		
			$('.content-jasaku').append(json.content2);
			$('.jasaku-' + json.item.id_service_detail).remove();
			$('.jasaku-' + json.item.id_service_detail).css('opacity', 1);
			$('#produks').modal('hide');
			// $('.form-control').keyup(function(e){
			// 	perhitungan();
			// });
			// $('.form-control').change(function(e){
			// 	perhitungan();
			// });

			// perhitungan();
		});
	}

	// perhitungan1 = function(){
	// 	var subtotal = 0;
		
	// 	$(':input[data-form="jumlah"]').each(function(i){
	// 		/* PENJUMLAHAN */
	// 		var harga = $(':input[data-form="harga"]')[i].value;
	// 		var kali = harga * $(this).val();

	// 		$(':input[data-form="total1"]')[i].value = kali;
	// 		subtotal += kali;
	// 	});

	// 	$('.resep-subtotal1').html(number_format(subtotal,2,',','.'));	
	// 	$(':input[name="grand_total1"]').val(subtotal);
	// 	}

	// 	$('.form-control').keyup(function(e){
	// 		perhitungan1();
	// 	});
	// 	$('.form-control').change(function(e){
	// 		perhitungan1();
	// 	});

// function hapus tindakan

	// id_hapuspaket= function(id){
	// 	$('[name="id_hapuspaket"]').val(id);
	// 	$('.btn-hapus5').show();
	// 	$('.item-paket').css('background', 'none');
	// 	$('[data-paket="' + id + '"]').css('background', '#ddd');

	// 	loadpaket(1);
	// }

	$(document).on('click', '.btn-hapus34', function(event) {
		event.preventDefault();

		$(this).closest('.baris_form').remove();
	
	});
	$(document).on('click', '.btn-pakethapus', function(event) {
		event.preventDefault();

		$(this).closest('.item-paket').remove();
	
	});
	$(document).on('click', '.btn-hapus34bhp', function(event) {
		event.preventDefault();

		$(this).closest('.baris_formbhp').remove();
	
	});
	$(document).on('click', '.btn-hapusbhp34', function(event) {
		event.preventDefault();

		$(this).closest('.baris_bhp').remove();
	
	});
	$(document).on('click', '.btn-hapus345', function(event) {
		event.preventDefault();

		$(this).closest('.bhp').remove();
	
	});

	loadjasa(1);
	loaditemsaturan(1);
	loadpaket(1);
	
});
