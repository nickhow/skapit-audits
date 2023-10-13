
  <div class="container mt-5">
        <div class="row justify-content-md-center ">
          <div class="col-10 col-md-8 col-lg-6 p-4 bg-white rounded">
              <h2>Update Question</h2>
    <form method="post" id="update_question" name="update_question" enctype="multipart/form-data" action="<?= site_url('/update-question') ?>">
      <input type="hidden" name="id" id="id" value="<?php echo $question_obj['id']; ?>">

      <div class="form-group pt-2">
        <label>Question</label>
        <input type="text" name="question" class="form-control" value="<?php echo $question_obj['question']; ?>">
      </div>
      
        <div class="form-group pt-2">
        <label>English</label>
        <input type="text" name="en" class="form-control" value="<?php echo $question_obj['en']; ?>">
      </div>
      
            <div class="form-group pt-2">
        <label>Spanish</label>
        <input type="text" name="es" class="form-control" value="<?php echo $question_obj['es']; ?>">
      </div>
      
            <div class="form-group pt-2">
        <label>French</label>
        <input type="text" name="fr" class="form-control" value="<?php echo $question_obj['fr']; ?>">
      </div>
      
            <div class="form-group pt-2">
        <label>German</label>
        <input type="text" name="de" class="form-control" value="<?php echo $question_obj['de']; ?>">
      </div>
      
            <div class="form-group pt-2">
        <label>Italian</label>
        <input type="text" name="it" class="form-control" value="<?php echo $question_obj['it']; ?>">
      </div>
      
      <div class="form-group pt-2">
        <label>Question Helper (Optionally upload an image or supply a link to a video)</label>
        <input type="text" name="helper_url" class="form-control" value="<?php echo $question_obj['helper_url']; ?>">

        <input type="file" name="helper_image" />
      </div>  
      
      <?php if($file){ var_dump($_POST); ?>
      
        <div id="<?php echo $file['file_name']; ?>">
        <p class="fs-5"><span class="text-primary">Current file: </span><?php echo $file['original_name']; ?></p>
        <div class="row">
          <div class="col-12 col-md-4 p-0">
            <a href="<?php  echo base_url()."/uploads/helper_images/".$question_obj['id']."/".$file['file_name']; ?>" target='_blank' ><div class="col-12 btn btn-sm btn-secondary">View</div></a>
          </div>
          <div class="col-12 col-md-4 mx-3 btn btn-sm btn-outline-danger" onclick="deleteFile('<?php  echo $file['file_name'] ?>')">Delete</div>
          </div>
        </div>
      <?php } ?>



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
    if ($("#update_question").length > 0) {
      $("#update_question").validate({
        rules: {
          question: {
            required: true,
          },
         en: {
            required: true,
          },
                   es: {
            required: true,
          },
                   de: {
            required: true,
          },
                   fr: {
            required: true,
          },
                   it: {
            required: true,
          },
          
        },
        messages: {
          question: {
            required: "Question is required.",
          },
                    en: {
            required: "English is required.",
          },
                    de: {
            required: "German is required.",
          },
                    it: {
            required: "Italian is required.",
          },
                    fr: {
            required: "French is required.",
          },
                    es: {
            required: "Spanish is required.",
          },
        },
      })
    }
  </script>
