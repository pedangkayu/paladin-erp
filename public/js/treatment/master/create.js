$(function(){

	//close_sidebar();
	$('[name="tanggal"]').datepicker();
	$('[name="duodate"]').datepicker();
	$('.cari-pasien').click(function(){
		// loadpamy();
	});
	//Hapus form tindakan //
	// $('.btn-hapus2').click(function(){
	// 	var $id = $('[name="id_delete"]').val();
	// 	$('[data-tindakanaturan="' + $id + '"]').remove();
	// 	$('[name="id_delete"]').val(0);
	// 	$('.btn-hapus2').hide();
	// });
	$('.btn-hapus3').click(function(){
		var $id = $('[name="id_hapus3"]').val();
		$('[data-jasaaturan="' + $id + '"]').remove();
		$('[name="id_hapus3"]').val(0);
		$('.btn-hapus3').hide();
	});
	$('.btn-hapus4').click(function(){
		var $id = $('[name="id_delete4"]').val();
		$('[data-itematuran="' + $id + '"]').remove();
		$('[name="id_delete4"]').val(0);
		$('.btn-hapus4').hide();
	});
		$('.btn-hapusjasa').click(function(){
		var $id = $('[name="id_deletejasa"]').val();
		$('[data-jasa="' + $id + '"]').remove();
		$('[name="id_deletejasa"]').val(0);
		$('.btn-hapusjasa').hide();
	});

	$('#tab-4 a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
	/* Load  nama pasien dari mssql */
	
	/* Load  nama tindakan  masuk 1*/
	loadtindakanatur = function(page){
		var grup = $('[name="modal-kode-tindakan"]').val();
		var tindakan = $('[name="modal-nama-tindakan"]').val();
		var param = {
			page : page,
			grup : grup,
			tindakan : tindakan
		};
		$('.modal-tindakanatur-list').css('opacity', .3);
		$.getJSON(_base_url + '/mastertreatment/loadtindakan', param, function(json){
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
	$('[name="modal-kode-tindakan"],[name="modal-nama-tindakan"]').keyup(function(e){
		if(e.keyCode == 13)add
			loadtindakanatur(1);
	});
	
	$('.btn-search-tindakan').click(function(){
		loadtindakanatur(1);
	});
	
	//masuk 1//
	add_tindakanatur = function(id){
	
		$('.tindakanaturan-' + id).css('opacity', .3);
		$('.btn-tindakanaturan-' + id).remove();
		$('.tindakanaturan-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/mastertreatment/addtindakan', {id : id}, function(json){
			// console.log(json);
			var $htm = '\
			<table  onclick="id_delete(' + json.pa.service_kode + ');" class="item-tindakanaturan" data-tindakanaturan="' + json.pa.service_kode + '">\
				<tr>\
				<td>\
						<input type="text" value="' + json.pa.nm_service + '" class="form-control" readonly="readonly" >\
						<input type="hidden" value="' + json.pa.service_kode + '" name="service_kode[]">\
				</td>\
				<td><button type="button" class="btn-resep btn-langer btn-sm" onclick="reseptretment(' + json.pa.service_kode + ',1);">Tambh BHP</button><td>\
				<td>'+json.btn+'</td>\
				</tr>\
				<tr>\
					<td colspan="4" class="jasa-' + json.pa.service_kode + '"></td>\
				<tr>\
				<tr>\
					<td colspan="4" class="item-' + json.pa.service_kode + '"></td>\
				</tr>\
				</tabel>\
			';
			$('.content-tindakanaturan').append($htm);
			$('.tindakanaturan-' + json.pa.service_kode).remove(); 
			$('.tindakanaturan-' + json.pa.coa).css('opacity', 1);


		});
	}
	add_jasa = function(id){
	var $a = $('[name="service_kode"]').val();

		$('.jasa-' + id).css('opacity', .3);
		$('.btn-jasa-' + id).remove();
		$('.jasa-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/mastertreatment/addjasa', {id : id}, function(json){
			console.log(json);
			var $htm = '\
				<tr onclick="id_deletejasa(' + json.pa.service_kode + ');" class="item-jasa" data-jasa="' + json.pa.service_kode + '">\
				<td>\
						<input type="text" value="' + json.pa.nm_service + '" class="form-control" readonly="readonly" >\
						<input type="hidden" value="' + json.pa.service_kode + '" name="service_kode_jasa[' + $a + '][]">\
						<input type="hidden" value="' +json.pa.id_service_detail+ '" name="id_service_detail[' + $a + '][]">\
				</td>\
				</tr>\
			';
			$('.jasa-' + $a ).append($htm);
			$('.jasa-' + json.pa.id_service_detail).remove();
		
		});

	}
	jasa = function(service_kode,page,coa){
		 $('#jasa').modal('show');

		 // Load data BHP
		var grup = $('[name="modal-kode-jasa"]').val();
		var tindakan = $('[name="modal-nama-jasa"]').val();
		var param = {
			page : page,
			grup : grup,
			tindakan : tindakan,
			coa :coa
		};

		$('.modal-jasa-list').css('opacity', .3);

		$.getJSON(_base_url + '/mastertreatment/loadjasa', param, function(json){

			$('.modal-jasa-list').html(json.content);
			$('.modal-jasa-pagin').html(json.pagin);
			$('.modal-jasa-list').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('[name="service_kode"]').val(service_kode);

			$('div.modal-jasa-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				jasa(service_kode,$page);
			});
		});
	}
	$('[name="modal-kode-jasa"], [name="modal-nama-jasa"]').keyup(function(e){
		if(e.keyCode == 13)add
			loadjasa(1);
	});
	$('.btn-search-jasa').click(function(){
		loadjasa(1);
	});

	loadjasa = function(page){

		var grup = $('[name="modal-kode-jasa"]').val();
		var tindakan = $('[name="modal-nama-jasa"]').val();
		var param = {
			page : page,
			grup : grup,
			tindakan : tindakan
		};

	$('.modal-jasa-list').css('opacity', .3);

	$.getJSON(_base_url + '/mastertreatment/loadjasa', param, function(json){

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
$('[name="modal-kodejasa"], [name="modal-nama-jasa"]').keyup(function(e){
	if(e.keyCode == 13)add
		loadjasa(1);
});
$('.btn-search-jasa').click(function(){
	loadjasa(1);
});

	//masuk222//
	reseptretment = function(service_kode,page,coa){
		 $('#reseptreatment').modal('show');

		 // Load data BHP
		var kode = $('[name="modal-kode-itematuran"]').val();
		var barang = $('[name="modal-barang-itematuran"]').val();

		var param = {
			page : page,
			kode : kode,
			barang : barang,
			coa :coa
		};

		$('.modal-itemsaturan-list').css('opacity', .3);

		$.getJSON(_base_url + '/mastertreatment/loaditemsaturan', param, function(json){

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
	$('.btn-search-itematuran').keyup(function(e){
		loaditemsaturan(1);
	});
	$('.btn-search-itematuran').click(function(){
		loaditemsaturan(1);
	});

	/* Load data Barang masuk 2222*/
	loaditemsaturan = function(page){

		var kode = $('[name="modal-kode-itematuran"]').val();
		var barang = $('[name="modal-barang-itematuran"]').val();

		var param = {
			page : page,
			kode : kode,
			barang : barang
		};

		$('.modal-itemsaturan-list').css('opacity', .3);

		$.getJSON(_base_url + '/mastertreatment/loaditemsaturan', param, function(json){

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
	$('.btn-search-itematuran').keyup(function(){
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
		$.getJSON(_base_url + '/mastertreatment/additematuran', {id : id}, function(json){
			var $htm = '\
				<tr class="row itematuran-barang" onclick="id_delete4(' + json.item.id_barang + ');" data-itematuran="' + json.item.id_barang + '">\
					<td>\
						<input type="text" value="' + json.item.kode + '" name="kode['+$index+'][]" readonly="readonly">\
						<input type="hidden" value="' + json.item.id_barang + '" name="id_barang[' + $index + '][]">\
					</td>\
					<td>\
						<input type="text" value="' + json.item.nm_barang + '" name="nm_barang[' + $index + '][]" readonly="readonly" class="form-control" required>\
					</td>\
					<td class="col-sm-3">\
						<div class="input-group input-group-sm">\
							<input type="number" data-form="jumlah_out" min="0"  value="1" name="jumlah_out[' + $index + '][]" class="form-control text-left"  required placeholder="Masukan Jumlahnya"/>\
						  	<span class="input-group-addon">' + json.item.nm_satuan + '</span>\
						  	<input type="hidden" name="id_satuan['+ $index+'][]" value="'+json.item.id_satuan+ '">\
							<input type="hidden" name="harga_jual[' + $index +'][]" value="' +  json.item.harga_jual+ '"/>\
						</div>\
						<input type="hidden" readonly="readonly" data-form="total" value="' + json.item.id_gudang + '" name="id_gudang[' + $index + '][]" class="form-control text-right" required>\
					</td>\
				</tr>\
			';
			$('.item-' + $index ).append($htm);
			$('.barangaturan-' + json.item.id_barang).remove();
		
		});
	}
	
		id_hapus3 = function(id){
		$('[name="id_hapus3"]').val(id);
		$('.btn-hapus3').show();
		$('.item-jasaaturan').css('background', 'none');
		$('[data-jasaaturan="' + id + '"]').css('background', '#ddd');
		loadjasaaturan(1);
	}
	id_delete4 = function(id){
		$('[name="id_delete4"]').val(id);
		$('.btn-hapus4').show();
		$('.itematuran-barang').css('background', 'none');
		$('[data-itematuran="' + id + '"]').css('background', '#ddd');

		loaditemsaturan(1);
	}
	id_deletejasa= function(id){
		$('[name="id_deletejasa"]').val(id);
		$('.btn-hapusjasa').show();
		$('.item-jasa').css('background', 'none');
		$('[data-jasa="' + id + '"]').css('background', '#ddd');

		loadjasa(1);
	}

	loadtindakanatur(1);
	loadjasa(1);
	loaditemsaturan(1);

	//loaditems(1);

	
	

});
