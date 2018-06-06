$(function(){

	destroy = function(id){
		swal({
			title: "Anda yakin ?",
			text: "Jasa ini mauk di hapus dari Daftar Paket Ini !",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			closeOnConfirm: true
		}, function(){
			$('.masterpak_' + id).css('opacity', .3);
			$.post(_base_url + '/mastertreatment/hapus', {id : id}, function(json){
				location.reload();
			}, 'json');
		});
	}
	sembunyi= function(id){
		swal({
			title: "Anda yakin ?",
			text: "Barang / Obat ini mauk di hapus dari Daftar Paket Ini !",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			closeOnConfirm: true
		}, function(){
			$('.bhp_' + id).css('opacity', .3);
			$.post(_base_url + '/mastertreatment/sembunyi', {id : id}, function(json){
				location.reload();
			}, 'json');
		});
	}

	//load
	bhp = function(id_service,page,coa){
		 $('#bhp').modal('show');

		 // Load data BHP
		var kode = $('[name="modal-kode-bhp"]').val();
		var barang = $('[name="modal-barang-bhp"]').val();

		var param = {
			page : page,
			kode : kode,
			barang : barang,
			coa :coa
		};

		$('.modal-bhp-list').css('opacity', .3);

		$.getJSON(_base_url + '/mastertreatment/loadbhp', param, function(json){

			$('.modal-bhp-list').html(json.content);
			$('.modal-bhp-pagin').html(json.pagin);
			$('.modal-bhp-list').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('[name="id_service"]').val(id_service);

			$('div.modal-bhp-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				bhp(id_service,$page);
			});
		});
	}
	$('[name="modal-kode-bhp"], [name="modal-barang-bhp"]').keyup(function(e){
		if(e.keyCode == 13)add
			loadbhp(1);
	});
	$('.btn-search-bhp').keyup(function(e){
		loadbhp(1);
	});
	$('.btn-search-bhp').click(function(){
		loadbhp(1);
	});

	/* Load data Barang masuk 2222*/
	loadbhp = function(page){

		var kode = $('[name="modal-kode-bhp"]').val();
		var barang = $('[name="modal-barang-bhp"]').val();

		var param = {
			page : page,
			kode : kode,
			barang : barang
		};

		$('.modal-bhp-list').css('opacity', .3);

		$.getJSON(_base_url + '/mastertreatment/loadbhp', param, function(json){

			$('.modal-bhp-list').html(json.content);
			$('.modal-bhp-pagin').html(json.pagin);
			$('.modal-bhp-list').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('div.modal-bhp-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				loadbhp($page);
			});
		});
	}
	$('[name="modal-kode-bhp"], [name="modal-barang-bhp"]').keyup(function(e){
		if(e.keyCode == 13)
			loadbhp(1);
	});
	$('.btn-search-bhp').keyup(function(){
		loadbhp(1);
	});
	$('.btn-search-bhp').click(function(){
		loadbhp(1);
	});
	add_bhp = function(id){
			var $index = $('[name="id_service"]').val();
			$('.bhp-' + id).css('opacity', .3);
			$('.btn-bhp-' + id).remove();
			$('.bhp-loading-' + id).removeClass('hide');
			$.getJSON(_base_url + '/mastertreatment/addbhp', {id : id}, function(json){
				var $htm = '\
					<tr class="bhp-barang" onclick="id_delete4(' + json.item.id_barang + ');" data-bhp="' + json.item.id_barang + '">\
						<td>\
							<input type="hidden" value="' + json.item.kode + '" name="kode[]" readonly="readonly">\
							<input type="hidden" value="' + json.item.id_barang + '" name="id_barangi[]">\
							<input type="text" value="' + json.item.nm_barang + '" name="nm_barang[]" readonly="readonly" class="form-control" required>\
						</td>\
						<td class="col-sm-3">\
							<div class="input-group input-group-sm">\
								<input type="number" data-form="jumlah_out" min="0"  value="1" name="jumlah_out[]" class="form-control text-left"  required placeholder="Masukan Jumlahnya"/>\
							  	<span class="input-group-addon">' + json.item.nm_satuan + '</span>\
							  	<input type="hidden" name="id_satuani[]" value="'+json.item.id_satuan+ '">\
								<input type="hidden" name="harga_jual[]" value="' +  json.item.harga_jual+ '"/>\
							</div>\
							<input type="hidden" readonly="readonly" data-form="total" value="' + json.item.id_gudang + '" name="id_gudangi[]" class="form-control text-right" required>\
						</td>\
					</tr>\
				';
				$('.content-bhp').append($htm);
				$('.bhp-' + json.item.id_barang).remove();
			
			});
		}

		jasa = function(id_service,page,coa){
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

		$.getJSON(_base_url + '/mastertreatment/loadupdatejasa', param, function(json){

			$('.modal-jasa-list').html(json.content);
			$('.modal-jasa-pagin').html(json.pagin);
			$('.modal-jasa-list').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('[name="id_service"]').val(id_service);

			$('div.modal-jasa-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				jasa(id_service,$page);
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

		$.getJSON(_base_url + '/mastertreatment/loadupdatejasa', param, function(json){

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
	add_jasaupdate = function(id){
	var $a = $('[name="id_service"]').val();

		$('.jasaupdate-' + id).css('opacity', .3);
		$('.btn-jasaupdate-' + id).remove();
		$('.jasaupdate-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/mastertreatment/addjasaupdate', {id : id}, function(json){
			console.log(json);
			var $htm = '\
				<tr onclick="id_deletejasaupdate(' + json.pa.id_service_detail + ');" class="item-jasaupdate" data-jasaupdate="' + json.pa.id_service_detail + '">\
				<td>\
						<input type="text" value="' + json.pa.nm_service + '" class="form-control" readonly="readonly" >\
						<input type="hidden" value="' + json.pa.service_kode + '" name="service_kode_jasa[]">\
						<input type="hidden" value="' +json.pa.id_service_detail+ '" name="id_service_detaili[]">\
				</td>\
				</tr>\
			';
			$('.content-jasa').append($htm);
			$('.jasaupdate-' + json.pa.id_service_detail).remove();
		
		});

	}

	loadbhp(1);
	loadjasa();

	});