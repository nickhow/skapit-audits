
  <div class="container mt-5">
        <div class="row justify-content-md-center ">
          <div class="col-10 col-md-8 col-lg-6 p-4 bg-white rounded">
              <h2>Create new property account</h2>
                
                <?php  if(isset($validation)): ?>
                <div class="alert alert-warning">
                   <?= $validation->listErrors() ?>
                </div>
                <?php endif;?>
                
            <form method="post" id="add_create" name="add_create" action="<?= site_url('/account/new') ?>">
            <h3>Property Contact Details</h3>
              <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="<?= set_value('name') ?>" class="form-control">
              </div>
        
              <div class="form-group pt-2">
                <label>Email</label>
                <input type="text" name="email" value="<?= set_value('email') ?>" class="form-control">
              </div>
              
              <div class="form-group pt-2">
                <label>Phone</label>
                <input type="text" name="phone" value="<?= set_value('phone') ?>" class="form-control">
              </div>
              
              <?php $session = session();  if($session->get('is_admin')): ?>
              
              <div class="form-group pt-2">
                <label>Accommodation Group</label>
                <select id="group_id" name="group_id" class="form-select">
                        <option value="0">No Group</option>
                        <?php foreach($groups as $group) { ?>
                            <option value="<?php echo $group['id'] ?>"  <?php echo set_select('group_id',$group['id'] , ( !empty($data) && $data == $group['id']  ? TRUE : FALSE ));?>  ><?php echo  $group['name']  ?></option>
                        <?php } ?>
                        
                </select>
              </div>
              
              <?php else: ?>
                <input type="hidden" id="group_id" name="group_id" value="<?php echo $session->get('group_id')?>">
              <?php endif; ?>
              
              <div id="isGroup">
                  <div class="form-group pt-2">
                    <label>Create User Profile</label>
                    <select id="group_manager" name="is_group_manager" class="form-select">
                            <option value="0" <?php echo set_select('is_group_manager','0' , ( !empty($data) && $data == '0'  ? TRUE : FALSE ));?> >No</option>
                            <option value="1" <?php echo set_select('is_group_manager','1' , ( !empty($data) && $data == '1'  ? TRUE : FALSE ));?> >Yes</option>
                    </select>
                    <p><small>If you want the contact for this property to be able to login and manage your group - create and manage the group's properties, audits and users.</small></p>
                  </div>
              
                  <div id="isGroupManager" class="p-2 border">
                      <h4>Group Manager Login</h4>
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
                  </div>
              </div>
              <h3 class="mt-3">About the Property</h3>
              <div class="form-group pt-2">
                <label>Accommodation Name</label>
                <input type="text" name="accommodation_name" value="<?= set_value('accommodation_name') ?>" class="form-control">
              </div>
              <div class="form-group pt-2">
                <label>Resort</label>
                <input type="text" name="resort" value="<?= set_value('resort') ?>" class="form-control">
              </div>
              <div class="form-group pt-2">
                <label>Country</label>
                <input type="text" name="country" value="<?= set_value('country') ?>" class="form-control">
              </div>
              
              <div class="form-group pt-2">
                <label>Notes</label>
                <textarea class="form-control" name="notes" value="<?= set_value('notes') ?>" rows="3"></textarea>
              </div>
              
              <div class="row mt-3">
                  <div class="col">
                      <h3>Send Audit</h3>
                  </div>
              </div>
              
              <div class="form-group pt-2">
                <label>Language</label>
                <select id="language" name="language" class="form-select">
                    <option value="" selected>Don't send an audit yet</option>
                    <option value="en">English</option>
                    <option value="fr">French</option>
                    <option value="de">German</option>
                    <option value="it">Italian</option>
                    <option value="es">Spanish</option>
                </select>
              </div>
              
              <div id="isPayableContainer" style="display: none;">
        
                  <div class="form-group pt-2">
                    <label>Does this audit require payment?</label>
                    <select id="isPayable" name="is_payable" class="form-select">
                        <option value='0'   >No</option>
                        <option value='1' selected >Yes</option>
                    </select>
                  </div>
                  
                  <div class="form-group pt-2" id="payableAmountContainer">
                    <label>What is the cost for the audit (EUR)?</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="border-radius:0.25rem 0 0 0.25rem!important" id="currency">€</span>
                        </div>
                        <input type="number" name="payable_amount" id="payableAmount" class="form-control" value="50.00" min="1" placeholder="Charge amount" aria-describedby="currency" disabled="disabled"/>
                    </div>
                  </div>
              
              </div>

    <!-- Custom Text Section -->

              <div class="form-group pt-2">
                <label>Add a custom introduction to the email?</label>
                <select name="custom_intro" id="custom_intro" class="form-select">
                    <option value='0'  selected >No</option>
                    <option value='1'  >Yes</option>
                </select>
              </div>

            
              <div class="form-group pt-2" id="custom_intro_text_container">
                <label>Custom introduction text</label>
                <textarea name="custom_intro_text" id="custom_intro_text" class="form-control" rows="3"></textarea>
              </div>
    <!-- Custom Text Section -->
        
              <div class="form-group p-3 text-center">
                <button type="submit" class="btn btn-primary btn-block">Add Property</button>
              </div>
            </form>  
          </div>
      </div> 

      </div>

    

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
  <script src='https://cdn.tiny.cloud/1/storhqrnsr6cvl7y316t629se8numd9vx3eejd804dxjmzz6/tinymce/5/tinymce.min.js' referrerpolicy="origin"></script>

  <script>
      tinymce.init({
        selector: '#custom_intro_text'
      });
    </script>

    <script>
        function hideShowEmailText(){
          let custom_intro = document.getElementById('custom_intro');
          let custom_intro_text = document.getElementById('custom_intro_text');
          let custom_intro_text_container = document.getElementById('custom_intro_text_container');
          if(document.getElementById('custom_intro').value == 0){
              custom_intro_text.disabled = true;
              custom_intro_text_container.style.display="none";
          } else {
              custom_intro_text.disabled = false;
              custom_intro_text_container.style.display="block";
          }
        }
        document.getElementById('custom_intro').addEventListener("change", hideShowEmailText);
        hideShowEmailText();
    </script>


  <script>
    if ($("#add_create").length > 0) {
      $("#add_create").validate({
        rules: {
          name: {
            required: true,
          },
          email: {
            required: true,
            maxlength: 60,
            email: true,
          },
        },
        messages: {
          name: {
            required: "Name is required.",
          },
          email: {
            required: "Email is required.",
            email: "It does not seem to be a valid email.",
            maxlength: "The email should be or equal to 60 chars.",
          },
        },
      })
    }
  </script>
  <script>
  let g_id = document.getElementById('group_id');
  let g_manager = document.getElementById('group_manager');
  let a_lang = document.getElementById('language');
  
    function updateForm(input, element){
        if(input.value == 0) {
                document.getElementById(element).style.display="none";
            } else {
                document.getElementById(element).style.display="block";
            }
        };
        
    function updatePayable(){
        if(a_lang.value !== "" && g_id.value == '0') {
            document.getElementById('isPayableContainer').style.display="block";
            document.getElementById('isPayable').removeAttribute("disabled");
            hideAmount();
        } else {
            document.getElementById('isPayableContainer').style.display="none";
            document.getElementById('isPayable').setAttribute("disabled", "disabled");
            document.getElementById('payableAmount').setAttribute("disabled", "disabled");
        }
        //hideAmount();
    }
    
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
   
    updateForm(g_id, 'isGroup');
    updateForm( g_manager, 'isGroupManager' ); 
   
    g_id.addEventListener('change', function(){ updateForm(g_id, 'isGroup') });
    g_manager.addEventListener('change', function(){ updateForm( g_manager, 'isGroupManager' ) });
    
    g_id.addEventListener('change', function(){ updatePayable() });
    a_lang.addEventListener('change', function(){ updatePayable(); });

   
  </script>
