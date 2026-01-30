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
        <div class="row justify-content-md-center">
            <div class="col-5">
                <h2>Register User</h2>

                <?php if(isset($validation)):?>
                <div class="alert alert-warning">
                   <?= $validation->listErrors() ?>
                </div>
                <?php endif;?>

                <form action="<?php echo base_url(); ?>/signup-controller/store" method="post">
                    <input type="hidden" name="account_id" value="<?php echo $account_id ?>" />
                    <input type="hidden" name="name" value="<?php echo $account_name ?>" />
                    <input type="hidden" name="email" value="<?php echo $account_email ?>" />
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
                        <button type="submit" class="btn btn-dark">Signup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>