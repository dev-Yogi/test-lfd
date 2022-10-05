<?php $this->load->view('header-naked') ?>

<div class="page bg-cyan">
    <div class="page-single">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="card" action="" method="post">
                        <div class="card-body p-6 text-center">
                            <div class="navbar-brand my-5">
                                <img src="https://aiminstitute.org/wp-content/themes/aim-2020/img/logo.png" class="navbar-brand-img" alt="AIM Student Portal">
                                Student Portal
                            </div>
                            <div class="h1 timer my-5"></div>
                            <div class="py-5">
                                <p>Welcome to the new AIM Student Portal! <br>We will be opening up on <b>June 22, 2020</b>. <br>Bookmark this page to come back to it later!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $script = <<<EOD
var countdown = new Countdown({
    dateEnd: new Date('June 22, 2020 14:00')
});
EOD;
?>
<?php $js = array(base_url('js/countdown.js')) ?>
<?php $this->load->view('footer-naked', compact('js', 'script')) ?>