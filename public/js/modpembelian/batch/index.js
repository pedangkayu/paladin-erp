$(function(){

	$('#source').select2();

	masterbatch = function(page){
		var $nm_barang 			= $('[name="nm_barang"').val();
		var $kode 				= $('[name="kode"').val();
		var $id_kategori 		= $('[name="kat"').val();
		var $tipe 				= $('[name="tipe"').val();
		var $limit 				= $('[name="limit"').val();
		var $titipan 			= $('[name="titipan"]').prop('checked');

		$('.content-batch').css('opacity', .3);

		$.getJSON(_base_url + '/batch/masterbatch', {

			page 				: page,
			nm_barang 			: $nm_barang,
			kode 				: $kode,
			id_kategori 		: $id_kategori,
			tipe 				: $tipe,
			limit 				: $limit,
			titipan 			: $titipan

		}, function(json){
			
			$('.content-batch').css('opacity', 1);

			$('.content-batch').html(json.content);
			$('.pagin').html(json.pagin);

			onDataCancel();

			$('div.pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				masterbatch($page);
			});
		});
	}

	$('.cari').click(function(){
		masterbatch(1);
	});

	$('div.pagin > ul.pagination > li > a').click(function(e){
		e.preventDefault();
		var $link 	= $(this).attr('href');
		var $split 	= $link.split('?page=');
		var $page 	= $split[1];
		masterbatch($page);
	});

	detail = function(id_batch){

		$('.nm_barang').html('...');
		$('.kode').html('...');
		$('.oleh').html('...');
		$('.nomor_batch').html('...');
		$('.total_qty').html('...');
		$('.qty_item').html('...');
		$('.sisa').html('...');
		$('.tgl_expired').html('...');
		$('.titipan').html('...');
		$('.tgl_terima_barang').html('...');
		$('.no_spbm').html('...');
		$('.no_po').html('...');
		$('.nm_pengirim').html('...');

		$.getJSON(_base_url + '/batch/detail', {id_batch : id_batch}, function(json){
			$('.nm_barang').html(json.nm_barang);
			$('.kode').html(json.kode);
			$('.oleh').html(json.oleh);
			$('.nomor_batch').html(json.nomor_batch);
			$('.total_qty').html(json.total_qty);
			$('.qty_item').html(json.qty_item);
			$('.sisa').html(json.sisa);
			$('.tgl_expired').html(json.tgl_expired);
			$('.titipan').html(json.titipan);
			$('.tgl_terima_barang').html(json.tgl_terima_barang);
			$('.no_spbm').html(json.no_spbm);
			$('.no_po').html(json.no_po);
			$('.nm_pengirim').html(json.nm_pengirim);
		});

	}

});