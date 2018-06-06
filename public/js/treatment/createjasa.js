$(function(){

	//close_sidebar();
	$('[name="tanggal"]').datepicker();
	$('[name="duodate"]').datepicker();
	$('.cari-pasien').click(function(){
		// loadpamy();
	});
	//Hapus form tindakan //
	$('.btn-hapus').click(function(){
		var $id = $('[name="id_delete2"]').val();
		$('[data-jasa="' + $id + '"]').remove();
		$('[name="id_delete2"]').val(0);
		$('.btn-hapus').hide();
	});
	
	// $('.btn-hapus2').click(function(){
	// 	var $id = $('[name="id_delete"]').val();
	// 	$('[data-tindakan="' + $id + '"]').remove();
	// 	$('[name="id_delete"]').val(0);
	// 	$('.btn-hapus2').hide();
	// });
	// $('.btn-hapus4').click(function(){
	// 	var $id = $('[name="id_delete4"]').val();
	// 	$('[data-item="' + $id + '"]').remove();
	// 	$('[name="id_delete4"]').val(0);
	// 	$('.btn-hapus4').hide();
	// });
	
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
	/* Load  nama pasien dari mssql */
	loadpasien = function(page){
		var ID_PASIEN = $('[name="modal-id_pasien"]').val();
		var NAMA_PASIEN = $('[name="nama_pasien"]').val();
		var param = {
			page : page,
			ID_PASIEN : ID_PASIEN,
			NAMA_PASIEN : NAMA_PASIEN
		};
		$('.modal-pasien-list').css('opacity', .3);

		$.getJSON(_base_url + '/jasa/loadpasien', param, function(json){

			$('.modal-pasien-list').html(json.content);
			$('.modal-pasien-pagin').html(json.pagin);
			$('.modal-pasien-list').css('opacity', 1);
			$('body').css('cursor', 'default');
			onDataCancel();
			$('div.modal-pasien-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				loadpasien($page);
			});
		});
	}
	$('[name="modal-id_pasien"]').keyup(function(e){
		if(e.keyCode == 13)
			loadpasien(1);
	});
	$('[name="nama_pasien"]').change(function(){
		loadpasien(1);
	});
	$('.btn-search-pasien').click(function(){
		loadpasien(1);
	});

	add_pasien = function(id){
		$('.pa-' + id).css('opacity', .3);
		$.getJSON(_base_url + '/jasa/addpasien', {id : id}, function(json){
			console.log(json);
			$htm = '';
			$('.content-pasien').append($htm);
				//$('.pa-' + json.pa.ID_PASIEN).remove(); <-- Jangan dihapus
			$('.pa-' + json.pa.id_pasien).css('opacity', 1);
			$('[name="NAMA_PASIEN"]').val(json.pa.nama_pasien);
			$('[name="ID_PASIEN"]').val(json.pa.id_pasien_hc);
			$('[name="id_pasien"]').val(json.pa.id_pasien);
			$('#pasien').modal('hide');

			});
		}
	/* Load  nama tindakan  */
	loadtindakanatur = function(page){
		var kode_service = $('[name="modal-kode-tindakan"]').val();
		var nama_service = $('[name="modal-nama-tindakan"]').val();
		var param = {
			page : page,
			kode_service : kode_service,
			nama_service : nama_service
		};
		$('.modal-tindakanatur-list').css('opacity', .3);
		$.getJSON(_base_url + '/jasa/loadtindakan', param, function(json){
			$('.modal-tindakanatur-list').html(json.content);
			$('.modal-tindakanatur-pagin').html(json.pagin);
			$('.modal-tindakanatur-list').css('opacity', 1);
			$('body').css('cursor', 'default');
			onDataCancel();
			$('div.modal-tindakanatur-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				loadtindakanatur($page);
			});
		});
	}
	$('[name="modal-kode-tindakan"]').keyup(function(e){
		if(e.keyCode == 13)
			loadtindakanatur(1);
	});
	$('[name="modal-nama-tindakan"]').change(function(){
		loadtindakanatur(1);
	});
	$('.btn-search-tindakan').click(function(){
		loadtindakanatur(1);
	});
	add_tindakan = function(id){
		$('.tindakan-' + id).css('opacity', .3);
		$('.btn-tindakan-' + id).remove();
		$('.tindakan-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/jasa/addtindakan', {id : id}, function(json){
			console.log(json);
			var $htm = '\
				<tr onclick="id_delete(' + json.pa.id_service + ');" class="item-tindakan" data-tindakan="' + json.pa.id_service + '">\
					<td>\
						' + json.pa.kode_service + '\
						<input type="hidden" value="' + json.pa.id_service + '" name="id_service[]">\
						<input type="hidden" value="1" name="status[]">\
					</td>\
					<td><input type="text" value="' + json.pa.nama_service + '" name="nama_service[]" readonly="readonly" class="form-control" required></td>\
				</tr>\
			';
			$('.content-tindakan').append($htm);
			$('.tindakan-' + json.pa.id_service).remove();
			$('.tindakan-' + json.pa.kode_service).css('opacity', 1);


		});
	}
	
	// function jasa
	loadjasaaturan = function(page){

		var kode_service = $('[name="modal-kode-jasa"]').val();
		var nama_service = $('[name="modal-nama-jasa"]').val();
		var param = {
			page : page,
			kode_service : kode_service,
			nama_service : nama_service
		};

		$('.modal-jasaaturan-list').css('opacity', .3);

		$.getJSON(_base_url + '/jasa/loadjasa', param, function(json){

			$('.modal-jasaaturan-list').html(json.content);
			$('.modal-jasa-pagin').html(json.pagin);
			$('.modal-jasaaturan-list').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('div.modal-jasa-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				loadjasaaturan($page);
			});
		});
	}
	$('[name="modal-kode-jasa"]').keyup(function(e){
		if(e.keyCode == 13)
			loadjasaaturan(1);
	});
	$('[name="modal-nama-jasa"]').change(function(){
		loadjasaaturan(1);
	});
	$('.btn-search-jasa').click(function(){
		loadjasaaturan(1);
	});
	add_jasa = function(id){
		$('.jasa-' + id).css('opacity', .3);
		$('.btn-jasa-' + id).remove();
		$('.jasa-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/jasa/addjasa', {id : id}, function(json){
			console.log(json);
				var $htm = '\
				<tr onclick="id_delete2(' + json.pa.id_service + ');" class="item-jasa" data-jasa="' + json.pa.id_service + '">\
					<td>\
						' + json.pa.kode_service + '\
						<input type="hidden" value="' + json.pa.id_service + '" name="id_service[]">\
						<input type="hidden" value="1" name="status[]">\
					</td>\
					<td><input type="text" value="' + json.pa.nama_service + '" name="nama_service[]" readonly="readonly" class="form-control" required></td>\
				</tr>\
			';
			$('.content-jasaaturan').append($htm);
			$('.jasa-' + json.pa.id_service).remove();
			$('.jasa-' + json.pa.kode_service).css('opacity', 1);
		});
	}

	// load untuk mengambil data obat habis pakek
	loaditems = function(page){

		var kode = $('[name="modal-kode-item"]').val();
		var barang = $('[name="modal-barang-item"]').val();

		var param = {
			page : page,
			kode : kode,
			barang : barang
		};

		$('.modal-items-list').css('opacity', .3);

		$.getJSON(_base_url + '/jasa/loaditems', param, function(json){

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
		if(e.keyCode == 13)
			loaditems(1);
	});
	$('.btn-search-item').click(function(){
		loaditems(1);
	});
	// function untuk add data obat
	add_item = function(id){
		$('.barang-' + id).css('opacity', .3);
		$('.btn-item-' + id).remove();
		$('.item-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/jasa/additem', {id : id}, function(json){
			var $htm = '\
				<tr onclick="id_delete4(' + json.item.id_barang + ');" class="item-barang" data-item="' + json.item.id_barang + '">\
					<td>\
					<div class="input-group input-group-sm">\
						' + json.item.kode + '\
						<input type="hidden" value="' + json.item.id_barang + '" name="id_barang[]">\
						<input type="hidden" value="' + json.item.id_item_gudang + '" name="id_item_gudang[]">\
					</div></td>\
					<td>' + json.item.nm_barang + '</td>\
					<td>\
					<div class="input-group input-group-sm">\
						<div class="input-group input-group-sm">\
							<input type="number" data-form="jumlah_out"  onchange="changeqty(this.value, ' + (json.item.masuk - json.item.keluar) + ');" value="0" name="jumlah_out[]" class="form-control text-right" required>\
						  	<span class="input-group-addon">' + json.item.nm_satuan + '</span>\
						  	<input type="hidden" name="id_satuan[]" value="' + json.item.id_satuan + '" />\
						</div>\
						<input type="hidden" data-form="harga_jual" value="' + json.item.harga_jual + '" name="harga_jual[]" class="form-control text-right" required>\
						<input type="hidden" data-form="status_obat" value="1" name="status_obat[]" class="form-control text-right" required>\
						<input type="hidden" readonly="readonly" data-form="total" value="' + json.item.id_gudang + '" name="id_gudang[]" class="form-control text-right" required>\
					</div>\
					</td>\
				</tr>\
			';
			$('.content-item').append($htm);
			$('.barang-' + json.item.id_barang).remove();
			$('[name="nm_barang"]').val(json.item.nm_barang);
			$('[name="id_barang"]').val(json.item.id_barang);
		});
	}
	changeqty = function(val, current){
			
		if(val > current)
			swal('PERINGATAN!', 'StOk tidak cukup! .');
	}

		
	loadpaket = function(page){
		// var kode_paket = $('[name="modal-kode-tindakan"]').val();
		var nama_paket = $('[name="modal-nama-paket"]').val();
		var param = {
			page : page,
			nama_paket : nama_paket
		};
		$('.modal-paket-list').css('opacity', .3);
		$.getJSON(_base_url + '/jasa/loadpaket', param, function(json){
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
	$('[name="modal-kode-paket"]').keyup(function(e){
		if(e.keyCode == 13)
			loadpaket(1);
	});
	$('[name="modal-nama-paket"]').change(function(){
		loadpaket(1);
	});
	$('.btn-search-paket').click(function(){
		loadpaket(1);
	});
	add_paket = function(id){
		$('.paket-' + id).css('opacity', .3);
		$('.btn-paket-' + id).remove();
		$('.paket-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/jasa/addpaket', {id : id}, function(json){
			// console.log(json);
			$('.content-paket').append(json.content);
			$('.paket-' + json.id_paket).remove();
			$('[name="id_paket"]').val(json.id_paket);
			// $('.jasaaturan-' + json.pa.kode_service).css('opacity', 1);


		});
	}
	/* Load data Barang */
	loaditemsaturan = function(page){

		var kode = $('[name="modal-kode-itematuran"]').val();
		var barang = $('[name="modal-barang-itematuran"]').val();

		var param = {
			page : page,
			kode : kode,
			barang : barang
		};


		$('.modal-itemsaturan-list').css('opacity', .3);

		$.getJSON(_base_url + '/jasa/loaditemsaturan', param, function(json){

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
	$('.btn-search-itematuran').click(function(){
		loaditemsaturan(1);
	});

	reseptretment = function(id_paket, page){
		 $('#reseptreatment').modal('show');

		 // Load data BHP
		var kode = $('[name="modal-kode-itematuran"]').val();
		var barang = $('[name="modal-barang-itematuran"]').val();

		var param = {
			
			kode : kode,
			barang : barang,
			paket : id_paket
		
		};

		$('.modal-itemsaturan-list').css('opacity', .3);

		$.getJSON(_base_url + '/jasa/loaditemsaturan', param, function(json){

			$('.modal-itemesaturan-list').html(json.content);
			$('.modal-itemsaturan-pagin').html(json.pagin);
			$('.modal-itemsaturan-list').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('[name="id_paket"]').val(id_paket);

			$('div.modal-itemsaturan-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				reseptretment($page);
			});
		});
	}
	$('[name="modal-kode-itematuran"], [name="modal-barang-itematuran"]').keyup(function(e){
		if(e.keyCode == 13)
			loaditemsaturan(1);
	});
	$('.btn-search-itematuran').click(function(){
		loaditemsaturan(1);
	});
	add_itematuran = function(id, id_paket){
		

		$('.barangaturan-' + id).css('opacity', .3);
		$('.btn-itematuran-' + id).remove();
		$('.itematuran-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/jasa/additematuran', {id : id, id_paket : id_paket}, function(json){
			var $index = id_paket;
			$(json.content).insertAfter('.item-obat-' + $index);
		
		});
	}

	id_hapuspaket= function(id){
		$('[name="id_hapuspaket"]').val(id);
		$('.btn-hapus5').show();
		$('.item-paket').css('background', 'none');
		$('[data-paket="' + id + '"]').css('background', '#ddd');

		loadpaket(1);
	}
	id_delete2= function(id){
		$('[name="id_delete2"]').val(id);
		$('.btn-hapus').show();
		$('.item-jasa').css('background', 'none');
		$('[data-jasa="' + id + '"]').css('background', '#ddd');

		loadjasaaturan(1);
	}
	// id_delete= function(id){
	// 	$('[name="id_delete"]').val(id);
	// 	$('.btn-hapus2').show();
	// 	$('.item-tindakan').css('background', 'none');
	// 	$('[data-tindakan="' + id + '"]').css('background', '#ddd');

	// 	loadpaket(1);
	// }
	// id_delete4= function(id){
	// 	$('[name="id_delete4"]').val(id);
	// 	$('.btn-hapus4').show();
	// 	$('.item-barang').css('background', 'none');
	// 	$('[data-item="' + id + '"]').css('background', '#ddd');

	// 	loadpaket(1);
	// }
	loadtindakanatur(1);
	loadjasaaturan(1);
	loadpaket(1);
	loadpasien(1);
	loaditems(1);
	loaditemsaturan(1);
	
});
