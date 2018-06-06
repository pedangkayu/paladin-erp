$(function(){

	//close_sidebar();
	// $('[name="tanggal"]').datepicker();
	// $('[name="duodate"]').datepicker();
	// $('.cari-pasien').click(function(){
	// 	loadpamy();
	// });
	//Hapus form tindakan //
	$('.btn-hapus').click(function(){
		var $id = $('[name="id_delete"]').val();
		$('[data-tindakan="' + $id + '"]').remove();
		$('[name="id_delete"]').val(0);
		$('.btn-hapus').hide();
	});

	$('.btn-delete').click(function(){
		var $id = $('[name="id_hapus"]').val();
		$('[data-item="' + $id + '"]').remove();
		$('[name="id_hapus"]').val(0);
		$('.btn-delete').hide();
	});

	$('#tab-4 a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
	/* Load  nama tindakan dari mssql */
	loadtindakan = function(page){
		var kode_service = $('[name="modal-kode-tin"]').val();
		var nama_service = $('[name="nama-tin"]').val();
		var param = {
			page : page,
			kode_service : kode_service,
			nama_service : nama_service
		};
		$('.modal-tindakan-list').css('opacity', .3);
		$.getJSON(_base_url + '/treatment/loadtindakan', param, function(json){
			$('.modal-tindakan-list').html(json.content);
			$('.modal-tindakan-pagin').html(json.pagin);
			$('.modal-tindakan-list').css('opacity', 1);
			$('body').css('cursor', 'default');
			onDataCancel();
			$('div.modal-tindakan-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				loadtindakan($page);
			});
		});
	}
	$('[name="modal-kode-tin"]').keyup(function(e){
		if(e.keyCode == 13)
			loadtindakan(1);
	});
	$('[name="nama-tin"]').change(function(){
		loadtindakan(1);
	});
	$('.btn-search-tin').click(function(){
		loadtindakan(1);
	});
	add_tindakan = function(id){
		$('.tindakan-' + id).css('opacity', .3);
		$('.btn-tindakan-' + id).remove();
		$('.tindakan-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/treatment/addtindakan', {id : id}, function(json){
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
			//$('.pa-' + json.pa.ID_PASIEN).remove(); <-- Jangan dihapus
			$('.tindakan-' + json.pa.kode_service).css('opacity', 1);


		});
	}
	// /* Load  nama pasien dari mssql */
	// loadpasien = function(page){
	// 	var ID_PASIEN = $('[name="modal-id_pasien"]').val();
	// 	var NAMA_PASIEN = $('[name="nama_pasien"]').val();
	// 	var param = {
	// 		page : page,
	// 		ID_PASIEN : ID_PASIEN,
	// 		NAMA_PASIEN : NAMA_PASIEN
	// 	};
	// 	$('.modal-pasien-list').css('opacity', .3);

	// 	$.getJSON(_base_url + '/treatment/loadpasien', param, function(json){

	// 		$('.modal-pasien-list').html(json.content);
	// 		$('.modal-pasien-pagin').html(json.pagin);
	// 		$('.modal-pasien-list').css('opacity', 1);
	// 		$('body').css('cursor', 'default');
	// 		onDataCancel();
	// 		$('div.modal-pasien-pagin > ul.pagination > li > a').click(function(e){
	// 			e.preventDefault();
	// 			var $link 	= $(this).attr('href');
	// 			var $split 	= $link.split('?page=');
	// 			var $page 	= $split[1];
	// 			loadpasien($page);
	// 		});
	// 	});
	// }
	// $('[name="modal-id_pasien"]').keyup(function(e){
	// 	if(e.keyCode == 13)
	// 		loadpasien(1);
	// });
	// $('[name="nama_pasien"]').change(function(){
	// 	loadpasien(1);
	// });
	// $('.btn-search-pasien').click(function(){
	// 	loadpasien(1);
	// });

	// 	add_pasien = function(id){
	// 		$('.pa-' + id).css('opacity', .3);
	// 		$.getJSON(_base_url + '/resep/additempa', {id : id}, function(json){
	// 			console.log(json);
	// 			$htm = '';
	// 			$('.content-pasien').append($htm);
	// 			//$('.pa-' + json.pa.ID_PASIEN).remove(); <-- Jangan dihapus
	// 			$('.pa-' + json.pa.id_pasien).css('opacity', 1);
	// 			$('[name="NAMA_PASIEN"]').val(json.pa.nama_pasien);
	// 			$('[name="ID_PASIEN"]').val(json.pa.id_pasien_hc);
	// 			$('[name="id_pasien"]').val(json.pa.id_pasien);
	// 			$('#pasien').modal('hide');

	// 		});
	// 	}
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

		$.getJSON(_base_url + '/treatment/loaditems', param, function(json){

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
		$.getJSON(_base_url + '/resep/additem', {id : id}, function(json){
			var $htm = '\
				<tr onclick="id_hapus(' + json.item.id_barang + ');" class="item-barang" data-item="' + json.item.id_barang + '">\
					<td>\
						' + json.item.kode + '\
						<input type="hidden" value="' + json.item.id_barang + '" name="id_barang[]">\
						<input type="hidden" value="' + json.item.id_item_gudang + '" name="id_item_gudang[]">\
					</td>\
					<td><input type="text" value="' + json.item.nm_barang + '" name="nm_barang[]" readonly="readonly" class="form-control" required></td>\
					<td>\
						<div class="input-group input-group-sm">\
							<input type="number" data-form="jumlah_out"  onchange="changeqty(this.value, ' + (json.item.masuk - json.item.keluar) + ');" value="0" name="jumlah_out[]" class="form-control text-right" required>\
						  	<span class="input-group-addon">' + json.item.nm_satuan + '</span>\
						  	<input type="hidden" name="id_satuan[]" value="' + json.item.id_satuan + '" />\
						</div>\
						<input type="hidden" data-form="harga_jual" value="' + json.item.harga_jual + '" name="harga_jual[]" class="form-control text-right" required>\
						<input type="hidden" data-form="status_obat" value="1" name="status_obat[]" class="form-control text-right" required>\
						<input type="hidden" readonly="readonly" data-form="total" value="' + json.item.id_gudang + '" name="id_gudang[]" class="form-control text-right" required>\
					</td>\
				</tr>\
			';
			$('.content-item').append($htm);
			$('.barang-' + json.item.id_barang).remove();
			$('[name="nm_barang"]').val(json.item.nm_barang);
			$('[name="id_barang"]').val(json.item.id_barang);
		});
	}
	// function jasa
	loadjasa = function(page){

		var kode_service = $('[name="modal-kode-jasa"]').val();
		var nama_service = $('[name="nama-jasa"]').val();

		var param = {
			page : page,
			kode_service : kode_service,
			nama_service : nama_service
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
	$('[name="modal-kode-jasa"]').keyup(function(e){
		if(e.keyCode == 13)
			loadjasa(1);
	});
	$('[name="nama-jasa"]').change(function(){
		loadjasa(1);
	});
	$('.btn-search-jasa').click(function(){
		loadjasa(1);
	});
	add_jasa = function(id){
		$('.jasa-' + id).css('opacity', .3);
		$('.btn-jasa-' + id).remove();
		$('.jasa-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/treatment/addjasa', {id : id}, function(json){
			console.log(json);
			var $htm = '\
				<tr onclick="id_delete(' + json.pa.id_service + ');" class="item-jasa" data-jasa="' + json.pa.id_service + '">\
					<td>\
						' + json.pa.kode_service + '\
						<input type="hidden" value="' + json.pa.id_service + '" name="id_service[]">\
						<input type="hidden" value="1" name="status[]">\
					</td>\
					<td><input type="text" value="' + json.pa.nama_service + '" name="nama_service[]" readonly="readonly" class="form-control" required></td>\
				</tr>\
			';
			$('.content-jasa').append($htm);
			//$('.pa-' + json.pa.ID_PASIEN).remove(); <-- Jangan dihapus
			$('.t-' + json.pa.kode_service).css('opacity', 1);


		});
	}

// function hapus tindakan
	id_delete = function(id){
		$('[name="id_delete"]').val(id);
		$('.btn-hapus').show();
		$('.item-tindakan').css('background', 'none');
		$('[data-tindakan="' + id + '"]').css('background', '#ddd');

		loadtindakan(1);
	}
		id_hapus = function(id){
		$('[name="id_hapus"]').val(id);
		$('.btn-delete').show();
		$('.item-barang').css('background', 'none');
		$('[data-item="' + id + '"]').css('background', '#ddd');
		loaditems(1);
	}

	loadtindakan(1);
	// loadpasien(1);
	loaditems(1);
	loadjasa(1);
});
