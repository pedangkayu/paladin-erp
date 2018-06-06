@extends('Master.Template')

@section('meta')
	<script type="text/javascript">
	  $(function(){

      $('.saldo_awal').keyup(function(){
        count();
      });
      $('.saldo_kembali').keyup(function(){
        count();
      });

      count = function(){
        var wal = $('[name="saldo_awal"]').val();
        var back = $('[name="saldo_kembali"]').val();
        var sisa = parseInt(wal) - parseInt(back);
        $('.sisa').val(sisa);
      }

    });
	</script>
@endsection

@section('title')
	Registrasi Kasir
@endsection

@section('content')
<div class="row">

   <div class="col-md-12">
       <div class="grid simple ">
           <div class="grid-title no-border">
               <h4>SHIFT KASIR TANGGAL <b>{{ strtoupper(Format::indoDate()) }}</b></h4>
					<p>
							<ol>
								<li>Setelah anda login dan anda akan bertugas menjadi KASIR, daftarkan SHIFT berapa yg sedang anda lakukan, lalu masukkan jumlah SALDO AWAL yg anda bawa ke Kassa</li>
								<li>Setelah Selesai waktu Shift, masukkan jumlah nominal Saldo Awal yang anda kembalikan ke bagian Keuangan</li>
								<li>Masukkan Uang Cash hasil Transaksi pembayaran yang anda Setor ke Bagian Keuangan</li>
								<li>Tekan Tombol SELESAI bila anda sudah tidak AKTIF menjadi Kasir di Kassa</li>
								<li>Buat Laporan Shift per Kasir, Tentukan Nama Kasir dan pilih Filter Waktu Shift Kasir. Proses SELESAI</li>
							</ol>
						</p>
           </div>
       </div>
   </div>

   <div class="col-md-4">
      <form class="" action="" method="post">
        <div class="grid simple ">
           <div class="grid-title no-border">
               <h4>Shift  <span class="semi-bold"> Hari Ini</span></h4></h4>
               <div class="tools">
			       <a href="javascript:;" class="collapse"></a>
                   <a href="javascript:;" class="reload"></a>
               </div>
           </div>
           <div class="grid-body no-border">

               <div class="form-group">
                   <label>Login Kasir Sebagai</label>
                   <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                   <input type="hidden" name="id_karyawan" value="{{ $user->id_karyawan }}">
                   <input type="hidden" name="id_shift_kasir" value="{{ $user->id_shift_kasir }}">
               </div>

               <div class="form-group">
                   <label>Kassa</label>
                   <select class="form-control id_karyawan" name="shift" required>
                       <option value="">- Pilih -</option>
                       <option {{ $user->shift == 1 ? 'selected' : '' }} value="1">Kassa 1</option>
          			   <option {{ $user->shift == 2 ? 'selected' : '' }} value="2">Kassa 2</option>
          			   <option {{ $user->shift == 3 ? 'selected' : '' }} value="3">Kassa 3</option>
					   <option {{ $user->shift == 4 ? 'selected' : '' }} value="4">Kassa 4</option>
                   </select>
               </div>

               <div class="form-group">
                   <label>Saldo Awal Dibawa (A)</label>
                   <input type="text" class="form-control saldo_awal" name="saldo_awal" id="saldo" value="{{ $user->saldo_awal }}">
               </div>

               <div class="form-group">
                   <label>Kembalikan Saldo Awal (B)</label>
                   <input type="text" class="form-control saldo_kembali" name="saldo_kembali" id="saldo" value="{{ $user->saldo_kembali }}">
               </div>

               <div class="form-group">
                   <label>Sisa Saldo ( A -  B )</label>
                   <input type="text" class="form-control sisa" value="{{ $user->saldo_awal - $user->saldo_kembali }}" disabled>
               </div>

               <div class="form-group">
                   <label>Pendapatan Kasir ( C )</label>
                   <input type="text" class="form-control" name="pendapatan_kassa" id="saldo" value="{{ $user->pendapatan_kassa }}">
		                <small>jumlah uang cash yang dikembalikan ke Bagian Keuangan</small>
               </div>
               {{ csrf_field() }}
      				<button type="submit" name="status" value="0" class="btn btn-primary btn-cons">{{ $user->id_shift_kasir > 0 ? 'Update' : 'Mulai' }}</button>
              @if($user->id_shift_kasir > 0 && $user->status == 0)
      				<button type="submit" name="status" value="1" class="btn btn-danger btn-cons">Selesai</button>
              @elseif($user->id_shift_kasir > 0)
              <a class="btn btn-danger btn-cons" href="{{ url('/biling/overshift') }}">Baru</a>
            @endif
           </div>
       </div>
      </form>
   </div>

   <div class="col-md-8">
       <div class="grid simple ">
           <div class="grid-title no-border">
               <h4>Data  <span class="semi-bold">Shift Kasir</span></h4>

               <div class="tools">
			       <a href="javascript:;" class="collapse"></a>
                   <a href="javascript:;" class="reload"></a>
               </div>

           </div>
           <div class="grid-body no-border">

				<table class="table table-hover table-striped daftar-prq">
						<thead>
							<tr>
								<th width="35%">Nama Kasir</th>
								<th width="15%">Tangal</th>
								<th width="10%">Kassa</th>
								<th width="20%">A (Rp)</th>
								<th width="20%">C (Rp)</th>
								<th width="10%">Status</th>
								<!-- th width="5%"></th -->
							</tr>
						</thead>

						<tbody class="contentPRQ">
              @forelse($items as $item)
              <tr>
                <td>
					@if($item->id_karyawan == $me->id_karyawan || in_array(1, $level))
                  	<a href="{{ url('/biling/overshift/' . $item->id_shift_kasir) }}">{{ $item->nm_depan }} {{ $item->nm_belakang }}</a>
					@else
						{{ $item->nm_depan }} {{ $item->nm_belakang }}
					@endif
                </td>
                <td>{{ Format::indoDate2($item->created_at) }}</td>
                <td>{{ $shifts[$item->shift] }}</td>
                <td>{{ number_format($item->saldo_awal,0,',','.') }}</td>
                <td>{{ number_format($item->pendapatan_kassa,0,',','.') }}</td>
                <td><span class="label {{ $item->status ? 'label-important' : 'label-success' }}">{{ $status[$item->status] }}</span></td>

				<!-- td>
					@if($item->id_karyawan == $me->id_karyawan || in_array(1, $level))
						<a href="{{ url('/biling/overshift/' . $item->id_shift_kasir) }}"><span class="label label-warning">Update</span></a>
					@else
						<a href="javascript:void();"><span class="label label-warning">Update</span></a>
					@endif
				</td -->

              </tr>
              @empty
                <tr>
                  <td colspan="6">Tidak ditemukan</td>
                </tr>
              @endforelse
						</tbody>
				</table>
           </div>

           <div class="pagin">
             {!! $items->render() !!}
           </div>

       </div>
   </div>

</div>


@endsection
