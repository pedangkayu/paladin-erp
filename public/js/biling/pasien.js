$(function(){

	$('.tgl').datepicker({
		format : 'yyyy-mm-dd'
	});

	itempasien = function(page){

		var $nama_pasien 	= $('[name="nama_pasien"]').val();
		var $status 	= $('[name="status"]').val();
		var $tanggal 	= $('[name="tanggal"]').val();
		var $limit 		= $('[name="limit"]').val();


		$('.list-pasien').css('opacity', .3);
		$.getJSON(_base_url + '/biling/anypasiens', {

			page 		: page,
			nama	: $nama_pasien,
			status 		: $status,
			tanggal 	: $tanggal,
			limit 		: $limit

		}, function(json){

			// console.log(json);

			$('.total-pasien').html(json.total);
			$('.list-pasien').html(json.items);
			$('.pagin').html(json.pagin);

			$('.list-pasien').css('opacity', 1);

			onDataCancel();

			$('div.pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				itempasien($page);
			});
		});

	}


	$('div.pagin > ul.pagination > li > a').click(function(e){
		e.preventDefault();
		var $link 	= $(this).attr('href');
		var $split 	= $link.split('?page=');
		var $page 	= $split[1];
		itempasien($page);
	});

	$('.btn-tanggal').click(function(){
		$('#tanggal').val('');
	});
	
	$('.cari').click(function(){
		itempasien(1);
	});
	$('select').change(function(){
		itempasien(1);
	});
	$('[name="nama_pasien"]').keyup(function(e){
		if(e.keyCode == 13)
			itempasien(1);
	});

});