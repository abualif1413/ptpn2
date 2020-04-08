<section class="py-5 slowmotion">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h6 class="text-uppercase mb-0">
                <button class="btn btn-primary pull-left" id="btnAdd">Tambah</button>
                <label class="pull-right">Data Hasil Panen</label>
            </h6>
          </div>
          <div class="card-body">
          <div style="overflow-x:auto;">
            <table id="DataTable_users" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kerani Kcs</th>
                        <th>Mandor</th>
                        <th>Nama Pemanen</th>
                        <th>Kebun</th>
                        <th>Afdeling</th>
                        <th>Barcode</th>
                        <th>TPH</th>
                        <th>Blok</th>
                        <th>J.Janjang</th>
                        <th>J.Brondolan</th>
                        <th>P.Alat</th>
                        <th>Tanggal Panen</th>
                        <th>Options</th>
                    </tr>
                </thead>
            </table>
          </div>

          <small><b>Catatan :</small><br>
              <small>(1) Data Panen Tampil Berdasarkan Tanggal Berjalan</small><br> 
              <small>(2) Jika Data Panen Lewat Dari Tanggal Berjalan Data Akan Hilang</small><br>  
          </div>
        </div>
      </div>
    </div>
  </section>

<style>
.select2-container .select2-selection--single {
    height: 40px;
}
.select2-container--default .select2-selection--single {
    background-color: #fff;
    border: 1px solid #d5dbdf;
    border-radius: 24px;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #444;
    line-height: 39px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    top: 6px;
}
</style>
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
        <?php echo form_open('', array('id' => 'myForm')); ?>

        <input type="hidden" name="id">
          <div class="form-group">
            <label for="Pemanen">Nama Pemanen:</label>
            <select style="width: 100%;" class="form-control pemanen" id="mySelect2" name="id_pemanen">
            <option value="">Pilih</option>
                <?php foreach ($pemanen as $key_pemanen) {?>
                  <option value="<?php print $key_pemanen->id ?>"><?php print $key_pemanen->nama_pemanen ?></option>
                <?php
                } ?>
            </select>
          </div>
          
          <div class="row">
          <div class="form-group col-md-6">
            <label for="Jumlah Panen Janjang">Jumlah Panen Janjang:</label>
            <input type="number" class="form-control" name="jmlh_panen">
          </div>

          <div class="form-group col-md-6">
            <label for="Jumlah Brondolan: Kg">Jumlah Brondolan: Kg</label>
            <input type="number" class="form-control" name="jmlh_brondolan">
          </div>
          </div>

          

          <div class="form-group ">
            <label for="Blok">Blok:</label>
            <select style="width: 100%;" class="form-control" name="blok" id="tampil">
            </select>
          </div>

          
          <div class="form-group ">
            <label for="TPH">TPH</label>
            <input type="number" class="form-control" name="tph">
          </div>

          <div class="form-group">
            <label for="Pemanen">Premi Alat Penggati</label>
            <select class="form-control" name="id_alat">
                <option value="">Pilih</option>
                <?php foreach ($alat as $key_alat) {?>
                  <option value="<?php print $key_alat->id ?>"><?php print $key_alat->nama_alat ?> ( <?php print $key_alat->premi_alat ?> )</option>
                <?php
                } ?>
            </select>
          </div>


        <?php echo form_close(); ?>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="btnSave" ><i class="fa fa-save"></i>&nbsp;Simpan</button>
      </div>

    </div>
  </div>
