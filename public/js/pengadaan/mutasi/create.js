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

		var kode = $('[name="modal-kode-barang"]').val();
		var barang = $('[name="modal-nama-barang"]').val();
		var gudang	=$('[name="id_gudang_tujuan"]').val();

		var param = {
			page : page,
			kode : kode,
			barang : barang,
			gudang :gudang
		};

		$('.modal-barang-list').css('opacity', .3);

		$.getJSON(_base_url + '/Mutasi/loadbarang', param, function(json){

			$('.modal-barang-list').html(json.content);
			$('.modal-barang-pagin').html(json.pagin);
			$('.modal-barang-list').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('div.modal-barang-pagin > ul.pagination > li > a').click(function(e){
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

	add_item = function(id){
		$('.barang-' + id).css('opacity', .3);
		$('.btn-item-' + id).remove();
		$('.item-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/Mutasi/additem', {id : id}, function(json){
			$htm = '\
				<tr onclick="id_hapus(' + json.item.id_barang + ');" class="item-barang baris_form" data-item="' + json.item.id_barang + '">\
					<td>\
						' + json.item.kode + '\
						<input type="hidden" value="' + json.item.id_barang + '" name="id_barang[]">\
					</td>\
					<td><input type="text" value="' + json.item.nm_barang + '" name="nm_barang[]" readonly="readonly" class="form-control" required></td>\
					<td>\
						<div class="input-group input-group-sm">\
							<input type="number" data-form="qty" min="1"  value="" name="qty[]" class="form-control text-right" required>\
						  	<span class="input-group-addon">' + json.item.nm_satuan + '</span>\
						  	<input type="hidden" name="id_satuan[]" value="' + json.item.id_satuan + '" />\
						  	<input type="hidden" name="tipe[]" value="'+json.item.tipe+'">\
						</div>\
					</td>\
					<td><button title="Hapus" type="button" class="btn btn-danger btn-hapus"><i class="fa fa-trash"></i></button></td>\
				</tr>\
			';
			$('.content-items').append($htm);
			$('.barang-' + json.item.id_barang).remove();
			$('[name="nm_barang"]').val(json.item.nm_barang);
			$('[name="id_barang"]').val(json.item.id_barang);
			if($htm!=''){
				$('#table-permohoanan').show();
			}
		});
	}

	$(document).on('click', '.btn-hapus', function(event) {
		event.preventDefault();

		$(this).closest('.baris_form').remove();
	
	});
	

	loadbarang(1);
	
});
