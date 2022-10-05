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
                            <div class="card-title">Forgot Password</div>
                            <?php alerts() ?>
                            <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" name="email" id="email" value="<?php echo set_value('email') ?>" class="form-control <?php echo is_valid('email') ?>">
                            <?php echo form_error('email') ?>
                        </div>

                        <div class="form-group">
                            <div class="g-recaptcha" name="g-recaptcha" data-sitekey="6LcGkhQTAAAAANzuHdzKcI9e_nbPQPxtT0kRcRVu"></div>
                            <?php echo form_error('g-recaptcha-response', '<div class="text-danger small">', '</div>') ?>
                        </div>

                        <div class="form-group">
                            <input type="submit" value="Send" class="btn btn-primary">
                            <a href="<?php echo base_url('user/login') ?>" class="btn btn-secondary ml-2">Cancel</a>
                        </div>
                    </form>
                    <div class="text-center text-muted">
                        <!-- Don't have account yet? <a href="<?php echo base_url('user/signup') ?>">Sign up</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $js = array('https://www.google.com/recaptcha/api.js') ?>
<?php $this->load->view('footer-naked', compact('js')) ?>