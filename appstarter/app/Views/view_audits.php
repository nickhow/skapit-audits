
<div class="container mt-4 py-4 px-4 bg-white">
    <div class="row py-3">
        <div class="col-12 col-md-4 px-3">
            <a href="<?php echo base_url('audit/reviewed');?>"><div class="col-12 btn btn-outline-dark">View Recently Reviewed Audits <i class="fas fa-arrow-right"></i></div></a>
        </div>
        <div class="col-12 col-md-4 px-3">
            <a href="<?php echo base_url('audit/expiring');?>"><div class="col-12 btn btn-outline-dark">View Expiring Audits <i class="fas fa-arrow-right"></i></div></a>
        </div>
        <div class="col-12 col-md-4 px-3">
            <a href="<?php echo base_url('audit/unpaid');?>"><div class="col-12 btn btn-outline-dark">View Unpaid Audits <i class="fas fa-arrow-right"></i></div></a>
        </div>
    </div>
    
    
    
    <h2>All Audits</h2>
    <div class="d-flex justify-content-end">
        <a href="<?php echo site_url('/audit/new') ?>" class="btn btn-success mb-2">Add Audit</a>
	</div>
    <?php if(session()->getFlashdata('msg')):?>
        <div class="alert alert-warning">
            <?= session()->getFlashdata('msg') ?>
        </div>
    <?php endif;?>
     
  <div class="mt-3">
     <table class="table table-bordered" id="audits-list">
       <thead>
          <tr>
             <th>View</th>
             <th>Accommodation Name</th>
             <th>First Contacted</th>
             <th>Last Contacted</th>
             <th>Status</th>
             <th>Last Opened</th>
             <th>Review</th>
             <th>On Salesforce</th>
             <th>Actions</th>
          </tr>
       </thead>
       <tbody>
          <?php if($audits): ?>
          <?php foreach($audits as $audit): ?>
          <tr>
              <td>
                <a href="<?php echo base_url('audit/'.$audit['id']);?>" target="_blank"><div class="btn btn-secondary btn-sm">View Audit</div></a>
              </td>
              <td><a href="<?php echo base_url('account/'.$audit['account_id']); ?>" target="_blank"><?php echo ucfirst($audit['accommodation_name']); ?></a></td>
              <td><?php echo date('d/m/Y', strtotime($audit['created_date'])); ?></td>
              <td><?php echo date('d/m/Y', strtotime($audit['sent_date'])); ?></td>
              <td>   
                    <?php 
                        if($audit['status'] === "pending_payment") { 
                            echo  ("<span class='text-danger text-center'>Pending Payment</span>"); 
                        } else {
                            echo ucfirst($audit['status']);
                        } 
                    ?>
              </td>
              <td><?php if( strpos($audit['last_updated'], '0000') !== 0 ) { echo date('d/m/Y', strtotime($audit['last_updated'])); } else { echo "Unopened"; } ?></td>
              
              <td>
                    <?php if($audit['status'] === "complete"): ?>
                        <a href="<?php echo base_url('audit/'.$audit['id'].'/review');?>" target="_blank" class="btn btn-success btn-sm">Review</a>
                    <?php elseif($audit['status'] === "reviewed"): ?>
                        BA: 
                        <?php if($audit['result_ba'] == "suitable"): ?>
                            <span class="text-primary text-center"><?php echo ucFirst($audit['result_ba']); ?></span>
                        <?php elseif ($audit['result_ba'] == "unsuitable"): ?>
                            <span class="text-danger text-center"><?php echo ucFirst($audit['result_ba']); ?></span>
                        <?php else: ?>
                            <span class="text-warning text-center">Unknown</span>
                        <?php endif ?>
                        <br/>
                        ABTA: 
                        <?php if($audit['result_abta'] == "suitable"): ?>
                            <span class="text-primary text-center"><?php echo ucFirst($audit['result_abta']); ?></span>
                        <?php elseif ($audit['result_abta'] == "unsuitable"): ?>
                            <span class="text-danger text-center"><?php echo ucFirst($audit['result_abta']); ?></span>
                        <?php else: ?>
                            <span class="text-warning text-center">Unknown</span>
                        <?php endif ?>
                     <?php elseif($audit['status'] === "reviewing"): ?>
                        <span class="text-warning text-center">Reviewing</span>
                    <?php else: ?>
                        <span class="text-secondary text-center"><i>Not ready for review</i></span>
                    <?php endif ?>
                </td>
                <td>
                <?php 
                        if($audit['added_to_salesforce'] === 1) { 
                            echo "<p class='success'><i class='far fa-tick fa-2x'></i><p>";
                        } else {
                            echo "<p class='danger'><i class='far fa-cross fa-2x'></i><p>";
                        }
                    ?>
                </td>
              <td>
                  <div class="row">
                      <div class="col-4 text-center">
                        <div class="text-secondary copy btn m-0 p-0" data-clipboard-text="<?php echo base_url('audit/'.$audit['id']);?>" onclick="toggleToolTip(this);" data-bs-toggle="tooltip" data-bs-trigger="manual" data-bs-placement="top" data-bs-title="Copied!"><i class="far fa-copy fa-2x"></i></div>
                      </div>
                      <div class="col-4 text-center">
                          <a href="<?php echo base_url('audit/'.$audit['id'].'/edit');?>" target="_blank" class="text-secondary" ><i class="far fa-edit fa-2x"></i></a>
                      </div>
                     <div class="col-4 text-center">
                          <a href="<?php echo base_url('audit/'.$audit['id'].'/delete');?>" class="text-danger"><i class="far fa-trash-alt fa-2x"></i></a>
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
 
<!--<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>-->


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/datetime-moment.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>


<script>
    $(document).ready( function () {
      $.fn.dataTable.moment( 'D/M/YYYY' ); 
      $('#audits-list').DataTable({
          responsive: true,
          "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
          "order": [[ 3, "desc" ]]
      });
      
  } );
</script>
<script>
    function emailFunction(){
        //write ajax call to emailer
    }
</script>
<script>
    var clipboard = new ClipboardJS('.copy');
</script>
<script>
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})
function toggleToolTip(el){
    var tooltip = bootstrap.Tooltip.getInstance(el);
    tooltip.toggle();
    setTimeout(function(){ tooltip.toggle(); }, 1000);
}
</script>

