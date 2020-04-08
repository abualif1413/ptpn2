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
    </head>
    <body>
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
                                <button title="Decode Image" class="btn btn-default btn-sm" id="decode-img" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-upload"></span></button>
                                <button title="Image shoot" class="btn btn-info btn-sm disabled" id="grab-img" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-picture"></span></button>
                                <button title="Play" class="btn btn-success btn-sm" id="play" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-play"></span></button>
                                <button title="Pause" class="btn btn-warning btn-sm" id="pause" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-pause"></span></button>
                                <button title="Stop streams" class="btn btn-danger btn-sm" id="stop" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-stop"></span></button>
                                <button title="Open Panels" class="btn btn-danger btn-sm" onclick="save();" data-toggle="modal" data-target="#myModal" ><span class="glyphicon glyphicon-pencil"></span></button>
                                </div>
                            <p id="scanned-QR" onchange="update"></p>
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

  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
        <!-- Modal content-->
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Modal Input Panen</h4>
        </div>
        <div class="modal-body">
            <form id="myForm" action="https://ptpn2.asikinonlineaja.com/Dataqcode/addpanen" method="POST">
                <div class="row">
                    <div class="form-group col-md-4">
                        <img id="img" width="100px" height="100px" src="../assets/backend/img/avatar.png" />
                    </div>
                    <div class="form-group col-md-4">
                        <label for="Hasil Panel">Nama Mandor:</label>
                        <input type="text" name="keranikcs" class="form-control" readonly>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="Hasil Panel">Nama Pemanen:</label>
                        <input type="text" name="nama_pemanen" class="form-control" readonly>
                    </div>
                </div>

                <div class="form-group" style="display: none;">
                    <label for="Hasil Panel">QrCode:</label>
                    <input id="code_id_value" type="text" name="qrcode" class="form-control" >
                </div>
                <div class="form-group" style="display: none;">
                    <label for="Hasil Panel">Pemanen:</label>
                    <input type="text" name="id_pemanen" class="form-control" >
                </div>

                <div class="form-group" style="display: none;">
                    <label for="Hasil Panel">Kerani Askep:</label>
                    <input type="text" name="id_kerani_askep" class="form-control" >
                </div>

                <div class="form-group" style="display:none ;">
                    <label for="Hasil Panel">Kerani Kcs:</label>
                    <input type="text" name="id_kerani_kcs" class="form-control" >
                </div>

                <div class="form-group" style="display: none;">
                    <label for="Hasil Panel">Kebun:</label>
                    <input type="text" name="id_kebun" class="form-control" >
                </div>

                <div class="form-group" style="display: none;">
                    <label for="Hasil Panel">Afdeling:</label>
                    <input type="text" name="id_afdeling" class="form-control" >
                </div>
                
                <div class="form-group">
                    <label for="Hasil Panel">Jumlah Panen:</label>
                    <input  type="text" name="jmlh_panen" class="form-control" >
                </div>

                <div class="form-group">
                    <label for="Hasil Panel">Blok:</label>
                    <select  name="blok" class="form-control" id="blok">
                    </select>
                </div>

                <div class="form-group">
                    <label for="Hasil Panel">TPH:</label>
                    <input  type="text" name="tph" class="form-control" >
                </div>

                <div class="form-group">
                <label for="Pemanen">Premi Alat Penggati</label>
                <select class="form-control" name="id_alat">
                    <option value="">Pilih</option>
                    <option value="1">Alat Panen Dengan Menggunakan Dodos ( 1400 )</option>
                    <option value="2">Alat Panen Dengan Menggunakan Egrek (1 Gala Bambu) ( 2200 )</option>
                    <option value="3">Alat Panen Dengan Menggunakan Egrek (2  Galah Bambu) ( 2400 )</option>
                    <option value="4">Alat Panen Dengan Menggunakan Egrek (3 Galah Bambu) ( 2600 )</option>
                </select>
                </div>

                <div class="form-group">
                    <label for="Hasil Panel">Jumlah Brondolan:</label>
                    <input  type="text" name="jmlh_brondolan" class="form-control" >
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button id="btnSave"  class="btn btn-default">Simpan</button>
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

        $('#btnSave').click(function () {
        var url = $('#myForm').attr('action');
        var data = new FormData($('#myForm')[0]);
        $('#btnSave').html('<i class="fa fa-save"></i>&nbsp; Simpan <img width="30" heigth="30" src="https://abeon-hosting.com/images/loading-gif-png-free-download-5.gif">').attr('disabled', 'disabled');
            $.ajax({
                type : 'ajax',
                method: 'POST',
                url  :url,
                data : data,
                async: false,
                cache: false,
                contentType:false,
                processData:false,
                dataType :'json',
                success : function(response){
                    setTimeout(function(){
                        if (response.success == true) {
                            $('#btnSave').prop('disabled', false);
                                if (response.type=='Add') {
                                    var type = 'Add';
                                    swal({
                                    type: 'success',
                                    title: 'Berhasil Input Panen',
                                    html: '',
                                    footer: ''
                                    });
                                    $('#btnSave').html('<i class="fa fa-save"></i>&nbsp;Simpan');
                                    $('#myForm')[0].reset();
                                }else if(response.type=='Update'){
                                    var type = 'Update';
                                    swal({
                                    type: 'success',
                                    title: 'Berhasil Update',
                                    footer: ''
                                    });
                                    $('#btnSave').html('<i class="fa fa-save"></i>&nbsp;Update');
                                }
                                $('#myModal').modal('hide');
                            }else {

                            if (response.error) {
                                $('#myModal').modal('hide');
                                swal({
                                type: 'warning',
                                title: 'Peringatan !!!!',
                                html: response.error,
                                footer: ''
                                });
                                $('#btnSave').html('<i class="fa fa-save"></i>&nbsp;Simpan');
                                $('#btnSave').prop('disabled', false);
                            }
                        }
                    },1000);
                },
                error: function(){
                    swal({
                        type: 'error',
                        title: 'Error Input !!..',
                        footer: ''
                    });
                    $('#btnSave').html('<i class="fa fa-save"></i>&nbsp;Simpan');
                    $('#btnSave').prop('disabled', false);
                }
            });
        });
            
            setInterval(update,1);
            function update() {
                var code_id_value = document.getElementById("scanned-QR").innerHTML;
                document.getElementById("code_id_value").value = code_id_value.substring(8,20);
            }
            update();

            function save(){
              var barcode=$('input[name=qrcode]').val();
              $.ajax
                ({
                    type: "POST",
                    url: "https://ptpn2.asikinonlineaja.com/Dataqcode",
                    data: {barcode:barcode},
                    cache: false,
                    success: function(response)
                    {   
                        console.log(response);
                        $('#myModal').find('.modal-title').text(response.barcode);
                        $('input[name=id_pemanen]').val(response.id);
                        $('input[name=nama_pemanen]').val(response.nama_pemanen);
                        $('input[name=id_kerani_askep]').val(response.id_kerani_askep);
                        $('input[name=id_kerani_kcs]').val(response.id_kerani_kcs);
                        $('input[name=id_kebun]').val(response.id_kebun);
                        $('input[name=id_afdeling]').val(response.id_afdeling);
                        $('input[name=keranikcs]').val(response.keranikcs);
                        if(!response.photo){
                            $("#img").attr("src","http://cdn.onlinewebfonts.com/svg/img_264157.png").fadeIn();
                        }else{
                            block();
                            $("#img").attr("src","../assets/backend/img/photo/"+response.photo+"").fadeIn();
                        }
                    } 
                });
            }
            
            function block(){
              var id_kebun=$('input[name=id_kebun]').val();
              var id_afdeling=$('input[name=id_afdeling]').val();
              $.ajax
                ({
                    type: "POST",
                    url: "https://ptpn2.asikinonlineaja.com/Dataqcode/blokpemananen",
                    data: {
                        id_kebun:id_kebun,
                        id_afdeling:id_afdeling
                    },
                    cache: false,
                    success: function(response)
                    {
                        
                        console.log(response);
                        if(!response.blok){
                            var len = response.length;
                            $("#blok").empty();
                            for( var i = 0; i<len; i++){
                            var blok = response[i]['blok'];
                            var id = response[i]['id'];
                            $("#blok").append("<option value='"+id+"'>Blok "+blok+"</option>");
                            }
                        }else{

                        }
                    } 
                });
            }

            $(document).ready(function(){
                $('#button').click( function(e) {
                    e.preventDefault(); // stops link from making page jump to the top
                    e.stopPropagation(); // when you click the button, it stops the page from seeing it as clicking the body too
                    $('#content').toggle();
                });
                
                $('#content').click( function(e) {
                    e.stopPropagation();
                });
                
                $('body').click( function() {
                    $('#content').hide();
                });
            });
        </script>
    </body>
</html>