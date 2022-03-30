<div id="charts" class="container py-3">
    <h3>Activity Overview</h3>
    <div class="row">
        <div class="col-12 col-md-6 py-3">
            <h3>Activity - past 7 days</h3>
            <div class="chart-container col-12 position-relative">
                <canvas id="auditChart"></canvas>
            </div>
        </div>
        <div class="col-12 col-md-6 py-3">
            <div class="row">
                <div class="col-12 col-lg-4">
                    <h3>Chase list</h3>
                </div>
                <div class="col-12 col-lg-8 d-flex align-items-center justify-content-end">
                    <div id="2" name="chase_filter" class="btn btn-outline-secondary btn-sm align-middle px-2 mx-2" onclick="refresh(2)">2 Days Old</div>
                    <div id="7" name="chase_filter" class="btn btn-secondary btn-sm align-middle px-2 mx-2" onclick="refresh(7)">7 Days Old</div>
                    <div id="30" name="chase_filter" class="btn btn-outline-secondary btn-sm align-middle px-2 mx-2" onclick="refresh(30)">30 Days Old</div>
                </div>
            </div>
            <div id="chases">
                <?php echo view('dashboard_chase', $chase);  ?>
            </div>
        </div>
</div>
        
    </div>
</div>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>
<script>
 var cData = JSON.parse(`<?php echo $chart_data; ?>`);

var labels = cData.label;   //was const -- breaks with reload
var data = {  //was const -- breaks with reload
  labels: labels,
  datasets: [{
    label: 'Sent',
    backgroundColor: 'rgb(255, 99, 132)',
    borderColor: 'rgb(255, 99, 132)',
    data: cData.sent,
    tension: 0.4
  },
  {
    label: 'open',
    backgroundColor: 'rgb(2, 123, 42)',
    borderColor: 'rgb(2, 123, 42)',
    data: cData.open,
    tension: 0.4
  },
  {
    label: 'In Progress',
    backgroundColor: 'rgb(122, 33, 132)',
    borderColor: 'rgb(122, 33, 132)',
    data: cData.progress,
    tension: 0.4
  }]
};

var config = {  //was const -- breaks with reload
  type: 'line',
  data: data,
  options: {  
    responsive: true,
    maintainAspectRatio: true,
    aspectRatio:2,
  }
};

  var myChart = new Chart(
    document.getElementById('auditChart'),
    config
  );
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script>
    
    function refresh(days){

    var buttons = document.getElementsByName('chase_filter');
    for (var button of buttons) {
        button.classList.remove('btn-secondary');
        button.classList.add('btn-outline-secondary');
    }
      document.getElementById(days).classList.remove('btn-outline-secondary');
      document.getElementById(days).classList.add('btn-secondary');
      $('#chases').load("<?php echo site_url("/refresh-stats/");?>"+days); 
    }

</script>