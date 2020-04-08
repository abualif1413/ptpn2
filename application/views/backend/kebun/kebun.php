<section class="py-5 slowmotion">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h6 class="text-uppercase mb-0">
                <button class="btn btn-primary pull-left" id="btnAdd">Tambah</button>
                
                <label class="pull-right">Data Kebun</label>
            </h6>
          </div>
          <div class="card-body">
          <div style="overflow-x:auto;">
            <table id="DataTable_Kebun" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Distrik</th>
                        <th>Nama Kebun</th>
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

          <div class="form-group">
            <label for="Kebun">Distrik:</label>
            <select class="form-control" name="id_distrik">
                <option value="">Pilih</option>
                <?php foreach ($Distrik as $key_distrik) {?>
                  <option value="<?php print $key_distrik->id ?>"><?php print $key_distrik->nama_lengkap ?></option>
                <?php
                } ?>
            </select>
          </div>

          <div class="form-group">
            <label for="Kebun">Nama Kebun:</label>
            <input type="text" class="form-control" name="nama_kebun">
            <input type="hidden" name="id">
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
        $('#myModal').modal('show');
        $('#myForm').attr('action','<?php echo site_url('Kebun/Add_Kebun')?>');
        $('#myModal').find('.modal-title').text('Tambah Data');
        $('input[name=id]').val('');
        $('#btnSave').html('<i class="fa fa-save"></i>&nbsp;Simpan');
        $('#img2').hide();
    });

    $('.close').click(function () {
      $('#myForm')[0].reset();
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
        $("#DataTable_Kebun").DataTable().ajax.reload(null,false);
    }

    $(document).ready(function() {
        $("#DataTable_Kebun").DataTable({
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
                url: '<?php echo base_url('Kebun/Data_Kebun');?>',
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
                { data:'distrik' },
                { data:'nama_kebun' },
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
      $('#myForm').attr('action','<?php echo site_url('Kebun/Update_Kebun')?>');
      $('#myModal').find('.modal-title').text('Ubah Data');
      $.ajax({
          type : 'GET',
          async: false,
          url  : '<?php echo site_url('Kebun/Edit_Kebun')?>',
          data:{
              id:id,
              '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>'
            },
          dataType :'json',
          success:function(data){
            console.log(data);
              $('input[name=id]').val(data.id);
              $('input[name=nama_kebun]').val(data.nama_kebun);
              $('select[name=id_distrik]').val(data.distrik);
          }
      });
  }

  function Delete(id) {
    var id = $(id).data("id");
    $('.btnDelete').html('&#10095;&#10095; Delete Data <img width="30" heigth="30" src="<?php echo base_url();?>assets/backend/img/loading.gif">').attr('disabled', 'disabled');
    $.ajax({
        type : 'ajax',
        method :'get',
        url  :'<?php echo site_url('Kebun/Delete_Kebun')?>',
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

</script>