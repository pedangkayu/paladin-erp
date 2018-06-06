$(function(){

	//close_sidebar();

	$('#add-new-blank').click(function(event) {
		//alert('gv');
		var $id = Math.random();
		$.getJSON(_base_url + '/mastertreatment/addblankform',  function(res){
			$('.content-item').append(res.isi);
		});
	});

	$('.btn-hapus').click(function(){
		var $id = $('[name="id_delete"]').val();
		$('[data-item="' + $id + '"]').remove();
		$('[name="id_delete"]').val(0);
		$('.btn-hapus').hide();

		matematika();
	});


	$('#tab-4 a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});

	loaditems = function(page){

		var kode = $('[name="modal-kode-item"]').val();
		var barang = $('[name="modal-barang-item"]').val();

		var param = {
			page : page,
			kode : kode,
			barang : barang
		};

		$('.modal-items-list').css('opacity', .3);

		$.getJSON(_base_url + '/mastertreatment/loaditems', param, function(json){
			
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


		add_item = function(id){
		$('.barang-' + id).css('opacity', .3);
		$('.btn-item-' + id).remove();
		$('.item-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/mastertreatment/additem', {id : id}, function(json){
			var $htm = '\
				<tr onclick="id_hapus(' + json.item.id_barang + ');" class="item-barang" data-item="' + json.item.id_barang + '">\
					<td>\
						' + json.item.kode + '\
						<input type="hidden" value="' + json.item.id_barang + '" name="id_barang_item[]">\
					</td>\
					<td><input type="text" value="' + json.item.nm_barang + '" name="nm_barang[]" readonly="readonly" class="form-control" required></td>\
					<td>\
						<div class="input-group input-group-sm">\
							<input type="number" data-form="jumlah_out"  value="0" name="jumlah_out[]" class="form-control text-right" required>\
						  	<span class="input-group-addon">' + json.item.nm_satuan + '</span>\
						  	<input type="hidden" name="id_satuan[]" value="' + json.item.id_satuan + '" />\
						</div>\
					</td>\
				</tr>\
			';
			$('.content-paket').append($htm);
			$('.barang-' + json.item.id_barang).remove();
			$('[name="nm_barang"]').val(json.item.nm_barang);
			$('[name="id_barang"]').val(json.item.id_barang);
		});
	}
	

	id_delete = function(id){
		$('[name="id_delete"]').val(id);
		$('.btn-hapus').show();
		$('.item-barang').css('background', 'none');
		$('[data-item="' + id + '"]').css('background', '#ddd');

		loaditems(1);
	}
	
	loaditems(1);

	$(document).on('click', '.btn-hapus', function(event) {
		event.preventDefault();
		// $('#btn-hapus').removeAttr('style');
		$(this).closest('.baris_form').remove();
		/* Act on the event */
	});
	// asd = function(){
	// 	$(this).parent().remove();
	// };
	
});