<?php $this->load->view('admin/header') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="page-header">
                        <h1 class="page-title">
                            Students 
                            <span class="text-muted small">
                                <span class="p-2">&middot;</span> 
                                <?php echo empty($student) ? "New" : "Edit" ?>
                            </span>
                        </h1>

                        <?php if (empty($student)): ?>
                            <a href="<?php echo base_url('admin/student/import') ?>" class="btn btn-secondary ml-auto">Import Students</a>
                        <?php endif ?>
                    </div>

                    <form method="post" enctype="multipart/form-data">
                        <?php echo alerts() ?>
                        <div class="card p-5 mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Program 
                                            <?php if (!empty($student)): ?>
                                                <a href="<?php echo base_url("admin/student/programs/{$student->id}") ?>" class="float-right btn btn-secondary btn-sm <?php echo is_staff() ? null : 'disabled' ?>">Manage Student's Programs</a>
                                            <?php endif ?>
                                        </label>
                                        <div class="form-control-plaintext"><?php echo $this->program->name ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">First Name</label>
                                        <input type="text" class="form-control <?php echo is_valid('first_name') ?>" name="first_name" value="<?php echo set_value('first_name', $student->first_name ?? "" ) ?>">
                                        <?php echo form_error('first_name') ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" class="form-control <?php echo is_valid('last_name') ?>" name="last_name" value="<?php echo set_value('last_name', $student->last_name ?? "" ) ?>">
                                        <?php echo form_error('last_name') ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input type="text" class="form-control <?php echo is_valid('email') ?>" name="email" value="<?php echo set_value('email', $student->email ?? "" ) ?>">
                                        <?php echo form_error('email') ?>
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

                                <?php if(empty($student)): ?>
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
                                <?php endif ?>

                            </div>

                            <div class="row">
                                <div class="col mt-5 d-flex">
                                    <button class="btn btn-primary"><?php echo empty($student) ? "Create" : "Update" ?></button>
                                    <a href="<?php echo base_url("admin/student/") ?>" class="btn btn-secondary ml-2">Cancel</a>
                                    <?php if (!empty($student) && is_staff()): ?>
                                        <a href="/member/admin/member/reset_password/<?php echo $student->id ?>" target="_blank" class="btn btn-secondary ml-auto"><i class="fe fe-lock mr-2"></i>Reset Password<i class="fe fe-external-link ml-2"></i></a>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($student)): ?>
                            <!-- <a href="<?php echo base_url("admin/student/remove/{$student->id}") ?>" onclick="return confirm('Are you sure you want to remove this student?')" class="btn btn-link float-right mr-3"><i class="fe fe-trash"></i> Remove Student</a> -->
                        <?php endif ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer') ?>