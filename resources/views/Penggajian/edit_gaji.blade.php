@extends('Master.Template')
@section('meta')
<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
<script src="{{ asset('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('/js/penggajian/create.js') }}"></script>
<script type="text/javascript">
$('#id_karyawan').select2({
    placeholder: "Pilih karyawan..."
});
function detailhonorku(id) {
 $("#detail").load(_base_url + '/penggajian/detailhonorku/'+id,function() {
     $(this).modal("show");
 });
}
function data_karyawan(){
    var id_karyawan=$('#id_karyawan').val();
    var id_departemen = $('#id_departemen').val();
    var limit = $('#limit').val();
        $('#data_karyawan_honor').load(_base_url +'/penggajian/karyawan',{
            id_karyawan:id_karyawan,
            id_departemen:id_departemen,
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
Numerisasi Gaji Pegawai
@endsection

@section('content')
<form action="{{ url('penggajian/update') }}" method="post">
    <input type="hidden" name="id_log_honor" value="{{$data_karyawan->id_log_honor}}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
     <div class="grid simple header-status">
        <div class="row">
            <div class="col-sm-12">
                <div class="grid solid red">
                    <div class="grid-body">
					        <h5> PERIODE GAJI {{ date('m/d/Y') }} </h5>
					        <h3> {{$data_karyawan->nm_depan}} {{$data_karyawan->nm_belakang}}</h3>

							<input type="hidden" name="id_karyawan" value={{$data_karyawan->id_karyawan}}>
							<input type="hidden" value="{{ date('m/d/Y') }}" name="tanggal" id="tanggal">

							<h5> Departemen : {{$data_karyawan->nm_departemen}} </h5>
							<h5> Jabatan : {{$data_karyawan->nm_jabatan}} </h5>
							<h5> Profesi : {{$data_karyawan->nm_profesi}} </h5>
							<hr />

							<h5> Sakit : 1x  | Alpha : 2x | Cuti : 4x</h5>

                    </div>
                </div>
            </div>
    </div>
</div>
<div class="col-sm-12">
    <div class="grid simple">
        <div class="grid-title no-border">
            <h4> Data <b>Pendapatan</b></h4>
        </div>
        <div class="grid-body no-border">
            <div class="text-right">
                <button type="button" title="tambah form pendapatan" class="btn btn-danger add-new-pendapatan"><i class="fa fa-plus"></i></button>
            </div>
            <div class="table-responsive">
                <table class="table table-striped daftar-skb">
                    <thead>
                        <tr>
                            <th width="40%" class="text-middle">Sumber Pendapatan </th>
                            <th width="10%" class="text-middle"></th>
                            <th width="30%" class="text-middle">Rate</th>
                            <th width="20%" class="text-middle">Jumlah</th>
                            <th width="10%" class="text-middle"></th>
                        </tr>
                    </thead>
                    <tbody class="content-gaji">
                        <?php
            			$no = 1;
            			$total=0;
            			?>
    					@foreach($detail_gaji as $item)
                        <?php
        				$i = 1;
        				$total += $item->nilai;
        				 ?>
    					<tr class="baris_komponen">
    						<td>{{ $item->nm_komponen_honor }}
                                <input type="hidden" name="id_karyawan_honor[]" value="{{$item->id_karyawan_honor}}">
                                <input type="hidden" name="id_log_honor_item[]" value="{{$item->id_log_honor_item}}">
                                <input type="hidden" name="id_komponen_honor[]" value="{{$item->id_komponen_honor}}">
                            </td>
                            <td>
                                <input type="tex" data-form="qty" name="qty[]" value="1" class="form-control">

                            </td>
    						<td>
                                <input type="number" data-form="nilai" name="nilai[]" value="{{$item->nilai}}" class="form-control subtotal">
                            </td>
    						<td ><input type="text" readonly name="total[]" data-form="total" value="{{ $item->nilai}}"></td>
                            <td><button  type="button" title="Hapus {{ $item->nm_komponen_honor }}   ini jika tidak di pakai" class="btn btn-danger btn-hapus"><i class="fa fa-trash"></i></button></td>
                        </tr>
    					@endforeach

                    </tbody>
                    <tbody class="content-pendapatan"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php
$no = 1;
$total_pinjaman=0;
$total_terbayar=0;
$sisa_hutang = 0;
?>
<div class="col-sm-12">
    <div class="grid simple">
        <div class="grid-title no-border">
            <h4> Data <b>Potongan Honor</b></h4>
        </div>
        <div class="grid-body no-border">
            <div class="text-right">
                <button type="button" class="btn btn-primary add-new-blank"><i class="fa fa-plus"></i></button>
            </div>
            <div class="table-responsive">
                <table class="table table-striped daftar-skb">
                    <thead>
                        <tr>
                            <th>Deskripsi</th>
                            <th>Jumlah Terbayar</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="content-gaji">
                        @foreach($data_potongan as $p)
                        <tr>
                            <td>{{ $p->nm_potongan }}
                                <input type="hidden" name="id_potongan[]" value="{{$p->id_potongan}}">
                            </td>
                                <input type="hidden" value="{{$p->tipe_potongan}}" name="tipe_potongan_honor[]">
            					<input type="hidden" name="id_loan[]" class="form-control"  value="{{$p->id_loan}}">
                                <input type="hidden" data-form="qty_potongan" name="qty_potongan_ku[]" value="1" class="form-control">
                                <td>
            					    <input type="number" data-form="nilai_potongan" name="nilai_potongan[]" value="{{$p->jumlah_potongan}}" class="form-control">
                                    <input type="hidden" readonly name="total_potongan_lainnya_ku[]" data-form="total_potongan_lainnya" value="{{$p->jumlah_potongan}}">
                                </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tbody class="content-item"></tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@if($data_casbon->count() >0)
    <div class="col-sm-6">
        <div class="grid simple">
            <div class="grid-title no-border">Potongan Kasbon</div>
            <div class="grid-body no-border">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Potongan </th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="content-gaji">
                        @foreach($data_casbon as $p)
                        <tr>
                            <td>{{ $p->nm_potongan }}
                                <input type="hidden" name="id_potongan[]" value="{{$p->id_potongan}}">
                            </td>
                                <input type="hidden" value="{{$p->tipe_potongan}}" name="tipe_potongan_casbon[]">
            					<input type="hidden" name="id_loan[]" class="form-control"  value="{{$p->id_loan}}">
                                <input type="hidden" data-form="qty_potongan" name="qty_potongan_ku[]" value="1" class="form-control">
                                <td>
            					    <input type="number" readonly="readonly" data-form="nilai_potongan" name="nilai_potongan[]" value="{{ $p->jumlah_potongan }}" class="form-control">
                                    <input type="hidden" readonly name="total_potongan_lainnya_ku[]" data-form="total_potongan_lainnya" value="{{$p->jumlah_potongan}}">
            					    <input type="hidden" readonly name="total_potongan[]"  value="{{$p->jumlah_potongan}}">
                                </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
<input type="hidden" name="total_pendapatan" value="{{$total}}">
<input type="hidden"  name="total_potongan" value="{{$sisa_hutang}}">
<input type="hidden"  name="gaji_bersih" value="{{($total-$sisa_hutang)}}">


<div class="col-sm-6">
    <div class="grid simple">
        <div class="grid-title no-border">
            <h4> Total <strong> Honor</strong></h4>
        </div>
        <div class="grid-body no-border">
            <div class="table-responsive">
                <table class="table table-striped">
                    <tr>
                        <td width="40%" class="text-right"><b>TOTAL PENDAPATAN :</b></td>
                        <td width="70%" class="gaji-subtotal">Rp. {{ number_format($total,0,',','.')}}</td>
                    </tr>

                    <tr>
                        <td class="text-right"><b>TOTAL HUTANG BAYAR :</b></td>
                        <td  class="text-left hutang-subtotal"> Rp. {{ number_format($sisa_hutang,0,',','.')}}</td>
                    </tr>

                    <tr>
                        <td class="text-right"><b>TOTAL POTONGAN :</b></td>
                        <td  class="text-left potongan-seluruhnya"> Rp. {{ number_format($sisa_hutang,0,',','.')}}</td>
                    </tr>

                    <tr valign="center">
                        <td class="text-right"><b>TOTAL GAJI BERSIH :</b></td>
                        <td class="text-left total_gaji_aff"> Rp. {{ number_format($total-$sisa_hutang,0,',','.')}}
                        </td>
                    </tr>

                    <tr valign="center">
                        <td colspan='2'><button class="btn btn-primary btn-block" type="submit">Update Proses Penggajian</button></td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</div>

</form>


@endsection
