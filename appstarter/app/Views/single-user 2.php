<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Update User</title>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-md-center">
            <div class="col-5 rounded border bg-white p-4">
                <h2>Update User</h2>

                <?php if(isset($validation)):?>
                <div class="alert alert-warning">
                   <?= $validation->listErrors() ?>
                </div>
                <?php endif;?>

                <form action="<?= site_url('/user/update') ?>" method="post">
                    <input type="hidden" name="id" value="<?php echo $user_obj['id']; ?>" >
                    <div class="form-group pt-2">
                        <label>Name</label>
                        <input type="text" name="name" placeholder="Name" value="<?php echo $user_obj['name']; ?>" class="form-control" >
                    </div>
                    
                    <?php if(session()->get('is_admin')): ?>
                    <div class="form-group pt-2">
                        <label>Accommodation Group</label>
                        <select id="group_id" name="group_id" class="form-select">
                                <option value="0">No Group</option>
                                <?php foreach($groups as $group) { ?>
                                    <option value="<?php echo $group['id'] ?>"  <?php echo set_select('group_id',$group['id'] , ( !empty($user_obj['group_id']) && $user_obj['group_id'] == $group['id']  ? TRUE : FALSE ));?>  ><?php echo  $group['name']  ?></option>
                                <?php } ?>
                                
                        </select>
                    </div>
                    <?php else: ?>
                        <input type="hidden" name="group_id" value="<?php echo ($user_obj['group_id']); ?>">
                    <?php endif; ?>
                    
                    <?php if($user_obj['account_id']) : ?>
                        <div class="pt-2">
                            <label>Account</label>
                            <div class="bg-light rounded border row m-0">
                                <div class="col-12">
                                </div>
                                <div class="col-6">
                                    <div class="form-group pt-2">
                                        <label>Property</label>
                                        <input type="text" name="property" placeholder="Property" disabled value="<?php echo $account_obj['accommodation_name']; ?>" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group pt-2">
                                        <label>Resort</label>
                                        <input type="text" name="resort" placeholder="Resort" disabled value="<?php echo $account_obj['resort']; ?>" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-12">
                                    <p class="m-0 py-2"><a href="<?php echo base_url('account/'.$user_obj['account_id']);?>">View Account</a></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-group pt-2">
                    <label>Username</label>
                        <input type="username" name="username" placeholder="Username" disabled value="<?php echo $user_obj['username']; ?>" class="form-control" >
                    </div>

                    <div class="form-group pt-2">
                    <label>Password</label>
                        <input type="password" name="password" placeholder="Password" class="form-control" >
                    </div>

                    <div class="d-grid p-3">
                        <button type="submit" class="btn btn-dark">Update user</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>