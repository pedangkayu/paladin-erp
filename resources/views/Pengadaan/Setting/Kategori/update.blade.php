@extends('Master.Template')

@section('content')
<div class="col-md-12">
	<div class="grid simple">
		<div class="grid-title no-border">
			<h4>Data Kategori</h4>
			<div class="tools">
				<a href="javascript:;" class="collapse"></a> 
				<a href="javascript:;" class="reload"></a>
			</div>
		</div>
		<div class="grid-body no-border">
			<div class="row">
			<div class="col-sm-7">
					<form action="{{ url('kategori/update') }}" method="post">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="id" value="{{ $data->id_kategori }}">
						<div class="form-group">
							<label>Nama Kategori</label>
							<input type="text" name="nama" class="form-control" required value="{{ $data->nm_kategori }}" >
						</div>
						<div class="form-group">
							<label>Alias</label>
							<input type="text" name="alias" class="form-control" value="{{ $data->alias }}" >
						</div>
					<div class="form-group">
						<label>Akun Persediaan</label>
						<select name="id_coa" required class="form-control" >
							<option value="">-Pilih COA-</option>
							{!! $select_coa !!}
						</select>
					</div>
					<div class="form-group">
						<label>Akun HPP (Pembelian)</label>
						<select name="coa_pembelian" required class="form-control">
							<option value="">- Pilih COA -</option>
							{!! $coa_pembelian !!}
						</select>
					</div>
						<div class="form-group">
							<button class="btn btn-primary" type="submit">Simpan</button>
							<a href="{{ url('kategori') }}"><button class="btn btn-danger" type="button">Kembali</button></a>
						</div>
					</form>
				</div>
			</div>
			
		</div>
	</div>
</div>
@endsection