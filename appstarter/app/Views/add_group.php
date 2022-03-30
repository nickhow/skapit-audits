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
                    <div class="pt-4 pb-2"><b>Group Configuration</b></div>
                    <div class="form-group pt-2">
                        <label>Sub-group settings</label>
                        <select id="uses_sub_groups" name="uses_sub_groups" class="form-select">
                            <option value='0' selected >Do not enable sub-groups</option>
                            <option value='1' >Enable sub-groups</option>
                            <option value='2' >This is a sub-group</option>
                        </select>
                        <small class="text-secondary">Sub-groups enable the group to create and manage their own groups, this may be used for separately operated sites within a group. We currently only support 1 additional group level. Sub-groups should only be enabled when required, not as standard.</small>
                    </div>
                    
                    <div class="form-group pt-2" id="group_mapping_container" style="display:none;">
                        <label>Which major group is this part of?</label>
                        <select id="group_mapping" name="group_mapping" class="form-select" disabled>
                            <?php foreach($groups as $group) { ?>
                                <option value="<?php echo $group['id'] ?>"  <?php echo set_select('group_id',$group['id'] , ( !empty($data) && $data == $group['id']  ? TRUE : FALSE ));?>  ><?php echo  $group['name']  ?></option>
                            <?php } ?>
                            </select>
                    </div>
                
                <div class="pt-4 pb-2"><b>Charge Settings</b></div>
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
                  <div class="pt-4 pb-2"><b>Group Manager Details</b></div>
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
  
  <?php if(session()->get('is_admin')): ?>
    <script>

        function updateGroupForm(){
            let subGroups = document.getElementById('uses_sub_groups');
            let mainGroupContainer = document.getElementById('group_mapping_container');
            let mainGroup = document.getElementById('group_mapping');
            if(subGroups.value == "2"){
                mainGroup.disabled = false;
                mainGroupContainer.style.display="block";
            } else {
                mainGroup.disabled = true;
                mainGroupContainer.style.display="none";
            }
        }
        document.getElementById('uses_sub_groups').addEventListener("change",updateGroupForm);
    </script>
  <?php endif ?>
