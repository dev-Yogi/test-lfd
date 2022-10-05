<?php $this->load->view('header-aim') ?>
<div class="page offering eoc">
    <div class="page-main">
        <div class="heading">
            <div class="container">
                <div class="row">
                    <div class="col text-center">
                        <h1>FASFA Assistance</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="offerings">
            <div class="container bg-white px-lg-5 pt-3">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3 py-5">
                        <p>Thank you for your interest in AIM's services.</p>
                        <p class="pb-5">Please fill out the information below and an AIM representative will contact you within 48 hours. </p>
                        <?php alerts() ?>
                        <form method="post" class="pb-5">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" class="form-control <?php echo is_valid('first_name') ?>" name="first_name" value="<?php echo set_value('first_name') ?>">
                                <?php echo form_error('first_name') ?>
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" class="form-control <?php echo is_valid('last_name') ?>" name="last_name" value="<?php echo set_value('last_name') ?>">
                                <?php echo form_error('last_name') ?>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" class="form-control <?php echo is_valid('email') ?>" name="email" value="<?php echo set_value('email') ?>">
                                <?php echo form_error('email') ?>
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" class="form-control <?php echo is_valid('phone') ?>" name="phone" value="<?php echo set_value('phone') ?>">
                                <?php echo form_error('phone') ?>
                            </div>
                            <div class="form-group">
                                <label>Has parent/guardian 1 completed a 4-year (Bachelor's) Degree from a college/university?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" <?php echo set_radio('parent_1_degree', '1') ?> name="parent_1_degree" id="parent-1-1" value="1">
                                    <label class="form-check-label" for="parent-1-1">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" <?php echo set_radio('parent_1_degree', '0') ?> name="parent_1_degree" id="parent-1-0" value="0">
                                    <label class="form-check-label" for="parent-1-0">No</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" <?php echo set_radio('parent_1_degree', '-1') ?> name="parent_1_degree" id="parent-1--1" value="-1">
                                    <label class="form-check-label" for="parent-1--1">N/A</label>
                                </div>
                                <?php echo form_error('parent_1_degree') ?>
                            </div>
                            <div class="form-group">
                                <label>Has parent/guardian 2 completed a 4-year (Bachelor's) Degree from a college/university?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" <?php echo set_radio('parent_2_degree', '1') ?> name="parent_2_degree" id="parent-2-1" value="1">
                                    <label class="form-check-label" for="parent-2-1">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" <?php echo set_radio('parent_2_degree', '0') ?> name="parent_2_degree" id="parent-2-0" value="0">
                                    <label class="form-check-label" for="parent-2-0">No</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" <?php echo set_radio('parent_2_degree', '-1') ?> name="parent_2_degree" id="parent-2--1" value="-1">
                                    <label class="form-check-label" for="parent-2--1">N/A</label>
                                </div>
                                <?php echo form_error('parent_2_degree') ?>
                            </div>
                            <div class="form-group">
                                <label>Is the student currently enrolled in a college prep or other federal program? <b>If yes</b>, please list the name of the course. Otherwise, leave blank.</label>
                                <input type="text" class="form-control <?php echo is_valid('currently_enrolled_course_name') ?>" name="currently_enrolled_course_name" value="<?php echo set_value('currently_enrolled_course_name') ?>">
                                <?php echo form_error('currently_enrolled_course_name') ?>
                            </div>
                            <input type="submit" value="Submit" class="btn btn-primary btn-sm">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('footer-aim') ?>