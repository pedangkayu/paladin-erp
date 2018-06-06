$(function(){
	pindah = function(id){
		swal({
			title: "Anda yakin ?",
			text: "Akan Melakukan Perpindahan Kelas !",
			type: "info",
			showCancelButton: true,
			confirmButtonColor: "#0aa699",
			confirmButtonText: "Lanjutkan",
			closeOnConfirm: true
		}, function(){
			$('.sr_' + id).css('opacity', .3);
			$.post(_base_url + '/treatment/pindah', {id : id}, function(json){
				location.reload();
			}, 'json');
		});
	}





})