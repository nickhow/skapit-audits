
  <div class="container mt-5">
        <div class="row justify-content-md-center ">
          <div class="col-10 col-md-8 col-lg-6 p-4 bg-white rounded">
              <h2>Upload Multiple Properties</h2>
                
                <?php  if(isset($validation)): ?>
                <div class="alert alert-warning">
                   <?= $validation->listErrors() ?>
                </div>
                <?php endif;?>

                <div>
                    <h3>Download template</h3>
                </div>

                <div class="p2"></div> 

                <div>
                    <form method="post" id="add_create" enctype="multipart/form-data" name="add_create" action="<?= site_url('/account/upload') ?>">
                        <h3>Upload Properties</h3>

                        <div class="form-group pt-2">
                            <label>Send out audits for these properties?</label>
                            <select id="send_audits" name="send_audits" class="form-select">
                                <option value='0' selected >No</option>
                                <option value='1' >Yes</option>
                            </select>
                        </div>

                        <div class="form-group pt-2">
                            <label>Send out audits for these properties?</label>
                            <input type="file" name="property_upload" />
                        </div>

                        <div class="form-group p-3 text-center">
                            <button type="submit" class="btn btn-primary btn-block">Create Properties</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
