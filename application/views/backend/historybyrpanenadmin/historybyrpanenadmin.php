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
           <label class="pull-right">History Premi Panen</label>
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
                          <th>Kerani Askep</th>
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
                  <!-- <small><b>Catatan :</small><br>
                  <small>(1) <b>A</b> = Hasil Panen Janjang / Pemanen </small><br>
                  <small>(2) <b>B</b> = Hasil Nilai Komidel (Kg)</small><br> 
                  <small>(3) <b>C</b> = Hasil Perkalian A*B (Kg)</small><br>
                  <small>(5) <b>Harga (Range)</b> </small><br>
                  <small>Jika C >=0 && C <700 =Rp. 0  </small><br>
                  <small>Jika C >=700 && C <910 =Rp. 45</small><br>
                  <small>Jika C >=910 && C <1225 =Rp. 50</small><br> 
                  <small>Jika C >=1225 =Rp. 55 </small> -->
       

          </div>
        </div>
      </div>
    </div>
  </section>

<script src="<?php echo base_url();?>assets/backend/js/jquery-3.3.1.js"></script>
<script type="text/javascript">
    
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
                url: '<?php echo base_url('Historybyrpanenadmin/datahistorypanen');?>',
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
                { data:'keraniaskep' },
                { data:'keranikcs' },
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


</script>