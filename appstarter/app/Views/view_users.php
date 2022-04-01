<div class="container mt-4 bg-white p-4 rounded">
    <h2>Users</h2>
    <?php if(session()->get('is_admin') || session()->get('enable_groups') ): ?>
        <div class="d-flex justify-content-end">
            <a href="<?php echo site_url('/signup') ?>" class="btn btn-success mb-2">Add A New User</a>
    	</div>
	<?php endif ?>

    <?php if(session()->getFlashdata('msg')):?>
        <div class="alert alert-warning">
            <?= session()->getFlashdata('msg') ?>
        </div>
    <?php endif;?>
     
  <div class="mt-3">
     <table class="table table-bordered" id="users-list">
       <thead>
          <tr>
             <th>Name</th>
             <th>Username</th>
             <th>Group / Property</th>
             <th>Created On</th>
             <th>Action</th>
          </tr>
       </thead>
       <tbody>
          <?php if($users): ?>
          <?php foreach($users as $user): ?>
          <tr>
             <td><?php echo $user['name']; ?></td>
             <td><?php echo $user['username']; ?></td>
             <td>
                 <?php if($user['group_name']) : ?>
                    <a href="<?php echo base_url('group/'.$user['group_id']);?>" target="_blank"><?php echo $user['group_name'];?></a>
                <?php elseif($user['account_name']): ?>
                    <a href="<?php echo base_url('account/'.$user['account_id']);?>" target="_blank"><?php echo $user['account_name']; ?></a>
                <?php endif; ?>
             </td>
             <td>
             <td><?php echo $user['created_date']; ?></td>
               <div class="row">
                    <!--  Remove for now - to buggy.
                    <div class="col-6 text-center">
                          <a href="<?php // echo base_url('user/'.$user['id']);?>"  class="text-secondary" ><i class="far fa-edit fa-2x"></i></a>
                    </div>
                      -->
                     <div class="col-6 text-center">
                         <?php if(session()->get('id') == $user['id']): ?>
                         <a class="text-secondary"><i class="far fa-trash-alt fa-2x"></i></a>
                         <?php else: ?>
                          <a href="<?php echo base_url('user/'.$user['id'].'/delete');?>" class="text-danger"><i class="far fa-trash-alt fa-2x"></i></a>
                          <?php endif ?>
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
      $('#users-list').DataTable({
          responsive: true
      });
  } );
</script>
