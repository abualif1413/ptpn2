<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/mint-choc/jquery-ui.css" rel="stylesheet" />

<section class="py-5 slowmotion">
    <div class="row">
        <div class="col-md-12">
        <div class="card">
            <div class="card-header">
            <h6 class="text-uppercase mb-0">
                <label class="pull-right">Data SPTBS Setelah Slip Timbang</label>
            </h6>
            </div>
            <div class="card-body">
            
                <div class="row">

                    <div class="col-md-12">
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
                                        <th>Tanggal</th>
                                        <th>SPTBS</th>
                                        <th>Nomor Polisi</th>
                                        <th>Kebun</th>
                                        <th>Afdeling</th>
                                        <th>Blok No.1</th>
                                        <th>Tandan No.1</th>
                                        <th>Berat No.1</th>
                                        <th>Blok No.2</th>
                                        <th>Tandan No.2</th>
                                        <th>Berat No.2</th>
                                        <th>Blok No.3</th>
                                        <th>Tandan No.3</th>
                                        <th>Berat No.3</th>
                                        <th>Berat TBS</th>
                                        <th>Brt Brondolan</th>
                                        <th>Berat Total</th>
                                        <th>Brt.Prediksi No.1</th>
                                        <th>Brt.Prediksi No.2</th>
                                        <th>Brt.Prediksi No.3</th>
                                        <th>TotalBeratPrediksi</th>
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
                    url: '<?php echo base_url('Sdhsliptimbang/Data_sdhsliptimbang');?>',
                    type: 'POST',
                    data: {
                        '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
                        is_date_search:is_date_search,
                        from: start_date,
                        to: end_date,
                        afdeling:afdeling,
                    }
                },
                order:[[1,'ASC']],
                columns:[
                    {
                        data:'no',
                        searchable:false,
                        orderable:false,
                    },
                    { 
                        data:'tanggal',
                        searchable:false,
                        orderable:false,
                    },
                    {   
                        data:'sptbs',
                        searchable:false,
                        orderable:false,
                    },
                    {   
                        data:'nomor_polisi_trek',
                        searchable:false,
                        orderable:false,
                    },
                    {   
                        data:'kebun',
                        searchable:false,
                        orderable:false,
                    },
                    {   
                        data:'afdeling',
                        searchable:false,
                        orderable:false,
                    },
                    {   
                        data:'id_blok_no_1',
                        searchable:false,
                        orderable:false,
                    },
                    {   
                        data:'jumlah_janjang_no_1',
                        searchable:false,
                        orderable:false,
                    },
                    { 
                        data:'berat_no_1',
                        searchable:false,
                        orderable:false,
                    },
                    {   
                        data:'id_blok_no_2',
                        searchable:false,
                        orderable:false,
                    },
                    { 
                        data:'jumlah_janjang_no_2',
                        searchable:false,
                        orderable:false,
                    },
                    { 
                        data:'berat_no_2',
                        searchable:false,
                        orderable:false,
                    },
                    {   
                        data:'id_blok_no_3',
                        searchable:false,
                        orderable:false,
                    },
                    {   
                        data:'jumlah_janjang_no_3',
                        searchable:false,
                        orderable:false,
                    },
                    { 
                        data:'berat_no_3',
                        searchable:false,
                        orderable:false,
                    },
                    {  
                        data:'berat_TBS',
                        searchable:false,
                        orderable:false,
                    },
                    {   
                        data:'berat_brondolan',
                        searchable:false,
                        orderable:false,
                    },
                    {   
                        data:'berat_total',
                        searchable:false,
                        orderable:false,
                    },
                    {   
                        data:'brt_prediksi_no_1',
                        searchable:false,
                        orderable:false,
                    },
                    {   
                        data:'brt_prediksi_no_2',
                        searchable:false,
                        orderable:false,
                    },
                    {   
                        data:'brt_prediksi_no_3',
                        searchable:false,
                        orderable:false,
                    },
                    {   
                        data:'total_prediksi',
                        searchable:false,
                        orderable:false,
                    }
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
                datatableview('yes', start_date, end_date,afdeling);
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
