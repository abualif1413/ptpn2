<section class="py-5 slowmotion">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h6 class="text-uppercase mb-0">
                <label class="pull-right">Data Mandor</label>
            </h6>
          </div>
          <div class="card-body">
          <div style="overflow-x:auto;">
            <table id="DataTable_users" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kerani Askep</th>
                        <th>Kerani Kcs</th>
                        <th>Nama Mandor</th>
                        <th>Email</th>
                        <th>K.Askep</th>
                        <th>Kebun</th>
                        <th>Afdeling</th>
                        <th>Jabatan</th>
                        <th>Photo</th>
                    </tr>
                </thead>
            </table>
          </div>
          </div>
        </div>
      </div>
    </div>
  </section>


<script src="<?php echo base_url();?>assets/backend/js/jquery-3.3.1.js"></script>
<script type="text/javascript">
    //FUNGSI ADD DATA USER

    $('#btnAdd').click(function () {
        $('#myModal').modal('show');
        $('#myForm').attr('action','<?php echo site_url('Mandorkeraniaskep/add_mandor')?>');
        $('#myModal').find('.modal-title').text('Tambah Data Mandor');
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
                url: '<?php echo base_url('Mandorkeraniaskep/Data_Mandor');?>',
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
                { data:'keraniaskep' },
                { data:'keranikcs' },
                { data:'nama_lengkap' },
                { data:'email' },
                { data:'keraniaskep' },
                { data:'nama_kebun' },
                { data:'nama_afdeling' },
                { data:'jabatan' },
                { data:'image' }
            ],
        });
    });


  function Update(id){
    $("#afdeling").empty();
      var token = $(id).data("token");
      $('#btnSave').html('Update');
      $('#myModal').modal('show');
      $('#myForm').attr('action','<?php echo site_url('Mandorkeraniaskep/update_mandor')?>');
      $('#myModal').find('.modal-title').text('Ubah Data');
      $.ajax({
          type : 'GET',
          async: false,
          url  : '<?php echo site_url('Mandorkeraniaskep/Edit_mandor')?>',
          data:{
            token:token,
              '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>'
            },
          dataType :'json',
          success:function(data){
            console.log(data);
              $('input[name=token]').val(data.token);
              $('input[name=id]').val(data.id);
              $('input[name=email]').val(data.email);
              $('input[name=password]').val(data.password);
              $('input[name=nama_lengkap]').val(data.nama_lengkap);
              $('select[name=role]').val(data.role);
              $('select[name=id_kerani_askep]').val(data.id_kerani_askep);
              $('select[name=status]').val(data.status);
              $('select[name=id_kebun]').val(data.id_kebun);
              $("#afdeling").append("<option value='"+data.id_afdeling+"'>"+data.nama_afdeling+"</option>");
              $('input[name=jabatan]').val(data.jabatan);
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
        url  :'<?php echo site_url('Mandorkeraniaskep/Delete_Mandor')?>',
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


$(document).ready(function()
{
  $("#kebun").change(function()
  {
      var id=$(this).val();
      $("#afdeling").find('option').remove();
      $.ajax
      ({
          type: "POST",
          url: "<?php echo site_url('Mandorkeraniaskep/afdeling')?>",
          data: {id:id},
          cache: false,
          success: function(html)
          {
            $("#afdeling").html(html);
          } 
      });
  }); 
});
</script>