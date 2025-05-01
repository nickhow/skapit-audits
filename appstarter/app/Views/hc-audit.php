
  <div class="container mt-5">
    
    <?php if(session()->getFlashdata('msg')):?>
        <div class="alert alert-warning py-3">
            <?= session()->getFlashdata('msg') ?>
        </div>
    <?php endif;?>
    
    <div class="container-fluid fixed-bottom">
         <div class="btn position-absolute bottom-0 end-0 pb-4 text-primary" id="topBtn"><i class="fas fa-chevron-circle-up fa-3x"></i></div>
    </div>
    
    <div class="row p-4 bg-white rounded">
        <h2>Property Details</h2>

        <?php if($audit_obj['is_resubmission']) : ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                This is a re-submission of a previously reviewed audit.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <div class="col-12 col-md-4">
            <div class="row">
                <div class="col">
                    <b>Contact Name</b>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <?php echo $property_obj['name']; ?>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="row">
                <div class="col">
                    <b>Email</b>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <a href="mailto:<?php echo $property_obj['email']; ?>"><?php echo $property_obj['email']; ?></a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="row">
                <div class="col">
                    <b>Phone</b>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <a href="mailto:<?php echo $property_obj['phone']; ?>"><?php echo $property_obj['phone']; ?></a>
                </div>
            </div>
        </div>
        <div class="p-1"></div>
        <div class="col-12 col-md-4">
            <div class="row">
                <div class="col">
                    <b>Property Name</b>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <?php echo $property_obj['accommodation_name'] . " (Type ". $audit_obj['type'] .")" ?>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="row">
                <div class="col">
                    <b>Country</b>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <?php echo $property_obj['country'] ?>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="row">
                <div class="col">
                    <b>Resort</b>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <?php echo $property_obj['resort'] ?>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="row">
                <div class="col fw-lighter">
                    <?php echo $text['type'] ?>
                </div>
            </div>
        </div>

    </div>
    
        <form method="post" id="review_audit" name="review_audit" action="<?= site_url('/score-audit') ?>" enctype="multipart/form-data">
          <input type="hidden" name="id" id="id" value="<?php echo $audit_obj['id']; ?>">
    
            <div class="row p-4 my-3 bg-white rounded">
                <div class="row">
                    <div  class="col-12 col-md-7 col-lg-8">
                        <h3>Audit Summary and Result</h3>
                    </div>
                    <div class="col-12 col-md-5 col-lg-4 text-center text-md-end">
                        <div class="btn btn-outline-primary px-5" onclick="checkScores()">Total the scores</div>
                    </div>
                </div>
                
                <div class="col-12 my-3">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <label>BA Total Score</label>
                        <input type="number" id="ba_total" name="audit_score_ba" class="form-control" value="<?php if(!isset($audit_obj['total_score_ba'])){ echo 0;/*$ba_total_score; */}else{ echo $audit_obj['total_score_ba'];} ?>">
                    </div>
                            
                    <div class="col-12 col-md-6">
                        <label>ABTA Total Score</label>
                        <input type="number" id="abta_total" name="audit_score_abta" class="form-control" value="<?php if(!isset($audit_obj['total_score_abta'])){ echo 0;/*$abta_total_score; */}else{ echo $audit_obj['total_score_abta'];} ?>">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-6 col-md-4">
                        <label>Result (BA)</label>
                        <select name="audit_result_ba" id="audit_result_ba" class="form-select">
                            <option value="suitable" <?php  if($audit_obj['result_ba']) { if($audit_obj['result_ba'] === 'suitable'){ echo "selected"; } } ?>>Suitable</option>
                            <option value="unsuitable" <?php  if($audit_obj['result_ba']) { if($audit_obj['result_ba'] === 'unsuitable'){ echo "selected"; } } ?> >Unsuitable</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-4">
                        <label>Result (ABTA)</label>
                        <select name="audit_result_abta" id="audit_result_abta" class="form-select">
                            <option value="suitable" <?php  if($audit_obj['result_abta']) { if($audit_obj['result_abta'] === 'suitable'){ echo "selected"; } } ?>>Suitable</option>
                            <option value="unsuitable" <?php  if($audit_obj['result_abta']) { if($audit_obj['result_abta'] === 'unsuitable'){ echo "selected"; } } ?> >Unsuitable</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 col-md-4">
                        <label>Expiry Date (BA)</label>
                        <input type="date" id="expiry_date_ba" name="expiry_date_ba" class="form-control" value="<?php echo date('Y-m-d', strtotime($audit_obj['expiry_date_ba'])); ?>">
                    </div>
                    <div class="col-6 col-md-4">
                        <label>Expiry Date (ABTA)</label>
                        <input type="date" id="expiry_date_abta" name="expiry_date_abta" class="form-control" value="<?php echo date('Y-m-d', strtotime($audit_obj['expiry_date_abta'])); ?>">
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <label>Comment</label>
                        <input type="text" name="audit_comment" class="form-control" value="<?php echo $audit_obj['comment'];  ?>">
                    </div>
                </div>
                
                <button type="submit" name="save" value="1" class="btn btn-outline-secondary btn-block">Save Review</button>
                <button type="submit" name="complete" value="1" class="btn btn-success btn-block float-end">Save & Complete Review</button>
          </div>
        </div>
    
    <div class="row p-4 my-3 bg-white rounded">
        <h2>Health and Safety Form</h2>

            <?php 
                $ba_total_score = "0";
                $abta_total_score = "0";
            ?>
            
            <?php foreach ($response_obj as $response){ ?>
                
                <?php if($response['answer_id'] == "8888"){ 
                    //skip this one
                    continue;
                 } else { ?>
                 
                <div class="my-3"></div>
                <div class="p-3 question-element">
                <p class="py-1 m-0"><b>Question <?php echo $response['question_number'] ?> : </b><?php echo $response['question'] ?></p>
                <?php if($response['answer_id'] == "9999"){ ?>
                    <div class="row py-1 m-0 px-0">
                        <div class="col-12 col-md-1 col-lg-1 px-0 align-self-center">
                            <b>Answer</b>
                        </div>
                        <div class="col-12 col-md-4 col-lg-4 px-0">
                            <?php
                                if(is_numeric($response['custom_answer'])){ ?>
                                    <input type="number" name="Q<?php echo $response['question_number']; ?>" id="Q<?php echo $response['question_number']; ?>" data-response-id="<?php echo $response['id']; ?>" data-answer-id="<?php echo $response['answer_id']; ?>" class="col-12 col-md-4 form-control audit-question-custom" value="<?php echo $response['custom_answer']; ?>">
                            <?php     
                                } else { ?>
                                <textarea name="Q<?php echo $response['question_number']; ?>" id="Q<?php echo $response['question_number']; ?>" data-response-id="<?php echo $response['id']; ?>" data-answer-id="<?php echo $response['answer_id']; ?>" class="col-12 col-md-4 form-control audit-question-custom" value="<?php echo $response['custom_answer']; ?>"> <?php echo $response['custom_answer']; ?> </textarea>
                            <?php  }  ?>                            
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="row py-1 m-0 px-0">
                        <div class="col-12 col-md-1 col-lg-1 px-0 align-self-center">
                            <b>Answer</b>
                        </div>
                        <div class="col-12 col-md-4 col-lg-4 px-0">
                        <select name="Q<?php echo $response['question_number']; ?>" id="Q<?php echo $response['question_number']; ?>" data-response-id="<?php echo $response['id']; ?>" class="col-12 col-md-4 form-select audit-question">
                            <?php 
                            foreach($answer_obj[$response['question_number']] as $answer){
                                echo "<option value='".$answer['id']."' ";
                                if($answer['id'] == $response['answer_id']){ echo "selected"; } 
                                echo ">".$answer['en']."</option>";
                            }
                        ?>
                        </select>
                    </div>
                    </div>
                <?php } ?>
               
    
                    <div class="form-group">
                        <div class="row">
                            <div class="col-12 col-md-3">
                                <label>BA Suggested Score</label>
                                <input type="number" name="<?php echo $response['id']?>[suggested_score_ba] " class="form-control" readonly value="<?php echo $response['suggested_score_ba'];  ?>">
                            </div>
                            
                            <div class="col-12 col-md-3">
                                <label>ABTA Suggested Score</label>
                                <input type="number" name="<?php echo $response['id']?>[suggested_score_abta] " class="form-control" readonly value="<?php echo $response['suggested_score_abta'];  ?>">
                            </div>
                            
                            <div class="col-12 col-md-3">
                                <label>BA Score</label>
                                <input type="number" name="<?php echo $response['id']?>[score_ba]" class="form-control ba_score" value="<?php if(!isset($response['score_ba'])){ echo $response['suggested_score_ba']; }else{ echo $response['score_ba'];}  ?>">
                            </div>
                            
                            <div class="col-12 col-md-3">
                                <label>ABTA Score</label>
                                <input type="number" name="<?php echo $response['id']?>[score_abta] " class="form-control abta_score" value="<?php if(!isset($response['score_abta'])){ echo $response['suggested_score_abta']; }else{ echo $response['score_abta'];}  ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label>Comment</label>
                                <input type="text" name="<?php echo $response['id']?>[comment] " class="form-control" value="<?php echo $response['comment'];  ?>">
                            </div>
                        </div>
                  </div>
                </div>
                <?php } ?>
            
                    
            <?php
                
                if(is_numeric($response['suggested_score_ba'])){
                    $ba_total_score += $response['suggested_score_ba'];
                }
                if(is_numeric($response['suggested_score_abta'])){
                    $abta_total_score += $response['suggested_score_abta'];
                }  
                
            } ?>
            </div>
            
            <div class="row p-4 bg-white rounded">
                <div class="row justify-content-around">
                    <div class="col-12 col-md-7 col-lg-9">
                        <h3>Supporting Documents</h3>
                    </div>
                    <div class="col-12 col-md-5 col-lg-3 p-2 border border-warning">
                        <p><b>Additional Documents</b></n></p>
                        <input type="file" name="evidence[]" multiple />
                    </div>
                </div>
                <div class="col-12">
                    <div class="row ">
                        <?php foreach($file_obj as $file) { ?>
                            <div class="col-4 text-center " id="<?php echo $file['file_name'] ?>">
                                <div class="row">
                                    <div class="col">
                                        <?php switch($file['description']){
                                            case '': echo "Evidence"; break;
                                            case 'file_operating_licence': echo "Operating Licence"; break;
                                            case 'file_public_liability_insurance': echo "Public Liability Insurance"; break;
                                            case 'file_fire_certificate': echo "Fire Certificate"; break;
                                        } ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <?php if(strpos($file['file_type'], 'image') !== false): ?>
                                             <img src="<?php echo base_url()."/uploads/".$audit_obj['id']."/".$file['file_name']; ?>" style="max-width:150px;" class="img-thumbnail" alt="Uploaded file"/>
                                        <?php else: ?>
                                            <img src="<?php echo base_url()."/uploads/placeholder.jpeg" ?>" style="max-width:150px;" class="img-thumbnail" alt="Uploaded file"/>
                                        <?php endif ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <a href="<?php echo base_url()."/uploads/".$file['file_name']; ?>" download >
                                            <div class="btn btn-outline-secondary m-2">
                                                <i class="fas fa-download"></i> Download
                                            </div>
                                        </a>
                                    </div>
                                    <?php if($file['description'] == "" ): ?>
                                    <div class="col">
                                        <div class="btn btn-outline-danger m-2" onclick="deleteFile('<?php echo $file['file_name'] ?>')">Delete</div>
                                    </div>
                                    <?php endif ?>
                                </div>
                            </div>
                            
                        <?php } ?>
                    </div>
                </div>
            </div>


        </form>
    
                <div class="row p-4 my-3 bg-white rounded">
                <div class="row">
                    <div class="col-12 col-md-7 col-lg-9">
                        <h3>Contact</h3>
                    </div>
                </div>
                <div class="col-12 my-3">
                    <div class="col-12">
                        <textarea id="comment_text" rows="3" cols="100" style="max-width:100%" placeholder="Contact notes"></textarea>
                    </div>
                    <div class="btn btn-sm btn-outline-success" onclick="saveComment();">Save contact</div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-bordered" id="contact-list">
                               <thead>
                                  <tr>
                                     <th>Date</th>
                                     <th>Comment</th>
                                     <th>Actions</th>
                                  </tr>
                               </thead>
                               <tbody>
                                  <?php if($contact): ?>
                                  <?php foreach($contact as $row): ?>
                                  <tr id="comment_<?php echo ucfirst($row['id']); ?>">
                                      <td><?php echo date('d/m/Y', strtotime($row['date'])); ?></td>
                                      <td><?php echo ucfirst($row['comment']); ?></td>
                                      <td><div class="btn btn-outline-danger btn-small" onclick="deleteComment('<?php echo ucfirst($row['id']); ?>')">Delete</div></td>
                                  </tr>
                                 <?php endforeach; ?>
                                 <?php else: ?>
                                    <tr>
                                      <td colspan="3">No comments recorded</td>
                                    </tr>
                                 <?php endif; ?>
                               </tbody>
                             </table>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    
  </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
    
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/datetime-moment.js"></script>
  
  <script>
    
      function formatDate(date, years) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();
    
        if (month.length < 2) 
            month = '0' + month;
        if (day.length < 2) 
            day = '0' + day;
    
        return [year+years, month, day].join('-');
    }


    function checkScores(){
        var ba_scores = document.getElementsByClassName('ba_score');
        var abta_scores = document.getElementsByClassName('abta_score');
          
        var ba_total=0;
        for(var i=0;i<ba_scores.length;i++){
            if(parseInt(ba_scores[i].value)){
                ba_total += parseInt(ba_scores[i].value);
            }
        }
        document.getElementById('ba_total').value = ba_total;
        
        var abta_total=0;
        for(var i=0;i<abta_scores.length;i++){
            if(parseInt(abta_scores[i].value)){
                abta_total += parseInt(abta_scores[i].value);
            }
        }
        document.getElementById('abta_total').value = abta_total;
        
        const types = {
        '1': {
              ba: 147,
              abta: 139,
            },
        '2': {
              ba: 181,
              abta: 174,
            },
        '3': {
              ba: 178,
              abta: 170,
            },
        '4': {
              ba: 178,
              abta: 170,
            },
        '5': {
              ba: 185,
              abta: 177,
            },
        };
        
        if(types['<?php echo $audit_obj['type']; ?>']['ba'] <= ba_total) {
            var date = formatDate(new Date(),3);
            document.getElementById('audit_result_ba').value = "suitable";
            document.getElementById('expiry_date_ba').value = date;
        } else {
            var date = formatDate(new Date(),0);
            document.getElementById('audit_result_ba').value = "unsuitable"
            document.getElementById('expiry_date_ba').value = date;
        }
        
        if(types['<?php echo $audit_obj['type']; ?>']['abta'] <= abta_total) {
            var date = formatDate(new Date(),3);
            document.getElementById('audit_result_abta').value = "suitable";
            document.getElementById('expiry_date_abta').value = date;
        } else {
            var date = formatDate(new Date(),0);
            document.getElementById('audit_result_abta').value = "unsuitable"
            document.getElementById('expiry_date_abta').value = date;
        }
    }
    
    window.addEventListener('load',function(){checkScores()});
    window.addEventListener('load',function(){
        var q = document.querySelectorAll('.question-element');
        q.forEach(function(el){
            highlightQuery(el);
            el.addEventListener('change',function(){highlightQuery(el)});
        });
    });
    
  </script>
  <script>
    var questions  = document.querySelectorAll('.audit-question');
    questions.forEach(function(el){
        el.addEventListener('change',function(){
            var rid = el.getAttribute('data-response-id');
            var answer = el.value;
        
            $.ajax({
                url: '<?php echo base_url(); ?>/update-answer',
                type: 'POST',
                data: {
                    'response_id': rid,
                    'answer_id': answer,
                },
                success: function(msg) {
                    location.reload();
                }               
            });
                
        });
    });
    
    var questions = document.querySelectorAll('.audit-question-custom');
    questions.forEach(function(el){
        el.addEventListener('focusout',function(){
            var rid = el.getAttribute('data-response-id');
            var answer = el.getAttribute('data-answer-id');
            var custom = el.value;
        
            $.ajax({
                url: '<?php echo base_url(); ?>/update-answer',
                type: 'POST',
                data: {
                    'response_id': rid,
                    'answer_id': answer,
                    'custom_answer':custom
                },
                success: function(msg) {
                    location.reload();
                }               
            });
                
        });
    });
  </script>
  
  <script>
    
    function highlightQuery(question){
        //per question check both scores and if either is classifies for highlight then call highlight
        var ba = question.querySelector(".ba_score");
        var abta = question.querySelector(".abta_score");
        
        if( ba.value >= 100015 || abta.value >= 100015){
            highlight(question,'good');
        } else if( ba.value <= -100015 || abta.value <= -100015){
            highlight(question,'bad');
        } else {
            highlight(question,'');
        }
        
    }
  
    function highlight(question,result){
        if(result == 'good'){
            question.style.backgroundColor="rgb(0 141 11 / 50%)"
            return;
        } else if(result == 'bad'){
            question.style.backgroundColor="rgb(203 0 0 / 50%)"
            return;
        } else {
            question.style.backgroundColor="#fff"
            return;
        }
    }
    
    <?php echo base_url(); ?>
  
    function saveComment(){
        var audit_id = document.getElementById('id').value;
        var comment = document.getElementById('comment_text').value;
        $.ajax({
            <?php $session = session();
                if($session->get('is_admin')){ echo "url: '".base_url()."/comment-save-admin',"; } 
                else { echo "url: '".base_url()."/comment-save',"; } 
            ?>
            type: 'POST',
            data: {
                'audit_id': audit_id,
                'comment': comment,
                'account_id': '<?= $property_obj['id']; ?>',
            },
            success: function(msg) {
                location.reload();
            }               
        });        
    }
    function deleteComment(id){
        $.ajax({
                url: '<?php echo base_url(); ?>/comment-delete',
                type: 'POST',
                data: {
                    'id': id,
                },
                success: function(msg) {
                    console.log(msg);
                    document.getElementById("comment_"+id).remove();
                }               
            });
    }
  </script>
  <script>
    $(document).ready( function () {
      $.fn.dataTable.moment( 'D/M/YYYY' ); 
      $('#contact-list').DataTable({
          responsive: true,
          "dom": 'ltip',
          "lengthMenu": [[1, 5, -1], [1, 5, "All"]],
          "order": [[ 0, "desc" ]]
          
      });
  } );
</script>
<script>
    function scrollTop(){
        scroll(0,100);
    }
    document.getElementById("topBtn").onclick = function() {scrollTop()};
</script>
  <script>
      function deleteFile(file_name){
            $.ajax({
                url: '<?= echo base_url(); ?>/remove-file/'+file_name,   
                type: 'POST',
                data: {
                
                },
                success: function(msg) {
                    console.log(msg);
                    document.getElementById(file_name).remove();
                    
                }               
            });
      }
  </script>

