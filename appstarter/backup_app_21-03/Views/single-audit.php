
  <div class="container mt-5">
        <div class="row justify-content-md-center ">
          <div class="col-10 col-md-8 col-lg-6 p-4 bg-white rounded">
              <h2>Update Audit</h2>
                <form method="post" id="update_account" name="update_account" 
                action="<?= site_url('/update-audit') ?>">
                  <input type="hidden" name="id" id="id" value="<?php echo $audit_obj['id']; ?>">
    
                  <div class="form-group pt-2">
                    <label>Language</label>
                    <select name="language" class="form-control">
                        <option value="en" <?php if( $audit_obj['language'] == "en"){echo "selected";} ?> >English</option>
                        <option value="fr" <?php if( $audit_obj['language'] == "fr"){echo "selected";} ?> >French</option>
                        <option value="de" <?php if( $audit_obj['language'] == "de"){echo "selected";} ?> >German</option>
                        <option value="it" <?php if( $audit_obj['language'] == "it"){echo "selected";} ?> >Italian</option>
                        <option value="es" <?php if( $audit_obj['language'] == "es"){echo "selected";} ?> >Spanish</option>
                    </select>
                  </div>
                  
                <?php if(session()->get('is_admin')): ?>
                    <div class="form-group pt-2">
                        <label>Does this audit require payment?</label>
                        <select name="is_payable" id="isPayable" class="form-select">
                            <option value='0' <?php if( $audit_obj['is_payable'] == "0"){echo "selected";} ?> >No</option>
                            <option value='1' <?php if( $audit_obj['is_payable'] == "1"){echo "selected";} ?> >Yes</option>
                        </select>
                      </div>

                
                  <div class="form-group pt-2" id="payableAmountContainer">
                    <label>What is the cost for the audit (EUR)?</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="border-radius:0.25rem 0 0 0.25rem!important" id="currency">€</span>
                        </div>
                        <input type="number" name="payable_amount" id="payableAmount" min="1" class="form-control" value="<?php echo $audit_obj['payable_amount']; ?>" placeholder="Charge amount" aria-describedby="currency"/>
                    </div>
                  </div>
                  
                <?php endif; ?>
                
                  <?php if(session()->get('is_admin')):?>
                      <?php if(in_array($audit_obj['status'], ["complete","reviewing","reviewed"])){ ?>
                      <div class="form-group pt-2">
                        <input class="form-check-input" type="checkbox" value="1" name="paid" id="paid" <?php if( $audit_obj['paid'] ) {echo "checked";} ?> >
                        <label class="form-check-label" for="paid">Hotel Check Paid</label>           
                      </div>
                      <?php }?>
                  <?php endif; ?>
                  
                  <div class="form-group py-3">
                    <a href="<?php echo base_url('audits'); ?>" class="btn btn-outline-dark btn-block"><i class="fas fa-arrow-left"></i> Back</a>
                    <button type="submit" class="btn btn-primary btn-block px-5 mx-2">Save Changes</button>
                  </div>
                </form>
                
                <?php if(!in_array($audit_obj['status'], ["complete","reviewing","reviewed"])){ ?>
                    <div class="row">
                        <div class="col">
                            <a href="<?php echo base_url('audit/'.$audit_obj['id'].'/chase');?>"><div class="btn btn-warning btn-sm"><i class="fas fa-envelope"></i> Send chase email</div></a>
                        </div>
                    </div>
                <?php }?>

  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
    <script>
      function hideAmount(){
        let amount = document.getElementById('payableAmount');
        let container = document.getElementById('payableAmountContainer');
        if(document.getElementById('isPayable').value == 0){
            amount.disabled = true;
            container.style.display="none";
        } else {
            amount.disabled = false;
            container.style.display="block";
        }
      }
      document.getElementById('isPayable').addEventListener("change", hideAmount);
      hideAmount();
  </script>