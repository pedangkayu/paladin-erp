$(function() {
	getItems = function(page){
		
		var $src 	= $('[name="src"').val();
		var $kode 	= $('[name="kode"').val();
		var $sort 	= $('[name="sort"]').val();
		var $orderby = $('[name="orderby"]').val();
		$('.contents-items').css('opacity', .3);
		$('body').css('cursor', 'wait');

		$.ajax({
			type 	: 'GET',
			url 	: _base_url + '/users/allitems',
			data 	: {
				page 	: page,
				src 	: $src,
				sort 	: $sort,
				orderby : $orderby
			},
			cache 	: false,
			dataType : 'json',
			success : function(res){
				$('.contents-items').html(res.data);
				console.log(res);
				$('.pagins').html(res.pagin);
				$('.contents-items').css('opacity', 1);
				$('body').css('cursor', 'default');

				$('div.pagins > ul.pagination > li > a').click(function(e){
					e.preventDefault();
					var $link 	= $(this).attr('href');
					var $split 	= $link.split('?page=');
					var $page 	= $split[1];
					getItems($page);
				});

				onDataCancel();
				$('[data-toggle="tooltip"]').tooltip();

			}
		});

	}

	$('div.pagins > ul.pagination > li > a').click(function(e){
		e.preventDefault();
		var $link 	= $(this).attr('href');
		var $split 	= $link.split('?page=');
		var $page 	= $split[1];
		getItems($page);
	});
	/*End Pagination*/


	$('.cari-user').click(function(){
		getItems(1);
	});		
});