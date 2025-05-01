  <div class="container mt-5">
        <div class="row justify-content-center ">
          <div class="col-10 col-md-8 col-lg-6 p-4 bg-white rounded h-100">
              <h2>Create new Health and Safety audit</h2>

              <?php if(session()->getFlashdata('msg')):?>
                  <div class="alert alert-warning">
                      <?= session()->getFlashdata('msg') ?>
                  </div>
              <?php endif;?>

            <form method="post" id="add_create" name="add_create" 
            action="<?= site_url('/submit-audit-form') ?>">
              
              <div class="form-group py-2">
                <label>Property</label>
                <select id="account" name="account" class="">
                  <option value="" selected disabled>Select a property</option>
                    <?php 
                      foreach($accounts as $account){
                        echo "<option value=".$account['id'].">".$account['accommodation_name']."</option>";
                      }
                    ?>
                </select>
                <small id="account_settings_description"></small>
              </div>
              
            <?php if(session()->get('is_admin')): ?>
              <div class="pb-2">
                <h4 class="m-0 pt-3">Payment</h4>
                <div class="form-group pt-2">
                  <label>Does this audit require payment?</label>
                  <select name="is_payable" id="isPayable" class="form-select">
                      <option value='0'  selected >No</option>
                      <option value='1'  >Yes</option>
                  </select>
                </div>

              
                <div class="form-group pt-2" id="payableAmountContainer">
                  <label>What is the cost for the audit (EUR)?</label>
                  <div class="input-group">
                      <div class="input-group-prepend">
                          <span class="input-group-text" style="border-radius:0.25rem 0 0 0.25rem!important" id="currency">€</span>
                      </div>
                      <input type="number" name="payable_amount" id="payableAmount" class="form-control" value="50.00" min="1" placeholder="Charge amount" aria-describedby="currency"/>
                  </div>
                </div>
              </div>
            <?php endif; ?>

            <h4 class="m-0 pt-3">Audit</h4>
            <div class="form-group pt-2">
                <label>Language</label>
                <select name="language" id="language" class="form-select">
                    <option value="en">English</option>
                    <option value="fr">Français</option>
                    <option value="de">Deutsch</option>
                    <option value="it">Italiano</option>
                    <option value="es">Español</option>
                </select>
              </div>

    <!-- Custom Text Section -->
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
    </div>  
              <div class="form-group pt-2" id="custom_intro_text_container">
                <label>Custom introduction text</label>
                <textarea name="custom_intro_text" id="custom_intro_text" class="form-control" rows="3"></textarea>
             </div>
    <!-- Custom Text Section -->

              <div class="form-group p-3 text-center">
                <button type="submit" class="btn btn-primary btn-block">Send Audit</button>
              </div>
            </form>
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

    </div>
  </div>

  <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
  <!--<script src='https://cdn.tiny.cloud/1/storhqrnsr6cvl7y316t629se8numd9vx3eejd804dxjmzz6/tinymce/5/tinymce.min.js' referrerpolicy="origin"></script>-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.5/js/standalone/selectize.min.js" integrity="sha512-JFjt3Gb92wFay5Pu6b0UCH9JIOkOGEfjIi7yykNWUwj55DBBp79VIJ9EPUzNimZ6FvX41jlTHpWFUQjog8P/sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.5/css/selectize.bootstrap5.min.css" integrity="sha512-w4sRMMxzHUVAyYk5ozDG+OAyOJqWAA+9sySOBWxiltj63A8co6YMESLeucKwQ5Sv7G4wycDPOmlHxkOhPW7LRg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <script>
  $(document).ready(function () {
      $('#account').selectize({
        placeholder: 'Select a property',
        sortField: 'text',
        onChange: function() {
          getChargeSettings();
        }
      });
  });
  </script>
<!--
  <script>
      tinymce.init({
        selector: '#custom_intro_text'
      });
    </script>
-->


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
        //tinymce.triggerSave();
        //intro = document.getElementById('custom_intro_text').value;
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


    <?php if(session()->get('is_admin')): ?>

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
  

      <script>
          function getChargeSettings(){
            let account = document.getElementById('account').value;
                $.ajax({
                    url: '<?php echo base_url(); ?>/account/charge-settings',
                    type: 'POST',
                    data: {
                        'id': account ,
                    },
                    success: function(msg) {
                       // console.log(msg);
                        updateChargeSettings(msg);
                        hideAmount();
                    }               
                });
          }
          
          function updateChargeSettings(msg){
              let settings = JSON.parse(msg);
              
              var text;
              
              if(settings.group_id == 0){
                  text = "Stand alone property, no group charge settings to suggest.";
                  //reset the inputs
                  document.getElementById('isPayable').value = '1'
                  document.getElementById('payableAmount').value = '50.00'
              } else {
                document.getElementById('isPayable').value = settings.is_payable;
                document.getElementById('payableAmount').value = settings.payable_amount;
                  
                text = "Group is charged: ";
                if(settings.is_payable == 1) { 
                    text += "Yes. Group charge amount: €"+settings.payable_amount;
                } else { 
                    text += "No";
                }
              }
              
              document.getElementById('account_settings_description').innerHTML = text;
              
          }
          
          document.getElementById('account').addEventListener("change", getChargeSettings);
          getChargeSettings();
          
      </script>
    <?php endif; ?>