<section class="py-5 slowmotion">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h6 class="text-uppercase mb-0"></h6>
        </div>
        <div class="card-body">
        <section>
            <div class="row mb-4">
              <!-- <div class="col-lg-12 mb-4 mb-lg-0">
              <h4>Selamat Datang Imam Wasmawi Di Aplikasi PTPN II</h4>
              </div> -->
              <div class="col-lg-3 mb-4 mb-lg-0">
                <div class="card">
                  <div class="card-header">
                    <h2 class="h6 text-uppercase mb-0">Photo Profile</h2>
                  </div>
                  <div class="card-body">
                    <div class="chart-holder">
                      <center>
                        <?php
                        $user=$this->session->userdata('user');
                        extract($user);
                        ?>
                        <?php
                        if($role=='s_admin'){?>
                          <div id="foto_admin"></div>
                        <?php
                        }else if($role=='k_askep'){?>
                          <div id="foto_kerani_askep"></div>
                        <?php
                        }else if($role=='kerani_kcs'){?>
                          <div id="foto_kerani_kcs"></div>
                        <?php
                        }else if($role=='m_kebun'){?>
                          <div id="foto_mandor"></div>
                        <?php
                        }else if($role=='distrik'){?>
                        
                        
                        <?php
                        }
                        ?>
                      </center>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-9 mb-4 mb-lg-0">
                <div class="card">
                  <div class="card-header">
                    <h2 class="h6 text-uppercase mb-0">Data Detail</h2>
                  </div>
                  <div class="card-body">
                    <div class="chart-holder">
                    <table class="table">
                    
                    <?php
                    if($role=='s_admin'){?>
                      <thead id="data_detail_admin"></thead>
                    <?php
                    }else if($role=='k_askep'){?>
                      <thead id="data_detail_kerani_askep"></thead>
                    <?php
                    }else if($role=='kerani_kcs'){?>
                      <thead id="data_detail_kerani_kcs"></thead>
                    <?php
                    }else if($role=='m_kebun'){?>
                      <thead id="data_detail_mandor"></thead>
                    <?php
                    }else if($role=='distrik'){?>
                    
                    
                    <?php
                    }
                    ?>
                  </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="<?php echo base_url();?>assets/backend/js/jquery-3.3.1.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
// google.charts.load('current', {packages: ['corechart', 'bar']});
// google.charts.setOnLoadCallback(drawBasic);
// function drawBasic() {
//       var jsonData = $.ajax({
//         url: "<?php echo base_url('Dashboard/grafik');?>",
//         dataType: "json",
//         async: false,
//         success: function(jsonData){
//           var data = google.visualization.arrayToDataTable(jsonData);
//             var options = {
//               title: 'Data Grafik Prestasi Satuan (Kg)',
//               chartArea: {width: '50%'},
//               hAxis: {
//                 title: 'Total Prestasi / <?php print date('M'); ?>',
//                 minValue: 0
//               }
//             };
//             var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
//             chart.draw(data, options);
//         }
//       }).responseText;
//     }
</script>

