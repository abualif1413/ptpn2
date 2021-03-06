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
                <label class="pull-right">Monitoring Manual</label>
            </h6>
            </div>
            <div class="card-body">
                <div style="overflow-x:auto;">
                    <form method="get">
                        <div class="form-group col-sm-5">
                            <label class="control-label" for="afdeling">Afdeling :</label>
                            <select class="form-control" name="afdeling" id="afdeling">
                                <option value="">- Pilih afdeling -</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-5">
                            <label class="control-label" for="afdeling">Tanggal</label>
                            <input type="text" name="tanggal" id="tanggal" class="form-control" value="" />
                        </div>
                        <hr />
                        <button class="btn btn-primary" type="button" name="tampilkan" id="tampilkan" value="tampilkan" onclick="go_monitor();">Tampilkan Data</button>
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
            url   : '<?php echo base_url()?>MonitoringManual/afdeling',
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
    
    function go_monitor() {
    	var id_afdeling = $("#afdeling").val();
    	var tanggal = $("#tanggal").val();
    	
    	if (id_afdeling == "" || tanggal == "") {
    		alert("Tentukn afdeling dan tanggal");
    	} else {
    		$("#hasil").html("<i>Loading...</i>");
    		$.ajax({
	            type  : 'POST',
	            url   : '<?php echo base_url()?>MonitoringManual/get_data',
	            async : true,
	            dataType : 'json',
	            data	: {id_afdeling:id_afdeling, tanggal:tanggal},
	            success : function(data){
	            	var hasil = "";
	            	$.each(data, function(i,v) {
	            		hasil += "<a href='#' class='list-group-item list-group-item-action flex-column align-items-start'>";
	            			hasil += "<div class='d-flex w-100 justify-content-between'>";
	            				hasil += "<h5 class='mb-1'>Blok : " + v.blok + "</h5>";
	            			hasil += "</div>";
	            			hasil += "<p class='mb-1'>";
	            				hasil += "<table>";
	            					hasil += "<tr>";
	            						hasil += "<td width='250px'>Panen (Jjg)</td>";
	            						hasil += "<td>:</td>";
	            						hasil += "<td>" + accounting.format(v.panen_blok, 2) + "</td>"
	            					hasil += "</tr>";
	            					hasil += "<tr>";
	            						hasil += "<td>Di SPTBS kan (Jjg)</td>";
	            						hasil += "<td>:</td>";
	            						hasil += "<td>" + accounting.format(v.trip_blok, 2) + "</td>"
	            					hasil += "</tr>";
	            					hasil += "<tr>";
	            						hasil += "<td>Restan akhirnya diangkut (Jjg)</td>";
	            						hasil += "<td>:</td>";
	            						hasil += "<td>" + accounting.format(v.restan_diangkut, 2) + "</td>"
	            					hasil += "</tr>";
	            					hasil += "<tr>";
	            						hasil += "<td>Panen Brd (Kg)</td>";
	            						hasil += "<td>:</td>";
	            						hasil += "<td>" + accounting.format(v.brondolan_panen_blok, 2) + "</td>"
	            					hasil += "</tr>";
	            					hasil += "<tr>";
	            						hasil += "<td>Realisasi hari ini (Kg)</td>";
	            						hasil += "<td>:</td>";
	            						hasil += "<td>" + accounting.format(v.kg_hari_ini, 2) + "</td>"
	            					hasil += "</tr>";
	            					hasil += "<tr>";
	            						hasil += "<td>Realisasi dari restan (Kg)</td>";
	            						hasil += "<td>:</td>";
	            						hasil += "<td>" + accounting.format(v.kg_dari_restan, 2) + "</td>"
	            					hasil += "</tr>";
	            					hasil += "<tr>";
	            						hasil += "<td>Realisasi Brd hari ini (Kg)</td>";
	            						hasil += "<td>:</td>";
	            						hasil += "<td>" + accounting.format((v.brondolan_realisasi_all * v.brondolan_panen_blok / v.brondolan_panen_all), 2) + "</td>"
	            					hasil += "</tr>";
	            				hasil += "</table>";
	            			hasil += "</p>"
	            		hasil += "</a>";
	            	});
	            	$("#hasil").html(hasil);
	            }
	        });
    	}
    }

    $(function() {
        afdeling();
    });
</script>