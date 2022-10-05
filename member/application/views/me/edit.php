<?php $this->load->view('header') ?>
<div class="container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Account <small class="ml-2 text-muted">Edit Account</small></h1>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header d-flex">
            <h3 class="card-title">Account Information Form</h3>
        </div>
        <div class="card-body">

            <?php alerts() ?>
            
            <form method="post">
                <div class="row">
                    <div class="form-group col-lg-3">
                        <label for="password">First Name</label>
                        <input type="text" name="first_name" id="first_name" value="<?php echo set_value('first_name', $member->first_name) ?>" class="form-control <?php echo is_valid('first_name') ?>">
                        <?php echo form_error('first_name') ?>
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="password">Last Name</label>
                        <input type="text" name="last_name" id="last_name" value="<?php echo set_value('last_name', $member->last_name) ?>" class="form-control <?php echo is_valid('last_name') ?>">
                        <?php echo form_error('last_name') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label for="password">Email</label>
                        <input type="text" name="email" id="email" value="<?php echo set_value('email', $member->email) ?>" class="form-control <?php echo is_valid('email') ?>">
                        <?php echo form_error('email') ?>
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