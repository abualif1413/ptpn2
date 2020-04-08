<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Aplikasi PTPN-II</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../assets/backend/css/sweetalert2.css">
        <style>#content {display: none; }</style>
        <style type="text/css">
        .swal2-popup {
            font-size: 1.6rem !important;
        }  
        </style>
        <link rel="icon" href="https://www.hetanews.com/images/20170428/20170428074430-logo-ptpn-ii.jpg">

       <div class="container" id="QR-Code" >
            <div class="">
                <div class="panel-body text-center">
                    <div class="col-md-12">
                        <div class="well" style="position: relative;display: inline-block;">
                            <canvas width="320" height="240" id="webcodecam-canvas"></canvas>
                            <div class="scanner-laser laser-rightBottom" style="opacity: 0.5;"></div>
                            <div class="scanner-laser laser-rightTop" style="opacity: 0.5;"></div>
                            <div class="scanner-laser laser-leftBottom" style="opacity: 0.5;"></div>
                            <div class="scanner-laser laser-leftTop" style="opacity: 0.5;"></div>
                        </div>
                        <div class="caption">
                                <div class="form-group">
                                <select class="form-control" id="camera-select" style="display: none;"></select>
                                <button style="display: none;" title="Decode Image" class="btn btn-default btn-sm" id="decode-img" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-upload" ></span></button>
                                <button style="display: none;" title="Image shoot" class="btn btn-info btn-sm disabled" id="grab-img" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-picture"></span></button>
                                <button title="Play" class="btn btn-success btn-sm" id="play" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-play"></span></button>
                                <button title="Pause" class="btn btn-warning btn-sm" id="pause" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-pause"></span></button>
                                <button title="Stop streams" class="btn btn-danger btn-sm" id="stop" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-stop"></span></button>
                                </div>
                            <p id="scanned-QR" onchange="update"></p>
                            <input id="code_id_value" type="hidden" name="qrcode">
                            <button  class="btn btn-danger btn-sm" id="validasi" onclick="absen();" >Validasi</span></button>
    
                        </div>
                        <div class="well" style="width: 100%;display:none;" >
                            <label id="zoom-value" width="100">Zoom: 2</label>
                            <input id="zoom" onchange="Page.changeZoom();" type="range" min="10" max="30" value="20">
                            <label id="brightness-value" width="100">Brightness: 0</label>
                            <input id="brightness" onchange="Page.changeBrightness();" type="range" min="0" max="128" value="0">
                            <label id="contrast-value" width="100">Contrast: 0</label>
                            <input id="contrast" onchange="Page.changeContrast();" type="range" min="0" max="64" value="0">
                            <label id="threshold-value" width="100">Threshold: 0</label>
                            <input id="threshold" onchange="Page.changeThreshold();" type="range" min="0" max="512" value="0">
                            <label id="sharpness-value" width="100">Sharpness: off</label>
                            <input id="sharpness" onchange="Page.changeSharpness();" type="checkbox">
                            <label id="grayscale-value" width="100">grayscale: off</label>
                            <input id="grayscale" onchange="Page.changeGrayscale();" type="checkbox">
                            <br>
                            <label id="flipVertical-value" width="100">Flip Vertical: off</label>
                            <input id="flipVertical" onchange="Page.changeVertical();" type="checkbox">
                            <label id="flipHorizontal-value" width="100">Flip Horizontal: off</label>
                            <input id="flipHorizontal" onchange="Page.changeHorizontal();" type="checkbox">
                        </div>
                    </div>
                    <div class="col-md-6" style="display:none">
                        <div class="thumbnail" id="result">
                            <div class="well" style="overflow: hidden;display: none;">
                                <img width="320" height="240" id="scanned-img" src="">
                            </div>
                            
                        </div>
                    </div>
                </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script type="text/javascript" src="js/filereader.js"></script>
        <script type="text/javascript" src="js/qrcodelib.js"></script>
        <script type="text/javascript" src="js/webcodecamjs.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
        <script src="../assets/backend/js/sweetalert2.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <script>
            setInterval(update,1);
            function update() {
                var code_id_value = document.getElementById("scanned-QR").innerHTML;
                document.getElementById("code_id_value").value = code_id_value.substring(8,20);
            }
            update();

            function absen(){
                var id_absen=$('input[name=qrcode]').val();
                if (id_absen === ""){
                    $('#dataabsen').html('data kosong');
                }else{
                    $('#validasi').html('<i class="fa fa-save"></i>&nbsp; Simpan <img width="30" heigth="30" src="https://abeon-hosting.com/images/loading-gif-png-free-download-5.gif">').attr('disabled', 'disabled');
                    $.ajax
                    ({
                        type: "POST",
                        url: "https://ptpn2.asikinonlineaja.com/Dataabsen/Add_Absen",
                        data: {id_absen:id_absen},
                        cache: false,
                        success: function(response)
                        {   
                            setTimeout(function(){
                            $( "#scanned-QR" ).empty();
                                if (response.success == true) {
                                    $('#validasi').prop('disabled', false);
                                    $('#validasi').html('<i class="fa fa-save"></i>&nbsp;Validasi');
                                        if (response.type=='Add') {
                                            var type = 'Add';
                                            swal({
                                            type: 'success',
                                            title: 'Berhasil',
                                            html: response.pesan,
                                            footer: ''
                                            });
                                        }
                                    }else {
                                   
                                    if (response.error) {
                                        $('#validasi').prop('disabled', false);
                                        $('#validasi').html('<i class="fa fa-save"></i>&nbsp;Validasi');
                                        swal({
                                        type: 'warning',
                                        title: 'Peringatan !!!!',
                                        html: response.error,
                                        footer: ''
                                        });
                                    }
                                }
                            },500);
                        } 
                    });
                }
            }
            
        </script>
    </body>
</html>