$(function(){

	/* Daftar SPB */

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
			$.post(_base_url + '/treatment/destroy', {id : id}, function(json){
				location.reload();
			}, 'json');
		});
	}
	ubahstatus = function(id){
		swal({
			title: "Anda yakin ?",
			text: "Treatment ini di batalkan !",
			type: "info",
			showCancelButton: true,
			confirmButtonColor: "#0aa699",
			confirmButtonText: "Lanjutkan",
			closeOnConfirm: true
		}, function(){
			$('.sr_' + id).css('opacity', .3);
			$.post(_base_url + '/treatment/ubahstatus', {id : id}, function(json){
				location.reload();
			}, 'json');
		});
	}
 // Searching data resep
 alltreatment = function(page){

	 var $nomor_treatment 	= $('[name="nomor_treatment"]').val();
	 var $limit 	= $('[name="limit"]').val();
	 var $id_pasien_hc = $('[name="id_pasien_hc"]').val();
	 $('.alltreatment').css('opacity', .3);
	 $.ajax({
		 type 	: 'GET',
		 url 	: _base_url + '/treatment/alltreatment',
		 data 	: {
			 page 				: page,
			nomor_treatment		:$nomor_treatment,
			limit 				: $limit,
			id_pasien_hc 		: $id_pasien_hc,
		 },
		 cache 	: false,
		 dataType : 'json',
		 success : function(json){
			 $('.alltreatment').html(json.data);
			 $('.pagintreatment').html(json.pagin);


			 $('div.pagintreatment > ul.pagination > li > a').click(function(e){
				 e.preventDefault();
				 var $link = $(this).attr('href');
				 var $split = $link.split('?page=');
				 var $page = $split[1];
				 alltreatment($page);
			 });

			 onDataCancel();

			 $('.alltreatment').css('opacity', 1);
		 }
	 });
 }

 $('div.pagintreatment > ul.pagination > li > a').click(function(e){
	 e.preventDefault();
	 var $link = $(this).attr('href');
	 var $split = $link.split('?page=');
	 var $page = $split[1];
	 alltreatment($page);
 });

 $('.caritreatment').click(function(){
	 alltreatment(1);
 });

 $('select').change(function(){
	 alltreatment(1);
 });

 $('[name="nomor_treatment"]').keyup(function(e){
	 if(e.keyCode == 13)
		 alltreatment(1);
 });
 $('[name="id_pasien_hc"]').keyup(function(e){
	 if(e.keyCode == 13)
		 alltreatment(1);
 });

});
