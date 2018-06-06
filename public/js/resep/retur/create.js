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
					<td class="v-align-middle">\
                         <div class="checkbox check-default">\
							<input id="checkbox'+json.item.id_barang+'" name="retur[]" type="checkbox" value="1">\
							<label for="checkbox'+json.item.id_barang+'"></label>\
						</div>\
                    </td>\
					<td>\
						<input type="hidden" value="' + json.item.id_barang + '" name="id_barang_p[]">\
						<input type="hidden" value="' + json.item.id_item_gudang + '" name="id_item_gudang_P[]">\
					<input type="text" value="' + json.item.nm_barang + '" name="nm_barang_p[]" readonly="readonly" class="form-control" required></td>\
					<td><span class="label '+json.class+'">'+json.stok+' ' + json.item.nm_satuan + '</span></td>\
					<td>\
						<div class="input-group input-group-sm">\
							<input type="number" min="0" data-form="qty" onchange="changeqty(this.value, ' + (json.item.masuk - json.item.keluar) + ');" value="" name="jumlah_out_p[]" class="form-control text-right" required>\
						  	<span class="input-group-addon">' + json.item.nm_satuan + '</span>\
						  	<input type="hidden" name="id_satuan_p[]" value="' + json.item.id_satuan + '" />\
						</div>\
					</td>\
					<td>\
						<div class="input-group input-group-sm">\
							<input type="number" name="qty_retur[]" readonly="readonly" value="0" class="form-control text-right">\
							<span class="input-group-addon">'+json.item.nm_satuan+'</span>\
						</div>\
					</td>\
					<td>\
						<select name="id_resep_aturan_p[]" class="form-control">' + json.pakai + '</select>\
						<input type="hidden" data-form="status_obat" value="1" name="status_obat[]" class="form-control text-right" required>\
						<input type="hidden" readonly="readonly" data-form="total" value="' + json.item.id_gudang + '" name="id_gudang_p[]" class="form-control text-right" required>\
						<input type="hidden" value="" name="keterangan_p[]" >\
						<input type="hidden" data-form="harga_jual"  value="' + json.item.harga_jual + '" name="harga_jual_p[]" class="form-control text-right" required>\
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
	
	$(document).on('click', '.btn-paten', function(event) {
		event.preventDefault();
		$(this).closest('.obat-paten').remove();
	});
	$(document).on('click', '.btn-cam', function(event) {
		event.preventDefault();
		$(this).closest('.obt-cam').remove();
	});


	loaditems(1);

});
