<section class="py-5 slowmotion">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h6 class="text-uppercase mb-0">
                <label class="pull-right">Data Hasil Panen</label>
            </h6>
          </div>
          <div class="card-body">
          <div style="overflow-x:auto;">
            <table id="DataTable_users" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kenari Kcs</th>
                        <th>Mandor Kebun</th>
                        <th>Nama Pemanen</th>
                        <th>Kebun</th>
                        <th>Afdeling</th>
                        <th>Barcode</th>
                        <th>TPH</th>
                        <th>Blok</th>
                        <th>J.Janjang</th>
                        <th>J.Brondolan</th>
                        <th>P.Alat</th>
                        <th>Tanggal Panen</th>
                        <th>Options</th>
                    </tr>
                </thead>
            </table>
          </div>

          <small><b>Catatan :</small><br>
              <small>(1) Data Panen Tampil Berdasarkan Tanggal Berjalan</small><br> 
              <small>(2) Jika Data Panen Lewat Dari Tanggal Berjalan Data Akan Hilang</small><br>  
          </div>
        </div>
      </div>
    </div>
  </section>

<script src="<?php echo base_url();?>assets/backend/js/jquery-3.3.1.js"></script>
<script type="text/javascript">
    //FUNGSI ADD DATA USER


    function reloadTable() {
        $("#DataTable_users").DataTable().ajax.reload(null,false);
    }

    $(document).ready(function() {
        $("#DataTable_users").DataTable({
            serverSide:true,
            responsive:true,
            processing:false,
            oLanguage: {
                sZeroRecords: "<center>Data tidak ditemukan</center>",
                sLengthMenu: "Tampilkan _MENU_",
                sSearch: "Cari data:",
                sInfo: "Menampilkan: _START_ - _END_ dari total: _TOTAL_ data",                                   
                oPaginate: {
                    sFirst: "Awal", "sPrevious": "Sebelumnya",
                    sNext: "Selanjutnya", "sLast": "Akhir"
                },
            },
            ajax: {
                url: '<?php echo base_url('DataHasilPanenkeraniaskep/data_hasil_panen');?>',
                type: 'POST',
                data: {'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>'}
            },
            order:[[2,'DESC']],
            columns:[
                {
                    data:'no',
                    searchable:false,
                    orderable:false,
                },
                { data:'nama_lengkap' },
                { data:'mandor' },
                { data:'nama_pemanen' },
                { data:'nama_kebun' },
                { data:'nama_afdeling' },
                { data:'barcode' },
                { data:'tph' },
                { data:'kode_blok' },
                { data:'jmlh_panen' },
                { data:'jmlh_brondolan' },
                { data:'premi_alat' },
                { data:'tanggal' },
                {
                    data:'button_action',
                    searchable:false,
                    orderable:false,
                }
            ],
        });
    });

</script>