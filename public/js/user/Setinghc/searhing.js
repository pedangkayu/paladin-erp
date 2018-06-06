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

 alluser = function(page){
 	var id = $('[name="id_pasien"]').val();
 	
	var $id_gudang_jasa = $('[name="id_gudang_jasa"]').val();
	var $limit           = $('[name="limit"]').val();
	var $id_gudang_item    = $('[name="id_gudang_item"]').val();

	 $('.alltreatment').css('opacity', .3);
	 $.ajax({
		 type 	: 'GET',
		 url 	: _base_url + '/Setinghc/alluser',
		 data 	: {
			page 				: page,
			id_gudang_jasa		:$id_gudang_jasa,
			limit 				: $limit,
			id_gudang_item 		: $id_gudang_item,
		 },
		 cache 	: false,
		 dataType : 'json',
		 success : function(json){
			 $('.alluser').html(json.data);
			 $('.paginuser').html(json.pagin);


			 $('div.paginuser > ul.pagination > li > a').click(function(e){
				 e.preventDefault();
				 var $link = $(this).attr('href');
				 var $split = $link.split('?page=');
				 var $page = $split[1];
				 alluser($page);
			 });

			 onDataCancel();

			 $('.alluser').css('opacity', 1);
		 }
	 });
 }

 $('div.paginuser > ul.pagination > li > a').click(function(e){
	 e.preventDefault();
	 var $link = $(this).attr('href');
	 var $split = $link.split('?page=');
	 var $page = $split[1];
	 alluser ($page);
 });

 $('.cariuser').click(function(){
	 alluser (1);
 });

 $('select').change(function(){
	 alluser (1);
 });

 $('[name="id_gudang_item"]').keyup(function(e){
	 if(e.keyCode == 13)
		 alluser (1);
 });
 $('[name="id_gudang_jasa"]').change(function(e){
	 if(e.keyCode == 13)add
		 alluser (1);
 });

});
