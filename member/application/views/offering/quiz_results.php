<?php $this->load->view('header-sya') ?>
<div class="page offering offering-quiz">
    <div class="page-main">
        <div class="heading">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h1>Quiz Results</h1>
                        <p>Find out which one of our offering would be right for you.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="offerings">
            <div class="container bg-white">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3 py-5 mb-5">
                        <?php alerts() ?>
                        <p>Sample results page!</p>
                        <div class="card" style="width: 18rem;">
                            <ul class="list-group list-group-flush list-unstyled">
                                <?php foreach ($results as $key => $value): ?>
                                    <li class="list-group-item">
                                        <?php $label = ucwords(str_replace("points_", "", $key)) ?>
                                        <?php echo $label ?>
                                        <span class="float-right"><?php echo $value ?> </span>
                                    </li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                        <a href="<?php echo base_url("offering") ?>" class="btn btn-sm mt-5"><i class="fa fa-chevron-left mr-2" aria-hidden="true"></i> Back to offering list</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('footer-aim') ?>