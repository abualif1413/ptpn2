<section class="py-5 slowmotion">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h6 class="text-uppercase mb-0">
                <button class="btn btn-primary pull-left" id="btnAdd">Tambah</button>
                
                <label class="pull-right">Data Distrik</label>
            </h6>
          </div>
          <div class="card-body">
          <div style="overflow-x:auto;">
            <table id="DataTable_users" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Distrik</th>
                        <th>Email</th>
                        <th>Photo</th>
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
            <label for="email">Email address:</label>
            <input type="email" class="form-control" name="email">
            <input type="hidden" name="token">
          </div>

          <div class="form-group">
            <label for="Username">Nama Distrik:</label>
            <input type="text" class="form-control" name="nama_distrik">
          </div>

          <div class="form-group">
            <label for="Role">Photo:</label>
            <input type="file" class="form-control" name="photo">
          </div>

          <div class="form-group gam">
             <img src="" id="img2" width="100" height="100">
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
        $('#myForm').attr('action','<?php echo site_url('Datadistrik/add_distrik')?>');
        $('#myModal').find('.modal-title').text('Tambah Data Kerani Askep');
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
                url: '<?php echo base_url('Datadistrik/Data_Distrik');?>',
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
                { data:'email' },
                { data:'image' },
                {
                    data:'button_action',
                    searchable:false,
                    orderable:false,
                }
            ],
        });
    });


  function Update(id){
    $("#afdeling").empty();
      var token = $(id).data("token");
      $('#btnSave').html('Update');
      $('#myModal').modal('show');
      $('#myForm').attr('action','<?php echo site_url('Datadistrik/Update_Distrik')?>');
      $('#myModal').find('.modal-title').text('Ubah Data Distrik');
      $.ajax({
          type : 'GET',
          async: false,
          url  : '<?php echo site_url('Datadistrik/Edit_Distrik')?>',
          data:{
            token:token,
              '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>'
            },
          dataType :'json',
          success:function(data){
            console.log(data);
              $('input[name=id]').val(data.id);
              $('input[name=token]').val(data.token);
              $('input[name=email]').val(data.email);
              $('input[name=nama_distrik]').val(data.nama_lengkap);
              $("#img2").attr("src","assets/backend/img/photo/"+data.photo+"").fadeIn();
          }
      });
  }

  function Delete(id) {
    var token = $(id).data("token");
    $('.btnDelete').html('&#10095;&#10095; Delete Data <img width="30" heigth="30" src="<?php echo base_url();?>assets/backend/img/loading.gif">').attr('disabled', 'disabled');
    $.ajax({
        type : 'ajax',
        method :'get',
        url  :'<?php echo site_url('Datadistrik/Delete_Distrik')?>',
        data:{
          token:token,
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