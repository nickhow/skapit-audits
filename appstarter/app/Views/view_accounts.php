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
             <th>Name</th>
             <th>Email</th>
             <th>Phone</th>
             <th>Accommodation Name</th>
             <th>Resort</th>
             <th>Country</th>
             <th>Created On</th>
             <th>Action</th>
          </tr>
       </thead>
       <tbody>
          <?php if($accounts): ?>
          <?php foreach($accounts as $account): ?>
          <tr>
             <td><?php echo $account['name']; ?></td>
             <td><?php echo $account['email']; ?></td>
             <td><?php echo $account['phone']; ?></td>
             <td><?php echo $account['accommodation_name']; ?></td>
             <td><?php echo $account['resort']; ?></td>
             <td><?php echo $account['country']; ?></td>
             <td><?php echo $account['created_date']; ?></td>
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
<script>
    $(document).ready( function () {
      $('#accounts-list').DataTable({
          responsive: true,
          "order": [[ 6, "desc" ]]
      });
  } );
</script>
