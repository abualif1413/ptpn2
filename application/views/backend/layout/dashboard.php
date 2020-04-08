<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $title; ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/backend/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,800">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/orionicons.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/style.default.css" id="theme-stylesheet">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/backend/css/custom.css">
    <link rel="shortcut icon" href="<?php echo base_url();?>assets/backend/img/favicon.png?3">
    <link rel="shortcut icon" href="<?php echo base_url();?>assets/backend/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/backend/css/sweetalert2.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
    <link rel="icon" href="https://www.hetanews.com/images/20170428/20170428074430-logo-ptpn-ii.jpg">
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" />
    
  </head>
  <body>
    <!-- navbar-->
    <?php $this->load->view('backend/layout/header')?>
    <div class="d-flex align-items-stretch">
      <!-- sidebar-->
      <?php $this->load->view('backend/layout/sidebar')?>
      <div class="page-holder w-100 d-flex flex-wrap">
        <div class="container-fluid px-xl-5">
        <!-- content-->
        <?php $this->load->view($view_content)?>
        </div>
        <!-- footer-->
      </div>
    </div>
    <!-- JavaScript files-->
    <script src="<?php echo base_url();?>assets/backend/js/jquery-3.3.1.js"></script>
    <script src="<?php echo base_url();?>assets/backend/vendor/popper.js/umd/popper.min.js"> </script>
    <script src="<?php echo base_url();?>assets/backend/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets/backend/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url();?>assets/backend/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo base_url();?>assets/backend/js/sweetalert2.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
    <!-- <script src=""></script> -->

    <script >
    $(document).ready(function() {
      $('#example').DataTable();
    });

    var exampleDiv = $('#sidebar');
      // with jQuery, you can select elements based on the textual contents
      // here, we're searching for a button that contains the text "Jump"
      $(".sidebar-toggler").click(function(){
          // check to see whether we've already added the 'jump' class 
          if ( exampleDiv.hasClass('shrink show') ) {
              // if so, remove the 'jump' class
              exampleDiv.removeClass('shrink show');
          }
          else {
              exampleDiv.addClass('shrink show');
          }
      });
    </script>
    
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-145367334-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'UA-145367334-1');
    </script>

    
  </body>
</html>