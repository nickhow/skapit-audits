<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Create New User</title>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-md-center ">
          <div class="col-10 col-md-8 col-lg-6 p-4 bg-white rounded">
                <h2>Register User</h2>

                <?php if(isset($validation)):?>
                <div class="alert alert-warning">
                   <?= $validation->listErrors() ?>
                </div>
                <?php endif;?>

                <form action="<?php echo base_url(); ?>/signup-controller/store" method="post">
                    <div class="form-group pt-2">
                        <label>Name</label>
                        <input type="text" name="name" placeholder="Name" value="<?= set_value('name') ?>" class="form-control" >
                    </div>
                    
                    <div class="form-group pt-2">
                        <label>Accommodation Group</label>
                        <select id="group_id" name="group_id" class="form-select">
                            <?php if( session()->get('id') == 1 ): //if the logged in user is me ?>
                                <option value="0">No Group</option>
                            <?php endif; ?>
                                <?php foreach($groups as $group) { ?>
                                    <option value="<?php echo $group['id'] ?>"  <?php echo set_select('group_id',$group['id'] , ( !empty($data) && $data == $group['id']  ? TRUE : FALSE ));?>  ><?php echo  $group['name']  ?></option>
                                <?php } ?>
                                
                        </select>
                    </div>

                    <div class="form-group pt-2">
                    <label>Email</label>
                        <input type="email" name="email" placeholder="Email" value="<?= set_value('email') ?>" class="form-control" >
                    </div>

                    <div class="form-group pt-2">
                    <label>Username</label>
                        <input type="username" name="username" placeholder="Username" value="<?= set_value('username') ?>" class="form-control" >
                    </div>

                    <div class="form-group pt-2">
                    <label>Password</label>
                        <input type="password" name="password" placeholder="Password" class="form-control" >
                    </div>

                    <div class="form-group pt-2">
                    <label>Confirm Password</label>
                        <input type="password" name="confirmpassword" placeholder="Confirm Password" class="form-control" >
                    </div>

                    <div class="d-grid p-3">
                        <button type="submit" class="btn btn-primary btn-block">Signup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>