@extends('Master.Template')

@section('csstop')
<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
@endsection
@section('meta')
<script src="{{ asset('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('js/resep/resepbaru.js') }}"></script>
<script type="text/javascript">
	$(function(){
		$('#kategori3').select2({
			placeholder: "Pilih nama Pasien...."
		});
		$('#dokter').select2({
			placeholder: "Pilih nama dokter...."
		});
		$('#pasien').select2({
			placeholder: "Pilih nama Pasien...."
		});
	});
</script>
@endsection

@section('title')
Pembuatan Resep
@endsection

@section('content')
<form method="post" action="{{ url('/resep/resep') }}">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<div class="row">
		<!-- left -->
		<div class="col-sm-12">
			<div class="grid simple">
			
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					<!-- Input Header -->
					<div class="row">
							<div class="col-sm-12">
								<div class="grid simple">
								
									<div class="grid-title no-border"></div>
									<div class="grid-body no-border">
										<!-- Input Header -->
										<div class="row">
											<div class="col-md-12">
									          <div class="grid simple">
									            <div class="grid-body no-border">
												<form class="form-no-horizontal-spacing" id="form-condensed">	
									              <div class="row column-seperation">
									              		<div class="row form-row">
									              			<div class="col-md-6">
										                      <div class="col-md-12">
										                      	<label class="form-label">Kategori</label>
										                      		  <select name="kategori" id="kategori3"  class="select2 form-control"  >
																		<option value="">-Pilih-</option>
																		<option value="1">Rawat Jalan</option>
																		<option value="2">Rawat Inap</option>
																		<option value="3">No Resep</option>
																	</select>
										                      </div>
										                    </div>
									                    </div>
									                    <br>
									                <div id="resep">
										                <div class="col-md-6">
															
										                    <div class="row form-row">
										                      <div class="col-md-12">
										                      	<label class="form-label">Pilih Dokter</label>
										                        <select name="id_karyawan" id="dokter"  class="select2 form-control"  >
											                        <option value="">Pilih Dokter</option>
											                        @foreach($dokter as $dokter)
										                            	<option value="{{ $dokter->id_karyawan }}" >{{ $dokter->nm_depan }}{{ $dokter->nm_belakang}}</option>
										                        	@endforeach
										                        </select>
										                      </div>
										                    </div>
										                
										                    <br>
										                    <div class="row form-row">
										                      <div class="col-md-12">
										                      	<label class="form-label">No Registrasi Pasien</label>
										                        <input type="hidden" class="form-control" readonly="readonly" name="id_pasien" id="id_pasien" value="" required="">
										                        	<div class="col-md-9">
																		<input type="text" class="form-control" readonly="readonly" name="ID_PASIEN" id="ID_PASIEN" value="" required>
										                        	</div>
										                        	<div class="col-md-3">
										                        		<button type="button" class="btn btn-primary cari-pasien" data-toggle="modal" data-target="#pasien" onclick="loadpa(1);">Pasien</button>
										                        	</div>
										                      </div>

										                    </div>
										                    <div class="row form-row">
										                      <div class="col-md-12">
										                      	<label class="form-label">Nama Pasien</label>
										                     	<input type="hidden" value="{{ date('m/d/Y') }}" name="tanggal" id="tanggal" class="form-control" readonly="readonly">
																<input type="hidden" value="{{ date('m/d/Y') }}" name="tanggal_p" id="tanggal_p" class="form-control" readonly="readonly">
																<input class="form-control" value="" name="NAMA_PASIEN"  rows="4"  required>
										                      </div>
										                    </div>
										                </div>
										                <div class="col-md-6">
														
										                  <!-- <h4>Postal Information</h4> -->
										                  
										                    <div class="row form-row">
										                      <div class="col-md-12">
										                      	<label class="form-label">Alamat Pasien</label>
										                        <input type="text" value=""  class="form-control" name="alamat_pasien">
										                      </div>
										                    </div>
										                    <div class="row form-row">
										                      <div class="col-md-12">
										                      	<label class="form-label">Tanggal Lahir Pasien</label>
										                        <input type="text" value=""  class="form-control" name="tgllahir_pasien">
										                      </div>
										                    </div>
										                </div>
									                </div>
									                <!-- no resep -->
									         <!--          <div id="no_resep" style="display:none">
										                <div class="col-md-6">
										                    <br>
										                    <div class="row form-row">
										                      <div class="col-md-12">
										                      	<label class="form-label">No Registrasi Pasien</label>
										                        <input type="hidden" class="form-control" readonly="readonly" name="id_pasien" id="id_pasien" value="" required="">
										                        	<div class="col-md-9">
																		<input type="text" class="form-control" readonly="readonly" name="ID_PASIEN" id="ID_PASIEN" value="" required>
										                        	</div>
										                        	<div class="col-md-3">
										                        		<button type="button" class="btn btn-primary cari-pasien" data-toggle="modal" data-target="#pasien">Pasien</button>
										                        	</div>
										                      </div>

										                    </div>
										                    <div class="row form-row">
										                      <div class="col-md-12">
										                      	<label class="form-label">Nama Pasien</label>
										                     	<input type="hidden" value="{{ date('m/d/Y') }}" name="tanggal" id="tanggal" class="form-control" readonly="readonly">
																<input type="hidden" value="{{ date('m/d/Y') }}" name="tanggal_p" id="tanggal_p" class="form-control" readonly="readonly">
																<input class="form-control" value="" name="NAMA_PASIEN"  >
																<!-- > 
										             <!--          </div>
										                    </div>
										                </div>
										                <div class="col-md-6">
														
										                  <!-- <h4>Postal Information</h4> -->
										                  
										                  <!--   <div class="row form-row">
										                      <div class="col-md-12">
										                      	<label class="form-label">Alamat Pasien</label>
										                        <input type="text" value=""  class="form-control" name="alamat_pasien">
										                      </div>
										                    </div>
										                    <div class="row form-row">
										                      <div class="col-md-12">
										                      	<label class="form-label">Tanggal Lahir Pasien</label>
										                        <input type="text" value=""  class="form-control" name="tgllahir_pasien">
										                      </div>
										                    </div>
										                </div>
									                </div> --> 
									              </div>
												
									            </div>
									          </div>
									        </div>
										</div>
									</div>
								</div>
							</div>
						
					</div>
					<!-- End Input Header -->
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<b>Resep Obat Paten</b><br>
									<th width="20%">Nama Obat</th>
									<th width="15%" >Jumlah </th>
									<th width="20%" >Cara Pakai</th>
									<th width="40%">Keterangan</th>
									<th width="20%">SubTotal</th>
								</tr>
							</thead>
							<tbody class="content-item"></tbody>
							
						</table>
					</div>
					<!-- footer  -->
					<div class="row" style="padding:10px 0;">
						<div class="col-sm-12">
							<div class="form-group">
								<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#produks" onclick="loaditems(1);"><i class="fa fa-search"></i> Obat </button>
								<a href="{{ url('/resep') }}"><button type="button" class="btn btn-primary">Kembali</button></a>
								<button type="button" class="btn btn-danger btn-hapus" style="display:none;"><i class="fa fa-trash"></i> Hapus</button>
								<input type="hidden" name="id_delete" value="0">
								<button type="button" class="btn btn-danger btn-delete" style="display:none;"><i class="fa fa-trash"></i> Hapus Campur</button>
								<input type="hidden" name="id_hapus" value="0">
								<button type="button" class="btn btn-danger btn-deletecampur" style="display:none;"><i class="fa fa-trash"></i> Hapus Paket Campur</button>
								<input type="hidden" name="id_hapuscampur" value="0">
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>

		<div class="grid simple">
		<div class="grid-title no-border"></div>
		<div class="grid-body no-border">
			<div class="obat-campur"></div>

			<div class="grid-footer">
				<div class="row">
					<div class="col-sm-2">
						<a href="{{ url('/resep') }}" class="btn btn-default btn-block">Batal</a>
					</div>
					<div class="col-sm-10">

					<table>
						<tr>
							<td width="50%" class="text-right"><strong>GrandTotal  Obat Paten:</strong></td>
							<input type="hidden" name="grand_total" value="0">
							<td width="70%" class="resep-subtotal text-right">0,00</td>
						</tr>
						
					</table>
					<hr class="garis">
					<table>
						<tr>
							<td width="50%" class="text-right"><strong>GrandTotal  Obat Campur:</strong></td>
							<input type="hidden" name="grand_totalcampur" value="0"> 
							<td width="70%" class="resep-subtotalcampur text-right">0,00</td>
						</tr>	
					</table>							
					</div>
					<div class="col-sm-offset-7 col-sm-3">
						<button class="btn btn-danger btn-cons" type="submit">Simpan</button>
					</div>
					
				</div>
			</div>
		</div>
	</div>

	</div>
