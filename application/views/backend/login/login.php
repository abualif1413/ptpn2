<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login Aplikasi PTPN-II</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/backend/vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <!-- Google fonts - Popppins for copy-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,800">
    <!-- orion icons-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/orionicons.css">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/style.default.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/custom.css">
    <!-- Favicon-->
    <link rel="icon" href="https://www.hetanews.com/images/20170428/20170428074430-logo-ptpn-ii.jpg">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/backend/css/sweetalert2.css">
    <style>
        #customer-chat-iframe{
            right: 10px;
        }
    </style>
    
  </head>
  <body>
    <div class="page-holder d-flex align-items-center">
      <div class="container">
        <div class="row align-items-center py-5">
          <div class="col-5 col-lg-7 mx-auto mb-5 mb-lg-0">
            <div class="pr-lg-5"><img src="<?php echo base_url();?>assets/backend/img/illustration.svg" alt="" class="img-fluid"></div>
          </div>
          <div class="col-lg-5 px-lg-4">
            <h1 class="text-base text-primary text-uppercase mb-4">Selamat Datang Di</h1>
            <h2 class="mb-4">Aplikasi PTPN-II</h2>
            <!-- <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.</p> -->

            <?php echo form_open('Authadmin/login', array('class' => 'mt-4', 'id' => 'logForm')); ?>
              <div class="form-group mb-4">
                <input type="text" id="email" placeholder="Email Or Username" class="form-control border-0 shadow form-control-lg">
              </div>
              <div class="form-group mb-4">
                <input type="password" id="password" placeholder="Password" class="form-control border-0 shadow form-control-lg text-violet">
              </div>
              <div class="form-group">
              <button  id="logText" class="btn btn-primary shadow px-5 pull-left" style="float: left;">Masuk</button>
              </div>
             <?php echo form_close(); ?>
             
          </div>
        </div>
        <!-- <p class="mt-5 mb-0 text-gray-400 text-center">Design by <a href="" class="external text-gray-400">PTPN-II</a></p> -->
      </div>
    </div>
    <!-- JavaScript files-->
    <script src="<?php echo base_url();?>assets/backend/js/jquery-3.3.1.js"></script>
    <script src="<?php echo base_url();?>assets/backend/vendor/popper.js/umd/popper.min.js"> </script>
    <script src="<?php echo base_url();?>assets/backend//vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets/backend/js/sweetalert2.js"></script>
    <script type="text/javascript" src="/livechats/php/app.php/widget-init.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#logText').html('Masuk');
            $('#logForm').submit(function(e){
                e.preventDefault();
                $('#logText').html('&#10095;&#10095; Checking <img width="30" heigth="25" src="<?php echo base_url();?>assets/backend/img/loading.gif">').attr('disabled', 'disabled');
                var url = $('#logForm').attr('action');
                var email = $('#email').val();
                var password = $('#password').val();
                var csrf_test_name = $('input[name=csrf_test_name]').val();
                var login = function(){
                    $.ajax({
                        type:'POST',
                        url: url,
                        dataType:'json',
                        data: {'<?php echo $this->security->get_csrf_token_name(); ?>':csrf_test_name,
                                email:email,
                                password:password
                              },
                        success:function(response){
                            $('#message').html(response.message);
                            $('#logText').prop('disabled', false);
                            $('#logText').html('Masuk');
                            if(response.error){
                                swal({
                                  type: 'warning',
                                  title: 'Warning !!!!!!',
                                  html: response.error,
                                  footer: ''
                                });
                                $('#logForm')[0].reset();
                            }else{
                                swal({
                                  type: 'success',
                                  title: 'Success Login',
                                  footer: ''
                                });

                                $('#logForm')[0].reset();
                                setTimeout(function(){
                                    location.reload();
                                }, 500);
                            }
                        }
                    });
                };
                setTimeout(login, 2000);
            });
        });
    </script>
  </body>
</html>