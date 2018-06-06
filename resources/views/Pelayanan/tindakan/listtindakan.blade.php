@extends('Master.Template')

@section('meta')
	 <!--  -->
<script type="text/javascript" src="{{ asset('js/treatment/search.js') }}"></script>

	<style type="text/css">
		td > .link{
			display: none;
		}
		table.daftar-skb tr:hover td .link{
			display: block;
		}
	</style>
@endsection

@section('title')
	Data Treatment
@endsection

@section('content')
<div class="row">
		<div class="col-sm-9">

			<div class="grid simple">
				<div class="grid-title no-border">

				</div>
				<div class="grid-body no-border">

					<div class="table-responsive">
						<table class="table table-striped daftar-skb">
							<thead>
								<tr>
									<th>No.</th>
									<th>No Treatment</th>
									<th>Kode Pasien</th>
									<th>Nama</th>
									<th>Pemeriksa</th>
									<th>Tanggal</th>
								</tr>
							</thead>
              <tbody class="alltreatment">
                  <?php $no = 1; ?>
                @forelse($items as $item)
                <tr class="tr_{{ $item->id_treatment }}">
                    <td>{{ $no }}</td>
                    <td width="20%">
                  <div>  {{ $item->nomor_treatment}}</div>
                      <small>
                          [
                            <a href="{{ url('/tindakan/detail/'.$item->id_treatment) }}">Lihat</a>|
                            <a href="{{url('/tindakan/pindah/'. $item->id_treatment)}}">Pindah Kelas</a>
                          ]
                        </small>
                    </td>
                    <td width="20%">{{ $item->id_pasien_hc  }}</td>
                    <td>{{ $item->nama_pasien}}</td>
                    <td>{{ $item->nm_depan }}{{$item->nm_belakang}}</td>
                    <td>{{ Format::indoDate2($item->created_at) }} &nbsp;{{ Format::hari($item->created_at) }}, {{ Format::jam($item->created_at) }}</td>
                    </td>

                  </tr>
                  <?php $no++; ?>
              @empty
              <tr>
                <td colspan="6">Tidak ditemukan</td>
              </tr>
              @endforelse

              </tbody>
            </table>
          </div>
          <div class="text-right pagintreatment">
            {!! $items->render() !!}
          </div>
					</div>

				</div>
			</div>

		<!-- halaman kanan layar -->
			<div class="col-sm-3">
		<div class="grid simple">
			<div class="grid-title no-border"></div>
			<div class="grid-body no-border text-center">
				<div class="btn-group" style="width:100%;">
				<a href="{{ url('/tindakan/create') }}" class="btn btn-primary btn-block dropdown-toggle" > Buat Treatment <span class="caret"></span></a>
				</div>
			</div>
		</div>

		<div class="grid simple">
			<div class="grid-title no-border"></div>
			<div class="grid-body no-border">
				<div class="form-group">
					<label>NO TREATMENT</label>
					<input type="text" name="nomor_treatment" class="form-control">
				</div>
				<div class="form-group">
					<label>Nomor Pasien</label>
					<input type="text" name="id_pasien_hc" class="form-control">
				</div>
				<div class="form-group">
					<label>Limit / Page</label>
					<select name="limit" class="form-control">
						<option value="5">5</option>
						<option value="10" selected="selected">10</option>
						<option value="50">50</option>
						<option value="100">100</option>
						<option value="500">500</option>
					</select>
				</div>

				<div class="form-group">
					<butto class="btn btn-block btn-primary caritreatment"><i class="fa fa-search"></i> Cari</button>
					</div>

				</div>
			</div>
		</div>
	</div>

		</div>
		@endsection
