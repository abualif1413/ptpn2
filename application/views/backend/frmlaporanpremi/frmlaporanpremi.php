<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/mint-choc/jquery-ui.css" rel="stylesheet" />
<style type="text/css">
    #panel_input tr:nth-child(odd) {
        background-color: #F9F9F9;
    }
</style>
<section class="py-5 slowmotion">
    <div class="row">
        <div class="col-md-12">
        <div class="card">
            <div class="card-header">
            <h6 class="text-uppercase mb-0">
                <label class="pull-right">Laporan Premi Panen</label>
            </h6>
            </div>
            <div class="card-body">
                <div style="overflow-x:auto;">
                    <form method="get">
                        <div class="form-group col-sm-5">
                            <label class="control-label" for="afdeling">Afdeling :</label>
                            <select class="form-control" name="afdeling" id="afdeling" onchange="get_mandor();">
                                <option value="">- Pilih afdeling -</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-5">
                            <label class="control-label" for="mandor">Mandor :</label>
                            <select class="form-control" name="mandor" id="mandor">
                                <option value="">- Pilih mandor -</option>
                            </select>
                        </div>
                        <br />
                        <hr />
                        <br />
                        <h5>Untuk laporan rincian per hari, Klik disini</h5>
                        <div class="form-group col-sm-5">
                            <label class="control-label" for="afdeling">Tanggal</label>
                            <input type="text" name="tanggal" id="tanggal" class="form-control" value="" />
                        </div>
                        <button class="btn btn-primary btn-sm" type="button" onclick="go_harian();">Tampilkan Laporan</button>
                        <br />
                        <hr />
                        <br />
                        <h5>Untuk laporan rekap per bulan, Klik disini</h5>
                        <div class="form-group col-sm-5">
                            <label class="control-label" for="bulan">Bulan :</label>
                            <select class="form-control" name="bulan" id="bulan" onchange="bersihkan();">
                                <option value="">- Pilih bulan -</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-5">
                            <label class="control-label" for="tahun">Tahun :</label>
                            <input type="text" name="tahun" id="tahun" class="form-control" onblur="bersihkan();" />
                        </div>
                        <button class="btn btn-primary btn-sm" type="button" onclick="go_bulanan();">Tampilkan Laporan</button>
                    </form>
                    <hr />
                    <div id="hasil" style="font-size: 80%;"></div>
                </div>
            </div>
        </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div id="modalLoading" class="modal fade" role="dialog">
	<div class="modal-dialog">
	
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-body">
				<p><i class="fa fa-spinner fa-spin" style="font-size:36px; font-weight: bold;">&nbsp;</i>&nbsp;&nbsp;
					Harap menunggu. Data hasil per pemanen sedang disimpan dan diproses
				</p>
			</div>
		</div>
	
	</div>
</div>

<script src="<?php echo base_url();?>assets/backend/js/jquery-3.3.1.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="<?php echo base_url();?>assets/backend/js/accounting.min.js"></script>
<script type="text/javascript">
	$('#tanggal').datepicker({
        defaultDate: "+1d",
        dateFormat: 'yy-mm-dd',
        showOtherMonths: false,
        changeMonth: false,
        selectOtherMonths: false,
        required: true,
        showOn: "focus",
        numberOfMonths: 1
    });
    
    function afdeling(){
        $.ajax({
            type  : 'POST',
            url   : '<?php echo base_url()?>KgPanenPerPemanen/afdeling',
            async : true,
            dataType : 'json',
            success : function(data){
            console.log(data);
                var html = '<option value="">- Pilih afdeling -</option>';
                var i;
                for(i=0; i<data.length; i++){
                    html += '<optgroup label="' + data[i].nama_kebun + '">';
                    for(var j=0; j<data[i].afdeling.length; j++) {
                        html += '<option value="'+data[i].afdeling[j].id+'">' + data[i].nama_kebun + ' - ' + data[i].afdeling[j].nama_afdeling + '</option>';
                    }
                    html += '</optgroup>';
                }
                $('#afdeling').html(html);
            }
        });
    }
    
    function get_mandor() {
    	var id_afdeling = $("#afdeling").val();
    	$('#mandor').html("<option value=''>- Pilih mandor -</option>");
    	$.ajax({
            type  		: 'POST',
            url   		: '<?php echo base_url()?>KgPanenPerPemanen/mandor',
            async 		: true,
            dataType	: 'json',
            data		: {id_afdeling:id_afdeling},
            success 	: function(data){
            	console.log(data);
                var html = '<option value="">- Pilih mandor -</option>';
                $.each(data, function(i, v) {
                	html += "<option value='" + v.id + "'>" + v.nama_lengkap + "</option>";
                });
                $('#mandor').html(html);
            }
        });
    }
    
    function bulan() {
        $.ajax({
            type  : 'POST',
            url   : '<?php echo base_url()?>FrmLaporanPremi/bulan',
            async : true,
            dataType : 'json',
            success : function(data){
            console.log(data);
                var html = '<option value="">- Pilih bulan -</option>';
                var i;
                for(i=0; i<data.length; i++){
                    html += '<option value="'+data[i].angka+'">'+data[i].nama+'</option>';
                }
                $('#bulan').html(html);
            }
        });
    }
    
    function go_harian() {
    	var id_mandor = $("#mandor").val();
    	var tanggal = $("#tanggal").val();
    	
    	window.open("LapPremiPanenHarian?id_mandor=" + id_mandor + "&tanggal=" + tanggal);
    }
    
    function go_bulanan() {
    	var id_mandor = $("#mandor").val();
    	var bulan = $("#bulan").val();
    	var tahun = $("#tahun").val();
    	
    	window.open("LapPremiPanenBulanan?id_mandor=" + id_mandor + "&bulan=" + bulan + "&tahun=" + tahun);
    }
    
    $(function() {
        afdeling();
        bulan();
    });
</script>