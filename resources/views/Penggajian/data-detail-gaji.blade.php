<div class="grid simple header-status">
        <div class="row">
            <div class="col-sm-12">
                <div class="grid solid red">
                    <div class="grid-body">
                        <p>
                            <div class="data-karyawan">
                                <strong> Nama karyawan</strong> : {{$data_karyawan->nm_depan}} &nbsp;{{$data_karyawan->nm_belakang}}
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
                                <strong>Profesi</strong>  : {{$data_karyawan->nama_profesi}}
                            </div>
                        </p>
                    </div>
                </div>
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
						<td>{{ $item->nm_komponen_honor }}</td>
                        <td>1 </td>
						<td >Rp. {{ number_format($item->nilai,0,',','.') }}</td>
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
