<?php $this->load->view('admin/header') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="page-header">
                        <h1 class="page-title">
                            Courses <span class="text-muted small"><span class="p-2">&middot;</span> Assign</span>
                        </h1>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <?php echo alerts() ?>
                        <?php if ($course->status != 'published'): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">This course is not published, and cannot have students assigned to it.<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button></div>
                        <?php endif ?>
                        <div class="card p-5 mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Program</label>
                                        <div class="form-control-plaintext"><?php echo $this->program->name ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Course Name</label>
                                        <div class="form-control-plaintext"><?php echo $course->name ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Students</label>
                                        <select id="student_ids" class="selectize form-control <?php echo is_valid('student_ids') ?>" name="student_ids[]" multiple>
                                            <option value="">Select a member</option>
                                            <?php foreach ($students as $student) : ?>
                                                <option value="<?php echo $student->id ?>" <?php echo set_select('student_ids') ?> >
                                                    <?php echo display_name($student) ?>
                                                </option>
                                            <?php endforeach ?>
                                        </select>
                                        <?php echo form_error('student_ids') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mt-5">
                                    <button class="btn btn-primary" <?php echo $course->status != 'published' ? 'disabled' : null ?>>Assign</button>
                                    <a href="<?php echo base_url("admin/course/view/{$course->id}") ?>" class="btn btn-secondary ml-2">Cancel</a>
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