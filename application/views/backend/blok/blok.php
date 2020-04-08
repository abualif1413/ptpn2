<section class="py-5 slowmotion">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h6 class="text-uppercase mb-0">
                <button class="btn btn-primary pull-left" id="btnAdd">Tambah</button>
                
                <label class="pull-right">Data Blok</label>
            </h6>
          </div>
          <div class="card-body">
          <div style="overflow-x:auto;">
            <table id="DataTable_Blok" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kebun</th>
                        <th>Afdeling</th>
                        <th>Blok</th>
                        <th>BT</th>
                        <th>Rp/P0</th>
                        <th>Rp/P1</th>
                        <th>Rp/P2</th>
                        <th>Rp/P3</th>
                        <th>Status</th>
                        <th>Thn Tanam</th>
                        <th>Pre Komidel (Kg)</th>
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
        <input type="hidden" name="id">
        <div class="row">
          <div class="form-group col-md-6">
            <label for="Kebun">Kebun:</label>
            <select class="form-control" name="id_kebun" id="kebun">
                <option value="">Pilih</option>
                <?php foreach ($kebun as $key_kebun) {?>
                  <option value="<?php print $key_kebun->id ?>"><?php print $key_kebun->nama_kebun ?></option>
                <?php
                } ?>
            </select>
          </div>

          <div class="form-group col-md-6">
            <label for="Afdeling">Afdeling:</label>
            <select class="form-control" name="id_afdeling" id="afdeling">
            </select>
          </div>

          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label for="Harga">Blok:</label>
              <input type="text" class="form-control" name="blok">
            </div>

            <div class="form-group col-md-6">
              <label for="Harga">Batas Tugas (BT) (Kg)</label>
              <input type="number" class="form-control" name="bt" id="BT">
            </div>
          </div>

          <div class="row" style="display:none;">
            <div class="form-group col-md-6">
              <label for="Harga">P0: Dari  <b id="n0">0</b> Sampai <b id="n1"></b></label>
              <input type="number" class="form-control" name="p0" id="P0" readonly>
            </div>

            <div class="form-group col-md-6">
              <label for="Harga">P1: Dari <b id="n2"></b> Sampai <b id="n3"></b></label>
              <input type="number" class="form-control" name="p1" id="P1" readonly>
            </div>
          </div>

          <div class="row" style="display:none;">
            <div class="form-group col-md-6">
              <label for="Harga">P2: Dari  <b id="n4"></b> Sampai <b id="n5"></b></label>
              <input type="number" class="form-control" name="p2" id="P2" readonly>
            </div>

            <div class="form-group col-md-6">
              <label for="Harga">P2: Dari  <b id="n6"></b> Dst</label>
              <input type="number" class="form-control" name="p3" id="P3" readonly>
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-6">
              <label for="Harga">Nilai Rp P0</label>
              <input type="number" class="form-control" name="rp_p0" >
            </div>

            <div class="form-group col-md-6">
              <label for="Harga">Nilai Rp P1</label>
              <input type="number" class="form-control" name="rp_p1" >
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-6">
              <label for="Harga">Nilai Rp P2</label>
              <input type="number" class="form-control" name="rp_p2">
            </div>

            <div class="form-group col-md-6">
              <label for="Harga">Nilai Rp P3</label>
              <input type="number" class="form-control" name="rp_p3" >
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-6">
              <label for="Afdeling">Status:</label>
              <select class="form-control" name="status">
                  <option value="">Pilih</option>
                  <option value="Y">Y</option>
                  <option value="N">N</option>
              </select>
            </div>

            <div class="form-group col-md-6">
              <label for="Afdeling">Keterangan Hari:</label>
              <select class="form-control" name="keterangan">
                  <option value="">Pilih</option>
                  <option value="All">All (Senin-Sabtu)</option>
                  <option value="Minggu">Minggu</option>
                  <option value="Tanggal_Merah">Tanggal Merah</option>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-6">
              <label for="Harga">Tahun Tanam:</label>
              <input type="number" class="form-control" name="tahun_tanam">
            </div>

            <div class="form-group col-md-6">
              <label for="Harga">Prediksi Komidel (Kg)</label>
              <input type="number" class="form-control" name="prediksi_komidel">
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
        $('#myModal').modal('show');
        $('#myForm').attr('action','<?php echo site_url('Blok/add_data_blok')?>');
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
        $("#DataTable_Blok").DataTable().ajax.reload(null,false);
    }

    $(document).ready(function() {
        $("#DataTable_Blok").DataTable({
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
                url: '<?php echo base_url('Blok/Data_Blok');?>',
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
                { data:'nama_kebun' },
                { data:'nama_afdeling'},
                { data:'blok' },
                { data:'bt' },
                { data:'rp_p0' },
                { data:'rp_p1' },
                { data:'rp_p2' },
                { data:'rp_p3' },
                { data:'status' },
                { data:'tahun_tanam' },
                { data:'prediksi_komidel' },
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
      var id = $(id).data("id");
      $('#btnSave').html('Update');
      $('#myModal').modal('show');
      $('#myForm').attr('action','<?php echo site_url('Blok/Update_Blok')?>');
      $('#myModal').find('.modal-title').text('Ubah Data');
      $.ajax({
          type : 'GET',
          async: false,
          url  : '<?php echo site_url('Blok/Edit_Blok')?>',
          data:{
              id:id,
              '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>'
            },
          dataType :'json',
          success:function(data){
            console.log(data);
              $('input[name=id]').val(data.id);
              $('select[name=id_kebun]').val(data.id_kebun);
              $("#afdeling").append("<option value='"+data.id_afdeling+"'>"+data.nama_afdeling+"</option>");
              $('input[name=blok]').val(data.blok);
              $('input[name=bt]').val(data.bt);
              $('input[name=p0]').val(data.p0);
              $('input[name=p1]').val(data.p1);
              $('input[name=p2]').val(data.p2);
              $('input[name=p3]').val(data.p3);
              $('input[name=rp_p0]').val(data.rp_p0);
              $('input[name=rp_p1]').val(data.rp_p1);
              $('input[name=rp_p2]').val(data.rp_p2);
              $('input[name=rp_p3]').val(data.rp_p3);
              $('select[name=status]').val(data.status);
              $('select[name=keterangan]').val(data.keterangan);
              $('input[name=tahun_tanam]').val(data.tahun_tanam);
              $('input[name=prediksi_komidel]').val(data.prediksi_komidel);
          }
      });
  }

  function Delete(id) {
    var id = $(id).data("id");
    $('.btnDelete').html('&#10095;&#10095; Delete Data <img width="30" heigth="30" src="<?php echo base_url();?>assets/backend/img/loading.gif">').attr('disabled', 'disabled');
    $.ajax({
        type : 'ajax',
        method :'get',
        url  :'<?php echo site_url('Blok/Delete_Blok')?>',
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
  $("#BT").keyup(function(){
    var BT  = $('#BT').val().substring(0,8);
    var A   = 1.3;
    var B   = 1.75;
    var N1  = BT*0;
    var N2  = BT*A;
    var N3  = BT*B;
    $("#P0").val(N1);
    $("#P1").val(BT);
    $("#P2").val(N2);
    $("#P3").val(N3);
    $("#n1").html(BT);
    $("#n2").html(BT);
    $("#n3").html(N2);
    $("#n4").html(N2);
    $("#n5").html(N3);
    $("#n6").html(N3);
  });

$(document).ready(function()
{
  $("#kebun").change(function()
  {
      var id=$(this).val();
      $("#afdeling").find('option').remove();
      $.ajax
      ({
          type: "POST",
          url: "<?php echo site_url('Blok/afdeling')?>",
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
