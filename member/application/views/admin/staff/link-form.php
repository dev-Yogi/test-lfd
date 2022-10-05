<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="page-header">
                        <h1 class="page-title">
                            Link <span class="text-muted small"><span class="p-2">&middot;</span> <?php echo empty($link) ? "New" : "Edit" ?></span>
                        </h1>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <?php echo alerts() ?>
                        <div class="card p-5 mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Link URL</label>
                                        <input type="text" class="form-control <?php echo is_valid('url') ?>" name="url" value="<?php echo set_value('url', $link->url ?? "" ) ?>">
                                        <?php echo form_error('url') ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Link Label</label>
                                        <input type="text" class="form-control <?php echo is_valid('label') ?>" name="label" value="<?php echo set_value('label', $link->label ?? "" ) ?>">
                                        <?php echo form_error('label') ?>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col mt-5">
                                    <button class="btn btn-primary"><?php echo empty($link) ? "Create" : "Update" ?></button>
                                    <a href="<?php echo base_url("admin/staff") ?>" class="btn btn-secondary ml-2">Cancel</a>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($link)): ?>
                            <a href="<?php echo base_url("admin/staff/link_remove/{$link->id}") ?>" onclick="return confirm('Are you sure you want to remove this link?')" class="btn btn-link float-right mr-3"><i class="fe fe-trash"></i> Remove link</a>
                        <?php endif ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer') ?>