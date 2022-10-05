<?php $this->load->view('admin/header') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="page-header">
                        <h1 class="page-title">
                            Courses <span class="text-muted small"><span class="p-2">&middot;</span> <?php echo empty($course) ? "New" : "Edit" ?></span>
                        </h1>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <?php echo alerts() ?>
                        <div class="card p-5 mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Program</label>
                                        <div class="form-control-plaintext"><?php echo $this->program->name ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Course Name</label>
                                        <input type="text" class="form-control <?php echo is_valid('name') ?>" name="name" value="<?php echo set_value('name', $course->name ?? "" ) ?>">
                                        <?php echo form_error('name') ?>
                                    </div>
                                    <?php if (!empty($course)): ?>
                                        <div class="form-group">
                                            <label class="form-label">Status</label>
                                            <?php if ($course->status == 'published'): ?>
                                                <span class="badge badge-primary">Published</span>
                                            <?php elseif ($course->status == 'draft'): ?>
                                                <span class="badge badge-default">Draft</span>
                                            <?php endif ?>
                                        </div>
                                    <?php endif ?>
                                    <div class="form-group">
                                        <label class="form-label">Course Image</label>
                                        <label for="formGroupExampleInput">Select a file to use as a cover image</label>

                                        <label for="formGroupExampleInput">You may save an image from <a href="https://unsplash.com/t/business-work" target="_blank">Unsplash <i class="fe fe-external-link"></i></a> by clicking on an image, clicking down into "Download free" and selecting the "Medium" size.</label>
                                        
                                        <br>
                                        <img src="<?php echo course_image_url($course->image ?? '') ?>">
                                        <small class="form-text text-muted">GIF/PNG/JPEG/JPG &middot; &lt;1MB</small>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input <?php echo is_valid('image') ?>" id="image" name="image">
                                            <label class="custom-file-label" for="image">Choose file</label>
                                            <?php echo form_error('image') ?>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Course Description</label>
                                        <input type="text" class="form-control <?php echo is_valid('description') ?>" name="description" value="<?php echo set_value('description', $course->description ?? "" ) ?>">
                                        <?php echo form_error('description') ?>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">What Students will Learn (One item per line)</label>
                                        <textarea type="text" rows="5" class="form-control <?php echo is_valid('whatyoulllearn') ?>" name="whatyoulllearn"><?php echo set_value('whatyoulllearn', $course->whatyoulllearn ?? "" ) ?></textarea>
                                        <?php echo form_error('whatyoulllearn') ?>
                                    </div>
                                    <?php $instructor_ids = array_column($course->instructors ?? [], 'id') ?>
                                    <div class="form-group">
                                            <label class="form-label">Instructor</label>
                                            <select id="instructor_ids" multiple class="selectize multiple form-control <?php echo is_valid('instructor_ids[]') ?>" name="instructor_ids[]">
                                                <option value="">Select a member</option>
                                                <?php foreach ($instructors as $instructor) : ?>
                                                    <option 
                                                    value="<?php echo $instructor->id ?>" 
                                                    <?php echo set_select('instructor_ids[]', $instructor->id, $instructor_ids ? in_array($instructor->id, $instructor_ids) : $this->member->id == $instructor->id) ?>>
                                                    <?php echo $instructor->last_name ?>, <?php echo $instructor->first_name ?>
                                                </option>
                                            <?php endforeach ?>
                                        </select>
                                        <?php echo form_error('instructor_ids[]') ?>
                                    </div>
                                    <?php $category_ids = array_column($course->categories ?? [], 'id') ?>
                                    <div class="form-group">
                                        <label class="form-label">Categories</label>
                                        <select id="category_ids" multiple class="selectize multiple form-control <?php echo is_valid('category_ids[]') ?>" name="category_ids[]">
                                            <option value="">Select a category</option>
                                            <?php foreach ($categories as $category) : ?>
                                                    <option 
                                                    value="<?php echo $category->id ?>" 
                                                    <?php echo set_select('category_ids[]', $category->id, in_array($category->id, $category_ids)) ?>>
                                                    <?php echo $category->name ?>
                                                </option>
                                            <?php endforeach ?>
                                        </select>
                                        <?php echo form_error('category_ids[]') ?>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Course Visibility</label>
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="is_assign_only" value="1" <?php echo set_checkbox('is_assign_only', '1', !empty($course->is_assign_only)) ?>>
                                            <span class="custom-control-label">Hide from catalog</span>
                                        </label>
                                        <small class="form-text text-muted">If this course is marked as hidden, students will not be able to see or start the course from the catalog. Instructors will instead need to assign students manually.</small>
                                        <?php echo form_error('is_assign_only') ?>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Lesson Completion Order</label>
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="force_order_lesson_completion" value="1" <?php echo set_checkbox('force_order_lesson_completion', '1', !empty($course->force_order_lesson_completion)) ?>>
                                            <span class="custom-control-label">Lessons must be completed in order</span>
                                        </label>
                                        <small class="form-text text-muted">Check the box above to only allow lessons to be viewed in the order they are shown in the course. Otherwise, students may select to start any lesson at any time.</small>
                                        <?php echo form_error('force_order_lesson_completion') ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col mt-5">
                                    <button class="btn btn-primary"><?php echo empty($course) ? "Create" : "Update" ?></button>
                                    <?php if (($course->status ?? null) == 'draft'): ?>
                                        <input type="submit" name="publish" value="Publish" class="btn btn-primary ml-2">
                                    <?php endif ?>
                                    <?php if (empty($course)): ?>
                                        <a href="<?php echo base_url("admin/course/") ?>" class="btn btn-secondary ml-2">Cancel</a>
                                    <?php else: ?>
                                        <a href="<?php echo base_url("admin/course/view/{$course->id}") ?>" class="btn btn-secondary ml-2">Cancel</a>
                                    <?php endif ?>
                                    <?php if (($course->status ?? null) == 'published'): ?>
                                        <a href="<?php echo base_url("admin/course/set_status/{$course->id}/draft") ?>" class="btn btn-link">Revert to Draft</a>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($course)): ?>
                            <a href="<?php echo base_url("admin/course/remove/{$course->id}") ?>" onclick="return confirm('Are you sure you want to remove this course?')" class="btn btn-link float-right mr-3"><i class="fe fe-trash"></i> Remove Course</a>
                        <?php endif ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer') ?>