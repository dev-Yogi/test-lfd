<?php $this->load->view('admin/header') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="page-header">
                        <h1 class="page-title">
                            Assignment <span class="text-muted small"><span class="p-2">&middot;</span> <?php echo empty($lesson) ? "New" : "Edit" ?></span>
                        </h1>
                    </div>
                    <form method="post">
                        <?php echo alerts() ?>
                        <div class="card p-5 mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Lesson</label>
                                        <input type="text" class="form-control-plaintext" name="title" value="<?php echo $lesson->title ?>">
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label class="form-label">Assignment Name</label>
                                        <input type="text" class="form-control <?php echo is_valid('label') ?>" name="label" value="<?php echo set_value('label', ($assignment->label ?? null)) ?>">
                                        <?php echo form_error('label') ?>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Assignment Instructions <small class="text-muted">(Optional)</small></label>
                                        <input type="text" class="form-control <?php echo is_valid('description') ?>" name="description" value="<?php echo set_value('description', ($assignment->description ?? null)) ?>">
                                        <?php echo form_error('description') ?>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <label class="form-label">Submission Type</label>
                                <?php if (empty($assignment)): ?>
                                    <select class="form-control <?php echo is_valid('type') ?> w-50" name="type">
                                        <option value="">Select type</option>
                                        <option value="text" <?php echo set_select('type', 'text', ($assignment->type ?? null) == 'text') ?>>Text</option>
                                        <option value="textarea" <?php echo set_select('type', 'textarea', ($assignment->type ?? null) == 'textarea') ?>>Textarea</option>
                                        <option value="upload" <?php echo set_select('type', 'upload', ($assignment->type ?? null) == 'upload') ?>>Document or Image Upload</option>
                                    </select>
                                    <small class="form-text text-muted">
                                        <ul class="pl-5">
                                            <li>Text: Suitable for one-line answers</li>
                                            <li>Textarea: Suitable for long text answers</li>
                                            <li>Document or Image Upload: Click <a href="" data-toggle="modal" data-target="#submission-info-modal">here</a> for details</li>
                                        </ul>
                                    </small>
                                    <?php echo form_error('type') ?>
                                <?php else: ?>
                                    <small class="form-text text-muted">Assignment type cannot be changed after creation.</small>
                                <?php endif ?>
                            </div>
                            <div class="row">
                                <div class="col mt-5">
                                    <button class="btn btn-primary"><?php echo empty($assignment) ? "Create" : "Update" ?></button>
                                    <a href="<?php echo base_url("admin/assignment/list/{$lesson->id}") ?>" class="btn btn-secondary ml-2">Cancel</a>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($assignment)): ?>
                            <a href="<?php echo base_url("admin/assignment/remove/{$assignment->id}") ?>" onclick="return confirm('Are you sure you want to remove this assignment?')" class="btn btn-link float-right mr-3"><i class="fe fe-trash"></i> Remove Assignment</a>
                        <?php endif ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="submission-info-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Document or Image Upload</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <p>This option allows the student to upload a file from their computer for their submission.</p>
                        <p>The file must be one of the allowed file formats below:</p>
                    </div>
                    <div class="col-lg-6">
                        <b>Document File Types</b>
                        <ul>
                            <li>txt</li>
                            <li>pdf</li>
                            <li>doc</li>
                            <li>docx</li>
                            <li>csv</li>
                            <li>html</li>
                            <li>css</li>
                            <li>odt</li>
                            <li>ods</li>
                            <li>xls</li>
                            <li>xlsx</li>
                            <li>ppt</li>
                            <li>pptx</li>
                        </ul>
                    </div>
                    <div class="col-lg-6">
                        <b>Image File Types</b>
                        <ul>
                            <li>jpg</li>
                            <li>jpeg</li>
                            <li>png</li>
                            <li>gif</li>
                            <li>tiff</li>
                            <li>psd</li>
                            <li>raw</li>
                            <li>bmp</li>
                            <li>svg</li>
                        </ul>
                    </div>
                    <div class="col-12">
                        <p>The maximum allowed file size is 50MB.</p>
                        <p>File uploads assignments are allowed once per lesson.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer') ?>