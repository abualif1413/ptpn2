<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo $title; ?></title>
    <link href="https://fonts.googleapis.com/css?family=Noto+Serif&display=swap" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css?family=PT+Sans&display=swap" rel="stylesheet"> 
    <style type="text/css">
        body {
            background: url("<?php echo base_url();?>assets/backend/img/bg-monitoring.jpg");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center; 
            background-size: cover;
            margin: 0px;
            font-family: 'Noto Serif', serif;
        }

        .judul_besar {
            font-weight: bold;
            font-size: 20pt;
            text-shadow: #0B130B 2px 2px 5px;
            color: #b9ddda;
            font-family: 'PT Sans', sans-serif !important;
        }

        .presentasi_cover {
            padding: 30px;
            background-color: #B4D7D3A1;
            width: 60%;
            margin: auto;
            margin-top: auto;
            margin-top: 100px;
            border: double 5px #6F9DAE;
            box-shadow: #136D7D66 10px 10px 10px;
            border-radius: 15px;
        }

        .presentasi {
            background-color: #B4D7D3A1;
        }

        .presentasi tr:nth-child(odd) {
            background-color: #BDDDD485;
        }

        .presentasi td:nth-child(1) {
            color: #175e57;
            font-weight: bold;
            font-size: 110%;
        }

        .presentasi td:nth-child(3) {
            color: #FFFFFF;
            font-weight: bold;
            font-size: 110%;
            text-shadow: #030C1A 3px 3px 5px;
        }

        .rincian_rekap_absen {
            font-size: 95% !important;
            padding: 10px !important;
            border-bottom: solid 1px #CCC !important;
        }
    </style>
    <script src="<?php echo base_url();?>assets/backend/js/jquery-3.3.1.js"></script>
    <script src="<?php echo base_url();?>assets/backend/js/accounting.min.js"></script>
    <script type="text/javascript">
       var bloks = <?php echo json_encode($blok); ?>;
       var max_bloks = bloks.length;
       var index_showing = 0;

       function presentation() {
            var this_blok = bloks[index_showing];
            $("#lblBlok").html(this_blok.blok);
            $("#lblTahunTanam").html(this_blok.tahun_tanam);
            $("#lblTargetPanen").html("- - - - -");
            $("#lblTargetPanenIni").html("- - - - -");
            $("#lblTbsJanjang").html("- - - - -");
            $("#lblTbsKg").html("- - - - -");
            $("#lblTbsPersen").html("- - - - -");
            $("#lblBrondolanKg").html("- - - - -");
            $("#lblBrondolanPersen").html("- - - - -");
            $("#presentasi_hasil").fadeIn(500, function() {
                $.ajax({
                    async       : false,
                    url         : "<?php echo base_url();?>SlideShowMonitoringPanen/get_data_monitoring",
                    type        : "post",
                    dataType    : "json",
                    data        : "id_blok=" + this_blok.id + "&tahun=<?php echo $tahun; ?>&bulan=<?php echo $bulan; ?>&per_tgl=<?php echo $per_tgl; ?>",
                    success     : function(r) {
                        $("#nama_kebun").html(r.nama_kebun);
                        $("#nama_afdeling").html(r.nama_afdeling);
                        $("#lblTargetPanen").html(accounting.format(r.target_panen, 0) + " Kg");
                        $("#lblTargetPanenIni").html(accounting.format(r.target_panen_sd_hari_ini, 0) + " Kg");
                        $("#lblTbsJanjang").html(accounting.format(r.jumlah_tbs_janjang, 0) + " Janjang");
                        $("#lblTbsKg").html(accounting.format(r.jumlah_tbs_kg, 2) + " Kg");
                        $("#lblTbsPersen").html(accounting.format(r.jumlah_tbs_persen, 2) + "%");
                        $("#lblBrondolanKg").html(accounting.format(r.jumlah_brondolan_kg, 2) + " Kg");
                        $("#lblBrondolanPersen").html(accounting.format(r.jumlah_brondolan_persen, 2) + "%");

                        var isi_rekap_absen = "<table width='100%' cellspacing='0' cellpadding='5'>";
                        $.each(r.rekap_absen, function(i, v) {
                            isi_rekap_absen += "<tr>";
                                isi_rekap_absen += "<td width='200px'>" + v.status_kehadiran + "</td>";
                                isi_rekap_absen += "<td width='5px'>:</td>";
                                isi_rekap_absen += "<td>" + v.jumlah + "</td>";
                            isi_rekap_absen += "</tr>";
                        });
                        isi_rekap_absen += "</table>";
                        $("#panel_rekap_absen").html(isi_rekap_absen);
                    }
                });
                setTimeout(function() {
                    $("#presentasi_hasil").fadeOut(500, function() {
                        if(index_showing == max_bloks - 1) {
                            index_showing = 0;
                        } else {
                            index_showing++;
                        }
                        presentation();
                    });
                }, 10000);
            });
       }

       $(function() {
            presentation();
       });
    </script>
</head>
<body>
    <div class="presentasi_cover">
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td width="70px">
                    <img src="<?php echo base_url();?>assets/backend/img/logo1.png" style="width: 100%;" />
                </td>
                <td align='center' class="judul_besar">
                    Monitoring hasil panen s/d tanggal <?php echo $per_tgl_tostring; ?><br />Kebun <span id="nama_kebun"></span> <span id="nama_afdeling"></span>
                </td>
                <td width="70px">
                <img src="<?php echo base_url();?>assets/backend/img/logo2.png" style="width: 100%;" />
                </td>
            </tr>
        </table>
        <br />
        <div class='presentasi'>
            <table width="100%">
                <tr>
                    <td width="40%" valign="top" id="panel_rekap_absen">
                    </td>
                    <td>
                        <table id="presentasi_hasil" width="100%" cellspacing="0" cellpadding="5" style="display: none;">
                            <tr>
                                <td width="220px">Blok</td>
                                <td width="5px">:</td>
                                <td id="lblBlok"></td>
                            </tr>
                            <tr>
                                <td>Tahun Tanam</td>
                                <td>:</td>
                                <td id="lblTahunTanam"></td>
                            </tr>
                            <tr>
                                <td>Target Panen</td>
                                <td>:</td>
                                <td id="lblTargetPanen"></td>
                            </tr>
                            <tr>
                                <td>Target Panen S/D hari ini</td>
                                <td>:</td>
                                <td id="lblTargetPanenIni"></td>
                            </tr>
                            <tr>
                                <td>Hasil TBS (Janjang)</td>
                                <td>:</td>
                                <td id="lblTbsJanjang"></td>
                            </tr>
                            <tr>
                                <td>Hasil TBS (Kg)</td>
                                <td>:</td>
                                <td id="lblTbsKg"></td>
                            </tr>
                            <tr>
                                <td>Hasil TBS (%)</td>
                                <td>:</td>
                                <td id="lblTbsPersen"></td>
                            </tr>
                            <tr>
                                <td>Hasil Brondolan (Kg)</td>
                                <td>:</td>
                                <td id="lblBrondolanKg"></td>
                            </tr>
                            <tr>
                                <td>Hasil Brondolan (%)</td>
                                <td>:</td>
                                <td id="lblBrondolanPersen"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
