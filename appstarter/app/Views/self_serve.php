<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Sign up</title>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-md-center ">
          <div class="col-10 col-md-8 col-lg-6 p-4 bg-white rounded">
                

                <?php if(isset($validation)):?>
                <div class="alert alert-warning">
                   <?= $validation->listErrors() ?>
                </div>
                <?php endif;?>

                <form action="<?php echo base_url(); ?>/SignupController/store_selfserve" method="post">
                    <h2>About You</h2>
                    <div class="form-group pt-2">
                        <label>Name</label>
                        <input type="text" name="name" placeholder="Name" value="<?= set_value('name') ?>" class="form-control" >
                    </div>

                    <div class="form-group pt-2">
                        <label>Email</label>
                        <input type="text" name="email" placeholder="Email" value="<?= set_value('email') ?>" class="form-control" >
                    </div>

                    <div class="form-group pt-2">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Password" class="form-control" >
                    </div>

                    <div class="form-group pt-2">
                        <label>Confirm Password</label>
                        <input type="password" name="confirmpassword" placeholder="Confirm Password" class="form-control" >
                    </div>

                    <!-- Private property or group :: show/hide the relevant bits of form -->
                    <div class="form-group pt-2">
                        <label>What type of account do you require?</label>
                        <select id="type" name="type" class="form-select">
                            <option value="private" selected >I only need to audit a single accomodation </option>
                            <option value="group" >I have a multi properties to audit </option>
                        </select>
                    </div>
                    


                    <div id="private_wrapper">
                        <h2>The Property</h2>
                        <div class="form-group pt-2">
                            <label>Contact Phone Number</label>
                            <input type="text" name="phone" placeholder="Phone" value="<?= set_value('phone') ?>" class="form-control" >
                        </div>
                        <div class="form-group pt-2">
                            <label>Accommodation Name</label>
                            <input type="text" name="accommodation_name" placeholder="Accommodation Name" value="<?= set_value('accommodation_name') ?>" class="form-control" >
                        </div>
                        <div class="form-group pt-2">
                            <label>Resort</label>
                            <input type="text" name="resort" placeholder="Resort" value="<?= set_value('resort') ?>" class="form-control" >
                        </div>
                        <div class="form-group pt-2">
                            <label>Country</label>
                            <input type="text" name="country" placeholder="Country" value="<?= set_value('country') ?>" class="form-control" >
                        </div>
                    </div>

                    <div id="group_wrapper">
                        <h2>The Accommodation Group</h2>
                        <div class="form-group pt-2">
                            <label>Group Name</label>
                            <input type="text" name="groupname" placeholder="Group Name" value="<?= set_value('groupname') ?>" class="form-control" >
                        </div>
                    </div>
                

                    <div class="d-grid p-3">
                        <button type="submit" class="btn btn-primary btn-block">Signup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

<script>
    function formUpdate(){
        let private = document.getElementById('private_wrapper');
        let group = document.getElementById('group_wrapper');
        
        if(document.getElementById('type').value == private){
            private.disabled = true;
            group.style.display="none";
        } else {
            private.disabled = false;
            group.style.display="block";
        }
    }
    document.getElementById('type').addEventListener("change", formUpdate);
    formUpdate();
</script>
    

</html>