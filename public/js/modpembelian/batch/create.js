$(function(){

	$('[name="tgl_exp"]').datepicker({
		format : 'yyyy-mm-dd'
	});

	getbatch = function(id_spbm_item, tgl_exp){
		$('[name="tgl_exp"]').val(tgl_exp);
		$('[name="id_spbm_item"]').val(id_spbm_item);
		$('[name="no_batch"]').focus();

		/* SETUP */
		$('.modal-cotent-batch').html('<tr><td colspan="3">Memuat...</td></tr>');
		$('.total').html(0);

		$.getJSON( _base_url + '/batch/getbatch', {id_spbm_item : id_spbm_item}, function(json){
			$('.modal-cotent-batch').html(json.content);
			$('.total').html(json.total);
			$('.total-batch-' + id_spbm_item).html(json.total);
			onDataCancel();

			$('[name="qty"]').val(json.sisa);

			$('[name="list_tanggal"]').datepicker({
				format : 'yyyy-mm-dd'
			});
		});
	}

	$('.add-batch').click(function(){
		var $no_batch = $('[name="no_batch"]').val();
		var $qty = $('[name="qty"]').val();
		var $tgl_exp = $('[name="tgl_exp"]').val();
		var $id_spbm_item = $('[name="id_spbm_item"]').val();
		var $id_spbm = $(this).data('spbm');

		var param = {
			no_batch : $no_batch,
			tgl_exp : $tgl_exp,
			id_spbm_item : $id_spbm_item,
			id_spbm : $id_spbm,
			qty : $qty
		};

		try{

			if($no_batch.length == 0 )
				throw "Nomor Batch/Kode Produksi belum ditentukan.";
			if($qty == "" || $qty == 0)
				throw "Total Qty belum ditentukan.";

			$('.add-batch').button('loading');
			$.post(_base_url + '/batch/create', param, function(json){
				
				swal('PERINGATAN!', json.err);		

				if(json.result == true){
					$('[name="no_batch"]').val('');
					$('[name="qty"]').val('');
				}

				getbatch($id_spbm_item, $tgl_exp);

				$('.add-batch').button('reset');
			}, 'json');

		}catch(e){
			swal('PERINGATAN!', e);
		}

		console.log(param);

	});

	update_qty = function(qty, id){
		$.post(_base_url + '/batch/updateqty', {qty : qty, id : id}, function(json){

		}, 'json');
	}

	hapus = function(id){

		swal({   
			title: "PERINGATAN!",   
			text: "Anda yakin ingin Menghapus Batch ini?",   
			type: "warning",   
			showCancelButton: true,   
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "Yes, delete it!",   
			closeOnConfirm: true
		}, function(){
			$('.batch-' + id).css('opacity', .3);
			$.post(_base_url + '/batch/hapus', {id : id}, function(json){
				$('.batch-' + json.id).remove();
			}, 'json');
		});

	}

});