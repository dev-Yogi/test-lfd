<?php $this->load->view('header-aim') ?>
<div class="page">
    <div class="page-main">
        <div class="heading">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="display-4">Programs</h1>
                        <p class="pt-3">Catalog of programs by AIM and the community</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="programs">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <form action="<?php echo base_url('program') ?>">
                            <div class="input-group mb-5">
                                <input type="text" class="form-control form-control-lg" name="search" value="<?php echo $keywords ?? null ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-8 offset-lg-2 text-muted text-center mb-3">
                        Want to tell us about a program? <a href="<?php echo base_url('program/submit') ?>" class="btn btn-outline-primary btn-sm ml-1">Submit one here!</a>
                    </div>

                    <div class="col-lg-12 text-center mb-5 mt-4">
                        <a href="<?php echo base_url('program/catalog/table') ?>" class="btn btn-outline-secondary"><i class="fa fa-table" aria-hidden="true"></i> View as table</a>
                    </div>

                    <?php if (!empty($categories)): ?>
                        <div class="col-10 offset-1">
                            <div class="row">
                                <?php foreach ($categories as $name => $programs): ?>
                                    <div class="col-12"><h2 class="my-5"><?php echo $name ?></h2></div>
                                    <?php foreach ($programs as $program): ?>
                                        <div class="col-lg-6">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <a href="<?php echo $program->url ?>" class="text-decoration-none"><h3 class=""><?php echo $program->title ?></h3></a>
                                                    <a href="<?php echo $program->url ?>" class="text-decoration-none text-inherit"><p><?php echo $program->description ?></p></a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                <?php endforeach ?>
                            </div>
                        </div>
                    <?php else: ?>

                        <div class="col-12"><h2 class="my-5">Search results for "<?php echo $keywords ?? null ?>"</h2></div>
                        <?php foreach ($programs as $program): ?>
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <a href="<?php echo $program->url ?>" class="text-decoration-none"><h3 class=""><?php echo $program->title ?></h3></a>
                                        <a href="<?php echo $program->url ?>" class="text-decoration-none text-inherit"><p><?php echo $program->description ?></p></a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    <?php endif ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('footer-aim') ?>