<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/mint-choc/jquery-ui.css" rel="stylesheet" />
<section class="py-5 slowmotion">
    <div class="row">
        <div class="col-md-12">
            <form method="get" autocomplete="off">
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
            <br />
            <div class="list-group" style="display:none;" id="panel_data_absen">
                <?php
                    foreach($dataAbsen as $data) {
                        $status_class = "";
                        switch(strtolower($data["kehadiran"])) {
                            case "h" :
                                $status_class = "list-group-item-primary";
                                break;
                            case "n/a" :
                                $status_class = "list-group-item-warning";
                                break;
                            default :
                                $status_class = "list-group-item-secondary";
                                break;
                        }
                ?>
                        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start <?php echo $status_class; ?>">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1"><?php echo $data["nama_pemanen"]; ?></h5>
                                <small><?php echo $data["kehadiran"]; ?> - <?php echo $data["jam"]; ?></small>
                            </div>
                            <!--<p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
                            <small>Donec id elit non mi porta.</small>-->
                        </a>
                <?php
                    }
                ?>
            </div>
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

    $(function() {
        $("#panel_data_absen").fadeIn(300);
    })
</script>