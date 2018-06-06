@extends('Master.Template')

@section('meta')
 <script type="text/javascript" src="{{ asset('/js/pengadaan/mutasi/termohon.js') }}"></script>
<style type="text/css">
	td > .link{
		display: none;
	}
	table.daftar-pmb tr:hover td .link{
		display: block;
	}
</style>
@endsection

@section('title')
PERMOHONAN MUTASI BARANG & OBAT
@endsection

@section('content')
<div class="row">
	<div class="col-sm-9">

		<div class="grid simple">
			<div class="grid-title no-border">
				<label for="finish_smb" align="right">
					Selesai <span class="total_finish" style="background:#ff0000;"></span>
					Menunggu <span class="total_proses" style="background:#23A500;"></span>
				</label>
			</div>
			
			<div class="grid-body no-border">
					<div class="table-responsive">
							<table class="table table-striped daftar-pmb">
								<thead>
									<tr>
										<th class="text-middle" width="5%">No.</th>
										<th class="text-middle text-center" width="20%">No PMB/PMO</th>
										<th class="text-middle text-center" width="21%">Pemohon</th>
										<th class="text-middle text-center" width="15%">Gudang Tujuan</th>
										<th class="text-middle text-center" width="17%">Tanggal</th>
										<th class="text-middle text-center" width="17%">Tanggal Approval</th>
										<th class="text-middle text-center" width="10%" class="text-center">Status</th>
									</tr>
								</thead>
								<tbody class="allpermohonan">
								<?php $no = 1; ?>
									@forelse($data as $item)
									<tr class="mutasi_{{ $item->id_mutasi_spb }}">
										<td>{{ $no }}</td>
										<td>
											<div>
												{{ $item->no_mutasi_spb }}
												{!! empty($item->id_acc) ? '<i class="fa fa-times text-muted pull-right" title="Belum terverifikasi"></i>' : '<i title="Terverifikasi" class="fa fa-check-circle text-success pull-right"></i>' !!}
											</div>
											<div class="link text-muted">
												<small>
													[
													<a href="#" onclick="detailspbmutasi({{ $item->id_mutasi_spb }});" data-toggle="modal" data-target="#detail">Lihat</a>
													@if(in_array($item->status, [2,3]))
													| <a href="#" data-toggle="modal" data-target="#detailSKB" onclick="listviewskb({{ $item->id_mutasi_spb }});">Lihat SKB</a>
													@endif
													@if($item->status < 2 && Auth::user()->permission > 1 && empty($item->id_acc))
													| <a href="{{ url('/Mutasi/editmutasi/'. $item->id_mutasi_spb)}}">Edit</a> | 
													<a href="javascript:;" onclick="delmutasispb({{ $item->id_mutasi_spb }});" class="text-danger">Hapus</a>
													@endif
													]
												</small>
											</div>
										</td>
										<td>
											<div>{{ $item->nm_depan }} {{ $item->nm_belakang }}</div>
											<div class="text-muted"><small>Dept : {{ $item->nm_departemen }}</small></div>
										</td>
										<td>{{$item->gudang_termohon}}</td>
										<td>
											<div>{{ Format::indoDate2($item->created_at) }}</div>
											<div class="text-muted"><small>{{ Format::hari($item->created_at) }}, {{ Format::jam($item->created_at) }}</small></div>
										</td>
										<td>
											@if(empty($item->tgl_approval) || $item->tgl_approval == '0000-00-00 00:00:00')
											<center>-</center>
											@else
											<div>{{ Format::indoDate2($item->tgl_approval) }}</div>
											<div class="text-muted"><small>{{ Format::hari($item->tgl_approval) }}, {{ Format::jam($item->tgl_approval) }}</small></div>
											@endif
										</td>
										<td class="text-center">
											{{ $status[$item->status] }}
										</td>
									</tr>
									<?php $no++; ?>
									@empty
									<tr>
										<td colspan="7"><div class="">Tidak ditemukan</div></td>
									</tr>
									@endforelse
								</tbody>
							</table>

							<div>
								<i title="Terverifikasi" class="fa fa-check-circle text-success"></i> Terverifikasi |
								<i class="fa fa-times text-muted" title="Belum terverifikasi"></i> Belum Terverifikasi |
								*Selesai = Hanya diapprove  oleh Gudang bersangkutan
							</div>
							<div class="text-right paginspb">
							
							</div>
					</div>
			</div>
		</div>

	</div>
	<div class="col-sm-3">
		<div class="grid simple">
			<div class="grid-title no-border"></div>
			<div class="grid-body no-border text-center">
				 <a class="btn btn-block btn-primary" href="{{ url('/Mutasi/create') }}"><i class="fa fa-plus"></i> Buat PMBU</a>
			</div>
		</div>

		<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
						<div class="form-group">
							<label>No PMBU</label>
							<input type="text" name="kode" class="form-control">
						</div>


						<div class="form-group">
							<div class="checkbox check-danger">
								<input type="checkbox" name="no_approve" value="1" id="no_approve">
								<label for="no_approve">Belum Verifikasi <span class="total_no_approve" style="background:#ff0000;"></span></label>
							</div>
						</div>
						<div class="form-group">
							<div class="check-danger">
								
								<label for="finish_smb">Selesai Proses<span class="total_finish" style="background:#ff0000;"></span></label>
							</div>
						</div>

						<div class="form-group">
							<label>Status PMBU</label>
							<select name="status" class="form-control">
								<option value="0">Semua</option>
								<option value="1">Baru</option>
								<option value="2">Proses</option>
								{{-- <option value="3">Selesai</option> --}}
								<option value="5">Selesai</option>
							</select>
						</div>

						<div class="form-group">
							<label>Gudang Tujuan</label>
								<select id="tujuan" name="tujuan" class="form-control" required>
									<option value="">Pilih Gudang</option>
									@foreach($gudang as $gudang)
										<option  value="{{ $gudang->id_gudang }}">{{ $gudang->nm_gudang }}</option>
									@endforeach
										
								</select>
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
							<butto class="btn btn-block btn-primary caripermohonan"><i class="fa fa-search"></i> Cari</button>
						</div>

				</div>
			</div>
		</div>
	</div>
	@endsection

	@section('footer')
	<!-- Modal -->
	<div class="modal fade" id="detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">NO <span class="viewkode"></span></h4>
				</div>
				<div class="modal-body">
					<div class="detail-pmb">Memuat...</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Keluar</button>
					<span class="btn-acc"></span>
				</div>
			</div>
		</div>
	</div>


	<!-- Modal -->
	<div class="modal fade" id="detailSKB" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Surat Keluar Barang</h4>
				</div>
				<div class="modal-body">
					<div class="grid simple">
						<div class="grid-title no-border"></div>
						<div class="grid-body no-border" id="listdetailSKB">
							Memuat...
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
				</div>
			</div>
		</div>
	</div>
	@endsection