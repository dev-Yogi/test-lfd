<?php $this->load->view('header') ?>
<div class="container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Meeting <small class="ml-2 text-muted">Organize Meeting</small></h1>
        </div>
    </div>

    <?php alerts() ?>
    <div class="row">
        <?php foreach ($users as $user): ?>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <span class="avatar avatar-xxl mr-5" style="background-image: url(<?php echo $user->avatar ?>)"></span>
                            <div class="media-body">
                                <h4 class="m-0"><?php echo $user->first_name . " " . $user->last_name ?></h4>
                                <p class="text-muted mb-0"><?php echo $user->title ?></p>
                                <div class="mb-0 mt-2">
                                    <a href="<?php echo $user->calendly_url ?>" class="btn btn-secondary" target="_blank"><i class="fe fe-calendar mr-2"></i>Schedule a Meeting</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>
<?php $this->load->view('footer') ?>