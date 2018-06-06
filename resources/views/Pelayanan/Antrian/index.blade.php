@extends('Master.Template')
@section('meta')
<!-- <script type="text/javascript" src="{{ asset('js/resep/resepacc.js') }}"></script> -->
<script type="text/javascript" src="{{ asset('js/antrian/antrian.js') }}"></script>

@endsection

@section('title')
  Daftar Antrian Pasien
@endsection

@section('content')
  <div class="row">
    <div class="col-sm-9">
      <div class="grid simple">
        <div class="grid-title no-border"></div>
        <div class="grid-body no-border">
      
   
        </div>
      </div>
      <div class="grid simple">
        <div class="grid-title no-border"></div>
        <div class="grid-body no-border">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
               <!--  <th class="text-center" width="5%">No</th> -->
                  <th class="text-center" width="15%" >Nomor Antrian</th>
                  <th class="text-center"  width="15%">No Registrasi</th>
                  <th class="text-center" width="20%">Nama Pasien</th>
                  <th class="text-center" width="20%"> Alamat</th>
                  <th class="text-center" width="3%"></th>
                </tr>
              </thead>
              <tbody class="modal-antrian-list">
                <tr>
                  <td colspan="5">Tidak ditemukan</td>
                </tr>
            </tbody>
          </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-3">
   <!--  <div class="grid simple">
      <div class="grid-title no-border"></div>
      <div class="grid-body no-border text-center">
        <div class="btn-group" style="width:100%;">
        <a href="{{ url('/resep/obatpaten') }}" class="btn btn-primary btn-block dropdown-toggle" > Buat Resep  <span class="caret"></span></a>
        </div>
      </div>
    </div> -->

    <div class="grid simple">
      <div class="grid-title no-border"></div>
      <div class="grid-body no-border">
        <div class="form-group">
          <label>Nomor Registrasi</label>
          <input type="text" name="id_pasien_hc" class="form-control">
        </div>
        <div class="form-group">
          <label>Kode Pasien</label>
          <input type="text" name="id_pasien">
        </div>
        <div class="form-group">
          <label>Nama</label>
          <input type="text" name="nama_pasien" class="form-control">
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
          <butto class="btn btn-block btn-primary cariresep"><i class="fa fa-search"></i> Cari</button>
          </div>

        </div>
      </div>
    </div>
  </div>

  </div>
@endsection
