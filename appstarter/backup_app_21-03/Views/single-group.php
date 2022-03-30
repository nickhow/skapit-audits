
  <div class="container mt-5">
        <div class="row justify-content-md-center ">
          <div class="col-10 col-md-8 col-lg-6 p-4 bg-white rounded">
              <h2>Update Group</h2>
    <form method="post" id="update_group" name="update_group" 
    action="<?= site_url('/update-group') ?>">
      <input type="hidden" name="id" id="id" value="<?php echo $group_obj['id']; ?>">

      <div class="form-group pt-2">
        <label>Name</label>
        <input type="text" name="name" class="form-control" value="<?php echo $group_obj['name']; ?>">
      </div>
      
        <?php if(session()->get('is_admin')): ?>
            <div class="form-group pt-2">
                <label>Does this group pay for their audits?</label>
                <select id="isPayable" name="is_payable" class="form-select">
                    <option value='0' <?php if( $group_obj['is_payable'] == "0") { echo "selected"; } ?> >No</option>
                    <option value='1' <?php if( $group_obj['is_payable'] == "1") { echo "selected"; } ?> >Yes</option>
                </select>
            </div>

            <div class="form-group pt-2" id="payableAmountContainer">
                <label>What is the cost for the audit (EUR)?</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="border-radius:0.25rem 0 0 0.25rem!important" id="currency">€</span>
                        </div>
                        <input type="number" name="payable_amount" id="payableAmount" min="1" class="form-control" value="<?php echo $group_obj['payable_amount']; ?>" placeholder="Charge amount" aria-describedby="currency"/>
                    </div>
                  </div>
                  
        <?php endif; ?>

      <div class="form-group py-3">
        <a href="<?php echo base_url('groups'); ?>" class="btn btn-outline-dark btn-block"><i class="fas fa-arrow-left"></i> Back</a>
        <button type="submit" class="btn btn-primary btn-block px-5 mx-2">Save Changes</button>
      </div>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
  <script>
    if ($("#update_account").length > 0) {
      $("#update_account").validate({
        rules: {
          name: {
            required: true,
          },
        },
        messages: {
          name: {
            required: "Name is required.",
          },
        },
      })
    }
  </script>
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