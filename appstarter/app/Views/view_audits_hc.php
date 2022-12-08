
<div class="container mt-4 py-4 px-4 bg-white">
  <h2>Health & Safety Audits for Review</h2>
    <?php if(session()->getFlashdata('msg')):?>
        <div class="alert alert-warning">
            <?= session()->getFlashdata('msg') ?>
        </div>
    <?php endif;?>
    
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
     <div id="paidToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <strong class="me-auto">Ski API Technologies</strong>
          <small>Just now</small>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            CSV file emailed
        </div>
      </div>
    </div>
    
     
  <div class="mt-3">
   
     <table class="table table-bordered" id="completed-audits-list">
       <thead>
          <tr>
             <th>Accommodation Name</th>
             <th>Type</th>
             <th>Date Submitted</th>
             <th>Action</th>
          </tr>
       </thead>
       <tbody>
          <?php if($audits): ?>
          <?php foreach($audits as $audit): ?>
          <tr>
              <td><?php echo ucfirst($audit['accommodation_name']); ?></td>
              <td><?php echo ucfirst($audit['type']); ?></td>
              <td><?php echo date('d/m/Y', strtotime($audit['completed_date'])); ?></td>
              <td>
                <a href="<?php echo base_url('audit/'.$audit['id'].'/review');?>" class="btn btn-success btn-sm">Review</a>
              </td>
          </tr>
         <?php endforeach; ?>
         <?php endif; ?>
       </tbody>
     </table>
  </div>
  
  <div class="row py-4"></div>
  
    <div class="mt-3">

    
        <div class="row">
            <div  class="col-12 col-md-7 col-lg-8">
                <h3>Reviewed Audits</h3>
            </div>
            <div class="col-12 col-md-5 col-lg-4 text-center text-md-end">
                <div class="btn btn-outline-primary px-5" onclick="emailFile()">Email Completed Audits CSV</div>
            </div>
        </div>
   
     <table class="table table-bordered" id="reviewed-audits-list">
       <thead>
          <tr>
             <th>Accommodation Name</th>
             <th>Type</th>
             <th>Audited Date</th>
             <th>Result BA</th>
             <th>Result ABTA</th>
             <th>Paid</th>
          </tr>
       </thead>
       <tbody>
          <?php if($reviewed_audits): ?>
          <?php foreach($reviewed_audits as $audit): ?>
          <tr>
              <td><?php echo ucfirst($audit['accommodation_name']); ?></td>
              <td><?php echo ucfirst($audit['type']); ?></td>
              <td><?php echo date('d/m/Y', strtotime($audit['audited_date'])); ?></td>
              <td><?php echo ucfirst($audit['result_ba']); ?></td>
              <td><?php echo ucfirst($audit['result_abta']); ?></td>
              <td>
                    <?php if ($audit['paid']) {
                            echo "Paid";
                        } else {
                            echo "Unpaid";
                        } 
                    ?>
              </td>
          </tr>
         <?php endforeach; ?>
         <?php endif; ?>
       </tbody>
     </table>
  </div>
  
  <div class="row py-4"></div>
  
    <div class="mt-3">
        <div class="row">
            <div class="col-12">
                <h3>Sent Audits</h3>
            </div>
        </div>
        <div class="container">
            
            <div class="row">
                <div class="d-none d-lg-block col-4">
                    <div class="chart-container">
                        <canvas id="auditChart"></canvas>
                    </div>  
                </div>
                <div class="col-12 col-lg-8">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="sent-tab" data-bs-toggle="tab" data-bs-target="#sent" type="button" role="tab" aria-controls="sent" aria-selected="true">Sent</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="open-tab" data-bs-toggle="tab" data-bs-target="#open" type="button" role="tab" aria-controls="open" aria-selected="false">Open</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="progress-tab" data-bs-toggle="tab" data-bs-target="#progress" type="button" role="tab" aria-controls="progress" aria-selected="false">In Progress</button>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="sent" role="tabpanel" aria-labelledby="sent-tab">
                            <?php if($with_hotel): ?>
                                <p class="py-3"><b>Sent</b>: Audits which have been sent to a hotel but have not been started.</p> 
                                <?php if($with_hotel['sent']):?>
                                <table class="table table-bordered" id="sent-audits-list">
                                    <thead>
                                        <tr>
                                            <th>Accommodation Name</th>
                                            <th>Resort</th>
                                            <th>Country</th>
                                            <th>First Sent Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($with_hotel['sent'] as $audit): ?>
                                        <tr>
                                            <td><?php echo ucfirst($audit['accommodation_name']); ?></td>
                                            <td><?php echo ucfirst($audit['resort']); ?></td>
                                            <td><?php echo ucfirst($audit['country']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($audit['sent_date'])); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                            <p><i>No audits in this category.</i></p>
                            <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <div class="tab-pane fade" id="open" role="tabpanel" aria-labelledby="open-tab">
                            <?php if($with_hotel): ?>
                                <p class="py-3"><b>Open</b>: Audits where the hotel has signed the waiver but not started the actual audit.</p>
                                <?php if($with_hotel['open']):?>
                                <table class="table table-bordered" id="sent-audits-list">
                                    <thead>
                                        <tr>
                                            <th>Accommodation Name</th>
                                            <th>Resort</th>
                                            <th>Country</th>
                                            <th>First Sent Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($with_hotel['sent'] as $audit): ?>
                                        <tr>
                                            <td><?php echo ucfirst($audit['accommodation_name']); ?></td>
                                            <td><?php echo ucfirst($audit['resort']); ?></td>
                                            <td><?php echo ucfirst($audit['country']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($audit['sent_date'])); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                            <p><i>No audits in this category.</i>
                            <?php endif; ?>
                            <?php endif; ?>      
                        </div>
                        <div class="tab-pane fade" id="progress" role="tabpanel" aria-labelledby="progress-tab">
                            <?php if($with_hotel): ?>
                                <p class="py-3"><b>In Progress</b>: Audits where the hotel is in the process of completing the audit.</p>
                                <?php if($with_hotel['progress']):?>
                                <table class="table table-bordered" id="sent-audits-list">
                                    <thead>
                                        <tr>
                                            <th>Accommodation Name</th>
                                            <th>Resort</th>
                                            <th>Country</th>
                                            <th>First Sent Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($with_hotel['sent'] as $audit): ?>
                                        <tr>
                                            <td><?php echo ucfirst($audit['accommodation_name']); ?></td>
                                            <td><?php echo ucfirst($audit['resort']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($audit['sent_date'])); ?></td>
                                            <td></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                            <p><i>No audits in this category.</i>
                            <?php endif; ?>
                            <?php endif; ?>      
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>
<script>


