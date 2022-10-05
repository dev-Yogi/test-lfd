<?php $this->load->view('header') ?>
<div class="container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Account <small class="ml-2 text-muted">General Settings</small></h1>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header d-flex">
            <h3 class="card-title">Account Information</h3>
        </div>
        <div class="card-body">

            <?php alerts() ?>
            <table class="table mb-5">
                <tr>
                    <th width="140">Name</th>
                    <td><?php echo $user->first_name ?> <?php echo $user->last_name ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo $user->email ?></td>
                </tr>
                <tr>
                    <th>Account Created</th>
                    <td><?php echo date('j M, Y', strtotime($user->created)) ?></td>
                </tr>
            </table>
            <a href="<?php echo base_url("me//edit") ?>" class="btn btn-secondary mr-2"><i class="fe fe-edit-2 mr-2 text-muted"></i> Edit Account</a>
            <a href="<?php echo base_url("me//password") ?>" class="btn btn-secondary mr-2"><i class="fe fe-lock mr-2 text-muted"></i> Change Password</a>
        </div>
    </div>
</div>
<?php $this->load->view('footer') ?>