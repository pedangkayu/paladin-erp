 @foreach($karyawan as $item)
     <tr>
         <td>{{ $item->nm_depan }} {{$item->nm_belakang}}</td>
         <td>{{$item->nm_departemen}}<br />
		 <small><i>{{$item->nama_profesi}}</i></small>
		 </td>

		 <td>
                @if($item->status_pembayaran ==1)
                    <span class="label label-important">Belum Diterima</span>
                @elseif($item->status_pembayaran ==2)
                    <span class="label label-danger">Sudah Diterima</span>
                @else
                    <span class="label label-info status">Belum di Proses</span>
                @endif
                <input type="hidden" name="id_karyawan[]" class="id_karyawanku" data-karyawan="id_{{$item->id_karyawan}}" value="{{$item->id_karyawan}}">
        </td>
		 <td>
             <a href="{{ url('/penggajian/reviewgaji/' . $item->id_karyawan ) }}" class="text-danger"><span class="label label-success">Proses</span></a>
		 </td>
         <!-- td><a href="#" onclick="event.preventDefault();detailhonorku({{ $item->id_karyawan }});" data-toggle="modal" data-target="#detail" class="text-danger " title="Proses Data"><i class="glyphicon glyphicon-zoom-in"></i></a>
         </td -->
     </tr>
 @endforeach
 <script type="text/javascript">
 function get_status_gaji_karyawan() {
     var tahun = $('.tahun').val();
     var bulan = $('.bulan').val();
     var list_karyawan = '';
     var i = 0;
     var akhir =
     $('.id_karyawanku').each(function(index, el) {
         if (i==0) {
             list_karyawan = $(this).val();
         }else{
             list_karyawan =  list_karyawan +','+ $(this).val();
         }
         i++;
     });
      $.post(_base_url + '/penggajian/status',{tahun:tahun, bulan:bulan, id_karyawan:list_karyawan}, function(res){
         $htm = '';
         $('.content-item').append($htm);
         $('.status').val(res.status);
     });
 }
 $(document).ready(function() {
     get_status_gaji_karyawan();
 });
 </script>
