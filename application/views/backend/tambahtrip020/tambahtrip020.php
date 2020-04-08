<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/mint-choc/jquery-ui.css" rel="stylesheet" />
<style type="text/css">
    #panel_input tr:nth-child(odd) {
        background-color: #F9F9F9;
    }
</style>
<section class="py-5 slowmotion">
    <div class="row">
        <div class="col-md-12">
            <form method="get" action="" autocomplete="off">
                <div class="row">
                    <div class="col-sm-2">Tanggal</div>
                    <div class="col-sm-3">
                        <input type="text" name="tanggal" id="tanggal" class="form-control" value="<?php echo $tanggal; ?>" />
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-primary" type="submit" name="tampilkan_data">Tampilkan Data</button>
                    </div>
                </div>
            </form>
            <hr />
            <?php
                if($tanggal != "") {
            ?>
                    <br />
                    <div class="list-group" id="panel_data_panen">
                        <?php
                            $no = 0;
                            foreach($dataTampil as $dt) {
                                if($dt["id_detail"] > 0) {
                                ?>
                                    <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1"><?php echo $dt["sptbs"]; ?></h5>
                                            <small>Blok : <?php echo $dt["blok"]; ?> - Thn Tanam <?php echo $dt["tahun_tanam"]; ?></small>
                                        </div>
                                        <p class="mb-1">
                                            <table>
                                            <tr>
                                                <td width="80px">Restan</td>
                                                <?php
                                                	if($dt["jumlah_restan"] > 0) {
                                                ?>
                                                		<td><b>: <?php echo $dt["jumlah_restan"]; ?></b> (<i>Tgl : <?php echo $dt["tgl_restan"]; ?></i>)</td>
                                                <?php
                                                	} else {
                                                ?>
                                                		<td><b>: <?php echo $dt["jumlah_restan"]; ?></b></td>
                                                <?php
                                                	}
                                                ?>
                                                
                                            </tr>
                                            <tr>
                                            <td>Janjang</td>
                                                <td><b>: <?php echo $dt["jumlah_janjang"]; ?></b></td>
                                            </tr>
                                            </table>
                                        </p>
                                        <small>Nomor Polisi <b>: <?php echo $dt["nomor_polisi_trek"]; ?></b>. Dan jumlah berondolan yang diangkut pada trip ini adalah <b><?php echo $dt["jumlah_brondolan"]; ?> Kg</b></small>
                                    </a>                                    
                                <?php
                                } else {
                                ?>
                                    <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1"><?php echo $dt["sptbs"]; ?></h5>
                                        </div>
                                        <small>Nomor Polisi <b>: <?php echo $dt["nomor_polisi_trek"]; ?></b>. Dan jumlah berondolan yang diangkut pada trip ini adalah <b><?php echo $dt["jumlah_brondolan"]; ?> Kg</b></small>
                                    </a>
                                <?php
                                }
                            }
                        ?>   
                    </div>
            <?php
                }
            ?>
        </div>
    </div>
</section>
<script src="<?php echo base_url();?>assets/backend/js/jquery-3.3.1.js"></script>
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
</script>