@extends('Master.Template')
@section('meta')
<script type="text/javascript" src="{{ asset('js/resep/resepacc.js') }}"></script>
@endsection

@section('title')
	Detail Resep OBat
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-13">
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
          <h1><i>Nama :{{ $data->nama_pasien }} </i></h1>
					<h4> Alamat &nbsp;: {{ $data->alamat_pasien}} &nbsp; </h4>
					<h4> Kota &nbsp; &nbsp; &nbsp;: {{$data->kota_pasien}}</h4>
					<h4> Hp &nbsp; &nbsp; &nbsp; &nbsp; : {{  $data->hp_pasien}}</h4>
					<h4><b>Kode Penanganan : {{ $data->nomor_treatment}}</b></h4>
					<span class="text-muted">
					Tanggal Penaganan : {{ Format::indoDate2($data->created_at) }} &nbsp;{{ Format::hari($data->created_at) }}, {{ Format::jam($data->created_at) }}
					</span>
					<!-- <p><div class="well well-sm">////</div></p> -->
					<div class="text-right">
					<tr class="sr_{{ $data->id_resep }}">
						<a href="{{ url('/treatment') }}" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
					</tr>
					</div>
				</div>
			</div>
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					<div class="table-responsive">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th width="20%">Kode</th>
									<th width="20%" >Jenis</th>

									<th width="15%" >Keterangan</th>
                  	<th width="15%" >Tarif</th>
								</tr>
							</thead>
              <tbody>
                  @foreach($tindakan as $item)
              <tr>
                <td>{{ $item->kode_service }}</td>
                <td >{{$item->nama_service }}</td>
                <td >{{ $tipe[$item->tipe]}}</td>
                <td >{{ number_format(($item->tarif)) }},00</td>
              </tr>
            @endforeach
          </tbody>
          </table>
					</div>


          <div class="table-responsive">
            <table class="table table-bordered">
            <h4><b> <center> Bahan Habis Pakai</center></b></h4>
              <thead>
                <tr>
                  <th width="20%">Kode</th>
                  <th width="20%" >Nama</th>
                  <th width="15%" >Jumlah</th>

                </tr>
              </thead>
              <tbody>
                  @foreach($bahan as $item)
              <tr>
                <td>{{ $item->kode }}</td>
                <td >{{$item->nm_barang }}</td>
                <td >{{ $item->qty }} &nbsp; {{$item->nm_satuan}}</td>
              </tr>
            @endforeach
          </tbody>
          </table>

          </div>

				</div>
			</div>
		</div>
	</div>
@endsection
