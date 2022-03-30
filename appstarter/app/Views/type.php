
  <div class="container-md mt-5">
    <div class="row">
        <div class="col-12 text-center">
            <h3 class="p-0"><?php echo $account_obj['accommodation_name']; ?></h3>
        </div>
    </div>
    <h1><?php echo $text['type_title']; ?></h1> 
    <form method="post" id="update_audit" name="update_audit" enctype="multipart/form-data" action="<?= site_url('/save-responses') ?>">
      <input type="hidden" name="id" id="id" value="<?php echo $audit_obj['id']; ?>">
        
        <?php if(session()->getFlashdata('msg')):?>
            <div class="alert alert-warning">
                <?= session()->getFlashdata('msg') ?>
            </div>
        <?php endif;?>
    
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b> <?php echo $text['type_question']; ?></b></label>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="type" id="1" value="1" checked>
                      <label class="form-check-label" for="1">
                        <?php echo $text['type_1']; ?>
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="type" id="2" value="2">
                      <label class="form-check-label" for="2">
                        <?php echo $text['type_2']; ?>
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="type" id="3" value="3">
                      <label class="form-check-label" for="3">
                        <?php echo $text['type_3']; ?>
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="type" id="4" value="5">
                      <label class="form-check-label" for="4">
                        <?php echo $text['type_4']; ?>
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="type" id="4" value="5">
                      <label class="form-check-label" for="5">
                        <?php echo $text['type_5']; ?>
                      </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group my-5 py-3">
        <button type="submit" name="save" value="1" class="btn btn-primary btn-block">
            <?php echo $text['save_button']; ?>
        </button>
      </div>
    </form>
  </div>
  
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
  

