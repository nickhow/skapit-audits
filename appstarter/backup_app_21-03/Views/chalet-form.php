
  <div class="container-md mt-5">
    <h1>Chalet Form</h1> 
    <form method="post" id="update_audit" name="update_audit" enctype="multipart/form-data" action="<?= site_url('/save-responses') ?>">
      <input type="hidden" name="id" id="id" value="<?php echo $audit_obj['id']; ?>">

        <?php  /* Question 1 */ $question = $questions[0]; ?> 
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                        foreach($question['answers'] as $answer) { 
                            echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'";
                            echo "data-response=".$answer['en_ans'];
                            if($question['response']) {
                                if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                            }
                            echo ">" . ucfirst($answer['answer']) . "</option>";
                         } 
                        ?>
                    </select>
                </div>
            </div>
        </div>
     
     <div class="row my-3" id="section-files">
            <div class="col">
                <div class="form-group"> 
                     <?php
                        if (isset($error)){
                            echo $error;
                        }
                    ?>
                    <table>
                       <?php foreach($file_obj as $file) { 
                             echo "<tr><td>" . $file['original_name'] . "</td><td><a href='".base_url()."/uploads/".$file['file_name']."' target='_blank' >View</a></td><td>Delete<td></tr>";
                        }?>
                        
                    </table>
                </div>
                <input type="file" name="file_one" />
        
                <input type="file" name="file_two" />
                
                <input type="file" name="file_three" />
            </div>
        </div>
        
      <div class="form-group my-5 py-3">
        <button type="submit" name="save" value="1" class="btn btn-primary btn-block">
            <?php 
                switch($audit_obj['language']) {
                    case 'en':
                        echo "Save";
                        break;
                    case 'fr':
                        echo "Sauver";
                        break;
                }
            ?>
        </button>
        <button type="submit" name="complete" value="1" class="btn btn-success btn-block">
            <?php 
                switch($audit_obj['language']) {
                    case 'en':
                        echo "Complete";
                        break;
                    case 'fr':
                        echo "Compléter";
                        break;
                }
            ?>
        </button>
      </div>
    </form>
  </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
  

    <script>
        //Need to do this per hideable section ...
        window.addEventListener('load',function(){showHide(document.getElementById('Q1'), document.getElementById('section-firealarm'))});
        document.getElementById('Q1').addEventListener('change',function(){showHide(document.getElementById('Q1'), document.getElementById('section-firealarm'))});
       
    
        function showHide(trigger, target){
            var ans = trigger.options[trigger.selectedIndex].getAttribute('data-response');
            if(ans == "No"){
                target.style.display="none";
            } else {
                target.style.display="block";
            }
        }
    </script>
