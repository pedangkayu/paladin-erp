$(function(){



	destroy = function(id){

		swal({
			title: "Anda yakin ?",
			text: "Obat ini mauk di hapus dari Daftar Resep!",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			closeOnConfirm: true
		}, function(){
			$('.rsp_' + id).css('opacity', .3);
			$.post(_base_url + '/resep/destroy', {id : id}, function(json){
				location.reload();
			}, 'json');
		});
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
 allresep = function(page){

		var $id_pasien_hc = $('[name="id_pasien_hc"]').val();
		var $limit        = $('[name="limit"]').val();
		var $nomor_resep  = $('[name="nomor_resep"]').val();
		var $nama_pasien  = $('[name="nama_pasien"]').val();
		var $status       = $('[name="status"]').val();
		var $status_resep = $('[name="status_resep"]').val();


	 $('.allresep').css('opacity', .3);
	 $.ajax({
		 type 	: 'GET',
		 url 	: _base_url + '/resep/allresep',
		 data 	: {
			 page 	: page,
			 id_pasien_hc 	: $id_pasien_hc,
			 	limit 	: $limit,
			 	status 	: $status, 
			nomor_resep	: $nomor_resep,
			status_resep : $status_resep,
			nama_pasien	:$nama_pasien,

		 },
		 cache 	: false,
		 dataType : 'json',
		 success : function(json){
			 $('.allresep').html(json.data);
			 $('.paginresep').html(json.pagin);


			 $('div.paginresep > ul.pagination > li > a').click(function(e){
				 e.preventDefault();
				 var $link = $(this).attr('href');
				 var $split = $link.split('?page=');
				 var $page = $split[1];
				 allresep($page);
			 });

			 onDataCancel();

			 $('.allresep').css('opacity', 1);
		 }
	 });
 }

 $('div.paginresep > ul.pagination > li > a').click(function(e){
	 e.preventDefault();
	 var $link = $(this).attr('href');
	 var $split = $link.split('?page=');
	 var $page = $split[1];
	 allresep($page);
 });

 $('.cariresep').click(function(){
	 allresep(1);
 });

 $('select').change(function(){
	 allresep(1);
 });

 $('[name="id_pasien_hc"]').keyup(function(){
	 // if(e.keyCode == 13)
		 allresep(1);
 });
 $('[name="status_resep"]').click(function(){
		allresep(1);
	});
  $('[name="nama_pasien"]').keyup(function(){
		allresep(1);
	});
 $('[name="nomor_resep"]').keyup(function(){
	 // if(e.keyCode == 13)
		 allresep(1);
 });

});
