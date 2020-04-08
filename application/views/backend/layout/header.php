<?php
$user=$this->session->userdata('user');
extract($user);


?>
<header class="header">
  <nav class="navbar navbar-expand-lg px-4 py-2 bg-white shadow"><a href="#" class="sidebar-toggler text-gray-500 mr-4 mr-lg-5 lead"><i class="fas fa-align-left"></i></a><a href="index.html" class="navbar-brand font-weight-bold text-uppercase text-base"></a>
  <a href="<?php echo site_url('Dashboard')?>" style="text-decoration:none;color:#333;font-weight: bold;font-size:40px;"><img src="https://koranbumn.com/wp-content/uploads/2018/07/ptpn-2-336x330.jpg" class="img-responsive" alt="Cinque Terre" style="width:50px;height:50px;"> &nbsp;PTPN-II </a>
  
    <ul class="ml-auto d-flex align-items-center list-unstyled mb-0">
      <li class="nav-item dropdown ml-auto"><a id="userInfo" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle"><img src="<?php echo base_url();?>assets/backend/img/photo/<?php print $photo;?>" alt="Jason Doe" style="max-width: 2.5rem;" class="img-fluid rounded-circle shadow"></a>
        <div aria-labelledby="userInfo" class="dropdown-menu"><a href="#" class="dropdown-item"><strong class="d-block text-uppercase headings-font-family"><?php print $nama_lengkap;?></strong><small><?php print $role;?></small></a>
          <div class="dropdown-divider"></div><a href="<?php echo site_url('Authadmin/logout')?>" class="dropdown-item">Keluar</a>
        </div>
      </li>
    </ul>
  </nav>
</header>