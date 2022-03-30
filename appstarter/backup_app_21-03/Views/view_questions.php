<div class="container mt-4 bg-white p-4 rounded">
    <h2>Questions</h2>
    <?php if(session()->getFlashdata('msg')):?>
        <div class="alert alert-warning">
            <?= session()->getFlashdata('msg') ?>
        </div>
    <?php endif;?>
  <div class="mt-3">
     <table class="table table-bordered" id="questions-list">
       <thead>
          <tr>
             <th>Question Number</th>
             <th>Question</th>
             <th>Actions</th>
          </tr>
       </thead>
       <tbody>
          <?php if($questions): ?>
          <?php foreach($questions as $question): ?>
          <tr>
             <td><?php echo $question['question_number']; ?></td>
             <td><?php echo $question['question']; ?></td>
             <td>
                  <div class="row">
                      <div class="col text-center">
                          <a href="<?php echo base_url('question/'.$question['id']);?>"  class="text-secondary" ><i class="far fa-edit fa-2x"></i></a>
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
      $('#questions-list').DataTable({
          responsive: true,
      });
  } );
</script>

