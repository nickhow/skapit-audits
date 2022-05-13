
  <link href="<?php echo base_url('css/main.css') ?>" rel="stylesheet">
  
  <style>
/* .completed {
    background-color:#1598159e;
    color: white;
} */

.key-completed{
    background-color: #1598159e!important;
    color: white;
}    
.key-error{
    background-color: #9815159e!important;
    color: white;
}
.collapsing {
    transition: none;
}

.accordion-header {
  scroll-margin-top: 85px; /* whatever is a nice number that gets you past the header */
}
</style>


  <div class="container-md mt-5 pb-5 mb-3">
    
    <?php if(!session()->getFlashdata('locked')): ?>
        <div class="row bg-white rounded p-3">
            <div class="col-12">
                <?php echo($text['audit_intro']); ?>
            </div>
            <div class="col-12">
                <?php if($audit_obj['is_payable']): ?> 
                    <div class="alert alert-warning">
                        <?php
                            echo(str_replace("{amount}", $audit_obj['payable_amount'], $text['audit_payment_intro']));
                            //echo($text['audit_payment_intro']); 
                        ?>
                    </div>
                <?php endif ?>
            </div>
        </div>

        <div class="container-fluid py-3 sticky-top bg-light">
            <h4><?php echo $text['progress']; ?> (<?php echo $account_obj['accommodation_name']; ?>)</h4>
            <div class="progress">
                <div class="progress-bar" id="progressBar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div> 

    <?php else: ?>
        <div class="container-fluid py-3 sticky-top bg-light">
            <h4><?php echo $account_obj['accommodation_name']; ?></h4>
            <div class="progress d-none">
                <div class="progress-bar" id="progressBar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div> 

    <?php endif; ?>
    <?php if($audit_obj['status'] == 'reviewed'): ?>
        <div class="row p-3">
            <div class="col-12 col-md-6">
                <div class="col-12">
                    <b>Audit result:</b>
                </div>
                <div class="col-12">
                    <h2><?php echo "BA: ". ucFirst($audit_obj['result_ba']);?></h2>
                    <h2><?php echo "ABTA: ". ucFirst($audit_obj['result_abta']);?></h2>
                </div>
                <p>This result expires on <?php echo  date('d/m/Y', strtotime($audit_obj['expiry_date_ba']));?> for BA, and <?php echo  date('d/m/Y', strtotime($audit_obj['expiry_date_abta']));?> for ABTA.</p>
            </div>
            <div class="col-12 col-md-6">
                <b>Understanding the feedback</b>
                <table class="table table-sm">
                    <tr>
                        <td class="key-completed" scope="row">Green Section</td>
                        <td >All the answers in this section are OK.</td>
                    </tr>
                    <tr>
                        <td class="key-error" scope="row">Red Section</td>
                        <td>At least one of the answers in this section causes a failure.</td>
                    </tr>
                    <tr>
                        <td class="key-error" scope="row">Red Question</td>
                        <td>This answer causes a failure.</td>
                    </tr>
                    <tr>
                        <td class="key-completed" scope="row">Green Question</td>
                        <td>This answer provides a redemption on a previous failure.</td>
                    </tr>
                </table>
            </div>
            <div class="row">
                <label><b>Comments</b></label>
                <p><?php echo $audit_obj['comment'] ?></p>
            </div>
            
        </div>
    <?php endif ?>           
                    
                    
    
    <form method="post" id="update_audit" name="update_audit" enctype="multipart/form-data" action="<?= site_url('/save-responses') ?>">
      <input type="hidden" name="id" id="id" value="<?php echo $audit_obj['id']; ?>">
        
        <?php if(session()->getFlashdata('msg')){
           echo "<div class='alert ".session()->getFlashdata('style')."'>";
                echo session()->getFlashdata('msg') ;
           echo "</div>"; 
            }    ?>
    
        <div class="accordion" id="form-accordion">
        
          <div class="accordion-item">
            <h2 class="accordion-header" id="form-accordion-understanding-property">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#form-accordion-understanding-property-body" aria-expanded="true" aria-controls="form-accordion-understanding-property-body">
                <?php echo "1. ".$text['section_property']; ?>
              </button>
            </h2>
            <div id="form-accordion-understanding-property-body" class="accordion-collapse collapse show" aria-labelledby="form-accordion-understanding-property">
                <div class="accordion-body">
        <?php  /* Question 1 */ $question = $questions[0]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-understanding-property-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-understanding-property-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
    
        <?php  /* Question 2 */ $question = $questions[1]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3" id="section-firealarm">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-understanding-property-body')"/>
                    <?php   } else { ?>
                    <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-understanding-property-body')">
                        <?php  //create all the answer options and choose the prev. saved option if there is one.
                        foreach($question['answers'] as $answer) {
                            
                            //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                            if($answer['en_ans'] === "Unanswered") {
                                echo "<option value='Unanswered'";
                                 if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                echo " >".ucfirst($answer['answer'])."</option>";
                            } else {
                                echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'";
                                echo "data-response='".$answer['en_ans']."'";
                                if($question['response']) {
                                    if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                }
                                echo ">" . ucfirst($answer['answer']) . "</option>";
                            }
                         } 
                        ?>
                    </select>
                    <?php  } ?>
                </div>
            </div>
        </div>
     <?php } ?>
     
        <?php  /* Question 3 */ $question = $questions[2]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-understanding-property-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-understanding-property-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                     if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 4 */ $question = $questions[3]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-understanding-property-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-understanding-property-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>       
        
          </div>
        </div>
      </div> <!-- End of section -->
      
        <div class="accordion-item">
            <h2 class="accordion-header" id="form-accordion-legal">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#form-accordion-legal-body" aria-expanded="true" aria-controls="form-accordion-legal-body">
                <?php echo "2. ".$text['section_legal']; ?>
              </button>
            </h2>
        
            <div id="form-accordion-legal-body" class="accordion-collapse collapse show" aria-labelledby="form-accordion-legal">
                <div class="accordion-body">
        
        <?php  /* Question 5 */ $question = $questions[4]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-legal-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-legal-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php  /* Question 6 */ $question = $questions[5]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-legal-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-legal-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 7 */ $question = $questions[6]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-legal-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-legal-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 8 */ $question = $questions[7]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onchange="updateProgress('form-accordion-legal-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-legal-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
          </div>
        </div>
      </div> <!-- End of section -->
      
        <div class="accordion-item">
            <h2 class="accordion-header" id="form-accordion-fire">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#form-accordion-fire-body" aria-expanded="true" aria-controls="form-accordion-fire-body">
                <?php echo "3. ".$text['section_fire']; ?>
              </button>
            </h2>
        
            <div id="form-accordion-fire-body" class="accordion-collapse collapse show" aria-labelledby="form-accordion-fire">
                <div class="accordion-body">
        
        <?php  /* Question 9 */ $question = $questions[8]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 10 */ $question = $questions[9]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 11 */ $question = $questions[10]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 12 */ $question = $questions[11]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 13 */ $question = $questions[12]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 14 */ $question = $questions[13]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 15 */ $question = $questions[14]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 16 */ $question = $questions[15]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 17 */ $question = $questions[16]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 18 */ $question = $questions[17]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 19 */ $question = $questions[18]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 20 */ $question = $questions[19]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 21 */ $question = $questions[20]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 22 */ $question = $questions[21]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 23 */ $question = $questions[22]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 24 */ $question = $questions[23]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 25 */ $question = $questions[24]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 26 */ $question = $questions[25]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 27 */ $question = $questions[26]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 28 */ $question = $questions[27]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 29 */ $question = $questions[28]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 30 */ $question = $questions[29]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 31 */ $question = $questions[30]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 32 */ $question = $questions[31]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 33 */ $question = $questions[32]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 34 */ $question = $questions[33]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 35 */ $question = $questions[34]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 36 */ $question = $questions[35]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 37 */ $question = $questions[36]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                    
                           //         echo ("<script>console.log('opt = q_ans_id ".$answer['id']." - r_ans_id".$question['response']['answer_id']."');</script>");
                                }
                            //    echo ("<script>document.getElementById('Q37').value = ".$question['response']['answer_id']."; console.log('set 37 = '+".$question['response']['answer_id'].");</script>");
                           //     echo ("console.log('set 37 = '+".$question['response']['answer_id'].");</script>");
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 38 */ $question = $questions[37]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 39 */ $question = $questions[38]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 40 */ $question = $questions[39]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
                <?php  /* Question 41 */ $question = $questions[40]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 42 */ $question = $questions[41]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 43 */ $question = $questions[42]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 44 */ $question = $questions[43]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 45 */ $question = $questions[44]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 46 */ $question = $questions[45]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 47 */ $question = $questions[46]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>


        <?php  /* Question 48 */ $question = $questions[47]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
    
        <?php  /* Question 49 */ $question = $questions[48]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3" id="section-firealarm">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                    <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php  //create all the answer options and choose the prev. saved option if there is one.
                        foreach($question['answers'] as $answer) {
                            
                            //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                            if($answer['en_ans'] === "Unanswered") {
                                echo "<option value='Unanswered'";
                                 if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                echo " >".ucfirst($answer['answer'])."</option>";
                            } else {
                                echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'";
                                echo "data-response='".$answer['en_ans']."'";
                                if($question['response']) {
                                    if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                }
                                echo ">" . ucfirst($answer['answer']) . "</option>";
                            }
                         } 
                        ?>
                    </select>
                    <?php  } ?>
                </div>
            </div>
        </div>
     <?php } ?>
     
        <?php  /* Question 50 */ $question = $questions[49]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                     if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 51 */ $question = $questions[50]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>        
        
        <?php  /* Question 52 */ $question = $questions[51]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php  /* Question 53 */ $question = $questions[52]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 54 */ $question = $questions[53]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 55 */ $question = $questions[54]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 56 */ $question = $questions[55]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 57 */ $question = $questions[56]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 58 */ $question = $questions[57]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 59 */ $question = $questions[58]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 60 */ $question = $questions[59]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fire-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 61 */ $question = $questions[60]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onchange="updateProgress('form-accordion-fire-body')" />
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fire-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
            
            </div>
        </div>
      </div> <!-- End of section -->
      
        <div class="accordion-item">
            <h2 class="accordion-header" id="form-accordion-hygiene">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#form-accordion-hygiene-body" aria-expanded="true" aria-controls="form-accordion-hygiene-body">
                <?php echo "4. ".$text['section_hygiene']; ?>
              </button>
            </h2>
        
            <div id="form-accordion-hygiene-body" class="accordion-collapse collapse show" aria-labelledby="form-accordion-hygiene">
                <div class="accordion-body">
        
        <?php  /* Question 62 */ $question = $questions[61]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-hygiene-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-hygiene-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 63 */ $question = $questions[62]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-hygiene-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-hygiene-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 64 */ $question = $questions[63]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-hygiene-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-hygiene-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 65 */ $question = $questions[64]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-hygiene-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-hygiene-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 66 */ $question = $questions[65]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-hygiene-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-hygiene-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 67 */ $question = $questions[66]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-hygiene-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-hygiene-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 68 */ $question = $questions[67]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-hygiene-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-hygiene-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 69 */ $question = $questions[68]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onchange="updateProgress('form-accordion-hygiene-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-hygiene-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
            
            </div>
        </div>
      </div> <!-- End of section -->
      
        <div class="accordion-item">
            <h2 class="accordion-header" id="form-accordion-pool">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#form-accordion-pool-body" aria-expanded="true" aria-controls="form-accordion-pool-body">
                <?php echo "5. ".$text['section_pool']; ?>
              </button>
            </h2>
        
            <div id="form-accordion-pool-body" class="accordion-collapse collapse show" aria-labelledby="form-accordion-pool">
                <div class="accordion-body">
        <?php  /* Question 70 */ $question = $questions[69]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-pool-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-pool-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 71 */ $question = $questions[70]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-pool-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-pool-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 72 */ $question = $questions[71]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-pool-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-pool-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 73 */ $question = $questions[72]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-pool-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-pool-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 74 */ $question = $questions[73]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-pool-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-pool-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 75 */ $question = $questions[74]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-pool-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-pool-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 76 */ $question = $questions[75]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-pool-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-pool-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 77 */ $question = $questions[76]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-pool-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-pool-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 78 */ $question = $questions[77]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-pool-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-pool-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 79 */ $question = $questions[78]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-pool-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-pool-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 80 */ $question = $questions[79]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-pool-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-pool-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 81 */ $question = $questions[80]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-pool-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-pool-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 82 */ $question = $questions[81]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-pool-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-pool-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 83 */ $question = $questions[82]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-pool-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-pool-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 84 */ $question = $questions[83]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-pool-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-pool-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 85 */ $question = $questions[84]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onchange="updateProgress('form-accordion-pool-body')" />
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-pool-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
            </div>
        </div>
      </div> <!-- End of section -->
      
        <div class="accordion-item">
            <h2 class="accordion-header" id="form-accordion-fuel">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#form-accordion-fuel-body" aria-expanded="true" aria-controls="form-accordion-fuel-body">
                <?php echo "6. ".$text['section_fuel']; ?>
              </button>
            </h2>
        
            <div id="form-accordion-fuel-body" class="accordion-collapse collapse show" aria-labelledby="form-accordion-fuel">
                <div class="accordion-body">
        
        <?php  /* Question 86 */ $question = $questions[85]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="text" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fuel-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fuel-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 87 */ $question = $questions[86]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fuel-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fuel-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
                <?php  /* Question 88 */ $question = $questions[87]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fuel-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fuel-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 89 */ $question = $questions[88]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fuel-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fuel-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 90 */ $question = $questions[89]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fuel-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fuel-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 91 */ $question = $questions[90]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fuel-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fuel-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 92 */ $question = $questions[91]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fuel-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fuel-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 93 */ $question = $questions[92]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fuel-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fuel-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 94 */ $question = $questions[93]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fuel-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fuel-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 95 */ $question = $questions[94]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fuel-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fuel-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
    
        <?php  /* Question 96 */ $question = $questions[95]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3" id="section-firealarm">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fuel-body')"/>
                    <?php   } else { ?>
                    <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fuel-body')">
                        <?php  //create all the answer options and choose the prev. saved option if there is one.
                        foreach($question['answers'] as $answer) {
                            
                            //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                            if($answer['en_ans'] === "Unanswered") {
                                echo "<option value='Unanswered'";
                                 if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                echo " >".ucfirst($answer['answer'])."</option>";
                            } else {
                                echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'";
                                echo "data-response='".$answer['en_ans']."'";
                                if($question['response']) {
                                    if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                }
                                echo ">" . ucfirst($answer['answer']) . "</option>";
                            }
                         } 
                        ?>
                    </select>
                    <?php  } ?>
                </div>
            </div>
        </div>
     <?php } ?>
     
        <?php  /* Question 97 */ $question = $questions[96]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fuel-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fuel-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                     if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 98 */ $question = $questions[97]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fuel-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fuel-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>        
        
        <?php  /* Question 99 */ $question = $questions[98]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fuel-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fuel-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php  /* Question 100 */ $question = $questions[99]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-fuel-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fuel-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 101 */ $question = $questions[100]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onchange="updateProgress('form-accordion-fuel-body')" />
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-fuel-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
            </div>
        </div>
      </div> <!-- End of section -->
      
        <div class="accordion-item">
            <h2 class="accordion-header" id="form-accordion-general">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#form-accordion-general-body" aria-expanded="true" aria-controls="form-accordion-general-body">
                <?php echo "7. ".$text['section_general']; ?>
              </button>
            </h2>
        
            <div id="form-accordion-general-body" class="accordion-collapse collapse show" aria-labelledby="form-accordion-general">
                <div class="accordion-body">
        <?php  /* Question 102 */ $question = $questions[101]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-general-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-general-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 103 */ $question = $questions[102]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-general-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-general-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 104 */ $question = $questions[103]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-general-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-general-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 105 */ $question = $questions[104]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-general-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-general-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 106 */ $question = $questions[105]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-general-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-general-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 107 */ $question = $questions[106]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-general-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-general-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 108 */ $question = $questions[107]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="text" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-general-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-general-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 109 */ $question = $questions[108]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-general-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-general-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 110 */ $question = $questions[109]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onchange="updateProgress('form-accordion-general-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select"  onchange="updateProgress('form-accordion-general-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        
            </div>
        </div>
      </div> <!-- End of section -->
      
        <div class="accordion-item">
            <h2 class="accordion-header" id="form-accordion-waterpark">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#form-accordion-waterpark-body" aria-expanded="true" aria-controls="form-accordion-waterpark-body">
                <?php echo "8. ".$text['section_waterpark']; ?>
              </button>
            </h2>
        
            <div id="form-accordion-waterpark-body" class="accordion-collapse collapse show" aria-labelledby="form-waterpark-fire">
                <div class="accordion-body">
                    
        <?php  /* Question 111 */ $question = $questions[110]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-waterpark-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-waterpark-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 112 */ $question = $questions[111]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-waterpark-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-waterpark-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 113 */ $question = $questions[112]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-waterpark-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-waterpark-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 114 */ $question = $questions[113]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onchange="updateProgress('form-accordion-waterpark-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-waterpark-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
            </div>
        </div>
      </div> <!-- End of section -->
      
        <div class="accordion-item">
            <h2 class="accordion-header" id="form-accordion-viral">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#form-accordion-viral-body" aria-expanded="true" aria-controls="form-accordion-viral-body">
                <?php echo "9. ".$text['section_viral']; ?>
              </button>
            </h2>
        
            <div id="form-accordion-viral-body" class="accordion-collapse collapse show" aria-labelledby="form-accordion-viral">
                <div class="accordion-body">
        
        <?php  /* Question 115 */ $question = $questions[114]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-viral-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-viral-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 116 */ $question = $questions[115]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-viral-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-viral-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 117 */ $question = $questions[116]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-viral-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-viral-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 118 */ $question = $questions[117]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-viral-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-viral-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 119 */ $question = $questions[118]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-viral-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-viral-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 120 */ $question = $questions[119]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-viral-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-viral-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 121 */ $question = $questions[120]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-viral-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-viral-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 122 */ $question = $questions[121]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-viral-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-viral-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 123 */ $question = $questions[122]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-viral-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-viral-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 124 */ $question = $questions[123]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onchange="updateProgress('form-accordion-viral-body')" />
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-viral-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php  /* Question 129 ** NEW QUESTION **  */ $question = $questions[128]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-viral-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-viral-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
          </div>
        </div>
      </div> <!-- End of section -->
      
        <div class="accordion-item">
            <h2 class="accordion-header" id="form-accordion-carbon">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#form-accordion-carbon-body" aria-expanded="true" aria-controls="form-accordion-carbon-body">
                <?php echo "10. ".$text['section_carbon']; ?>
              </button>
            </h2>
        
            <div id="form-accordion-carbon-body" class="accordion-collapse collapse show" aria-labelledby="form-accordion-carbon">
                <div class="accordion-body">
        
        <?php  /* Question 125 */ $question = $questions[124]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-carbon-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-carbon-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 126 */ $question = $questions[125]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-carbon-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-carbon-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 127 */ $question = $questions[126]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onfocusout="updateProgress('form-accordion-carbon-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-carbon-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php  /* Question 128 */ $question = $questions[127]; ?> 
        <?php if($question['hide_for_1'] && $audit_obj['type'] == 1
        || $question['hide_for_2'] && $audit_obj['type'] == 2
        || $question['hide_for_3'] && $audit_obj['type'] == 3
        || $question['hide_for_4'] && $audit_obj['type'] == 4
        || $question['hide_for_5'] && $audit_obj['type'] == 5
        ) {
            //set the value for this answer to 0 (not N/A)
            ?>
            <input type="hidden" name="<?php echo $question['id'] ?>" value="ignore">
            <?php
        } else { ?>
        <div class="row my-3">
            <div class="col">
                <div class="form-group"> 
                    <label class="pb-2"><b><?php echo ucfirst($question['question']) ?></b></label>
                    <?php if($question['has_custom_answer']){ ?>
                        <input type="number" name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-control" value ="<?php if($question['response']) { echo $question['response']['custom_answer']; } ?>" onchange="updateProgress('form-accordion-carbon-body')"/>
                    <?php   } else { ?>
                        <select name="<?php echo $question['id'] ?>" id="Q<?php echo $question['id'] ?>" class="form-select" onchange="updateProgress('form-accordion-carbon-body')">
                        <?php //create all the answer options and choose the prev. saved option if there is one.
                            foreach($question['answers'] as $answer) { 
                                
                                //if it's the unanswered one do it different -> value as not the id, but something identifiable later
                                if($answer['en_ans'] === "Unanswered") {
                                    echo "<option value='Unanswered'";
                                    if(!$question['response'] || $question['response'] == 0) { echo "selected"; }
                                    echo " >".ucfirst($answer['answer'])."</option>";
                                } else {
                                    echo "<option id='A" . $answer['id'] ."' value='" . $answer['id'] ."'"; 
                                    echo "data-response='".$answer['en_ans']."'";
                                    if($question['response']) {
                                        if($answer['id'] === $question['response']['answer_id']){ echo "selected"; } 
                                    } 
                                    echo ">" . ucfirst($answer['answer']) . "</option>";
                                } 
                            }
                        ?>
                        </select>
                      <?php  } ?>
                </div>
            </div>
        </div>
        <?php } ?>
       
        <!-- 
        *
        *
        * * * END OF FORM * * *
        *
        *
        -->
     
        </div> <!-- end of accordion -->
        </div>
        </div>
      </div> <!-- End of section -->   
      
           <div class="row my-3" id="section-files">
            <div class="col">

                <div class="row">
                   <div class="col-12 col-md-4">
                        <div class="col-12"><h4>Operating Licence</h4></div>
                        <div class="col-12 py-2">
                            <input type="file" name="file_operating_licence" />
                        </div>
                        <div class="col-12">
                            <?php foreach($file_obj as $file) { 
                                if($file['description'] == 'file_operating_licence'): ?>
                                <div id="<?php echo $file['file_name']; ?>">
                                    <p class="fs-5"><span class="text-primary">Current file: </span><?php echo $file['original_name']; ?></p>
                                    <div class="row">
                                        <div class="col-12 col-md-4 p-0">
                                            <a href="<?php echo base_url()."/uploads/".$audit_obj['id']."/".$file['file_name']; ?>" target='_blank' ><div class="col-12 btn btn-sm btn-secondary">View</div></a>
                                        </div>
                                        <div class="col-12 col-md-4 mx-3 btn btn-sm btn-outline-danger" onclick="deleteFile('<?php echo $file['file_name'] ?>')">Delete</div>
                                    </div>

                                </div>
                                <?php endif;
                                }
                            ?>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-4">
                        <div class="col-12"><h4>Public Liability Insurance</h4></div>
                        <div class="col-12 py-2">
                            <input type="file" name="file_public_liability_insurance" />
                        </div>
                        <div class="col-12">
                            <?php foreach($file_obj as $file) { 
                                if($file['description'] == 'file_public_liability_insurance'): ?>
                                <div id="<?php echo $file['file_name']; ?>">
                                    <p class="fs-5"><span class="text-primary">Current file: </span><?php echo $file['original_name']; ?></p>
                                    <div class="row">
                                        <div class="col-12 col-md-4 p-0">
                                            <a href="<?php echo base_url()."/uploads/".$audit_obj['id']."/".$file['file_name']; ?>" target='_blank' ><div class="col-12 btn btn-sm btn-secondary">View</div></a>
                                        </div>
                                        <div class="col-12 col-md-4 mx-3 btn btn-sm btn-outline-danger" onclick="deleteFile('<?php echo $file['file_name'] ?>')">Delete</div>
                                    </div>

                                </div>
                                <?php endif;
                                }
                            ?>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-4">
                        <div class="col-12"><h4>Fire Certificate</h4></div>
                        <div class="col-12 py-2">
                            <input type="file" name="file_fire_certificate" />
                        </div>
                        <div class="col-12">
                            <?php foreach($file_obj as $file) { 
                                if($file['description'] == 'file_fire_certificate'): ?>
                                <div id="<?php echo $file['file_name']; ?>">
                                    <p class="fs-5"><span class="text-primary">Current file: </span><?php echo $file['original_name']; ?></p>
                                    <div class="row">
                                        <div class="col-12 col-md-4 p-0">
                                            <a href="<?php echo base_url()."/uploads/".$audit_obj['id']."/".$file['file_name']; ?>" target='_blank' ><div class="col-12 btn btn-sm btn-secondary">View</div></a>
                                        </div>
                                        <div class="col-12 col-md-4 mx-3 btn btn-sm btn-outline-danger" onclick="deleteFile('<?php echo $file['file_name'] ?>')">Delete</div>
                                    </div>

                                </div>
                                <?php endif;
                                }
                            ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>

