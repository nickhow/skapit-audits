
  <div class="container mt-5">
        <div class="row justify-content-md-center ">
          <div class="col-10 col-md-8 col-lg-6 p-4 bg-white rounded">
              <h2>Upload Multiple Properties</h2>
                
                <?php  if(isset($validation)): ?>
                <div class="alert alert-warning">
                   <?= $validation->listErrors() ?>
                </div>
                <?php endif;?>
                <?php if(session()->getFlashdata('msg')):?>
                    <div class="alert alert-warning">
                        <?= session()->getFlashdata('msg') ?>
                    </div>
                <?php endif;?>

                <div>
                    <h3>Download template</h3>
                </div>
                <div class="row">
                    <div class="col-12 col-md-4 col-lg-3">
                        <a href="<?php if(session()->get('is_admin')||session()->get('enable_groups')){echo base_url()."/uploads/templates/template_admin.csv";}else{echo base_url()."/uploads/templates/template.csv";} ?>" download >
                            <div class="btn btn-outline-secondary m-2">
                                <i class="fas fa-download"></i> Download
                            </div>
                        </a>
                    <div>
                    <div class="col-12 col-md-8 col-lg-9">
                        <div class="form-text">
                            Please remove from the template file the example row of data. You can keep or remove the row of titles, they will not be added in the import.
                        </div>
                    </div>
                </div>
                <?php if(is_array($groups)): ?>
                    <div class="accordion pt-4" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                            <button id="groups_button" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Show group IDs
                            </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Group Name</th>
                                                <th scope="col">Group ID</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                            foreach($groups as $group){
                                                echo ("<tr>");
                                                echo ("<td>".$group['name']."</td>");
                                                echo ("<td>".$group['id']."</td>");
                                                echo ("</tr>");
                                            };
                                            echo ("</tbody></table");  
                                        ?> 
                                </div>
                            </div>
                        </div>
                    </div>
                <?php    endif; ?>

                <div class="py-2"></div> 

                <div>
                    <form method="post" id="add_create" enctype="multipart/form-data" name="add_create" action="<?= site_url('/account/upload') ?>">
                        <h3>Upload Properties</h3>

                        <div class="form-group pt-4">
                            <label>Upload the CSV file</label>
                            <input type="file" name="property_upload" />
                        </div>

                        <h3 class="pt-4"> Audit Settings</h3>

                        <div class="form-group pt-2">
                            <label>Send out audits for these properties?</label>
                            <select id="send_audits" name="send_audits" class="form-select">
                                <option value='0' selected >No</option>
                                <option value='1' >Yes</option>
                            </select>
                        </div>


        <?php if(session()->get('is_admin')): ?>

                        <div id="isPayableContainer" style="display: none;">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group pt-2">
                                        <label>Does this audit require payment?</label>
                                        <select id="isPayable" name="is_payable" class="form-select">
                                            <option value='0'   >No</option>
                                            <option value='1' selected >Yes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">            
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
                            </div>
                        </div>
        <?php endif; ?>

                        <!-- Custom Text Section -->
                        <div id="email_container" style="display: none;">

                            <div class="form-group pt-2">
                                <label>Language</label>
                                <select id="language" name="language" class="form-select">
                                    <option value="en">English</option>
                                    <option value="fr">French</option>
                                    <option value="de">German</option>
                                    <option value="it">Italian</option>
                                    <option value="es">Spanish</option>
                                </select>
                            </div>

                            <div class="row pt-2 g-3">
                                <div class="col-12 col-md-6">
                                    <div class="form-group pt-2">
                                        <label>Add a custom introduction to the email?</label>
                                        <select name="custom_intro" id="custom_intro" class="form-select">
                                            <option value='0'  selected >No</option>
                                            <option value='1'  >Yes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-none d-md-block col-6 align-self-end">
                                    <div class="form-group pt-2 text-end">
                                        <!-- Button trigger modal -->
                                        <button id="view_email" type="button" class="btn btn-outline-secondary" >Preview Email</button> <!-- data-bs-toggle="modal" data-bs-target="#exampleModal" -->
                                    </div>
                                </div>

                        
                                <div class="form-group pt-2" id="custom_intro_text_container">
                                    <label>Custom introduction text</label>
                                    <textarea name="custom_intro_text" id="custom_intro_text" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- Custom Text Section -->



                        <div class="form-group p-3 text-center">
                            <button type="submit" class="btn btn-primary btn-block">Create Properties</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Email Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-white">
                <div class="col border p-4 bg-light">
                    <div id="showEmail"></div>
                </div>
            </div>

            </div>
        </div>
    </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
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
        let a_lang = document.getElementById('language');
        let send_audits = document.getElementById('send_audits');

        function updateCustomText(){
            if(send_audits.value == 1) {
                document.getElementById('email_container').style.display="block";
            }else{
                document.getElementById('email_container').style.display="none";
            }
        }
    
    <?php if(session()->get('is_admin')):?>        
    
            function updatePayable(){
                if(send_audits.value == 1) {
                    document.getElementById('isPayableContainer').style.display="block";
                    document.getElementById('isPayable').removeAttribute("disabled");
                    hideAmount();
                } else {
                    document.getElementById('isPayableContainer').style.display="none";
                    document.getElementById('isPayable').setAttribute("disabled", "disabled");
                    document.getElementById('payableAmount').setAttribute("disabled", "disabled");
                }
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
    <?php endif; ?>    

    send_audits.addEventListener('change', function(){ updatePayable(); updateCustomText(); });
    a_lang.addEventListener('change', function(){ updateCustomText(); });
    
    updatePayable(); 
    updateCustomText();
    </script>

    <script>
        function getEmailHtml(){
            var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
            var lang = document.getElementById('language').value;

            $.ajax({
                url: '<?php echo base_url(); ?>/email/new_audit/'+lang,
                type: 'get',
                success: function(emailResponse) {
                emailResponse = JSON.parse(emailResponse);
                var  emailHtml = emailResponse.html;

                if(document.getElementById('custom_intro').value == 0){
                    intro = "";
                } else {
                    tinymce.triggerSave();
                    intro = document.getElementById('custom_intro_text').value;
                }
                emailHtml = emailHtml.replace('__custom_intro__', intro);
                document.getElementById('showEmail').innerHTML = emailHtml;
                myModal.show();
                }               
            });
        }
        document.getElementById('view_email').addEventListener("click", getEmailHtml);
    </script>
<script>
    var button = document.getElementById('groups_button');
    
    function showHideText(){   
        if(button.classList.contains('collapsed')){
            button.innerHTML = "Show group IDs";
        } else {
            button.innerHTML = "Hide group IDs";
        }
    }
    button.addEventListener('click', showHideText);
</script>