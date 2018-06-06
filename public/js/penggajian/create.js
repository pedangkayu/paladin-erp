$(function(){

	$(document).on('click', '.btn-hapus', function(event) {
		event.preventDefault();
		$(this).closest('.baris_komponen').remove();
		$(document).ready(function() {
			matematika();
		});
	});
	$(document).on('click', '.btn-pinjaman', function(event) {
		event.preventDefault();
		$(this).closest('.baris_loan').remove();
		$(document).ready(function() {
			matematika();
		});
	});
	$(document).on('click', '.btn-hapus1', function(event) {
		event.preventDefault();
		$(this).closest('.baris_tambahan').remove();
		$(document).ready(function() {
			matematika();
		});
	});
	$(document).on('click', '.btn-hapus2', function(event) {
		event.preventDefault();
		$(this).closest('.baris_pendapatan').remove();
		$(document).ready(function() {
			matematika();
		});
	});
	$('.add-new-blank').click(function(){
		var $id = Math.random();
		$.getJSON(_base_url + '/penggajian/potonganku', {}, function(json){
		var $htm = '\
			<tr  class="baris_tambahan" data-item="' + $id + '">\
					<input type="hidden" value="1" name="tipe[]">\
					<td><select name="id_loanku[]" class="form-control">' + json.potonganku + '</select>\</td>\
				<td><input type="hidden" data-form="qty_potongan" name="qty_potongan[]" value="1" class="form-control">\
					<input type="number" data-form="nilai_potongan" name="nilai_potongan[]" value="" class="form-control">\
					<input type="hidden" readonly name="total_potongan_lainnya[]" data-form="total_potongan_lainnya" value="0">\
				</td>\
				<td><button  type="button" title="Hapus   ini jika tidak di pakai" class="btn  btn-hapus1">X</button></td>\
			</tr>\
		';
		$('.content-item').append($htm);
		$('.form-control').keyup(function(e){
			matematika();
		});
		$('.form-control').change(function(e){
			matematika();
		});
	});
	});
	$('.add-new-pendapatan').click(function(){
		var $id = Math.random();
		$.getJSON(_base_url + '/penggajian/pendapatanku', {}, function(json){
			var $htm = '\
				<tr  class="baris_pendapatan" data-item="' + $id + '">\
				    <input type="text" name="id_karyawan_honor[]" value="0">\
				<td><select name="id_komponen_honor[]" class="form-control">' + json.komponen + '</select></td>\
				<td><input type="number" readonly="readonly" data-form="qty" name="qty[]" value="1" class="form-control"></td>\
					<td><input type="number" data-form="nilai" name="nilai[]" value="0"  class="form-control"></td>\
						<input type="hidden" name="id_karyawan_honor[]" value="0">\
					<td><input type="number" min="0" readonly="readonly" name="total[]" data-form="total" value="0">\
					</td>\
					<td><button  type="button" title="Hapus   ini jika tidak di pakai" class="btn  btn-hapus2">X</button></td>\
				</tr>\
			';
		$('.content-pendapatan').append($htm);
		$('.form-control').keyup(function(e){
			matematika();
		});
		$('.form-control').change(function(e){
			matematika();
		});
	});
});
	matematika = function(){
		// alert('hahaha');
		var subtotal = 0;
		var subtotal_potongan =0;
		var subtotal_hutang = 0;
		$(':input[data-form="qty"]').each(function(i){
			var harga = $(':input[data-form="nilai"]')[i].value;
			var kali = harga * $(this).val();
			$(':input[data-form="total"]')[i].value = kali;
			subtotal += kali;
		});

		$(':input[data-form="qty_potongan"]').each(function(i){
			var harga_potongan = $(':input[data-form="nilai_potongan"]')[i].value;
			var kali_potongan = harga_potongan * $(this).val();
			$(':input[data-form="total_potongan_lainnya"]')[i].value = kali_potongan;
			subtotal_potongan += kali_potongan;
		});
		$(':input[data-form="qty_hutang"]').each(function(i){
			var nilai_hutang = $(':input[data-form="nilai_hutang"]')[i].value;
			var kali_hutang = nilai_hutang * $(this).val();
			$(':input[data-form="total_hutang"]')[i].value = kali_hutang;
			subtotal_hutang += kali_hutang;
		});
		var jumlah_potongan = subtotal_potongan + subtotal_hutang;
		var total_gajiku = subtotal - subtotal_potongan; //penddapatan di kurangi potongan
		var total_gaji_aff = total_gajiku - subtotal_hutang; /// di kurangi hutang
		$('.gaji-subtotal').html(number_format(subtotal,2,',','.'));
		$('.potongan-subtotal').html(number_format(subtotal_potongan,2,',','.'));
		$('.potongan-seluruhnya').html(number_format(jumlah_potongan,2,',','.'));
		$('.hutang-subtotal').html(number_format(subtotal_hutang,2,',','.'));
		$('.total_gaji_aff').html(number_format(total_gaji_aff,2,',','.'));
		$('.total_hutang_k').html(number_format(subtotal_hutang,2,',','.'));
		$(':input[name="total_pendapatan"]').val(subtotal);
		$(':input[name="total_potongan"]').val(jumlah_potongan);
		$(':input[name="gaji_bersih"]').val(total_gaji_aff);
		//$(':input[name="jumlah_pembayaran"]').val(total);
		$(':input[name="total_hutang"]').val(subtotal_hutang);

	}

	$('.form-control').keyup(function(e){
		matematika();
	});
	$('.form-control').change(function(e){
		matematika();
	});




});
