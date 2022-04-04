<div class="container mt-4 bg-white p-4 rounded">
    <h2>Groups</h2>
    <div class="d-flex justify-content-end">
        <a href="<?php echo site_url('/group/new') ?>" class="btn btn-success mb-2">Add Group</a>
	</div>
    <?php if(session()->getFlashdata('msg')):?>
        <div class="alert alert-warning">
            <?= session()->getFlashdata('msg') ?>
        </div>
    <?php endif;?>
  <div class="mt-3">
     <table class="table table-bordered" id="groups-list">
       <thead>
          <tr>
             <th>Name</th>
             <th>Created On</th>
             <th>Actions</th>
          </tr>
       </thead>
       <tbody>
          <?php if($groups): ?>
          <?php foreach($groups as $group): ?>
          <tr>
             <td><?php echo $group['name']; ?></td>
             <td><?php echo date('d/m/Y', strtotime($group['created_date'])); ?></td>
             <td>
                  <div class="row">
                      <div class="col-6 text-center">
                          <a href="<?php echo base_url('group/'.$group['id']);?>"  class="text-secondary" ><i class="far fa-edit fa-2x"></i></a>
                      </div>
                     <div class="col-6 text-center">
                          <a href="<?php echo base_url('group/'.$group['id'].'/delete');?>" class="text-danger"><i class="far fa-trash-alt fa-2x"></i></a>
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.11.5/sorting/datetime-moment.js"></script>
<script>
    $(document).ready( function () {
      
      $.fn.dataTable.moment('DD MM YYYY');

      $('#groups-list').DataTable({
          responsive: true,
          "order": [[ 1, "desc" ]],
          "columns": [
              null,
                { "type": "date" },
                null,
          ],
      });
      
  } );
</script>

