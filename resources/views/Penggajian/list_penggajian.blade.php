@extends('Master.Template')
@section('meta')
<script type="text/javascript" src="{{ asset('/js/penggajian/view.js') }}"></script>
<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
<script src="{{ asset('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    $('#id_karyawan').select2({
        placeholder: "Pilih karyawan..."
    });
function detailgajiku(id) {
     $("#detail_gajiku").load(_base_url + '/penggajian/detailgaji/'+id,function() {
         $(this).modal("show");
     });
    }
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
               <h4>Data  <span class="semi-bold">Daftar Gaji Karyawan</span></h4>
               <div class="tools">
			       <a href="javascript:;" class="collapse"></a>
                   <a href="javascript:;" class="reload"></a>
               </div>
               <div class="row">
                   <div class="col-md-6">
                       <div class="form-group">
                           <select class="select" style="width:100%;" name="bulan" id="bulan">
                               @for($i=1;$i<13;$i++)
                               <option value="{{ $i }}" {{ date('m') == $i ? 'selected' : '' }}>{{ Format::nama_bulan($i) }}</option>
                               @endfor
                           </select>
                       </div>
                   </div>
                   <div class="col-md-6">
                       <div class="form-group">
                           <select class="select text-right" style="width:100%;" name="tahun" id="tahun">
                               @for($i = 2000; $i <= date('Y'); $i++)
                               <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
                               @endfor
                           </select>
                       </div>
                   </div>
               </div>
           </div>
           <div class="grid-body no-border">
                 <table class="table table-striped daftar-skb">
                       <thead>
                           <tr>
                               <th>No</th>
                               <th>Karyawan </th>
                               <th>Departemen/Profesi</th>
                               <th>Diterima</th>
                               <th>Priode Gaji</th>
                               <th>Aksi</th>
                           </tr>
                       </thead>
                       <tbody class="content-gaji">
           				<tr>
           					<td colspan="10">Data Tidak ditemukan</td>
           				</tr>
           			</tbody>

           		</table>
           		<div class="pagin text-center"></div>
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
               <div class="form-group">
                   <label>Karyawan</label>
                   <select class="form-control id_karyawan" id="id_karyawan" name="id_karyawan" >
                       <option value="">- Pilih -</option>
                       @foreach ($karyawan as $key)
                           <option value="{{ $key->id_karyawan}}">{{$key->nm_depan}} {{$key->nm_belakang}}</option>
                       @endforeach
                   </select>
               </div>
               <div class="form-group">
                   <label>Departemen</label>
                   <select class="form-control id_departemen"  id="id_departemen" name="id_departemen" >
                       <option value="">- Pilih -</option>
                       @foreach ($karyawan as $key)
                           <option value="{{ $key->id_departemen}}">{{$key->nm_departemen}}</option>
                       @endforeach
                   </select>
               </div>
               <div class="form-group">
                   <label>Limit / Page</label>
                   <select name="limit" id="limit"  class="form-control limit">
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
<div class="modal fade" id="detail_gajiku" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
@endsection
