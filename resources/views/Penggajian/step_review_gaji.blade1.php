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
Gajia & THR
@endsection

@section('content')
<form action="{{ url('penggajian/reviewgaji') }}" method="post">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="grid simple header-status">
        <div class="row">
            <div class="col-sm-8">
                <div class="grid solid red">
                    <div class="grid-body">
                        <p>
                            <div class="data-karyawan">
                                <strong> Nama karyawan</strong> : {{$data_karyawan->nm_depan}} &nbsp;{{$data_karyawan->nm_belakang}}
                                <input type="hidden" name="id_karyawan" value={{$data_karyawan->id_karyawan}}>
                            </div>
                        </p>
                        <p>
                            <div class="data-karyawan">
                                <strong> Departemen</strong> : {{$data_karyawan->nm_departemen}}
                            </div>
                        </p>
                        <p>
                            <div class="data-karyawan">
                                <strong> jabatan</strong> : {{$data_karyawan->nm_jabatan}}
                            </div>
                        </p>
                        <p>
                            <div class="data_karyawan">
                                <strong>Profesi</strong>  : {{$data_karyawan->nm_profesi}}
                            </div>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="grid solid">
                    <div class="grid-body">
                        <label for="tahun">Priode</label>
                        <div class="input-group transparent">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" value="{{ date('m/d/Y') }}" name="tanggal" id="tanggal" class="form-control" readonly="readonly">
                        </div>
                        <br>
                    </div>
                </div>
                <button class="btn btn-primary btn-block" type="submit">Proses</button>
            </div>
    </div>
</div>
<div class="col-sm-12">
    <div class="grid simple">
        <div class="grid-title no-border">
            <h4> Data <strong>ditemukan</strong></h4>
        </div>
        <div class="grid-body no-border">
            <div class="text-right">
                <button type="button" title="tambah form pendapatan" class="btn btn-danger add-new-pendapatan"><i class="fa fa-plus"></i></button>
            </div>
            <div class="table-responsive">
                <table class="table table-striped daftar-skb">
                    <thead>
                        <tr>
                            <th width="30%" class="text-middle">Pendapatan </th>
                            <th width="20%" class="text-middle"></th>
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
                                <input type="hidden" name="id_komponen_honor[]" value="{{$item->id_komponen_honor}}">
                            </td>
                            <td>
                                <input type="tex" data-form="qty" name="qty[]" value="1" class="form-control">

                            </td>
    						<td>
                                <input type="number" data-form="nilai" name="nilai[]" value="{{$item->nilai}}" class="form-control subtotal">
                            </td>
    						<td ><input type="text" readonly name="total[]" data-form="total" value="{{ number_format($item->nilai,2,',','.') }}"></td>
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
<div class="col-sm-7">
    <div class="grid simple">
        <div class="grid-title no-border">
            <h4> Data <strong> Pinjaman Belum Lunas</strong></h4>
        </div>
        <div class="grid-body no-border">
            <div class="text-right">
            </div>
            <div class="table-responsive">
                <table class="table table-striped daftar-skb">
                    <thead>
                        <tr>
                            <th>Potongan</th>
                            <th>No Loan</th>
                            <th>Jumlah Pembayar</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="content-gaji">
                        <?php
            			$no = 1;
            			$total_pinjaman=0;
                        $total_terbayar=0;
                        $sisa_hutang = 0;
            			?>
    					@foreach($data_loan as $item)
                        <?php
        				$i = 1;
                        $total_pinjaman += $item->nominal;
        				$total_terbayar += $item->total_terbayar;
                        $sisa_hutang = $total_pinjaman - $total_terbayar;
        				//  ?>
    					<tr class="baris_loan">
                            <td><input type="text" readonly name="potongan_gaji" value="Pinjaman Uang"></td>
    						<td>{{ $item->no_pinjaman }}
                                <input type="hidden" value="2" name="tipe">
                                <input type="hidden" value="{{$item->nominal}}" name="nominal">
                                <input type="hidden" value="{{$item->id_loan}}" name="id_loan">
                                <input type="hidden" value="{{$item->total_terbayar}}" name="total_terbayar">
                            </td>
    						<td>
                                <input type="hidden" data-form="qty_hutang" name="qty_hutang" value="1" class="form-control">
                                <input type="hidden" readonly name="total_hutang" data-form="total_hutang" value="0">
                                <input type="number" data-form="nilai_hutang" max="{{($item->nominal - $item->total_terbayar) }}" min="0" name="nilai_hutang" value="{{($item->nominal - $item->total_terbayar) }}" class="form-control subtotal">
                            </td>
                            <td>
                                <button  type="button" title="Hapus {{ $item->no_pinjaman }}   ini jika tidak akan dibayar" class="btn btn-danger btn-pinjaman"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
    					@endforeach
                        </tbody>
                    <!-- <tbody class="content-item"></tbody> -->
                    <input type="hidden" name="total_pendapatan" value="{{$total}}">
                    <input type="hidden"  name="total_potongan" value="{{$sisa_hutang}}">
                    <!-- <input type="hidden"  name="total_potongan" value=""> -->
                    <input type="hidden"  name="total_hutang" value="{{($item->nominal - $item->total_terbayar) }}">
                    <input type="hidden"  name="gaji_bersih" value="{{($total-$sisa_hutang)}}">
                </table>
            </div>

        </div>
    </div>
</div>
<div class="col-sm-5">
    <div class="grid simple">
        <div class="grid-title no-border">
            <h4> Data <strong> Potongan Lainnya</strong></h4>
        </div>
        <div class="grid-body no-border">
            <div class="text-right">
                <button type="button" class="btn btn-primary add-new-blank"><i class="fa fa-plus"></i></button>
            </div>
            <div class="table-responsive">
                <table class="table table-striped daftar-skb">
                    <thead>
                        <tr>
                            <th>Potongan</th>
                            <th>Jumlah Pembayar</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="content-item"></tbody>
                </table>
            </div>

        </div>
    </div>
</div>
<div class="col-sm-7">
    <div class="grid simple">

    </div>
</div>
<div class="col-sm-5">
    <div class="grid simple">
        <div class="grid-title no-border">
            <h4> Total <strong> Honor</strong></h4>
        </div>
        <div class="grid-body no-border">
            <div class="table-responsive">
                <table class="table table-striped">
                    <tr>
                        <td width="40%" class="text-right"><strong>Total Pendapatan :</strong></td>
                        <td width="70%" class="gaji-subtotal text-right">Rp.{{ number_format($total,0,',','.')}}</td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Total Potongan Lainnya:</strong>
                        </td>
                        <td  class="text-left potongan-subtotal"> Rp.00
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Total Hutang Bayar :</strong>
                        </td>
                        <td  class="text-left hutang-subtotal"> Rp.{{ number_format($sisa_hutang,0,',','.')}}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Total Potongan  :</strong>
                        </td>
                        <td  class="text-left potongan-seluruhnya"> Rp.{{ number_format($sisa_hutang,0,',','.')}}
                        </td>
                    </tr>
                    <tr valign="center">
                        <td class="text-right" ><center>Total Gaji Bersih</center> </td>
                        <td  class="text-left total_gaji_aff"> Rp.{{ number_format($total-$sisa_hutang,0,',','.')}}
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</div>

</form>

@endsection
