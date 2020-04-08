<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/mint-choc/jquery-ui.css" rel="stylesheet" />

<section class="py-5 slowmotion">
    <div class="row">
        <div class="col-md-12">
        <div class="card">
            <div class="card-header">
            <h6 class="text-uppercase mb-0">
            <button  class="btn btn-primary" id="btnAdd">Tambah Trip</button>
                <label class="pull-right">Input Trip Temporery</label>
            </h6>
            </div>
            <div class="card-body">
            
                <div class="row">
                
                    <div class="col-md-12">
                        <div style="overflow-x:auto;">
                            <table id="trip" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>SPTBS</th>
                                        <th>Kerani Kcs</th>
                                        <th>Kebun</th>
                                        <th>Afdeling</th>
                                        <th>Blok No.1</th>
                                        <th>Janjang No.1</th>
                                        <th>Tahun No.1</th>
                                        <th>Blok No.2</th>
                                        <th>Janjang No.2</th>
                                        <th>Tahun No.2</th>
                                        <th>Blok No.3</th>
                                        <th>Janjang No.3</th>
                                        <th>Tahun No.3</th>
                                        <th>J.Brondolan No 1</th>
                                        <th>J.Brondolan No 2</th>
                                        <th>J.Brondolan No 3</th>
                                        <th>N.Polisi Trek</th>
                                        <th>Tanggal</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
          <small style="color:red;">Catatan : 1. Blok Yang Di Input Hanya Blok Yang Panen Saja dan Nilai 0 Dihapus Jika Mau Di isi Jumlah Janjang </small>
        <?php echo form_open('', array('id' => 'myForm')); ?>
        <input type="hidden" name="id">
        
        <div class="form-group ">
            <label for="">Nomor SPTBS</label>
            <input type="text" class="form-control" name="no_sptbs" readonly>
        </div>

        <div class="form-group ">
            <label for="">Nomor Polisi Mobil</label>
            <input type="text" class="form-control" name="nomor_polisi_trek" id="BK" onkeyup="myFunction()">
        </div>

        <div class="row">
          <div class="form-group col-md-4">
            <label for="Blok">Blok No.1 :</label>
            <select style="width: 100%;" class="form-control" name="id_blok_1" id="tampil1" >
            </select>
          </div>

          <div class="form-group col-md-4">
            <label for="">J.Janjang No.1</label>
            <input type="text" class="form-control"  name="jumlah_janjang_1" value="0">
          </div>

          <div class="form-group col-md-4">
            <label for="">J.Brondolan No.1</label>
            <input type="text" class="form-control"  name="jumlah_brondolan_1" value="0">
          </div>
        </div>

        <div class="row">
          <div class="form-group col-md-4">
            <label for="Blok">Blok No.2 :</label>
            <select style="width: 100%;" class="form-control" name="id_blok_2" id="tampil2" >
            </select>
          </div>

          <div class="form-group col-md-4">
            <label for="">J.Janjang No.2</label>
            <input type="text" class="form-control"  name="jumlah_janjang_2" value="0" >
          </div>

          <div class="form-group col-md-4">
            <label for="">J.Brondolan No.2</label>
            <input type="text" class="form-control"  name="jumlah_brondolan_2" value="0">
          </div>

        </div>

        <div class="row">
          <div class="form-group col-md-4">
            <label for="Blok">Blok No.3 :</label>
            <select style="width: 100%;" class="form-control" name="id_blok_3" id="tampil3">
            </select>
          </div>

          <div class="form-group col-md-4">
            <label for="">J.Janjang No.3</label>
            <input type="text" class="form-control" name="jumlah_janjang_3" value="0">
          </div>

          <div class="form-group col-md-4">
            <label for="">J.Brondolan No.3</label>
            <input type="text" class="form-control"  name="jumlah_brondolan_3" value="0">
          </div>

        </div>
        <div class="row">
          <!-- <div class="form-group col-md-6">
            <label for="">Total Jumlah Brondolan</label>
            <input type="text" class="form-control" name="jumlah_brondolan">
          </div> -->

          <div class="form-group col-md-12">
            <label for="">Tanggal</label>
            <input type="text" id="datetimepicker" class="form-control" name="tanggal">
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
<script src="<?php echo base_url();?>assets/backend/js/jquery-3.3.1.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.pemanen').select2();
        $('.blok').select2();
    });

    $('#btnAdd').click(function () {
        $('#myForm')[0].reset();
        nomor_stpbs();
        $('#myModal').modal('show');
        $('#myForm').attr('action','<?php echo site_url('TambahTrip/add_trip')?>');
        $('#myModal').find('.modal-title').text('Tambah Trip (Mohon Baca Catatan)');
        $('#btnSave').html('<i class="fa fa-save"></i>&nbsp;Simpan');
    });

   
    $('#btnSave').click(function () {
    nomor_stpbs();
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
                reloadTable();
                if (response.success == true) {
                    $('#myForm')[0].reset();
                    $('#btnSave').prop('disabled', false);
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
                        $('#myForm')[0].reset();
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
                      html += '<option value="0">None</option>';
                  }
                  else{   
                      var html = '<option value="0">None</option>';
                      var i;
                      for(i=0; i<data.length; i++){
                        html += '<option value='+data[i].id+'>Blok '+data[i].blok+'</option>';
                      }
                  }
                  $('#tampil1').html(html);
                  $('#tampil2').html(html);
                  $('#tampil3').html(html);
                }
            });
        }
 
    });


    function reloadTable() {
        $("#trip").DataTable().ajax.reload(null,false);
    }

    $(document).ready(function() {
        $("#trip").DataTable({
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
                url: '<?php echo base_url('TambahTrip/data_trip');?>',
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
                { data:'sptbs' },
                { data:'keranikcs' },
                { data:'kebun' },
                { data:'afdeling' },
                { data:'blok_1' },
                { data:'jumlah_janjang_1' },
                { data:'tahun_tanam_1' },
                { data:'blok_2' },
                { data:'jumlah_janjang_2' },
                { data:'tahun_tanam_2' },
                { data:'blok_3' },
                { data:'jumlah_janjang_3' },
                { data:'tahun_tanam_3' },
                { data:'jumlah_taksir_brondolan_1' },
                { data:'jumlah_taksir_brondolan_2' },
                { data:'jumlah_taksir_brondolan_3' },
                { data:'nomor_polisi_trek' },
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
        $('input[name=no_sptbs]').val('');
        $('#btnSave').html('Update');
        $('#myModal').modal('show');
        $('#myForm').attr('action','<?php echo site_url('TambahTrip/Update_Trip')?>');
        $('#myModal').find('.modal-title').text('Ubah Data Trip');
        $.ajax({
            type : 'POST',
            async: false,
            url  : '<?php echo site_url('TambahTrip/Edit_Trip')?>',
            data:{
                id:id,
                '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>'
                },
            dataType :'json',
            success:function(data){
                console.log(data);
                $('input[name=id]').val(data.id);
                $('input[name=no_sptbs]').val(data.sptbs);
                $('select[name=id_blok_1]').val(data.id_blok_1);
                $('select[name=id_blok_2]').val(data.id_blok_2);
                $('select[name=id_blok_3]').val(data.id_blok_3);
                $('input[name=jumlah_janjang_1]').val(data.jumlah_janjang_1);
                $('input[name=jumlah_janjang_2]').val(data.jumlah_janjang_2);
                $('input[name=jumlah_janjang_3]').val(data.jumlah_janjang_3);
                $('input[name=jumlah_brondolan_1]').val(data.jumlah_taksir_brondolan_1);
                $('input[name=jumlah_brondolan_2]').val(data.jumlah_taksir_brondolan_2);
                $('input[name=jumlah_brondolan_3]').val(data.jumlah_taksir_brondolan_3);
                $('input[name=nomor_polisi_trek]').val(data.nomor_polisi_trek);
                $('input[name=tanggal]').val(data.tanggal);
            }
        });
    }

    function Delete(id) {
        var id = $(id).data("id");
        $('.btnDelete').html('&#10095;&#10095; Delete Data <img width="30" heigth="30" src="<?php echo base_url();?>assets/backend/img/loading.gif">').attr('disabled', 'disabled');
        $.ajax({
            type : 'ajax',
            method :'post',
            url  :'<?php echo site_url('TambahTrip/Delete_Trip')?>',
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

    nomor_stpbs();
    function nomor_stpbs(){
        $.ajax({
            type : 'POST',
            async: false,
            url  : '<?php echo site_url('TambahTrip/nomor_sptbs')?>',
            dataType :'json',
            success:function(data){
                $('input[name=no_sptbs]').val(data.kode);
            }
        });
    }

    var d = new Date();
    var startDate = new Date(),
            noOfDaysToAdd = -1,
            count = 1;

    while(count <= noOfDaysToAdd){
            startDate.setDate(startDate.getDate() - 1);
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
        beforeShowDay: noSundays,
    });
    
    function noSundays(date) {
        return [date.getDay() != 0, ''];
    }
        
</script>

<script>
function myFunction() {
    var x = document.getElementById("BK");
    x.value = x.value.toUpperCase();
}

// function checkOption(obj) {
//     var input = document.getElementById("input");
//     input.disabled = obj.value == "0";
//     if(obj.value == "0"){
//     	document.getElementById("input").value = "0";
//     }else{
//     	document.getElementById("input").value = "";
//     }
//     var menu2 = document.getElementById("tampil2");
//     menu2.disabled = obj.value == "0"; 
    
// }

// function checkOption2(obj) {
// 		 var input2 = document.getElementById("input2");
//     input2.disabled = obj.value == "0"; 
//     if(obj.value == "0"){
//     	document.getElementById("input2").value = "0";
//     }else{
//     	document.getElementById("input2").value = "";
//     }
//      var menu3 = document.getElementById("tampil3");
//     menu3.disabled = obj.value == "0";
// }

// function checkOption3(obj) {
// 	var input3 = document.getElementById("input3");
//     input3.disabled = obj.value == "0";
//     if(obj.value == "0"){
//     	document.getElementById("input3").value = "0";
//     }else{
//     	document.getElementById("input3").value = "";
//     }
// }

</script>
