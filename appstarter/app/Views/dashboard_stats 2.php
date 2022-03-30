
<div class="container">
    <div class="row">
        <div class="col bg-primary text-center text-white rounded p-3 m-3">
            <h1><?php echo $new_completed->count;  ?></h1>
            <p><b>Completed<br/>(last 7 days)</b></p>
        </div>
        <div class="col bg-primary text-center text-white rounded p-3 m-3">
            <h1><?php echo $new_reviewed->count; ?></h1>
            <p><b>Audited<br/>(last 7 days)</b></p>
        </div>
        <div class="col bg-primary text-center text-white rounded p-3 m-3">
            <h1><?php echo $new_pass->count;  ?></h1>
            <p><b>Passes<br/>(last 7 days)</b></p>
        </div>
       <div class="col bg-primary text-center text-white rounded p-3 m-3">
            <h1><?php echo $total_reviewed->count;  ?></h1>
            <p><b>Total active<br/>(passed, not expired)</b></p>
        </div>
        <div class="col bg-primary text-center text-white rounded p-3 m-3">
            <h1><?php echo $expire_soon->count;  ?></h1>
            <p><b>Expiring<br/>(in next 30 days)</b></p>
        </div>
    </div>
</div> 


