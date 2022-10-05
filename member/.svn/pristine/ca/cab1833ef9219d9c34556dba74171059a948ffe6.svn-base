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
                    <form class="card" action="" method="post">
                        <div class="card-body p-6">
                            <div class="card-title">Register a new account</div>
                            <?php alerts() ?>
                            <div class="form-group">
                                <label for="first_name">First Name<span class="form-required">*</span></label>
                                <input type="text" name="first_name" id="first_name" value="<?php echo set_value('first_name') ?>" class="form-control <?php echo is_valid('first_name') ?>">
                                <?php echo form_error('first_name') ?>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" id="last_name" value="<?php echo set_value('last_name') ?>" class="form-control <?php echo is_valid('last_name') ?>">
                                <?php echo form_error('last_name') ?>
                            </div>
                            <div class="form-group">
                                <label for="email">Email<span class="form-required">*</span></label>
                                <input type="text" name="email" id="email" value="<?php echo set_value('email') ?>" class="form-control <?php echo is_valid('email') ?>">
                                <?php echo form_error('email') ?>
                            </div>
                            <div class="form-group">
                                <label for="password">Password<span class="form-required">*</span></label>
                                <input type="password" name="password" id="password" value="" class="form-control <?php echo is_valid('password') ?>">
                                <?php echo form_error('password') ?>
                            </div>
                            <div class="form-group">
                                <label for="password2">Confirm Password<span class="form-required">*</span></label>
                                <input type="password" name="password2" id="password2" value="" class="form-control <?php echo is_valid('password2') ?>">
                                <?php echo form_error('password2') ?>
                            </div>

                            <div class="form-group">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input <?php echo is_valid('agreed_terms') ?>" name="agreed_terms" id="agreed_terms" value="1">
                                    <span class="custom-control-label small">I agree to the Terms & Conditions<span class="form-required">*</span> <a href="<?php echo base_url('terms') ?>" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Read Terms & Conditions" target="_blank"><i class="fe fe-external-link"></i></a></span>
                                    <?php echo form_error('agreed_terms') ?>
                                </label>
                            </div>

                            <div class="form-group">
                                <div class="g-recaptcha" name="g-recaptcha" data-sitekey="6LcGkhQTAAAAANzuHdzKcI9e_nbPQPxtT0kRcRVu"></div>
                                <?php echo form_error('g-recaptcha-response', '<div class="text-danger small">', '</div>') ?>
                            </div>

                            <div class="form-footer">
                                <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
                            </div>
                        </div>
                    </form>
                    <div class="text-center text-muted">
                        Already have an account? <a href="<?php echo base_url("user/login?{$_SERVER['QUERY_STRING']}") ?>">Sign In</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $js = array('https://www.google.com/recaptcha/api.js') ?>
<?php $this->load->view('footer-naked', compact('js')) ?>