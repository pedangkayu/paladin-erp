$(function(){

	getlaporan = function(page){

		var $bulan    = $('[name="bulan"]').val();
		var $tahun    = $('[name="tahun"]').val();


		var $limit    = $('[name="limit"]').val();

		var $id_karyawan    = $('[name="id_karyawan"]').val();
		var $id_departemen    = $('[name="id_departemen"]').val();

		$('.btn-proses').button('loading');

		var param = {
			page 	: page,
			bulan	: $bulan,
			tahun	: $tahun,
			id_karyawan : $id_karyawan,
			id_departemen : $id_departemen,
			limit	: $limit
		};

		$('.content-gaji').css('opacity', .3);
		$.getJSON(_base_url + '/penggajian/allpenggajian', param, function(json){

			// SETUP
			$('.btn-proses').button('reset');
			$('.content-gaji').css('opacity', 1);
			onDataCancel();

			// CONTENT
			$('.content-gaji').html(json.content);
			$('.pagin').html(json.pagin);


			$('div.pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link     = $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				getlaporan($page);
			});

		}, 'json');

	}

	$('.btn-proses').click(function(){
		getlaporan(1);
	});
    $('select').change(function(){
        getlaporan(1);
    });
    $(document).ready(function() {
        getlaporan();
    });

});
