
<div class="container mt-4 py-4 px-4 bg-white">
    <h2>Newly Audited (last 7 days)</h2>
     
  <div class="mt-3">
     <table class="table table-bordered" id="audits-list">
       <thead>
          <tr>
             <th>View</th>
             <th>Type</th>
             <th>Accommodation Name</th>
             <th>Audited On</th>
             <th>Result BA</th>
             <th>Result ABTA</th>
             <th>Result dnata</th>
          </tr>
       </thead>
       <tbody>
          <?php if($audits): ?>
          <?php foreach($audits as $audit): ?>
          <tr>
             <td><a href="<?php echo base_url('audit/'.$audit['id']);?>"><div class="btn btn-secondary btn-sm">View Audit</div></a></td>
              <td><?php echo ucfirst($audit['type']); ?></td>
              <td><?php echo ucfirst($audit['accommodation_name']); ?></td>
              <td><?php echo date('d/m/Y', strtotime($audit['audited_date'])); ?></td>
              <td><?php echo ucfirst($audit['result_ba']); ?></td>
              <td><?php echo ucfirst($audit['result_abta']); ?></td>
              <td><?php echo ucfirst($audit['result_dnata']); ?></td>
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