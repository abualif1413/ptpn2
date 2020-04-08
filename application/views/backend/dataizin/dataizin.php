
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/mint-choc/jquery-ui.css" rel="stylesheet" />
<section class="py-5 slowmotion">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h6 class="text-uppercase mb-0">
                <button class="btn btn-primary pull-left" id="btnAdd">Tambah</button>
                <label class="pull-right">Data Izin</label>
            </h6>
          </div>
          <div class="card-body">
          <div style="overflow-x:auto;">
            <table id="DataTable_users" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>QR Code</th>
                        <th>Nama Pemanen</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>keterangan</th>
                        <th>Option</th>
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
            <input type="hidden"  name="id"> 
          <div class="form-group">
            <label for="Nama Pemmanen">Nama Pemmanen:</label>
            <select class="form-control" name="id_absen" id="show_data">
                <option value="">Pilih</option>
            </select>
          </div>
          <div class="form-group">
            <label for="Tanggal">Tanggal:</label>
            <input type="date" class="form-control" name="tanggal">
          </div>

          <div class="form-group">
            <label for="Jenis">Jenis:</label>
            <select class="form-control" name="jenis">
                <option value="">Pilih</option>
                <option value="S">Sakit</option>
                <option value="C">Cuti</option>
                <option value="P1">Izin (P1)</option>
                <option value="P2">Izin (P2)</option>
                <option value="P3">Izin (P3)</option>
                <option value="H1">H1</option>
                <option value="S1">S1</option>
                <option value="S2">S2</option>
            </select>
          </div>

          <div class="form-group">
            <label for="Keterangan">Keterangan:</label>
            <textarea type="text" class="form-control" name="keterangan"></textarea>
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
                url: '<?php echo base_url('Dataizin/Data_Izin');?>',
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
                { data:'id_absen' },
                { data:'nama_pemanen' },
                { data:'tanggal' },
                { data:'jenis' },
                { data:'keterangan' },
                { 
                    data:'button_action',
                    searchable:false,
                    orderable:false, 
                }
            ],
        });

        
    });

    $('#btnAdd').click(function () {
        $('#myModal').modal('show');
        $('#myForm').attr('action','<?php echo site_url('Dataizin/Add_Izin')?>');
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

        
    function Update(id){
        var id = $(id).data("id");
        $('#btnSave').html('Update');
        $('#myModal').modal('show');
        $('#myForm').attr('action','<?php echo site_url('Dataizin/Update_Izin')?>');
        $('#myModal').find('.modal-title').text('Ubah Data');
        $.ajax({
            type : 'GET',
            async: false,
            url  : '<?php echo site_url('Dataizin/Edit_Izin')?>',
            data:{
                id:id,
                '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>'
                },
            dataType :'json',
            success:function(data){
                console.log(data);
                $('input[name=id]').val(data.id);
                $('select[name=id_absen]').val(data.id_absen);
                $('select[name=jenis]').val(data.jenis);
                $('input[name=tanggal]').val(data.tanggal);
                $('textarea[name=keterangan]').val(data.keterangan);
            }
        });
    }

    function Delete(id) {
        var id = $(id).data("id");
        $('.btnDelete').html('&#10095;&#10095; Delete Data <img width="30" heigth="30" src="<?php echo base_url();?>assets/backend/img/loading.gif">').attr('disabled', 'disabled');
        $.ajax({
            type : 'ajax',
            method :'get',
            url  :'<?php echo site_url('Dataizin/Delete_Izin')?>',
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
    showpemanen();
    function showpemanen(){
        $.ajax({
            type  : 'GET',
            url   : '<?php echo base_url()?>Dataizin/datapemanen',
            async : true,
            dataType : 'json',
            success : function(data){
                var html = '';
                var i;
                for(i=0; i<data.length; i++){
                    html += '<option value="'+data[i].barcode+'">'+data[i].nama_pemanen+'</option>';
                }
                $('#show_data').html(html);
            }

        });
    }
</script>