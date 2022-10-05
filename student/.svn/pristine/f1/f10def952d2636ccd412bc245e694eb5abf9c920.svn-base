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
                                        <label class="form-label">Program</label>
                                        <input type="text" class="form-control-plaintext" name="title" value="<?php echo $this->program->name ?>">
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label class="form-label">Attach Existing Field</label>
                                        <select class="form-control form-control-sm w-50" name="field_id">
                                            <option disabled selected>Select a Field</option>
                                            <?php foreach ($fields as $field): ?>
                                                <option value="<?php echo $field->id ?>"><?php echo $field->label ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    <?php echo form_error('field_id') ?>
                                    </div>
                                    <div class="mb-4">- or -</div>
                                    <div class="form-group">
                                        <a href="<?php echo base_url('admin/settings/field_add') ?>" class="btn btn-secondary"><i class="fe fe-plus mr-2"></i> Create a New Field</a>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col mt-5">
                                    <button class="btn btn-primary"><?php echo empty($assignment) ? "Create" : "Update" ?></button>
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