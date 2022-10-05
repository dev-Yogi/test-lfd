

<?php $this->load->view('header') ?>
<div class="container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Contact Us</h1>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <?php alerts() ?>
            <form method="post">
                <div class="row">

                    <div class="form-group col-md-6">
                        <label for="first_name">First Name<span class="form-required">*</span></label>
                        <input type="text" name="first_name" id="first_name" value="<?php echo set_value('first_name') ?>" class="form-control <?php echo is_valid('first_name') ?>">
                        <?php echo form_error('first_name') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name" id="last_name" value="<?php echo set_value('last_name') ?>" class="form-control <?php echo is_valid('last_name') ?>">
                        <?php echo form_error('last_name') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="email">Email<span class="form-required">*</span></label>
                        <input type="text" name="email" id="email" value="<?php echo set_value('email') ?>" class="form-control <?php echo is_valid('email') ?>">
                        <?php echo form_error('email') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="type">Are you an Employer or a Job Seeker?<span class="form-required">*</span></label>
                        <select class="form-control <?php echo is_valid('type') ?>" name="type">
                            <option value="jobseeker" <?php echo set_select('type', 'jobseeker') ?>>Job Seeker</option>
                            <option value="employer" <?php echo set_select('type', 'employer') ?>>Employer</option>
                            <option value="other" <?php echo set_select('type', 'other') ?>>Other</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="message">Message<span class="form-required">*</span></label>
                        <textarea class="form-control <?php echo is_valid('message') ?>" rows="8" name="message"><?php echo set_value('message') ?></textarea>
                        <?php echo form_error('message') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <input type="submit" value="Send" class="btn btn-primary">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view('footer') ?>
