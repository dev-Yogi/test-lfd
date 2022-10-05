<?php $this->load->view('header-naked') ?>

<div class="page">
    <div class="page-single">
        <div class="container">
            <div class="row">
                <div class="col col-login mx-auto">
                    <div class="text-center mb-6">
                        <img src="<?php echo base_url('img/logos/logo.png') ?>" class="small-logo">
                        <h6 class="mt-5">AIM Platform</h6>
                    </div>
                    <form class="card" action="" method="post">
                        <div class="card-body p-6">
                            <div class="card-title">Forgot Password</div>
                            <?php alerts() ?>
                            <div class="form-group">
                                <label for="password">New Password</label>
                                <input type="password" name="password" id="password" value="<?php echo set_value('password') ?>" class="form-control <?php echo is_valid('password') ?>">
                                <?php echo form_error('password') ?>
                            </div>
                            <div class="form-group">
                                <label for="password2">New Password (again)</label>
                                <input type="password" name="password2" id="password2" value="<?php echo set_value('password2') ?>" class="form-control <?php echo is_valid('password2') ?>">
                                <?php echo form_error('password2') ?>
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Send" class="btn btn-primary">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('footer-naked') ?>