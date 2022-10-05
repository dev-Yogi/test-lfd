<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="page-header">
                        <h1 class="page-title">
                            EOC <span class="text-muted small"><span class="p-2">&middot;</span> View Submission</span>
                        </h1>
                    </div>
                    <form method="post">
                        <?php echo alerts() ?>
                        <div class="card p-5 mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Submitted</label>
                                        <input type="text" class="form-control-plaintext" name="first_name" value="<?php echo date('d M, Y h:ia', strtotime($submission->created)) ?>">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">First Name</label>
                                        <input type="text" class="form-control-plaintext" name="first_name" value="<?php echo $submission->first_name ?>">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" class="form-control-plaintext" name="last_name" value="<?php echo $submission->last_name ?>">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input type="text" class="form-control-plaintext" name="email" value="<?php echo $submission->email ?>">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Phone</label>
                                        <input type="text" class="form-control-plaintext" name="phone" value="<?php echo $submission->phone ?>">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Has parent/guardian 1 completed a 4-year (Bachelor's) Degree from a college/university?</label>
                                        <input type="text" class="form-control-plaintext" name="parent_1_degree" value="<?php echo $submission->parent_1_degree ?>">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Has parent/guardian 2 completed a 4-year (Bachelor's) Degree from a college/university?</label>
                                        <input type="text" class="form-control-plaintext" name="parent_2_degree" value="<?php echo $submission->parent_2_degree ?>">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Is the student currently enrolled in a college prep or other federal program? If yes, please list the name of the course. Otherwise, leave blank.</label>
                                        <input type="text" class="form-control-plaintext" name="currently_enrolled_course_name" value="<?php echo $submission->currently_enrolled_course_name ?>">
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col mt-5">
                                    <a href="<?php echo base_url("admin/eoc") ?>" class="btn btn-secondary">Back</a>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($category)): ?>
                            <a href="<?php echo base_url("admin/program_category/remove/{$category->id}") ?>" onclick="return confirm('Are you sure you want to remove this category?')" class="btn btn-link float-right mr-3"><i class="fe fe-trash"></i> Remove Category</a>
                        <?php endif ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer') ?>