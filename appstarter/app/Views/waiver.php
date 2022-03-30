  <div class="container mt-5">
    <div class="row">
        <div class="col-12 text-center">
            <h3 class="p-0"><?php echo $account_obj['accommodation_name']; ?></h3>
        </div>
    </div>
    <h1><?php echo ucfirst($text['waiver_title']) ?></h1> 
    <form method="post" id="update_audit" name="update_audit" action="<?= site_url('/save-responses') ?>">
        <input type="hidden" name="id" id="id" value="<?php echo $audit_obj['id']; ?>">
    
        <div class="row my-3" id="extra_info_included">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($text['waiver_extra_info']) ?></b></label>
                    <select name="waiver_extra_info_included" id="waiver_extra_info_included" class="form-select">
                        <option id="No" value="0" data-response="No"><?php echo ucfirst($text['no']); ?></option>
                        <option id="Yes" value="1" data-response="Yes"><?php echo ucfirst($text['yes']); ?></option>
                    </select>
                </div>
            </div>   
        </div>
        <div class="row my-3" id="extra_info">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($text['waiver_details']); ?></b></label>
                    <textarea name="waiver_extra_info" id="waiver_extra_info" rows="3" class="form-control w-100"></textarea>
                </div>
            </div>   
        </div>
        
        <div class="row my-3" id="waiver_body">
            <div class="col">
                <?php echo ucfirst($text['waiver_body']) ?>
            </div>
        </div>
        
        <div class="row my-3" id="waiver_policy">
            <div class="col">
                <a href="<?php echo site_url('/privacy-policy/'); echo $audit_obj['language']; ?>" target="_blank"> <?php echo ucfirst($text['privacy_policy_title']); ?> </a>
            </div>
        </div>
        
        <div class="row my-3" id="waiver_about">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($text['waiver_name']) ?></b></label>
                    <input name="waiver_name" id="waiver_name" class="form-control"/>
                </div>
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($text['waiver_job_title']) ?></b></label>
                    <input name="waiver_job_title" id="waiver_job_title" class="form-control"/>
                </div>
                <!-- remove email from here, it's currently unused.
                <div class="form-group"> 
                    <label class="pb-2"><b><?php // echo ucfirst($text['waiver_email']) ?></b></label>
                    <input name="waiver_email" id="waiver_email" class="form-control"/>
                </div>
                -->
            </div>   
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 p-4 bg-white border border-warning text-center">
                <label for="waiver"><?php echo ucfirst($text['waiver_sign']); ?></label>
                <input type="checkbox" id="waiver" name="waiver" required>
                
                <div class="form-group pt-4">
                    <button type="submit" name="save" value="1" class="btn btn-primary btn-block"><?php echo strtoupper($text['waiver_continue']); ?></button>
                </div>
            </div>
        </div>

        <div class="row my-3" id="waiver_last">
            <div class="col">
                <?php echo ucfirst($text['waiver_last']) ?>
            </div>
        </div>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
