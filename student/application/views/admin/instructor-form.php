<?php $this->load->view('admin/header') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="page-header">
                        <h1 class="page-title">
                            Instructors <span class="text-muted small"><span class="p-2">&middot;</span> <?php echo empty($category) ? "New" : "Edit" ?></span>
                        </h1>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <?php echo alerts() ?>
                        <div class="card p-5 mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">First Name</label>
                                        <input type="text" class="form-control <?php echo is_valid('first_name') ?>" name="first_name" value="<?php echo set_value('first_name', $instructor->first_name ?? "" ) ?>">
                                        <?php echo form_error('first_name') ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" class="form-control <?php echo is_valid('last_name') ?>" name="last_name" value="<?php echo set_value('last_name', $instructor->last_name ?? "" ) ?>">
                                        <?php echo form_error('last_name') ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input type="text" class="form-control <?php echo is_valid('email') ?>" name="email" value="<?php echo set_value('email', $instructor->email ?? "" ) ?>">
                                        <?php echo form_error('email') ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col mt-5 d-flex">
                                    <button class="btn btn-primary"><?php echo empty($instructor) ? "Create" : "Update" ?></button>
                                    <a href="<?php echo base_url("admin/instructor/") ?>" class="btn btn-secondary ml-2">Cancel</a>
                                    <?php if (!empty($instructor) && is_staff()): ?>
                                        <a href="/member/admin/member/reset_password/<?php echo $instructor->id ?>" target="_blank" class="btn btn-secondary ml-auto"><i class="fe fe-lock mr-2"></i>Reset Password<i class="fe fe-external-link ml-2"></i></a>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($instructor)): ?>
                            <a href="<?php echo base_url("admin/instructor/remove/{$instructor->id}") ?>" onclick="return confirm('Are you sure you want to remove this instructor?')" class="btn btn-link float-right mr-3"><i class="fe fe-trash"></i> Remove Instructor</a>
                        <?php endif ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer') ?>