<?php 
    $counts['sent'] = count($with_hotel['sent']);
    $counts['open'] = count($with_hotel['open']);
    $counts['progress'] = count($with_hotel['progress']);
?>

var cData = JSON.parse(`<?php echo json_encode($counts); ?>`);
const data = {
  labels: [
    "Sent ($counts['sent'])",
    'Open',
    'In Progress'
  ],
  datasets: [{
    label: 'Audits',
    data: [cData.sent,cData.open,cData.progress],
    backgroundColor: [
      'rgb(255, 99, 132)',
      'rgb(54, 162, 235)',
      'rgb(255, 205, 86)'
    ],
    hoverOffset: 4
  }]
};

const config = {
  type: 'pie',
  data: data,
};

  var myChart = new Chart(
    document.getElementById('auditChart'),
    config
  );
</script>


<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/datetime-moment.js"></script>

<script>
    $(document).ready( function () {
      $.fn.dataTable.moment( 'D/M/YYYY' ); 
      $('#completed-audits-list').DataTable({
          responsive: true,
           "order": [[ 2, "asc" ]]
      });
      $('#reviewed-audits-list').DataTable({
          responsive: true,
           "order": [[ 2, "desc" ]]
      });
  } );
  
    function emailFile(){
        $.ajax({
            url: '<?php echo site_url("/generate-csv") ?>',
            type: 'GET',
            success: function(msg) {
                var myToast = new bootstrap.Toast(document.getElementById('paidToast'));
                myToast.show();
            }               
        });
    }
</script>

