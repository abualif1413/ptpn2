<div id="sidebar" class="sidebar py-3">
  <!-- <div class="text-gray-400 text-uppercase px-3 px-lg-4 py-4 font-weight-bold small headings-font-family">Menu
  </div> -->
  <ul class="sidebar-menu list-unstyled">
        <?php
        $user=$this->session->userdata('user');
        extract($user);
        ?>
        <li class="sidebar-list-item"><a href="<?php echo site_url('Dashboard')?>" class="sidebar-link text-muted"><i class="o-home-1 mr-3 text-gray"></i><span>Home</span></a></li>
        
        <?php
        if ($role=='m_kebun'){?>
        <li class="sidebar-list-item"><a href="<?php echo site_url('Qrabsensi')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Qr Absensi</span></a></li>
        <li class="sidebar-list-item"><a href="<?php echo site_url('DataPemanen')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Data Pemanen</span></a></li>
        <li class="sidebar-list-item"><a href="<?php echo site_url('Dataabsen020')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Data Absensi Pemanen</span></a></li>
        <li class="sidebar-list-item"><a href="<?php echo site_url('Dataizin')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Data Izin</span></a></li>
        <?php
        }else if($role=='k_askep'){?>
        <li class="sidebar-list-item"><a href="<?php echo site_url('Mandorkeraniaskep')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Data Mandor</span></a></li>
        <li class="sidebar-list-item"><a href="<?php echo site_url('DataPemanenkeraniaskep')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Data Pemanen</span></a></li>
        <li class="sidebar-list-item"><a href="<?php echo site_url('DataHasilPanenkeraniaskep')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Data Hasil Panen</span></a></li>
        <!-- <li class="sidebar-list-item"><a href="<?php echo site_url('Inputkomidel')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Input Komidel</span></a></li>
        <li class="sidebar-list-item"><a href="<?php echo site_url('DataKomidel')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Data Komidel</span></a></li> -->
        <li class="sidebar-list-item"><a href="<?php echo site_url('ListByrPanen')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Premi Panen</span></a></li>
        <li class="sidebar-list-item"><a href="<?php echo site_url('Historybyrpanen')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>History Premi Panen</span></a></li>
        <!--<li class="sidebar-list-item"><a href="<?php echo site_url('Inputkomidelnew')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Data SPTBS Sebelum Slip Timbang</span></a></li>-->
        <li class="sidebar-list-item"><a href="<?php echo site_url('Inputkomidelnew020')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Data SPTBS Setelah Slip Timbang</span></a></li>
        <li class="sidebar-list-item"><a href="<?php echo site_url('DataKomidel')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Validasi Komidel dan Data Prod. Per Blok</span></a></li>
        <?php
        }else if($role=='s_admin'){?>
        
        <li class="sidebar-list-item"><a href="<?php echo site_url('Datadistrik')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Data Distrik</span></a></li>
        <li class="sidebar-list-item"><a href="<?php echo site_url('Kebun')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Data Kebun</span></a></li>
        <li class="sidebar-list-item"><a href="<?php echo site_url('Afdeling')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Data Afdeling</span></a></li>
        <li class="sidebar-list-item"><a href="<?php echo site_url('Blok')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Data Blok</span></a></li>

        <li class="sidebar-list-item"><a href="<?php echo site_url('Keraniaskep')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Data Kerani Askep</span></a></li>
        <li class="sidebar-list-item"><a href="<?php echo site_url('Keranikcs')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Data Kerani KCS</span></a></li>
        <li class="sidebar-list-item"><a href="<?php echo site_url('Mandoradmin')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Data Mandor</span></a></li>
        <li class="sidebar-list-item"><a href="<?php echo site_url('DataPemanenadmin')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Data Pemanen</span></a></li>
        
        <li class="sidebar-list-item"><a href="<?php echo site_url('DataHariLibur')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Data Hari Libur</span></a></li>
        <li class="sidebar-list-item"><a href="<?php echo site_url('ListByrPanenadmin')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Premi Panen</span></a></li>
        <li class="sidebar-list-item"><a href="<?php echo site_url('Historybyrpanenadmin')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>History Premi Panen</span></a></li>
        <li class="sidebar-list-item"><a href="<?php echo site_url('Absenpemanenadmin')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Data Absen Pemanen</span></a></li>
        <li class="sidebar-list-item"><a href="<?php echo site_url('Datauseradmin')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Data Users</span></a></li>
        <li class="sidebar-list-item"><a href="<?php echo site_url('Helpdesk')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Help Desk</span></a></li>
        <li class="sidebar-list-item"><a href="<?php echo site_url('Backupdb')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Backup DB</span></a></li>
        
        <li class="sidebar-list-item"><a href="<?php echo site_url('TargetPanenBulanan')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Target Panen Bulanan</span></a></li>
        
        <?php
        }else if($role=='kerani_kcs'){?>
        <li class="sidebar-list-item"><a href="<?php echo site_url('Qrpanen')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Qr Panen</span></a></li>
        <li class="sidebar-list-item"><a href="<?php echo site_url('Mandor')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Data Mandor</span></a></li>
        <li class="sidebar-list-item"><a href="<?php echo site_url('DataHasilPanen020')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Data Hasil Panen</span></a></li>
        <!--<li class="sidebar-list-item"><a href="<?php echo site_url('TambahTrip')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Tambah Trip</span></a></li>-->
        <li class="sidebar-list-item"><a href="<?php echo site_url('TambahTrip020')?>" class="sidebar-link text-muted"><i class="o-sales-up-1 mr-3 text-gray"></i><span>Tambah Trip</span></a></li>
        
        <?php
        }
        ?>
        <li class="sidebar-list-item"><a href="<?php echo site_url('Gantipassword')?>" class="sidebar-link text-muted"><i class="o-exit-1 mr-3 text-gray"></i><span>Ganti Password</span></a></li>
        <li class="sidebar-list-item"><a href="<?php echo site_url('Authadmin/logout')?>" class="sidebar-link text-muted"><i class="o-exit-1 mr-3 text-gray"></i><span>Keluar</span></a></li>
  </ul>
</div>
