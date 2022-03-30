<div class="container mt-4 py-4 px-4 bg-white">
<h2>Chase List <?php echo $chase_time ?> days</h2>
  <div class="mt-3">
     <table class="table table-bordered" id="audits-list">
       <thead>
          <tr>
             <th>Account</th>
             <th>Type</th>
             <th>Accommodation Name</th>
             <th>Created On</th>
             <th>Last Sent On</th>
             <th>Status</th>
             <th>Next Chase Email</th>
             <th>Chase</th>
          </tr>
       </thead>
       <tbody>
          <?php if($chase): ?>
          <?php foreach($chase as $audit): ?>
          <tr>
              <td><?php echo ucfirst($audit->name); ?></td>
              <td><?php echo ucfirst($audit->type); ?></td>
              <td><?php echo ucfirst($audit->accommodation_name); ?></td>
              <td><?php echo date('d/m/Y', strtotime($audit->created_date)); ?></td>
              <td><?php echo date('d/m/Y', strtotime($audit->sent_date)); ?></td>
              <td><?php echo ucfirst($audit->status); ?></td>
               <td><?php echo ($audit->next_chase); ?></td>
              <td class="text-center"><a href="<?php echo base_url('audit/'.$audit->id.'/chase');?>"><div class="btn btn-warning btn-sm"><i class="fas fa-envelope"></i> Send chase email</div></a></td>
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
      $('#audits-list').DataTable({
          responsive: true
      });
  } );
</script>