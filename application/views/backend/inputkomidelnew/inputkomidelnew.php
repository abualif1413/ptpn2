<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/mint-choc/jquery-ui.css" rel="stylesheet" />
<section class="py-5 slowmotion">
    <div class="row">
        <div class="col-md-12">
        <div class="card">
            <div class="card-header">
            <h6 class="text-uppercase mb-0">
                <label class="pull-right">Data SPTBS Sebelum Slip Timbang</label>
            </h6>
            </div>
            <div class="card-body">
            
                <div class="row">

                    <div class="col-md-12">
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
                        <div style="overflow-x:auto;">
                            <br>
                            <form class="form-inline">
                            <div class="form-group">
                                <input type="text" class="form-control" name="from" id="datetimepicker" style="width:140px;" required>
                            </div>
                            &nbsp;&nbsp;&nbsp;
                            <div class="form-group">
                                <input type="text" class="form-control" name="to" id="datetimepicker2" style="width:140px;" required>
                            </div>
                            &nbsp;&nbsp;&nbsp;
                            <div class="form-group">
                                <select class="form-control" name="afdeling" id="dataafdeling" required></select>
                            </div>
                            &nbsp;&nbsp;&nbsp;
                            <a hreff="#" class="btn btn-primary" id="search">Cari</a>&nbsp;&nbsp;&nbsp;
                            </form>
                            <br>
                            <table id="trip" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Berat TBS</th>
                                        <th>Tanggal</th>
                                        <th>SPTBS</th>
                                        <th>N.Polisi Trek</th>
                                        <th>Kerani Kcs</th>
                                        <th>Kebun</th>
                                        <th>Afdeling</th>
                                        <th>Blok No.1</th>
                                        <th>Janjang No.1</th>
                                        <th>Tahun No.1</th>
                                        <th>J.Taksir Brondolan No 1</th>
                                        <th>Blok No.2</th>
                                        <th>Janjang No.2</th>
                                        <th>Tahun No.2</th>
                                        <th>J.Taksir Brondolan No 2</th>
                                        <th>Blok No.3</th>
                                        <th>Janjang No.3</th>
                                        <th>Tahun No.3</th>
                                        <th>J.Taksir Brondolan No 3</th>
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


<script src="<?php echo base_url();?>assets/backend/js/jquery-3.3.1.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript">
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



    function reloadTable() {
        $("#trip").DataTable().ajax.reload(null,false);
    }

    $(document).ready(function() {
        datatableview();
        function datatableview(is_date_search, start_date='', end_date='',afdeling='') {
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
                    url: '<?php echo base_url('Inputkomidelnew/data_komidel_new');?>',
                    type: 'POST',
                    data: {
                        '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
                        is_date_search:is_date_search,
                        from: start_date,
                        to: end_date,
                        afdeling:afdeling,
                        }
                },
                order:[[1,'DESC']],
                columns:[
                    {
                        data:'no',
                        searchable:false,
                        orderable:false,
                    },
                    {
                        data:'button_action',
                        searchable:false,
                        orderable:false,
                    },
                    { data:'tanggal' },
                    { data:'sptbs' },
                    { data:'nomor_polisi_trek' },
                    { data:'keranikcs' },
                    { data:'kebun' },
                    { data:'afdeling' },
                    { data:'blok_1' },
                    { data:'jumlah_janjang_1' },
                    { data:'tahun_tanam_1' },
                    { data:'jumlah_taksir_brondolan_1' },
                    { data:'blok_2' },
                    { data:'jumlah_janjang_2' },
                    { data:'tahun_tanam_2' },
                    { data:'jumlah_taksir_brondolan_2' },
                    { data:'blok_3' },
                    { data:'jumlah_janjang_3' },
                    { data:'tahun_tanam_3' },
                    { data:'jumlah_taksir_brondolan_3' }
                    
                    
                ],
            });
        }

        $('#search').click(function(){
            var start_date = $('input[name=from]').val();
            var end_date = $('input[name=to]').val();
            var afdeling = $('select[name=afdeling]').val();
            if(start_date != '' && end_date !='' && afdeling !='')
            {
                $('#trip').DataTable().destroy();
                datatableview('yes', start_date, end_date, afdeling);
            }
            else
            {
                swal({
                    type: 'warning',
                    title: 'Tanggal dan Afdeling Tidak Boleh Kosong',
                    footer: ''
                });
            }
        }); 

    });

    afdeling();
    function afdeling(){
        $.ajax({
            type  : 'POST',
            url   : '<?php echo base_url()?>Sdhsliptimbang/afdeling',
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
</script>