</div>
<script src="<?php echo base_url();?>assets/backend/js/jquery-3.3.1.js"></script>
<script type="text/javascript">
    //FUNGSI ADD DATA USER

    // In your Javascript (external .js resource or <script> tag)
    $(document).ready(function() {
        $('.pemanen').select2();
        $('.blok').select2();
    });


    $('#btnAdd').click(function () {
        kode_barcode();
        $('#myModal').modal('show');
        $('#myForm').attr('action','<?php echo site_url('DataHasilPanen/add_data_hasil_panen')?>');
        $('#myModal').find('.modal-title').text('Tambah Data Panen');
        $('input[name=id]').val('');
        $('#btnSave').html('<i class="fa fa-save"></i>&nbsp;Simpan');
    });

    $('.close').click(function () {
      $('#myForm')[0].reset();
      kode_barcode();
    });

    $('#btnSave').click(function () {
    var url = $('#myForm').attr('action');
    var data = new FormData($('#myForm')[0]);
    $('#btnSave').html('<i class="fa fa-save"></i>&nbsp; Simpan <img width="30" heigth="30" src="<?php echo base_url();?>assets/backend/img/loading.gif">').attr('disabled', 'disabled');
    $.ajax({
        type : 'ajax',
        method: 'POST',
        url  :url,
        data : data,
        async: false,
        cache: false,
        contentType:false,
        processData:false,
        dataType :'json',
        success : function(response){
            setTimeout(function(){
                kode_barcode();
                if (response.success == true) {
                    $('#myForm')[0].reset();
                    $('#btnSave').prop('disabled', false);
                    reloadTable();
                        if (response.type=='Add') {
                            var type = 'Add';
                            swal({
                              type: 'success',
                              title: 'Berhasil Input',
                              footer: ''
                            });
                            $('#btnSave').html('<i class="fa fa-save"></i>&nbsp;Simpan');
                        }else if(response.type=='Update'){
                            var type = 'Update';
                            swal({
                              type: 'success',
                              title: 'Berhasil Update',
                              footer: ''
                            });
                            $('#btnSave').html('<i class="fa fa-save"></i>&nbsp;Update');
                        }
                        $('#myModal').modal('hide');
                      }else {

                    if (response.error) {
                        $('#myModal').modal('hide');
                        swal({
                          type: 'warning',
                          title: 'Peringatan !!!!',
                          html: response.error,
                          footer: ''
                        });
                        $('#btnSave').html('<i class="fa fa-save"></i>&nbsp;Simpan');
                        $('#btnSave').prop('disabled', false);
                    }
                }
            },1000);
        },
        error: function(){
            swal({
                  type: 'error',
                  title: 'Error Input !!..',
                  footer: ''
            });
            $('#btnSave').html('<i class="fa fa-save"></i>&nbsp;Simpan');
            $('#btnSave').prop('disabled', false);
        }
    });
    });


    function reloadTable() {
        $("#DataTable_users").DataTable().ajax.reload(null,false);
    }

    $(document).ready(function() {
        $("#DataTable_users").DataTable({
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
                url: '<?php echo base_url('DataHasilPanen/data_hasil_panen');?>',
                type: 'POST',
                data: {'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>'}
            },
            order:[[2,'DESC']],
            columns:[
                {
                    data:'no',
                    searchable:false,
                    orderable:false,
                },
                { data:'keranikcs' },
                { data:'mandor' },
                { data:'nama_pemanen' },
                { data:'nama_kebun' },
                { data:'nama_afdeling' },
                { data:'barcode' },
                { data:'tph' },
                { data:'kode_blok' },
                { data:'jmlh_panen' },
                { data:'jmlh_brondolan' },
                { data:'premi_alat' },
                { data:'tanggal' },
                {
                    data:'button_action',
                    searchable:false,
                    orderable:false,
                }
            ],
        });
    });


  function Update(id){
      var id = $(id).data("id");
      $('#btnSave').html('Update');
      $('#myModal').modal('show');
      $('#myForm').attr('action','<?php echo site_url('DataHasilPanen/update_hasil_panen')?>');
      $('#myModal').find('.modal-title').text('Ubah Data');
      $.ajax({
          type : 'GET',
          async: false,
          url  : '<?php echo site_url('DataHasilPanen/Edit_hasil_panen')?>',
          data:{
              id:id,
              '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>'
            },
          dataType :'json',
          success:function(data){
            console.log(data);
              $('input[name=id]').val(data.id);
              $('#mySelect2').val(data.id_pemanen).trigger('change');
              $('select[name=id_kebun]').val(data.id_kebun);
              $('select[name=id_afdeling]').val(data.id_afdeling);
              $('input[name=jmlh_panen]').val(data.jmlh_panen);
              $('input[name=tph]').val(data.tph);
              $('select[name=blok]').val(data.blok);
              $('input[name=jmlh_brondolan]').val(data.jmlh_brondolan);
              $('select[name=id_alat]').val(data.id_alat);
          }
      });
  }

  function Delete(id) {
    var id = $(id).data("id");
    $('.btnDelete').html('&#10095;&#10095; Delete Data <img width="30" heigth="30" src="<?php echo base_url();?>assets/backend/img/loading.gif">').attr('disabled', 'disabled');
    $.ajax({
        type : 'ajax',
        method :'get',
        url  :'<?php echo site_url('DataHasilPanen/Delete_hasil_panen')?>',
        data:{
          id:id,
          '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>'
        },
        async: false,
        dataType :'json',
        success : function(response){
          reloadTable();
          swal({
            type: 'success',
            title: 'Berhasil Delete',
            footer: ''
          });
        }
    });
  }

    kode_barcode();
    function kode_barcode() {
        $.ajax({
            type : 'ajax',
            url  : '<?php echo site_url('DataPemanen/barcode')?>',
            async: false,
            dataType :'json',
            success:function(response){
            $('input[name=barcode').val(response.barcode);
            },
            error:function () {
            // alert('DATA TIDAK ADA');
            }
        });
    }
</script>

<script type="text/javascript">
    $(document).ready(function(){
        tampil_data_barang();
        function tampil_data_barang(){
            $.ajax({
                type  : 'ajax',
                url   : '<?php echo site_url('DataHasilPanen/blok')?>',
                async : false,
                dataType : 'json',
                success : function(data){
                  if (!$.trim(data)){   
                      html += '<option value="">Pilih </option><option value="">None</option>';
                  }
                  else{   
                      var html = '<option value="">Pilih</option>';
                      var i;
                      for(i=0; i<data.length; i++){
                        html += '<option value='+data[i].id+'>Blok '+data[i].blok+'</option>';
                      }
                  }
                  $('#tampil').html(html);
                }
            });
        }
 
    });
 
</script>