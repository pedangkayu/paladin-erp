$(function(){
	
	//close_sidebar();
	$('[name="tanggal"]').datepicker();
	$('[name="duodate"]').datepicker();
	


	$('#tab-4 a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
	

	/* Load data Barang */
	loadbarang = function(page){

		var kode = $('[name="modal-kode-item"]').val();
		var barang = $('[name="modal-nama-item"]').val();
		var gudang	=$('[name="id_gudang_tujuan"]').val();

		var param = {
			page : page,
			kode : kode,
			barang : barang,
			gudang :gudang
		};

		$('.modal-item-list').css('opacity', .3);

		$.getJSON(_base_url + '/Mutasi/loaditem', param, function(json){

			$('.modal-item-list').html(json.content);
			$('.modal-item-pagin').html(json.pagin);
			$('.modal-item-list').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('div.modal-item-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				loadbarang($page);
			});
		});
	}
	$('[name="modal-kode-barang"], [name="modal-nama-barang"]').keyup(function(e){
		if(e.keyCode == 13)
			loadbarang(1);
	});
	$('[name="id_gudang_tujuan"]').change(function(){
		loadbarang(1);
	});
	$('.btn-search-barang').click(function(){
		loadbarang(1);
	});

	add_itembarang = function(id){
		$('.item-' + id).css('opacity', .3);
		$('.btn-barang-' + id).remove();
		$('.barang-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/Mutasi/additembarang', {id : id}, function(json){
			$htm = '\
				<tr onclick="id_hapus(' + json.item.id_barang + ');" class="item-barang baris_form" data-item="' + json.item.id_barang + '">\
					<td>\
						' + json.item.kode + '\
						<input type="hidden" value="' + json.item.id_barang + '" name="id_barang_item[]">\
					</td>\
					<td><input type="text" value="' + json.item.nm_barang + '" name="nm_barang[]" readonly="readonly" class="form-control" required></td>\
					<td>\
						<div class="input-group input-group-sm">\
							<input type="number" data-form="qty" min="0"  value="0" name="qty_item[]" class="form-control text-right" required>\
						  	<span class="input-group-addon">' + json.item.nm_satuan + '</span>\
						  	<input type="hidden" name="id_satuan_item[]" value="' + json.item.id_satuan + '" />\
						  	<input type="hidden" name="tipe_item[]" value="'+json.item.tipe+'">\
						</div>\
					</td>\
					<td><button title="Hapus" type="button" class="btn btn-danger btn-hapus"><i class="fa fa-trash"></i></button></td>\
				</tr>\
			';
			$('.content-brang-item').append($htm);
			$('.item-' + json.item.id_barang).remove();
			$('[name="nm_barang"]').val(json.item.nm_barang);
			$('[name="id_barang"]').val(json.item.id_barang);
			
		});
	}

	$(document).on('click', '.btn-hapus', function(event) {
		event.preventDefault();

		$(this).closest('.baris_form').remove();
	
	});
	
	delmutasiitem = function(id){

		swal({   
			title: "Anda yakin ?",   
			text: "Item ini  akan dihapus secara permanen dan tidak dapat dikembalikan!",   
			type: "warning",   
			showCancelButton: true,   
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "Yes, delete it!",   
			closeOnConfirm: true
		}, function(){
			$('.item_' + id).css('opacity', .3);
			$.post(_base_url + '/Mutasi/delmutasiitem', {id : id}, function(json){
				$('.item_' + id).remove();
			}, 'json');
		});

		
	}
	loadbarang(1);
	
});