<script type="text/javascript">
  // PROFILE ADMIN
	$(document).ready(function(){
		profil_admin();
		function profil_admin(){
		    $.ajax({
		        type  : 'ajax',
		        url   : '<?php echo base_url('Dashboard/profile_admin')?>',
		        async : false,
		        dataType : 'json',
		        success : function(data){
                var url='<?php echo base_url()?>';
		            var data_detail_admin = '';
                var foto_admin = '';
		            var i;
                if(data==null){
                  
                }else{
                  for(i=0; i<data.length; i++){
                    data_detail_admin +='<tr>'+
                              '<th>Nama</th>'+
                              '<th>'+data[i].nama_lengkap+'</th>'+
                              '</tr>'+
                              '<tr>'+
                              '<th>Level</th>'+
                              '<th>'+data[i].role+'</th>'+
                              '</tr>';
                  }

                  for(i=0; i<data.length; i++){
                    foto_admin +='<img  class="img-responsive" alt="Responsive image" width="150" height="150" src="'+url+'/assets/backend/img/photo/'+data[i].photo+'"><br><h5>'+data[i].nama_lengkap+'</h5>';
                  }
                }
                
                $('#foto_admin').html(foto_admin);
		            $('#data_detail_admin').html(data_detail_admin);
		        }

		    });
		}
	});

  // PROFILE KERANI ASKEP
	$(document).ready(function(){
		profil_askep();
		function profil_askep(){
		    $.ajax({
		        type  : 'ajax',
		        url   : '<?php echo base_url('Dashboard/profile_kerani_askep')?>',
		        async : false,
		        dataType : 'json',
		        success : function(data){
                var url='<?php echo base_url()?>';
		            var data_detail_kerani_askep = '';
                var foto_kerani_askep = '';
		            var i;
                if(data==null){

                }else{
                  for(i=0; i<data.length; i++){
                    data_detail_kerani_askep +='<tr>'+
                              '<th>Distrik</th>'+
                              '<th>'+data[i].distrik+'</th>'+
                              '</tr>'+
                              '<tr>'+
                              '<th>Kerani Askep</th>'+
                              '<th>'+data[i].keraniaskep+'</th>'+
                              '</tr>'+
                              '<tr>'+
                              '<th>Kebun</th>'+
                              '<th>'+data[i].nama_kebun+'</th>'+
                              '</tr>'+
                              '<tr>'+
                              '<th>Level</th>'+
                              '<th>'+data[i].role+'</th>'+
                              '</tr>';
                  }
                  for(i=0; i<data.length; i++){
                    foto_kerani_askep +='<img  class="img-responsive" alt="Responsive image" width="150" height="150" src="'+url+'/assets/backend/img/photo/'+data[i].photo+'"><br><h5>'+data[i].keraniaskep+'</h5>';
                  }
                }
                
                $('#foto_kerani_askep').html(foto_kerani_askep);
		            $('#data_detail_kerani_askep').html(data_detail_kerani_askep);
		        }

		    });
		}
	});

  // PROFILE KERANI KCS
  $(document).ready(function(){
		profil_kcs();
		function profil_kcs(){
		    $.ajax({
		        type  : 'ajax',
		        url   : '<?php echo base_url('Dashboard/profile_kerani_kcs')?>',
		        async : false,
		        dataType : 'json',
		        success : function(data){
                var url='<?php echo base_url()?>';
		            var data_detail_kerani_kcs = '';
                var foto_kerani_kcs = '';
		            var i;

                if(data==null){

                }else{
                  for(i=0; i<data.length; i++){
                    data_detail_kerani_kcs +='<tr>'+
                              '<th>Distrik</th>'+
                              '<th>'+data[i].distrik+'</th>'+
                              '</tr>'+
                              '<tr>'+
                              '<th>Kerani Askep</th>'+
                              '<th>'+data[i].keraniaskep+'</th>'+
                              '</tr>'+
                              '<tr>'+
                              '<th>Kerani Kcs</th>'+
                              '<th>'+data[i].keraniakcs+'</th>'+
                              '</tr>'+
                              '<tr>'+
                              '<th>Kebun</th>'+
                              '<th>'+data[i].nama_kebun+'</th>'+
                              '</tr>'+
                              '<tr>'+
                              '<th>Afdeling</th>'+
                              '<th>'+data[i].nama_afdeling+'</th>'+
                              '</tr>'+
                              '<tr>'+
                              '<th>Level</th>'+
                              '<th>'+data[i].role+'</th>'+
                              '</tr>';
                  }
                  for(i=0; i<data.length; i++){
                    foto_kerani_kcs +='<img  class="img-responsive" alt="Responsive image" width="150" height="150" src="'+url+'/assets/backend/img/photo/'+data[i].photo+'"><br><h5>'+data[i].keraniakcs+'</h5>';
                  }
                }
                
                $('#foto_kerani_kcs').html(foto_kerani_kcs);
		            $('#data_detail_kerani_kcs').html(data_detail_kerani_kcs);
		        }

		    });
		}
	});

  // PROFILE MANDOR

  $(document).ready(function(){
		profil_mandor();
		function profil_mandor(){
		    $.ajax({
		        type  : 'ajax',
		        url   : '<?php echo base_url('Dashboard/profile_mandor')?>',
		        async : false,
		        dataType : 'json',
		        success : function(data){
                var url='<?php echo base_url()?>';
		            var data_detail_mandor = '';
                var foto_mandor = '';
		            var i;

                if(data==null){
                  
                }else{
                    for(i=0; i<data.length; i++){
                    data_detail_mandor +='<tr>'+
                              '<th>Distrik</th>'+
                              '<th>'+data[i].distrik+'</th>'+
                              '</tr>'+
                              '<tr>'+
                              '<th>Kerani Askep</th>'+
                              '<th>'+data[i].keraniaskep+'</th>'+
                              '</tr>'+
                              '<tr>'+
                              '<th>Kerani Kcs</th>'+
                              '<th>'+data[i].keraniakcs+'</th>'+
                              '</tr>'+
                              '<tr>'+
                              '<th>Mandor</th>'+
                              '<th>'+data[i].mandor+'</th>'+
                              '</tr>'+
                              '<tr>'+
                              '<th>Kebun</th>'+
                              '<th>'+data[i].nama_kebun+'</th>'+
                              '</tr>'+
                              '<tr>'+
                              '<th>Afdeling</th>'+
                              '<th>'+data[i].nama_afdeling+'</th>'+
                              '</tr>'+
                              '<tr>'+
                              '<th>Level</th>'+
                              '<th>'+data[i].role+'</th>'+
                              '</tr>';
                  }
                  for(i=0; i<data.length; i++){
                    foto_mandor +='<img  class="img-responsive" alt="Responsive image" width="150" height="150" src="'+url+'/assets/backend/img/photo/'+data[i].photo+'"><br><h5>'+data[i].mandor+'</h5>';
                  }
                }
		            
                $('#foto_mandor').html(foto_mandor);
		            $('#data_detail_mandor').html(data_detail_mandor);
		        }

		    });
		}
	});
</script>