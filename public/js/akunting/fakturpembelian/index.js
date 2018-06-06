$(function(){
	itemsfaktur = function(page){

		var $no_faktur 	= $('[name="no_faktur"]').val();
		var $duodate 	= $('[name="duodate"]').val();
		var $tanggal 	= $('[name="tanggal"]').val();
		var $status 	= $('[name="status"]').val();
		var $limit 		= $('[name="limit"]').val();


		$('.content-faktur').css('opacity', .3);
		$.getJSON(_base_url + '/fakturpembelian/itemsfaktur', {

			page 		: page,
			no_faktur	: $no_faktur,
			duodate 	: $duodate,
			tanggal 	: $tanggal,
			status 		: $status,
			limit 		: $limit

		}, function(json){

			console.log(json);

			$('.total-faktur').html(json.total);
			$('.content-faktur').html(json.content);
			$('.pagin').html(json.pagin);

			$('.content-faktur').css('opacity', 1);

			onDataCancel();

			$('div.pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				itemsfaktur($page);
			});
		});

	}


	$('div.pagin > ul.pagination > li > a').click(function(e){
		e.preventDefault();
		var $link 	= $(this).attr('href');
		var $split 	= $link.split('?page=');
		var $page 	= $split[1];
		itemsfaktur($page);
	});


	$('.tgl').datepicker({
		format : 'yyyy-mm-dd'
	});
	$('.btn-tanggal').click(function(){
		$('#tanggal').val('');
	});
	$('.btn-duodate').click(function(){
		$('#duodate').val('');
	});

	$('.cari').click(function(){
		itemsfaktur(1);
	});
	$('select').change(function(){
		itemsfaktur(1);
	});

	hapus = function(id){
		swal({   
			title: "PERINGATAN!",   
			text: "Anda yakin ingin membatalkan Faktur ini?",   
			type: "warning",   
			showCancelButton: true,   
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "Yes",   
			closeOnConfirm: true
		}, function(){
			
			$('.faktur-' + id).css('opacity', .3);
			$.post(_base_url + '/fakturpendapatan/delete', {id : id}, function(json){
				$('.faktur-' + json.id).remove();
			}, 'json');

		});
	}
	newtukar = function(id){
		$('.no_faktur').html('');
		$('.btn-acc').html('');
		$('.detail-tukar').html('Memuat...');
		$.post(_base_url + '/fakturpembelian/newtukar', {id : id}, function(json){
			$('.no_faktur').html(json.no_faktur);
			$('.detail-tukar').html(json.content);
			$('.btn-acc').html(json.button);
		}, 'json');
	}

	acc = function(id){
		
		swal({   
			title: "Anda yakin ?",   
			text: "Akan Melakukan Tukar Faktur!",   
			type: "warning",   
			showCancelButton: true,   
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "Yes, !",   
			closeOnConfirm: true
		}, function(){

		$('.btn-acc').button('loading');
		$.post(_base_url + '/fakturpembelian/acc', {id : id}, function(json){
			
			$('.btn-accs').remove();
			$('#detail').modal('hide');
			var page = $('.pagination').find('.active').find('span').html();
			itemsfaktur(page);

			swal('Sukses!', 'Tukar Faktur Berhasil.');

		}, 'json');
	});
}
});