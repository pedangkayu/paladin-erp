$(function(){



	destroy = function(id){

		swal({
			title: "Anda yakin ?",
			text: "Obat ini mauk di hapus dari Daftar Resep!",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			closeOnConfirm: true
		}, function(){
			$('.rsp_' + id).css('opacity', .3);
			$.post(_base_url + '/resep/destroy', {id : id}, function(json){
				location.reload();
			}, 'json');
		});
	}
	destroy_cam = function(id){

		swal({
			title: "Anda yakin ?",
			text: "Obat ini mauk di hapus dari Daftar Resep!",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			closeOnConfirm: true
		}, function(){
			$('.campur_' + id).css('opacity', .3);
			$.post(_base_url + '/resep/destroycampur', {id : id}, function(json){
				location.reload();
			}, 'json');
		});
	}
	ubahstatus = function(id){
		swal({
			title: "Anda yakin ?",
			text: "Obat Ini mauk di kasihkan ke pasien !",
			type: "info",
			showCancelButton: true,
			confirmButtonColor: "#0aa699",
			confirmButtonText: "Lanjutkan",
			closeOnConfirm: true
		}, function(){
			$('.sr_' + id).css('opacity', .3);
			$.post(_base_url + '/resep/ubahstatus', {id : id}, function(json){
				location.reload();
			}, 'json');
		});
	}
 // Searching data resep
 allresep = function(page){

		var $id_pasien_hc = $('[name="id_pasien_hc"]').val();
		var $limit        = $('[name="limit"]').val();
		var $nomor_resep  = $('[name="nomor_resep"]').val();
		var $nama_pasien  = $('[name="nama_pasien"]').val();
		var $status       = $('[name="status"]').val();
		var $status_resep = $('[name="status_resep"]').val();


	 $('.allresep').css('opacity', .3);
	 $.ajax({
		 type 	: 'GET',
		 url 	: _base_url + '/resep/allresep',
		 data 	: {
			 page 	: page,
			 id_pasien_hc 	: $id_pasien_hc,
			 	limit 	: $limit,
			 	status 	: $status, 
			nomor_resep	: $nomor_resep,
			status_resep : $status_resep,
			nama_pasien	:$nama_pasien,

		 },
		 cache 	: false,
		 dataType : 'json',
		 success : function(json){
			 $('.allresep').html(json.data);
			 $('.paginresep').html(json.pagin);


			 $('div.paginresep > ul.pagination > li > a').click(function(e){
				 e.preventDefault();
				 var $link = $(this).attr('href');
				 var $split = $link.split('?page=');
				 var $page = $split[1];
				 allresep($page);
			 });

			 onDataCancel();

			 $('.allresep').css('opacity', 1);
		 }
	 });
 }

 $('div.paginresep > ul.pagination > li > a').click(function(e){
	 e.preventDefault();
	 var $link = $(this).attr('href');
	 var $split = $link.split('?page=');
	 var $page = $split[1];
	 allresep($page);
 });

 $('.cariresep').click(function(){
	 allresep(1);
 });

 $('select').change(function(){
	 allresep(1);
 });

 $('[name="id_pasien_hc"]').keyup(function(){
	 // if(e.keyCode == 13)
		 allresep(1);
 });
 $('[name="status_resep"]').click(function(){
		allresep(1);
	});
  $('[name="nama_pasien"]').keyup(function(){
		allresep(1);
	});
 $('[name="nomor_resep"]').keyup(function(){
	 // if(e.keyCode == 13)
		 allresep(1);
 });
 // hitung hitungan total harga obat di apotik
perhitungan = function(){
		var subtotal = 0;
		
		$(':input[data-form="qty"]').each(function(i){
			/* PENJUMLAHAN */
			var harga = $(':input[data-form="harga_jual"]')[i].value;
			var kali = harga * $(this).val();

			$(':input[data-form="total"]')[i].value = kali;
			subtotal += kali;
		});
		$('.resep-subtotal').html(number_format(subtotal,2,',','.'));	
	}

	$('.form-control').keyup(function(e){
		perhitungan();
	});
	$('.form-control').change(function(e){
		perhitungan();
	});

	loaditems = function(page){

		var kode = $('[name="modal-kode-obat"]').val();
		var barang = $('[name="modal-barang-obat"]').val();

		var param = {
			page : page,
			kode : kode,
			barang : barang
		};

		$('.modal-obat-list').css('opacity', .3);

		$.getJSON(_base_url + '/resep/loaditems', param, function(json){

			$('.modal-obat-list').html(json.content);
			$('.modal-obat-pagin').html(json.pagin);
			$('.modal-obat-list').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('div.modal-obat-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				loaditems($page);
			});
		});
	}
	$('[name="modal-kode-obat"], [name="modal-barang-obat"]').keyup(function(e){
		if(e.keyCode == 13)
			loaditems(1);
	});
	$('.btn-search-obat').click(function(){
		loaditems(1);
	});
		/*  Penambaha Item  */
	add_item = function(id){
		$('.barang-' + id).css('opacity', .3);
		$('.btn-item-' + id).remove();
		$('.item-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/resep/additem', {id : id}, function(json){
			var $htm = '\
				<tr  class="item-barang obat-paten" data-item="' + json.item.id_barang + '">\
					<td>\
						<input type="hidden" value="' + json.item.id_barang + '" name="id_barang_p[]">\
						<input type="hidden" value="' + json.item.id_item_gudang + '" name="id_item_gudang_P[]">\
					<input type="text" value="' + json.item.nm_barang + '" name="nm_barang_p[]" readonly="readonly" class="form-control" required></td>\
					<td>'+json.stok+' ' + json.item.nm_satuan + '</td>\
					<td>\
						<div class="input-group input-group-sm">\
							<input type="number" min="0" data-form="qty" onchange="changeqty(this.value, ' + (json.item.masuk - json.item.keluar) + ');" value="" name="jumlah_out_p[]" class="form-control text-right" required>\
						  	<span class="input-group-addon">' + json.item.nm_satuan + '</span>\
						  	<input type="hidden" name="id_satuan_p[]" value="' + json.item.id_satuan + '" />\
						</div>\
					</td>\
					<td>\
						<select name="id_resep_aturan_p[]" class="form-control">' + json.pakai + '</select>\
						<input type="hidden" data-form="status_obat" value="1" name="status_obat[]" class="form-control text-right" required>\
						<input type="hidden" readonly="readonly" data-form="total" value="' + json.item.id_gudang + '" name="id_gudang_p[]" class="form-control text-right" required>\
					</td>\
					<td>\
						<input type="hidden" value="" name="keterangan_p[]" >\
						<input type="text" data-form="harga_jual"  value="' + json.item.harga_jual + '" name="harga_jual_p[]" class="form-control text-right" required>\
					</td>\
					<td>\
					<div><button title="Hapus BHP" type="button" class="btn btn-danger btn-paten"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></div>\</div>\</td>\
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
	tambahcampur = function(id_resep_item,page){
		 $('#tambahcampur').modal('show');

		 // Load data BHP
		var kode = $('[name="modal-kode-campur"]').val();
		var barang = $('[name="modal-barang-campur"]').val();

		var param = {
			page : page,
			kode : kode,
			barang : barang
		};

		$('.modal-campur-list').css('opacity', .3);

		$.getJSON(_base_url + '/resep/loadbhp', param, function(json){

			$('.modal-campur-list').html(json.content);
			$('.modal-campur-pagin').html(json.pagin);
			$('.modal-campur-list').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('[name="id_resep_item"]').val(id_resep_item);

			$('div.modal-campur-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				tambahcampur(id_resep_item,$page);
			});
		});
	}
	$('[name="modal-kode-campur"], [name="modal-barang-campur"]').keyup(function(e){
		if(e.keyCode == 13)
			loadbhp(1);
	});
	$('.btn-search-campur').click(function(){
		loadbhp(1);
	});
		/* Load data Barang */
	loadbhp = function(page){

		var kode = $('[name="modal-kode-campur"]').val();
		var barang = $('[name="modal-barang-campur"]').val();

		var param = {
			page : page,
			kode : kode,
			barang : barang
		};

		$('.modal-campur-list').css('opacity', .3);

		$.getJSON(_base_url + '/resep/loadbhp', param, function(json){

			$('.modal-campur-list').html(json.content);
			$('.modal-campur-pagin').html(json.pagin);
			$('.modal-campur-list').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('div.modal-campur-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				loadbhp($page);
			});
		});
	}
	$('[name="modal-kode-campur"], [name="modal-barang-campur"]').keyup(function(e){
		if(e.keyCode == 13)
			loadbhp(1);
	});
	$('.btn-search-campur').click(function(){
		loadbhp(1);
	});
	add_obtcampur = function(id){
	var $index = $('[name="id_resep_item"]').val();
	// console.log($index);
	$('.barangaturan-' + id).css('opacity', .3);
	$('.btn-itematuran-' + id).remove();
	$('.itematuran-loading-' + id).removeClass('hide');
	$.getJSON(_base_url + '/resep/addobtcampur', {id : id}, function(json){
		var $htm = '\
				<div class="item-barang obt-cam" data-item="' + json.item.id_barang + '">\
					<div class="col-sm-3">\
						' + json.item.kode + '\
						<input type="hidden" value="' + json.item.id_barang + '" name="id_barang_c[' + $index + '][]">\
						<input type="hidden" value="' + json.item.id_item_gudang + '" name="id_item_gudang_c[' + $index + '][]">\
					</div>\
					<div class="col-sm-4"><input type="text" value="' + json.item.nm_barang + '" name="nm_barang_c[' + $index + '][]" readonly="readonly" class="form-control" required></div>\
					<div class="col-sm-3">\
						<div class="input-group input-group-sm">\
							<input type="number" min="0" data-form="jumlah_out" onchange="changeqty(this.value, ' + (json.item.masuk - json.item.keluar) + ');" value="1" name="jumlah_out_c[' + $index + '][]" class="form-control text-right" required>\
						  	<span class="input-group-addon">' + json.item.nm_satuan + '</span>\
						  	<input type="hidden" name="id_satuan_c[' + $index + '][]" value="' + json.item.id_satuan + '" />\
						</div>\
						<input type="hidden" data-form="harga_jual" value="' + json.item.harga_jual + '" name="harga_jual_c[' + $index + '][]" class="form-control text-right" required>\
						<input type="hidden" data-form="status_obat" value="2" name="status_obat_c[' + $index + '][]" class="form-control text-right" required>\
						<input type="hidden" readonly="readonly" data-form="total" value="' + json.item.id_gudang + '" name="id_gudang_c[' + $index + '][]" class="form-control text-right" required>\
					</div>\
					<div><button title="Hapus BHP" type="button" class="btn btn-danger btn-cam"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></div>\
					</div>\
				</div>\
			';

		// $($htm).insertAfter('.last_item_paket');
		$('.itemhpb-' + $index ).append($htm);
		// $('.content-campur').append($htm);
		$('.barangaturan-' + json.item.id_barang).remove();
	
	});
}
	$(document).on('click', '.btn-paten', function(event) {
		event.preventDefault();
		$(this).closest('.obat-paten').remove();
	});
	$(document).on('click', '.btn-cam', function(event) {
		event.preventDefault();
		$(this).closest('.obt-cam').remove();
	});


	loaditems(1);
	loadbhp(1);
});
