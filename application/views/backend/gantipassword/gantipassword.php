<section class="py-5 slowmotion">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h6 class="text-uppercase mb-0"></h6>
        </div>
        <div class="card-body">
            <form id="myForm">
              
              <div class="form-group">
                <label for="exampleInputPassword1">Password Baru</label>
                <input type="password" class="form-control" name="password_baru" placeholder="Password Baru">
              </div>
              
            </form>
            <button type="submit" id="btnSave" class="btn btn-primary">Ganti Password</button>
        
        </div>
      </div>
    </div>
  </div>
</section>

<script src="<?php echo base_url();?>assets/backend/js/jquery-3.3.1.js"></script>
<script>
    
    $('#btnSave').click(function () {
        var data = new FormData($('#myForm')[0]);
        $('#btnSave').html('<i class="fa fa-save"></i>&nbsp; Ganti Password <img width="30" heigth="30" src="<?php echo base_url();?>assets/backend/img/loading.gif">').attr('disabled', 'disabled');
        $.ajax({
            type : 'ajax',
            method: 'POST',
            url  :'<?php echo site_url('Gantipassword/ubahpassword')?>',
            data : data,
            async: false,
            cache: false,
            contentType:false,
            processData:false,
            dataType :'json',
            success : function(response){
                setTimeout(function(){
                    if (response.success == true) {
                        $('#myForm')[0].reset();
                        $('#btnSave').prop('disabled', false);
                            if (response.type=='Add') {
                                var type = 'Add';
                                swal({
                                  type: 'success',
                                  title: 'Berhasil Input',
                                  footer: ''
                                });
                                $('#btnSave').html('<i class="fa fa-save"></i>&nbsp; Ganti Password');
                            }else if(response.type=='Update'){
                                var type = 'Update';
                                swal({
                                  type: 'success',
                                  title: 'Berhasil Ganti Password',
                                  footer: ''
                                });
                                $('#btnSave').html('<i class="fa fa-save"></i>&nbsp; Ganti Password');
                            }
                          }else {
    
                        if (response.error) {
                            swal({
                              type: 'warning',
                              title: 'Peringatan !!!!',
                              html: response.error,
                              footer: ''
                            });
                            $('#btnSave').html('<i class="fa fa-save"></i>&nbsp; Ganti Password');
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
                $('#btnSave').html('<i class="fa fa-save"></i>&nbsp; Ganti Password');
                $('#btnSave').prop('disabled', false);
            }
        });
    });

    
</script>



