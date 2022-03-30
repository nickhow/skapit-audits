<table class="table table-bordered" id="chase-list">
    <thead>
        <tr>
            <th>Name</th>
            <th>Property</th>
            <th>Last Opened</th>
            <th>Next Chase Email</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php $moreToShow = false; if($chase): ?>
        <?php $count = 0; ?>
        <?php foreach($chase as $audit): ?>
        <tr>
            <td><?php echo ucfirst($audit->name); ?></td>
            <td><?php echo ucfirst($audit->accommodation_name); ?></td>
            <td><?php echo date('d/m/Y', strtotime($audit->sent_date)); ?></td>
            <td><?php echo ($audit->next_chase); ?></td>
            <td class="text-center"><a href="<?php echo base_url('audit/'.$audit->id.'/chase');?>"><i class="fas fa-envelope"></i></a></td>
        </tr>
        <?php $count++;   if($count >= 5) { $moreToShow = true; break; } ?>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
            <td colspan="3"><i>No chases to show</i></td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php if($moreToShow): ?>
    <a href="<?php echo base_url('chases/'.$chase_time.'/full');?>" target="_blank"><div class="btn btn-outline-primary" >View all <?echo count($chase) ?> chases</div></a>
<?php endif;?>