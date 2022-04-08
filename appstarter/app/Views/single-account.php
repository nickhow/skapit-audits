  <div class="container mt-5">
        <div class="row justify-content-md-center ">
          <div class="col-12 col-lg-4 p-4 bg-white rounded">
              <h2>Update Account</h2>
    <form method="post" id="update_account" name="update_account" 
    action="<?= site_url('/update-account') ?>">
      <input type="hidden" name="id" id="id" value="<?php echo $account_obj['id']; ?>">

      <div class="form-group pt-2">
        <label>Name</label>
        <input type="text" name="name" class="form-control" value="<?php echo $account_obj['name']; ?>">
      </div>
 
      <div class="form-group pt-2">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?php echo $account_obj['email']; ?>">
      </div>
      <div class="form-group pt-2">
        <label>Phone</label>
        <input type="text" name="phone" class="form-control" value="<?php echo $account_obj['phone']; ?>">
      </div>
      <div class="form-group pt-2">
        <label>Group</label>
        <select name="group_id" id="group_id" class="form-select" <?php if(!session()->get('is_admin') && !session()->get('enable_groups')){echo "disabled";} ?> >
            <?php if(session()->get('is_admin')):?>
                <option value="0" <?php if( $account_obj['group_id'] == "0"){echo "selected";} ?>>No Group</option>
            <?php endif; ?>
            <?php
                foreach($group_objects as $group) {
                    $selected = "";
                    if ( $account_obj['group_id'] === $group['id'])  { $selected="selected" ; }
                    echo "<option value='".$group['id']."'". $selected ." >".$group['name']."</option>";
                }
            ?>
        </select>
      </div>
      <div class="form-group pt-2">
                <label>Is Group Manager</label>
                <select name="is_group_manager" disabled class="form-select">
                        <option value="0" <?php if( $account_obj['is_group_manager'] == "0"){echo "selected";} ?>>No</option>
                        <option value="1" <?php if( $account_obj['is_group_manager'] == "1"){echo "selected";} ?>>Yes</option>
                    </select>
              </div>
      <div class="form-group pt-2">
        <label>Accommodation Name</label>
        <input type="text" name="accommodation_name" class="form-control" value="<?php echo $account_obj['accommodation_name']; ?>">
      </div>
      <div class="form-group pt-2">
        <label>Resort</label>
        <input type="text" name="resort" class="form-control" value="<?php echo $account_obj['resort']; ?>">
      </div>
      <div class="form-group pt-2">
        <label>Country</label>
        <input type="text" name="country" class="form-control" value="<?php echo $account_obj['country']; ?>">
      </div>
      
      <div class="form-group pt-2">
        <label>Last Contact</label>
        <input type="date" name="sent_date" class="form-control" value="
                <?php if(!empty($audit_objects){ echo date('Y-m-d', strtotime($audit_objects[0]->sent_date));} else echo date('Y-m-d', strtotime('1970-01-01'); ?>
            ">
      </div>
   
      <div class="form-group pt-2">
        <label>Notes</label>
        <textarea class="form-control" name="notes" rows="3"><?php echo $account_obj['notes']; ?></textarea>
      </div>
      
      <div class="form-group py-3">
        <a href="<?php echo base_url('accounts'); ?>" class="btn btn-outline-dark btn-block"><i class="fas fa-arrow-left"></i> Back</a>
        <button type="submit" class="btn btn-primary btn-block px-5 mx-2">Save Changes</button>
      </div>
    </form>
  </div>
  
  <div class="col-12 col-lg-7 col-xl-6 h-100 sticky-lg-top p-0">
      <?php if(session()->get('is_admin')): ?>
      <div class="bg-white rounded p-4 mx-lg-3 my-3 mt-lg-0 mb-4">
                <div class="row">
                    <div class="col-12 col-md-7 col-lg-9">
                        <h3>Contact</h3>
                    </div>
                </div>
                <div class="col-12 my-3">
                    <div class="col-12">
                        <textarea id="comment_text" rows="3" style="width:100%" placeholder="Contact notes"></textarea>
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
        <?php endif; ?>
        
                <div class="bg-white rounded p-4 mx-lg-3 mb-3 mb-lg-0">
                <div class="row">
                    <div class="col-12 col-md-7 col-lg-9 pt-4">
                        <h3>Audits</h3>
                    </div>
                </div>
                
                    <?php if($audits): ?>

                    <div class="container">
                        <?php foreach($audits as $audit): ?>
                            <div class="row py-2 border-bottom">
                                <div class="col-12">
                                    <a href="<?php echo base_url('audit/'.$audit['audit_id']);?>" target="_blank"><?php echo $audit['audit_id']; ?></a>
                                </div>
                                <div class="col-12">
                                    <?php if($audit['status'] == "complete" || $audit['status'] == "reviewed" ||  $audit['status'] == "reviewing" ): ?>
                                    <?php switch ($audit['status']){
                                        case 'reviewing': ?>
                                            <div class="row">
                                                <div class="col-6">Completed on <?php echo date('d/m/Y', strtotime($audit['completed_date'])); ?></div>
                                                <div class="col-6 text-warning text-end">Under review</div>
                                            </div>
                                        <?php break;
                                        case 'complete': ?>
                                            <div class="row">
                                                <div class="col-6">Completed on <?php echo date('d/m/Y', strtotime($audit['completed_date'])); ?></div>
                                                <div class="col-6 text-warning text-end">Waiting for review</div>
                                            </div>
                                         <? break;
                                        case 'reviewed': ?>
                                            <div class="row">
                                                <div class="col-12">Audited on <?php echo date('d/m/Y', strtotime($audit['audited_date'])); ?></div>
                                            </div>
                                            <div class="row py-2">
                                                <div class="row">
                                                    <div class="col-2">BA</div>
                                                    <div class="col-5">
                                                        <?php echo strtoupper($audit['result_ba']); ?>
                                                    </div>
                                                    <div class="col-5 text-end">
                                                        <?php echo "Exp.". date('d/m/Y', strtotime($audit['expiry_date_ba'])); ?>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-2">ABTA</div>
                                                    <div class="col-5">
                                                        <?php echo strtoupper($audit['result_abta']); ?>
                                                    </div>
                                                    <div class="col-5 text-end">
                                                        <?php echo "Exp.". date('d/m/Y', strtotime($audit['expiry_date_abta'])); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <? break; 
                                    } ?>
                            
                                    <div class="col-12 py-2">
                                        <?php if($audit['is_payable'] == "1"): ?>
                                            <div class"col-12">
                                                <? if($audit['is_paid'] == "1"): ?>
                                                    <div class="row">
                                                        <div class="text-success col-6">Paid <i class='fa fa-check'></i></div>
                                                        <div class="col-6 text-end"><a href="https://dashboard.stripe.com/test/payments/<? echo $audit['payment_id'] ?>" target="_blank">Payment</a></div>
                                                    </div>
                                                <? else: ?>
                                                    <p class="text-danger">Not Paid <i class='fa fa-times'></i></p>
                                                <? endif ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="col-12">
                                                <div class="text-success">Free Audit</div>
                                            </div>
                                        <? endif ?>
                                    </div>
                    
                                    <?php else: ?>
                                    <!-- incomplete -->
                                    <div class="row">
                                        <div class="col-6">Created on <?php echo date('d/m/Y', strtotime($audit['created_date'])); ?></div>
                                        <div class="col-6 text-end text-danger">
                                            <?php switch ($audit['status']){
                                                case 'sent':
                                                    echo 'Unopened';
                                                    break;  
                                                case 'in progress':
                                                    echo 'In Progress';
                                                    break;
                                                case 'pending_payment':
                                                    echo 'Pending Payment';
                                                    break;  
                                                case 'open':
                                                    echo 'Open';
                                                    break;
                                            } ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
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
    if ($("#update_account").length > 0) {
      $("#update_account").validate({
        rules: {
          name: {
            required: true,
          },
          email: {
            required: true,
            maxlength: 80,
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
            maxlength: "The email should be or equal to 80 chars.",
          },
        },
      })
    }
  </script>
    <script>
    function saveComment(){
        var audit_id = document.getElementById('id').value;
        var comment = document.getElementById('comment_text').value;
        $.ajax({
            url: '<?php echo base_url('comment-save-admin');?>',
            type: 'POST',
            data: {
                'audit_id': '',
                'comment': comment,
                'account_id': '<?php echo $account_obj['id']; ?>',
            },
            success: function(msg) {
                location.reload();
            }               
        });        
    }
    function deleteComment(id){
        $.ajax({
                url: '<?php echo base_url('comment-delete');?>',
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
