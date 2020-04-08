<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/mint-choc/jquery-ui.css" rel="stylesheet" />

<section class="py-5 slowmotion">
    <div class="row">

      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h6 class="text-uppercase mb-0">
            Data Nilai Komidel
            </h6>
          </div>
          <div class="card-body">
          <div class="alert alert-error" role="alert" style="display:none; background-color:red;color:#fff;"></div>
           <div style="overflow-x:auto;">
           <br>
           <form class="form-inline">
              <div class="form-group">
                <input type="text" class="form-control" name="tanggal" id="datetimepicker" style="width:140px;">
              </div>
              
              &nbsp;&nbsp;&nbsp;
              <div class="form-group">
                <select class="form-control" name="afdeling" id="dataafdeling"></select>
              </div>
              &nbsp;&nbsp;&nbsp;
              <a hreff="#" class="btn btn-primary" onclick="komidel()">Cari</a>&nbsp;&nbsp;&nbsp;
              <a hreff="#" class="btn btn-primary" id="btnSave">Validasi</a>
            </form>
            <br>

            <?php echo form_open('', array('id' => 'myForm')); ?>
            <table id="mydata" class="table table-striped table-bordered" style="width:100%">
                  <thead>
                      <tr>
                        <th>Tanggal</th>
                        <th>Kebun</th>
                        <th>Afdeling</th>
                        <th>Blok</th>
                        <th>Total Janjang Blok</th>
                        <th>Total Berat TBS Blok (Kg)</th>
                        <th>Komidel</th>
                        <th>Tahun Tanam</th>
                        <th>Total Brondolan Taksir</th>
                        <th>Total Brondolan Timbang</th>
                        <th>Koef Proporsi (%)</th>
                      </tr>
                  </thead>
                  <tbody id="show_data">
                  </tbody>
              </table>
              <?php echo form_close(); ?>
              </div>  
          </div>
        </div>
      </div>
    </div>
  </section>


<script src="<?php echo base_url();?>assets/backend/js/jquery-3.3.1.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
<script type="text/javascript">

    function formatCurrency(amount) {
        var i = parseFloat(amount);
        if (isNaN(i)) {
            i = 0.00;
        }
        var minus = '';
        if (i < 0) {
            minus = '-';
        }
        i = Math.abs(i);
        i = parseInt((i + .005) * 100);
        i = i / 100;
        s = new String(i);
        if (s.indexOf('.') < 0) {
            s += '.00';
        }
        if (s.indexOf('.') == (s.length - 2)) {
            s += '0';
        }
        s = minus + s;
        return s;
    }

    function komidel(){
        $.ajax({
            type  : 'POST',
            url   : '<?php echo base_url()?>DataKomidel/data_komidel',
            async : true,
            dataType : 'json',
            data:{
                tanggal: $('input[name=tanggal]').val(),
                afdeling: $('select[name=afdeling]').val(),
            },
            success : function(data){
                var html = '';
                var i;
                for(i=0; i<data.length; i++){
                    html += '<tr>'+
                    '<td>'+data[i].tanggal+'</td>'+
                              '<td>'+data[i].kebun+'</td>'+
                              '<td>'+data[i].afdeling+'</td>'+
                              '<td>'+data[i].blok+'</td>'+
                              '<td>'+data[i].total_janjang_blok+'</td>'+
                              '<td>'+formatCurrency(data[i].total_berat_blok)+'</td>'+
                              '<td>'+formatCurrency(data[i].komidel)+'<input type="hidden" name="id_kerani_askep[]" value="'+data[i].id_kerani_askep+'"><input type="hidden" name="id_kerani_kcs[]" value="'+data[i].id_kerani_kcs+'"><input type="hidden" name="id_kebun[]" value="'+data[i].id_kebun+'"><input type="hidden" name="id_afdeling[]" value="'+data[i].id_afdeling+'"><input type="hidden" name="id_blok[]" value="'+data[i].id_blok+'"><input type="hidden" name="total_janjang_blok[]" value="'+formatCurrency(data[i].total_janjang_blok)+'"><input type="hidden" name="total_berat_blok[]" value="'+formatCurrency(data[i].total_berat_blok)+'"><input type="hidden" name="komidel[]" value="'+formatCurrency(data[i].komidel)+'"><input type="hidden" name="tanggal[]" value="'+data[i].tanggal+'"><input type="hidden" name="tahun_tanam[]" value="'+data[i].tahun_tanam+'"><input type="hidden" name="bron_tak[]" value="'+formatCurrency(data[i].bron_tak)+'"><input type="hidden" name="bron_tim[]" value="'+formatCurrency(data[i].bron_tim)+'"><input type="hidden" name="koef[]" value="'+formatCurrency(data[i].koef)+'"></td>'+
                              '<td>'+data[i].tahun_tanam+'</td>'+
                              '<td>'+formatCurrency(data[i].bron_tak)+'</td>'+
                              '<td>'+formatCurrency(data[i].bron_tim)+'</td>'+
                              '<td>'+formatCurrency(data[i].koef)+'%</td>'+
                            '</tr>';
                }
                $('#show_data').html(html);
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


    
    $('#btnSave').click(function () {
      var data = new FormData($('#myForm')[0]);
      $('#btnSave').html('<i class="fa fa-save"></i>&nbsp; Simpan <img width="30" heigth="30" src="<?php echo base_url();?>assets/backend/img/loading.gif">').attr('disabled', 'disabled');
      $.ajax({
          type : 'ajax',
          method:'POST',
          url  : '<?php echo base_url()?>DataKomidel/Validasi_komidel',
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
                          if (response.type=='Add') {
                              var type = 'Add';
                              swal({
                                type: 'success',
                                title: 'Berhasil Validasi',
                                footer: ''
                              });
                              $('#btnSave').html('<i class="fa fa-save"></i>&nbsp;Validasi');
                          }else if(response.type=='Update'){
                              var type = 'Update';
                              swal({
                                type: 'success',
                                title: 'Berhasil Update',
                                footer: ''
                              });
                              $('#btnSave').html('<i class="fa fa-save"></i>&nbsp;Update');
                          }
                        }else {

                      if (response.error) {
                          $('.alert-error').html(response.error).fadeIn().delay(10000).fadeOut('slow');
                          $('#btnSave').html('<i class="fa fa-save"></i>&nbsp;Validasi');
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
              $('#btnSave').html('<i class="fa fa-save"></i>&nbsp;Validasi');
              $('#btnSave').prop('disabled', false);
          }
      });
  });


  afdeling();
  function afdeling(){
        $.ajax({
            type  : 'POST',
            url   : '<?php echo base_url()?>DataKomidel/afdeling',
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
</script>