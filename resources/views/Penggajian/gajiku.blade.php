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
                                <input type="hidden" name="id_karyawan" value={{$data_karyawan->id_karyawan}}"">
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
                        <label for="tahun">Periode</label>
                        <select class="select text-center" style="width:100%;" name="periode" id="tahun">
                            @for($i = date('Y'); $i <= date('Y'); $i++)
                            <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>


                        <label for="bulan">Bulan</label>
                        <select class="select" style="width:100%;" name="bulan" id="bulan">
                            <option value="1" {{ date('m') == 1 ? 'selected' : '' }}>Januari</option>
                            <option value="2" {{ date('m') == 2 ? 'selected' : '' }}>Februari</option>
                            <option value="3" {{ date('m') == 3 ? 'selected' : '' }}>Maret</option>
                            <option value="4" {{ date('m') == 4 ? 'selected' : '' }}>April</option>
                            <option value="5" {{ date('m') == 5 ? 'selected' : '' }}>Mei</option>
                            <option value="6" {{ date('m') == 6 ? 'selected' : '' }}>Juni</option>
                            <option value="7" {{ date('m') == 7 ? 'selected' : '' }}>Juli</option>
                            <option value="8" {{ date('m') == 8 ? 'selected' : '' }}>Agustus</option>
                            <option value="9" {{ date('m') == 9 ? 'selected' : '' }}>September</option>
                            <option value="10" {{ date('m') == 10 ? 'selected' : '' }}>Oktober</option>
                            <option value="11" {{ date('m') == 11 ? 'selected' : '' }}>Nopenber</option>
                            <option value="12" {{ date('m') == 12 ? 'selected' : '' }}>Desember</option>
                        </select>
                        <br>
                    </div>
                </div>
                <button class="btn btn-primary btn-block" type="submit">Proses</button>
            </div>
    </div>
</div>
<div class="col-sm-8">
<div class="grid simple">
    <div class="grid-title no-border">
        <h4> Data <strong>ditemukan</strong></h4>
    </div>
    <div class="grid-body no-border">
        <div class="table-responsive">
            <table class="table table-striped daftar-skb">
                <thead>
                    <tr>
                        <th>Pendapatan </th>
                        <th>Aktif</th>
                        <th>Rate</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody class="content-gaji">
					@foreach($detail_gaji as $item)
					<tr>
						<td>{{ $item->nm_komponen_honor }}
                            <input type="hidden" name="id_karyawan_honor[]" value="{{$item->id_karyawan_honor}}">
                            <input type="hidden" name="id_komponen_honor[]" value="{{$item->id_komponen_honor}}">
                        </td>
                        <td>1 </td>
						<td>
                            <input type="hidden" name="nilai[]" value="{{$item->nilai}}">
                            Rp. {{ number_format($item->nilai,0,',','.') }}
                        </td>
						<td >{{ number_format($item->nilai,2,',','.') }}</td>
					</tr>
					@endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<div class="col-sm-4">
<div class="grid simple">
    <div class="grid-title no-border">
        <h4> Data <strong>ditemukan</strong></h4>
    </div>
    <div class="grid-body no-border">

        <div class="table-responsive">
            <table class="table table-striped daftar-skb">
                <thead>
                    <tr>
                        <th>Potongan</th>
                        <th>Jumlah Pembayar</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="content-potongan">
                </tbody>
            </table>
        </div>

    </div>
</div>
</div>
</form>

@endsection
