$(function(){
	
	$('.tgl').datepicker({
		format : 'yyyy-mm-dd'
	});

	$('[data-exp="exp"]').datepicker({
		format : 'yyyy-mm-dd'
	});
	
	$('[name="sj"]').focus();

	$('[type="number"]').change(function(){
		var qty = $(this).val();
		var max = $(this).data('max');
		if(qty > max)
			$(this).val(max);
		else if(qty < 0)
			$(this).val(0);
	});

	addbonus = function(id){
		$('.btn-' + id).button('loading');
		$.post(_base_url + '/gr/addbonus', { id : id }, function(json){
			$('.bonus').append(json.content);
			$('.btn-' + id).button('reset');
			$('.btn-' + id).addClass('hide');

			$('[data-exp="bonus"]').datepicker({
				format : 'yyyy-mm-dd'
			});

			countbonus(function(res){
				if(res > 0)
					$('tr.no-bonus').addClass('hide');
				else
					$('tr.no-bonus').removeClass('hide');
			});

		}, 'json');

	}

	rmbonus = function(id){
		$('.bonus-' + id).remove();
		$('.btn-' + id).removeClass('hide');
		countbonus(function(res){
			if(res > 0)
				$('tr.no-bonus').addClass('hide');
			else
				$('tr.no-bonus').removeClass('hide');
		});
	}

	countbonus = function(res){
		var count = [];
		$('.item-bonus').each(function(i){
			count[i] = i;
		});
		return res(count.length);	
	}

	$('form').submit(function(){
		$('.btn-kembali').remove();
		swal('', 'Proses ini membutuhkan beberapa waktu...');
	});

	otheritems = function(page){

		var kode_other = $('[name="kode_other"]').val();
		var nm_other = $('[name="nm_barang_other"]').val();
		$('.other-items').css('opacity', .3);
		$.getJSON( _base_url + '/gr/otheritems', {

			kode : kode_other,
			barang : nm_other,
			page : page

		}, function(json){
			$('.other-items').html(json.items);
			$('.total-other-item').html(json.total);
			$('.pagin-other-items').html(json.pagin);

			$('.other-items').css('opacity', 1);

			$('div.pagin-other-items > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				otheritems($page);
			});

		});

	}

	$('[name="kode_other"], [name="nm_barang_other"]').keyup(function(e){
		console.log(e.keyCode);
		if(e.keyCode == 13)
			otheritems(1);
	});

	addotheritem = function(id){
		$('.other_tem_' + id).fadeOut('slow');
		$.getJSON(_base_url + '/gr/bonusother', { id : id }, function(json){
			$('.bonus').append(json.content);
			$('.btn-' + id).button('reset');
			$('.btn-' + id).addClass('hide');

			$('[data-exp="bonus"]').datepicker({
				format : 'yyyy-mm-dd'
			});

			$('#myModal').modal('hide');

		});
	}

});