<style>
 td, th {
     vertical-align: middle!important;
}
</style>

<div class="container mt-4 bg-white p-4 rounded">
    <h2>Properties</h2>
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
             <th>Group</th>
             <th>Created On</th>
             <th>Latest audit status</th>
             <th>Audit Actions</th>
          </tr>
       </thead>
       <tbody>
          <?php if($accounts): print_r($accounts); ?>
          
          <?php foreach($accounts as $account):   ?>

           <?php $account = [
                    'account' = [
                        'id' => '1',
                        'accommodation_name' => 'test',
                        'resort' => 'resort',
                    ],
            ]; 
           ?>
  

          <tr>
             <td>
                <div class="row">
                    <div class="col-8">
                    <a href="<?php echo base_url('account/'.$account['account']['id']);?>"><?php echo $account['account']['accommodation_name'].", ".$account['account']['resort']; ?></a>
                    </div>
                    <div class="col-4 ms-auto">
                        <div class="row">
                            <div class="col-6 text-center">
                                <a href="<?php echo base_url('account/'.$account['account']['id']);?>"  class="text-secondary" ><i class="far fa-edit fa-2x"></i></a>
                            </div>
                            <div class="col-6 text-center">
                                <a href="<?php echo base_url('account/'.$account['account']['id'].'/delete');?>" class="text-danger"><i class="far fa-trash-alt fa-2x"></i></a>
                            </div>
                        </div>
                  </div>
                
            </td>
            <td><?php if(is_null($account['group'])) { echo  "<i>No group</i>"; } else { echo $account['group']['name']; } ?></td>
            <td><?php echo date('d/m/Y', strtotime($account['account']['created_date'])); ?></td>


<?php   if( is_null($account['audit']) || !$account['audit'] || count($account['audit']) === 0): ?>
                    <td>No audits for this property</td><td><!-- no audit to show actions for --> </td>
<?php else: ?>

                    <td>
                    <!-- Status : sent, open, in progress, pending_payment = Active  //  complete, reviewing = Submitted // reviewed = {show results} -->

                        <?php 
                        
                        if ( in_array($account['audit']['status'], array("sent", "open", "in progress", "pending_payment") ) ){
                                echo "Active";
                        } elseif ( in_array($account['audit']['status'], array("complete", "reviewing") ) ){
                            echo "Submitted";
                        } elseif ( in_array($account['audit']['status'], array("reviewed") ) ) { ?>

                            <table class="table m-0">
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php if($account['audit']['result_ba'] == "suitable"): ?>
                                                BA: <span class="text-primary text-center"><?php echo ucFirst($account['audit']['result_ba']); ?></span>
                                            <?php elseif ($account['audit']['result_ba'] == "unsuitable"): ?>
                                                BA: <span class="text-danger text-center"><?php echo ucFirst($account['audit']['result_ba']); ?></span>
                                            <?php else: ?>
                                                BA: <span class="text-warning text-center">Unknown</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($account['audit']['result_abta'] == "suitable"): ?>
                                                ABTA: <span class="text-primary text-center"><?php echo ucFirst($account['audit']['result_abta']); ?></span>
                                            <?php elseif ($account['audit']['result_abta'] == "unsuitable"): ?>
                                                ABTA: <span class="text-danger text-center"><?php echo ucFirst($account['audit']['result_abta']); ?></span>
                                            <?php else: ?>
                                                ABTA: <span class="text-warning text-center">Unknown</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><p class="m-0"><small>Exp.  <?php echo date('d/m/Y', strtotime($account['audit']['expiry_date_ba'])); ?></small></p></td>
                                        <td><p class="m-0"><small>Exp.  <?php echo date('d/m/Y', strtotime($account['audit']['expiry_date_abta'])); ?></small></p></td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php } else { echo "Unknown" ; } ?>
                    </td> 
                    <td>
                        <div class="row">
                            <div class="col text-center">
                                <a href="<?php echo base_url('audit/'.$account['audit']['id']);?>"  class="text-secondary">View Audit</a>
                            </div>

                            <div class="col text-center">
                                <?php 
                                if ( in_array($account['audit']['status'], array("sent", "open", "in progress", "pending_payment") ) ): ?>
                                        <a href="<?php echo base_url('audit/'.$account['audit']['id'].'/edit');?>"  class="text-secondary">Edit Audit</a>
                                <?php elseif ( $account['audit']['status'] == "reviewed" && ( $account['audit']['result_ba'] == "unsuitable" || $account['audit']['result_abta'] == "unsuitable" )): ?>
                                    <a href="<?php echo base_url('audit/'.$account['audit']['id'].'/resubmit');?>" class="text-danger">Resubmit</i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
<?php endif; ?>
             
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
          "order": [[ 2, "desc" ]]
      });
  } );
</script>
