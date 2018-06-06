$(function(){
aktif = function(id){
		swal({
			title: "Anda yakin ?",
			text: "Akan Aktifkan Paket  ini !",
			type: "info",
			showCancelButton: true,
			confirmButtonColor: "#0aa699",
			confirmButtonText: "Lanjutkan",
			closeOnConfirm: true
		}, function(){
			$('.tin_' + id).css('opacity', .3);
			$.post(_base_url + '/mastertreatment/aktif', {id : id}, function(json){
				location.reload();
			}, 'json');
		});
	}

nonaktif = function(id){
		swal({
			title: "Anda yakin ?",
			text: "Akan Non Aktifkan Paket Jasa ini !",
			type: "info",
			showCancelButton: true,
			confirmButtonColor: "#0aa699",
			confirmButtonText: "Lanjutkan",
			closeOnConfirm: true
		}, function(){
			$('.tin_' + id).css('opacity', .3);
			$.post(_base_url + '/mastertreatment/nonaktif', {id : id}, function(json){
				location.reload();
			}, 'json');
		});
	}	
	detail_tinda = function(id){
		$('.detail-tindakan').html('Memuat...');
			$('.service').html('');
		 // console.log(json);
		$.post(_base_url + '/mastertreatment/detailtindakan', {id : id}, function(json){
			console.log(json);
			$('.detail-tindakan').html(json.content);
			$('.service').html(json.service);
		}, 'json');
	}

	detailpaket = function(id){
		$('.viewnama').html('');
		$('.detail-paket').html('Memuat...');
		$('btn-add').html('');
		$.post(_base_url + '/mastertreatment/detailpaket', {id : id}, function(json){
			$('.viewnama').html(json.nm_paket);
			$('.detail-paket').html(json.content);
			$('.btn-add').html(json.additem);
		}, 'json');
	}


	hapus = function(id){
		swal({
			title: "Anda Yakin ?",
			text: "Akan Menghapus Data Ini !",
			type: "info",
			showCancelButton: true,
			confirmButtonColor: "#0aa699",
			confirmButtonText: "Lanjutkan",
			closeOnConfirm : true,
		}, function(){
			$('.data1_' +id).css('opacity', .3);
			$.post(_base_url + '/mastertreatment/destroy', {id : id}, function(json){
				location.reload();
			}, 'json');
		});


	}
alldata = function(page){

	var $nm_service = $('[name="nm_service"]').val();
	var $id_unit	=$('[name="id_unit"]').val();
	var $limit      = $('[name="limit"]').val();
	var $status		= $('[name="status"]').val();
	 $('.alldata').css('opacity', .3);
	 $.ajax({
		 type 	: 'GET',
		 url 	: _base_url + '/mastertreatment/alldata',
		 data 	: {
			page 				: page,
			nm_service			: $nm_service,
			id_unit				:$id_unit,
			status				:$status,
			limit 				: $limit,
		 },
		 cache 	: false,
		 dataType : 'json',
		 success : function(json){
			 $('.alldata').html(json.data);
			 $('.pagindata').html(json.pagin);


			 $('div.pagindata > ul.pagination > li > a').click(function(e){
				 e.preventDefault();
				 var $link = $(this).attr('href');
				 var $split = $link.split('?page=');
				 var $page = $split[1];
				 alldata($page);
			 });

			 onDataCancel();

			 $('.alldata').css('opacity', 1);
		 }
	 });
 }

 $('div.pagindata > ul.pagination > li > a').click(function(e){
	 e.preventDefault();
	 var $link = $(this).attr('href');
	 var $split = $link.split('?page=');
	 var $page = $split[1];
	 alldata($page);
 });

 $('.caridata').click(function(){
	 alldata(1);
 });

 $('select').change(function(){
	 alldata(1);
 });

 $('[name="nm_service"]').keyup(function(e){
	 if(e.keyCode == 13)
		 alldata(1);
 });


 allpaket = function(page){

	var $nm_service = $('[name="nm_service"]').val();
	var $limit      = $('[name="limit"]').val();
	 $('.allpaket').css('opacity', .3);
	 $.ajax({
		 type 	: 'GET',
		 url 	: _base_url + '/mastertreatment/allpaket',
		 data 	: {
			page 				: page,
			nm_service			: $nm_service,
			limit 				: $limit,
		 },
		 cache 	: false,
		 dataType : 'json',
		 success : function(json){
			 $('.allpaket').html(json.data);
			 $('.paginpaket').html(json.pagin);


			 $('div.paginpaket > ul.pagination > li > a').click(function(e){
				 e.preventDefault();
				 var $link = $(this).attr('href');
				 var $split = $link.split('?page=');
				 var $page = $split[1];
				 allpaket($page);
			 });

			 onDataCancel();

			 $('.allpaket').css('opacity', 1);
		 }
	 });
 }

 $('div.paginpaket > ul.pagination > li > a').click(function(e){
	 e.preventDefault();
	 var $link = $(this).attr('href');
	 var $split = $link.split('?page=');
	 var $page = $split[1];
	 allpaket($page);
 });

 $('.caripaket').click(function(){
	 allpaket(1);
 });

 $('select').change(function(){
	 allpaket(1);
 });

 $('[name="nm_service"]').keyup(function(e){
	 if(e.keyCode == 13)
		 allpaket(1);
 });

});