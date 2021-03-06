<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/mint-choc/jquery-ui.css" rel="stylesheet" />

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
           <label class="pull-right">Premi Panen</label>
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
            <br>
            <form class="form-inline">
            <div class="form-group">
                <input type="text" class="form-control" name="from" id="datetimepicker" style="width:140px;" required>
            </div>
            &nbsp;&nbsp;&nbsp;
            <div class="form-group">
                <input type="text" class="form-control" name="to" id="datetimepicker2" style="width:140px;" required>
            </div>
            &nbsp;&nbsp;&nbsp;
            <div class="form-group">
                <select class="form-control" name="afdeling" id="dataafdeling" required></select>
            </div>
            &nbsp;&nbsp;&nbsp;
            <a hreff="#" class="btn btn-primary" id="search">Cari</a>&nbsp;&nbsp;&nbsp;
            </form>
            <br>
              <table id="DataTable_listbyrpanen" class="table table-striped table-bordered" style="width:100%">
                  <thead>
                      <tr>
                          <th>No</th>
                          <th>Status</th>
                          <th width="300px;">Tgl_Panen</th>
                          <th>Kcs</th>
                          <th>Pemanen</th>
                          <th>Kebun</th>
                          <th>Afdeling</th>
                          <th>Blok</th>
                          <th>BT</th>
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
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript">
    

    function reloadTable() {
        $("#DataTable_listbyrpanen").DataTable().ajax.reload(null,false);
    }


    $(document).ready(function() {

      datatableview();
      function datatableview(is_date_search, start_date='', end_date='',afdeling='') {
        $("#DataTable_listbyrpanen").DataTable({
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
                url: '<?php echo base_url('ListByrPanen/datalistbayarpanen_keraniaskep');?>',
                type: 'POST',
                data: {
                      '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
                      is_date_search:is_date_search,
                      from: start_date,
                      to: end_date,
                      afdeling:afdeling,
                }
            },
            order:[[1,'DESC']],
            columns:[
                {
                    data:'no',
                    searchable:false,
                    orderable:false,
                },
                
                {
                    data:'button_action',
                    searchable:false,
                    orderable:false,
                },
                { 
                    data:'tanggal',
                    searchable:false,
                    orderable:false, 
                },
                { 
                    data:'mandor',
                    searchable:false,
                    orderable:false,
                },
                {   
                    data:'nama_pemanen',
                    searchable:false,
                    orderable:false,
                },
                { 
                    data:'nama_kebun',
                    searchable:false,
                    orderable:false,
                },
                { 
                    data:'nama_afdeling',
                    searchable:false,
                    orderable:false,
                },
                {   
                    data:'kode_blok',
                    searchable:false,
                    orderable:false, 
                },
                {   
                    data:'bt',
                    searchable:false,
                    orderable:false,
                },
                { 
                    data:'janjang',
                    searchable:false,
                    orderable:false,
                },
                { 
                    data:'nilai_komidel',
                    searchable:false,
                    orderable:false, 
                },
                {   
                    data:'prestasi',
                    searchable:false,
                    orderable:false, 
                },
                { 
                    data:'jmlh_brondolan',
                    searchable:false,
                    orderable:false,
                },
                { 
                    data:'brondolan_timbang',
                    searchable:false,
                    orderable:false,
                },
                { 
                    data:'koef',
                    searchable:false,
                    orderable:false,
                },
                { 
                    data:'nama_alat',
                    searchable:false,
                    orderable:false, 
                },
                { 
                    data:'premi',
                    searchable:false,
                    orderable:false,
                },
                {   
                    data:'premi_alat1',
                    searchable:false,
                    orderable:false, 
                },
                { 
                    data:'premi_brondolan1',
                    searchable:false,
                    orderable:false, 
                },
                { 
                    data:'total',
                    searchable:false,
                    orderable:false, 
                }
                
            ],
        });
      }

      $('#search').click(function(){
            var start_date = $('input[name=from]').val();
            var end_date = $('input[name=to]').val();
            var afdeling = $('select[name=afdeling]').val();
            if(start_date != '' && end_date !='' && afdeling !='')
            {
                $('#DataTable_listbyrpanen').DataTable().destroy();
                datatableview('yes', start_date, end_date,afdeling);
            }
            else
            {
                swal({
                    type: 'warning',
                    title: 'Tanggal dan Afdeling Tidak Boleh Kosong',
                    footer: ''
                });
            }
        }); 


    });


  function Validasi(id) {
    var id_kerani_kcs = $(id).data("id_kerani_kcs");
    var id_kebun = $(id).data("id_kebun");
    var id_afdeling = $(id).data("id_afdeling");
    var id_pemanen = $(id).data("id_pemanen");
    var tph = $(id).data("tph");
    var blok = $(id).data("blok");
    var jmlh_panen = $(id).data("jmlh_panen");
    var nilai_komidel = $(id).data("nilai_komidel");
    var prestasi = $(id).data("prestasi");
    var jmlh_brondolan = $(id).data("jmlh_brondolan");
    var bron_tim = $(id).data("bron_tim");
    var koef = $(id).data("koef");
    var tanggal = $(id).data("tanggal");
    var nama_alat = $(id).data("nama_alat");
    var tbs = $(id).data("tbs");
    var premi_alat = $(id).data("premi_alat");
    var premi_brondolan = $(id).data("premi_brondolan");
    var id_kerani_askep = $(id).data("id_kerani_askep");
    var total = $(id).data("total");
    var id = $(id).data("id");

    $('#validasi').html('&#10095;&#10095; Delete Data <img width="30" heigth="30" src="<?php echo base_url();?>assets/backend/img/loading.gif">').attr('disabled', 'disabled');
    $.ajax({
        type : 'ajax',
        method :'POST',
        url  :'<?php echo site_url('ListByrPanen/validasipanen')?>',
        data:{
          id:id,
          id_kerani_kcs:id_kerani_kcs,
          id_kebun:id_kebun,
          id_afdeling:id_afdeling,
          id_pemanen:id_pemanen,
          tph:tph,
          blok:blok,
          jmlh_panen:jmlh_panen,
          nilai_komidel:nilai_komidel,
          prestasi:prestasi,
          jmlh_brondolan:jmlh_brondolan,
          bron_tim:bron_tim,
          koef:koef,
          nama_alat:nama_alat,
          tbs:tbs,
          premi_alat:premi_alat,
          premi_brondolan:premi_brondolan,
          tanggal:tanggal,
          id_kerani_askep:id_kerani_askep,
          total:total,
          '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>'
        },
        async: false,
        dataType :'json',
        success : function(response){
          reloadTable();
          swal({
            type: 'success',
            title: 'Berhasil Validasi',
            footer: ''
          });
        }
    });

  }

  
  afdeling();
    function afdeling(){
        $.ajax({
            type  : 'POST',
            url   : '<?php echo base_url()?>Sdhsliptimbang/afdeling',
            async : true,
            dataType : 'json',
            success : function(data){
            console.log(data);
                var html = '<option value="">Pilih Afdeling</option>';
                var i;
                for(i=0; i<data.length; i++){
                    html += '<option value="'+data[i].id+'">'+data[i].nama_afdeling+'</option>';
                }
                $('#dataafdeling').html(html);
            }
        });
    }

    
    var d = new Date();
    var startDate = new Date(),
            noOfDaysToAdd = 1,
            count = 1;

    while(count <= noOfDaysToAdd){
            startDate.setDate(startDate.getDate() - 30);
            if(startDate.getDay() != 0){
                    count++;
            }
    }

    $('#datetimepicker').datepicker({
        defaultDate: "+1d",
        minDate: startDate,
        maxDate: d,
        dateFormat: 'yy-m-d',
        showOtherMonths: false,
        changeMonth: false,
        selectOtherMonths: false,
        required: true,
        showOn: "focus",
        numberOfMonths: 1,
        // beforeShowDay: noSundays,
    });

    $('#datetimepicker2').datepicker({
        defaultDate: "+1d",
        minDate: startDate,
        maxDate: d,
        dateFormat: 'yy-m-d',
        showOtherMonths: false,
        changeMonth: false,
        selectOtherMonths: false,
        required: true,
        showOn: "focus",
        numberOfMonths: 1,
        // beforeShowDay: noSundays,
    });
    
    function noSundays(date) {
        return [date.getDay() != 0, ''];
    }
</script>