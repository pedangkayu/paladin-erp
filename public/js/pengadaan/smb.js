$(function(){
	$.getJSON(_base_url + '/Smb/notifsmb', {}, function(json){
		if(json.total > 0){
			var $total = json.total > 9 ? '9+' : json.total;
			$('.spb-notif').html('<span  title="' + json.total + '" class="badge" style="background:#ff0000;">' + $total + '</span>');

		}
	});
	
	// 
	/*Pagination*/
	allpmb = function(page){

		var $no 	= $('[name="kode"]').val();
		var $status = $('[name="status"]').val();
		var $limit 	= $('[name="limit"]').val();
		var $pemohon_gud 	= $('[name="pemohon_gud"]').val();
		var $deadline = $('[name="deadline"]').val();
		var $surat = $('[name="surat"]').val();
		// console.log($pemohon_gud);
		$('.allpmb').css('opacity', .3);
		$.ajax({
			type 	: 'GET',
			url 	: _base_url + '/Smb/allpmb',
			data 	: {
				page 	: page, 
				kode 	: $no, 
				status 	: $status, 
				limit 	: $limit,
				pemohon_gud 	: $pemohon_gud,
				deadline: $deadline,
				surat	: $surat
			},
			cache 	: false,
			dataType : 'json',
			success : function(json){
				$('.allpmb').html(json.data);
				$('.paginpmb').html(json.pagin);

				$('div.paginpmb > ul.pagination > li > a').click(function(e){
					e.preventDefault();
					var $link = $(this).attr('href');
					var $split = $link.split('?page=');
					var $page = $split[1];
					allpmb($page);
				});

				$('.allpmb').css('opacity', 1);
				onDataCancel();
			}
		});
	}

	$('div.paginpmb > ul.pagination > li > a').click(function(e){
		e.preventDefault();
		var $link = $(this).attr('href');
		var $split = $link.split('?page=');
		var $page = $split[1];
		allpmb($page);
	});

	$('.caripmb').click(function(){
		allpmb(1);
	});


	detailspbmutasi = function(id){
		$('.viewkode').html('');
		$('.btn-acc').html('');
		$('.detail-pmb').html('Memuat...');
		$.post(_base_url + '/Smb/detailspbmutasi', {id : id}, function(json){
			$('.viewkode').html(json.kode);
			$('.detail-pmb').html(json.content);
			$('.btn-acc').html(json.button);
		}, 'json');
	}

	$('.btn-prosesSPB').click(function(){
		swal({
			title: "Anda yakin ?",   
			text: "Pastikan kembali permintaan ini, jika sudah yakin silahkan dilanjutkan!",
			type: "warning",   
			showCancelButton: true,   
			confirmButtonColor: "#0b9c8f",   
			confirmButtonText: "Yes, Process!",   
			closeOnConfirm: true 
		}, function(){
			onDataCancel();
			$('#prosesSPB').submit();
		});
	});


	/*Pagination*/
	allsmb = function(page){

		var $no_mutasi_spb = $('[name="no_mutasi_spb"]').val();
		var $no_mutasi_skb	= $('[name="no_mutasi_skb"]').val();
		var $limit 	= $('[name="limit"]').val();
		var $pemohon 	= $('[name="unit_pemohon"]').val();
		var $tanggal = $('[name="tanggal"]').val();

		$('.content-smb').css('opacity', .3);
		$.ajax({
			type 	: 'GET',
			url 	: _base_url + '/Smb/allsmb',
			data 	: {
				page 	: page,
				no_mutasi_skb 	: $no_mutasi_skb,
				no_mutasi_spb 	: $no_mutasi_spb,
				limit 	: $limit,
				pemohon 	: $pemohon,
				tanggal	: $tanggal
			},
			cache 	: false,
			dataType : 'json',
			success : function(json){
				$('.content-smb').html(json.content);
				$('.pagin-smb').html(json.pagin);

				$('div.pagin-smb > ul.pagination > li > a').click(function(e){
					e.preventDefault();
					var $link = $(this).attr('href');
					var $split = $link.split('?page=');
					var $page = $split[1];
					allsmb($page);
				});

				$('.content-smb').css('opacity', 1);
				onDataCancel();
			}
		});
	}

	$('div.pagin-smb > ul.pagination > li > a').click(function(e){
		e.preventDefault();
		var $link = $(this).attr('href');
		var $split = $link.split('?page=');
		var $page = $split[1];
		allsmb($page);
	});

	$('.carismb').click(function(){
		allsmb(1);
	});

	ubahspb = function(id){
		swal({   
			title: "Anda yakin ?",   
			text: "Permohonan akan dianggap selesai!",   
			type: "info",   
			showCancelButton: true,   
			confirmButtonColor: "#0aa699",   
			confirmButtonText: "Selesai",   
			closeOnConfirm: true
		}, function(){
			$('.spb_' + id).css('opacity', .3);
			$.post(_base_url + '/skb/ubahspb', {id : id}, function(json){
				location.reload();
			}, 'json');
		});
	}

});