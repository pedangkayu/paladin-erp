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
 alldeposit = function(page){

		var $id_pasien_hc = $('[name="id_pasien_hc"]').val();
		var $limit        = $('[name="limit"]').val();
		
		var $nama_pasien  = $('[name="nama_pasien"]').val();
		


	 $('.alldeposit').css('opacity', .3);
	 $.ajax({
		 type 	: 'GET',
		 url 	: _base_url + '/Deposit/alldeposit',
		 data 	: {
			 page 	: page,
			 id_pasien_hc 	: $id_pasien_hc,
			 	limit 	: $limit,
			nama_pasien	:$nama_pasien,

		 },
		 cache 	: false,
		 dataType : 'json',
		 success : function(json){
			 $('.alldeposit').html(json.data);
			 $('.pagindeposit').html(json.pagin);


			 $('div.pagindeposit > ul.pagination > li > a').click(function(e){
				 e.preventDefault();
				 var $link = $(this).attr('href');
				 var $split = $link.split('?page=');
				 var $page = $split[1];
				 alldeposit($page);
			 });

			 onDataCancel();

			 $('.alldeposit').css('opacity', 1);
		 }
	 });
 }

 $('div.pagindeposit > ul.pagination > li > a').click(function(e){
	 e.preventDefault();
	 var $link = $(this).attr('href');
	 var $split = $link.split('?page=');
	 var $page = $split[1];
	 alldeposit($page);
 });

 $('.carideposit').click(function(){
	 alldeposit(1);
 });

 $('select').change(function(){
	 alldeposit(1);
 });

 $('[name="id_pasien_hc"]').keyup(function(){
	 // if(e.keyCode == 13)
		 alldeposit(1);
 });

  $('[name="nama_pasien"]').keyup(function(){
		alldeposit(1);
	});
 

});
