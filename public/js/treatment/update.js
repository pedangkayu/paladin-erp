$(function(){


	hapus= function(id){
		swal({
			title: "Anda yakin ?",
			text: "Jasa ini mauk Diahpus dari Transaksi treatment Secara Permanen !",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			closeOnConfirm: true
		}, function(){
			$('.treatment' + id).css('opacity', .3);
			$.post(_base_url + '/treatment/hapus', {id : id}, function(json){
				location.reload();
			}, 'json');
		});
	}
	refound= function(id){
		swal({
			title: "Anda yakin ?",
			text: "Jasa Ini mauk di Refund dari Transaksi treatment !",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			closeOnConfirm: true
		}, function(){
			$('.refound' + id).css('opacity', .3);
			$.post(_base_url + '/treatment/refound', {id : id}, function(json){
				location.reload();
			}, 'json');
		});
	}
	batalrefound= function(id){
		swal({
			title: "Anda yakin ?",
			text: "Batal Refund Jasa ini !",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			closeOnConfirm: true
		}, function(){
			$('.batalrefound' + id).css('opacity', .3);
			$.post(_base_url + '/treatment/batalrefound', {id : id}, function(json){
				location.reload();
			}, 'json');
		});
	}
	reseptretment = function(id_treatment_item,page,kode_service){
		 $('#reseptreatment').modal('show');

		 // Load data BHP
		var kode = $('[name="modal-kode-itemupdate"]').val();
		var barang = $('[name="modal-barang-itemupdate"]').val();
		var unit	=$('[name="unit"]').val();

		var param = {
			page : page,
			kode : kode,
			barang : barang,
			kode_service :kode_service,
			unit : unit
		};

		$('.modal-itemupdate-list').css('opacity', .3);

		$.getJSON(_base_url + '/treatment/loadupdatebhp', param, function(json){

			$('.modal-itemupdate-list').html(json.content);
			$('.modal-itemupdate-pagin').html(json.pagin);
			$('.modal-itemupdate-list').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('[name="id_treatment_item"]').val(id_treatment_item);

			$('div.modal-itemupdate-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				reseptretment(id_treatment_item,$page);
			});
		});
	}
	$('[name="modal-kode-itemupdate"], [name="modal-barang-itemupdate"]').keyup(function(e){
		if(e.keyCode == 13)add
			loaditemupdate(1);
	});
	$('[name="unit"]').change(function(){
		loaditemupdate(1);
	});
	$('.btn-search-itemupdate').click(function(){
		loaditemupdate(1);
	});
	
	/* Load data Barang */
	loaditemupdate = function(page){

		var kode = $('[name="modal-kode-itemupdate"]').val();
		var barang = $('[name="modal-barang-itemupdate"]').val();
		var unit	=$('[name="unit"]').val();

		var param = {
			page : page,
			kode : kode,
			barang : barang,
			unit : unit
		};

		$('.modal-itemupdate-list').css('opacity', .3);

		$.getJSON(_base_url + '/treatment/loadupdatebhp', param, function(json){

			$('.modal-itemupdate-list').html(json.content);
			$('.modal-itemupdate-pagin').html(json.pagin);
			$('.modal-itemupdate-list').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('div.modal-itemupdate-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				loaditemupdate($page);
			});
		});
	}
	$('[name="modal-kode-itemupdate"], [name="modal-barang-itemupdate"]').keyup(function(e){
		if(e.keyCode == 13)
			loaditemupdate(1);
	});
	$('[name="unit"]').change(function(){
		loaditemupdate(1);
	});
	$('.btn-search-itemupdate').click(function(){
		loaditemupdate(1);
	});

	add_updatebhp = function(id){
		var $index = $('[name="id_treatment_item"]').val();
		$('.update-' + id).css('opacity', .3);
		$('.btn-itemupdate-' + id).remove();
		$('.itemupdate-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/treatment/additemupdate', {id : id}, function(json){
			var $htm = '\
				<div class="row bhp-update itematuran-barang" onclick="id_delete4(' + json.item.id_item_gudang + ');" data-itematuran="' + json.item.id_item_gudang + '">\
					<div class="col-sm-3">\
						' + json.item.kode + '\
						<input type="hidden" value="' + json.item.id_barang + '" name="id_barang_item[' + $index + '][]">\
						<input type="hidden" value="' + json.item.id_item_gudang + '" name="id_item_gudang_item[' + $index + '][]">\
					</div>\
					<div class="col-sm-4">\
						' + json.item.nm_barang + '\
					</div>\
					<div class="col-sm-3">\
						<div class="input-group input-group-sm">\
							<input type="number" data-form="jumlah_out" min="0"  onchange="changeqty(this.value, ' + (json.item.masuk - json.item.keluar) + ');" value="1" name="jumlah_out_item[' + $index + '][]" class="form-control text-right"  required placeholder="Masukan Jumlahnya"/>\
						  	<span class="input-group-addon">' + json.item.nm_satuan + '</span>\
						  	<input type="hidden" name="tipeitem[' + $index + '][]" value="1" >\
						  	<input type="hidden" name="id_satuanitem[' + $index + '][]" value="'+json.item.id_satuan+ '">\
							<input type="hidden" data-form="harga_jual" name="harga_jualitem[' + $index + '][]" value="' +  json.item.harga_jual+ '"/>\
						</div>\
						<input type="hidden" readonly="readonly" data-form="total" value="" name="totalitem[]" class="form-control text-right" required>\
					</div>\
					<div> <input type="radio"  name="pakek[' + $index + '][]'+json.item.id_barang+'"  value="1">Ya\
                       <input type="radio"  name="pakek[' + $index + '][]'+json.item.id_barang+'"  checked="checked" value="0">Tidak\
					<button title="Hapus BHP" type="button" class="btn btn-danger btn-bhp"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></div>\
				</div>\
			';
			$('.item-' + $index ).append($htm);
			$('.update-' + json.item.id_item_gudang).remove();
			// $('.form-control').keyup(function(e){
			// 	perhitungan();
			// });
			// $('.form-control').change(function(e){
			// 	perhitungan();
			// });

			// perhitungan();
				});
			}
	updatejasa = function(id_treatment_item,page,kode_service){
		 $('#updatejasaform').modal('show');
		var tindakan  = $('[name="modal-nama-jasa"]').val();
		var unit_jasa =$('[name="unit_jasa"]').val();
		var param = {
			page : page,
			tindakan : tindakan,
			kode_service :kode_service,
			unit_jasa	:unit_jasa
		};

		$('.modal-jasa-list').css('opacity', .3);

		$.getJSON(_base_url + '/treatment/loadjasaupdate', param, function(json){

			$('.modal-jasa-list').html(json.content);
			$('.modal-jasa-pagin').html(json.pagin);
			$('.modal-jasa-list').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('[name="id_treatment_item"]').val(id_treatment_item);

			$('div.modal-jasa-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				updatejasaform(id_treatment_item,$page);
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
	
	/* Load data Barang */
	loadjasa = function(page){

		var tindakan = $('[name="modal-nama-jasa"]').val();
		var unit_jasa =$('[name="unit_jasa"]').val();

		var param = {
			page : page,
			tindakan : tindakan,
			unit_jasa	:unit_jasa
		};

		$('.modal-jasa-list-list').css('opacity', .3);

		$.getJSON(_base_url + '/treatment/loadjasaupdate', param, function(json){

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
add_jasaupdate = function(id){
		var $ja = $('[name="id_treatment_item"]').val();
		$('.jasaupdate-' + id).css('opacity', .3);
		$('.btn-jasaupdate-' + id).remove();
		$('.jasaupdate-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/treatment/addjasaupdate', {id : id}, function(json){
			// console.log(json);
			var $htm = '\
				 <table class="jasa-up">\
		             <tr onclick="id_hapusjasaku(' +json.item.id_service_detail+');" class="item-jasaku" data-jasaku="' +json.item.id_service_detail+ '">\
		              <td>\
		                <input type="hidden" value="0" name="id_service['+$ja+'][]">\
		                <input type="hidden" value="1" name="statusi['+$ja+'][]">\
		                <input type="hidden" name="tipeseri['+$ja+'][]" value="2" >\
		                <input type="hidden" name="service_kodei['+$ja+'][]" value="'+json.item.service_kode +'">\
		                <input type="hidden"  min="0" data-form="jumlah_out"  class="form-control" name="ju['+$ja+'][]" value="1" />\
		                <input type="hidden" class="form-control" data-form="harga_jual" name="tarif_dasari['+$ja+'][]" value="'+json.item.tarif_dasar+'"/>\
		                <input type="hidden" name="statusjasa[]" value="1" >\
		                <input type="text" value="'+json.item.nm_service+ '" name="nm_servicei['+$ja+'][]" readonly="readonly" class="form-control" required>\
		                <input type="hidden" readonly="readonly" data-form="total" value="'+ number_format(json.item.tarif_dasar,0,'','') +'" name="total['+$ja+'][]" class="form-control text-right" required>\
			            </td>\
			           '+json.dok+'\
		            </tr>\
		            </table>\
					';
			$('.content-jasaupdate-').append($htm);
			// $('.content-jasaupdate-'+$ja).append(json.content2);
			$('.jasaupdate-' + json.item.id_service_detail).remove();
			$('.jasaupdate-' + json.item.id_service_detail).css('opacity', 1);
			$('#updatejasaform').modal('hide');
			
		});
	}
		 tambah_dokter = function(id) {
		$.getJSON(_base_url + '/treatment/tambahdokter', {id : id}, function(json){
			 // console.log(json);
			var $htm = '\
			<tr class="baris_form">\
				<td>\
					<select name="id_dr['+id+'][]" class="form-control">' + json.dokter + '</select>\
				</td>\
				<td>\
				<select class="form-control" id="jabatan" required name="jabatani['+id+'][]">\
				<option value="">Pilih Jabatan Dokter </option>\
					<option value="1">DPJP</option>\
					<option value="2">Operator</option>\
				</select>\
				</td>\
				<td><button title="Hapus" type="button" class="btn btn-danger btn-hapus34"><i class="fa fa-trash"></i></button></td>\
			</tr>\
		';
			$('.contn-' + id).append($htm);
		});
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
			$.getJSON(_base_url + '/treatment/loadpaketupdate', param, function(json){
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
	add_paketupdate = function(id){
		$('.paket-' + id).css('opacity', .3);
		$('.btn-paket-' + id).remove();
		$('.paket-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/treatment/addpaketupdate', {id : id}, function(json){
			// console.log(json);
			$('.contn-treatment-update').append(json.content);
			$('.paket-' + json.paket.id_service).remove();	
			$('#produks').modal('hide');
	
		});
	}
	addbhppaket = function(id_treatment_item,page,kode_service){
		 $('#addbhppaket').modal('show');

		 // Load data BHP
		var kode = $('[name="modal-kode-paket]').val();
		var barang = $('[name="modal-barang-paket"]').val();
		var unit	=$('[name="unit"]').val();

		var param = {
			page : page,
			kode : kode,
			barang : barang,
			kode_service :kode_service,
			unit : unit
		};

		$('.modal-itempaket-list').css('opacity', .3);

		$.getJSON(_base_url + '/treatment/loadbhppaket', param, function(json){

			$('.modal-itempaket-list').html(json.content);
			$('.modal-itempaket-pagin').html(json.pagin);
			$('.modal-itempaket-list').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('[name="id_treatment_item"]').val(id_treatment_item);

			$('div.modal-itempaket-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				addbhppaket(id_treatment_item,$page);
			});
		});
	}
	$('[name="modal-kode-paket"], [name="modal-barang-paket"]').keyup(function(e){
		if(e.keyCode == 13)add
			loaditempaket(1);
	});
	$('[name="unit"]').change(function(){
		loaditempaket(1);
	});
	$('.btn-search-itempaket').click(function(){
		loaditempaket(1);
	});
	
	/* Load data Barang */
	loaditempaket = function(page){

		var kode = $('[name="modal-kode-paket]').val();
		var barang = $('[name="modal-barang-paket"]').val();
		var unit	=$('[name="unit"]').val();

		var param = {
			page : page,
			kode : kode,
			barang : barang,
			unit : unit
		};

		$('.modal-itempaket-list').css('opacity', .3);

		$.getJSON(_base_url + '/treatment/loadbhppaket', param, function(json){

			$('.modal-itempaket-list').html(json.content);
			$('.modal-itempaket-pagin').html(json.pagin);
			$('.modal-itempaket-list').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('div.modal-itempaket-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				loaditempaket($page);
			});
		});
	}
	$('[name="modal-kode-paket"], [name="modal-barang-paket"]').keyup(function(e){
		if(e.keyCode == 13)
			loaditempaket(1);
	});
	$('[name="unit"]').change(function(){
		loaditempaket(1);
	});
	$('.btn-search-itempaket').click(function(){
		loaditempaket(1);
	});

		add_bhppaket = function(id){
		var $index = $('[name="id_treatment_item"]').val();
		$('.update-' + id).css('opacity', .3);
		$('.btn-itemupdate-' + id).remove();
		$('.itemupdate-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/treatment/additemupdate', {id : id}, function(json){
			var $htm = '\
				<div class="row bhp-update itematuran-barang" onclick="id_delete4(' + json.item.id_item_gudang + ');" data-itematuran="' + json.item.id_item_gudang + '">\
					<div class="col-sm-3">\
						' + json.item.kode + '\
						<input type="hidden" value="' + json.item.id_barang + '" name="id_barang_item_paket[' + $index + '][]">\
						<input type="hidden" value="' + json.item.id_item_gudang + '" name="id_item_gudang_paket[' + $index + '][]">\
					</div>\
					<div class="col-sm-4">\
						' + json.item.nm_barang + '\
					</div>\
					<div class="col-sm-3">\
						<div class="input-group input-group-sm">\
							<input type="number" data-form="jumlah_out" min="0"  onchange="changeqty(this.value, ' + (json.item.masuk - json.item.keluar) + ');" value="1" name="jumlah_out_paket[' + $index + '][]" class="form-control text-right"  required placeholder="Masukan Jumlahnya"/>\
						  	<span class="input-group-addon">' + json.item.nm_satuan + '</span>\
						  	<input type="hidden" name="tipe_paket[' + $index + '][]" value="1" >\
						  	<input type="hidden" name="id_satuan_paket[' + $index + '][]" value="'+json.item.id_satuan+ '">\
							<input type="hidden" data-form="harga_jual" name="harga_jual_paket[' + $index + '][]" value="' +  json.item.harga_jual+ '"/>\
						</div>\
						<input type="hidden"  name="stok_paket['+$index+'][]"  value="'+json.akhir+'">\
						<input type="hidden" readonly="readonly" data-form="total" value="" name="total_paket[]" class="form-control text-right" required>\
					</div>\
					<div> <input type="radio"  name="pakek_paket[' + $index + '][]'+json.item.id_barang+'"  value="1">Ya\
                       <input type="radio"  name="pakek_paket[' + $index + '][]'+json.item.id_barang+'"  checked="checked" value="0">Tidak\
					<button title="Hapus BHP" type="button" class="btn btn-danger btn-bhp"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></div>\
				</div>\
			';
			$('.itembhp-' + $index ).append($htm);
			$('.update-' + json.item.id_item_gudang).remove();
				});
			}
	$(document).on('click', '.btn-hapus34', function(event) {
	event.preventDefault();

	$(this).closest('.baris_form').remove();
	});
	$(document).on('click', '.btn-jasa-up', function(event) {
		event.preventDefault();

		$(this).closest('.jasa-up').remove();
	});
	$(document).on('click', '.btn-bhp', function(event) {
		event.preventDefault();
		$(this).closest('.bhp-update').remove();
	});
	$(document).on('click', '.btn-paket-update', function(event) {
		event.preventDefault();

		$(this).closest('.item-paket-update').remove();
	
	});

	// loadjasa(1);
	// loaditemupdate(1);
	});