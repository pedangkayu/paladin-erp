$(function(){

detailpinjaman1 = function(id){
		$('.viewkode').html('');
		$('.btn-accpinjaman').html('');
		$('.detail-pinjaman').html('Memuat...');
		$.post(_base_url + '/Pinjaman/detailpinjaman', {id : id}, function(json){
			$('.viewkode').html(json.no_pinjaman);
			$('.detail-pinjaman').html(json.content);
			$('.btn-accpinjaman').html(json.button);
		}, 'json');
	}

	acc = function(id){
		$('.btn-accpinjaman').button('loading');
		$.post(_base_url + '/Pinjaman/accpinjaman', {id : id}, function(json){
			
			$('.btn-accpinjaman').remove();
			$('#detail').modal('hide');
			var page = $('.pagination').find('.active').find('span').html();
			allpinjaman(page);

			swal('Sukses!', 'Permohonan berhasil terverifikasi.');

		}, 'json');
	}



	destroy_cam = function(id){

		swal({
			title: "Anda yakin ?",
			text: "Obat ini mauk di hapus dari Daftar Resep!",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			closeOnConfirm: true
		}, function(){
			$('.campur_' + id).css('opacity', .3);
			$.post(_base_url + '/resep/destroycampur', {id : id}, function(json){
				location.reload();
			}, 'json');
		});
	}
	ubahstatus = function(id){
		swal({
			title: "Anda yakin ?",
			text: "Obat Ini mauk di kasihkan ke pasien !",
			type: "info",
			showCancelButton: true,
			confirmButtonColor: "#0aa699",
			confirmButtonText: "Lanjutkan",
			closeOnConfirm: true
		}, function(){
			$('.sr_' + id).css('opacity', .3);
			$.post(_base_url + '/resep/ubahstatus', {id : id}, function(json){
				location.reload();
			}, 'json');
		});
	}
 // Searching data resep
 allpinjaman = function(page){

		var $no_pinjaman = $('[name="no_pinjaman"]').val();
		var $limit        = $('[name="limit"]').val();
		var $status  = $('[name="status"]').val();
		var $nm_depan  = $('[name="nm_depan"]').val();
		


	 $('.allpinjaman').css('opacity', .3);
	 $.ajax({
		 type 	: 'GET',
		 url 	: _base_url + '/Pinjaman/allpinjaman',
		 data 	: {
			 page 	: page,
			 no_pinjaman 	: $no_pinjaman,
			 	limit 	: $limit,
			 	status 	: $status,
			nm_depan	:$nm_depan,

		 },
		 cache 	: false,
		 dataType : 'json',
		 success : function(json){
			 $('.allpinjaman').html(json.data);
			 $('.paginpinjaman').html(json.pagin);


			 $('div.paginpinjaman > ul.pagination > li > a').click(function(e){
				 e.preventDefault();
				 var $link = $(this).attr('href');
				 var $split = $link.split('?page=');
				 var $page = $split[1];
				 allpinjaman($page);
			 });

			 onDataCancel();

			 $('.allpinjaman').css('opacity', 1);
		 }
	 });
 }

 $('div.paginpinjaman > ul.pagination > li > a').click(function(e){
	 e.preventDefault();
	 var $link = $(this).attr('href');
	 var $split = $link.split('?page=');
	 var $page = $split[1];
	 allpinjaman($page);
 });

 $('.caripinjaman').click(function(){
	 allpinjaman(1);
 });

 $('select').change(function(){
	 allpinjaman(1);
 });

 $('[name="no_pinjaman"]').keyup(function(){
	 // if(e.keyCode == 13)
		 allpinjaman(1);
 });

  $('[name="nama_pasien"]').keyup(function(){
		allpinjaman(1);
	});
 

});
