<?php $this->load->view('admin/header') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="page-header">
                        <h1 class="page-title">
                            Field <span class="text-muted small"><span class="p-2">&middot;</span> <?php echo empty($field) ? "New" : "Edit" ?></span>
                        </h1>
                    </div>
                    <form method="post">
                        <?php echo alerts() ?>
                        <div class="card p-5 mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Field Name</label>
                                        <input type="text" class="form-control-plaintext" name="title" value="<?php echo $field->label ?>">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Field Type</label>
                                        <input type="text" class="form-control-plaintext" name="title" value="<?php echo ucwords($field->type) ?>">
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label class="form-label">Options</label>
                                        <ul>
                                            <?php foreach ($field->options as $option): ?>
                                                <li><?php echo $option->label ?></li>
                                            <?php endforeach ?>
                                        </ul>
                                        <div class="input-group mb-3">
                                            <input type="text" name="option_label" class="form-control" placeholder="Add New Option" aria-label="Add New Option" aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary"><i class="fe fe-plus mr-2"></i>Add Option</button>
                                            </div>
                                        </div>
                                        <?php echo form_error('option_label') ?>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col mt-5">
                                    <a href="<?php echo base_url("admin/settings/field/all") ?>" class="btn btn-secondary">Back</a>
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