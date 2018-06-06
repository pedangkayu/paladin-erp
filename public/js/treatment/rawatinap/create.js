$(function(){

	//close_sidebar();
	$('[name="tanggal"]').datepicker();
	$('[name="duodate"]').datepicker();

	$('.cari-pasien').click(function(){
		// loadpamy();
	});



	$('.btn-hapus').click(function(){
		var $id = $('[name="id_delete"]').val();
		$('[data-item="' + $id + '"]').remove();
		$('[name="id_delete"]').val(0);
		$('.btn-hapus').hide();

	});


	$('#tab-4 a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});

	/* Load  nama pasien rawat dari mssql */
	loadrawat = function(page){

		var ID_PASIEN = $('[name="modal-id_pasien"]').val();
		var NAMA_PASIEN = $('[name="modal-nama_pasien"]').val();
		var param = {
			page : page,
			ID_PASIEN : ID_PASIEN,
			NAMA_PASIEN : NAMA_PASIEN
		};
		$('.modal-rawat-list').css('opacity', .3);

		$.getJSON(_base_url + '/Rawatinap/loadrawat', param, function(json){

			$('.modal-rawat-list').html(json.content);
			$('.modal-rawat-pagin').html(json.pagin);
			$('.modal-rawat-list').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('div.modal-rawat-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				loadrawat($page);
			});
		});
	}
	$('[name="modal-id_pasien"]').keyup(function(e){
		if(e.keyCode == 13)
			loadrawat(1);
	});
	$('[name="modal-nama_pasien"]').change(function(){
		loadrawat(1);
	});
	$('.btn-search-pasien').click(function(){
		loadrawat(1);
	});


	add_rawat= function(id){
		$('.rawat-' + id).css('opacity', .3);
		$('.btn-rawat-' + id).remove();
		$('.rawat-loading-' + id).removeClass('hide');
		$.getJSON(_base_url + '/Rawatinap/addrawat', {id : id}, function(json){
			// console.log(json);//
			$htm = '';
			$('.content-item').append($htm);
			$('.rawat-' + json.item.ID_ANTRIAN_RWT_INAP).remove();
			$('.rawat-' + json.item.ID_ANTRIAN_RWT_INAP).css('opacity', 1);
			$('[name="nama_pasien"]').val(json.item.NAMA_PASIEN);
			$('[name="id_pasien"]').val(json.item.ID_PASIEN);
			$('[name="id_antrian"]').val(json.item.ID_ANTRIAN_RWT_INAP);
			$('[name="alamat_pasien"]').val(json.item.ALAMAT_PASIEN);
			$('[name="tgl_cekin"]').val(json.item.TGL_MULAI_PAKAI_KAMAR);
			$('#rawat').modal('hide');
		});
	}
	//---aadd data rawat ianap//

	id_delete = function(id){
		$('[name="id_delete"]').val(id);
		$('.btn-hapus').show();
		$('.item-barang').css('background', 'none');
		$('[data-item="' + id + '"]').css('background', '#ddd');
		loadrawat(1);
	}
	loadrawat(1);
});