</form>
@endsection

@section('footer')
<!-- Modal -->
<div class="modal fade" id="produks" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Semua Jenis Obat</h4>
			</div>
			<div class="modal-body">
				<ul class="nav nav-tabs" id="tab-4">
					<li class="active" data-toggle="link-tab"><a href="#item">Obat Paten </a></li>
					<li data-toggle="link-tab"><a href="#po" onclick="loadcam(1);">Obat Campur</a></li>
				</ul>

				<div class="tab-content">
				<!-- PAKET Modal -->
					<div class="tab-pane active" id="item">
						<div class="row">
							<div class="col-sm-4">
								<input type="text" name="modal-kode-item" class="form-control" placeholder="Kode Obat">
							</div>
							<div class="col-sm-5">
								<input type="text" name="modal-barang-item" class="form-control" placeholder="Nama  Obat">
							</div>
							<div class="col-sm-3">
								<div class="btn-group">
									<button class="btn btn-white btn-search-item"><i class="fa fa-search"></i></button>
									<button title="Refresh" class="btn btn-white btn-search-item"><i class="fa fa-refresh"></i></button>
								</div>
							</div>
						</div>
						<br />
						<div>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Kode</th>
										<th>Barang</th>
										<th>Stok</th>
										<th></th>
									</tr>
								</thead>
								<tbody class="modal-items-list">
									<tr>
										<td colspan="3">Memuat...</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="modal-items-pagin text-center"></div>
					</div>

					<div class="tab-pane" id="po">
						<div class="row">
							<div class="col-sm-4">
								<input type="text" name="modal-kode-itemc" class="form-control" placeholder="Kode Obat">
							</div>
							<div class="col-sm-5">
								<input type="text" name="modal-barang-itemc" class="form-control" placeholder="Nama  Obat">
							</div>
							<div class="col-sm-3">
								<div class="btn-group">
									<button class="btn btn-white btn-search-itemc"><i class="fa fa-search"></i></button>
									<button title="Refresh" class="btn btn-white btn-search-itemc"><i class="fa fa-refresh"></i></button>
								</div>
							</div>
						</div>
						<br />
						<div>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Kode</th>
										<th>Nama</th>
										<th>Stok</th>
										<th></th>
									</tr>
								</thead>
									<tbody class="modal-cam-list">
									<tr>
										<td colspan="3">Memuat...</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="modal-cam-pagin text-center"></div>
						<table class="table table-striped">
						<thead>
								<tr>
									<th width="15%">Kode</th>
									<th width="35%" class="text-left">Nama Obat</th>
									<th width="20%" class="text-right"></th>
								</tr>
							</thead>
						<tbody class="content-itemc"></tbody>
						<!-- <tbody class="content-item"></tbody> -->
					<tfoot class="keterangan-campur"hidden>
						<tr>
						<td >
							<select style="width:100%;" name="id_resep_aturan_campur[]" id="id_resep_aturan" required>
			             	 		<option value="">Pilih Cara Pakai</option>
			                       @foreach($pakai as $item)
			                          <option value="{{ $item->id_resep_aturan }}" >{{ $item->resep_aturan }}</option>
			                 		 @endforeach
			                  </select> 
 							</td>
 							<td width="50%">
							<textarea  name="ket_campur[]" id="keterangan" class="form-control" rows="5" cols="2"></textarea>
 							</td>
 						</tr>
						</tfoot>

						</table>
						<button type="button" class="btn btn-danger btn-delete" style="display:none;"><i class="fa fa-trash"></i> Hapus Campur</button>
						<input type="submit" name="button"  class="btn btn-primary"  onclick="kirim();" value="Kirim">
						<input type="hidden" name="home-tab" value="#po">
					</div>
					<!-- BHP
					<div class="tab-pane " id="bhp">
						<div class="row">
							<div class="col-sm-4">
								<input type="text" name="modal-kode-tindakan" class="form-control" placeholder="Kode Tindakan">
							</div>
							<div class="col-sm-5">
								<input type="text" name="modal-nama-tindakan" class="form-control" placeholder="Nama Tindakan">
							</div>
							<div class="col-sm-3">
								<div class="btn-group">
									<button class="btn btn-white btn-search-tindakan"><i class="fa fa-search"></i></button>
									<button title="Refresh" class="btn btn-white btn-search-tindakan"><i class="fa fa-refresh"></i></button>
								</div>
							</div>
						</div>
						<br />
						<div>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Kode</th>
										<th>Nama</th>
										<th></th>
									</tr>
								</thead>
								<tbody class="modal-itemsaturan-list">
									<tr>
										<td colspan="3">Memuat...</td>
									</tr>
								</tbody>


							</table>
						</div>
						<div class="modal-itemsaturan-pagin text-center"></div>
					</div> -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
				
				<input type="hidden" name="home-tab" value="#p">
			</div>
		</div>
	</div>
