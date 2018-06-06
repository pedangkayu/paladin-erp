$(function(){
	/*Pagination*/
	getItems = function(page){
		
		var $kode 	= $('[name="kode"]').val();
		var $item 	= $('[name="nm_barang"]').val();
		var $kat 	= $('[name="id_kategori"]').val();
		var $tipe 	= $('[name="jenis"]').val();
		var $limit 	= $('[name="limit"]').val();

		$('.content-barang').css('opacity', .3);
		$('body').css('cursor', 'wait');

		$.ajax({
			type 	: 'GET',
			url 	: _base_url + '/subgudang/allitems',
			data 	: {
				page 	: page,
				kode 	: $kode,
				item 	: $item,
				kat 	: $kat,
				tipe 	: $tipe,
				limit 	: $limit
			},
			cache 	: false,
			dataType : 'json',
			success : function(res){
				$('.content-barang').html(res.data);
				$('.pagins').html(res.pagin);
				$('.content-barang').css('opacity', 1);
				$('body').css('cursor', 'default');

				onDataCancel();

				$('div.pagins > ul.pagination > li > a').click(function(e){
					e.preventDefault();
					var $link 	= $(this).attr('href');
					var $split 	= $link.split('?page=');
					var $page 	= $split[1];
					getItems($page);
				});
			}
		});

	}
	
	$('div.pagins > ul.pagination > li > a').click(function(e){
		e.preventDefault();
		var $link 	= $(this).attr('href');
		var $split 	= $link.split('?page=');
		var $page 	= $split[1];
		getItems($page);
	});
	/*End Pagination*/

	/*Advance Searching*/
	$('.Searching').click(function(){
		getItems(1);
	});
	$('[name="id_kategori"]').change(function(){
		getItems(1);
	});
	$('[name="jenis"]').change(function(){
		getItems(1);
	});
	$('[name="limit"]').change(function(){
		getItems(1);
	});
	$('[name="kode"]').keyup(function(e){
		if(e.keyCode == 13 || $(this).val().length == 0){
			getItems(1);
		}
	});
	$('[name="nm_barang"]').keyup(function(e){
		if(e.keyCode == 13 || $(this).val().length == 0){
			getItems(1);
		}
	});
	/*End Advance Searching*/

	/*Tambah Item Barang*/
	add = function(id){
		$('.item_' + id).css('opacity', .3);
		$('body').css('cursor', 'wait');
		$.ajax({
			type : 'POST',
			url : _base_url + '/subgudang/additem',
			data : {
				id : id
			},
			cache : false,
			dataType : 'json',
			success : function(res){
				$('.item_' + res.id).remove();
				itemSelected();
				$('body').css('cursor', 'default');
			}
		});
	}

	itemSelected = function(){
		$('.item-selected').css('opacity', .3);
		var $tipe 	= $('[name="jenis"]').val();
		$.getJSON(_base_url + '/subgudang/itemselected', {tipe : $tipe}, function(res){
			$('.item-selected').html(res.data);
			$('.loading-item-selected').addClass('hide');
			$('.total').html(res.count);
			$('.item-selected').css('opacity', 1);

			if(res.count > 0){
				$('.cart').removeClass('hide');
			}else{
				$('.cart').addClass('hide');
			}

			$('.hover-item').hover(function(){
				$(this).find('.oneitem').toggle();
			});
		});
	}

	/*Delete All Item*/
	$('.dellAll').click(function(){
		$(this).button('loading');
		$.getJSON(_base_url + '/subgudang/dellall', {}, function(ses){
			itemSelected();
			getItems(1);
			$('.dellAll').button('reset');
		});
	});
	/*Menghapu sitem terpilih stu per satu*/
	trashme = function(id){
		$('.me_' + id).css('opacity', .3);
		$('body').css('cursor', 'wait');
		$('.me_' + id).find('.oneitem').remove();
		$.post(_base_url + '/subgudang/trashme', {id : id}, function(res){
			if(res.result == true){
				$('.me_' + id).remove();
				itemSelected();
				getItems(1);
			}
		}, 'json');
			
	}
	itemSelected();
	loaditem = function(page){

		var kode = $('[name="modal-kode"]').val();
		var barang = $('[name="modal-barang"]').val();

		var param = {
			page : page,
			kode : kode,
			barang : barang
		};

		$('.modal-item-list').css('opacity', .3);

		$.getJSON(_base_url + '/subgudang/loaditem', param, function(json){

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
				loaditem($page);
			});
		});
	}
	$('[name="modal-kode-item"], [name="modal-barang-item"]').keyup(function(e){
		if(e.keyCode == 13)
			loaditem(1);
	});
	$('.btn-search-item').keyup(function(){
		loaditem(1);
	});
	$('.btn-search-item').click(function(){
		loaditem(1);
	});
	add_item = function(id){
		$('.item-' + id).css('opacity', .3);
		$('.btn-item-' + id).remove();
		$('.item-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/subgudang/additem', {id : id}, function(json){
			var $htm = '\
				<tr class="row item-barang" onclick="id_delete4(' + json.item.id_barang + ');" data-item="' + json.item.id_barang + '">\
					<td ><a href="javascript:;" data-toggle="tooltip" data-placement="bottom" title="'+ json.item.nm_barang+'">'+ json.item.nm_barang+'</a>\
						<div class="text-muted"><small>'+ json.item.kode +'</small></div>\
					</td>\
					<td>\
						<input type="hidden" value="' + json.item.id_barang + '" name="id_barang_t[]">\
						<input type="text" size="3" value="0" name="[]" readonly="readonly" class="form-control" required>\
					</td>\
					<td >\
							<input type="number" data-form="jumlah_masuk" min="0"  value="" name="jumlah_masuk[]" class="form-control text-left"  required placeholder="Masukkan Jumlahnya"/>\
							<input type="hidden" data-form="jumlah_out" min="0"  value="0" name="jumlah_out[]" class="form-control text-left"  required placeholder="Masukkan Jumlahnya"/>\
						  	<input type="hidden" name="id_satuan_t[]" value="'+json.item.id_satuan+ '">\
						  	<input type="hidden" name="gudang[]" value="'+json.id_gudang+'">\
					</td>\
					<td><span class="input-group-addon">' + json.item.nm_satuan + '</span></td>\
					<td><button title="Hapus Barang" type="button" class="btn  btn-hapus">x</button></td>\
				</tr>\
			';
			$('.item-' ).append($htm);
			$('.item-' + json.item.id_barang).remove();
		
		});
	}

	$(document).on('click', '.btn-hapus', function(event) {
		event.preventDefault();

		$(this).closest('.item-barang').remove();
	
	});
});
