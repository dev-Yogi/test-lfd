<?php $this->load->view('admin/header') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="page-header">
                        <h1 class="page-title">
                            Field <span class="text-muted small"><span class="p-2">&middot;</span> <?php echo empty($lesson) ? "New" : "Edit" ?></span>
                        </h1>
                    </div>
                    <form method="post">
                        <?php echo alerts() ?>
                        <div class="card p-5 mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Field Name</label>
                                        <input type="text" class="form-control <?php echo is_valid('label') ?>" name="label" value="<?php echo set_value('label', ($field->label ?? null)) ?>">
                                        <?php echo form_error('label') ?>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label class="form-label">Submission Type</label>
                                        <?php if (empty($field)): ?>
                                            <select class="form-control <?php echo is_valid('type') ?> w-50" name="type">
                                                <option value="">Select type</option>
                                                <option value="text" <?php echo set_select('type', 'text', ($field->type ?? null) == 'text') ?>>Text</option>
                                                <option value="select" <?php echo set_select('type', 'select', ($field->type ?? null) == 'select') ?>>Select</option>
                                            </select>
                                            <small class="form-text text-muted">
                                                <ul class="pl-5">
                                                    <li>Text: Suitable for one-line answers</li>
                                                    <li>Select: A drop-down of multiple options.</li>
                                                </ul>
                                            </small>
                                            <?php echo form_error('type') ?>
                                        <?php else: ?>
                                            <small class="form-text text-muted">Field type cannot be changed after creation.</small>
                                        <?php endif ?>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col mt-5">
                                    <button class="btn btn-primary"><?php echo empty($field) ? "Create" : "Update" ?></button>
                                    <a href="<?php echo base_url("admin/settings/field") ?>" class="btn btn-secondary ml-2">Cancel</a>
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