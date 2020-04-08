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
                    <label class="pull-right"><?php echo $title; ?></label>
                </h6>
            </div>
            <div class="card-body">
                <form method="get" action="javascript:void(0);" onsubmit="return get_sptbs_list();">
                    <div class="row">
                        <div class="col-sm-2">Tanggal</div>
                        <div class="col-sm-3">
                            <input type="text" name="tanggal" id="tanggal" class="form-control" value="" />
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-primary" type="submit" name="tampilkan_data">Tampilkan Data</button>
                        </div>
                    </div>
                </form>
                <br /><br />
                <div class="list-group" id="panel_sptbs">

                </div>
            </div>
            </div>
        </div>
    </div>
</section>
<script src="<?php echo base_url();?>assets/backend/js/jquery-3.3.1.js"></script>
<script src="<?php echo base_url();?>assets/backend/js/accounting.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
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

    function get_sptbs_list() {
        var tanggal = $("#tanggal").val();
        $("#panel_sptbs").html("<a href='javascript:void(0);' class='list-group-item list-group-item-action flex-column align-items-start'>Loading...</a>");
        if(tanggal != "") {
            $.ajax({
                async       : "false",
                url         : "<?php echo base_url('Inputkomidelnew020/get_sptbs_list');?>",
                type        : "post",
                dataType    : "json",
                data        : "tanggal=" + tanggal,
                success     : function(r) {
                    var dataSptbs = "<h4>Data SPTBS</h4>";
                    $.each(r, function(i, v) {
                        if(v.status.toLowerCase() == "n") {
                            if(v.jlh_anak > 0) {
                                dataSptbs += "<a href='javascript:void(0);' class='list-group-item list-group-item-action flex-column align-items-start'>";
                                    dataSptbs += "<div class='d-flex w-100 justify-content-between'>";
                                        dataSptbs += "<h5 class='mb-1'>SPTBS : " + v.sptbs + "</h5>";
                                        dataSptbs += "<small>No. Polisi : " + v.nomor_polisi_trek + "</small>";
                                    dataSptbs += "</div>";
                                    dataSptbs += "<p class='mb-1'>";
                                        dataSptbs += "<table>";
                                            dataSptbs += "<tr>";
                                                dataSptbs += "<td width='150px'>TBS (Kg)</td>";
                                                dataSptbs += "<td><input type='text' id='timbang_pks_" + v.id + "' style='width: 100px;' /></td>";
                                                dataSptbs += "<td></td>";
                                            dataSptbs += "</tr>";
                                            dataSptbs += "<tr>";
                                                dataSptbs += "<td>Brondolan (Kg)</td>";
                                                dataSptbs += "<td><input type='text' id='timbang_brd_" + v.id + "' style='width: 100px;' /></td>";
                                                dataSptbs += "<td><button class='' onclick='get_detail_sptbs_list(" + v.id + ")'>Lanjut</button></td>";
                                            dataSptbs += "</tr>";
                                        dataSptbs += "</table>";
                                    dataSptbs += "</p>";
                                dataSptbs += "</a>";
                            } else {
                                dataSptbs += "<a href='javascript:void(0);' class='list-group-item list-group-item-action flex-column align-items-start'>";
                                    dataSptbs += "<div class='d-flex w-100 justify-content-between'>";
                                        dataSptbs += "<h5 class='mb-1'>SPTBS : " + v.sptbs + "</h5>";
                                        dataSptbs += "<small>No. Polisi : " + v.nomor_polisi_trek + "</small>";
                                    dataSptbs += "</div>";
                                    dataSptbs += "<p class='mb-1'>";
                                        dataSptbs += "<table>";
                                            dataSptbs += "<tr>";
                                                dataSptbs += "<td width='150px'>TBS (Kg)</td>";
                                                dataSptbs += "<td width='100px'><input type='text' id='timbang_pks_" + v.id + "' style='width: 100px;' value='0' readonly /></td>";
                                                dataSptbs += "<td></td>";
                                            dataSptbs += "</tr>";
                                            dataSptbs += "<tr>";
                                                dataSptbs += "<td>Brondolan (Kg)</td>";
                                                dataSptbs += "<td><input type='text' id='timbang_brd_" + v.id + "' style='width: 100px;' /></td>";
                                                dataSptbs += "<td><button class='' onclick='proses_dan_simpan_hanya_brondolan(" + v.id + ")'>Proses dan Simpan</button></td>";
                                            dataSptbs += "</tr>";
                                            dataSptbs += "<tr>";
                                                dataSptbs += "<td colspan='3' style='padding-top: 15px;'><small class='alert alert-warning'>Data trip / SPTBS ini tidak memiliki data janjang. Ada indikasi bahwa trip / SPTBS ini hanya mengangkut brondolan.</small></td>";
                                            dataSptbs += "</tr>";
                                        dataSptbs += "</table>";
                                    dataSptbs += "</p>";
                                dataSptbs += "</a>";
                            }
                            
                        } else {
                            if(v.jlh_anak > 0) {
                                dataSptbs += "<a href='javascript:void(0);' class='list-group-item list-group-item-action list-group-item-primary flex-column align-items-start'>";
                                    dataSptbs += "<div class='d-flex w-100 justify-content-between'>";
                                        dataSptbs += "<h5 class='mb-1'>SPTBS : " +
                                                        v.sptbs + "<br />" +
                                                        "<small>TBS : " + accounting.format(v.timbang_pks, 2) + "</small><br />" +
                                                        "<small>Brondolan : " + accounting.format(v.timbang_brd, 2) + "</small>" +
                                                    "</h5>";
                                        dataSptbs += "<small>No. Polisi : " + v.nomor_polisi_trek + "</small>";
                                    dataSptbs += "</div><br />";
                                    dataSptbs += "<p class='mb-1'>";
                                        $.each(v.detail, function(i_det, v_det) {
                                        	var tgl_restan = (v_det.tgl_restan != null) ? v_det.tgl_restan : "";
                                            dataSptbs += "<table>";
                                                dataSptbs += "<tr>";
                                                    dataSptbs += "<td width='150px'>Blok</td>";
                                                    dataSptbs += "<td><b>: " + v_det.blok + " (TT : " + v_det.tahun_tanam + ")" + "</b></td>";
                                                dataSptbs += "</tr>";
                                                dataSptbs += "<tr>";
                                                    dataSptbs += "<td width='150px'>Hasil (Kg)</td>";
                                                    dataSptbs += "<td><b>: " + accounting.format(v_det.hasil_kg, 2) + "</b></td>";
                                                dataSptbs += "</tr>";
                                                dataSptbs += "<tr>";
                                                    dataSptbs += "<td width='150px'>Hasil Restan (Kg)</td>";
                                                    dataSptbs += "<td><b>: " + accounting.format(v_det.hasil_kg_restan, 2) + "<br />Tgl : " + tgl_restan + "</b></td>";
                                                dataSptbs += "</tr>";
                                            dataSptbs += "</table><hr />";
                                        });
                                    dataSptbs += "</p>";
                                dataSptbs += "</a>";
                            } else {
                                dataSptbs += "<a href='javascript:void(0);' class='list-group-item list-group-item-action list-group-item-primary flex-column align-items-start'>";
                                    dataSptbs += "<div class='d-flex w-100 justify-content-between'>";
                                        dataSptbs += "<h5 class='mb-1'>SPTBS : " +
                                                        v.sptbs + "<br />" +
                                                        "<small>TBS : " + accounting.format(v.timbang_pks, 2) + "</small><br />" +
                                                        "<small>Brondolan : " + accounting.format(v.timbang_brd, 2) + "</small>" +
                                                    "</h5>";
                                        dataSptbs += "<small>No. Polisi : " + v.nomor_polisi_trek + "</small>";
                                    dataSptbs += "</div><br />";
                                    dataSptbs += "<p class='alert alert-warning'><small>Data trip / SPTBS ini tidak memiliki data janjang. Ada indikasi bahwa trip / SPTBS ini hanya mengangkut brondolan.</small></p>";
                                dataSptbs += "</a>";
                            }
                        }
                        
                    });
                    $("#panel_sptbs").fadeOut(300, function() {
                        $("#panel_sptbs").html(dataSptbs);
                        $("#panel_sptbs").fadeIn(300);
                    });
                }
            });
        } else {
            alert("Pilih tanggal dahulu");
        }

        return false;
    }

    function get_detail_sptbs_list(id_trip) {
        var timbang_pks = $("#timbang_pks_" + id_trip).val();
        var timbang_brd = $("#timbang_brd_" + id_trip).val();
        if(timbang_pks == "" || timbang_pks == "0" || !$.isNumeric(timbang_pks) || timbang_brd == "" || !$.isNumeric(timbang_brd)) {
            alert("Isikan angka hasil penimbangan PKS untuk TBS dan brondolan");
        } else {
            $("#panel_sptbs").html("<a href='javascript:void(0);' class='list-group-item list-group-item-action flex-column align-items-start'>Loading...</a>");
            $.ajax({
                async       : "false",
                url         : "<?php echo base_url('Inputkomidelnew020/get_detail_sptbs_list');?>",
                type        : "post",
                dataType    : "json",
                data        : "id_trip=" + id_trip + "&timbang_pks=" + timbang_pks + "&timbang_brd=" + timbang_brd,
                success     : function(r) {
                    var dataSptbs = "<h4>Data rincian SPTBS<br />" +
                                        "<small>TBS (KG) : " + accounting.format(timbang_pks, 2) + "</small><br />" +
                                        "<small>Brondolan (KG) : " + accounting.format(timbang_brd, 2) + "</small>" +
                                    "</h4>";
                    $.each(r, function(i, v) {
                    	var tgl_restan = (v.tgl_restan != null) ? v.tgl_restan : "";
                        dataSptbs += "<a href='#' class='list-group-item list-group-item-action flex-column align-items-start'>";
                            dataSptbs += "<div class='d-flex w-100 justify-content-between'>";
                                dataSptbs += "<h5 class='mb-1'>Blok : " + v.blok + " (TT : " + v.tahun_tanam + ")</h5>";
                                dataSptbs += "<small>" + v.nama_kebun + " - " + v.nama_afdeling + "</small>";
                            dataSptbs += "</div>";
                            dataSptbs += "<p class='mb-1'>";
                                dataSptbs += "<table>";
                                    dataSptbs += "<tr>";
                                        dataSptbs += "<td width='100px'>Jlh Janjang</td>";
                                        dataSptbs += "<td><b>: " + accounting.format(v.jumlah_janjang, 2) + "</b></td>";
                                        dataSptbs += "<td width='3px'></td>";
                                        dataSptbs += "<td width='150px'>Jlh Restan</td>";
                                        dataSptbs += "<td><b>: " + accounting.format(v.jumlah_restan, 2) + " Tgl : " + tgl_restan + "</b></td>";
                                    dataSptbs += "</tr>";
                                    dataSptbs += "<tr>";
                                        dataSptbs += "<td width='100px'>Hasil (Kg)</td>";
                                        dataSptbs += "<td><b>: " + accounting.format(v.hasil, 2) + "</b></td>";
                                        dataSptbs += "<td width='3px'></td>";
                                        dataSptbs += "<td width='150px'>Hasil Restan (Kg)</td>";
                                        dataSptbs += "<td><b>: " + accounting.format(v.hasil_restan, 2) + "</b></td>";
                                    dataSptbs += "</tr>";
                                dataSptbs += "</table>";
                            dataSptbs += "</p>";
                        dataSptbs += "</a>";
                    });
                    dataSptbs += "<div style='height: 10px;'>&nbsp;</div><button class='btn btn-primary' onclick='proses_dan_simpan();' style='width: 200px;'>Proses dan Simpan</button>";
                    dataSptbs += "<div style='height: 10px;'>&nbsp;</div><button class='btn btn-warning' onclick='get_sptbs_list();' style='width: 200px;'>Kembali</button>";
                    dataSptbs += "<div style='height: 10px;'>&nbsp;</div><div id='panel_hasil' style='display:none;'></div>";
                    $("#panel_sptbs").fadeOut(300, function() {
                        $("#panel_sptbs").html(dataSptbs);
                        $("#panel_hasil").html(JSON.stringify(r));
                        $("#panel_sptbs").fadeIn(300);
                    });
                }
            });
        }
    }

    function proses_dan_simpan() {
        if(confirm("Anda yakin akan memproses data ini?")) {
            var data = $("#panel_hasil").html();
            $.ajax({
                async       : "false",
                url         : "<?php echo base_url('Inputkomidelnew020/proses_dan_simpan');?>",
                type        : "post",
                dataType    : "text",
                data        : "data=" + data,
                success     : function(r) {
                    alert("Data telah selesai diproses");
                    get_sptbs_list();
                }
            });
        }
    }

    function proses_dan_simpan_hanya_brondolan(id_trip) {
        var timbang_pks = $("#timbang_pks_" + id_trip).val();
        var timbang_brd = $("#timbang_brd_" + id_trip).val();

        if(!$.isNumeric(timbang_brd) || timbang_brd == "0") {
            alert("Isikan jumlah Kg Brondolan");
        } else {
            if(confirm("Anda yakin akan memproses data ini?")) {
                $.ajax({
                    async       : "false",
                    url         : "<?php echo base_url('Inputkomidelnew020/proses_dan_simpan_hanya_brondolan');?>",
                    type        : "post",
                    dataType    : "text",
                    data        : "id_trip=" + id_trip + "&timbang_pks=" + timbang_pks + "&timbang_brd=" + timbang_brd,
                    success     : function(r) {
                        alert("Data telah selesai diproses");
                        get_sptbs_list();
                    }
                });
            }
        }
    }
</script>