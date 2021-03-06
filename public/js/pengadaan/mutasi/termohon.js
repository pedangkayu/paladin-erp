$(function(){
	$('.parent-item-selected').slimscroll({
		height : '280px',
		alwaysVisible: true
	});

	$('[type="number"]').change(function(){
		var val = $(this).val();
		if(val < 0)
			$(this).val(0);
	});

	/*Tambah Item Barang*/
	// add = function(id){
	// 	$('.item_' + id).css('opacity', .3);
	// 	$('body').css('cursor', 'wait');
	// 	$.ajax({
	// 		type : 'POST',
	// 		url : _base_url + '/pmbumum/additem',
	// 		data : {
	// 			id : id
	// 		},
	// 		cache : false,
	// 		dataType : 'json',
	// 		success : function(res){
	// 			$('.item_' + res.id).remove();
	// 			itemSelected();
	// 			$('body').css('cursor', 'default');
	// 		}
	// 	});
	// }

	// itemSelected = function(){

	// 	var $tipe = $('[name="tipegudang"]').val();

	// 	$('.item-selected').css('opacity', .3);
	// 	$.getJSON(_base_url + '/pmbumum/itemselected', { tipe : $tipe }, function(res){
	// 		$('.item-selected').html(res.data);
	// 		$('.loading-item-selected').addClass('hide');
	// 		$('.total').html(res.count);
	// 		$('.item-selected').css('opacity', 1);

	// 		if(res.count > 0){
	// 			$('.cart').removeClass('hide');
	// 		}else{
	// 			$('.cart').addClass('hide');
	// 		}

	// 		$('.hover-item').hover(function(){
	// 			$(this).find('.oneitem').toggle();
	// 		});
	// 	});
	// }

	// /*Delete All Item*/
	// $('.dellAll').click(function(){
	// 	$(this).button('loading');

	// 	var $tipe = $('[name="tipegudang"]').val();

	// 	$.getJSON(_base_url + '/pmbumum/dellall', {tipe : $tipe }, function(ses){
	// 		itemSelected();
	// 		getItems(1);
	// 		$('.dellAll').button('reset');
	// 	});
	// });
	
	// /*Menghapu sitem terpilih stu per satu*/
	// trashme = function(id){
	// 	$('.me_' + id).css('opacity', .3);
	// 	$('body').css('cursor', 'wait');
	// 	$('.me_' + id).find('.oneitem').remove();
	// 	$.post(_base_url + '/pmbumum/trashme', {id : id}, function(res){
	// 		if(res.result == true){
	// 			$('.me_' + id).remove();
	// 			getItems(1);
	// 		}
	// 	}, 'json');
			
	// }
	// itemSelected();

	/* Daftar SPB */

	delmutasispb = function(id){

		swal({   
			title: "Anda yakin ?",   
			text: "Permohonan akan dihapus secara permanen dan tidak dapat dikembalikan!",   
			type: "warning",   
			showCancelButton: true,   
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "Yes, delete it!",   
			closeOnConfirm: true
		}, function(){
			$('.mutasi_' + id).css('opacity', .3);
			$.post(_base_url + '/Mutasi/delmutasispb', {id : id}, function(json){
				$('.mutasi_' + id).remove();
			}, 'json');
		});

		
	}

	/*Pagination*/
	allpermohonan = function(page){
		
		var $no       = $('[name="kode"]').val();
		var $status   = $('[name="status"]').val();
		var $no_verif = $('[name="no_approve"]').prop('checked');
		var $limit    = $('[name="limit"]').val();
		var $gtujuan   = $('[name="tujuan"]').val();

		$('.allpermohonan').css('opacity', .3);
		$.ajax({
			type 	: 'GET',
			url 	: _base_url + '/Mutasi/allpermohonan',
			data 	: {
				page 	: page, 
				kode 	: $no, 
				status 	: $status, 
				limit 	: $limit,
				gtujuan : $gtujuan,
				// finish_smb : $finish_smb,
				no_verif : $no_verif
			},
			cache 	: false,
			dataType : 'json',
			success : function(json){
				$('.allpermohonan').html(json.data);
				$('.paginspb').html(json.pagin);
				$('.total-data').html(json.total);

				$('div.paginspb > ul.pagination > li > a').click(function(e){
					e.preventDefault();
					var $link = $(this).attr('href');
					var $split = $link.split('?page=');
					var $page = $split[1];
					allpermohonan($page);
				});

				onDataCancel();

				$('.allpermohonan').css('opacity', 1);
			}
		});
	}

	$('div.paginspb > ul.pagination > li > a').click(function(e){
		e.preventDefault();
		var $link = $(this).attr('href');
		var $split = $link.split('?page=');
		var $page = $split[1];
		allpermohonan($page);
	});

	$('.caripermohonan').click(function(){
		allpermohonan(1);
	});

	$('select').change(function(){
		allpermohonan(1);
	});

	$('[name="kode"]').keyup(function(e){
		if(e.keyCode == 13)
			allpermohonan(1);
	});
	$('[name="no_approve"]').click(function(){
		allpermohonan(1);
	});
	

	$.post(_base_url + '/Mutasi/noverif', {no_verif : true}, function(json){
		if(json.total != 0)
			$('.total_no_approve').addClass('badge').css('color', '#fff').html(json.total);
	}, 'json');

	$.post(_base_url +'/Mutasi/finishsmb', {finish_smb: 'true'}, function(json) {
		if (json.total_finish !=0) 
			$('.total_finish').addClass('badge').css('color', '#fff').html(json.total_finish);
	},'json');
	
	$.post(_base_url +'/Mutasi/proses', {}, function(json) {
		if (json.total_proses !=0) 
			$('.total_proses').addClass('badge').css('color', '#fff').html(json.total_proses);
	},'json');

	detailspbmutasi = function(id){
		$('.viewkode').html('');
		$('.btn-acc').html('');
		$('.detail-pmb').html('Memuat...');
		$.post(_base_url + '/Mutasi/detailspbmutasi', {id : id}, function(json){
			$('.viewkode').html(json.kode);
			$('.detail-pmb').html(json.content);
			$('.btn-acc').html(json.button);
		}, 'json');
	}

	acc = function(id){
		$('.btn-accspb').button('loading');
		$.post(_base_url + '/Mutasi/accmutasi', {id : id}, function(json){
			
			$('.btn-accspb').remove();
			$('#detail').modal('hide');
			var page = $('.pagination').find('.active').find('span').html();
			allpermohonan(page);

			swal('Sukses!', 'Permohonan berhasil terverifikasi.');

		}, 'json');
	}

	listviewskb = function(id){
		$('#listdetailSKB').html('Memuat...');
		$.getJSON(_base_url + '/pmbumum/listskb', {id : id}, function(json){
			//console.log(json);
			$('#listdetailSKB').html(json.content);
		});
	}

	/**
	* Review Barang
	*/
	review = function(id){
		var body = $('.review-detail');
		var kode = $('.review-kode');
		var load = '<i class="fa fa-circle-o-notch fa-spin"></i> Memuat...';
		body.html(load);
		kode.html('');
		$('.link').html('');
		$.ajax({
			type : 'POST',
			url : _base_url + '/logistik/review',
			data : {id : id},
			cache : false,
			dataType : 'json',
			success : function(res){
				if(res.result == true){
					kode.html(res.kode);
					body.html(res.content);
					//$('.link').html(res.link);
				}
			}
		});
	}

});