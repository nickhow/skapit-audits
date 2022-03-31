  <div class="container mt-5">
        <div class="row justify-content-md-center ">
          <div class="col-10 col-md-8 col-lg-6 p-4 bg-white rounded">
              <h2>Create new hotel group</h2>
              
                <?php  if(isset($validation)): ?>
                
                <div class="alert alert-warning">
                   <?= $validation->listErrors() ?>
                </div>
                <?php endif;?>
                
                
                <form method="post" id="add_create" name="add_create" 
                action="<?= site_url('/group/new') ?>">
                  <div class="form-group pt-2 pb-2">
                    <label>Group Name</label>
                    <input type="text" name="group_name" value="<?= set_value('group_name') ?>" class="form-control">
                  </div>
                  
                <?php if(session()->get('is_admin')): ?>
                    <div class="form-group pt-2">
                        <label>Does this group pay for their audits?</label>
                        <select id="isPayable" name="is_payable" class="form-select">
                            <option value='0'>No</option>
                            <option value='1' selected >Yes</option>
                        </select>
                      </div>
                
                  <div class="form-group pt-2" id="payableAmountContainer">
                    <label>What is the cost for the audits (EUR)?</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="border-radius:0.25rem 0 0 0.25rem!important" id="currency">€</span>
                        </div>
                        <input type="number" name="payable_amount" id="payableAmount" class="form-control" value="50.00" min="1" placeholder="Charge amount" aria-describedby="currency"/>
                    </div>
                  </div>
                  
                <?php endif; ?>
                  
                  <h3>Group Manager Details</h3>
                    <div class="form-group pt-2">
                        <label>Group Manager Name</label>
                        <input type="text" name="group_manager_name" placeholder="Name" value="<?= set_value('group_manager_name') ?>" class="form-control" >
                    </div>

                    <div class="form-group pt-2">
                        <label>Username</label>
                        <input type="username" name="username" placeholder="Username" value="<?= set_value('username') ?>" class="form-control" >
                    </div>

                    <div class="form-group pt-2">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" >
                    </div>

                    <div class="form-group pt-2">
                        <label>Confirm password</label>
                        <input type="password" name="confirmpassword" class="form-control" >
                    </div>
            
                  <div class="form-group p-3 text-center">
                    <button type="submit" class="btn btn-primary btn-block">Save Group</button>
                  </div>
                </form>
            </div>
        </div>
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