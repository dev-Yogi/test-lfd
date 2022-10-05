<?php $this->load->view('header') ?>
<div class="container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Account <small class="ml-2 text-muted">Change Password</small></h1>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header d-flex">
            <h3 class="card-title">Change Password</h3>
        </div>
        <div class="card-body">
            <?php alerts() ?>
            <form method="post">
                <div class="row">
                    <div class="form-group col-lg-4">
                        <label for="password">Current Password</label>
                        <input type="password" name="current_password" id="current_password" value="<?php echo set_value('current_password') ?>" class="form-control <?php echo is_valid('current_password') ?>">
                        <?php echo form_error('current_password') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-4">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" value="<?php echo set_value('password') ?>" class="form-control <?php echo is_valid('password') ?>">
                        <?php echo form_error('password') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-4">
                        <label for="password2">Password (again)</label>
                        <input type="password" name="password2" id="password2" value="<?php echo set_value('password2') ?>" class="form-control <?php echo is_valid('password2') ?>">
                        <?php echo form_error('password2') ?>
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" value="Save" class="btn btn-primary">
                    <a href="<?php echo base_url("me") ?>" class="btn btn-seconday">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view('footer') ?>
