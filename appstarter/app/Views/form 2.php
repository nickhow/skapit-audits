  <style>
    .container {
      max-width: 500px;
    }

    .error {
      display: block;
      padding-top: 5px;
      font-size: 14px;
      color: red;
    }
  </style>
  <div class="container mt-5">
    <form method="post" id="update_account" name="update_account" 
    action="<?= site_url('/update-audit') ?>">
      <input type="hidden" name="id" id="id" value="<?php echo $audit_obj['id']; ?>">
    <?php if(!$audit_obj['waiver_signed']){ 
            //show the waiver ... 
            include('waiver.php');
          } else { 
                // show the form ... depending on the type of property
                if($audit_obj['type'] == 'hotel'){  
                 include('hotel-form.php');
                } else { ?>
                   <h2>Generic Form</h2> 
    <?php       } 
           }  ?>
        
        
         <h3>Select an image from your computer and upload it to the cloud</h3>
        <?php
                if (isset($error)){
                    echo $error;
                }
            ?>
            
            <input type="file" id="fileupload" name="fileupload" />
                
        
      <div class="form-group">
        <button type="submit" name="save" value="1" class="btn btn-primary btn-block">Save</button>
         <?php if($audit_obj['waiver_signed']){ ?> <button type="submit" name="complete" value="1" class="btn btn-success btn-block">Complete</button> <?php } ?>
      </div>
    </form>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>  <!-- slim has no ajax --> 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>

    <script>
        document.getElementById('profile_image').addEventListener('change', function(){
            async function uploadFile() {
                let formData = new FormData();           
                formData.append("file", fileupload.files[0]);
                await fetch('/upload.php', {
                  method: "POST", 
                  body: formData
                });    
                alert('The file has been uploaded successfully.');
            }
        });
    </script>
