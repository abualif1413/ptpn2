
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/mint-choc/jquery-ui.css" rel="stylesheet" />
<section class="py-5 slowmotion">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h6 class="text-uppercase mb-0">
                <label class="pull-right">Data Absensi</label>
            </h6>
          </div>
          <div class="card-body">
          <div style="overflow-x:auto;">
          <br>
          <form action="<?php echo base_url('Dataabsen/Laporan_Absen');?>" method="POST" target="blank">
            <div class="row">
                <div class="col">
                    <select class="form-control" name="bulan">
                        <option value="">Pilih</option>
                        <option value="01">01 - Januari</option>
                        <option value="02">02 - Februari</option>
                        <option value="03">03 - Maret</option>
                        <option value="04">04 - April</option>
                        <option value="05">05 - Mei</option>
                        <option value="06">06 - Juni</option>
                        <option value="07">07 - Juli</option>
                        <option value="08">08 - Agustus</option>
                        <option value="09">09 - September</option>
                        <option value="10">10 - Oktober</option>
                        <option value="11">11 - November</option>
                        <option value="12">12 - Desember</option>
                    </select>
                </div>
                <div class="col">
                    <select class="form-control" name="tahun">
                        <option value="">Pilih</option>
                        <?php
                        for ($i= 2000 ; $i <= 2050 ; $i++) { ?>
                            <option value="<?php print $i;?>"><?php print $i;?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col">
                <button type="submit" class="btn btn-primary">Print</button>
                </div>
            </div>
            </form>
            <br>
            <table id="DataTable_users" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>QR Code</th>
                        <th>Nama Pemanen</th>
                        <th>Tanggal Absen</th>
                        <th>Waktu Absen</th>
                    </tr>
                </thead>
            </table>
          </div>
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
                url: '<?php echo base_url('Dataabsen/Data_Absen');?>',
                type: 'POST',
                data: {'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>'}
            },
            order:[[1,'DESC']],
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'pdf', 'print'
            ],
            columns:[
                {
                    data:'no',
                    searchable:false,
                    orderable:false,
                },
                { data:'id_absen' },
                { data:'nama_pemanen' },
                { data:'tanggal' },
                { data:'jam' }
            ],
        });
    });


</script>