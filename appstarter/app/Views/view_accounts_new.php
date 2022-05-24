<div class="container mt-4 bg-white p-4 rounded">
    <h2>Accounts</h2>
    <div class="d-flex justify-content-end">
        <a href="<?php echo site_url('/account/new') ?>" class="btn btn-success mb-2">Add A New Property</a>
	</div>

    <?php if(session()->getFlashdata('msg')):?>
        <div class="alert alert-warning">
            <?= session()->getFlashdata('msg') ?>
        </div>
    <?php endif;?>
     
  <div class="mt-3">
     <table class="table table-bordered" id="accounts-list">
       <thead>
          <tr>
             <th>Property Name</th>
             <th>Created On</th>
             <th>Property Actions</th>
             <th>Latest audit status</th>
             <th>Audit Actions</th>
          </tr>
       </thead>
       <tbody>
          <?php if($accounts): ?>
          <?php foreach($accounts as $account): ?>
          <tr>
             <td><?php echo $account['accommodation_name']; ?></td>
             <td><?php echo date('d/m/Y', strtotime($account['created_date'])); ?></td>
             <td>
               <div class="row">
                      <div class="col-6 text-center">
                          <a href="<?php echo base_url('account/'.$account['id']);?>"  class="text-secondary" ><i class="far fa-edit fa-2x"></i></a>
                      </div>
                     <div class="col-6 text-center">
                          <a href="<?php echo base_url('account/'.$account['id'].'/delete');?>" class="text-danger"><i class="far fa-trash-alt fa-2x"></i></a>
                      </div>
                  </div>
              </td>

              <td>
              <!-- Status : sent, open, in progress, pending_payment = Active  //  complete, reviewing = Submitted // reviewed = {show results} -->
                <?php if ( in_array($audits[$account['id']]['status'], array("sent", "open", "in progress", "pending_payment") ) ){
                        echo "Active";
                 } elseif ( in_array($audits[$account['id']]['status'], array("complete", "reviewing") ) ){
                     echo "Submitted";
                 } elseif ( in_array($audits[$account['id']]['status'], array("reviewed") ) ) {
                    echo "BA:"; ?>
                    <?php if($audits[$account['id']]['result_ba'] == "suitable"): ?>
                        <span class="text-primary text-center"><?php echo ucFirst($audit['result_ba']); ?></span>
                    <?php elseif ($audits[$account['id']]['result_ba'] == "unsuitable"): ?>
                        <span class="text-danger text-center"><?php echo ucFirst($audit['result_ba']); ?></span>
                    <?php else: ?>
                        <span class="text-warning text-center">Unknown</span>
                    <?php endif; ?>
                    <br/>
                    <?php
                    echo "ABTA"; ?>
                    <?php if($audits[$account['id']]['result_abta'] == "suitable"): ?>
                        <span class="text-primary text-center"><?php echo ucFirst($audit['result_abta']); ?></span>
                    <?php elseif ($audits[$account['id']]['result_abta'] == "unsuitable"): ?>
                        <span class="text-danger text-center"><?php echo ucFirst($audit['result_abta']); ?></span>
                    <?php else: ?>
                        <span class="text-warning text-center">Unknown</span>
                    <?php endif; 
                 } else {
                     echo "Unknown" ;
                }
                ?>
              </td> 
              <td>
               <div class="row">
                      <div class="col text-center">
                          <a href="<?php echo base_url('audit/'.$audits[$account['id']]['id']);?>"  class="text-secondary">Edit Audit</a>
                      </div>
                      <div class="col text-center">
                          <a href="<?php echo base_url('audit/'.$audits[$account['id']]['id']);?>"  class="text-secondary">View Audit</a>
                      </div>
                     <div class="col text-center">
                          <a href="<?php echo base_url('audit/'.$audits[$account['id']]['id']);?>" class="text-danger">Resubmit</i></a>
                      </div>
                  </div>
              </td>

             
          </tr>
         <?php endforeach; ?>
         <?php endif; ?>
       </tbody>
     </table>
  </div>
</div>
 
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/datetime-moment.js"></script>
<script>
    $(document).ready( function () {
      $.fn.dataTable.moment( 'D/M/YYYY' ); 
      $('#accounts-list').DataTable({
          responsive: true,
          "order": [[ 6, "desc" ]]
      });
  } );
</script>