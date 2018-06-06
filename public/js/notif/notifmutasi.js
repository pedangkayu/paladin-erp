$(function(){
	

	$.getJSON(_base_url + '/Smb/notifsmb', {}, function(json){
		if (json.total !=0) 
				$('.menunggu-konfir').addClass('badge').css('color', '#fff').html(json.total);
		},'json');
		

	// $.post(_base_url + '/Mutasi/noverif', {no_verif : true}, function(json){
	// 	if(json.total != 0)
	// 		$('.total_no_approve').addClass('badge').css('color', '#fff').html(json.total);
	// }, 'json');

	// $.post(_base_url +'/Mutasi/finishsmb', {finish_smb: 'true'}, function(json) {
	// 	if (json.total_finish !=0) 
	// 		$('.total_finish').addClass('badge').css('color', '#fff').html(json.total_finish);
	// },'json');
	
	// $.post(_base_url +'/Mutasi/proses', {}, function(json) {
	// 	if (json.total_proses !=0) 
	// 		$('.total_proses').addClass('badge').css('color', '#fff').html(json.total_proses);
	// },'json');


});