<?php $this->load->view('admin/header') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="page-header">
                        <h1 class="page-title">
                            Students <span class="text-muted small"><span class="p-2">&middot;</span> Programs</span>
                        </h1>
                    </div>
                    <form method="post">
                        <?php echo alerts() ?>
                        <div class="card p-5 mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Name</label>
                                        <div class="form-control-plaintext"><?php echo display_name($student) ?></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Programs</label>
                                        <div class="custom-controls-stacked">
                                            <?php foreach ($programs as $program): ?>
                                                <!-- Check if student has this program -->
                                                <?php $has_program = false ?>
                                                <?php foreach ($student->programs as $student_program): ?>
                                                    <?php if ($program->id == $student_program->id): ?>
                                                        <?php $has_program = true ?>
                                                    <?php endif ?>
                                                <?php endforeach ?>
                                                <label class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="programs[]" value="<?php echo $program->id ?>" <?php echo $has_program ? "checked" : null ?>>
                                                    <span class="custom-control-label"><?php echo $program->name ?></span>
                                                </label>
                                            <?php endforeach ?>
                                        </div>
                                        <small class="form-text text-muted"><i class="fe fe-alert-triangle"></i> Unchecking a checked checkbox will remove the student from the program. Their progress will not be lost, but they will no longer be able to see the program or its content.</small>

                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col mt-5 d-flex">
                                    <button class="btn btn-primary">Apply</button>
                                    <a href="<?php echo base_url("admin/student/view/{$student->id}") ?>" class="btn btn-secondary ml-2">Back to Student</a>
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