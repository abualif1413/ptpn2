<section class="py-5 slowmotion">
    <div class="row">

      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h6 class="text-uppercase mb-0">
                Input Data Komidel
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
            <table id="DataTable_Berat" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kerani Kcs</th>
                        <th>Mandor Kebun</th>
                        <th>Kebun</th>
                        <th>Afdeling</th>
                        <th>Jmlh Panen Janjang</th>
                        <th>Tanggal Panen</th>
                        <th>Detail</th>
                        <th>Options</th>
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
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- Modal body -->
      <div class="modal-body">
      <div style="overflow-x:auto;">
      <table id="Datatable_detail" class="table table-striped table-bordered" style="width:100%">
          <thead>
              <tr>
                  <th>No</th>
                  <th>Kerani Kcs</th>
                  <th>Pemanen</th>
                  <th>Kebun</th>
                  <th>Afdeling</th>
                  <th>Barcode</th>
                  <th>TPH</th>
                  <th>Blok</th>
                  <th>J.Janjang</th>
                  <th>J.Brondolan</th>
                  <th>P.Alat</th>
                  <th>Tanggal</th>

              </tr>
          </thead>
          <tbody id="show_data">
              
          </tbody>
          <tbody>
          </tbody>
      </table>
      </div>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo base_url();?>assets/backend/js/jquery-3.3.1.js"></script>
<script type="text/javascript">
    
    $(document).ready(function() {
        $("#DataTable_Berat").DataTable({
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
                url: '<?php echo base_url('Inputkomidel/data_input_berat');?>',
                type: 'POST',
                data: {'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>'}
            },
            order:[[5,'DESC']],
            columns:[
                {
                    data:'no',
                    searchable:false,
                    orderable:false,
                },
                { data:'nama_lengkap' },
                { data:'mandor' },
                { data:'nama_kebun' },
                { data:'nama_afdeling' },
                {
                    data:'total',
                    searchable:false,
                    orderable:false,
                },
                { data:'tanggal' },
                {
                    data:'button_detail',
                    searchable:false,
                    orderable:false,
                },
                {
                    data:'button_action',
                    searchable:false,
                    orderable:false,
                }
            ],
        });
    });

    function reloadTablekomidel() {
        $("#DataTable_Komidel").DataTable().ajax.reload(null,false);
    }

    function reloadTableberat() {
        $("#DataTable_Berat").DataTable().ajax.reload(null,false);
    }

    $(document).ready(function() {
        $("#DataTable_Komidel").DataTable({
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
                url: '<?php echo base_url('DataKomidel/data_komidel');?>',
                type: 'POST',
                data: {'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>'}
            },
            order:[[1,'ASC']],
            columns:[
                {
                    data:'no',
                    searchable:false,
                    orderable:false,
                },
                { data:'nama_lengkap' },
                { data:'nama_kebun' },
                { data:'nama_afdeling' },
                { data:'jmlh_panen' },
                { data:'kg' },
                { data:'nilai_komidel' },
                { data:'tanggal_panen' },
                { data:'create_att' },
                {
                    data:'button_action',
                    searchable:false,
                    orderable:false,
                }
            ],
        });
    });

  function Detail(id) {
    $('#myModal').modal('show');
    var id_kerani_kcs = $(id).data("id_kerani_kcs");
    var id_afdeling = $(id).data("id_afdeling");
    var tanggal = $(id).data("tanggal");
    var id_pemanen = $(id).data("id_pemanen");
    var id_kebun = $(id).data("id_kebun");
  $(document).ready(function(){
        tampil_data_barang();
        $('#Datatable_detail').dataTable();
        function tampil_data_barang(){
            $.ajax({
                type  : 'POST',
                url   : '<?php echo base_url()?>Inputkomidel/detail_hasil_panen',
                async : false,
                dataType : 'json',
                data:{
                    id_kerani_kcs:id_kerani_kcs,
                    id_kebun:id_kebun,
                    id_afdeling:id_afdeling,
                    tanggal:tanggal,
                },
                success : function(data){
                    var html = '';
                    var i;
                    var no=1;
                    for(i=0; i<data.length; i++){
                        html += '<tr>'+
                                '<td>'+no+'</td>'+
                                '<td>'+data[i].nama_lengkap+'</td>'+
                                '<td>'+data[i].nama_pemanen+'</td>'+
                                '<td>'+data[i].nama_kebun+'</td>'+
                                '<td>'+data[i].nama_afdeling+'</td>'+
                                '<td>'+data[i].barcode+'</td>'+
                                '<td>'+data[i].tph+'</td>'+
                                '<td>'+data[i].kode_blok+'</td>'+
                                '<td>'+data[i].jmlh_panen+'</td>'+
                                '<td>'+data[i].jmlh_brondolan+'</td>'+
                                '<td>'+data[i].premi_alat+'</td>'+
                                '<td>'+data[i].tanggal+'</td>'+
                                '</tr>';
                                no++;
                    }
                    $('#show_data').html(html);
                }
            });
        }
        $('.close').click( function () {
          $('#Datatable_detail').DataTable().destroy();
        });
 
    });
  }
  
</script>