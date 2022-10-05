<?php $this->load->view('admin/header') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="page-header">
                        <h1 class="page-title">
                            Programs <span class="text-muted small"><span class="p-2">&middot;</span> <?php echo empty($program) ? "New" : "Edit" ?></span>
                        </h1>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <?php echo alerts() ?>
                        <div class="card p-5 mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Program Name</label>
                                        <input type="text" class="form-control <?php echo is_valid('name') ?>" name="name" value="<?php echo set_value('name', $program->name ?? "" ) ?>">
                                        <?php echo form_error('name') ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Program Description</label>
                                        <textarea type="text" rows="5" class="form-control <?php echo is_valid('description') ?>" name="description"><?php echo set_value('description', $program->description ?? "" ) ?></textarea>
                                        <?php echo form_error('description') ?>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col mt-5">
                                    <button class="btn btn-primary"><?php echo empty($program) ? "Create" : "Update" ?></button>
                                    <a href="<?php echo base_url("admin/program/") ?>" class="btn btn-secondary ml-2">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer') ?>