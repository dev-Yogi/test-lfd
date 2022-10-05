<?php $this->load->view('admin/header') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="page-header">
                        <h1 class="page-title">
                            Students <span class="text-muted small"><span class="p-2">&middot;</span> Import</span>
                        </h1>
                    </div>

                    <form method="post" enctype="multipart/form-data">
                        <?php echo alerts() ?>
                        <div class="card p-5 mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Program</label>
                                        <p><?php echo $this->program->name ?></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">CSV File</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input <?php echo is_valid('csv') ?>" name="csv">
                                            <label class="custom-file-label">Choose file</label>
                                            <?php echo form_error('csv') ?>
                                        </div>
                                        <small class="form-text text-muted">Comma separated values only.</small>
                                        <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#importExample">Import File Instructions</button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Instructor</label>
                                        <select id="instructor_id" class="selectize form-control <?php echo is_valid('instructor_id') ?>" name="instructor_id">
                                            <option value="">Select a member</option>
                                            <?php foreach ($instructors as $instructor): ?>
                                                <option 
                                                value="<?php echo $instructor->id ?>" 
                                                <?php echo set_select('instructor_id', $instructor->id, ($student->instructor_id ?? null) == $instructor->id) ?> >
                                                <?php echo $instructor->last_name ?>, <?php echo $instructor->first_name ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                    <?php echo form_error('instructor_id') ?>
                                    <small class="form-text text-muted">Optional - Select which instructor to apply to all imported students.</small>
                                </div>
                            </div>
                            <?php foreach ($extra_fields as $field): ?>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label"><?php echo $field->label ?></label>
                                        <?php if ($field->type == 'select'): ?>
                                            <select class="selectize form-control <?php echo is_valid($field->id) ?>" name="<?php echo $field->id ?>">
                                                <option>Choose an option</option>
                                                <?php foreach ($field->options as $option): ?>
                                                    <option value="<?php echo $option->id ?>" <?php echo set_select($field->id, $option->id, ($student->extra_fields[$field->id]->value ?? null) == $option->id) ?> ><?php echo $option->label ?></option>
                                                <?php endforeach ?>
                                            </select>
                                            <?php else: ?>
                                                <input type="text" class="form-control <?php echo is_valid($field->id) ?>" name="<?php echo $field->id ?>" value="<?php echo set_value($field->id, $student->extra_fields[$field->id]->value ?? null) ?>">
                                            <?php endif ?>
                                            <?php echo form_error($field->id) ?>
                                        </div>
                                    </div>
                                <?php endforeach ?>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="notify_student" value="1" checked>
                                            <span class="custom-control-label">Send email to student emails</span>
                                        </label>
                                        <small class="form-text text-muted">This will send each student an email with their login details.</small>
                                    </div>
                                    <div class="form-group">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="notify_instructor" value="1">
                                            <span class="custom-control-label">Send email to instructor</span>
                                        </label>
                                        <small class="form-text text-muted">This will send the instructor an email with student login details.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col mt-5 d-flex">
                                    <button class="btn btn-primary">Import</button>
                                    <a href="<?php echo base_url("admin/student/") ?>" class="btn btn-secondary ml-2">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="importExample" tabindex="-1" role="dialog" aria-labelledby="importExampleLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="importExampleLabel">Import Instructions</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </button>
    </div>
    <div class="modal-body">
        <img src="<?php echo base_url('img/admin/student_import_example.png') ?>">
        <p class="mt-4">Details:</p>
        <ul>
            <li>Columns must be Last Name, First Name, Email in order</li>
            <li>Must be saved as a comma-separated file (CSV)</li>
            <li>Rows holding emails that already exist in the portal will not be imported</li>
        </ul>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</div>
</div>
</div>
<?php $this->load->view('admin/footer') ?>