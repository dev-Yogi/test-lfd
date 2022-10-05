<?php $this->load->view('admin/header') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="page-header">
                        <h1 class="page-title">
                            Lessons <span class="text-muted small"><span class="p-2">&middot;</span> <?php echo empty($lesson) || ($create_from_copy ?? null) ? "New" : "Edit" ?></span>
                        </h1>
                    </div>
                    <form method="post">
                        <?php echo alerts() ?>
                        <div class="card p-5 mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Lesson Title</label>
                                        <input type="text" class="form-control <?php echo is_valid('title') ?>" name="title" value="<?php echo set_value('title', $lesson->title ?? "" ) ?>">
                                        <?php echo form_error('title') ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Lesson Content</label>

                                        <textarea cols="80" id="lesson_ckeditor" name="content" rows="10" data-sample-short class="form-control <?php echo is_valid('content') ?>"><?php echo set_value('content', $lesson->content ?? null) ?></textarea>
                                        <?php echo form_error('content') ?>


                                        
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Lesson Order</label>
                                        <input type="text" class="form-control <?php echo is_valid('order') ?>" name="order" value="<?php echo set_value('order', $lesson->order ?? count($course->lessons) + 1 ) ?>">
                                        <?php echo form_error('order') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Lesson Length</label>
                                        <select id="length" class="form-control <?php echo is_valid('length') ?>" name="length">
                                            <option value="">Select length</option>
                                            <option value="5" <?php echo set_select('length', 5, ($lesson->length ?? 0) == 5) ?>>5 mins</option>
                                            <option value="10" <?php echo set_select('length', 10, ($lesson->length ?? 0) == 10) ?>>10 mins</option>
                                            <option value="15" <?php echo set_select('length', 15, ($lesson->length ?? 0) == 15) ?>>15 mins</option>
                                            <option value="30" <?php echo set_select('length', 30, ($lesson->length ?? 0) == 30) ?>>30 mins</option>
                                            <option value="45" <?php echo set_select('length', 45, ($lesson->length ?? 0) == 45) ?>>45 mins</option>
                                            <option value="60" <?php echo set_select('length', 60, ($lesson->length ?? 0) == 60) ?>>1 hr</option>
                                            <option value="120" <?php echo set_select('length', 120, ($lesson->length ?? 0) == 120) ?>>2 hrs</option>
                                            <option value="180" <?php echo set_select('length', 180, ($lesson->length ?? 0) == 180) ?>>3 hrs</option>
                                            <option value="240" <?php echo set_select('length', 240, ($lesson->length ?? 0) == 240) ?>>4 hrs</option>
                                        </select>
                                        <?php echo form_error('length') ?>
                                    </div>
                                </div>
                            </div>
                            <?php if (empty($lesson) || ($create_from_copy ?? null)): ?>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="form-label">Attach an assignment?</label>
                                            <label class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="has_assignment" value="1">
                                                <span class="custom-control-label">Yes, put an assignment on this lesson</span>
                                            </label>
                                            <small class="form-text text-muted">This will put a textbox-type assignment called "Assignment" on the lesson.</small>
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>
                            <div class="row">
                                <div class="col mt-5">
                                    <?php if (($create_from_copy ?? null)): ?>
                                        <div class="form-text text-muted mb-3">All videos and assignments will also be copied.</div>
                                    <?php endif ?>
                                    <button class="btn btn-primary"><?php echo empty($lesson) || ($create_from_copy ?? null) ? "Create" : "Update" ?></button>
                                    <a href="<?php echo base_url("admin/course/view/{$course->id}") ?>" class="btn btn-secondary ml-2">Cancel</a>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($lesson) && !($create_from_copy ?? null)): ?>
                            <a href="<?php echo base_url("admin/lesson/remove/{$lesson->id}") ?>" onclick="return confirm('Are you sure you want to remove this lesson?')" class="btn btn-link float-right mr-3"><i class="fe fe-trash"></i> Remove Lesson</a>
                        <?php endif ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer') ?>