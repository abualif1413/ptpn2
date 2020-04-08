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
                <label class="pull-right">Tambah Data Trip Temporary</label>
            </h6>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Blok</th>
                            <th width="300px">Jlh. Janjang</th>
                            <th width="300px">Restan Awal</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($result as $res) {
                    ?>
                        <tr class="data_detail" id_blok="<?php echo $res["id"]; ?>">
                            <td>Blok <?php echo $res["blok"]; ?></td>
                            <td>
                                <input type="text" name="jmlh_janjang_<?php echo $res["id"]; ?>" id="jmlh_janjang_<?php echo $res["id"]; ?>"
                                    value="<?php echo $res["jmlh_panen"]; ?>"
                                    batas="<?php echo $res["jmlh_panen"]; ?>"
                                    onblur="val_input(this);"
                                    class="form-control" />
                            </td>
                            <td>
                                <input type="text" name="jmlh_restan_<?php echo $res["id"]; ?>" id="jmlh_restan_<?php echo $res["id"]; ?>"
                                    value="" class="form-control" />
                            </td>
                        </tr>            
                    <?php
                        }
                    ?>
                    </tbody>
                </table>
                <hr />
                <form method="post" action="<?php echo site_url('TambahTrip020Insert/GoInsert'); ?>" id="frm_sptbs">
                    <div class="row">
                        <div class="col-sm-2">No. SPTBS</div>
                        <div class="col-sm-3">
                            <input type="text" name="no_sptbs" id="no_sptbs" class="form-control" />
                        </div>
                        <div class="col-sm-2">No. Polisi</div>
                        <div class="col-sm-2">
                            <input type="text" name="no_polisi" id="no_polisi" class="form-control" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">Brondolan (Kg)</div>
                        <div class="col-sm-3">
                            <input type="text" name="brondolan" id="brondolan" class="form-control" />
                        </div>
                    </div>
                    <input type="hidden" name="tanggal" id="tanggal" value="<?php echo $tanggal; ?>" />
                    <input type="hidden" name="detail" id="detail" value="" />
                </form>
                <hr />
                <button type="button" class="btn btn-primary" onclick="go_save();">Simpan</button>
            </div>
        </div>
        </div>
    </div>
</section>
<script src="<?php echo base_url();?>assets/backend/js/jquery-3.3.1.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript">
   function go_save() {
        var data_detail = [];
        $(".data_detail").each(function() {
            var id_blok = $(this).attr("id_blok");
            var jmlh_janjang = $("#jmlh_janjang_" + id_blok).val();
            var jmlh_restan = $("#jmlh_restan_" + id_blok).val();
            var temp_data = {"id_blok": id_blok, "jmlh_janjang": jmlh_janjang, "jmlh_restan": jmlh_restan};
            data_detail.push(temp_data);
        });
        var strData = JSON.stringify(data_detail);
        $("#detail").val(strData);
        $("#frm_sptbs").submit();
   }
   function val_input(elm) {
        var nilai = $(elm).val();
        var kembali = $(elm).attr("batas");
        if($.isNumeric(nilai)) {
            if(parseFloat(nilai) < parseFloat(kembali)) {
                kembali = nilai;
            }
        }
        $(elm).val(kembali);
   }
</script>