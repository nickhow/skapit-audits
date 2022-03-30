  <div class="container mt-5">
        <div class="row justify-content-md-center ">
          <div class="col-10 col-md-8 col-lg-6 p-4 bg-white rounded">
              <h2>Create new Health and Safety audit</h2>

            <form method="post" id="add_create" name="add_create" 
            action="<?= site_url('/submit-audit-form') ?>">
              
              <div class="form-group pt-2">
                <label>Language</label>
                <select name="language" class="form-select">
                    <option value="en">English</option>
                    <option value="fr">French</option>
                    <option value="de">German</option>
                    <option value="it">Italian</option>
                    <option value="es">Spanish</option>
                </select>
              </div>
              
                <div class="form-group pt-2">
                <label>Property</label>
                <select name="account" class="form-select">
                    <?php 
                        foreach($accounts as $account){
                            echo "<option value=".$account['id'].">".$account['accommodation_name']."</option>";
                        }
                    ?>
                </select>
              </div>
              
            <?php if(session()->get('is_admin')): ?>
            <div class="form-group pt-2">
                <label>Does this audit require payment?</label>
                <select name="is_payable" class="form-select">
                    <option value='0'   >No</option>
                    <option value='1' selected >Yes</option>
                </select>
              </div>
            <?php endif; ?>
        
              <div class="form-group p-3 text-center">
                <button type="submit" class="btn btn-primary btn-block">Send Audit</button>
              </div>
            </form>
        </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