<?php if(!session()->getFlashdata('locked')): ?>
  <div class="container fixed-bottom bg-white rounded">
      <div class="row">
          <div class="form-group my-3 py-1">
        <button type="submit" name="save" value="1" class="btn btn-primary btn-block">
            <?php echo $text['save_button']; ?>
        </button>
        <button type="submit" name="complete" value="1" class="btn btn-success btn-block">
            <?php echo $text['complete_button']; if($audit_obj['is_payable']){echo ($text['complete_with_payment']);}?>
        </button>
      </div>
      </div>
  </div>
<?php endif; ?>
    </form>
  </div>
  
 
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
    
    <script> var isLocked = <?php if( session()->getFlashdata('locked')) { echo"true";}else{echo"false";} ?></script>
    
    <script src="<?php echo base_url('js/main.js') ?>"></script>

    <?php if(session()->getFlashdata('failed_complete')){ echo "<script>document.addEventListener('load',formValidation());</script>"; }  ?>
    
    <?php if(session()->getFlashdata('locked')): ?>
        <script>
            window.addEventListener('load',function(){
                var questions = document.querySelectorAll('select, input');
                questions.forEach(function(question){
                    question.disabled='true';
                });
            });
        </script>
        
        <!-- Create a new end point to get the responses as JSON and pull in the relevant comment? -->
        <script>
            var questions = document.querySelectorAll('select, input');
            var responses;
            
                $.ajax({
                    url: '<?php echo site_url('/audit/responses/'.$audit_obj['id']) ?>',
                    type: 'GET',
                    success: function(data) {
                        responses = JSON.parse(data);
                        questions.forEach(function(question){
                            var qid = question.getAttribute('name');
                            if(isNaN(qid) || responses[qid]['answer_id'] == "8888"){  //8888 is a skipped question
                        	//nothing
                            } else {
                                var parent = question.parentElement;
                                var el = document.createElement('div');
                                el.classList.add('text-secondary');
                                var text = document.createElement('small');
                                
                                text.innerHTML= '<b><i>Feedback: </i></b>';
                                if( responses[qid]['comment'] == ''){
                                    text.innerHTML += '<i>none</i>';
                                } else {
                                    el.classList.add('alert','alert-warning');
                                    text.innerHTML += responses[qid]['comment'];
                                }
                                el.appendChild(text);
                                parent.appendChild(el);
                                
                                if( responses[qid]['score_ba'] >= 100015 || responses[qid]['score_abta'] >= 100015){
                                    question.parentElement.parentElement.style.backgroundColor='rgb(0 141 11 / 50%)';
                                }
                                if( responses[qid]['score_ba'] <= -100015 || responses[qid]['score_abta'] <= -100015){
                                    question.parentElement.parentElement.style.backgroundColor='rgb(203 0 0 / 50%)';
                                    
                                    //make the accordion red
                                    var list = question.closest(".accordion-item").getElementsByClassName('accordion-button');
                                    for(btn of list){btn.classList.add('completed-error')};
                                }
                            }

                        }); 
                        
                    }               
                });
        </script>
    <?php endif;  ?>
     <?php if(session()->get('is_admin') && session()->getFlashdata('locked')) : ?>
             <script>
            var questions = document.querySelectorAll('select, input');
            var responses;
            
                $.ajax({
                    url:  '<?php echo site_url('/audit/responses/'.$audit_obj['id']) ?>',
                    type: 'GET',
                    success: function(data) {
                        responses = JSON.parse(data);
                        questions.forEach(function(question){
                            var qid = question.getAttribute('name');
                             if(isNaN(qid) || responses[qid]['answer_id'] == '8888'){  //8888 is a skipped question
                        	//nothing
                            } else {
                                var parent = question.parentElement;
                                var el = document.createElement('p');
                                el.innerHTML= '<div><b><i>Scores: </i></b>';
                                el.innerHTML += ' BA: '+responses[qid]['score_ba'];
                                el.innerHTML += ' ABTA: '+responses[qid]['score_abta'];
                                el.innerHTML += '</div>'
                                parent.appendChild(el);
                                
                                if( responses[qid]['score_ba'] >= 100015 || responses[qid]['score_abta'] >= 100015){
                                    question.parentElement.parentElement.style.backgroundColor='rgb(0 141 11 / 50%)';
                                }
                                if( responses[qid]['score_ba'] <= -100015 || responses[qid]['score_abta'] <= -100015){
                                    question.parentElement.parentElement.style.backgroundColor='rgb(203 0 0 / 50%)';
                                    
                                    //make the accordion red
                                    var list = question.closest(".accordion-item").getElementsByClassName('accordion-button');
                                    for(btn of list){btn.classList.add('completed-error')};
                                }
                                
                            }

                        }); 
                        
                    }               
                });
        </script>
        <?php endif; ?>