</div>
</div>


<!-- Modal Pasien -->
<div class="modal fade" id="pasien" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Data Pasien</h4>
			</div>
			<div class="modal-body">
					<li class="active" data-toggle="link-tab"><a href="#items">PASIEN HEALTH CORNER</a></li>
				<div class="tab-content">
					<div class="tab-pane active"  id="items">
						<div class="row">
							<div class="col-sm-4">
								<input type="text" name="modal-id_pasien" class="form-control" placeholder="Nomor Registrasi / Reg Number">
							</div>
							<div class="col-sm-5">
								<input type="text" name="modal-nama_pasien" class="form-control" placeholder="Nama  Pasien">
							</div>
							<div class="col-sm-3">
								<div class="btn-group">
									<button class="btn btn-white btn-search-pasien"><i class="fa fa-search"></i></button>
									<button title="Refresh" class="btn btn-white btn-search-item"><i class="fa fa-refresh"></i></button>
								</div>
							</div>
						</div>
						<br />
						<div>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Nomor Registrasi</th>
										<th>Nama</th>
										<th></th>
									</tr>
								</thead>
								<tbody class="modal-po-list">
									<tr>
										<td colspan="3">Memuat...</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="modal-po-pagin text-center"></div>
					</div>


				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
				<input type="hidden" name="home-tab" value="#items">
			</div>
		</div>
	</div>
</div>
@endsection
