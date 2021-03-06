<section class="py-5 slowmotion">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h6 class="text-uppercase mb-0">
                <label class="pull-right">Data Pemanen</label>
            </h6>
          </div>
          <div class="card-body">
          <div style="overflow-x:auto;">
            <table id="DataTable_users" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kerani Askep</th>
                        <th>Kerani Kcs</th>
                        <th>Mandor Kebun</th>
                        <th>Photo Pemanen</th>
                        <th>Nama Pemanen</th>
                        <th>Kebun</th>
                        <th>Afdeling</th>
                        <th>Barcode</th>
                        <th>Img Barcode</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
            </table>
          </div>
          </div>
        </div>
      </div>
    </div>
  </section>


<!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <?php echo form_open('', array('id' => 'myForm')); ?>
          <div class="row">
          <div class="form-group col-md-6">
            <label for="Pemanen">Barcode:</label>
            <input type="text" class="form-control" name="barcode" readonly>
            <input type="hidden" name="id">
          </div>

          <div class="form-group col-md-6">
            <label for="Pemanen">Nama Pemanen:</label>
            <input type="text" class="form-control" name="nama_pemanen">
          </div>
          </div>

          <div class="form-group">
            <label for="Photo">Keterangan:</label>
            <textarea  class="form-control" name="keterangan"></textarea>
          </div>

          <div class="row">
          <div class="form-group col-md-6">
            <label for="email">Photo:</label>
            <input type="file" class="form-control" name="photo">
          </div>

          <div class="form-group gam col-md-6">
             <img src="" id="img2" width="100" height="100">
          </div>
          </div>

        <?php echo form_close(); ?>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="btnSave" ><i class="fa fa-save"></i>&nbsp;Simpan</button>
      </div>

    </div>
  </div>
</div>
<script src="<?php echo base_url();?>assets/backend/js/jquery-3.3.1.js"></script>
<script type="text/javascript">
    

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
                url: '<?php echo base_url('DataPemanenkeraniaskep/data_pemanen');?>',
                type: 'POST',
                data: {'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>'}
            },
            order:[[1,'ASC']],
            columns:[
                {
                    data:'no',
                    searchable:false,
                    orderable:false,
                },
                
                { data:'keraniaskep' },
                { data:'keranikcs' },
                { data:'nama_lengkap' },
                { data:'image' },
                { data:'nama_pemanen' },
                { data:'nama_kebun' },
                { data:'nama_afdeling' },
                { data:'barcode' },
                { data:'img_barcode' },
                { data:'keterangan' }
            ],
        });
    });

</script>