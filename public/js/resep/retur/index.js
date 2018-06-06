$(function(){

	/*Pagination*/
	allretur = function(page){
		
		var $no_retur_resep = $('[name="no_retur_resep"]').val();
		var $tanggal_retur  = $('[name="tanggal_retur"]').val();
		var $no_resep       = $('[name="no_resep"]').val();
		var $id_pasien_hc   = $('[name="id_pasien_hc"]').val();
		var $limit          = $('[name="limit"]').val();

		$('.item-retur').css('opacity', .3);
		$('body').css('cursor', 'wait');

		$.ajax({
			type 	: 'GET',
			url 	: _base_url + '/resep/allretur',
			data 	: {
				page 	: page,
				no_retur_resep 	: $no_retur_resep,
				tanggal_retur : $tanggal_retur,
				no_resep 	: $no_resep,
				id_pasien_hc: $id_pasien_hc,
				limit 	: $limit
			},
			cache 	: false,
			dataType : 'json',
			success : function(res){
				$('.item-retur').html(res.content);
				$('.pagin').html(res.pagin);
				$('.item-retur').css('opacity', 1);
				$('body').css('cursor', 'default');

				onDataCancel();
				
				$('div.pagin > ul.pagination > li > a').click(function(e){
					e.preventDefault();
					var $link     = $(this).attr('href');
					var $split 	= $link.split('?page=');
					var $page 	= $split[1];
					allretur($page);
				});
			}
		});

	}
	
	$('div.pagin > ul.pagination > li > a').click(function(e){
		e.preventDefault();
		var $link 	= $(this).attr('href');
		var $split 	= $link.split('?page=');
		var $page 	= $split[1];
		allretur($page);
	});
	/*End Pagination*/
	 $('select').change(function(){
	 allretur(1);
 		});
	 $('[name="id_pasien_hc"]').keyup(function(){
		 allretur(1);
 	});
	 $('[name="no_resep"]').keyup(function(){
		 allretur(1);
 	});
	 $('[name="no_retur_resep"]').keyup(function(){
		 allretur(1);
 	});
	 $('[name="tanggal_retur"]').keyup(function(){
		 allretur(1);
 	});
	$('.cari').click(function(){
		allretur(1);
	});

});