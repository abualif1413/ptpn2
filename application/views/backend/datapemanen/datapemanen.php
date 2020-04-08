<section class="py-5 slowmotion">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h6 class="text-uppercase mb-0">
                <button class="btn btn-primary pull-left" id="btnAdd">Tambah</button>
                
                <label class="pull-right">Data Pemanen</label>
            </h6>
          </div>
          <div class="card-body">
          <div style="overflow-x:auto;">
            <table id="DataTable_users" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kerani KCS</th>
                        <th>Mandor Kebun</th>
                        <th>Photo Pemanen</th>
                        <th>Nama Pemanen</th>
                        <th>Kebun</th>
                        <th>Afdeling</th>
                        <th>Kode Qrcode</th>
                        <th>Qr Code</th>
                        <th>Keterangan</th>
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
          <div class="row">
          <div class="form-group col-md-6">
            <label for="Pemanen">Barcode:</label>
            <input type="text" class="form-control" name="barcode" readonly>
            <input type="hidden" name="id">
          </div>

          <div class="form-group col-md-6">
            <label for="Pemanen">Nama Pemanen:</label>
            <input type="text" class="form-control" name="nama_pemanen">
          </div>
          </div>

          <div class="form-group">
            <label for="Photo">Keterangan:</label>
            <textarea  class="form-control" name="keterangan"></textarea>
          </div>

          <div class="row">
          <div class="form-group col-md-6">
            <label for="email">Photo:</label>
            <input type="file" class="form-control" name="photo">
          </div>

          <div class="form-group gam col-md-6">
             <img src="" id="img2" width="100" height="100">
          </div>
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

    $('#btnAdd').click(function () {
        kode_barcode();
        $('#myModal').modal('show');
        $('#myForm').attr('action','<?php echo site_url('DataPemanen/add_data_pemanen')?>');
        $('#myModal').find('.modal-title').text('Tambah Data Pemanen');
        $('input[name=id]').val('');
        $('#btnSave').html('<i class="fa fa-save"></i>&nbsp;Simpan');
        $('#img2').hide();
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
                url: '<?php echo base_url('DataPemanen/data_pemanen');?>',
                type: 'POST',
                data: {'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>'}
            },
            order:[[1,'ASC']],
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'pdf', 'print'
            ],
            columns:[
                {
                    data:'no',
                    searchable:false,
                    orderable:false,
                },
                { data:'keranikcs' },
                { data:'nama_lengkap' },
                { data:'image' },
                { data:'nama_pemanen' },
                { data:'nama_kebun' },
                { data:'nama_afdeling' },
                { data:'barcode' },
                { data:'img_barcode' },
                { data:'keterangan' },
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
      $('#myForm').attr('action','<?php echo site_url('DataPemanen/update_Pemanen')?>');
      $('#myModal').find('.modal-title').text('Ubah Data');
      $.ajax({
          type : 'GET',
          async: false,
          url  : '<?php echo site_url('DataPemanen/Edit_Pemanen')?>',
          data:{
              id:id,
              '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>'
            },
          dataType :'json',
          success:function(data){
            console.log(data);
              $('input[name=id]').val(data.id);
              $('input[name=barcode]').val(data.barcode);
              $('input[name=nama_pemanen]').val(data.nama_pemanen);
              $('textarea[name=keterangan]').val(data.keterangan);
              $("#img2").attr("src","assets/backend/img/photo/"+data.photo+"").fadeIn();
          }
      });
  }

  function Delete(id) {
    var id = $(id).data("id");
    $('.btnDelete').html('&#10095;&#10095; Delete Data <img width="30" heigth="30" src="<?php echo base_url();?>assets/backend/img/loading.gif">').attr('disabled', 'disabled');
    $.ajax({
        type : 'ajax',
        method :'get',
        url  :'<?php echo site_url('DataPemanen/Delete_Pemanen')?>',
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