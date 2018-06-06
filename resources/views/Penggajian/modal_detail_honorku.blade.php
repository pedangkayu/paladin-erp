<div class="modal-dialog modal-lg">
   <div class="modal-content">
       <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
           <h4 class="modal-title" id="myModalLabel">Data Detail Honor <span class="viewkode">karyawan</span></h4>
       </div>
       <div class="modal-body">
           <div class="row">
               <div class="col-sm-8">
                   <div class="grid solid red">
                       <div class="grid-body">
                           <p>
                               <div class="data-karyawan">
                                   <strong> Nama karyawan</strong> : {{$data_karyawan->nm_depan}} &nbsp;{{$data_karyawan->nm_belakang}}
                                   <input type="hidden" name="id_karyawan" class="id_karyawan" id="id_karyawan" value="{{$data_karyawan->id_karyawan}}">
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
                   <div class="grid solid gren">
                       <div class="grid-body">

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
                                           <td>1
                                               <input type="hidden" name="id_karyawan_honor[]" class="id_karyawan_honor" id="id_karyawan_honot" value="{{$item->id_karyawan_honor}}">
                                           </td>
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
           </div>
       </div>
       <div class="modal-footer">
           <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Keluar</button>
           <span class="btn-accpinjaman"></span>
       </div>
   </div>
</div>
