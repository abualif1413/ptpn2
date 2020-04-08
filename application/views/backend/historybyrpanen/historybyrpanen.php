<style>

.card {
    background-color: #ffffff;
    border: 1px solid rgba(0, 34, 51, 0.1);
    box-shadow: 2px 4px 10px 0 rgba(0, 34, 51, 0.05), 2px 4px 10px 0 rgba(0, 34, 51, 0.05);
    border-radius: 0.15rem;
}

/* Tabs Card */

.tab-card {
  border:1px solid #eee;
}

.tab-card-header {
  background:none;
}
/* Default mode */
.tab-card-header > .nav-tabs {
  border: none;
  margin: 0px;
}
.tab-card-header > .nav-tabs > li {
  margin-right: 2px;
}
.tab-card-header > .nav-tabs > li > a {
  border: 0;
  border-bottom:2px solid transparent;
  margin-right: 0;
  color: #737373;
  padding: 2px 15px;
}

.tab-card-header > .nav-tabs > li > a.show {
    border-bottom:2px solid #007bff;
    color: #007bff;
}
.tab-card-header > .nav-tabs > li > a:hover {
    color: #007bff;
}

.tab-card-header > .tab-content {
  padding-bottom: 0;
}
</style>


<section class="py-5 slowmotion">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h6 class="text-uppercase mb-0">
           <label class="pull-right">History Premi Panen 
           <button class="btn btn-primary" id="btnAdd">Print Premi</button>
           <button class="btn btn-primary" id="btnAdd2">Print Detail</button>
           </label>
            </h6>
          </div>
          <div class="card-body">
          <?php
          if($this->session->flashdata('success')){?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><?php echo $this->session->flashdata('success'); ?></strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <?php
          }
          ?>

          <?php
          if($this->session->flashdata('error')){?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><?php echo $this->session->flashdata('error'); ?></strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <?php
          }
          ?>

        <?php
          if($this->session->flashdata('warning')){?>
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong><?php echo $this->session->flashdata('warning'); ?></strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <?php
          }
          ?>
            <div style="overflow-x:auto;">
              <table id="DataTable_historypanen" class="table table-striped table-bordered" style="width:100%">
                  <thead>
                      <tr>
                          <th>No</th>
                          <th>Kerani Kcs</th>
                          <th>Pemanen</th>
                          <th>Kebun</th>
                          <th>Afdeling</th>
                          <th>Blok</th>
                          <th>Janjang</th>
                          <th width="100px;">N.Komidel (Kg)</th>
                          <th width="100px;">Prestasi (Kg)</th>
                          <th width="100px;">Brondolan Taksir (Kg)</th>
                          <th width="100px;">Brondolan Timbang (Kg)</th>
                          <th width="100px;">Pengali Proporsi (%)</th>
                          <th width="100px;">Alat</th>
                          <th width="300px;">Premi__TBS</th>
                          <th width="200px;">Premi_Alat</th>
                          <th width="200px;">Premi_Brondolan</th>
                          <th width="200px;">Premi_Total</th>
                          <th width="300px;">Tgl_Panen</th>
                          
                      </tr>
                  </thead>
              </table>  
              </div>
          </div>
        </div>
      </div>
    </div>
  </section>


<!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form method="POST" target="blank" action="<?php echo base_url('Historybyrpanen/laporan');?>">
          <div class="form-group">
            <label for="Kebun">Kebun:</label>
            <select class="form-control" name="id_kebun" id="id_kebun" required>
                <option value="">Pilih</option>
                <?php foreach ($kebun as $key_kebun) {?>
                  <option value="<?php print $key_kebun->id ?>"><?php print $key_kebun->nama_kebun ?></option>
                <?php
                } ?>
            </select>
          </div>

          <div class="form-group">
            <label for="Afdeling">Nama Afdeling:</label>
            <select class="form-control" name="id_afdeling" id="id_afdeling" required>
                <option value="">Pilih</option>
                
            </select>
          </div>

          <div class="form-group">
          <label for="Afdeling">Bulan:</label>
              <select class="form-control" name="bulan" required>
                  <option value="">Pilih</option>
                  <option value="01">01 - Januari</option>
                  <option value="02">02 - Februari</option>
                  <option value="03">03 - Maret</option>
                  <option value="04">04 - April</option>
                  <option value="05">05 - Mei</option>
                  <option value="06">06 - Juni</option>
                  <option value="07">07 - Juli</option>
                  <option value="08">08 - Agustus</option>
                  <option value="09">09 - September</option>
                  <option value="10">10 - Oktober</option>
                  <option value="11">11 - November</option>
                  <option value="12">12 - Desember</option>
              </select>
          </div>

          <div class="form-group">
          <label for="Afdeling">Tahun:</label>
              <select class="form-control" name="tahun" required>
                  <option value="">Pilih</option>
                  <?php
                  for ($i= 2000 ; $i <= 2050 ; $i++) { ?>
                      <option value="<?php print $i;?>"><?php print $i;?></option>
                  <?php
                  }
                  ?>
              </select>
          </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger"  ><i class="fa fa-save"></i>&nbsp;Print</button>
      </div>
      </form>

    </div>
  </div>
</div>

