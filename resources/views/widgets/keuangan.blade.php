@if(in_array(true, $config['check']))
<!-- Script plugin js lain di simpan di videw welcome.blade.php -->
<script type="text/javascript" src="{{ asset('/js/laporankeuangan/dashboard.js') }}"></script>
<style type="text/css">
  .demo-placeholder{
    height: 300px;
  }
  .demo-placeholder-bln{
    min-height: 520px;
  }
</style>

<div class="row spacing-bottom 2col">

  <div class="col-md-3 col-sm-6 spacing-bottom-sm spacing-bottom">
    <div class="tiles blue added-margin">
      <div class="tiles-body">
        <div class="tiles-title">
          TOTAL HUTANG
        </div>
        <div class="heading ">
        <span class="animate-number total_hutang" data-value="0" data-animation-duration="1200">0</span>
        </div>
        <div class="description"><i class="icon-custom-calendar"></i><span class="text-white mini-description ">&nbsp; per tanggal {{ Format::indoDate2() }}</span></div>
      </div>
    </div>
  </div>


  <div class="col-md-3 col-sm-6 spacing-bottom-sm spacing-bottom">
    <div class="tiles red added-margin">
     <div class="tiles-body">
      <div class="tiles-title">
        HUTANG JATUH TEMPO
      </div>
      <div class="heading">
        <span class="animate-number total_hutang_jth_tempo" data-value="0" data-animation-duration="1000">0</span>
      </div>
      <div class="description"><i class="icon-custom-right"></i><span class="text-white mini-description ">&nbsp; per tanggal {{ Format::indoDate2() }}</span></div>
     </div>
    </div>
  </div>


  <div class="col-md-3 col-sm-6 spacing-bottom">
    <div class="tiles green added-margin">
    <div class="tiles-body">
      <div class="tiles-title">
        TOTAL PIUTANG
      </div>
      <div class="heading">
        <span class="animate-number total_piutang" data-value="0" data-animation-duration="1200">0</span>
      </div>
      <div class="description"><i class="icon-custom-right"></i><span class="text-white mini-description ">&nbsp; per tanggal {{ Format::indoDate2() }}</span></div>
    </div>
    </div>

  </div>


  <div class="col-md-3 col-sm-6">
    <div class="tiles red added-margin">
      <div class="tiles-body">
      <div class="tiles-title">
        PIUTANG JATUH TEMPO
      </div>
      <div class="row-fluid">
        <div class="heading">
          <span class="animate-number total_piutang_jth_tempo" data-value="0" data-animation-duration="700">0</span>
        </div>
      </div>
      <div class="description"><i class="icon-custom-right"></i><span class="text-white mini-description ">&nbsp; per tanggal {{ Format::indoDate2() }}</span></div>

     </div>
    </div>
  </div>
</div>


<div class="grid simple">
  <div class="grid-title no-border">
    <h4>Rugi Laba <strong>tahun {{ date('Y') }}</strong></h4>
  </div>
  <div class="grid-body no-border">
    <div class="row">
      <div class="col-sm-3">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center">Bulan</th>
              <th class="text-center">Total</th>
            </tr>
          </thead>
          <tbody class="detail-rlbln"></tbody>
        </table>
      </div>
      <div class="col-sm-9">
        <div id="rlbln" class="demo-placeholder-bln"></div>
      </div>
    </div>
  </div>
</div>


<!-- CHARTS -->
<div class="row">
  <div class="col-sm-6">

    <div class="grid simple">
      <div class="grid-title no-border">
        <h4>Ratio <strong>per tahun</strong></h4>
      </div>
      <div class="grid-body no-border">
        <p>data dalam 5 tahun terakhir</p>
        <div id="placeholder" class="demo-placeholder"></div>
      </div>
    </div>

  </div>

  <div class="col-sm-6">

    <div class="grid simple">
      <div class="grid-title no-border">
        <h4>Rugi Laba <strong>per tahun</strong></h4>
      </div>
      <div class="grid-body no-border">
        <p>data dalam 5 tahun terakhir</p>
        <div id="rugilaba" class="demo-placeholder"></div>
      </div>
    </div>

  </div>

</div>


@endif
