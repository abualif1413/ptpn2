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
                <label class="pull-right">Prestasi Panen per Pemanen</label>
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
                        <div class="form-group col-sm-5">
                            <label class="control-label" for="afdeling">Tanggal</label>
                            <input type="text" name="tanggal" id="tanggal" class="form-control" value="" />
                        </div>
                        <hr />
                        <button class="btn btn-primary" type="button" name="tampilkan" id="tampilkan" value="tampilkan" onclick="go_kg_hasil_panen();">Tampilkan Data</button>
                    </form>
                    <hr />
                    <div id="hasil"></div>
                </div>
            </div>
        </div>
        </div>
    </div>
</section>

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
    
    function go_kg_hasil_panen() {
    	$("#hasil").html("<i>Loading...</i>");
    	var tanggal = $("#tanggal").val();
    	var id_mandor = $("#mandor").val();
    	$.ajax({
            type  		: 'POST',
            url   		: '<?php echo base_url()?>KgPanenPerPemanen/go_kg_hasil_panen',
            async 		: true,
            dataType	: 'json',
            data		: {tanggal:tanggal, id_mandor:id_mandor},
            success 	: function(data){
            	console.log(data);
            	var hasil = "";
            	$.each(data, function(i,v) {
            		if(v.urutan == 1) {
            			hasil += "<a href='#' class='list-group-item list-group-item-action flex-column align-items-start'>";
	            			hasil += "<div class='d-flex w-100 justify-content-between'>";
	            				hasil += "<h5 class='mb-1'>" + v.nama_pemanen + "</h5>";
	            			hasil += "</div>";
	            			hasil += "<p class='mb-1'>";
	            				hasil += "<table class='table table-stripped'>";
	            					hasil += "<thead class='bg-warning'>";
	            						hasil += "<tr>";
	            							hasil += "<th>Blok</th>";
	            							hasil += "<th>TBS (Jjg)</th>";
	            							hasil += "<th>Brd (Kg)</th>";
	            							hasil += "<th>TBS (Realisasi Kg)</th>";
	            							hasil += "<th>Brd (Realisasi Kg)</th>";
	            						hasil += "</tr>";
	            					hasil += "</thead>";
	            					hasil += "<tbody>";
            		} else if(v.urutan == 2) {
	            						hasil += "<tr>";
	            							hasil += "<td>" + v.blok + "</td>";
	            							hasil += "<td>" + accounting.format(v.jmlh_panen) + "</td>";
	            							hasil += "<td>" + accounting.format(v.jmlh_brondolan) + "</td>";
	            							hasil += "<td>" + accounting.format(v.kg_tbs, 2) + "</td>";
	            							hasil += "<td>" + accounting.format(v.kg_brd, 2) + "</td>";
	            						hasil += "</tr>";
            		} else if(v.urutan == 3) {
            						hasil += "</tbody>";
            					hasil += "</table>";
            				hasil += "</p>";
            			hasil += "</a>";
            		}
            	});
            	$("#hasil").html(hasil);
            }
        });
    }

    $(function() {
        afdeling();
    });
</script>