<!-- The Modal -->
<div class="modal" id="myModal2">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form method="POST" target="blank" action="<?php echo base_url('Historybyrpanen/laporandetail');?>">
   
          <div class="form-group">
            <label for="Kebun">Kebun:</label>
            <select class="form-control" name="id_kebun" id="id_kebun2" required>
                <option value="">Pilih</option>
                <?php foreach ($kebun as $key_kebun) {?>
                  <option value="<?php print $key_kebun->id ?>"><?php print $key_kebun->nama_kebun ?></option>
                <?php
                } ?>
            </select>
          </div>

          <div class="form-group">
            <label for="Afdeling">Nama Afdeling:</label>
            <select class="form-control" name="id_afdeling" id="id_afdeling2" required>
                <option value="">Pilih</option>
            </select>
          </div>

          <div class="form-group">
            <label for="Nama Pemanen">Nama Pemanen:</label>
            <select class="form-control" name="id_pemanen" id="id_pemanen" required>
                <option value="">Pilih</option>
            </select>
          </div>

          <div class="form-group">
          <label for="Afdeling">Bulan:</label>
              <select class="form-control" name="bulan" required>
                  <option value="">Pilih</option>
                  <option value="01">01 - Januari</option>
                  <option value="02">02 - Februari</option>
                  <option value="03">03 - Maret</option>
                  <option value="04">04 - April</option>
                  <option value="05">05 - Mei</option>
                  <option value="06">06 - Juni</option>
                  <option value="07">07 - Juli</option>
                  <option value="08">08 - Agustus</option>
                  <option value="09">09 - September</option>
                  <option value="10">10 - Oktober</option>
                  <option value="11">11 - November</option>
                  <option value="12">12 - Desember</option>
              </select>
          </div>

          <div class="form-group">
          <label for="Afdeling">Tahun:</label>
              <select class="form-control" name="tahun" required>
                  <option value="">Pilih</option>
                  <?php
                  for ($i= 2000 ; $i <= 2050 ; $i++) { ?>
                      <option value="<?php print $i;?>"><?php print $i;?></option>
                  <?php
                  }
                  ?>
              </select>
          </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger"  ><i class="fa fa-save"></i>&nbsp;Print</button>
      </div>
      </form>

    </div>
  </div>
</div>

<script src="<?php echo base_url();?>assets/backend/js/jquery-3.3.1.js"></script>
<script type="text/javascript">
    
    $('#btnAdd').click(function () {
        $('#myModal').modal('show');
        $('#myModal').find('.modal-title').text('Print');
    });

    $('#btnAdd2').click(function () {
        $('#myModal2').modal('show');
        $('#myModal2').find('.modal-title').text('Print');
    });

    $(document).ready(function() {
        $("#DataTable_historypanen").DataTable({
            serverSide:true,
            responsive:true,
            processing:false,
            oLanguage: {
                sZeroRecords: "<center>Data tidak ditemukan</center>",
                sLengthMenu: "Tampilkan _MENU_",
                sSearch: "Cari data:",
                sInfo: "Menampilkan: _START_ - _END_ dari total: _TOTAL_ data",                                   
                oPaginate: {
                    sFirst: "Awal", "sPrevious": "Sebelumnya",
                    sNext: "Selanjutnya", "sLast": "Akhir"
                },
            },
            ajax: {
                url: '<?php echo base_url('Historybyrpanen/datahistorypanen');?>',
                type: 'POST',
                data: {'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>'}
            },
            order:[[1,'DESC']],
            columns:[
                {
                    data:'no',
                    searchable:false,
                    orderable:false,
                },
                { data:'kenarikcs' },
                { data:'nama_pemanen' },
                { data:'nama_kebun' },
                { data:'nama_afdeling' },
                { data:'kode_blok' },
                { data:'jmlh_panen' },
                { data:'nilai_komidel' },
                { data:'prestasi' },
                { data:'jmlh_brondolan' },
                { data:'bron_tim' },
                { data:'koef' },
                { data:'nama_alat' },
                { data:'tbs1' },
                { data:'premi_alat1' },
                { data:'premi_brondolan1' },
                { data:'total1' },
                { data:'tanggal' }
            ],
        });
    });


  $(document).ready(function()
  {
    $("#id_kebun").change(function()
    {
        var id_kebun=$(this).val();
        $("#id_afdeling").find('option').remove();
        $.ajax
        ({
            type: "POST",
            url: "<?php echo site_url('Historybyrpanen/afdeling')?>",
            data: {id_kebun:id_kebun},
            cache: false,
            success: function(html)
            {
              $("#id_afdeling").html(html);
            } 
        });
    }); 
  });

  $(document).ready(function()
  {
    $("#id_kebun2").change(function()
    {
        var id_kebun=$(this).val();
        $("#id_afdeling2").find('option').remove();
        $.ajax
        ({
            type: "POST",
            url: "<?php echo site_url('Historybyrpanen/afdeling')?>",
            data: {id_kebun:id_kebun},
            cache: false,
            success: function(html)
            {
              $("#id_afdeling2").html(html);
            } 
        });
    }); 
  });

  $(document).ready(function()
  {
    $("#id_afdeling2").change(function()
    {
        var id_afdeling=$(this).val();
        var id_kebun = $('#id_kebun2').val();
        $("#id_pemanen").find('option').remove();
        $.ajax
        ({
            type: "POST",
            url: "<?php echo site_url('Historybyrpanen/pemanen')?>",
            data: {id_kebun:id_kebun,id_afdeling:id_afdeling},
            cache: false,
            success: function(html)
            {
              $("#id_pemanen").html(html);
            } 
        });
    }); 
  });
</script>