<?php $this->load->view('header-naked') ?>

<div class="page">
    <div class="page-single">
        <div class="container">
            <div class="row">
                <div class="col col-login mx-auto">
                    <div class="text-center mb-6">
                        <a href="https://aiminstitute.org/"><img src="<?php echo base_url('img/logos/logo.png') ?>" class="small-logo"></a>
                        <a href="https://aiminstitute.org/" class="text-inherit text-decoration-none"><h6 class="mt-5">AIM Platform</h6></a>
                    </div>
                    <?php alerts() ?>
                    <form class="card" action="" method="post">
                        <div class="card-body p-6">
                            <div class="card-title">Login to your account</div>
                            <div class="form-group">
                                <label class="form-label">Email address</label>
                                <input type="text" name="email" id="email" value="<?php echo set_value('email') ?>" class="form-control <?php echo is_valid('email') ?>">
                                <?php echo form_error('email') ?>
                            </div>
                            <div class="form-group">
                                <label class="form-label">
                                    Password
                                    <a href="<?php echo base_url('user/forgot_password') ?>" class="float-right small">Forgot Password</a>
                                </label>
                                <input type="password" name="password" id="password" value="<?php echo set_value('password') ?>" class="form-control <?php echo is_valid('email') ?>">
                                <?php echo form_error('password') ?>
                            </div>
                            <div class="form-group">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input">
                                    <span class="custom-control-label">Remember me</span>
                                </label>
                            </div>
                            <div class="form-footer">
                                <button type="submit" class="btn btn-primary btn-block">Sign in</button>
                            </div>
                        </div>
                    </form>
                    <div class="text-center text-muted">
                        Don't have account yet? <a href="<?php echo base_url("user/signup?{$_SERVER['QUERY_STRING']}") ?>">Sign up</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('footer-naked') ?>