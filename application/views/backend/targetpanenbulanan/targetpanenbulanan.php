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
                <label class="pull-right">Data Target Panen Bulanan</label>
            </h6>
            </div>
            <div class="card-body">
                <div style="overflow-x:auto;">
                    <form method="get">
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
                        <div class="form-group col-sm-5">
                            <label class="control-label" for="afdeling">Afdeling :</label>
                            <select class="form-control" name="afdeling" id="afdeling" onchange="bersihkan();">
                                <option value="">- Pilih afdeling -</option>
                            </select>
                        </div>
                        <hr />
                        <button class="btn btn-primary" type="button" name="tampilkan" id="tampilkan" value="tampilkan">Tampilkan data target panen</button>
                        <button class="btn btn-success" id="slide_show" type="button">Slide show monitoring</button>
                    </form>
                    <hr />
                    <table border="0" cellpadding="5" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="200px">Blok</th>
                                <th width="200px">Tahun Tanam</th>
                                <th width="200px">Target Panen (Kg)</th>
                            </tr>
                        </thead>
                        <tbody id="panel_input">

                        </tbody>
                    </table>
                    <hr />
                    <button class="btn btn-primary" id="simpan_data" type="button" style="display: none;">Simpan data target panen</button>
                </div>
            </div>
        </div>
        </div>
    </div>
</section>

<script src="<?php echo base_url();?>assets/backend/js/jquery-3.3.1.js"></script>
<script type="text/javascript">
    function afdeling(){
        $.ajax({
            type  : 'POST',
            url   : '<?php echo base_url()?>TargetPanenBulanan/afdeling',
            async : true,
            dataType : 'json',
            success : function(data){
            console.log(data);
                var html = '<option value="">- Pilih afdeling -</option>';
                html += '<option value="0">Semua Kebun</option>';
                var i;
                for(i=0; i<data.length; i++){
                    html += '<optgroup label="' + data[i].nama_kebun + '">';
                    for(var j=0; j<data[i].afdeling.length; j++) {
                        html += '<option value="'+data[i].afdeling[j].id+'">'+data[i].afdeling[j].nama_afdeling+'</option>';
                    }
                    html += '</optgroup>';
                }
                $('#afdeling').html(html);
            }
        });
    }

    function bulan() {
        $.ajax({
            type  : 'POST',
            url   : '<?php echo base_url()?>TargetPanenBulanan/bulan',
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

    function tampil() {
        var bulan = $("#bulan").val();
        var tahun = $("#tahun").val();
        var afdeling = $("#afdeling").val();

        if(bulan == "" || tahun == "" || afdeling == "") {
            alert("Isikan pada bulan dan tahun berapa serta afdeling mana yang akan diinput data target panennya");
        } else {
            $.ajax({
                type  : 'POST',
                url   : '<?php echo base_url()?>TargetPanenBulanan/tampilkan',
                async : true,
                data  : "bulan=" + bulan + "&tahun=" + tahun + "&afdeling=" + afdeling,
                dataType : 'json',
                success : function(data){
                    console.log(data);
                    var str_panel = "";
                    $.each(data, function(i, v) {
                        var str = "<tr>" +
                            "<td>Blok : " + v.blok + "</td>" +
                            "<td>" + v.tahun_tanam + "</td>" +
                            "<td><input type='text' class='form-control input_target' id_blok='" + v.id + "' value='" + v.target_panen + "' onblur='cek_numerik(this, " + v.target_panen + ")' /></td>" +
                        "</tr>";
                        str_panel += str;
                    });
                    $("#panel_input").html(str_panel);
                    $("#simpan_data").show();
                    $("#simpan_data").removeAttr("disabled");
                }
            });
        }
    }

    function go_slide_show() {
        var bulan = $("#bulan").val();
        var tahun = $("#tahun").val();
        var afdeling = $("#afdeling").val();

        if(bulan == "" || tahun == "" || afdeling == "") {
            alert("Isikan pada bulan dan tahun berapa serta afdeling mana yang akan diinput data target panennya");
        } else {
            var url_slide_show = "<?php echo base_url() . "SlideShowMonitoringPanen" ?>?bulan=" + bulan + "&tahun=" + tahun + "&afdeling=" + afdeling;
            window.open(url_slide_show);
        }
    }

    function bersihkan() {
        $("#panel_input").html("");
        $("#simpan_data").hide();
    }

    function cek_numerik(elemen, nilai_default) {
        var nilai = $(elemen).val();
        if(!$.isNumeric(nilai)) {
            $(elemen).val(nilai_default);
        }
    }

    function go_simpan() {
        $("#simpan_data").attr("disabled", "disabled");
        var data_input = [];
        $(".input_target").each(function() {
            var elemen = $(this);
            var bulan = $("#bulan").val();
            var tahun = $("#tahun").val();
            var id_blok = $(elemen).attr("id_blok");
            var target = $(elemen).val();
            var data_input_temp = {"bulan" : bulan, "tahun" : tahun, "id_blok" : id_blok, "target_panen" : target};
            data_input.push(data_input_temp);
        });
        var data_input_to_string = JSON.stringify(data_input);
        //alert(data_input_to_string);
        $.ajax({
            type  : 'POST',
            url   : '<?php echo base_url()?>TargetPanenBulanan/go_simpan',
            async : true,
            data  : "data_input_to_string=" + data_input_to_string,
            dataType : 'text',
            success : function(data){
                alert("Data target panen telah disimpan.");
                tampil();
            }
        });
    }

    $(function() {
        afdeling();
        bulan();
    });

    $("#tampilkan").click(function() {
        tampil();
    });

    $("#slide_show").click(function() {
        go_slide_show();
    });

    $("#simpan_data").click(function() {
        go_simpan();
    })
</script>