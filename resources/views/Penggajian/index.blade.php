@extends('Master.Template')
@section('meta')
<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
<script src="{{ asset('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
$('#id_karyawan').select2({
    placeholder: "Pilih karyawan..."
});
function detailhonorku(id) {
 $("#detail").load(_base_url + '/penggajian/detailhonorku/'+id,function() {
     $(this).modal("show");
 });
}
function get_status_gaji() {
    // alert('hahha');
    var id_karyawan=$('.id_karyawan').val();
    var tahun = $('.tahun').val();
    var bulan = $('.bulan').val();
    // alert(bulan);
    $('#status').load(_base_url +'/penggajian/get_status_gaji',{
        tahun :tahun,
        bulan:bulan,
        id_karyawan:id_karyawan
    },
    function() {
});
}
$(document).ready(function() {
    get_status_gaji();
});

function data_karyawan(){
    var id_karyawan=$('#id_karyawan').val();
    var id_departemen = $('#id_departemen').val();
    var tahun = $('#tahun').val();
    var bulan = $('#bulan').val();
    var limit = $('#limit').val();
        $('#data_karyawan_honor').load(_base_url +'/penggajian/karyawan',{
            id_karyawan:id_karyawan,
            id_departemen:id_departemen,
            tahun :tahun,
            bulan:bulan,
            limit :limit
        },
        function() {
    });
}
$(document).ready(function() {
    data_karyawan();
});

</script>
@endsection

@section('title')
Honorarium
@endsection

@section('content')
<?php
$tahun = date("Y");
$bulan = date("m");
 ?>
<div class="row">
   <div class="col-md-8">
       <div class="grid simple ">
           <div class="grid-title no-border">
               <h4>Data  <span class="semi-bold">Karyawan Aktif</span></h4>
               <div class="tools">
			       <a href="javascript:;" class="collapse"></a>
                   <a href="javascript:;" class="reload"></a>
               </div>
           </div>

           <div class="grid-body no-border">
                 <table class="table table-striped daftar-skb">
                       <thead>
                           <tr>
                               <th>Karyawan </th>
                               <th>Departemen/Profesi</th>
                               <th>Diterima</th>
                               <th>Proses</th>
                           </tr>
                       </thead>
                       <tbody id="data_karyawan_honor">
                       </tbody>
               </table>
           </div>

       </div>
   </div>
   <div class="col-md-4">
       <div class="grid simple ">
           <div class="grid-title no-border">
               <h4>Pencarian </h4>
               <div class="tools">
			       <a href="javascript:;" class="collapse"></a>
                   <a href="javascript:;" class="reload"></a>
               </div>
           </div>
           <div class="grid-body no-border">
               <a href="{{ url('penggajian/listpenggajian') }}" class="btn btn-danger">List Gaji Karyawan</a>
               <hr>
               <div class="form-group">
                   <label>Karyawan</label>
                   <select class="form-control id_karyawan" onchange="data_karyawan()" id="id_karyawan" name="id_karyawan" >
                       <option value="">- Pilih -</option>
                       @foreach ($karyawan as $key)
                           <option value="{{ $key->id_karyawan}}">{{$key->nm_depan}} {{$key->nm_belakang}}</option>
                       @endforeach
                   </select>
               </div>
               <div class="form-group">
                   <label>Departemen</label>
                   <input type="hidden" class="form-control tahun" name="tahun" id="tahun" value="{{$tahun}}">
                   <input type="hidden" class="form-control bulan" name="bulan" id="bulan" value="{{$bulan}}">
                   <select class="form-control id_departemen" onchange="data_karyawan()" id="id_departemen" name="id_departemen" >
                       <option value="">- Pilih -</option>
                       @foreach ($karyawan as $key)
                           <option value="{{ $key->id_departemen}}">{{$key->nm_departemen}}</option>
                       @endforeach
                   </select>
               </div>
               <div class="form-group">
                   <label>Limit / Page</label>
                   <select name="limit" id="limit" onchange="data_karyawan()" class="form-control limit">
                       <option value="5">5</option>
                       <option value="10" selected="selected">10</option>
                       <option value="50">50</option>
                       <option value="100">100</option>
                       <option value="500">500</option>
                   </select>
               </div>
           </div>
       </div>
   </div>
</div>
<div class="modal fade" id="detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>

	<!-- <div class="row">
		<div class="col-sm-12">
		</div>
	</div> -->

@endsection
