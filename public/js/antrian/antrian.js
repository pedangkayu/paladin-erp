$(function(){

loadantrian = function(page){

		var NO_ANTRIAN = $('[name="modal-no_antrian"]').val();
		var NAMA_PASIEN = $('[name="modal-nama"]').val();
		var param = {
			page : page,
			NO_ANTRIAN : NO_ANTRIAN,
			NAMA_PASIEN : NAMA_PASIEN
		};
		$('.modal-antrian-list').css('opacity', .3);

		$.getJSON(_base_url + '/treatment/antrian', param, function(json){

			$('.modal-antrian-list').html(json.content);
			$('.modal-antrian-pagin').html(json.pagin);
			$('.modal-antrian-list').css('opacity', 1);
			$('body').css('cursor', 'default');

			onDataCancel();

			$('div.modal-antrian-pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				loadantrian($page);
			});
		});
	}
	$('[name="modal-no_antrian"], [name="modal-nama"]').keyup(function(e){
		if(e.keyCode == 13)
			loadantrian(1);
	});
	$('.btn-search-antrian').click(function(){
		loadantrian(1);
	});
	add_antrian = function(id){
	$('.antri-' + id).css('opacity', .3);
	$('.btn-antrian' + id).remove();
	$('.antrian-loading-' + id).removeClass('hide');
	$.getJSON(_base_url + '/treatment/addantrian', {id : id}, function(json){
		// console.log(json);
		$htm = '';
		$('.content-antri').append($htm);
		$('.antri-' + json.an.ID_JADWAL).remove();
		$('.antri-' + json.pa.id_pasien).css('opacity', 1);
		$('[name="id_pasien"]').val(json.pa.id_pasien_hc);
		$('[name="no_antrian"]').val(json.pa.no_antrian);
		$('[name="mas_id_pgw"]').val(json.an.MAS_ID_PGW);
		$('[name="ID_JADWAL"]').val(json.pa.id_jadwal_hc);
		$('[name="NAMA_PASIEN"]').val(json.an.NAMA_PASIEN);


		$('#antrian').modal('hide');

	});
}


loadantrian(1);

});